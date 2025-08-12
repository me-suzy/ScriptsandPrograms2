<?
$file_rev="041305";
$file_lang="EN";
// If you translate this file, *PLEASE* send it to me
// at darkrose@eschew.net

// Many of the variables contained in this file are used
// as common variables throughout the script. I have tried
// my best to include these variables in the "generic"
// section. I know many languages use different suffixes
// and what-not when used in context, so I have included
// the context in which some variables are used in the
// comments.
//
// Mail templates are located in the /templates/mail directory
// Error messages are located in the /lang/errors.php file

// admin menu
// titles
$LANG_menu_acct="Accounts";
$LANG_menu_administration="Administration";
$LANG_menu_tools="Tools";
$LANG_menu_nav="Navigation";

// options..
// accounts
$LANG_menu_valacct="Validate";
$LANG_menu_addacct="Add Accounts";
$LANG_menu_listacct="List Accounts";
$LANG_menu_changedefault="Default Banner";

// administration
$LANG_menu_mailer="Mailer Manager";
$LANG_menu_categories="Category Admin";
$LANG_menu_editpass="Change Password";
$LANG_menu_addadmin="Add/remove Admin";

// tools
$LANG_menu_dbtools="Database Tools";
$LANG_menu_templates="Edit Templates";
$LANG_editvars_title="Edit Variables";
$LANG_menu_editcss="Edit Style Sheet";
$LANG_menu_faqmgr="FAQ Manager";
$LANG_menu_checkbanners="Check Banners";
$LANG_menu_editcou="Edit COU";
$LANG_menu_editrules="Edit Rules";
$LANG_promo_title="Promo Manager";
$LANG_menu_pause="Pause Exchange";
$LANG_menu_unpause="UnPause Exchange";
$LANG_commerce_title="Store Manager";
$LANG_updatemgr_title="Update Manager";

// navigation
$LANG_menu_help="Help";
$LANG_menu_home="Home";
$LANG_menu_logout="Logout";

//Stats Page (/admin/stats.php)
$LANG_stats_nopend="There are no pending accounts.";
$LANG_stats_pend_sing="pending account found.";
$LANG_stats_pend_plur="pending accounts found.";
$LANG_stats_title="Admin Control Panel";
$LANG_stats_statssnapshot="Snapshot: Stats for";
$LANG_stats_valusr="Validated Users";
$LANG_stats_pendusr="Pending Users";
$LANG_stats_totexp="Total Exposures";
$LANG_stats_loosecred="Loose Credits";
$LANG_stats_totalban="Total Banners Served";
$LANG_stats_totclicks="Total Clicks to Sites";
$LANG_stats_totsicl="Total Clicks From Sites";
$LANG_stats_overrat="Overall Exchange Ratio";
$LANG_stats_pendacct="Pending Accounts";
$LANG_stats_addacct="Add an Account";

// Paused message for Stats page
$LANG_exchange_paused="<b>THE EXCHANGE IS PAUSED!</b> This means that only the default banners will be shown. Users will still continue to receive credit for exposures. To resume normal functionality, click the <b>Unpause Exchange</b> link.";

// Validate account (/admin/validate.php)
$LANG_val_instructions="The following account(s) are awaiting validation. To validate an account, click on the account name.";
// $LANG_val_awaiting is for the number of accounts at the bottom
// of the validation page. (eg: "3 account(s) awaiting validation")
$LANG_val_awaiting="account(s) awaiting validation.";
$LANG_val_noaccts="Sorry, there are currently no accounts awaiting validation.";

// Add Account (/admin/addacct.php)
// uses some of the edit account form/lang files
$LANG_addacct_title="Add an Account";
$LANG_addacct_msg="The Account has been added!";
$LANG_addacct_button="Home";

// The following are shared between the Add Account,
// edit account and validate account pages.
$LANG_edit_realname="Real Name";
$LANG_edit_login="Login ID";
$LANG_edit_pass="Password";
$LANG_edit_email="Email Address";
$LANG_edit_category="Category";
$LANG_edit_nocats="The Administrator has not defined any categories, so you can not change your category at this time";
$LANG_edit_exposures="Exposures";
$LANG_edit_credits="Credits";
$LANG_edit_clicks="Clicks to site";
$LANG_edit_siteclicks="Clicks from site";
$LANG_edit_raw="RAW Mode";
$LANG_edit_status="Account Status";
$LANG_edit_approved="Approved";
$LANG_edit_notapproved="Not Approved";
$LANG_edit_defaultacct="Default Account";
$LANG_edit_sendletter="Send Newsletter";
$LANG_edit_button_val="Validate Account";
$LANG_edit_button_reset="Reset This Page";
$LANG_edit_button_delraw="Delete RAW HTML";
$LANG_edit_button_del="Delete Account";

