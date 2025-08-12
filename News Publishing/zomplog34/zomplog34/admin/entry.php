<?php
/* Written by Gerben Schmidt, http://scripts.zomp.nl */
include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

$user = loadUser($_SESSION['login'],$link,$table_users);

$query = "SELECT * FROM $table WHERE username = '$user[login]' ORDER BY id DESC";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$numrows = mysql_num_rows($result);
$entries = arrayMaker($result,MYSQL_ASSOC);

?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>

<form name="form1" method="post" action="schredder.php">
<input name="table" type="hidden" value="<? echo "$table"; ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="63%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
      <tr>
        <td colspan="6"><h1><? echo "$lang_edit_delete"; ?></h1></td>
        </tr>
		<? if($user[admin]){ ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="title"><? echo "$lang_your_entries"; ?></span></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
	  <? } ?>
      <tr>
        <td width="40%">
		<?
		if(!$numrows){
echo "$lang_no_results";
}	
?></td>
        <td width="5%">&nbsp;</td>
        <td width="10%">&nbsp;</td>
        <td width="15%">&nbsp;</td>
        <td width="15%">&nbsp;</td>
        <td width="15%">&nbsp;</td>
      </tr>
      <?  
	  
foreach($entries as $entry){

echo '<tr bgcolor="'.($i++ % 2 == 0 ? "#EEEEEE":"#FFFFFF").'">';
?>
      
       
          <td valign="top">
		<?
 
 echo "$entry[title]"; ?></td>
        <td valign="top">&nbsp;</td>
        <td valign="top"><? echo "$user[login]"; ?></td>
        <td valign="top"><?
		if($entry[catid]){
$query = "SELECT * FROM $table_cat WHERE id = $entry[catid]";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$cat = mysql_fetch_array($result,MYSQL_ASSOC);
echo "$cat[name]";
}

?></td>
        <td valign="top"><? echo "<a href='editor.php?id=$entry[id]'>$lang_edit</a>"; ?></td>
        <td valign="top"><input type="checkbox" name="id[]" value="<? echo "$entry[id]"; ?>"> <? echo "$lang_delete"; ?></td>
      </tr>
      <?
 }

 if($user[admin]){ 
 if(!$_GET[order]){
 $order = "id";
 }
 else
 {
 $order = $_GET[order];
 }
 
 $query = "SELECT * FROM $table WHERE username != '$_SESSION[login]' ORDER BY $order DESC";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$numrows = mysql_num_rows($result);
$entries = arrayMaker($result,MYSQL_ASSOC);
 ?>
       <tr>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td><input type="submit" name="Submit" value="<? echo "$lang_delete"; ?>"></td>
       </tr>
       <tr>
        <td colspan="6">&nbsp;</td>
        </tr>
		       <tr>
		         <td class="title"><? echo "$lang_entries_by_others"; ?></td>
		         <td>&nbsp;</td>
		         <td colspan="4" class="title"><select name="order" onChange="MM_jumpMenu('parent',this,0)">
                   <option selected><? echo "$lang_order_by"; ?></option>
                   <option value="entry.php?order=username"><? echo "$lang_username"; ?></option>
                   <option value="entry.php?order=id"><? echo "$lang_date"; ?></option>
                 </select></td>
	           </tr>
		       <tr>
        <td colspan="6">&nbsp;</td>
        </tr>
      <?  
	  
foreach($entries as $entry){
echo '<tr bgcolor="'.($i++ % 2 == 0 ? "#EEEEEE":"#FFFFFF").'">';
?>

      
          <td valign="top">
		<?
 $query = "SELECT * FROM $table_users WHERE login = '$entry[username]' LIMIT 1";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$entryuser = mysql_fetch_array($result);		
 
 echo "$entry[title]"; ?></td>
        <td valign="top">&nbsp;</td>
        <td valign="top"><? echo "$entryuser[login]"; ?></td>
        <td valign="top">&nbsp;</td>
        <td valign="top"><? echo "<a href='editor.php?id=$entry[id]'>$lang_edit</a> | <A HREF='schredder.php?tablename=$table&id=$entry[id]' onclick=\"return verify()\">$lang_delete</A>"; ?></td>
        <td valign="top"><input type="checkbox" name="id[]" value="<? echo "$entry[id]"; ?>">
          </td>
      </tr>
      <?
 } 
 }
 ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="submit" name="Submit" value="<? echo "$lang_delete"; ?>"></td>
      </tr>
    </table></td>
    <td width="4%">&nbsp;</td>
    <td width="33%" valign="top"><?php include('menu.php'); ?></td>
  </tr>
</table>
</form>
<? include("footer.php"); ?>