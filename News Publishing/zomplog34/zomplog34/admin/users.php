<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */
ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);

$query = "SELECT * FROM $table_users ORDER BY id ASC";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$users = arrayMaker($result,MYSQL_ASSOC);

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
		
		newUser();

		header("Location: users.php?message=9");
		ob_end_flush();
	}
}

if($_GET[message]){ 
displayMessage($_GET[message]);
  }

if(!empty($messages)){
	displayErrors($messages);
}
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="69%" valign="top"><form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
      <table width="447" border="0" cellspacing="0" class="text">
        <tr>
          <td colspan="2"><h1><? echo "$lang_manage_users"; ?></h1></td>
        </tr>
        <tr>
          <td colspan="2" class="title">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" class="title"><? echo "$lang_create_user"; ?></td>
        </tr>
        <tr>
          <td width="150"><? echo "$lang_username"; ?></td>
          <td width="293"><input type="text" name="login" value="<?php print $_POST["login"] ?>" maxlength="15"></td>
        </tr>
        <tr>
          <td><? echo "$lang_password"; ?></td>
          <td><input type="password" name="password" value="" maxlength="15"></td>
        </tr>
        <tr>
          <td><? echo "$lang_retype_password"; ?></td>
          <td><input type="password" name="password2" value="" maxlength="15"></td>
        </tr>
        <tr>
          <td><? echo "$lang_administrator"; ?></td>
          <td><input name="admin" type="checkbox" id="admin" value="1"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="submit" type="submit" value="Submit"></td>
        </tr>
      </table>
    </form>
      <table width="448"  border="0" cellspacing="0" cellpadding="0" class="text">
        <tr>
          <td width="29%" class="title"><? echo "$lang_current_users"; ?></td>
          <td width="49%">&nbsp;</td>
          <td width="22%">&nbsp;</td>
        </tr>
        <?
foreach($users as $user){
echo '<tr bgcolor="'.($i++ % 2 == 0 ? "#EEEEEE":"#FFFFFF").'">';
?>

          <td><? echo "$user[login]"; ?></td>
          <td><? 
	if($user[admin]){
	echo "$lang_administrator"; 
	}
	else
	{
	echo "$lang_user"; 
	}
	?></td>
          <td><? echo "<a href='editor_users.php?username=$user[login]'>$lang_edit</a> | <A HREF='schredder.php?tablename=$table_users&id=$user[id]' onclick=\"return verify()\">$lang_delete</A>"; ?></td>
        </tr>
        <?
 }
 ?>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="31%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
  </tr>
</table>
<? include("footer.php"); ?>