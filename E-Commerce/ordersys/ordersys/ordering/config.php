<?php
/*

OrderSys 1.5 - Configuration
----------------------------

Set up MySQL account and database first

Use a plain text or code editor (not MS Word)

Be careful about the commas, quotes, slashes, etc.

Three sections:
 Essential - you must change or fill the values between ''
 Customizable
 Authorization - read readme.txt to understand implementation
 Change not suggested

*/
///////////////////// ESSENTIAL /////////////////////////////
// MySQL server host
$host = 'localhost'; // e.g. 'localhost' (if MySQL server is on the same computer as the web server application), 129.86.35.77, etc. You may add the port address (like, 129.86.35.77:8900, if 8900 is the MySQL port)
// MySQL database name
$db_name = 'laborder';
// MySQL account username
$user = 'xxx'; // this account must have select, insert, update, delete, create and drop permissions
// MySQL account password
$pass = 'xxx';
// currency symbol; change $ to pound, etc.
$currency ="$";
// Main website -> parental website -> interface creator site
// e.g., ERS Lab -> Ordering System -> Interface creator
// Title of main website - make '' if you want it empty
$mainsite_name = 'Laboratory of Pamela Stanley';
// Main website's url - make '' if you want it empty
$mainsite_url = 'http://stanxterm.aecom.yu.edu/index.htm';
// Title of parent website - leave as such, change or make '' if you want it empty
$parentsite_name = 'OrderSys';
// Parent website's url - URL to the /ordering folder, with the last /; make '' if you want it empty
$parentsite_url = 'http://stanxterm.aecom.yu.edu/secondary/ordering/';
// Title of interface creator section of the site
$site_name = 'Interface creator';
// URL to the interface_creator folder inside ordering/ (complete, with the last /; e.g. http://www.mysite.com/path_to_interface_creator/)
$site_url = 'http://stanxterm.aecom.yu.edu/secondary/ordering/interface_creator/';
// for the print form; other parameters, style, etc., can be changed by editing print.php
$form_title = "AECOM ORDER FORM";
$chief = "P. Stanley";
$room_bldg = "Ch. 516";
$extn = "3470";
// Path to the 'uploads' folder inside interface_creator; make sure the webserver can write in this folder; also, the temporary upload folder used by PHP (depends on php.ini file used by PHP) should also be writable (usually it is so). Please put slash (/) at the end. This may be different in Windows systems - e.g. 'c:\\data\\web\\dadabik\\uploads\\' on windows systems
$upload_directory = '/Library/WebServer/Documents/secondary/ordering/interface_creator/uploads/';

////---------------CUSTOMIZABLE------------- ////

// maximum results per page
$max_results = 15;
// max size in bytes allowed for the uploaded files
$max_upload_file_size = 10000000; // 10 MB
// allowed file extensions (users will be able to upload only files having these extensions)
$allowed_file_exts_ar[0] = 'jpg';
$allowed_file_exts_ar[1] = 'gif';
$allowed_file_exts_ar[2] = 'tif';
$allowed_file_exts_ar[3] = 'tiff';
$allowed_file_exts_ar[4] = 'png';
$allowed_file_exts_ar[5] = 'txt';
$allowed_file_exts_ar[6] = 'rtf';
$allowed_file_exts_ar[7] = 'doc';
$allowed_file_exts_ar[8] = 'xls';
$allowed_file_exts_ar[9] = 'htm';
$allowed_file_exts_ar[10] = 'html';
$allowed_file_exts_ar[11] = 'csv';
$allowed_file_exts_ar[12] = 'zip';
$allowed_file_exts_ar[13] = 'sit';
$allowed_file_exts_ar[14] = 'pdf';
$allowed_file_exts_ar[15] = 'jpeg';
$allowed_file_exts_ar[16] = 'psd';
$allowed_file_exts_ar[17] = 'jpf';
$allowed_all_files = 0; // set to 1 if you want to allow all extensions, and also file without extension

////-------------AUTHORIZATION-------------////

// Please read help/readme.txt when deciding the type and degree of authorization

