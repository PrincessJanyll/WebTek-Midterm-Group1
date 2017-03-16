<?php
// set conditions to show widget areas
global $wp_query;
$i = 1;
if (is_page() || is_single()) {
    $postid = $wp_query->post->ID;
    if (trim(get_post_meta($postid, 'sidebar_number', true)) != "") {
        while ($i <= 10) {
            if (trim(get_post_meta($postid, 'sidebar_number', true)) == $i) {
                if (is_active_sidebar( "sidebar_" . $i )) {
                    dynamic_sidebar( "sidebar_" . $i );
                }
            }
            $i++;
        }
    } else if ( is_single() ) {
        if (is_active_sidebar( "sidebar_blog" )) {
            dynamic_sidebar( "sidebar_blog" );
        }
    } else {
        if (is_active_sidebar( "sidebar_pages" )) {
            dynamic_sidebar( "sidebar_pages" );
        }
    }
} else if (class_exists( 'WooCommerce' )){
    if (is_shop()) {
        // defaults to no sidebar
    }
} else if (is_home() || is_front_page() || is_archive() || is_search()) {
    if (is_active_sidebar( "sidebar_blog" )) {
        dynamic_sidebar( "sidebar_blog" );
    } else {
        the_widget('WP_Widget_Pages', 'title=Pages', 'before_title=<h3 class="widget_title">&after_title=</h3>&before_widget=<div class="widget sidebar_widget">&after_widget=</div>'); 
        the_widget('WP_Widget_Categories', 'title=Categories', 'before_title=<h3 class="widget_title">&after_title=</h3>&before_widget=<div class="widget sidebar_widget">&after_widget=</div>');
        the_widget( 'WP_Widget_Meta', 'title=Meta', 'before_title=<h3 class="widget_title">&after_title=</h3>&before_widget=<div class="widget sidebar_widget">&after_widget=</div>');
    }
}
?>