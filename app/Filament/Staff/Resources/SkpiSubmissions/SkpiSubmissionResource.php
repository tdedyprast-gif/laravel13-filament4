<?php

namespace App\Filament\Staff\Resources\SkpiSubmissions;

use App\Filament\Staff\Resources\SkpiSubmissions\Pages\CreateSkpiSubmission;
use App\Filament\Staff\Resources\SkpiSubmissions\Pages\EditSkpiSubmission;
use App\Filament\Staff\Resources\SkpiSubmissions\Pages\ListSkpiSubmissions;
use App\Filament\Staff\Resources\SkpiSubmissions\Schemas\SkpiSubmissionForm;
use App\Filament\Staff\Resources\SkpiSubmissions\Tables\SkpiSubmissionsTable;
use App\Models\SkpiSubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class SkpiSubmissionResource extends Resource
{
    protected static ?string $model = SkpiSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Pengajuan SKPI';

    protected static ?string $modelLabel = 'Pengajuan SKPI';

    protected static ?string $pluralModelLabel = 'Pengajuan SKPI';

    protected static string|UnitEnum|null $navigationGroup = 'SKPI';

    protected static ?string $recordTitleAttribute = 'nomor_skpi';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user?->hasRole('mahasiswa')) {
            return $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return SkpiSubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SkpiSubmissionsTable::configure($table);
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
            'index' => ListSkpiSubmissions::route('/'),
            'create' => CreateSkpiSubmission::route('/create'),
            'edit' => EditSkpiSubmission::route('/{record}/edit'),
        ];
    }
}
