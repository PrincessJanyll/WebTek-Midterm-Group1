<?php
$nimbus_left_featured = nimbus_get_option('nimbus_left_featured');
$nimbus_center_featured = nimbus_get_option('nimbus_center_featured');
$nimbus_right_featured = nimbus_get_option('nimbus_right_featured');
$nimbus_featured = array(
    'nimbus_left_featured'              =>  $nimbus_left_featured,
    'nimbus_center_featured'            =>  $nimbus_center_featured,
    'nimbus_right_featured'             =>  $nimbus_right_featured,
);
$section_bg=nimbus_get_option('fp-featured-background-image');
if (!empty($section_bg)) {
    $nimbus_parallax="data-parallax='scroll' data-image-src='" . esc_url($section_bg) . "' style='background: transparent;padding:220px 0 200px;background: rgba(0, 0, 0, 0.3);'";
    $parallax_active="parallax_active";
} 
if (nimbus_get_option('fp-featured-toggle') == '1') { ?>
   <section id="<?php if (nimbus_get_option('fp-featured-slug')=='') {echo "featured";} else {echo esc_attr(nimbus_get_option('fp-featured-slug'));} ?>" class="frontpage-row frontpage-featured <?php if(isset($parallax_active)){echo $parallax_active;} ?>" <?php if(isset($nimbus_parallax)){echo $nimbus_parallax;} ?>>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if (nimbus_get_option('fp-featured-title') != '') { ?>
                        <div class="featured-title h1"><?php echo esc_html(nimbus_get_option('fp-featured-title')); ?></div>
                    <?php } ?>
                    <?php if (nimbus_get_option('fp-featured-sub-title') != '') { ?>
                        <div class="featured-sub-title h4"><?php echo esc_html(nimbus_get_option('fp-featured-sub-title')); ?></div>
                    <?php } ?>
                    <div class="row row-centered">
                    <?php
                    foreach ($nimbus_featured as $key => $featured) {
                        if (!empty($featured)) {
                            $original_query = $wp_query;
                            $wp_query = null;
                            $wp_query = new WP_Query(array('page_id' => $featured, 'posts_per_page' => 1, 'post__not_in' => get_option( 'sticky_posts' )));
                            if (have_posts()) {
                                while (have_posts()) {
                                    the_post();
                                    $nimbus_thumb_src = wp_get_attachment_image_src( get_post_thumbnail_id($featured), 'nimbus_750_500' );
                                    $nimbus_thumb_url = $nimbus_thumb_src[0];
                                    ?>
                                    
                                    <div class="col-sm-4 col-centered featured-item-col" data-sr="wait 0.2s, enter left and move 50px after 1s">
                                        <a class="featured-item" href="<?php echo get_permalink($featured); ?>">
                                            <img src="<?php echo $nimbus_thumb_url; ?>" class="img-responsive center-block">
                                            <h4 class="featured-item-title"><?php the_title(); ?></h4>
                                            <p class="featured-item-sub-title"><?php $the_excerpt = nimbus_get_the_excerpt_by_id($featured); if (!empty($the_excerpt)) { echo $the_excerpt;  } else { nimbus_clear(8); } ?></p>
                                        </a>
                                    </div> 

                                    
                                    <?php
                                }
                            } else {
                                    get_template_part( 'parts/error', 'no_results');
                            }
                            $wp_query = null;
                            $wp_query = $original_query;
                            wp_reset_postdata();
                        }
                    }
                    ?> 
                    </div>
                </div> 
            </div>    
        </div>    
    </section> 
<?php } else if (nimbus_get_option('fp-featured-toggle') == '3') {
    // Don't do anything
} else { ?>  
   <section id="<?php if (nimbus_get_option('fp-featured-slug')=='') {echo "featured";} else {echo esc_attr(nimbus_get_option('fp-featured-slug'));} ?>" class="frontpage-row frontpage-featured preview">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="featured-title h1"><?php _e('Theme Features','wp-simple'); ?></div>
                    <div class="featured-sub-title h4"><?php _e('Three reasons to choose Simple for your next website project!','wp-simple'); ?></div>
                    <div class="row row-centered">
                        <div class="col-sm-4 col-centered featured-item-col" data-sr="wait 0.2s, enter left and move 50px after 1s">
                            <a class="featured-item" href="#">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/preview/750x500-<?php echo rand(1,8); ?>.jpg"class="img-responsive center-block">
                                <h4 class="featured-item-title"><?php _e('One-Page Layout','wp-simple'); ?></h4>
                                <p class="featured-item-sub-title"><?php _e('Beautiful one-page frontpage with smooth scrolling navigation.','wp-simple'); ?></p>
                            </a>
                        </div>          
                        <div class="col-sm-4 col-centered featured-item-col" data-sr="wait 0.2s, enter left and move 50px after 1s">
                            <a class="featured-item" href="#">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/preview/750x500-<?php echo rand(1,8); ?>.jpg"class="img-responsive center-block">
                                <h4 class="featured-item-title"><?php _e('Customizer','wp-simple'); ?></h4>
                                <p class="featured-item-sub-title"><?php _e('Uses the WordPress Core customizer for easy administration.','wp-simple'); ?></p>
                            </a>
                        </div> 
                        <div class="col-sm-4 col-centered featured-item-col" data-sr="wait 0.2s, enter left and move 50px after 1s">
                            <a class="featured-item" href="#">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/preview/750x500-<?php echo rand(1,8); ?>.jpg"class="img-responsive center-block">
                                <h4 class="featured-item-title"><?php _e('Parallax Effects','wp-simple'); ?></h4>
                                <p class="featured-item-sub-title"><?php _e('Graceful scroll-activated parallax effects throughout.','wp-simple'); ?></p>
                            </a>
                        </div> 
                    </div>    
                </div> 
            </div>    
        </div>    
    </section> 
<?php } ?>