// Edit Account (/admin/edit.php)
$LANG_edit_title="Edit account";
$LANG_edit_heading="Edit/Validate Account";
// send e-mail link to the right of the e-mail field
$LANG_email_button_send="Send Email";
$LANG_edit_saleshist="Credit Sales History";
// for viewing raw mode HTML
$LANG_edit_raw_current="current";
$LANG_edit_button_addban="Add Banner";
$LANG_edit_button="Back to Stats";
$LANG_edit_bannerlink="View/Edit Banners";
$LANG_stats_banner_hdr="Banners";
$LANG_stats_hdr_add="Add a Banner";
// Banner report at the bottom of the edit page
// eg: "3 active banners found for [accountname]"
$LANG_edit_banners="active banner(s) found for";

// Edit/validate Confirm message:
$LANG_editconf_msg="The Account has been edited.";
$LANG_valconf_msg="The Account has been validated.";

// Banners (/admin/banners.php)
$LANG_targeturl="Target URL";
$LANG_filename="Filename";
$LANG_views="Views";
$LANG_clicks="Clicks";
$LANG_bannerurl="Banner URL";
$LANG_menu_target="Change URL(s)";
$LANG_button_banner_del="Delete Banner";
$LANG_banner_instructions="To edit a banner's target URL or banner URL, alter the data in the appropriate field, then click the <b>Change URL(s)</b> button. To delete the banner, click the <b>Delete Banner</b> link. To visit the site specified in the Target URL, click the banner belonging to that target URL.";

// this variable displays at the bottom of the "Banners" page.
// eg: "4 banner(s) found for this account"
$LANG_banner_found="banner(s) found for this account";
$LANG_stats_nobanner="No banners found for this account!";

// Delete Banner (/admin/deletebanner.php)
$LANG_delban_title="Delete Banner";
$LANG_delban_verbage="Are you sure you want to remove this banner? This is a procedure that cannot be undone.<br>";
$LANG_delban_go="Yes, Delete This Banner";
$LANG_delbanconf_verbage="The banner has been deleted!";

// Account Listing page (/admin/listall.php)
$LANG_listall_title="List All Accounts";
$LANG_listall_button_back="Back to Stats";
$LANG_listall_default="Default Accounts";
$LANG_listall_nodef="No Default Accounts found.";
$LANG_listall_def_sing="default account found.";
$LANG_listall_def_plur="default accounts found.";
$LANG_listall_nonorm="No Normal Accounts found.";
$LANG_listall_norm_head="Normal Active Accounts";
$LANG_listall_norm_sing="normal account found.";
$LANG_listall_norm_plur="normal accounts found.";

// Delete Account Page (/admin/deleteaccount.php)
$LANG_delacct_title="Delete Account";
$LANG_delacct_verbage="Are you sure you want to permenantly delete the account with the login name of";
$LANG_delacct_go="Yes, Delete Account";
$LANG_delacct_done="The following account has been deleted:";

// Default Banner (/admin/changedefaultbanner.php)
$LANG_menu_changedefault="Default Banner";
$LANG_changedefault_title="Change Default Banner";
$LANG_changedefault_message="This function changes the default banner when no banners are eligible for display for some reason. This is not the same as default accounts, as a default account is an unmetered account. The default banner is currently set to:";
$LANG_changedefault_url="Target";
$LANG_changedefault_nodefault="There is not currently a default banner set!";
$LANG_changedefault_bannerurl="Banner URL";

// Email All Accounts (/admin/email.php)
$LANG_email_title="Email All Accounts";
$LANG_email_override="Override User Preferences";
$LANG_email_override_warning="Use this option *SPARINGLY*";
$LANG_email_message="Message:<p>See the Help file for information regarding these variable functions. Valid Variables are";
$LANG_email_button_reset="Reset this Page";
$LANG_email_address="E-mail address";
$LANG_email_user="E-mail a User";
$LANG_email_senttouser="An e-mail has been sent to";
$LANG_email_return="Click here</a> to return to the account edit screen.";
$LANG_email_allcats="All Categories";

