<?php

/* **************************************************************************************************** */
// Setup Theme
/* **************************************************************************************************** */

add_action('after_setup_theme', 'nimbus_setup');

if (!function_exists('nimbus_setup')){

    function nimbus_setup() {

       // Localization

        $lang_local = get_template_directory() . '/lang';
        load_theme_textdomain('wp-simple', $lang_local);

        // Register Thumbnail Sizes

        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1170, 9999, true);
        add_image_size('nimbus_750_500', 750, 500, true);

        // Load feed links

        add_theme_support('automatic-feed-links');

        // Support Custom Background

        $nimbus_custom_background_defaults = array(
            'default-color' => 'ffffff'
        );
        add_theme_support( 'custom-background', $nimbus_custom_background_defaults );
        
        // Set Content Width

        global $content_width;
        if ( ! isset( $content_width ) ) {
            $content_width = 720;
        }

        // Register Menus

        register_nav_menu('primary', __('Primary Menu', 'wp-simple' ));

        // Support title tag

        add_theme_support( "title-tag" );

        // Woo Support

        add_theme_support( 'woocommerce' );

    }
}


/* **************************************************************************************************** */
// Load Admin Panel
/* **************************************************************************************************** */


require_once(get_template_directory() . '/inc/kirki/kirki.php' );
require_once(get_template_directory() . '/inc/options.php' );
require_once(get_template_directory() . '/inc/pro/class-customize.php' );

// Duplicate and import any theme options from <= 2.0.4

add_action( 'wp_loaded', 'wp_simple_update_migration' );

function wp_simple_update_migration() {
    $mod=get_option( 'theme_mods_wp-simple' );
    if ($mod) {
        if (isset( $mod['nimbus_simple_options'] )) {
        	$newmod = array();
        	foreach ($mod['nimbus_simple_options'] as $key => $value) {
        		if (isset($value['url'])){
        			$value = $value['url'];
        		}
        		$newmod[$key] = $value;
        	}
        	add_option( 'theme_mods_wp-simple_backup', $mod, '', 'yes');
        	delete_option( 'theme_mods_wp-simple' );
            add_option( 'theme_mods_wp-simple', $newmod, '', 'yes');
        } 
    }
}

// Get Options
    
function nimbus_get_option($optionID, $default_data = false) {
    if (get_theme_mod( $optionID )) {
        return get_theme_mod( $optionID );   
    } else {
        return NULL;
    }
}


/* **************************************************************************************************** */
// Meta Boxes
/* **************************************************************************************************** */

require_once(get_template_directory() . '/inc/meta_boxes.php');


/* **************************************************************************************************** */
// Custom Widgets
/* **************************************************************************************************** */
 
require_once(get_template_directory() . '/inc/widgets.php');


/* **************************************************************************************************** */
// Custom NavWalker
/* **************************************************************************************************** */
 
require_once(get_template_directory() . '/inc/wp_bootstrap_navwalker.php');


/* **************************************************************************************************** */
// Modify Search Form
/* **************************************************************************************************** */

if (!function_exists('nimbus_modify_search_form')){
    function nimbus_modify_search_form($form) {
        $form = '<form method="get" id="searchform" action="' . home_url() . '/" >';
        if (is_search()) {
            $form .='<input type="text" value="' . esc_attr(apply_filters('the_search_query', get_search_query())) . '" name="s" id="s" />';
        } else {
            $form .='<input type="text" value="Search" name="s" id="s"  onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;"/>';
        }
        $form .= '<input type="image" id="searchsubmit" src="' . get_template_directory_uri() . '/assets/images/search_icon.png" />
                </form>';
        return $form;
    }
}
add_filter('get_search_form', 'nimbus_modify_search_form');


/* **************************************************************************************************** */
// Override gallery style
/* **************************************************************************************************** */

add_filter( 'use_default_gallery_style', '__return_false' );



/* **************************************************************************************************** */
// Register Sidebars
/* **************************************************************************************************** */

add_action('widgets_init', 'nimbus_register_sidebars');

