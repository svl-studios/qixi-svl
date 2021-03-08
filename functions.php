<?php
/**
 * Functions for SVL Admin (Qixi Child Theme).
 *
 * @package    SVL Admin (Seele Child Theme)
 * @author     SVL Studios
 * @copyright  Copyright (c) 2021, SVL Studios
 * @link       http://www.svlstudios.com
 * @since      1.0.0
 */

/**
 * Load child theme stylesheet.
 */
function svl_childtheme_style() {
	global $qixi_options;

	$theme     = wp_get_theme();
	$child_ver = $theme->get( 'Version' );

	wp_enqueue_style(
		'svl-main-style-child-css',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'svl-main-styles-css' ),
		$child_ver,
		'all'
	);
}

add_action( 'wp_enqueue_scripts', 'svl_childtheme_style' );

/**
 * Load child theme specific functions
 */
function svl_setup() {
	require_once get_stylesheet_directory() . '/admin/class-qixi-functions.php';
	require_once get_stylesheet_directory() . '/demo/class-qixi-demo-toggle.php';
}

add_action( 'after_setup_theme', 'svl_setup', 9 );