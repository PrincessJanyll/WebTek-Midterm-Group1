<?php if (is_front_page() && !is_paged())  { ?>
    <?php  $section_bg=nimbus_get_option('fp-banner-background-image');
    if (!empty($section_bg)) {
        $nimbus_parallax="data-parallax='scroll' data-image-src='" . esc_url($section_bg) . "' style='background: rgba(0, 0, 0, 0.3);'";
        $parallax_active="parallax_active";
    } ?>
    <?php if (nimbus_get_option('fp-banner-toggle') == '1') { ?>
        <section class="frontpage-banner <?php if(isset($parallax_active)){echo $parallax_active;} ?>" <?php if(isset($nimbus_parallax)){echo $nimbus_parallax;} ?>>
            <div class="container">
                <div class="banner-wrap" data-sr='wait 0.4s, scale up 25%'>
                    <?php if (nimbus_get_option('fp-banner-title') != '') { ?>
                        <div class="banner-title"><?php echo esc_html(nimbus_get_option('fp-banner-title')); ?></div>
                    <?php } ?>
                    <?php if (nimbus_get_option('fp-banner-sub-title') != '') { ?>
                        <div class="banner-sub-title"><?php echo esc_html(nimbus_get_option('fp-banner-sub-title')); ?></div>
                    <?php } ?>
                    <?php if (nimbus_get_option('fp-banner-button-url') != '') { ?>
                        <div class="banner-link-button"><a href="<?php echo esc_url(nimbus_get_option('fp-banner-button-url')); ?>"><?php echo esc_html(nimbus_get_option('fp-banner-button-text')); ?></a></div>
                    <?php } ?>
                </div>      
            </div>    
        </section>  
    <?php } else if (nimbus_get_option('fp-banner-toggle') == '3') { ?>
        <?php // do nothing ?>
    <?php } else { ?>     
        <section class="frontpage-banner parallax_active" data-parallax='scroll' data-image-src='<?php echo get_template_directory_uri(); ?>/assets/images/preview/deer.jpg' style='background: transparent;background: rgba(0, 0, 0, 0.3);'>
            <div class="container">
                <div class="banner-wrap" data-sr='wait 0.9s, scale up 25%'>
                    <div class="banner-title"><?php _e('Simple','wp-simple'); ?></div>
                    <div class="banner-sub-title"><?php _e('A Business Wordpress Theme','wp-simple'); ?></div>
                    <div class="banner-link-button"><a href="http://www.nimbusthemes.com/free/simple/"><?php _e('Learn More','wp-simple'); ?></a></div>
                </div>    
            </div>    
        </section> 
    <?php } ?> 
<?php } else { ?>
    <?php
    if ( has_post_thumbnail() ) {
        $thumb_id = get_post_thumbnail_id();
        $thumb_url_array = wp_get_attachment_image_src($thumb_id, 'full');
        $thumb_url = $thumb_url_array[0];
        if (!empty($thumb_url)) {
            $nimbus_parallax="data-parallax='scroll' data-image-src='" . esc_url($thumb_url) . "' style='background: rgba(0, 0, 0, 0.35);'";
            $parallax_active="parallax_active";
        }
    } else {
        $section_bg=nimbus_get_option('sub-banner-background-image');
        if (!empty($section_bg)) {
            $nimbus_parallax="data-parallax='scroll' data-image-src='" . esc_url($section_bg) . "' style='background: rgba(0, 0, 0, 0.35);'";
            $parallax_active="parallax_active";
        } else {
            $nimbus_parallax="data-parallax='scroll' data-image-src='" . get_template_directory_uri() . "/assets/images/preview/deer.jpg' style='background: rgba(0, 0, 0, 0.35);'";
            $parallax_active="parallax_active";
        }
    }
    ?>
    <section class="subpage-banner <?php if(isset($parallax_active)){echo $parallax_active;} ?>"  <?php if(isset($nimbus_parallax)){echo $nimbus_parallax;} ?>>
        <div class="container">
            <div class="banner-wrap" data-sr='wait 0.4s, scale up 25%'>
                <h1 class="banner-title"><?php get_template_part( 'parts/title'); ?></h1>
            </div>
        </div>
    </section>
<?php } ?>