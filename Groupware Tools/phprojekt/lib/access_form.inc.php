<?php

// access_form.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: access_form.inc.php,v 1.23.2.1 2005/08/12 14:24:10 paolo Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) die('Please use index.php!');


include_once('show_group_users.inc.php');
echo '<script type="text/javascript" src="../lib/selector/dbl_select_mover.js"></script>'."\n";

// access selection
function access_form2($acc_read, $exclude_user, $acc_write, $same_as_parent=0, $write_access_allowed=0, $fieldname='acc') {
    global $user_ID, $user_group, $read_o, $user_kurz, $sql_user_group;

    // set default mode
    if (!$acc_read) {
        if (PHPR_ACC_DEFAULT == 1) $acc_read = 'group';
        else                       $acc_read = 'private';
    }

    // access: 1 = alone, 2 = all, 3 = some
    // personal file

    $str .= '
    <fieldset style="border:1px solid black;width:400px;padding:10px;">
    <legend>'.__('Release').'</legend>
    <div style="margin-right:5em;float:left;">
    <input type="radio" name="'.$fieldname.'" value="private"';
    if ($acc_read == 'private') $str .= ' checked="checked"';
    $str .= read_o($read_o)." /> <label for='".$fieldname."'>".__('none')."</label><br />\n";
    // choose profile
    $str .= '<input type="radio" name="'.$fieldname.'" id="'.$fieldname.'" value="4"';
    if ($acc_read == '4') $str .= ' checked="checked"';
    $str .= " />\n";
    $str .= '<label for="'.$fieldname.'"> '.__('or profile')."</label>:\n";
    $str .= '<select name="profil"'.read_o($read_o).'> <option value=""></option>'."\n";
    $result2 = db_query("SELECT ID, bezeichnung, personen
                           FROM ".DB_PREFIX."profile
                          WHERE von = '$user_ID'
                       ORDER BY bezeichnung");
    while ($row2 = db_fetch_row($result2)) {
        $str .= "<option value=".$row2[0];
        if ($acc_read == $row2[2]) $str .= ' selected="selected"';
        $str .= ">$row2[1]</option>\n";
    }
    $str .= "</select><br />\n";
    // file for all
    $str .= "<input type='radio' name='".$fieldname."' value='group'";
    if ($acc_read === 'group') $str .= ' checked="checked"';
    $str .= read_o($read_o)." />\n";
    $str .= "<label for='".$fieldname."'>".__('Group')."</label><br />\n";
    // all groups
    if (PHPR_ACC_ALL_GROUPS) {
        $str .= "<input type='radio' name='".$fieldname."' value='system'".read_o($read_o);
        if ($acc_read == 'system') $str .= ' checked="checked"';
        $str .= " />\n";
        $str .= "<label for='".$fieldname."'>".__('All groups')."</label> <br />\n";
    }
    // same level as directory
    if ($same_as_parent > 0) {
        $str .= "<input type='radio' name=".$fieldname." value='same_as_parent'".read_o($read_o)." />\n";
        $str .= "<label for='".$fieldname."'> ".__('As parent object')."</label> <br />\n";
    }
    $str .= '
    </div>
    <div style="float:left;">
    ';
    // choose users
    $str .= "<input type='radio' name='".$fieldname."' value='3'".read_o($read_o);
    if (!in_array($acc_read, array('private', 'group', 'system'))) $str .= ' checked="checked"';

    $str .= " />\n";
    $str .= '<label for="'.$fieldname.'">'.__('Some').':</label><br />
    <select size="5" name="persons[]" multiple="multiple"'.read_o($read_o).'>'.show_group_users($user_group, $exclude_user, $acc_read, true).'</select>
    </div>
    <div style="clear:both;">
';
    // last row in this table cell: write access!
    if ($write_access_allowed > 0) {
        // set default write mode
        if (!$acc_write) {
            if (PHPR_ACC_WRITE_DEFAULT == '1') $acc_write = 'on';
        }
        $checked = ($acc_write == 'w' or $acc_write == 'on') ? ' selected="selected"' : '';
        $str .= '<br />
    <select name="acc_write"'.$checked.read_o($read_o).'>
        <option value="">'.__('only read access to selection').'</option>
        <option value="w"'.$checked.'>'.__('read and write access to selection').'</option>
    </select>';
    }
    $str .= '
    </div>
    </fieldset>
';
    return $str;
}


function access_form($acc_read, $exclude_user, $acc_write, $same_as_parent=0, $write_access_allowed=0, $fieldname='acc') {
    global $user_ID, $user_group, $read_o, $img_path, $user_kurz, $sql_user_group;

    // set default mode
    if (!$acc_read) {
        if (PHPR_ACC_DEFAULT == 1) $acc_read = 'group';
        else $acc_read = 'private';
    }

    // access: 1 = alone, 2 = all, 3 = some
    // personal file
    $str .= "<table>\n<tr>\n<td><input type='radio' name='".$fieldname."' value='private'";
    if ($acc_read == 'private') $str .= ' checked="checked"';
    $str .= read_o($read_o)." /> ".__('Me')." &nbsp; \n";
    // file for all
    $str .= "<input type='radio' name='".$fieldname."' value='group'";
    if ($acc_read == 'group') $str .= ' checked="checked"';
    $str .= read_o($read_o)." /> ".__('Group')." <br />\n";
    // choose profile
    $str .= '<input type="radio" name="'.$fieldname.'" value="4"';
    if ($acc_read == '4') $str .= ' checked="checked"';
    $str .= ' /> '.__('or profile').': <select name="profil"'.read_o($read_o).'> <option value=""></option>'."\n";
    $result2 = db_query("SELECT ID, bezeichnung, personen
                           FROM ".DB_PREFIX."profile
                          WHERE von = '$user_ID'
                       ORDER BY bezeichnung");
    while ($row2 = db_fetch_row($result2)) {
        $str .= "<option value=".$row2[0];
        if ($acc_read == $row2[2]) $str .= ' selected="selected"';
        $str .= ">$row2[1]</option>\n";
    }
    $str .= "</select><br />\n";

    // all groups
    if (PHPR_ACC_ALL_GROUPS) {
        $str .= "<input type='radio' name='".$fieldname."' value='system'".read_o($read_o);
        if ($acc_read == 'system') $str .= ' checked="checked"';
        $str .= ' />'.__('All groups')." <br />\n";
    }

    // same level as directory
    if ($same_as_parent > 0) {
        $str .= "<input type='radio' name='".$fieldname."' value='same_as_parent'".read_o($read_o)." /> ".__('As parent object')." <br />\n";
    }

    // last row in this table cell: write access!
    if ($write_access_allowed > 0) {
        $str .= '<img src="'.$img_path.'/s.gif" width="140" height="1" vspace="2" alt="" /><br />'."\n";
        // set default write mode
        if (!$acc_write) {
            if (PHPR_ACC_WRITE_DEFAULT == '1') $acc_write = 'on';
        }
        $checked = ($acc_write == 'w' or $acc_write == 'on') ? ' checked="checked"' : '';
        $str .= '<input type="checkbox" name="acc_write" value="w"'.$checked.read_o($read_o).' /> '.__('Write access')."</td>\n";
    }
    // choose users
    $str .= "<td>\n<input type='radio' name=".$fieldname." value='3'".read_o($read_o);
    if (ereg(";}", $acc_read)) $str .= ' checked="checked"';
    $str .= ' />'.__('Some').":<br />\n";
    $str .= '<select name="persons[]" multiple size="5"'.read_o($read_o).">\n";
    // select user from this group
    // show all members of the group, exclude yourself
    $str .= show_group_users($user_group, $exclude_user, $acc_read);
    $str .= "</select>\n</td>\n</tr>\n</table>\n";

    // end access
    return $str;
}

?>
