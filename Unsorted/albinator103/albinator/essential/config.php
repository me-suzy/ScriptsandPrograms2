<?php

// Configure the following to suit your vales

$database_name = 'albinator';      // database name
$database_host = 'localhost';      // host name
$database_port = '';               // Port, leave it blank if you don't know about it
$database_user = 'albinator';      // database username
$database_pass = 'albinator';      // database password

$tbl_userinfo      = 'albinator_userinfo';     // User Information Table Name
$tbl_pictures      = 'albinator_pictures';       // Picture Information Table Name
$tbl_userwait      = 'albinator_userwait';       // Unactivated Accounts Table Name
$tbl_config        = 'albinator_config';         // Configuration TableName
$tbl_albumlist     = 'albinator_albumlist';      // Album List Table Name
$tbl_adlogs        = 'albinator_adlogs';         // Adminstration Logs Table Name
$tbl_ecards        = 'albinator_ecards';         // Ecards TableName
$tbl_publist       = 'albinator_publist';        // Public email traper list TableName
$tbl_reminders     = 'albinator_reminders';      // Reminder List Table Name
$tbl_userprofile   = 'albinator_userprofile';    // User Profile field storage

$__PConnect        = false;            // set it true if you wish to use pconnect



// vBulletin Settings 
// (tutorial :: http://www.albinator.com/manual/vb-configure.php)

// $db_register_url = 'http://www.mysite.com/vb/register.php';
// $dbBadUserAllow  = true;  // if true then it will allow the user to change bad usernames to something new.

// $integrate_db    = true;
// $intergrate_known= "vb";
// $fld_uid         = "userid";
// $fld_uid_name    = "username";
// $fld_password    = "password";
// $fld_session     = "lastactivity";
// $tbl_user_alter  = 'user';
// $cookiedomain    = "";
// $cookie_uid      = "bbuserid";
// $cookie_password = "bbpassword";
// $cookie_session  = "bblastvisit";


// Albinator Settings
$integrate_db    = false;
$tbl_user_alter  = '';
$fld_uid         = "uid";
$fld_uid_name    = "uid";
$fld_password    = "password";
$fld_session     = "sessiontime";
$cookie_uid      = "uid";
$cookie_password = "uidpassword";
$cookie_session  = false;

?>