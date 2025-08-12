<?php

$strAMenusLogs		= 'Logs';
$strAMenusNotify        = 'Notify';
$strAMenusAllUsr        = 'All Users';
$strAMenusSys           = 'System Status';
$strAMenusAddAc		= 'Add Account';
$strAMenusOnlineUsr	= 'Online Users';
$strAMenusUsr           = 'User Management';
$strAMenusUsrb          = 'Userinfo';
$strAMenusProfile		= 'Profile Creator';
$strAMenusUnactUsr	= 'Unactivated Users';
$strAMenusOnlMan		= 'Online Manual';
$strAMenusAdminstrators	= 'Adminstators';
$strAMenusConfiguration = 'Configuration';
$strAdminWelcome		= "Your $Config_sitename Admin Panel";
$strAMenusModerate   = 'Moderate Comments';
$strCategoryMngt		= 'Category Management';

$strAdminNoAccess       = 'Sorry you don\'t have access to the Admin Panel';
$strAdminLoggedOff	= 'logged off';
$strAdminNotifyOpt2     = 'Unactivated';
$strAdminNotifyOpt3     = 'Blocked';
$strAdminNotifyOpt4     = 'Activated';
$strSettingsName7b      = 're-type password';

$strActivate		= 'activate';
$strSendCode		= 'send code';
$strMail			= 'Mail';
$strRevising		= 'Revising';
$strAdmin		 	= 'Admin';
$strPending			= 'pending';
$strProfile			= 'Profile';
$strSpace			= 'space';
$strFile		 	= 'File';
$strLogoff			= 'logoff';
$strField			= 'field';
$strInter		 	= 'Inter';
$strLogs		 	= 'Logs';
$strAdminstration  	= 'Adminstration';
$strAddAccount     	= 'Register';
$strAdminAlbums1	 	= 'System only has 1 album, more needed to move/copy...';
$strRegisterName7  	= 'Account Setup done successfully';


