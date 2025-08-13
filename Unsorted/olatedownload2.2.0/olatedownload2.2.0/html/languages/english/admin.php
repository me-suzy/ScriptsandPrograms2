<?php
/***************************************************************************
 *                      Olate Download v2 - Download Manager
 *
 *                           http://www.olate.com
 *                            -------------------
 *   author                : David Mytton
 *   copyright             : (C) Olate 2003 
 *
 *   Support for Olate scripts is provided at the Olate website. Licensing
 *   information is available in the license.htm file included in this
 *   distribution and on the Olate website.                  
 ***************************************************************************/

// Define admin texts - see config.php for author information
$language = array();
$language['button_activate']						= 'Activate';
$language['button_add']								= 'Add';
$language['button_update']							= 'Update';
$language['credits_poweredby']						= 'Powered by Olate Download ';
$language['description_accessdenied']				= 'Access Denied';
$language['description_allfields']					= 'You must complete all the required fields. <a href="JavaScript:history.go(-1);">Click here to go back</a>.';
$language['description_categories_add']				= 'To add a new category to the database, enter the name below and then click Add.';
$language['description_categories_added']			= 'The category has been added to the database.';
$language['description_categories_delete']			= 'To delete a category, select it from the list below:';
$language['description_categories_deleted']			= 'The category has been deleted.';
$language['description_categories_edit']			= 'To edit a category, select it from the list below:';
$language['description_categories_edit_view']		= 'To edit the selected category, make any changes and then click Update:';
$language['description_categories_edited']			= 'The category has been updated.';
$language['description_categories_name']			= 'Name:';
$language['description_config_colours']				= 'Colours and fonts are all controlled via the CSS file at /css/style.css.';
$language['description_config_general']				= 'There are several settings you can modify via the administration area. The settings are shown below. Click Update to make the changes.';
$language['description_config_general_noslash']		= '(No trailing slash)';
$language['description_config_general_upd']			= 'The database has been updated.';
$language['description_config_language']			= 'This page allows you to view the language currently in use on in the script along with author details and view available languages that you can activate for use in the script.';
$language['description_config_language_activated']	= 'The new language has been activated.';
$language['description_config_language_created']	= 'This language file was created by ';
$language['description_config_language_current']	= 'Language Currently In Use:';
$language['description_config_language_designed']	= ' and is designed for Olate Download version ';
$language['description_config_language_new']		= 'New languages can be downloaded from the Scripts &gt; Olate Download section of the <a href="http://www.olate.com" target="_blank">Olate website</a>. Instructions for installing a new language file are provided in the manual. ';
$language['description_config_language_on']			= ' on ';
$language['description_config_language_select']		= 'Select one of the installed languages from the menu below then click on Activate to continue.';
$language['description_config_languagesel']			= '---Available Languages---';
$language['description_downloads_add']				= 'To add a new download to the database, fill in the fields below then click on Add. If you do not wish to use the custom fields, leave them empty.';
$language['description_downloads_added']			= 'The new download has been added to the database.';
$language['description_downloads_categorysel']		= '---Select A Category---';
$language['description_downloads_categorycur']		= 'Current: ';
$language['description_downloads_delete']			= 'To delete a download, please select it from the list below:';
$language['description_downloads_deleted']			= 'The download has been deleted.';
$language['description_downloads_edit']				= 'To edit a download, please select it from the list below:';
$language['description_downloads_edit_view']		= 'The download you selected is shown below. Make the required changes and then click Update.';
$language['description_downloads_edited']			= 'The download has been updated.';
$language['description_downloads_mb']				= 'Mb:';
$language['description_downloads_noimg']			= 'Leave blank if no image ';
$language['description_loggedinas']					= 'Logged in as ';
$language['description_main']						= 'Select one of the options below to administer your downloads area. <a href="'.$config['urlpath'].'/index.php" target="_blank">Click here to view the downloads main page.</a>';
$language['description_users_add']					= '<p>To add a new user to the database, select a username and password and then click Add. All passwords are MD5 encrypted which means it is not possible to retrieve the password from the database once it has been assigned to the user.</p><p>MD5 is a one way hashing algorithm which means that once you encode a string with it, it is virtually impossible to decrypt it due to the vast amounts of computing power that would be necessary to do so.</p><p>If you select the Master option, it will not be possible to delete this user from the Olate Download admin area.</p>';
$language['description_users_added']				= 'The user has been added to the database.';
$language['description_users_delete']				= 'To delete a user, please select it from the list below. Users marked as Master cannot be deleted.';
$language['description_users_deleted']				= 'The user has been deleted from the database.';
$language['description_other_changelog']			= 'You can review all changes made to the script since version 2.0.0 in the Change Log available <a href="http://www.olate.com/scripts/Olate Download/changelog.php" target="_blank">here</a>.';
$language['description_other_license']				= 'The latest version of the Olate License that this script is used under is retrieved from the Olate server and shown below. You can see when the license was last updated by the date at the bottom of the page.';
$language['description_other_mailinglist']			= 'You can receive information about the latest updates and new releases via the <a href="http://www.olate.com/list/index.php" target="_blank">Olate Scripts Announcements Mailing List</a>.';
$language['description_users_master']				= 'The user you selected cannot be deleted as it is marked as a Master user.';
$language['description_other_support']				= '<p>The scripts available on Olate are produced for free. Support is provided for each script in the <a href="http://www.olate.com/forums" target="_blank">Forums </a> only. No e-mail support is provided. The members of the forums, including Olate team members are under no obligation to provide support so do not expect instant help with every issue. If a member knows the solution to your problem, they will assist. </p><p>Note that support is only provided for unmodified versions of the scripts - i.e. the originals downloaded from Olate. Support for modified versions should be obtained from the author.</p>';
$language['description_other_updates']				= '<p>This page checks the Olate server for any available updates for Olate Download to ensure that your copy of the script is always up to date. If an update is available, details will be displayed along with a link to allow you to download the update.</p><p><strong>Olate Update Server Response:</strong></p>';
$language['link_addcategory']						= 'Add New Category';
$language['link_adddownload']						= 'Add New Download';
$language['link_adduser']							= 'Add New User';
$language['link_administration']					= 'Administration';
$language['link_adminmain']							= 'Back to Admin Index';
$language['link_clicktologin']						= 'Click here to login';
$language['link_deletecategory']					= 'Delete Existing Category';
$language['link_deletedownload']					= 'Delete Existing Download';
$language['link_deleteuser']						= 'Delete Existing User';
$language['link_editcategory']						= 'Edit Existing Category';
$language['link_editdownload']						= 'Edit Existing Download';
$language['link_generalsettings']					= 'General Settings';
$language['link_languages']							= 'Languages';
$language['link_languages_viewgenconfig']			= 'View General Configuration';
$language['link_languages_viewlangconfig']			= 'View Language Configuration';
$language['link_license']							= 'License';
$language['link_logout']							= 'Logout';
$language['link_support']							= 'Technical Support';
$language['link_updates']							= 'Updates';
$language['link_viewmain']							= 'View Downloads Main Page';
$language['title_admin']							= 'Administration';
$language['title_admin_main']						= 'Administration - Main';
$language['title_categories']						= 'Categories:';
$language['title_categories_add']					= ' - Categories - Add New Category';
$language['title_categories_delete']				= ' - Categories - Delete Existing Category';
$language['title_categories_edit']					= ' - Categories - Edit Existing Category';
$language['title_categories_name']					= 'Name:';
$language['title_config_general']					= ' - Configuration - General Settings';
$language['title_config_language']					= ' - Configuration - Languages';
$language['title_config_language_available']		= 'Available Languages';
$language['title_config_general_alldownloads']		= 'Display "All Downloads" Link:';
$language['title_config_general_displaytd']			= 'Display "Top Downloads" Link:';
$language['title_config_general_numbertd']			= 'Number of Top Downloads:';
$language['title_config_general_numberpage']		= 'Number of Downloads Per Page:';
$language['title_config_general_path']				= 'Script Path:';
$language['title_config_general_ratings']			= 'Enable Ratings:';
$language['title_config_general_searchlink']		= 'Display "Search" Link:';
$language['title_config_general_sorting']			= 'Enable Sorting:';
$language['title_config_general_version']			= 'Version:';
$language['title_configuration']					= 'Configuration';
$language['title_downloads']						= 'Downloads:';
$language['title_downloads_add']					= ' - Downloads - Add New Download';
$language['title_downloads_category']				= 'Category:';
$language['title_downloads_custom1']				= 'Custom Field 1:';
$language['title_downloads_custom2']				= 'Custom Field 2:';
$language['title_downloads_custom_label']			= 'Label:';
$language['title_downloads_custom_value']			= 'Value:';
$language['title_downloads_date']					= 'Date:';
$language['title_downloads_delete']					= ' - Downloads - Delete Existing Download';
$language['title_downloads_description_b']			= 'Description - Brief:';
$language['title_downloads_description_f']			= 'Description - Full:';
$language['title_downloads_edit']					= ' - Downloads - Edit Existing Download';
$language['title_downloads_image']					= 'Image Location:';
$language['title_downloads_location']				= 'File Location:';
$language['title_downloads_name']					= 'Name:';
$language['title_downloads_size']					= 'File Size:';
$language['title_master']							= 'Master:';
$language['title_other']							= 'Other:';
$language['title_users_add']						= ' - Users - Add New User';
$language['title_users_delete']						= ' - Users - Delete Existing User';
$language['title_other_changelog']					= 'Change Log:';
$language['title_other_license']					= ' - Other - License';
$language['title_other_mailinglist']				= 'Mailing List:';
$language['title_other_support']					= ' - Other - Technical Support';
$language['title_other_updates']					= ' - Other - Updates';
$language['title_password']							= 'Password:';
$language['title_script']							= 'Download Management Script';
$language['title_users']							= 'Users:';
$language['title_username']							= 'Username:';
?>