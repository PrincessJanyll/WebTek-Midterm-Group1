<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Restaurant_and_Cafe
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function restaurant_and_cafe_body_classes( $classes ) {
  $restaurant_and_cafe_ed_slider = get_theme_mod( 'restaurant_and_cafe_ed_slider' ); 

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

  // Adds a class of custom-background-image to sites with a custom background image.
  if ( get_background_image() ) {
    $classes[] = 'custom-background-image';
  }
    
  // Adds a class of custom-background-color to sites with a custom background color.
  if ( get_background_color() != 'ffffff' ) {
    $classes[] = 'custom-background-color';
  }

  if( !( is_active_sidebar( 'right-sidebar' ) ) || is_page_template( 'template-home.php' ) ) {
    $classes[] = 'full-width'; 

  }elseif( is_page() ){

    $restaurant_and_cafe_post_class = restaurant_and_cafe_sidebar_layout();      
    if( $restaurant_and_cafe_post_class == 'no-sidebar' )
      $classes[] = 'full-width';

  }else{
    $classes[] = '';
  }

	return $classes;
}
add_filter( 'body_class', 'restaurant_and_cafe_body_classes' );

/**
 * Custom Bread Crumb
 *
 * @link http://www.qualitytuts.com/wordpress-custom-breadcrumbs-without-plugin/
 */
 
