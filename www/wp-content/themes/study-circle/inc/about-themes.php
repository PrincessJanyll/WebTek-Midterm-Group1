<?php
/**
 * Study Circle About Theme
 *
 * @package Study Circle
 */

//about theme info
add_action( 'admin_menu', 'study_circle_abouttheme' );
function study_circle_abouttheme() {    	
	add_theme_page( __('About Theme Info', 'study-circle'), __('About Theme Info', 'study-circle'), 'edit_theme_options', 'study_circle_guide', 'study_circle_mostrar_guide');   
} 

//Guidline for about theme
function study_circle_mostrar_guide() { 
?>

<div class="wrap-GT">
	<div class="gt-left">
   		   <div class="heading-gt"><h2><?php _e('About Theme Info', 'study-circle'); ?></h2></div>
          <p><?php _e('Study Circle is a free Education WordPress theme. It is perfect for school, college, tution classes, coaching classes, personal, bloging and any small business. It is user friendly customizer options and Compatible in wordPress Latest Version. also Compatible with WooCommerce, Nextgen gallery ,Contact Form 7 and many WordPress popular plugins. ','study-circle'); ?></p>
<div class="heading-gt"> <?php _e('Theme Features', 'study-circle'); ?></div>
 
<div class="col-2">
  <h4><?php _e('Theme Customizer', 'study-circle'); ?></h4>
  <div class="description"><?php _e('The built-in customizer panel quickly change aspects of the design and display changes live before saving them.', 'study-circle'); ?></div>
</div>

<div class="col-2">
  <h4><?php _e('Responsive Ready', 'study-circle'); ?></h4>
  <div class="description"><?php _e('The themes layout will automatically adjust and fit on any screen resolution and looks great on any device. Fully optimized for iPhone and iPad.', 'study-circle'); ?></div>
</div>

<div class="col-2">
<h4><?php _e('Cross Browser Compatible', 'study-circle'); ?></h4>
<div class="description"><?php _e('Our themes are tested in all mordern web browsers and compatible with the latest version including Chrome,Firefox, Safari, Opera, IE11 and above.', 'study-circle'); ?></div>
</div>

<div class="col-2">
<h4><?php _e('E-commerce', 'study-circle'); ?></h4>
<div class="description"><?php _e('Fully compatible with WooCommerce plugin. Just install the plugin and turn your site into a full featured online shop and start selling products.', 'study-circle'); ?></div>
</div>

</div><!-- .gt-left -->
	
	<div><br />		
        <hr>	
        <a href="<?php echo STUDY_CIRCLE_LIVE_DEMO; ?>" target="_blank"><?php _e('Live Demo', 'study-circle'); ?></a> | 
        <a href="<?php echo STUDY_CIRCLE_PROTHEME_URL; ?>"><?php _e('Purchase Pro', 'study-circle'); ?></a> | 
        <a href="<?php echo STUDY_CIRCLE_THEME_DOC; ?>" target="_blank"><?php _e('Documentation', 'study-circle'); ?></a>
        <hr />  
	</div>		
	</div><!-- .gt-right-->
    <div class="clear"></div>
</div><!-- .wrap-GT -->
<?php } ?>