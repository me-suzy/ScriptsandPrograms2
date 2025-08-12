<?php

// $Id: EN.php 256 2005-11-28 08:33:29Z ryan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Define that this file is loaded
if(!defined('LANGUAGE_LOADED')) {
	define('LANGUAGE_LOADED', true);
}

// Set the language information
$language_code = 'EN';
$language_name = 'English';
$language_version = '2.6';
$language_platform = '2.6.x';
$language_author = 'Ryan Djurovich';
$language_license = 'GNU General Public License';

// Menu titles
$MENU['START'] = 'Start';
$MENU['PAGES'] = 'Pages';
$MENU['MEDIA'] = 'Media';
$MENU['ADDONS'] = 'Add-ons';
$MENU['MODULES'] = 'Modules';
$MENU['TEMPLATES'] = 'Templates';
$MENU['LANGUAGES'] = 'Languages';
$MENU['PREFERENCES'] = 'Preferences';
$MENU['SETTINGS'] = 'Settings';
$MENU['ACCESS'] = 'Access';
$MENU['USERS'] = 'Users';
$MENU['GROUPS'] = 'Groups';
$MENU['HELP'] = 'Help';
$MENU['VIEW'] = 'View';
$MENU['LOGOUT'] = 'Log-out';
$MENU['LOGIN'] = 'Login';
$MENU['FORGOT'] = 'Retrieve Login Details';

// Section overviews
$OVERVIEW['START'] = 'Administration overview';
$OVERVIEW['PAGES'] = 'Manage your websites pages...';
$OVERVIEW['MEDIA'] = 'Manage files stored in the media folder...';
$OVERVIEW['MODULES'] = 'Manage Website Baker modules...';
$OVERVIEW['TEMPLATES'] = 'Change the look and feel of your website with templates...';
$OVERVIEW['LANGUAGES'] = 'Manage Website Baker languages...';
$OVERVIEW['PREFERENCES'] = 'Change preferences such as email address, password, etc... ';
$OVERVIEW['SETTINGS'] = 'Changes settings for Website Baker...';
$OVERVIEW['USERS'] = 'Manage users who can log-in to Website Baker...';
$OVERVIEW['GROUPS'] = 'Manage user groups and their system permissions...';
$OVERVIEW['HELP'] = 'Got a questions? Find your answer...';
$OVERVIEW['VIEW'] = 'Quickly view and browse your website in a new window...';

// Headings
$HEADING['MODIFY_DELETE_PAGE'] = 'Modify/Delete Page';
$HEADING['DELETED_PAGES'] = 'Deleted Pages';
$HEADING['ADD_PAGE'] = 'Add Page';
$HEADING['ADD_HEADING'] = 'Add Heading';
$HEADING['MODIFY_PAGE'] = 'Modify Page';
$HEADING['MODIFY_PAGE_SETTINGS'] = 'Modify Page Settings';
$HEADING['MODIFY_ADVANCED_PAGE_SETTINGS'] = 'Modify Advanced Page Settings';
$HEADING['MANAGE_SECTIONS'] = 'Manage Sections';
$HEADING['MODIFY_INTRO_PAGE'] = 'Modify Intro Page';

$HEADING['BROWSE_MEDIA'] = 'Browse Media';
$HEADING['CREATE_FOLDER'] = 'Create Folder';
$HEADING['UPLOAD_FILES'] = 'Upload File(s)';

$HEADING['INSTALL_MODULE'] = 'Install Module';
$HEADING['UNINSTALL_MODULE'] = 'Uninstall Module';
$HEADING['MODULE_DETAILS'] = 'Module Details';

$HEADING['INSTALL_TEMPLATE'] = 'Install Template';
$HEADING['UNINSTALL_TEMPLATE'] = 'Uninstall Template';
$HEADING['TEMPLATE_DETAILS'] = 'Template Details';

$HEADING['INSTALL_LANGUAGE'] = 'Install Language';
$HEADING['UNINSTALL_LANGUAGE'] = 'Uninstall Language';
$HEADING['LANGUAGE_DETAILS'] = 'Language Details';

$HEADING['MY_SETTINGS'] = 'My Settings';
$HEADING['MY_EMAIL'] = 'My Email';
$HEADING['MY_PASSWORD'] = 'My Password';

