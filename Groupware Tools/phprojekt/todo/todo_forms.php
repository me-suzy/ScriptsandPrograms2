<?php

// todo_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: todo_forms.php,v 1.36.2.2 2005/08/26 06:03:12 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use todo.php!");

// check role
if (check_role("todo") < 2) die("You are not allowed to do this!");


if (eregi("xxx", $projekt_ID)) $projekt_ID = substr($projekt_ID, 11);
if (eregi("xxx", $contact_ID)) $contact_ID = substr($contact_ID, 11);

if ($justform == 2) $onload = array( 'window.opener.location.reload();', 'window.close();' );
else if ($justform > 0) $justform++;

echo datepicker();

include_once($lib_path."/access_form.inc.php");
$include_path = $path_pre."lib/permission.inc.php";
include_once($include_path);


// fetch data from record
if ($ID > 0) {
    // mark that the user has touched the record
    touch_record('todo', $ID);

    // fetch values from db
    $result = db_query("SELECT ID, von, acc_write, status, ext, progress, acc
                          FROM ".DB_PREFIX."todo
                         WHERE ID = '$ID'
                           AND (acc LIKE 'system' OR von = '$user_ID' OR ext = '$user_ID'
                                OR ((acc LIKE 'group' OR acc LIKE '%\"$user_kurz\"%')
                                    AND $sql_user_group))") or db_die();
    $row = db_fetch_row($result);
    if (!$row[0]) die("You are not privileged to do this!");
    if (($row[1] <> $user_ID and $row[2] <> 'w' and $row[4] <> $user_ID) or check_role("todo") < 2) $read_o = 1;
    else $read_o = 0;
}

if ($ID) $head = slookup('todo', 'remark', 'ID', $ID);
else     $head = __('New todo');

// tabs
$tabs   = array();
$hidden = array();
$buttons = array();
// form start
if (SID) $hidden[session_name()] = session_id();
$buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'enctype' => 'multipart/form-data', 'name' => 'frm');
$output=get_buttons($buttons);
$output.= get_tabs_area($tabs);

// button bar
$buttons = array();


