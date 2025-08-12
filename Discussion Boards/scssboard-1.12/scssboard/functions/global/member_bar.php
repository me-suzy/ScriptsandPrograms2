<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<div class='member_bar'>
	<span class='nav_buttons_container'>
	<? 
	if (!$current_user) {
	if ($_MAIN[allow_signups] == "yes") {
		echo "<span class='main_button'><a href='index.php?&amp;act=register'>Register</a></span>&nbsp;";
	} else {
		echo "<span class='main_button'><a href='index.php'>Registration Disabled</a></span>&nbsp;";
	} }?>
	<span class='nav_button'><a href='index.php?&amp;act=users'>Members</a></span>&nbsp;
	<span class='nav_button'><a href='index.php?&amp;act=search'>Search</a></span>
	</span>
	<?php 
	if(!$current_user) { 
		echo "
		<form method='post' action='index.php?&amp;act=login'>Username: &nbsp; <input type='text' name='username' size='20' class='small_input' />&nbsp; 
		Password: &nbsp; <input type='password' name='password' size='20' class='small_input' />&nbsp; 
		Remember Me <input type='checkbox' name='remember_me' checked='checked' style='position:relative; top:2px;' />&nbsp; 
		<input type='submit' value='Login' class='form_button' /></form>";
	} 
	if($current_user) { 
		echo "Logged in as <strong>$current_user[users_username]</strong>
		&nbsp;[ <a href='index.php?&amp;act=logout'>Logout</a> ]
		&nbsp;[ <a href='index.php?&amp;act=profile'>Profile</a> ]"; 
	} 
	if($current_user[users_level] >= 3) {
		echo "&nbsp; [ <a href='index.php?act=admin-home'>Admin CP</a> ]";
	} ?>
</div>