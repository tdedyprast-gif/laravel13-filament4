<?php

namespace App\Filament\Staff\Resources\SkpiItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SkpiItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('submission.mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),
                TextColumn::make('submission.mahasiswa.nama_mahasiswa')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'achievement' => 'Prestasi',
                        'certificate' => 'Sertifikat',
                        'organization' => 'Organisasi',
                        'internship' => 'Magang',
                        'language' => 'Bahasa',
                        'skill' => 'Keahlian',
                        'activity' => 'Kegiatan',
                        default => $state,
                    }),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('organizer')
                    ->label('Penyelenggara')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('level')
                    ->label('Tingkat')
                    ->toggleable(),
                IconColumn::make('is_verified')
                    ->label('Valid')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'achievement' => 'Prestasi',
                        'certificate' => 'Sertifikat',
                        'organization' => 'Organisasi',
                        'internship' => 'Magang',
                        'language' => 'Bahasa',
                        'skill' => 'Keahlian',
                        'activity' => 'Kegiatan',
                    ]),
                TernaryFilter::make('is_verified')
                    ->label('Status Verifikasi'),
            ])
            ->recordActions([
                Action::make('verify')
                    ->label('Validasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => ! $record->is_verified)
                    ->action(fn ($record) => $record->forceFill([
                        'is_verified' => true,
                        'verified_at' => now(),
                        'verified_by' => Auth::id(),
                    ])->save()),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
