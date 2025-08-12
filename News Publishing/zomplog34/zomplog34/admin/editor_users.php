<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */

ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_GET[username],$link,$table_users);


if($_POST["Submit"]){
	
	field_validator("password", $_POST["password"], "string", 4, 15);
	field_validator("confirmation password", $_POST["password2"], "string", 4, 15);
	
	
	if(strcmp($_POST["password"], $_POST["password2"])) {
		
		$messages[]="Your passwords did not match";
	}
	
	if(!empty($messages)){
	displayErrors($messages);
}
	
	if(empty($messages)) {
		
		updateUser($user[id],$link,$table_users);
		
		header("Location: editor_users.php?username=$user[login]&message=4");

	}
}

if($_GET[message]){ 
displayMessage($_GET[message]);
  }
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="67%" valign="top"><form name="myform" method="post" enctype="multipart/form-data">
      <table width="101%"  border="0" cellspacing="0" cellpadding="0" class="text">
        <tr>
          <td><h1><? echo "$lang_editprofile: $user[login]"; ?></h1></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_username"; ?></span> <? echo "$user[login]"; ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_password"; ?></span></td>
        </tr>
        <tr>
          <td><input name="password" type="password" id="password" value="<? echo "$user[password]" ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_retype_password"; ?></span></td>
        </tr>
        <tr>
          <td><input name="password2" type="password" id="password2" value="<? echo "$user[password]" ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_name"; ?></span></td>
        </tr>
        <tr>
          <td><input name="name" type="text" id="name" value="<? echo "$user[name]"; ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_email"; ?></span></td>
        </tr>
        <tr>
          <td><input name="email" type="text" id="email" value="<? echo "$user[email]"; ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><span class="title"><? echo "$lang_about_me"; ?></span></td>
        </tr>
        <tr>
          <td><textarea name="about" cols="45" rows="8" id="about"><? echo "$user[about]"; ?></textarea></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><input type="submit" name="Submit" value="Submit"></td>
        </tr>
      </table>
    </form>
      </td>
    <td width="33%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
  </tr>
</table>
<? include("footer.php"); ?>