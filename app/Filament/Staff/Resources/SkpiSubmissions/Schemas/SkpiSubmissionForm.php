<?php

namespace App\Filament\Staff\Resources\SkpiSubmissions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkpiSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pengajuan')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Akun Mahasiswa')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('msmhs_id')
                            ->label('Mahasiswa')
                            ->relationship('mahasiswa', 'nama_mahasiswa')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => "{$record->nim} - {$record->nama_mahasiswa}")
                            ->searchable(['nim', 'nama_mahasiswa'])
                            ->preload()
                            ->required(),
                        TextInput::make('nomor_skpi')
                            ->label('Nomor SKPI')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'submitted' => 'Diajukan',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak',
                            ])
                            ->default('draft')
                            ->required(),
                    ]),
                Section::make('Alur Verifikasi')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('submitted_at')
                            ->label('Tanggal Diajukan'),
                        DateTimePicker::make('verified_at')
                            ->label('Tanggal Verifikasi'),
                        Select::make('verified_by')
                            ->label('Diverifikasi oleh')
                            ->relationship('verifier', 'name')
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('rejected_at')
                            ->label('Tanggal Ditolak'),
                        Select::make('rejected_by')
                            ->label('Ditolak oleh')
                            ->relationship('rejector', 'name')
                            ->searchable()
                            ->preload(),
                        Textarea::make('rejection_note')
                            ->label('Catatan Penolakan')
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->label('Catatan Internal')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
