# GitHub Copilot Repository Instructions

## Project Overview

This is a WordPress theme built with a modern development stack. The project follows strict coding standards and architectural patterns for maintainability and scalability.

## Architecture & Structure

### File Organization

```
/
├── blocks/               # Custom Gutenberg blocks (ACF-based)
├── components/           # Reusable UI components
├── src/                  # Source files
│   ├── styles/           # SCSS stylesheets
│   ├── scripts/          # TypeScript files
│   ├── functions/        # PHP helper functions
│   └── inc/              # Additional PHP includes
├── assets/               # Static assets (images, fonts, etc.)
├── vendor/               # Composer dependencies
└── woocommerce/          # WooCommerce template overrides
```

## Preferred Libraries

- WordPress 6+
- ACF Pro
- WooCommerce
- Swiper.js
- Fancybox
- TypeScript 5
- SCSS (Dart Sass)

### WordPress Theme Files

- Standard WordPress template hierarchy
- Custom post types and taxonomies
- ACF (Advanced Custom Fields) for all custom fields
- Custom Gutenberg blocks using ACF

### Build Configuration

**Webpack Features:**

- TypeScript compilation with ts-loader
- SCSS compilation with node-sass-glob-importer
- ESLint integration with Airbnb TypeScript config
- Stylelint with SCSS support
- BrowserSync for development with local config
- Automatic vendor file detection with glob patterns
- PostCSS with Autoprefixer
- Source maps for development

**Local Development:**

- Requires `local-site.json` configuration for BrowserSync (example structure: domain, ports, services for Local by Flywheel)
- Development watch mode: `npm run dev-watch`
- Production builds exclude source maps and enable minification

**Example local-site.json structure:**

```json
{
	"database": "mysql-8.0.16",
	"domain": "base-theme.local",
	"environment": "lightning",
	"hostConnections": null,
	"id": "5n7I8Mkan",
	"localVersion": "5.2.3+2248",
	"multiSite": null,
	"mysql": { "database": "local", "password": "root", "user": "root" },
	"name": "base-theme",
	"path": "/Users/mb-bma/Local Sites/base-theme",
	"phpVersion": "8.4.0",
	"ports": {},
	"services": {
		"mailhog": {
			"ports": { "SMTP": [10053], "WEB": [10052] },
			"type": "lightning",
			"version": "1.0.0"
		},
		"mysql": {
			"ports": { "MYSQL": [10055] },
			"role": "db",
			"type": "lightning",
			"version": "8.0.16"
		},
		"nginx": {
			"ports": { "HTTP": [10051] },
			"role": "http",
			"type": "lightning",
			"version": "1.16.0"
		},
		"php": {
			"ports": { "cgi": [10054] },
			"role": "php",
			"type": "lightning",
			"version": "8.4.0"
		}
	},
	"workspace": null
}
```

## Code Documentation & Comments

### PHP Documentation Standards

#### File Headers

Every PHP file must start with a security check. view.php files must also include a purpose comment:

```php
<?php
defined('ABSPATH') || exit('Forbidden'); // Exit if accessed directly.

/**
 * (Block name) Block Template
 */
```

#### Function Documentation

Use PHPDoc standards for all functions:

```php
/**
 * The get_flex_content function is a way to include the flexible content field in a block.
 *
 * @param string $template_type the key of the template field
 */

function get_flex_content(string $template_type): array {
    // Function implementation...
}
```

#### Class Documentation

Document classes with purpose, usage examples, and property descriptions:

```php
/**
 * FlexContent class to manage flexible content rendering
 */
class FlexContent {
    /**
     * @var string The accumulated HTML content
     */
    public $content = '';
}
```

#### Inline Comments

Use clear, descriptive inline comments for complex logic:

```php
// Extract field values at the top for better readability
$title = get_field('title') ?? [];
$gallery = get_field('gallery') ?? [];

// Skip rendering if essential data is missing
if (empty($gallery) || empty($title['title'])) {
    return;
}

// Build query arguments with proper taxonomy filtering
$tax_query = [];
if (!empty($_GET['category'])) {
    $tax_query[] = [
        'taxonomy' => 'product_category',
        'field'    => 'slug',
        'terms'    => sanitize_text_field($_GET['category']),
    ];
}
```

