<?php

// index.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: index.php,v 1.35.2.1 2005/09/21 12:48:23 fgraf Exp $


// ***********
// preparation

// define the error level for the next lines, it will be changed in the lib
// to the desired value.
error_reporting(0);

// set some other variables
$var_ini_set = ini_set('magic_quotes_gpc', 'on');
$var_ini_set = ini_set('include_path',     './');
// avoid this d... error warning since it does not affect the scritps here
$var_ini_set = ini_set('session.bug_compat_42',   1);
$var_ini_set = ini_set('session.bug_compat_warn', 0);

// authentification etc.
$path_pre = './';
$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;

// set baseurl
$bu1 = explode('index.php', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
$_SESSION['baseurl'] = $bu1[0];

// redirect
redirect();

// ´define today
if (!$day) today();

// *******
// actions
// *******

// 1. action: logout
// logout  -> login!
if ($module == 'logout') { logout(); }

// 2. action: change groups
// if change of group, set it in variable
if ($change_group) {
    // is the user member of the requested group?
    $result = db_query("SELECT grup_ID
                          FROM ".DB_PREFIX."grup_user
                         WHERE user_ID = '$user_ID'") or db_die();
    while ($row = db_fetch_row($result)) {
        $groups_[] = $row[0];
    }
    if (!in_array($change_group, $groups_)) {
        exit;
    }
    $user_group = $change_group;
    $sql_user_group = "(gruppe = '$user_group')";
    $_SESSION['user_group'] =& $user_group;
}

// 3. action: close chat
// close chat? -> delete alivefile & chatfile
if ($chataction == 'logout') {
    $alivefile = $user_group.'_alive';
    $chatfile  = $user_group.'_'.$chatfile;

    // last personen closes the light :-)
    if (file_exists('chat/'.$alivefile)) {
        $lines = file('chat/'.$alivefile);
    }
    if (!$lines[1]) {
        // save chat file only if a flag in the config is set
        if ($save_chat) {
            // prepare name of file to save
            $datum   = date("D_d_M_Hui");
            $newname = $datum.'-'.$user_group.'.txt';
            copy("$chatfile","$newname");
        }
        if (file_exists("chat/$chatfile")) {
            unlink("chat/$chatfile");
        }
        if (file_exists("chat/$alivefile")) {
            unlink("chat/$alivefile");
        }
    }
}


// 4. action: call frames

// define how a modules starts: with tree view open or closed and x items/per page
if (!$tree_mode) {
    if ($start_tree_mode) {
        $tree_mode = $start_tree_mode;
    } else {
        $tree_mode = 'open';
    }
}

// no module chosen?
if (!$module) {
    if ($startmodule <> '') {
        // take the start module for the settings ...
        $module = $startmodule;
    } else {
        // or as the default value summary
        $module = 'summary';
    }
}

// redirect to where the user wanted to go, except logout page
if (strlen($_REQUEST['return_path']) and !ereg('logout', $_REQUEST['return_path'])) {
    $return_path = urldecode($_REQUEST['return_path']);
    if($return_path == '/'){
        $return_path .= 'index.php';
    }

    if (strpos($return_path, "/")===0) {
        $url = substr($return_path, 1);
    }

    if(strstr($url, '?')){
        $url .= '&'.SID;
    }
    else{
        $url .= '?'.SID;
    }
    header('Location: '.$url);
    exit;
}

if ($module != 'logout') {
    header('Location: '.$module.'/'.$module.'.php?'.$_SERVER['QUERY_STRING']);
    exit;
}



// ****************
// logout functions
function logout() {
    global $path_pre;
    track_logout();
    // store settings: filter, column width, sort
    save_settings();
    // destroy the session - on some system the first, on some system the second function doesn't work :-|
    @session_unset();
    @session_destroy();
    unset($user_pw, $user_name, $module);
    // call the loginscreen again
    include $path_pre.'lib/auth.inc.php';
}

// track logout
function track_logout() {
    global $dbTSnull;
    if ($GLOBALS['logs'] and $GLOBALS['logID']) {
        $logID = $GLOBALS['logID'];
        $result2 = db_query("UPDATE ".DB_PREFIX."logs
                                SET logout = '$dbTSnull'
                              WHERE ID = '$logID'") or db_die();
    }
}

function save_settings() {
    global $user_ID, $f_sort, $flist, $diropen, $tdw;
    $result = db_query("SELECT settings
                          FROM ".DB_PREFIX."users
                         WHERE ID = '$user_ID'") or db_die();
    $row = db_fetch_row($result);
    $tmp_settings = unserialize($row[0]);
    if ($f_sort)  $tmp_settings['f_sort_store']  = $f_sort;
    if ($flist)   $tmp_settings['flist_store']   = $flist;
    if ($diropen) $tmp_settings['diropen_store'] = $diropen;
    if ($tdw)     $tmp_settings['tdw_store']     = $tdw;
    if ($_SESSION['show_read_elements']) {
        $tmp_settings['show_read_elements_settings'] = $_SESSION['show_read_elements'];
    }
    if ($_SESSION['show_archive_elements']) {
        $tmp_settings['show_archive_elements_settings'] = $_SESSION['show_archive_elements'];
    }

    $result = db_query("UPDATE ".DB_PREFIX."users
                           SET settings = '".serialize($tmp_settings)."'
                         WHERE ID = '$user_ID'") or db_die();
}

?>
