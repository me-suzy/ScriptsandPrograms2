<?php

// step2a.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: step2a.php,v 1.49 2005/07/11 10:50:38 nina Exp $

// check whether setup.php calls this script - authentication!
if (!defined("setup_included")) die("Please use setup.php!");


// some checks and defines to prevent php warnings
if (!isset($version)) $version = '';
if (!isset($tagesanfang)) $tagesanfang = '';
if (!isset($tagesende)) $tagesende = '';
if (!isset($bgcolor1)) $bgcolor1 = '';
if (!isset($bgcolor2)) $bgcolor2 = '';
if (!isset($bgcolor3)) $bgcolor3 = '';
if (!isset($terminfarbe)) $terminfarbe = '';
if (!isset($bgcolor_mark)) $bgcolor_mark = '';
if (!isset($bgcolor_hili)) $bgcolor_hili = '';
if (!isset($logo)) $logo = '';
if (!isset($hp_url)) $hp_url = '';
if (!isset($pw_crypt)) $pw_crypt = '';
if (!isset($pw_change)) $pw_change = '';
if (!isset($mail_new_event)) $mail_new_event = '';
if (!isset($ldap)) $ldap = '';
if (!isset($acc_all_groups)) $acc_all_groups = '';
if (!isset($db_prefix)) $db_prefix = '';
if (!isset($login_kurz)) $login_kurz = '';
if (!isset($timezone)) $timezone = '';
if (!isset($rts_mail)) $rts_mail = '';
if (!isset($groups)) $groups = '';
if (!isset($skin)) $skin = '';


