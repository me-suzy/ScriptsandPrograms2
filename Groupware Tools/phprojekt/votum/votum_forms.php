<?php

// votum_forms.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: votum_forms.php,v 1.17.2.1 2005/08/12 14:27:35 paolo Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("votum") < 2) die("You are not allowed to do this!");


// tabs
$tabs = array();

// form start
$buttons = array();
$hidden  = array('action' => 'new', 'mode' => 'data');
if (SID) $hidden[session_name()] = session_id();
$buttons[] = array('type' => 'form_start', 'hidden' => $hidden, 'onsubmit' => 'return chkForm(\'frm\',\'thema\',\''.__('Please specify a description!').'!\')', 'name' => 'frm');

$output = get_buttons($buttons);
$output .= get_tabs_area($tabs);

// button bar
$buttons = array();

$buttons[] = array('type' => 'submit', 'name' => 'submit', 'value' => __('Create'), 'active' => false);
$buttons[] = array('type' => 'link', 'href' => 'votum.php', 'text' => __('back'), 'active' => false);
$output .= get_buttons_area($buttons);
$output .= '<div class="hline"></div>';

/*******************************
*       basic fields
*******************************/
$form_fields = array();
$form_fields[] = array('type' => 'text', 'name' => 'thema', 'label' => __('Question:'), 'value' => '');
$html = '
<label for="modus" class="formbody">'.__('Alternatives').__(':').'</label>
<input type="radio" name="modus" id="modus" value="r" checked="checked" class="options" /> '.__('just one <b>Alternative</b> or').'
<input class="options" type="radio" name="modus" value="c" /> '.__('several to choose?');
$form_fields[] = array('type' => 'parsed_html', 'html' => $html);
$form_fields[] = array('type' => 'text', 'name' => 'text1', 'label' => __('Text').' 1'.__(':'), 'value' => '');
$form_fields[] = array('type' => 'text', 'name' => 'text2', 'label' => __('Text').' 2'.__(':'), 'value' => '');
$form_fields[] = array('type' => 'text', 'name' => 'text3', 'label' => __('Text').' 3'.__(':'), 'value' => '');
$basic_fields = get_form_content($form_fields);

/*******************************
*    participants fields
*******************************/
$form_fields = array();
$html = '';

// manual selection
if ($user_group) {
    $query = "SELECT users.ID, nachname, vorname, role
                FROM ".DB_PREFIX."users, ".DB_PREFIX."grup_user
               WHERE grup_ID = '$user_group'
                 AND user_ID = ".DB_PREFIX."users.ID
                 AND ".DB_PREFIX."users.status = 0
                 AND ".DB_PREFIX."users.usertype = 0
            ORDER BY nachname";
}
else {
    $query = "SELECT ID, nachname, vorname, role
                FROM ".DB_PREFIX."users
               WHERE status = 0
                 AND usertype = 0
            ORDER BY nachname";
}
$result2 = db_query($query) or db_die();
while ($row2 = db_fetch_row($result2)) {
    // only show these users which are allowed to take part in a vote
    $result = db_query("SELECT ".DB_PREFIX."roles.ID, votum
                          FROM ".DB_PREFIX."roles, ".DB_PREFIX."users
                         WHERE role=".DB_PREFIX."roles.ID") or db_die();
    $row = db_fetch_row($result);
    if (!$row[0] or $row[1] > 0) {
        $html .= "<input type='checkbox' name='s[]' value='$row2[0]' /> $row2[1], $row2[2]\n";
    }
}

// profiles
$html .= "&nbsp;-&nbsp;".__('or profile').": <select name='profil'>\n";
$html .= "<option value='0'></option>\n";
$result = db_query("SELECT ID, bezeichnung
                      FROM ".DB_PREFIX."profile
                     WHERE von='$user_ID'") or db_die();
while ($row = db_fetch_row($result)) {
    $html .= "<option value='$row[0]'>".html_out($row[1])."</option>\n";
}
$html .= "</select>\n<br />\n";

$form_fields[] = array('type' => 'parsed_html', 'html' => $html);
$participants_fields = get_form_content($form_fields);

$output .= '
<br />
<div class="inner_content">
    <a name="content"></a>
    <div class="boxHeader">'.__('Basis data').'</div>
    <div class="boxContent">'.$basic_fields.'</div>
    <br style="clear:both" /><br />

    <div class="boxHeader">'.__('Participants:').'</div>
    <div class="boxContent">'.$participants_fields.'</div>
    <br style="clear:both" /><br />
</div>
';

echo $output;

?>