// Email Sending status page (/admin/emailgo.php)
$LANG_emailgo_title="Email All Accounts";
$LANG_emailgo_msg_all="Emailing <b>all</b> accounts (including those who opted out of mailing list)...this may take a while.";
$LANG_emailgo_msg_only="Emailing newsletter enabled accounts...this may take a while.";

// Email confirmation page (/admin/emailsend.php)
$LANG_emailconf_title="Email All Accounts - CONFIRM";
$LANG_emailconf_subject="Subject";
$LANG_emailconf_msg="Message";
$LANG_emailconf_button_send="Send Email";
$LANG_emailconf_button_reset="Reset This Page";

// Category Admin (/admin/catmain.php)
$LANG_catmain_title="Category Management";
$LANG_catmain_header="Current Categories";
$LANG_catmain_catname="Category Name";
$LANG_catmain_sites="Sites";
$LANG_catmain_addcat="Add a Category";
$LANG_catsfound_singular=" category found.";
$LANG_catsfound_plurl=" categories found.";

// Delete Category (/admin/delcat.php)
$LANG_delcat_title="Delete Category";
$LANG_delcat_acctexist="There are <b>$get_count</b> accounts currently in this category. Deleting this category will reset these accounts to the default category.  Are you <b>*SURE*</b> you wish to do this?";
$LANG_delcat_sure="Are you sure you wish to delete this category?";
$LANG_delcat_button="Yes, Delete Category";

// Delete Category Confirmation (/admin/delcatconf.php)
$LANG_delcatconf_reset="Resetting all accounts in defunct category to Default Category (this may take a moment, there are currently <b>$get_num</b> sites that need to be fixed)";
$LANG_delcatconf_status="Changing Category for <b>$name</b> account (id: <b>$id</b>)";
$LANG_delcatconf_success="The Category has been Deleted";

// Edit Category (/admin/editcat.php)
$LANG_editcat_title="Edit Category";
$LANG_editcat_catname="Category Name";
$LANG_editcat_success="The category has been edited!";

// Add Admin page (/admin/addadmin.php)
$LANG_addadmin_title="Add/remove an Administrator Account";
$LANG_addadmin_list="Current Administrators";
$LANG_addadmin_newlogin="New Login";
$LANG_addadmin_pass1="Password";
$LANG_addadmin_pass2="Password Again";

// Change Password page (/admin/editpass.php)
$LANG_editpass_title="Change Admin Password";
$LANG_editpass_newpass="New Password";
$LANG_editpass_newpass1="New Password Again";
$LANG_editpass_button="Change Password";
$LANG_editpass_reset="Reset This Page";

//Password confirmation page (/admin/pwconfirm.php)
$LANG_pwconfirm_title="Change Password";
$LANG_pwconfirm_success="Your password has been changed! Please <a href=\"index.php\">return to the login page</a> and log back in with your new password.";

// Database tools.. (/admin/dbtools.php)
$LANG_db_title="Database Tools";
$LANG_db_buname="Backup Set";
$LANG_db_budate="Date Created";
$LANG_db_delete="Delete";
$LANG_db_backupfiles="Backup files";
$LANG_db_newbuset="Create a new backup set";
$LANG_db_instructions="To create a backup set, click the \"Create a new backup set\" link (it may take a few moments on large databases). Click on the file name once the set is created to view it in your web browser. Click File/Save in your web browser to save it to your computer.<p><b>WARNING!</b> Because the Database backup directory is world readable and writable, it is strongly recommended you set up an .htaccess file to password protect the directory! At bare minimum, insure the backup files are deleted after you download/view them.";
$LANG_db_restore="Restore";
$LANG_db_upload="Upload a Backup Set";
$LANG_db_upload_button="Upload Set";

// Edit Templates (/admin/templates.php)
$LANG_menu_templates="Edit Templates";
$LANG_templates_message="Select a template you wish to edit from the drop-down menu below. The page will then load in the text box. When you are satisfied with your changes, click the Save button to save your template.";
$LANG_templates_warning="WARNING: This really does change your templates and you can really create problems by altering them if you do not know what you are doing! Change them at your own risk!";
$LANG_template_box="Template";
$LANG_template_choose="Please choose a template..";
$LANG_preview="Preview";
$LANG_valid_tags="Valid tags are:";

