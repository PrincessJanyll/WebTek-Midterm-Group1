<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Restaurant_and_Cafe
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
<div id="page" class="site">
	
	<header id="masthead" class="site-header <?php if( !is_page_template( 'template-home.php' ) ) { esc_attr_e( 'header-inner', 'restaurant-and-cafe' ); }  ?>" role="banner">
		<div class = "container">
		<div class="site-branding">
		<?php 
			 if( function_exists( 'has_custom_logo' ) && has_custom_logo() ){
               	the_custom_logo();
             } 

        ?>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo esc_html( $description ); /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>
		</div><!-- .site-branding -->
			<div id="mobile-header">
			    <a id="responsive-menu-button" href="#sidr-main">
			    	<span class="fa fa-navicon"></span>
			    </a>
			</div>
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<div>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
			</div>
		</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->

  	<?php 
  	$restaurant_and_cafe_ed_breadcrumb = get_theme_mod( 'restaurant_and_cafe_ed_breadcrumb' );
   		if( ! ( is_page_template( 'template-home.php' ) || is_404() ) && (true == $restaurant_and_cafe_ed_breadcrumb)   ) { ?>

			<div class="breadcrumbs">
				<div class="container">
					<?php do_action( 'restaurant_and_cafe_breadcrumbs' ); ?>
				</div>
			</div>
   	
	<?php } ?>
	<?php 
	 	if( !is_page_template( 'template-home.php' ) ) {
			echo '<div class = "container"><div id="content" class="site-content"><div class = "row">';
		} ?>