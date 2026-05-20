<?php

namespace App\Filament\Staff\Resources\Msmhs\Pages;

use App\Filament\Staff\Resources\Msmhs\MsmhsResource;
use App\Support\MsmhsExcelImporter;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ListMsmhs extends ListRecords
{
    protected static string $resource = MsmhsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importMsmhs')
                ->label('Import MSMHS Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('File MSMHS (.xlsx)')
                        ->disk('local')
                        ->directory('imports/msmhs')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/octet-stream',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    try {
                        $path = Storage::disk('local')->path($data['file']);
                        $result = app(MsmhsExcelImporter::class)->import($path);

                        Notification::make()
                            ->title('Import MSMHS selesai')
                            ->body("Baru: {$result['created']}, diperbarui: {$result['updated']}, dilewati: {$result['skipped']}.")
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        Notification::make()
                            ->title('Import MSMHS gagal')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
