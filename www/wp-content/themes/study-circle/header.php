<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package Study Circle
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="pageholder">
<div class="header <?php if( !is_front_page() && !is_home() ){ ?>headerinner<?php } ?>">
        <div class="container">
            <div class="logo">
            			<?php study_circle_the_custom_logo(); ?>
                        <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
                        <span><?php bloginfo('description'); ?></span>
            </div><!-- logo -->
             <div class="hdrright">
             <div class="toggle">
                <a class="toggleMenu" href="#"><?php _e('Menu','study-circle'); ?></a>
             </div><!-- toggle --> 
            
            <div class="sitenav">
                    <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
            </div><!-- site-nav -->
            </div>
            <div class="clear"></div>
            
        </div><!-- container -->
  </div><!--.header -->

<!-- Slider Section -->
<?php 
if ( is_front_page() && ! is_home() ) 
{
$hide_slide = get_theme_mod('study_circle_disabled_slides', '1');
if($hide_slide == '')
{
	for($sld=7; $sld<10; $sld++) {
		if( get_theme_mod('study_circle_page-setting'.$sld)) {
			$slidequery = new WP_query('page_id='.absint(get_theme_mod('study_circle_page-setting'.$sld,true)));
			while( $slidequery->have_posts() ) : $slidequery->the_post();
				$image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
				$img_arr[] = $image;
				$id_arr[] = $post->ID;
			endwhile;
			wp_reset_postdata();
		}
	}
?>
<?php if(!empty($id_arr)){ ?>
<section id="home_slider">
<div class="slider-wrapper theme-default">
<div id="slider" class="nivoSlider">
	<?php 
	$i=1;
	foreach($img_arr as $url){ ?>
	<?php if(!empty($url)){ ?>
	<img src="<?php echo $url; ?>" title="#slidecaption<?php echo $i; ?>" />
	<?php }else{ ?>
	<img src="<?php echo esc_url( get_template_directory_uri() ) ; ?>/images/slides/slider-default.jpg" title="#slidecaption<?php echo $i; ?>" />
	<?php } ?>
	<?php $i++; }  ?>
</div>   
<?php 
$i=1;
foreach($id_arr as $id){ 
$title = get_the_title( $id ); 
$post = get_post($id); 
$content = wp_trim_words( $post->post_content, 30, '' );
?>                 
<div id="slidecaption<?php echo $i; ?>" class="nivo-html-caption">
<div class="slide_info">
<h2><?php echo $title; ?></h2>
<p><?php echo $content; ?></p>
<a class="slide_more" href="<?php the_permalink(); ?>"><?php _e('Read More', 'study-circle');?></a>
</div>
</div>      
<?php $i++; } ?>       
 </div>
<div class="clear"></div>   
</section>
<?php } } ?> 
<?php } ?>
       
        
<?php if ( is_front_page() && ! is_home() ) { ?>

<?php
$hide_whychooseus = get_theme_mod('study_circle_disabled_whychooseus', '1');
if( $hide_whychooseus == ''){
if( get_theme_mod('study_circle_why-page1')) {
?> 
<section id="Appwrap">
		<div class="container">
		   <div class="appointmentwrap"> 
				<?php 
				$queryvar = new WP_query('page_id='.absint(get_theme_mod('study_circle_why-page1' ,true)));
                while( $queryvar->have_posts() ) : $queryvar->the_post();
				?> 
                <h3><?php the_title(); ?></h3> 
                <p><?php echo wp_trim_words( get_the_content(), 60, '...' ); ?></p>
                <a class="ReadMore" href="<?php the_permalink(); ?>"><?php _e('Read More','study-circle'); ?></a> 
                <?php 
				endwhile;
				wp_reset_postdata(); 
				?> 
	  	 </div> 
	  <div class="clear"></div>
	</div><!-- container -->
</section>
<?php } } ?>


<?php
$hide_pageboxes = get_theme_mod('study_circle_disabled_pgboxes', '1');
if( $hide_pageboxes == ''){
?>  
<section id="wrapsecond">
<div class="container">
    <div class="services-wrap">                       
        <?php for($p=1; $p<4; $p++) { ?>       
        <?php if( get_theme_mod('study_circle_page-column'.$p,false)) { ?>          
            <?php $queryvar = new WP_query('page_id='.absint(get_theme_mod('study_circle_page-column'.$p,true))); ?>				
                    <?php while( $queryvar->have_posts() ) : $queryvar->the_post(); ?> 
                    <div class="fourbox <?php if($p % 3 == 0) { echo "last_column"; } ?>">                                    
						<?php if(has_post_thumbnail() ) { ?>
                        <div class="thumbbx"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail();?></a></div>
                        <?php } ?>
                        <div class="pagecontent">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>                                    
                            <p><?php echo wp_trim_words( get_the_content(), 20, '...' ); ?></p>
                            <a class="ReadMore" href="<?php the_permalink(); ?>"><?php _e('Read More','study-circle'); ?></a> 
                        </div>                                   
                    </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                    
        <?php } } ?>  
    <div class="clear"></div>  
</div><!-- services-wrap-->
<div class="clear"></div>
</div><!-- container -->
</section> 
<?php } ?>       
<?php }?>