// Edit variables page (/admin/editvars.php)
$LANG_editvars_title="Edit Variables";
$LANG_varedit_dirs="Define the system variables. These are your global parameters that define things such as your exchange ratio, exchange name, administrator e-mail address, etc. See the <a href=\"../docs/install.php\">installation instructions</a> for details.";

$LANG_dbinstall_head="Database Info";
$LANG_varedit_dbhost="Database Host";
$LANG_varedit_dblogin="Database Login";
$LANG_varedit_dbpass="Database Password";
$LANG_varedit_dbname="Database Name";

$LANG_pathing_head="Pathing & Admin Info";
$LANG_varedit_baseurl="Base Exchange URL";
$LANG_varedit_baseurl_note="do not include trailing slash";
$LANG_varedit_basepath="Base Path";
$LANG_varedit_exchangename="Exchange Name";
$LANG_varedit_sitename="Site Name";
$LANG_varedit_adminname="Admin Name";
$LANG_varedit_adminemail="Admin Email";

$LANG_banners_head= "Banners";
$LANG_varedit_width="Banner Width";
$LANG_varedit_height="Banner Height";
$LANG_varedit_pixels="pixels";
$LANG_varedit_defrat="Default Ratio";
$LANG_varedit_showimage="Show Exchange Image";
$LANG_varedit_imageurl="Exchange Image URL";
$LANG_left="Left";
$LANG_right="Right";
$LANG_top="Top";
$LANG_bottom="Bottom";
$LANG_varedit_imageurl_msg="full URL required";
$LANG_varedit_showtext="Show Exchange Link";
$LANG_varedit_exchangetext="Text";
$LANG_varedit_reqapproval="Require Banner Approval";
$LANG_varedit_upload="Allow Uploads";
$LANG_varedit_maxsize="Maximum Filesize";
$LANG_varedit_uploadpath="Upload Path (No trailing slashes)";
$LANG_varedit_upurl="Upload directory URL";
$LANG_varedit_maxbanners="Maximum Banners";

$LANG_anticheat="Anti-Cheat";
$LANG_varedit_anticheat="Anti-Cheat method";
$LANG_varedit_cookies="Cookies";
$LANG_varedit_db="Database";
$LANG_varedit_none="None";
$LANG_varedit_duration="Duration";
$LANG_varedit_duration_msg="Seconds..";

$LANG_referral_credits="Referral & Credits";
$LANG_varedit_referral="Referral Program";
$LANG_varedit_bounty="Referral Bounty";
$LANG_varedit_startcred="Starting Credits";
$LANG_varedit_sellcredits="Sell Credits";

$LANG_misc="Miscallaneous";
$LANG_varedit_topnum="Top x will display";
$LANG_varedit_topnum_other="Accounts";
$LANG_varedit_sendemail="Send Admin Email";
$LANG_varedit_usemd5="Use MD5 Encrypted Passwords";
$LANG_varedit_usegz="Use gZip/Zend code";
$LANG_varedit_userand="Use mySQL4 rand()";
$LANG_varedit_logclicks="Log Clicks";
$LANG_varedit_userandwarn="Requires mySQL 4 or greater";
$LANG_date_format="Date Format";

// Edit Style Sheet (/admin/editcss.php)
$LANG_editcss_directionstop="Choose the Style sheet you would like to use with your exchange, then click Submit. Your changes will appear instantly. If you do not see the style sheet you would like to select, upload it to your /templates/css directory under your root exchange directory.";
$LANG_editcss_instructions1="Select the style sheet you would like to edit from the list below, then click Submit. Your sheet will load. Make your changes, then click save to write the file. You must select the edited style sheet (if you have not done so already) after saving for your changes to appear!";
$LANG_editcss_loadbutton="Load CSS";

// FAQ Manager (/admin/faq.php)
$LANG_faq_found="FAQ question(s) found.";
$LANG_faq_add="Add an article";

// Check Banners (/admin/checkbanners.php)
$LANG_checkbanners_description="This utility is designed to check all banners and target URLs currently in the Exchange. This tool is useful to clean dead accounts and broken banner links out of your database. If the banner or target URL fails a check, the account is automatically pulled from the rotation and inserted into the validation queue. You may then re-validate these accounts to insure they are valid.<p>To begin checking banners, click the <b>Check Banners</b> link below. Note that it is not necessary to use this utility if you are hosting the banners on your exchange locally.";
$LANG_status_OK="OK";
$LANG_status_broken="BROKEN";

