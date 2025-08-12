<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);

$query = "SELECT * FROM $table_pages ORDER BY id DESC";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$numrows = mysql_num_rows($result);
$pages = arrayMaker($result,MYSQL_ASSOC);

?>
<form name="form1" method="post" action="schredder.php">
<input name="table" type="hidden" value="<? echo "$table_pages"; ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="61%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
      <tr>
        <td colspan="3"><h1><? echo "$lang_edit_delete_page"; ?></h1></td>
        </tr>
      <tr>
        <td width="68%"><?
		if(!$numrows){
echo "$lang_no_results";
}	
?></td>
        <td width="15%">&nbsp;</td>
        <td width="17%">&nbsp;</td>
      </tr>
      <?
foreach($pages as $page){
echo '<tr bgcolor="'.($i++ % 2 == 0 ? "#EEEEEE":"#FFFFFF").'">';
?>

        <td><? echo "$page[title]"; ?></td>
        <td><? echo "<a href='editor_pages.php?id=$page[id]'>$lang_edit</a>"; ?></td>
        <td><input type="checkbox" name="id[]" value="<? echo "$page[id]"; ?>"></td>
      </tr>
      <?
 }
 ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="submit" name="Submit" value="delete"></td>
      </tr>
    </table></td>
    <td width="39%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
  </tr>
</table>
</form>
<? include("footer.php"); ?>