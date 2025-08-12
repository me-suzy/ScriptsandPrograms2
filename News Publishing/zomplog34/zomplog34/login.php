<?php

/* Written by Gerben Schmidt, http://scripts.zomp.nl */
ob_start();
include_once("admin/functions.php");
include_once("admin/config.php");
include("admin/session.php");
include('admin/loadsettings.php');
include("skins/$settings[skin]/header.php");	

checkLoggedIn("no");


if($_POST["submit"]) {
	
	field_validator("login name", $_POST["login"], "alphanumeric", 4, 15);
	
	field_validator("password", $_POST["password"], "string", 4, 15);


	if($messages){ 
		doIndex();
		
		exit;
	}

    if( !($row = checkPass($_POST["login"], $_POST["password"])) ) {
		
        $messages[]="Incorrect login/password, try again";
    } 

	
	if($messages){
		doIndex();
		exit;
	}

	
	cleanMemberSession($row[login], $row[password]);

	header("Location: admin/members.php?".session_name()."=".session_id());
} else {	
	
	doIndex();
}


function doIndex() {
	
	global $messages;



if($messages) { displayErrors($messages); }

?>
<br />
<form action="<?=$_SERVER["admin/PHP_SELF"]?>" method="POST">
  <table class="text">
    <tr> 
      <td class='zomplog'>Login:</td>
      <td><input type="text" name="login" value="<?php print $_POST["login"] ?>" maxlength="15"></td>
    </tr>
    <tr> 
      <td class='zomplog'>Password:</td>
      <td><input type="password" name="password" value="" maxlength="15"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="submit" type="submit" value="Submit"></td>
    </tr>
  </table>
</form>
<br />
<?php 
}

include("skins/$settings[skin]/footer.php"); ?>
