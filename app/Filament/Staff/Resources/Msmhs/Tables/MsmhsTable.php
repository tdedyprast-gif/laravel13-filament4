<?php

namespace App\Filament\Staff\Resources\Msmhs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MsmhsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_mahasiswa')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kode_program_studi')
                    ->label('Kode Prodi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->sortable(),
                TextColumn::make('tahun_masuk')
                    ->label('Angkatan')
                    ->sortable(),
                TextColumn::make('status_aktivitas_mahasiswa')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Akun Login')
                    ->searchable()
                    ->placeholder('Belum terhubung')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kode_program_studi')
                    ->label('Program Studi')
                    ->options(fn (): array => \App\Models\Msmhs::query()
                        ->whereNotNull('kode_program_studi')
                        ->distinct()
                        ->orderBy('kode_program_studi')
                        ->pluck('kode_program_studi', 'kode_program_studi')
                        ->all()),
                SelectFilter::make('tahun_masuk')
                    ->label('Angkatan')
                    ->options(fn (): array => \App\Models\Msmhs::query()
                        ->whereNotNull('tahun_masuk')
                        ->distinct()
                        ->orderByDesc('tahun_masuk')
                        ->pluck('tahun_masuk', 'tahun_masuk')
                        ->all()),
            ]);
    }
}