$HEADING['GENERAL_SETTINGS'] = 'General Settings';
$HEADING['DEFAULT_SETTINGS'] = 'Default Settings';
$HEADING['SEARCH_SETTINGS'] = 'Search Settings';
$HEADING['FILESYSTEM_SETTINGS'] = 'Filesystem Settings';
$HEADING['SERVER_SETTINGS'] = 'Server Settings';
$HEADING['ADMINISTRATION_TOOLS'] = 'Administration Tools';

$HEADING['MODIFY_DELETE_USER'] = 'Modify/Delete User';
$HEADING['ADD_USER'] = 'Add User';
$HEADING['MODIFY_USER'] = 'Modify User';

$HEADING['MODIFY_DELETE_GROUP'] = 'Modify/Delete Group';
$HEADING['ADD_GROUP'] = 'Add Group';
$HEADING['MODIFY_GROUP'] = 'Modify Group';

// Other text
$TEXT['ADD'] = 'Add';
$TEXT['MODIFY'] = 'Modify';
$TEXT['SETTINGS'] = 'Settings';
$TEXT['DELETE'] = 'Delete';
$TEXT['SAVE'] = 'Save';
$TEXT['RESET'] = 'Reset';
$TEXT['LOGIN'] = 'Login';
$TEXT['RELOAD'] = 'Reload';
$TEXT['CANCEL'] = 'Cancel';
$TEXT['NAME'] = 'Name';
$TEXT['PLEASE_SELECT'] = 'Please select';
$TEXT['TITLE'] = 'Title';
$TEXT['PARENT'] = 'Parent';
$TEXT['TYPE'] = 'Type';
$TEXT['VISIBILITY'] = 'Visibility';
$TEXT['PRIVATE'] = 'Private';
$TEXT['PUBLIC'] = 'Public';
$TEXT['NONE'] = 'None';
$TEXT['NONE_FOUND'] = 'None Found';
$TEXT['CURRENT'] = 'Current';
$TEXT['CHANGE'] = 'Change';
$TEXT['WINDOW'] = 'Window';
$TEXT['DESCRIPTION'] = 'Description';
$TEXT['KEYWORDS'] = 'Keywords';
$TEXT['ADMINISTRATORS'] = 'Administrators';
$TEXT['PRIVATE_VIEWERS'] = 'Private Viewers';
$TEXT['EXPAND'] = 'Expand';
$TEXT['COLLAPSE'] = 'Collapse';
$TEXT['MOVE_UP'] = 'Move Up';
$TEXT['MOVE_DOWN'] = 'Move Down';
$TEXT['RENAME'] = 'Rename';
$TEXT['MODIFY_SETTINGS'] = 'Modify Settings';
$TEXT['MODIFY_CONTENT'] = 'Modify Content';
$TEXT['VIEW'] = 'View';
$TEXT['UP'] = 'Up';
$TEXT['FORGOTTEN_DETAILS'] = 'Forgotten your details?';
$TEXT['NEED_TO_LOGIN'] = 'Need to log-in?';
$TEXT['SEND_DETAILS'] = 'Send Details';
$TEXT['USERNAME'] = 'Username';
$TEXT['PASSWORD'] = 'Password';
$TEXT['HOME'] = 'Home';
$TEXT['TARGET_FOLDER'] = 'Target folder';
$TEXT['OVERWRITE_EXISTING'] = 'Overwrite existing';
$TEXT['FILE'] = 'File';
$TEXT['FILES'] = 'Files';
$TEXT['FOLDER'] = 'Folder';
$TEXT['FOLDERS'] = 'Folders';
$TEXT['CREATE_FOLDER'] = 'Create Folder';
$TEXT['UPLOAD_FILES'] = 'Upload File(s)';
$TEXT['CURRENT_FOLDER'] = 'Current Folder';
$TEXT['TO'] = 'To';
$TEXT['FROM'] = 'From';
$TEXT['INSTALL'] = 'Install';
$TEXT['UNINSTALL'] = 'Uninstall';
$TEXT['VIEW_DETAILS'] = 'View Details';
$TEXT['DISPLAY_NAME'] = 'Display Name';
$TEXT['AUTHOR'] = 'Author';
$TEXT['VERSION'] = 'Version';
$TEXT['DESIGNED_FOR'] = 'Designed For';
$TEXT['DESCRIPTION'] = 'Description';
$TEXT['EMAIL'] = 'Email';
$TEXT['LANGUAGE'] = 'Language';
$TEXT['TIMEZONE'] = 'Timezone';
$TEXT['CURRENT_PASSWORD'] = 'Current Password';
$TEXT['NEW_PASSWORD'] = 'New Password';
$TEXT['RETYPE_NEW_PASSWORD'] = 'Re-type New Password';
$TEXT['ACTIVE'] = 'Active';
$TEXT['DISABLED'] = 'Disabled';
$TEXT['ENABLED'] = 'Enabled';
$TEXT['RETYPE_PASSWORD'] = 'Re-type Password';
$TEXT['GROUP'] = 'Group';
$TEXT['SYSTEM_PERMISSIONS'] = 'System Permissions';
$TEXT['MODULE_PERMISSIONS'] = 'Module Permissions';
$TEXT['SHOW_ADVANCED'] = 'Show Advanced Options';
$TEXT['HIDE_ADVANCED'] = 'Hide Advanced Options';
$TEXT['BASIC'] = 'Basic';
$TEXT['ADVANCED'] = 'Advanced';
$TEXT['WEBSITE'] = 'Website';
$TEXT['DEFAULT'] = 'Default';
$TEXT['KEYWORDS'] = 'Keywords';
$TEXT['TEXT'] = 'Text';
$TEXT['HEADER'] = 'Header';
$TEXT['FOOTER'] = 'Footer';
$TEXT['TEMPLATE'] = 'Template';
$TEXT['INSTALLATION'] = 'Installation';
$TEXT['DATABASE'] = 'Database';
$TEXT['HOST'] = 'Host';
$TEXT['INTRO'] = 'Intro';
$TEXT['PAGE'] = 'Page';
$TEXT['SIGNUP'] = 'Sign-up';
$TEXT['PHP_ERROR_LEVEL'] = 'PHP Error Reporting Level';
$TEXT['ADMIN'] = 'Admin';
$TEXT['PATH'] = 'Path';
$TEXT['URL'] = 'URL';
$TEXT['FRONTEND'] = 'Front-end';
$TEXT['EXTENSION'] = 'Extension';
$TEXT['TABLE_PREFIX'] = 'Table Prefix';
$TEXT['CHANGES'] = 'Changes';
$TEXT['ADMINISTRATION'] = 'Administration';
$TEXT['FORGOT_DETAILS'] = 'Forgot Details?';
$TEXT['LOGGED_IN'] = 'Logged-In';
$TEXT['WELCOME_BACK'] = 'Welcome back';
$TEXT['FULL_NAME'] = 'Full Name';
$TEXT['ACCOUNT_SIGNUP'] = 'Account Sign-Up';
$TEXT['LINK'] = 'Link';
$TEXT['TARGET'] = 'Target';
$TEXT['NEW_WINDOW'] = 'New Window';
$TEXT['SAME_WINDOW'] = 'Same Window';
$TEXT['PAGE_LEVEL_LIMIT'] = 'Page Level Limit';
$TEXT['SUCCESS'] = 'Success';
$TEXT['ERROR'] = 'Error';
$TEXT['ARE_YOU_SURE'] = 'Are you sure?';
$TEXT['YES'] = 'Yes';
$TEXT['NO'] = 'No';
$TEXT['SYSTEM_DEFAULT'] = 'System Default';
$TEXT['PAGE_TITLE'] = 'Page Title';
$TEXT['MENU_TITLE'] = 'Menu Title';
$TEXT['ACTIONS'] = 'Actions';
$TEXT['UNKNOWN'] = 'Unknown';
$TEXT['BLOCK'] = 'Block';
$TEXT['SEARCH'] = 'Search';
$TEXT['SEARCHING'] = 'Searching';
$TEXT['POST'] = 'Post';
$TEXT['COMMENT'] = 'Comment';
$TEXT['COMMENTS'] = 'Comments';
$TEXT['COMMENTING'] = 'Commenting';
$TEXT['SHORT'] = 'Short';
$TEXT['LONG'] = 'Long';
$TEXT['LOOP'] = 'Loop';
$TEXT['FIELD'] = 'Field';
$TEXT['REQUIRED'] = 'Required';
$TEXT['LENGTH'] = 'Length';
$TEXT['MESSAGE'] = 'Message';
$TEXT['SUBJECT'] = 'Subject';
$TEXT['MATCH'] = 'Match';
$TEXT['ALL_WORDS'] = 'All Words';
$TEXT['ANY_WORDS'] = 'Any Words';
$TEXT['EXACT_MATCH'] = 'Exact Match';
$TEXT['SHOW'] = 'Show';
$TEXT['HIDE'] = 'Hide';
$TEXT['START_PUBLISHING'] = 'Start Publishing';
$TEXT['FINISH_PUBLISHING'] = 'Finish Publishing';
$TEXT['DATE'] = 'Date';
$TEXT['START'] = 'Start';
$TEXT['END'] = 'End';
$TEXT['IMAGE'] = 'Image';
$TEXT['ICON'] = 'Icon';
$TEXT['SECTION'] = 'Section';
$TEXT['DATE_FORMAT'] = 'Date Format';
$TEXT['TIME_FORMAT'] = 'Time Format';
$TEXT['RESULTS'] = 'Results';
$TEXT['RESIZE'] = 'Re-size';
$TEXT['MANAGE'] = 'Manage';
$TEXT['CODE'] = 'Code';
$TEXT['WIDTH'] = 'Width';
$TEXT['HEIGHT'] = 'Height';
$TEXT['MORE'] = 'More';
$TEXT['READ_MORE'] = 'Read More';
$TEXT['CHANGE_SETTINGS'] = 'Change Settings';
$TEXT['CURRENT_PAGE'] = 'Current Page';
$TEXT['CLOSE'] = 'Close';
$TEXT['INTRO_PAGE'] = 'Intro Page';
$TEXT['INSTALLATION_URL'] = 'Installation URL';
$TEXT['INSTALLATION_PATH'] = 'Installation Path';
$TEXT['PAGE_EXTENSION'] = 'Page Extension';
$TEXT['NO_RESULTS'] = 'No Results';
$TEXT['WEBSITE_TITLE'] = 'Website Title';
$TEXT['WEBSITE_DESCRIPTION'] = 'Website Description';
$TEXT['WEBSITE_KEYWORDS'] = 'Website Keywords';
$TEXT['WEBSITE_HEADER'] = 'Website Header';
$TEXT['WEBSITE_FOOTER'] = 'Website Footer';
$TEXT['RESULTS_HEADER'] = 'Results Header';
$TEXT['RESULTS_LOOP'] = 'Results Loop';
$TEXT['RESULTS_FOOTER'] = 'Results Footer';
$TEXT['LEVEL'] = 'Level';
$TEXT['NOT_FOUND'] = 'Not Found';
$TEXT['PAGE_SPACER'] = 'Page Spacer';
$TEXT['MATCHING'] = 'Matching';
$TEXT['TEMPLATE_PERMISSIONS'] = 'Template Permissions';
$TEXT['PAGES_DIRECTORY'] = 'Pages Directory';
$TEXT['MEDIA_DIRECTORY'] = 'Media Directory';
$TEXT['FILE_MODE'] = 'File Mode';
$TEXT['USER'] = 'User';
$TEXT['OTHERS'] = 'Others';
$TEXT['READ'] = 'Read';
$TEXT['WRITE'] = 'Write';
$TEXT['EXECUTE'] = 'Execute';
$TEXT['SMART_LOGIN'] = 'Smart Login';
$TEXT['REMEMBER_ME'] = 'Remember Me';
$TEXT['FILESYSTEM_PERMISSIONS'] = 'Filesystem Permissions';
$TEXT['DIRECTORIES'] = 'Directories';
$TEXT['DIRECTORY_MODE'] = 'Directory Mode';
$TEXT['LIST_OPTIONS'] = 'List Options';
$TEXT['OPTION'] = 'Option';
$TEXT['ALLOW_MULTIPLE_SELECTIONS'] = 'Allow Multiple Selections';
$TEXT['TEXTFIELD'] = 'Textfield';
$TEXT['TEXTAREA'] = 'Textarea';
$TEXT['SELECT_BOX'] = 'Select Box';
$TEXT['CHECKBOX_GROUP'] = 'Checkbox Group';
$TEXT['RADIO_BUTTON_GROUP'] = 'Radio Button Group';
$TEXT['SIZE'] = 'Size';
$TEXT['DEFAULT_TEXT'] = 'Default Text';
$TEXT['SEPERATOR'] = 'Seperator';
$TEXT['BACK'] = 'Back';
$TEXT['UNDER_CONSTRUCTION'] = 'Under Construction';
$TEXT['MULTISELECT'] = 'Multi-select';
$TEXT['SHORT_TEXT'] = 'Short Text';
$TEXT['LONG_TEXT'] = 'Long Text';
$TEXT['HOMEPAGE_REDIRECTION'] = 'Homepage Redirection';
$TEXT['HEADING'] = 'Heading';
$TEXT['MULTIPLE_MENUS'] = 'Multiple Menu\'s';
$TEXT['REGISTERED'] = 'Registered';
$TEXT['START'] = 'Start';
$TEXT['SECTION_BLOCKS'] = 'Section Blocks';
$TEXT['REGISTERED_VIEWERS'] = 'Registered Viewers';
$TEXT['SUBMISSION_ID'] = 'Submission ID';
$TEXT['SUBMISSIONS'] = 'Submissions';
$TEXT['SUBMITTED'] = 'Submitted';
$TEXT['MAX_SUBMISSIONS_PER_HOUR'] = 'Max. Submissions Per Hour';
$TEXT['SUBMISSIONS_STORED_IN_DATABASE'] = 'Submissions Stored In Database';
$TEXT['EMAIL_ADDRESS'] = 'Email Address';
$TEXT['CUSTOM'] = 'Custom';
$TEXT['ANONYMOUS'] = 'Anonymous';
$TEXT['SERVER_OPERATING_SYSTEM'] = 'Server Operating System';
$TEXT['WORLD_WRITEABLE_FILE_PERMISSIONS'] = 'World-writeable file permissions';
$TEXT['LINUX_UNIX_BASED'] = 'Linux/Unix based';
$TEXT['WINDOWS'] = 'Windows';
$TEXT['HOME_FOLDER'] = 'Home Folder';
$TEXT['HOME_FOLDERS'] = 'Home Folders';
$TEXT['PAGE_TRASH'] = 'Page Trash';
$TEXT['INLINE'] = 'In-line';
$TEXT['SEPARATE'] = 'Separate';
$TEXT['DELETED'] = 'Deleted';
$TEXT['VIEW_DELETED_PAGES'] = 'View Deleted Pages';
$TEXT['EMPTY_TRASH'] = 'Empty Trash';
$TEXT['TRASH_EMPTIED'] = 'Trash Emptied';
$TEXT['ADD_SECTION'] = 'Add Section';
$TEXT['POST_HEADER'] = 'Post Header';
$TEXT['POST_FOOTER'] = 'Post Footer';
$TEXT['POSTS_PER_PAGE'] = 'Posts Per Page';
$TEXT['RESIZE_IMAGE_TO'] = 'Resize Image To';
$TEXT['UNLIMITED'] = 'Unlimited';
$TEXT['OF'] = 'Of';
$TEXT['OUT_OF'] = 'Out Of';
$TEXT['NEXT'] = 'Next';
$TEXT['PREVIOUS'] = 'Previous';
$TEXT['NEXT_PAGE'] = 'Next Page';
$TEXT['PREVIOUS_PAGE'] = 'Previous Page';
$TEXT['ON'] = 'On';
$TEXT['LAST_UPDATED_BY'] = 'Last Updated By';
$TEXT['RESULTS_FOR'] = 'Results For';
$TEXT['TIME'] = 'Time';
$TEXT['WYSIWYG_STYLE'] = 'WYSIWYG Style';
$TEXT['WYSIWYG_EDITOR'] = "WYSIWYG Editor";
$TEXT['SERVER_EMAIL'] = 'Server Email';
$TEXT['MENU'] = 'Menu';
$TEXT['MANAGE_GROUPS'] = 'Manage Groups';
$TEXT['MANAGE_USERS'] = 'Manage Users';
$TEXT['PAGE_LANGUAGES'] = 'Page Languages';
$TEXT['HIDDEN'] = 'Hidden';
$TEXT['MAIN'] = 'Main';
$TEXT['RENAME_FILES_ON_UPLOAD'] = 'Rename Files On Upload';
$TEXT['APP_NAME'] = 'Application Name';
$TEXT['SESSION_IDENTIFIER'] = 'Session Identifier';
$TEXT['BACKUP'] = 'Backup';
$TEXT['RESTORE'] = 'Restore';
$TEXT['BACKUP_DATABASE'] = 'Backup Database';
$TEXT['RESTORE_DATABASE'] = 'Restore Database';
$TEXT['BACKUP_MEDIA'] = 'Backup Media';
$TEXT['RESTORE_MEDIA'] = 'Restore Media';
$TEXT['ADMINISTRATION_TOOL'] = 'Administration tool';
$TEXT['CAPTCHA_VERIFICATION'] = 'Captcha Verification';
$TEXT['VERIFICATION'] = 'Verification';
$TEXT['DEFAULT_CHARSET'] = 'Default Charset';
$TEXT['CHARSET'] = 'Charset';


