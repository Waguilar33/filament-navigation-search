@php
    use Filament\Support\Enums\IconSize;
    use Filament\Support\Icons\Heroicon;
@endphp

<x-filament::input.wrapper :prefix-icon="Heroicon::MagnifyingGlass" inline-prefix>
    <x-filament::input
        type="search"
        data-navigation-search-input="{{ $mode }}"
        x-model="query"
        x-on:keydown.arrow-down.prevent="moveHighlight(1)"
        x-on:keydown.arrow-up.prevent="moveHighlight(-1)"
        x-on:keydown.enter.prevent="openHighlighted($el.closest('[data-navigation-search-panel]'))"
        x-on:keydown.escape="query = ''"
        :placeholder="$placeholder"
        :aria-label="$placeholder"
        autocomplete="off"
    />
</x-filament::input.wrapper>

<ul x-show="query.trim() !== ''" x-cloak class="fi-navigation-search-results">
    @foreach ($entries as $index => $entry)
        <li
            x-show="matches.includes({{ $index }})"
            x-bind:class="{ 'fi-active': matches[active] === {{ $index }} }"
            class="fi-sidebar-item fi-sidebar-item-has-url"
        >
            <a
                {{ \Filament\Support\generate_href_html($entry->url, $entry->opensInNewTab) }}
                data-navigation-search-result="{{ $index }}"
                x-on:click="query = ''; $store.sidebar.isMobile && $store.sidebar.close()"
                class="fi-sidebar-item-btn"
            >
                @if (filled($entry->icon))
                    {{ \Filament\Support\generate_icon_html($entry->icon, attributes: (new \Filament\Support\View\ComponentAttributeBag)->class(['fi-sidebar-item-icon']), size: IconSize::Large) }}
                @endif

                <span class="fi-sidebar-item-label">{{ $entry->label }}</span>

                @if (filled($entry->group))
                    <span class="fi-navigation-search-group">{{ $entry->group }}</span>
                @endif
            </a>
        </li>
    @endforeach

    <li x-show="matches.length === 0" class="fi-navigation-search-empty">
        {{ __('filament-navigation-search::navigation-search.no_results') }}
    </li>
</ul>
