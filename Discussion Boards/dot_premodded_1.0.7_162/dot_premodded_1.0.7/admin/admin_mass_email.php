<?php
/***************************************************************************
*                             admin_mass_email.php
*                              -------------------
*     begin                : Thu May 31, 2001
*     copyright            : (C) 2001 The phpBB Group
*     email                : support@phpbb.com
*
*     $Id: admin_mass_email.php,v 1.15.2.7 2003/05/03 23:24:01 acydburn Exp $
*
****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['General']['Mass_Email'] = $filename;
	
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
// Increase maximum execution time in case of a lot of users, but don't complain about it if it isn't
// allowed.
//
@set_time_limit(1200);

$message = '';
$subject = '';
$designated = '';
$cancel_email = '';

//
// Do the job ...
//

// start mod email to designated groups
// first, find out whether the user has just designated the groups ($designated) or has canceled out of designating groups ($cancel_email) or done neither...
if ( isset($HTTP_POST_VARS['designated']) )
{
	$designated = TRUE;
}

if ( isset($HTTP_POST_VARS['cancel_email']) )
{
	$cancel_email = TRUE;
}

// now, if the user has not just designated the groups (or canceled out), do the regular thing of asking for the email message and subject...
// so, added the !$designate and !$cancel part of the next if clause
// end mod email to designated groups
if ( isset($HTTP_POST_VARS['submit']) && !$designated && !$cancel_email )

{
	$subject = htmlspecialchars(stripslashes(trim($HTTP_POST_VARS['subject'])));
	$message = htmlspecialchars(stripslashes(trim($HTTP_POST_VARS['message'])));
	
	$error = FALSE;
	$error_msg = '';

	if ( empty($subject) )
	{
		$error = true;
		$error_msg .= ( !empty($error_msg) ) ? '<br />' . $lang['Empty_subject'] : $lang['Empty_subject'];
	}

	if ( empty($message) )
	{
		$error = true;
		$error_msg .= ( !empty($error_msg) ) ? '<br />' . $lang['Empty_message'] : $lang['Empty_message'];
	}

// start mod email to designated groups
	if ($error_msg == '')
	{

	// here we allow the user to specify which groups to email the post to...
		$page_title = $lang['Designated_groups_CP'];
		include('./page_header_admin.'.$phpEx);

		$template->assign_vars(array(

		'L_DESIGNATED_GROUPS_CP' => $lang['Designated_groups_CP'],
		'L_DESIGNATED_GROUPS_CP_EXPLAIN' => $lang['Designated_groups_CP_explain'],
		'L_GROUPS' => $lang['Usergroups'],
		'L_SELECT' => $lang['Select'],
		'L_DESIGNATE' => $lang['Send_email'],
		'L_CANCEL_EMAIL' => $lang['Cancel_email'],
		'L_MARK_ALL_GROUPS' => $lang['Mark_all_groups'],
		'L_UNMARK_ALL_GROUPS' => $lang['Unmark_all_groups'],

		'S_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="subject" value="' . $subject . '" /><input type="hidden" name="message" value="' . $message . '" />',
		'S_DESIGNATED_GROUPS_CP_ACTION' => append_sid("admin_mass_email.$phpEx"))
		);

		$template->set_filenames(array(
		'body' => 'designated_groups_cp_body.tpl')
		);

		$sql = "SELECT group_id, group_name, group_notify
		FROM " . GROUPS_TABLE . "
		WHERE group_single_user = 0
		ORDER BY group_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Cannot find group info', '', __LINE__, __FILE__, $sql);
		}

		// feed the template the information for the 'all users' row...
		$group_id = -1;
		$group_name = $lang['All_users'];
		$group_notify = FALSE;

		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('listrow', array(
		'ROW_COLOR' => '#' . $row_color,
		'ROW_CLASS' => $row_class,

		'S_MARK_ID' => $group_id,
		'S_DEFAULT_DESIGNATED_GROUPS' => ( $group_notify ) ? 'checked="checked"' : '',

		'U_GROUP_NAME' => $group_name)
		);

		// now feed the template with info for the other groups (for the remaining rows)
		while ( $row = $db->sql_fetchrow($result) )
		{
			$group_id = $row['group_id'];
			$group_name = $row['group_name'];
			$group_notify = ( $row['group_notify'] == 1 ) ? TRUE : FALSE;

			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('listrow', array(
			'ROW_COLOR' => '#' . $row_color,
			'ROW_CLASS' => $row_class,

			'S_MARK_ID' => $group_id,
			'S_DEFAULT_DESIGNATED_GROUPS' => ( $group_notify ) ? 'checked="checked"' : '',

			'U_GROUP_NAME' => $group_name)
			);
		}

		$template->pparse('body');
		include('./page_footer_admin.'.$phpEx);

		// ok, the template is all drawn.  When it comes back, 'designated' will be in the hidden field so $designated will be true and the rest of the notification is handled below where the if ($designated) statement is.
	}
}

// if this is a post that is getting emailed and the user has just designated the groups to be emailed, do the user notifiation and then go back to usual redirect...

if ($designated)
{

	// first, let's recover the subject and message
	$subject = stripslashes(trim($HTTP_POST_VARS['subject']));
	$message = stripslashes(trim($HTTP_POST_VARS['message']));

	// next step...update the group notify information...
	$mark_list = ( !empty($HTTP_POST_VARS['mark']) ) ? $HTTP_POST_VARS['mark'] : 0;
	if ( isset($mark_list) && !is_array($mark_list) )
	{
		// Set to empty array instead of '0' if nothing is selected.
		$mark_list = array();
	}

	// now check to see if 'All Users' was selected; if so, set $group_id = -1 and skip 
	// the part that resets the group notify columns
	if ($mark_list[0] == -1)
	{
		$group_id = -1;
	}
	else
	{
		// 'All Users' was not selected, so now we zero out the group_notify column and then set that column to 1 
		// for whichever groups the admin or mod just selected...
		$sql = "UPDATE " . GROUPS_TABLE . "
		SET group_notify = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not reset group_notify to 0', '', __LINE__, __FILE__, $sql);
		}

		$group_id = 0;
		if (count($mark_list)>0)
		{
			$sql = "UPDATE " . GROUPS_TABLE . "
			SET group_notify = 1
			WHERE group_id IN (" . implode(',',$mark_list) .")";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update group_notify status', '', __LINE__, __FILE__, $sql);
			}
		}
	}

			// get the banned list (code borrowed from the topic reply code in the original user notification text)...note that, like the original phpbb code, this only checks banned user_ids and not other banned items like banned emails
			$sql = "SELECT ban_userid
			FROM " . BANLIST_TABLE;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain banlist', '', __LINE__, __FILE__, $sql);
			}

			$user_id_sql = '';
			while ($row = $db->sql_fetchrow($result))
			{
				if (isset($row['ban_userid']) && !empty($row['ban_userid']))
				{
					$user_id_sql .= ', ' . $row['ban_userid'];
				}
			}
			// done getting banned list of user_ids

			$sql = ( $group_id != -1 ) ? "SELECT DISTINCT u.user_id, u.user_email, u.user_lang, u.username
			FROM " . USERS_TABLE . " AS u, " . USER_GROUP_TABLE . " AS ug, " . GROUPS_TABLE . " AS g
			WHERE u.user_id = ug.user_id AND u.user_active = 1 AND u.user_id NOT IN (" . ANONYMOUS . $user_id_sql . ") 
			AND ug.group_id = g.group_id AND g.group_notify = 1" : "SELECT user_id, user_email, user_lang, username FROM " . USERS_TABLE . " 
			WHERE user_active = 1 AND user_id NOT IN (" . ANONYMOUS . $user_id_sql . ")";
			
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not select group members to email', '', __LINE__, __FILE__, $sql);
		}

// end mod email to designated groups (but note that this mod also commented out the next block of code)...

//	$group_id = intval($HTTP_POST_VARS[POST_GROUPS_URL]);
//
//	$sql = ( $group_id != -1 ) ? "SELECT u.user_email FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug WHERE ug.group_id = $group_id AND ug.user_pending <> " . TRUE . " AND u.user_id = ug.user_id" : "SELECT user_email FROM " . USERS_TABLE;
//	if ( !($result = $db->sql_query($sql)) )
//	{
//		message_die(GENERAL_ERROR, 'Could not select group members', '', __LINE__, __FILE__, $sql);
//	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		$bcc_list = array();
		do
		{
			$bcc_list[] = $row['user_email'];
		}
		while ( $row = $db->sql_fetchrow($result) );

		$db->sql_freeresult($result);
	}
	else
	{
		$message = ( $group_id != -1 ) ? $lang['Group_not_exist'] : $lang['No_such_user'];

		$error = true;
		$error_msg .= ( !empty($error_msg) ) ? '<br />' . $message : $message;
	}

	if ( !$error )
	{
		include($phpbb_root_path . 'includes/emailer.'.$phpEx);

		//
		// Let's do some checking to make sure that mass mail functions
		// are working in win32 versions of php.
		//
		if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
		{
			$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions
			// since php's are broken by default
			$board_config['smtp_delivery'] = 1;
			$board_config['smtp_host'] = @$ini_val('SMTP');
		}

		$emailer = new emailer($board_config['smtp_delivery']);
	
		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);

			// start mod email to designated groups...start to chunk emails into batches ,..the following block of code
	  // chunks each email with more than 100 users into multiple identical emails, each with
	// no more than 100 bccs...this is useful if the smtp server for the forum does not allow more than a 
	// certain number of addressees per email (a lot of smtp servers limit the number of email addressees to 100).
	// This chunking takes a fair bit of time, so if you don't need this feature you should turn it off.
	// If you want to turn this feature off, change $chunk = TRUE to $chunk = FALSE .
	// If you want to change the maximum number of bccs, change '100' to any limit you want
	$chunk = TRUE;
	$max_per_batch = 100;
	$number_bccs = count($bcc_list);
	$number_batches = ( $chunk ) ? max( 1, ceil($number_bccs/$max_per_batch) ) : 1;
	for ($j = 0; $j < $number_batches; $j++)
	{
		$start_bcc = $j * $max_per_batch;
		// the next line sets final_bcc number for the batch at $start_bcc for the batch plus the number of bccs in the batch;
		// the number in the batch is the lesser of the remaining bccs to be sent and the max_per_batch (see the min function below...)
		// of course, if $chunk is turned off (set to FALSE) this will assure that the entire thing is done in one batch.
		( $chunk ) ? $final_bcc = $start_bcc + min( $number_bccs - $start_bcc, $max_per_batch ) : $final_bcc = $number_bccs;
		// end of chunking part (for now)
		for ($i = $start_bcc; $i < $final_bcc; $i++)
	// end mod email to designated groups
		{
			$emailer->bcc($bcc_list[$i]);
		}

		$email_headers = 'X-AntiAbuse: Board servername - ' . $board_config['server_name'] . "\n";
		$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
		$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
		$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

		$emailer->use_template('admin_send_email');
		// start mod email to designated groups (and end mod too)...commented out the next line so the 'To' addressee will be 'mail list' 
            // instead of the board address (so the board doesn't receive a zillion silly emails)...this is the same way that regular email reply
            // notifications are alrady handled in a clean phpbb setup
            //		$emailer->email_address($board_config['board_email']);
		$emailer->set_subject($subject);
		$emailer->extra_headers($email_headers);

		$emailer->assign_vars(array(
			'SITENAME' => $board_config['sitename'], 
			'BOARD_EMAIL' => $board_config['board_email'], 
			'MESSAGE' => $message)
		);
		$emailer->send();
		$emailer->reset();
	}
// start mod email to designated groups (and end mod too)...added the bracket in the preceding line to close the new for statement

		// start mod email to designated groups
// commented out the old code that gave a screen saying 'email sent' but just sat there afterwards, and then replaced that code
// with code that automatically takes you back to the ACP after 3 seconds...
//		message_die(GENERAL_MESSAGE, $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'],  '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>'));
	$template->assign_vars(array(
	'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("index.$phpEx?pane=right") . '">')
	);
	$message = $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'],  '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
// end mod email to designated groups
	}
}	

// start mod email to designated groups
// otherwise, if this is a post that was getting emailed but the user has canceled out the email, just go to the usual redirect...
if ($cancel_email)
{
	$template->assign_vars(array(
	'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("index.$phpEx?pane=right") . '">')
	);
	$message = $lang['Email_cancelled'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'],  '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
// end mod email to designated group

if ( $error )
{
	$template->set_filenames(array(
		'reg_header' => 'error_body.tpl')
	);
	$template->assign_vars(array(
		'ERROR_MESSAGE' => $error_msg)
	);
	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
}

// start mod email to designated groups... commented out the code that finds all groups, since we don't use that way of selecting groups anymore
// Initial input of email...
// note that if it gets down here, the user hasn't hit submit yet so this just paints the normal initial email screen
//
// Initial selection
//
// $sql = "SELECT group_id, group_name
// 	FROM ".GROUPS_TABLE . "
// 	WHERE group_single_user <> 1";
// if ( !($result = $db->sql_query($sql)) )
// {
// 	message_die(GENERAL_ERROR, 'Could not obtain list of groups', '', __LINE__, __FILE__, $sql);
// }
// 
// $select_list = '<select name = "' . POST_GROUPS_URL . '"><option value = "-1">' . $lang['All_users'] . '</option>';
// if ( $row = $db->sql_fetchrow($result) )
// {
// 	do
// 	{
// 		$select_list .= '<option value = "' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
// 	}
// 	while ( $row = $db->sql_fetchrow($result) );
// }
// $select_list .= '</select>';
// end mod email to designated groups

//
// Generate page
//
include('./page_header_admin.'.$phpEx);

$template->set_filenames(array(
	'body' => 'admin/user_email_body.tpl')
);

$template->assign_vars(array(
	'MESSAGE' => $message,
	'SUBJECT' => $subject, 

	'L_EMAIL_TITLE' => $lang['Email'],
	'L_EMAIL_EXPLAIN' => $lang['Mass_email_explain'],
	'L_COMPOSE' => $lang['Compose'],
	
	'L_EMAIL_SUBJECT' => $lang['Subject'],
	'L_EMAIL_MSG' => $lang['Message'],
	'L_EMAIL' => $lang['Email'],
	'L_NOTICE' => $notice,

	'S_USER_ACTION' => append_sid('admin_mass_email.'.$phpEx),
	'S_GROUP_SELECT' => $select_list)
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>