// installation from scratch
if ($setup == "install") {

    //*** db test ***

    // test mysql access
    if ($db_type == "mysql") {
        $link = mysql_connect($db_host, $db_user, $db_pass) or die(__('Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'));
    }
    // test sqlite access
    else if ($db_type == "sqlite") {
        $dbfilename = dirname(dirname(__FILE__))."/$db_name.db";
        if (! $link = sqlite_open($dbfilename , 0666, $sqliteerror) ) {
            $db_test = "failed";
        }
    }
    // test interbase access
    else if ($db_type == "interbase") {
        $db_host2 =  $db_host.":".$db_name;
        $link = ibase_connect($db_host2, $db_user, $db_pass) or die(__('Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'));
    }
    // test ms_sql
    else if ($db_type == "ms_sql") {
        $link = mssql_connect($db_host, $db_user, $db_pass) or die(__('Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'));
    }
    // test oracle
    else if ($db_type == "oracle") {
        $link = OCILogon($db_user, $db_pass, $db_name) or die(__('Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'));
        $datestmt = OCIParse($link, "alter session set NLS_DATE_FORMAT='YYYY-MM-DD HH:MI:SS'");
        OCIExecute($datestmt);
    }
    // test informix
    else if ($db_type == "informix") {
        if ($db_host == "") { $db = $db_name; }
        else { $db = $db_name."@".$db_host; }
        $link = ifx_connect($db, $db_user, $db_pass) or die(__('Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'));
    }
    // test postgres
    else if ( $db_type == "postgresql" ) {
        echo "<br>Trying to connect to ".$db_name;
        $link = $link = pg_connect((($db_host == "") ? "" : "host= ".$db_host." ").(($db_pass == "") ? "" : "password=".$db_pass." ")."dbname=".$db_name." user=".$db_user);
        echo "<br>$link";
        if (!$link) {
            echo "<br>Trying to connect to template1";
            $link = pg_connect((($db_host == "") ? "" : "host = ".$db_host." ").(($db_pass == "") ? "" : "password=".$db_pass." ")."dbname=template1 user=".$db_user) or die("Can't connect to db, nor to template1");
            echo "<br>Trying to create database ".$db_name;
            $result = pg_query($link, "CREATE DATABASE ".$db_name) or die("Can't create database ".$db_name);
            echo "<br>Database create, closing connection to template1";
            $link = pg_close($link) or die("Can't close database connection");
            echo "<br>Opening new connection to db ".$db_name;
            $link = pg_connect((($db_host == "" or $db_host == "localhost") ? "" : "host = ".$db_host." ").(($db_pass == "") ? "" : "password=".$db_pass." ")."dbname=".$db_name." user=".$db_user) or die("Can't connect to newly created db.");
        }
    }
    //** end test db access

    echo "<br><b>".__('Seems that You have a valid database connection!')."!</b><br><br>";
    // set default parameters for first time installation

    $profile = 1;
    $todo = 1;
    $lesezeichen = 0;
    $votum = 0;
    $forum = 1;
    $ressourcen = 1;
    if (function_exists('imap_open')) $quickmail = 2;
    else                              $quickmail = 1;
    $faxpath = '';
    $chat = 1;
    $chat_names = 0;
    $chat_time = 1;
    $projekte = 2;
    $dateien = getcwd().'/upload';
    $dat_rel = 'upload';
    $doc_path = 'docs';
    $att_path = 'attach';
    $adressen = 1;
    $sync = 1;
    $contacts_profiles = 1;
    $reminder = 0;
    $remind_freq = 15;
    $timecard = 2;
    $notes = 1;
    $protokoll=0;
    $rts = 1;
    $rts_duedate = 0;
    $rts_cust_acc = 0;
    $rts_cust_acc = 0;
    $rts_chef = 0;
    $logs = 1;
    $history_log = 1;
    $calltype = 'callto';
    $tr_hover = 1;
    $groupviewuserheader = 1;
    $cont_usrdef1 = __('Userdefined').' 1';
    $cont_usrdef2 = __('Userdefined').' 2';
    $sms_remind_service = 0; //enables sms or email reminder service for calendar
    $invitation = 1;

    // these values will be written in the config immediately
    $forum_tree_open = 1;
    $forum_notify = 1;
    $filemanager = 1;
    $file_path = getcwd().'/upload';
    $filemanager_notify = 1;
    $filemanager_versioning = 1;
    $support_pdf = 1;
    $support_html = 1;
    if (extension_loaded('gd')) $support_chart = 1;
    else                        $support_chart = 0;
    $acc_default = 1;
    $acc_write_default = 1;
    $alivefile = 'alive';
    $chatfile = 'chat.txt';
    $chatfreq = 10000;
    $alivefreq = 30000;
    $max_lines = 2000;
    $maxhits = 30;
    $session_time_limit = 0;
    $filter_maxhits = 0;
    $todo_option_accepted = 0;
    $calendar_dateconflicts_maxdays = 10;
    $calendar_dateconflicts_maxhits = 10;
    $show_links_module = 1;
    $default_visi = 0;
    $access_groups = 0;

    // mail variables
    if (isset($_SERVER["OS"]) && strpos(strtolower($_SERVER["OS"]), 'windows') !== false) {
        $mail_eol = '\r\n'; // end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)
        $mail_eoh = '\r\n'; // end of header line; e.g. \r\n (conform to RFC 2821 / 2822)
    }
    else if (isset($_SERVER["OS"]) && strpos(strtolower($_SERVER["OS"]), 'mac') !== false) {
        $mail_eol = '\r'; // end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)
        $mail_eoh = '\r'; // end of header line; e.g. \r\n (conform to RFC 2821 / 2822)
    }
    else {
        $mail_eol = '\n'; // end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)
        $mail_eoh = '\n'; // end of header line; e.g. \r\n (conform to RFC 2821 / 2822)
    }

    // send via mail() or socket
    $mail_mode = 0;// 0: use mail(); 1: use socket
    // SMTP account data (only needed in case of socket)
    $smtp_hostname  = "localhost"; // the real address of the SMTP mail server, you have access to (maybe localhost)
    $local_hostname = "hereiam";   // name of the local server to identify it while HELO procedure
                                   // may be transmitted to the receiver as content of the headers
    // Authentication?
    $mail_auth = 0;  //Authentication  0: no auth; 1: with POP before SMTP; 2: SMTP auth (via socket only!!)
    // fill out in case of authentication via POP before SMTP
    $pop_account  = "itsme"; // real username for POP before SMTP
    $pop_password = "mypw";  // password for this pop account
    $pop_hostname = "mypop.domain.net"; // the POP server
    //fill out in case of SMTP authentication
    $smtp_account  = "itsme"; // real username for SMTP auth
    $smtp_password = "mypw";  // password for this account

    $compatibility_mode = 0;
}
//  ...or update.
else {
    if ($setup == 'config') $sync = 1;
    if (is_readable("../../config.inc.php"))  include_once("../../config.inc.php");
    else if (is_readable("./config.inc.php")) include_once("./config.inc.php");
    include_once("./lib/lib.inc.php");
    constants_to_vars();
    // special hack for version 4.2 - we have to define the hili colours for the
    // first ... and last time, from now on they will exist in the config.inc.php
    if ((ereg("4.1",$version) or ereg("4.0",$version) or ereg("3.3",$version) or
         ereg("3.2",$version) or ereg("3.1",$version) or ereg("3.0",$version) or
         ereg("2.4",$version) or ereg("2.3",$version) or $version == "2.2" or $version == "2.1"
         or $version == "2.0" or $version == "1.3" or $version == "1.2")) {
        $doc_path       = 'docs';
        $att_path       = 'attach';
        $calltype       = 'callto';
        $history_log    = '0';
        $filter_maxhits = '0';
        $bgcolor_mark   = '#E6DE90';
        $bgcolor_hili   = '#FFFFFF';
        $support_pdf    = 0;
        $support_html   = 0;
        $support_chart  = 0;
        if (!$default_size) $default_size = '60';
    }
    $filemanager = (strlen($dateien) > 0);
    $file_path = $dateien;
    $calendar_dateconflicts_maxdays = 10;
    $calendar_dateconflicts_maxhits = 10;
    $show_links_module = 1;
    $compatibility_mode = 0;
}

