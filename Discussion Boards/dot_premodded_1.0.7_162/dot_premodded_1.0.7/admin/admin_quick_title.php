<?php
/***************************************************************************
*                             admin_quick_title.php
*                              -------------------
*     begin                : Tue July 15, 2003
*     copyright            : (C) 2003 Xavier Olive
*     email                : xavier@2037.biz
*
*     $Id: admin_quick_title.php,v 1.1.1 2003/07/22 01:00:00
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
	$file = basename(__FILE__);
	$module['General']['Title_infos'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
$start = ( isset($HTTP_GET_VARS['start']) ) ? $HTTP_GET_VARS['start'] : 0;
if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ($HTTP_GET_VARS['mode']) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
}
else 
{
	//
	// These could be entered via a form button
	//
	if( isset($HTTP_POST_VARS['add']) )
	{
		$mode = "add";
	}
	else if( isset($HTTP_POST_VARS['save']) )
	{
		$mode = "save";
	}
	else
	{
		$mode = "";
	}
}


if( $mode != "" )
{
	if( $mode == "edit" || $mode == "add" )
	{
		//
		// They want to add a new title info, show the form.
		//
		$title_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;
		
		$s_hidden_fields = "";
		
		if( $mode == "edit" )
		{
			if( empty($title_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['Must_select_title']);
			}

			$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . "  
				WHERE id = $title_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't obtain title data", "", __LINE__, __FILE__, $sql);
			}
			
			$title_info = $db->sql_fetchrow($result);
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $title_id . '" />';

		}
		else
		{

		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

		
		$template->set_filenames(array(
			"body" => "admin/title_edit_body.tpl")
		);

		$template->assign_vars(array(
			"TITLE_INFO" => str_replace("\"", "'", $title_info['title_info']),
			"ADMIN_CHECKED" => ($title_info['admin_auth']==1) ? 'CHECKED' : '',
			"MOD_CHECKED" => ($title_info['mod_auth']==1) ? 'CHECKED' : '',
			"POSTER_CHECKED" => ($title_info['poster_auth']==1) ? 'CHECKED' : '',
			"ADMIN_TITLE" => $lang['Title_infos'],
			"ADMIN_TITLE_EXPLAIN" => $lang['Quick_title_explain'],		
			"S_TITLE_ACTION" => append_sid("admin_quick_title.$phpEx"),
			"S_HIDDEN_FIELDS" => $s_hidden_fields,
			"ADMIN" => $lang['Administrator'],
			"MODERATOR" => $lang['Moderator'],
			"POSTER" => $lang['Topic_poster'],
			"L_SUBMIT" => $lang['Submit'],
			"L_RESET" => $lang['Reset'],
			"L_TITLE_TITLE" => $lang['Add_new_title_info'],
			"L_PERM_INFO" => $lang['Title_perm_info'],
			"L_TITLE_INFO" => $lang['Title_info'],
			"L_PERM_EXPLAIN" => $lang['Title_perm_info_explain'],
			'L_DATE_FORMAT' => $lang['Date_format'],
			'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
			'DATE_FORMAT' => $title_info['date_format'])
		);
		
	}
	else if( $mode == "save" )
	{
		//
		// Ok, they sent us our info, let's update it.
		//
		
		$title_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;
		$admin = (!empty($HTTP_POST_VARS['admin_auth']) ) ? 1 : 0 ;
		$mod = (!empty($HTTP_POST_VARS['mod_auth']) ) ? 1 : 0 ;
		$poster = (!empty($HTTP_POST_VARS['poster_auth']) ) ? 1 : 0 ;
		$name = ( isset($HTTP_POST_VARS['title_info']) ) ? trim($HTTP_POST_VARS['title_info']) : "";

		$date = ( isset($HTTP_POST_VARS['date_format']) ) ? trim($HTTP_POST_VARS['date_format']) : "";


		if( $name == "" )
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_title']);
		}


		if ($title_id)
		{

			$sql = "UPDATE " . TITLE_INFOS_TABLE . " 
				SET title_info = '" . str_replace("\'", "''", $name) . "', date_format = '" . str_replace("\'", "''", $date) . "', admin_auth = $admin, mod_auth = $mod, poster_auth = $poster
				WHERE id = $title_id";

			$message = $lang['Title_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . TITLE_INFOS_TABLE . " (title_info, admin_auth, mod_auth, poster_auth, date_format)
				VALUES ('" . str_replace("\'", "''", $name) . "', $admin, $mod, $poster,'" . str_replace("\'", "''", $date) . "')";

			$message = $lang['Title_added'];
		}
		
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update/insert into title_infos table", "", __LINE__, __FILE__, $sql);
		}

		$message .= "<br /><br />" . sprintf($lang['Click_return_titleadmin'], "<a href=\"" . append_sid("admin_quick_title.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);

	}
	else if( $mode == "delete" )
	{
		//
		// Ok, they want to delete their title
		//
		
		if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
		{
			$title_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
		}
		else
		{
			$title_id = 0;
		}
		
		if( $title_id )
		{
			$sql = "DELETE FROM " . TITLE_INFOS_TABLE . "  
				WHERE id = $title_id";
			
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete title data", "", __LINE__, __FILE__, $sql);
			}
			

			$message = $lang['Title_removed'] . "<br /><br />" . sprintf($lang['Click_return_titleadmin'], "<a href=\"" . append_sid("admin_quick_title.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_title']);
		}
	}
	else
	{
		//
		// They didn't feel like giving us any information. Oh, too bad, we'll just display the
		// list then...
		//
		$template->set_filenames(array(
			"body" => "admin/title_list_body.tpl")
		);
		
		$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . "  
			ORDER BY id ASC";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain title data", "", __LINE__, __FILE__, $sql);
		}
		
		$title_rows = $db->sql_fetchrowset($result);
		$title_count = count($title_rows);
		
		$template->assign_vars(array(
			"S_TITLE_ACTION" => append_sid("admin_quick_title.$phpEx"),
			"ADMIN_TITLE" => $lang['Title_infos'],
			"ADMIN_TITLE_EXPLAIN" => $lang['Quick_title_explain'],
			"HEAD_TITLE" => $lang['Title_head'],
			"HEAD_AUTH" => $lang['Title_auth'],
			"ADD_NEW" => $lang['Add_new'],
			"HEAD_DATE" => $lang['Date_format'],
			"L_EDIT" => $lang['Edit'],
			"L_DELETE" => $lang['Delete'])
		);
		
		for( $i = 0; $i < $title_count; $i++)
		{

			$title_id=$title_rows[$i]['id'];
			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			$perm = ($title_rows[$i]['admin_auth']==1) ? $lang['Administrator'].'</br>' : '';
			$perm .= ($title_rows[$i]['mod_auth']==1) ? $lang['Moderator'].'</br>' : '';
			$perm .= ($title_rows[$i]['poster_auth']==1) ? $lang['Topic_poster'] : '';
			$template->assign_block_vars("title", array(
				"ROW_COLOR" => "#" . $row_color,
				"ROW_CLASS" => $row_class,
				"TITLE" => $title_rows[$i]['title_info'],
				"PERMISSIONS" => $perm,
				"DATE_FORMAT" => $title_rows[$i]['date_format'],


	
				"U_TITLE_EDIT" => append_sid("admin_quick_title.$phpEx?mode=edit&amp;id=$title_id"),
				"U_TITLE_DELETE" => append_sid("admin_quick_title.$phpEx?mode=delete&amp;id=$title_id"))
			);
		}
	}
}
else
{
	//
	// Show the default page
	//
		$template->set_filenames(array(
			"body" => "admin/title_list_body.tpl")
		);

		$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . "  
			ORDER BY id ASC LIMIT $start, 40";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain title data", "", __LINE__, __FILE__, $sql);
		}
		
		$title_rows = $db->sql_fetchrowset($result);
		$title_count = count($title_rows);

		$sql = "SELECT count(*) AS total
		FROM " . TITLE_INFOS_TABLE;
		if ( !($result = $db->sql_query($sql)) ) 
	   { 
	      message_die(GENERAL_ERROR, 'Error getting total informations for title', '', __LINE__, __FILE__, $sql); 
	   }

	   if ( $total = $db->sql_fetchrow($result) ) 
	   { 
	      $total_records = $total['total']; 
	
	      $pagination = generate_pagination("admin_quick_title.$phpEx?mode=$mode", $total_records, 40, $start). ' '; 
	   } 

		$template->assign_vars(array(
		"ADMIN_TITLE" => $lang['Title_infos'],
		"ADMIN_TITLE_EXPLAIN" => $lang['Quick_title_explain'],
		"HEAD_TITLE" => $lang['Title_head'],
		"HEAD_AUTH" => $lang['Title_auth'],
		"HEAD_DATE" => $lang['Date_format'],
		"L_EDIT" => $lang['Edit'],
		"L_DELETE" => $lang['Delete'],
		'PAGINATION' => $pagination,
		"ADD_NEW" => $lang['Add_new'],
		"S_TITLE_ACTION" => append_sid("admin_quick_title.$phpEx"))
		);
		
		for( $i = 0; $i < $title_count; $i++)
		{

			$title_id=$title_rows[$i]['id'];
			$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			$perm = ($title_rows[$i]['admin_auth']==1) ? $lang['Administrator'].'</br>' : '';
			$perm .= ($title_rows[$i]['mod_auth']==1) ? $lang['Moderator'].'</br>' : '';
			$perm .= ($title_rows[$i]['poster_auth']==1) ? $lang['Topic_poster'] : '';

			$template->assign_block_vars("title", array(
				"ROW_COLOR" => "#" . $row_color,
				"ROW_CLASS" => $row_class,
				"TITLE" => $title_rows[$i]['title_info'],
				"PERMISSIONS" => $perm,
				"DATE_FORMAT" => $title_rows[$i]['date_format'],

				"U_TITLE_EDIT" => append_sid("admin_quick_title.$phpEx?mode=edit&amp;id=$title_id"),
				"U_TITLE_DELETE" => append_sid("admin_quick_title.$phpEx?mode=delete&amp;id=$title_id"))
			);	}
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>