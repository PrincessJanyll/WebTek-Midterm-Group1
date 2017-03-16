<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/single-loop-all.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 *
 * NOTICE:
 * This file is designed to be "automatic", and display the fields you have selected in your Display Settings.
 * You can completely customize the file - if that's your intention, it is recommended that you refer to
 * the view file titled "single-loop-all-sample.php" for examples of functions to use for complete control.
 * */

global $inventory_display;
$inventory_display = apply_filters('wpim_display_listing_settings', $inventory_display);
global $display_labels;
?>
<div class="<?php wpinventory_class(); ?>">
	<?php foreach($inventory_display AS $sort=>$field) { ?>
		<p class="<?php echo $field; ?>">
			<?php if ($display_labels) { ?>
				<span class="label"><?php wpinventory_the_label($field); ?></span>
		<?php }?>
		<?php if ($field != 'inventory_description') { ?>
			<a href="<?php wpinventory_the_permalink(); ?>"><?php wpinventory_the_field($field); ?></a>
		<?php } else { ?>
			<?php wpinventory_the_field($field); ?>
		<?php } ?>
	<?php } ?>
</div>