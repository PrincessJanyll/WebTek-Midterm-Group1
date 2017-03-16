<?php  
/**
 * Study Circle functions and definitions
 *
 * @package Study Circle
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */

if ( ! function_exists( 'study_circle_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function study_circle_setup() { 
	global $content_width;
	if ( ! isset( $content_width ) )
		$content_width = 640; /* pixels */

	load_theme_textdomain( 'study-circle', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('woocommerce');
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-header', array( 
		'default-text-color' => false,
		'header-text' => false,
	) );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 100,
		'flex-height' => true,
	) );
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'study-circle' ),
		'footer' => __( 'Footer Menu', 'study-circle' ),
	) );
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff'
	) );
	add_editor_style( 'editor-style.css' );
} 
endif; // study_circle_setup
add_action( 'after_setup_theme', 'study_circle_setup' );

function study_circle_widgets_init() { 	
	
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'study-circle' ),
		'description'   => __( 'Appears on blog page sidebar', 'study-circle' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	
	
	register_sidebar( array(
		'name'          => __( 'Footer Widget 1', 'study-circle' ),
		'description'   => __( 'Appears on footer', 'study-circle' ),
		'id'            => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="cols-4 widget-column-1 %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Widget 2', 'study-circle' ),
		'description'   => __( 'Appears on footer', 'study-circle' ),
		'id'            => 'footer-2',
		'before_widget' => '<aside id="%1$s" class="cols-4 widget-column-2 %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Widget 3', 'study-circle' ),
		'description'   => __( 'Appears on footer', 'study-circle' ),
		'id'            => 'footer-3',
		'before_widget' => '<aside id="%1$s" class="cols-4 widget-column-3 %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footer Widget 4', 'study-circle' ),
		'description'   => __( 'Appears on footer', 'study-circle' ),
		'id'            => 'footer-4',
		'before_widget' => '<aside id="%1$s" class="cols-4 widget-column-4 %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5>',
		'after_title'   => '</h5>',
	) );	
	
}
add_action( 'widgets_init', 'study_circle_widgets_init' );


function study_circle_font_url(){
		$font_url = '';		
		
		/* Translators: If there are any character that are not
		* supported by Montserrat, trsnalate this to off, do not
		* translate into your own language.
		*/
		$montserrat = _x('on','montserrat:on or off','study-circle');		
			
		if('off' !== $montserrat ){
			$font_family = array();
			
			if('off' !== $montserrat){
				$font_family[] = 'Montserrat:300,400,600,700,800,900';
			}
					
						
			$query_args = array(
				'family'	=> urlencode(implode('|',$font_family)),
			);
			
			$font_url = add_query_arg($query_args,'//fonts.googleapis.com/css');
		}
		
	return $font_url;
	}


function study_circle_scripts() {
	wp_enqueue_style('study-circle-font', study_circle_font_url(), array());
	wp_enqueue_style( 'study-circle-basic-style', get_stylesheet_uri() );
	wp_enqueue_style( 'nivo-slider', get_template_directory_uri()."/css/nivo-slider.css" );
	wp_enqueue_style( 'study-circle-responsive', get_template_directory_uri()."/css/responsive.css" );		
	wp_enqueue_style( 'study-circle-default', get_template_directory_uri()."/css/default.css" );
	wp_enqueue_script( 'jquery-nivo-slider', get_template_directory_uri() . '/js/jquery.nivo.slider.js', array('jquery') );
	wp_enqueue_script( 'study-circle-custom', get_template_directory_uri() . '/js/custom.js' );
	wp_enqueue_style( 'animation', get_template_directory_uri()."/css/animation.css" );	

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'study_circle_scripts' );

function study_circle_ie_stylesheet(){

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style('study-circle-ie', get_template_directory_uri().'/css/ie.css', array( 'study-circle-style' ), '20160928' );
	wp_style_add_data('study-circle-ie','conditional','lt IE 10');
	
	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'study-circle-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'study-circle-style' ), '20160928' );
	wp_style_add_data( 'study-circle-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'study-circle-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'study-circle-style' ), '20160928' );
	wp_style_add_data( 'study-circle-ie7', 'conditional', 'lt IE 8' );	

	}
	
add_action('wp_enqueue_scripts','study_circle_ie_stylesheet');


define('STUDY_CIRCLE_LIVE_DEMO','https://gracethemes.com/demo/studycircle/','study-circle');
define('STUDY_CIRCLE_PROTHEME_URL','https://gracethemes.com/themes/education-wordpress-theme/','study-circle');
define('STUDY_CIRCLE_THEME_DOC','https://gracethemes.com/documentation/studycircle-doc/','study-circle');


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom template for about theme.
 */
require get_template_directory() . '/inc/about-themes.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


if ( ! function_exists( 'study_circle_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 *
 */
function study_circle_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;