// Success/error messages
$MESSAGE['FRONTEND']['SORRY_NO_VIEWING_PERMISSIONS'] = 'Sorry, you do not have permissions to view this page';

$MESSAGE['ADMIN']['INSUFFICIENT_PRIVELLIGES'] = 'Insufficient privelliges to be here';

$MESSAGE['LOGIN']['BOTH_BLANK'] = 'Please enter you username and password below';
$MESSAGE['LOGIN']['USERNAME_BLANK'] = 'Please enter a username';
$MESSAGE['LOGIN']['PASSWORD_BLANK'] = 'Please enter a password';
$MESSAGE['LOGIN']['USERNAME_TOO_SHORT'] = 'Supplied username to short';
$MESSAGE['LOGIN']['PASSWORD_TOO_SHORT'] = 'Supplied password to short';
$MESSAGE['LOGIN']['USERNAME_TOO_LONG'] = 'Supplied username to long';
$MESSAGE['LOGIN']['PASSWORD_TOO_LONG'] = 'Supplied password to long';
$MESSAGE['LOGIN']['AUTHENTICATION_FAILED'] = 'Username or password incorrect';

$MESSAGE['SIGNUP']['NO_EMAIL'] = 'You must enter an email address';

$MESSAGE['FORGOT_PASS']['NO_DATA'] = 'Please enter your email address below';
$MESSAGE['FORGOT_PASS']['EMAIL_NOT_FOUND'] = 'The email that you entered cannot be found in the database';
$MESSAGE['FORGOT_PASS']['CANNOT_EMAIL'] = 'Unable to email password, please contact system administrator';
$MESSAGE['FORGOT_PASS']['PASSWORD_RESET'] = 'Your username and password have been sent to your email address';
$MESSAGE['FORGOT_PASS']['ALREADY_RESET'] = 'Password cannot be reset more than once per hour, sorry';

