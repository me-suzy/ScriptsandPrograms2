<?php 
define('IN_PHPBB', true); 
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$userdata = session_pagestart($user_ip, PAGE_STAFF);
init_userprefs($userdata); 

$page_title = $lang['Staff'];
include('includes/page_header.'.$phpEx); 

$template->set_filenames(array(
	'body' => 'staff_body.tpl',
));

$is_auth_ary = array();
$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forum_data);

$sql_categories = "SELECT ug.group_id, f.forum_id, f.cat_id, f.forum_name, c.cat_id, c.cat_title, c.cat_order, g.group_id, g.group_single_user, c.cat_order
		FROM ". AUTH_ACCESS_TABLE ." aa, ". USER_GROUP_TABLE ." ug, ". FORUMS_TABLE ." f, ". CATEGORIES_TABLE ." c, ". GROUPS_TABLE ." g      
		WHERE aa.auth_mod = ". TRUE . " 
			AND g.group_id = aa.group_id
			AND ug.group_id = aa.group_id
			AND f.forum_id = aa.forum_id
			ORDER BY c.cat_order";
			
if( !$result_categories = $db->sql_query($sql_categories) ) 
{ 
	message_die(GENERAL_ERROR, 'Could not query categories.', '', __LINE__, __FILE__, $sql_categories); 
} 
while( $row = $db->sql_fetchrow($result_categories) ) 
{  
		$cat_order = $row['cat_order'];
		$cat_id = $row['cat_id'];
		if( !isset($th_cat_id[$cat_id]) )
		{
		$th_cat_order[$cat_order] = $cat_id;
		$th_cat_title[$cat_id] = '<a href="'. append_sid("index.$phpEx?c=$cat_id") .'" class="forumlink"><u>'. $row['cat_title'] .'</u></a><br />';
		}
}

$sql_forums = "SELECT ug.group_id, f.forum_id, f.cat_id, f.forum_name
		FROM ". AUTH_ACCESS_TABLE ." aa, ". USER_GROUP_TABLE ." ug, ". FORUMS_TABLE ." f
		WHERE aa.auth_mod = ". TRUE . " 
			AND ug.group_id = aa.group_id
			AND f.forum_id = aa.forum_id";

if( !$result_forums = $db->sql_query($sql_forums) ) 
{ 
	message_die(GENERAL_ERROR, 'Could not query forums.', '', __LINE__, __FILE__, $sql_categories); 
} 
while( $frow = $db->sql_fetchrow($result_forums) ) 
{ 

		$cat_id = $frow['cat_id'];
		$forum_id = $frow['forum_id'];
		if( !isset($th_forums_id[$forum_id]) )
		{
		$th_forums_id[$forum_id] = $forum_id;
		$th_forums_cat[$cat_id][$forum_id] = 1;
		$th_forums_inm[$cat_id] = 0;
       	$th_forums_title[$forum_id]	= '<a href="'. append_sid("viewforum.$phpEx?f=$forum_id") .'" class="genmed">'. $frow['forum_name'] .'</a><br />'; 
		}
} 

$sql_modgroups = "SELECT ug.group_id, aa.group_id, g.group_id, g.group_single_user, g.group_type, aa.forum_id, f.cat_id, f.forum_id, g.group_name
		FROM ". AUTH_ACCESS_TABLE ." aa, ". USER_GROUP_TABLE ." ug, ". GROUPS_TABLE ." g, ". FORUMS_TABLE ." f
		WHERE aa.auth_mod = ". TRUE . " 
			AND ug.group_id = aa.group_id
			AND g.group_id = ug.group_id
			AND aa.forum_id = f.forum_id";

if( !$result_modgroups = $db->sql_query($sql_modgroups) ) 
{ 
	message_die(GENERAL_ERROR, 'Could not query modgroups.', '', __LINE__, __FILE__, $sql_categories); 
} 
while( $mrow = $db->sql_fetchrow($result_modgroups) ) 
{ 

		$group_id = $mrow['group_id'];
		$forum_id = $mrow['forum_id'];
		$forum_cat_id = $mrow['cat_id'];
		if( !isset($th_for_mod[$forum_cat_id][$forum_id]) )
		{
		$th_wmod_id[$forum_id] = $forum_id;
		$th_for_mod[$forum_cat_id][$forum_id] = $group_id;
		$th_mod_title[$group_id]	= '<a href="'. append_sid("groupcp.$phpEx?g=$group_id") .'" class="forumlink"><u>'. $mrow['group_name'] .'</u></a><br />'; 
		}
} 