// if you do not wish to allow editing/deleting/adding items by visitors other than those coming from specific IP addresses, please put the allowed addresses below in the format shown in $allowed and leave $all_affect_items as "no". Else make $all_affect_items = "yes". If you do not want such outside visitors to even browse the tables, set $all_see_tables = "no". Note that a visitor needs to browse a table first to see items to edit, etc. Restrictive settings will NOT be overridden by any .htaccess settings, and that they do not restrict pages inside interface_creator/. Those pages can let one edit the data and the settings below will be virtually ineffective if one can figure out the URL to go to those pages.
$all_see_tables = "yes"; //yes/no
$all_affect_items = "yes"; //yes/no
$allowed=array( // allowed for these ip addresses
"129.98.50.118",
"129.98.50.211",
"127.0.0.1",
"129.98.51.136"
);
// every time an order form is generated, the order history table is amended. Set IP addresses and yes/no as above below if you want to restrict this. With the settings below, for persons generating the form from outside the IP addresses, the order history table will not be affected. I.e., there will not be any record kept of the order form that was generated. Restrictive settings will NOT be overridden by any .htaccess settings
$all_order_history = "no"; //yes/no
$allowed1=array( // allowed for these ip addresses
"129.98.50.118",
"129.98.50.211",
"127.0.0.1",
"129.98.51.136"
);
// regarding total expenditure display at front
$all_see_expenditure = "no"; //yes/no
$allowed2=array( // allowed for these ip addresses
"129.98.50.118",
"129.98.50.211",
"127.0.0.1",
"129.98.51.136"
);

// Enable admin authentication (0|1). If 1, an admin username and password will be needed to access the administrative pages. For this to work, the users table (or different table if specified so below) must exist in the database (with default OrderSys installation, it does). The users table comes with one in-built record for an admin account with username - root - and password - letizia. See help.htm and/or readme.txt for more. 
$enable_admin_authentication = 1;
// Enable the authentication of the user (0|1). If 1 you have to login; you must set it to 1 if you want to enable one of the authorization features below
$enable_authentication = 0;
// These features need not be enabled for the ones above to work. If one of these is enabled, they make the system user-based
// Enable delete authorization, only who inserted a record can delete it (0|1)
$enable_delete_authorization = 0;
// Enable update authorization, only who inserted a record can modify it (0|1)
$enable_update_authorization = 0;
// Enable browse authorization, only who inserted a record can view details for it (and see it listed or search for it when using interface_creator data browser) (0|1)
$enable_browse_authorization = 0;

////-------------CHANGES NOT SUGGESTED-------------////

// remove the // in front of the two lines below for debugging purpose and add // to line further below

