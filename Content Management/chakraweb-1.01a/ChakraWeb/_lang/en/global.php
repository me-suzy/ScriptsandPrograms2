<?php
// ----------------------------------------------------------------------
// Purpose: Definition Module In English
// Author: Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [global.php] file directly...");

// ----------------------------------------------------------------------
// Global Definitions
// ----------------------------------------------------------------------
define('_POWERED_BY_CHAKRAWEB', '<a href="http://chakra.quick4all.com/Products/ChakraWeb/"><img border="0" src="/images/chweb_power.gif"></a>');
define('_MADE_BY_CHAKRAWEB', '
This web site was made of <a href="http://chakra.quick4all.com/Products/ChakraWeb/">ChakraWeb</a>, a web portal system written in PHP. 
ChakraWeb is free software released under the <a href="http://www.gnu.org/">GNU/GPL License</a>.');


// ----------------------------------------------------------------------
// Field Definitions
// ----------------------------------------------------------------------
define('_FLD_ACTIVE', 'Active');
define('_FLD_ADMIN', 'Admin');
define('_FLD_ATTR', 'Attribute');
define('_FLD_DESCRIPTION', 'Description');
define('_FLD_EMPTY', 'EMPTY');
define('_FLD_FOLDER', 'FOLDER');
define('_FLD_FULLNAME', 'Full Name');
define('_FLD_GREAT', 'Great');
define('_FLD_HITS', 'Hits');
define('_FLD_ID', 'Id');
define('_FLD_MEMBER_LEVEL', 'Member Level');
define('_FLD_MISC_ATTR', 'Misc Attribute');
define('_FLD_NAME', 'Name');
define('_FLD_ORDER', 'Order');
define('_FLD_ORDER_NOTE', 'Order on list or menu.');
define('_FLD_PAGE', 'PAGE');
define('_FLD_PASSWORD', 'PSW');
define('_FLD_REDIRECT', 'Redirect To');
define('_FLD_SEEALSO_TITLE', 'See Also');
define('_FLD_SELECT_COUNTRY', 'Select Country');
define('_FLD_SHOW', 'Show');
define('_FLD_TITLE', 'Title');
define('_FLD_USERID', 'UID');
define('_FLD_VISIT', 'Visit');
define('_FLD_NOACTIVE', 'Non-Active');
define('_FLD_WEB_ACTIVATE', 'Activate');
define('_FLD_WEB_ACTIVE', 'ACTIVE');
define('_FLD_WEB_MAINTENANCE', 'Inactivate (Maintenance)');
define('_FLD_WEB_NOACTIVE', 'NON-ACTIVE');


// ----------------------------------------------------------------------
// Navigation Definitions
// ----------------------------------------------------------------------
define('_NAV_ABOUTUS', 'About Us');
define('_NAV_ADDNEW', 'ADD NEW');
define('_NAV_ADVRND', 'List of Random Advertising Text');
define('_NAV_ADVRND_EDIT', 'Edit');
define('_NAV_ADVTEXT', 'List of Advertising Text');
define('_NAV_ADVTEXT_EDIT', 'Edit');
define('_NAV_ADV_SEARCH', 'Adv. Search');
define('_NAV_APPROVE', 'APPROVE');
define('_NAV_ATTR', 'ATTRIBUTE');
define('_NAV_BOOKMARKUS', 'Bookmark');
define('_NAV_CONTROL_PANEL', 'Control Panel');
define('_NAV_DELETE', 'DELETE');
define('_NAV_DIR_HELP', 'Directory Help');
define('_NAV_DOWNLOAD_PAGE', 'Download');
define('_NAV_EDIT', 'EDIT');
define('_NAV_FEEDBACK', 'Feedback');
define('_NAV_FILE_MANAGEMENT', 'File Management');
define('_NAV_FRONTPAGE', 'Home');
define('_NAV_HELP', 'Help');
define('_NAV_HIDE', 'HIDE');
define('_NAV_HOME', 'Home');
define('_NAV_LINKTOUS', 'Link to Us');
define('_NAV_MACROTEXT', 'Macro Text');
define('_NAV_MACROTEXT_EDIT', 'Edit Macro Text');
define('_NAV_MEMBERS', 'Registered Members');
define('_NAV_MEMBER_ADD', 'Add Member');
define('_NAV_MEMBER_INFO', 'Member Info');
define('_NAV_MEMBER_LOGOUT', 'Logout');
define('_NAV_MEMBER_PROFILE', 'Member Profile');
define('_NAV_MEMBER_SENDMAIL', 'Send E-mail');
define('_NAV_MEMBER_SERVICE', 'Member Services');
define('_NAV_MEMBER_SERVICE_EDIT', 'Edit Service');
define('_NAV_MEMBER_SERVICE_SENDMAIL', 'Send E-mail');
define('_NAV_MEMBER_STARTPAGE', 'Start Page');
define('_NAV_MEMBER_VISIT', 'List Of Member Visit');
define('_NAV_MORE_LIST', 'More...');
define('_NAV_MOVE', 'MOVE');
define('_NAV_MYPROFILE', 'My Profile');
define('_NAV_PAGE_HITS_ASC', 'Un-Favorite Pages');
define('_NAV_PAGE_HITS_DESC', 'Favorite Pages');
define('_NAV_PAGE_HITS_RESET', 'Reset Hits');
define('_NAV_PRIVACY_POLICY', 'Privacy Policy');
define('_NAV_REGISTER', 'Register');
define('_NAV_SEARCH_TIPS', 'Search Tips');
define('_NAV_SERVICE_MEMBER_LIST', 'Members List');
define('_NAV_SHOW', 'SHOW');
define('_NAV_SITEMAP', 'Sitemap');
define('_NAV_SYSDBASE_EDIT', 'Change Database');
define('_NAV_SYSOTHER_EDIT', 'Change Other Paramaters');
define('_NAV_SYSVAR_EDIT', 'Change System Variables');
define('_NAV_TERM_OF_USE', 'Term Of Use');
define('_NAV_TODO_LINK', 'Link');
define('_NAV_TODO_LIST', 'Todo List');
define('_NAV_WEB_ACTIVATE', 'Maintenance Time');
define('_NAV_GOTO_PAGE', 'Goto Page');


// ----------------------------------------------------------------------
// Misc Definitions
// ----------------------------------------------------------------------
define(DEFAULT_PAGE_CONTENT, "<h1>{page_title}</h1>\n\n{style:info:\$page_desc}\n<p>Sorry. This page is under construction.</p>");

define('_MEMBER_REGISTRATION_TITLE', 'Member Registration');
define('_MEMBER_REGISTRATION_CONTENT', '
<p>Member registration process is free. 
Before filling in the form below, make sure that you have understood the 
<a href="/privacy_policy.html">Privacy Policy</a>
and <a href="/terms_of_use.html">Term Of Use</a>.
The advantage of membership can be seen on the bottom of the page.</p>
<p>After you filling in the form, We will send you an e-mail soon
that contain your password and you can login to this website right away.</p>');

define('_LINK_ADDNEW_MESSAGE', '<a href="/phpmod/link.php?op=show&cat={folder_id}">Add Link</a> to This Page');
define('_LINK_FORM_TITLE', 'ADD LINK');
define('_LINK_ADD_TITLE', 'Add Link');
define('_LINK_UPDATE_TITLE', 'EDIT LINK');
define('_LINK_ADD_NOLOGIN', '
<p>You are not a registered user or you have not logged in yet.
If you were registered you could add links on this website.</p>

<p>Becoming a registered user is a quick and easy process.
Why do we require registration for access to certain features?
So we can offer you only the highest quality content,
each item is individually reviewed and approved by our staff.
We hope to offer you only valuable information.</p>

<p><a href="/phpmod/register.php">Member Registration</a></p>
');

define('_LINK_ADD_THANKYOU', '<p>Thank you for your contribution. 
Your link will be reviewed by our staff to be added to this page.</p>');

define('_COMMENT_PROMO', 'If you are a member of this website, you can write a comment.
<a href="/phpmod/register.php">Register</a> now. Easy, Fast, and FREE. :)');

define('_ABOUT_AUTHOR', 'About The Author');
define('_ADVRND_EDIT_TITLE', 'Edit Random Advertising Text');
define('_ADVRND_TITLE', 'List of Random Advertising Text');
define('_ADVTEXT_EDIT_TITLE', 'Edit Advertising Text');
define('_ADVTEXT_TITLE', 'List of Advertising Text');

define('_AUTHOR', 'Author');
define('_AUTHOR_BY', 'By');

define('_COMMENT_FORM_TITLE', 'PLEASE WRITE YOUR COMMENT HERE');
define('_CONTROL_PANEL_TITLE', 'Control Panel');
define('_DOWNLOAD_ERROR_TITLE', 'Download Failed');

define('_FEEDBACK_MESSAGE', 'We welcome your feedback! Write suggestions, ideas, and comments on our feedback form on this page');
define('_FEEDBACK_THANK_MESSAGE', '<p>Thank you for your contribution. </p>');
define('_FEEDBACK_THANK_TITLE', 'Thank you for your feedback');
define('_FEEDBACK_TITLE', 'Your Feedback');

define('_FILE_MANAGEMENT_TITLE', 'File Management');

define('_FOLDER_ADD_TITLE', 'Add Sub-Folder');
define('_FOLDER_ATTR_TITLE', 'Edit Folder Attribute');
define('_FOLDER_DELETE_TITLE', 'Delete Folder <b>"%s"</b>');

define('_HPAGE_DELETE_MESSAGE', 'Sorry, you can delete any folder, except the homepage.');
define('_HPAGE_DELETE_TITLE', 'Delete Homepage');
define('_HPAGE_MOVE_MESSAGE', 'Sorry, you can move any folder, except the homepage.');
define('_HPAGE_MOVE_TITLE', 'Move Homepage');
define('_HPAGE_SEARCH_MESSAGE', 'Keywords: <b>%s</b>');
define('_HPAGE_SEARCH_TITLE', 'Search Result');

define('_LANG_CHANGE', 'Change Language');

define('_LOSTPASSWORD_SUBJECT_FMT', 'New Password %s');
define('_LOST_PASSWORD', 'Lost Password?');
define('_LOST_PASSWORD_MESSAGE', 'No problem, we will send you a new password by e-mail. 
Fill your ID and your e-mail (E-mail that you use when you register here).');
define('_LOST_PASSWORD_TITLE', 'Lost Password');

define('_MACROTEXT_EDIT_TITLE', 'Change Macro Text');
define('_MACROTEXT_TITLE', 'List of Macro Text');

define('_MEMBER_ADD_SUBJECT', 'We Have You as a New Member');
define('_MEMBER_ADD_TITLE', 'Add New Member');
define('_MEMBER_INFO_MESSAGE', 'Change your personal information on the form below:');
define('_MEMBER_INFO_TITLE', 'Member Info');
define('_MEMBER_LOGIN', 'Member Login');
define('_MEMBER_ONLY_MESSAGE', 'The file/page you requested can be access by member only. 
<p>Register your self by fill <a href="/phpmod/register.php">The Registration Form</a>. 
(The registration process is free of charge).');

define('_MEMBER_ONLY_TITLE', 'Member Only');
define('_MEMBER_PAGE', 'Member');
define('_MEMBER_PAGE_EMPTY_FMT', '<p><b>Hello %s.</b><br>You can promote your self here by filling your complete profile. Click <a href="%s">HERE</a>.</p>');
define('_MEMBER_PAGE_FOOTNOTE_FMT', '<p><b>%s</b>, You can edit your profile. Click <a href="%s">HERE</a>.</p>');
define('_MEMBER_PAGE_FMT', 'Member Page - %s');
define('_MEMBER_PAGE_EDIT_FMT', 'Edit Member Page - %s');
define('_MEMBER_PAGE_MESSAGE_FMT', '<p>This page contains the list of all members of this website. Currently there are  %d members (Not all of them can be seen here). Please show the list by click the first letter of their names or click ALL to see all members.</p>');
define('_MEMBER_PAGE_TITLE', 'Members List');
define('_MEMBER_REGISTRATION', 'Registration');
define('_MEMBER_SERVICE_EDIT_TITLE', 'Change Member Services');
define('_MEMBER_SERVICE_SENDMAIL_TITLE', 'Send E-mail');
define('_MEMBER_SERVICE_TITLE', 'Member Services');
define('_MEMBER_STARTPAGE_MESSAGE', 'Start page is the page that shown after the member login on this website. Change the start page by filling in the form below.');
define('_MEMBER_STARTPAGE_TITLE', 'Edit Start Page');
define('_MEMBER_VISIT_RESET', '<p>To reset the visits and hits counter, click <a href="/phpmod/member_visit.php?op=reset">HERE</a></p>');
define('_MEMBER_VISIT_TITLE', 'List of Member Visits');

define('_NEWS_EDIT_MESSAGE', '');
define('_NEWS_EDIT_TITLE', 'Edit News');
define('_NEWS_FORM_TITLE', 'PLEASE ENTER A NEW NEWS');

define('_PAGE_EDIT_MESSAGE', 'Are you want to edit/create that page? Click <a href="%s">HERE</a>.');
define('_PAGE_HITS', 'Hits');
define('_PAGE_HITS_ASC_MESSAGE', '<p>Here the list of un-favorite pages on this website.</p>');
define('_PAGE_HITS_ASC_TITLE_FMT', 'The %d Un-favorite Page(s)');
define('_PAGE_HITS_DESC_MESSAGE', '<p>Here the list of favorite pages on this website.</p>');
define('_PAGE_HITS_DESC_TITLE_FMT', 'The %d Favorite Pages');
define('_PAGE_HITS_RESET_TITLE', 'Reset Hits Counter Of All Pages');
define('_PAGE_HITS_TITLE_FMT', 'Hit List of Folder "%s"');
define('_PAGE_NOTFOUND_MESSAGE', 'The page/file you requested (<b>%s</b>) not found on this server.');
define('_PAGE_NOTFOUND_TITLE', 'Page/File Not Found');
define('_PAGE_RATING', 'Rating');
define('_PAGE_SOURCE_FMT', 'Source: %s from website %s');
define('_PAGE_UPDATE_ON', 'Update On');
define('_PAGE_VOTE_BY', 'Vote By');
define('_REGISTRATION_SUBJECT_FMT', 'Member Registration : %s');

define('_REGIST_STATUS_MSG1', 'Congratulation, you are now a member of this website. 
Your password we sent via e-mail. 
Please open your e-mail and come back to this website. Thank You.');

define('_REGIST_STATUS_MSG2', 'Congratulation, You are now a member of this website. 
Your password is: %s. Use this password to login on this website.');

define('_REGIST_STATUS_TITLE', 'Member Registration');
define('_SENDPASSWORD_STATUS_MSG1', 'Your password we sent via e-mail. 
Please open your e-mail and come back to this website. Thank You.');

define('_SENDPASSWORD_STATUS_MSG2', 'Sending e-mail failed. 
Your password is: %s. Use this password to login on this website.');

define('_SENDPASSWORD_STATUS_TITLE', 'New Password');
define('_SERVICE_MEMBER_LIST_TITLE_FMT', 'List of Members That Use "%s" Email Service');
define('_MAIN_SUBFOLDER', 'NAVIGATION');
define('_SYSDBASE_EDIT_TITLE', 'Change Database');
define('_SYSOTHER_EDIT_TITLE', 'Change Other Parameters');
define('_SYSVAR_EDIT_TITLE', 'Edit System Variables');

define('_THEME_CHANGE', 'Change Theme');

define('_TODO_LINK_MESSAGE', '<p>
Here the list of links that need authorization. You can delete, edit, or allow the link to be shown on the list.</p>');
define('_TODO_LINK_TITLE', 'Link Authorization');

define('_TODO_LIST_TITLE', 'Administrator\'s To Do List');

define('_UNAUTHORISIZED_ACCESS_MESSAGE', 'File/Page you requested cannot be accessed. You need authorization.');
define('_UNAUTHORISIZED_ACCESS_TITLE', 'Un-Authorized Access');

define('_WEB_ACTIVATE_TITLE', 'Maintenance Time');

define('_WEB_SEARCH', 'Find');
define('_WEB_SEARCH_BTN', 'Go');

define('_WPAGE_DELETE_TITLE', 'Delete Page %s');
define('_WPAGE_EDIT_CONTENT', '<h1>Edit The Web Page</h1>Please fill the form below.');
define('_WPAGE_MOVE_TITLE', 'Move Page  %s');

define('_YOU_ARE_HERE', 'You Are Here');
define('_GUEST_FULLNAME', 'Guest');
define('_GUEST_NAME', 'Guest');
define('_USER_NAME_FMT', '%s');

define('_SETUP_FOLDER_EXIST', '<b>Reminder</b>. Please remember to remove the the setup directory (/setup/) from your ChakraWeb directory. If you do not remove these files then users can obtain the password to your database!.');

define('_NEW_COMMENT_LIST_TITLE', 'New Comment');
define('_NEW_FEEDBACK_LIST_TITLE', 'New Feedback');

// ----------------------------------------------------------------------
// Error Messages
// ----------------------------------------------------------------------
define('_ERR_ADDNEW_MEMBER_FAILED', 'Add New Member Failed.');
define('_ERR_ADM_AUTHOR_ONLY', 'Your request rejected. Only Administartor and the author can change the folder/page.');
define('_ERR_CHANGE_OTHER_MEMBER_INFO', 'Sorry, you cannot change other member info.');
define('_ERR_DBHOST_EMPTY', 'The database host cannot be empty.');
define('_ERR_DBNAME_EMPTY', 'The database name cannot be empty.');
define('_ERR_DBUSER_EMPTY', 'The database user cannot be empty.');
define('_ERR_EMPTY_TITLE', 'The title cannot be empty. Please re-enter the form.');
define('_ERR_FEEDBACK_MESSAGE_EMPTY', 'Sorry, your feedback is empty. Please re-enter the form.');
define('_ERR_FILE_ALREADY_EXIST', 'The file name already exist on database.');
define('_ERR_FOLDER_ALREADY_EXIST', 'The folder name already exist on database.');
define('_ERR_INVALID_ADVRND', 'Invalid data. Please fill all field on the form correctly.');
define('_ERR_INVALID_ADVTEXT', 'Invalid data. Please fill all field on the form correctly.');
define('_ERR_INVALID_MACROTEXT', 'Invalid data. Please fill all field on the form correctly.');
define('_ERR_INVALID_COUNTRY', 'Invalid country. Please enter your country correctly.');
define('_ERR_INVALID_EMAIL_FORMAT', 'Invalid e-mail format. Please enter your e-mail correctly.');
define('_ERR_INVALID_EMAIL_SERVICE', 'Invalid data. Please fill all field on the form correctly.');
define('_ERR_INVALID_HPAGE', 'Invalid homepage. Please re-enter the form.');
define('_ERR_INVALID_PASSWORD', 'Invalid Password.');
define('_ERR_INVALID_PASSWORD2', 'The tow password is not match.');
define('_ERR_INVALID_REDIRECT_URL', 'Invalid Redirect URL');
define('_ERR_INVALID_SOURCE_URL', 'Invalid Source URL');
define('_ERR_INVALID_URL', 'Invalid URL');
define('_ERR_INVALID_USER_FULLNAME', 'Invalid name. Please enter your fullname correctly.');
define('_ERR_INVALID_USER_NAME', 'Invalid name. Please enter your name correctly.');
define('_ERR_LOGIN_FAILED_MESSAGE', 'Invalid User ID or password. Please re-enter the form.');
define('_ERR_LOGIN_FAILED_TITLE', 'Login Failed');
define('_ERR_NEWS_NOT_FOUND_MESSAGE', 'Sorry. The news you requested not found on this server.');
define('_ERR_NOT_USE_BROWSER', 'Request rejected. Please use your browser to process your request.');
define('_ERR_OPR_DELETE', 'Request rejected. Only administrator can delete folder/page.');
define('_ERR_OPR_DENIED_MESSAGE', 'Request rejected. Please try other request.');
define('_ERR_OPR_DENIED_TITLE', 'Operation Rejected');
define('_ERR_OPR_MOVE', 'Request rejected. Only Administrator can move folder/page.');
define('_ERR_REGISTER_FAILED', '<p>Registration failed. 
You use the same ID or e-mail with other member. Use other ID or e-mail.</p>');
define('_ERR_SEND_EMAIL_FAILED', 'Sending e-mail failed.');
define('_ERR_UNABLE_TO_CONNECT_DBASE', 'Unable to connect the database.');
define('_ERR_UNABLE_TO_CONNECT_DBASE_HOST', 'Unable to connect the host database.');
define('_ERR_UNKNOWN_OPERATION', 'Unknown operation');
define('_ERR_USER_EMAIL_NOT_MATCH', 'Your e-mail is not match');
define('_ERR_USER_NAME_NOT_FOUND', 'Member name is not found');


// ----------------------------------------------------------------------
// Lists
// ----------------------------------------------------------------------

$gLanguageList = array(
				'en' => 'English',
				'id' => 'Indonesia',
			);


?>