// assign currency symbol
if (!isset($cur_symbol) || $cur_symbol == '') $cur_symbol = "&euro;";

// for update in version 3.0: set $maxhits parameter
if (!$maxhits) $maxhits = 30;
// for versions < 3: set events_par to 1
if (eregi("2.4", $version)) $events_par = 1;
// for version 4.0: set calendar to on
if (!isset($calendar)) $calendar = 1;

echo "<p style='font-size: 10pt;'>\n";
echo __('Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>')."\n";
echo "<br><b>".__('Install component: insert a 1, otherwise keep the field empty')."</b> <br><br> \n";

// Installation parameter
$_SESSION['version'] =& $version;
$_SESSION['langua'] =& $langua;
// db Parameter
$_SESSION['db_type'] =& $db_type;
$_SESSION['db_host'] =& $db_host;
$_SESSION['db_user'] =& $db_user;
$_SESSION['db_pass'] =& $db_pass;
$_SESSION['db_name'] =& $db_name;
// chat Parameter
$_SESSION['alivefile'] =& $alivefile;
$_SESSION['chatfile'] =& $chatfile;
$_SESSION['chatfreq'] =& $chatfreq;
$_SESSION['alivefreq'] =& $alivefreq;
// some other, for the installation not visible parameters
$_SESSION['forum_tree_open'] =& $forum_tree_open;
$_SESSION['forum_notify'] =& $forum_notify;
$_SESSION['filemanager_notify'] =& $filemanager_notify;
$_SESSION['filemanager_versioning'] =& $filemanager_versioning;
$_SESSION['events_par'] =& $events_par;
$_SESSION['maxhits'] =& $maxhits;
$_SESSION['session_time_limit'] =& $session_time_limit;
$_SESSION['filter_maxhits'] =& $filter_maxhits;
$_SESSION['contacts_nolink'] =& $contacts_nolink;
$_SESSION['sms_remind_service'] =& $sms_remind_service;
$_SESSION['max_lines'] =& $max_lines;
$_SESSION['support_pdf'] =& $support_pdf;
$_SESSION['support_html'] =& $support_html;
$_SESSION['support_chart'] =& $support_chart;
$_SESSION['acc_default'] =& $acc_default;
$_SESSION['default_size'] =& $default_size;
$_SESSION['show_links_module'] =& $show_links_module;
$_SESSION['compability_mode'] =& $compability_mode;

