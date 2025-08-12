<?php
/** @file story.inc.php
 *
 *	This file contains constants relating to 
 *	story objects such as SSStory, SSScene, etc.
 *	
 *	Copyright (C) 2004  Karim Shehadeh
 *	
 *	This program is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU General Public License
 *	as published by the Free Software Foundation; either version 2
 *	of the License, or (at your option) any later version.
 *	
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *	
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *	
 *	@version 0.1
 *	@date October, 2003	
 */

////////////////////////////////////////////////
/////////// DISCUSSION FORUM IDS
////////////////////////////////////////////////

/** The version of StoryStream currently running */
define ('SS_VERSION', '0.3.2');

/** This is the discussion forum used internally to discuss stories */
define ('FORUM_STORY', 1);

/** This is the discussion forum used internally to discuss scenes */
define ('FORUM_SCENE', 2);

////////////////////////////////////////////////
/////////// FORM SUBMIT ACTIONS
////////////////////////////////////////////////
define ('SUBMIT_ACTION_ADD_SCENE', "Add Scene");
define ('SUBMIT_ACTION_EDIT_SCENE', "Edit Scene");
define ('SUBMIT_ACTION_ADD_STORY', "Add Story");
define ('SUBMIT_ACTION_EDIT_STORY', "Edit Story");
define ('SUBMIT_ACTION_ADD_FORK', "Add Fork");
define ('SUBMIT_ACTION_EDIT_FORK', "Edit Fork");
define ('SUBMIT_ACTION_RATE', "Post Review");
define ('SUBMIT_ACTION_CLEAR_RATING', "Clear Review");
define ('SUBMIT_ACTION_CLASSIFY', "Post Classification");
define ('SUBMIT_ACTION_CLEAR_GENRE', "Clear Classification");
define ('SUBMIT_ACTION_SUBMIT_ALL', "Submit All");
define ('SUBMIT_ACTION_CLEAR_ALL', "Clear All");
define ('SUBMIT_ACTION_POST', "Submit Comment");
define ('SUBMIT_ACTION_ADD_GROUP', "Add Group");
define ('SUBMIT_ACTION_EDIT_GROUP', "Edit Group");
define ('SUBMIT_ACTION_INVITE_USERS', "Invite User(s)");

////////////////////////////////////////////////
/////////// SUBMIT ACTION PARAMETERS
////////////////////////////////////////////////
define ('SUBMIT_ACTION', 'submit');
define ('SUBMIT_ACTION_FORK_ID', "fork_id");
define ('SUBMIT_ACTION_SCENE_ID', "scene_id");
define ('SUBMIT_ACTION_STORY_ID', "story_id");

////////////////////////////////////////////////
/////////// PAGE ACTIONS
////////////////////////////////////////////////
define ('PAGE_ACTION_VIEW', "view");
define ('PAGE_ACTION_ADD', "add");
define ('PAGE_ACTION_EDIT', "edit");
define ('PAGE_ACTION_DELETE', "delete");
define ('PAGE_ACTION_UNDELETE', "undelete");
define ('PAGE_ACTION_BROWSE', "browse");
define ('PAGE_ACTION_READ_REVIEWS', "reviews");
define ('PAGE_ACTION_BAN_USER', "ban");
define ('PAGE_ACTION_UNBAN_USER', "unban");
define ('PAGE_ACTION_UNINVITE_USER', "uninvite");
define ('PAGE_ACTION_ACCEPT_INVITATION', "accept");
define ('PAGE_ACTION_DECLINE_INVITATION', "decline");
define ('PAGE_ACTION_WITHDRAW', "withdraw");
define ('PAGE_ACTION_BOOKMARK_MANAGER', 'bm');

////////////////////////////////////////////////
/////////// PAGE ACTION PARAMETERS
////////////////////////////////////////////////
define ('PAGE_ACTION', "a");
define ('PAGE_ACTION_FORK_ID', "fork_id");
define ('PAGE_ACTION_SCENE_ID', "scene_id");
define ('PAGE_ACTION_STORY_ID', "story_id");
define ('PAGE_ACTION_BOOKMARK_ID', "bmi");
define ('PAGE_ACTION_ANNOUNCE_ID', 'aid');
define ('PAGE_ACTION_GROUP_ID', 'gid');
define ('PAGE_ACTION_CLASSIFICATION', 'c');

////////////////////////////////////////////////
/////////// SITE ROOTS
////////////////////////////////////////////////
define ('READING_ROOT', $GLOBALS['SCRIPT_ROOT'].'read');
define ('AUTHORING_ROOT', $GLOBALS['SCRIPT_ROOT'].'authors');
define ('MEMBER_ROOT', $GLOBALS['SCRIPT_ROOT'].'members');

////////////////////////////////////////////////
/////////// SITE SECTIONS
////////////////////////////////////////////////

/** Working in the scene section. */
define ('SECTION_SCENE', 1);

/** Currently working with stories */
define ('SECTION_STORY', 2);

/** Working on the admin front page. */
define ('SECTION_ADMIN_MAIN', 3);

