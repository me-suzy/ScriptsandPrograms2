<?php

// interbase.inc.php - PHProjekt Version 4.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: interbase.inc.php,v 1.5.2.1 2005/08/19 12:46:01 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) die("Please use index.php!");

// Connect
$dbIDnull = "null";
$db_host2 = $db_host.":".$db_name;
$_database_ressource_identifier = ibase_connect($db_host2, $db_user, $db_pass);
if (!$_database_ressource_identifier) die("<b>Database connection failed!</b><br>Call admin, please.");

// execute sql query
function db_query($query) {
    global $_database_ressource_identifier, $stmt;

    // check and repair blank data '' and quotes in queries
    if (eregi('select',$query)) {
        $query = ereg_replace("= ''","= '99999'",$query);
        $query = ereg_replace("=''","= '99999'",$query);
    }
    if (eregi('insert|update|delete|create',$query)) {
        $query = ereg_replace("= ''","= NULL",$query);
        $query = ereg_replace("=''","= NULL",$query);
        $query = ereg_replace(",''",",NULL",$query);
        $query = ereg_replace(", ''",",NULL",$query);
        $query = ereg_replace("\\\'","''",$query);
        $query = ereg_replace('\\\"','"',$query);
    }

    // Check for Interbase-Firebird reserved words
    $patterns[] = '/(\W)(role)(\W)/i';
    $patterns[] = '/(\W)(type)(\W)/i';
    $patterns[] = '/(\W)(password)(\W)/i';
    $patterns[] = '/(\W)(action)(\W)/i';
    $query = preg_replace($patterns, '\\1"\\2"\\3', $query);

    //echo $query;
    //echo "<br />";

    $stmt = ibase_query($_database_ressource_identifier, $query);
    if (eregi('insert|update|delete|create', $query)) {
        ibase_commit();
    }
    return $stmt;
}

// fetch row statement
function db_fetch_row($result) {
    return ibase_fetch_row($result);
}

// Error-Messages
function db_die() {
    echo ibase_errmsg();
    die("</body></html>");
}

// error code
function get_sql_errno($resource) {
    return ibase_errcode();
}

?>
