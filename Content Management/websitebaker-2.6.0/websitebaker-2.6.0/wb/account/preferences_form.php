<?php

// $Id: preferences_form.php 239 2005-11-22 11:50:41Z stefan $

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
	header('Location: ../index.php');
}

?>

<h1>&nbsp;<?php echo $HEADING['MY_SETTINGS']; ?></h1>

<form name="user" action="<?php echo WB_URL.'/account/preferences.php'; ?>" method="post" style="margin-bottom: 5px;">
<input type="hidden" name="user_id" value="{USER_ID}" />

<table cellpadding="5" cellspacing="0" border="0" width="97%">
<tr>
	<td width="140"><?php echo $TEXT['DISPLAY_NAME']; ?>:</td>
	<td class="value_input">
		<input type="text" name="display_name" style="width: 380px;" maxlength="255" value="<?php echo $admin->get_display_name(); ?>" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['LANGUAGE']; ?>:</td>
	<td>
		<select name="language" style="width: 380px;">
		<?php
		// Insert language values
		if($handle = opendir(WB_PATH.'/languages/')) {
		   while (false !== ($file = readdir($handle))) {
				if($file != '.' AND $file != '..' AND $file != '.svn' AND $file != 'index.php') {
					// Get language name
					require(WB_PATH.'/languages/'.$file);
					// Check if it is selected
					if(LANGUAGE == $language_code) {
						?>
						<option value="<?php echo $language_code; ?>" selected><?php echo $language_name.' ('.$language_code.')'; ?></option>
						<?php
					} else {
						?>
						<option value="<?php echo $language_code; ?>"><?php echo $language_name.' ('.$language_code.')'; ?></option>
						<?php
					}
				}
			}
			// Restore language to original file
			require(WB_PATH.'/languages/'.LANGUAGE.'.php');
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['TIMEZONE']; ?>:</td>
	<td>
		<select name="timezone" style="width: 380px;">
			<option value="-20"><?php echo $TEXT['PLEASE_SELECT']; ?>...</option>
			<?php
			// Insert default timezone values
			require_once(ADMIN_PATH.'/interface/timezones.php');
			foreach($TIMEZONES AS $hour_offset => $title) {
				if($admin->get_timezone() == $hour_offset*60*60) {
					?>
					<option value="<?php echo $hour_offset; ?>" selected><?php echo $title; ?></option>
					<?php
				} else {
					?>
					<option value="<?php echo $hour_offset; ?>"><?php echo $title; ?></option>
					<?php
				}
			}
			?>
		</select>
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['DATE_FORMAT']; ?>:</td>
	<td>
		<select name="date_format" style="width: 98%;">
			<option value="">Please select...</option>
			<?php
			// Insert date format list
			$user_time = true;
			require_once(ADMIN_PATH.'/interface/date_formats.php');
			foreach($DATE_FORMATS AS $format => $title) {
				$format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
				if($format != 'system_default') {
					$value = $format;
				} else {
					$value = '';
				}
				if(DATE_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
					$selected = ' selected';
				} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
					$selected = ' selected';
				} else {
					$selected = '';
				}
				echo '<option value="'.$value.'"'.$selected.'>'.$title.'</option>';
			}
			?>>
		</select>
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['TIME_FORMAT']; ?>:</td>
	<td>
		<select name="time_format" style="width: 98%;">
			<option value="">Please select...</option>
			<?php
			// Insert time format list
			$user_time = true;
			require_once(ADMIN_PATH.'/interface/time_formats.php');
			foreach($TIME_FORMATS AS $format => $title) {
				$format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
				if($format != 'system_default') {
					$value = $format;
				} else {
					$value = '';
				}
				if(TIME_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
					$selected = ' selected';
				} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
					$selected = ' selected';
				} else {
					$selected = '';
				}
				echo '<option value="'.$value.'"'.$selected.'>'.$title.'</option>';
			}
			?>
		</select>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['SAVE']; ?>" />
		<input type="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>" />
	</td>
</tr>
</table>

</form>


<h1>&nbsp;<?php echo $HEADING['MY_EMAIL']; ?></h1>

<form name="email" action="<?php echo WB_URL.'/account/preferences.php'; ?>" method="post" style="margin-bottom: 5px;">
<input type="hidden" name="user_id" value="{USER_ID}" />

<table cellpadding="5" cellspacing="0" border="0" width="97%">
<tr>
	<td width="140"><?php echo $TEXT['CURRENT_PASSWORD']; ?>:</td>
	<td>
		<input type="password" name="current_password" style="width: 380px;" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['EMAIL']; ?>:</td>
	<td class="value_input">
		<input type="text" name="email" style="width: 380px;" maxlength="255" value="<?php echo $admin->get_email(); ?>" />
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['SAVE']; ?>" />
		<input type="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>" />
	</td>
</tr>
</table>

</form>


<h1>&nbsp;<?php echo $HEADING['MY_PASSWORD']; ?></h1>

<form name="user" action="<?php echo WB_URL.'/account/preferences.php'; ?>" method="post">
<input type="hidden" name="user_id" value="{USER_ID}" />

<table cellpadding="5" cellspacing="0" border="0" width="97%">
<tr>
	<td width="140"><?php echo $TEXT['CURRENT_PASSWORD']; ?>:</td>
	<td>
		<input type="password" name="current_password" style="width: 380px;" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['NEW_PASSWORD']; ?>:</td>
	<td>
		<input type="password" name="new_password" style="width: 380px;" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['RETYPE_NEW_PASSWORD']; ?>:</td>
	<td>
		<input type="password" name="new_password2" style="width: 380px;" />
	</td>
</tr>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['SAVE']; ?>" />
		<input type="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>" />
	</td>
</tr>
</table>

</form>