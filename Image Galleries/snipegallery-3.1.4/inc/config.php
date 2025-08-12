<?php
/**
* Snipe Gallery configuration file
*
* This file holds many of the configuration settings for snipe galley.  Although 
* you CAN edit it by hand, we do not suggest it unless you have at least a little
* bit of a clue. :)  
*
* For those without as much of a clue, you can edit all of these settings through 
* the Settings area of the Snipe Gallery Admin
* @access	private
* @package	admin
*     
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*/

/* -----------------------------------------------------------*/
/* ERROR REPORTING                                            */
/* -----------------------------------------------------------*/
/* Set the level of error reporting for PHP to use.  For testing
* we have this set to error_reporting(E_ALL), however for the live
* production environment, we recommend using a less strict 
* error reporting model to prevent confusing error messages */
error_reporting(E_ERROR);

$cfg_program_version = "v.3.1.4";


/* -----------------------------------------------------------*/
/* DATABASE VARIABLES                                         */
/* -----------------------------------------------------------*/
/* 
* Change these settings to reflect your mySQL database host, 
* username, password, and database name 
*/

/**     
* mySQL database host
* @global string $cfg_database_host
*/
$cfg_database_host = "localhost";

/**     
* mySQL database user
* @global string $cfg_database_user
*/
$cfg_database_user = "ENTER_YOUR_DATABASE_USERNAME";

/**     
* mySQL database password
* @global string $cfg_database_pass
*/
$cfg_database_pass = "ENTER_YOUR_DATABASE_PASSWORD";

/**     
* mySQL database name
* @global string $cfg_database_name
*/
$cfg_database_name = "ENTER_YOUR_DATABASE_NAME";

/**     
* Debug database errors
* 
* Allows you to print out more useful 
* database error message, or turn off mysql error reporting.
* The mysql errors that are printed to the screen tend to be 
* fairly user-friendly, so it is recommended that this variable
* be left "on", enabling users who encounter problems to be 
* able to report error messages that will help the admin 
* troubleshoot the problem. 
*
* set to 1 to leave debugging on, set to 0 to turn it off
* @global integer $cfg_debug_on
*/
$cfg_debug_on = 1;


/**     
* Language
* @global string $cfg_database_name
*/
$cfg_use_langfile = "en";


/* -----------------------------------------------------------*/
/* URL/PATH VARIABLES                                         */
/* -----------------------------------------------------------*/

/**     
* root path to snipe gallery  - no trailing slash
* @global string $cfg_app_path
*/
$cfg_app_path = $_SERVER['DOCUMENT_ROOT']."/demo";

/**     
* url to the system - no trailing slash
* @global string $cfg_app_url
*/
$cfg_app_url = "http://www.snipegallery.com/demo";

/**     
* root path to snipe gallery admin  - no trailing slash
* @global string $cfg_admin_path
*/
$cfg_admin_path = $cfg_app_path . '/admin';

/**     
* url to the snipe gallery admin - no trailing slash
* @global string $cfg_admin_url
*/
$cfg_admin_url = $cfg_app_url . '/admin';


/**     
* path to frame images directory - no trailing slash
* @global string $cfg_frames_path
*/
$cfg_frames_path = $cfg_app_path . '/frame_imgs'; 


/**     
* url to frame images directory - no trailing slash
* @global string $cfg_frames_url
*/
$cfg_frames_url = $cfg_app_url . '/frame_imgs'; 


/**     
* path to fullsize images directory - no trailing slash
* @global string $cfg_pics_path
*/
$cfg_pics_path = $cfg_app_path . '/pics'; 


/**     
* url to fullsize images directory - no trailing slash
* @global string $cfg_pics_url
*/
$cfg_pics_url = $cfg_app_url . '/pics'; 


/**     
* path to thumbnail images directory - no trailing slash
* @global string $cfg_thumb_path
*/
$cfg_thumb_path = $cfg_app_path . '/thumbs';


/**     
* url to thumbnail images directory - no trailing slash
* @global string $cfg_thumb_url
*/
$cfg_thumb_url = $cfg_app_url . '/thumbs'; 


/**     
* path to unzipping program
* @global string $cfg_unzip_path
*/
$cfg_unzip_path = 'unzip';

/* -----------------------------------------------------------*/
/* FEATURE VARIABLES                                       */
/* -----------------------------------------------------------*/


