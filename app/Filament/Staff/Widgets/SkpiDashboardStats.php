<?php

namespace App\Filament\Staff\Widgets;

use App\Filament\Staff\Resources\SkpiItems\SkpiItemResource;
use App\Filament\Staff\Resources\SkpiSubmissions\SkpiSubmissionResource;
use App\Filament\Staff\Resources\Users\UserResource;
use App\Models\SkpiItem;
use App\Models\SkpiSubmission;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SkpiDashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $pendingActivations = User::query()
            ->where('activation_status', 'pending')
            ->count();

        $pendingSkpiSubmissions = SkpiSubmission::query()
            ->where('status', 'submitted')
            ->count();

        $pendingSkpiItems = SkpiItem::query()
            ->where('is_verified', false)
            ->count();

        return [
            Stat::make('Ajuan Aktivasi Akun', $pendingActivations)
                ->description('Klik untuk membuka form validasi akun')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color($pendingActivations > 0 ? 'warning' : 'success')
                ->url(UserResource::getUrl('index', [
                    'tableFilters' => [
                        'activation_status' => [
                            'value' => 'pending',
                        ],
                    ],
                ])),
            Stat::make('Ajuan Verifikasi SKPI', $pendingSkpiSubmissions)
                ->description('Klik untuk membuka pengajuan SKPI')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color($pendingSkpiSubmissions > 0 ? 'info' : 'success')
                ->url(SkpiSubmissionResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'value' => 'submitted',
                        ],
                    ],
                ])),
            Stat::make('Item SKPI Belum Valid', $pendingSkpiItems)
                ->description('Klik untuk membuka item validasi')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color($pendingSkpiItems > 0 ? 'danger' : 'success')
                ->url(SkpiItemResource::getUrl('index', [
                    'tableFilters' => [
                        'is_verified' => [
                            'value' => false,
                        ],
                    ],
                ])),
        ];
    }
}
