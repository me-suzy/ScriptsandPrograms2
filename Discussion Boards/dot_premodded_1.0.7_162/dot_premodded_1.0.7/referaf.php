<?php
/***************************************************************************
 *                                referaf.php
 *                            -------------------
 *   begin                : Saturday, Dec 20, 2003
 *   copyright            : Triumvirate Studios
 *   $Id: $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
if (!$userdata['session_logged_in'])
{
    $redirect = ( isset($start) ) ? "&start=$start" : '';
    redirect(append_sid("login.$phpEx?redirect=referaf.$phpEx" . $redirect, true));
}
//
// End session management
//

//
// Start output of page
//
$page_title = $lang['Index'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

//
// Generate the page
//
$template->set_filenames(array(
	'body' => 'referaf_body.tpl')
);
if(isset($_POST['submitreferaf']))
{
$sql = "SELECT * FROM phpbb_raf";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error inserting data', '', __LINE__, __FILE__, $sql);
}
while($row = $db->sql_fetchrow($result))
{
$thanks_message = $row['thanks_message'];
$sitename = $row['sitename'];
$siteaddress = $row['siteaddress'];
$mail_message = $row['mail_message'];
}
$message = $thanks_message;
$message .= "<br /><br />Click <a href=\"index.php\">Here</a> to return to the index.";

$rafmessage = $_POST['rafmessage'];
$femail = $_POST['friendemail'];
$friendname = $_POST['friendname'];

$msg = $mail_message . "\n" . $rafmessage . "\n" . $userdata['username'] . " - " . $userdata['user_email'] . "\n" . $siteaddress;

$recipient = $femail;
$subject = "Referall to ".$sitename;
$mailheaders = "From:  ".$userdata['user_email']."\n";
$mailheaders .= "Reply-To: ".$userdata['user_email'];

mail($recipient, $subject, $msg, $mailheaders);
message_die(GENERAL_MESSAGE, $message);
}
else
{
	$template->assign_vars(array(
	'L_REFERAF' => $lang['ReferAF'],
	'L_FNAME' => $lang['FName'],
	'L_RAFMESS' => $lang['RAFMessage'],
	'L_FEMAIL' => $lang['FEMAIL'])
);


$template->assign_vars(array(
'S_ACTION' => append_sid("referaf.php"))
);
$template->pparse('body');
}
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
