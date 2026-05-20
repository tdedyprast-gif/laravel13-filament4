<?php

namespace App\Filament\Staff\Resources\Msmhs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MsmhsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Mahasiswa')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nim')
                            ->label('NIM')
                            ->required(),
                        TextInput::make('nama_mahasiswa')
                            ->label('Nama Mahasiswa')
                            ->required(),
                        TextInput::make('kode_perguruan_tinggi')
                            ->label('Kode PT'),
                        TextInput::make('kode_program_studi')
                            ->label('Kode Prodi'),
                        TextInput::make('kode_jenjang_studi')
                            ->label('Jenjang'),
                        TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir'),
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir'),
                        TextInput::make('jenis_kelamin')
                            ->label('Jenis Kelamin'),
                        TextInput::make('tahun_masuk')
                            ->label('Tahun Masuk')
                            ->numeric(),
                        TextInput::make('status_aktivitas_mahasiswa')
                            ->label('Status Aktivitas'),
                    ]),
            ]);
    }
}