/**     
* Keep the original filename
* CAUTION - As of this version, the system does not check to see if 
* an image of the same name exists - it will overwrite your older image
* if you're not careful with file naming.
* 0 = off, 1= on
* @global integer $cfg_orig_filenames
*/
$cfg_orig_filenames = 0; 


/**     
* Display the keywords to the user?
* 0 = off, 1= on
* @global integer $cfg_display_keywords
*/
$cfg_display_keywords = 1; 


/**     
* Seach the keywords in the user display?
* 0 = off, 1= on
* @global integer $cfg_search_keywords
*/
$cfg_search_keywords = 1; 


/**     
* Set a limit to the number of images within a zip file that can be 
* manipulated
* @global integer $cfg_max_import_munge
*/
$cfg_max_import_munge = 25; 


/**     
* Set a limit to the number of images within a zip file that can be 
* imported
* @global integer $cfg_max_import
*/
$cfg_max_import = 10; 


/**     
* Force the fullsize image to be resized on the fly - useful for
* people who wish to upload directly from digital cameras or photo
* CD's.  This will cap the WIDTH of the image to whatever you set 
* your $cfg_max_fullsize_width variable to, if the image's height
* exceeds the value of cfg_use_fullsize_ceil
* Set to 1 to enable, 0 to disable.
* @global integer $cfg_use_fullsize_ceil
*/
$cfg_use_fullsize_ceil = 1; 


/**     
* Force the fullsize image to be resized on the fly - useful for
* people who wish to upload directly from digital cameras or photo
* CD's.  This will cap the HEIGHT of the image to whatever you set 
* your $cfg_max_fullsize_height variable to, if the image's height
* exceeds the value of cfg_use_fullsize_hceil
* Set to 1 to enable, 0 to disable.
* @global integer $cfg_use_fullsize_hceil
*/
$cfg_use_fullsize_hceil = 1; 


/**     
* Default image size to resize down to for fullsize images
* @global integer $cfg_max_fullsize_width
*/
$cfg_max_fullsize_width = 600; 


/**     
* Default image size to resize down to for fullsize images
* @global integer $cfg_max_fullsize_width
*/
$cfg_max_fullsize_height = 600; 


/**     
* Allow dropshadows to be used
* 0 = off, 1= on
* @global integer $cfg_use_dropshadow
*/
$cfg_use_dropshadow = 1;


/**     
* Allow picture frames to be used
* 0 = off, 1= on
* @global integer $cfg_use_frame
*/
$cfg_use_frame = 1;


/**     
* Allow cropping tool to be used.
* This should work on most browsers, but you can always
* disable it here if it doesn't work on yours.
* 0 = off, 1= on
* @global integer $cfg_enable_croptool
*/
$cfg_enable_croptool = 1;

/**     
* Allow watermarking feature for galleries
* 0 = off, 1= on
* @global integer $cfg_enable_watermark
*/
$cfg_enable_watermark = 1;

/**     
* Local import directory
* This is the directory that you will FTP files to if you wish to 
* import files from the server.  This can be left alone.
* @global string $cfg_local_import_dir
*/
$cfg_local_import_dir = $cfg_admin_path."/import/uploaded";


/* -----------------------------------------------------------*/
/* WATERMARKING VARIABLES                                       */
/* -----------------------------------------------------------*/

/**     
* path to fonts directory - no trailing slash
* @global string $cfg_font_path
*/
$cfg_font_path = $cfg_admin_path . '/fonts'; 

/**     
* quality of jpeg
* @global integer $cfg_jpeg_qual
*/
$cfg_jpeg_qual = 100; 


/**     
* size of overlaid text
* @global integer $cfg_font_size
*/
$cfg_font_size = 10; 


/**     
* name of overlaid text font - use the exact filename of the font 
* you have uploaded to the $cfg_font_path directory
* @global string $cfg_font_name
*/
$cfg_font_name = "SADNESS_.TTF"; 


/**     
* save original image in cache on server.
* this option is offered because when snipe gallery watermarks
* images, it modifis the actual image.  If you wanted to go back
* in and crop that image later, or re-do the thumbnail,  if
* the original image was nott  saved, it would only allow you 
* to modify the watermarked version.
*
* If you have a virtual hosting account and plan to upload
* a lot of images, you may want to keep an eye on your disk
* space to be sure the cache isn't eating up your disk space.
* If you ave the available space however, we do recommend that you
* enable this feature.
*
* 0 = off, 1= on
*
* @global integer $cfg_use_cache
*/
$cfg_use_cache = 1; 

/**     
* path to cache directory - no trailing slash
* @global string $cfg_cache_path
*/
$cfg_cache_path = $cfg_admin_path . '/cache'; 

