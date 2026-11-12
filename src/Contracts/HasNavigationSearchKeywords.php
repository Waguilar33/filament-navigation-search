<?php

declare(strict_types=1);

namespace Waguilar\FilamentNavigationSearch\Contracts;

interface HasNavigationSearchKeywords
{
    /**
     * @return list<string>
     */
    public static function getNavigationSearchKeywords(): array;
}