//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
error_reporting(0);
// metatags for search engines
$meta_keywords = 'OrderSys, Laboratory, Ordering, Purchase, Purchasing, PHP, MySQL, system, inventory, lab, database, stocks, budget, administration, tracking, research, software, buying, shopping, cart';
$meta_description = 'OrderSys ordering system is a customizable, optionally user account-based, web-based PHP and MySQL - based software system for purchase ordering and tracking items; it was designed for use in a biomedical research laboratory but can be used in other settings.';
$meta_generator = 'OrderSys 1.5';
// many of the following settings apply only when working with tables in interface creator's data browser
// display the main MySQL statements of insert/search/edit/detail operations for debugging (0|1); note that the insert sql statement is will be displayed only if insert_again_after_insert is set to 1
$display_sql = 0;
// display all the MySQL statements and the MySQL error messages in case of database errors for debugging (0|1)
$debug_mode = 0;
// Relative URL of uploads directory inside interface_creator; note slash at end
$upload_relative_url = 'uploads/';
// the name of the table which contains user information and field names, and values for type of user, in the table; the user, password and user type field names; the value used to identify the administrator and the normal user roles must be a string (e.g., 'admin'); users_tab is installed by default by the Interface Creator. The table may have other fields. The password field should store MD5 encrypted passwords. The table is not accessible unless authentication is enabled and the user is an administrator. See interface_creator/help.htm and readme.txt
$users_table_name = 'users';
$users_table_username_field = 'username';
$users_table_password_field = 'md5_password';
$users_table_user_type_field = 'group';
$users_table_user_type_administrator_value = 'Administrator';
$users_table_user_type_normal_user_value = 'Normal';
// enable delete all feature (delete feature must be enabled too, from the administration interface; only for interface_creator data browser) (0|1)
$enable_delete_all_feature = 1;
// enable export to csv for excel feature (0|1)
$export_to_csv_feature = 1;
// csv separator
$csv_separator = ",";
// internal table name; the interface creator needs these tables. These are not the main tables but are anciliary tables that specify how the forms that interact with the main tables appear
$prefix_internal_table = 'dadabik_'; // you can safety leave this option as is
// the name of the main file of DaDaBIK, you can safety leave this option as is unless you need to rename index.php to something else
$dadabik_main_file = 'index_long.php';
// the name of the file for popups of DaDaBIK, you can safety leave this option as is unless you need to rename index_short.php to something else
$dadabik_short_file = 'index_short.php';
// the name of the login page of DaDaBIK, you can safety leave this option as is unless you need to rename login.php to something else
$dadabik_login_file = 'login.php';
// number of records displayed per page
$records_per_page = 10;
// ask confirmation before deleting a record? (0|1)
$ask_confirmation_delete = 1;
// show update and search button also at the top of the form (0|1)
$show_top_buttons = 0;
// maximum number of records to be displayed as duplicated during insert
$number_duplicated_records = 30;
// select similarity percentage for duplicated insert check
// works if field to check for duplicates
$percentage_similarity = 100;
// display the "I think that x is similar to y......" statement during duplication check (0|1)
$display_is_similar = 1;
// the size (number of row) of the select_multiple_menu fields
$size_multiple_select = 3;
// allow the choice "and/or" directly in the form during the search (0|1)
$select_operator_feature = 1;
// default operator (or/and), if the previous is set to 0
$default_operator = 'and';
// target window for details/edit/delete (not insert), 'self' is the same window, 'blank' a new window; 'blank' will open as popup.
$edit_target_window = 'blank';
// popup window parameters; are passed for Javascript; needed if 'blank' above
$popup_parameters = 'height=400,width=400,scrollbars=yes,resizable=yes';
// coloumn at which a text, textarea, password and select_single field will be wrapped in the results, this value determines also the width of the coloumn in the results table if $word_wrap_fix_width is 1
$word_wrap_col = '25';
// allow that the $word_wrap_col value determines also the width of the coloumn in the results table (0|1)
$word_wrap_fix_width = 1;
// always wrap words at the $word_wrap_col column, even if it is necessary to cut them (0|1)
$enable_word_wrap_cut = 1;
// 'literal_english': May 31, 2002 'latin': 31/5/2002 'numeric_english': 5-31-2002
// note that, depending on your system, you can have problem displaying dates prior to 01-01-1970 or after 19-01-2038 if you use the literal english format; in particular, it is know that this problem affects windows systems
$date_format = 'literal_english';
// date field separator (divides day, month and year; used only with latin and numeric_english date format)
$date_separator = "-";
// start and end year for date field, used to build the year combo box for date fields
$start_year = 2000;
$end_year = 2015;
$delete_icon = 'images/delete.gif';
$edit_icon = 'images/update.gif';
$details_icon = 'images/details.gif';
// force the change table control to autosumbit when the user changes the table
$autosumbit_change_table_control = 1;
// choose if, after an insert, want to see again the insert form (1) or not (0)
$insert_again_after_insert = 1;
// when 'other choices' are allowed for menus, should options be auto-updated (0/1 - no/yes)
$autoupdate_options = 0;
// alias_prefix
$alias_prefix = '__'; // you can safety leave this option as is
// table_list_name name
$table_list_name = "dadabik_table_list"; // you can safety leave this option as is, you *must* leave this option as is after the installation
// you can change the wordings for many of the messages/hint/other texts here
$submit_buttons_ar = array (
	"insert"    => "Insert a new entry",
	"search/update/delete" => "Search/update/delete entries",
	"insert_short"    => "Insert",
	"search_short" => "Search",
	"new_mailing" => "New mailing",
	"check_existing_mailing" => "Check existing mailing",
	"send_mailing" => "Send existing mailing",
	"insert_anyway"    => "Insert anyway",
	"search"    => "Search for an entry",
	"update"    => "Save",
	"ext_update"    => "Update your profile",
	"yes"    => "Yes",
	"no"    => "No",
	"go_back" => "Go back",
	"edit" => "Edit",
	"delete" => "Delete",
	"details" => "Details",
	"send" => "Send",
	"print_labels" => "Print labels",
	"change_table" => "Change table"
);
$normal_messages_ar = array (
	"show_all_records" => "Show all entries",
	"logout" => "Log out",
	"top" => "Top",
	"show_all" => "Show all",
	"home" => "Home",
	"select_operator" => "Select the operator:",
	"all_conditions_required" => "All conditions required",
	"any_conditions_required" => "Any of the conditions required",
	"all_contacts" => "All contacts",
	"removed" => "removed",
	"please" => "Please",
	"and_check_form" => "and check the form.",
	"and_try_again" => "and try again.",
	"none" => "none",
	"are_you_sure" => "Are you sure?",
	"delete_all" => "Delete all",
	"really?" => "Really?",
	"delete_are_you_sure" => "You are going to delete the entry. Are you sure?",
	"required_fields_missed" => "Atleast one of the required fields (shown in purple) is empty! That field could be a field to upload a file and you may be choosing to delete an uploaded file, rendering the field empty.",
	"alphabetic_not_valid" => "You have inserted a/some number/s into an alphabetic field.",
	"numeric_not_valid" => "You have inserted a/some non-numeric characters into a numeric field.",
	"email_not_valid" => "The e-mail address/es you have inserted is/are not valid.",
	"url_not_valid" => "The url/s you have inserted is/are not valid.",
	"phone_not_valid" => "The phone number/s you have inserted is/are not valid.<br />Please use the \"+(country code)(area code)(number)\" format e.g. +390523599318.",
	"date_not_valid" => "You have inserted one or more invalid dates.",
	"similar_records" => "<br /><br />What do you want to do? These existing entry(s) seem similar to the one you want to insert. You can edit or delete some of them.",
	"no_records_found" => "No entries found.",
	"records_found" => "entries found",
	"number_records" => "Number of entries: ",
	"details_of_record" => "Entry details",
	"edit_record" => "Editing entry",
	"edit_profile" => "Update your profile information",
	"i_think_that" => "<br />I think that ",
	"is_similar_to" => " is similar to ",
	"page" => "Page ",
	"of" => " of ",
	"day" => "Day",
	"month" => "Month",
	"year" => "Year",
	"administration" => "Administration",
	"create_update_internal_table" => "Create or update internal table",
	"other...." => "... or type in",
	"insert_record" => "Insert a new entry",
	"search_records" => "Search for entries",
	"exactly" => "exactly",
	"like" => "like",
	"required_fields_red" => "Required fields are in purple.",
	"insert_result" => "Insert result: ",
	"record_inserted" => "The entry was correctly inserted. You can insert another entry below or close this window.",
	"update_result" => "Update result: ",
	"record_updated" => "The entry was correctly updated. You can re-edit below or close this window.",
	"profile_updated" => "Your profile has been correctly updated.",
	"delete_result" => "Delete result: ",
	"record_deleted" => "The entry was correctly deleted. Files, if uploaded for the entry, have also been deleted.",
	"duplication_possible" => "It appears that the new to-be-entry is like these existing entry(s). If you insert the entry, some crucial field value, such as a log-in name, may get duplicated, existing in more than one entry. This may cause problems.",
	"filename_already_used" => "Attachment filename is in use; please change name.",
	"created" => "created",
	"all_records_found" => "all entries found",
	"add_contacts_to" => "Add contacts to",
	"contacts" => "contacts",
	"you_have_added" => "You have added",
	"of_which_duplicated" => "of which is/are duplicated",
	"of_which_with_no_info" => "of which having not enough information",
	"is_composed_by" => "is now composed by",
	"go_back_to_home_send_or_add" => "You can now go back to the home page and send the mailing, or search and add other contacts to this mailing.",
	"fields_max_length" => "You have inserted too much text in one or more field.",
	"prefix" => "Prefix",
	"print_warning" => "Please set the print margin to (0,0,0,0) (top, bottom, left, right) in your browser in order to print correctly the labels.",
	"current_upload" => "Current file",
	"delete" => "delete",
	"total_records" => "Total entries",
	"confirm_delete?" => "Confirm delete?",
	"is_equal" => "is equal to",
	"contains" => "contains",
	"starts_with" => "starts with",
	"ends_with" => "ends with",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Export CSV (opens in Excel)"
	);
