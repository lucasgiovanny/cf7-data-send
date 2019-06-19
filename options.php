<div class="wrap">
<h2>CF7 Data to URL Settings</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields('cf7urlsender'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Base URL to send data:</th>
<td><input type="text" name="base_url" value="<?php echo get_option('base_url'); ?>" /></td>
</tr>

</tr>

</table>

<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>
