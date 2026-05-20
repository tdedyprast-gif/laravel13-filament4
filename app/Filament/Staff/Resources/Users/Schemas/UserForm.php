<?php

namespace App\Filament\Staff\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        Select::make('msmhs_id')
                            ->label('Data Mahasiswa')
                            ->relationship('mahasiswa', 'nama_mahasiswa')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->nim} - {$record->nama_mahasiswa}")
                            ->searchable(['nim', 'nama_mahasiswa'])
                            ->preload(),
                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                        DateTimePicker::make('email_verified_at'),
                    ]),
                Section::make('Role & Aktivasi')
                    ->columns(2)
                    ->schema([
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),
                        Select::make('activation_status')
                            ->label('Status Aktivasi')
                            ->options([
                                'pending' => 'Menunggu Validasi',
                                'active' => 'Aktif',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('pending')
                            ->required(),
                        DateTimePicker::make('activation_requested_at')
                            ->label('Tanggal Pengajuan Aktivasi'),
                        DateTimePicker::make('activated_at')
                            ->label('Tanggal Aktivasi'),
                        Select::make('activated_by')
                            ->label('Divalidasi oleh')
                            ->relationship('activator', 'name')
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('activation_rejected_at')
                            ->label('Tanggal Penolakan'),
                        Textarea::make('activation_rejection_note')
                            ->label('Catatan Penolakan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
