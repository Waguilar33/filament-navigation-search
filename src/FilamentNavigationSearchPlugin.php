<?php

declare(strict_types=1);

namespace Waguilar\FilamentNavigationSearch;

use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Waguilar\FilamentNavigationSearch\Actions\BuildNavigationSearchIndexAction;

final class FilamentNavigationSearchPlugin implements Plugin
{
    public static function make(): self
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'navigation-search';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): View => view('filament-navigation-search::field', [
                'entries' => app(BuildNavigationSearchIndexAction::class)->handle(Filament::getNavigation()),
            ]),
        );
    }

    public function boot(Panel $panel): void {}
}