$MESSAGE['START']['WELCOME_MESSAGE'] = 'Welcome to Website Baker Administration';
$MESSAGE['START']['INSTALL_DIR_EXISTS'] = 'Warning, Installation Directory Still Exists!';
$MESSAGE['START']['CURRENT_USER'] = 'You are currently logged in as:';

$MESSAGE['SETTINGS']['UNABLE_OPEN_CONFIG'] = 'Unable to open the configuration file';
$MESSAGE['SETTINGS']['UNABLE_WRITE_CONFIG'] = 'Cannot write to configuration file';
$MESSAGE['SETTINGS']['SAVED'] = 'Settings saved successfully';
$MESSAGE['SETTINGS']['MODE_SWITCH_WARNING'] = 'Please Note: Pressing this button resets all unsaved changes';
$MESSAGE['SETTINGS']['WORLD_WRITEABLE_WARNING'] = 'Please note: this is only recommended for testing environments';

$MESSAGE['USERS']['ADDED'] = 'User added successfully';
$MESSAGE['USERS']['SAVED'] = 'User saved successfully';
$MESSAGE['USERS']['DELETED'] = 'User deleted successfully';
$MESSAGE['USERS']['NO_GROUP'] = 'No group was selected';
$MESSAGE['USERS']['USERNAME_TOO_SHORT'] = 'The username you entered was too short';
$MESSAGE['USERS']['PASSWORD_TOO_SHORT'] = 'The password you entered was too short';
$MESSAGE['USERS']['PASSWORD_MISMATCH'] = 'The passwords you entered do not match';
$MESSAGE['USERS']['INVALID_EMAIL'] = 'The email address you entered is invalid';
$MESSAGE['USERS']['EMAIL_TAKEN'] = 'The email you entered is already in use';
$MESSAGE['USERS']['USERNAME_TAKEN'] = 'The username you entered is already taken';
$MESSAGE['USERS']['CHANGING_PASSWORD'] = 'Please note: You should only enter values in the above fields if you wish to change this users password';
$MESSAGE['USERS']['CONFIRM_DELETE'] = 'Are you sure you want to delete the selected user?';

