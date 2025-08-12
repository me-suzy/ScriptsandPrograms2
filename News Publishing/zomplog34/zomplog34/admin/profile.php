<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */

ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);
$moblog = loadMoblogSettings($link,$table_moblog);


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
		
		header("Location: profile.php?message=4");
		ob_end_flush();
	}
}

if($_GET[message]){ 
displayMessage($_GET[message]);
  }
?>
<form name="myform" method="post" enctype="multipart/form-data">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="57%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
        <tr>
          <td><h1><? echo "$lang_editprofile: $user[login]"; ?></h1></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><? echo "$lang_password"; ?></td>
        </tr>
        <tr>
          <td><input name="password" type="password" id="password" value="<? echo "$user[password]" ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><? echo "$lang_retype_password"; ?></td>
        </tr>
        <tr>
          <td><input name="password2" type="password" id="password2" value="<? echo "$user[password]" ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><? echo "$lang_name"; ?></td>
        </tr>
        <tr>
          <td><input name="name" type="text" id="name" value="<? echo "$user[name]"; ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><? echo "$lang_email"; ?>
              <? if($moblog[use_moblog]){
	echo "--> $lang_use_email_moblog. <a href='help.php?id=1'>$lang_what_is_moblogging</a>";
	}
	?></td>
        </tr>
        <tr>
          <td><input name="email" type="text" id="email" value="<? echo "$user[email]"; ?>"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><? echo "$lang_about_me"; ?></td>
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
      </table></td>
      <td width="43%" valign="top"><?php include('menu.php'); ?></td>
    </tr>
  </table>
</form>
<? include("footer.php"); ?>
