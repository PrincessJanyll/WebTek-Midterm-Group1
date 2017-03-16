<?php
  

// #################################################
// Kirki
// #################################################


Kirki::add_config( 'wp-simple-config', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );


// User Guide

Kirki::add_section( 'setup', array(
    'title'          => __( 'Theme Userguide', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'userguide-info',
	'label'    => __( 'Userguide', 'wp-simple' ),
	'section'  => 'setup',
	'type'     => 'custom',
	'priority' => 1,
	'description'   => __( 'This theme was designed to be very easy to set up but just in case we\'ve created a userguide to assist: ', 'wp-simple' ) . '<a href="https://docs.google.com/document/d/1dYjRbrKt9JaE4YjfH48aI2JzEOEybFCxoUFVbDU1om8" target="_blank" class="button button-wp-simple-secondary">View User Guide</a>',
) );


// General

Kirki::add_section( 'general', array(
    'title'          => __( 'General Theme Settings', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'logo',
	'label'    => __( 'Text Logo', 'wp-simple' ),
	'section'  => 'general',
	'type'     => 'text',
	'priority' => 10,
	'default'  => ''
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'copyright',
	'label'    => __( 'Copyright Text', 'wp-simple' ),
	'section'  => 'general',
	'type'     => 'text',
	'priority' => 10,
	'default'  => get_bloginfo( 'name' )
) );
          
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'home-slug',
	'label'    => __( 'Top of Homepage Navigation Menu ID', 'wp-simple' ),
	'section'  => 'general',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'home', 
	'description'=>'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default shown in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://mysite.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://mysite.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.'
) );          
            
            
// Banner Stuff Here           
            
Kirki::add_panel( 'banner_settings', array(
    'priority'    => 2,
    'title'       => __( 'Banner Settings', 'wp-simple' ),
    'description' => '',
) );


Kirki::add_section( 'fp_banner_options', array(
    'title'          => __( 'Frontpage General Options', 'wp-simple' ),
    'description'    => '',
    'panel'          => 'banner_settings',
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );


Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-banner-toggle',
	'label'       => __( 'Frontpage Banner Status', 'wp-simple' ),
	'section'     => 'fp_banner_options',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => __( 'Show', 'wp-simple' ),
        '2'   => __( 'Demo', 'wp-simple' ),
		'3'   => __( 'Hide', 'wp-simple' ),
	),
) );


Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-banner-background-color-new',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp_banner_options',
	'default'     => '#ea940d',
	'priority'    => 1,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-banner',
			'property' => 'background-color',
		),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-banner-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp_banner_options',
	'default'     => '',
	'priority'    => 1,
) );