### TypeScript/JavaScript Documentation

#### Function Documentation

```typescript
/**
 * Opens popup with specified ID and manages body scroll
 *
 * @param popupName - The popup identifier matching data-popup attribute
 * @returns void
 */
function openPopup(popupName: string): void {
	// Implementation...
}
```

#### Complex Logic Comments

```typescript
// Query DOM elements with specific type annotations for better IDE support
const buttons = document.querySelectorAll<HTMLButtonElement>(".button");

// Use forEach instead of for loops for better readability and modern JS practices
buttons.forEach((button) => {
	// Add event listener with arrow function to maintain scope
	button.addEventListener("click", () => {
		// Handle button click logic here
	});
});
```

### Comment Guidelines

#### When to Comment

**ALWAYS comment:**

- Complex business logic or algorithms
- WordPress hooks and filters
- Security-related code
- Performance optimizations
- Browser-specific workarounds
- ACF field relationships and dependencies
- Database queries and data manipulation
- Third-party integrations (Gravity Forms, WooCommerce)

**DON'T comment:**

- Self-explanatory code
- Simple variable assignments
- Standard WordPress functions
- Basic HTML structure

#### Comment Style Guidelines

1. **Use clear, concise language**
2. **Write comments before writing code** (helps clarify thinking)
3. **Update comments when code changes**
4. **Use proper grammar and spelling**
5. **Avoid obvious comments** (`$i++; // Increment i`)
6. **Explain WHY, not WHAT**

#### Comments to Ignore

**DO NOT flag or comment on these common patterns:**

- **Black/white color values** - Variables like `#000;` or `$color-white: #fff;` are self-explanatory
- **Fixed pixel values** - Values like `height: 80px;` or `border-radius: 4px;` don't need explanation / variables
- **Magic numbers with clear context** - Numbers used in well-named variables or clear mathematical operations (e.g., `$grid-columns: 12;`, `width: 100% / 3;`)
- **Loading fields** - ACF fields are loaded automatically and are extracted within the view.php files
- **ACF keys** - Top-level fields in `config.php` do not require manual keys as they are automatically generated.

#### Special Comment Types

```php
// TODO: Implement caching for product queries
// FIXME: Mobile layout breaks on Safari iOS 14
// HACK: Temporary workaround for ACF bug #1234
// NOTE: This function depends on session being started
// WARNING: Do not modify without updating related CSS
```

### Documentation Maintenance

- Review and update comments during code reviews
- Remove outdated or incorrect comments
- Ensure examples in comments remain functional
- Keep PHPDoc annotations current with parameter types
- Update file headers when functionality changes significantly

## Coding Standards & Conventions

### PHP Coding Standards

#### Array Syntax

- **ALWAYS use square bracket syntax `[]` for arrays, NEVER `array()`**

```php
$fields = [
    'key' => 'value',
    'items' => [
        'item1',
        'item2'
    ]
];
```

#### ACF Field Configuration

- Use associative arrays with square brackets
- Follow consistent key naming: `{type}_{section}_{field}`
- Always include `'key'`, `'name'`, `'label'`, `'type'`
- Use `__('Label', 'lumie')` for internationalization
- Use true/false for boolean values, not strings or integers

```php
[
    'key' => 'block_hero_title',
    'name' => 'title',
    'label' => esc_html__('Title', 'lumie'),
    'type' => 'text',
    'wrapper' => [
        'width' => '50',
    ],
]
```

#### WordPress Patterns

- Use `defined('ABSPATH') || exit('Forbidden');` at the top of PHP files
- Use consistent prefixes for ACF field keys (e.g., `block_`, `field_`, `settings_`)
- Use WordPress coding standards for hooks and filters

### CSS/SCSS Standards

#### BEM Methodology

**STRICTLY follow BEM (Block Element Modifier) naming convention:**

```scss
// Block
.hero {
}

// Element (use double underscore)
.hero__title {
}
.hero__content {
}
.hero__button {
}

// Modifier (use double dash)
.hero--large {
}
.hero--dark {
}
.hero__title--centered {
}
```

#### SCSS Organization

- Use partial files with underscore prefix `_filename.scss`
- Import order: variables → functions → mixins → base → components

```scss
.block {
	&__element {
		&--modifier {
			// styles here
		}
	}
}
```

