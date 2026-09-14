# Changelog

## Version 1.3.2 - Release Date: [24-04-2026]

### Tweaks:

- The sanitizing in the buttons is changed to a less strict variant

## Version 1.3.1 - Release Date: [19-03-2026]

### Tweaks:

- The sanitizing in the content frames is removed, because it hindered Gravity Forms usage

## Version 1.3.0 - Release Date: [03-03-2026]

### New features:

- Added a new button action 'buttons', which can be used to create a new group of buttons. This can be used recursive up to 3 times.

### Tweaks:

- The post types labels are shown in the settings, instead of the post type name

### Refactor:

- The `mbw_button_cfs` helper function is created.
- The `mbw_render_button` helper function is created.

## Version 1.2.2 - Release Date: [23-01-2026]

### Tweaks:

- The content in the wysiwyg editor now also accepts `<script>` tags

### Bugfixes:

- Fixed a JavaScript error which occured when a page doesn't have a widget

## Version 1.2.1 - Release Date: [19-01-2026]

### Bugfixes:

- Fixed svg support for the image field in the widget custom fields.

## Version 1.2.0 - Release Date: [13-11-2025]

### New features:

- Added a new button action 'click-event', which can be used to create custom click-events

## Version 1.1.3 - Release Date: [12-11-2025]

### Bugfixes:

- Added a priority to `mbw_add_widget_custom_fields`, to make sure all post types can be retrieved

## Version 1.1.2 - Release Date: [26-09-2025]

### Tweaks:

- Refactored the display input fields to make it more intuitively

### Bugfixes:

- Used the `init` hook instead of the `acf/init` hook to load the custom fields, so it can load post types from other plugins (i.e. WooCommerce)
- Removed attachments from the available post types

## Version 1.1.1 - Release Date: [16-09-2025]

### New features:

- Added ID attributes to the buttons (toggle included), making it easier to track clicks

## Version 1.1.0 - Release Date: [10-09-2025]

### New features:

- Automated updates

## Version 1.0.0 - Release Date: [04-09-2025]

### New features:

- Added `mbwidget` post type with basic functionalities:
    - The widget has a default message and an image
    - The widget has buttons which can act as link or can show a new content window
    - The widget can be shown/hidden on specific posts/post types
    - The widget can open automatically conditionally
    - The widget buttons colors can be chosen from a options page
    - The plugin supports caching for multiple well-known caching plugins
