<?php

// mysql.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: mysql.inc.php,v 1.8.2.2 2005/08/19 12:46:01 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) die("Please use index.php!");

// Connect
$dbIDnull = "null";

$_database_ressource_identifier = mysql_connect($db_host, $db_user, $db_pass) or mysql_error();
$_database_connection = mysql_select_db($db_name);
if (!$_database_ressource_identifier or (isset($_database_connection) and !$_database_connection)) die("<b>Database connection failed!</b><br>Call admin, please.");

// execute sql query
function db_query($query) {
    return mysql_query($query);
}

// fetch row statement
function db_fetch_row($result) {
    return mysql_fetch_row($result);
}

// Error-Messages
// only display them if error reporting for phprojekt is activated. 
// else exit with a non path disclosing error message
function db_die() {
    if (PHPR_ERROR_REPORTING_LEVEL == 1) {
        echo '<p><b>Database error.</b> Error message: ', mysql_error(), '</p><p>Backtrace:</p><pre>';
        print_r(debug_backtrace());
        die("</pre></body></html>");
    } else die(__('Sorry, there has been a database error. Please contact your local system administrator for help.'));
}

// error code
function get_sql_errno($resource) {
    // $resource not needed, but keep it in parameter list
    return mysql_errno();
}

?>
