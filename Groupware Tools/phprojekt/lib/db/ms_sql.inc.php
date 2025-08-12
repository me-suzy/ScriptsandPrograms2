<?php

// ms_sql.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: ms_sql.inc.php,v 1.4.2.1 2005/08/19 12:46:01 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) die("Please use index.php!");

// Connect
$dbIDnull = "msnull";
$_database_ressource_identifier = mssql_connect($db_host, $db_user, $db_pass);
$_database_connection = mssql_select_db($db_name);
if (!$_database_ressource_identifier or (isset($_database_connection) and !$_database_connection)) die("<b>Database connection failed!</b><br>Call admin, please.");

// execute sql query
function db_query($query) {
    $sPattern  = 'msnull[ ]*,';
    $sNewQuery = eregi_replace($sPattern, '', $query);
    if ($sNewQuery != $query) {
        $sPattern  = '\([ ]*ID[ ]*,';
        $sNewQuery = eregi_replace($sPattern, '(', $sNewQuery);
        $sPattern  = '\,[ ]*ID[ ]*,';
        $sNewQuery = eregi_replace($sPattern, ',', $sNewQuery);
    }
    $sPattern  = '\\\\\'';
    $sSubst    = '\'\'';
    $sNewQuery = eregi_replace($sPattern, $sSubst, $sNewQuery);
    $sPattern  = '\\\\"';
    $sSubst    = '"';
    $sNewQuery = eregi_replace($sPattern, $sSubst, $sNewQuery);
    return mssql_query($sNewQuery);
}

// fetch row statement
function db_fetch_row($result) {
    return mssql_fetch_row($result);
}

// Error-Messages
function db_die() {
    echo mssql_get_last_message();
    die("</body></html>");
}

// error code
function get_sql_errno($resource) {
    return mssql_get_last_message();
}

?>