if (!function_exists('nimbus_register_sidebars')){
    function nimbus_register_sidebars() {
       
       
    
    // frontpage - about
    register_sidebar(array(
        'id' => 'frontpage-about-left',
        'name' => __('Frontpage About Left', 'wp-simple' ),
        'before_widget' => '<div class="col-sm-4" data-sr="wait 0.3s, enter right and move 50px after 1s">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>'
    ));
    
    register_sidebar(array(
        'id' => 'frontpage-about-center',
        'name' => __('Frontpage About Center', 'wp-simple' ),
        'before_widget' => '<div class="col-sm-4" data-sr="wait 0.3s, enter right and move 50px after 1s">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>'
    ));
    
    
    register_sidebar(array(
        'id' => 'frontpage-about-right',
        'name' => __('Frontpage About Right', 'wp-simple' ),
        'before_widget' => '<div class="col-sm-4" data-sr="wait 0.3s, enter right and move 50px after 1s">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>'
    ));
    
    // frontpage - team - left
    register_sidebar(array(
        'id' => 'frontpage-team-left',
        'name' => __('Frontpage Team Left', 'wp-simple' ),
        'before_widget' => '<div class="team-item" data-sr="wait 0.3s, enter right and move 50px after 1s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="team-item-title">',
        'after_title' => '</h4>'
    ));

    // frontpage - team - center left
    register_sidebar(array(
        'id' => 'frontpage-team-center-left',
        'name' => __('Frontpage Team Center Left', 'wp-simple' ),
        'before_widget' => '<div class="team-item" data-sr="wait 0.3s, enter right and move 50px after 1s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="team-item-title">',
        'after_title' => '</h4>'
    ));
    
    // frontpage - team - center right
    register_sidebar(array(
        'id' => 'frontpage-team-center-right',
        'name' => __('Frontpage Team Center Right', 'wp-simple' ),
        'before_widget' => '<div class="team-item" data-sr="wait 0.3s, enter right and move 50px after 1s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="team-item-title">',
        'after_title' => '</h4>'
    ));
    
    // frontpage - team - right
    register_sidebar(array(
        'id' => 'frontpage-team-right',
        'name' => __('Frontpage Team Right', 'wp-simple' ),
        'before_widget' => '<div class="team-item" data-sr="wait 0.3s, enter right and move 50px after 1s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="team-item-title">',
        'after_title' => '</h4>'
    ));    
    
    // frontpage - social
    register_sidebar(array(
        'id' => 'frontpage-social-media',
        'name' => __('Frontpage Social Media', 'wp-simple' ),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
        
    register_sidebar(array(
        'name' => __('Default Page Sidebar', 'wp-simple' ),
        'id' => 'sidebar_pages',
        'description' => __('Widgets in this area will be displayed in the sidebar on the pages.', 'wp-simple' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s widget sidebar_widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget_title">',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => __('Default Blog Sidebar', 'wp-simple' ),
        'id' => 'sidebar_blog',
        'description' => __('Widgets in this area will be displayed in the sidebar on the blog and posts.', 'wp-simple' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s widget sidebar_widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget_title">',
        'after_title' => '</h3>'
    ));    
        
        // create 50 alternate sidebar widget areas for use on post and pages
        $i = 1;
        while ($i <= 10) {
            register_sidebar(array(
                'name' => __('Alternate Sidebar #', 'wp-simple' ) . $i,
                'id' => 'sidebar_' . $i,
                'description' => __('Widgets in this area will be displayed in the sidebar for any posts, pages or portfolio items that are taged with sidebar', 'wp-simple' ) . $i . '.',
                'before_widget' => '<div class="widget">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget_title">',
                'after_title' => '</h3>'
            ));
            $i++;
        }
    }
}

/* **************************************************************************************************** */
// Excerpt Modifications
/* **************************************************************************************************** */

// Excerpt Length

add_filter('excerpt_length', 'nimbus_excerpt_length');
if (!function_exists('nimbus_excerpt_length')){
    function nimbus_excerpt_length($length) {
        return 40;
    }
}

// Excerpt More

add_filter('excerpt_more', 'nimbus_excerpt_more');

if (!function_exists('nimbus_excerpt_more')){
    function nimbus_excerpt_more($more) {
        return '';
    }
}

// Add to pages

add_action('init', 'nimbus_add_excerpts_to_pages');

if (!function_exists('nimbus_add_excerpts_to_pages')){
    function nimbus_add_excerpts_to_pages() {
        add_post_type_support('page', 'excerpt');
    }
}


// Get my ID

function nimbus_get_the_excerpt_by_id($post_id) {
  global $post;
  $save_post = $post;
  $post = get_post($post_id);
  $output = get_the_excerpt();
  $post = $save_post;
  return $output;
}

/* **************************************************************************************************** */
// Enable Threaded Comments
/* **************************************************************************************************** */

add_action('wp_enqueue_scripts', 'nimbus_threaded_comments');

function nimbus_threaded_comments() {
    if (is_singular() && comments_open() && (get_option('thread_comments') == 1)) {
        wp_enqueue_script('comment-reply');
    }
}

/* **************************************************************************************************** */
// Modify Comments Output
/* **************************************************************************************************** */

if (!function_exists('nimbus_comment')){
    function nimbus_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        ?>
        <li <?php comment_class(); ?> id="li_comment_<?php comment_ID() ?>">
            <div id="comment_<?php comment_ID(); ?>" class="comment_wrap clearfix">
                <?php echo get_avatar($comment, $size = '75'); ?>
                <div class="comment_content">
                    <p class="left"><strong><?php comment_author_link(); ?></strong><br />
                    <?php echo(get_comment_date()) ?> <?php edit_comment_link(__('(Edit)', 'wp-simple' ), '  ', '') ?></p>
                    <p class="right"><?php comment_reply_link(array_merge($args, array('reply_text' => __('Leave a Reply', 'wp-simple' ), 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></p>
                    <div class="clear"></div>
                    <?php
                    if ($comment->comment_approved == '0') {
                    ?>
                        <em><?php _e('Your comment is awaiting moderation.', 'wp-simple' ) ?></em>
                    <?php
                    }
                    ?>
                    <?php comment_text() ?>
                </div>
                <div class="clear"></div>
            </div>
    <?php
    }
}


/* **************************************************************************************************** */
// Modify Ping Output
/* **************************************************************************************************** */

if (!function_exists('nimbus_ping')){
    function nimbus_ping($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        ?>
        <li id="comment_<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
    <?php
    }
}


/* **************************************************************************************************** */
// Modify Comment Text Fields
/* **************************************************************************************************** */

add_filter('comment_form_default_fields', 'nimbus_comment_fields');

if (!function_exists('nimbus_comment_fields')){
    function nimbus_comment_fields($fields) {

        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ( $req ? " aria-required='true'" : '' );

        $fields['author'] = '<div class="row"><div class="col-md-4 comment_fields"><p class="comment-form-author">' . '<label for="author">' . __('Name', 'wp-simple' ) . '</label> ' . ( $req ? '<span class="required">*</span><br />' : '' ) . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>';
        $fields['email'] = '<p class="comment-form-email"><label for="email">' . __('Email', 'wp-simple' ) . '</label> ' . ( $req ? '<span class="required">*</span><br />' : '' ) . '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>';
        $fields['url'] = '<p class="comment-form-url"><label for="url">' . __('Website', 'wp-simple' ) . '</label><br />' . '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p></div> ';

        return $fields;
    }
}


/* **************************************************************************************************** */
// Modify Avatar Classes
/* **************************************************************************************************** */

add_filter('get_avatar','nimbus_avatar_class');

if (!function_exists('nimbus_avatar_class')){
    function nimbus_avatar_class($class) {
        $class = str_replace("class='avatar", "class='avatar img-responsive", $class) ;
        return $class;
    }
}

/* **************************************************************************************************** */
// Add Post Link Classes
/* **************************************************************************************************** */

add_filter('next_post_link', 'nimbus_posts_link_next_class');

if (!function_exists('nimbus_posts_link_next_class')){
    function nimbus_posts_link_next_class($format){
         $format = str_replace('href=', 'class="post_next btn" href=', $format);
         return $format;
    }
}

add_filter('previous_post_link', 'nimbus_posts_link_prev_class');

if (!function_exists('nimbus_posts_link_prev_class')){
    function nimbus_posts_link_prev_class($format) {
         $format = str_replace('href=', 'class="post_prev btn" href=', $format);
         return $format;
    }
}

/* **************************************************************************************************** */
// Add next_posts Link Classes
/* **************************************************************************************************** */

add_filter('next_posts_link_attributes', 'nimbus_posts_link_class');
add_filter('previous_posts_link_attributes', 'nimbus_posts_link_class');

if (!function_exists('nimbus_posts_link_class')){
    function nimbus_posts_link_class() {
        return 'class="btn"';
    }
}

/* **************************************************************************************************** */
// Add Image Classes ##Look for way to apply to exsisting
/* **************************************************************************************************** */

add_filter('get_image_tag_class','nimbus_add_image_class');

if (!function_exists('nimbus_add_image_class')){
    function nimbus_add_image_class($class){
        $class .= ' img-responsive';
        return $class;
    }
}

/* **************************************************************************************************** */
// Load Public Scripts
/* **************************************************************************************************** */

add_action('wp_enqueue_scripts', 'nimbus_public_scripts');

if (!function_exists('nimbus_public_scripts')){
    function nimbus_public_scripts() {
        if (!is_admin()) {
            wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '2.2.2', true);
            wp_enqueue_script('jquery-waypoints', get_template_directory_uri() . '/assets/js/jquery.waypoints.min.js', array('jquery'), '2.2.2', true);
            wp_enqueue_script('nicescroll', get_template_directory_uri() . '/assets/js/nicescroll.min.js', array('jquery'), '3.6.0', true);
            wp_enqueue_script('parallax',get_template_directory_uri() . '/assets/js/parallax.min.js',array('jquery'),'1.3.1',true);
            wp_enqueue_script('scrollreveal',get_template_directory_uri() . '/assets/js/scrollReveal.min.js',array('jquery'),'2.3.2',true);
            wp_enqueue_script('jquery-easing',get_template_directory_uri() . '/assets/js/jquery.easing.min.js',array('jquery'),'1.3.0',true);
            wp_enqueue_script('wp_simple_public', get_template_directory_uri() . '/assets/js/public.js', array('jquery'), '2.0.0', true);
            wp_enqueue_script('html5shiv',get_template_directory_uri() . '/assets/js/html5shiv.js',array(),'3.6.2',false);
            wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
            wp_enqueue_script('respond',get_template_directory_uri() . '/assets/js/respond.min.js',array(),'1.3.0',false);
            wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );
        }
    }
}


/* **************************************************************************************************** */
// Load Public CSS
/* **************************************************************************************************** */

add_action('wp_enqueue_scripts', 'nimbus_public_styles', 1 );

if (!function_exists('nimbus_public_styles')){
    function nimbus_public_styles() {
        if (!is_admin()) {
            wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '1.0', 'all');
            wp_enqueue_style( 'wp_simple_bootstrap_fix', get_template_directory_uri() . '/assets/css/bootstrap-fix.css', array(), '1.0', 'all');
            wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '1.0', 'all');
            wp_enqueue_style( 'wp_simple_font-lato', '//fonts.googleapis.com/css?family=Lato:300,400,700,900', array(), '1.0', 'all');
            wp_enqueue_style( 'wp_simple_nimbus-style', get_bloginfo( 'stylesheet_url' ), false, get_bloginfo('version') );
        }
    }
}



/* **************************************************************************************************** */
// Clear Helper/s
/* **************************************************************************************************** */

function nimbus_clear($ht = "0") {
echo "<div class='clear' style='height:" . $ht . "px;'></div>";
}



/* **************************************************************************************************** */
// WooCommerce Support
/* **************************************************************************************************** */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'nimbus_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'nimbus_wrapper_end', 10);

function nimbus_wrapper_start() {
  echo '<div class="container"><div class="content row"><div class="col-xs-12 content-column">';
}

function nimbus_wrapper_end() {
  echo '</div></div></div>';
}



/* **************************************************************************************************** */
// Scrollto in footer
/* **************************************************************************************************** */

add_action('wp_footer', 'nimbus_contact_js', 99);

function nimbus_contact_js() {
    global $post;
    if(isset($_POST['submitted'])) { ?>
    	<script>
    	    var offset = jQuery("#<?php if (nimbus_get_option('fp-contact-slug')=='') {echo 'contact';} else {echo esc_attr(nimbus_get_option('fp-contact-slug'));} ?>").height();
            jQuery('html, body').delay( 1000 ).stop().animate({
                scrollTop: jQuery("#<?php if (nimbus_get_option('fp-contact-slug')=='') {echo 'contact';} else {echo esc_attr(nimbus_get_option('fp-contact-slug'));} ?>").offset().top + offset
            }, 1000, 'easeInOutQuad');
        </script>
    <?php }
}