// mail Parameter
$_SESSION['mail_send_arg'] =& $mail_send_arg;
$_SESSION['mail_eol'] =& $mail_eol;
$_SESSION['mail_eoh'] =& $mail_eoh;
$_SESSION['mail_mode'] =& $mail_mode;
$_SESSION['smtp_hostname'] =& $smtp_hostname;
$_SESSION['local_hostname'] =& $local_hostname;
$_SESSION['mail_auth'] =& $mail_auth;
$_SESSION['pop_account'] =& $pop_account;
$_SESSION['pop_password'] =& $pop_password;
$_SESSION['pop_hostname'] =& $pop_hostname;
$_SESSION['smtp_account'] =& $smtp_account;
$_SESSION['smtp_password'] =& $smtp_password;

// path parameters
$_SESSION['doc_path'] =& $doc_path;
$_SESSION['att_path'] =& $att_path;

// filemanager parameters
$_SESSION['dat_crypt'] =& $dat_crypt;

// calendar parameters
$_SESSION['calendar_dateconflicts_maxdays'] =& $calendar_dateconflicts_maxdays;
$_SESSION['calendar_dateconflicts_maxhits'] =& $calendar_dateconflicts_maxhits;

// begin form and define next step
echo "<form action='setup.php' method=post>\n";
echo "<input type='hidden' name='step' value='2b'>\n";
echo "<input type=hidden name='".session_name()."' value='".session_id()."'>\n";

// modules
echo "<table border='1' cellspacing='1' cellpadding='2' bgcolor='#D0D0D0' width='500'>\n";
echo "<tr><td colspan='2'><b>Modules:</td></tr>\n";

echo "<tr><td>".__('Calendar').":</td><td><input type='text' size=1 maxlength=1 name='calendar' value='$calendar'></td></tr>\n ";
//echo "<tr><td>&nbsp;</td><td><input type='text' size=1 maxlength=1 name='invitation' value='$invitation'>".__('0 Date assignment by chief,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitation System')."</td></tr>\n ";
echo "<tr><td>".__('Time card').":</td><td><input type='text' size=1 maxlength=1 name='timecard' value='$timecard'> ".__('1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief')."</td></tr>\n ";
echo "<tr><td>".__('Projects').":</td><td><input type='text' size=1 maxlength=1 name='projekte' value='$projekte'> ".__('1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)')."</td></tr>\n ";
echo "<tr><td>".__('Contact manager').":</td><td><input type='text' size=1 maxlength=1 name='adressen' value='$adressen'></td></tr>\n ";
echo "<tr><td>".__('Profiles for contacts').":</td><td><input type='text' size=1 maxlength=1 name='contacts_profiles' value='$contacts_profiles'></td></tr>\n ";
//echo "<tr><td>".__('Group views').":</td><td><input type='text' size=1 maxlength=1 name='profile' value='$profile'></td></tr>\n";
//echo "<tr><td>".__('Resources').":</td><td><input type='text' size=1 maxlength=1 name='ressourcen' value='$ressourcen'></td></tr>\n";
echo "<tr><td>".__('Todo lists').":</td><td><input type='text' size=1 maxlength=1 name='todo' value='$todo'></td></tr>\n";
echo "<tr><td>".__('Forum').":</td><td><input type='text' size=1 maxlength=1 name='forum' value='$forum'></td></tr>\n";
echo "<tr><td>".__('Notes').":</td><td><input type='text' size=1 maxlength=1 name='notes' value='$notes'>\n ";
echo "<tr><td>".__('File management').": </td><td><input type='text' size=1 name='filemanager' value='$filemanager'> </td></tr>\n ";
echo "<tr><td>".__('Voting system').":</td><td><input type='text' size=1 maxlength=1 name='votum' value='$votum'></td></tr>\n";
echo "<tr><td>".__('Bookmarks').":</td><td><input type='text' size=1 maxlength=1 name='lesezeichen' value='$lesezeichen'></td></tr>\n ";
echo "<tr><td>".__('Chat').":</td><td><input type='text' size=1 maxlength=1 name='chat' value='$chat'></td></tr>\n ";
echo "<tr><td>".__('Name format in chat list').":</td><td><input type='text' size=1 maxlength=1 name='chat_names' value='$chat_names'> ".__('0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name')."</td></tr>\n ";
echo "<tr><td>".__('Timestamp for chat messages').":</td><td><input type='text' size=1 maxlength=1 name='chat_time' value='$chat_time'></td></tr>\n ";
// Logging?
echo "<tr><td>".__('Track user login/logout').": </td><td><input type='text' size=1 maxlength=1 name='logs' value='$logs'>&nbsp;</td></tr>\n";
// history
echo "<tr><td>".__('Log history of records').": </td><td><input type='text' size=1 maxlength=1 name='history_log' value='$history_log'>&nbsp;</td></tr>\n";
// mail options
echo "<tr><td>".__('Mail').":</td><td><input type='text' size=1 maxlength=1 name='quickmail' value='$quickmail'> 1: ".__('send mail');
if (function_exists('imap_open')) echo " 2: ".__(' only,<br> &nbsp; &nbsp; full mail client');
echo "</td></tr>\n ";
echo "<tr><td>Fax:</td><td><input type='text' size=20 name='faxpath' value='$faxpath'><br>".__('Path to sendfax')." (".__('no fax option: leave blank').")</td></tr>\n ";
echo "<tr><td>".__('Reminder').":</td><td><input type='text' size=1 maxlength=1 name='reminder' value='$reminder'> ".__('1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.')."</td></tr>\n";
echo "<tr><td>".__('Alarm').": </td><td><input type='text' size=2 name='remind_freq' value='$remind_freq'> ".__('max. minutes before the event')."</td></tr>\n ";

