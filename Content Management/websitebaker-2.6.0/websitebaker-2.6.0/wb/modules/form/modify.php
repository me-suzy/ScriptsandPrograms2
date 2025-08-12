<?php

// $Id: modify.php 103 2005-09-14 07:19:34Z ryan $

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

// Must include code to stop this file being access directly
if(!defined('WB_PATH')) { exit("Cannot access this file directly"); }

?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" width="33%">
		<input type="button" value="<?php echo $TEXT['ADD'].' '.$TEXT['FIELD']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/form/add_field.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
	<td align="right" width="33%">
		<input type="button" value="<?php echo $TEXT['SETTINGS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/form/modify_settings.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
</tr>
</table>

<br />

<h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$TEXT['FIELD']; ?></h2>
<?php

// Loop through existing fields
$query_fields = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_form_fields` WHERE section_id = '$section_id' ORDER BY position ASC");
if($query_fields->numRows() > 0) {
	$num_fields = $query_fields->numRows();
	$row = 'a';
	?>
	<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<?php
	while($field = $query_fields->fetchRow()) {
		?>
		<tr class="row_<?php echo $row; ?>" height="20">
			<td width="20" style="padding-left: 5px;">
				<a href="<?php echo WB_URL; ?>/modules/form/modify_field.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&field_id=<?php echo $field['field_id']; ?>" title="<?php echo $TEXT['MODIFY']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="^" />
				</a>
			</td>		
			<td>
				<a href="<?php echo WB_URL; ?>/modules/form/modify_field.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&field_id=<?php echo $field['field_id']; ?>">
					<?php echo $field['title']; ?>
				</a>
			</td>
			<td width="175">
				<?php
				echo $TEXT['TYPE'].':';
				if($field['type'] == 'textfield') {
					echo $TEXT['SHORT_TEXT'];
				} elseif($field['type'] == 'textarea') {
					echo $TEXT['LONG_TEXT'];
				} elseif($field['type'] == 'heading') {
					echo $TEXT['HEADING'];
				} elseif($field['type'] == 'select') {
					echo $TEXT['SELECT_BOX'];
				} elseif($field['type'] == 'checkbox') {
					echo $TEXT['CHECKBOX_GROUP'];
				} elseif($field['type'] == 'radio') {
					echo $TEXT['RADIO_BUTTON_GROUP'];
				} elseif($field['type'] == 'email') {
					echo $TEXT['EMAIL_ADDRESS'];
				}
				?>
			</td>
			<td width="95">		
			<?php 
			if ($field['type'] != 'group_begin') {
				echo $TEXT['REQUIRED'].': '; if($field['required'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; }
			}
			?>
			</td>
			<td width="110">
			<?php
			if ($field['type'] == 'select') {
				$field['extra'] = explode(',',$field['extra']);
				echo $TEXT['MULTISELECT'].': '; if($field['extra'][1] == 'multiple') { echo $TEXT['YES']; } else { echo $TEXT['NO']; }
			}
			?>
			</td>
			<td width="20">
			<?php if($field['position'] != 1) { ?>
				<a href="<?php echo WB_URL; ?>/modules/form/move_up.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&field_id=<?php echo $field['field_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/up_16.png" border="0" alt="^" />
				</a>
			<?php } ?>
			</td>
			<td width="20">
			<?php if($field['position'] != $num_fields) { ?>
				<a href="<?php echo WB_URL; ?>/modules/form/move_down.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&field_id=<?php echo $field['field_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/down_16.png" border="0" alt="v" />
				</a>
			<?php } ?>
			</td>
			<td width="20">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/form/delete_field.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&field_id=<?php echo $field['field_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		<?php
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	?>
	</table>
	<?php
} else {
	echo $TEXT['NONE_FOUND'];
}

?>

<br /><br />

<h2><?php echo $TEXT['SUBMISSIONS']; ?></h2>

<?php

// Query submissions table
$query_submissions = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_form_submissions` WHERE section_id = '$section_id' ORDER BY submitted_when ASC");
if($query_submissions->numRows() > 0) {
	?>
	<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<?php
	// List submissions
	$row = 'a';
	while($submission = $query_submissions->fetchRow()) {
		?>
		<tr class="row_<?php echo $row; ?>" height="20">
			<td width="20" style="padding-left: 5px;">
				<a href="<?php echo WB_URL; ?>/modules/form/view_submission.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&submission_id=<?php echo $submission['submission_id']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/folder_16.png" alt="<?php echo $TEXT['OPEN']; ?>" border="0" />
				</a>
			</td>
			<td width="237"><?php echo $TEXT['SUBMISSION_ID'].': '.$submission['submission_id']; ?></td>
			<td><?php echo $TEXT['SUBMITTED'].': '.gmdate(TIME_FORMAT.', '.DATE_FORMAT, $submission['submitted_when']+TIMEZONE); ?></td>
			<td width="20">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/form/delete_submission.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&submission_id=<?php echo $submission['submission_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		<?php
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	?>
	</table>
	<?php
} else {
	echo $TEXT['NONE_FOUND'];
}

?>

<br />