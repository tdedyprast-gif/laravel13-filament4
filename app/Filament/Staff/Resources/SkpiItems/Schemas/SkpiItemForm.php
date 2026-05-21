<?php

namespace App\Filament\Staff\Resources\SkpiItems\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                            ->relationship(
                                name: 'submission',
                                titleAttribute: 'nomor_skpi',
                                modifyQueryUsing: fn (Builder $query) => Auth::user()?->hasRole('mahasiswa')
                                    ? $query->where('user_id', Auth::id())
                                    : $query,
                            )
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
                            ->required()
                            ->maxLength(255),
                        TextInput::make('level')
                            ->label('Tingkat')
                            ->placeholder('Lokal, Regional, Nasional, Internasional')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('achievement')
                            ->label('Capaian')
                            ->placeholder('Juara 1, Peserta, Ketua, Anggota')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('certificate_number')
                            ->label('Nomor Sertifikat')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('started_on')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('ended_on')
                            ->label('Tanggal Selesai')
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->required()
                            ->default(0),
                        TextInput::make('certificate_file')
                            ->label('Link Bukti/Sertifikat')
                            ->placeholder('https://drive.google.com/...')
                            ->url()
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('English')
                            ->placeholder('English: Describe the achievement, activity, or skill in detail. ')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Section::make('Verifikasi')
                    ->visible(fn (): bool => ! Auth::user()?->hasRole('mahasiswa'))
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