/**     
* RGB of overlaid text
* @global string $cfg_font_color
*/
$cfg_font_color = "255,255,255"; 


/**     
* position of watermark text on image
* 0 = top 
* 1 = bottom 
* 2 = middle 
* @global integer $cfg_font_pos
*/
$cfg_font_pos = 1; 


/**     
* pixels from side
* @global integer $cfg_font_left_padding
*/
$cfg_font_h_padding = 15; 

/**     
* pixels from top
* @global integer $cfg_font_top_padding
*/
$cfg_font_v_padding = 20;


/* -----------------------------------------------------------*/
/* PAGINATION VARIABLES                                       */
/* -----------------------------------------------------------*/

/**     
* Number of columns across for each page.
* This number is used with the $cfg_per_page_limit value.  You will
* normally want to select a $cfg_per_page_limit value that is 
* evenly divisible by the number you select as your 
* @global integer $cfg_num_columns $cfg_num_columns.
*
* For example, the default settings are 12 images per page with
* 4 columns across.  This means that there will be 3 rows of 
* images, each with 4 images per row.  
*/
$cfg_num_columns = 4;


/**     
* This specifies how many records per page you 
* wish to display on pages where many records may be returned.  
* Once the $user_limit_view has been reached, pagination will begin  
* @global integer $cfg_per_page_limit
*/
$cfg_per_page_limit = 12;


/**     
* this allows a "google-like" pagination system, 
* showing only x number of page numbers at a time.  This can be helpful 
* if the query would result in many page number links  
* @global integer $max_pages_to_show
*/
$max_pages_to_show = 5;

/**     
* Set where the next, prev links appear on the image display
* page.  
* acceptable values: top, bottom or both
* @global string $cfg_nextprev_links
*/
$cfg_nextprev_links = "both"; 



/* -----------------------------------------------------------*/
/* IMAGE DISPLAY VARIABLES                                    */
/* -----------------------------------------------------------*/

/**     
* Thumbnail width for generated thumbnails 
* @global integer $cfg_thumb_width
*/
$cfg_thumb_width = 100;


/**     
* "On" mouseover color
* @global integer $cfg_js_on
*/
$cfg_js_on = "#FFFFFF";


/**     
* "Off" mouseover color
* @global integer $cfg_js_off
*/
$cfg_js_off = "#E8E8E8";


/**     
* Max thumbnail width for cropping tool 
* @global integer $cfg_maxthumb_width
*/
$cfg_maxthumb_width = $cfg_thumb_width;


/**     
* Max thumbnail height for cropping tool 
* @global integer $cfg_maxthumb_height
*/
$cfg_maxthumb_height = $cfg_thumb_width;


/**     
* Min thumbnail width for cropping tool 
* @global integer $cfg_minthumb_width
*/
$cfg_minthumb_width = 80;


/**     
* Min thumbnail height for cropping tool 
* @global integer $cfg_minthumb_height
*/
$cfg_minthumb_height = 80;


/**     
* Maximum image title words displayed in thumbnail
* gallery view.  This is to prevent weird layout things from 
* happening if some images have very long titles.  After the
* number of words specified has been reached, the image title
* will be truncated and followed by "..."
* @global integer $cfg_wordnumber_max
*/
$cfg_wordnumber_max = 4; 



/* -----------------------------------------------------------*/
/* IPTC VARIABLES                                             */
/* -----------------------------------------------------------*/

/**     
* Should the option be available to use IPTC meta image data?
* (If you're not sure what that means, it is safe to leave this off)
* 0 = off, 1= on
* @global integer $cfg_use_iptc_meta
*/
$cfg_use_iptc_meta = 1;


/**     
* Should the IPTC meta data option be checked by default?
* (If you're not sure what that means, it is safe to leave this off)
* 0 = off, 1= on
* @global integer $cfg_use_iptc_meta
*/
$cfg_iptc_meta_default = 1;


/**     
* Should the IPTC meta data be displayed to the user?
* (If you're not sure what that means, it is safe to leave this off)
* 0 = off, 1= on
* @global integer $cfg_use_iptc_meta
*/
$cfg_iptc_user_view = 0;


/* Find out some info about the version of gdlib that is running */
$gd_info_array = gd_info();
$min_php_version = "4.0.6";
$rec_gd_version = "2";
$min_gd_version = "1.8";

include ($cfg_admin_path."/lang/".$cfg_use_langfile.".php");
?>