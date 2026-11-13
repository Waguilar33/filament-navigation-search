@php
    use Filament\Support\Icons\Heroicon;
@endphp

<x-filament::input.wrapper :prefix-icon="Heroicon::MagnifyingGlass" inline-prefix>
    <x-filament::input
        type="search"
        data-navigation-search-input="{{ $mode }}"
        x-model="query"
        x-on:focus="syncResults()"
        x-on:keydown.arrow-down.prevent="moveHighlight(1)"
        x-on:keydown.arrow-up.prevent="moveHighlight(-1)"
        x-on:keydown.enter.prevent="openHighlighted($el.closest('[data-navigation-search-panel]'))"
        x-on:keydown.escape="query = ''"
        :placeholder="$placeholder"
        :aria-label="$placeholder"
        autocomplete="off"
    />
</x-filament::input.wrapper>