$MESSAGE['GROUPS']['ADDED'] = 'Group added successfully';
$MESSAGE['GROUPS']['SAVED'] = 'Group saved successfully';
$MESSAGE['GROUPS']['DELETED'] = 'Group deleted successfully';
$MESSAGE['GROUPS']['GROUP_NAME_BLANK'] = 'Group name is blank';
$MESSAGE['GROUPS']['CONFIRM_DELETE'] = 'Are you sure you want to delete the selected group (and any users that belong to it)?';
$MESSAGE['GROUPS']['NO_GROUPS_FOUND'] = 'No groups found';
$MESSAGE['GROUPS']['GROUP_NAME_EXISTS'] = 'Group name already exists';

$MESSAGE['PREFERENCES']['DETAILS_SAVED'] = 'Details saved successfully';
$MESSAGE['PREFERENCES']['EMAIL_UPDATED'] = 'Email updated successfully';
$MESSAGE['PREFERENCES']['CURRENT_PASSWORD_INCORRECT'] = 'The (current) password you entered is incorrect';
$MESSAGE['PREFERENCES']['PASSWORD_CHANGED'] = 'Password changed successfully';

$MESSAGE['TEMPLATES']['CHANGE_TEMPLATE_NOTICE'] = 'Please note: to change the template you must go to the Settings section';

