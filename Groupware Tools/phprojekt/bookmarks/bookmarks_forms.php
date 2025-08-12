<?php

// bookmarks_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: bookmarks_forms.php,v 1.15 2005/06/20 11:50:25 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("bookmarks") < 2) die("You are not allowed to do this!");


// tabs
$tabs   = array();
$tmp    = get_export_link_data('bookmarks', false);
$tabs[] = array('href' => $tmp['href'], 'active' => $tmp['active'], 'id' => 'tab4', 'target' => '_self', 'text' => $tmp['text'], 'position' => 'right');

// form start
$hidden = array('mode'=>'data', 'mode2'=>'bookmarks', 'ID'=>$ID);
foreach ($view_param as $key => $value) {
    $hidden[$key] = $value;
}
if (SID) $hidden[session_name()] = session_id();
$buttons = array();
$buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'onsubmit' => 'return chkForm(\'frm\',\'url\',\''.__('Please specify a description!').'!\');', 'name' => 'frm');
$output = get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();
if ($ID > 0) {
    $buttons[] = array('type' => 'submit', 'name' => 'modify_b', 'value' => __('Modify'), 'active' => false);
    $buttons[] = array('type' => 'submit', 'name' => 'loeschen', 'value' => __('Delete'), 'active' => false, 'onclick' => 'return confirm(\''.__('Are you sure?').'\');');
}
else {
    $buttons[] = array('type' => 'submit', 'name' => 'create_b', 'value' => __('Create'), 'active' => false);
}
$buttons[] = array('type' => 'link', 'href' => 'bookmarks.php', 'text' => __('back'), 'active' => false);

$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

if ($ID > 0) {
    $result = db_query("SELECT ID, datum, von, url, bezeichnung, bemerkung, gruppe
                          FROM ".DB_PREFIX."lesezeichen
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ($row[0]) {
        $row[3] = stripslashes($row[3]);
        $row[4] = stripslashes($row[4]);
        $row[5] = stripslashes($row[5]);

        // mark that the user has touched the record
        touch_record('lesezeichen', $ID);
    }
}

/*******************************
*       basic fields
*******************************/
$form_fields   = array();
$form_fields[] = array('type' => 'text', 'name' => 'url', 'label' => __('URL').__(':'), 'value' => html_out($row[3]));
$form_fields[] = array('type' => 'text', 'name' => 'bezeichnung', 'label' => __('Description').__(':'), 'value' => html_out($row[4]));
$form_fields[] = array('type' => 'textarea', 'name' => 'bemerkung', 'label' => __('Comment').__(':'), 'value' => html_out($row[5]));
$basic_fields  = get_form_content($form_fields);

$output .= '
<br/>
<div class="inner_content">
    <a name="content"></a>
    <a name="oben" id="oben"></a>
    <div class="boxHeaderLeft">'.__('Basis data').'</div>
    <div class="boxHeaderRight"><a class="formBoxHeader" href="#unten">'.__('Links').'</a></div>
    <div class="boxContent">'.$basic_fields.'</div></div>
    <br style="clear:both" /><br />

</form>
';

echo $output;

?>
