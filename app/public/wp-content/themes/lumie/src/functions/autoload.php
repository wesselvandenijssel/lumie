<?php
require get_template_directory() . '/vendor/autoload.php';

use Symfony\Component\Finder\Finder;

// Search for all files and directories in this folder
$finder = new Finder();
$finder->files()->in(__DIR__);
$finder->files()->notName(
	[
		'auto-finder.php',
		// Excludes the 'theme-support' file, which must be included first to ensure compatibility with the ACF fields.
		'theme-support.php',
		// Excludes the 'theme-helpers' file, which must be included first to ensure compatibility with other files.
		'theme-helpers.php',
	]
);

// Prevents the inclusion of default Woocommerce files.
$finder->exclude(['woocommerce']);

if ($finder->hasResults()) {
	foreach ($finder as $file) {
		require_once $file->getRealPath();
	}
}
