<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\HtmlString;
use Waguilar\FilamentNavigationSearch\Actions\BuildNavigationSearchIndexAction;
use Waguilar\FilamentNavigationSearch\Data\NavigationSearchEntry;

/**
 * @return array<string, NavigationSearchEntry>
 */
function navigationIndex(string $locale = 'en'): array
{
    app()->setLocale($locale);
    Filament::setCurrentPanel('demo');
    Filament::bootCurrentPanel();

    return collect(app(BuildNavigationSearchIndexAction::class)->handle(Filament::getNavigation()))
        ->keyBy(fn (NavigationSearchEntry $entry): string => $entry->label)
        ->all();
}

it('uses the label Filament resolved', function (): void {
    expect(navigationIndex())->toHaveKeys(['Orders', 'Customers', 'Reports']);
});

it('uses the translated label Filament resolved', function (): void {
    expect(navigationIndex('es'))->toHaveKeys(['Órdenes', 'Clientes', 'Informes']);
});

it('adds the singular model label of a resource as a term', function (): void {
    $index = navigationIndex('es');

    expect($index['Órdenes']->terms)->toContain('Orden')
        ->and($index['Clientes']->terms)->toContain('Cliente');
});

it('adds the group label as a term', function (): void {
    $entry = navigationIndex('es')['Órdenes'];

    expect($entry->group)->toBe('Ventas')
        ->and($entry->terms)->toContain('Ventas');
});

it('adds the keywords a page declares as terms', function (): void {
    expect(navigationIndex('es')['Informes']->terms)->toContain('Analítica');
});

it('leaves out hidden items and items the user cannot access', function (): void {
    expect(navigationIndex())->not->toHaveKeys(['Archive', 'Payroll']);
});

it('indexes an item added by hand on its label and group only', function (): void {
    $entry = navigationIndex()['Filament docs'];

    expect($entry->terms)->toBe(['Filament docs', 'Settings'])
        ->and($entry->url)->toBe('https://filamentphp.com/docs')
        ->and($entry->opensInNewTab)->toBeTrue();
});

it('leaves out items without a url', function (): void {
    $entries = app(BuildNavigationSearchIndexAction::class)->handle([
        NavigationGroup::make('Group')->items([
            NavigationItem::make('Nowhere'),
            NavigationItem::make('Somewhere')->url('/somewhere'),
        ]),
    ]);

    expect($entries)->toHaveCount(1)
        ->and($entries[0]->label)->toBe('Somewhere');
});

it('reads the text of a label given as html', function (): void {
    $entries = app(BuildNavigationSearchIndexAction::class)->handle([
        NavigationGroup::make()->items([
            NavigationItem::make(new HtmlString('<b>Bold</b>'))->url('/bold'),
        ]),
    ]);

    expect($entries[0]->label)->toBe('Bold')
        ->and($entries[0]->group)->toBeNull()
        ->and($entries[0]->terms)->toBe(['Bold']);
});
