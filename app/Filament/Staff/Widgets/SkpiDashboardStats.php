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
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;

class SkpiDashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        if ($user?->hasRole('mahasiswa')) {
            $ownSubmissions = SkpiSubmission::query()
                ->where('user_id', $user->id);

            $latestSubmission = (clone $ownSubmissions)
                ->latest()
                ->first();

            $status = $latestSubmission?->status ?? 'belum_ada';
            $statusLabel = match ($status) {
                'draft' => 'Draft',
                'submitted' => 'Sedang Diverifikasi',
                'verified' => 'Terverifikasi',
                'rejected' => 'Perlu Perbaikan',
                default => 'Belum Ada Pengajuan',
            };

            return [
                Stat::make(
                    'Status Pengajuan SKPI',
                    $this->statValueLink($statusLabel, SkpiSubmissionResource::getUrl('index'), 'Buka pengajuan SKPI Anda'),
                )
                    ->description('Pengajuan Anda')
                    ->descriptionIcon('heroicon-m-clipboard-document-check')
                    ->color(match ($status) {
                        'submitted' => 'info',
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->extraAttributes(['class' => 'skpi-action-stat']),
                Stat::make(
                    'Jumlah Pengajuan Anda',
                    $this->statValueLink((clone $ownSubmissions)->count(), SkpiSubmissionResource::getUrl('index'), 'Lihat semua pengajuan SKPI Anda'),
                )
                    ->description('Akun login')
                    ->descriptionIcon('heroicon-m-user')
                    ->color('success')
                    ->extraAttributes(['class' => 'skpi-action-stat']),
            ];
        }

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
            Stat::make(
                'Ajuan Aktivasi Akun',
                $this->statValueLink($pendingActivations, UserResource::getUrl('index', [
                    'tableFilters' => [
                        'activation_status' => [
                            'value' => 'pending',
                        ],
                    ],
                ]), 'Buka daftar validasi akun'),
            )
                ->description('Validasi akun')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color($pendingActivations > 0 ? 'warning' : 'success')
                ->extraAttributes(['class' => 'skpi-action-stat']),
            Stat::make(
                'Ajuan Verifikasi SKPI',
                $this->statValueLink($pendingSkpiSubmissions, SkpiSubmissionResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'value' => 'submitted',
                        ],
                    ],
                ]), 'Buka daftar verifikasi pengajuan SKPI'),
            )
                ->description('Verifikasi SKPI')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color($pendingSkpiSubmissions > 0 ? 'info' : 'success')
                ->extraAttributes(['class' => 'skpi-action-stat']),
            Stat::make(
                'Item SKPI Belum Valid',
                $this->statValueLink($pendingSkpiItems, SkpiItemResource::getUrl('index', [
                    'tableFilters' => [
                        'is_verified' => [
                            'value' => false,
                        ],
                    ],
                ]), 'Buka daftar item SKPI belum valid'),
            )
                ->description('Validasi item')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color($pendingSkpiItems > 0 ? 'danger' : 'success')
                ->extraAttributes(['class' => 'skpi-action-stat']),
        ];
    }

    protected function statValueLink(string | int $value, string $url, string $label): HtmlString
    {
        return new HtmlString(sprintf(
            '<a href="%s" class="skpi-stat-value-link" aria-label="%s">%s</a>',
            e($url),
            e($label),
            e((string) $value),
        ));
    }
}