#### Breakpoint System

```scss
$breakpoints: (
	"mobile": (
		max-width: 739px,
	),
	"narrow": (
		min-width: 740px,
	),
	"normal": (
		min-width: 980px,
	),
	"large": (
		min-width: 1220px,
	),
	"xl": (
		min-width: 1320px,
	),
);

// Usage
@include bp("mobile") {
	// mobile styles
}

@include bp-only("narrow", "normal") {
	// styles between breakpoints
}
```

#### Functions and Variables

- Use `pxtorem()` function for pixel to rem conversion
- Use `pxtoem()` for em values with base font size
- Color variables: `$hue-accent-1`, `$hue-dark-1`, etc.
- Spacing variables: `$pad-mobile`, `$pad-normal`, etc.
- Use `assets()` function for asset paths

#### Font Awesome Icons

**Render icons with CSS pseudo-elements, NOT `<i class="fa-...">` elements.** This is the established theme pattern (see `single__author-social-link` in `src/styles/components/blog/_single.scss` and `.review-stars` in `_review-stars.scss`).

- Icon glyphs are centralized as unicode codepoints in `src/styles/partials/_variables.scss` (e.g. `$fa-arrow-right: "\f061";`). Add new icons there.
- Font families live in `_config.scss`: `$fa` (`"Font Awesome 6 Pro"`) for solid/regular/light, `$fab` (`"Font Awesome 6 Brands"`) for brand icons.
- Apply an icon on a `::before`/`::after` with `content: $fa-name;` plus `font-family: $fa;` (or `$fab`) and the correct `font-weight` — **weight selects the style**: `900` = solid, `400` = regular/brands, `300` = light.

```scss
.block__icon::before {
	content: $fa-arrow-up-right;
	font-family: $fa;
	font-weight: 900;
}
```

**Why:** class-names change between FA major versions (`fa` → `fas` → `fa-solid`), which breaks markup across many templates; unicode codepoints are stable and live in one file. It also avoids relying on the full FA kit CSS for class resolution.

**Accessibility:** pseudo-element glyphs are ignored by screen readers, so:

- **Decorative** icons need nothing extra (no stray `<i aria-hidden>`).
- **Meaningful/interactive** icons (icon-only link/button, ratings) MUST carry a text alternative on the element itself — `aria-label` on the link/button, or `role="img"` + `aria-label` on the wrapper (see `get_review_stars()` in `src/functions/randomness.php`). Never rely on the icon alone to convey meaning.

### TypeScript/JavaScript Standards

#### Type Safety

- Use strict TypeScript with proper type annotations
- Query DOM elements with specific types:

```typescript
const buttons = document.querySelectorAll<HTMLButtonElement>(".button");
const input = document.querySelector<HTMLInputElement>("#input");
```

#### Event Handling

- Use `addEventListener` for all event binding
- Use arrow functions for event callbacks
- Use `forEach` for NodeList iteration:

```typescript
buttons.forEach((button) =>
	button.addEventListener("click", () => {
		// handler logic
	}),
);
```

#### Module Organization

- Import external libraries at the top
- Use ES6 imports/exports
- Organize related functionality in modules

### WordPress Block Development

#### Block Structure

Each custom block includes:

```
blocks/block-name/
├── config.php          # ACF field configuration
├── view.php            # Template rendering
├── _block-name.scss    # Block-specific styles
└── block-name.ts       # Block-specific JavaScript (optional)
```

#### Block Configuration Pattern

```php
return [
    'title' => __('Block Title', 'lumie'),
    'category' => 'block-elements',
    'mode' => 'edit',
    'fields' => [
        [
			'name' => 'field_name',
            'label' => __('Field Label', 'lumie'),
            'type' => 'text',
            // field configuration...
        ],
    ],
];
```

#### Block Templates

- Use `general_section($block, ['class' => ['section', 'block-name']])` helper for consistent section wrapper HTML with proper attributes
- Use `echo !is_admin() ? '[raw]' : '';` for editor compatibility

### Component Development

#### Component Structure

```
components/component-name/
├── component-name.php   # PHP template/logic
├── _component-name.scss # Component styles
└── component-name.ts    # Component JavaScript (optional)
```

#### Component Patterns

