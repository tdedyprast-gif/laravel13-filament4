<?php

namespace App\Filament\Staff\Resources\SkpiItems\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkpiItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Item')
                    ->columns(2)
                    ->schema([
                        Select::make('skpi_submission_id')
                            ->label('Pengajuan SKPI')
                            ->relationship('submission', 'nomor_skpi')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => trim(($record->nomor_skpi ?: 'Draft').' - '.$record->mahasiswa?->nim.' - '.$record->mahasiswa?->nama_mahasiswa))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'achievement' => 'Prestasi',
                                'certificate' => 'Sertifikat',
                                'organization' => 'Organisasi',
                                'internship' => 'Magang',
                                'language' => 'Bahasa',
                                'skill' => 'Keahlian',
                                'activity' => 'Kegiatan',
                            ])
                            ->required(),
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('organizer')
                            ->label('Penyelenggara')
                            ->maxLength(255),
                        TextInput::make('level')
                            ->label('Tingkat')
                            ->placeholder('Lokal, Regional, Nasional, Internasional')
                            ->maxLength(255),
                        TextInput::make('achievement')
                            ->label('Capaian')
                            ->placeholder('Juara 1, Peserta, Ketua, Anggota')
                            ->maxLength(255),
                        TextInput::make('certificate_number')
                            ->label('Nomor Sertifikat')
                            ->maxLength(255),
                        DatePicker::make('started_on')
                            ->label('Tanggal Mulai'),
                        DatePicker::make('ended_on')
                            ->label('Tanggal Selesai'),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        FileUpload::make('certificate_file')
                            ->label('File Bukti/Sertifikat')
                            ->directory('skpi/certificates')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ]),
                Section::make('Verifikasi')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_verified')
                            ->label('Terverifikasi')
                            ->default(false),
                        Select::make('verified_by')
                            ->label('Diverifikasi oleh')
                            ->relationship('verifier', 'name')
                            ->searchable()
                            ->preload(),
                        Textarea::make('verification_note')
                            ->label('Catatan Verifikasi')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
