<?php

namespace App\Support;

use App\Models\Msmhs;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class MsmhsExcelImporter
{
    /**
     * @var array<string, string>
     */
    private const COLUMN_MAP = [
        'KDPTIMSMHS' => 'kode_perguruan_tinggi',
        'KDPSTMSMHS' => 'kode_program_studi',
        'KDJENMSMHS' => 'kode_jenjang_studi',
        'NIMHSMSMHS' => 'nim',
        'NMMHSMSMHS' => 'nama_mahasiswa',
        'TPLHRMSMHS' => 'tempat_lahir',
        'TGLHRMSMHS' => 'tanggal_lahir',
        'KDJEKMSMHS' => 'jenis_kelamin',
        'TAHUNMSMHS' => 'tahun_masuk',
        'SMAWLMSMHS' => 'semester_awal',
        'BTSTUMSMHS' => 'batas_studi',
        'ASSMAMSMHS' => 'kode_provinsi_asal',
        'TGMSKMSMHS' => 'tanggal_masuk',
        'TGLLSMSMHS' => 'tanggal_lulus',
        'STMHSMSMHS' => 'status_aktivitas_mahasiswa',
        'STPIDMSMHS' => 'status_awal_mahasiswa',
        'SKSDIMSMHS' => 'sks_diakui',
        'ASNIMMSMHS' => 'nim_asal',
        'ASPTIMSMHS' => 'kode_pt_asal',
        'ASJENMSMHS' => 'kode_jenjang_asal',
        'ASPSTMSMHS' => 'kode_prodi_asal',
        'BISTUMSMHS' => 'kode_biaya_studi',
        'PEKSBMSMHS' => 'kode_pekerjaan',
        'NMPEKMSMHS' => 'nama_tempat_bekerja',
        'PTPEKMSMHS' => 'kode_pt_tempat_bekerja',
        'PSPEKMSMHS' => 'kode_prodi_tempat_bekerja',
        'NMPRMMSMHS' => 'nidn_promotor',
        'NOKP1MSMHS' => 'nidn_ko_promotor_1',
        'NOKP2MSMHS' => 'nidn_ko_promotor_2',
        'NOKP3MSMHS' => 'nidn_ko_promotor_3',
        'NOKP4MSMHS' => 'nidn_ko_promotor_4',
    ];

    /**
     * @return array{created: int, updated: int, skipped: int}
     */
    public function import(string $path): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('PHP extension zip belum aktif.');
        }

        $rows = $this->readRows($path);
        $headers = array_map(fn ($value) => trim((string) $value), array_shift($rows) ?? []);

        if (! in_array('NIMHSMSMHS', $headers, true)) {
            throw new InvalidArgumentException('File Excel tidak memuat kolom NIMHSMSMHS.');
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        DB::transaction(function () use ($rows, $headers, &$created, &$updated, &$skipped): void {
            foreach ($rows as $row) {
                $source = array_combine($headers, array_pad($row, count($headers), null));

                if ($source === false) {
                    $skipped++;

                    continue;
                }

                $attributes = $this->mapRow($source);

                if (blank(Arr::get($attributes, 'nim')) || blank(Arr::get($attributes, 'nama_mahasiswa'))) {
                    $skipped++;

                    continue;
                }

                $record = Msmhs::query()->updateOrCreate(
                    ['nim' => $attributes['nim']],
                    $attributes,
                );

                $record->wasRecentlyCreated ? $created++ : $updated++;
            }
        });

        return compact('created', 'updated', 'skipped');
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    private function readRows(string $path): array
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new InvalidArgumentException('File Excel tidak dapat dibuka.');
        }

        try {
            $sharedStrings = $this->readSharedStrings($zip);
            $worksheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');

            if ($worksheetXml === false) {
                throw new InvalidArgumentException('Sheet Data tidak ditemukan pada file Excel.');
            }

            $worksheet = new SimpleXMLElement($worksheetXml);
            $rows = [];

            foreach ($worksheet->sheetData->row as $row) {
                $values = [];

                foreach ($row->c as $cell) {
                    $reference = (string) $cell['r'];
                    $columnIndex = $this->columnIndex($reference);
                    $type = (string) $cell['t'];

                    $values[$columnIndex] = match ($type) {
                        's' => $sharedStrings[(int) $cell->v] ?? null,
                        'inlineStr' => isset($cell->is->t) ? (string) $cell->is->t : null,
                        default => isset($cell->v) ? (string) $cell->v : null,
                    };
                }

                if ($values === []) {
                    continue;
                }

                ksort($values);
                $rows[] = array_values($values);
            }

            return $rows;
        } finally {
            $zip->close();
        }
    }

    /**
     * @return array<int, string>
     */
    private function readSharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');

        if ($xml === false) {
            return [];
        }

        $document = new \DOMDocument();
        $document->loadXML($xml);

        $strings = [];

        foreach ($document->getElementsByTagName('si') as $item) {
            $text = '';

            foreach ($item->getElementsByTagName('t') as $textNode) {
                $text .= $textNode->nodeValue;
            }

            $strings[] = $text;
        }

        return $strings;
    }

    private function columnIndex(string $cellReference): int
    {
        $letters = preg_replace('/\d+/', '', $cellReference) ?: '';
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = ($index * 26) + (ord(strtoupper($letter)) - 64);
        }

        return $index - 1;
    }

    /**
     * @param array<string, mixed> $source
     *
     * @return array<string, mixed>
     */
    private function mapRow(array $source): array
    {
        $attributes = [];

        foreach (self::COLUMN_MAP as $excelColumn => $attribute) {
            $attributes[$attribute] = $this->normalizeValue($attribute, $source[$excelColumn] ?? null);
        }

        return $attributes;
    }

    private function normalizeValue(string $attribute, mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        $value = is_string($value) ? trim($value) : $value;

        if ($value === '' || $value === null) {
            return null;
        }

        if (in_array($attribute, ['tanggal_lahir', 'tanggal_masuk', 'tanggal_lulus'], true)) {
            return $value === '0000-00-00' ? null : $value;
        }

        if ($attribute === 'tahun_masuk') {
            return is_numeric($value) ? (int) $value : null;
        }

        if ($attribute === 'sks_diakui') {
            return is_numeric($value) ? (int) $value : 0;
        }

        return (string) $value;
    }
}
