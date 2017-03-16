<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 04/02/2015
 * Time: 13:57
 */


// Interdire l'accée direct...
if ( !defined( 'ABSPATH' ) ) exit;


wp_register_style( "display_css_sp_normalize", plugins_url("studypress/css/player/normalize.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_sp_normalize');


wp_register_style( "display_css_sp_player", plugins_url("studypress/css/player/player.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_sp_player');


wp_register_style( "display_css_sp_style", plugins_url("studypress/css/player/style-".$type.".css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_sp_style');

// Owl Carousel
wp_register_style( "display_css_sp_carousel", plugins_url("studypress/css/player/owl.carousel.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_sp_carousel');

//Mary Lou
wp_register_style( "display_css_sp_tabs", plugins_url("studypress/css/player/tabs.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_sp_tabs');

wp_register_style( "display_css_sp_tabstyle", plugins_url("studypress/css/player/tabstyles.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_sp_tabstyle');



wp_register_style( "display_css_sp_rating", plugins_url("studypress/css/player/rating.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_sp_rating');


wp_register_style( "display_css_sp_size", plugins_url("studypress/css/player/sp_player_". $sp_sizePlayer .".css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_css_sp_size');






?>
