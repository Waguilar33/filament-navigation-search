<?php

declare(strict_types=1);

namespace Waguilar\FilamentNavigationSearch;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Waguilar\FilamentNavigationSearch\Actions\BuildNavigationSearchIndexAction;

final class FilamentNavigationSearchPlugin implements Plugin
{
    use EvaluatesClosures;

    private bool|Closure $filtersNavigation = false;

    private bool|Closure $isSticky = false;

    public static function make(): self
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'navigation-search';
    }

    public function filterNavigation(bool|Closure $condition = true): self
    {
        $this->filtersNavigation = $condition;

        return $this;
    }

    public function filtersNavigation(): bool
    {
        return (bool) $this->evaluate($this->filtersNavigation);
    }

    public function sticky(bool|Closure $condition = true): self
    {
        $this->isSticky = $condition;

        return $this;
    }

    public function isSticky(): bool
    {
        return (bool) $this->evaluate($this->isSticky);
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): View => view('filament-navigation-search::field', [
                'entries' => app(BuildNavigationSearchIndexAction::class)->handle(Filament::getNavigation()),
                'filterNavigation' => $this->filtersNavigation(),
                'sticky' => $this->isSticky(),
            ]),
        );
    }

    public function boot(Panel $panel): void {}
}
