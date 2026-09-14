# Lumie - WordPress Theme

![Logo](https://www.lumiedrinkz.nl/wp-content/themes/lumie/assets/lumie-logo.svg)

This repository contains the WordPress base theme from [Lumie](https://www.lumiedrinkz.nl/).

## Table of contents

- [Technologies](#technologies)
- [Installation](#installation)
- [Plugins](#plugins)
- [Other information](#other-information)
- [Functions](#functions)
- [Authors](#authors)
- [License](#license)

## Technologies

The project is created with:

- **PHP:** 8.5
- **WordPress:** 7.0
- **TypeScript:** Modern ES6+ with strict typing
- **NodeJS:** 24.15.0
- **NPM:** 11.12.1
- **Webpack:** 5.106.2 with modern build pipeline
- **ESLint:** 9.39.4 with flat configuration
- **Sass:** Modern scss compiler
- **Playwright:** 1.60.0 for automated screenshot generation

## Installation

Place the repository in the `wp-content/themes` folder of your WordPress install.

Install dev dependencies by running the following command.

```bash
npm install
```

```bash
composer install
```

To run webpack in development mode, run the following command.

```bash
npm run dev
```

To build for production, run:

```bash
npm run build
```

For development with browser-sync, run the following command:

```bash
npm run dev-watch
```

## Plugins

The theme is dependant on the following plugins:

- [ACF](https://www.advancedcustomfields.com/resources/)
- [ACF: Font Awesome](https://wordpress.org/plugins/advanced-custom-fields-font-awesome/)
- [Gravity Forms](https://docs.gravityforms.com/)
- [Yoast SEO](https://nl.wordpress.org/plugins/wordpress-seo/)

## Other information

- The changelog of the theme can be found in the [changelog.md](changelog.md) file.

## Functions

### Core Helper Functions

- **assets()** - Return URI to asset files. Looks in the `/assets/` folder.
- **attr()** - Add attributes to HTML elements (class, id, style, etc.). Transforms array to HTML attributes.
- **get_attr()** - Build HTML attributes from array and return as string.
- **component()** - Include reusable components. Components found in `./components/**/*.php`.
- **layout()** - Include layout files for clone blocks like title and content. Found in `./layout/layout-*.php`.

### ACF & Content Functions

- **get_all_forms()** - Retrieve all Gravity Forms for form management and selection.
- **get_general_settings()** - Return array with general ACF settings fields (spacings, background).
- **get_flex_content()** - Generate flexible content field configuration for ACF blocks.
- **general_section()** - Streamlined handling of general block settings (spacings, anchor links).
- **get_logo()** - Fetch the site logo from theme options with customizable attributes.
- **get_review_stars()** - Generate accessible review-star from a 1–10 score.
- **notification()** - Render the global site notification bar from theme options, with from/until scheduling.

### Title & Heading Functions

- **get_first_block_id()** - Retrieve the ID of the first ACF block on the page.
- **calculate_title_element()** - Calculate the correct heading element (h1-h6) based on block data.

### Utility Functions

- **encrypt_decrypt()** - Secure encryption/decryption for sensitive data like entry IDs.
- **input_to_button()** - Transform Gravity Forms input buttons to semantic button elements.
- **get_entry_value()** - Shortcode to retrieve Gravity Forms field values from URL parameters.
- **get_year()** - Shortcode `[get_year]` outputting the current year (e.g. footer copyright).
- **reading_time()** - Calculate estimated reading time from a post's ACF-block and classic content.

### Popup Functions

- **load_popup_names()** - Load available popup names for ACF selection fields.
- **add_global_popup()** - Add popup to global queue for footer rendering.
- **footer_popups()** - Render all queued popups in the footer.
- **popup_shortcode()** - Shortcode for displaying popups: `[popup id="popup-name"]`.

## Frontend Libraries

### Fancybox (v6.0.17)

Modern lightbox library for images, videos, and content modals.

**Documentation:** [https://fancyapps.com/fancybox/](https://fancyapps.com/fancybox/)

### Swiper (Modern Touch Slider)

Touch-enabled slider/carousel component.

**Documentation:** [https://swiperjs.com/](https://swiperjs.com/)

## Authors

- [@wesselvandenijssel](https://github.com/wesselvandenijssel)

## License

[MIT](https://choosealicense.com/licenses/mit/)
