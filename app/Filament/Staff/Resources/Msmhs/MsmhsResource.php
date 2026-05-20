<?php

namespace App\Filament\Staff\Resources\Msmhs;

use App\Filament\Staff\Resources\Msmhs\Pages\ListMsmhs;
use App\Filament\Staff\Resources\Msmhs\Schemas\MsmhsForm;
use App\Filament\Staff\Resources\Msmhs\Tables\MsmhsTable;
use App\Models\Msmhs;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MsmhsResource extends Resource
{
    protected static ?string $model = Msmhs::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Data Mahasiswa';

    protected static ?string $modelLabel = 'Mahasiswa';

    protected static ?string $pluralModelLabel = 'Data Mahasiswa';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $recordTitleAttribute = 'nama_mahasiswa';

    public static function form(Schema $schema): Schema
    {
        return MsmhsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MsmhsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMsmhs::route('/'),
        ];
    }
}
