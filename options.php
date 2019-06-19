<div class="wrap">
<h2>CF7 Data to URL Settings</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options');?>
<?php settings_fields('cf7datasend');?>

<table class="form-table">

<tr valign="top">
<th scope="row">Base URL to send data:</th>
<td><input type="text" name="base_url" value="<?php echo get_option('base_url'); ?>" /></td>
</tr>

<tr valign="top">
<th scope="row">Form to be watched:</th>
<td>
<select name="form_id" id="form_id">
    <option value="">-- Select --</option><?php
$dbValue = get_option('form_id'); //example!
$posts = get_posts(array(
    'post_type' => 'wpcf7_contact_form',
    'numberposts' => -1,
));
foreach ($posts as $p) {
    echo '<option value="' . $p->ID . '"' . selected($p->ID, $dbValue, false) . '>' . $p->post_title . ' (' . $p->ID . ')</option>';
}?>
</select>
</td>
</tr>

</tr>

</table>

<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes')?>" />
</p>

</form>
</div>
