<?php

namespace App\Filament\Staff\Resources\SkpiSubmissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SkpiSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_skpi')
                    ->label('Nomor SKPI')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),
                TextColumn::make('mahasiswa.nama_mahasiswa')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->limit(35),
                TextColumn::make('user.name')
                    ->label('Akun')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'submitted' => 'Diajukan',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'info',
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('verified_at')
                    ->label('Diverifikasi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Diajukan',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => $record->status !== 'verified')
                    ->action(fn ($record) => $record->forceFill([
                        'status' => 'verified',
                        'verified_at' => now(),
                        'verified_by' => Auth::id(),
                        'rejected_at' => null,
                        'rejected_by' => null,
                        'rejection_note' => null,
                    ])->save()),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->status !== 'rejected')
                    ->form([
                        Textarea::make('rejection_note')
                            ->label('Catatan Penolakan')
                            ->required(),
                    ])
                    ->action(fn ($record, array $data) => $record->forceFill([
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'rejected_by' => Auth::id(),
                        'rejection_note' => $data['rejection_note'],
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
