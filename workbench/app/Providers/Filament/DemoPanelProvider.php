<?php

declare(strict_types=1);

namespace Workbench\App\Providers\Filament;

use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Waguilar\FilamentNavigationSearch\FilamentNavigationSearchPlugin;
use Workbench\App\Http\Middleware\SetDemoLocale;
use Workbench\App\Http\Middleware\SetDemoNavigationMode;

final class DemoPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('demo')
            ->path('demo')
            ->spa()
            ->sidebarCollapsibleOnDesktop()
            ->pages([Dashboard::class])
            ->discoverPages(in: __DIR__.'/../../Filament/Pages', for: 'Workbench\\App\\Filament\\Pages')
            ->discoverResources(in: __DIR__.'/../../Filament/Resources', for: 'Workbench\\App\\Filament\\Resources')
            ->plugin(FilamentNavigationSearchPlugin::make()->filterNavigation(fn (): bool => (bool) session('demo_filter_navigation', false)))
            ->navigationItems([
                NavigationItem::make(fn (): string => __('demo::demo.filament_docs'))
                    ->url('https://filamentphp.com/docs', shouldOpenInNewTab: true)
                    ->group(fn (): string => __('demo::demo.settings')),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                SetDemoLocale::class,
                SetDemoNavigationMode::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ], isPersistent: true);
    }
}
