<?php extract($args); ?>
<form id="wpim_reserve" name="wpinventory_reserve" method="post" action="#wpim_reserve" class="wpinventory_reserve">
	<?php if ($form_title) { ?>
	<h2><?php echo $form_title; ?></h2>
	<?php 
	}
		if ($error) { ?>
	<div class="error"><?php echo $error; ?></div>
	<?php }?>
	<?php if ($display_name) {
		$required = ($display_name == 2) ? ' required' : ''; ?>
		<div class="name<?php echo $required; ?>">
			<label><?php echo $name_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<input type="text" name="wpinventory_reserve_name" value="<?php echo $name; ?>"<?php echo $required; ?> />
		</div>
	<?php } ?>
	<?php if ($display_address) {
		$required = ($display_address == 2) ? ' required' : ''; ?>
		<div class="address<?php echo $required; ?>">
			<label><?php echo $address_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<input type="text" name="wpinventory_reserve_address" value="<?php echo $address; ?>"<?php echo $required; ?> />
		</div>
	<?php } ?>
	<?php if ($display_city) {
		$required = ($display_city == 2) ? ' required' : '';  ?>
		<div class="city"<?php echo $required; ?>>
			<label><?php echo $city_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<input type="text" name="wpinventory_reserve_city" value="<?php echo $city; ?>"<?php echo $required; ?> />
		</div>
	<?php } ?>
	<?php if ($display_state) {
		$required = ($display_state == 2) ? ' required' : '';  ?>
		<div class="state"<?php echo $required; ?>>
			<label><?php echo $state_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<input type="text" name="wpinventory_reserve_state" value="<?php echo $state; ?>"<?php echo $required; ?> />
		</div>
	<?php } ?>
	<?php if ($display_zip) {
		$required = ($display_zip == 2) ? ' required' : '';  ?>
		<div class="zip"<?php echo $required; ?>>
			<label><?php echo $zip_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<input type="text" name="wpinventory_reserve_zip" value="<?php echo $zip; ?>"<?php echo $required; ?> />
		</div>
	<?php } ?>
	<?php if ($display_phone) {
		$required = ($display_phone == 2) ? ' required' : '';  ?>
		<div class="phone"<?php echo $required; ?>>
			<label><?php echo $phone_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<input type="text" name="wpinventory_reserve_phone" value="<?php echo $phone; ?>"<?php echo $required; ?> />
		</div>
	<?php } ?>
	<?php if ($display_email) {
		$required = ($display_email == 2) ? ' required' : '';  ?>
		<div class="email"<?php echo $required; ?>>
			<label><?php echo $email_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<input type="text" name="wpinventory_reserve_email" value="<?php echo $email; ?>"<?php echo $required; ?> />
		</div>
	<?php } ?>
	<?php if ($display_quantity) {
		$required = ($display_quantity == 2) ? ' required' : '';  ?>
		<div class="quantity"<?php echo $required; ?>>
			<label><?php echo $quantity_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<input type="text" name="wpinventory_reserve_quantity" value="<?php echo $quantity; ?>"<?php echo $required; ?> />
		</div>
	<?php } ?>
	<?php if ($display_message) {
		$required = ($display_message == 2) ? ' required' : '';  ?>
		<div class="message"<?php echo $required; ?>>
			<label><?php echo $message_label; ?><?php if ($required) { echo '<span class="req">*</span>'; } ?></label>
			<textarea name="wpinventory_reserve_message"<?php echo $required; ?>><?php echo $message; ?></textarea>
		</div>
	<?php } ?>
	<div class="submit">
		<input type="submit" name="wpinventory_reserve_submit" value="<?php echo $submit_label; ?>" />
	</div>
</form>