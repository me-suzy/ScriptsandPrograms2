<?php
/** @file SSDBase.class.php
	This file contains associative arrays of all the 
	relevant tables in the database.
	
	Each table definition is an associative array with 
	the following structure:
	
	name => [table name],
	fields => [array of field names]
	
	@version 0.1
	@date October, 2003
*/

$TABLE_FORK =
	array (
		'name' => 'ss_fork',
		'fields' =>
			array (
				'ID' => 'fork_id',
				'NAME' => 'name',
				'USER_ID' => 'user_id',
				'DESCRIPTION' => 'description',
				'FROM_SCENE' => 'from_scene_id',
				'TO_SCENE' => 'to_scene_id',
				'STORY_ID' => 'story_id',
				'START_MOD' => 'start_mod_id',
				'LAST_MOD' => 'last_mod_id',
				'CHOSEN_COUNT' => 'chosen_count',
				'STATUS' => 'status'
			)
	);

$TABLE_MOD = 
	array (
		'name' => 'ss_modification',
		'fields' =>
			array (
				'ID' => 'mod_id',
				'TARGET_TYPE' => 'subject_type',
				'TARGET_ID' => 'subject_id',
				'STORY_ID' => 'story_id',
				'USER_ID' => 'user_id',
				'MOD_DATE' => 'date',
				'MOD_IP' => 'ip',
				'ACTION' => 'action',
				'MOD_DATA' => 'data',
				'CLIENT_INFO' => 'client_info'
			)
	);

$TABLE_RATING = 
	array (
		'name' => 'ss_rating',
		'fields' =>
			array (
				'ID' => 'rating_id',
				'SUBJECT_TYPE' => 'subject_type',
				'SUBJECT_ID' => 'subject_id',
				'USER_ID' => 'user_id',
				'STORY_ID' => 'story_id',
				'RATING' => 'rating',
				'WEIGHT' => 'weight',
				'CLASSIFICATION_ID' => 'classification_id',
				'DATE' => 'date',
				'RATING_IP' => 'ip',
				'COMMENT' => 'comment',
				'CLIENT_INFO' => 'client_info'
			)
	);

$TABLE_CLASSIFICATION_LIST = 
	array (
		'name' => 'ss_classification_list',
		'fields' =>
			array (
				'CLASSIFICATION' => 'classification_name',
				'SUBJECT_TYPE' => 'subject_type',
				'SUBJECT_ID' => 'subject_id',
			)
	);

$TABLE_CLASSIFICATION = 
	array (
		'name' => 'ss_classification',
		'fields' =>
			array (
				'ID' => 'classification_id',
				'SUBJECT_TYPE' => 'subject_type',
				'SUBJECT_ID' => 'subject_id',
				'STORY_ID' => 'story_id',
				'USER_ID' => 'user_id',
				'RATING_ID' => 'rating_id',
				'CLASSIFICATION' => 'classification',
				'WEIGHT' => 'weight',
				'DATE' => 'date',
				'IP' => 'ip',
				'COMMENT' => 'comment',
				'CLIENT_INFO' => 'client_info'
			)
	);

$TABLE_SCENE = 
	array (
		'name' => 'ss_scene',
		'fields' =>
			array (
				'ID' => 'scene_id',
				'NAME' => 'name',
				'DESCRIP' => 'description',
				'USER' => 'user_id',
				'RATING' => 'rating',
				'STORYID' => 'story_id',
				'SOURCE_FORK' => 'source_fork_id',
				'END_FORK' => 'end_fork_id',
				'TYPE' => 'type',
				'START_MOD' => 'start_mod_id',
				'LAST_MOD' => 'last_mod_id',
				'STATUS' => 'status',
				'DATA_TEXT' => 'data_text',
				'PHPBB_TOPIC_ID' => 'phpbb_topic_id',
				'DATA_TYPE' => 'data_type',
				'DATA_BINARY' => 'data_binary',
				'DATA_PROPS' => 'data_properties',
				'LICENSE_URL' => 'license_url',
				'LICENSE_CODE' => 'license_code',
				'LICENSE_NAME' => 'license_name'
			)
	);
	
