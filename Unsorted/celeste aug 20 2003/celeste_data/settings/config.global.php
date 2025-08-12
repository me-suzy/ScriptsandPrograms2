<?php
/**
 * Project Source File
 * Celeste 2003 1.00 Build 0011
 * Jul 19, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2003 CelesteSoft.com. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

//define ('SET_DEBUG_LEVEL', E_ALL);
define('SET_VERSION', '1.1.0');
define ('SET_DEBUG_LEVEL', E_ALL ^ E_NOTICE);

define('SET_DATABASE_HOST', 'localhost');
define('SET_DATABASE_USER', 'root');
define('SET_DATABASE_PASSWORD', '');
define('SET_DATABASE_DBNAME', 'celeste2003');

define('SET_ALLOW_TITLE_POSTS',1);
define('SET_ALLOW_TITLE_RATING',2);

/**
 * Open / Close
 */
define('SET_BOARD_CLOSE',0);
define('SET_BOARD_CLOSE_MESSAGE','System Updating');

/**
 * 
 */
define('SET_DEFAULT_CHARSET','ISO-8859-1');

define('SET_BAN_IP',0);
define('SET_ONLINE_DURATION',900);

define('SET_TITLE','Celeste 2003');
define('SET_FORUM_URL','http://www.celestesoft.com/forum/');
define('SET_GZIP_LEVEL',2);
define('SET_TEMPLATE_TABLE','celeste_template');
define('SET_USE_TEMPLATE_CACHE',1);

define('SET_BENCH_TIME',1);
define('SET_MAX_EXECUTION_TIME',15);

define('SET_TOPIC_CACHE_LEVEL',-1);
// 0 : disabled
// 1 : cache topics in public forum into HTTP accessible dir
// 2 : cache topics in public forum into HTTP accessible dir
//       and topics in private forum into HTTP inaccessible dir
// 3 : cache all topics into HTTP accessible dir

/**
 * Cookie
 */
define('SET_COOKIE_HEADER',ce_);
define('SET_COOKIE_SCOPEDIR', '');
define('SET_COOKIE_LIFETIME',31536000);

/**
 * Register
 */
define('SET_ALLOW_MULTI_REG',1);
// allow registered user to register

define('SET_REG_METHOD',0);
// 0 : account immediately available after registeration.
// 1 : account is sent to guest by email. and user has to activate accound before use.
// 2 : password is generated and send.
// 3 : account is set to guest. user need to wait for the admin to activate it.

define('SET_ALLOW_DUPE_EMAIL',1);
define('SET_ENABLE_REG',1);

/**
 * Time / Date
 */
define('SET_TIME_ZONE_OFFSET',-60);
define('SET_DATE_FORMAT','Y-m-d');
define('SET_TIME_FORMAT','M j Y, g:i:s A');

/**
 * Email
 */
define('SET_BOARD_EMAIL','celeste@yourdomain.com');
define('SET_ADMIN_EMAIL','celeste@yourdomain.com');
define('SET_EMAIL_SENDER','Celeste 2003 Mailer');
define('SET_ENABLE_EMAIL',1);
define('SET_DELAY_SENDMAIL',1);

/**
 * Guest
 */
define('SET_GUEST_NAME','Guest');
define('SET_ALLOW_GUEST',1);
define('SET_ALLOW_GUEST_VIEW_USER',1);
define('SET_ALLOW_GUEST_VIEW_USER_LIST',1);

/**
 * Post
 */
define('SET_FLOOD_CONTROL_TIME',10);
define('SET_ALLOW_FLASH',1);
define('SET_ALLOW_SMILE',1);
define('SET_MAX_POST_LENGTH',10240);

/**
 * Signature
 */
define('SET_ALLOW_CETAG_SIGN',1);
define('SET_ALLOW_IMG_SIGN',1);
define('SET_ALLOW_FLASH_SIGN',1);
define('SET_ALLOW_HTML_SIGN',0);
define('SET_ALLOW_IMG_SIGN_MAX',1);
define('SET_ALLOW_SMILE_SIGN',1);

/**
 * P.M.
 */
define('SET_PM_MAX_PMS',40);
define('SET_PM_MAX_LENGTH',512);
define('SET_PM_MAX_RECIEVERS',5);
define('SET_PM_AUTO_CHECK',1);

/**
 * Avatar
 */
define('SET_ALLOW_USER_UPLOAD_AVATAR',1);
define('SET_MIN_POST_TO_UPLOAD_AVATAR',0);
define('SET_MIN_RATING_TO_UPLOAD_AVATAR',0);
define('SET_MAX_AVATAR_HEIGHT',80);
define('SET_MAX_AVATAR_WIDTH',80);
define('SET_MAX_AVATAR_FILESIZE',1048576);

