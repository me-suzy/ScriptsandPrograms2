<?php

// $Id: forgot_form.php 239 2005-11-22 11:50:41Z stefan $

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

if(!defined('WB_URL')) {
	header('Location: ../pages/index.php');
}

// Create new database object
$database = new database();

// Check if the user has already submitted the form, otherwise show it
if(isset($_POST['email']) AND $_POST['email'] != "") {
	
	$email = $_POST['email'];
	
	// Check if the email exists in the database
	$query = "SELECT user_id,username,display_name,email,last_reset FROM ".TABLE_PREFIX."users WHERE email = '".$wb->add_slashes($_POST['email'])."'";
	$results = $database->query($query);
	if($results->numRows() > 0) {
		// Get the id, username, and email from the above db query
		$results_array = $results->fetchRow();
		
		// Check if the password has been reset in the last 2 hours
		$last_reset = $results_array['last_reset'];
		$time_diff = mktime()-$last_reset; // Time since last reset in seconds
		$time_diff = $time_diff/60/60; // Time since last reset in hours
		if($time_diff < 2) {
			
			// Tell the user that their password cannot be reset more than once per hour
			$message = $MESSAGE['FORGOT_PASS']['ALREADY_RESET'];
			
		} else {
		
			// Generate a random password then update the database with it
			$new_pass = '';
			$salt = "abchefghjkmnpqrstuvwxyz0123456789";
			srand((double)microtime()*1000000);
			$i = 0;
			while ($i <= 7) {
				$num = rand() % 33;
				$tmp = substr($salt, $num, 1);
				$new_pass = $new_pass . $tmp;
				$i++;
			}
			
			$database->query("UPDATE ".TABLE_PREFIX."users SET password = '".md5($new_pass)."' WHERE user_id = '".$results_array['user_id']."'");
			
			if($database->is_error()) {
				// Error updating database
				$message = $database->get_error();
			} else {
				// Setup email to send
				$mail_subject = 'Your login details...';
				$mail_to = $email;
				$mail_message = ''.
'Hello '.$results_array["display_name"].', 

Your '.WEBSITE_TITLE.' administration login details are:
Username: '.$results_array["username"].'
Password: '.$new_pass.'

Your password has been reset to the one above.
This means that your old password will no longer work.

If you have received this message in error, please delete it immediatly.';
				// Try sending the email
				if(mail($mail_to, $mail_subject, $mail_message)) {
					$message = $MESSAGE['FORGOT_PASS']['PASSWORD_RESET'];
					$display_form = false;
				} else {
					$message = $MESSAGE['FORGOT_PASS']['CANNOT_EMAIL'];
				}
			}
		}	
	} else {
		// Email doesn't exist, so tell the user
		$message = $MESSAGE['FORGOT_PASS']['EMAIL_NOT_FOUND'];
	}
	
} else {
	$email = '';
}

if(!isset($message)) {
	$message = $MESSAGE['FORGOT_PASS']['NO_DATA'];
	$message_color = '000000';
} else {
	$message_color = 'FF0000';
}
	
?>
<h1 style="text-align: center;"><?php echo $MENU['FORGOT']; ?></h1>

<form name="forgot_pass" action="<?php echo WB_URL.'/account/forgot'.PAGE_EXTENSION; ?>" method="post">
	<input type="hidden" name="url" value="{URL}" />
		<table cellpadding="5" cellspacing="0" border="0" align="center" width="500">
		<tr>
			<td height="40" align="center" style="color: #<?php echo $message_color; ?>;" colspan="2">
			<?php echo $message; ?>
			</td>
		</tr>
		<?php if(!isset($display_form) OR $display_form != false) { ?>
		<tr>
			<td height="10" colspan="2"></td>
		</tr>
		<tr>
			<td width="165" height="30" align="right"><?php echo $TEXT['EMAIL']; ?>:</td>
			<td><input type="text" maxlength="30" name="email" value="<?php echo $email; ?>" style="width: 180px;" /></td>
		</tr>
		<tr height="30">
			<td>&nbsp;</td>
			<td><input type="submit" name="submit" value="<?php echo $TEXT['SEND_DETAILS']; ?>" style="width: 180px; font-size: 10px; color: #003366; border: 1px solid #336699; background-color: #DDDDDD; padding: 3px; text-transform: uppercase;"></td>
		</tr>
		<tr style="display: {DISPLAY_FORM}">
			<td height="10" colspan="2"></td>
		</tr>
		<?php } ?>
		</table>
</form>