<?php

declare(strict_types=1);

namespace Waguilar\FilamentNavigationSearch\Actions;

use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
use Waguilar\FilamentNavigationSearch\Contracts\HasNavigationSearchKeywords;
use Waguilar\FilamentNavigationSearch\Data\NavigationSearchEntry;

final class BuildNavigationSearchIndexAction
{
    /**
     * @param  array<NavigationGroup>  $groups
     * @return list<NavigationSearchEntry>
     */
    public function handle(array $groups): array
    {
        $entries = [];

        foreach ($groups as $group) {
            $groupLabel = $this->text($group->getLabel());

            /** @var array<NavigationItem> $items */
            $items = collect($group->getItems())->all();

            foreach ($items as $item) {
                $url = $item->getUrl();

                if (blank($url)) {
                    continue;
                }

                $label = $this->text($item->getLabel()) ?? '';

                $entries[] = new NavigationSearchEntry(
                    label: $label,
                    group: $groupLabel,
                    url: $url,
                    icon: $item->getIcon(),
                    opensInNewTab: $item->shouldOpenUrlInNewTab(),
                    terms: $this->terms($item, $label, $groupLabel),
                );
            }
        }

        return $entries;
    }

    /**
     * @return list<string>
     */
    private function terms(NavigationItem $item, string $label, ?string $groupLabel): array
    {
        $key = $item->getKey();
        $terms = [$label, $groupLabel];

        if (is_subclass_of($key, Resource::class)) {
            $terms[] = $key::getModelLabel();
        }

        if (is_subclass_of($key, HasNavigationSearchKeywords::class)) {
            array_push($terms, ...$key::getNavigationSearchKeywords());
        }

        return array_values(array_unique(array_filter($terms, filled(...))));
    }

    private function text(string|Htmlable|null $value): ?string
    {
        if ($value instanceof Htmlable) {
            return trim(strip_tags($value->toHtml()));
        }

        return $value;
    }
}
