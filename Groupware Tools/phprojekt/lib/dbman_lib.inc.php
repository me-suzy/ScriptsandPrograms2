<?php

// dbman_lib.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: dbman_lib.inc.php,v 1.45.2.3 2005/09/21 06:54:29 fgraf Exp $

if (!defined('lib_included')) die('Please use index.php!');

// space for element names which are displayed on the left side before the input element
$text_width = 75;

// include lib to fetch the sessiond data and to perform check
if (!$path_pre) $path_pre = '../';
include_once($path_pre.'lib/lib.inc.php');

include_once($lib_path.'/dbman_forms.inc.php');
include_once($lib_path.'/dbman_list.inc.php');
include_once($lib_path.'/dbman_filter.inc.php');
include_once($lib_path.'/dbman_data.inc.php');

// include context menu in any other case
if ($contextmenu == 1) {
    include_once($path_pre.'lib/contextmenu.inc.php');
     $menu2 = new contextmenu();
}



// fetch all elements of a form, it's properties and the related values if an ID is given
function build_array($module, $ID, $mode='forms', $id_field='ID') {
    global $tablename;

    // determine whether the list of the form mode is active - and then just fetch those records which have a position value > 0
    $mode2 = ($mode == 'view') ? 'list_pos' : 'form_pos';

    $table = isset($tablename[$module]) ? $tablename[$module] : $module;
    $table = ($table == 'files') ? 'dateien' : $table;
    // form array for fields
    $result = db_query("SELECT db_name, form_name, form_type, form_tooltip, form_pos, form_regexp,
                               form_default, form_select, list_pos, filter_show, list_alt, db_table,
                               form_colspan, form_rowspan
                          FROM ".DB_PREFIX."db_manager
                         WHERE db_table LIKE '$table'
                           AND $mode2 > 0
                           AND db_inactive <> '1'
                      ORDER BY $mode2, ID") or db_die();
    while ($row = db_fetch_row($result)) {
        $fields[$row[0]] = array( 'form_name'    => $row[1],
                                  'form_type'    => $row[2],
                                  'form_tooltip' => $row[3],
                                  'form_pos'     => $row[4],
                                  'form_regexp'  => $row[5],
                                  'form_default' => $row[6],
                                  'form_select'  => $row[7],
                                  'list_pos'     => $row[8],
                                  'filter_show'  => $row[9],
                                  'list_alt'     => $row[10],
                                  'tablename'    => $row[11],
                                  'form_colspan' => $row[12],
                                  'form_rowspan' => $row[13] );
    }
    if (count($fields)>=1) $db_fields = array_keys($fields);


    // fetch the values of this record - either with a valid ID (>0) or as dummy entries
    if (!ereg(',', $ID) and $mode2 = 'form_pos' and $ID > 0) {
        $i = 0;
        $result = db_query("SELECT ".implode(',', $db_fields)."
                              FROM ".qss(DB_PREFIX.$table)."
                             WHERE ".qss($id_field)." = '$ID'") or db_die();
        $row = db_fetch_row($result);
        if ($row) $row = explode("·", html_out(implode("·", $row)));
        for ($i=0; $i < count($row); $i++) {
            $fields[$db_fields[$i]]['value'] = $row[$i];
        }
    }
    else {
        for ($i=0; $i < count($db_fields); $i++) {
            $fields[$db_fields[$i]]['value'] = '';
        }
        // add projectID and contactID
    }

    return $fields;
}


// end file operations
// ****************

// if the mail module is installed, then all mailto: links
// should point to this module
function showmail_link($mailadress) {
    if (PHPR_QUICKMAIL > 0) {
        $str =  "<a href=\"javascript:mailto(0,'$mailadress','".(SID ? session_id() : '')."')\">$mailadress</a>&nbsp;\n";
    }
    else {
        $str = "<a href='mailto:$mailadress'>$mailadress</a>&nbsp;\n";
    }
    return $str;
}

// replaces strings with a dollaras prefix with the value of the variables of the same name
function enable_vars($string) {
    if(strpos($string, "__('") !== false){
        // replace language function
        $string = preg_replace("/(__\('.*?'\))/e",
              "''.eval('return \\1;').''",
              $string);
        // replace some other specials like concatenating operators
        $string = preg_replace("/(^.*$)/e",
              "eval('return \"\\1\";')",
              $string);
        return $string;
    }
    return preg_replace_callback('#\$(\w+)#si', 'enable_vars2', $string);
}
function enable_vars2($varname) {
    return $GLOBALS[$varname[1]];
}


/**
* sets the archiv flag to several entries
* @author Albrecht Günther / Alex Haslberger
* @param array  $ID ids of the entries
* @param string $module module to which the entry belongs
* @return void
*/
function set_archiv_flag($ID, $module) {
    global $user_ID, $user_access, $dbIDnull, $dbTSnull;

    $arr_ID = explode(',',$ID);
    // check which ID has already an entry
    $result = db_query("SELECT t_record
                          FROM ".DB_PREFIX."db_records
                         WHERE t_record in ('".implode("','", $arr_ID)."')
                           AND t_author = '$user_ID'
                           AND t_module = '$module'");
    $ids = db_fetch_row($result);
    if (!is_array($ids)){ return; }
    
    foreach ($arr_ID as $ID) {
        if (in_array($ID, $ids)) {
            $result = db_query(xss("UPDATE ".DB_PREFIX."db_records
                                   SET t_datum = '$dbTSnull',
                                       t_archiv = 1
                                 WHERE t_record = '$ID'
                                   AND t_module = '$module'
                                   AND t_author = '$user_ID'")) or db_die();
        }
        else {
            $result = db_query(xss("INSERT INTO ".DB_PREFIX."db_records
                                            ( t_ID,    t_author,             t_module,             t_record,  t_datum, t_archiv)
                                     VALUES ($dbIDnull,'$user_ID', '$module',  '$ID', '$dbTSnull', 1)")) or db_die();
        }
    }
}


/**
* sets the read flag to several entries
* @author Albrecht Günther / Alex Haslberger
* @param array  $ID ids of the entries
* @param string $module module to which the entry belongs
* @return void
*/
function set_read_flag($ID, $module) {
    global $user_ID, $dbIDnull, $dbTSnull;
    $arr_ID = explode(',',$ID);
    settype($ID, 'array');
    
    // check which ID has already an entry
    $result = db_query("SELECT t_record
                          FROM ".DB_PREFIX."db_records
                         WHERE t_record in ('".implode("','", $arr_ID)."')
                           AND t_author = '$user_ID'
                           AND t_module = '$module'");
    $ids = db_fetch_row($result);
    if (!is_array($ids)){ return; }
    
    foreach ($arr_ID as $ID) {
        if (in_array($ID, $ids)) {
            $result = db_query(xss("UPDATE ".DB_PREFIX."db_records
                                   SET t_datum = '$dbTSnull',
                                       t_touched = 1
                                 WHERE t_record = '$ID'
                                   AND t_module = '$module'
                                   AND t_author = '$user_ID'")) or db_die();
        }
        else {
            $result = db_query(xss("INSERT INTO ".DB_PREFIX."db_records
                                            ( t_ID,    t_author,             t_module,             t_record,  t_datum, t_touched)
                                     VALUES ($dbIDnull,'$user_ID', '$module',  '$ID', '$dbTSnull', 1)")) or db_die();
        }
    }
}


function set_status($ID, $module, $status) {
    global $user_ID, $user_access, $tablename;

    $arr_ID = explode(',', $ID);
    foreach ($arr_ID as $ID) {
        $result = db_query(xss("UPDATE ".qss(DB_PREFIX.$tablename[$module])."
                               SET status = '". (int) $status ."'
                             WHERE ID = '$ID'")) or db_die();
    }
}


function set_button($url, $method='post', $hidden, $submitname='submit', $submitvalue) {
    $str = "<form action='$url' method='$method'>\n";
    foreach ($hidden as $hidden_name => $hidden_value) { $str .= "<input type=hidden name='$hidden_name' value='$hidden_value'>\n"; }
    $str .= "<input class='center' type=submit name='$submitname' value='$submitvalue'></form>\n";
    return $str;
}

?>