if (!$read_o) {
    // display input form
    if (!$ID) {
        $buttons[] = array('type' => 'hidden', 'name' => 'step', 'value' => 'create');
        $buttons[] = array('type' => 'submit', 'name' => 'modify', 'value' => __('Create'), 'active' => false);
    } // modify and delete
    else {
        $buttons[] = array('type' => 'hidden', 'name' => 'step', 'value' => 'update');
        if ($row[1] == $user_ID or ($row[4] == $user_ID and $row[3] <> 4)) {
            $buttons[] = array('type' => 'submit', 'name' => 'modify', 'value' => __('Modify'), 'active' => false);
        }
        // delete if you are the owner, an user with chief status or a todo from version 3.3
        if (ereg("c",$user_access) or $row[1] == $user_ID or (!$row[1] and $row[4] == $user_ID)) {
            $buttons[] = array('type' => 'submit', 'name' => 'delete', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
        }
        // undertake
        if ($row[4] == 0) {
            $buttons[] = array('type' => 'link', 'href' => 'todo.php?mode=data&amp;undertake=1&amp;ID='.$row[0].$sid, 'text' => __('Undertake'), 'active' => false);
        }
    }
}

// print
if ($ID > 0) {
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);

    /* disable print buttons in 5.0
    $ure = "../misc/print.php?ID=$row[0]&module=todo";
    $output .= "<input type='button' onclick='window.open(\"$ure\",\"_blank\")' value='".__('print')."' class='button' />\n";
    */
}
// cancel
if ($justform > 0) {
    $buttons[] = array('type' => 'button', 'name' => 'close', 'value' => __('Close window'), 'active' => false, 'onclick' => 'window.close();');
}
else {
    $buttons[] = array('type' => 'link', 'href' => 'todo.php?type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('back'), 'active' => false);
}

// end buttons chief only
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

/*******************************
*       basic fields
*******************************/
$form_fields   = array();
$form_fields[] = array('type' => 'hidden', 'name' => 'ID', 'value' => $ID);
$form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
$form_fields[] = array('type' => 'hidden', 'name' => 'justform', 'value' => $justform);
if (SID) $form_fields[] = array('type' => 'hidden', 'name' => session_name(), 'value' => session_id());
foreach($view_param as $key => $value){
    $form_fields[] = array('type' => 'hidden', 'name' => $key, 'value' => $value);
}
$form_fields[] = array('type' => 'parsed_html', 'html' => build_form($fields));
$basic_fields = get_form_content($form_fields);

/*******************************
*   categorization fields
*******************************/
$form_fields = array();
if (!$row[0] or ($row[1] == $user_ID and $row[3] == 1)) {
    $options = array();
    foreach ($status_arr as $statusnr => $statusname) {
    // possible values: accepted and rejected
        if ($statusnr >= 1 and $statusnr <= 3) {
            if ($statusnr == 3 && !PHPR_TODO_OPTION_ACCEPTED) continue;
            if ($statusnr == 2) {
                $selected = true;
            }
            else {
                $selected = false;
            }
            $options[] = array('value' => $statusnr, 'text' => $statusname, 'selected' => $selected);
        }
    }
    $form_fields[] = array('type' => 'select', 'name' => 'status', 'label' => __('Status').__(':'), 'options' => $options);
}
else if ($ID > 0) {
    $options = array();
    // select box only if the user is the recipient and the status is still pending ...
    if ($row[4] == $user_ID and $row[3] == 2) {
        foreach ($status_arr as $statusnr => $statusname) {
            // possible values: accepted and rejected
            if ($statusnr >= 2 and $statusnr <= 4) {
                $options[] = array('value' => $statusnr, 'text' => $statusname, 'selected' => false);
            }
        }
        $form_fields[] = array('type' => 'select', 'name' => 'status', 'label' => __('Status').__(':'), 'options' => $options);
    }
    // next possible mode: if accepted, give him a checkbox to mark this todo as done
    else if ($row[3] == 3) {
        if ($row[4] == $user_ID ) $form_fields[] = array('type' => 'checkbox', 'readonly'=>false,'name' => 'todo_done', 'label' => $status_arr[$row[3]], 'label_right' => __('done'));
        else $form_fields[] = array('type' => 'checkbox', 'readonly'=>true,'name' => 'todo_done', 'label' => $status_arr[$row[3]], 'label_right' => __('done'));
    }
    // otherwise just print the current status
    else {
        $form_fields[] = array('type' => 'parsed_html', 'html' => $status_arr[$row[3]]);
    }
}

// Progress
if ($row[4] == $user_ID and ($row[3] > 1 and $row[3] < 5)) {
    $form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
    $form_fields[] = array('type' => 'hidden', 'name' => 'cstatus', 'value' => $GLOBALS['cstatus']);
    $form_fields[] = array('type' => 'hidden', 'name' => 'category', 'value' => $GLOBALS['category']);
    $form_fields[] = array('type' => 'hidden', 'name' => 'ID', 'value' => $ID);
    //$form_fields[] = array('type' => 'hidden', 'name' => 'step', 'value' => 'update_progress');
    $form_fields[] = array('type' => 'text', 'name' => 'progress', 'label' => __('progress').__(':'), 'value' => $row[5], 'onblur' => 'this.form.submit();', 'label_right' => ' %');
}
else {
     $form_fields[] = array('type' => 'parsed_html', 'html' => $row[5].'%');
}
$categorization_fields = get_form_content($form_fields);

/*******************************
*      assignment fields
*******************************/
$form_fields = array();
if (!$ID) $form_fields[] = array('type' => 'checkbox','name'=>'notify_recipient', 'label'=>__('Notify recipient'));


include_once("../lib/access_form.inc.php");
// acc_read, exclude the user itself, acc_write, no parent possible, write access=yes
$form_fields[] = array('type' => 'parsed_html', 'html' => access_form2($row[6], 1, $row[2], 0, 1));
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

echo $output;

?>
