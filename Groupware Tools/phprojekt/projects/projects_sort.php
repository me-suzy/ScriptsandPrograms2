<?php

// projects_sort.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: projects_sort.php,v 1.3 2005/06/20 14:52:51 paolo Exp $

function projects_sort($liste0) {
    global $liste2;

    $liste2 = array();
    for ($i=0; $i< count($liste0); $i++) {
        projects_sort2($liste0[$i]);
    }
    return $liste2;
}

function projects_sort2($ID) {
    global $liste2;

    $result2 = db_query("SELECT ID, next_mode, next_proj
                           FROM ".DB_PREFIX."projekte
                          WHERE ID = '$ID'") or db_die();
    $row2 = db_fetch_row($result2);
    // only consider this project if it hasn't been listed before
    if (!$liste2[0] or !in_array($row2[0], $liste2)) {
        // if a) this project is dependend from another or 2) as something in the list before, take this first
        if ($row2[1] == 1) projects_sort2($row2[2]);
        // now take the record itself to the list
        $liste2[] = $ID;
        // it has an entry after him? now consider this one.
        if ($row2[1] == 2) projects_sort2($row2[2]);
    }
}

?>