$TABLE_STORY = 
	array (
		'name' => 'ss_story',
		'fields' =>
			array (
				'ID' => 'story_id',
				'NAME' => 'name',
				'DESCRIP' => 'description',
				'SYNOPSIS' => 'synopsis',
				'USER' => 'user_id',
				'TYPE' => 'type',
				'RATING' => 'rating',
				'PERM' => 'permission',
				'START_MOD' => 'start_mod_id',
				'LAST_MOD' => 'last_mod_id',
				'STATUS' => 'status',
				'DEGREES' => 'degrees',
				'BEGIN_SCENE' => 'begin_scene_id',
				'END_SCENE' => 'end_scene_id',
				'PHPBB_TOPIC_ID' => 'phpbb_topic_id',
				'DATA_TYPE' => 'data_type',
				'DATA_BINARY' => 'data_binary',
				'DATA_PROPS' => 'data_properties',
				'LICENSE_URL' => 'license_url',
				'LICENSE_CODE' => 'license_code',
				'LICENSE_NAME' => 'license_name',
				'GROUP_ID' => 'group_id'
			)
	);
	
$TABLE_USER = 
	array (
		'name' => 'ss_users',
		'fields' =>
			array (
				'USERNAME' => 'username',
				'FIRST_NAME' => 'first_name',
				'LAST_NAME' => 'last_name',
				'PASSWORD' => 'password',
				'DATE_JOINED' => 'date_joined',
				'DATE_LAST_LOGIN' => 'date_lastlogin',
				'LAST_SESSION_ID' => 'last_session_id',
				'LAST_ACTIVITY_DATE' => 'date_lastactivity',
				'LOGIN_IP' => 'login_ip',
				'LOGIN_CLIENT_INFO' => 'login_client_info',
				'USER_TYPE' => 'user_type',
				'STATUS' => 'status',
				'EMAIL' => 'email',
				'HASH' => 'hash',
				'PHPBB_USER_ID' => 'phpbb_user_id',
				'PHPBB_SESSION_ID' => 'phpbb_session_id',
				'NOTIFY_NEW_STORY' => 'email_notify_new_story',
				'NOTIFY_NEW_SCENE_FORK' => 'email_notify_new_scene_fork',
				'NOTIFY_UPDATES' => 'email_notify_updates',
				'RANK' => 'rank'
			)
	);
	
$TABLE_VIEW = 
	array (
		'name' => 'ss_view',
		'fields' =>
			array (
				'ID' => 'view_id',
				'TARGET_TYPE' => 'target_type',
				'TARGET_ID' => 'target_id',
				'STORY_ID' => 'story_id',
				'USER' => 'user_id',
				'VIEW_DATE' => 'view_date',
				'VIEW_IP' => 'view_ip',
				'CLIENT_INFO' => 'client_info'
			)
	);

$TABLE_BOOKMARKS = 
	array (
		'name' => 'ss_bookmarks',
		'fields' =>
			array (
				'ID' => 'bookmark_id',
				'SUBJECT_TYPE' => 'subject_type',
				'SUBJECT_ID' => 'subject_id',
				'USER_ID' => 'user_id',
				'DATE' => 'date',
				'NAME' => 'name',
				'NOTES' => 'notes',
			)
	);

$TABLE_ANNOUNCEMENTS = 
	array (
		'name' => 'ss_announcements',
		'fields' =>
			array (
				'ID' => 'announcement_id',
				'SUBJECT' => 'subject',
				'TEXT' => 'text',
				'USERNAME' => 'username',
				'DATE' => 'date'
			)
	);
	
$TABLE_GROUP =
	array (
		'name' => 'ss_group',
		'fields' =>
			array (
				'ID' => 'group_id',
				'NAME' => 'name',
				'USERNAME' => 'username',
				'DESCRIPTION' => 'description',
				'DATE' => 'date',
				'STATUS' => 'status',
				'ALLOW_VIEW' => 'allow_nonmember_viewing'
			)
	);

$TABLE_GROUP_USER_MAPPING =
	array (
		'name' => 'ss_user_group_map',
		'fields' =>
			array (
				'ID' => 'group_id',
				'USERNAME' => 'username',
				'DATE' => 'date',
				'TYPE' => 'type'
			)
	);	
	
?>