<?php 
$section_bg=nimbus_get_option('fp-social-background-image');
if (!empty($section_bg)) {
    $nimbus_parallax="data-parallax='scroll' data-image-src='" . esc_url($section_bg) . "' style='background: transparent;padding:220px 0 200px;background: rgba(0, 0, 0, 0.3);'";
    $parallax_active="parallax_active";
} 
if (nimbus_get_option('fp-social-toggle') == '1') { ?>
    <section id="<?php if (nimbus_get_option('fp-social-slug')=='') {echo "social";} else {echo esc_attr(nimbus_get_option('fp-social-slug'));} ?>" class="frontpage-row frontpage-social <?php if(isset($parallax_active)){echo $parallax_active;} ?>" <?php if(isset($nimbus_parallax)){echo $nimbus_parallax;} ?>>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if (nimbus_get_option('fp-social-title') != '') { ?>
                        <div class="social-title h1"><?php echo esc_html(nimbus_get_option('fp-social-title')); ?></div>
                    <?php } ?>
                    <?php if (nimbus_get_option('fp-social-sub-title') != '') { ?>
                        <div class="social-sub-title h4"><?php echo esc_html(nimbus_get_option('fp-social-sub-title')); ?></div>
                    <?php } ?>
                    <div class="inline-center-wrapper">  
                    <?php if ( is_active_sidebar( 'frontpage-social-media' ) ) { ?>
                    	<?php dynamic_sidebar( 'frontpage-social-media' ); ?>
                    <?php } ?>
                    </div>
                </div> 
            </div>    
        </div>    
    </section> 
<?php } else if (nimbus_get_option('fp-social-toggle') == '3') {
    // Don't do anything
} else { ?>  
    <section id="<?php if (nimbus_get_option('fp-social-slug')=='') {echo "social";} else {echo esc_attr(nimbus_get_option('fp-social-slug'));} ?>" class="frontpage-row frontpage-social preview parallax_active" data-parallax='scroll' data-image-src='<?php echo get_template_directory_uri(); ?>/assets/images/preview/jelly.jpg' style='background: transparent;padding:220px 0 200px;background: rgba(0, 0, 0, 0.3);'>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="social-title h1"><?php _e('Connect With Us','wp-simple'); ?></div>
                    <div class="social-sub-title h4"><?php _e('There are lots of ways to connect with us, and we want you to try them all!','wp-simple'); ?></div>
                    <div class="inline-center-wrapper">  
                        <div data-sr="wait 0.2s, scale up 25%">
                            <a href="#"><i class="fa fa-bitbucket"></i><br><span class="social-item-title h5"><?php _e('BitBucket','wp-simple'); ?></span><br><span class="social-item-sub-title h5"><?php _e('Follow our code.','wp-simple'); ?></span></a>
                        </div>
                        <div data-sr="wait 0.2s, scale up 25%">
                            <a href="#"><i class="fa fa-twitter"></i><br><span class="social-item-title h5"><?php _e('Twitter','wp-simple'); ?></span><br><span class="social-item-sub-title h5"><?php _e('Latest tweets.','wp-simple'); ?></span></a>
                        </div>
                        <div data-sr="wait 0.2s, scale up 25%">
                            <a href="#"><i class="fa fa-facebook"></i><br><span class="social-item-title h5"><?php _e('Facebook','wp-simple'); ?></span><br><span class="social-item-sub-title h5"><?php _e('Be our friend.','wp-simple'); ?></span></a>
                        </div>
                        <div data-sr="wait 0.2s, scale up 25%">
                            <a href="#"><i class="fa fa-instagram"></i><br><span class="social-item-title h5"><?php _e('Instagram','wp-simple'); ?></span><br><span class="social-item-sub-title h5"><?php _e('See our pics.','wp-simple'); ?></span></a>
                        </div>
                        <div data-sr="wait 0.2s, scale up 25%">
                            <a href="#"><i class="fa fa-linkedin"></i><br><span class="social-item-title h5"><?php _e('Linkedin','wp-simple'); ?></span><br><span class="social-item-sub-title h5"><?php _e('Let\'s network.','wp-simple'); ?></span></a>
                        </div>
                    </div>
                </div> 
            </div>    
        </div>    
    </section> 
<?php } ?> 