/** Working on the reading page. */
define ('SECTION_READ', 4);

/** Working on the front page. */
define ('SECTION_MAIN', 5);

/** Working on the front page. */
define ('SECTION_MEMBER', 6);

////////////////////////////////////////////////
/////////// STORY STATUS
////////////////////////////////////////////////

/** The story is currently active and available for viewing and editing. */
define ('STORY_STATUS_ACTIVE', 1);

/** The story is currently available for viewing but not editing */
define ('STORY_STATUS_DRAFT', 2);

/** The story is not available for viewing or editing */
define ('STORY_STATUS_DELETED', 4);

////////////////////////////////////////////////
/////////// STORY STATUS
////////////////////////////////////////////////

/** The originating author can read the story. */
define ('STORY_PERMISSION_AUTHOR_READ', 0x0001);

/** The originating author can write to the story. */
define ('STORY_PERMISSION_AUTHOR_WRITE', 0x0002);

/** A registered user can read the story. */
define ('STORY_PERMISSION_USER_READ', 0x0004);

/** A registered user can write to the story. */
define ('STORY_PERMISSION_USER_WRITE', 0x0008);

/** A guest user can read the story. */
define ('STORY_PERMISSION_GUEST_READ', 0x0010);

/** A guest user can write to the story. */
define ('STORY_PERMISSION_GUEST_WRITE', 0x0020);

/** This permission set allows the author to read and write to the story but no one else can see it or change it */
define ('STORY_PERMISSION_AUTHOR_ONLY', STORY_PERMISSION_AUTHOR_READ|STORY_PERMISSION_AUTHOR_WRITE);

/** This permission set allows the author to read and write to the story and also all registered users can modify the story */
define ('STORY_PERMISSION_REGISTERED_READWRITE', STORY_PERMISSION_AUTHOR_ONLY|STORY_PERMISSION_USER_READ,STORY_PERMISSION_USER_WRITE);

////////////////////////////////////////////////
/////////// STORY TYPES
////////////////////////////////////////////////
/** The story has a beginning scene. */
define ('STORY_TYPE_BEGIN', 1);

/** The story has a beginning scene and an ending scene */
define ('STORY_TYPE_BEGIN_END', 2);

/** The story does not have a set beginning scene or ending scene */
define ('STORY_TYPE_NO_BEGIN_NO_END', 3);

////////////////////////////////////////////////
/////////// SCENE STATUS
////////////////////////////////////////////////

/** The scene is currently active and available for viewing and editing. */
define ('SCENE_STATUS_DRAFT', 1);

/** The scene is no longer being edited and is available for viewing  */
define ('SCENE_STATUS_ACTIVE', 2);

/** The scene has been deleted */
define ('SCENE_STATUS_DELETED', 3);

////////////////////////////////////////////////
/////////// SCENE DATA TYPES
////////////////////////////////////////////////

/** The scene is made up entirely of text (w/HTML possibly)*/
define ('SCENE_DATA_NONE', 1);

/** The scene has an image (JPG) */
define ('SCENE_DATA_IMAGE', 2);

/** The scene has a flash file (SWF) */
define ('SCENE_DATA_FLASH', 3);

/** The scene has a sound file (mp3) */
define ('SCENE_DATA_SOUND', 4);

////////////////////////////////////////////////
/////////// SCENE TYPE
////////////////////////////////////////////////

/** The scene is the first one in a story*/
define ('SCENE_TYPE_BEGINNING', 1);

/** The scene is the last scenes in a story. */
define ('SCENE_TYPE_ENDING', 2);

/** The scene is not the beginning scene nor is it the ending scene. */
define ('SCENE_TYPE_MIDDLE', 3);

////////////////////////////////////////////////
/////////// FORK STATUS
////////////////////////////////////////////////

/** The fork is currently active and available for viewing, editing and for linking. */
define ('FORK_STATUS_ACTIVE', 1);

/** The fork has been deleted */
define ('FORK_STATUS_DELETED', 3);

////////////////////////////////////////////////
/////////// USER STATUS
////////////////////////////////////////////////

/** The user is an active user. */
define ('USER_STATUS_ACTIVE', 1);

/** The user's account was frozen perhaps due to inappropriate submissions */
define ('USER_STATUS_FROZEN', 2);

/** The user has not logged in for so long that they are considered 'stale' */
define ('USER_STATUS_STALE', 3);

/** The user has been deleted */
define ('USER_STATUS_DELETED', 4);

/** The user has not yet confirmed his registration */
define ('USER_STATUS_UNCONFIRMED', 5);

////////////////////////////////////////////////
/////////// GRUOP STATUS
////////////////////////////////////////////////

/** The user is an active user. */
define ('GROUP_STATUS_ACTIVE', 1);

/** The user's account was frozen perhaps due to inappropriate submissions */
define ('GROUP_STATUS_FROZEN', 2);

////////////////////////////////////////////////
/////////// TARGET TYPES
////////////////////////////////////////////////

/** Identifies NULL objects */
define ('OBJECT_TYPE_NONE', 0);

