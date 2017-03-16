<?php 
$section_bg=nimbus_get_option('fp-action1-background-image');
if (!empty($section_bg)) {
    $nimbus_parallax="data-parallax='scroll' data-image-src='" . esc_url($section_bg) . "' style='background: transparent;padding:220px 0 200px;background: rgba(0, 0, 0, 0.3);'";
    $parallax_active="parallax_active";
} 
if (nimbus_get_option('fp-action1-toggle') == '1') { ?>
    <section id="<?php if (nimbus_get_option('fp-action1-slug')=='') {echo "action1";} else {echo esc_attr(nimbus_get_option('fp-action1-slug'));} ?>" class="frontpage-row frontpage-action1 <?php if(isset($parallax_active)){echo $parallax_active;} ?>" <?php if(isset($nimbus_parallax)){echo $nimbus_parallax;} ?>>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if (nimbus_get_option('fp-action1-title') != '') { ?>
                        <div class="action1-title h1" data-sr='wait 0.2s, scale up 25%'><?php echo esc_html(nimbus_get_option('fp-action1-title')); ?></div>
                    <?php } ?>
                    <?php if (nimbus_get_option('fp-action1-sub-title') != '') { ?>
                        <div class="action1-sub-title h4" data-sr='wait 0.2s, scale up 25%'><?php echo esc_html(nimbus_get_option('fp-action1-sub-title')); ?></div>
                    <?php } ?>
                    <?php if ((nimbus_get_option('fp-action1-button-text') != '') && (nimbus_get_option('fp-action1-button-url') != '')) { ?>
                        <div class="action1-link-button" data-sr='wait 0.2s, scale up 25%'><a href="<?php echo esc_url(nimbus_get_option('fp-action1-button-url')); ?>"><?php echo esc_html(nimbus_get_option('fp-action1-button-text')); ?></a></div>
                    <?php } ?>
                </div> 
            </div>    
        </div>    
    </section> 
<?php } else if (nimbus_get_option('fp-action1-toggle') == '3') {
    // Don't do anything
} else { ?>  
    <section id="<?php if (nimbus_get_option('fp-action1-slug')=='') {echo "action1";} else {echo esc_attr(nimbus_get_option('fp-action1-slug'));} ?>" class="frontpage-row frontpage-action1 preview parallax_active" data-parallax='scroll' data-image-src='<?php echo get_template_directory_uri(); ?>/assets/images/preview/ruler.jpg' style='background: transparent;padding:220px 0 200px;background: rgba(0, 0, 0, 0.3);'>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="action1-title h1" data-sr='wait 0.2s, scale up 25%'><?php _e('Call To Action','wp-simple'); ?></div>
                    <div class="action1-sub-title h4" data-sr='wait 0.2s, scale up 25%'><?php _e('Convince me why I should take this action.','wp-simple'); ?></div>
                    <div class="action1-link-button" data-sr='wait 0.2s, scale up 25%'><a href="#"><?php _e('Go For It!','wp-simple'); ?></a></div>
                </div> 
            </div>    
        </div>    
    </section> 
<?php } ?> 

