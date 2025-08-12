<?php

if ($HTTP_POST_VARS['mode'] == 'subcategories') {
    header("Location: subcategories.php?lstParentCat=".$HTTP_POST_VARS['cat_par']."");
}

include_once('../includes/db_connect.php');
check_admin();

$mode = (!empty($HTTP_POST_VARS['mode']))?$HTTP_POST_VARS['mode']:"view";
$parCategory = (!empty($HTTP_POST_VARS['lstParentCat']))?$HTTP_POST_VARS['lstParentCat']:"";
$cat_id = (!empty($HTTP_POST_VARS['cat_id']))?$HTTP_POST_VARS['cat_id']:"";

switch ($mode) {
    case 'addcategory':
//        if (!empty($HTTP_POST_VARS['newCategory'])) {
//            $newCategory=$HTTP_POST_VARS['newCategory'];
//            $newParCategory=(!empty($HTTP_POST_VARS['newParCategory']))?$HTTP_POST_VARS['newParCategory']:$parCategory;
//            $parCategory=$newParCategory;
//            $query="INSERT INTO dir_categories (cat_parent, cat_child)
//                    VALUES ('$newParCategory', '$newCategory')";
//            $result = mysql_query($query,$link) or die(mysql_error());
//        } elseif (empty($HTTP_POST_VARS['newCategory'])&&!empty($HTTP_POST_VARS['newParCategory'])) {
            if (!empty($HTTP_POST_VARS['newParCategory'])) {    
            $parCategory = $HTTP_POST_VARS['newParCategory'];
            //Check if already exist
            $query = "SELECT COUNT(*) as count FROM dir_categories
                       WHERE cat_parent = '$parCategory'";
            $result = mysql_query($query,$link);
            $count = mysql_fetch_array($result);
            if (!$count['count']) {
                $query="INSERT INTO dir_categories (cat_parent)
                        VALUES ('$newParCategory')";
            $result = mysql_query($query,$link) or die(mysql_error());
            }
            //EOF Check if already exist
        }
        break;
    case 'delcategory':
    
          $query = "SELECT cat_parent FROM dir_categories WHERE cat_id = '$cat_id'";
          $result = mysql_query($query,$link);
          $aParent = mysql_fetch_array($result);
          
          $query_del = "DELETE FROM dir_categories WHERE cat_parent = '".$aParent[0]."'";
          $result = mysql_query($query_del,$link) or die(mysql_error());
//          echo '<pre>';
//          print_r($array_parent);
//          exit;
//        $query="DELETE FROM dir_categories WHERE cat_id = '$cat_id'";
//        $result = mysql_query($query,$link) or die(mysql_error());
        $query="DELETE FROM dir_site_list WHERE cat_id = '$cat_id'";
        $result = mysql_query($query,$link) or die(mysql_error());
        unset($cat_id);
        break;
    case 'editcategory':
        $postname = "CategoryName_".$cat_id;
        if (!empty($HTTP_POST_VARS[$postname])) {
            $newCategory=$HTTP_POST_VARS[$postname];
            $query="UPDATE dir_categories SET cat_parent='$newCategory'
                    WHERE cat_id = '$cat_id'";
            $result = mysql_query($query,$link) or die(mysql_error());
        }
        break;
    case 'editparcat':
        if (!empty($HTTP_POST_VARS['ParCategory_'.$HTTP_POST_VARS['cat_id']])) {
    
            $newParCategory=$HTTP_POST_VARS['ParCategory_'.$HTTP_POST_VARS['cat_id']];
            $OldPar = $HTTP_POST_VARS['OldPar'];
            $query="UPDATE dir_categories SET cat_parent='$newParCategory'
                        WHERE cat_parent = '$OldPar'";
            $result = mysql_query($query,$link) or die(mysql_error());
        }
        header("Location: categories.php");
        break;
}
include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");
include("$CONST_INCLUDE_ROOT/includes/admin_header.php");


?>
    <form method="post" name="frmCategories" action="" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="view">
    <input type="hidden" name="cat_id" value="">
    <input type="hidden" name="OldPar" value="">
    <input type="hidden" name="cat_par" value="">
    <table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td>Main category: </td>
            <td>&nbsp;</td>
            <td><input type="input" class="input" style='width:150px;' name="newParCategory" value=''></td>
            <td>&nbsp;<input type="submit" name="Submit" value="Add" class="button" onClick="frmCategories.mode.value='addcategory'; return true;"> <!--<input type="submit" name="Submit" value="Remove" class="button" onClick="frmCategories.mode.value='delparcat'; return true;">--></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
    <table border="0" align="center" cellpadding="1" cellspacing="1">
        <tr>
            <td class='sideNavHead' align='center'>Main Category</td>
            <td class='sideNavHead' align='center' colspan='3'>Action</td>
        </tr>
                <?php
                        $categories=mysql_query("SELECT cat_parent, cat_id FROM dir_categories GROUP BY cat_parent",$link);
                        while ($sql_array = mysql_fetch_object($categories)) {
                            print("
        <tr>
            <td> <input type='input' class='input' style='width:200px;' name='ParCategory_".$sql_array->cat_id."' value='$sql_array->cat_parent'></td>
            <td> <input type='submit' name='Submit' value='Save' class='button' onClick=\"frmCategories.mode.value='editparcat';frmCategories.OldPar.value='$sql_array->cat_parent';frmCategories.cat_id.value='$sql_array->cat_id'; return true;\"></td>
            <td> <input type='submit' name='Submit' value='Remove' class='button' onClick=\"if (confirm('Are you sure?')){frmCategories.mode.value='delcategory';frmCategories.cat_id.value='$sql_array->cat_id';return true;}else return false;\"></td>
            <td> <input type='submit' name='Submit' value='Subcategories' class='button' onClick=\"frmCategories.mode.value='subcategories';frmCategories.cat_par.value='$sql_array->cat_parent'; return true;\"></td>
        </tr>
                            ");
                        }
                        //Check if has empty subcategories
                            $empty_sub_categories = mysql_query("SELECT COUNT(*) as count FROM dir_categories WHERE cat_parent = '$parCategory' AND cat_child = ''",$link);
                            $count_empty = mysql_fetch_array($empty_sub_categories);
                        //EOF Check if has empty subcategories
?>                        
    </table>
    </form>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>

