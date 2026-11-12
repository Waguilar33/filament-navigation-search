<?php

declare(strict_types=1);

namespace Waguilar\FilamentNavigationSearch;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FilamentNavigationSearchServiceProvider extends PackageServiceProvider
{
    public const NAME = 'filament-navigation-search';

    public const PACKAGE = 'waguilar33/filament-navigation-search';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::NAME)
            ->hasViews(self::NAME)
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make(self::NAME, __DIR__.'/../resources/css/navigation-search.css'),
        ], self::PACKAGE);
    }
}
