<?php

// $Id: signup_form.php 253 2005-11-27 12:43:22Z ryan $

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

<style>
.value_input input, .value_input text, .value_input select {
	width: 300px;
}
</style>

<h1>&nbsp;<?php echo $TEXT['SIGNUP']; ?></h1>

<form name="user" action="<?php echo WB_URL.'/account/signup'.PAGE_EXTENSION; ?>" method="post">

<table cellpadding="5" cellspacing="0" border="0" width="90%">
<tr>
	<td width="180"><?php echo $TEXT['USERNAME']; ?>:</td>
	<td class="value_input">
		<input type="text" name="username" maxlength="30" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['DISPLAY_NAME']; ?> (<?php echo $TEXT['FULL_NAME']; ?>):</td>
	<td class="value_input">
		<input type="text" name="display_name" maxlength="255" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['EMAIL']; ?>:</td>
	<td class="value_input">
		<input type="text" name="email" maxlength="255" />
	</td>
</tr>
<?php
// Captcha
if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { /* Make's sure GD library is installed */
	if(CAPTCHA_VERIFICATION == true) {
		$_SESSION['captcha'] = '';
		for($i = 0; $i < 5; $i++) {
			$_SESSION['captcha'] .= rand(0,9);
		}
		?><tr><td class="field_title"><?php echo $TEXT['VERIFICATION']; ?>:</td><td>
		<table cellpadding="2" cellspacing="0" border="0">
		<tr><td><img src="<?php echo WB_URL; ?>/include/captcha.php" alt="Captcha" /></td>
		<td><input type="text" name="captcha" maxlength="5" /></td>
		</tr></table>
		</td></tr>
		<?php
	}
}
?>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['SIGNUP']; ?>" />
		<input type="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>" />
	</td>
</tr>
</table>

</form>

<br />
&nbsp; 