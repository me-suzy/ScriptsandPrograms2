<?
//////////////////////////// COPYRIGHT NOTICE //////////////////////////////
// Program Name  	 : PhotoPost PHP                                  //
// Program Version 	 : 3.1                                            //
// Contributing Developer: Michael Pierce                                 //
// Supplied By           : Goshik [WTN]                                   //
// Nullified By          : CyKuH [WTN]                                    //
//  This script is part of PhotoPost PHP, a software application by       //
// All Enthusiast, Inc.  Use of any kind of part or all of this           //
// script or modification of this script requires a license from All      //
// Enthusiast, Inc.  Use or modification of this script without a license //
// constitutes Software Piracy and will result in legal action from All   //
//                                                                        //
//           PhotoPost Copyright 2002, All Enthusiast, Inc.               //
//                       Copyright WTN Team`2002                          //
////////////////////////////////////////////////////////////////////////////
//**************** External Header Configuration ****************//
//
// the isset prevents vB headers from being sent during login/logout -
// do not remove this line as it may cause the vB
// headers to interfere with the login/logout process
//
// To include the file, first edit the header-inc.php file and then 
// uncomment out the line below

if ( !isset($skip_exheader) ) {
    //include "header-inc.php";
}

//**************** MySQL Database Configuration ****************//

// PhotoPost database host address, or leave as localhost
$host="localhost";

// PhotoPost's database name
$database="photopost";

// MySQL username and password to access PhotoPost's database
//
// These two variables are for the userid and password needed to access
// the PhotoPost database named above.
$mysql_user="root";
$mysql_password="";

// User database host address, or leave as localhost
$host_bb="localhost";

// User database MySQL database name
//
// This is the variable for the User Database; if you are using Internal
// as your registration system, then these variables are the same as the
// ones above.  If you are linking to a message board system,
// thse variables should be set to the database, user and password for that
// database.
$database_bb="photopost";

// MySQL username and password to access user database
//
// These two variables are for the userid and password needed to access
// the PhotoPost or BB database.
$user_bb="root";
$password_bb="";


//**************** Application Configuration ****************//

// Are the Boards OPEN or CLOSED?
// set this to "closed" if you want your boards to be offline
$ppboards="open";

//////////////////////// Application Configuration ////////////////////////////////
// These variables set the path to the UNZIP and MOGRIFY commands on your system
// This only needs to be set if you are allowing ZIP uploads. These are full paths,
// including the name of the executable (.exe extensions for windows)
// The -j option for Info-ZIP's UNZIP tells it to ignore paths in ZIP file
$zip_command = "/usr/bin/unzip -j";

// Path to MOGRIFY executable
// There should be no spaces in the directory names, use short names if necessary. 
// Examples:
// $mogrify_command = "c:\progra~1\imagemagick\mogrify.exe";
// $mogrify_command = "c:\ImageMagick\mogrify.exe";
$mogrify_command = "/usr/lib/X11/mogrify";

// GD2 support
// this will only work if you have GD2 or better installed
// 0 - use mogrify; 1 - use GD2
$usegd = 0;

// on-the-fly watermarks
// requires GD2 and you must edit the file watermark.php
$onthefly=0;

// Debug variable.
// 0 = No debug notifications
// 1 = Program should generate an email and send it to the site administrator
// 2 = Program should terminate with a formatted screen with error message
// When set to 0 or 1, the program will not end on non-fatal errors.
$debug=2;

// Cookie variable
// This should be set to match the path for your cookies, / sets the cookie
// to be usable throughout the site. If your BB system has a different setting,
// then you need to put that path here as well.
$cookie_path="/";

// BotBuster integration
// http://www.botbuster.com
// Set to "yes" if you have BotBuster on your system (this includes the necessary tag)
$botbuster="no";

// ZLIB compression
// Set to "1" if you want to enable Zlib compression
$compression="0";

// Date Format
// you can change the format of how dates are displayed
// keywords:
// dow = day of week (Mon, Tue, Wed...)
// month = month (Jan, Feb, Mar...)
// mm = month in numerical format, dd = date, yyyy = year
$ppdateformat = "dow month dd, yyyy";

// If you are running VB and want the server to display the time as an offset
// of GMT (for example, to the timezone where your server is located), enter the
// offset here
$gmtoffset = 0;

// IP caching
// This variable is used to track IP addresses and userids for voting and viewing purposes
// $ipcache is set in HOURS. To limit voting and view increments to once a day, you would set
// $ipcache to 24. Depending on the volume of activity on your set, you may want to monitor
// the size of your cache and adjust accordingly.
//
// Setting $ipcache to 0 disables this feature.
$ipcache = 0;

// Fonts
// Here is where you can set the fonts used throughout PhotoPost
// Multiple fonts can be specified (just as in HTML code)
$mainfonts="MS Sans Serif,Geneva,Arial";
$fontsmall = "1";
$fontmedium = "2";
$fontlarge = "3";

?>
