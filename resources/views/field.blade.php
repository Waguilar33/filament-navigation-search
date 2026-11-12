@php
    use Filament\Support\Icons\Heroicon;

    $terms = collect($entries)->map(fn ($entry) => $entry->terms)->all();
    $placeholder = __('filament-navigation-search::navigation-search.placeholder');
@endphp

<div
    x-data="{
        query: '',
        active: 0,
        matches: [],
        haystacks: [],
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
            })
        },
        moveHighlight(step) {
            if (! this.matches.length) {
                return
            }

            this.active = (this.active + step + this.matches.length) % this.matches.length
        },
        openHighlighted(panel) {
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
    x-on:livewire:navigated.window="query = ''"
    class="fi-navigation-search"
>
    <div x-show="$store.sidebar.isOpen" data-navigation-search-panel>
        @include('filament-navigation-search::search-panel', ['mode' => 'expanded'])
    </div>

    <x-filament::dropdown
        :placement="(__('filament-panels::layout.direction') === 'rtl') ? 'left-start' : 'right-start'"
        :menu="false"
        data-navigation-search-dropdown
        width="xs"
        x-show="! $store.sidebar.isOpen"
        x-cloak
    >
        <x-slot name="trigger">
            <button
                type="button"
                x-on:click="requestAnimationFrame(() => $el.closest('.fi-navigation-search').querySelector(`[data-navigation-search-input='collapsed']`).focus())"
                aria-label="{{ $placeholder }}"
                class="fi-sidebar-group-dropdown-trigger-btn"
            >
                {{ \Filament\Support\generate_icon_html(Heroicon::OutlinedMagnifyingGlass, size: \Filament\Support\Enums\IconSize::Large) }}
            </button>
        </x-slot>

        <div class="fi-navigation-search-dropdown" data-navigation-search-panel>
            @include('filament-navigation-search::search-panel', ['mode' => 'collapsed'])
        </div>
    </x-filament::dropdown>
</div>
