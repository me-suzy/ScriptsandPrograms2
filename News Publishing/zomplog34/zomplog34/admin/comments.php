<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */

include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

 if($_GET[message] && empty($messages)){
displayMessage($_GET[message]);
  }

$comments = loadAllComments($link,$table_comments);
?>
<form name="form1" method="post" action="schredder.php">
<input name="table" type="hidden" value="<? echo "$table_comments"; ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="64%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
      <tr>
        <td colspan="5"><h1><? echo "$lang_manage_comments"; ?></h1></td>
        </tr>
      <tr>
        <td width="35%"><?
		if(!$comments){
echo "$lang_no_results";
}	
?></td>
        <td width="31%">&nbsp;</td>
        <td width="10%">&nbsp;</td>
        <td width="9%">&nbsp;</td>
        <td width="15%">&nbsp;</td>
        </tr>
      <?
foreach($comments as $comment){
echo '<tr bgcolor="'.($i++ % 2 == 0 ? "#EEEEEE":"#FFFFFF").'">';
?>
        <td><? echo "$comment[name]"; ?></td>
          <td><? echo "$comment[ip]"; ?></td>
          <td><a href="<? echo "ban.php?ip=$comment[ip]"; ?>"><? echo "$lang_ban"; ?></a></td>
          <td><? echo "<a href='comments_editor.php?id=$comment[id]'>$lang_edit</a>"; ?></td>
        <td><input type="checkbox" name="id[]" value="<? echo "$comment[id]"; ?>"></td>
        </tr>
      <?
 }
 ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="submit" name="Submit" value="delete"></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5"><h1>banned ip-adresses</h1></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
	  <?
	  $query = "SELECT ip FROM $table_banned";
$result = mysql_query($query,$link) or die("Could not load banned ip information.");
$banned = mysql_fetch_array($result,MYSQL_ASSOC);

if(!$banned){
?>
<tr>
        <td colspan="5"><? echo "$lang_no_results"; ?></td>
  
     </tr>
	 <?
}
else
{

foreach($banned as $ip){
echo '<tr bgcolor="'.($i++ % 2 == 0 ? "#EEEEEE":"#FFFFFF").'">';
?>
	  
        <td><? echo "$ip"; ?></td>
        <td><a href="<? echo "ban.php?undo=$ip"; ?>">undo ban</a></td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
<?
}
}
?>	  
    </table></td>
    <td width="36%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
  </tr>
</table>
</form>
<?

include('footer.php');
?>