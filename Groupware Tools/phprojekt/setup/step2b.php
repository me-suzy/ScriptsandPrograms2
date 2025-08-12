<?php

// step2b.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: step2b.php,v 1.22 2005/06/30 13:23:03 paolo Exp $

// check whether setup.php calls this script - authentication!
if (!defined('setup_included')) die('Please use setup.php!');


if ($setup == 'install') {
    $bgcolor1 = '#C2C2C2';
    $bgcolor2 = '#D5D5D5';
    $bgcolor3 = '#E0E0E0';
    $bgcolor_mark = '#E6DE90';
    $bgcolor_hili = '#FFFFFF';
    $terminfarbe = '#FFFFFF';
    $logo = 'img/logo.gif';
    $hp_url = 'http://www.phprojekt.com';
    $tagesanfang = 8;
    $tagesende = 20;
    $pw_crypt = 1;
    $pw_change = 1;
    $groups = 1;
    $acc_all_groups = 0;
    $ldap = 0;
    $cur_symbol = "&euro;";
    $login_kurz = 2;
    $mail_new_event = 1;
    $events_par = 1;
    $timezone = 0;
    $contacts_profiles = 1;
    $skin = 'default';
    $db_prefix = '';
    $default_size = 40;
}

// for versions < 3.1: set tr hoghlighting to enabled
if (eregi("2.4|3.0", $version)) $tr_hover = 1;


// begin form and define next step
echo "
<form name='theform' action='setup.php' method='post' onsubmit='return checkrootpw();'>
    <input type='hidden' name='step' value='3'>
    <input type='hidden' name='".session_name()."' value='".session_id()."'>

    <table border='1' cellspacing='1' cellpadding='2' bgcolor='#D0D0D0' width='500'>
";

// root password
if ($setup == 'install') {
    echo "
        <tr>
            <td>".__('Please define here a password for the administrator "root":')."</td>
            <td><input type='password' name='rootpass' value=''></td>
        </tr>\n";
}

// System parameter
echo "<tr><td colspan='2'><b>System</b></td></tr>\n";

// set group system and prefix - only at installation
if ($setup == 'install') {
    // prefix for database tables
    // own max length for postgres since pg cannot take any sequence < 16 chars
    ($db_type == 'postgresql') ? $prefix_ml = 8 : $prefix_ml = 10;
    echo "<tr><td>".__('Prefix for table names in db').": </td><td><input type='text' size='10' maxlength='$prefix_ml' name='db_prefix'></td></tr>\n";
    // group system mandatory!
    echo "<input type='hidden' name='groups' value='1'>\n";
}
else {
    echo "<input type='hidden' name='db_prefix' value='$db_prefix'>\n";
    echo "<input type='hidden' name='groups' value='$groups'>\n";
}
// option to release objects for all groups, e.g. contacts
echo "<tr><td>".__('Access for all groups').": </td><td><input type='text' size='1' maxlength='1' name='acc_all_groups' value='$acc_all_groups'> ".__('Option to release objects in all groups')."</td></tr>\n";


// define login name: 0 = last name, 1 = short name, 2 = loginname
if ($setup == "install") {
    echo "<tr><td><input type='hidden' name='login_kurz' value='2'>&nbsp;</td><td>&nbsp;</td></tr>\n";
}
// first time installation: only loginname possible, update/configure: optional
else {
    echo "<tr><td>".__('Login name').": </td><td><input type='text' size='1' maxlength='1'  name='login_kurz' value='$login_kurz'> ".__('0: last name, 1: short name, 2: login name')."</td></tr>\n";
}

// using ldap?
if (function_exists('ldap_connect')) {
    echo "<tr><td>".__('use LDAP').": </td><td><input type='text' size='1' maxlength=1 name='ldap' value='$ldap'></td></tr>\n";
}

