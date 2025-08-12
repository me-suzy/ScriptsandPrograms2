<?php

// $Id: modify_settings.php 227 2005-11-20 10:22:27Z ryan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/*
The Website Baker Project would like to thank Rudolph Lartey <www.carbonect.com>
for his contributions to this module - adding extra field types
*/

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Get header and footer
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_settings WHERE section_id = '$section_id'");
$setting = $query_content->fetchRow();

// Set raw html <'s and >'s to be replace by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');

?>

<style type="text/css">
.setting_name {
	vertical-align: top;
}
</style>

<form name="edit" action="<?php echo WB_URL; ?>/modules/form/save_settings.php" method="post" style="margin: 0;">

<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">

<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="setting_name" width="220"><?php echo $TEXT['HEADER']; ?>:</td>
	<td class="setting_name">
		<textarea name="header" style="width: 100%; height: 80px;"><?php echo ($setting['header']); ?></textarea>
	</td>
</tr>
<tr>
	<td class="setting_name"><?php echo $TEXT['FIELD'].' '.$TEXT['LOOP']; ?>:</td>
	<td class="setting_name">
		<textarea name="field_loop" style="width: 100%; height: 60px;"><?php echo ($setting['field_loop']); ?></textarea>
	</td>
</tr>
<tr>
	<td class="setting_name"><?php echo $TEXT['FOOTER']; ?>:</td>
	<td class="setting_name">
		<textarea name="footer" style="width: 100%; height: 80px;"><?php echo str_replace($raw, $friendly, ($setting['footer'])); ?></textarea>
	</td>
</tr>
<tr>
	<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['TO']; ?>:</td>
	<td class="setting_name">
		<textarea name="email_to" style="width: 100%; height: 30px;"><?php echo str_replace($raw, $friendly, ($setting['email_to'])); ?></textarea>
	</td>
</tr>
<tr>
	<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['FROM']; ?>:</td>
	<td class="setting_name">
		<select name="email_from_field" style="width: 100%;">
			<option value="" onclick="javascript: document.getElementById('email_from').style.display = 'block';"><?php echo $TEXT['CUSTOM']; ?>:</option>
			<?php
			$email_from_value = str_replace($raw, $friendly, ($setting['email_from']));
			$query_email_fields = $database->query("SELECT field_id,title FROM ".TABLE_PREFIX."mod_form_fields WHERE section_id = '$section_id' ORDER BY position ASC");
			if($query_email_fields->numRows() > 0) {
				while($field = $query_email_fields->fetchRow()) {
					?>
					<option value="field<?php echo $field['field_id']; ?>"<?php if($email_from_value == 'field'.$field['field_id']) { echo ' selected'; $selected = true; } ?> onclick="javascript: document.getElementById('email_from').style.display = 'none';">
						<?php echo $TEXT['FIELD'].': '.$field['title']; ?>
					</option>
					<?php
				}
			}
			?>
		</select>
		<input type="text" name="email_from" id="email_from" style="width: 100%; display: <?php if(isset($selected) AND $selected == true) { echo 'none'; } else { echo 'block'; } ?>;" maxlength="255" value="<?php if(substr($email_from_value, 0, 5) != 'field') { echo $email_from_value; } ?>" />
	</td>
</tr>
<tr>
	<td class="setting_name"><?php echo $TEXT['EMAIL'].' '.$TEXT['SUBJECT']; ?>:</td>
	<td class="setting_name">
		<input type="text" name="email_subject" style="width: 100%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['email_subject'])); ?>" />
	</td>
</tr>
<tr>
	<td class="setting_name"><?php echo $TEXT['SUCCESS'].' '.$TEXT['MESSAGE']; ?>:</td>
	<td class="setting_name">
		<textarea name="success_message" style="width: 100%; height: 80px;"><?php echo str_replace($raw, $friendly, ($setting['success_message'])); ?></textarea>
	</td>
</tr>
<tr>
	<td class="setting_name"><?php echo $TEXT['MAX_SUBMISSIONS_PER_HOUR']; ?>:</td>
	<td class="setting_name">
		<input type="text" name="max_submissions" style="width: 100%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['max_submissions'])); ?>" />
	</td>
</tr>
<tr>
	<td class="setting_name"><?php echo $TEXT['SUBMISSIONS_STORED_IN_DATABASE']; ?>:</td>
	<td class="setting_name">
		<input type="text" name="stored_submissions" style="width: 100%;" maxlength="255" value="<?php echo str_replace($raw, $friendly, ($setting['stored_submissions'])); ?>" />
	</td>
</tr>
<?php if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { /* Make's sure GD library is installed */ ?>
<tr>
	<td class="setting_name"><?php echo $TEXT['CAPTCHA_VERIFICATION']; ?>:</td>
	<td>
		<input type="radio" name="use_captcha" id="use_captcha_true" value="1"<?php if($setting['use_captcha'] == true) { echo ' checked'; } ?> />
		<label for="use_captcha_true"><?php echo $TEXT['ENABLED']; ?></label>
		<input type="radio" name="use_captcha" id="use_captcha_false" value="0"<?php if($setting['use_captcha'] == false) { echo ' checked'; } ?> />
		<label for="use_captcha_false"><?php echo $TEXT['DISABLED']; ?></label>
	</td>
</tr>
<?php } ?>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="225">&nbsp;</td>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE'].' '.$TEXT['SETTINGS']; ?>" style="width: 200px; margin-top: 5px;"></form>
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>


<?php

// Print admin footer
$admin->print_footer();

?>