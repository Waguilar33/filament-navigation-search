# Navigation Search Rules

**Status:** Stage 1 built on branch `feat/stage-1`, to be released as v1.0.0-beta.1.
**Last updated:** 2026-11-12

## Agreed rules

| # | Rule | Reason |
|---|---|---|
| NS1 | Menu search is its own field, separate from Filament's global search. It never registers a global search provider. | Owner, 2026-11-12: "one is to quickly search the options on my menu, and the other one is a global search that has nothing to do with this." |
| NS2 | Matches show as a result list under the field. The plugin never hides or reorders Filament's own sidebar groups and items. | Owner agreed 2026-11-12. Filtering the menu in place depends on Filament's internal sidebar markup and cannot work in the collapsed sidebar. |
| NS3 | The index is built on the server from the panel's resolved navigation (`Filament::getNavigation()`). Labels, translations, hidden items and authorization come from Filament; the plugin keeps no list of its own. | Owner, 2026-11-12: Filament already resolves every label, including the fallback to the model label, and the translations. |
| NS4 | An entry matches on its navigation label, its group label, and, for a Resource, its singular model label (`getModelLabel()`). | Owner, 2026-11-12: reach the label Filament resolves even when none is set. |
| NS5 | A Resource or Page adds search keywords by implementing `HasNavigationSearchKeywords` (`getNavigationSearchKeywords()`, returning translated strings). The README documents it as the way to extend the search. | Owner, 2026-11-12: extending it is documentation; plugins built the Filament way work as they are. |
| NS6 | Matching ignores case and accents and runs in the browser over the index rendered with the page. Typing makes no request. | Proposed 2026-11-12 with the first recommendation; not objected. Spanish labels carry accents ("ordenes" finds "Órdenes"). |
| NS7 | Keys: `/` focuses the field, arrow keys move through results, Enter opens the highlighted result, Esc clears. | Proposed 2026-11-12 with the first recommendation; not objected. |
| NS8 | In the collapsed (icon-only) desktop sidebar the field becomes a search icon that opens the same field and results in a dropdown. | Owner, 2026-11-12: the icon-only sidebar still needs search. |
| NS9 | The field shows only in panels that register the plugin. The plugin adds it through the panel's own `renderHook(PanelsRenderHook::SIDEBAR_NAV_START, …)`. | Raised 2026-11-12 in the plan; a global hook would add it to every panel. |
| NS10 | The query clears after a result opens, including in SPA mode. | Raised 2026-11-12 in the plan; the sidebar persists across `wire:navigate`. |
| NS11 | The plugin's own words (placeholder, no results) are translated in `en` and `es`. | Owner, 2026-11-12: translations must be handled. |
| NS12 | Beta supports Filament `^5.10.2` only and is released as `v1.0.0-beta.N` from a public repository. | Owner, 2026-11-12: everything stays in beta for a while. Filament 4 is not verified. |

## Superseded

| Rule | Old wording | Replaced | Why |
|---|---|---|---|

## Facts

- Filament 5.11: `Resource::getNavigationItems()` and `Page::getNavigationItems()` key each `NavigationItem` with the class name (`->key(static::class)`). The index reads `getKey()` to reach the Resource or Page behind an entry; an item added by hand has no class behind it and matches on its label and group only.
- `PanelsRenderHook::SIDEBAR_NAV_START` renders inside `<nav class="fi-sidebar-nav">`, before the group list.

## Parked

| Item | Why parked |
|---|---|
| Cluster pages, child items (`parentItem`) and panels using `->topNavigation()` | Owner, 2026-11-12: out of stage 1. |
| A plugin-level callback to add keywords to pages the app does not own | Owner, 2026-11-12: documentation first; revisit if a real case appears. |
| Filament 4 support | Not verified; beta targets Filament 5. |
| Packagist and the Filament plugin directory | Owner decides when beta ends. |