function restaurant_and_cafe_breadcrumbs_cb() {
 
  if( get_theme_mod( 'restaurant_and_cafe_ed_breadcrumb' ) && ! is_404() ){ 
        
    global $post;
    
    $post_page   = get_option( 'page_for_posts' ); //The ID of the page that displays posts.
    $show_front  = get_option( 'show_on_front' ); //What to show on the front page
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter   = get_theme_mod( 'restaurant_and_cafe_breadcrumb_separator', __( '>', 'restaurant-and-cafe' ) ); // delimiter between crumbs
    $home        = get_theme_mod( 'restaurant_and_cafe_breadcrumb_home_text', __( 'Home', 'restaurant-and-cafe' ) ); // text for the 'Home' link
    $showCurrent = get_theme_mod( 'restaurant_and_cafe_ed_current', '1' ); // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before      = '<span class="current">'; // tag before the current crumb
    $after       = '</span>'; // tag after the current crumb        
 
    if( is_front_page() ){
    
        if( $showOnHome == 1 ) echo '<div id="crumbs"><div class="container"><a href="' . esc_url( home_url() ) . '">' . esc_html( $home ) . '</a></div></div>';
    
    }else{
 
        echo '<div id="crumbs"><div class="container"><a href="' . esc_url( home_url() ) . '">' . esc_html( $home ) . '</a> ';
        
        if( is_home() ){
            
            if( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( single_post_title( '', false ) ) . $after;
                          
        }elseif( is_category() ){
            
            $thisCat    = get_category( get_query_var( 'cat' ), false );
            
            if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                $p = get_post( $post_page );
                echo esc_html( $delimiter ) . ' <a href="' . esc_url( get_permalink( $post_page ) ) . '">' . esc_html( $p->post_title ) . '</a> ';  
            }      
            
            if ( $thisCat->parent != 0 ) echo get_category_parents( $thisCat->parent, TRUE, ' ' . $delimiter . ' ' );
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before .  esc_html( single_cat_title( '', false ) ) . $after;
            
     
        }elseif( restaurant_and_cafe_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ){ //For Woocommerce archive page
    
            $current_term = $GLOBALS['wp_query']->get_queried_object();
            if( is_product_category() ){
                $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                $ancestors = array_reverse( $ancestors );
        		foreach ( $ancestors as $ancestor ) {
        			$ancestor = get_term( $ancestor, 'product_cat' );    
        			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
        				echo esc_html( $delimiter ) . ' <a href="' . esc_url( get_term_link( $ancestor ) ) . '">' . esc_html( $ancestor->name ) . '</a> ';
        			}
        		}
            }           
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( $current_term->name ) . $after;
            
        } elseif( restaurant_and_cafe_woocommerce_activated() && is_shop() ){ //Shop Archive page
            if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
    			return;
    		}
    		$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
    
    		if ( ! $_name ) {
    			$product_post_type = get_post_type_object( 'product' );
    			$_name = $product_post_type->labels->singular_name;
    		}
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( $_name ) . $after;
            
        }elseif( is_tag() ){
            
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( single_tag_title( '', false ) ) . $after;
     
        }elseif( is_author() ){
            
            global $author;
            $userdata = get_userdata( $author );
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( $userdata->display_name ) . $after;
     
        }elseif( is_search() ){
            
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html__( 'Search Result', 'restaurant-and-cafe' ) . $after;
     
        }elseif( is_day() ){
            
            echo esc_html( $delimiter ) . ' <a href="' . esc_url( get_year_link( get_the_time('Y') ) ) . '">' . esc_html( get_the_time('Y') ) . '</a> ';
            echo esc_html( $delimiter ) . ' <a href="' . esc_url( get_month_link( get_the_time('Y'), get_the_time('m') ) ) . '">' . esc_html( get_the_time('F') ) . '</a> ';
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' .  $before . esc_html( get_the_time('d') ) . $after;
     
        }elseif( is_month() ){
            
            echo esc_html( $delimiter ) . ' <a href="' . esc_url( get_year_link( get_the_time('Y') ) ) . '">' . esc_html( get_the_time('Y') ) . '</a> ';
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( get_the_time('F') ) . $after;
     
        }elseif( is_year() ){
            
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( get_the_time('Y') ) . $after;
     
        }elseif( is_single() && !is_attachment() ){
            
            if( restaurant_and_cafe_woocommerce_activated() && 'product' === get_post_type() ){ //For Woocommerce single product
        		/** NEED TO CHECK THIS PORTION WHILE INTEGRATION WITH WOOCOMMERCE */
                if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
        			$main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
        			$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                    $ancestors = array_reverse( $ancestors );
            		foreach ( $ancestors as $ancestor ) {
            			$ancestor = get_term( $ancestor, 'product_cat' );    
            			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
            				echo esc_html( $delimiter ) . ' <a href="' . esc_url( get_term_link( $ancestor ) ) . '">' . esc_html( $ancestor->name ) . '</a> ';
            			}
            		}
        			echo esc_html( $delimiter ) . ' <a href="' . esc_url( get_term_link( $main_term ) ) . '">' . esc_html( $main_term->name ) . '</a> ';
        		}
                
                if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( get_the_title() ) . $after;
                
            }elseif ( get_post_type() != 'post' ) {
                    $post_type = get_post_type_object(get_post_type());
                    if( $post_type->has_archive == true ){
                        $slug = $post_type->rewrite;
                        echo '<a href="' . esc_url( $homeLink . '/' . $slug['slug'] ) . '/">' . esc_html( $post_type->labels->singular_name ) . '</a> <span class="separator">' . esc_html( $delimiter ) . '</span>';
                    }
                    if ( $showCurrent == 1 ) echo $before . esc_html( get_the_title() ) . $after;
                } else { //For Post
                
                $cat_object       = get_the_category();
                $potential_parent = 0;
                
                if( $show_front === 'page' && $post_page ){ //If static blog post page is set
                    $p = get_post( $post_page );
                    echo esc_html( $delimiter ) . ' <a href="' . esc_url( get_permalink( $post_page ) ) . '">' . esc_html( $p->post_title ) . '</a> ';  
                }
                
                if( is_array( $cat_object ) ){ //Getting category hierarchy if any
        
        			//Now try to find the deepest term of those that we know of
        			$use_term = key( $cat_object );
        			foreach( $cat_object as $key => $object )
        			{
        				//Can't use the next($cat_object) trick since order is unknown
        				if( $object->parent > 0  && ( $potential_parent === 0 || $object->parent === $potential_parent ) ){
        					$use_term = $key;
        					$potential_parent = $object->term_id;
        				}
        			}
                    
        			$cat = $cat_object[$use_term];
              
                    $cats = get_category_parents( $cat, TRUE, ' ' . esc_html( $delimiter ) . ' ' );
                    if ( $showCurrent == 0 ) $cats = preg_replace( "#^(.+)\s$delimiter\s$#", "$1", $cats );
                    echo $delimiter . ' ' . $cats;
                }
    
                if ( $showCurrent == 1 ) echo $before . esc_html( get_the_title() ) . $after;
            }
        
        }elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ){
            
            $post_type = get_post_type_object(get_post_type());
            echo esc_html( $delimiter ) . ' ' . $before . esc_html( $post_type->labels->singular_name ) . $after;
     
        }elseif ( is_attachment() ){
            
            $parent = get_post( $post->post_parent );
            $cat = get_the_category( $parent->ID );
            if( $cat ){
                $cat = $cat[0];
                echo esc_html( $delimiter ) . ' ' . get_category_parents( $cat, TRUE, ' ' . $delimiter . ' ' );
                echo '<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( $parent->post_title ) . '</a>';
            }
            if ( $showCurrent == 1 ) echo ' ' . $delimiter . ' ' . $before . esc_html( get_the_title() ) . $after;
     
        } elseif ( is_page() && !$post->post_parent ) {
            
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( get_the_title() ) . $after;
     
        } elseif ( is_page() && $post->post_parent ) {
            
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            
            while( $parent_id ){
                $page = get_post( $parent_id );
                $breadcrumbs[] = esc_html( $delimiter ) . ' <a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a> ';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse( $breadcrumbs );
            for( $i = 0; $i < count( $breadcrumbs ); $i++ ){
                echo $breadcrumbs[$i];
            }
            if ( $showCurrent == 1 ) echo esc_html( $delimiter ) . ' ' . $before . esc_html( get_the_title() ) . $after;
     
        } 
        
        if ( get_query_var('paged') && ( $showCurrent == 1 ) ) echo __( ' (Page', 'restaurant-and-cafe' ) . ' ' . get_query_var('paged') . __( ')', 'restaurant-and-cafe' );
     
        echo '</div></div>';
 
    }
}
} 
add_action( 'restaurant_and_cafe_breadcrumbs', 'restaurant_and_cafe_breadcrumbs_cb' );


