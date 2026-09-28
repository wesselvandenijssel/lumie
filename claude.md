# Claude Code Instructions: Lumie WordPress Site

This is the WordPress root (`app/public/`) of the Lumie webshop, running in **Local (by Flywheel)**. These instructions cover the whole install. Theme code has its own `CLAUDE.md` with coding standards and the full brand guidelines.

## Where Things Live

```
~/Local Sites/lumie/
├── app/
│   └── public/                          # WordPress root, THIS FOLDER (WP-CLI works here)
│       ├── wp-admin/                    # WordPress core, never edit
│       ├── wp-includes/                 # WordPress core, never edit
│       ├── wp-content/
│       │   ├── themes/lumie/            # Theme, see its own CLAUDE.md
│       │   ├── plugins/                 # Third-party plugins, never edit in place
│       │   └── uploads/                 # Media library
│       └── wp-config.php
├── logs/
│   ├── nginx/
│   └── php/
│       └── error.log                    # PHP errors
└── conf/                                # Local configuration
```

The theme folder has the same name as the Local site. If the site is not called `lumie`, use the actual folder name.

## Where to Put Code

- **Theme code** goes in `wp-content/themes/lumie/`. Follow the theme `CLAUDE.md` for PHP, SCSS, TypeScript, blocks and components.
- **Never edit WordPress core** (`wp-admin/`, `wp-includes/`, root PHP files).
- **Never edit plugin files** in `wp-content/plugins/`. Updates overwrite them. Use hooks and filters from the theme instead.
- **WooCommerce template changes** go in the theme under `woocommerce/`, not in the plugin.
- Ask before creating a new plugin or mu-plugin.

## WP-CLI

Run WP-CLI directly from this folder:

```bash
wp plugin list
wp theme list
wp cache flush
wp option get siteurl
wp db query "SELECT * FROM wp_options LIMIT 5"
```

When working from inside the theme folder, use the subshell pattern from the theme `CLAUDE.md`: `(cd ../../../../ && wp ...)`.

Load the `/wordpress-local` skill for any WP-CLI, database or log work.

### Logs

```bash
tail -n 100 ../../logs/php/error.log
```

## Database Safety

- Run `wp db export` before any change that writes to the database.
- Confirm with the user before: `wp search-replace`, `wp db reset`, `wp db import`, deleting posts, products, users or options, and activating or deactivating plugins.
- Always run `wp search-replace` with `--dry-run` first and show the result.
- Never edit `wp-config.php` without explicit approval. Never commit it or share its credentials.

## Version Control

Check `git status` before assuming a folder is tracked. Do not add WordPress core, plugins or `uploads/` to git.

## Legal Rules for the Whole Site

Lumie contains 1.2% alcohol. The Dutch Reclamecode voor Alcoholhoudende Dranken and EU Regulation (EC) No 1924/2006 apply to every page, product and post. This also covers content you change through WP-CLI, the database or plugin settings.

- The slogan **"Geen 18, geen alcohol"** stays visible on every page.
- The age check runs on entry **and** at checkout. Never disable, bypass or cache around it. This includes plugin settings, caching plugin exclusions and WooCommerce checkout changes. Flag any change that could affect it.
- No references to hangovers, recovery, hydration or feeling fitter for Lumie drinks. This applies to product titles, descriptions, excerpts, alt text, SEO meta and structured data.
- Hydration may only be mentioned for the electrolyte tablets. Their product page shows no Lumie drinks (related products, upsells, cross-sells).
- Never present sport, study success or social success as a result of drinking.
- Allowed: "energieverlaagd" (at least 30% fewer calories than a comparable product), "laag alcoholgehalte", calorie count and alcohol percentage.
- Models and creators in images are demonstrably 25 or older.

Brand colors, typography, logo and contrast rules live in the theme `CLAUDE.md`.

## Tool Use Expectations

- Prefer the dedicated tools (Read, Edit, Write, Grep, Glob) over shell equivalents.
- Make independent tool calls in parallel where possible.
- Confirm before destructive or shared-state actions.
- Keep responses short. Reference files as `path:line`.
