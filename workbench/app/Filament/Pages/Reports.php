<?php

declare(strict_types=1);

namespace Workbench\App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Waguilar\FilamentNavigationSearch\Contracts\HasNavigationSearchKeywords;

final class Reports extends Page implements HasNavigationSearchKeywords
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    public static function getNavigationLabel(): string
    {
        return __('demo::demo.reports');
    }

    public static function getNavigationGroup(): string
    {
        return __('demo::demo.settings');
    }

    public static function getNavigationSearchKeywords(): array
    {
        return [__('demo::demo.analytics')];
    }
}
