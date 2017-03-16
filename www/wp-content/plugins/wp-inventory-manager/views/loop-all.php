<?php


/**
 * DEVELOPERS:
 * This file is the default view, and is designed to utilize the "Display" settings from the dashboard.
 *
 * This file is loaded when the display setting "Display Listing as Table" is set to "No".
 *
 * You can absolutely override this utilizing WP Inventory's Override functionality.
 * Look at the file "loop-all-sample.php" for an example of how to modify these files.
 *
 * The loop specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/loop-shortcode.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality
 * */

if (wpinventory_is_single()) {
	wpinventory_get_template_part('single-item');
	return;
}

wpinventory_get_items();
echo wpinventory_filter_form('filter=true&sort=true');

global $inventory_display;
$inventory_display = wpinventory_get_display_settings('listing');

global $display_labels;
$display_labels = wpinventory_get_config('display_listing_labels');

if (wpinventory_have_items()) { ?>
	<div class="wpinventory_loop wp_inventory_loop_all wpinventory_loop_all_div">
		<?php while (wpinventory_have_items()) {
				wpinventory_the_item();
				wpinventory_get_template_part('single-loop-all');
			} ?>
	</div>
<?php
	echo wpinventory_pagination();
	} else { ?>
	<p class="wpinventory_warning"><?php WPIMCore::_e('No Inventory Items'); ?></p>
<?php } ?>