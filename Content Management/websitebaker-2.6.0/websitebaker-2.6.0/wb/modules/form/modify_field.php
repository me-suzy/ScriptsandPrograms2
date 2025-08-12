<?php

// $Id: modify_field.php 116 2005-09-16 21:20:22Z stefan $

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

// Get id
if(!isset($_GET['field_id']) OR !is_numeric($_GET['field_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$field_id = $_GET['field_id'];
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Get header and footer
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_fields WHERE field_id = '$field_id'");
$form = $query_content->fetchRow();
$type = $form['type'];
if($type == '') {
	$type = 'none';
}

// Set raw html <'s and >'s to be replaced by friendly html code
$raw = array('<', '>');
$friendly = array('&lt;', '&gt;');
?>

<form name="modify" action="<?php echo WB_URL; ?>/modules/form/save_field.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="field_id" value="<?php echo $field_id; ?>">

<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr>
	<td width="80"><?php echo $TEXT['TITLE']; ?>:</td>
	<td>
		<input type="text" name="title" value="<?php echo htmlspecialchars(($form['title'])); ?>" style="width: 100%;" maxlength="255" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['TYPE']; ?>:</td>
	<td>
		<select name="type" style="width: 100%;">
			<option value=""><?php echo $TEXT['PLEASE_SELECT']; ?>...</option>
			<option value="heading"<?php if($type == 'heading') { echo ' selected'; } ?>><?php echo $TEXT['HEADING']; ?></option>
			<option value="textfield"<?php if($type == 'textfield') { echo ' selected'; } ?>><?php echo $TEXT['SHORT'].' '.$TEXT['TEXT']; ?> (Textfield)</option>
			<option value="textarea"<?php if($type == 'textarea') { echo ' selected'; } ?>><?php echo $TEXT['LONG'].' '.$TEXT['TEXT']; ?> (Textarea)</option>
			<option value="select"<?php if($type == 'select') { echo ' selected'; } ?>><?php echo $TEXT['SELECT_BOX']; ?></option>
			<option value="checkbox"<?php if($type == 'checkbox') { echo ' selected'; } ?>><?php echo $TEXT['CHECKBOX_GROUP']; ?></option>
			<option value="radio"<?php if($type == 'radio') { echo ' selected'; } ?>><?php echo $TEXT['RADIO_BUTTON_GROUP']; ?></option>
			<option value="email"<?php if($type == 'email') { echo ' selected'; } ?>><?php echo $TEXT['EMAIL_ADDRESS']; ?></option>
		</select>
	</td>
</tr>
<?php if($type != 'none' AND $type != 'email') { ?>
	<?php if($type == 'heading') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['TEMPLATE']; ?>:</td>
		<td>
		<textarea name="template" style="width: 100%; height: 20px;"><?php echo htmlspecialchars(($form['extra'])); ?></textarea>
		</td>
	</tr>
	<?php } elseif($type == 'textfield') { ?>
	<tr>
		<td><?php echo $TEXT['LENGTH']; ?>:</td>
		<td>
			<input type="text" name="length" value="<?php echo $form['extra']; ?>" style="width: 100%;" maxlength="3" />
		</td>
	</tr>
	<tr>
		<td><?php echo $TEXT['DEFAULT_TEXT']; ?>:</td>
		<td>
			<input type="text" name="value" value="<?php echo $form['value']; ?>" style="width: 100%;" />
		</td>
	</tr>
	<?php } elseif($type == 'textarea') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['DEFAULT_TEXT']; ?>:</td>
		<td>
			<textarea name="value" style="width: 100%; height: 100px;"><?php echo $form['value']; ?></textarea>
		</td>
	</tr>
	<?php } elseif($type == 'select' OR $type = 'radio' OR $type = 'checkbox') { ?>
	<tr>
		<td valign="top"><?php echo 'List'; ?>:</td>
		<td>
			<?php
			$option_count = 0;
			$list = explode(',', $form['value']);
			foreach($list AS $option_value) {
				$option_count = $option_count+1;
				?>
				<table cellpadding="3" cellspacing="0" width="100%" border="0">
				<tr>
					<td width="70"><?php echo $TEXT['OPTION'].' '.$option_count; ?>:</td>
					<td>
						<input type="text" name="value<?php echo $option_count; ?>" value="<?php echo $option_value; ?>" style="width: 250px;" />
					</td>
				</tr>
				</table>
				<?php
			}
			for($i = 0; $i < 2; $i++) {
				$option_count = $option_count+1;
				?>
				<table cellpadding="3" cellspacing="0" width="100%" border="0">
				<tr>
					<td width="70"><?php echo $TEXT['OPTION'].' '.$option_count; ?>:</td>
					<td>
						<input type="text" name="value<?php echo $option_count; ?>" value="" style="width: 250px;" />
					</td>
				</tr>
				</table>
				<?php
			}
			?>
			<input type="hidden" name="list_count" value="<?php echo $option_count; ?>" />
		</td>
	</tr>
	<?php } ?>
	<?php if($type == 'select') { ?>
	<tr>
		<td><?php echo $TEXT['SIZE']; ?>:</td>
		<td>
			<?php $form['extra'] = explode(',',$form['extra']); ?>
			<input type="text" name="size" value="<?php echo trim($form['extra'][0]); ?>" style="width: 100%;" maxlength="3" />
		</td>
	</tr>
	<tr>
		<td><?php echo $TEXT['ALLOW_MULTIPLE_SELECTIONS']; ?>:</td>
		<td>
			<input type="radio" name="multiselect" id="multiselect_true" value="multiple" <?php if($form['extra'][1] == 'multiple') { echo ' checked'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('multiselect_true').checked = true;">
			<?php echo $TEXT['YES']; ?>
			</a>
			&nbsp;
			<input type="radio" name="multiselect" id="multiselect_false" value="" <?php if($form['extra'][1] == '') { echo ' checked'; } ?> />
			<a href="#" onclick="javascript: document.getElementById('multiselect_false').checked = true;">
			<?php echo $TEXT['NO']; ?>
			</a>
		</td>
	</tr>
	<?php } elseif($type == 'checkbox' OR $type == 'radio') { ?>
	<tr>
		<td valign="top"><?php echo $TEXT['SEPERATOR']; ?>:</td>
		<td>
			<input type="text" name="seperator" value="<?php echo $form['extra']; ?>" style="width: 100%;" />
		</td>
	</tr>
	<?php } ?>
<?php } ?>
<?php if($type != 'heading' AND $type != 'none') { ?>
<tr>
	<td><?php echo $TEXT['REQUIRED']; ?>:</td>
	<td>
		<input type="radio" name="required" id="required_true" value="1" <?php if($form['required'] == 1) { echo ' checked'; } ?> />
		<a href="#" onclick="javascript: document.getElementById('required_true').checked = true;">
		<?php echo $TEXT['YES']; ?>
		</a>
		&nbsp;
		<input type="radio" name="required" id="required_false" value="0" <?php if($form['required'] == 0) { echo ' checked'; } ?> />
		<a href="#" onclick="javascript: document.getElementById('required_false').checked = true;">
		<?php echo $TEXT['NO']; ?>
		</a>
	</td>
</tr>
<?php } ?>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="90">&nbsp;
		
	</td>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE'].' '.$TEXT['FIELD']; ?>" style="width: 200px; margin-top: 5px;"></form>
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CLOSE']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

<?php

// Print admin footer
$admin->print_footer();

?>