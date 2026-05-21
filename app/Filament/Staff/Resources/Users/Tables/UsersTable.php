<?php

namespace App\Filament\Staff\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->separator(', '),
                TextColumn::make('activation_status')
                    ->label('Aktivasi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Validasi',
                        'active' => 'Aktif',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('activation_status')
                    ->label('Status Aktivasi')
                    ->options([
                        'pending' => 'Menunggu Validasi',
                        'active' => 'Aktif',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('activateAccount')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => $record->activation_status !== 'active')
                    ->action(function ($record): void {
                        $record->forceFill([
                            'activation_status' => 'active',
                            'activated_at' => now(),
                            'activated_by' => Auth::id(),
                            'activation_rejected_at' => null,
                            'activation_rejection_note' => null,
                        ])->save();

                        Role::findOrCreate('mahasiswa', 'web');

                        if (method_exists($record, 'assignRole') && ! $record->hasRole('mahasiswa')) {
                            $record->assignRole('mahasiswa');
                        }
                    }),
                Action::make('rejectActivation')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->activation_status !== 'rejected')
                    ->form([
                        Textarea::make('activation_rejection_note')
                            ->label('Catatan Penolakan')
                            ->required(),
                    ])
                    ->action(fn ($record, array $data) => $record->forceFill([
                        'activation_status' => 'rejected',
                        'activation_rejected_at' => now(),
                        'activation_rejection_note' => $data['activation_rejection_note'],
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
