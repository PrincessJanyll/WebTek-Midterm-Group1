<?php 
global $post;
$paged = get_query_var( 'paged', 1 );
if (is_home() && is_paged()){
	_e('Page: ', 'wp-simple' );
	echo $paged;
} else if (is_home()){
	echo get_the_title( get_option('page_for_posts', true) );
} else if (is_singular()){
	echo get_the_title();
} else if (is_archive()) {
	if (class_exists( 'WooCommerce' )){
	    if (is_shop()) {
			$shop_page_id = wc_get_page_id( 'shop' );
      		echo get_the_title( $shop_page_id );
	    } else {
			single_term_title( "", true );
	    }
	} else {
		if (is_date()){
			if ( is_day() ) { 
				echo get_the_date(); 
			} else if ( is_month() ){ 
				echo  get_the_date('F, Y'); 
			} else if ( is_year() ){ 
				echo  get_the_date('Y'); 
			}
		} else if (is_author()) {
			_e('Posts by: ', 'wp-simple' );
			echo get_query_var('author_name');
		} else if (is_category()) {
			single_cat_title( '', true );
		} else if (is_tag()) {
			single_tag_title();
		}
	}
} else if (is_404()){
	_e('404', 'wp-simple' );
} else if (is_search()){
	_e('Results for: ', 'wp-simple' ) . get_search_query();
}
?>