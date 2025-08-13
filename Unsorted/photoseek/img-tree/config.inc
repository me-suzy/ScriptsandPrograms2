<?php
 // file: config.inc
 // desc: PhotoSeek config file
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

if (!defined(__CONFIG_INC__)) {

define(__CONFIG_INC__, true);

include "webtools.php"; // include webtools kit
include "API.inc";	// include the API

 // ***************************************
 // internal package versioning information
 // ***************************************

 define (PACKAGE_NAME, "PhotoSeek"   );
 define (CODED_BY,     "jeff b"      );
 define (VERSION,      "0.3 (Mackey)");

 // ***********************************
 // change these to reflect your system
 // ***********************************

 define (DB_HOST,   "localhost");
 define (DB_NAME,   "photoseek");
 define (DB_USER,   "root"     );
 define (DB_PW,     "password" );
 define (DB_ENGINE,  SQL_MYSQL );
 
 // ********** LOCATIONS OF EXECUTABLES ************
 //$convert_exec  = exec ("which convert" );  // path to IM's convert 
 $convert_exec    = "/usr/bin/convert"; // path to IM's convert (static)
 $djpeg_exec      = "/usr/bin/djpeg";         // path to UNIX djpeg (static)
 $pnmscale_exec   = "/usr/bin/pnmscale";      // path to UNIX pnmscale (static)
 $cjpeg_exec      = "/usr/bin/cjpeg";         // path to UNIX cjpeg (static)
 $zip_exec        = "/usr/bin/zip";           // path to ZIP (static)
 $binhex_exec     = "/usr/bin/binhex";        // path to binhex (static)
 
 define (GRAPHICS, true);             // graphics enhanced
 define (BASIC_AUTHENTICATION, true); // authentication for search
 define (BINHEX_ENABLED, true);       // enable/disable binhex downloads
 define (ZIP_ENABLED, true);          // enable/disable zip downloads
 define (CONVERT_STYLE, "imagemagick"); // (imagemagick|unix)
 define (IMAGE_CLICK_ACTION, "info");   // (info|download|false)
 define (EPOCH, 1988);                // starting year/epoch for date funct.
 define (SHOW_ADMIN_LINK, true);      // show admin link from search page ?
 define (DISABLE_CATEGORIES, false);  // disable categories support
                                      // (useful if you don't want people
				      //  using them due to limitations...)
 define (CITY_TEXT, "Location");      // what cities are called (in case
 				      //  the installation categorizes by
				      //  "Location" instead of "City")
 define (PULLDOWNS, true);            // use pulldowns for cat/keywords?
                                      //  (turn off for speed increase)

 define (VERBOSE, false);             // Uncomment to turn on verbose output.
                                      // Useful for debugging

 define (BGCOLOR, 	"#dddddd");
 define (LINKCOLOR,	"#0000ff");
 define (VLINKCOLOR,	"#0000ff");

 // *********************************
 // set the administrative users here
 // *********************************

 $admin_users = array(
   "weasel"=>"donuts",
   "admin"=>"donuts"
   );

 define (MAX_NUM_RES_LIST,	10);  // maximum number of results (list)
 define (MAX_NUM_RES_THUMBS,	40);  // maximum number of results (thumbs)

 define (MAX_PAGES,		20);  // maximum number of result pages
 define (THUMBS_COLUMNS,	 5);  // number of thumbnail columns

} // end if defined

?>
