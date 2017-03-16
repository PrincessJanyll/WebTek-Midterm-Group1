<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 04/02/2015
 * Time: 13:57
 */

// Interdire l'accée direct...
if ( !defined( 'ABSPATH' ) ) exit;


wp_register_style( "display_responsive", plugins_url("studypress/css/player/responsive.css",__ROOT_PLUGIN__) );
wp_enqueue_style('display_responsive');




?>
