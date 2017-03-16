<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/single-loop-all.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

global $inventory_display;

?>
<li class="<?php wpinventory_class(); ?>">
	<span class="inventory_number"><a href="<?php wpinventory_the_permalink(); ?>"><?php wpinventory_the_number(); ?></a></span>
	<span class="inventory_name"><?php wpinventory_the_name(); ?></span>
	<span class="inventory_image"><?php wpinventory_the_featured_image(); ?></span>
	<span class="inventory_price"><?php wpinventory_the_price(); ?></span>
</li>