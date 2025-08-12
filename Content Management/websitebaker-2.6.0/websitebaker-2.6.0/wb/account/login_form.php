<?php

// $Id: login_form.php 240 2005-11-23 15:17:50Z stefan $

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

if(defined('SMART_LOGIN') AND SMART_LOGIN == 'enabled') {
	// Generate username field name
	$username_fieldname = 'username_';
	$password_fieldname = 'password_';
	$salt = "abchefghjkmnpqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	$i = 0;
	while ($i <= 7) {
		$num = rand() % 33;
		$tmp = substr($salt, $num, 1);
		$username_fieldname = $username_fieldname . $tmp;
		$password_fieldname = $password_fieldname . $tmp;
		$i++;
	}
} else {
	$username_fieldname = 'username';
	$password_fieldname = 'password';
}

?>
<style>
.value_input input, .value_input text, .value_input select {
	width: 220px;
}
</style>

<h1>&nbsp;Login</h1>
&nbsp;<?php echo $thisApp->message; ?>
<br />
<br />

<form name="login" action="<?php echo WB_URL.'/account/login'.PAGE_EXTENSION; ?>" method="post">
<input type="hidden" name="username_fieldname" value="<?php echo $username_fieldname; ?>" />
<input type="hidden" name="password_fieldname" value="<?php echo $password_fieldname; ?>" />
<input type="hidden" name="redirect" value="<?php echo $thisApp->redirect_url;?>" />

<table cellpadding="5" cellspacing="0" border="0" width="90%">
<tr>
	<td width="100"><?php echo $TEXT['USERNAME']; ?>:</td>
	<td class="value_input">
		<input type="text" name="<?php echo $username_fieldname; ?>" maxlength="30" />
		<script type="text/javascript" language="javascript">
		document.login.<?php echo $username_fieldname; ?>.focus();
		</script>
	</td>
</tr>
<tr>
	<td width="100"><?php echo $TEXT['PASSWORD']; ?>:</td>
	<td class="value_input">
		<input type="password" name="<?php echo $password_fieldname; ?>" maxlength="30" />
	</td>
</tr>
<?php if($username_fieldname != 'username') { ?>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="checkbox" name="remember" id="remember" value="true" />
		<label for="remember">
			<?php echo $TEXT['REMEMBER_ME']; ?>
		</label>
	</td>
</tr>
<?php } ?>
<tr>
	<td>&nbsp;</td>
	<td>
		<input type="submit" name="submit" value="<?php echo $TEXT['LOGIN']; ?>" />
		<input type="reset" name="reset" value="<?php echo $TEXT['RESET']; ?>" />
	</td>
</tr>
</table>

</form>

<br />

<a href="<?php echo WB_URL; ?>/account/forgot.php"><?php echo $TEXT['FORGOTTEN_DETAILS']; ?></a>