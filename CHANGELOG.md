# Changelog

All notable changes to `filament-navigation-search` will be documented in this file.

## v1.0.0-beta.6 - 2026-11-26

- In the collapsed (icon-only) sidebar the search dropdown now opens level with the icon. It opened lower (and further right) by however far the sidebar sits from the top-left of the window, such as below a topbar.

## v1.0.0-beta.5 - 2026-11-15

- In the collapsed (icon-only) sidebar the search opens to the right of the icon again. The sidebar's scrolling clipped the dropdown, so it flipped left and off the screen; it now opens outside the sidebar.

## v1.0.0-beta.4 - 2026-11-13

- New `sticky()` option: keeps the field pinned to the top of the sidebar while the menu scrolls. Off by default.

## v1.0.0-beta.3 - 2026-11-12

- The search icon in the collapsed (icon-only) sidebar now lines up with the other sidebar icons and no longer makes the sidebar overflow sideways.

## v1.0.0-beta.2 - 2026-11-12

- Results show in Filament's dropdown component under the field, as wide as the field, with the group as a gray badge.
- New `filterNavigation()` option: filters Filament's sidebar in place while typing, opening collapsed groups that hold a match and restoring them after. Off by default.

## v1.0.0-beta.1 - 2026-11-12

- A search field at the top of the sidebar of panels that register the plugin, separate from global search.
- Matches the menu label, the group label, a resource's singular model label and keywords from `HasNavigationSearchKeywords`, ignoring case and accents.
- Keyboard: `/`, arrow keys, Enter, Esc.
- A search icon with a dropdown in the collapsed sidebar.
- English and Spanish.
