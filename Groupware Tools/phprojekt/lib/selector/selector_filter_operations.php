<?php

// selector_filter_operations.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Franz Graf, $Author: johann $
// $Id: selector_filter_operations.php,v 1.6.2.1 2005/09/10 09:37:24 johann Exp $

/*
* This file has to be included after an initialization of a selector.
*/

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Not to be called directly!');


$sd = array();
if (is_array($part_personen)) {
    foreach($part_personen as $pcid)
    $sd['usr'.$pcid] = "on";
}
if (is_array($part_contacts)) {
    foreach($part_contacts as $pcid)
    $sd['con'.$pcid] = "on";
}

$_SESSION['filters'] =& $filters;
if (!isset($stuff) && isset($_POST['sthis'])) {
    $stuff = unserialize(urldecode($_POST['sthis']));
}
if (!isset($stuff) && isset($_SESSION['sthis1']) && isset($selector_name)) {
    $stuff = $_SESSION['sthis1'][$selector_name];
}

// this is an absolutely mad hack to get preselected items
// from the form via GET, when a filter is deleted :-/
// this has to be transformed in a PUT var... urghs
if (isset($_GET['preselect']) && $_GET['preselect'] != '') {
    $preselect_from_get = explode('-', $_GET['preselect']);
    foreach ($preselect_from_get as $val) {
        $_POST[$sel->name."dsts"][] = (int) $val;
    }
    unset($_GET['preselect']);
}

// Filter
if (isset($filterdel)) {
    $_SESSION['filters'] =& $filters;
    $sarr =& $filters[$sel->name];
    // delete all filter on '-1'
    if ($filterdel == '-1') $sarr = array();
    else                    unset($sarr[$filterdel]);
    $stuff['preselect'] = $sel->get_chosen();
} else if (isset($filterset_x) || isset($filterset)) {
    $prse = $sel->datasource."parse_filters";
    $prse($sel);
    $stuff['preselect'] = $sel->get_chosen();
}
if (isset($filterform) && $filterform == "done") {
    // no new filter is set, but we might have used some extras
    $stuff['preselect'] = $sel->get_chosen();
    foreach (${$sel->datasource.'extras'} as $val) {
        foreach ($val['formname'] as $formname) {
            if (isset(${$formname}) && !empty(${$formname})) {
                $answer = $val['evalform']($sel->sourcedata);
                $stuff['preselect'] = $stuff['preselect']+$answer;
            }
        }
    }
}
// actualize after submit

// Wenn's die session-var noch nicht gibt, initialisieren wir sie
// mit $stuff['preselect'] - weiß ja nicht, ob da schon was drinsteht
// 'javascript' ist vorsichtshalber noch true (=nix barrierefrei)
// Die Sessionvar ist so krank benannt, damit man sie in der Session als einen
// bestimmten calender_selektor wiedererkennen kann.
if (!isset($_SESSION[$selector_name])) {
    $_SESSION[$selector_name]['data'] = $stuff['preselect'];
    $_SESSION[$selector_name]['javascript'] = true;
}

// Ergebisse vom Filter mit der Session mergen
if (isset($stuff['preselect']) && !empty($stuff['preselect'])) {
    foreach ($stuff['preselect'] as $tmp_val) {
        $_SESSION[$selector_name]['data'][$tmp_val] = "on";
    }
}

// JavaScript ist NICHT aktiv, wenn einer der hin/her-Buttons durchkommt.
// Ab dann ist alles gut (dann kann gesucht werden solang gewollt)
if (isset($_POST['movsrcdst']) or isset($_POST['movdstsrc']) or !$_SESSION[$selector_name]['javascript']) {
    $_SESSION[$selector_name]['javascript'] = false;

    // Adden: Einträge links sind selektiert und müssen zu den sessiondaten gemerged werden
    if (isset($_POST['movsrcdst']) && isset($_POST[$selector_name.'srcs'])) {
        foreach ($_POST[$selector_name.'srcs'] as $tmp_val) {
            $_SESSION[$selector_name]['data'][$tmp_val] = "on";
        }
    }
    unset($tmp_val);
    // Removen: Einträge rechts sind selektiert und sollen von den sessiondaten "abgezogen" werden
    if (isset($_POST['movdstsrc']) && isset($_POST[$selector_name.'dsts'])) {
        foreach ($_POST[$selector_name.'dsts'] as $tmp_val) {
            unset($_SESSION[$selector_name]['data'][$tmp_val]);
        }
    }
    unset($tmp_val);
    // aktualisierte Daten zurückschreiben
    $stuff['preselect'] = $_SESSION[$selector_name]['data'];
}

?>
