<?php

namespace App\Filament\Staff\Resources\SkpiItems;

use App\Filament\Staff\Resources\SkpiItems\Pages\CreateSkpiItem;
use App\Filament\Staff\Resources\SkpiItems\Pages\EditSkpiItem;
use App\Filament\Staff\Resources\SkpiItems\Pages\ListSkpiItems;
use App\Filament\Staff\Resources\SkpiItems\Schemas\SkpiItemForm;
use App\Filament\Staff\Resources\SkpiItems\Tables\SkpiItemsTable;
use App\Models\SkpiItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SkpiItemResource extends Resource
{
    protected static ?string $model = SkpiItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $navigationLabel = 'Item SKPI';

    protected static ?string $modelLabel = 'Item SKPI';

    protected static ?string $pluralModelLabel = 'Item SKPI';

    protected static string|UnitEnum|null $navigationGroup = 'SKPI';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return SkpiItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SkpiItemsTable::configure($table);
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
            'index' => ListSkpiItems::route('/'),
            'create' => CreateSkpiItem::route('/create'),
            'edit' => EditSkpiItem::route('/{record}/edit'),
        ];
    }
}
