<?php
/**
 * Storefront Child Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package storefront-child
 */

add_action( 'wp_enqueue_scripts', 'storefront_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function storefront_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'storefront-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'storefront-style' ]
	);
}


/**
 * Remove sidebar from single product page
 */
add_action( 'get_header', 'remove_storefront_sidebar' );

function remove_storefront_sidebar()
{
	if (is_product()) {
		remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
	}
}
