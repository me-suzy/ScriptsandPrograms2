<?php

/* D.E. Classifieds v1.04  
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToLibDir').'func_tree.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_add_edit.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$myDB = db_connect();

$content = array();

$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];


if (isset($cat_id) ) 
{   $content[] = '$path;' ;
    $gbl["func_arg_arr"] = array("cat_id"=>$cat_id);
    $content[] = 'checkUser(\'ask_to_add($cat_id)\', \'You have to log in.\', $gbl["func_arg_arr"]); ' ;
}
else
{   $content[] = 'checkUser(\'get_tree(select_to_add)\', \'You have to log in.\'); ' ;
}

// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_select_to_add'));

db_disconnect($myDB);


# ------- START FUNCTIONS ------


// ************* START FUNCTION ask_to_add() *************

function ask_to_add($cat_id)
{
    GLOBAL $myDB ;

    echo 'Below is the path to the category you chose:<BR><BR>';

    $path = climb_tree($cat_id, 'select_add') ; 
    $path_arr = split("\!\@\#_SPLIT_\!\@\#", $path);
    for ($i=0; $i<count($path_arr); $i++)
    {   echo $path_arr[$i] ;
        if($i < count($path_arr)-2)
        {   echo '<FONT CLASS="subCat" COLOR="#FF0000"><B>>> </B></FONT>' ;
        }
    }
   

    echo '<BR><BR>';
    echo '<a href="add_item.php?cat_id='.$cat_id.'"><FONT CLASS="subCat">Click here to add item to this category</FONT></a>';
    echo '<BR>';
    echo '<NOSCRIPT>';
    echo '<a href="select_to_add.php"><FONT CLASS="subCat">Click here to choose a different category</FONT></a><BR>';
    echo '</FONT>';
    echo '</NOSCRIPT>';

    echo '<SCRIPT LANGUAGE="JAVASCRIPT">';
    echo 'document.write(\'<a href="javascript:history.go(-1);"><FONT CLASS="subCat">Click here to choose a different category</FONT></a><BR>\');';
    echo '</SCRIPT>';

} // end function ask_to_add() 

// ************* END FUNCTION ask_to_add() *************


?>




