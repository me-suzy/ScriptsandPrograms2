<?php

/* Written by Gerben Schmidt, http://scripts.zomp.nl */
ob_start();

include_once("functions.php");
include('config.php');
include("session.php");
include("header.php");

checkLoggedIn("yes");


if($_POST["addcat"]){
	
	if(!$_POST[catname]){
		$messages[]="You did not fill out a category name";
	}
	
if(!empty($messages)){
	displayErrors($messages);
}	
	
	if(empty($messages)) {
		
		newCat($link,$table_cat);
		
header("Location: members.php?message=6");
ob_end_flush();		
		

	}
}

if($_POST["editcat"]){
	
	if(!$_POST[catname]){
		$messages[]="You did not fill out a category name";
	}
	
if(!empty($messages)){
	displayErrors($messages);
}	
	
	if(empty($messages)) {

changeCat($link,$table_cat);

header("Location: members.php?message=7");
ob_end_flush();		


	}
}


// add category form



?>

	<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
    </script>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="62%" valign="top"><form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
          <table width="421" border="0" class="text">
            <tr>
              <td colspan="2" class="title"><h1><? echo "$lang_manage_categories"; ?></h1></td>
            </tr>
            <tr>
              <td colspan="2" class="title">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" class="title"><? echo "$lang_new_category"; ?></td>
            </tr>
            <tr>
              <td width="165"><? echo "$lang_category_name"; ?></td>
              <td width="246"><input name="catname" type="text" id="catname"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><input type="submit" name="addcat" value="Submit"></td>
            </tr>
          </table>
        </form>
          <form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
            <table width="422" border="0" class="text">
              <tr>
                <td colspan="3" class="title"><? echo "$lang_edit_category"; ?></td>
              </tr>
              <tr>
                <td><? echo "$lang_choose_category"; ?></td>
                <td><select name="catid" style="width: 140px" onChange="MM_jumpMenu('parent',this,0)">
                    <option selected><? echo "$lang_category"; ?></option>
                    <?
	  
$cat_array = loadCat($link,$table_cat);
foreach ($cat_array as $mycat){

echo '<option value="category.php?catid='.$mycat["id"].'">'.$mycat["name"];
}

?>
                </select></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="165"><? echo "$lang_edit_category_name"; ?></td>
                <?
if($_GET[catid]){
$category = loadOnecat($link,$table_cat);
}
?>
                <td width="226"><input name="catname" type="text" value="<? print($category['name']); ?>"></td>
                <td width="17"><input type="hidden" name="id" value="<? print($category['id']); ?>"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="editcat" value="Submit"></td>
              </tr>
            </table>
          </form>
          <table width="100%" border="0" cellpadding="0" cellspacing="0" class="text">
            <tr>
              <td colspan="2" class="title"><? echo "$lang_delete_category"; ?></td>
            </tr>
            <?
  foreach ($cat_array as $mycat){
  echo '<tr bgcolor="'.($i++ % 2 == 0 ? "#EEEEEE":"#FFFFFF").'">';
  ?>

              <td width="434"><? echo "$mycat[name]"; ?></td>
              <td width="135"><? echo "<A HREF='schredder.php?tablename=$table_cat&id=$mycat[id]' onclick=\"return verify()\">$lang_delete</A>"; ?></td>
              </tr>
            <? } ?>
          </table>
        </td>
        <td width="38%" valign="top"><?php include('menu.php'); ?>&nbsp;</td>
      </tr>
    </table>
	<? include("footer.php"); ?>