// for config.php
if(preg_match ("/(config)/i", $SCRIPT_NAME))
{
$strConfigLangCmt1   = "Don't use + or | signs in $strName or $strID";
$strConfigLangCmt2   = '(e.g. English)';
$strConfigLangCmt3   = '(e.g. eng)';
$strConfigLangCmt4   = 'Only one Language, can\'t be deleted';


$strConfigCmt1	   = 'Configuration saved';
$strConfigCmt2	   = 'You changed the space calculation scheme, you need to revise the database';
$strConfigCmt3	   = 'No Admin name provided';
$strConfigCmt4	   = 'Invalid Admin email address';
$strConfigCmt5	   = 'No System Url provided';
$strConfigCmt6	   = 'No Datapath';
$strConfigCmt7	   = 'No CGIDIR';
$strConfigCmt8	   = 'No Imagedir';
$strConfigCmt9	   = 'No Logout Time provided';
$strConfigCmt10	   = 'No encryption key provided';
$strConfigCmt11	   = 'Invalid encryption key, can contain digits & chars only';
$strConfigCmt12	   = 'No default space provided';
$strConfigCmt12b	   = 'No default no. of photos provided';
$strConfigCmt13	   = 'No default no. of albums provided';
$strConfigCmt14	   = 'No default no. of reminders provided';
$strConfigCmt15	   = 'No Minimum Show provided';
$strConfigCmt16	   = 'No maxshow of thumbnails provided';
$strConfigCmt17	   = 'No reminder msg limit';
$strConfigCmt18	   = 'No maximum size of uploaded file';
$strConfigCmt19	   = 'No Ecards Days provided';
$strConfigCmt20	   = 'No Unactivated Days provided';
$strConfigCmt21	   = 'No abuse mail link';
$strConfigCmt22	   = 'No allowed types in list';
$strConfigCmt23	   = 'No allowed types Show';
$strConfigCmt24	   = 'No width of short thumb';
$strConfigCmt25	   = 'No height of short thumb';
$strConfigCmt26	   = 'No width of long thumb';
$strConfigCmt27	   = 'No height of long thumb';
$strConfigCmt28	   = 'No maximum image width';
$strConfigCmt29	   = 'No maximum image height';
$strConfigCmt30	   = 'Default Language should be Admin Lang, Feedback options & Help is not Language Supported';
$strConfigCmt31	   = 'If you use GD 1.6 resize, To avoid: abrupt results, you should not force size and should use 0 in maximum limits.';

$strConfigOpt1       = 'Admin Name';
$strConfigOpt2       = 'Admin Email';
$strConfigOpt3       = 'Site Name';
$strConfigOpt4       = 'System Name';
$strConfigOpt5       = 'Buy line';
$strConfigOpt6       = 'Site Title';
$strConfigOpt7       = 'System Url (path to albinator index.php)<br>add http:// in front';
$strConfigOpt8       = 'Root Directory (optional)';
$strConfigOpt9       = 'cgi-bin (relative from albinator\'s root)';
$strConfigOpt10      = 'Albinator\'s ImageDir (relative path)';
$strConfigOpt11      = 'Data Path';
$strConfigOpt12      = 'Main TableSize (%)';
$strConfigOpt13      = 'Main Table Bgcolor';
$strConfigOpt14      = 'Main Table Bgimage (absolute path)';
$strConfigOpt15      = 'Maximum Inactivity time to logout (in secs)';
$strConfigOpt16      = 'Encryption key (chars & digits)';
$strConfigOpt17      = "Default Space to allot to users ($byteUnits[2])";
$strConfigOpt17b     = 'Default Photo Limit to allot';
$strConfigOpt18      = 'Default Albums Limit to allot';
$strConfigOpt19      = 'Default Reminders Limit to allot';
$strConfigOpt20      = 'Show minimum fields (uploading/ecards)';
$strConfigOpt21      = 'Maximum chars in Reminder Message';
$strConfigOpt22      = 'Maximum Thumbnails to show in one album show page';
$strConfigOpt23      = "Maximum Size of the Uploaded file (in $byteUnits[1])";
$strConfigOpt24      = 'Maximum days to keep ecards';
$strConfigOpt25      = 'Maximum days to keep unactivated accounts';
$strConfigOpt26      = 'Make Logs';
$strConfigOpt27      = 'Logoff all users on System Shutdown';
$strConfigOpt28      = 'Abuse Report Link / mail<br>(add mailto: in front for mail link)';
$strConfigOpt29      = 'Default Prefs flags to give';
$strConfigOpt30      = 'allowed types';
$strConfigOpt30a     = 'Allowed types of images<br>(seperate by comma only without spacing)';
$strConfigOpt31      = "Allowed image Types to show to user";
$strConfigOpt32      = 'Ban user names list<br>(seperate by comma only without spacing)';
$strConfigOpt32b	   = '[don\'t remove temp,system]';
$strConfigOpt33      = 'Msg Footer';
$strConfigOpt33b     = 'Advertise Msg';
$strConfigOpt33c     = 'Block Notify Msg';
$strConfigOpt33d	   = 'Block Notify : Early Msg';
$strConfigOpt35	   = 'Buy Link (in case user is of short of space, albums, etc): he/she be linked here';
$strConfigOpt34a	   = 'Thumbnails (in pixels)';
$strConfigOpt34	   = 'For width long images';
$strConfigOpt37	   = 'For height long images';
$strConfigOpt38	   = 'Width';
$strConfigOpt39	   = 'Height';
$strConfigOpt40	   = 'Maximum width of image (for display)';
$strConfigOpt41	   = 'Maximum height image (for display)';
$strConfigOpt42	   = 'Force Size';
$strConfigOpt43	   = 'Resize using';
$strConfigOpt44      = 'Space Calculation';
$strConfigOpt44a     = 'Thumb only';
$strConfigOpt44b     = 'Intermediate only';
$strConfigOpt44c     = 'Thumb + Intermediate';
$strConfigOpt45	   = 'Default Language';
$strConfigOpt45b	   = 'Force';
$strConfigOpt46	   = 'Show Process Time take in footer';
$strConfigOpt47	   = 'Default User Validity<br>e.g. for giving demo a/c for a week, value = 7';
$strConfigOpt48      = 'Block Notify earlier to';
$strConfigOpt49      = 'Block Notify how days earlier';
$strConfigOpt50	   = 'List private albums in directory listing';
$strConfigOpt51      = 'Ban search terms list<br>(seperate by comma only without spacing)';
$strConfigOpt52      = 'Maximum Slide Show Interval<br><span class="ts">after how many seconds the slide show must progress</span>';
$strConfigOpt53      = 'Maximum Recent Rated Users<br><span class="ts">num of users to show in who recently rated a photo</span>';
$strConfigOpt54      = 'Maximum Top List Limit<br><span class="ts">number of results to show in Top N males/females/latest additions</span>';
$strConfigOpt55      = 'Allow to rate';
$strConfigOpt56      = 'Show ratings';
$strConfigOpt56a     = 'as image graph';
$strConfigOpt56b     = 'as % percentage';
$strConfigOpt57	   = 'Photo Comments Settings (if addon is installed)';
$strConfigOpt57a	   = 'Maximum Character limit';
$strConfigOpt57b	   = 'Parse Urls';
$strConfigOpt57c	   = 'Who can add comment';
$strConfigOpt57d	   = 'Moderate Comments';
$strConfigOpt57e	   = 'Allow dual comments<br><span class="ts">comments on same photo by same member/ip</span>';

$strConfigOpenManual = 'open manual for details';
$strConfigMenus	   = array('', 'Basic System', 'System Paths', 'Default Rules', 'Limits', 'Image Settings', 'Critical Settings');
$strConfigMenusAdvice= array('', '', "$strNote: No trailing slash / in any directory paths", "$strNote: For Limits, 0 = no limit", '', '', '');
$strConfigWelcome    = 'select area to Configure';

$strConfigType1	   = 'Mail Settings';
$strConfigType2	   = 'Critical Configuration';
$strConfigType3	   = 'Other Variables';
}

