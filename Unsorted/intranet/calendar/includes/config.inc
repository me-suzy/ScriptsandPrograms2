<?php

// Site-specific settings.
// Configure these for your site.
// You shouldn't have to modify anything outside of this file.
//
/////////////////////////////////////////////////////////////////

$PROGRAM_NAME = "WebCalendar v0.9.21";
$PROGRAM_URL = "http://webcalendar.sourceforge.net/";

// Database settings
// db_type should be one of: "mysql", "oracle", "postgresql", "odbc"

// MySQL example
$db_type = "mysql";
$db_host = "localhost";
$db_login = "PUT YOUR MYSQL LOGIN NAME HERE";
$db_password = "PUT YOUR MYSQL PASWORD HERE";
$db_database = "intranet";

// Oracle example
//$db_type = "oracle";
//$db_host = ""; // use localhost
//$db_login = "webcalendar";
//$db_password = "webcal01";
// for oracle, db_database should be the name in tnsnames.ora
//$db_database = "orcl";

// ODBC example
//$db_type = "odbc";
//$db_host = ""; // not used for odbc
//$db_login = "webcalendar";
//$db_password = "webcal01";
// for oracle, db_database should be the name in tnsnames.ora
//$db_database = "webcalendar"; // this is the ODBC DSN

// Language options  The first is the name presented to users while
// the second is the filename (without the ".txt") that must exist
// in the translations subdirectory.
$languages = array (
  "English" =>"English-US",
  "Czech" => "Czech",
  "Danish" => "Danish",
  "Dutch" =>"Dutch",
  "French" =>"French",
  "German" =>"German",
  "Italian" => "Italian",
  "Korean" =>"Korean",
  "Portuguese" =>"Portuguese",
  "Spanish" =>"Spanish"
  // add new languages here!  (don't forget to add a comma at the end of
  // last line above.)
);

// Language default setting.
// Users can override this in the preferences settings for all pages except
// the login page.
$LANGUAGE = "English-US";

// Are you setting this up as a multi-user system?
// You can always start as a single-user system and change to multi-user
// later.  To enable single-user mode, uncomment out the following line
// and set it to a login name (that you would use if you ever switched to
// multi-user).  In single-user mode, you will not be prompted for a login,
// nor will you be asked to select participants for events.
// NOTE: If you select single-user and then upgrade to multi-user later,
// you'll have to add in the login name you've set below to the cal_user
// table.
//$single_user_login = "cknudsen";

// Do you want to use web-based login or use HTTP authorization?
// NOTE: You can only use HTTP authorization if PHP is built as
// an Apache module.
// NOTE #2: There's no need to use this if you're running single
// user mode.
// (Comment the following line out to use HTTP-based login rather than
// web-based login.)
//$use_http_auth = true;


// Approval: this doesn't apply if you're running single-user.  If you
// are running multi-user, you can optionally allow users to approve/confirm
// when events are added to their calendar.
// Users that have not approved/confirmed will be displayed with "(?)" next
// to their name when you view the event.
// NOTE: you will never need to approve an event that you created, only an
// an event that another user added to your calendar.
$require_approvals = TRUE; // set to TRUE or FALSE

// If email addresses are provided for users, then they will receive
// email notifications when an event is added/modified in their calendar.
// Set the from address here.  This will only be used if the person adding
// the event does not have an email address specified.
$email_fallback_from = "youremailhere@somewhere.com";

// Should we use a cookie to remember the last login?  When returning
// to the login page, users will see their login already filled in
// (without the password filled in) and the user preferences will have
// been loaded (color settings and language setting).
$remember_last_login = true;

// DEFAULT SETTINGS: everything below here can be customized by the
// user in the Preferences page.
// Default color settings.  Users can override these.
$BGCOLOR = "#C0C0C0"; // document background color
$H2COLOR = "#000000"; // color of page titles
$CELLBG = "#C0C0C0"; // color of table cells in month view
$TABLEBG = "#000000"; // lines separating table cells
$THBG = "#FFFFFF"; // background color of table column headers
$THFG = "#000000"; // text color of table column headers
$POPUP_FG = "#000000"; // text color in popup of event description
$POPUP_BG = "#FFFFFF"; // background color in popup of event description
$TODAYCELLBG = "#E0E0E0"; // color of table cells of current day in month view
//$UNAPPROVED_COLOR = "#008000"; // color of unapproved events

// Default view: should be "month", "year", "week", or "day".
$STARTVIEW = "week";

// Weeks can start on either Sunday or Monday.
// If Monday, weeks will be numbered using ISO week numbers (which start
// on the first Monday of the year.)
// US week numbers will be shown
$WEEK_START = 0; // either 0=Sunday or 1=Monday

// Preferences for time.
// Options are "24", "12"
$TIME_FORMAT = "12";

// Display unapproved events in the calendar.
// If set to "N", then they must be approved before they are displayed.
$DISPLAY_UNAPPROVED = "Y";

// Display small icons for view, edit, delete next to events.  If set
// to no, then you must click on the event to get to the view page, which
// then gives you access to these features.
$DISPLAY_ICONS = "Y";

// Display the week number in the calendar
$DISPLAY_WEEKNUMBER = "Y";

// Default hours to display when viewing day-at-a-glance
$WORK_DAY_START_HOUR = 8;
$WORK_DAY_END_HOUR = 17;


// error check
if ( $use_http_auth && strlen ( $single_user_login ) ) {
  echo "Error in config.inc: No need to define \$use_http_auth " .
    "and \$single_user_login";
  exit;
}

?>
