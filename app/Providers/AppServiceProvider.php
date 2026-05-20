<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): HtmlString => new HtmlString(<<<'HTML'
                <style>
                    .fi-simple-layout {
                        min-height: 100vh;
                        background-image:
                            linear-gradient(120deg, rgba(6, 78, 59, .86), rgba(15, 23, 42, .58)),
                            url('https://siakad.stkippacitan.ac.id/images_siakad/menu/bg-siakad-main.jpg');
                        background-size: cover;
                        background-position: center;
                        position: relative;
                        overflow: hidden;
                    }

                    .fi-simple-layout::before {
                        content: "";
                        position: fixed;
                        inset: 0;
                        pointer-events: none;
                        background-image:
                            linear-gradient(rgba(255, 255, 255, .07) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255, 255, 255, .07) 1px, transparent 1px),
                            radial-gradient(circle at 20% 20%, rgba(52, 211, 153, .22), transparent 28%),
                            radial-gradient(circle at 80% 70%, rgba(14, 165, 233, .18), transparent 26%);
                        background-size: 42px 42px, 42px 42px, 100% 100%, 100% 100%;
                    }

                    .fi-simple-layout::after {
                        content: "Sistem Informasi SKPI";
                        position: fixed;
                        left: clamp(32px, 8vw, 120px);
                        top: 42%;
                        max-width: 660px;
                        transform: translateY(-50%);
                        color: white;
                        font-size: clamp(44px, 6vw, 78px);
                        line-height: 1.04;
                        font-weight: 400;
                        letter-spacing: 0;
                        text-shadow: 0 24px 70px rgba(0, 0, 0, .45);
                        pointer-events: none;
                    }

                    .fi-simple-main {
                        margin-left: auto !important;
                        margin-right: clamp(24px, 7vw, 96px) !important;
                        width: min(100%, 440px) !important;
                        position: relative;
                        z-index: 2;
                    }

                    .fi-simple-header,
                    .fi-logo,
                    .fi-simple-header-heading,
                    .fi-simple-header-subheading {
                        display: none !important;
                    }

                .fi-simple-main > section,
                .fi-simple-main .fi-simple-section {
                    border-radius: 24px !important;
                    background: rgba(8, 12, 18, .72) !important;
                    backdrop-filter: blur(18px);
                    -webkit-backdrop-filter: blur(18px);
                    box-shadow: 0 28px 90px rgba(0, 0, 0, .34);
                    border: 1px solid rgba(255, 255, 255, .16);
                }

                    @media (max-width: 900px) {
                        .fi-simple-layout {
                            justify-content: center;
                            padding: 24px;
                        }

                        .fi-simple-layout::after {
                            display: none;
                        }

                        .fi-simple-main {
                            margin-left: auto !important;
                            margin-right: auto !important;
                        }
                    }
                </style>
            HTML),
        );
    }
}