/**
 * Upload
 */
define('SET_ALLOW_UPLOAD_TYPE','gif jpg exe php sql zip rar png pdb doc dir');
define('SET_ALLOW_UPLOAD_SIZE',1048576);
define('SET_DIRECT_ATT',1);
define('SET_DIRECT_ATT_DISALLOW_TYPE','php dir');
define('SET_ATTACH_MAX_REQ_RATING',20);
define('SET_ATTACH_DL_PAY_RATING',1);

/**
 * Customization
 */
define('SET_DEFAULT_READMODE','flat');
define('SET_TOPIC_PP',25);
define('SET_POST_PP',15);
define('SET_USER_PP',20);
define('SET_HOT_TOPIC',15);
define('SET_FAST_REPLY',1);
define('SET_POST_REVIEW_NUMBER',6);
define('SET_MAX_FAVORITES',20);

/**
 * Poll
 */
define('SET_MAX_POLL_OPTIONS', 10);

/**
 * Redirect
 */
define('SET_FORWARD_TIME',5);
define('SET_DISPLAY_REDIRECT_PAGE',1);

/**
 * Online List
 */
define('SET_DISPLAY_INDEX_ONLINELIST',1);
define('SET_ONLINE_PER_LINE',15);
define('SET_DISPLAY_FORUM_ONLINELIST',0);

/**
 * Appearance
 */
define('SET_INDEX_TITLE','Forum Index');
define('SET_NEW_TOPIC_TITLE','New Topic');
define('SET_REPLY_TOPIC_TITLE','New Reply');
define('SET_LOGIN_PAGE_TITLE','Log In');
define('SET_LOGOUT_PAGE_TITLE','Log Out');
define('SET_ATTACHMENT_DOWN_TITLE','Download Attachment');
define('SET_REPLY_HEADER','Re:');
define('SET_EDIT_POST_TITLE','Edit Post');
define('SET_USER_VIEW_TITLE','View User Info');
define('SET_USER_CP_TITLE','User Control Panel');
define('SET_POST_DEL_TITLE','Delete Post');
define('SET_SEARCH_TITLE','Search Forum');
define('SET_REQ_PWD_TITLE','Request For Password');
define('SET_ACTIVATE_ACCOUNT_TITLE','Activate Account');
define('SET_EMAIL_TOPIC_TITLE','Email The Topic To Your Friend');
define('SET_SHOWNEW_TITLE','New Posts Since Last Visit');
define('SET_NEW_POLL_TITLE','New Poll');
define('SET_EDIT_POLL_TITLE','Edit Poll');
define('SET_ANNOUNCEMENT_TITLE','Announcement');
define('SET_MEMBER_LIST_TITLE','Member List');
define('SET_SENDMAIL_TITLE','Send Email');
define('SET_PM_INBOX_TITLE','Private Messages - Inbox');
define('SET_PM_OUTBOX_TITLE','Private Messages - Outbox');
define('SET_PM_CONTACT_LIST','Private Messages - Contact List');
define('SET_PM_IGNORE_LIST','Private Messages - Black List');
define('SET_PM_NEW_TITLE','Private Messages - New');

define('SET_ON','On');
define('SET_OFF','Off');
define('SET_ONLINE','Online');
define('SET_OFFLINE','Offline');
define('SET_CAN','can');
define('SET_CANNOT','cannot');
define('SET_USER_VIEW_NONE','<i>not available</i>');
define('SET_ELITE_STRING','<font color=#FF0000>[E]</font>');

define('SET_TABLE_WIDTH','98%');
define('SET_BG_COLOR','#98b2cc');
define('SET_BORDER_COLOR','#EDECED');
define('SET_TOPICROW_COLOR','#54659C');
define('SET_CATEROW_COLOR','#98b2cc');
define('SET_MAIN_COLOR1','#FFFFFF');
define('SET_MAIN_COLOR2','#F5F5FF');
define('SET_INNER_COLOR','#FFFFFF');
define('SET_HIGHLIGHT_COLOR','red');

define('SET_QUOTE_BORDER_COLOR','#000000');
define('SET_QUOTE_INNER_COLOR','#e8f4ff');
define('SET_ROOT_CODE', 'e138262cdff78ec6b6db6a3183fc28f4');

define('SET_CODE_BORDER_COLOR', '#000066');
define('SET_CODE_INNER_COLOR', '#f4f4f4');

define('SET_USE_PCONNECT',0);
define('SET_REGISTER_TITLE', 'Register');

define('SET_LOGIN_ANTI_SPAM',0);
define('SET_REG_ANTI_SPAM',1);