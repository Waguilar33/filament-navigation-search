# Filament Navigation Search

> **Beta.** The API may change before 1.0.

A search field at the top of a Filament panel's sidebar that finds pages in the menu. It is separate from Filament's global search: global search finds records, this finds pages.

- Matches the menu label, the group label and, for a resource, its singular model label, so "order" finds "Orders".
- Ignores case and accents: "ordenes" finds "Órdenes".
- Uses the labels Filament already resolved: translations, renamed labels and the model-label fallback work as they are. Hidden pages and pages the user cannot access never show.
- Results show in Filament's own dropdown under the field, as wide as the field, with the group as a badge.
- Keys: `/` opens the search, arrow keys move, Enter opens the page, Esc clears.
- In a collapsed (icon-only) sidebar it becomes a search icon that opens the field in a dropdown.
- Works with SPA mode and the mobile sidebar.

## Requirements

- PHP 8.2+
- Filament 5.10.2+

## Installation

```bash
composer require waguilar33/filament-navigation-search:"^1.0@beta"
php artisan filament:assets
```

Register the plugin on each panel that should have the field:

```php
use Waguilar\FilamentNavigationSearch\FilamentNavigationSearchPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(FilamentNavigationSearchPlugin::make());
}
```

Panels without the plugin are left untouched.

## Filtering the sidebar instead

By default matches show in a dropdown under the field and the sidebar stays as it is. To filter Filament's own sidebar while typing instead, turn on `filterNavigation()`:

```php
FilamentNavigationSearchPlugin::make()
    ->filterNavigation()
```

Items that do not match are hidden, groups left empty are hidden, and collapsed groups holding a match open while searching and close again when the search is cleared. Arrow keys move a highlight through the visible items and Enter opens it. In the collapsed (icon-only) sidebar the search icon still opens the dropdown results.

`filterNavigation()` accepts a closure, so it can depend on the user or the panel. This mode works on Filament's sidebar markup, so a Filament update can change how it behaves; the default dropdown mode does not depend on that markup.

## Adding keywords

A resource or page can add extra words people may search for by implementing `HasNavigationSearchKeywords`. Return translated strings, so each language finds its own words:

```php
use Waguilar\FilamentNavigationSearch\Contracts\HasNavigationSearchKeywords;

class InvoiceResource extends Resource implements HasNavigationSearchKeywords
{
    public static function getNavigationSearchKeywords(): array
    {
        return [__('invoices.keywords.bill'), __('invoices.keywords.receipt')];
    }
}
```

Pages from other plugins are found by their label, group and model label without any change. To add keywords to one of them, extend the page or resource in your app, implement the interface on your class, and register your class on the panel instead of the original.

## What it does not search

- Records (use Filament's global search for those)
- Cluster pages and child items (`parentItem`)
- Panels using `->topNavigation()`

## Translations

The field ships in English and Spanish. Publish the translations to change or add a language:

```bash
php artisan vendor:publish --tag=filament-navigation-search-translations
```

## Testing

```bash
composer test
composer lint
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## License

MIT. See [LICENSE.md](LICENSE.md).
