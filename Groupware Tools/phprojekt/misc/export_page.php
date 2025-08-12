<?php

// export_page.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Franz Graf, $auth$
// $Id: export_page.php,v 1.11 2005/07/04 10:59:48 nina Exp $

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);

$hiddenfields = hidden_fields($_REQUEST);

$radio_array = export_create_radio();

// ------------------------------------
echo set_page_header();
include_once($path_pre.'lib/navigation.inc.php');
echo "
<!-- begin content -->
<div class='content'>
    <div class='topline'></div>
    <div class='export_tabs_area'>
        <span class='tabs_area_modname'>".__('export')."</span>
    </div>
    <div class='topline'></div>
    <div class='hline'></div>

    <div style='margin:1em;'>
        <b>".export_create_header()."</b><br /><br />

        <form action='./export.php' method='post' style='margin:5px;'>
        $hiddenfields
";
foreach ($radio_array AS $key => $value) {
    echo "
        <label for='$key' class='settings' style='width:1em;'>$value</label>
        <input class='settings_options' style='border:none;' type='radio' name='medium' id='$key' value='$key' /><br style='clear:both;'/>\n";
}

echo '
        <input type="submit" class="submit" value="'.__('go').'" />
        </form>

        <br />
        <a href="'.export_create_link().'">'.__('back').'</a>
    </div>
</div>


<!-- end content -->
</body>
</html>
';


// -------------------------- Only Functions below --------------------------

/**
* creates the header depending on $file
*
* @return string header
*/
function export_create_header() {
    $header = "";
    if ($_REQUEST['file'] == "timecard") {        $header = __('export_timecard'); }
    if ($_REQUEST['file'] == "timecard_admin") {  $header = __('export_timecard'); }
    if ($_REQUEST['file'] == "users") {           $header = __('export_users'); }
    if ($_REQUEST['file'] == "contacts") {        $header = __('export_contacts'); }
    if ($_REQUEST['file'] == "projects") {        $header = __('export_projects'); }
    if ($_REQUEST['file'] == "bookmarks") {       $header = __('export_bookmarks'); }
    if ($_REQUEST['file'] == "timeproj") {        $header = __('export_timeproj'); }
    if ($_REQUEST['file'] == "project_stat") {    $header = __('export_project_stat'); }
    if ($_REQUEST['file'] == "project_stat_date") {$header = __('export_project_stat'); }
    if ($_REQUEST['file'] == 'todo') {            $header = __('export_todo'); }
    if ($_REQUEST['file'] == "notes") {           $header = __('export_notes'); }
    if ($_REQUEST['file'] == 'calendar') {        $header = __('export_calendar'); }
    if ($_REQUEST['file'] == 'calendar_detail'){  $header = __('export_calendar_detail'); }
    return $header;
}
function export_create_link() {
    $back = "";
    if(($_REQUEST['file'] == "project_stat") or($_REQUEST['file'] == "project_stat_date")){
    	$back='/'.PHPR_INSTALL_DIR.'projects/projects.php?mode=stat';
    }
    else if ($_REQUEST['file'] == "users") { 
    	$back='/'.PHPR_INSTALL_DIR.'contacts/contacts.php?action=members';	
    }
    else{
    	 $back='/'.PHPR_INSTALL_DIR.$_REQUEST['file'].'/'.$_REQUEST['file'].'.php';
    }
    return $back;
}

/**
* creates the array of radiobuttons
*
* @return array (file-extension => label)
*/
function export_create_radio() {
    $radio = array();

    if ($_REQUEST['file'] == 'calendar') {
        $radio['csv'] = 'CSV';
        $radio['ics'] = 'iCal';
        $radio['xml'] = 'XML';
        $radio['xls'] = 'XLS';
    }
    else {
        if (PHPR_SUPPORT_PDF) $radio['pdf'] = "PDF";
        $radio['xml']   = "XML";
        $radio['html']  = "HTML";
        $radio['csv']   = "CSV";
        $radio['xls']   = "XLS";
        $radio['rtf']   = "RTF";
        $radio['doc']   = "DOC";
        $radio['print'] = __('print');
    }

    return $radio;
}

?>