$error_messages_ar = array (
	"int_db_empty" => "Error! The internal database is empty!",
	"get" => "Error in $_GET variables. The PHP codes need to be examined",

	"no_functions" => "Error! No functions selected! Please go back to the home page.",
	"no_unique_key" => "Error! You do not have any primary key in your table.",	
	"upload_error" => "An error occurred when trying to upload the file. Either the extension is not specified in the filename - e.g., the file is named IMAGE instead of IMAGE.JPG - or, uploading of a file-type as yours is not allowed, or the file is too big.",
	"no_authorization_update_delete" => "You don't have the authorization to modify/delete this record.",
	"no_authorization_view" => "You don't have the authorization to view this record.",
	"deleted_only_authorizated_records" => "Only those records for which you have the authorization have been deleted."
	);
$login_messages_ar = array(
	"username" => "Username",
	"password" => "Password",
	"please_authenticate" => "You need to be identified to continue",
	"login" => "Log in",
	"logout" => "Log out",
	"username_password_are_required" => "Username and password are required",
	"incorrect_admin_login" => "An administrator username and password is required",
	"pwd_gen_link" => "Create password",
	"incorrect_login" => "The submitted username or password is incorrect",
	"pwd_explain_text" =>"Input a word to be used as password, click <b>crypt it!</b> to generate the MD5 hash, then click <b>register</b> to fill it in the form.",
	"pwd_suggest_email_sending"=>"You may want to send yourself a mail to remember the password",
	"pwd_send_link_text" =>"Send mail!",
	"pwd_encrypt_button_text" => "Crypt it!",
	"pwd_register_button_text" => "Register password and exit!"
);
?>