/** 
 * Hook to move comment text field to the bottom in WP 4.4 
 *
 * @link http://www.wpbeginner.com/wp-tutorials/how-to-move-comment-text-field-to-bottom-in-wordpress-4-4/  
 */
function restaurant_and_cafe_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}

add_filter( 'comment_form_fields', 'restaurant_and_cafe_move_comment_field_to_bottom' );

/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
 
 function restaurant_and_cafe_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	
    <footer class="comment-meta">
    
        <div class="comment-author vcard">
    	<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
    	<?php printf( __( '<b class="fn">%s</b>', 'restaurant-and-cafe' ), get_comment_author_link() ); ?>
    	</div>
    	<?php if ( $comment->comment_approved == '0' ) : ?>
    		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'restaurant-and-cafe' ); ?></em>
    		<br />
    	<?php endif; ?>
    
    	<div class="comment-metadata commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><time datetime="<?php comment_date(); ?>">
    		<?php
    			/* translators: 1: date, 2: time */
    			printf( __( '%s', 'restaurant-and-cafe' ), get_comment_date() ); ?></time></a><?php edit_comment_link( __( '(Edit)', 'restaurant-and-cafe' ), '  ', '' );
    		?>
    	</div>
    </footer>
    
    <div class="comment-content"><?php comment_text(); ?></div>

	<div class="reply">
	<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}