/*
// enables sms reminder service
echo "<tr><td>$inst_text22e: </td><td><input type='text' size=2 name='sms_remind_service' value='$sms_remind_service'> $inst_text22f</td></tr>\n";
*/

// File management
#echo "<tr><td rowspan='2'>".__('File management').":</td>\n";
#echo "<td><input type='text' size='30' name='dat_rel' value='$dat_rel'><br>".__('Name of the directory where the files will be stored<br>( no file management: empty field)')."</td></tr>\n";
#echo "<tr><td><input type='text' size='30' name='dateien' value='$dateien'><br>".__('absolute path to this directory (no files = empty field)')." </td></tr>\n";

echo "<tr><td>".__('Help desk').":</td><td><input type='text' size=1 maxlength=1 name='rts' value='$rts'> ".__('Help Desk Manager / Trouble Ticket System')."</td></tr>\n ";
echo "<tr><td>".__('RT Option: Customer can set a due date').":</td><td><input type='text' size=1 maxlength=1 name='rts_duedate' value='$rts_duedate'></td></tr>\n ";
echo "<tr><td>".__('RT Option: Customer Authentification').":</td><td><input type='text' size=1 maxlength=1 name='rts_cust_acc' value='$rts_cust_acc'> ".__('0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email')."</td></tr>\n ";
echo "<tr><td>".__('RT Option: Assigning request').":</td><td><input type='text' size=1 maxlength=1 name='rts_chef' value='$rts_chef'> ".__('0: by everybody, 1: only by persons with status chief')."</td></tr>\n ";
echo "<tr><td>".__('Email Address of the support').":</td><td><input type='text' size=30 name='rts_mail' value='$rts_mail'></td></tr>\n ";
echo "<tr><td>".__('Host-Path').":</td><td><input type='text' size=30 name='host_path' value='".strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))).'://'.$_SERVER['SERVER_NAME'].'/'."'></td></tr>\n ";
echo "<tr><td>".__('Installation directory').":</td><td><input type='text' size=30 name='installation_dir' value='".substr($_SERVER['PHP_SELF'], 1, strrpos($_SERVER['PHP_SELF'], '/'))."'></td></tr>\n ";

// Submit button
echo "<tr><td>&nbsp;</td><td><input type='submit' value='".__('go')."'></td></tr></table>\n";

