<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Study Circle
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function study_circle_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'study_circle_jetpack_setup' );