//timezone difference server - user
echo "<tr><td>".__('Timezone').": </td><td><select name='timezone'>\n";
for ($i=-23; $i<24; $i++) {
    echo "<option value='$i'";
    if ($timezone == $i) echo " selected";
    echo ">$i</option>\n";
}
echo "</select> ".__('Timezone difference [h] Server - user')."</td></tr>\n";
// password Crypting
// pw crypting at installation ...
if ($setup == "install" and function_exists('crypt')) {
    echo "<tr><td>".__('Encrypt passwords').": </td><td><input type='text' size='1' maxlength='1' name='pw_crypt' value='$pw_crypt'></td></tr>\n";
}
// ... or pw crypting at update or config
else if ($setup <> "install" and function_exists('crypt') and !$pw_crypt) {
    echo "<tr><td>".__('Encrypt passwords').": </td><td><input type='text' size='1' maxlength='1' name='pw_crypt_update'></td></tr>\n";
}
// pw's already crypted? -> pw-crypt remains 1
else if ($setup <> "install" and $pw_crypt) {
    echo "<input type='hidden' name='pw_crypt' value='1'>\n";
}

// set user privilege level for changing pw
echo "<tr><td>".__('Password change').": </td><td><input type='text' size='1' maxlength='1' name='pw_change' value='$pw_change'> ".__('New passwords by the user - 0: none - 1: only random passwords - 2: choose own')."</td></tr>\n";
// if the chef inserts new events in user calendars, mail him the event
echo "<tr><td>".__('Notification on new event in others calendar').": </td><td><input type='text' size='1' maxlength='1' name='mail_new_event' value='$mail_new_event'>&nbsp;</td></tr>\n";

/* not used anymore
// set the character length of short names
if ($setup == "install" or !$kurz_len) {
    echo "<tr><td>$inst_text83: </td><td><input type='text' size='1' maxlength='1' name='kurz_len' value='$kurz_len'>&nbsp;</td></tr>\n ";
}
else {   echo "<input type=hidden name='kurz_len' value='$kurz_len'>\n"; }
*/

echo "
        <tr>
            <td>".__('Header groupviews').": </td>
            <td><select name='groupviewuserheader'>
                    <option value='0'".($groupviewuserheader!=1&&$groupviewuserheader!=2?" selected":"").">".__('name, F.')."</option>
                    <option value='1'".($groupviewuserheader==1?" selected":"").">".__('shortname')."</option>
                    <option value='2'".($groupviewuserheader==2?" selected":"").">".__('loginname')."</option>
                </select>
            </td>
        </tr>

        <tr>
            <td colspan='2'><b>Layout</b></td>
        </tr>
        <tr>
            <td>".__('Default size of form elements')."</td>
            <td><input type='text' name='default_size' value='$default_size' size='2' maxlength='2'></td>
        </tr>
        <tr>
            <td>".__('Currency symbol')."</td>
            <td><input style=\"font-family: Arial,helvetica,sans-serif;\" type='text' name='cur_symbol' value='$cur_symbol' size='2'>".__('current').": $cur_symbol</td>
        </tr>
        <tr>
            <td>".__('First hour of the day:')."</td>
            <td><input type='text' name='tagesanfang' value='$tagesanfang' size='2' maxlength='2'></td>
        </tr>
        <tr>
            <td>".__('Last hour of the day:')."</td>
            <td><input type='text' name='tagesende' value='$tagesende' size='2' maxlength='2'></td>
        </tr>
        <tr>
            <td>".__('First background color')."</td>
            <td><input type='text' name='bgcolor1' value='$bgcolor1' size='8' maxlength='7'></td>
        </tr>
        <tr>
            <td>".__('Second background color')."</td>
            <td><input type='text' name='bgcolor2' value='$bgcolor2' size='8'></td>
        </tr>
        <tr>
            <td>".__('Third background color')."</td>
            <td><input type='text' name='bgcolor3' value='$bgcolor3' size='8'></td>
        </tr>
        <tr>
            <td>".__('Color to mark rows')."</td>
            <td><input type='text' name='bgcolor_mark' value='$bgcolor_mark' size='8'></td>
        </tr>
        <tr>
            <td>".__('Color to highlight rows')."</td>
            <td><input type='text' name='bgcolor_hili' value='$bgcolor_hili' size='8'></td>
        </tr>
        <tr>
            <td>".__('company icon yes = insert name of image')."</td>
            <td><input type='text' name='logo' value='$logo' size='25'></td>
        </tr>
        <tr>
            <td>".__('URL to the homepage of the company')."</td>
            <td><input type='text' name='hp_url' value='$hp_url' size='25'></td>
        </tr>
        <tr>
            <td>".__('Highlight list records with mouseover')."</td>
            <td><input type='text' name='tr_hover' value='$tr_hover' size='1' maxlength='1'></td>
        </tr>
        <tr>
            <td>".__('Contact manager')."<br>".__('Name of userdefined field')." 1</td>
            <td>&nbsp;<br><input type='text' name='cont_usrdef1' value='$cont_usrdef1' size='12' maxlength='12'></td>
        </tr>
        <tr>
            <td>".__('Name of userdefined field')." 2</td>
            <td><input type='text' name='cont_usrdef2' value='$cont_usrdef2' size='12' maxlength='12'></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td><input type='submit' value='".__('go')."'></td>
        </tr>
    </table>
