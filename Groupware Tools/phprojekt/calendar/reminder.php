<?php
// reminder.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: reminder.php,v 1.17 2005/07/22 18:32:55 paolo Exp $

$path_pre = '../';
$include_path = $path_pre.'lib/lib.inc.php';
include_once($include_path);
$include_path = $path_pre.'lib/email_getpart.inc.php';
include_once($include_path);

// page header
$remind_refresh = 15 * 60000;
$output = set_html_tag();
$output .= '
<head>
<title>'.__('Reminder').'</title>
';
if (isset($css_inc) && is_array($css_inc) && count($css_inc) > 0) {
    foreach ($css_inc as $css) {
        $output .= $css;
    }
}
$output .= '
<script type="text/javascript">
<!--
window.setTimeout(\'location.reload()\', '.$remind_refresh.');
//-->
</script>
<link rel="shortcut icon" href="/'.PHPR_INSTALL_DIR.'favicon.ico" />
</head>
';

$set_timezone      = isset($settings['timezone']) ? $settings['timezone'] : PHPR_TIMEZONE;
$set_reminder      = isset($settings['reminder']) ? $settings['reminder'] : PHPR_REMINDER;
$set_reminder_freq = isset($settings['remind_freq']) ? $settings['remind_freq'] : PHPR_REMIND_FREQ;

// *************************
// query calendar for events
// *************************

// define time and date
$now = (date('H') + $set_timezone) * 60 + date('i', mktime());
$str_now = substr('0'.(date('H') + $set_timezone).date('i', mktime()), -4);
$query = "SELECT event, anfang, ende
            FROM ".DB_PREFIX."termine
           WHERE an = '$user_ID'
             AND datum = '".date("Y-m-d")."'
             AND anfang >= '$str_now'
        ORDER BY anfang";
$res = db_query($query) or db_die();
$mess = '';
while ($row = db_fetch_row($res)) {
    $text = html_out(substr($row[0], 0, 16));
    // string has more than 16 characters? cut and insert '...'
    if (strlen($row[0]) > 16) $text .= '..';

    // add event
    $mess .= "
    <tr>
        <td>$row[1]-$row[2]: $text</td>
    </tr>
";
    $begin = substr($row[1], 0, 2) * 60 + substr($row[1], 2, 2);
    $now = (date('H') + $set_timezone)*60 + date('i', mktime());
    if ($set_reminder == '2' and ($begin <= ($now + $set_reminder_freq)) and ($begin > $now)) {
        $a = $begin - $now;
        $mess2 = "$row[0] ".__('Starts in')." $a ".__('minutes');
    }
}
// end event query

// *******************
// check for new mails
// *******************
$mail_list = '';
if ($reminder_mail && PHPR_QUICKMAIL == 2) {
    // set variables for mail_fetch
    $view_only = 1;
    $i = 0;
    // if no special account is given - loop over all mail accounts
    $query = "SELECT ID, von, accountname, hostname, type, username, password
                FROM ".DB_PREFIX."mail_account
               WHERE von = '$user_ID'
                 AND collect = 1";
    $res = db_query($query) or db_die();
    while ($row = db_fetch_row($res) and $i < 11) {
        include_once('../mail/mail_fetch.php');
        if (isset($mail_arr)) {
            foreach ($mail_arr as $m_subject=>$m_sender) {
                $mail_list .= "$m_subject ($m_sender)\n";
                $i++;
            }
        }
    }
    if ($i == 10) $mail_list .= '...';
}
// end check mail
// **************

// no events found for today?
if (!$mess) $mess = '    <tr><th>'.__('No events yet today').'.</th></tr>'."\n";

// if the alert option is on and there is en avent for alert or incoming mail, activate the alert box
if (($mess2 and $set_reminder == 2) or ($mail_list and $reminder_mail == 2)) {
    $message = '';
    if ($mess2)     $message .= "$mess2\\n\\n";
    if ($mail_list) $message .= __('New mail arrived');
    $output .= '<body style="margin:0px;background-color:'.PHPR_BGCOLOR3.
               ';" onload="self.focus();alert(\''.$message.'\')">'."\n";
}
// otherwise simply show the list in the window
else {
    $output .= '<body style="margin:0px;background-color:'.PHPR_BGCOLOR3.';">'."\n";
}

// write mail message
if ($mail_list) {
    $output .= '<div title="'.$mail_list.'">'.__('New mail arrived').'</div><br />'."\n";
}

// write events
$output .= '
<table cellpadding="0" cellspacing="0" border="0">
'.$mess.'
</table>

</body>
</html>
';

echo $output;

?>
