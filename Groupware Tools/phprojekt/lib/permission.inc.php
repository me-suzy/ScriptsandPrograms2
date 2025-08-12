<?php

// permission.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: permission.inc.php,v 1.4 2005/07/12 14:55:21 paolo Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) die('Please use index.php!');


// check permission
function check_permission($table, $author, $ID) {
    global $user_ID;

    $result = db_query("SELECT ".qss($author)."
                          FROM ".qss(DB_PREFIX.$table)."
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ($row[0] == 0) die('no entry found.');
    if ($row[0] <> $user_ID) die('You are not privileged to do this!');
}

?>