Kirki::add_section( 'fp_banner_customizer_options', array(
    'title'          => __( 'Frontpage Custom Banner Options', 'wp-simple' ),
    'description'    => '',
    'panel'          => 'banner_settings',
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-banner-title',
	'label'    => __( 'Banner - Main Title', 'wp-simple' ),
	'section'  => 'fp_banner_customizer_options',
	'type'     => 'text',
	'priority' => 1,
	'default'  => '',
	'description'   => __( 'This is the big text in the banner. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-banner-sub-title',
	'label'    => __( 'Banner - Sub Title', 'wp-simple' ),
	'section'  => 'fp_banner_customizer_options',
	'type'     => 'text',
	'priority' => 1,
	'default'  => '',
	'description'   => __( 'This is the smaller text in the banner. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-banner-button-text',
	'label'    => __( 'Banner - Button Text', 'wp-simple' ),
	'section'  => 'fp_banner_customizer_options',
	'type'     => 'text',
	'priority' => 1,
	'default'  => '',
	'description'   => __( 'This is the button in the banner. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-banner-button-url',
	'label'    => __( 'Banner - Button Destination URL', 'wp-simple' ),
	'section'  => 'fp_banner_customizer_options',
	'type'     => 'text',
	'priority' => 1,
	'default'  => '',
	'description'   => __( 'This is the button link destination in the banner.', 'wp-simple' ),
	'sanitize_callback' => 'wp_simple_sanitize_url'
) );


Kirki::add_section( 'sub_banner_options', array(
    'title'          => __( 'Subpage Banner Options', 'wp-simple' ),
    'description'    => '',
    'panel'          => 'banner_settings',
    'priority'       => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );


Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'sub-banner-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'sub_banner_options',
	'default'     => '#ea940d',
	'priority'    => 1,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.subpage-banner',
			'property' => 'background-color',
		),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'sub-banner-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'sub_banner_options',
	'default'     => '',
	'priority'    => 1,
) );

    
// Frontpage Featured

Kirki::add_section( 'fp-featured', array(
    'title'          => __( 'Frontpage Featured Section', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-featured-toggle',
	'label'       => __( 'Frontpage Featured Status', 'wp-simple' ),
	'section'     => 'fp-featured',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2' => esc_attr__( 'Demo', 'wp-simple' ),
		'3'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-featured-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-featured',
	'default'     => '#ffffff',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-featured',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-featured-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-featured',
	'default'     => '',
	'priority'    => 10,
) ); 

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-featured-title',
	'label'    => __( 'Featured - Main Title', 'wp-simple' ),
	'section'  => 'fp-featured',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the featured section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-featured-sub-title',
	'label'    => __( 'Featured - Sub Title', 'wp-simple' ),
	'section'  => 'fp-featured',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the smaller text in the featured section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'select',
	'settings'    => 'nimbus_left_featured',
	'label'       => __( 'Left Featured Page Column (from latest 50)', 'wp-simple' ),
	'section'     => 'fp-featured',
	'default'     => wp_simple_random_page(),
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => Kirki_Helper::get_posts( array( 'posts_per_page' => 50, 'post_type' => 'page' ) ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'select',
	'settings'    => 'nimbus_center_featured',
	'label'       => __( 'Center Featured Page Column (from latest 50)', 'wp-simple' ),
	'section'     => 'fp-featured',
	'default'     => wp_simple_random_page(),
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => Kirki_Helper::get_posts( array( 'posts_per_page' => 50, 'post_type' => 'page' ) ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'select',
	'settings'    => 'nimbus_right_featured',
	'label'       => __( 'Right Featured Page Column (from latest 50)', 'wp-simple' ),
	'section'     => 'fp-featured',
	'default'     => wp_simple_random_page(),
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => Kirki_Helper::get_posts( array( 'posts_per_page' => 50, 'post_type' => 'page' ) ),
) );


Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-featured-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-featured',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'featured',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );


// Action 1
            
Kirki::add_section( 'fp-action1', array(
    'title'          => __( 'Frontpage Action Row #1', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-action1-toggle',
	'label'       => __( 'Frontpage Action Row Status', 'wp-simple' ),
	'section'     => 'fp-action1',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2' => esc_attr__( 'Demo', 'wp-simple' ),
		'3'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-action1-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-action1',
	'default'     => '#4c5152',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-action1',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-action1-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-action1',
	'default'     => '',
	'priority'    => 10,
) ); 
            
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action1-title',
	'label'    => __( 'Action Row #1 - Main Title', 'wp-simple' ),
	'section'  => 'fp-action1',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the Action Row #1 section. Leave blank to hide.', 'wp-simple' ),
) );
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action1-sub-title',
	'label'    => __( 'Action Row #1 - Sub Title', 'wp-simple' ),
	'section'  => 'fp-action1',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the smaller text in the Action Row #1 section. Leave blank to hide.', 'wp-simple' ),
) );
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action1-button-text',
	'label'    => __( 'Action Row #1 - Button Text', 'wp-simple' ),
	'section'  => 'fp-action1',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the text in the button. Leave blank to hide.', 'wp-simple' ),
) );
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action1-button-url',
	'label'    => __( 'Action Row #1 - Button URL', 'wp-simple' ),
	'section'  => 'fp-action1',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is link destination for the button. Leave blank to hide.', 'wp-simple' ),
	'sanitize_callback' => 'wp_simple_sanitize_url'
) );        
            
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action1-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-action1',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'action1',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );
           