$MESSAGE['MEDIA']['DIR_DOT_DOT_SLASH'] = 'Cannot include ../ in the folder name';
$MESSAGE['MEDIA']['DIR_DOES_NOT_EXIST'] = 'Directory does not exist';
$MESSAGE['MEDIA']['TARGET_DOT_DOT_SLASH'] = 'Cannot have ../ in the folder target';
$MESSAGE['MEDIA']['NAME_DOT_DOT_SLASH'] = 'Cannot include ../ in the name';
$MESSAGE['MEDIA']['NAME_INDEX_PHP'] = 'Cannot use index.php as the name';
$MESSAGE['MEDIA']['NONE_FOUND'] = 'No media found in the current folder';
$MESSAGE['MEDIA']['FILE_NOT_FOUND'] = 'File not found';
$MESSAGE['MEDIA']['DELETED_FILE'] = 'File deleted successfully';
$MESSAGE['MEDIA']['DELETED_DIR'] = 'Folder deleted successfully';
$MESSAGE['MEDIA']['CONFIRM_DELETE'] = 'Are you sure you want to delete the following file or folder?';
$MESSAGE['MEDIA']['CANNOT_DELETE_FILE'] = 'Cannot delete the selected file';
$MESSAGE['MEDIA']['CANNOT_DELETE_DIR'] = 'Cannot delete the selected folder';
$MESSAGE['MEDIA']['BLANK_NAME'] = 'You did not enter a new name';
$MESSAGE['MEDIA']['BLANK_EXTENSION'] = 'You did not enter a file extension';
$MESSAGE['MEDIA']['RENAMED'] = 'Rename successful';
$MESSAGE['MEDIA']['CANNOT_RENAME'] = 'Rename unsuccessful';
$MESSAGE['MEDIA']['FILE_EXISTS'] = 'A file matching the name you entered already exists';
$MESSAGE['MEDIA']['DIR_EXISTS'] = 'A folder matching the name you entered already exists';
$MESSAGE['MEDIA']['DIR_MADE'] = 'Folder created successfully';
$MESSAGE['MEDIA']['DIR_NOT_MADE'] = 'Unable to create folder';
$MESSAGE['MEDIA']['SINGLE_UPLOADED'] = ' file was successfully uploaded';
$MESSAGE['MEDIA']['UPLOADED'] = ' files were successfully uploaded';

