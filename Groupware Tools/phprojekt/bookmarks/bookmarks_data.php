<?php

// bookmarks_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: bookmarks_data.php,v 1.7 2005/06/10 15:20:51 paolo Exp $

// security issue: check whether the lib has been included
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("bookmarks") < 2) die("You are not allowed to do this!");


if ($loeschen)    delete_bookmark();
else if ($ID > 0) update_bookmark();
else              insert_bookmark();

include_once("../bookmarks/bookmarks_view.php");


function delete_bookmark() {
    global $ID, $user_ID;

    if ($ID > 0) {
        $res = db_query("SELECT bezeichnung
                           FROM ".DB_PREFIX."lesezeichen
                          WHERE ID = '$ID'
                            AND von = '$user_ID'");
        $row = db_fetch_row($res);
        if ($row[0]) {
            $row[0] = stripslashes($row[0]);
            $res = db_query("DELETE FROM ".DB_PREFIX."lesezeichen
                                   WHERE ID = '$ID'
                                     AND von = '$user_ID'") or db_die();
            if ($res) {
                message_stack_in("$row[0] ".__(' is deleted.'), "boookmarks", "notice");
            }
        }
    }
}


function update_bookmark() {
    global $url, $bezeichnung, $bemerkung, $ID, $user_ID;

    $res = false;
    if (check_values()) {
        $query = "UPDATE ".DB_PREFIX."lesezeichen
                     SET url = '".addslashes($url)."',
                         bezeichnung = '".addslashes($bezeichnung)."',
                         bemerkung = '".addslashes($bemerkung)."'
                   WHERE ID = '$ID'
                     AND von = '$user_ID'";
        $res = db_query(xss($query)) or db_die();
    }
    if ($res) {
        message_stack_in(xss($bezeichnung)." ".__(' is changed.'), "bookmarks", "notice");
    }
}


function insert_bookmark() {
    global $url, $bezeichnung, $dbIDnull, $dbTSnull, $user_ID, $bemerkung, $user_group;

    $res = false;
    if (check_values()) {
        $query = "INSERT INTO ".DB_PREFIX."lesezeichen
                              ( ID,        datum,     von,       url,
                                bezeichnung,   bemerkung,  gruppe )
                       VALUES ($dbIDnull, '$dbTSnull', '$user_ID', '".addslashes($url)."',
                               '".addslashes($bezeichnung)."','".addslashes($bemerkung)."','$user_group')";
        $res = db_query(xss($query)) or db_die();
    }
    if ($res) {
        message_stack_in(xss($bezeichnung)." ".__('is taken to the bookmark list.'), "bookmarks", "notice");
    }
}


function check_values() {
    global $ID, $url, $bezeichnung, $sid, $sql_user_group;

    $ret = true;
    if (!$url) {
        message_stack_in(__('Insert a valid Internet address! ')."!", "bookmarks", "error");
        $ret = false;
    }
    if (!$bezeichnung) {
        message_stack_in(__('Please specify a description!')."!", "bookmarks", "error");
        $ret = false;
    }
    if (!ereg("^http",$url) and !ereg("^ftp://",$url)) $url = "http://".$url;

    // fetch all bookmarks from this group
    if (!$ID) $ID = 0;
    $result = db_query("SELECT ID, url, bezeichnung
                          FROM ".DB_PREFIX."lesezeichen
                         WHERE ID <> '$ID'
                           AND $sql_user_group") or db_die();
    while ($row = db_fetch_row($result)) {
        $row[1] = stripslashes($row[1]);
        $row[2] = stripslashes($row[2]);
        // check for double url entries
        if ($url == $row[1]) {
            message_stack_in(__('This address already exists with a different description')."! $row[2]", "bookmarks", "error");
            $ret = false;
        }
        // check for double names
        if ($bezeichnung == $row[2]) {
            message_stack_in("$row[2] ".__(' already exists. ')."!", "bookmarks", "error");
            $ret = false;
        }
    }
    return $ret;
}

?>
