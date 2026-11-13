<x-filament::dropdown.list>
    @foreach ($entries as $index => $entry)
        <x-filament::dropdown.list.item
            tag="a"
            :href="$entry->url"
            :target="$entry->opensInNewTab ? '_blank' : null"
            :icon="$entry->icon"
            :badge="$entry->group"
            badge-color="gray"
            data-navigation-search-result="{{ $index }}"
            x-show="matches.includes({{ $index }})"
            x-bind:class="{ 'fi-selected': matches[active] === {{ $index }} }"
            x-on:click="query = ''; $store.sidebar.isMobile && $store.sidebar.close()"
        >
            {{ $entry->label }}
        </x-filament::dropdown.list.item>
    @endforeach

    <p x-show="matches.length === 0" class="fi-navigation-search-empty">
        {{ __('filament-navigation-search::navigation-search.no_results') }}
    </p>
</x-filament::dropdown.list>
