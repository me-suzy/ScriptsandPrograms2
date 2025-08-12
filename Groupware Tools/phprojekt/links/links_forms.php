<?php

// links_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: links_forms.php,v 1.15 2005/06/20 14:43:03 paolo Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role ... check deactivated since we do not see any security problem
// if (check_role("links") < 1) die("You are not allowed to do this!");

include_once("$lib_path/access_form.inc.php");
$include_path = $path_pre."lib/permission.inc.php";
include_once $include_path;
// special treatment of the fieldname of ID. since this db-table will be used often, it's name has been chenged into t_ID (like the other fields)

// tabs
$tabs    = array();
$buttons = array();
$hidden  = array();

if (SID) $hidden[session_name()] = session_id();
$buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'enctype' => 'multipart/form-data', 'name' => 'frm', 'onsubmit' => 'return chkForm(\'frm\',\'recordname\',\''.__('Please specify a description!').'!\');');
$output = get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();

// copy
if ($cop_b <> '') {
    $buttons[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Copy'), 'active' => false);
    $buttons[] = array('type' => 'submit', 'name' => 'cancel_b', 'value' => __('back'), 'active' => false);
}
// modify/import or delete/undo
elseif ($ID > 0) {
    $buttons[] = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Modify'), 'active' => false);
    $buttons[] = array('type' => 'submit', 'name' => 'delete_b', 'value' => __('Delete'), 'active' => false);
    $buttons[] = array('type' => 'submit', 'name' => 'cancel_b', 'value' => __('back'), 'active' => false);
}
elseif (!$ID) {
    $buttons[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Create'), 'active' => false);
    $buttons[] = array('type' => 'submit', 'name' => 'cancel_b', 'value' => __('back'), 'active' => false);
}

else {
    $buttons[] = array('type' => 'submit', 'name' => 'cancel_b', 'value' => __('back'), 'active' => false);
}
$output .= get_buttons_area($buttons);

$output .= '<div class="hline"></div>';


// fetch data from record
if ($ID > 0) {

  // fetch values from db and
  $result = db_query("select t_ID, t_author
                        from ".DB_PREFIX."db_records
                       where t_ID = '$ID' and
                             t_author = '$user_ID'") or db_die();
  $row = db_fetch_row($result);
  if (!$row[0]) { die("You are not privileged to do this!"); }
}



#$hidden = array_merge(array('ID'=>$ID,'mode'=>'data'), $view_param);
#$output .= hidden_fields($hidden);


/*******************************
*       basic fields
*******************************/
$form_fields = array();
$form_fields[] = array('type' => 'hidden', 'name' => 'ID', 'value' => $ID);
$form_fields[] = array('type' => 'hidden', 'name' => 'mode', 'value' => 'data');
#$form_fields[] = array('type' => 'hidden', 'name' => 'justform', 'value' => $justform);
#foreach($view_param as $key => $value){
#    $form_fields[] = array('type' => 'hidden', 'name' => $key, 'value' => $value);
#}
$form_fields[] = array('type' => 'parsed_html', 'html' => build_form($fields));
$basic_fields = get_form_content($form_fields);


$output .= '
<br/>
<div class="inner_content">
    <a name="content"></a>
    <a name="oben" id="oben"></a>
    <div class="boxHeaderLeft">'.__('Basis data').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#unten">'.__('Links').'</a></div>
    <div class="boxContent">'.$basic_fields.'
    <br style="clear:both"/><br/>
</div></div></form>
<br style="clear:both"/><br/>
';



echo $output;

?>