- Self-contained and reusable
- Pass data via parameters using `component()` function
- Use BEM naming for CSS classes
- Include JavaScript only when necessary
- Use in foreach loops for repetitive elements (cards, news items, vacancy listings)

**Example component usage:**

```php
// In blocks - loop through data and render components
if (!empty($news_items)) {
    foreach ($news_items as $item) {
        component('news-card', [
            'title' => $item['title'],
            'excerpt' => $item['excerpt'],
            'image' => $item['image'],
            'link' => $item['link']
        ]);
    }
}
```

### Block Variable Extraction Rules

**When relevant, include line numbers and filenames to clarify code explanations:**

**Example format:** "On line [X] in `blocks/[name]/view.php`, the `$variable` (from config.php line [Y]) does [purpose]."

**Common Issues:**

- **Missing variable**: Check field name in config.php matches
- **Empty values**: Use `$field = $field ?? '';` for validation
- **Array errors**: Use `$value = $group['sub_field'] ?? '';` for nested fields

## Development Workflow

### Build Process

- **Development**: `npm run dev` or `npm run dev-watch` (with BrowserSync)
- **Production**: `npm run build`
- Webpack handles TypeScript compilation and SCSS processing
- ESLint and Stylelint for code quality

### Asset Management

- TypeScript files compiled to `dist/main.js` (created during build)
- SCSS files compiled to `dist/style.css` (created during build)
- Images and fonts in `assets/` directory
- Use `assets()` SCSS function for asset paths

### Code Quality Tools

- **ESLint**: Airbnb TypeScript configuration
- **Stylelint**: Standard SCSS configuration with custom rules
- **PHP**: WordPress coding standards
- All configured in respective `eslint.config.mjs`, `.stylelintrc.js` files

## Key Patterns & Helpers

### PHP Helpers

- `get_field()` - ACF field retrieval
- `get_general_settings()` - Common block settings
- `get_flex_content()` - Flexible content configuration
- `general_section()` - Consistent section wrapper

### SCSS Utilities

- Responsive breakpoint mixins: `@include bp("mobile")`
- Font size functions: `pxtorem()`, `pxtoem()`
- Grid system with column classes
- Utility classes for spacing, alignment

### JavaScript Utilities

- Swiper.js for carousels and sliders
- Fancybox for lightboxes and modals
- Custom popup system with localStorage
- Mobile menu toggle functionality

## Layout & Popup Systems

### Layout System

The theme uses a flexible layout system through the `layout/` directory for rendering content blocks:

#### Layout Content (`layout/layout-content.php`)

Processes flexible content arrays with predefined layout types:

```php
// Custom helper function for rendering flexible content layouts
layout("content", [
    'content' => $content,
]);
```

**Default Supported Layout Types:**

- `title` - Renders titles with proper heading hierarchy
- `content` - Basic HTML content using FlexContent class
- `fold_content` - Expandable content with "read more" trigger
- `image` - Image display with thumbnail support
- `logos` - Logo carousel with Swiper integration
- `accordions` - Expandable accordion sections
- `form` - Gravity Forms integration
- `buttons` - Button groups

#### FlexContent Class (`src/classes/flex-content.php`)

Manages content rendering with chainable methods:

```php
$content = new FlexContent();
$content->setContent($html);
$content->setImage($image_id, 'thumbnail');
$content->setLogos($logos_array, true); // with swiper
echo $content->getContent();
```

#### Layout Title (`layout/layout-title.php`)

Handles title rendering with automatic heading hierarchy:

### Popup System

The theme includes a comprehensive popup system for modals and overlays:

#### Popup Component (`components/popup/`)

**PHP Template (`popup.php`):**

```php
// Custom helper function to include reusable components
// Looks for components/{name}/{name}.php and extracts args as variables
component('popup', [
    'popup_id' => 'unique-popup-id',
    'title' => 'Popup Title',
    'content' => $content,
]);
```

**HTML Structure:**

```html
<div class="popup" data-popup="unique-popup-id">
	<div class="popup__close"></div>
	<div class="popup__content">
		<!-- Dynamic content here -->
	</div>
</div>
```

**Popup Functions:**

- `openPopup(popupName: string)` - Opens popup with fade-in animation
- `closePopup()` - Closes all popups and resets iframe sources
- Auto-attaches event listeners to `.show-popup` and `.popup__close` elements

