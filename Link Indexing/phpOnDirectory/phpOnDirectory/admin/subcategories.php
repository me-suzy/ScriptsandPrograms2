<?php

include_once('../includes/db_connect.php');
check_admin();


$mode = (!empty($HTTP_POST_VARS['mode']))?$HTTP_POST_VARS['mode']:"view";
$parCategory = (!empty($HTTP_GET_VARS['lstParentCat']))?$HTTP_GET_VARS['lstParentCat']:"";
$cat_id = (!empty($HTTP_POST_VARS['cat_id']))?$HTTP_POST_VARS['cat_id']:"";



switch ($mode) {
    case 'addcategory':
        if (!empty($HTTP_POST_VARS['newCategory'])) {
            $newCategory=$HTTP_POST_VARS['newCategory'];
//            $newParCategory=(!empty($HTTP_POST_VARS['newParCategory']))?$HTTP_POST_VARS['newParCategory']:$parCategory;
            $parCategory = $HTTP_POST_VARS['par_name'];
            
            //check if has empty 
                $res_check = mysql_query("SELECT cat_id FROM dir_categories
                                             WHERE 
                                             cat_parent = '$parCategory'
                                             AND cat_child = ''
                                          ");
                
                if (mysql_num_rows($res_check)) {
                    $oCheck = mysql_fetch_object($res_check);
                    $query="UPDATE dir_categories 
                                SET
                                    cat_child = '$newCategory'
                            WHERE 
                                cat_id = '".$oCheck->cat_id."'
                            ";
                } else {
                    $query="INSERT INTO dir_categories (cat_parent, cat_child)
                    VALUES ('$parCategory', '$newCategory')";
                }
            //eof check if has empty
            
            
            $result = mysql_query($query,$link) or die(mysql_error());
        } 
        break;
    case 'delcategory':
        $query="DELETE FROM dir_categories WHERE cat_id = '$cat_id'";
        $result = mysql_query($query,$link) or die(mysql_error());
        $query="DELETE FROM dir_site_list WHERE cat_id = '$cat_id'";
        $result = mysql_query($query,$link) or die(mysql_error());
        unset($cat_id);
        break;
    case 'editcategory':
        $postname = "CategoryName_".$cat_id;
        if (!empty($HTTP_POST_VARS[$postname])) {
            $newCategory=$HTTP_POST_VARS[$postname];
            $query="UPDATE dir_categories SET cat_child='$newCategory'
                    WHERE cat_id = '$cat_id'";
            $result = mysql_query($query,$link) or die(mysql_error());
        }
        header("Location: subcategories.php?lstParentCat=$parCategory");
        break;
    case 'back':
        header("Location: categories.php");
        break;
}

include("$CONST_INCLUDE_ROOT/Templates/maintemplate.header.inc.php");
include("$CONST_INCLUDE_ROOT/includes/admin_header.php");

?>
    <form method="post" name="frmCategories" action="" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="view">
    <input type="hidden" name="cat_id" value="">
    <input type="hidden" name="par_name" value="<?=$parCategory?>">
    <table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td>Subcategory: </td>
            <td>&nbsp;</td>
            <td><input type="input" class="input" style='width:150px;' name="newCategory" value=''></td>
            <td>&nbsp;<input type="submit" name="Submit" value="Add" class="button" onClick="frmCategories.mode.value='addcategory'; return true;"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table border="0" align="center" cellpadding="1" cellspacing="1">
        <tr>
            <td class='sideNavHead' align='center'>Main Category: </td>
            <td class='sideNavHead' align='center'>Subcategory</td>
            <td class='sideNavHead' align='center'>Sites</td>
            <td class='sideNavHead' align='center' colspan='2'>Action</td>
        </tr>
                <?php
                        $sub_categories=mysql_query("SELECT cat_child, cat_id, cat_parent FROM dir_categories WHERE cat_parent = '$parCategory'",$link);
//                        exit;
                        $sub_category="";
                        
                        while ($sql_array = mysql_fetch_object($sub_categories)) {
                            $urls=0;
                            $linked_sites=mysql_query("SELECT COUNT(*) as urls FROM dir_site_list WHERE cat_id = '$sql_array->cat_id'",$link);
                            while ($links = mysql_fetch_object($linked_sites)) $urls=$links->urls;
                            if ($sql_array->cat_child){
                            print("
        <tr>
            <td> $sql_array->cat_parent</td>
            <td> <input type='input' class='input' style='width:200px;' name='CategoryName_$sql_array->cat_id' value='$sql_array->cat_child'></td>
            <td align='center'> $urls</td>
            <td><input type='submit' name='Submit' value='Save' class='button' onClick=\"frmCategories.mode.value='editcategory';frmCategories.cat_id.value='$sql_array->cat_id'; return true;\"></td>
            <td> <input type='submit' name='Submit' value='Remove' class='button' onClick=\"if (confirm('Are you sure?')){frmCategories.mode.value='delcategory';frmCategories.cat_id.value='$sql_array->cat_id';return true;}else return false;\"></td>
        </tr>
                            ");
                            }
                        }
                        
?>              
    <tr>
        <td colspan="5" align="center"><input type='submit' name='Submit' value='Back' class='button' onClick="frmCategories.mode.value='back'; return true;"></td>
    </tr>          
    </table>
    </form>

<?include("$CONST_INCLUDE_ROOT/Templates/maintemplate.footer.inc.php");?>