// About 

Kirki::add_section( 'fp-about', array(
    'title'          => __( 'Frontpage About Section', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-about-toggle',
	'label'       => __( 'About Status', 'wp-simple' ),
	'section'     => 'fp-about',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2' => esc_attr__( 'Demo', 'wp-simple' ),
		'3'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-about-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-about',
	'default'     => '#ffffff',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-about',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-about-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-about',
	'default'     => '',
	'priority'    => 10,
) ); 

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-about-title',
	'label'    => __( 'About - Main Title', 'wp-simple' ),
	'section'  => 'fp-about',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the about section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-about-sub-title',
	'label'    => __( 'About - Sub Title', 'wp-simple' ),
	'section'  => 'fp-about',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the smaller text in the about section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-about-description',
	'label'    => __( 'About - Description', 'wp-simple' ),
	'section'  => 'fp-about',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the smallest text in the about section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'custom',
	'settings'    => 'about-widget-note',
	'label'       => 'Populate About Content',
	'section'     => 'fp-about',
	'default'     => __( 'To populate the About content section, you will need to add About content widgets to the Frontpage About widget areas. Go to the Widgets section under Apperance in the left sidebar.', 'wp-simple' ),
	'priority'    => 10,
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-about-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-about',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'about',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );


// Action2

Kirki::add_section( 'fp-action2', array(
    'title'          => __( 'Frontpage Action Row #2', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-action2-toggle',
	'label'       => __( 'Frontpage Action Row #2 Status', 'wp-simple' ),
	'section'     => 'fp-action2',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2' => esc_attr__( 'Demo', 'wp-simple' ),
		'3'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-action2-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-action2',
	'default'     => '#4c5152',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-action2',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-action2-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-action2',
	'default'     => '',
	'priority'    => 10,
) ); 
            
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action2-title',
	'label'    => __( 'Action Row #2 - Main Title', 'wp-simple' ),
	'section'  => 'fp-action2',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the Action Row #2 section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action2-button-text',
	'label'    => __( 'Action Row #2 - Button Text', 'wp-simple' ),
	'section'  => 'fp-action2',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the text in the button. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action2-button-url',
	'label'    => __( 'Action Row #2 - Button URL', 'wp-simple' ),
	'section'  => 'fp-action2',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is link destination for the button. Leave blank to hide.', 'wp-simple' ),
	'sanitize_callback' => 'wp_simple_sanitize_url'
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-action2-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-action2',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'action2',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );
           
            
// Team

Kirki::add_section( 'fp-team', array(
    'title'          => __( 'Frontpage Team Section', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-team-toggle',
	'label'       => __( 'Team Status', 'wp-simple' ),
	'section'     => 'fp-team',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2' => esc_attr__( 'Demo', 'wp-simple' ),
		'3'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-team-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-team',
	'default'     => '#ffffff',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-team',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-team-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-team',
	'default'     => '',
	'priority'    => 10,
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-team-title',
	'label'    => __( 'Team - Main Title', 'wp-simple' ),
	'section'  => 'fp-team',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the team section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-team-sub-title',
	'label'    => __( 'Team - Sub Title', 'wp-simple' ),
	'section'  => 'fp-team',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the smaller text in the team section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'custom',
	'settings'    => 'team-widget-note',
	'label'       => 'Populate Team Content',
	'section'     => 'fp-team',
	'default'     => __( 'To populate the Team content section, you will need to add About content widgets to the Frontpage Team widget areas. Go to the Widgets section under Apperance in the left sidebar.', 'wp-simple' ),
	'priority'    => 10,
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-team-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-team',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'team',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );

// Social Media 

Kirki::add_section( 'fp-social', array(
    'title'          => __( 'Frontpage Social Media Section', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-social-toggle',
	'label'       => __( 'Social Status', 'wp-simple' ),
	'section'     => 'fp-social',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2' => esc_attr__( 'Demo', 'wp-simple' ),
		'3'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-social-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-social',
	'default'     => '#ffffff',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-social',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-social-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-social',
	'default'     => '',
	'priority'    => 10,
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-social-title',
	'label'    => __( 'Social - Main Title', 'wp-simple' ),
	'section'  => 'fp-social',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the social section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-social-sub-title',
	'label'    => __( 'Social - Sub Title', 'wp-simple' ),
	'section'  => 'fp-social',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the smaller text in the social section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'custom',
	'settings'    => 'social-widget-note',
	'label'       => __( 'Populate Social Meida Section Content', 'wp-simple' ),
	'section'     => 'fp-social',
	'default'     => __( 'To populate the Social Media section, you will need to add Social Meida widgets to the Social Media widget areas.  Go to the Widgets section under Apperance in the left sidebar.', 'wp-simple' ),
	'priority'    => 10,
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-social-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-social',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'social',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );
        
        
// Testimonial            
            
Kirki::add_section( 'fp-test', array(
    'title'          => __( 'Frontpage Testimonial Section', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-test-toggle',
	'label'       => __( 'Testimonial Status', 'wp-simple' ),
	'section'     => 'fp-test',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2' => esc_attr__( 'Demo', 'wp-simple' ),
		'3'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-test-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-test',
	'default'     => '#ffffff',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-test',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-test-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-test',
	'default'     => '',
	'priority'    => 10,
) );
        

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-test-image',
	'label'       => __( 'Testimonial Section Image', 'wp-simple' ),
	'section'     => 'fp-test',
	'default'     => '',
	'priority'    => 10,
	'description' => 'Upload an image of the individual being quoted in the testimonial. Ideally, this image should be 320x302px.' 
) );
        
            
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-test-title',
	'label'    => __( 'Testimonial - Main Title', 'wp-simple' ),
	'section'  => 'fp-test',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the testimonial section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'     => 'textarea',
	'settings' => 'fp-test-description',
	'label'    => __( 'Testimonial', 'wp-simple' ),
	'section'  => 'fp-test',
	'default'  => '',
	'priority' => 10,
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-test-tag',
	'label'    => __( 'Testimonial - Name', 'wp-simple' ),
	'section'  => 'fp-test',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the name under the testimonial section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-test-tag-url',
	'label'    => __( 'Testimonial - Website Link', 'wp-simple' ),
	'section'  => 'fp-test',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the link applied to the name above.', 'wp-simple' ),
	'sanitize_callback' => 'wp_simple_sanitize_url'
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-test-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-test',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'test',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );  
            
            
// News

Kirki::add_section( 'fp-news', array(
    'title'          => __( 'Frontpage Page Content Section', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'fp-news-toggle',
	'label'       => __( 'Content Row Status', 'wp-simple' ),
	'section'     => 'fp-news',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-news-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-news',
	'default'     => '#e7e7e7',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-news',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-news-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-news',
	'default'     => '',
	'priority'    => 10,
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-news-title',
	'label'    => __( 'Content - Main Title', 'wp-simple' ),
	'section'  => 'fp-news',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the news section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-news-sub-title',
	'label'    => __( 'Content - Sub Title', 'wp-simple' ),
	'section'  => 'fp-news',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the smaller text in the news section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-news-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-news',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'news',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );




// Contact

Kirki::add_section( 'fp-contact', array(
    'title'          => __( 'Frontpage Contact Section', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'contact-toggle',
	'label'       => __( 'Contact Status', 'wp-simple' ),
	'section'     => 'fp-contact',
	'default'     => '2',
	'priority'    => 1,
	'choices'     => array(
		'1'   => esc_attr__( 'Show', 'wp-simple' ),
		'2' => esc_attr__( 'Demo', 'wp-simple' ),
		'3'  => esc_attr__( 'Hide', 'wp-simple' ),
	),
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'color',
	'settings'    => 'fp-contact-background-color',
	'label'       => __( 'Row Background Color', 'wp-simple' ),
	'section'     => 'fp-contact',
	'default'     => '#ffffff',
	'priority'    => 10,
	'alpha'       => true,
	'description'   => __( 'Pick a background color for the row.', 'wp-simple' ),
	'output' => array(
		array(
			'element'  => '.frontpage-contact',
			'property' => 'background-color',
		),
	),
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'image',
	'settings'    => 'fp-contact-background-image',
	'label'       => __( 'Parallax Row Background', 'wp-simple' ),
	'section'     => 'fp-contact',
	'default'     => '',
	'priority'    => 10,
) );
            
Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-contact-title',
	'label'    => __( 'Contact - Main Title', 'wp-simple' ),
	'section'  => 'fp-contact',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the big text in the team section. Leave blank to hide.', 'wp-simple' ),
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-team-sub-title',
	'label'    => __( 'Contact - Sub Title', 'wp-simple' ),
	'section'  => 'fp-team',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'description'   => __( 'This is the smaller text in the team section. Leave blank to hide.', 'wp-simple' ),
) );


Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'contact-mailto',
	'label'    => __( 'Mailto Email', 'wp-simple' ),
	'section'  => 'fp-contact',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'sanitize_callback' => 'wp_simple_sanitize_email'
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'contact-mailfrom',
	'label'    => __( 'Mailfrom Email', 'wp-simple' ),
	'section'  => 'fp-contact',
	'type'     => 'text',
	'priority' => 10,
	'default'  => '',
	'sanitize_callback' => 'wp_simple_sanitize_email'
) );

Kirki::add_field( 'wp-simple-config', array(
	'settings' => 'fp-contact-slug',
	'label'    => __( 'Navigation Menu ID', 'wp-simple' ),
	'section'  => 'fp-contact',
	'type'     => 'text',
	'priority' => 10,
	'default'  => 'contact',
	'description'   => __( 'The frontpage section IDs (what shows up in the hover state and the address bar when clicked) have already been set to a default show in this field. If you would like to change the ID so that a different term comes up in the slug for that section (ie. http://example.com/#top instead of /#home), then change the term below for the corresponding section. You will also want to add the custom menu items in the Menus section of your dashboard (click "Links," then add the entire URL, such as http://example.com/#top). IMPORTANT: You must also add this term to the title field in the menu editor. If you do not see this field you may have to activate it by selecting the Screen Options tab in the top right of the page and then checking the Title Attribute box.', 'wp-simple' ),
) );
    
// Blog settings

Kirki::add_section( 'blog-settings', array(
    'title'          => __( 'Blog Settings', 'wp-simple' ),
    'description'    => '',
    'panel'          => '', 
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
) );

Kirki::add_field( 'wp-simple-config', array(
	'type'        => 'radio-buttonset',
	'settings'    => 'nimbus_blog_sidebar_position',
	'label'       => __( 'Blog Sidebar Position', 'wp-simple' ),
	'section'     => 'blog-settings',
	'default'     => 'right',
	'priority'    => 1,
	'choices'     => array(
		'right'   => esc_attr__( 'Right', 'wp-simple' ),
		'left'  => esc_attr__( 'Left', 'wp-simple' ),
	),
) );


// #################################################
// Some Custom Sanitize Functions
// #################################################

function wp_simple_sanitize_url( $value ) {

    $value=esc_url( $value );

    return $value;

}

function wp_simple_sanitize_email( $value ) {

    $value=sanitize_email( $value );

    return $value;

}

// #################################################
// Get a Random Page ID
// #################################################

function wp_simple_random_page(){
    $get_pages = get_pages();
    if(!empty($get_pages)) {
        shuffle($get_pages);
        $page = $get_pages[0]->ID;
    } else {
        $page = "";
    }
    return $page;
}