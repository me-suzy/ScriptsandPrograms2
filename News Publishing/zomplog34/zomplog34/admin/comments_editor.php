<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */

ob_start();
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

if(!$_SESSION["loggedIn"]){
?>

You are not allowed to view this page, please log in first.
<?
}
else
{


$comment = loadOneComment($_GET[id],$link,$table_comments);

if($_POST[addreview]){

if(!$_POST[name])
{
$messages[]="You did not fill out your name";
}

if(!$_POST[comment])
{
$messages[]="You did not write a comment";
}

if(!empty($messages)){
	displayErrors($messages);
}

if(empty($messages)) {
		changeComment($_GET[id],$link,$table_comments);
		
header("Location: comments_editor.php?id=$comment[id]&message=8");
ob_end_flush();		

	}

	}
	
if($_GET[message]){ 
displayMessage($_GET[message]);
  }	

?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="55%" valign="top"><form method="POST" enctype="multipart/form-data"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
      <tr>
        <td><h1><? echo "$lang_edit_comment"; ?></h1></td>
      </tr>
      <tr>
        <td>
        </td>
      </tr>
      <tr>
        <td><? echo "$lang_comment_name"; ?></td>
      </tr>
      <tr>
        <td><input name="name" type="text" id="name" value="<? echo "$comment[name]"; ?>"></td>
      </tr>
      <tr>
        <td class="title">&nbsp;</td>
      </tr>
      <tr>
        <td><? echo "$lang_comment"; ?></td>
      </tr>
      <tr>
        <td><textarea name="comment" cols="40" rows="5" id="comment"><? echo "$comment[comment]"; ?></textarea>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="submit" name="addreview" value="Submit"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></form></td>
    <td width="45%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
  </tr>
</table>
<?
	}
	include("footer.php");
	?>