**Features:**

- **Animation System**: CSS classes for smooth transitions
    - `popup--active` → `popup--show` (staggered timing)
    - `popup-background--active` → `popup-background--show`
- **Body Scroll Lock**: Adds `no-scroll` class to prevent background scrolling
- **Iframe Reset**: Automatically reloads embedded videos/iframes on close
- **Multiple Popup Support**: Can handle multiple popups with same data-popup attribute

### Button System

The theme includes a comprehensive button system with multiple types and functionality:

#### Button Classes (`src/classes/button.php`)

**BlockButton Class:**

```php
$button = new BlockButton('Button Text');
$button->set_type('btn btn--primary');
$button->set_display('phone'); // or 'desktop'
$button->set_link($url, $title, $target);
// OR
$button->set_popup($popup_id);
echo $button->get_button();
```

**BlockButtons Class:**

```php
if (!empty($buttons_group) && is_array($buttons_group)) {
	$button_group = new BlockButtons($buttons_group);
	echo $button_group->get_buttons();
}
```

#### Button Features

**Display Control:**

- `phone` - Only visible on mobile devices
- `desktop` - Only visible on desktop devices
- Empty - Visible on all devices

**Button Actions:**

- **Link**: Standard anchor link with URL, title, and target
- **Popup**: Triggers popup system with `data-popup` attribute

**Icon Support:**

- Icons can be added before and after button text
- Uses `icon_before` and `icon_after` fields

## Best Practices

### Performance

- Lazy load images where appropriate
- Minimize HTTP requests
- Use efficient selectors in CSS
- Optimize images and use modern formats

### Accessibility

- Semantic HTML structure
- Proper ARIA labels and roles
- Keyboard navigation support
- Color contrast compliance

### SEO & Standards

- Valid HTML markup
- Structured data where applicable
- Meta tag management
- Clean URL structure

### Browser Support

- Target modern browsers (last 2 versions, >1% usage)
- Graceful degradation for older browsers
- Mobile-first responsive design

## Common Pitfalls to Avoid

1. **Use modern PHP syntax** - Always use `[]` for arrays, never `array()`
2. **Don't mix BEM naming** - Stick to `block__element--modifier`
3. **Avoid deep nesting** - Keep SCSS nesting reasonable
4. **Don't forget type annotations** - Use proper TypeScript types
5. **Always check for elements** - Use optional chaining or null checks
6. **Follow WordPress security** - Sanitize inputs, use nonces
7. **Don't hardcode values** - Use variables and functions
8. **Test on mobile** - Mobile-first development approach
9. **Avoid inline styles** - Use classes and SCSS for styling
10. **Use PHP short echo tags** - `<?= $variable ?>` for outputting variables

## File Naming Conventions

- **PHP files**: `kebab-case.php`
- **SCSS files**: `_kebab-case.scss` (partials with underscore)
- **TypeScript files**: `kebab-case.ts`
- **Blocks/Components**: `kebab-case` directory names
- **CSS classes**: BEM convention with `kebab-case`
- **PHP variables**: `$snake_case`
- **JavaScript variables**: `camelCase`

## Git & PR Guidelines

### Commit Message Format

- Use [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) for all commit messages.
- Format: `<type>(optional-scope): <description>`
- Types: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `chore`, `build`, `ci`
- Example: `feat(blocks): add testimonial block`
- Keep messages concise but descriptive.

### Branch Naming Conventions

- Use `feature/`, `bugfix/`, or `hotfix/` prefixes.
- Format: `<type>/<short-description>`
- Examples:
    - `feature/testimonial-block`
    - `fix/header-logo-alignment`
    - `hotfix/php-error-on-save`
- Use kebab-case for branch names.

### Pull Request (PR) Review Requirements

- PRs must be small, focused, and address a single concern.
- Include a description with a screenshot if applicable.
- At least one reviewer must approve before merging.
- All CI checks must pass before merge.
- Address all review comments before merging.

## Summary

This WordPress theme prioritizes maintainability, performance, and developer experience while adhering to modern web development standards and WordPress best practices.

---

These instructions define the expected standards for all Copilot completions in this repository. Copilot should prefer examples, syntax, and file structures as shown above.