$MESSAGE['PAGES']['ADDED'] = 'Page added successfully';
$MESSAGE['PAGES']['ADDED_HEADING'] = 'Page heading added successfully';
$MESSAGE['PAGES']['PAGE_EXISTS'] = 'A page with the same or similar title exists';
$MESSAGE['PAGES']['CANNOT_CREATE_ACCESS_FILE'] = 'Error creating access file in the /pages directory (insufficient privileges)';
$MESSAGE['PAGES']['CANNOT_DELETE_ACCESS_FILE'] = 'Error deleting access file in the /pages directory (insufficient privileges)';
$MESSAGE['PAGES']['NOT_FOUND'] = 'Page not found';
$MESSAGE['PAGES']['SAVED'] = 'Page saved successfully';
$MESSAGE['PAGES']['SAVED_SETTINGS'] = 'Page settings saved successfully';
$MESSAGE['PAGES']['NOT_SAVED'] = 'Error saving page';
$MESSAGE['PAGES']['DELETE_CONFIRM'] = 'Are you sure you want to delete the selected page (and all of its sub-pages)';
$MESSAGE['PAGES']['DELETED'] = 'Page deleted successfully';
$MESSAGE['PAGES']['RESTORED'] = 'Page restored successfully';
$MESSAGE['PAGES']['BLANK_PAGE_TITLE'] = 'Please enter a page title';
$MESSAGE['PAGES']['BLANK_MENU_TITLE'] = 'Please enter a menu title';
$MESSAGE['PAGES']['REORDERED'] = 'Page re-ordered successfully';
$MESSAGE['PAGES']['CANNOT_REORDER'] = 'Error re-ordering page';
$MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS'] = 'You do not have permissions to modify this page';
$MESSAGE['PAGES']['INTRO_NOT_WRITABLE'] = 'Cannot write to file /pages/intro.php (insufficient privileges)';
$MESSAGE['PAGES']['INTRO_SAVED'] = 'Intro page saved successfully';
$MESSAGE['PAGES']['LAST_MODIFIED'] = 'Last modification by';
$MESSAGE['PAGES']['INTRO_LINK'] = 'Click HERE to modify the intro page';
$MESSAGE['PAGES']['SECTIONS_PROPERTIES_SAVED'] = 'Section properties saved successfully';
$MESSAGE['PAGES']['RETURN_TO_PAGES'] = 'Return to pages';

