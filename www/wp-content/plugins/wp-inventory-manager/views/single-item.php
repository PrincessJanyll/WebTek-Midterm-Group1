<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/single-item.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

wpinventory_get_items();
if (wpinventory_have_items()) {
	while(wpinventory_have_items()) {
		wpinventory_the_item();
		$inventory_display = wpinventory_get_display_settings('detail');
		$display_labels = wpinventory_get_config('display_detail_labels');
		?>
		<div class="<?php wpinventory_class(); ?>">
		<?php foreach($inventory_display AS $sort=>$field) { ?>
			<div class="<?php echo $field; ?>">
				<?php if ($display_labels) { ?>
					<span class="wpinventory_label"><?php wpinventory_the_label($field); ?></span>
				<?php }
				wpinventory_the_field($field); ?>
			</div>
			<?php do_action('wpim_the_field_' . $field); ?>
	 <?php } ?>
	 	</div>
<?php }
	wpinventory_backlink();

	do_action('wpim_before_reserve_form');

	echo $reserve_form = wpinventory_reserve_form();

	do_action('wpim_after_reserve_form');
}