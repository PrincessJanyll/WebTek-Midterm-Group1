<?php 
$section_bg=nimbus_get_option('fp-test-background-image');
if (!empty($section_bg)) {
    $nimbus_parallax="data-parallax='scroll' data-image-src='" . esc_url($section_bg) . "' style='background: transparent;padding:220px 0 200px;background: rgba(0, 0, 0, 0.3);'";
    $parallax_active="parallax_active";
} 
if (nimbus_get_option('fp-test-toggle') == '1') { ?>
    <?php $fp_test_image = nimbus_get_option('fp-test-image');?>
    <?php $fp_test_slug = nimbus_get_option('fp-test-slug'); ?>
     <section id="<?php if (nimbus_get_option('fp-test-slug')=='') {echo "test";} else {echo esc_attr(nimbus_get_option('fp-test-slug'));} ?>" class="frontpage-row frontpage-test <?php if(isset($parallax_active)){echo $parallax_active;} ?>" <?php if(isset($nimbus_parallax)){echo $nimbus_parallax;} ?>>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 col-md-offset-1"  data-sr="wait 0.3s, enter left and move 50px after 1s">
                            <div class="test-item">
                                <img class="img-responsive img-circle frontpage-test-image center-block" src="<?php echo $fp_test_image; ?>" />
                            </div>
                        </div>          
                        <div class="col-md-7"  data-sr="wait 0.3s, enter right and move 50px after 1s">
                            <?php if (nimbus_get_option('fp-test-title') != '') { ?>
                                <h3 class="test-title"><?php echo esc_html(nimbus_get_option('fp-test-title')); ?></h3>
                            <?php } ?>
                            <?php if (nimbus_get_option('fp-test-title') != '') { ?>
                                <div class="test-desc"><?php echo esc_html(nimbus_get_option('fp-test-description')); ?></div>
                            <?php } ?>
                            <?php if (nimbus_get_option('fp-test-tag') != '') { ?>
                                <?php if (nimbus_get_option('fp-test-tag-url') != '') {
                                    $before_tag='<a href="'.esc_url(nimbus_get_option('fp-test-tag-url')).'">';
                                    $after_tag='</a>';
                                }else{ $before_tag=''; $after_tag=''; } ?>
                                <div class="test-tag">~ <?php echo $before_tag; ?><?php echo esc_html(nimbus_get_option('fp-test-tag')); ?><?php echo $after_tag; ?></div>
                            <?php } ?>
                        </div> 
                    </div>    
                </div> 
            </div>    
        </div>    
    </section> 
<?php } else if (nimbus_get_option('fp-test-toggle') == '3') {
    // Don't do anything
} else { ?>  
    <section id="<?php if (nimbus_get_option('fp-test-slug')=='') {echo "test";} else {echo esc_attr(nimbus_get_option('fp-test-slug'));} ?>" class="frontpage-row frontpage-test preview">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 col-md-offset-1"  data-sr="wait 0.3s, enter left and move 50px after 1s">
                            <div class="test-item">
                                <img class="img-responsive frontpage-test-image center-block" src="<?php echo get_template_directory_uri(); ?>/assets/images/preview/320x302.jpg" />
                            </div>
                        </div>          
                        <div class="col-md-7"  data-sr="wait 0.3s, enter right and move 50px after 1s">
                            <h3 class="test-title"><?php _e('This is the theme of my dreams!','wp-simple'); ?></h3>
                            <div class="test-desc"><?php _e('"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi."','wp-simple'); ?></div>
                            <div class="test-tag">~ <a href="#"><?php _e('Lucy McFallon','wp-simple'); ?></a></div>
                        </div> 
                    </div>    
                </div> 
            </div>    
        </div>    
    </section> 
<?php } ?> 

