<?php

declare(strict_types=1);

it('shows the search field with a result for every visible menu page', function (): void {
    $this->get('/demo')
        ->assertOk()
        ->assertSee('data-navigation-search-input="expanded"', false)
        ->assertSee('data-navigation-search-input="collapsed"', false)
        ->assertSee('Search menu')
        ->assertSeeInOrder(['data-navigation-search-result="0"', 'Dashboard'], false)
        ->assertSee('data-navigation-search-result="4"', false)
        ->assertDontSee('data-navigation-search-result="5"', false)
        ->assertSee('No pages found');
});

it('translates the search field', function (): void {
    $this->get('/demo?locale=es')
        ->assertOk()
        ->assertSee('Buscar en el menú')
        ->assertSee('No se encontraron páginas');
});

it('leaves panels without the plugin untouched', function (): void {
    $this->get('/plain')
        ->assertOk()
        ->assertDontSee('data-navigation-search-input', false);
});

it('shows results in Filament dropdown items under the field', function (): void {
    $this->get('/demo')
        ->assertOk()
        ->assertSee('fi-navigation-search-anchor', false)
        ->assertSeeInOrder(['fi-dropdown-list-item', 'data-navigation-search-result="1"'], false)
        ->assertDontSee('data-navigation-search-filters-navigation', false);
});

it('filters the sidebar instead of showing results when the option is on', function (): void {
    $this->get('/demo?filter=1')
        ->assertOk()
        ->assertSee('data-navigation-search-filters-navigation', false)
        ->assertDontSee('fi-navigation-search-anchor', false)
        ->assertSee('data-navigation-search-input="collapsed"', false);
});
