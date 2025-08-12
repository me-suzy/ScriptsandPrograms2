<?php

/* Written by Gerben Schmidt, http://scripts.zomp.nl */

ob_start();
include_once("admin/functions.php");
include_once("admin/config.php");
include("admin/session.php");
include('admin/loadsettings.php');
include("skins/$settings[skin]/header.php");	

checkLoggedIn("no");

if(!$settings[use_join]){
header("Location: index.php");
}

if($_POST["submit"]){
	
	field_validator("login name", $_POST["login"], "alphanumeric", 4, 15);
	field_validator("password", $_POST["password"], "string", 4, 15);
	field_validator("confirmation password", $_POST["password2"], "string", 4, 15);
	
	
	if(strcmp($_POST["password"], $_POST["password2"])) {
		
		$messages[]="Your passwords did not match";
	}

	
	$query="SELECT login FROM $table_users WHERE login='".$_POST["login"]."'";
	
	
	$result=mysql_query($query, $link) or die("MySQL query $query failed.  Error if any: ".mysql_error());
	

	if( ($row=mysql_fetch_array($result)) ){
		$messages[]="Login ID \"".$_POST["login"]."\" already exists.  Try another.";
	}

	
	if(empty($messages)) {
		
		newUser($_POST["login"], $_POST["password"]);

		
		cleanMemberSession($_POST["login"], $_POST["password"]);

		
		header("Location: admin/members.php?".session_name()."=".session_id());

	}
}


if(!empty($messages)){
	displayErrors($messages);
}
?>
<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
  <table class="text">
    <tr> 
      <td>Login:</td>
      <td><input type="text" name="login" value="<?php print $_POST["login"] ?>" maxlength="15"></td>
    </tr>
    <tr> 
      <td>Password:</td>
      <td><input type="password" name="password" value="" maxlength="15"></td>
    </tr>
    <tr> 
      <td>Confirm password:</td>
      <td><input type="password" name="password2" value="" maxlength="15"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="submit" type="submit" value="Submit"></td>
    </tr>
  </table>
</form>
<? include("skins/$settings[skin]/footer.php"); ?>
