<?php

// settings_selector.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Albrecht Guenther, Franz Graf, $Author: fgraf $
// $Id: settings_selector.php,v 1.19.2.1 2005/09/07 14:02:33 fgraf Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Please use settings.php!');

include_once($lib_path."/selector/selector.inc.php");

echo '
<div class="inner_content">
    <div class="boxContent">
';

// --------- Selektor config ---------
// Options for Quickaddings
// This isn't called quick_ADD_ for fun! If one of the
// entries is chosen it's put into the 'chosen'-box!
$usersextras = array(
                    //'names'    => array('getform'=>'usersextra_names',
                                          //'evalform' => 'userseval_extra_names',
                                          //'formname' => array('usersextra_namevor','usersextra_namenach')),
                    'profiles' => array('getform'  => 'usersextra_profiles',
                                        'evalform' => 'userseval_extra_profiles',
                                        'formname' => array('usersextra_profile','usersextra_profileglob')),
                    //'projects' => array('getform'=>'usersextra_projects',
                                          //'evalform' => 'userseval_extra_projects',
                                          //'formname' => array('usersextra_project','usersextra_projectglob','usersextra_projectbutton')),
                    'groups'   => array('getform'  => 'usersextra_groups',
                                        'evalform' => 'userseval_extra_groups',
                                        'formname' => array('usersextra_group'))
                    );

// Options for datasource
$opt_where = array("g.grup_ID=$user_group", "g.user_ID=u.ID");
$g_grup_ID = selector_get_groupIds();
if (is_array($g_grup_ID) && count($g_grup_ID) > 0) {
    $opt_where[] = "g.grup_ID IN ('".implode("','", $g_grup_ID)."')";
}
unset($g_grup_ID);
$opt = array('title'     => $_SESSION['settings_5']['formdata']['_title'],
             'table'     => array('users as u','grup_user as g'),
             'where'     => $opt_where,
             'order'     => 'u.nachname',
             'direction' => 'asc',
             'ID'        => 'u.ID',
             'display'   => array('u.vorname','u.nachname','u.firma'),
             //'filter'    => array('text'=> array('u.vorname'=>'Vorname', 'u.nachname'=>'Familienname','u.firma'=>'Firma'), 'alternative' => array()),
             'dstring'   => '%s %s: %s',
             'save'      => array('table'=>'protokoll','field'=>'part_personen','method'=>'serialize','where'=>"ID=$ID"),
             'extra'     => array('projects'=>$row[14]),
             'choose'    => 'Benutzer',
             //'reload'    => "protokoll.php?mode=forms&ID=$ID&".$sid,
             'reload'    => '',
             //'limit'     => '100',
             'filter'    => array('text' => array('nachname' => __('Family Name'), 'vorname' => __('First Name')) )
            );

$selector_name = "settings_selector_";
if (isset($delete_selector_filters)) $filters[$selector_name] = array();
include_once($lib_path."/selector/class.selector.php");
echo "<script src='".$lib_path."/selector/dbl_select_mover.js' type='text/javascript'></script>\n";

// new Selektor
$sel = new PHProjektSelector($selector_name, 'users', $opt, 'multiple', 'select');
$sel->finishFormSubmitName = 'finishForm.'.$_SESSION['settings_5']['formdata']['_return'];
$sel->set_hidden_fields(array('mode' => $_SESSION['settings_5']['formdata']['_mode'], 'action_selector_to_selector' => 1 ));


settype($_SESSION['settings_5']['formdata']['_selector'], 'array');
if (!isset($stuff['preselect'])) {
    $stuff['preselect'] = array();
    foreach ($_SESSION['settings_5']['formdata']['_selector'] as $tmp_id) {
        $stuff['preselect'][$tmp_id] = "1";
    }
    unset($tmp_id);
}
include_once($lib_path."/selector/selector_filter_operations.php");

// print all the stuff!
$sel->show_window($stuff['preselect'], 15, "./settings.php");
// ---------- Selektor end ---------



// ---------- Finishform begin ---------
// Jetzt kommt das form, mit dem die Daten wieder in den Aufrufer zurückgeschossen
// werden sollen.
// Alle ursprünglichen REQUEST-Daten und die neuen Kontakte werden hier aufgeführt.
// Ein Extra-FORM wird hier deswegen gebraucht, da hier evtl noch ein separater "mode"
// übergeben werden soll
echo "
<br />
<form action='".$_SERVER['PHP_SELF']."' method='post' name='finishForm'>
    <input type='hidden' name='ID'   value='".$_SESSION['settings_5']['formdata']['_ID']."' />
    <input type='hidden' name='mode' value='".$_SESSION['settings_5']['formdata']['_mode']."' />
";

if (SID) echo "    <input type='hidden' name='".session_name()."' value='".session_id()."' />\n";

// in get_chosen the shortnames are listed
foreach ($stuff['preselect'] as $tmp_ID => $tmp) {
    echo "    <input type='hidden' name='selector[]' value='".$tmp_ID."' />\n";
}
unset($tmp, $tmp_ID);
echo "
    <input class='submit' type='submit' name='".$_SESSION['settings_5']['formdata']['_return']."' value='".__('Undertake')."' />
    <input class='submit' type='submit' name='".$_SESSION['settings_5']['formdata']['_cancel']."' value='".__('Cancel')."' />
</form>
";
// ---------- Finishform end ---------

?>
