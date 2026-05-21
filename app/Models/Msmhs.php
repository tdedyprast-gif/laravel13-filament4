<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Msmhs extends Model
{
    protected $table = 'msmhs';

    protected $fillable = [
        'kode_perguruan_tinggi',
        'kode_program_studi',
        'kode_jenjang_studi',
        'nim',
        'nama_mahasiswa',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'tahun_masuk',
        'semester_awal',
        'batas_studi',
        'kode_provinsi_asal',
        'tanggal_masuk',
        'tanggal_lulus',
        'status_aktivitas_mahasiswa',
        'status_awal_mahasiswa',
        'sks_diakui',
        'nim_asal',
        'kode_pt_asal',
        'kode_jenjang_asal',
        'kode_prodi_asal',
        'kode_biaya_studi',
        'kode_pekerjaan',
        'nama_tempat_bekerja',
        'kode_pt_tempat_bekerja',
        'kode_prodi_tempat_bekerja',
        'nidn_promotor',
        'nidn_ko_promotor_1',
        'nidn_ko_promotor_2',
        'nidn_ko_promotor_3',
        'nidn_ko_promotor_4',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_masuk' => 'date',
            'tanggal_lulus' => 'date',
            'tahun_masuk' => 'integer',
            'sks_diakui' => 'integer',
        ];
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'msmhs_id');
    }

    public function skpiSubmissions(): HasMany
    {
        return $this->hasMany(SkpiSubmission::class);
    }

    public function latestSkpiSubmission(): HasOne
    {
        return $this->hasOne(SkpiSubmission::class)->latestOfMany();
    }
}
