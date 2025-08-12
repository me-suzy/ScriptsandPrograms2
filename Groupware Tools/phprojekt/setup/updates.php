<?php

// updates.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: updates.php,v 1.65.2.5 2005/08/24 11:13:20 fgraf Exp $

// check whether setup.php calls this script - authentication!
if (!defined("setup_included")) die("Please use setup.php!");


// set plz (zip code) to varchar so the adress e.g. D-123456 is valid
if (($setup <> "install") and $version == "1.2") {
    $result = db_query("ALTER TABLE user CHANGE plz plz ".$db_varchar10[$db_type]) or db_die();
}
if (($setup <> "install") and $version == "1.2") {
    $result = db_query("ALTER TABLE contacts CHANGE plz plz ".$db_varchar10[$db_type]) or db_die();
}

// update to version 2.0 ***********
if (($setup <> "install") and ($version == "1.3" or $version == "1.2")) {

    // prepare db to be compatible with other db's, extend several tables
    // extinct damned timestamp and date

    // users
    $result = db_query("ALTER TABLE user RENAME users") or db_die();
    $result = db_query("alter table users add mobil ".$db_varchar40[$db_type]) or db_die();
    $result = db_query("update users set mobil = ''") or db_die();
    $result = db_query("ALTER TABLE users CHANGE access acc ".$db_varchar4[$db_type]) or db_die();

    // termine = events
    $result = db_query("ALTER TABLE termine CHANGE text event ".$db_varchar40[$db_type]) or db_die();
    $result = db_query("alter table termine add ort ".$db_varchar40[$db_type]) or db_die();
    $result = db_query("update termine set ort = ''") or db_die();
    $result = db_query("alter table termine add contact ".$db_varchar255[$db_type]) or db_die();
    $result = db_query("update termine set contact = ''") or db_die();
    $result = db_query("alter table termine add note2 ".$db_text[$db_type]) or db_die();
    $result = db_query("update termine set note2 = ''") or db_die();
    $result = db_query("alter table termine add div1 ".$db_varchar40[$db_type]) or db_die();
    $result = db_query("update termine set div1 = ''") or db_die();
    $result = db_query("alter table termine add div2 ".$db_varchar40[$db_type]) or db_die();
    $result = db_query("update termine set div2 = ''") or db_die();
    $result = db_query("ALTER TABLE termine CHANGE erstellt erstellt ".$db_varchar20[$db_type]) or db_die();
    $result = db_query("ALTER TABLE termine CHANGE datum datum ".$db_varchar10[$db_type]) or db_die();

    // groups
    if ($groups and $groups_old and ($version <> "1.0b" and $version <> "0.9.3" and $version <> "0.9.2")) {
        $result = db_query("ALTER TABLE groups RENAME gruppen") or db_die();
    }
    // contacts
    if ($adressen  and $adressen_old) {
        $result = db_query("ALTER TABLE contacts CHANGE access acc ".$db_varchar4[$db_type]) or db_die();
        $result = db_query("alter table contacts add email2 ".$db_varchar60[$db_type]) or db_die();
        $result = db_query("update contacts set email2 = ''") or db_die();
        $result = db_query("alter table contacts add mobil ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update contacts set mobil = ''") or db_die();
        $result = db_query("alter table contacts add url ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update contacts set url = ''") or db_die();
        $result = db_query("alter table contacts add div1 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update contacts set div1 = ''") or db_die();
        $result = db_query("alter table contacts add div2 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update contacts set div2 = ''") or db_die();
    }
    // Forum
    if ($forum and $forum_old) {
        $result = db_query("ALTER TABLE forum CHANGE text remark ".$db_text[$db_type]) or db_die();
        $result = db_query("ALTER TABLE forum CHANGE datum datum ".$db_varchar20[$db_type]) or db_die();
    }
    // lesezeichen - bookmarks
    if ($lesezeichen and $lesezeichen_old) {
        $result = db_query("ALTER TABLE lesezeichen CHANGE datum datum ".$db_varchar20[$db_type]) or db_die();
    }
    // Notes
    if ($notes and $notes_old and ($version <> "0.9.3" and $version <> "0.9.2")) {
        $result = db_query("ALTER TABLE notes CHANGE text remark ".$db_text[$db_type]) or db_die();
        $result = db_query("alter table notes add contact ".$db_int8[$db_type]) or db_die();
        $result = db_query("update notes set contact = ''") or db_die();
        $result = db_query("alter table notes add ext ".$db_int8[$db_type]) or db_die();
        $result = db_query("update notes set ext = ''") or db_die();
        $result = db_query("alter table notes add div1 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update notes set div1 = ''") or db_die();
        $result = db_query("alter table notes add div2 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update notes set div2 = ''") or db_die();
    }
    // todo lists
    if ($todo and $todo_old) {
        $result = db_query("ALTER TABLE todo CHANGE text note ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("alter table todo add ext ".$db_int8[$db_type]) or db_die();
        $result = db_query("update todo set ext = ''") or db_die();
        $result = db_query("alter table todo add div1 ".$db_text[$db_type]) or db_die();
        $result = db_query("update todo set div1 = ''") or db_die();
        $result = db_query("alter table todo add div2 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update todo set div2 = ''") or db_die();
    }
    // rts request tracker system
    if ($rts and $rts_old and ($version <> "1.1" and $version <> "1.0b" and $version <> "0.9.3" and $version <> "0.9.2")) {
        $result = db_query("ALTER TABLE rts CHANGE text note ".$db_text[$db_type]) or db_die();
        $result = db_query("ALTER TABLE rts_cat CHANGE user users ".$db_varchar10[$db_type]) or db_die();
        $result = db_query("ALTER TABLE rts CHANGE access acc ".$db_int1[$db_type]) or db_die();
    }
    // project management
    if ($projekte and $projekte_old) {
        $result = db_query("alter table projekte add chef ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update projekte set chef = 0") or db_die();
        $result = db_query("alter table projekte add typ ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update projekte set typ = ''") or db_die();
        $result = db_query("alter table projekte add parent ".$db_int4[$db_type]) or db_die();
        $result = db_query("update projekte set parent = '0'") or db_die();
        $result = db_query("alter table projekte add ziel ".$db_varchar255[$db_type]) or db_die();
        $result = db_query("update projekte set ziel = ''") or db_die();
        $result = db_query("alter table projekte add note ".$db_text[$db_type]) or db_die();
        $result = db_query("update projekte set note = ''") or db_die();
        $result = db_query("alter table projekte add kategorie ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update projekte set kategorie = ''") or db_die();
        $result = db_query("alter table projekte add contact ".$db_int8[$db_type]) or db_die();
        $result = db_query("update projekte set contact = ''") or db_die();
        $result = db_query("alter table projekte add stundensatz ".$db_int8[$db_type]) or db_die();
        $result = db_query("update projekte set stundensatz = ''") or db_die();
        $result = db_query("alter table projekte add budget ".$db_int11[$db_type]) or db_die();
        $result = db_query("update projekte set budget = ''") or db_die();
        $result = db_query("alter table projekte add div1 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update projekte set div1 = ''") or db_die();
        $result = db_query("alter table projekte add div2 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update projekte set div2 = ''") or db_die();
        $result = db_query("ALTER TABLE projekte CHANGE ende ende ".$db_varchar10[$db_type]) or db_die();
        $result = db_query("ALTER TABLE projekte CHANGE statuseintrag statuseintrag ".$db_varchar10[$db_type]) or db_die();
        $result = db_query("ALTER TABLE projekte CHANGE anfang anfang ".$db_varchar10[$db_type]) or db_die();
    }
    // timeproj = assign work time to projects
    if ($projekte == "2" and $projekte_old == "2" and ($version <> "0.9.3" and $version <> "0.9.2")) {
        $result = db_query("ALTER TABLE timeproj CHANGE user users ".$db_int4[$db_type]) or db_die();
        $result = db_query("alter table timeproj add note ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update timeproj set note = ''") or db_die();
        $result = db_query("alter table timeproj add ext ".$db_int2[$db_type]) or db_die();
        $result = db_query("update timeproj set ext = ''") or db_die();
        $result = db_query("alter table timeproj add div1 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update timeproj set div1 = ''") or db_die();
        $result = db_query("alter table timeproj add div2 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update timeproj set div2 = ''") or db_die();
        $result = db_query("ALTER TABLE timeproj CHANGE datum datum ".$db_varchar10[$db_type]) or db_die();
    }
    // timecard
    if ($timecard and $timecard_old and ($version <> "0.9.3" and $version <> "0.9.2")) {
        $result = db_query("ALTER TABLE timecard CHANGE user users ".$db_varchar255[$db_type]) or db_die();
        $result = db_query("ALTER TABLE timecard CHANGE begin anfang ".$db_varchar4[$db_type]) or db_die();
        $result = db_query("ALTER TABLE timecard CHANGE end ende ".$db_varchar4[$db_type]) or db_die();
        $result = db_query("alter table timecard add note ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update timecard set note = ''") or db_die();
        $result = db_query("alter table timecard add div1 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update timecard set div1 = ''") or db_die();
        $result = db_query("alter table timecard add div2 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update timecard set div2 = ''") or db_die();
        $result = db_query("ALTER TABLE timecard CHANGE datum datum ".$db_varchar10[$db_type]) or db_die();
    }
    // dateien = files
    if ($dateien and $dateien_old) {
        $result = db_query("ALTER TABLE dateien CHANGE access acc ".$db_text[$db_type]) or db_die();
        $result = db_query("ALTER TABLE dateien CHANGE size filesize ".$db_int11[$db_type]) or db_die();
        $result = db_query("ALTER TABLE dateien CHANGE text remark ".$db_varchar255[$db_type]) or db_die();
        $result = db_query("ALTER TABLE dateien ADD tempname ".$db_varchar60[$db_type]) or db_die();
        $result = db_query("alter table dateien add typ ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update dateien set typ = ''") or db_die();
        $result = db_query("alter table dateien add div1 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update dateien set div1 = ''") or db_die();
        $result = db_query("alter table dateien add div2 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update dateien set div2 = ''") or db_die();
        $result = db_query("ALTER TABLE dateien CHANGE datum datum ".$db_varchar20[$db_type]) or db_die();
    }
    // ressourcen = resources
    if ($ressourcen and $ressourcen_old) {
        $result = db_query("alter table ressourcen add typ ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ressourcen set typ = ''") or db_die();
        $result = db_query("alter table ressourcen add div1 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ressourcen set div1 = ''") or db_die();
        $result = db_query("alter table ressourcen add div2 ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ressourcen set div2 = ''") or db_die();
    }
    // votum - polls
    if ($votum and $votum_old) {
        $result = db_query("ALTER TABLE votum CHANGE datum datum ".$db_varchar20[$db_type]) or db_die();
    }
}

// update to Version 2.1 ***********
if (($setup == "update") and ($version == "2.0" or $version == "1.3" or $version == "1.2")) {
    if ($groups) {
        $result = db_query("
            CREATE TABLE grup_user (
            ID ".$db_int8_auto[$db_type].",
            grup_ID ".$db_int8[$db_type].",
            user_ID ".$db_int8[$db_type].",
            PRIMARY KEY (ID)
            ) ");
    }
}

// update to version 2.2 ***********
if (($setup == "update") and ($version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2")) {
    if ($groups) {
        $result = db_query("select ID, gruppe from users") or db_die();
        while ($row = db_fetch_row($result)) {
            if ($row[1] > 0) {
                $result2 = db_query("insert into grup_user values ($dbIDnull,'$row[1]','$row[0]')") or db_die();
            }
        }
    }
}

// update to Version 2.3 ***********
if (($setup == "update") and ($version == "2.2" or $version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2")) {
    $result = db_query("alter table users add loginname ".$db_varchar40[$db_type]) or db_die();
    $result = db_query("update users set loginname = ''") or db_die();
}

// update to Version 2.4 ***********
if (($setup == "update") and ($version == "2.3.1" or $version == "2.3" or $version == "2.2" or $version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2")) {
    if ($dateien and $dateien_old) {
        $result = db_query("update dateien set div1 = '0'") or db_die();
        $result = db_query("update dateien set typ = 'f' where filesize > 0") or db_die();
        $result = db_query("update dateien set typ = 'l' where filesize = 0") or db_die();
    }
    if ($projekte and $projekte_old) {
        $result = db_query("update projekte set parent = 0 where (parent = '' or parent is null)") or db_die();
    }
}

// update to Version 3.0 ***********
if (($setup == "update") and (ereg("2.4",$version) or ereg("2.3",$version) or $version == "2.2" or $version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2")) {
    $result = db_query("alter table users add ldap_name ".$db_varchar40[$db_type]) or db_die();
    $result = db_query("update users set ldap_name = ''") or db_die();
    if ($notes and $notes_old) {
        $result = db_query("alter table notes add projekt ".$db_int6[$db_type]) or db_die();
        $result = db_query("update notes set projekt = ''") or db_die();
    }
}

// update to Version 3.1 ***********
if (($setup == "update") and (ereg("3.0",$version) or ereg("2.4",$version) or ereg("2.3",$version) or $version == "2.2" or $version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2")) {
    $result = db_query("alter table users add anrede ".$db_varchar10[$db_type]) or db_die();
    $result = db_query("update users set anrede = ''") or db_die();
    $result = db_query("alter table users add sms ".$db_varchar60[$db_type]) or db_die();
    $result = db_query("update users set sms = ''") or db_die();
    if ($adressen  and $adressen_old) {
        $result = db_query("alter table contacts add anrede ".$db_varchar10[$db_type]) or db_die();
        $result = db_query("update contacts set anrede = ''") or db_die();
        $result = db_query("alter table contacts add state ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update contacts set state = ''") or db_die();
    }
    $result = db_query("alter table termine add remind ".$db_int4[$db_type]) or db_die();
    $result = db_query("update termine set remind = ''") or db_die();
}

// update to Version 3.2 ***********
if (($setup == "update") and (ereg("3.1",$version) or ereg("3.0",$version) or ereg("2.4",$version) or ereg("2.3",$version) or $version == "2.2" or $version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2")) {
    // create separte field to store a flag for imported contacts until they are verified.
    if ($adressen  and $adressen_old) {
        $result = db_query("alter table contacts add import ".$db_char1[$db_type]) or db_die();
        $result = db_query("update contacts set import = ''") or db_die();
        echo "updating table contacts add field 'import' ...<br />\n";
    }
    // new field: flag whether the event is open to public calendars
    $result = db_query("alter table termine add visi ".$db_char1[$db_type]) or db_die();
    $result = db_query("update termine set visi = '0'") or db_die();
    echo "updating table termine add field 'visi' ...<br />\n";

    if ($forum and $forum_old) {
        $result = db_query("alter table forum add lastchange ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update forum set lastchange = ''") or db_die();
        echo "updating table forum add field 'lastchange'...<br />\n";

        // set field lastchange of root postings to the date value of the last comment
        echo "updating table forum set date of last posting to each thread ...<br /><br />\n";
        $result = db_query("select ID, datum from forum where antwort > 0") or db_die();
        while ($row = db_fetch_row($result)) {
            $antwort = $row[0];
            while ($antwort > 0) {
                $result2 = db_query("select ID, antwort from forum where ID='$antwort'") or db_die();
                $row2 = db_fetch_row($result2);
                $antwort = $row2[1];
            }
            $result3 = db_query("select ID, datum from forum where ID = '$row2[0]'") or db_die();
            $row3 = db_fetch_row($result3);
            if ($row3[1] < $row[1]) {
                $result3 = db_query("update forum set lastchange='$row[1]' where ID = '$row3[0]'") or db_die();
            }
        }
    }

    // add field pw to store the password file file encryption
    if ($dateien  and $dateien_old) {
        $result = db_query("alter table dateien add pw ".$db_varchar255[$db_type]) or db_die();
        $result = db_query("update dateien set pw = ''") or db_die();
    }
} // end update to version 3.2

// update to Version 3.3 ***********
if (($setup == "update") and (ereg("3.2",$version) or ereg("3.1",$version) or ereg("3.0",$version) or ereg("2.4",$version) or ereg("2.3",$version) or $version == "2.2" or $version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2")) {

    // update catalan values from ca to ct
    $result = db_query("update users set sprache = 'ct' where sprache = 'ca'") or db_die();

    // add field 'notify' to forum module
    $result = db_query("alter table forum add notify ".$db_varchar2[$db_type]) or db_die();
    $result = db_query("update forum set notify = ''") or db_die();

    // add a new table for the mail client to create own sender/signature combinations
    if ($quickmail == 2) {
        $result = db_query("
            CREATE TABLE mail_sender (
            ID ".$db_int8_auto[$db_type].",
            von ".$db_int6[$db_type].",
            title ".$db_varchar80[$db_type].",
            sender ".$db_varchar255[$db_type].",
            signature ".$db_text[$db_type].",
            PRIMARY KEY (ID)
        ) ");
        if ($db_type == "oracle") { sequence("mail_sender"); }
        if ($db_type == "interbase") {ib_autoinc("mail_sender"); }
    }

    // update filemanager: set access value '1' to 'private', '2' to 'group'
    if ($dateien  and $dateien_old) {
        $result = db_query("update dateien set acc = 'private' where acc = '1'") or db_die();
        $result = db_query("update dateien set acc = 'group' where acc = '2'") or db_die();
        // add more fields
        $result = db_query("alter table dateien add acc_write ".$db_text[$db_type]) or db_die();
        $result = db_query("update dateien set acc_write = ''") or db_die();
        $result = db_query("alter table dateien add version ".$db_varchar4[$db_type]) or db_die();
        $result = db_query("update dateien set version = ''") or db_die();
        $result = db_query("alter table dateien add lock_user ".$db_varchar10[$db_type]) or db_die();
        $result = db_query("update dateien set lock_user = ''") or db_die();
        $result = db_query("alter table dateien add contact ".$db_varchar10[$db_type]) or db_die();
        $result = db_query("update dateien set contact = ''") or db_die();
    }
} // end update to Version 3.3

// update to Version 4.0 ***********
if (($setup == "update") and (ereg("3.3",$version) or ereg("3.2",$version) or ereg("3.1",$version) or ereg("3.0",$version) or ereg("2.4",$version) or ereg("2.3",$version) or $version == "2.2" or $version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2")) {

    // update resources, create own table
    if ($ressourcen) {
        $result = db_query("
            CREATE TABLE termine_res_rel (
            ID ".$db_int8_auto[$db_type].",
            termin_ID ".$db_int8[$db_type].",
            res_ID ".$db_int8[$db_type].",
            PRIMARY KEY (ID)
        ) ");
    }
    if ($db_type == "oracle") { sequence("termine_res_rel"); }
    if ($db_type == "interbase") {ib_autoinc("termine_res_rel"); }

    // update resources, store them in an own table termine_res_rel
    $result = db_query("select ID, ressource from termine where ressource > 0") or db_die();
    while ($row = db_fetch_row($result)) {
        $result2 = db_query("insert into termine_res_rel values($dbIDnull, '$row[0]', '$row[1]')") or db_die();
        $result2 = db_query("update termine set ressource = '' where ID = '$row[0]'") or db_die();
    }

    // Roles
    // create table roles
    $result = db_query("
            CREATE TABLE roles (
            ID ".$db_int8_auto[$db_type].",
            von ".$db_int6[$db_type].",
            title ".$db_varchar60[$db_type].",
            remark ".$db_text[$db_type].",
            summary ".$db_int1[$db_type].",
            calendar ".$db_int1[$db_type].",
            contacts ".$db_int1[$db_type].",
            forum ".$db_int1[$db_type].",
            chat ".$db_int1[$db_type].",
            filemanager ".$db_int1[$db_type].",
            bookmarks ".$db_int1[$db_type].",
            votum ".$db_int1[$db_type].",
            mail ".$db_int1[$db_type].",
            notes ".$db_int1[$db_type].",
            helpdesk ".$db_int1[$db_type].",
            projects ".$db_int1[$db_type].",
            timecard ".$db_int1[$db_type].",
            todo ".$db_int1[$db_type].",
            news ".$db_int1[$db_type].",
            PRIMARY KEY (ID)
            ) ");
    if ($db_type == "oracle") { sequence("roles"); }
    if ($db_type == "interbase") {ib_autoinc("roles"); }

    // add field role in table users
    $result = db_query("alter table users add role ".$db_int4[$db_type]) or db_die();
    $result = db_query("update users set role = ''") or db_die();
    $result = db_query("alter table users add proxy ".$db_text[$db_type]) or db_die();
    $result = db_query("update users set proxy = ''") or db_die();
    $result = db_query("alter table users add settings ".$db_text[$db_type]) or db_die();
    $result = db_query("update users set settings = ''") or db_die();

    // add field parent in table contacts
    if ($adressen  and $adressen_old) {
        $result = db_query("alter table contacts add parent ".$db_int8[$db_type]) or db_die();
        $result = db_query("update contacts set parent = '0'") or db_die();
    }
    //extend table todo
    if ($todo and $todo_old) {
        $result = db_query("alter table todo add note ".$db_text[$db_type]) or db_die();
        $result = db_query("update todo set note = ''") or db_die();
        $result = db_query("alter table todo add deadline ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update todo set deadline = ''") or db_die();
        $result = db_query("alter table todo add datum ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update todo set datum = ''") or db_die();
        $result = db_query("alter table todo add status ".$db_int1[$db_type]) or db_die();
        $result = db_query("update todo set status = ''") or db_die();
        $result = db_query("alter table todo add priority ".$db_int1[$db_type]) or db_die();
        $result = db_query("update todo set priority = ''") or db_die();
        $result = db_query("alter table todo add progress ".$db_int3[$db_type]) or db_die();
        $result = db_query("update todo set progress = ''") or db_die();
        $result = db_query("alter table todo add project ".$db_int6[$db_type]) or db_die();
        $result = db_query("update todo set project = ''") or db_die();
        $result = db_query("alter table todo add contact ".$db_int8[$db_type]) or db_die();
        $result = db_query("update todo set contact = ''") or db_die();

        // change the field values of todo - which nerd has done such stupid field naming anyway? ;)
        $result = db_query("select ID, von from todo") or db_die();
        while ($row = db_fetch_row($result)) {
            $result2 = db_query("update todo set ext = $row[1], von = '' where ID = '$row[0]'") or db_die();
        }
    }

    // add dependency and next-in-list in table projects
    if ($projekte and $projekte_old) {
        $result = db_query("alter table projekte add depend_mode ".$db_int2[$db_type]) or db_die();
        $result = db_query("update projekte set depend_mode = ''") or db_die();
        $result = db_query("alter table projekte add depend_proj ".$db_int6[$db_type]) or db_die();
        $result = db_query("update projekte set depend_proj = ''") or db_die();
        $result = db_query("alter table projekte add next_mode ".$db_int2[$db_type]) or db_die();
        $result = db_query("update projekte set next_mode = ''") or db_die();
        $result = db_query("alter table projekte add next_proj ".$db_int6[$db_type]) or db_die();
        $result = db_query("update projekte set next_proj = ''") or db_die();
    }

    // add some fields for future syncing
    // in todo ...
    if ($todo and $todo_old) {
        $result = db_query("alter table todo add sync1 ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update todo set sync1 = ''") or db_die();
        $result = db_query("alter table todo add sync2 ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update todo set sync2 = ''") or db_die();
    }
    // ... contacts ...
    if ($adressen  and $adressen_old) {
        $result = db_query("alter table contacts add sync1 ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update contacts set sync1 = ''") or db_die();
        $result = db_query("alter table contacts add sync2 ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update contacts set sync2 = ''") or db_die();
    }
    // ... calendar ...
    $result = db_query("alter table termine add sync1 ".$db_varchar20[$db_type]) or db_die();
    $result = db_query("update termine set sync1 = ''") or db_die();
    $result = db_query("alter table termine add sync2 ".$db_varchar20[$db_type]) or db_die();
    $result = db_query("update termine set sync2 = ''") or db_die();

    // ... and notes!
    if ($notes and $notes_old) {
        $result = db_query("alter table notes add sync1 ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update notes set sync1 = ''") or db_die();
        $result = db_query("alter table notes add sync2 ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update notes set sync2 = ''") or db_die();
    }
    // end preparation for sycning

    // forgot to add the fields comment fields inthe table todo :-)) -> here we go
    if ($todo and $todo_old) {
        $result = db_query("alter table todo add comment1 ".$db_text[$db_type]) or db_die();
        $result = db_query("update todo set comment1 = ''") or db_die();
        $result = db_query("alter table todo add comment2 ".$db_text[$db_type]) or db_die();
        $result = db_query("update todo set comment2 = ''") or db_die();
    }

} // end update to version 4.0

// update to Version 4.1 ***********
if ( ($setup == "update") and (ereg("4.0",$version) or ereg("3.3",$version) or
     ereg("3.2",$version) or ereg("3.1",$version) or ereg("3.0",$version) or
     ereg("2.4",$version) or ereg("2.3",$version) or $version == "2.2" or
     $version == "2.1" or $version == "2.0" or $version == "1.3" or $version == "1.2") ) {

    // enhance the contactmanager with access rights
    $result = db_query("alter table ".DB_PREFIX."contacts add acc_read ".$db_text[$db_type]) or db_die();
    $result = db_query("update ".DB_PREFIX."contacts set acc_read = ''") or db_die();
    $result = db_query("alter table ".DB_PREFIX."contacts add acc_write ".$db_text[$db_type]) or db_die();
    $result = db_query("update ".DB_PREFIX."contacts set acc_write = ''") or db_die();
    // update table contacts, shift all values from field 'acc' to field 'access': NULL = private, a = group
    $result = db_query("select ID, acc from ".DB_PREFIX."contacts") or db_die();
    while ($row = db_fetch_row($result)) {
        if ($row[1] == 'a') { $access = 'group'; }
        else { $access = 'private'; }
        $result2 = db_query("update ".DB_PREFIX."contacts set acc_read = '$access', acc = '' where ID = '$row[0]'") or db_die();
    }


    // make sure that a folder named /upload exists since we need it for the module desinger
    mkdir('docs',0600);

    // form designer
    // 1. the table itself
    $result = db_query("
            CREATE TABLE ".DB_PREFIX."db_manager (
            ID ".$db_int8_auto[$db_type].",
            db_table ".$db_varchar40[$db_type].",
            db_name ".$db_varchar40[$db_type].",
            form_name ".$db_varchar255[$db_type].",
            form_type ".$db_varchar20[$db_type].",
            form_tooltip ".$db_varchar255[$db_type].",
            form_pos ".$db_int4[$db_type].",
            form_colspan ".$db_int2[$db_type].",
            form_rowspan ".$db_int2[$db_type].",
            form_regexp ".$db_varchar255[$db_type].",
            form_default ".$db_varchar255[$db_type].",
            form_select ".$db_text[$db_type].",
            list_pos ".$db_int4[$db_type].",
            list_alt ".$db_varchar2[$db_type].",
            filter_show ".$db_varchar2[$db_type].",
            db_inactive ".$db_int1[$db_type].",
            PRIMARY KEY (ID)
            ) ");
    if ($db_type == "oracle") { sequence("db_manager"); }
    if ($db_type == "interbase") {ib_autoinc("db_manager"); }

    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (1, 'contacts', 'nachname', '__(\'Family Name\')', 'text', 'give the description: last name, company name or organisation etc.', 1, 1, 1, NULL, NULL, NULL, 1, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (2, 'contacts', 'vorname', '__(\'First Name\')', 'text', 'Type in the first name of the person', 2, 1, 1, NULL, NULL, NULL, 2, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (3, 'contacts', 'anrede', '__(\'Salutation\')', 'text', 'Title of the person: Mr, Mrs, Dr., Majesty etc. ...', 3, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (4, 'contacts', 'firma', '__(\'Company\')', 'text', 'Name of associated team or company', 4, 1, 1, NULL, NULL, NULL, 0, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (5, 'contacts', 'email', 'email', 'email', 'enter the main email address of this contact', 5, 1, 1, NULL, NULL, NULL, 3, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (6, 'contacts', 'email2', 'email 2', 'email', 'enter an alternative mail address of this contact', 6, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (7, 'contacts', 'tel1', '__(\'Phone\') 1', 'phone', 'enter the primary phone number of this contact', 7, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (8, 'contacts', 'tel2', '__(\'Phone\') 2', 'phone', 'enter the secondary phone number of this contact', 8, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (9, 'contacts', 'mobil', '__(\'mobile // mobile phone\')', 'phone', 'enter the cellular phone number', 9, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (10, 'contacts', 'fax', '__(\'Fax\')', 'text', 'enter the fax number of this contact', 10, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (11, 'contacts', 'strasse', '__(\'Street\')', 'text', 'the street where the person lives or the company is located', 11, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (12, 'contacts', 'stadt', '__(\'City\')', 'text', 'the city where the person lives or the company is located', 12, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (13, 'contacts', 'plz', '__(\'Zip code\')', 'text', 'the coresponding zip code', 13, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (14, 'contacts', 'land', '__(\'Country\')', 'text', 'the country', 14, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (15, 'contacts', 'state', '__(\'State\')', 'text', 'region or state (USA)', 15, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (16, 'contacts', 'url', 'url', 'url', 'the homepage - private or business', 16, 1, 1, NULL, NULL, NULL, 4, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (17, 'contacts', 'div1', '', 'text', 'a default userdefined field', 17, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (18, 'contacts', 'div2', '', 'text', 'another default userdefined field', 18, 1, 1, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (19, 'contacts', 'bemerkung', '__(\'Comment\')', 'textarea', 'a comment about this record', 19, 2, 5, NULL, NULL, NULL, 0, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (20, 'contacts', 'kategorie', '__(\'Category\')', 'select_category', 'Select an existing category or insert a new one', 20, 1, 1, NULL, NULL, '(acc like \'system\' or ((von = \$user_ID or acc like \'group\' or acc like \'%\\\\\"\$user_kurz\\\\\"%\') and \$sql_user_group))', 4, NULL, '1', 0)") or db_die();
    ###$result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'db_records', 't_remark',           '__(\'Remark\')',           'textarea',         NULL,                                               1,          2,            3,            NULL,           NULL,           NULL,                   1,          NULL,       '1',            0)") or db_die();
    ###$result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'db_records', 't_wichtung',         '__(\'Priority\')',         'select_values',    'set the priority',                                 3,          1,            1,            NULL,           NULL,           '1|2|3|4|5|6|7|8|9',    3,          NULL,       '1',            0)") or db_die();
    ###$result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'db_records', 't_reminder_datum',   '__(\'Reminder date\')',    'date',             'reminder date',                                    3,          1,            1,            NULL,           NULL,           NULL,                   3,          NULL,       '1',            0)") or db_die();



    if ($todo and $todo_old) {
        // oh, I forgot: module todo gets a 'start' field as well!
        $result = db_query("alter table ".DB_PREFIX."todo add anfang ".$db_varchar20[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."todo set anfang = ''") or db_die();

        // since we open the module todo for the whole group we have to set the group as well!
        // -> add new field 'gruppe' and assign the current records to the according group
        $result = db_query("alter table ".DB_PREFIX."todo add gruppe ".$db_int4[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."todo set gruppe = ''") or db_die();
        // extended access for objects in module todo ...
        $result = db_query("alter table ".DB_PREFIX."todo add acc ".$db_text[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."todo set acc = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."todo add acc_write ".$db_text[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."todo set acc_write = ''") or db_die();

        if ($groups > 0) {
            $result = db_query("select ID, von, ext
                                    from ".DB_PREFIX."todo") or db_die();
            while ($row = db_fetch_row($result)) {
                // fetch the groupID's of this author
                $result2 = db_query("select grup_ID from ".DB_PREFIX."grup_user where user_ID = '$row[1]'") or db_die();
                while ($row2 = db_fetch_row($result2)) { $author_groups[] = $row2[0]; }
                $result3 = db_query("select grup_ID from ".DB_PREFIX."grup_user where user_ID = '$row[2]'") or db_die();
                while ($row3 = db_fetch_row($result3)) { $recipient_groups[] = $row3[0]; }
                // now we have the two arrays of group memeberships (mostly it will be one element) -> compare the values
                foreach ($recipient_groups as $rec_group) {
                    if (in_array($rec_group, $author_groups)) $same_group = $rec_group;
                }
                // if no group is found at all ... well, go to hell and take the first groupID from the author :)
                if (!$same_group) $same_group = $author_groups[0];
                // update the record
                $result = db_query("update ".DB_PREFIX."todo
                                    set gruppe = '$same_group',
                                        acc = 'private'
                                    where ID = '$row[0]'") or db_die();
            }
        }
    }

    // ... and now for notes!
    if ($notes and $notes_old) {
        $result = db_query("alter table ".DB_PREFIX."notes add acc ".$db_text[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."notes set acc = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."notes add acc_write ".$db_text[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."notes set acc_write = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."notes add gruppe ".$db_int4[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."notes set gruppe = ''") or db_die();
        // rewrite access rules in table notes
        $result = db_query("select ".DB_PREFIX."notes.ID, ext, ".DB_PREFIX."users.gruppe
                              from ".DB_PREFIX."notes, ".DB_PREFIX."users
                             where ".DB_PREFIX."notes.von = ".DB_PREFIX."users.ID") or db_die();
        while ($row = db_fetch_row($result)) {
            if ($row[1] > 0) {
                $access = 'group';
                $gruppe = $row[1];
            }
            else {
                $access = 'private';
                $gruppe = $row[2];
            }
            $result2 = db_query("update ".DB_PREFIX."notes set acc = '$access', gruppe = '$gruppe' where ID = '$row[0]'") or db_die();
        }
    }
    if ($projekte and $projekte_old) {
        $result = db_query("alter table ".DB_PREFIX."projekte add probability ".$db_int4[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."projekte set probability = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."projekte add ende_real ".$db_varchar10[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."projekte set ende_real = ''") or db_die();
    }

    // mail accounts
    if ($quickmail > 1) {
        $result = db_query("alter table ".DB_PREFIX."mail_account add mail_auth ".$db_int2[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_account set mail_auth = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_account add pop_hostname ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_account set pop_hostname = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_account add pop_account ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_account set pop_account = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_account add pop_password ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_account set pop_password = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_account add smtp_hostname ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_account set smtp_hostname = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_account add smtp_account ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_account set smtp_account = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_account add smtp_password ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_account set smtp_password = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_account add collect ".$db_int1[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_account set collect = 1") or db_die();
    }

    // add table 'contacts_import_schemes' if contacts_profiles are enabled
    if ($contacts_profiles > 0) {
        $result = db_query("
            CREATE TABLE ".DB_PREFIX."contacts_import_patterns (
            ID ".$db_int8_auto[$db_type].",
            name ".$db_varchar40[$db_type].",
            von ".$db_int6[$db_type].",
            pattern ".$db_text[$db_type].",
            PRIMARY KEY (ID)
        ) ");
        if ($db_type == "oracle") { sequence("contacts_import_patterns"); }
        if ($db_type == "interbase") {ib_autoinc("contacts_import_patterns"); }
    }

    // since in 4.1 new parameters for sending mails are introduced we have to preset them here
    if (strpos(strtolower($_SERVER["OS"]), 'windows') !== false) {
        $mail_eol = "\r\n"; // end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)
        $mail_eoh = "\r\n"; // end of header line; e.g. \r\n (conform to RFC 2821 / 2822)
    }
    else if (strpos(strtolower($_SERVER["OS"]), 'mac') !== false) {
        $mail_eol = "\r"; // end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)
        $mail_eoh = "\r"; // end of header line; e.g. \r\n (conform to RFC 2821 / 2822)
    }
    else {
        $mail_eol = "\n"; // end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)
        $mail_eoh = "\n"; // end of header line; e.g. \r\n (conform to RFC 2821 / 2822)
    }
    $mail_mode = "0";
    $mail_auth = "0";
    $smtp_hostname = "localhost";
    $local_hostname = "hereiam";
    $pop_account = "itsme";
    $pop_password = "mypw";
    $pop_hostname = "mypop.domain.net";
    $smtp_account = "itsme";
    $smtp_password = "mypw";
} // end update to version 4.1

// from now on all db tables have to be the db_prefix!


// *********************************
// update to Version 4.2 ***********
if ( ($setup == "update") and (ereg("4.1",$version) or ereg("4.0",$version) or
     ereg("3.3",$version) or ereg("3.2",$version) or ereg("3.1",$version) or
     ereg("3.0",$version) or ereg("2.4",$version) or ereg("2.3",$version) or
     $version == "2.2" or $version == "2.1" or $version == "2.0" or
     $version == "1.3" or $version == "1.2") ) {

    // extend module designer with other modules
    $result = db_query("select max(ID) from ".DB_PREFIX."db_manager") or db_die();
    $row = db_fetch_row($result);
    $ii = $row[0];

    // notes
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'notes', 'remark', '__(\'Remark\')', 'textarea', 'bodytext of the note', 2, 2, 5, NULL, NULL, NULL, 2, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'notes', 'name', '__(\'Title\')', 'text', 'Title of this note', 1, 2, 1, '', NULL, NULL, 1, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'notes', 'contact', '__(\'Contact\')', 'contact', 'Contact related to this note', 3, 1, 1, NULL, NULL, NULL, 3, NULL, NULL, 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'notes', 'projekt', '__(\'Projects\')', 'project', 'Project related to this note', 4, 1, 1, NULL, NULL, NULL, 4, NULL, NULL, 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'notes', 'div1', '__(\'added\')', 'timestamp_create', '', 5, 1, 1, NULL, NULL, '', 5, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'notes', 'div2', '__(\'changed\')', 'timestamp_modify', '', 6, 1, 1, NULL, NULL, '', 6, NULL, '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'notes', 'kategorie', '__(\'Category\')', 'select_category', 'Select an existing category or insert a new one', 7, 1, 1, NULL, NULL, '(acc like \'system\' or ((von = \$user_ID or acc like \'group\' or acc like \'%\\\\\"\$user_kurz\\\\\"%\') and \$sql_user_group))', 0, NULL, '1', 0)") or db_die();

    // projects
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'name', '__(\'Project Name\')', 'text', 'the name of the project', 1, 1, 1, NULL, NULL, NULL, 1, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'kategorie', '__(\'Category\')', 'select_values', 'current category (or status) of this project', 2, 1, 1, NULL, NULL, '1#\$proj_text20|2#\$proj_text21|3#\$proj_text23|4#\$proj_text24|5#\$proj_text25|6#\$proj_text26|7#\$proj_text27', 1, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'ziel', '__(\'Aim\')', 'text', 'describe the aim of this project', 9, 1, 1, NULL, NULL, NULL, 0, '0', '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'anfang', '__(\'Start\')', 'date', 'start day', 3, 1, 1, NULL, NULL, NULL, 3, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'ende', '__(\'End\')', 'date', 'planned end', 4, 1, 1, NULL, NULL, NULL, 4, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'contact', '__(\'Contact\')', 'contact', 'select the customer/contact', 6, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'chef', '__(\'Leader\')', 'userID', 'Seelct a user of this group as the project leader', 5, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'wichtung', '__(\'Priority\')', 'select_values', 'set the priority of this project', 10, 1, 1, NULL, NULL, '1\|2\|3\|4\|5\|6\|7\|8\|9', 0, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'stundensatz', '__(\'Hourly rate\')', 'text', 'hourly rate of this project', 7, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'budget', '__(\'Budget\')', 'text', '', 8, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'status', '__(\'Status\') [%]', 'userID_access', 'current completion status', 11, 1, 1, NULL, NULL,'chef', 4, NULL, '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'statuseintrag', '__(\'Last status change\')', 'display', 'date of last change of status', 12, 1, 1, NULL, NULL, NULL, 0, '1', '1', 0)") or db_die();
    $result = db_query("insert into ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES (".($ii+=1).", 'projekte', 'note', '__(\'Remark\')', 'textarea', 'remarks', 13, 2, 5, NULL, NULL, NULL, 0, '0', '1', 0)") or db_die();

    // acc & acc_write for projects
    if ($projekte and $projekte_old) {

        // convert the project leader: from variable user_kurz to user_ID
        $result = db_query("select ID, chef
                            from ".DB_PREFIX."projekte") or db_die();
        while ($row = db_fetch_row($result)) {
            $result2 = db_query("update  ".DB_PREFIX."projekte set
                                        chef = '".slookup('users','ID','kurz',$row[1])."'
                                    where ID = '$row[0]'") or db_die();
        }

        $result = db_query("alter table ".DB_PREFIX."projekte add acc ".$db_text[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."projekte set acc = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."projekte add acc_write ".$db_text[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."projekte set acc_write = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."projekte add von ".$db_int8[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."projekte set von = ''") or db_die();

        // set the author and the access for each project: owner will be project leader, or participant with chief status or a participant
        $result = db_query("select ID, chef, personen, gruppe , name
                            from ".DB_PREFIX."projekte") or db_die();
        while ($row = db_fetch_row($result)) {
            $von = '';
            // project leader
                if ($row[1]) {
                $von = $row[1];
                $w = '';
                }
            // particpants
            elseif (strlen($row[2]) > 6) {
                foreach (unserialize($row[1]) as $participant) {
                    // if a user is chief, take him as the author
                    if (ereg('c', slookup('users','acc','kurz',$participant))) {
                        $von = slookup('users','ID','kurz',$participant);
                    }
                }
                // if no project leader and no user with chief status is amongs the participants, just take the first one
                if (!$von) {
                    $participants = unserialize($row[2]);
                    $von = slookup('users','ID','kurz',$participants[0]);
                    $w = 'w';
                }
            }
            // last chance: take a user with chief status in this group
            else {
                $result2 = db_query("select ID, acc
                                    from ".DB_PREFIX."users where gruppe = '$row[3]'") or db_die();
                while ($row2 = db_fetch_row($result2)) {
                    if (ereg('c',$row2[1])) {
                        $von = $row2[0];
                        $w = 'w';
                    }
                }
                // oh no! stil havent found anyone? :-/ -> take the last user of this group and finish it!
                if (!$von) {
                    $von = $row2[0];
                    $w = 'w';
                }
            }
            // now update the record - set the participants as the group which is able to watch the record
            $result2 = db_query("update ".DB_PREFIX."projekte set
                                        von = '$von',
                                        acc = 'group',
                                        acc_write = '$w'
                                where ID = '$row[0]'") or db_die();

        }
    }

    // parent and category for notes
    if ($notes and $notes_old) {
        $result = db_query("alter table ".DB_PREFIX."notes add parent ".$db_int8[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."notes set parent = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."notes add kategorie ".$db_varchar40[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."notes set kategorie = ''") or db_die();
    }

    // access for forum and set all entries as 'group writeable'
    if ($forum and $forum_old) {
        $result = db_query("alter table ".DB_PREFIX."forum add acc ".$db_text[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."forum set acc = 'group'") or db_die();
        $result = db_query("alter table ".DB_PREFIX."forum add acc_write ".$db_text[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."forum set acc_write = ''") or db_die();
    }

    // hourly rate per user
    $result = db_query("alter table ".DB_PREFIX."users add hrate ".$db_varchar10[$db_type]) or db_die();
    $result = db_query("update ".DB_PREFIX."users set hrate = ''") or db_die();

    // create history table
    if ($history_log) {
        $result = db_query("
            CREATE TABLE ".DB_PREFIX."history (
            ID ".$db_int8_auto[$db_type].",
            von ".$db_int8[$db_type].",
            _date ".$db_varchar20[$db_type].",
            _table ".$db_varchar60[$db_type].",
            _field ".$db_varchar60[$db_type].",
            _record ".$db_int8[$db_type].",
            last_value ".$db_text[$db_type].",
            new_value ".$db_text[$db_type].",
            PRIMARY KEY (ID)
        ) ");
    }

    // extend mail table with message ID, project and contacr relation
    if ($quickmail > 1) {
        $result = db_query("alter table ".DB_PREFIX."mail_client add contact ".$db_int8[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_client set contact = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_client add projekt ".$db_int8[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_client set projekt = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_client add message_ID ".$db_varchar128[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_client set message_ID = ''") or db_die();
        // extend mail ruels for contacts and projects
        $result = db_query("alter table ".DB_PREFIX."mail_rules add projekt ".$db_int8[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_rules set projekt = ''") or db_die();
        $result = db_query("alter table ".DB_PREFIX."mail_rules add contact ".$db_int8[$db_type]) or db_die();
        $result = db_query("update ".DB_PREFIX."mail_rules set contact = ''") or db_die();
    }

    // update sync fields
    // in todo ...
    if ($todo and $todo_old) {
        $result = db_query("update ".DB_PREFIX."todo set sync2 = '$dbTSnull'") or db_die();
        $result = db_query("update ".DB_PREFIX."todo set sync1 = sync2") or db_die();
    }
    // ... contacts ...
    if ($adressen  and $adressen_old) {
        $result = db_query("update ".DB_PREFIX."contacts set sync2 = '$dbTSnull'") or db_die();
        $result = db_query("update ".DB_PREFIX."contacts set sync1 = sync2") or db_die();
    }
    // ... calendar ...
    $result = db_query("update ".DB_PREFIX."termine set sync2 = '$dbTSnull'") or db_die();
    $result = db_query("update ".DB_PREFIX."termine set sync1 = sync2") or db_die();
    // ... and notes!
    if ($notes and $notes_old) {
        $result = db_query("update ".DB_PREFIX."notes set sync2 = '$dbTSnull'") or db_die();
        $result = db_query("update ".DB_PREFIX."notes set sync1 = sync2") or db_die();
    }

    // add pathes in the config.inc.php to move the directories
    $doc_path       = 'docs';
    $att_path       = 'attach';
    $calltype       = 'callto';
    $history_log    = '0';
    $filter_maxhits = '0';
    $bgcolor_mark   = '#E6DE90';
    $bgcolor_hili   = '#FFFFFF';
    $support_pdf    = '0';
    $support_html   = '0';
    $support_chart  = '0';
    if (!$default_size) $default_size = '60';
} // end update 4.2


// *************
// update to 5.0
// *************

if ( ($setup == "update") and (ereg("4.2",$version) or ereg("4.1",$version) or
     ereg("4.0",$version) or ereg("3.3",$version) or ereg("3.2",$version) or
     ereg("3.1",$version) or ereg("3.0",$version) or ereg("2.4",$version) or
     ereg("2.3",$version) or $version == "2.2" or $version == "2.1" or
     $version == "2.0" or $version == "1.3" or $version == "1.2") ) {

    if($_SESSION['dat_crypt'] == 0){
        echo __('Filenames will now be crypted ...')." ...<br />";
        // crypt old files in filesystem and database
        $dir = $_SESSION['dateien'];
        if(file_exists($dir)){
            $handle = opendir($dir);
            $ignore_files = array('..', '.', 'index.html');
            while ($file = readdir ($handle)) {
                if (!in_array($file, $ignore_files)) {
                    $new_filename = rnd_string();
                    // because renaming is difficult on various OS, use copy and unlink
                    copy($dir.'/'.$file, $dir.'/'.$new_filename);
                    unlink($dir.'/'.$file);
                    $result = db_query("UPDATE ".DB_PREFIX."dateien SET tempname = '$new_filename' WHERE tempname = '$file'") or db_die();
                }
            }
            closedir($handle);
        }
    }
    // update groupless systems
    if ($_SESSION['groups'] == 0) {
        $result = db_query("SELECT COUNT(*)
                              FROM ".DB_PREFIX."gruppen
                             WHERE name = 'default'") or db_die();
        $res = db_fetch_row($result);
        // create default group
        if (!$res[0]) {
            $result = db_query("INSERT INTO ".DB_PREFIX."gruppen
                                            (ID, name, kurz, bemerkung)
                                     VALUES ($dbIDnull, 'default', 'def', 'default group')") or db_die();
        }
        // get group id of default group
        $result = db_query("SELECT ID
                              FROM ".DB_PREFIX."gruppen
                             WHERE name = 'default'") or db_die();
        $res = db_fetch_row($result);
        $default_group_id = $res[0];
        // put groupless users into default group -> we ask for gruppe = 0 to skip root account (gruppe = NULL)
        $result = db_query("UPDATE ".DB_PREFIX."users
                               SET gruppe = $default_group_id
                             WHERE gruppe = 0") or db_die();
    }

    // grup_user
    if ($groups_old) {
        // create own index for user_ID and grup_ID
        $result = db_query("CREATE INDEX grup_user_user_ID ON ".DB_PREFIX."grup_user (user_ID)") or db_die();
        $result = db_query("CREATE INDEX grup_user_grup_ID ON ".DB_PREFIX."grup_user (grup_ID)") or db_die();
        // test user normally has no entry in grup_user in version < 5.0 -> add entry to avoid errors in v5
        $result = db_query("SELECT gu.ID, u.ID FROM ".DB_PREFIX."users u LEFT JOIN ".DB_PREFIX."grup_user gu ON u.ID = gu.user_ID WHERE u.loginname = 'test'") or db_die();
        $res = db_fetch_row($result);
        if (is_null($res[0])) {
            $result = db_query("INSERT INTO ".DB_PREFIX."grup_user (ID, grup_ID, user_ID) VALUES ($dbIDnull, 1, '".$res[1]."')") or db_die();
        }
    }

    // history
    if ($history_log and $history_log_old) {
        $result = db_query("ALTER TABLE ".DB_PREFIX."history CHANGE von von ".$db_int8[$db_type]) or db_die() ;
    }

    // notes
    if ($notes and $notes_old) {
        $result = db_query("ALTER TABLE ".DB_PREFIX."notes CHANGE parent parent ".$db_int6[$db_type]) or db_die() ;
        $result = db_query("ALTER TABLE ".DB_PREFIX."notes CHANGE kategorie kategorie ".$db_varchar100[$db_type]) or db_die() ;
    }

    // rts
    if ($rts and $rts_old) {
        $result = db_query("ALTER TABLE ".DB_PREFIX."rts CHANGE parent parent ".$db_int8[$db_type]) or db_die() ;
    }

    // timecard
    if ($timecard and $timecard_old) {
        $result = db_query("ALTER TABLE ".DB_PREFIX."timecard ADD nettoh ".$db_int2[$db_type]) or db_die();
        $result = db_query("ALTER TABLE ".DB_PREFIX."timecard ADD nettom ".$db_int2[$db_type]) or db_die();
        $result = db_query("ALTER TABLE ".DB_PREFIX."timecard ADD ip_address ".$db_varchar255[$db_type]) or db_die();
    }

    // profiles
    if (!$profile_old) {
        $result = db_query("
            CREATE TABLE ".DB_PREFIX."profile (
            ID ".$db_int8_auto[$db_type].",
            von ".$db_int8[$db_type].",
            bezeichnung ".$db_varchar20[$db_type].",
            personen ".$db_text[$db_type].",
            gruppe ".$db_int8[$db_type].",
            PRIMARY KEY (ID)
          ) ");
        if ($result) echo __('profiles (for user-profiles) created').".<br>\n";
        else { echo __('An error ocurred while creating table: ')." 'profile' <br>\n"; $error = 1; }
        if ($db_type == "oracle") sequence("profile");
        if ($db_type == "interbase") ib_autoinc("profile");
    }
    else {
        $result = db_query("ALTER TABLE ".DB_PREFIX."profile CHANGE bezeichnung bezeichnung ".$db_varchar20[$db_type]) or db_die() ;
    }

    // db_records
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."db_records (
        t_ID ".$db_int8_auto[$db_type].",
        t_author ".$db_int6[$db_type].",
        t_module ".$db_varchar40[$db_type].",
        t_record ".$db_int8[$db_type].",
        t_name ".$db_varchar255[$db_type].",
        t_datum ".$db_varchar20[$db_type].",
        t_touched ".$db_int2[$db_type].",
        t_archiv ".$db_int2[$db_type].",
        t_reminder ".$db_int2[$db_type].",
        t_reminder_datum ".$db_varchar20[$db_type].",
        t_wichtung ".$db_int2[$db_type].",
        t_remark ".$db_text[$db_type].",
        t_acc ".$db_text[$db_type].",
        t_gruppe ".$db_int6[$db_type].",
        t_parent ".$db_int6[$db_type].",
        PRIMARY KEY (t_ID)
    ) ");
    if ($db_type == "oracle") { sequence("db_records","t_ID"); }
    if ($db_type == "interbase") { ib_autoinc("db_records"); }

    // logintoken
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."logintoken (
        ID ".$db_int8_auto[$db_type].",
        von ".$db_int8[$db_type].",
        token ".$db_varchar255[$db_type].",
        user_ID ".$db_int8[$db_type].",
        url ".$db_varchar255[$db_type].",
        valid ".$db_varchar20[$db_type].",
        used ".$db_varchar20[$db_type].",
        datum ".$db_varchar20[$db_type].",
        PRIMARY KEY (ID)
    ) ");
    if ($db_type == "oracle") { sequence("logintoken"); }
    if ($db_type == "interbase") { ib_autoinc("logintoken"); }

    // filter
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."filter (
        ID ".$db_int8_auto[$db_type].",
        von ".$db_varchar20[$db_type].",
        module ".$db_varchar255[$db_type].",
        name ".$db_varchar255[$db_type].",
        remark ".$db_text[$db_type].",
        filter ".$db_text[$db_type].",
        PRIMARY KEY (ID)
    ) ");
    if ($db_type == "oracle") { sequence("filter"); }
    if ($db_type == "interbase") { ib_autoinc("filter"); }

    // sync
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."sync_rel (
        ID ".$db_int11_auto[$db_type].",
        user_ID ".$db_int8[$db_type].",
        sync_type ".$db_varchar255[$db_type].",
        sync_version ".$db_varchar255[$db_type].",
        sync_ID ".$db_varchar255[$db_type].",
        sync_module ".$db_varchar255[$db_type].",
        sync_checksum ".$db_varchar40[$db_type].",
        phprojekt_ID ".$db_int8[$db_type].",
        phprojekt_module ".$db_varchar40[$db_type].",
        created ".$db_varchar20[$db_type].",
        modified ".$db_varchar20[$db_type].",
        PRIMARY KEY (ID)
    )");
    if ($db_type == "oracle") { sequence("sync_rel"); }
    if ($db_type == "interbase") { ib_autoinc("sync_rel"); }

    // add the records in the db manager
    $result = db_query("SELECT MAX(ID) FROM ".DB_PREFIX."db_manager") or db_die();
    $row = db_fetch_row($result);
    $ii = $row[0];

    // db_manager
    $result = db_query("ALTER TABLE ".DB_PREFIX."db_manager ADD rights ".$db_varchar4[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."db_manager ADD ownercolumn ".$db_varchar255[$db_type]) or db_die();

    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_module', '__(\'Module\')', 'display', 'Module name', 1, 1, 1, NULL, NULL, NULL, 1, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_remark', '__(\'Remark\')', 'text', 'Remark', 3, 1, 1, NULL, NULL, NULL, 2, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_archiv', '__(\'Archive\')', 'text', 'Archive', 6, 1, 1, NULL, NULL, NULL, 0, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_touched', '__(\'Touched\')', 'text', 'Touched', 7, 1, 1, NULL, NULL, NULL, 0, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_name', '__(\'Title\')', 'text', 'Title', 2, 1, 1, NULL, NULL, NULL, 1, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_wichtung', '__(\'Priority\')', 'select_values', 'Priority', 4, 1, 1, NULL, NULL, '0|1|2|3|4|5|6|7|8|9', 4, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_reminder_datum', '__(\'Resubmission at:\')', 'date', 'Date', 5, 1, 1, NULL, NULL, '', 5, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_record', '__(\'Record set\')', 'display', 'ID of the target record', 0, 1, 1, '', NULL, NULL, 0, NULL, 0, 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'db_records', 't_datum', '__(\'Date\')', 'date', '', 0, 1, 1, NULL, NULL, '', 0, NULL, '0', 0, NULL, NULL)") or db_die();

    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'termine', 'event', '__(\'Title\')', 'text', 'Title of this event', 1, 1, 1, '', NULL, NULL, 1, NULL, '1', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'termine', 'datum', '__(\'Date\')', 'text', 'Date of this event', 2, 1, 1, '', NULL, NULL, 2, NULL, '1', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'termine', 'anfang', '__(\'Start\')', 'text', 'Title of this event', 3, 1, 1, '', NULL, NULL, 3, NULL, '1', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'termine', 'ende', '__(\'End\')', 'text', 'end of this event', 4, 1, 1, '', NULL, NULL, 4, NULL, '1', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'termine', 'von', '__(\'Author\')', 'userID', 'Author of this event', 5, 1, 1, '', NULL, NULL, 5, NULL, '1', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'termine', 'an', '__(\'Recipient\')', 'userID', 'Recipient', 6, 1, 1, '', NULL, NULL, 6, NULL, '1', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'termine', 'partstat', '__(\'Participation\')', 'select_values', 'Title of this event', 7, 1, 1, '', NULL, '1#__(\'untreated\')|2#__(\'accepted\')|3#__(\'rejected\')', 7, NULL, '1', 0)") or db_die();

    // mail
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'mail_client', 'remark', '__(\'Comment\')', 'textarea', NULL, 1, 2, 2, NULL, NULL, NULL, 3, NULL, 'on', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'mail_client', 'subject', '__(\'subject\')', 'text', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'on', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'mail_client', 'sender', '__(\'Sender\')', 'text', NULL, 0, 0, 0, NULL, NULL, NULL, 3, NULL, 'on', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'mail_client', 'kat', '__(\'Category\')', 'select_category', NULL, 2, 2, 1, NULL, NULL, NULL, 4, NULL, 'on', 0)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive) VALUES ($dbIDnull, 'mail_client', 'projekt', '__(\'Project\')', 'project', NULL, 3, 2, 1, NULL, NULL, NULL, 5, NULL, 'on', 0)") or db_die();

    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','remark','__(\'Title\')','text','Kurze Beschreibung',1,2,1,NULL,NULL,NULL,1,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','deadline','__(\'Deadline\')','date','',7,1,1,'','','',2,'','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','datum','__(\'Date\')','timestamp_create','',5,1,1,'','','',0,'','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','priority','__(\'Priority\')','select_values',NULL,4,1,1,NULL,NULL,'0|1|2|3|4|5|6|7|8|9',5,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','project','__(\'Project\')','project',NULL,9,1,1,NULL,NULL,NULL,6,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','contact','__(\'Contact\')','contact',NULL,8,1,1,NULL,NULL,NULL,0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','note','__(\'Describe your request\')','textarea',NULL,11,2,3,NULL,NULL,NULL,0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','comment1','__(\'Remark\') __(\'Author\')','textarea',NULL,12,2,3,NULL,NULL,NULL,NULL,NULL,'1',1,'o','von')") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','comment2','__(\'Remark\') __(\'Receiver\')','textarea',NULL,13,2,3,NULL,NULL,NULL,NULL,NULL,'1',1,'o','ext')") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','von','__(\'of\')','user_show',NULL,2,1,1,NULL,NULL,NULL,3,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','anfang','__(\'Begin\')','date',NULL,6,1,1,NULL,NULL,NULL,0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull,'todo','ext','__(\'to\')','userID',NULL,3,1,1,NULL,NULL,NULL,4,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'todo', 'progress', '__(\'progress\') [%]', 'text', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'todo', 'status', '__(\'Status\')', 'select_values', NULL, NULL, NULL, NULL, NULL, NULL, '1#__(\'waiting\')|2#__(\'Open\')|3#__(\'accepted\')|4#__(\'rejected\')|5#__(\'ended\')', 7, NULL, NULL, 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'name', '__(\'Title\')', 'text', 'the title of the request', 1, 1, 1, NULL, NULL, NULL, 1, '0', '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'note', '__(\'Remark\')', 'textarea', 'the body of the request set by the customer', 6, 1, 1, NULL, NULL, NULL, 0, '1', '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'submit', '__(\'Date\')', 'timestamp_create', 'date/time the request ha been submitted', 4, 1, 1, NULL, NULL, NULL, 0, '0', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'recorded', '__(\'Author\')', 'authorID', 'the user who wrote this request', 5, 1, 1, NULL, NULL, NULL, 0, '0', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'contact', '__(\'Contact\')', 'contact_create', 'contact related to this request', 9, 1, 1, NULL, NULL, NULL, 3, '0', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'email', '__(\'Email Address\')', 'email_create', 'insert the mail address in case the customer is not listed', 12, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'due_date', '__(\'Due date\')', 'date', 'due date of this request', 9, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'assigned', '__(\'Assigned\')', 'userID', 'assign the request to this user', 10, 1, 1, NULL, NULL, NULL, 4, '1', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'priority', '__(\'Priority\')', 'select_values', 'set the priority of this project', 11, 1, 1, NULL, NULL, '0|1|2|3|4|5|6|7|8|9', 5, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'remark', '__(\'remark\')', 'textarea', 'internal remark to this request', 7, 1, 1, NULL, NULL, NULL, 0, '1', '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'solution', '__(\'solve\')', 'textarea', 'A text will cause: a mail to the customer and closing the request', 8, 1, 1, NULL, NULL, NULL, 6, '0', '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'solved', '__(\'solved\') . __(''From'')', 'user_show', 'the user who has solved this request', 14, 1, 1, '', '', '', 0, '0', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'solve_time', '__(\'solved\')', 'timestamp_show', 'date and time when the request has been solved', 16, 1, 1, NULL, NULL, NULL, 0, '0', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'acc', '__(\'access\')', 'select_values', 'requests with status open appear in the knowledge base!', 17, 1, 1, NULL, NULL, '0#n/a|1#intern|2#auf', 4, '0', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'proj', '__(\'Projects\')', 'project', 'project related to this request', 14, 1, 1, NULL, NULL, '', 0, '0', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'dateien', 'filename', '__(\'Title\')', 'text', 'Title of the file or directory', 1, 2, 1, '', NULL, NULL, 1, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'dateien', 'remark', '__(\'Comment\')', 'textarea', 'remark related to this file', 4, 2, 5, NULL, NULL, NULL, 2, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'dateien', 'contact', '__(\'Contact\')', 'contact', 'Contact related to this file', 5, 1, 1, NULL, NULL, NULL, 3, NULL, NULL, 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'dateien', 'div2', '__(\'Projects\')', 'project', 'Project related to this file', 6, 1, 1, NULL, NULL, NULL, 4, NULL, NULL, 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'dateien', 'kat', '__(\'Category\')', 'select_category', 'Select an existing category or insert a new one', 7, 1, 1, NULL, NULL, '(acc like \'system\' or ((von =  or acc like \'group\' or acc like \'%\"\"%\') and (1 = 1)))', 0, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'dateien', 'datum', '__(\'changed\')', 'timestamp_modify', '', 101, 1, 1, NULL, NULL, '', 6, NULL, '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'dateien', 'lock_user', '__(\'locked by\')', 'user_show', 'Name of the user who has locked this file temporarly', 11, 1, 1, '', '', '', 5, '0', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager (ID, db_table, db_name, form_name, form_type, form_tooltip, form_pos, form_colspan, form_rowspan, form_regexp, form_default, form_select, list_pos, list_alt, filter_show, db_inactive, rights, ownercolumn) VALUES ($dbIDnull, 'rts', 'ID', '__(\'ID\')', 'text', NULL, NULL, 1, 1, NULL, NULL, NULL, 2, NULL, '1', 0, NULL, NULL)") or db_die();

    // update old language vars
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Family Name\')' WHERE form_name = '\$m1_text26'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'First Name\')' WHERE form_name = '\$m1_text25'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Title\')' WHERE form_name = '\$info_text17'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Company\')' WHERE form_name = '\$m1_text28'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Phone\') 1' WHERE form_name = '\$m1_text29 1'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Phone\') 2' WHERE form_name = '\$m1_text29 2'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'mobile\')' WHERE form_name = '\$admin_text92'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Fax\')' WHERE form_name = '\$m1_text30'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Street\')' WHERE form_name = '\$m1_text31'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'City\')' WHERE form_name = '\$m1_text32'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Zip code\')' WHERE form_name = '\$admin_text26'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Country\')' WHERE form_name = '\$m1_text33'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'State\')' WHERE form_name = '\$info_text18'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '' WHERE form_name = '\$cont_usrdef1'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '' WHERE form_name = '\$cont_usrdef2'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Comment\')' WHERE form_name = '\$m1_text36'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Category\')' WHERE form_name = '\$admin_text70'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Comment\')' WHERE form_name = '\$m1_text36'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Title\')' WHERE form_name = '\$info_text5'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Contact\')' WHERE form_name = '\$proj_text12'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Projects\')' WHERE form_name = '\$o_projects'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'added\')' WHERE form_name = '\$notes_text2'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'changed\')' WHERE form_name = '\$notes_text3'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Category\')' WHERE form_name = '\$admin_text70'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Project Name\')' WHERE form_name = '\$proj_name'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Category\')' WHERE form_name = '\$info_text8'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Begin\')' WHERE form_name = '\$proj_start'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'End\')' WHERE form_name = '\$proj_end'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Leader\')' WHERE form_name = '\$proj_chef'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Contact\')' WHERE form_name = '\$proj_text12'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Hourly rate\')' WHERE form_name = '\$proj_text13'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Calculated budget\')' WHERE form_name = '\$proj_text14'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Aim\')' WHERE form_name = '\$proj_text11'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Priority\')' WHERE form_name = '\$proj_prio'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Status\') [%]' WHERE form_name = '\$proj_stat [%]'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Last status change\')' WHERE form_name = '\$proj_chan'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_name = '__(\'Remark\')' WHERE form_name = '\$admin_text71'") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."db_manager set form_select = '1#__(\'offered\')|2#__(\'ordered\')|3#__(\'Working\')|4#__(\'ended\')|5#__(\'stopped\')|6#__(\'Re-Opened\')|7#__(\'waiting\')' WHERE form_name = '__(\'Category\')' AND db_table='projekte' AND db_name='kategorie'") or db_die();
    // end reminder table

    // table for touched records
    // db_records
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."db_records (
        t_ID ".$db_int8_auto[$db_type].",
        t_author ".$db_int8[$db_type].",
        t_module ".$db_varchar40[$db_type].",
        t_record ".$db_int8[$db_type].",
        t_name ".$db_varchar255[$db_type].",
        t_datum ".$db_varchar20[$db_type].",
        t_touched ".$db_int2[$db_type].",
        t_archiv ".$db_int2[$db_type].",
        t_reminder ".$db_int2[$db_type].",
        t_reminder_datum ".$db_varchar20[$db_type].",
        t_wichtung ".$db_int2[$db_type].",
        t_remark ".$db_text[$db_type].",
        t_acc ".$db_text[$db_type].",
        t_gruppe ".$db_int6[$db_type].",
        t_parent ".$db_int6[$db_type].",
        PRIMARY KEY (t_ID)
    ) ");
    if ($db_type == "oracle") { sequence("db_records","t_ID"); }
    if ($db_type == "interbase") { ib_autoinc("db_records"); }

    // set up user proxy table
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."users_proxy (
        ID ".$db_int8_auto[$db_type].",
        user_ID ".$db_int8[$db_type].",
        proxy_ID ".$db_int8[$db_type].",
        PRIMARY KEY (ID)
    )");
    // create own index for proxy_ID and user_ID
    $result = db_query("CREATE INDEX users_proxy_proxy_ID ON ".DB_PREFIX."users_proxy (proxy_ID)");
    $result = db_query("CREATE INDEX users_proxy_user_ID ON ".DB_PREFIX."users_proxy (user_ID)");
    if ($db_type == "oracle") { sequence("users_proxy"); }
    if ($db_type == "interbase") { ib_autoinc("users_proxy"); }

    // set up users reader table
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."users_reader (
        ID ".$db_int8_auto[$db_type].",
        user_ID ".$db_int8[$db_type].",
        reader_ID ".$db_int8[$db_type].",
        PRIMARY KEY (ID)
    )");
    // create own index for reader_ID and user_ID
    $result = db_query("CREATE INDEX users_reader_reader_ID ON ".DB_PREFIX."users_reader (reader_ID)");
    $result = db_query("CREATE INDEX users_reader_user_ID ON ".DB_PREFIX."users_reader (user_ID)");
    if ($db_type == "oracle") { sequence("users_reader"); }
    if ($db_type == "interbase") { ib_autoinc("users_reader"); }

    // set up users viewer table
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."users_viewer (
        ID ".$db_int8_auto[$db_type].",
        user_ID ".$db_int8[$db_type].",
        viewer_ID ".$db_int8[$db_type].",
        PRIMARY KEY (ID)
    )");
    // create own index for viewer_ID and user_ID
    $result = db_query("CREATE INDEX users_viewer_viewer_ID ON ".DB_PREFIX."users_viewer (viewer_ID)");
    $result = db_query("CREATE INDEX users_viewer_user_ID ON ".DB_PREFIX."users_viewer (user_ID)");
    if ($db_type == "oracle") { sequence("users_viewer"); }
    if ($db_type == "interbase") { ib_autoinc("users_viewer"); }

    // extend users table
    $result = db_query("ALTER TABLE ".DB_PREFIX."users ADD remark ".$db_text[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."users ADD usertype ".$db_int1[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."users ADD status ".$db_int1[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."users CHANGE hrate hrate ".$db_varchar20[$db_type]) or db_die() ;
    $result = db_query("ALTER TABLE ".DB_PREFIX."users ADD INDEX (kurz)");
    $result = db_query("ALTER TABLE ".DB_PREFIX."users ADD INDEX (gruppe)");
    $result = db_query("UPDATE ".DB_PREFIX."users SET remark = '', usertype = 0, status = 0") or db_die();

    // calendar stuff
// TODO: check if this query works on all db systems cause it has an auto increment (sequence)..
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine CHANGE ID ID ".$db_int11_auto[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD parent ".$db_int11[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD partstat ".$db_int1[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD status ".$db_int1[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD priority ".$db_int1[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD serie_id ".$db_int11[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD serie_typ ".$db_varchar4[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD serie_bis ".$db_varchar10[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD upload ".$db_text[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine CHANGE note2 remark ".$db_text[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine CHANGE visi visi ".$db_int1[$db_type]) or db_die();
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD INDEX (anfang)");
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD INDEX (ende)");
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD INDEX (von)");
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD INDEX (an)");
    $result = db_query("ALTER TABLE ".DB_PREFIX."termine ADD INDEX (visi)");
    $result = db_query("UPDATE ".DB_PREFIX."termine
                           SET parent = 0, partstat = 2, status = 0,
                               priority = 0, serie_id = 0, serie_typ = '',
                               serie_bis = '', upload = ''") or db_die();
    $result = db_query("UPDATE ".DB_PREFIX."termine SET visi = 0 WHERE visi IS NULL") or db_die();

    if ($forum && $forum_old) {
        $result = db_query("ALTER TABLE ".DB_PREFIX."forum ADD parent ".$db_int8[$db_type]) or db_die();
        $result = db_query("UPDATE ".DB_PREFIX."forum SET parent = 0") or db_die();
    }
    if ($todo && $todo_old) {
        $result = db_query("ALTER TABLE ".DB_PREFIX."todo ADD parent ".$db_int11[$db_type]) or db_die();
        $result = db_query("ALTER TABLE ".DB_PREFIX."todo CHANGE progress progress ".$db_int4[$db_type]) or db_die() ;
    }

    if ($projekte && $projekte_old) {
        $result = db_query("UPDATE ".DB_PREFIX."db_manager SET db_inactive = 1 WHERE db_table='projekte' AND db_name='status'") or db_die();
        $result = db_query("UPDATE ".DB_PREFIX."db_manager SET db_inactive = 1 WHERE db_table='projekte' AND db_name='statuseintrag'") or db_die();
    }

    if (($dateien && $dateien_old) || $file_path) {
        $result = db_query("ALTER TABLE ".DB_PREFIX."dateien ADD parent ".$db_int8[$db_type]) or db_die();
        $result = db_query("ALTER TABLE ".DB_PREFIX."dateien ADD userfile ".$db_varchar255[$db_type]." AFTER filename") or db_die() ;
        $result = db_query("ALTER TABLE ".DB_PREFIX."dateien CHANGE gruppe gruppe ".$db_int8[$db_type]) or db_die() ;
        $result = db_query("ALTER TABLE ".DB_PREFIX."dateien CHANGE version version ".$db_varchar4[$db_type]) or db_die() ;
    }
    // FIXME!!!
    $result = db_query("UPDATE ".DB_PREFIX."db_manager
                           SET form_type = 'select_sql',
                               form_select = 'select users.ID, users.nachname, users.vorname
                                                from users,termine
                                               where users.ID = termine.von and
                                                     termine.an = \$user_ID
                                            group by users.ID
                                            order by nachname'
                         WHERE form_type = 'users_select_distinct'") or db_die();

    // update table "dateien"
    if (($dateien && $dateien_old) || $file_path) {
        $result = db_query("UPDATE ".DB_PREFIX."dateien SET parent = div1, contact = div2") or db_die();
        $result = db_query("UPDATE ".DB_PREFIX."dateien SET div1 = NULL, div2 = NULL") or db_die();
    }

    // import old calendar data
    define('UPDATE_SCRIPT', true);
    echo "Updating resource/event tables/data ...<br />\n";
    include_once('./setup/import_cal_data.php');

} // end update 5.0

?>