else if(preg_match ("/(adlogs.php)/i", $SCRIPT_NAME))
{
$strAdminLogsOpt1 = 'User logs';
$strAdminLogsOpt2	= 'Admin Logs';
$strAdminLogsOpt3 = 'Other';
$strAdminLogsOpt4	= 'All Logs';
}

else if(preg_match ("/(notify.php)/i", $SCRIPT_NAME))
{
$strAdminNotifyCmt1 = 'select the group of users you want to notify';
$strAdminNotifyCmt2 = 'Public List, contains the emails recorded from tell friends or ecards, for information sake only.';
$strAdminNotifyCmt3 = 'No send name provided';
$strAdminNotifyCmt4 = 'Invalid sender email address';
$strAdminNotifyCmt5 = 'Mails sent';

$strAdminNotifyOpt1 = 'send all';
$strAdminNotifyOpt5 = 'Public List';
$strAdminNotifyOpt6 = 'Select all';
$strAdminNotifyOpt7 = 'Unselect all';
$strAdminNotifyOpt8 = 'ref-uid';
$strAdminNotifyOpt9 = 'selected users';
$strAdminNotifyOpt10='Msg Footer';
}

else if(preg_match ("/(ecards.php)/i", $SCRIPT_NAME))
{
$strAdminEcardsRules = "<li>$strColor are in format $strText$strColor|bgcolor ( 1|1 $strDefault $strColor)</li>
        <li> for $strAMenusNotify and $strMailStatus 0 $strNo $strSent, 1 - $strSent</li>
        <li> $strMusic 0 = $strNone</li>
        <li> code is for recieving authentication (don't $strChange when $strMailStatus = 1)</li>
        <li> $strDate: first 4=$strYear, $strNext 2=$strMonth, $strNext 2=$strDate</li>";
}

else if(preg_match ("/(reminders.php)/i", $SCRIPT_NAME))
{
$strAdminReminderRules = "<li>$strYear or $strMonth = 0 = $strReminderEveryInfo</li>
<li>$strMailStatus: 1 = $strReminderWhenOpt1, 2 = $strReminderWhenOpt2, 3 = $strReminderWhenOpt3, 0 = unknown error</li>";
}

else if(preg_match ("/(revise.php)|(sysstat.php)/i", $SCRIPT_NAME))
{
$strAdminSysCmt1 = 'System is Shutdown';
$strAdminSysCmt2 = 'All users logged off!';
$strAdminSysCmt3 = 'System is Open';
$strAdminSysCmt4 = 'Shutdown';
$strAdminSysCmt5 = 'Open';

$strAdminSysMsg  = 'We are closed for sometime for maintenance Purposes';
}

else if(preg_match ("/(unact.php)/i", $SCRIPT_NAME))
{
$strAdminUnactMail1  = "Hi,\n\nYour account has been activated by the Adminstrator at %1.\n\nYou can login now at $Config_sitename_url\n\n%2";
$strAdminUnactMail2  = "Hi,\n\n$strAdmin has sent your details for account at %1, \n\n$strUsername: %2 \npassword: %3 \n\nYou can login now at: %4\n\n%5";
}

else if(preg_match ("/(userprofile)/i", $SCRIPT_NAME))
{
$strAdminProfileOpt1 = 'Desc.';


$strAdminProfileOpt2 = 'Default Value';
$strAdminProfileOpt3 = 'Options';
$strAdminProfileCmt1 = 'on large User database all answer deletion may take some time';
$strAdminProfileCmt2 = 'The $strOrder should be a number';
$strAdminProfileCmt3 = 'Options should be atleast 2';
$strAdminProfileCmt4 = 'No options provided for $strField';
$strAdminProfileCmt5 = 'No description provided for $strField';
$strAdminProfileCmt6 = 'No type selected from list';

$strAdminProfileRules='Do not use | (pipe) or , (comma) in any of the field values or options<br>and don\'t use + (plus) sign [for checkbox options]<br>[use comma to only seperate options)';
}

else if(preg_match ("/(usrmngt.php)/i", $SCRIPT_NAME))
{
$strAdminUsrmngtCmt1 = 'find user using one of the following methods:';
$strAdminUsrmngtCmt2 = 'Managing User';
$strAdminUsrmngtCmt3 = 'send details';
$strAdminUsrmngtCmt4 = 'send pass on email change';
$strAdminUsrmngtCmt5 = 'online';
$strAdminUsrmngtCmt6 = 'offline';
$strAdminUsrmngtCmt7 = 'Specified %1 Limit is less than the current %2 created'; // e.g. %1,%2 = album 
$strAdminUsrmngtCmt8 = 'Import Current user database into Albinator tables';
$strAdminUsrmngtCmt9 = 'Database doesnt exists or user/password provided is incorrect';
$strAdminUsrmngtCmt10= 'Table or fields do not exist';
$strAdminUsrmngtCmt11= 'No data found';
$strAdminUsrmngtCmt12= 'No Welcome mail message';
$strAdminUsrmngtCmt13= 'Invalid Validity';

$strAdminUsrmngtOpt1 = 'Search';
$strAdminUsrmngtOpt2 = 'Recently Activated';
$strAdminUsrmngtOpt3 = 'Realname';
$strAdminUsrmngtOpt4 = 'Last Accessed';
$strAdminUsrmngtOpt5 = 'Confirmed Date';
$strAdminUsrmngtOpt6 = 'not confirmed';
$strAdminUsrmngtOpt7 = 'Current Time/Date';
$strAdminUsrmngtOpt8 = 'Time/Date from<br>&nbsp;server clock';
$strAdminUsrmngtOpt9 = 'Total %1 Activated in last %2 days'; // e.g. %1 = users, %2 = 5
$strAdminUsrmngtOpt10= 'unblock';
$strAdminUsrmngtOpt11= 'Import';
$strAdminUsrmngtOpt12= 'Validity';

$strAdminImportOpt1  = 'database name';
$strAdminImportOpt2  = 'database username';
$strAdminImportOpt3  = 'database password';
$strAdminImportOpt4  = 'field names';
$strAdminImportOpt5  = 'already md5';
$strAdminImportOpt6  = 'send welcome mail';
$strAdminImportOpt7  = 'send activation link';
$strAdminImportOpt8  = 'table name';
$strAdminImportOpt9  = 'Rejected Usernames';

$strAdminUserNotFound= 'User not found in db';
$strAdminUserFound   = 'User(s) found';
$strAdminUserStatus  = 'Current Status';

$strSettingsName3    = 'photo limit';
$strSettingsName4    = 'album limit';
$strSettingsName5    = 'reminders limit';
$strSettingsName6    = 'space limit';
$strSettingsName7a   = 'new password';

$strAdminUserMail1   = "Hi %1,\n\nYou changed your email address, therefore for security purposes here is you new password. \n $strUsername: %2 \n password: %3\n\nPlease change it asap.\n\n";
$strAdminUserMail2   = "Hi %1,\n\n Here are your new details: \n $strUsername: %2 \n password: %3\n\nLogin at %4/login.php\n";
}

$strAMenusReminder	= $strMenusReminders;

?>