// carry old parameters - the value has to be known because if the old value is set to zero and new to 1 the tbla has to be created
echo "<input type=hidden name='profile_old' value='$profile'>\n";
echo "<input type=hidden name='todo_old' value='$todo'>\n";
echo "<input type=hidden name='forum_old' value='$forum'>\n";
echo "<input type=hidden name='votum_old' value='$votum'>\n";
echo "<input type=hidden name='lesezeichen_old' value='$lesezeichen'>\n";
echo "<input type=hidden name='ressourcen_old' value='$ressourcen'>\n";
echo "<input type=hidden name='adressen_old' value='$adressen'>\n";
echo "<input type=hidden name='quickmail_old' value='$quickmail'>\n";
echo "<input type=hidden name='projekte_old' value='$projekte'>\n";
echo "<input type=hidden name='timecard_old' value='$timecard'>\n";
echo "<input type=hidden name='notes_old' value='$notes'>\n";
echo "<input type=hidden name='dateien_old' value='$dateien'>\n";
echo "<input type=hidden name='groups_old' value='".$groups."'>\n";
echo "<input type=hidden name='rts_old' value='$rts'>\n";
echo "<input type=hidden name='logs_old' value='$logs'>\n";
echo "<input type=hidden name='history_log_old' value='$history_log'>\n";
echo "<input type=hidden name='contacts_profiles_old' value='$contacts_profiles'>\n";
echo "<input type=hidden name='file_path' value='$file_path'>\n";
echo "<input type=hidden name='sync_old' value='$sync'>\n";
echo "<input type=hidden name='sync' value='$sync'>\n";

// ...  and the layout/system parameters as well.
echo "<input type=hidden name='tagesanfang' value='$tagesanfang'>\n";
echo "<input type=hidden name='tagesende' value='$tagesende'>\n";
echo "<input type=hidden name='groupviewuserheader' value='$groupviewuserheader'>\n";
echo "<input type=hidden name='bgcolor1' value='$bgcolor1'>\n";
echo "<input type=hidden name='bgcolor2' value='$bgcolor2'>\n";
echo "<input type=hidden name='bgcolor3' value='$bgcolor3'>\n";
echo "<input type=hidden name='terminfarbe' value='$terminfarbe'>\n";
echo "<input type=hidden name='bgcolor_mark' value='$bgcolor_mark'>\n";
echo "<input type=hidden name='bgcolor_hili' value='$bgcolor_hili'>\n";
echo "<input type=hidden name='logo' value='$logo'>\n";
echo "<input type=hidden name='hp_url' value='$hp_url'>\n";
echo "<input type=hidden name='cur_symbol' value='$cur_symbol'>\n";
echo "<input type=hidden name='tr_hover' value='$tr_hover'>\n";
echo "<input type=hidden name='pw_crypt' value='".$pw_crypt."'>\n";
echo "<input type=hidden name='pw_change' value='".$pw_change."'>\n";
echo "<input type=hidden name='mail_new_event' value='$mail_new_event'>\n";
echo "<input type=hidden name='ldap' value='$ldap'>\n";
echo "<input type=hidden name='calltype' value='$calltype'>\n";
echo "<input type=hidden name='groups' value='".$groups."'>\n";
echo "<input type=hidden name='acc_all_groups' value='$acc_all_groups'>\n";
echo "<input type=hidden name='db_prefix' value='".$db_prefix."'>\n";
echo "<input type=hidden name='login_kurz' value='".$login_kurz."'>\n";
echo "<input type=hidden name='timezone' value='$timezone'>\n";
echo "<input type=hidden name='skin' value='$skin'>\n";
echo "<input type=hidden name='cont_usrdef1' value='$cont_usrdef1'>\n";
echo "<input type=hidden name='cont_usrdef2' value='$cont_usrdef2'>\n";
echo "</form>\n";

// write in these values in case the option is 'configure' -> step 1 will be left out.
$_SESSION['db_type'] =& $db_type;
$_SESSION['langua'] =& $langua;
$_SESSION['setup'] =& $setup;
$_SESSION['default_visi'] =& $default_visi;
$_SESSION['access_groups'] =& $access_groups;
$_SESSION['groups'] =& $groups;

?>