$sql_ranks = "SELECT * FROM ". RANKS_TABLE ." ORDER BY rank_special, rank_min";
if( !($results_ranks = $db->sql_query($sql_ranks)) )
{
	message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql_ranks);
}
$ranksrow = array();
while( $row = $db->sql_fetchrow($results_ranks) )
{
	if ( $row['rank_special'] = 1)
	{
	$ranksrow_id = $row['rank_id'];
	if( !isset($ranksrow_title[$ranksrow_id]) )
		{
		$ranksrow_title[$ranksrow_id] = $row['rank_title'];
		}
	}
}

$sql_users = "SELECT * FROM ". AUTH_ACCESS_TABLE ." aa, ". GROUPS_TABLE ." g, ". USER_GROUP_TABLE ." ug, ". USERS_TABLE ." u
		   	WHERE aa.auth_mod = ". TRUE . " 
			AND aa.group_id = ug.group_id 
			AND ug.user_id = u.user_id";

if( !$result_users = $db->sql_query($sql_users) ) 
{ 
	message_die(GENERAL_ERROR, 'Could not query users.', '', __LINE__, __FILE__, $sql_users); 
} 
while( $staff = $db->sql_fetchrow($result_users) ) 
{ 

		$group_id = $staff['group_id'];
		$user_id = $staff['user_id'];
		$th_user_id[$user_id] = $user_id;

		if( !isset($th_user_in_group[$user_id][$group_id]) )
		{
		$th_user_in_group[$user_id][$group_id] = 1;
		$th_user_link[$user_id]	= '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&u=$user_id") .'" class="gen">'. $staff['username'] .'</a><br />';
		$th_user_real_name[$user_id] = $staff['user_realname'];
		
		
		if (isset($ranksrow_title[$staff['user_rank']]))
		{

		$th_user_rank[$user_id] = $ranksrow_title[$staff['user_rank']];
		
		
		}

			$pmto[$user_id] = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$user_id");
			$pm[$user_id] = '<a href="' . $pmto[$user_id] . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
			$mailto[$user_id] = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $staff['user_id']) : 'mailto:' . $staff['user_email'];
			$mail[$user_id] = ( $staff['user_email'] ) ? '<a href="' . $mailto[$user_id] . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>' : '';

			$msn[$staff[user_id]] = ( $staff['user_msnm'] ) ? '<a href="mailto: '.$staff['user_msnm'].'"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" border="0" /></a>' : '';
			$yim[$staff[user_id]] = ( $staff['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $staff['user_yim'] . '&amp;.src=pg"><img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" /></a>' : '';
			$aim[$staff[user_id]] = ( $staff['user_aim'] ) ? '<a href="aim:goim?screenname=' . $staff['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
			$icq[$staff[user_id]] = ( $staff['user_icq'] ) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto=' . $staff['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" /></a>' : '';

			$www[$staff[user_id]] = ( $staff['user_website'] ) ? '<a href="' . $staff['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '';

		}
					
} 


$sql_admin = "SELECT * FROM ". USERS_TABLE ." u
		   	WHERE user_level = 1";

if( !$result_admin = $db->sql_query($sql_admin) ) 
{ 
	message_die(GENERAL_ERROR, 'Could not query users.', '', __LINE__, __FILE__, $sql_users); 
} 
while( $staff = $db->sql_fetchrow($result_admin) ) 
{ 

		$user_id = $staff['user_id'];
		$th_user_id[$user_id] = $user_id;

		if( !isset($th_user_admin[$user_id]) )
		{
		$th_user_admin[$user_id] = $user_id;
		$th_admin_link[$user_id]	= '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&u=$user_id") .'" class="gen">'. $staff['username'] .'</a><br />';
		$th_admin_real_name[$user_id] = $staff['user_realname'];
			
		if (isset($ranksrow_title[$staff['user_rank']]))
		{
		$th_admin_rank[$user_id] = $ranksrow_title[$staff['user_rank']];
		}

		$pmto[$user_id] = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$user_id");
		$pm[$user_id] = '<a href="' . $pmto[$user_id] . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
		$mailto[$user_id] = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $staff['user_id']) : 'mailto:' . $staff['user_email'];
		$mail[$user_id] = ( $staff['user_email'] ) ? '<a href="' . $mailto[$user_id] . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>' : '';

		$msn[$user_id] = ( $staff['user_msnm'] ) ? '<a href="mailto: '.$staff['user_msnm'].'"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" border="0" /></a>' : '';
		$yim[$user_id] = ( $staff['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $staff['user_yim'] . '&amp;.src=pg"><img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" /></a>' : '';
		$aim[$user_id] = ( $staff['user_aim'] ) ? '<a href="aim:goim?screenname=' . $staff['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
		$icq[$user_id] = ( $staff['user_icq'] ) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto=' . $staff['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" /></a>' : '';

		$www[$user_id] = ( $staff['user_website'] ) ? '<a href="' . $staff['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '';

		}
					
} 
foreach( $th_cat_order as $wert) 
{		$kc = $kc + 1;
        if ($kc > 2) {$kc = 1;}
		else {$kc = 2;}
		
		$row_class = ( !($kc % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		$empty[$wert] = 0;
		$temp_cat_title = $th_cat_title[$wert];
		$template->assign_block_vars('category', array(
		'title' => $temp_cat_title,
		'ROW_CLASS' => $row_class,
		));
	
	if (!isset(	$th_forums_inm[$wert]))
	{
	$km = $kc + 1;
   	if ($km > 2) {$km = 1;}
	else {$km = 2;}
	$mod_row_class = ( !($km % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
    $no_mod = '<a href="#Administrator" class="forumlink"><u>'. $lang[is_no_mod] .'</u></a><br />';
	$template->assign_block_vars('category.mods', array(
	'none' => $no_mod,
	'ROW_CLASS' => $mod_row_class,
	));	
	}
	else
	{
	foreach ( $th_forums_id as $bump) 
	{	

		if( isset($th_forums_cat[$wert][$bump]))
		{
		
			$template->assign_block_vars('category.forums', array(
			'title' => $th_forums_title[$bump],
			
			));	
	

		}

	}
	
    	    $km = $kc + 1;
        	if ($km > 2) {$km = 1;}
			else {$km = 2;}

	foreach ( $th_wmod_id as $temp) 
	{				

		$mod_row_class = ( !($km % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$group_id_temp = $th_for_mod[$wert][$temp];
				
		if( !isset($done[$wert][$group_id_temp]))
		{
		$template->assign_block_vars('category.mods', array(
		'title' =>  $th_mod_title[$group_id_temp],
		'ROW_CLASS' => $mod_row_class,
		));	
		$done[$wert][$group_id_temp] = 1;
		}
	    



		foreach ( $th_user_id as $tempuser)
		{
			if( !isset($doneuser[$wert][$group_id_temp][$tempuser]))
			{
			if ( isset($th_user_in_group[$tempuser][$group_id_temp]))
			{
		
		
			$template->assign_block_vars('category.mods.users', array(
			'LINK' =>  $th_user_link[$tempuser],
			'REAL_NAME' => $th_user_real_name[$tempuser],
			'RANK' => 	$th_user_rank[$tempuser],
			'PM' => $pm[$tempuser],
			'EMAIL' => $mail[$tempuser],
			'MSN' => $msn[$tempuser],
			'YIM' => $yim[$tempuser],
			'AIM' => $aim[$tempuser],
			'ICQ' => $icq[$tempuser],
			'WWW' => $www[$tempuser],
		
			));	
			$doneuser[$wert][$group_id_temp][$tempuser] = 1;
			}
    		}
		

		
		}

	}
	}



}

$ka = $km;
if ($ka > 2) {$ka = 1;}
else {$ka = 2;}

foreach ($th_user_admin as $tempadmin)
{
			$ka = $ka + 1;
			if ($ka > 2) {$ka = 1;}
			else {$ka = 2;}

			if( !isset($doneadmin[$tempadmin]))
			{
		    $admin_row_class = ( !($ka % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			$template->assign_block_vars('admin', array(
			'ROW_CLASS' => $admin_row_class,
			'LINK' =>  $th_admin_link[$tempadmin],
			'REAL_NAME' => $th_admin_real_name[$tempadmin],
			'RANK' => 	$th_admin_rank[$tempadmin],
			'PM' => $pm[$tempadmin],
			'EMAIL' => $mail[$tempadmin],
			'MSN' => $msn[$tempadmin],
			'YIM' => $yim[$tempadmin],
			'AIM' => $aim[$tempadmin],
			'ICQ' => $icq[$tempadmin],
			'WWW' => $www[$tempadmin],
		
			));	
			$doneadmin[$tempadmin] = 1;
			}
    		

}



$template->assign_vars(array( 
	'L_CATEGORY' => $lang['Category'],
	'L_FORUMS' => $lang['Staff_forums'],
	'L_ISNOMOD' => $lang['is_no_mod'],
	'L_MODERATORS' =>  $lang['Moderators'],
	'L_ADMINISTRATOR' => $lang['Auth_Administrators'],
	'L_CONTACT' => $lang['Staff_contact'],
	'L_MESSENGER' => $lang['Staff_messenger'],
	'L_WWW' => $lang['Website'],
	

));


$template->pparse('body');
include('includes/page_tail.'.$phpEx); 
?>