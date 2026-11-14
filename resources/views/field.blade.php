@php
    use Filament\Support\Enums\IconSize;
    use Filament\Support\Icons\Heroicon;

    $terms = collect($entries)->map(fn ($entry) => $entry->terms)->all();
    $urls = collect($entries)->map(fn ($entry) => $entry->url)->all();
    $placeholder = __('filament-navigation-search::navigation-search.placeholder');
@endphp

<div
    x-data="{
        query: '',
        active: 0,
        matches: [],
        haystacks: [],
        urls: @js($urls),
        filtersNavigation: @js($filterNavigation),
        filteredItems: [],
        savedCollapsedGroups: null,
        normalize(value) {
            return value.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase()
        },
        init() {
            this.haystacks = @js($terms).map((terms) => this.normalize(terms.join(' ')))
            this.$watch('query', (query) => {
                const words = this.normalize(query).split(/\s+/).filter(Boolean)
                this.active = 0
                this.matches = words.length
                    ? this.haystacks.flatMap((haystack, index) => words.every((word) => haystack.includes(word)) ? [index] : [])
                    : []
                this.syncResults()
            })
            this.$watch('$store.sidebar.isOpen', () => this.syncResults())
        },
        isSearching() {
            return this.query.trim() !== ''
        },
        isFilteringNavigation() {
            return this.filtersNavigation && this.$store.sidebar.isOpen
        },
        syncResults() {
            this.filterNavigation(this.isFilteringNavigation() && this.isSearching())

            const dropdown = this.$root.querySelector('[data-navigation-search-results]')

            if (! dropdown) {
                return
            }

            const results = Alpine.$data(dropdown)

            if (this.$store.sidebar.isOpen && this.isSearching()) {
                results.open(new MouseEvent('click'))

                return
            }

            results.close()
        },
        filterNavigation(isFiltering) {
            const navigation = this.$root.closest('.fi-sidebar-nav')
            const matchedUrls = new Set(this.matches.map((index) => this.urls[index]))
            const openedGroups = []

            this.filteredItems = []

            navigation?.querySelectorAll('.fi-sidebar-nav-groups .fi-sidebar-item').forEach((item) => {
                const isMatch = matchedUrls.has(item.querySelector(':scope > a')?.getAttribute('href'))

                item.style.display = (! isFiltering || isMatch) ? '' : 'none'
                delete item.dataset.navigationSearchHighlighted

                if (isFiltering && isMatch) {
                    this.filteredItems.push(item)
                }
            })

            navigation?.querySelectorAll('.fi-sidebar-nav-groups > .fi-sidebar-group').forEach((group) => {
                const hasMatch = this.filteredItems.some((item) => group.contains(item))

                group.style.display = (! isFiltering || hasMatch) ? '' : 'none'

                if (hasMatch) {
                    openedGroups.push(group.dataset.groupLabel)
                }
            })

            if (isFiltering) {
                this.savedCollapsedGroups ??= [...(this.$store.sidebar.collapsedGroups ?? [])]
                this.$store.sidebar.collapsedGroups = this.savedCollapsedGroups.filter((label) => ! openedGroups.includes(label))
                this.highlightFilteredItem()

                return
            }

            if (this.savedCollapsedGroups !== null) {
                this.$store.sidebar.collapsedGroups = this.savedCollapsedGroups
                this.savedCollapsedGroups = null
            }
        },
        highlightFilteredItem() {
            this.filteredItems.forEach((item, index) => {
                if (index === this.active) {
                    item.dataset.navigationSearchHighlighted = ''
                } else {
                    delete item.dataset.navigationSearchHighlighted
                }
            })
        },
        moveHighlight(step) {
            const count = this.isFilteringNavigation() ? this.filteredItems.length : this.matches.length

            if (! count) {
                return
            }

            this.active = (this.active + step + count) % count

            if (this.isFilteringNavigation()) {
                this.highlightFilteredItem()
            }
        },
        openHighlighted(panel) {
            if (this.isFilteringNavigation()) {
                const link = this.filteredItems[this.active]?.querySelector(':scope > a')

                this.query = ''
                link?.click()

                return
            }

            const index = this.matches[this.active]

            if (index === undefined) {
                return
            }

            panel.querySelector(`[data-navigation-search-result='${index}']`).click()
        },
        focusFromShortcut(event) {
            const target = event.target

            if (target.isContentEditable || ['INPUT', 'SELECT', 'TEXTAREA'].includes(target.tagName)) {
                return
            }

            event.preventDefault()

            const input = (mode) => this.$root.querySelector(`[data-navigation-search-input='${mode}']`)

            if (this.$store.sidebar.isOpen) {
                input('expanded').focus()

                return
            }

            if (this.$store.sidebar.isMobile) {
                this.$store.sidebar.open()
                this.$nextTick(() => input('expanded').focus())

                return
            }

            Alpine.$data(this.$root.querySelector('[data-navigation-search-dropdown]')).open(event)
            requestAnimationFrame(() => input('collapsed').focus())
        },
    }"
    x-on:keydown.slash.window="focusFromShortcut($event)"
    x-on:livewire:navigate.window="query = ''"
    x-on:livewire:navigated.window="query = ''"
    @if ($filterNavigation)
        data-navigation-search-filters-navigation
    @endif
    @if ($sticky)
        data-navigation-search-sticky
        x-bind:data-navigation-search-sidebar-collapsed="! $store.sidebar.isOpen"
    @endif
    class="fi-navigation-search"
>
    <div x-show="$store.sidebar.isOpen" data-navigation-search-panel>
        @include('filament-navigation-search::input', ['mode' => 'expanded'])

        @if ($filterNavigation)
            <p x-show="isSearching() && filteredItems.length === 0" x-cloak class="fi-navigation-search-empty">
                {{ __('filament-navigation-search::navigation-search.no_results') }}
            </p>
        @else
            <x-filament::dropdown
                placement="bottom-start"
                :menu="false"
                width="none"
                data-navigation-search-results
            >
                <x-slot name="trigger">
                    <span tabindex="-1" aria-hidden="true" class="fi-navigation-search-anchor"></span>
                </x-slot>

                @include('filament-navigation-search::results')
            </x-filament::dropdown>
        @endif
    </div>

    <div x-show="! $store.sidebar.isOpen" x-cloak class="fi-sidebar-nav-groups">
        <x-filament::dropdown
            :placement="(__('filament-panels::layout.direction') === 'rtl') ? 'left-start' : 'right-start'"
            :menu="false"
            data-navigation-search-dropdown
            width="xs"
        >
            <x-slot name="trigger">
                <button
                    type="button"
                    x-on:click="requestAnimationFrame(() => $el.closest('.fi-navigation-search').querySelector(`[data-navigation-search-input='collapsed']`).focus())"
                    aria-label="{{ $placeholder }}"
                    class="fi-sidebar-group-dropdown-trigger-btn"
                >
                    {{ \Filament\Support\generate_icon_html(Heroicon::OutlinedMagnifyingGlass, size: IconSize::Large) }}
                </button>
            </x-slot>

            <div class="fi-navigation-search-dropdown" data-navigation-search-panel>
                @include('filament-navigation-search::input', ['mode' => 'collapsed'])

                <div x-show="isSearching()" x-cloak class="fi-navigation-search-dropdown-results">
                    @include('filament-navigation-search::results')
                </div>
            </div>
        </x-filament::dropdown>
    </div>
</div>
