<?php
/***************************************************************************
 *                            privmsg_export.php
 *                            -------------------
 *   begin                :   June 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This hack is released under the GPL License.
 *   This hack can be freely used, but not distributed, without permission.
 *   Intellectual Property is retained by the author listed above.
 *
 ***************************************************************************/

     define('IN_PHPBB', true);
     $phpbb_root_path = './';
     include($phpbb_root_path . 'extension.inc');
     include($phpbb_root_path . 'common.'.$phpEx);
     include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
     include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

     // Start session management
     $userdata = session_pagestart($user_ip, PAGE_PRIVMSGS);
     init_userprefs($userdata);
     // End session management

     // Start Include language file
     $language = $board_config['default_lang'];
     if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_main.'.$phpEx) )
          {
               $language = 'english';
          } // if
          
     if ( $userdata['user_id'] < 1 )
          {
		message_die(GENERAL_ERROR, $lang['sign_in_to_export'], '', __LINE__, __FILE__, $sql);
          }

     include($phpbb_root_path . 'language/lang_' . $language . '/lang_main.' . $phpEx);
     // end include language file

	$sql = "SELECT u.username AS username_1, u.user_id AS user_id_1, u2.username AS username_2, u2.user_id AS user_id_2, u.user_sig_bbcode_uid, u.user_posts, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_regdate, u.user_msnm, u.user_viewemail, u.user_rank, u.user_sig, u.user_avatar, pm.*, pmt.privmsgs_bbcode_uid, pmt.privmsgs_text, pmt.privmsgs_bbcode_uid
		FROM (" . PRIVMSGS_TABLE . " pm
                   LEFT JOIN " . PRIVMSGS_TEXT_TABLE . " pmt ON pmt.privmsgs_text_id = pm.privmsgs_id
                   LEFT JOIN " . USERS_TABLE . " u ON pm.privmsgs_from_userid = u.user_id
                   LEFT JOIN " . USERS_TABLE . " u2 ON pm.privmsgs_to_userid = u2.user_id)
		WHERE pmt.privmsgs_text_id = pm.privmsgs_id
			AND ( u.user_id = " . $userdata['user_id'] . "
			OR u2.user_id = " . $userdata['user_id'] . ")
                ORDER BY pm.privmsgs_date DESC";
			
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query private message post information', '', __LINE__, __FILE__, $sql);
	}

     $total_messages = 0;
     while( $row = $db->sql_fetchrow($result) )
     {
         $messages_rowset[] = $row;
         $total_messages++;
     } // while

     echo "<html><body>";
     for($i = 0; $i < $total_messages; $i++)
             {
                 echo "###################################################################################################<br>";
                 echo "<b>" . $lang['export_pm_from'] . " </b>" .  $messages_rowset[$i]['username_1'] . "<br>";
                 echo "<b>" . $lang['export_pm_to'] . " </b>" . $messages_rowset[$i]['username_2'] . "<br>";
                 echo "<b>" . $lang['export_pm_subject'] . " </b>" . $messages_rowset[$i]['privmsgs_subject'] . "<br>";
                 echo "<b>" . $lang['export_pm_time'] . " </b>" . create_date("m/d/Y - h:i:s", $messages_rowset[$i]['privmsgs_date'], $board_config['board_timezone']) . "<br>";
                 echo "<b>" . $lang['export_pm_text'] . " </b><br>" . bbencode_second_pass($messages_rowset[$i]['privmsgs_text'], $messages_rowset[$i]['privmsgs_bbcode_uid']) . "<br>";
                 echo "###################################################################################################<br><br>";
             }
             
    echo "</body></html>";
?>