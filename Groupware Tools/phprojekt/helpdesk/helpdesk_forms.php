<?php

// helpdesk_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: helpdesk_forms.php,v 1.27.2.2 2005/09/07 12:02:58 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) { die("Please use index.php!"); }

include_once("$lib_path/access_form.inc.php");

// check role
if (check_role("helpdesk") < 1) { die("You are not allowed to do this!"); }

// fetch data from record
if ($ID > 0) {
  // mark that the user has touched the record
  touch_record('rts', $ID);
  // fetch values from db
  $result = db_query("select ID,contact,email,submit,recorded,name,note,due_date,status,
                             assigned,priority,remark,solution,solved,solve_time,acc,div1,div2,proj,
                             acc_read, acc_write, von
                        from ".DB_PREFIX."rts
                       where (acc_read like 'system' or ((von = '$user_ID' or assigned = '$user_ID' or acc_read like 'group' or acc_read like '%\"$user_kurz\"%') and $sql_user_group)) and
                              ID = '$ID'") or db_die();
  $row = db_fetch_row($result);
  if (!$row[0]) { die("You are not privileged to do this!"); }
  #printr($row[21]);
  // if (($row[9] <> $user_ID and $row[21] <> $user_ID and $row[20] <> 'w') or check_role("helpdesk") < 2 or($row[8]==5)) { $read_o = 1; }
  if (($row[9] <> $user_ID and $row[21] <> $user_ID and $row[20] <> 'w') or check_role("helpdesk") < 2) { $read_o = 1; }
  else $read_o = 0;
}

//tabs
$tabs = array();
// form start
$hidden = array();
$buttons = array();
$hidden = array('mode' => 'forms', 'page' => $page,'ID' =>$ID);
if(SID) $hidden[session_name()] = session_id();
$buttons[] = array('type' => 'form_start', 'name' => 'frm', 'hidden' => $hidden, 'onsubmit' => 'return chkForm(\'frm\',\'name\',\''.__('Please insert a name').'\');');
$output=get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();

if (!$read_o){
    if (!$ID) {
        $buttons[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Accept'), 'active' => false);
        $buttons[] = array('type' => 'hidden', 'name' => 'anlegen', 'value' => 'neu_anlegen');
    } // modify and delete
    else {
        $buttons[] = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Accept'), 'active' => false);
        // change values
        $buttons[] = array('type' => 'hidden', 'name' => 'aendern', 'value' => 'aendern');
        // check whether there is no subproject beyond this one. if no -> allow to delete
        $result2 = db_query("select ID
                               from ".DB_PREFIX."projekte
                              where parent = '$ID'") or db_die();
        $row2 = db_fetch_row($result2);
        if ($row2[0] == '') {
            $buttons[] = array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
        }
    }
} // end buttons chief only
// history & print
if ($ID > 0) {
    $buttons[] = array('type' => 'link', 'href' => $_SERVER['PHP_SELF'].'?mode=view&amp;set_read_flag=1&amp;ID_s='.$ID.$sid, 'text' => __('Mark as read'), 'active' => false);
    /* disable
    $buttons[] = array('type' => 'link', 'href' => '../misc/history.php?reflink=helpdesk/helpdesk.php&table=rts&mID='.$ID.'&mod='.__('Helpdesk'), 'text' => __('History'), 'active' => false);
    */
    /* disable print buttons in 5.0
    $output.= "<input type='button' onclick='window.open(\"../misc/print.php?ID=$row[0]&module=helpdesk\",\"_blank\")' value='".__('print')."' class='button' />\n";
    */
}
// cancel
if ($justform > 0) {
    $buttons[] = array('type' => 'button', 'name' => 'close', 'value' => __('Close window'), 'active' => false, 'onclick' => 'window.close();');
}
else {
    $buttons[] = array('type' => 'link', 'href' => 'helpdesk.php?type='.$type.'&amp;sort='.$sort.'&amp;mode=view&amp;up='.$up.'&amp;filter='.$filter.'&amp;keyword='.$keyword.'&amp;perpage='.$perpage.'&amp;page='.$page, 'text' => __('Cancel'), 'active' => false);
}
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

/*******************************
*       basic fields
*******************************/
$form_fields = array();
$form_fields[] = array('type' => 'hidden', 'name' => 'ID', 'value' => $ID);
$form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
if (SID) $form_fields[] = array('type' => 'hidden', 'name' => session_name(), 'value' => session_id());
foreach($view_param as $key => $value){
    $form_fields[] = array('type' => 'hidden', 'name' => $key, 'value' => $value);
}

$form_fields[] = array('type' => 'parsed_html', 'html' => build_form($fields));
$basic_fields = get_form_content($form_fields);

/*******************************
*       status fields
*******************************/
$form_fields = array();
// set category
$found_mandatory = false;
$tmp = '
<span class="formk">'.__('Status').':</span>
<div class="formk">
';
// selected status index
$db_status_ix = -1;
foreach($status_arr as  $status_nr=>$status1){
    if($status1[0] == $row[8]){
        $db_status_ix = $status_nr;
        break;
    }
}
foreach ($status_arr as $status_nr=>$status1) {
    if ($status1[2] != 2) { // filter active workflow states
        $tmp .=  '<input type="radio" name="status" value="'.$status_nr.'"';
        // conditions to select them: current status has to be 'earlier' then this status and the person has to be entitled
        $allowed_users = array();
        foreach ($status1[1] as $db_col) {
            $allowed_users[] = $row[$db_col];
        }
        if (!$found_mandatory and in_array($user_ID, $allowed_users)) {  $tmp .= read_o(0); }
        else $tmp .= read_o(1);
        // selected?
        if ($status1[0] == $row[8]) $tmp .= ' checked="checked"';
        $tmp .= " /> ".$status1[3]."<br />\n";
        if ($status1[2] == 1 and $status_nr > $db_status_ix) {
            $found_mandatory = true;
        }
    }
}
$form_fields[] = array('type' => 'parsed_html', 'html' => $tmp);
$status_fields = get_form_content($form_fields);
$tmp2 = '
<span class="formk">'.__('Visibility').':</span>
<div class="formk">
';
/*******************************
*     visibility fields
*******************************/
$form_fields = array();
// selected access
$db_access= $row[15];
foreach($access as  $access_val=>$access_key){
     $tmp2 .=  '<input type="radio" name="acc" value="'.$access_val.'"';
        if (!$read_o) {  $tmp .= read_o(0); }
        else $tmp2 .= read_o(1);
        // selected?
        if ($access_val == $db_access) $tmp2 .= ' checked="checked"';
        $tmp2 .= " /> ".$access_key."<br />\n";
       
}
$form_fields[] = array('type' => 'parsed_html', 'html' => $tmp2);
$access_fields = get_form_content($form_fields);

/*******************************
*      assignment fields
*******************************/
$form_fields = array();
include_once("../lib/access_form.inc.php");
// acc_read, exclude the user itself, acc_write, no parent possible, write access=yes
$form_fields[] = array('type' => 'parsed_html', 'html' => access_form2($row[19], 1, $row[20], 0, 1,'acc_read'));
$assignment_fields = get_form_content($form_fields);

$output .= '
<br/>
<div class="inner_content">
    <a name="content"></a>
    <a name="oben" id="oben"></a>
    <div class="boxHeaderLeft">'.__('Basis data').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#unten">'.__('Links').'</a></div>
    <div class="boxContent">'.$basic_fields.'</div></div>
    <br style="clear:both"/><br/>

    <div class="boxHeaderLeft">'.__('Ticket status').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
    <div class="boxContent">'.$status_fields.'</div></div>
    <br style="clear:both"/><br/>
    
    <div class="boxHeaderLeft">'.__('Visibility').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
    <div class="boxContent">'.$access_fields.'</div></div>
    <br style="clear:both"/><br/>

    <a name="unten" id="unten"></a>
    <div class="boxHeaderLeft">'.__('Assignment').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#oben">'.__('Basis data').'</a></div>
    <div class="boxContent">'.$assignment_fields.'</div>
    <br style="clear:both"/><br/>
</div>
    </form>
<br style="clear:both"/><br/>
';

echo $output;

?>
