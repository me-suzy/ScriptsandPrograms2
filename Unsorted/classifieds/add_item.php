<?php

/* D.E. Classifieds v1.04
   Copyright © 2002 Frank E. Fitzgerald 
   Distributed under the GNU GPL .
   See the file named "LICENSE".  */

/**************************************
 * File Name: add_item.php            *
 * ---------                          *
 *                                    *
 **************************************/

require_once 'path_cnfg.php';

require_once(path_cnfg('pathToLibDir').'func_common.php');
require_once(path_cnfg('pathToLibDir').'func_checkUser.php');
require_once(path_cnfg('pathToCnfgDir').'cnfg_vars.php');
require_once(path_cnfg('pathToLibDir').'vars_add_edit.php');
require_once(path_cnfg('pathToLibDir').'vars_gbl.php');

$myDB = db_connect();

$content = array();

$cookie = $HTTP_COOKIE_VARS['log_in_cookie'];

$gbl["func_arg_arr"] = array("cat_id"=>$cat_id);
$content[] = 'checkUser(\'add_item_form($cat_id)\', \'You have to log in.\', $gbl["func_arg_arr"]); ';   

// This line brings in the template file.
// If you want to use a different template file 
// simply change this line to require the template 
// file that you want to use.
require_once(path_cnfg('pathToTemplatesDir').cnfg('tmplt_add_item'));

db_disconnect($myDB);


# --- START FUNCTIONS ---



// ************* START FUNCTION doFirst() *************

function add_item_form($cat_id)
{
    GLOBAL $gbl_quick_tips_description, $gbl_quick_tips_image_short ;

    //echo 'main_cat= '.$main_cat.' sub_cat= '.$sub_cat;

    echo $gbl_quick_tips_description.$gbl_quick_tips_image_short;

    ?>

    <FORM NAME="add_item_form" ACTION="<?php echo cnfg('deDir'); ?>add_item_results.php?cat_id=<?php echo $cat_id; ?>" METHOD="POST" enctype="multipart/form-data">

    <TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="100%">
    <TR>
    <TD VALIGN="TOP" ALIGN="RIGHT">Title:</TD>
    <TD VALIGN="TOP"><INPUT TYPE="TEXT" NAME="title"></TD>
    </TR>
    <TR>
    <TD VALIGN="TOP" ALIGN="RIGHT">Description:</TD>
    <TD VALIGN="TOP">
    <TEXTAREA NAME="description" ROWS="15" COLS="45"></TEXTAREA></TD>
    </TR>
    <TR>
    <TD VALIGN="TOP" ALIGN="RIGHT">Email:</TD>
    <TD VALIGN="TOP"><INPUT TYPE="TEXT" NAME="email"></TD>
    </TR>
    <TR>
    <TD VALIGN="TOP" ALIGN="RIGHT"></TD>
    <TD VALIGN="TOP">Email is visible:<INPUT TYPE="CHECKBOX" NAME="visible_email" CHECKED>
    <BR>&nbsp;
    </TD>
    </TR>
    <TR>
    <TD VALIGN="TOP">
    &nbsp;
    </TD>
    <TD VALIGN="TOP">
    <!-- <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000"> -->
    Upload an image for this item:
    </TD>
    </TR>
    <TR>
    <TD VALIGN="TOP" ALIGN="RIGHT">
    <FONT COLOR="#FF0000">(*Optional)</FONT>
    </TD>
    <TD VALIGN="TOP">
    <input type="file" name="form_data1">
    </TD>
    </TR>
    <TR>
    <TD VALIGN="TOP" ALIGN="RIGHT">
    &nbsp;
    </TD>
    <TD VALIGN="TOP">
    <input type="file" name="form_data2" >
    </TD>
    </TR>
    <TR>
    <TD VALIGN="TOP">
    &nbsp;
    </TD>
    <TD VALIGN="TOP">
    <FONT CLASS="user" COLOR="#FF0000">
    Image must be a gif or  jpg/jpeg
    </FONT>
    </TD>
    </TR>
    <TR>
    <TD VALIGN=TOP>&nbsp;</TD>
    <TD VALIGN=TOP>
    <BR>
    <INPUT TYPE="SUBMIT" VALUE="SUBMIT">
    </TD>
    </TR>
    </TABLE>

    </FORM>

    <?php


} // end function doFirst()

// ************* END FUNCTION doFirst() *************

