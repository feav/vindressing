<?php
if ( ! defined( 'ABSPATH' )) {
	exit;
}
global $woocommerce,$wc_product_attributes;
if( strlen(get_option('jms_imageswatch_attributes')) > 0 ) $swatch_attributes = explode(",", get_option('jms_imageswatch_attributes'));
$imageswatch_productbox_attribute = get_option('jms_imageswatch_productbox_attribute');
$imageswatch_productbox_position = get_option('jms_imageswatch_productbox_position');
$imageswatch_productbox_number = get_option('jms_imageswatch_productbox_number');
$imageswatch_tooltips = get_option('jms_imageswatch_tooltips');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errors = array();
	$attributes_str = '';
	if(count($_POST['imageswatch_attributes']) > 0)
		$attributes_str = implode(",",$_POST['imageswatch_attributes']);
	update_option( 'jms_imageswatch_attributes', $attributes_str );
	update_option( 'jms_imageswatch_productbox_attribute', $_POST['imageswatch_productbox_attribute'] );
	update_option( 'jms_imageswatch_productbox_position', $_POST['imageswatch_productbox_position'] );
	update_option( 'jms_imageswatch_productbox_number', $_POST['imageswatch_productbox_number'] );
	update_option( 'jms_imageswatch_tooltips', $_POST['imageswatch_tooltips'] );

	if (empty($errors)) {
		echo '<div class="updated"><p><strong>'; echo esc_html_e( 'The Setting saved!', 'jms-imageswatch' ).'.</strong></p></div>';
		echo '<script>
			setTimeout(function(){location.href="admin.php?page=jms-imageswatch"} , 3000);
		</script>';
	} else {
		echo '<div id="message" class="error"><p>'; echo esc_html_e( 'Error in save process', 'jms-imageswatch' ).'.</p></div>';
	}
} //End if submit condition

?>

<div class="wrap">
	<h1><?php echo esc_html_e( 'Image Swatch Setting', 'jms-imageswatch' );?></h2>
	<form action="" method="POST">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label><?php echo esc_html_e( 'Attributes Swatch', 'jms-imageswatch' );?></label></th>
					<td>
					<fieldset>
						<?php foreach($wc_product_attributes as $attr => $val):?>
						<label>
							<input class="checkbox of-input" value="<?php echo $attr;?>" <?php if(isset($swatch_attributes) && in_array($attr, $swatch_attributes)) {?>checked="checked"<?php }?> name="imageswatch_attributes[]" type="checkbox">
							<?php echo $val->attribute_label;?>
						</label><br />
						<?php endforeach; ?>
					</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo esc_html_e( 'Attribute Show on Product Box', 'jms-imageswatch' );?></label></th>
					<td>
					<fieldset>
						<?php foreach($wc_product_attributes as $attr => $val):?>
						<label>
							<input class="checkbox of-input" value="<?php echo $attr;?>" <?php if(isset($imageswatch_productbox_attribute) && ($attr == $imageswatch_productbox_attribute)) {?>checked="checked"<?php }?> name="imageswatch_productbox_attribute" type="radio">
							<?php echo $val->attribute_label;?>
						</label><br />
						<?php endforeach; ?>
					</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo esc_html_e( 'Position On Product Box', 'jms-imageswatch' );?></label></th>
					<td>
					<fieldset>
						<select name="imageswatch_productbox_position">
							<option value="before-product-box" <?php if($imageswatch_productbox_position == 'before-product-box') {?> selected="selected"<?php } ?>><?php echo esc_html_e( 'Before Product Box', 'jms-imageswatch' );?></option>
							<option value="before-title" <?php if($imageswatch_productbox_position == 'before-title') {?> selected="selected"<?php } ?>><?php echo esc_html_e( 'Before Product Name', 'jms-imageswatch' );?></option>
							<option value="after-title" <?php if($imageswatch_productbox_position == 'after-title') {?> selected="selected"<?php } ?>><?php echo esc_html_e( 'After Product Name', 'jms-imageswatch' );?></option>
							<option value="after-product-box" <?php if($imageswatch_productbox_position == 'after-product-box') {?> selected="selected"<?php } ?>><?php echo esc_html_e( 'After Product Box', 'jms-imageswatch' );?></option>
						</select>
					</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo esc_html_e( 'Maximum Variation Show on Product Box', 'jms-imageswatch' );?></label></th>
					<td>
					<fieldset>
						<input name="imageswatch_productbox_number" type="number" value="<?php echo $imageswatch_productbox_number; ?>" />
					</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row"><label><?php echo esc_html_e( 'Enable Tooltip', 'jms-imageswatch' );?></label></th>
					<td>
					<fieldset>
						<label>
							<input class="of-input of-radio" name="imageswatch_tooltips" value="1" <?php if ($imageswatch_tooltips == 1 ) {?> checked="checked"<?php } ?> type="radio">
							<span><?php echo esc_html_e( 'Yes', 'jms-imageswatch' );?></span>
						</label><br />
						<label>
							<input class="of-input of-radio" name="imageswatch_tooltips" value="0" <?php if ($imageswatch_tooltips == 0 ) {?> checked="checked"<?php } ?> type="radio">
							<span><?php echo esc_html_e( 'No', 'jms-imageswatch' );?></span>
						</label>
					</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" class="button button-primary" value="<?php echo esc_html_e( 'Save Changes', 'jms-imageswatch' );?>" /></p>
	</form>
</div>
