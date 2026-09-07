# Release Notes

## [Unreleased]

### Added

- Link the Apphold X account from the about page
- Make the app installable as a PWA with a web app manifest, icons and a pass-through service worker
- Show the latest posts of the Apphold blog on the about page, cached for a day
- Verify the "remember me" login with feature tests

### Changed

- Scope the session and "remember me" cookie names to each installation so two apps on the same domain
  no longer log each other out
- Stop setting the framework's shared `XSRF-TOKEN` cookie, which was also mixed up between apps on the same domain
- Rebuild the stylesheet around a single set of design tokens: brand purple, warm neutrals, semantic
  tints, elevation and radius scales
- Deepen the brand purple so white text on the navigation and on primary buttons meets accessibility
  contrast (was 3.8:1, now 6.2:1)
- Replace the dark table headers with light small caps headers and the solid badges with tinted ones,
  all above 5.5:1 contrast
- Refresh cards, buttons, inputs, dropdowns, toasts and modals with rounder corners, softer shadows
  and brand colored focus rings
- Move the duplicated `<head>` markup of the three layouts into a shared partial
- Raise the contrast of the whole interface: darker body text, visible table row separators and card
  borders and deeper badge tints with an outline
- Use a plain white page background, the cards now stand out through their border instead of a tint
- Drop the leftover `border-0` utilities so every card and every table row shows its light border
- Reduce the height of the main navigation bar, the desktop nav links no longer pad out to 1.5rem
- Slightly smaller "APPHOLD" wordmark in the main navigation
- Give every table the same row height and the same sort affordance, the caret now comes from the
  stylesheet and the headers all go through the `sort_link()` helper
- Sorting a list keeps the search and the filters that are already applied

### Fixed

- Table pages no longer scroll the whole page sideways on phones, the tables scroll on their own
- Page actions next to the breadcrumb are always visible on phones instead of hiding behind a second menu button
- The personal access token form and table no longer overflow on phones
- Allow pinch zooming again on mobile devices
- Keep table rows on a single line on phones and show an edge shadow while a table can still be scrolled
- Card headers, table headers and card footers no longer bleed over the rounded card corners
- Search and filter controls take the full row on phones instead of keeping their desktop widths
- The installation specific cookie names now also hold with a cached config, previously every installation
  fell back to the same name and logged the others out


## [1.0.0] - 2026-03-26

### Added

- Add account management support to the app (#1)
- Add support for global settings (#2)
- Allow multiple users with their own data (#3)
- Add links CRUD page (#4)
- Add tags CRUD page (#5)
- Create dashboard page for listing all the links (#6)
- Support Event tracking for each project allowing users to see a history of events and its details (#7)
- Add other common pages such as the user dashboard, about, settings and other account related pages (#8)
