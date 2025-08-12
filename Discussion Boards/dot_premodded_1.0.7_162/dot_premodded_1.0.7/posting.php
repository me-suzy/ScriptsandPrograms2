<?php
/***************************************************************************
 *                                posting.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: posting.php,v 1.159.2.22 2004/07/11 16:46:16 acydburn Exp $
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
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//
// Check and set various parameters
//
$params = array('submit' => 'post', 'preview' => 'preview', 'designated' => 'designated', 'cancel_email' => 'cancel_email', 'include_text' => 'include_text', 'include_unauthorized_users' => 'include_unauthorized_users', 'delete' => 'delete', 'poll_delete' => 'poll_delete', 'poll_add' => 'add_poll_option', 'poll_edit' => 'edit_poll_option', 'mode' => 'mode');
while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = '';
	}
}

$confirm = isset($HTTP_POST_VARS['confirm']) ? true : false;

$params = array('forum_id' => POST_FORUM_URL, 'topic_id' => POST_TOPIC_URL, 'post_id' => POST_POST_URL);
while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? intval($HTTP_POST_VARS[$param]) : intval($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = '';
	}
}

switch ($mode) {
case 'newtopic':
        $secretkey = 'f' . $forum_id;
        break;
case 'reply':
        $secretkey = 't' . $topic_id;
        break;
case 'editpost':
        $secretkey = 'p' . $post_id;    
        break;                
}

$authkey = md5("lala" . $secretkey . "fopfop");
$authval = md5($HTTP_SERVER_VARS['HTTP_USER_AGENT'] . $secretkey . $HTTP_SERVER_VARS['REMOTE_ADDR']);  
$timekey = md5("time" . $secretkey);
$timeval = time();

# If this is a submit which doesn't jive with the above, turn it into a preview
if ($submit && (!isset($HTTP_POST_VARS[$authkey])
                || $HTTP_POST_VARS[$authkey] != $authval
                || !isset($HTTP_POST_VARS[$timekey])
                || $HTTP_POST_VARS[$timekey] > time() - 5))
{
        $submit = false;
        $preview = true;
}

$refresh = $preview ||$orig_word = $replacement_word = array(); $poll_add || $poll_edit || $poll_delete;

//
// Set topic type
//
$topic_type = ( in_array($topic_type, array(POST_NORMAL, POST_STICKY, POST_ANNOUNCE)) ) ? $topic_type : POST_NORMAL;

//
// If the mode is set to topic review then output
// that review ...
//
if ( $mode == 'topicreview' )
{
	require($phpbb_root_path . 'includes/topic_review.'.$phpEx);

	topic_review($topic_id, false);
	exit;
}
else if ( $mode == 'smilies' )
{
	generate_smilies('window', PAGE_POSTING);
	exit;
}

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_POSTING);
init_userprefs($userdata);
//
// End session management
//

// start mod email to designated groups
// if this is a post that is getting emailed and the user has just designated the groups to be emailed, do the user notifiation and then go back to usual redirect...

		if ($designated)
		{

				// first step...update the group notify information...
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

				// now get information to use for the email itself...

				$error_msg = '';
				$post_data = array();
				$mail_to_enabled_groups = TRUE;
				$notify_user = ( !empty($HTTP_POST_VARS['notify']) ) ? TRUE : 0;

				$sql = "SELECT t.forum_id, t.topic_id, t.topic_title, p.post_id, p.post_username, pt.post_subject, pt.post_text, u.username, u.user_id
				FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . POSTS_TEXT_TABLE . " pt, " . USERS_TABLE . " u
				WHERE p.post_id = $post_id
				AND t.topic_id = p.topic_id
				AND pt.post_id = p.post_id AND u.user_id = p.poster_id";

				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not collect post data', '', __LINE__, __FILE__, $sql);
				}
					$post_info = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					$forum_id = $post_info['forum_id'];
					$topic_id = $post_info['topic_id'];
					$topic_title = ( $post_info['post_subject'] ) ? $post_info['post_subject'] : $post_info['topic_title'];
					$post_data['username'] = $post_info[username];
					$post_data['message'] = $post_info[post_text];

					$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $post_info);
					if ( !$is_auth['auth_mod'] )
					{
					   message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
					}

					user_notification($mode, $post_data, $topic_title, $forum_id, $topic_id, $post_id, $notify_user, $mail_to_enabled_groups, $include_text, $include_unauthorized_users, $group_id);

					$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_POST_URL . "=" . $post_id) . '#' . $post_id . '">')
					);
					$message .=  $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_POST_URL . "=$post_id#$post_id") . '">', '</a>');
					message_die(GENERAL_MESSAGE, $message);
		}

// otherwise, if this is a post that was getting emailed but the user has canceled out the email, just go to the usual redirect...
		if ($cancel_email)
		{
				$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_POST_URL . "=$post_id#$post_id") . '">')
				);
				$message = $lang['Stored_but_email_cancelled'] . '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_POST_URL . "=" . $post_id) . '#' . $post_id . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
		}
// end mod email to designated groups

//
// Was cancel pressed? If so then redirect to the appropriate
// page, no point in continuing with any further checks
//
if ( isset($HTTP_POST_VARS['cancel']) )
{
	if ( $post_id )
	{
		$redirect = "viewtopic.$phpEx?" . POST_POST_URL . "=$post_id";
		$post_append = "#$post_id";
	}
	else if ( $topic_id )
	{
		$redirect = "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id";
		$post_append = '';
	}
	else if ( $forum_id )
	{
		$redirect = "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id";
		$post_append = '';
	}
	else
	{
		$redirect = "index.$phpEx";
		$post_append = '';
	}

	redirect(append_sid($redirect, true) . $post_append);
}

//
// What auth type do we need to check?
//
$is_auth = array();
switch( $mode )
{
	case 'newtopic':
		if ( $topic_type == POST_ANNOUNCE )
		{
			$is_auth_type = 'auth_announce';
		}
		else if ( $topic_type == POST_STICKY )
		{
			$is_auth_type = 'auth_sticky';
		}
		else if ( $topic_type == POST_TITLE_LINK )
		{
			$is_auth_type = 'auth_post';
		}
		else
		{
			$is_auth_type = 'auth_post';
		}
		break;
	case 'reply':
	case 'quote':
		$is_auth_type = 'auth_reply';
		break;
	case 'editpost':
		$is_auth_type = 'auth_edit';
		break;
	case 'delete':
	case 'poll_delete':
		$is_auth_type = 'auth_delete';
		break;
	case 'vote':
		$is_auth_type = 'auth_vote';
		break;
	case 'topicreview':
		$is_auth_type = 'auth_read';
		break;
	default:
		message_die(GENERAL_MESSAGE, $lang['No_post_mode']);
		break;
}

//
// Here we do various lookups to find topic_id, forum_id, post_id etc.
// Doing it here prevents spoofing (eg. faking forum_id, topic_id or post_id
//
$error_msg = '';
$post_data = array();
switch ( $mode )
{
	case 'newtopic':
		if ( empty($forum_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['Forum_not_exist']);
		}

		$sql = "SELECT * 
			FROM " . FORUMS_TABLE . " 
			WHERE forum_id = $forum_id";
		break;

	case 'reply':
	case 'vote':
		if ( empty( $topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['No_topic_id']);
		}

		$sql = "SELECT f.*, t.topic_status, t.topic_title, t.topic_type 
			FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t
			WHERE t.topic_id = $topic_id
				AND f.forum_id = t.forum_id";
		break;

	case 'quote':
	case 'editpost':
	case 'delete':
	case 'poll_delete':
		if ( empty($post_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['No_post_id']);
		}

		$select_sql = (!$submit) ? ', t.topic_title, p.enable_bbcode, p.enable_html, p.enable_smilies, p.enable_sig, p.post_username, pt.post_subject, pt.post_text, pt.bbcode_uid, u.username, u.user_id, u.user_sig, u.user_sig_bbcode_uid' : '';
		$from_sql = ( !$submit ) ? ", " . POSTS_TEXT_TABLE . " pt, " . USERS_TABLE . " u" : '';
		$where_sql = ( !$submit ) ? "AND pt.post_id = p.post_id AND u.user_id = p.poster_id" : '';

		$sql = "SELECT f.*, t.topic_id, t.topic_status, t.topic_type, t.topic_first_post_id, t.topic_last_post_id, t.topic_vote, p.post_id, p.poster_id" . $select_sql . " 
			FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f" . $from_sql . " 
			WHERE p.post_id = $post_id 
				AND t.topic_id = p.topic_id 
				AND f.forum_id = p.forum_id
				$where_sql";
		break;

	default:
		message_die(GENERAL_MESSAGE, $lang['No_valid_mode']);
}

if ( $result = $db->sql_query($sql) )
{
	$post_info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$forum_id = $post_info['forum_id'];
	$forum_name = $post_info['forum_name'];

	$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $post_info);

	if ( $post_info['forum_status'] == FORUM_LOCKED && !$is_auth['auth_mod']) 
	{ 
	   message_die(GENERAL_MESSAGE, $lang['Forum_locked']); 
	} 
	else if ( $mode != 'newtopic' && $post_info['topic_status'] == TOPIC_LOCKED && !$is_auth['auth_mod']) 
	{ 
	   message_die(GENERAL_MESSAGE, $lang['Topic_locked']); 
	} 

	if ( $mode == 'editpost' || $mode == 'delete' || $mode == 'poll_delete' )
	{
		$topic_id = $post_info['topic_id'];

		$post_data['poster_post'] = ( $post_info['poster_id'] == $userdata['user_id'] ) ? true : false;
		$post_data['first_post'] = ( $post_info['topic_first_post_id'] == $post_id ) ? true : false;
		$post_data['last_post'] = ( $post_info['topic_last_post_id'] == $post_id ) ? true : false;
		$post_data['last_topic'] = ( $post_info['forum_last_post_id'] == $post_id ) ? true : false;
		$post_data['has_poll'] = ( $post_info['topic_vote'] ) ? true : false; 
		$post_data['topic_type'] = $post_info['topic_type'];
		$post_data['poster_id'] = $post_info['poster_id'];

		if ( $post_data['first_post'] && $post_data['has_poll'] )
		{
			$sql = "SELECT * 
				FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr 
				WHERE vd.topic_id = $topic_id 
					AND vr.vote_id = vd.vote_id 
				ORDER BY vr.vote_option_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain vote data for this topic', '', __LINE__, __FILE__, $sql);
			}

			$poll_options = array();
			$poll_results_sum = 0;
			if ( $row = $db->sql_fetchrow($result) )
			{
				$poll_title = $row['vote_text'];
				$poll_id = $row['vote_id'];
				$poll_length = $row['vote_length'] / 86400;

				do
				{
					$poll_options[$row['vote_option_id']] = $row['vote_option_text']; 
					$poll_results_sum += $row['vote_result'];
				}
				while ( $row = $db->sql_fetchrow($result) );
			}
			$db->sql_freeresult($result);

			$post_data['edit_poll'] = ( ( !$poll_results_sum || $is_auth['auth_mod'] ) && $post_data['first_post'] ) ? true : 0;
		}
		else 
		{
			$post_data['edit_poll'] = ($post_data['first_post'] && $is_auth['auth_pollcreate']) ? true : false;
		}
		
		//
		// Can this user edit/delete the post/poll?
		//
		if ( $post_info['poster_id'] != $userdata['user_id'] && !$is_auth['auth_mod'] )
		{
			$message = ( $delete || $mode == 'delete' ) ? $lang['Delete_own_posts'] : $lang['Edit_own_posts'];
			$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		else if ( !$post_data['last_post'] && !$is_auth['auth_mod'] && ( $mode == 'delete' || $delete ) )
		{
			message_die(GENERAL_MESSAGE, $lang['Cannot_delete_replied']);
		}
		else if ( !$post_data['edit_poll'] && !$is_auth['auth_mod'] && ( $mode == 'poll_delete' || $poll_delete ) )
		{
			message_die(GENERAL_MESSAGE, $lang['Cannot_delete_poll']);
		}
	}
	else
	{
		if ( $mode == 'quote' )
		{
			$topic_id = $post_info['topic_id'];
		}
		if ( $mode == 'newtopic' )
		{
			$post_data['topic_type'] = POST_NORMAL;
		}

		$post_data['first_post'] = ( $mode == 'newtopic' ) ? true : 0;
		$post_data['last_post'] = false;
		$post_data['has_poll'] = false;
		$post_data['edit_poll'] = false;
	}
	if ( $mode == 'poll_delete' && !isset($poll_id) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_such_post']);
	}
}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_such_post']);
}

//
// The user is not authed, if they're not logged in then redirect
// them, else show them an error message
//
if ( !$is_auth[$is_auth_type] )
{
	if ( $userdata['session_logged_in'] )
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_' . $is_auth_type], $is_auth[$is_auth_type . "_type"]));
	}

	switch( $mode )
	{
		case 'newtopic':
			$redirect = "mode=newtopic&" . POST_FORUM_URL . "=" . $forum_id;
			break;
		case 'reply':
		case 'topicreview':
			$redirect = "mode=reply&" . POST_TOPIC_URL . "=" . $topic_id;
			break;
		case 'quote':
		case 'editpost':
			$redirect = "mode=quote&" . POST_POST_URL ."=" . $post_id;
			break;
	}

	redirect(append_sid("login.$phpEx?redirect=posting.$phpEx&" . $redirect, true));
}

//
// Set toggles for various options
//
if ( !$board_config['allow_html'] )
{
	$html_on = 0;
}
else
{
	$html_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_html']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_html'] : $userdata['user_allowhtml'] );
}

if ( !$board_config['allow_bbcode'] )
{
	$bbcode_on = 0;
}
else
{
	$bbcode_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_bbcode']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_bbcode'] : $userdata['user_allowbbcode'] );
}

if ( !$board_config['allow_smilies'] )
{
	$smilies_on = 0;
}
else
{
	$smilies_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_smilies']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_smilies'] : $userdata['user_allowsmile'] );
}

// start mod email to designated groups
$mail_to_enabled_groups = ( $userdata['user_level'] == 1 and ( $submit || $refresh ) and !empty($HTTP_POST_VARS['mail_to_enabled_groups'])) ? TRUE : 0;
// end mod email to designated groups

if ( ($submit || $refresh) && $is_auth['auth_read'])
{
	$notify_user = ( !empty($HTTP_POST_VARS['notify']) ) ? TRUE : 0;
}
else
{
	if ( $mode != 'newtopic' && $userdata['session_logged_in'] && $is_auth['auth_read'] )
	{
		$sql = "SELECT topic_id 
			FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = $topic_id 
				AND user_id = " . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain topic watch information', '', __LINE__, __FILE__, $sql);
		}

		$notify_user = ( $db->sql_fetchrow($result) ) ? TRUE : $userdata['user_notify'];
		$db->sql_freeresult($result);
	}
	else
	{
		$notify_user = ( $userdata['session_logged_in'] && $is_auth['auth_read'] ) ? $userdata['user_notify'] : 0;
	}
}

$attach_sig = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['attach_sig']) ) ? TRUE : 0 ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? 0 : $userdata['user_attachsig'] );

execute_posting_attachment_handling();

// --------------------
//  What shall we do?
//
if ( ( $delete || $poll_delete || $mode == 'delete' ) && !$confirm )
{
	//
	// Confirm deletion
	//
	$s_hidden_fields = '<input type="hidden" name="' . POST_POST_URL . '" value="' . $post_id . '" />';
	$s_hidden_fields .= ( $delete || $mode == "delete" ) ? '<input type="hidden" name="mode" value="delete" />' : '<input type="hidden" name="mode" value="poll_delete" />';

	$l_confirm = ( $delete || $mode == 'delete' ) ? $lang['Confirm_delete'] : $lang['Confirm_delete_poll'];

	//
	// Output confirmation page
	//
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'confirm_body' => 'confirm_body.tpl')
	);

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Information'],
		'MESSAGE_TEXT' => $l_confirm,

		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'S_CONFIRM_ACTION' => append_sid("posting.$phpEx"),
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);

	$template->pparse('confirm_body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else if ( $mode == 'vote' )
{
	//
	// Vote in a poll
	//
	if ( !empty($HTTP_POST_VARS['vote_id']) )
	{
		$vote_option_id = intval($HTTP_POST_VARS['vote_id']);

		$sql = "SELECT vd.vote_id    
			FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
			WHERE vd.topic_id = $topic_id 
				AND vr.vote_id = vd.vote_id 
				AND vr.vote_option_id = $vote_option_id
			GROUP BY vd.vote_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain vote data for this topic', '', __LINE__, __FILE__, $sql);
		}

		if ( $vote_info = $db->sql_fetchrow($result) )
		{
			$vote_id = $vote_info['vote_id'];

			$sql = "SELECT * 
				FROM " . VOTE_USERS_TABLE . "  
				WHERE vote_id = $vote_id 
					AND vote_user_id = " . $userdata['user_id'];
			if ( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain user vote data for this topic', '', __LINE__, __FILE__, $sql);
			}

			if ( !($row = $db->sql_fetchrow($result2)) )
			{
				$sql = "UPDATE " . VOTE_RESULTS_TABLE . " 
					SET vote_result = vote_result + 1 
					WHERE vote_id = $vote_id 
						AND vote_option_id = $vote_option_id";
				if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
				{
					message_die(GENERAL_ERROR, 'Could not update poll result', '', __LINE__, __FILE__, $sql);
				}

				$sql = "INSERT INTO " . VOTE_USERS_TABLE . " (vote_id, vote_user_id, vote_user_ip) 
					VALUES ($vote_id, " . $userdata['user_id'] . ", '$user_ip')";
				if ( !$db->sql_query($sql, END_TRANSACTION) )
				{
					message_die(GENERAL_ERROR, "Could not insert user_id for poll", "", __LINE__, __FILE__, $sql);
				}

				$message = $lang['Vote_cast'];
			}
			else
			{
				$message = $lang['Already_voted'];
			}
			$db->sql_freeresult($result2);
		}
		else
		{
			$message = $lang['No_vote_option'];
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">')
		);
		$message .=  '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		redirect(append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id", true));
	}
}
else if ( $submit || $confirm )
{
	//
	// Submit post/vote (newtopic, edit, reply, etc.)
	//
	$return_message = '';
	$return_meta = '';

	switch ( $mode )
	{
		case 'editpost':
		case 'newtopic':
		case 'reply':
			$username = ( !empty($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : '';
			$subject = ( !empty($HTTP_POST_VARS['subject']) ) ? trim($HTTP_POST_VARS['subject']) : '';
			$message = ( !empty($HTTP_POST_VARS['message']) ) ? $HTTP_POST_VARS['message'] : '';
			$poll_title = ( isset($HTTP_POST_VARS['poll_title']) && $is_auth['auth_pollcreate'] ) ? $HTTP_POST_VARS['poll_title'] : '';
			$poll_options = ( isset($HTTP_POST_VARS['poll_option_text']) && $is_auth['auth_pollcreate'] ) ? $HTTP_POST_VARS['poll_option_text'] : '';
			$poll_length = ( isset($HTTP_POST_VARS['poll_length']) && $is_auth['auth_pollcreate'] ) ? $HTTP_POST_VARS['poll_length'] : '';
			$bbcode_uid = '';

			if ( $topic_type == POST_TITLE_LINK )
			{
				$bbcode_on = 0;
				$html_on = 0;
				$smilies_on = 0;
			}

			prepare_post($mode, $post_data, $bbcode_on, $html_on, $smilies_on, $error_msg, $username, $bbcode_uid, $subject, $message, $poll_title, $poll_options, $poll_length);

			if ( $error_msg == '' )
			{
				$topic_type = ( $topic_type != $post_data['topic_type'] && !$is_auth['auth_sticky'] && !$is_auth['auth_announce'] ) ? $post_data['topic_type'] : $topic_type;

				submit_post($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id, $poll_id, $topic_type, $bbcode_on, $html_on, $smilies_on, $attach_sig, $bbcode_uid, str_replace("\'", "''", $username), str_replace("\'", "''", $subject), str_replace("\'", "''", $message), str_replace("\'", "''", $poll_title), $poll_options, $poll_length);
			}
			break;

		case 'delete':
		case 'poll_delete':
			delete_post($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id, $poll_id);
			break;
	}

	if ( $error_msg == '' )
	{
		if ( $mode != 'editpost' )
		{
			$user_id = ( $mode == 'reply' || $mode == 'newtopic' ) ? $userdata['user_id'] : $post_data['poster_id'];
			update_post_stats($mode, $post_data, $forum_id, $topic_id, $post_id, $user_id);
		}

		$attachment_mod['posting']->insert_attachment($post_id);

// 		start mod email to designated groups (and end mod too): note, we deleted the user notification line and moved that concept after the cookies writing is complete...

		if ( $mode == 'newtopic' || $mode == 'reply' )
		{
			$tracking_topics = ( !empty($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : array();
			$tracking_forums = ( !empty($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : array();

			if ( count($tracking_topics) + count($tracking_forums) == 100 && empty($tracking_topics[$topic_id]) )
			{
				asort($tracking_topics);
				unset($tracking_topics[key($tracking_topics)]);
			}

			$tracking_topics[$topic_id] = time();

			setcookie($board_config['cookie_name'] . '_t', serialize($tracking_topics), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
		}

		// start mod email to designated groups
		if ($error_msg == '' && $mail_to_enabled_groups)
		{


			// here we allow the user to specify which groups to email the post to...
			$page_title = $lang['Designated_groups_CP'];
			include($phpbb_root_path . 'includes/page_header.'.$phpEx);

			$template->assign_vars(array(

			'L_DESIGNATED_GROUPS_CP' => $lang['Designated_groups_CP'],
			'L_DESIGNATED_GROUPS_CP_EXPLAIN' => $lang['Designated_groups_CP_explain'],
			'L_GROUPS' => $lang['Usergroups'],
			'L_SELECT' => $lang['Select'],
			'L_DESIGNATE' => $lang['Send_email'],
			'L_CANCEL_EMAIL' => $lang['Cancel_email'],
			'L_MARK_ALL_GROUPS' => $lang['Mark_all_groups'],
			'L_UNMARK_ALL_GROUPS' => $lang['Unmark_all_groups'],
			'L_INCLUDE_TEXT' => $lang['Include_text'],
			'L_INCLUDE_UNAUTHORIZED_USERS' => $lang['Include_unauthorized_users'],

			'S_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_POST_URL . '" value="' . $post_id . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="notify" value="' . $notify_user . '" />',
			'S_DESIGNATED_GROUPS_CP_ACTION' => append_sid("posting.$phpEx"),
			'S_INCLUDE_TEXT' => '',
			'S_INCLUDE_UNAUTHORIZED_USERS' => '')
			);
			// note that if you want to change the default so the email includes the text of the post by default, change relevant line above to 'S_INCLUDE_TEXT' => 'checked="checked"',
			// and if you want to change the default so the email goes to all users in the selected groups regardless of whether they are authorized to read the relevant forum, change the relevant line above to 'S_INCLUDE_UNAUTHORIZED_USERS' => 'checked="checked"')

			$template->set_filenames(array(
			'body' => 'designated_groups_cp_body.tpl')
			);
								
			$template->assign_block_vars('switch_include_text_checkbox', array());

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
			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

			// ok, the template is all drawn.  When it comes back, 'designated' will be in the hidden field so $designate will be true and the rest of the notification is handled close to the top[ of posting.php.
		}

		if ($error_msg == '' && $mode != 'poll_delete')
		{
			// the following code is borrowed from David Herrmann's forum notification mod on phpbbhacks...
			// the code replaced the original lines in phpbb that read 'user_notification($mode blah blah...
			// but I changed the fourth line below this to test whether mode is newtopic rather than whether post is first post to avoid repeat emails when a first post is edited numerous times...

			$post_data['subject'] = $subject;
			$post_data['username'] = ( $userdata['user_id'] == ANONYMOUS ) ? $username : $userdata['username'];
			$post_data['message'] = $message;
			if ( $mode == 'newtopic' )
			{
				// fetch topic title
				$sql = "SELECT topic_title, topic_id
					FROM " . TOPICS_TABLE . "
					WHERE topic_id = $topic_id";

				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain topic title for notification', '', __LINE__, __FILE__, $sql);
				}

				if ( $topic_info = $db->sql_fetchrow($result) )
				{
					user_notification('newtopic', $post_data, $topic_info['topic_title'], $forum_id, $topic_id, $post_id, $notify_user, $mail_to_enabled_groups, $include_text, $include_unauthorized_users, $group_id);
				}

			}
			else
			{
				user_notification($mode, $post_data, $post_info['topic_title'], $forum_id, $topic_id, $post_id, $notify_user, $mail_to_enabled_groups, $include_text, $include_unauthorized_users, $group_id);
			}
		}
		// end mod email to designated groups

		$template->assign_vars(array(
			'META' => $return_meta)
		);
		message_die(GENERAL_MESSAGE, $return_message);
	}
}

if( $refresh || isset($HTTP_POST_VARS['del_poll_option']) || $error_msg != '' )
{
	$username = ( !empty($HTTP_POST_VARS['username']) ) ? htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['username']))) : '';
	$subject = ( !empty($HTTP_POST_VARS['subject']) ) ? htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['subject']))) : '';
	$message = ( !empty($HTTP_POST_VARS['message']) ) ? htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['message']))) : '';

	$poll_title = ( !empty($HTTP_POST_VARS['poll_title']) ) ? htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['poll_title']))) : '';
	$poll_length = ( isset($HTTP_POST_VARS['poll_length']) ) ? max(0, intval($HTTP_POST_VARS['poll_length'])) : 0;

	$poll_options = array();
	if ( !empty($HTTP_POST_VARS['poll_option_text']) )
	{
		while( list($option_id, $option_text) = @each($HTTP_POST_VARS['poll_option_text']) )
		{
			if( isset($HTTP_POST_VARS['del_poll_option'][$option_id]) )
			{
				unset($poll_options[$option_id]);
			}
			else if ( !empty($option_text) ) 
			{
				$poll_options[$option_id] = htmlspecialchars(trim(stripslashes($option_text)));
			}
		}
	}

	if ( isset($poll_add) && !empty($HTTP_POST_VARS['add_poll_option_text']) )
	{
		$poll_options[] = htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['add_poll_option_text'])));
	}

	if ( $mode == 'newtopic' || $mode == 'reply')
	{
		$user_sig = ( $userdata['user_sig'] != '' && $board_config['allow_sig'] ) ? $userdata['user_sig'] : '';
	}
	else if ( $mode == 'editpost' )
	{
		$user_sig = ( $post_info['user_sig'] != '' && $board_config['allow_sig'] ) ? $post_info['user_sig'] : '';
		$userdata['user_sig_bbcode_uid'] = $post_info['user_sig_bbcode_uid'];
	}
	
	if( $preview )
	{
		$orig_word = array();
		$replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);

		$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
		$preview_message = stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on, $bbcode_uid));
		$preview_subject = $subject;
		$preview_username = $username;

		//
		// Finalise processing as per viewtopic
		//
		if( !$html_on )
		{
			if( $user_sig != '' || !$userdata['user_allowhtml'] )
			{
				$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $user_sig);
			}
		}

		if( $attach_sig && $user_sig != '' && $userdata['user_sig_bbcode_uid'] )
		{
			$user_sig = bbencode_second_pass($user_sig, $userdata['user_sig_bbcode_uid']);
		}

		if( $bbcode_on )
		{
			$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);
		}

		if( !empty($orig_word) )
		{
			$preview_username = ( !empty($username) ) ? preg_replace($orig_word, $replacement_word, $preview_username) : '';
			$preview_subject = ( !empty($subject) ) ? preg_replace($orig_word, $replacement_word, $preview_subject) : '';
			$preview_message = ( !empty($preview_message) ) ? preg_replace($orig_word, $replacement_word, $preview_message) : '';
		}

		if( $user_sig != '' )
		{
			$user_sig = make_clickable($user_sig);
		}
		$preview_message = make_clickable($preview_message);

		if( $smilies_on )
		{
			if( $userdata['user_allowsmile'] && $user_sig != '' )
			{
				$user_sig = smilies_pass($user_sig);
			}

			$preview_message = smilies_pass($preview_message);
		}

		if( $attach_sig && $user_sig != '' )
		{
			$preview_message = $preview_message . '<br /><br />_________________<br />' . $user_sig;
		}

		$preview_message = str_replace("\n", '<br />', $preview_message);

		$preview_message = word_wrap_pass($preview_message);

		$template->set_filenames(array(
			'preview' => 'posting_preview.tpl')
		);

		$attachment_mod['posting']->preview_attachments();

		$template->assign_vars(array(
			'TOPIC_TITLE' => $preview_subject,
			'POST_SUBJECT' => $preview_subject,
			'POSTER_NAME' => $preview_username,
			'POST_DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
			'MESSAGE' => $preview_message,

			'L_POST_SUBJECT' => $lang['Post_subject'], 
			'L_PREVIEW' => $lang['Preview'],
			'L_POSTED' => $lang['Posted'], 
			'L_POST' => $lang['Post'])
		);
		$template->assign_var_from_handle('POST_PREVIEW_BOX', 'preview');
	}
	else if( $error_msg != '' )
	{
		$template->set_filenames(array(
			'reg_header' => 'error_body.tpl')
		);
		$template->assign_vars(array(
			'ERROR_MESSAGE' => $error_msg)
		);
		$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	}
}
else
{
	//
	// User default entry point
	//
	if ( $mode == 'newtopic' )
	{
		$user_sig = ( $userdata['user_sig'] != '' ) ? $userdata['user_sig'] : '';

		$username = ($userdata['session_logged_in']) ? $userdata['username'] : '';
		$poll_title = '';
		$poll_length = '';
		$subject = '';
		$message = '';
	}
	else if ( $mode == 'reply' )
	{
		$user_sig = ( $userdata['user_sig'] != '' ) ? $userdata['user_sig'] : '';

		$username = ( $userdata['session_logged_in'] ) ? $userdata['username'] : '';
		$subject = '';
		$message = '';

	}
	else if ( $mode == 'quote' || $mode == 'editpost' )
	{
		$subject = ( $post_data['first_post'] ) ? $post_info['topic_title'] : $post_info['post_subject'];
		$message = $post_info['post_text'];

		if ( $mode == 'editpost' )
		{
			$attach_sig = ( $post_info['enable_sig'] && $post_info['user_sig'] != '' ) ? TRUE : 0; 
			$user_sig = $post_info['user_sig'];

			$html_on = ( $post_info['enable_html'] ) ? true : false;
			$bbcode_on = ( $post_info['enable_bbcode'] ) ? true : false;
			$smilies_on = ( $post_info['enable_smilies'] ) ? true : false;
		}
		else
		{
			$attach_sig = ( $userdata['user_attachsig'] ) ? TRUE : 0;
			$user_sig = $userdata['user_sig'];
		}

		if ( $post_info['bbcode_uid'] != '' )
		{
			$message = preg_replace('/\:(([a-z0-9]:)?)' . $post_info['bbcode_uid'] . '/s', '', $message);
		}

		$message = str_replace('<', '&lt;', $message);
		$message = str_replace('>', '&gt;', $message);
		$message = str_replace('<br />', "\n", $message);

		if ( $mode == 'quote' )
		{
			$orig_word = array();
			$replacement_word = array();
			obtain_word_list($orig_word, $replace_word);

			$msg_date =  create_date($board_config['default_dateformat'], $postrow['post_time'], $board_config['board_timezone']);

			// Use trim to get rid of spaces placed there by MS-SQL 2000
			$quote_username = ( trim($post_info['post_username']) != '' ) ? $post_info['post_username'] : $post_info['username'];
			$message = '[quote="' . $quote_username . '"]' . $message . '[/quote]';

			if ( !empty($orig_word) )
			{
				$subject = ( !empty($subject) ) ? preg_replace($orig_word, $replace_word, $subject) : '';
				$message = ( !empty($message) ) ? preg_replace($orig_word, $replace_word, $message) : '';
			}

			if ( !preg_match('/^Re:/', $subject) && strlen($subject) > 0 )
			{
				$subject = 'Re: ' . $subject;
			}

			$mode = 'reply';
		}
		else
		{
			$username = ( $post_info['user_id'] == ANONYMOUS && !empty($post_info['post_username']) ) ? $post_info['post_username'] : '';
		}
	}
}

//
// Signature toggle selection
//
if( $user_sig != '' )
{
	$template->assign_block_vars('switch_signature_checkbox', array());
}

//
// HTML toggle selection
//
if ( $board_config['allow_html'] )
{
	$html_status = $lang['HTML_is_ON'];
	$template->assign_block_vars('switch_html_checkbox', array());
}
else
{
	$html_status = $lang['HTML_is_OFF'];
}

//
// BBCode toggle selection
//
if ( $board_config['allow_bbcode'] )
{
	$bbcode_status = $lang['BBCode_is_ON'];
	$template->assign_block_vars('switch_bbcode_checkbox', array());
}
else
{
	$bbcode_status = $lang['BBCode_is_OFF'];
}

//
// Smilies toggle selection
//
if ( $board_config['allow_smilies'] )
{
	$smilies_status = $lang['Smilies_are_ON'];
	$template->assign_block_vars('switch_smilies_checkbox', array());
}
else
{
	$smilies_status = $lang['Smilies_are_OFF'];
}

if( !$userdata['session_logged_in'] || ( $mode == 'editpost' && $post_info['poster_id'] == ANONYMOUS ) )
{
	$template->assign_block_vars('switch_username_select', array());
}

// start mod email to designated groups
//	the following code adds a checkbox option in new posts for the admin only

if( $userdata['user_level'] == 1 )
{
	$template->assign_block_vars('switch_mail_to_enabled_groups_checkbox', array());
}

// end mod email to designated groups

//
// Notify checkbox - only show if user is logged in
//
if ( $userdata['session_logged_in'] && $is_auth['auth_read'] )
{
	if ( $mode != 'editpost' || ( $mode == 'editpost' && $post_info['poster_id'] != ANONYMOUS ) )
	{
		$template->assign_block_vars('switch_notify_checkbox', array());
	}
}

//
// Delete selection
//
if ( $mode == 'editpost' && ( ( $is_auth['auth_delete'] && $post_data['last_post'] && ( !$post_data['has_poll'] || $post_data['edit_poll'] ) ) || $is_auth['auth_mod'] ) )
{
	$template->assign_block_vars('switch_delete_checkbox', array());
}

//
// Topic type selection
//
$topic_type_toggle = '';
if ( $mode == 'newtopic' || ( $mode == 'editpost' && $post_data['first_post'] ) )
{
	$template->assign_block_vars('switch_type_toggle', array());

	if( ( $userdata['user_level'] == ADMIN || $userdata['user_level'] == MOD ) && $is_auth['auth_post'] )
	{
		$topic_type_toggle .= '<input type="radio" name="topictype" value="' . POST_TITLE_LINK . '"';
		if ( $post_data['topic_type'] == POST_TITLE_LINK || $topic_type == POST_TITLE_LINK )
		{
			$topic_type_toggle .= ' checked="checked"';
		}
		$topic_type_toggle .= ' /> ' . $lang['Post_Weblink'] . '&nbsp;&nbsp;';
	}
	
      if( $is_auth['auth_sticky'] )
	{
		$topic_type_toggle .= '<input type="radio" name="topictype" value="' . POST_STICKY . '"';
		if ( $post_data['topic_type'] == POST_STICKY || $topic_type == POST_STICKY )
		{
			$topic_type_toggle .= ' checked="checked"';
		}
		$topic_type_toggle .= ' /> ' . $lang['Post_Sticky'] . '&nbsp;&nbsp;';
	}

	if( $is_auth['auth_announce'] )
	{
		$topic_type_toggle .= '<input type="radio" name="topictype" value="' . POST_ANNOUNCE . '"';
		if ( $post_data['topic_type'] == POST_ANNOUNCE || $topic_type == POST_ANNOUNCE )
		{
			$topic_type_toggle .= ' checked="checked"';
		}
		$topic_type_toggle .= ' /> ' . $lang['Post_Announcement'] . '&nbsp;&nbsp;';
	}

	if ( $topic_type_toggle != '' )
	{
		$topic_type_toggle = $lang['Post_topic_as'] . ': <input type="radio" name="topictype" value="' . POST_NORMAL .'"' . ( ( $post_data['topic_type'] == POST_NORMAL || $topic_type == POST_NORMAL ) ? ' checked="checked"' : '' ) . ' /> ' . $lang['Post_Normal'] . '&nbsp;&nbsp;' . $topic_type_toggle;
	}
}

$hidden_form_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';

switch( $mode )
{
	case 'newtopic':
		$page_title = $lang['Post_a_new_topic'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
		break;

	case 'reply':
		$page_title = $lang['Post_a_reply'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
		break;

	case 'editpost':
		$page_title = $lang['Edit_Post'];
		$hidden_form_fields .= '<input type="hidden" name="' . POST_POST_URL . '" value="' . $post_id . '" />';
		break;
}

// Generate smilies listing for page output
generate_smilies('inline', PAGE_POSTING);

$hidden_form_fields .= '<input type="hidden" name="' . $authkey . '" value="' . $authval . '">';
$hidden_form_fields .= '<input type="hidden" name="' . $timekey . '" value="' . $timeval . '">';

//
// Include page header
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'posting_body.tpl', 
	'pollbody' => 'posting_poll_body.tpl', 
	'reviewbody' => 'posting_topic_review.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'FORUM_NAME' => $forum_name,
	'L_POST_A' => $page_title,
	'L_POST_SUBJECT' => $lang['Post_subject'], 

	'U_VIEW_FORUM' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"))
);

//
// This enables the forum/topic title to be output for posting
// but not for privmsg (where it makes no sense)
//
$template->assign_block_vars('switch_not_privmsg', array());

//
// Output the data to the template
//
$template->assign_vars(array(
	'USERNAME' => $username,
	'SUBJECT' => $subject,
	'MESSAGE' => $message,
	'HTML_STATUS' => $html_status,
	'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
	'SMILIES_STATUS' => $smilies_status, 

      'L_ENTER_YOUR_NAME' => $lang['Enter_your_name'],
	'L_SUBJECT' => $lang['Subject'],
	'L_MESSAGE_BODY' => $lang['Message_body'],
	'L_OPTIONS' => $lang['Options'],
	'L_PREVIEW' => $lang['Preview'],
	'L_SPELLCHECK' => $lang['Spellcheck'],
	'L_SUBMIT' => $lang['Submit'],
	'L_CANCEL' => $lang['Cancel'],
	'L_CONFIRM_DELETE' => $lang['Confirm_delete'],
	'L_DISABLE_HTML' => $lang['Disable_HTML_post'], 
	'L_DISABLE_BBCODE' => $lang['Disable_BBCode_post'], 
	'L_DISABLE_SMILIES' => $lang['Disable_Smilies_post'], 
	'L_ATTACH_SIGNATURE' => $lang['Attach_signature'], 
	'L_NOTIFY_ON_REPLY' => $lang['Notify'], 
	'L_DELETE_POST' => $lang['Delete_post'],
	'L_MAIL_TO_ENABLED_GROUPS' => $lang['Mail_to_enabled_groups'],

	'L_BBCODE_B_HELP' => $lang['bbcode_b_help'], 
	'L_BBCODE_I_HELP' => $lang['bbcode_i_help'], 
	'L_BBCODE_U_HELP' => $lang['bbcode_u_help'], 
	'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'], 
	'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
	'L_BBCODE_L_HELP' => $lang['bbcode_l_help'], 
	'L_BBCODE_O_HELP' => $lang['bbcode_o_help'], 
	'L_BBCODE_P_HELP' => $lang['bbcode_p_help'], 
	'L_BBCODE_W_HELP' => $lang['bbcode_w_help'], 
	'L_BBCODE_A_HELP' => $lang['bbcode_a_help'], 
	'L_BBCODE_S_HELP' => $lang['bbcode_s_help'], 
	'L_BBCODE_F_HELP' => $lang['bbcode_f_help'], 
	'L_EMPTY_MESSAGE' => $lang['Empty_message'],

	'L_FONT_COLOR' => $lang['Font_color'], 
	'L_COLOR_DEFAULT' => $lang['color_default'], 
	'L_COLOR_DARK_RED' => $lang['color_dark_red'], 
	'L_COLOR_RED' => $lang['color_red'], 
	'L_COLOR_ORANGE' => $lang['color_orange'], 
	'L_COLOR_BROWN' => $lang['color_brown'], 
	'L_COLOR_YELLOW' => $lang['color_yellow'], 
	'L_COLOR_GREEN' => $lang['color_green'], 
	'L_COLOR_OLIVE' => $lang['color_olive'], 
	'L_COLOR_CYAN' => $lang['color_cyan'], 
	'L_COLOR_BLUE' => $lang['color_blue'], 
	'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'], 
	'L_COLOR_INDIGO' => $lang['color_indigo'], 
	'L_COLOR_VIOLET' => $lang['color_violet'], 
	'L_COLOR_WHITE' => $lang['color_white'], 
	'L_COLOR_BLACK' => $lang['color_black'], 

	'L_FONT_SIZE' => $lang['Font_size'], 
	'L_FONT_TINY' => $lang['font_tiny'], 
	'L_FONT_SMALL' => $lang['font_small'], 
	'L_FONT_NORMAL' => $lang['font_normal'], 
	'L_FONT_LARGE' => $lang['font_large'], 
	'L_FONT_HUGE' => $lang['font_huge'], 

	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
	'L_STYLES_TIP' => $lang['Styles_tip'], 

	'U_VIEWTOPIC' => ( $mode == 'reply' ) ? append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postorder=desc") : '', 
	'U_REVIEW_TOPIC' => ( $mode == 'reply' ) ? append_sid("posting.$phpEx?mode=topicreview&amp;" . POST_TOPIC_URL . "=$topic_id") : '', 

	'S_HTML_CHECKED' => ( !$html_on ) ? 'checked="checked"' : '', 
	'S_BBCODE_CHECKED' => ( !$bbcode_on ) ? 'checked="checked"' : '', 
	'S_SMILIES_CHECKED' => ( !$smilies_on ) ? 'checked="checked"' : '', 
	'S_SIGNATURE_CHECKED' => ( $attach_sig ) ? 'checked="checked"' : '',
	'S_MAIL_ENABLED_GROUPS_CHECKED' => ( $mail_to_enabled_groups ) ? 'checked="checked"' : '',
	'S_NOTIFY_CHECKED' => ( $notify_user ) ? 'checked="checked"' : '', 
	'S_TYPE_TOGGLE' => $topic_type_toggle, 
	'S_TOPIC_ID' => $topic_id, 
	'S_POST_ACTION' => append_sid("posting.$phpEx"),
	'S_HIDDEN_FORM_FIELDS' => $hidden_form_fields)
);

//
// Poll entry switch/output
//
if( ( $mode == 'newtopic' || ( $mode == 'editpost' && $post_data['edit_poll']) ) && $is_auth['auth_pollcreate'] )
{
	$template->assign_vars(array(
		'L_ADD_A_POLL' => $lang['Add_poll'],  
		'L_ADD_POLL_EXPLAIN' => $lang['Add_poll_explain'],   
		'L_POLL_QUESTION' => $lang['Poll_question'],   
		'L_POLL_OPTION' => $lang['Poll_option'],  
		'L_ADD_OPTION' => $lang['Add_option'],
		'L_UPDATE_OPTION' => $lang['Update'],
		'L_DELETE_OPTION' => $lang['Delete'], 
		'L_POLL_LENGTH' => $lang['Poll_for'],  
		'L_DAYS' => $lang['Days'], 
		'L_POLL_LENGTH_EXPLAIN' => $lang['Poll_for_explain'], 
		'L_POLL_DELETE' => $lang['Delete_poll'],
		
		'POLL_TITLE' => $poll_title,
		'POLL_LENGTH' => $poll_length)
	);

	if( $mode == 'editpost' && $post_data['edit_poll'] && $post_data['has_poll'])
	{
		$template->assign_block_vars('switch_poll_delete_toggle', array());
	}

	if( !empty($poll_options) )
	{
		while( list($option_id, $option_text) = each($poll_options) )
		{
			$template->assign_block_vars('poll_option_rows', array(
				'POLL_OPTION' => str_replace('"', '&quot;', $option_text), 

				'S_POLL_OPTION_NUM' => $option_id)
			);
		}
	}

	$template->assign_var_from_handle('POLLBOX', 'pollbody');
}

//
// Topic review
//
if( $mode == 'reply' && $is_auth['auth_read'] )
{
	require($phpbb_root_path . 'includes/topic_review.'.$phpEx);
	topic_review($topic_id, true);

	$template->assign_block_vars('switch_inline_mode', array());
	$template->assign_var_from_handle('TOPIC_REVIEW_BOX', 'reviewbody');
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