/** Function to get Sections */
function restaurant_and_cafe_get_sections(){

$restaurant_and_cafe_sections = array( 

    'featured-section' => array(
        'class' => 'section-1',
        'id'    => 'featured'    
    ),
    
    'about-section' => array(
        'class' => 'section-2',
        'id'    => 'about'    
    ),

    'services-section' => array(
        'class' => 'section-3',
        'id'    => 'service'    
    ),
    
    'testimonial-section' => array(
        'class' => 'section-4',
        'id'    => 'testimonial'    
    ),

    'tabmenu-section' => array(
        'class' => 'section-5',
        'id'    => 'tabmenu'    
    ),
    
    'reservation-section' => array(
        'class' => 'section-6',
        'id'    => 'reservation'    
    ),

    'blog-section' => array(
        'class' => 'blog-section',
        'id'    => 'blog'
    )
      
);


$restaurant_and_cafe_enabled_section = array();
foreach ( $restaurant_and_cafe_sections as $restaurant_and_cafe_section ) {
    
    if ( esc_attr( get_theme_mod( 'restaurant_and_cafe_ed_' . $restaurant_and_cafe_section['id'] . '_section' ) ) == 1 ){
        $restaurant_and_cafe_enabled_section[] = array(
            'id' => $restaurant_and_cafe_section['id'],
            'class' => $restaurant_and_cafe_section['class']
        );
    }
}
return $restaurant_and_cafe_enabled_section;
}


/** CallBack function for Banner */
function restaurant_and_cafe_banner_cb(){
$restaurant_and_cafe_ed_banner_section = get_theme_mod( 'restaurant_and_cafe_ed_banner_section' );
$restaurant_and_cafe_banner_post = get_theme_mod( 'restaurant_and_cafe_banner_post' );
$restaurant_and_cafe_banner_read_more = get_theme_mod( 'restaurant_and_cafe_banner_read_more',  __( 'Get Started', 'restaurant-and-cafe' ) );
$restaurant_and_cafe_enabled_sections = restaurant_and_cafe_get_sections();

$banner_class = '';
if( !is_page_template( 'template-home.php' ) || !$restaurant_and_cafe_ed_banner_section ) $banner_class = ' banner-inner';

?>
<div class="banner">
    <?php 
        if( $restaurant_and_cafe_ed_banner_section && is_page_template( 'template-home.php' ) ){
            
            $banner_qry = new WP_Query( array( 'p' => $restaurant_and_cafe_banner_post ) );
            
            if( $banner_qry->have_posts() ){
                while( $banner_qry->have_posts() ){
                    $banner_qry->the_post();
                    $categories_list = get_the_category_list( esc_html__( ', ', 'restaurant-and-cafe' ) );
                    if( has_post_thumbnail() ){
                        the_post_thumbnail( 'restaurant-and-cafe-banner' );
                    ?>
                    <div class="banner-text">
                    <div class="container">
                    <div class="text">
                      <strong class="title"><?php the_title(); ?></strong>
                      <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?> " class="btn-green"><?php echo esc_html( $restaurant_and_cafe_banner_read_more ); ?></a>
                    </div>
                    </div>
                    </div>
                  <div class="btn-scroll-down"><span><?php esc_html_e('scroll Down','restaurant-and-cafe'); ?></span></div><div id="next_section"></div>
                    <?php
                    }
                }
                wp_reset_postdata();
            }
            
        }
    ?>
</div>
<?php
}

add_action( 'restaurant_and_cafe_banner', 'restaurant_and_cafe_banner_cb' );

function restaurant_and_cafe_author_info_box_cb( ) {
    if( get_the_author_meta( 'description' ) ){
        global $post;
    ?>
    <section class="author">
        <div class="img-holder"><?php echo get_avatar( get_the_author_meta( 'ID' ), 126 ); ?></div>
            <div class="text-holder">
                <strong class="name"><?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?></strong>
                <?php echo wpautop( esc_html( get_the_author_meta( 'description' ) ) ); ?>
            </div>
    </section>
    <?php  
    }  
}
add_action( 'restaurant_and_cafe_author_info_box', 'restaurant_and_cafe_author_info_box_cb' );

