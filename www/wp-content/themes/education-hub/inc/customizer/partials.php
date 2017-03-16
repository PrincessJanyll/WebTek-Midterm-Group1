<?php
/**
 * Customizer partials.
 *
 * @package Education_Hub
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.9.3
 *
 * @return void
 */
function education_hub_customize_partial_blogname() {

	bloginfo( 'name' );

}

/**
 * Render the site description for the selective refresh partial.
 *
 * @since 1.9.3
 *
 * @return void
 */
function education_hub_customize_partial_blogdescription() {

	bloginfo( 'description' );

}