// Edit COU/Rules (/admin/editstuff.php)
$LANG_editstuff_message="HTML is enabled. Consult the documentation for information about what the variables do (they are pretty self-explanatory).<p> Valid Variables are";
$LANG_editstuff_result="The page has been edited.";
$LANG_editstuff_url="Click here</a> to see the changes!";
$LANG_exchange_paused="<b>THE EXCHANGE IS PAUSED!</b> This means that only the default banners will be shown. Users will still continue to receive credit for exposures. To resume normal functionality, click the <b>Unpause Exchange</b> link.";

// Promo/Coupon Manager (/admin/promos.php)
$LANG_promo_title="Promo Manager";
$LANG_promo_noitems="There are currently no active promotional items. You may create new items by using the form below.";
$LANG_promo_history="View";
$LANG_promo_name="Promotion Name";
$LANG_promo_code="Code";
$LANG_promo_type="Type";
$LANG_promo_credits="Credits";
$LANG_promo_status="Options";
$LANG_promo_timestamp="Start Date";
$LANG_promo_type1="Mass Credits"; 
$LANG_promo_type2="off item"; // percentage off item. expressed as xx% off item.
$LANG_promo_type3="Special item";
$LANG_promo_add="Add a Promotion";
$LANG_promo_value="Value (if needed)";
$LANG_promo_reuse="Users can re-use coupon code";
$LANG_promo_reuseint="Reuse Interval";
$LANG_promo_reusedays="Days";
$LANG_promo_usertype="Eligible User Type";
$LANG_promo_newonly="New Users Only";
$LANG_promo_all="All Users";
$LANG_promo_listall="List All";
$LANG_promo_listdel="List Deleted";
$LANG_promo_listact="List Active";
$LANG_promo_deleted="DELETED"; // Status of promo displayed in name field
$LANG_promo_active="ACTIVE";

// Promo Details.. (/admin/promodetails.php)
$LANG_promodet_title="Coupon - Detailed View";
$LANG_promodet_overview="Coupon Overview";
$LANG_promodet_loghead="Usage Log";
$LANG_promodet_nostats="This coupon has not been used. Stats will be available once the coupon has been used by a user.";
$LANG_promodet_id="Coupon ID";
$LANG_promodet_name="Coupon Name";
$LANG_promodet_type="Coupon Type";
$LANG_promodet_code="Coupon Code";
$LANG_promodet_vals="Coupon Value";
$LANG_promodet_credits="Coupon Credits";
$LANG_promodet_reuse="User May Reuse";
$LANG_promodet_reuseint="Reuse Blackout Expire";
$LANG_promodet_reuseintdays="days";
$LANG_promodet_usertype="User Type";
$LANG_promodet_timestamp="Created On";
$LANG_promodet_status="Coupon Status";
$LANG_promodet_usedate="Date Used";

// Store Manager (/admin/commerce.php)
$LANG_commerce_title="Store Manager";
$LANG_commerce_noitems="There are currently no items available in the store. You may create new items by using the form below.";
$LANG_commerce_name="Product Name";
$LANG_commerce_credits="Credits";
$LANG_commerce_price="Price";
$LANG_commerce_purchased="Purchased";
$LANG_commerce_options="Options";
$LANG_commerce_add="Add an Item";
$LANG_commerce_edititem="Edit Item";
$LANG_commerce_filterhead="Filters";
$LANG_commerce_recordsperpage="Records per page";

$LANG_commerce_view="View/search for transactions";
$LANG_commerce_notrans="There are no transactions available to view. This could be because there are no transactions in the database or there are no transactions matching the filter you have chosen.";
$LANG_next="Next";
$LANG_previous="Previous";
$LANG_commerce_invoice="Invoice";
$LANG_commerce_user="User";
$LANG_commerce_item="Item";
$LANG_commerce_status="Payment Status";
$LANG_commerce_payment="Payment Gross";
$LANG_commerce_email="Payer Email";
$LANG_commerce_date="Date";

$LANG_commerce_filterorders="Show orders with status";
$LANG_commerce_uidsearch="Search for orders place by User ID";
$LANG_commerce_go="Go!";
$LANG_commerce_reset="Clear/reset filters";

// Login Page (/admin/index.php)
$LANG_index_title="Admin Control Panel Login";
$LANG_index_msg="Banner Exchange<br>Admin Control Panel";
$LANG_index_login="Login";
$LANG_index_password="Password";

