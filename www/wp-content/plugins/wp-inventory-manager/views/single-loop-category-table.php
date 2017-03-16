<?php

/**
 * The single template specifically designed for the shortcode.
 * This file may be overridden by copying it into your theme directory, into a folder titled wpinventory/views/single-loop-all.php
 * While inventory does not use the WP post types, it does model functions after the WP core functions
 * to provide similar functionality.
 * */

global $inventory_display;

?>
<tr class="<?php wpinventory_class(); ?>">
	<?php foreach($inventory_display AS $sort=>$field) { ?>
		<td class="<?php echo $field; ?>"><a href="<?php wpinventory_the_permalink(); ?>"><?php wpinventory_the_field($field); ?></a></td>
	<?php } ?>
</tr>