</form>
";

// js check on root pw
echo '
<script type="text/javascript">
<!--
document.theform.rootpass.focus();
function checkrootpw() {
    var field = document.theform.rootpass.value;
    if (field == "") {
        alert("'.__('Please enter a password!').'");
        document.theform.rootpass.focus();
        return false;
    }
    return true;
}
//-->
</script>
';


$_SESSION['profile'] =& $profile;
$_SESSION['todo'] =& $todo;
$_SESSION['forum'] =& $forum;
$_SESSION['votum'] =& $votum;
$_SESSION['chat'] =& $chat;
$_SESSION['chat_names'] =& $chat_names;
$_SESSION['chat_time'] =& $chat_time;
$_SESSION['lesezeichen'] =& $lesezeichen;
$_SESSION['ressourcen'] =& $ressourcen;
$_SESSION['adressen'] =& $adressen;
$_SESSION['quickmail'] =& $quickmail;
$_SESSION['projekte'] =& $projekte;
$_SESSION['timecard'] =& $timecard;
$_SESSION['pause'] =& $pause;
$_SESSION['notes'] =& $notes;
$_SESSION['dateien'] =& $dateien;
$_SESSION['groups'] =& $groups;
$_SESSION['faxpath'] =& $faxpath;
$_SESSION['reminder'] =& $reminder;
$_SESSION['remind_freq'] =& $remind_freq;
$_SESSION['dat_rel'] =& $dat_rel;
$_SESSION['dateien'] =& $dateien;
$_SESSION['dat_crypt'] =& $dat_crypt;
$_SESSION['dat_crypt_update'] =& $dat_crypt_update;
$_SESSION['calendar'] =& $calendar;
$_SESSION['invitation'] =& $invitation;
$_SESSION['rts'] =& $rts;
$_SESSION['rts_duedate'] =& $rts_duedate;
$_SESSION['rts_cust_acc'] =& $rts_cust_acc;
$_SESSION['rts_chef'] =& $rts_chef;
$_SESSION['rts_mail'] =& $rts_mail;
$_SESSION['contacts_profiles'] =& $contacts_profiles;
$_SESSION['logs'] =& $logs;
$_SESSION['history_log'] =& $history_log;
$_SESSION['calltype'] =& $calltype;
$_SESSION['acc_default'] =& $acc_default;
$_SESSION['acc_write_default'] =& $acc_write_default;
$_SESSION['todo_option_accepted'] =& $todo_option_accepted;
$_SESSION['installation_dir'] =& $installation_dir;
$_SESSION['host_path'] =& $host_path;
$_SESSION['profile_old'] =& $profile_old;
$_SESSION['todo_old'] =& $todo_old;
$_SESSION['forum_old'] =& $forum_old;
$_SESSION['votum_old'] =& $votum_old;
$_SESSION['lesezeichen_old'] =& $lesezeichen_old;
$_SESSION['ressourcen_old'] =& $ressourcen_old;
$_SESSION['adressen_old'] =& $adressen_old;
$_SESSION['quickmail_old'] =& $quickmail_old;
$_SESSION['projekte_old'] =& $projekte_old;
$_SESSION['timecard_old'] =& $timecard_old;
$_SESSION['notes_old'] =& $notes_old;
$_SESSION['dateien_old'] =& $dateien_old;
$_SESSION['groups_old'] =& $groups_old;
$_SESSION['rts_old'] =& $rts_old;
$_SESSION['logs_old'] =& $logs_old;
$_SESSION['history_log_old'] =& $history_log_old;
$_SESSION['contacts_profiles_old'] =& $contacts_profiles_old;
$_SESSION['skin'] =& $skin;
$_SESSION['file_path'] =& $file_path;
$_SESSION['filemanager'] =& $filemanager;
$_SESSION['sync'] =& $sync;
$_SESSION['sync_old'] =& $sync_old;

?>
