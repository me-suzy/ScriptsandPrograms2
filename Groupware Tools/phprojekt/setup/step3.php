<?php

// step3.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: step3.php,v 1.106.2.4 2005/08/24 11:13:20 fgraf Exp $

// check whether setup.php calls this script - authentication!
if (!defined("setup_included")) die("Please use setup.php!");


// save default language to avoid new setting in the lib
$langua_save = $langua;

// set contstant avoid_auth in order to bypass authentication in lib
if (!defined('avoid_auth')) define("avoid_auth", "1");

// include lib
include_once("./lib/lib.inc.php");
include_once('./lib/db/'.$db_type.'.inc.php');

// fetch db definitions
include("./setup/db_var.inc.php");
// restore language
$langua = $langua_save;

// updates
include_once("./setup/updates.php");

// crypt passwords
if ($pw_crypt_update) {
    echo __('Passwords will now be encrypted ...')." ...<br />";
    $result = db_query("SELECT ID, pw, nachname
                          FROM ".DB_PREFIX."users") or db_die();
    while ($row = db_fetch_row($result)) {
        $enc_pw = encrypt($row[1],$row[1]);
        $result2 = db_query("UPDATE ".DB_PREFIX."users
                                SET pw = '$enc_pw'
                              WHERE ID = '$row[0]'") or db_die();
    }
    $pw_crypt = "1";
    echo __('finished')."!<br /><br />";
}
// crypt filenames
if ($dat_crypt_update) {
    echo __('Filenames will now be crypted ...')." ...<br />";
    // loop over all files in the upload directory
    $result = db_query("SELECT ID, filename
                          FROM ".DB_PREFIX."dateien") or db_die();
    while ($row = db_fetch_row($result)) {
        // exclude directories
        if (is_file("$dat_rel/$row[1]")) {
            $rnd = rnd_string(9);
            $result2 = db_query("UPDATE ".DB_PREFIX."dateien
                                    SET tempname = '$rnd'
                                  WHERE ID = '$row[0]'") or db_die();
            // because renaming is difficult on various OS, use copy and unlink
            copy("$dat_rel/$row[1]", "$dat_rel/$rnd");
            unlink("$dat_rel/$row[1]");
        }
    }
    echo __('finished')."!<br><br>";
}

// ******************************
// Begin table setup ************
// Filemanagement    ************

if ($filemanager and ($setup == "install" or !$dateien_old)) {
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."dateien (
        ID ".$db_int8_auto[$db_type].",
        von ".$db_int8[$db_type].",
        filename ".$db_varchar255[$db_type].",
        userfile ".$db_varchar255[$db_type].",
        remark ".$db_varchar255[$db_type].",
        kat ".$db_varchar40[$db_type].",
        acc ".$db_text[$db_type].",
        datum ".$db_varchar20[$db_type].",
        filesize ".$db_int11[$db_type].",
        gruppe ".$db_int8[$db_type].",
        tempname ".$db_varchar255[$db_type].",
        typ ".$db_varchar40[$db_type].",
        div1 ".$db_varchar40[$db_type].",
        div2 ".$db_varchar40[$db_type].",
        pw ".$db_varchar255[$db_type].",
        acc_write ".$db_text[$db_type].",
        version ".$db_varchar4[$db_type].",
        lock_user ".$db_varchar10[$db_type].",
        contact ".$db_int6[$db_type].",
        parent ".$db_int8[$db_type].",
        PRIMARY KEY (ID)
      ) ");

    if ($result) {
        echo __('Table dateien (for file-handling) created').".<br>\n";
    }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'dateien'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("dateien"); }
    if ($db_type == "interbase") {ib_autoinc("dateien"); }
}

//**************************************

// Todo lists
if ($todo and ($setup == "install" or !$todo_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."todo (
      ID ".$db_int8_auto[$db_type].",
      von ".$db_int8[$db_type].",
      remark ".$db_varchar255[$db_type].",
      ext ".$db_int8[$db_type].",
      div1 ".$db_text[$db_type].",
      div2 ".$db_varchar40[$db_type].",
      note ".$db_text[$db_type].",
      deadline ".$db_varchar20[$db_type].",
      datum ".$db_varchar20[$db_type].",
      status ".$db_int1[$db_type].",
      priority ".$db_int1[$db_type].",
      progress ".$db_int4[$db_type].",
      project ".$db_int6[$db_type].",
      contact ".$db_int8[$db_type].",
      sync1 ".$db_varchar20[$db_type].",
      sync2 ".$db_varchar20[$db_type].",
      comment1 ".$db_text[$db_type].",
      comment2 ".$db_text[$db_type].",
      anfang ".$db_varchar20[$db_type].",
      gruppe ".$db_int4[$db_type].",
      acc ".$db_text[$db_type].",
      acc_write ".$db_text[$db_type].",
      parent ".$db_int11[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table todo (for todo-lists) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'todo'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("todo"); }
    if ($db_type == "interbase") {ib_autoinc("todo"); }
}

//**************************************

//  Forum
if ($forum and ($setup == "install" or !$forum_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."forum (
      ID ".$db_int8_auto[$db_type].",
      antwort ".$db_int8[$db_type].",
      von ".$db_int8[$db_type].",
      titel ".$db_varchar80[$db_type].",
      remark ".$db_text[$db_type].",
      kat ".$db_varchar20[$db_type].",
      datum ".$db_varchar20[$db_type].",
      gruppe ".$db_int4[$db_type].",
      lastchange ".$db_varchar20[$db_type].",
      notify ".$db_varchar2[$db_type].",
      acc ".$db_text[$db_type].",
      acc_write ".$db_text[$db_type].",
      parent ".$db_int8[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table forum (for discssions etc.) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'forum'<br>\n"; $error = 1;
    }
    // create own index for antworten
    $result = db_query("CREATE INDEX forum_antwort ON ".DB_PREFIX."forum (antwort)");

    if ($db_type == "oracle") { sequence("forum"); }
    if ($db_type == "interbase") { ib_autoinc("forum"); }
}


//******************************************

// sync
if ($sync and ($setup == "install" or !$sync_old)) {
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
    if ($result) { echo __('Table sync_rel (for synchronization) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'sync_rel'<br>\n"; $error = 1;
    }

    if ($db_type == "oracle") { sequence("sync_rel"); }
    if ($db_type == "interbase") { ib_autoinc("sync_rel"); }
}


//******************************************
// polls/votum/Umfragen
if ($votum and ($setup == "install" or !$votum_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."votum (
      ID ".$db_int8_auto[$db_type].",
      datum ".$db_varchar20[$db_type].",
      von ".$db_int8[$db_type].",
      thema ".$db_varchar255[$db_type].",
      modus ".$db_char1[$db_type].",
      an ".$db_text[$db_type].",
      fertig ".$db_text[$db_type].",
      text1 ".$db_varchar60[$db_type].",
      text2 ".$db_varchar60[$db_type].",
      text3 ".$db_varchar60[$db_type].",
      zahl1 ".$db_int4[$db_type].",
      zahl2 ".$db_int4[$db_type].",
      zahl3 ".$db_int4[$db_type].",
      kein  ".$db_int4[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table votum (for polls) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'votum'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("votum"); }
    if ($db_type == "interbase") { ib_autoinc("votum"); }
}

//*******************************************

// Lesezeichen - bookmarks
if ($lesezeichen and ($setup == "install" or !$lesezeichen_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."lesezeichen (
      ID ".$db_int8_auto[$db_type].",
      datum ".$db_varchar20[$db_type].",
      von ".$db_int8[$db_type].",
      url ".$db_varchar255[$db_type].",
      bezeichnung ".$db_varchar255[$db_type].",
      bemerkung ".$db_varchar255[$db_type].",
      gruppe ".$db_int6[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table lesezeichen (for bookmarks) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'lesezeichen'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("lesezeichen"); }
    if ($db_type == "interbase") { ib_autoinc("lesezeichen"); }
}

//********************************************

// Projekte - projects
if ($projekte and ($setup == "install" or !$projekte_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."projekte (
      ID ".$db_int8_auto[$db_type].",
      name ".$db_varchar80[$db_type].",
      ende ".$db_varchar10[$db_type].",
      personen ".$db_text[$db_type].",
      wichtung ".$db_varchar20[$db_type].",
      status ".$db_int3[$db_type].",
      statuseintrag ".$db_varchar10[$db_type].",
      anfang ".$db_varchar10[$db_type].",
      gruppe ".$db_int4[$db_type].",
      chef ".$db_varchar20[$db_type].",
      typ ".$db_varchar40[$db_type].",
      parent ".$db_int6[$db_type].",
      ziel ".$db_varchar255[$db_type].",
      note ".$db_text[$db_type].",
      kategorie ".$db_varchar40[$db_type].",
      contact ".$db_int8[$db_type].",
      stundensatz ".$db_int8[$db_type].",
      budget ".$db_int11[$db_type].",
      div1 ".$db_varchar40[$db_type].",
      div2 ".$db_varchar40[$db_type].",
      depend_mode ".$db_int2[$db_type].",
      depend_proj ".$db_int6[$db_type].",
      next_mode ".$db_int2[$db_type].",
      next_proj ".$db_int6[$db_type].",
      probability ".$db_int3[$db_type].",
      ende_real ".$db_varchar10[$db_type].",
      acc ".$db_text[$db_type].",
      acc_write ".$db_text[$db_type].",
      von ".$db_int8[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table projekte (for project management) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'projekte'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("projekte"); }
    if ($db_type == "interbase") { ib_autoinc("projekte"); }
}

// book work time on projects
if ($projekte > 1 and ($setup == "install" or $projekte_old < 2)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."timeproj (
      ID ".$db_int8_auto[$db_type].",
      users ".$db_int4[$db_type].",
      projekt ".$db_int4[$db_type].",
      datum ".$db_varchar10[$db_type].",
      h ".$db_int2[$db_type].",
      m ".$db_int2[$db_type].",
      kat ".$db_varchar255[$db_type].",
      note ".$db_varchar40[$db_type].",
      ext ".$db_int2[$db_type].",
      div1 ".$db_varchar40[$db_type].",
      div2 ".$db_varchar40[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table timeproj (assigning work time to projects) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'timeproj'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("timeproj"); }
    if ($db_type == "interbase") { ib_autoinc("timeproj"); }
}

//********************************************

// Kontakte - contacts
if ($adressen and ($setup == "install" or !$adressen_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."contacts (
      ID ".$db_int8_auto[$db_type].",
      vorname ".$db_varchar20[$db_type].",
      nachname ".$db_varchar40[$db_type].",
      gruppe ".$db_int4[$db_type].",
      firma ".$db_varchar60[$db_type].",
      email ".$db_varchar80[$db_type].",
      tel1 ".$db_varchar60[$db_type].",
      tel2 ".$db_varchar60[$db_type].",
      fax ".$db_varchar60[$db_type].",
      strasse ".$db_varchar60[$db_type].",
      stadt ".$db_varchar60[$db_type].",
      plz ".$db_varchar10[$db_type].",
      land ".$db_varchar40[$db_type].",
      kategorie ".$db_varchar40[$db_type].",
      bemerkung ".$db_text[$db_type].",
      von ".$db_int8[$db_type].",
      acc ".$db_varchar4[$db_type].",
      email2 ".$db_varchar80[$db_type].",
      mobil ".$db_varchar60[$db_type].",
      url ".$db_varchar80[$db_type].",
      div1 ".$db_varchar60[$db_type].",
      div2 ".$db_varchar60[$db_type].",
      anrede ".$db_varchar20[$db_type].",
      state ".$db_varchar40[$db_type].",
      import ".$db_char1[$db_type].",
      parent ".$db_int8[$db_type].",
      sync1 ".$db_varchar20[$db_type].",
      sync2 ".$db_varchar20[$db_type].",
      acc_read ".$db_text[$db_type].",
      acc_write ".$db_text[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table contacts (for external contacts) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]){
        echo __('An error ocurred while creating table: ')." 'contacts'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("contacts"); }
    if ($db_type == "interbase") { ib_autoinc("contacts"); }
}

//********************************************

// Notizen - notes
if ($notes and ($setup == "install" or !$notes_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."notes (
      ID ".$db_int8_auto[$db_type].",
      von ".$db_int8[$db_type].",
      name ".$db_varchar255[$db_type].",
      remark ".$db_text[$db_type].",
      contact ".$db_int8[$db_type].",
      ext ".$db_int8[$db_type].",
      div1 ".$db_varchar40[$db_type].",
      div2 ".$db_varchar40[$db_type].",
      projekt ".$db_int6[$db_type].",
      sync1 ".$db_varchar20[$db_type].",
      sync2 ".$db_varchar20[$db_type].",
      acc ".$db_text[$db_type].",
      acc_write ".$db_text[$db_type].",
      gruppe ".$db_int4[$db_type].",
      parent ".$db_int6[$db_type].",
      kategorie ".$db_varchar100[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table notes (for notes) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'notes'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("notes"); }
    if ($db_type == "interbase") { ib_autoinc("notes"); }
}

//********************************************

   // Zeitkarte - timecard
if ($timecard and ($setup == "install" or !$timecard_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."timecard (
      ID ".$db_int8_auto[$db_type].",
      users ".$db_int8[$db_type].",
      datum ".$db_varchar10[$db_type].",
      projekt ".$db_int8[$db_type].",
      anfang ".$db_int4[$db_type].",
      ende ".$db_int4[$db_type].",
      nettoh ".$db_int2[$db_type].",
      nettom ".$db_int2[$db_type].",
      ip_address ".$db_varchar255[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table timecard (for time sheet system) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'timecard'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("timecard"); }
    if ($db_type == "interbase") { ib_autoinc("timecard"); }
}

//********************************************

   // Gruppen - groups
if ($setup == "install" or !$groups_old) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."gruppen (
      ID ".$db_int8_auto[$db_type].",
      name ".$db_varchar255[$db_type].",
      kurz ".$db_varchar10[$db_type].",
      kategorie ".$db_varchar255[$db_type].",
      bemerkung ".$db_varchar255[$db_type].",
      chef ".$db_int8[$db_type].",
      div1 ".$db_varchar255[$db_type].",
      div2 ".$db_varchar255[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if (!$result) {
        if(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
            echo __('An error ocurred while creating table: ')." 'groups'<br>\n"; $error = 1;
        }
    }
    if ($db_type == "oracle") { sequence("gruppen"); }
    if ($db_type == "interbase") { ib_autoinc("gruppen"); }

    // additional groups
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."grup_user (
      ID ".$db_int8_auto[$db_type].",
      grup_ID ".$db_int4[$db_type].",
      user_ID ".$db_int8[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table groups (for group management) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'grup_user'<br>\n"; $error = 1;
    }
    // create own index for user_ID and grup_ID
    $result = db_query("CREATE INDEX grup_user_user_ID ON ".DB_PREFIX."grup_user (user_ID)");
    $result = db_query("CREATE INDEX grup_user_grup_ID ON ".DB_PREFIX."grup_user (grup_ID)");

    if ($db_type == "oracle") { sequence("grup_user"); }
    if ($db_type == "interbase") { ib_autoinc("grup_user"); }
}

//********************************************

   // helpdesk
if ($rts and ($setup == "install" or !$rts_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."rts (
      ID ".$db_int8_auto[$db_type].",
      contact ".$db_int4[$db_type].",
      email ".$db_varchar80[$db_type].",
      submit ".$db_varchar20[$db_type].",
      recorded ".$db_int6[$db_type].",
      name ".$db_varchar255[$db_type].",
      note ".$db_text[$db_type].",
      due_date ".$db_varchar20[$db_type].",
      status ".$db_varchar20[$db_type].",
      assigned ".$db_varchar20[$db_type].",
      priority ".$db_int1[$db_type].",
      remark ".$db_text[$db_type].",
      solution ".$db_text[$db_type].",
      solved ".$db_int4[$db_type].",
      solve_time ".$db_varchar20[$db_type].",
      acc ".$db_int1[$db_type].",
      div1 ".$db_varchar255[$db_type].",
      div2 ".$db_varchar255[$db_type].",
      proj ".$db_int6[$db_type].",
      acc_read ".$db_text[$db_type].",
      acc_write ".$db_text[$db_type].",
      von ".$db_int8[$db_type].",
      gruppe ".$db_int6[$db_type].",
      parent ".$db_int8[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if (!$result) {
        if(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
            echo __('An error ocurred while creating table: ')." 'rts'<br>\n"; $error = 1;
        }
    }
    if ($db_type == "oracle") { sequence("rts"); }
    if ($db_type == "interbase") { ib_autoinc("rts"); }

    //helpdesk_cat
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."rts_cat (
      ID ".$db_int8_auto[$db_type].",
      name ".$db_varchar60[$db_type].",
      users ".$db_varchar10[$db_type].",
      gruppe ".$db_varchar10[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table rts and rts_cat (for the help desk) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'rts_cat'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("rts_cat"); }
    if ($db_type == "interbase") { ib_autoinc("rts_cat"); }
}

//********************************************

  // mail reader
if ($quickmail == 2 and ($setup == "install" or $quickmail_old < 2)) {
    // mail account
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."mail_account (
      ID ".$db_int8_auto[$db_type].",
      von ".$db_int8[$db_type].",
      accountname ".$db_varchar40[$db_type].",
      hostname ".$db_varchar80[$db_type].",
      type ".$db_varchar10[$db_type].",
      username ".$db_varchar60[$db_type].",
      password ".$db_varchar60[$db_type].",
      mail_auth ".$db_int2[$db_type].",
      pop_hostname ".$db_varchar40[$db_type].",
      pop_account ".$db_varchar40[$db_type].",
      pop_password ".$db_varchar40[$db_type].",
      smtp_hostname ".$db_varchar40[$db_type].",
      smtp_account ".$db_varchar40[$db_type].",
      smtp_password ".$db_varchar40[$db_type].",
      collect ".$db_int2[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if (!$result) {
        if(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
            echo __('An error ocurred while creating table: ')." 'mail_account'<br>\n"; $error = 1;
        }
    }
    if ($db_type == "oracle") { sequence("mail_account"); }
    if ($db_type == "interbase") { ib_autoinc("mail_account"); }

    // mail attachments
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."mail_attach (
      ID ".$db_int8_auto[$db_type].",
      parent ".$db_int8[$db_type].",
      filename ".$db_varchar255[$db_type].",
      tempname ".$db_varchar255[$db_type].",
      filesize ".$db_int11[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if (!$result) {
        if(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
            echo __('An error ocurred while creating table: ')." 'mail_attach'<br>\n"; $error = 1;
        }
    }
    if ($db_type == "oracle") { sequence("mail_attach"); }
    if ($db_type == "interbase") { ib_autoinc("mail_attach"); }
    // create own index for field parent (reference to field ID of table mail_client
    // -> the mail ID
    $result = db_query("CREATE INDEX mail_attach_parent ON ".DB_PREFIX."mail_attach (parent)");

    // mail sender/signature
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."mail_sender (
      ID ".$db_int8_auto[$db_type].",
      von ".$db_int8[$db_type].",
      title ".$db_varchar80[$db_type].",
      sender ".$db_varchar255[$db_type].",
      signature ".$db_text[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if (!$result) {
        if(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
            echo __('An error ocurred while creating table: ')." 'mail_sender'<br>\n"; $error = 1;
        }
    }
    if ($db_type == "oracle") { sequence("mail_sender"); }
    if ($db_type == "interbase") { ib_autoinc("mail_sender"); }

    // mail client
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."mail_client (
      ID ".$db_int8_auto[$db_type].",
      von ".$db_int8[$db_type].",
      subject ".$db_varchar255[$db_type].",
      body ".$db_text[$db_type].",
      sender ".$db_varchar128[$db_type].",
      recipient ".$db_text[$db_type].",
      cc ".$db_text[$db_type].",
      kat ".$db_varchar40[$db_type].",
      remark ".$db_text[$db_type].",
      date_received ".$db_varchar20[$db_type].",
      touched ".$db_int1[$db_type].",
      typ ".$db_char1[$db_type].",
      parent ".$db_varchar40[$db_type].",
      date_sent ".$db_varchar20[$db_type].",
      header ".$db_text[$db_type].",
      replyto ".$db_varchar128[$db_type].",
      acc ".$db_text[$db_type].",
      body_html ".$db_text[$db_type].",
      message_ID ".$db_varchar255[$db_type].",
      projekt ".$db_int6[$db_type].",
      contact ".$db_int6[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if (!$result) {
        if(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
            echo __('An error ocurred while creating table: ')." 'mail_client'<br>\n"; $error = 1;
        }
    }
    if ($db_type == "oracle") { sequence("mail_client"); }
    if ($db_type == "interbase") { ib_autoinc("mail_client"); }
    // create own index for field 'von' (fererence to field ID, table users)
    $result = db_query("CREATE INDEX mail_client_von ON ".DB_PREFIX."mail_client (von)");

    // mail rules
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."mail_rules (
      ID ".$db_int8_auto[$db_type].",
      von ".$db_int8[$db_type].",
      title ".$db_varchar80[$db_type].",
      phrase ".$db_varchar60[$db_type].",
      type ".$db_varchar60[$db_type].",
      is_not ".$db_varchar3[$db_type].",
      parent ".$db_int8[$db_type].",
      action ".$db_varchar10[$db_type].",
      projekt ".$db_int6[$db_type].",
      contact ".$db_int6[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created').".<br>\n"; }
    if (!$result) {
        if(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
            echo __('An error ocurred while creating table: ')." 'mail_rules'<br>\n"; $error = 1;
        }
    }
    if ($db_type == "oracle") { sequence("mail_rules"); }
    if ($db_type == "interbase") { ib_autoinc("mail_rules"); }
}

//********************************************

   // Logging
if ($logs and ($setup == "install" or !$logs_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."logs (
      ID ".$db_int8_auto[$db_type].",
      von ".$db_int8[$db_type].",
      login ".$db_varchar20[$db_type].",
      logout ".$db_varchar20[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Table logs (for user login/-out tracking) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'logs'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("logs"); }
    if ($db_type == "interbase") { ib_autoinc("logs"); }
}

//********************************************

   // history
if ($history_log and ($setup == "install" or !$history_log_old)) {
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
    if ($result) { echo __('Table logs (for user login/-out tracking) created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'history'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("history"); }
    if ($db_type == "interbase") { ib_autoinc("history"); }
}

//*******************************************

   // Contact profiles
if ($contacts_profiles and ($setup == "install" or !$contacts_profiles_old)) {
    $result = db_query("
      CREATE TABLE ".DB_PREFIX."contacts_profiles (
      ID ".$db_int8_auto[$db_type].",
      von ".$db_int8[$db_type].",
      name ".$db_varchar128[$db_type].",
      remark ".$db_text[$db_type].",
      kategorie ".$db_varchar20[$db_type].",
      acc ".$db_varchar4[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if (!$result) {
        if(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
            echo __('An error ocurred while creating table: ')." 'contacts_profiles'<br>\n"; $error = 1;
        }
    }
    if ($db_type == "oracle") { sequence("contacts_profiles"); }
    if ($db_type == "interbase") { ib_autoinc("contacts_profiles"); }

    $result = db_query("
      CREATE TABLE ".DB_PREFIX."contacts_prof_rel (
      ID ".$db_int8_auto[$db_type].",
      contact_ID ".$db_int8[$db_type].",
      contacts_profiles_ID ".$db_int8[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($result) { echo __('Tables contacts_profiles und contacts_prof_rel created').".<br>\n"; }
    elseif(get_sql_errno($result) != $db_error_code_table_exists[$db_type]) {
        echo __('An error ocurred while creating table: ')." 'contacts_prof_rel'<br>\n"; $error = 1;
    }
    if ($db_type == "oracle") { sequence("contacts_prof_rel"); }
    if ($db_type == "interbase") { ib_autoinc("contacts_prof_rel"); }

    $result = db_query("
      CREATE TABLE ".DB_PREFIX."contacts_import_patterns (
        ID ".$db_int8_auto[$db_type].",
        name ".$db_varchar40[$db_type].",
        von ".$db_int6[$db_type].",
        pattern ".$db_text[$db_type].",
      PRIMARY KEY (ID)
    ) ");
    if ($db_type == "oracle") { sequence("contacts_import_patterns"); }
    if ($db_type == "interbase") { ib_autoinc("contacts_import_patterns"); }
}



//********************************************
// end of the step 'creating tables' according to the settings in the config.inc.php
// the next four tables users, termine, roles & setings will be created in all cases
// *******************************************
if ($setup == "install") {

    // profiles
    $result = db_query("
        CREATE TABLE ".DB_PREFIX."profile (
        ID ".$db_int8_auto[$db_type].",
        von ".$db_int8[$db_type].",
        bezeichnung ".$db_varchar20[$db_type].",
        personen ".$db_text[$db_type].",
        gruppe ".$db_int8[$db_type].",
        PRIMARY KEY (ID)
      ) ");
    if ($result) { echo __('profiles (for user-profiles) created').".<br>\n"; }
    else { echo __('An error ocurred while creating table: ')." 'profile' <br>\n"; $error = 1; }
    if ($db_type == "oracle") { sequence("profile"); }
    if ($db_type == "interbase") { ib_autoinc("profile"); }


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
  if ($result) { echo __('logintoken (...)').".<br>\n"; }
  else { echo __('An error ocurred while creating table: ')." 'logintoken'<br>"; $error = 1; }
  if ($db_type == "oracle") { sequence("logintoken"); }
  if ($db_type == "interbase") { ib_autoinc("logintoken"); }
  $result = db_query("
    CREATE TABLE ".DB_PREFIX."users (
    ID ".$db_int8_auto[$db_type].",
    vorname ".$db_varchar40[$db_type].",
    nachname ".$db_varchar40[$db_type].",
    kurz ".$db_varchar10[$db_type].",
    pw ".$db_varchar40[$db_type].",
    firma ".$db_varchar40[$db_type].",
    gruppe ".$db_int4[$db_type].",
    email ".$db_varchar60[$db_type].",
    acc ".$db_varchar4[$db_type].",
    tel1 ".$db_varchar40[$db_type].",
    tel2 ".$db_varchar40[$db_type].",
    fax ".$db_varchar40[$db_type].",
    strasse ".$db_varchar40[$db_type].",
    stadt ".$db_varchar40[$db_type].",
    plz ".$db_varchar10[$db_type].",
    land ".$db_varchar40[$db_type].",
    sprache ".$db_varchar2[$db_type].",
    mobil ".$db_varchar40[$db_type].",
    loginname ".$db_varchar40[$db_type].",
    ldap_name ".$db_varchar40[$db_type].",
    anrede ".$db_varchar10[$db_type].",
    sms ".$db_varchar60[$db_type].",
    role ".$db_int4[$db_type].",
    settings ".$db_text[$db_type].",
    hrate ".$db_varchar20[$db_type].",
    remark ".$db_text[$db_type].",
    usertype ".$db_int1[$db_type].",
    status ".$db_int1[$db_type].",
    PRIMARY KEY (ID)
  ) ");
  if ($result) { echo __('users (for authentification and address management)').".<br>\n"; }
  else { echo __('An error ocurred while creating table: ')." 'users'<br>"; $error = 1; }
  // create own index for kurz and gruppe
  $result = db_query("CREATE INDEX users_kurz ON ".DB_PREFIX."users (kurz)");
  $result = db_query("CREATE INDEX users_gruppe ON ".DB_PREFIX."users (gruppe)");
  if ($db_type == "oracle") { sequence("users"); }
  if ($db_type == "interbase") { ib_autoinc("users"); }

  // set up user proxy table
  $result = db_query("CREATE TABLE ".DB_PREFIX."users_proxy (
        ID ".$db_int8_auto[$db_type].",
        user_ID ".$db_int8[$db_type].",
        proxy_ID ".$db_int8[$db_type].",
        PRIMARY KEY (ID)
  )");
  if ($result) { echo __('user rights proxy)').".<br>\n"; }
  else { echo __('An error ocurred while creating table: ')." 'users_proxy'<br>"; $error = 1; }
  // create own index for proxy_ID and user_ID
  $result = db_query("CREATE INDEX users_proxy_proxy_ID ON ".DB_PREFIX."users_proxy (proxy_ID)");
  $result = db_query("CREATE INDEX users_proxy_user_ID ON ".DB_PREFIX."users_proxy (user_ID)");
  if ($db_type == "oracle") { sequence("users_proxy"); }
  if ($db_type == "interbase") { ib_autoinc("users_proxy"); }

  // set up users reader table
  $result = db_query("CREATE TABLE ".DB_PREFIX."users_reader (
        ID ".$db_int8_auto[$db_type].",
        user_ID ".$db_int8[$db_type].",
        reader_ID ".$db_int8[$db_type].",
        PRIMARY KEY (ID)
  )");
  if ($result) { echo __('user reader rights)').".<br>\n"; }
  else { echo __('An error ocurred while creating table: ')." 'users_reader'<br>"; $error = 1; }
  // create own index for reader_ID and user_ID
  $result = db_query("CREATE INDEX users_reader_reader_ID ON ".DB_PREFIX."users_reader (reader_ID)");
  $result = db_query("CREATE INDEX users_reader_user_ID ON ".DB_PREFIX."users_reader (user_ID)");
  if ($db_type == "oracle") { sequence("users_reader"); }
  if ($db_type == "interbase") { ib_autoinc("users_reader"); }

  // set up users viewer table
  $result = db_query("CREATE TABLE ".DB_PREFIX."users_viewer (
        ID ".$db_int8_auto[$db_type].",
        user_ID ".$db_int8[$db_type].",
        viewer_ID ".$db_int8[$db_type].",
        PRIMARY KEY (ID)
  )");
  if ($result) { echo __('user reader rights)').".<br>\n"; }
  else { echo __('An error ocurred while creating table: ')." 'users_viewer'<br>"; $error = 1; }
  // create own index for viewer_ID and user_ID
  $result = db_query("CREATE INDEX users_viewer_viewer_ID ON ".DB_PREFIX."users_viewer (viewer_ID)");
  $result = db_query("CREATE INDEX users_viewer_user_ID ON ".DB_PREFIX."users_viewer (user_ID)");
  if ($db_type == "oracle") { sequence("users_viewer"); }
  if ($db_type == "interbase") { ib_autoinc("users_viewer"); }


  //********************************************
    $q = "CREATE TABLE ".DB_PREFIX."termine (
        ID ".$db_int11_auto[$db_type].",
        parent ".$db_int11[$db_type].",
        serie_id ".$db_int11[$db_type].",
        serie_typ ".$db_varchar4[$db_type].",
        serie_bis ".$db_varchar10[$db_type].",
        von ".$db_int8[$db_type].",
        an ".$db_int8[$db_type].",
        event ".$db_varchar128[$db_type].",
        remark ".$db_text[$db_type].",
        projekt ".$db_int8[$db_type].",
        datum ".$db_varchar10[$db_type].",
        anfang ".$db_varchar4[$db_type].",
        ende ".$db_varchar4[$db_type].",
        ort ".$db_varchar40[$db_type].",
        contact ".$db_int8[$db_type].",
        remind ".$db_int4[$db_type].",
        visi ".$db_int1[$db_type].",
        partstat ".$db_int1[$db_type].",
        priority ".$db_int1[$db_type].",
        status ".$db_int1[$db_type].",
        sync1 ".$db_varchar20[$db_type].",
        sync2 ".$db_varchar20[$db_type].",
        upload ".$db_text[$db_type].",
        PRIMARY KEY (ID)
    )";
    $result = db_query($q);
    if ($result) { echo __('Table termine (for events) created').".<br>\n"; }
    else { echo __('An error ocurred while creating table: ')." 'termine'<br>\n"; $error = 1; }
    // create own index for anfang, ende, von, an and visi
    $result = db_query("CREATE INDEX termine_anfang ON ".DB_PREFIX."termine (anfang)");
    $result = db_query("CREATE INDEX termine_ende ON ".DB_PREFIX."termine (ende)");
    $result = db_query("CREATE INDEX termine_von ON ".DB_PREFIX."termine (von)");
    $result = db_query("CREATE INDEX termine_an ON ".DB_PREFIX."termine (an)");
    $result = db_query("CREATE INDEX termine_visi ON ".DB_PREFIX."termine (visi)");
    if ($db_type == "oracle") { sequence("termine"); }
    if ($db_type == "interbase") { ib_autoinc("termine"); }


  // Roles
  $result = db_query("
    CREATE TABLE ".DB_PREFIX."roles (
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
  if ($db_type == "interbase") { ib_autoinc("roles"); }

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
    rights ".$db_varchar4[$db_type].",
    ownercolumn ".$db_varchar255[$db_type].",
    PRIMARY KEY (ID)
  ) ");
  if ($db_type == "oracle") { sequence("db_manager"); }
  if ($db_type == "interbase") { ib_autoinc("db_manager"); }

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


  //filter
  $result = db_query("
    CREATE TABLE ".DB_PREFIX."filter(
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


    // 2. now add the values
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (1,'contacts','vorname','__(\'First Name\')','text','Type in the first name of the person',4,1,1,NULL,NULL,NULL,2,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (2,'contacts','firma','__(\'Company\')','text','Name of associated team or company',6,1,1,NULL,NULL,NULL,0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (3,'contacts','nachname','__(\'Family Name\')','text','give the description: last name, company name or organisation etc.',5,1,1,NULL,NULL,NULL,1,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (4,'contacts','anrede','__(\'Salutation\')','text','Title of the person: Mr, Mrs, Dr., Majesty etc. ...',1,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (5,'contacts','email2','email 2','email','enter an alternative mail address of this contact',18,1,1,NULL,NULL,NULL,0,'1','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (6,'contacts','tel1','__(\'Phone\') 1','text','enter the primary phone number of this contact',8,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (7,'contacts','strasse','__(\'Street\')','text','the street where the person lives or the company is located',11,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (8,'contacts','tel2','__(\'Phone\') 2','text','enter the secondary phone number of this contact',9,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (9,'contacts','fax','__(\'Fax\')','text','enter the fax number of this contact',10,1,1,NULL,NULL,NULL,0,'1','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (10,'contacts','stadt','__(\'City\')','text','the city where the person lives or the company is located',12,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (11,'contacts','plz','__(\'Zip code\')','text','the coresponding zip code',13,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (12,'contacts','land','__(\'Country\')','text','the country',15,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (13,'contacts','bemerkung','__(\'Comment\')','textarea','a comment about this record',17,1,3,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (14,'contacts','mobil','__(\'mobile\')','phone','enter the cellular phone number',19,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (15,'contacts','url','url','url','the homepage - private or business',20,1,1,NULL,NULL,NULL,4,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (16,'contacts','div1','$cont_usrdef1','text','a default userdefined field',21,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (17,'contacts','div2','$cont_usrdef2','text','another default userdefined field',22,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (18,'contacts','state','__(\'State\')','text','region or state (USA)',17,1,1,NULL,NULL,NULL,0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (19,'contacts','email','email','email','enter the main email address of this contact',5,1,1,NULL,NULL,NULL,3,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (20,'contacts','kategorie','__(\'Category\')','select_category','Select an existing category or insert a new one',23,1,1,NULL,NULL,'(acc like \'system\' or ((von = $user_ID or acc like \'group\' or acc like \'%\\\"$user_kurz\\\"%\') and ".addslashes($sql_user_group)."))',4,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (22,'notes','remark','__(\'Comment\')','textarea','bodytext of the note',4,2,5,NULL,NULL,NULL,2,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (21,'notes','name','__(\'Title\')','text','Title of this note',1,2,1,'',NULL,NULL,1,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (23,'notes','contact','__(\'Contact\')','contact','Contact related to this note',5,1,1,NULL,NULL,NULL,3,NULL,NULL,0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (24,'notes','projekt','__(\'Projects\')','project','Project related to this note',6,1,1,NULL,NULL,NULL,4,NULL,NULL,0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (25,'notes','kategorie','__(\'Category\')','select_category','Select an existing category or insert a new one',7,1,1,NULL,NULL,'(acc like \'system\' or ((von = $user_ID or acc like \'group\' or acc like \'%\\\"$user_kurz\\\"%\') and ".addslashes($sql_user_group)."))',0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (26,'notes','div1','__(\'added\')','timestamp_create','',100,1,1,NULL,NULL,'',5,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (27,'notes','div2','__(\'changed\')','timestamp_modify','',101,1,1,NULL,NULL,'',6,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (33,'projekte','ende','__(\'End\')','date','planned end',6,1,1,NULL,NULL,NULL,3,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (31,'projekte','name','__(\'Project Name\')','text','the name of the project',1,1,1,NULL,NULL,NULL,1,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (34,'projekte','wichtung','__(\'Priority\')','select_values','set the priority of this project',4,1,1,NULL,NULL,'0|1|2|3|4|5|6|7|8|9',0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (32,'projekte','anfang','__(\'Begin\')','date','start day',5,1,1,NULL,NULL,NULL,2,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (35,'projekte','status','__(\'Status\') [%]','userID_access','current completion status',0,1,1,NULL,NULL,'chef',4,NULL,'1','1',NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (36,'projekte','statuseintrag','__(\'Last status change\')','display','date of last change of status',12,1,1,NULL,NULL,NULL,0,'1','1','1',NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (37,'projekte','ziel','__(\'Aim\')','textarea','descirbe the aim of this project',8,1,4,NULL,NULL,NULL,0,'0','0',1,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (38,'projekte','note','__(\'Remark\')','textarea','remarks',7,1,4,NULL,NULL,NULL,0,'0','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (39,'projekte','contact','__(\'Contact\')','contact','select the customer/contact',13,1,1,NULL,NULL,NULL,0,'1','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (40,'projekte','stundensatz','__(\'Hourly rate\')','text','hourly rate of this project',9,1,1,NULL,NULL,NULL,0,'1','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (41,'projekte','budget','__(\'Calculated budget\')','text','',10,1,1,NULL,NULL,NULL,0,'1','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (50,'rts','name','__(\'Title\')','text','the title of the request',1,1,1,NULL,NULL,NULL,1,'0','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (51,'rts','note','__(\'Remark\')','textarea','the body of the request set by the customer',6,1,1,NULL,NULL,NULL,0,'1','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (52,'rts','submit','__(\'Date\')','timestamp_create','date/time the request ha been submitted',4,1,1,NULL,NULL,NULL,0,'0','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (53,'rts','recorded','__(\'Author\')','authorID','the user who wrote this request',5,1,1,NULL,NULL,NULL,0,'0','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (54,'rts','contact','__(\'Contact\')','contact_create','contact related to this request',9,1,1,NULL,NULL,NULL,0,'0','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (55,'rts','email','__(\'Email Address\')','email_create','insert the mail address in case the customer is not listed',12,1,1,NULL,NULL,NULL,0,'0','0',1,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (56,'rts','due_date','__(\'Due date\')','date','due date of this request',9,1,1,NULL,NULL,NULL,3,'1','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (57,'rts','assigned','__(\'Assigned\')','userID','assign the request to this user',10,1,1,NULL,NULL,NULL,4,'1','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (58,'rts','priority','__(\'Priority\')','select_values','set the priority of this project',11,1,1,NULL,NULL,'0|1|2|3|4|5|6|7|8|9',5,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (59,'rts','remark','__(\'remark\')','textarea','internal remark to this request',7,1,1,NULL,NULL,NULL,0,'1','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (60,'rts','solution','__(\'solve\')','textarea','A text will cause: a mail to the customer and closing the request',8,1,1,NULL,NULL,NULL,0,'0','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (61,'rts','solved','__(\'solved\') __(\'From\')','user_show','the user who has solved this request',14,1,1,'','','',0,'0','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (62,'rts','solve_time','__(\'solved\')','timestamp_show','date and time when the request has been solved',16,1,1,NULL,NULL,NULL,0,'0','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (63,'rts','acc','__(\'access\')','select_values','requests with status open appear in the knowledge base!',17,1,1,NULL,NULL,'0#__(\'n/a\')|1#__(\'internal\')|2#__(\'open\')',0,'0','0',1,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (64,'rts','proj','__(\'Projects\')','project','project related to this request',14,1,1,NULL,NULL,'',0,'0','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (65,'rts','status','__(\'Status\')','select_values','state of this request',0,0,0,NULL,NULL,'1#__(\'unconfirmed\')|2#__(\'new\')|3#__(\'assigned\')|4#__(\'reopened\')|5#__(\'solved\')|6#__(\'verified\')|7#__(\'closed\')',6,'0','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (86,'dateien','filename','__(\'Title\')','text','Title of the file or directory',1,2,1,'',NULL,NULL,1,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (87,'dateien','remark','__(\'Comment\')','textarea','remark related to this file',4,2,5,NULL,NULL,NULL,2,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (88,'dateien','contact','__(\'Contact\')','contact','Contact related to this file',5,1,1,NULL,NULL,NULL,3,NULL,NULL,0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (89,'dateien','div2','__(\'Projects\')','project','Project related to this file',6,1,1,NULL,NULL,NULL,4,NULL,NULL,0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (90,'dateien','kat','__(\'Category\')','select_category','Select an existing category or insert a new one',7,1,1,NULL,NULL,'(acc like \'system\' or ((von = $user_ID or acc like \'group\' or acc like \'%\\\"$user_kurz\\\"%\') and ".addslashes($sql_user_group)."))',5,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (91,'dateien','datum','__(\'changed\')','timestamp_modify','',101,1,1,NULL,NULL,'',0,NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (94,'dateien','filesize','__(\'filesize (Byte)\')','display_byte','',0,1,1,NULL,NULL,'','6',NULL,'0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (93,'dateien','lock_user','__(\'locked by\')','user_show','Name of the user who has locked this file temporarly',11,1,1,'','','',0,'0','0',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (95,'todo','remark','__(\'Title\')','text','Kurze Beschreibung',1,2,1,NULL,NULL,NULL,1,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (96,'todo','deadline','__(\'Deadline\')','date','',7,1,1,'','','',2,'','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (97,'todo','datum','__(\'Date\')','timestamp_create','',5,1,1,'','','',0,'','1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (99,'todo','priority','__(\'Priority\')','select_values',NULL,4,1,1,NULL,NULL,'0|1|2|3|4|5|6|7|8|9',5,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (100,'todo','project','__(\'Project\')','project',NULL,9,1,1,NULL,NULL,NULL,6,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (101,'todo','contact','__(\'Contact\')','contact',NULL,8,1,1,NULL,NULL,NULL,0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (102,'todo','note','__(\'Describe your request\')','textarea',NULL,11,2,3,NULL,NULL,NULL,0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (103,'todo','comment1','__(\'Remark\') __(\'Author\')','textarea',NULL,12,2,3,NULL,NULL,NULL,NULL,NULL,'1',1,'o','von')") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (104,'todo','comment2','__(\'Remark\') __(\'Receiver\')','textarea',NULL,13,2,3,NULL,NULL,NULL,NULL,NULL,'1',1,'o','ext')") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (106,'todo','von','__(\'of\')','user_show',NULL,2,1,1,NULL,NULL,NULL,3,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (105,'todo','anfang','__(\'Begin\')','date',NULL,6,1,1,NULL,NULL,NULL,0,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (107,'todo','ext','__(\'to\')','userID',NULL,3,1,1,NULL,NULL,NULL,4,NULL,'1',0,NULL,NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (122, 'todo', 'progress', '__(\'progress\') [%]', 'text', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (123, 'todo', 'status', '__(\'Status\')', 'select_values', NULL, NULL, NULL, NULL, NULL, NULL, '1#__(\'waiting\')|2#__(\'Open\')|3#__(\'accepted\')|4#__(\'rejected\')|5#__(\'ended\')', 7, NULL, NULL, 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (108, 'termine', 'event', '__(\'Title\')', 'text', 'Title of this event', 1, 1, 1, '', NULL, NULL, 1, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (109, 'termine', 'datum', '__(\'Date\')', 'text', 'Date of this event', 2, 1, 1, '', NULL, NULL, 2, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (110, 'termine', 'anfang', '__(\'Start\')', 'text', 'Title of this event', 3, 1, 1, '', NULL, NULL, 3, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (111, 'termine', 'ende', '__(\'End\')', 'text', 'end of this event', 4, 1, 1, '', NULL, NULL, 4, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (112, 'termine', 'von', '__(\'Author\')', 'userID', 'Author of this event', 5, 1, 1, '', NULL, NULL, 5, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (113, 'termine', 'an', '__(\'Recipient\')', 'userID', 'Recipient', 6, 1, 1, '', NULL, NULL, 6, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (114, 'termine', 'partstat', '__(\'Participation\')', 'select_values', 'Title of this event', 7, 1, 1, '', NULL, '1#__(\'untreated\')|2#__(\'accepted\')|3#__(\'rejected\')', 7, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (115, 'projekte', 'kategorie', '__(\'Category\')', 'select_values', 'current category (or status) of this project', 14, 1, 1, NULL, NULL, '1#__(\'offered\')|2#__(\'ordered\')|3#__(\'Working\')|4#__(\'ended\')|5#__(\'stopped\')|6#__(\'Re-Opened\')|7#__(\'waiting\')', 1, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (116, 'projekte', 'chef', '__(\'Leader\')', 'userID', 'Select a user of this group as the project leader', 15, 1, 1, NULL, NULL, NULL, 0, '1', '0', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (117, 'mail_client', 'remark', '__(\'Comment\')', 'textarea', NULL, 1, 2, 2, NULL, NULL, NULL, 3, NULL, 'on', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (118, 'mail_client', 'subject', '__(\'subject\')', 'text', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 'on', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (119, 'mail_client', 'sender', '__(\'Sender\')', 'text', NULL, 0, 0, 0, NULL, NULL, NULL, 3, NULL, 'on', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (120, 'mail_client', 'kat', '__(\'Category\')', 'select_category', NULL, 2, 2, 1, NULL, NULL, NULL, 4, NULL, 'on', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (121, 'mail_client', 'projekt', '__(\'Project\')', 'project', NULL, 3, 2, 1, NULL, NULL, NULL, 5, NULL, 'on', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (124, 'rts', 'ID', '__(\'ID\')', 'text', NULL, NULL, 1, 1, NULL, NULL, NULL, 2, NULL, '1', 0, NULL, NULL)") or db_die();

    ###$result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (125,  'db_records','t_module','__(\'Module\')','text', 'Module name', 1, 1, 1, NULL, NULL, NULL, 1, NULL, '1', 0, NULL, NULL)") or db_die();
    ###$result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (126,  'db_records','t_remark','__(\'Remark\')','text', 'Remark', 2, 1, 1, NULL, NULL, NULL, 2, NULL, '1', 0, NULL, NULL)") or db_die();
    ###$result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (127,  'db_records','t_archiv','__(\'Archive\')','text', 'Archive', 3, 1, 1, NULL, NULL, NULL, 3, NULL, '1', 0, NULL, NULL)") or db_die();
    ###$result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (128,  'db_records','t_touched','__(\'Touched\')','text', 'Touched', 4, 1, 1, NULL, NULL, NULL, 4, NULL, '1', 0, NULL, NULL)") or db_die();

//exit;

    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (125, 'db_records', 't_module', '__(\'Module\')', 'display', 'Module name', 1, 1, 1, NULL, NULL, NULL, 1, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (126, 'db_records', 't_remark', '__(\'Remark\')', 'text', 'Remark', 3, 1, 1, NULL, NULL, NULL, 2, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (127, 'db_records', 't_archiv', '__(\'Archive\')', 'text', 'Archive', 6, 1, 1, NULL, NULL, NULL, 0, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (128, 'db_records', 't_touched', '__(\'Touched\')', 'text', 'Touched', 7, 1, 1, NULL, NULL, NULL, 0, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (129, 'db_records', 't_name', '__(\'Title\')', 'text', 'Title', 2, 1, 1, NULL, NULL, NULL, 1, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (130, 'db_records', 't_wichtung', '__(\'Priority\')', 'select_values', 'Priority', 4, 1, 1, NULL, NULL, '0|1|2|3|4|5|6|7|8|9', 4, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (131, 'db_records', 't_reminder_datum', '__(\'Resubmission at:\')', 'date', 'Date', 5, 1, 1, NULL, NULL, '', 5, NULL, '1', 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (132, 'db_records', 't_record', '__(\'Record set\')', 'display', 'ID of the target record', 0, 1, 1, '', NULL, NULL, 0, NULL, 0, 0, NULL, NULL)") or db_die();
    $result = db_query("INSERT INTO ".DB_PREFIX."db_manager VALUES (133, 'db_records', 't_datum', '__(\'Date\')', 'date', '', 0, 1, 1, NULL, NULL, '', 0, NULL, '0', 0, NULL, NULL)") or db_die();


    // settings

  //*********************************************
  // Example user and group data ****************

  if (trim($rootpass) == '') $rootpass = 'root';
  // crypt example user data?
  if ($pw_crypt) {
    $pw_root = encrypt($rootpass, $rootpass);
    $pw_test = encrypt("test", "test");
  }
  else {
    $pw_root = $rootpass;
    $pw_test = "test";
  }
  // short names
  $l_root = "root1";
  $l_test = "test1";
  // if groups -> create group default
  if ($groups) {
    $result = db_query("INSERT INTO ".DB_PREFIX."gruppen
                                    (   ID,     name,     kurz,kategorie,chef)
                             VALUES ($dbIDnull,'default','def','default', 1 )") or db_die();
    if (!$result) $error = 1;
    $result = db_query("INSERT INTO ".DB_PREFIX."grup_user
                                    (   ID,    grup_ID,user_ID)
                             VALUES ($dbIDnull,1,      2)") or db_die();
    if (!$result) $error = 1;
    echo " ".__('The group default has been created').".<br />";
    $gr_var = "1";   // Flag for next insert: user test is assigned to group default
  }
  // create user root and test
  $result = db_query("INSERT INTO ".DB_PREFIX."users
                             (ID,      vorname,nachname,kurz,     pw,      acc,  sprache,loginname,remark,usertype,status)
                      VALUES ($dbIDnull,'root','root','$l_root','$pw_root','an','$langua','root','Administrator','0','0')") or db_die();
  if (!$result) $error = 1;
  $result = db_query("INSERT INTO ".DB_PREFIX."users
                             (ID,vorname,nachname,kurz,pw,gruppe,acc,sprache,loginname,remark,usertype,status)
                      VALUES ($dbIDnull,'test','test','$l_test','$pw_test','$gr_var','cy','$langua','test','Test User','0','0')") or db_die();
  if (!$result) $error = 1;

  echo "<br /> ".__('The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)').".<br /><br />";

  // tell the login parameters - login via short name and normal condition
  if      ($login_kurz == 0) echo "<b>LOGIN: root/$rootpass - test/test</b><br />";
  else if ($login_kurz == 1) echo "<b>LOGIN: $l_root/$rootpass - $l_test/test</b><br />";
  else if ($login_kurz == 2) echo "<b>LOGIN: root/$rootpass - test/test</b><br />";

} // Ende Klammer install  - end of install brace

// ************************************************

// compose string to be written into the config file.
$eol_trans = array("\r" =>'\r', "\n" =>'\n');
$mail_eoh  = strtr($mail_eoh, $eol_trans);
$mail_eol  = strtr($mail_eol, $eol_trans);
$timecard_change=60;
$timecard_delete=1;
$config_file = "<?php

define('PHPR_VERSION', '5.0');

// paths
define('PHPR_HOST_PATH', '".$host_path."'); // ".__('absolute path to host, e.g. http://myhost/')."
define('PHPR_INSTALL_DIR', '".$installation_dir."'); // ".__('installation directory below host, e.g. myInstallation/of/phprojekt5/')."

// database
define('PHPR_DB_TYPE',   '$db_type');     // ".__('Type of database system')."
define('PHPR_DB_HOST',   '$db_host');     // ".__('Location of the database')."
define('PHPR_DB_USER',   '$db_user');     // ".__('Username for the access')."
define('PHPR_DB_PASS',   '$db_pass');     // ".__('Password for the access')."
define('PHPR_DB_NAME',   '$db_name');     // ".__('Name of the database')."
define('PHPR_DB_PREFIX', '$db_prefix');   // $inst

// system
define('PHPR_SESSION_NAME', 'PHPR".strtoupper(substr(md5(time()), 0, 5))."');
define('PHPR_LOGIN_SHORT', '$login_kurz');          // ".__('Login name')." - ".__('0: last name, 1: short name, 2: login name')."
define('PHPR_PW_CHANGE', '$pw_change');            // ".__('Password change')."
define('PHPR_PW_CRYPT', '$pw_crypt');              // ".__('Encrypt passwords')."
define('PHPR_ACC_ALL_GROUPS', '$acc_all_groups');         // ".__('Option to release objects in all groups')."
define('PHPR_ACC_DEFAULT', '$acc_default');               // ".__('Default access mode: private=0, group=1')."
define('PHPR_ACC_WRITE_DEFAULT', '$acc_write_default');   // ".__('Default write access mode: private=0, group=1')."
define('PHPR_LDAP', '$ldap');                             // ".__('use LDAP')."
define('PHPR_TIMEZONE', '$timezone');                     // ".__('Timezone difference [h] Server - user')."
define('PHPR_SESSION_TIME_LIMIT', '$session_time_limit'); // ".__('Time limit for sessions')."
define('PHPR_MAXHITS', '$maxhits');                       // ".__('max. hits displayed in search module')."
define('PHPR_LOGS', '$logs');                             // ".__('Logging')."
define('PHPR_HISTORY_LOG', '$history_log');               // ".__('Log history of records')."
define('PHPR_ERROR_REPORTING_LEVEL', '0');                // ".__('0: default mode, 1: Only for debugging mode')."
define('PHPR_SUPPORT_PDF', '$support_pdf');
define('PHPR_SUPPORT_HTML', '$support_html');
define('PHPR_SUPPORT_CHART', '$support_chart');
define('PHPR_DOC_PATH', '$doc_path');
define('PHPR_ATT_PATH', '$att_path');
define('PHPR_FILE_PATH', '$file_path');
define('PHPR_FILTER_MAXHITS', '20');
define('PHPR_ROLES', '1');
define('PHPR_DEFAULT_VISI', '$default_visi');
define('PHPR_ACCESS_GROUPS', '$access_groups');
define('PHPR_COMPATIBILITY_MODE', '$compatibility_mode');

// modules
define('PHPR_VOTUM', '$votum');              // ".__('Voting system yes = 1, no = 0')."
define('PHPR_BOOKMARKS', '$lesezeichen');  // ".__('Bookmarks yes = 1, no = 0')."
define('PHPR_LINKS', '$show_links_module');

// calendar
define('PHPR_CALENDAR', '$calendar');                        // ".__('Calendar')."
define('PHPR_GROUPVIEWUSERHEADER', '$groupviewuserheader');  // ".__('Header groupviews')."
define('PHPR_MAIL_NEW_EVENT', '$mail_new_event');            // ".__('Notification on new event in others calendar')."
define('PHPR_DAY_START', '$tagesanfang');                  // ".__('First hour of the day:').": 6,7,8,9
define('PHPR_DAY_END', '$tagesende');                      // ".__('Last hour of the day:').": 17,18,19,21
define('PHPR_CALENDAR_DATECONFLICTS_MAXDAYS', '$calendar_dateconflicts_maxdays');
define('PHPR_CALENDAR_DATECONFLICTS_MAXHITS', '$calendar_dateconflicts_maxhits');

// reminder
define('PHPR_REMINDER', '$reminder');                      // ".__('Reminder').", ".__('Alarm x minutes before the event').": = 2
define('PHPR_REMIND_FREQ', '$remind_freq');                // $inst_text73
define('PHPR_SMS_REMIND_SERVICE', '$sms_remind_service');  // ".__('Reminds via SMS/Email')."

// projects
define('PHPR_PROJECTS', '$projekte'); // ".__('Project management yes = 1, no = 0').", ".__('additionally assign resources to events').": = 2

// timecard
define('PHPR_TIMECARD', '$timecard'); // ".__('Timecard').", ".__('Mail to the chief').": = 2
define('PHPR_TIMECARD_DELETE', '$timecard_delete');
define('PHPR_TIMECARD_CHANGE', '$timecard_change');
define('PHPR_TIMECARD_NETTO', '');

// contacts
define('PHPR_CONTACTS', '$adressen');                   // ".__('Address book  = 1, nein = 0')."
define('PHPR_CONT_USRDEF1', '$cont_usrdef1');           // ".__('Name of userdefined field')." 1
define('PHPR_CONT_USRDEF2', '$cont_usrdef2');           // ".__('Name of userdefined field')." 2
define('PHPR_CONTACTS_PROFILES', '$contacts_profiles'); // ".__('Profiles for contacts')."
define('PHPR_CALLTYPE', '$calltype');

// notes
define('PHPR_NOTES', '$notes'); // ".__('Notes')."

// todo
define('PHPR_TODO', '$todo');                // ".__('Todo-Lists yes = 1, no = 0')."
define('PHPR_TODO_OPTION_ACCEPTED', '$todo_option_accepted'); // ".__('Select-Option accepted available = 1, not available = 0')."

// mail
define('PHPR_QUICKMAIL', '$quickmail');         // ".__('Mail no = 0, only send = 1, send and receive = 2')."
define('PHPR_FAXPATH', '$faxpath');             // ".__('Path to sendfax')."
define('PHPR_MAIL_SEND_ARG', '$mail_send_arg'); // ".__('Adds -f as 5. parameter to mail(), see php manual')."
define('PHPR_MAIL_EOL', \"$mail_eol\"); // ".__('end of line in body; e.g. \\r\\n (conform to RFC 2821 / 2822)')."
define('PHPR_MAIL_EOH', \"$mail_eoh\"); // ".__('end of header line; e.g. \\r\\n (conform to RFC 2821 / 2822)')."
define('PHPR_MAIL_MODE', '$mail_mode');      // ".__('Sendmail mode: 0: use mail(); 1: use socket')."
define('PHPR_MAIL_AUTH', '$mail_auth');  // ".__('Authentication')." - 0:".__('No Authentication').",1:".__('with POP before SMTP').",2:".__('SMTP auth (via socket only!)')."
// ".__('SMTP account data (only needed in case of socket)')."
define('PHPR_SMTP_HOSTNAME', '$smtp_hostname');  // ".__('the real address of the SMTP mail server, you have access to (maybe localhost)')."
define('PHPR_LOCAL_HOSTNAME', '$local_hostname');   // ".__('name of the local server to identify it while HELO procedure')."
// ".__('fill out in case of authentication via POP before SMTP')."
define('PHPR_POP_ACCOUNT', '$pop_account'); // ".__('real username for POP before SMTP')."
define('PHPR_POP_PASSWORD', '$pop_password'); // ".__('password for this pop account')."
define('PHPR_POP_HOSTNAME', '$pop_hostname'); // ".__('the POP server')."
// ".__('fill out in case of SMTP authentication')."
define('PHPR_SMTP_ACCOUNT', '$smtp_account'); // ".__('real username for SMTP auth')."
define('PHPR_SMTP_PASSWORD', '$smtp_password'); // ".__('password for this account')."

// file manager
define('PHPR_FILEMANAGER', '".($filemanager == '0' ? '0' : '1')."');   //  ".__('File management no = 0').",  ".__('yes = 1')."
define('PHPR_FILEMANAGER_NOTIFY', '$filemanager_notify');         // ".__('Enables mail notification on new elements')."
#\$filemanager_versioning = '$filemanager_versioning'; // ".__('Enables versioning for files')." ----> deprecated <----

// forum
define('PHPR_FORUM', '$forum');                     // ".__('Forum yes = 1, no = 0')."
define('PHPR_FORUM_TREE_OPEN', '$forum_tree_open'); // ".__('default mode for forum tree: 1 - open, 0 - closed').";
define('PHPR_FORUM_NOTIFY', '$forum_notify');       // ".__('Enables mail notification on new elements')."

// helpdesk - rts
define('PHPR_RTS', '$rts');                   // ".__('Help desk')."
define('PHPR_RTS_MAIL', '$rts_mail');         // ".__('Email Address of the support')."
define('PHPR_RTS_DUEDATE', '$rts_duedate');   // 1: ".__('RT Option: Customer can set a due date')."
#define('PHPR_RTS_CHEF', '$rts_chef');         // ".__('RT Option: Assigning request').": ".__('0: by everybody, 1: only by persons with status chief')." ----> deprecated <----
define('PHPR_RTS_CUST_ACC', '$rts_cust_acc'); // 1: ".__('RT Option: Customer Authentification').", ".__('0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email')."

// chat
define('PHPR_CHAT', '$chat');                // ".__('Chat yes = 1, no = 0')."
define('PHPR_ALIVEFILE', '$alivefile');
define('PHPR_CHATFILE', '$chatfile');
define('PHPR_CHATFREQ', '$chatfreq');
define('PHPR_ALIVEFREQ', '$alivefreq');
define('PHPR_MAX_LINES', '$max_lines');
define('PHPR_CHAT_TIME', '$chat_time');     // ".__('Timestamp for chat messages')."
define('PHPR_CHAT_NAMES', '$chat_names');   // ".__('Name format in chat list')."
                                  // ".__('0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name')."

// layout
define('PHPR_SKIN', '$skin');
define('PHPR_DEFAULT_SIZE', '$default_size'); // ".__('Default size of form elements')."
define('PHPR_CUR_SYMBOL', '$cur_symbol');     // ".__('Currency symbol')."
define('PHPR_BGCOLOR1', '$bgcolor1');         // ".__('First background color')."
define('PHPR_BGCOLOR2', '$bgcolor2');         // ".__('Second background color')."
define('PHPR_BGCOLOR3', '$bgcolor3');         // ".__('Third background color')."
define('PHPR_BGCOLOR_MARK', '$bgcolor_mark'); // ".__('Color to mark rows')."
define('PHPR_BGCOLOR_HILI', '$bgcolor_hili'); // ".__('Color to highlight rows')."
define('PHPR_LOGO', '$logo');                 // ".__('company icon yes = insert name of image')."
define('PHPR_HP_URL', '$hp_url');             // ".__('URL to the homepage of the company').", ".__('no = leave empty')."
define('PHPR_TR_HOVER', '$tr_hover');         // ".__('Highlight list records with mouseover')."

?>
"; // end of string for $config_file, now write config.inc.php

if (!$error) {
    $fp = @fopen("config.inc.php", 'wb+');
    $fw = fwrite($fp, $config_file);
    if (!$fw) {
        $error = 1;
        echo "<br><b>PANIC! <br> config.inc.php can't be written!</b><br>";
    }
    fclose($fp);
}

// error or success?
if (!$error) {
    echo "<b>".__('Finished')."!</b>\n";

    if ($setup == "install") {
        echo "<br><p style='font-size: 10pt;'>";
        echo __('All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>')."<br><br>\n";
        if ($groups) { echo __('The user test is now member of the group default.<br>Now you can create new groups and add new users to the group')."<br>"; }
        echo "<br>".__('To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.')."</p>";

        // try to create the directory for uploads
        if ($setup == "install" and $file_path and !is_dir($file_path)) {
            $result = mkdir($file_path,0700);
            // no created? -> show error message
            if (!result) echo "<br><b>".__('Please create the file directory').": '$file_path'</b><br><br>";
        }

        // try to create the directory for attachments
        if ($quickmail_old < 2 and $quickmail == 2 and !is_dir(getcwd().'/attach')) {
            $result = mkdir(getcwd().'/attach',0700);
            // not created? -> show error message
            if (!$result) echo "<br><b>".__('Please create the file directory').": 'attach'</b><br><br>";
        }
        // create a directory for uploads in all cases since this dir will be used by several modules (future)
        mkdir(getcwd().'/docs',0700);

        // ensure that the webserver has the read/write priviledge for attach, chat and upload dir
        if ($dateien or $chat or $quickmail == 2) {
            echo __('The server needs the privilege to write to the directories').":<br>\n";
            if ($filemanager) echo "<i>'upload'</i><br>\n";
            if ($chat) echo "<i>'chat'</i><br>\n";
            if ($quickmail == 2) echo "<i>'attach'</i><br>\n";
        }
    }
    // last message: call index.php :-)
    echo "<br>".__('Please run index.php: ')." <a href='index.php'>index.php</a><br>\n";
}
// errors!!
else {
    echo "<b>".__('There were errors, please have a look at the messages above')."!</b>\n";
    echo __('<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>')."\n";
}

// destroy the session - on some system the first, on some system the second function doesn't work :-))
@session_unset();
@session_destroy();

?>
