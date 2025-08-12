<?php

// notes_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: notes_forms.php,v 1.32.2.6 2005/09/12 12:42:29 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("notes") < 1) die("You are not allowed to do this!");


if (eregi("xxx", $projekt_ID)) $projekt_ID = substr($projekt_ID, 11);
if (eregi("xxx", $contact_ID)) $contact_ID = substr($contact_ID, 11);

include_once($lib_path."/access_form.inc.php");
$include_path = $path_pre."lib/permission.inc.php";
include_once($include_path);

if ($justform == 2) $onload = array('window.opener.location.reload();', 'window.close();');
else if ($justform > 0) $justform++;
//echo set_body_tag();

// fetch data from record
if ($ID > 0) {
    // mark that the user has touched the record
    touch_record('notes', $ID);

    // fetch values from db
    $result = db_query("SELECT ID, von, name, remark, contact, ext, div1, div2,
                               projekt, sync1, sync2, acc, acc_write, parent
                          FROM ".DB_PREFIX."notes
                         WHERE (acc LIKE 'system'
                                OR ((von = '$user_ID' OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%')
                                    AND $sql_user_group))
                           AND ".DB_PREFIX."notes.ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if (!$row[0]) die("You are not privileged to do this!");
    if (($row[1] <> $user_ID and $row[12] <> 'w') or check_role("notes") < 2) $read_o = 1;
    else $read_o = 0;
}

if ($ID) $head = slookup('notes', 'name', 'ID', $ID);
else     $head = __('New note');
if (!$head) $head = __('New note');

// tabs
$tabs    = array();
$hidden  = array();
$buttons = array();
if (SID) $hidden[session_name()] = session_id();

// form start
$buttons[] = array('type' => 'form_start', 'name'=>'frm','hidden' => $hidden, 'onsubmit' => "return chkForm('frm','typ','".__('Please insert a name')."');");
$output .= get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();


if (!$read_o) {
    if (!$ID) {
        // create new note
        $buttons[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Accept'), 'active' => false);
        // hidden
        $buttons[] = array('type' => 'hidden', 'name' => 'anlegen', 'value' => 'neu_anlegen');
    } // modify and delete
    else {
        // modify note
        $buttons[] = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Accept'), 'active' => false);
        // hidden
        $buttons[] = array('type' => 'hidden', 'name' => 'aendern', 'value' => 'aendern');
        // check if the note belongs to a project and if user=owner.
        if (PHPR_PROJECTS) {
            $result2 = db_query("SELECT ID
                                   FROM ".DB_PREFIX."projekte
                                  WHERE parent = '$ID'") or db_die();
        }
        $row2 = db_fetch_row($result2);
        if ($row2[0] == '' and $row[1] == $user_ID) {
            $buttons[] = array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
        }
    }
} // end buttons chief only

// print
if ($ID > 0) {
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);
}

// cancel
if ($justform > 0) {
    $buttons[] = array('type' => 'button', 'name' => 'close', 'value' => __('Close window'), 'active' => false, 'onclick' => 'window.close();');
}
else {
    $buttons[] = array('type' => 'link', 'href' => 'notes.php?type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('Cancel'), 'active' => false);
}
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

/*******************************
*       basic fields
*******************************/
$form_fields = array();
$form_fields[] = array('type' => 'hidden', 'name' => 'ID', 'value' => $ID);
$form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
$form_fields[] = array('type' => 'hidden', 'name' => 'justform', 'value' => $justform);
if (SID) $form_fields[] = array('type' => 'hidden', 'name' => session_name(), 'value' => session_id());
foreach ($view_param as $key=>$value) {
    $form_fields[] = array('type' => 'hidden', 'name' => $key, 'value' => $value);
}
$form_fields[] = array('type' => 'parsed_html', 'html' => build_form($fields));
$basic_fields  = get_form_content($form_fields);

/*******************************
*    categorization fields
*******************************/
$form_fields = array();
$select_field = '<label for="parent" class="formbody">'.__('Parent object').':</label>
                 <select id="parent" class="options" name="parent"'.read_o($read_o).'><option value="0"></option>';
$select_field .= show_elements_of_tree("notes",
                        "name",
                        "WHERE (acc LIKE 'system' OR ((von = $user_ID OR acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%') AND $sql_user_group))",
                        "acc",
                        " ORDER BY name", $row[13], "parent", $ID);
$select_field .= '</select>';
$form_fields[] = array('type' => 'parsed_html', 'html' => $select_field);
$categorization_fields = get_form_content($form_fields);

/*******************************
*     assignment fields
*******************************/
$form_fields = array();
include_once("../lib/access_form.inc.php");
// acc_read, exclude the user itself, acc_write, no parent possible, write access=yes
$form_fields[] = array('type' => 'parsed_html', 'html' => access_form2($row[11], 1, $row[12], 0, 1));
$assignment_fields = get_form_content($form_fields);

$output .= '
<br />

<div class="inner_content">
    <a name="content"></a>
    <a name="oben" id="oben"></a>
    <div class="boxHeaderLeft">'.__('Basis data').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#unten">'.__('Links').'</a></div>
    <div class="boxContent">'.$basic_fields.'</div>
    <br style="clear:both" /><br />

    <div class="boxHeaderLeft">'.__('Categorization').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
    <div class="boxContent">'.$categorization_fields.'</div>
    <br style="clear:both" /><br />

    <a name="unten" id="unten"></a>
    <div class="boxHeaderLeft">'.__('Assignment').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
    <div class="boxContent">'.$assignment_fields.'</div>
    <br style="clear:both" /><br />
</div>

<br style="clear:both" /><br />
</form>
';

//*********************
// show related objects

/** current database structure doesn't allow related objects for notes!
if ($ID > 0) {
    $output .= "<br />\n";
    $projekt_ID = $ID;

    // include the lib
    include_once("$lib_path/show_related.inc.php");
    $referer = "projects.php?mode=forms&amp;ID=$ID";

    // show related todos
    if (PHPR_TODO and check_role("todo") > 0) {
        $query = "project = '$ID'";
        $output .= show_related_todo($query, $referer);
        $output .= "<br />\n";
    }

    // related notes, show only for existing projects
    if (PHPR_NOTES and check_role("notes") > 0) {
        $module = "notes";
        $query = "projekt = '$ID'";
        $output .= show_related_notes($query, $referer);
        $output .= "<br />\n";
    }

    // show related files
    if (PHPR_FILEMANAGER and check_role("filemanager") > 0) {
        $module = "notes";
        $query = "div2='$ID'";
        $output .= show_related_files($query, $referer);
        $output .= "<br />\n";
    }

    // show related events
    if (PHPR_CALENDAR and check_role("calendar") > 0) {
        $module = "notes";
        $query = "projekt = '$ID'";
        $output .= show_related_events($query, $referer);
        $output .= "<br />\n";
    }

    // show history
    if (PHPR_HISTORY_LOG) $output .= history_show('notes', $ID);
}
// end show related objects
*/

echo $output;

?>