/** Callback Function for about Block */
function restaurant_and_cafe_about_cb(){

$restaurant_and_cafe_about_section_bg = get_theme_mod('restaurant_and_cafe_about_section_bg');
$restaurant_and_cafe_about_section_page = get_theme_mod( 'restaurant_and_cafe_about_section_page' );
    

    ?>
    <section class="section-2" id="<?php echo esc_html( 'about' ); ?>" <?php if( $restaurant_and_cafe_about_section_bg ) echo 'style="background: url(' . esc_url( $restaurant_and_cafe_about_section_bg ) . '); background-size: cover; background-repeat: no-repeat; background-position: center;"';?> >
<?php
 if($restaurant_and_cafe_about_section_page){

  $about_qry = new WP_Query( array( 
                    'post_type'             => 'page',
                    'post__in'              => array( $restaurant_and_cafe_about_section_page ),
                    'post_status'           => 'publish',
                    'posts_per_page'        => -1,
                    'ignore_sticky_posts'   => true ) );

 ?>

  <div class="container">
    <div class="holder">
    <?php
      if( $about_qry->have_posts() ){                
                    while( $about_qry->have_posts() ){
                        $about_qry->the_post(); ?>
            <div class="row">
               <div class="col">
                <div class="images">                    
                <?php 
                    if( has_post_thumbnail() ){
                        the_post_thumbnail( 'restaurant-and-cafe-about-section' ); 
                    }
                ?>
                </div>
                 </div>
              <div class="col">
                <div class="text-holder">
                <?php
                   the_title( '<h2 class="main-title">', '</h2>' );  
                       the_excerpt();
                    ?>
                </div>
              </div>
            </div>
            <?php } 

            wp_reset_postdata();

          } ?>
    </div>
  </div>
<?php } ?>
</section>
<?php
 } 
 add_action( 'restaurant_and_cafe_about', 'restaurant_and_cafe_about_cb' ); 

/**
 * Return sidebar layouts for pages
*/
function restaurant_and_cafe_sidebar_layout(){
    global $post;
    
    if( get_post_meta( $post->ID, 'restaurant_and_cafe_sidebar_layout', true ) ){
        return get_post_meta( $post->ID, 'restaurant_and_cafe_sidebar_layout', true );    
    }else{
        return 'right-sidebar';
    }
    
}

if ( ! function_exists( 'restaurant_and_cafe_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function restaurant_and_cafe_excerpt_more() {
  return ' &hellip; ';
}
endif;
add_filter( 'excerpt_more', 'restaurant_and_cafe_excerpt_more' );


if ( ! function_exists( 'restaurant_and_cafe_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function restaurant_and_cafe_excerpt_length( $length ) {
  if( is_page_template('template-home.php') ){
    return 30;
  }else{  return 45;}
}
endif;
add_filter( 'excerpt_length', 'restaurant_and_cafe_excerpt_length', 999 );


/**
 * Footer Credits 
*/
function restaurant_and_cafe_footer_credit(){
?>
    <div class="site-info">
        <?php echo esc_html__( 'Copyright &copy; ', 'restaurant-and-cafe' ) . date_i18n('Y'); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>.&nbsp;
        <span class="by">
        <a href="<?php echo esc_url( 'http://raratheme.com/wordpress-themes/restaurant-and-cafe/' ); ?>" rel="author" target="_blank"><?php echo esc_html__( 'Restaurant And Cafe by Rara Theme', 'restaurant-and-cafe' ); ?></a>.
        <?php printf( esc_html__( 'Powered by %s', 'restaurant-and-cafe' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'restaurant-and-cafe' ) ) .'" target="_blank">WordPress</a>' ); ?>.
        </span>
    </div>

<?php
}
add_action( 'restaurant_and_cafe_footer', 'restaurant_and_cafe_footer_credit' );

/**
 * Escape iframe
*/
function restaurant_and_cafe_sanitize_iframe( $iframe ){
        $allow_tag = array(
            'iframe'=>array(
                'src'             => array()
            ) );
    return wp_kses( $iframe, $allow_tag );
    }

/**
 * Custom CSS
*/
function restaurant_and_cafe_custom_css(){
    $custom_css = get_theme_mod( 'restaurant_and_cafe_custom_css' );
    if( $custom_css ){
        echo '<style type="text/css" media="all">';
        echo wp_strip_all_tags( $custom_css );
        echo '</style>';
    }
}
add_action( 'wp_head', 'restaurant_and_cafe_custom_css', 101 );