<?php

declare(strict_types=1);

namespace Waguilar\FilamentNavigationSearch\Data;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;

final readonly class NavigationSearchEntry
{
    /**
     * @param  list<string>  $terms
     */
    public function __construct(
        public string $label,
        public ?string $group,
        public string $url,
        public string|BackedEnum|Htmlable|null $icon,
        public bool $opensInNewTab,
        public array $terms,
    ) {}
}
