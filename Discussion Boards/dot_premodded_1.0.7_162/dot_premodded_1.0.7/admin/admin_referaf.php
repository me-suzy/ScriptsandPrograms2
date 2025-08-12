<?php
/***************************************************************************
*     copyright            : (C) 2004 Triumvirate Studios
*     email                : livewire@livewirerpg.net
*
****************************************************************************/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Refer a Friend']['Settings'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_referaf_body.tpl'
	)
);

include('./page_header_admin.'.$phpEx);

if(isset($_POST['referafvalues']))
{
$sitename = $_POST['sitename'];
$siteaddress = $_POST['siteaddress'];
$thanks_message = $_POST['thanks_message'];
$mail_message = $_POST['mail_message'];
$psql = "TRUNCATE TABLE phpbb_raf";
if ( !($result = $db->sql_query($psql)) )
{
	message_die(GENERAL_ERROR, 'Cannot do that.', '', 

__LINE__, __FILE__, $psql);
}
$sql = "INSERT INTO phpbb_raf  SET sitename = '$sitename', siteaddress = '$siteaddress', thanks_message = '$thanks_message', mail_message = '$mail_message'";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Can\'t Insert', '', 

__LINE__, __FILE__, $sql);
}
$message = "Updated values.  You're all set.";
message_die(GENERAL_MESSAGE, $message);
}
else
{
	$query = "SELECT * FROM phpbb_raf";
	if ( !($result = $db->sql_query($query)) )
{
	message_die(GENERAL_ERROR, 'No Data, eek!', '', 

__LINE__, __FILE__, $query);
}
while($row = $db->sql_fetchrow($result))
{
	$thanks_message = $row['thanks_message'];
	$sitename = $row['sitename'];
	$siteaddress = $row['siteaddress'];
	$mail_message = $row['mail_message'];
	$template->assign_vars(array(
		'V_TMESSAGE' => $thanks_message,
		'V_SITENAME' => $sitename,
		'V_SITEADDRESS' => $siteaddress,
		'V_MMESSAGE' => $mail_message)
	);
}
	$template->assign_vars(array(
		'L_REFERAF' => $lang['ReferAF'],
		'L_THANKSMESS' => $lang['Thanks_Message'],
		'L_MAILMESS' => $lang['Mail_Message'],
		'L_SITENAME' => $lang['Site_Name'],
		'L_SITEURL' => $lang['Site_URL'],
		'S_ACTION' => append_sid('admin_referaf.'.$phpEx))
	);
	$template->pparse('body');
}
include('./page_footer_admin.'.$phpEx);

?>