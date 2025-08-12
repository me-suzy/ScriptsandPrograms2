<?php

// oracle.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: oracle.inc.php,v 1.4.2.1 2005/08/19 12:46:01 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) die("Please use index.php!");

// Connect
$dbIDnull = "null";

$_database_ressource_identifier = OCILogon($db_user, $db_pass, $db_name);
$datestmt = OCIParse($_database_ressource_identifier, "alter session set NLS_DATE_FORMAT='YYYY-MM-DD HH:MI:SS'");
OCIExecute($datestmt);
if (!$_database_ressource_identifier) die("<b>Database connection failed!</b><br>Call admin, please.");

// execute sql query
function db_query($query) {
    global $_database_ressource_identifier;

    if (eregi('insert|update|delete|create',$query)) {
        if ( (eregi('insert', $query)) && (stristr($query, '(null,')) ) {
            $tok = explode (" ", $query);
            $seq_name = $tok[2];
            $seq_name = '('.$seq_name."_id_seq.nextval";
            $query = str_replace ('(null,', $seq_name, $query);
        }
        $stmt = OCIParse($_database_ressource_identifier, $query);
        OCIExecute($stmt);
        $commit_stmt = OCIParse($_database_ressource_identifier, 'commit');
        OCIExecute($commit_stmt);
    }
    else {
        $stmt = OCIParse($_database_ressource_identifier, $query);
        OCIExecute($stmt);
    }
    return $stmt;
}

// fetch row statement
function db_fetch_row ($result) {
    OCIFetchInto($result, $row, OCI_RETURN_NULLS+OCI_RETURN_LOBS);
    return $row;
}

// Error-Messages
function db_die() {
    echo OCIError($stmt);
    die("</body></html>");
}

// error code
function get_sql_errno($resource) {
    $error = OCIError();
    if ($error !== false) {
        return $error['code'];
    }
    return '';
}

?>
