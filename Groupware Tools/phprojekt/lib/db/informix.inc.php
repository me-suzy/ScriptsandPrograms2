<?php

// informix.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: informix.inc.php,v 1.4.2.1 2005/08/19 12:46:01 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) die("Please use index.php!");

// Connect
$dbIDnull = "0";
if ($db_host == "") $db = $db_name;
else $db = $db_name."@".$db_host;
$_database_ressource_identifier = ifx_connect($db, $db_user, $db_pass);
if (!$_database_ressource_identifier) die("<b>Database connection failed!</b><br>Call admin, please.");

// execute sql query
function db_query($query) {
    $rid = ifx_prepare($query, $_database_ressource_identifier);
    if (!ifx_do($rid)) {
        ifx_error();
    }
    return $rid;
}

// fetch row statement
function db_fetch_row($result) {
    if ($row = ifx_fetch_row($result)) {
        $types = ifx_fieldtypes($result);
        $row = array_values($row);
    }
    return $row;
}

// Error-Messages
function db_die() {
    echo ifx_error();
    die("</body></html>");
}

// error code
function get_sql_errno($resource) {
    return ifx_error();
}

?>
