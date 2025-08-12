<?php
/***************************************************************************
 *                             Registration IP
 *                            -------------------
 *   begin                : Monday, May 3, 2004
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : woody@scoobler.com
 ***************************************************************************/

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
	$file = basename(__FILE__);
	$module['Users']['Registration IP'] = "$file";
	return;
}
//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');

require('./pagestart.' . $phpEx);
//
// Set mode
//
if( isset( $HTTP_POST_VARS['mode'] ) || isset( $HTTP_GET_VARS['mode'] ) )
{
	$mode = ( isset( $HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = '';
}

//
// Begin program
//
if ( $mode == 'edit' || $mode == 'save' && ( isset($HTTP_POST_VARS['username']) || isset($HTTP_GET_VARS[POST_USERS_URL]) || isset( $HTTP_POST_VARS[POST_USERS_URL]) ) )
{
$user_id = $HTTP_POST_VARS['username'];
//
// Let's set the template
//
$template->set_filenames(array("body" => "admin/user_ip_list.tpl"));
//
// Lets select a users info
//
$sql = "SELECT *
  FROM " . USERS_TABLE . "
	WHERE username='".$user_id."'";
$result = mysql_query($sql);	
if( !$result = $db->sql_query($sql) )
{
		message_die(GENERAL_ERROR, "Could not query database.", $lang['Error'], __LINE__, __FILE__, $sql);
}else{
$result_rows = $db->sql_fetchrowset($result);
$result_count = count($result_rows);
$main_ip = $result_rows[0]['user_reg_ip'];
$main_email = $result_rows[0]['user_email'];
$main_host = $result_rows[0]['user_reg_host'];
$main_posts = $result_rows[0]['user_posts'];
$main_joined = create_date($lang['DATE_FORMAT'], $result_rows[0]['user_regdate'], $board_config['board_timezone']);

//Let check if anyone else has registered from the main users IP
$sql2 = "SELECT *
  FROM " . USERS_TABLE . "
	WHERE user_reg_ip='".$main_ip."'";
$result2 = mysql_query($sql2);
$result2_rows = $db->sql_fetchrowset($result2);
$result2_count = count($result2_rows);
if( !$result2 = $db->sql_query($sql2) )
{
$no_others = "This is the only user to register from $main_ip";
}else{
for($i = 0; $i < $result2_count; $i++)
	{
		$usrname = $result2_rows[$i]['username'];
		$usremail = $result2_rows[$i]['user_email'];
		$usrhost = $result2_rows[$i]['user_reg_host'];
    $usrjoined = create_date($lang['DATE_FORMAT'], $result2_rows[$i]['user_regdate'], $board_config['board_timezone']);
		$usrposts = $result2_rows[$i]['user_posts'];		
    	
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars("LIST",array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			"USRNAME" => $usrname,
			"USRHOST" => $usrhost,
			"USREMAIL" => $usremail,
			"USRPOSTS" => $usrposts,
      "USRJOINED" => $usrjoined,
      ));
  }
}
}
$template->assign_vars(array(
  "MAINUSR" => $user_id,
  "MAINIP" => $main_ip,
  "MAINEMAIL" => $main_email,
  "MAINHOST" => $main_host,
  "MAINPOSTS" => $main_posts,
  "MAINJOINED" => $main_joined,
  ));
$template->pparse("body");
}
else
{
	//
	// Default user selection box
	//
	$template->set_filenames(array(
		'body' => 'admin/user_select_body.tpl')
	);

	$template->assign_vars(array(
		'L_USER_TITLE' => $lang['User_admin'],
		'L_USER_EXPLAIN' => $lang['User_admin_explain'],
		'L_USER_SELECT' => $lang['Select_a_User'],
		'L_LOOK_UP' => $lang['Look_up_user'],
		'L_FIND_USERNAME' => $lang['Find_username'],

		'U_SEARCH_USER' => append_sid("./../search.$phpEx?mode=searchuser"),

		'S_USER_ACTION' => append_sid("admin_reg_ip.$phpEx"),
		'S_USER_SELECT' => $select_list)
	);
	$template->pparse('body');

}
include('./page_footer_admin.'.$phpEx);
?>