// Logout Page (/admin/logout.php)
$LANG_logout_title="Logged Out";
$LANG_logout_msg="You have been successfully logged out!<p><a href=\"index.php\">Click Here</a> to return to the login screen.";

// Update page (/admin/update.php)
$LANG_updatemgr_title="Update Manager";
$LANG_updatemgr_inst="The update manager provides an easy way to check for and obtain updates for phpBannerExchange 2.x. In order for the update manager to work properly, you must make the file \"manifest.php\" world writable (chmod 777). This file is located in your root exchange directory. We will check these permissions for you as part of the update process. Please choose one of the following options:";
$LANG_updatemgr_full="Complete refresh/update";
$LANG_updatemgr_fulldesc="Refreshes your manifest file with the currently file version numbers and checks for updates on the master server. (Recommended)";
$LANG_updatemgr_refresh="Refresh Manifest";
$LANG_updatemgr_refreshdesc="Refreshes your manifest file with the current file version numbers only.";
$LANG_updatemgr_updonly="Update Only";
$LANG_updatemgr_updonlydesc="Obtains updates WITHOUT updating your manifest list. If your manifest is out of date, you will NOT recieve the latest updates!";
$LANG_updatemgr_checkperms="Checking permissions on the <b>manifest.php</b> file...";
$LANG_updatemgr_permok="The file <b>manifest.php</b> is writable. Continuing...";
$LANG_updatemgr_manifestcheck="Checking file versioning...";
$LANG_updatemgr_manifeststamp="The manifest was last updated";
$LANG_updatemgr_never="Never";
$LANG_updatemgr_url="Update URL";
$LANG_updatemgr_popmanifest="Populating Manifest. Please wait...";

// update manager errors (can't put these in the errors.php file)..
$LANG_updatemgr_permerror="<b>ERROR!</b> The file <b>manifest.php</b> is NOT writeable by the script! This means you can not update your manifest! Check the permissions on this file and try again (hint: chmod 777 manifest.php).";
$LANG_updatemgr_nomanifest="<b>ERROR!</b> The file <b>manifest.php</b> can't be found by the script! Insure the manifest.php file is located in the root phpBannerExchange folder and try again!";
$LANG_updatemgr_successwrite="The <b>manifest.php</b> file has been successfully written! Continuing...";
$LANG_updatemgr_getmaster="Attempting to open a connection to the master file. This make take a moment.";

$LANG_updatemgr_cantconnect="The script was not able to connect to the remote site. This could be because your version of PHP does not support this feature or your server administrator has disabled the \"<b>allow_url_fopen</b>\" directive. It could also be caused by the server being inaccessible at the moment. If the problem persists, please contact your server administrator to insure you are using PHP version 4.3.0 or higher and the <b>allow_url_fopen</b> directive is enabled in your PHP configuration.";
$LANG_updatemgr_compare="Processing version information..please wait..";
$LANG_updatemgr_uptodate="Same version";
$LANG_updatemgr_needsupdt="Different version";
// $number files found on the master list.
$LANG_updatemgr_valsfound="files found on the master list.";
$LANG_updatemgr_notupgrade="All of your files are up to date! There are no updated files waiting to be downloaded.";
$LANG_updatemgr_updwaiting="file(s) await upgrading. The following files have been updated and are ready for download:";
$LANG_updatemgr_updateinst="To install the updates, simply save the files to your computer, rename the .txt extensions to .php, and upload them to the appropriate directory on your server. The folder names are provided next to the file above (eg: \"/index.php\" means this file is your \"index.php\" file located in the root phpBannerExchange directory, \"/admin/validate.php\" is your \"validate.php\" file located in your \"/admin\" folder, etc.";
$LANG_updatemgr_changelog="View Release Notes";

// Warnings..
$LANG_installdir_warning="WARNING! The software has detected that the install folder exists! This is a security risk. It is absolutely imperative that this folder be deleted!";

// Generic words used all over the place
$LANG_yes="Yes";
$LANG_no="No";
$LANG_back="Back";
$LANG_submit="Submit";
$LANG_reset="Reset";
$LANG_emailing="Emailing";
$LANG_done="Done";
$LANG_ID="ID";
$LANG_question="Question";
$LANG_action="Action";
$LANG_delete="Delete";
$LANG_edit="Edit";
$LANG_answer="Answer";
$LANG_reactivate="Reactivate";

//MAIL TEMPLATES ARE IN THE /template/mail DIRECTORY!!

?>