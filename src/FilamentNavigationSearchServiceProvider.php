<?php

declare(strict_types=1);

namespace Waguilar\FilamentNavigationSearch;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FilamentNavigationSearchServiceProvider extends PackageServiceProvider
{
    public const NAME = 'filament-navigation-search';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::NAME)
            ->hasViews(self::NAME)
            ->hasTranslations();
    }
}
