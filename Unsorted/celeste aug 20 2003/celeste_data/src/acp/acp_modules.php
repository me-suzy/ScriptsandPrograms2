<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

$acp_group = array(
'global'=>'Global Settings',
'forum'=>'Forum Manager',
'post'=>'Posts & Topics',
'user'=>'Users & Groups',
);


$acp_category = array();
$acp_category['global'] = array(
1=>'System Settings',
2=>'Appearance Settings',
4=>'Ban',
3=>'Cache & Data' 
);
$acp_category['forum'] = array(
1=>'Categories & Forums',
2=>'Update Counters',
3=>'Announcement',
4=>'Permissions'
);
$acp_category['post'] = array(
1=>'Topic Manager',
2=>'Post Managers',
3=>'Attachment Manager'
);
$acp_category['user'] = array(
1=>'User Manager',
2=>'Email',
3=>'Group Manager',
6=>'P.M.'
);

$acp_module = array();
$acp_module['global'] = array();
$acp_module['forum'] = array();
$acp_module['post'] = array();
$acp_module['user'] = array();

$acp_module['global'][1] = array(
'global::oc'=>'Open/Close Celeste',
'global::general'=>'General Settings',
'global::mis'=>'Miscellaneous Settings',
'user::title'=>'User Titles/Ranks',
'post::censoredword'=>'Censor Setting',
'post::smile'=>'Smile Tag Setting'
);
$acp_module['global'][2] = array(
'app::set'=>'Template Variables',
'app::editor'=>'Template Editor',
'app::new'=>'Add new template'
);
$acp_module['global'][3] = array(
'cache::clear'=>'Clear Cache',
'log'=>'View Logs'
);
$acp_module['global'][4] = array(
'ban::uname'=>'UserName Censor Settings',
'ban::ip'=>'Ban IP Address'
);


$acp_module['forum'][1] = array(
'forum::man'=>'Manage Center',
'forum::add'=>'Add Category/Forum',
'forum::merge'=>'Merge Forums'
);
$acp_module['forum'][2] = array(
'forum::update'=>'Update Counters'
);
$acp_module['forum'][3] = array(
'ann::add'=>'Add Announcement',
'ann'=>'View Announcements'
);
$acp_module['forum'][4] = array(
'per::edit'=>'Add Permission',
'per::view'=>'View Permissions'
);

$acp_module['post'][1] = array(
'topic::massdel'=>'Mass Delete',
'topic::move'=>'Mass Move',
//'topic::elite'=>'Download Elite Topics'
);
$acp_module['post'][2] = array(
'topic::massdel'=>'Mass Delete'
);
$acp_module['post'][3] = array(
'attach'=>'View Attachments',
'attach::move'=>'Move Attachments'
);

$acp_module['user'][1] = array(
'user::list'=>'View User List',
'user::act'=>'Activate Users',
'user::act&delete_all=1'=>'Delete Inactive Users',
'user::edit'=>'Add New User',
'user::massdel'=>'Mass Delete',
'user::massgroup'=>'Mass Group',
'user::guest'=>'View Guests'
);
$acp_module['user'][2] = array(
'user::mail'=>'Bulk Mails',
'user::mail::send'=>'Send Delayed Mails'
);
$acp_module['user'][3] = array(
'group::list'=>'View Group List',
'group::edit'=>'Add New Group'
);
$acp_module['user'][6] = array(
'pm::view'=>'View PMs',
'pm::massdel'=>'Mass Delete'
);