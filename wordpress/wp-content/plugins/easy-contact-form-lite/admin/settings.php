<?php 
if (isset($_POST['merlic_easyform_submit'])) {
	if ($_POST['merlic_easyform_recaptcha'] == 'yes') {
		update_option('merlic_easyform_recaptcha', 'yes');
		update_option('merlic_easyform_recaptcha_public', $_POST['merlic_easyform_recaptcha_public']);
		update_option('merlic_easyform_recaptcha_private', $_POST['merlic_easyform_recaptcha_private']);
	}
	else {
		delete_option('merlic_easyform_recaptcha');
	}
}

?>
<h3>General Options</h3>
<form method="POST" accept-charset="utf-8" target="_self" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <label for="merlic_easyform_recaptcha">
                    <?php _e('Use Recaptcha'); ?>
                </label>
            </th>
            <td>
            	<?php
				$checked = get_option('merlic_easyform_recaptcha')=='yes'?'checked="checked"':'';
				?>
                <input type="checkbox" class="checkbox" name="merlic_easyform_recaptcha" value="yes" <?php echo $checked; ?>/> Yes
                <br/>
                <span class="description"><?php _e('Recaptcha makes sure that forms are submitted by humans and not by spam softwares'); ?></span>
            </td>
        </tr>
    </table>
    <br/>
    <input class="button-primary" type="submit" name="merlic_easyform_submit" value="<?php _e('Save Options'); ?>" />
</form>