/** Identifies scene objects - SSScene */
define ('OBJECT_TYPE_SCENE', 1);

/** Identifies story objects - SSStory */
define ('OBJECT_TYPE_STORY', 2);

/** Identifies fork objects - SSFork */
define ('OBJECT_TYPE_FORK', 3);

/** Identifies bookmark objects - SSBookmark */
define ('OBJECT_TYPE_BOOKMARK', 4);

/** Identifies classification objects - SSClassification */
define ('OBJECT_TYPE_CLASSIFICATION', 5);

/** Identifies rating objects - SSRating */
define ('OBJECT_TYPE_RATING', 6);

/** Identifies story path objects - SSStoryPath */
define ('OBJECT_TYPE_STREAM', 7);

/** Identifies announcement objects - SSAnnouncement */
define ('OBJECT_TYPE_ANNOUNCEMENT', 8);

/** Identifies group objects - SSGroup */
define ('OBJECT_TYPE_GROUP', 9);

/** Identifies view records - SSView */
define ('OBJECT_TYPE_VIEW', 10);

/** Identifies modification records - SSMod */
define ('OBJECT_TYPE_MOD', 11);

////////////////////////////////////////////////
/////////// USER TYPES
////////////////////////////////////////////////

/** The user can read stories. */
define ('USER_TYPE_REGISTERED', 1);

/** The user is an administrator of the site giving him permission to do all sorts of things. */
define ('USER_TYPE_ADMIN', 3);

////////////////////////////////////////////////
/////////// ITEM TYPES
////////////////////////////////////////////////

/** This is a story item (SSStory) */
define ('ITEM_TYPE_STORY', 1);

/** This is a story item (SSScene) */
define ('ITEM_TYPE_SCENE', 2);

/** This is a story item (SSFork) */
define ('ITEM_TYPE_FORK', 3);

////////////////////////////////////////////////
/////////// MODIFICATION ACTIONS
////////////////////////////////////////////////

/** The object was added to the database */
define ('MOD_ACTION_ADD', 1);

/** The object record was edited in the database */
define ('MOD_ACTION_EDIT', 2);

/** The object record was deleted from the database */
define ('MOD_ACTION_DELETE', 3);


////////////////////////////////////////////////
/////////// GROUP MEMBER TYPES
////////////////////////////////////////////////

/** Defines the user to be the one who started the group */
define ('GROUP_USER_TYPE_ADMIN', 1);

/** Defines the user as a member of the group */
define ('GROUP_USER_TYPE_MEMBER', 2);

/** Defines the user as being invited but not yet joining */
define ('GROUP_USER_TYPE_INVITED', 3);

/** Defines the user as having once been a member, but was removed */
define ('GROUP_USER_TYPE_EXPELLED', 4);

////////////////////////////////////////////////
/////////// GROUP MEMBER ADMIN ACTIONS
////////////////////////////////////////////////

/** Used to ban an existing member */
define ('GROUP_USER_ACTION_BAN', "Ban User");

/** Used to reintegrate a previously banned user */
define ('GROUP_USER_ACTION_UNBAN', "UnBan User");

/** Used to uninvite a previously invited user */
define ('GROUP_USER_ACTION_UNINVITE', "Uninvite User");

////////////////////////////////////////////////
/////////// OBJECT PROPERTIES
////////////////////////////////////////////////

define ('PROP_USERNAME', 'username');
define ('PROP_NAME', 'name');
define ('PROP_DESCRIPTION', 'description');
define ('PROP_FROM_SCENE_ID', 'from_scene_id');
define ('PROP_CHOSEN_COUNT', 'chosen_count');
define ('PROP_STATUS', 'status');
define ('PROP_STORY_ID', 'story_id');
define ('PROP_ID', 'id');
define ('PROP_SYNOPSIS', 'synopsis');
define ('PROP_TYPE', 'type');
define ('PROP_PHPBB_TOPIC_ID', 'phpbb_topic_id');
define ('PROP_SOURCE_FORK_ID', 'source_fork_id');
define ('PROP_END_FORK_ID', 'end_fork_id');
define ('PROP_DATA_TYPE', 'data_type');
define ('PROP_DATA_TEXT', 'data_text');
define ('PROP_DATA_BINARY', 'data_binary');
define ('PROP_DATA_PROPERTIES', 'data_properties');
define ('PROP_LICENSE_URL', 'license_url');
define ('PROP_LICENSE_NAME', 'license_name');
define ('PROP_LICENSE_CODE', 'license_code');
define ('PROP_RATING', 'rating');
define ('PROP_PERMISSION', 'permission');
define ('PROP_DATE', 'date');
define ('PROP_USERS', 'users');
define ('PROP_RANK', 'rank');
define ('PROP_GROUP_ID', 'group_id');
define ('PROP_ALLOW_VIEW', 'allow_nonmember_viewing');

////////////////////////////////////////////////
/////////// INTERNAL ERROR CODES
////////////////////////////////////////////////
define ('ERROR_OBJECT_NOT_FOUND', 'A StoryStream could not be found in the database');
?>
