<?php

// $Id: view_submission.php 125 2005-09-18 23:07:06Z ryan $

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
if(!isset($_GET['submission_id']) OR !is_numeric($_GET['submission_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$submission_id = $_GET['submission_id'];
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Get submission details
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_form_submissions WHERE submission_id = '$submission_id'");
$submission = $query_content->fetchRow();

// Get the user details of whoever did this submission
$query_user = "SELECT username,display_name FROM ".TABLE_PREFIX."users WHERE user_id = '".$submission['submitted_by']."'";
$get_user = $database->query($query_user);
if($get_user->numRows() != 0) {
	$user = $get_user->fetchRow();
} else {
	$user['display_name'] = 'Unknown';
	$user['username'] = 'unknown';
}

?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td><?php echo $TEXT['SUBMISSION_ID']; ?>:</td>
	<td><?php echo $submission['submission_id']; ?></td>
</tr>
<tr>
	<td><?php echo $TEXT['SUBMITTED']; ?>:</td>
	<td><?php echo gmdate(TIME_FORMAT.', '.DATE_FORMAT, $submission['submitted_when']+TIMEZONE); ?></td>
</td>
<tr>
	<td><?php echo $TEXT['USER']; ?>:</td>
	<td><?php echo $user['display_name'].' ('.$user['username'].')'; ?></td>
</tr>
<tr>
	<td colspan="2">
		<hr />
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php echo nl2br($submission['body']); ?>
	</td>
</tr>
</table>

<br />

<input type="button" value="<?php echo $TEXT['CLOSE']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 150px; margin-top: 5px;" />
<input type="button" value="<?php echo $TEXT['DELETE']; ?>" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/form/delete_submission.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&submission_id=<?php echo $submission_id; ?>');" style="width: 150px; margin-top: 5px;" />
<?php

// Print admin footer
$admin->print_footer();

?>