$MESSAGE['GENERIC']['FILL_IN_ALL'] = 'Please go back and fill-in all fields';
$MESSAGE['GENERIC']['FILE_TYPE'] = 'Please note that the file you upload must be of the following format:';
$MESSAGE['GENERIC']['FILE_TYPES'] = 'Please note that the file you upload must be in one of the following formats:';
$MESSAGE['GENERIC']['CANNOT_UPLOAD'] = 'Cannot upload file';
$MESSAGE['GENERIC']['ALREADY_INSTALLED'] = 'Already installed';
$MESSAGE['GENERIC']['NOT_INSTALLED'] = 'Not installed';
$MESSAGE['GENERIC']['CANNOT_UNINSTALL'] = 'Cannot uninstall';
$MESSAGE['GENERIC']['CANNOT_UNZIP'] = 'Cannot unzip file';
$MESSAGE['GENERIC']['INSTALLED'] = 'Installed successfully';
$MESSAGE['GENERIC']['UPGRADED'] = 'Upgraded successfully';
$MESSAGE['GENERIC']['UNINSTALLED'] = 'Uninstalled successfully';
$MESSAGE['GENERIC']['BAD_PERMISSIONS'] = 'Unable to write to the target directory';
$MESSAGE['GENERIC']['INVALID'] = 'The file you uploaded is invalid';
$MESSAGE['GENERIC']['CANNOT_UNINSTALL_IN_USE'] = 'Cannot Uninstall: the selected file is in use';
$MESSAGE['GENERIC']['WEBSITE_UNDER_CONTRUCTION'] = 'Website Under Construction';
$MESSAGE['GENERIC']['PLEASE_CHECK_BACK_SOON'] = 'Please check back soon...';
$MESSAGE['GENERIC']['PLEASE_BE_PATIENT'] = 'Please be patient, this might take a while.';
$MESSAGE['GENERIC']['ERROR_OPENING_FILE'] = 'Error opening file.';

$MESSAGE['MOD_FORM']['REQUIRED_FIELDS'] = 'You must enter details for the following fields';
$MESSAGE['MOD_FORM']['EXCESS_SUBMISSIONS'] = 'Sorry, this form has been submitted too many times so far this hour. Please retry in the next hour.';
$MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'] = 'The verification number (also known as Captcha) that you entered is incorrect. If you are having problems reading the Captcha, please email: '.SERVER_EMAIL;

$MESSAGE['MOD_RELOAD']['PLEASE_SELECT'] = 'Please selected which add-ons you would like to have reloaded';
$MESSAGE['MOD_RELOAD']['MODULES_RELOADED'] = 'Modules reloaded successfully';
$MESSAGE['MOD_RELOAD']['TEMPLATES_RELOADED'] = 'Templates reloaded successfully';
$MESSAGE['MOD_RELOAD']['LANGUAGES_RELOADED'] = 'Languages reloaded successfully';

?>