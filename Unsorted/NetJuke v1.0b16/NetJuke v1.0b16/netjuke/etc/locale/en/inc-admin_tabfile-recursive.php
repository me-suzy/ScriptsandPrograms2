<?php

##################################################

# /admin/tabfile-recursive.php

##################################################

define( "TFREC_HEADER", "RECURSIVE AUDIO FILE FINDER" );
define( "TFREC_HEADER_AUTO", "Auto mode" );
define( "TFREC_HEADER_INTER", "Interactive mode" );
define( "TFREC_CAPTION_1", "" );
define( "TFREC_CAPTION_2", "Please submit a sub-directory to scan, or click the form button to start from the top of your music directory. Scanned directories MUST be within the music directory as set in the preference file. If you are scanning you music directory for the first time, we strongly advise to limit the search to sub-directories." );
define( "TFREC_OPTION_1", "Recursive" );
define( "TFREC_OPTION_2", "Get info from filename from files that don't have an ID3 tag and queue them" );
define( "TFREC_OPTION_3", "Verbose" );
define( "TFREC_OPTION_4", "Import To The Database Directly" );
define( "TFREC_BTN", "Start Scanning" );

define( "TFREC_COLS_TR", "Title" );
define( "TFREC_COLS_AR", "Artist" );
define( "TFREC_COLS_AL", "Album" );
define( "TFREC_COLS_GE", "Genre" );
define( "TFREC_COLS_FS", "Size" );
define( "TFREC_COLS_TI", "Time" );
define( "TFREC_COLS_TN", "Track Number" );
define( "TFREC_COLS_TC", "Track Count" );
define( "TFREC_COLS_YR", "Year" );
define( "TFREC_COLS_DT", "Date" );
define( "TFREC_COLS_DA", "Date Added" );
define( "TFREC_COLS_BR", "Bit Rate" );
define( "TFREC_COLS_SR", "Sample Rate" );
define( "TFREC_COLS_VA", "Volume Adjustment" );
define( "TFREC_COLS_FK", "Kind" );
define( "TFREC_COLS_CT", "Comments" );
define( "TFREC_COLS_LC", "Location" );

define( "TFREC_ERROR_NOMUDIR_1", "The directory you requested to scan is not registered as this site's music directory." );
define( "TFREC_ERROR_NOMUDIR_2", "is not in the defined music directory." );
define( "TFREC_ERROR_NODIR", "Please provide a valid directory to be scanned for MP3 files." );
define( "TFREC_ERROR_NOFILE", "All the files in your music library have previously been imported." );

define( "TFREC_VIEW", "View the file to be imported." );
define( "TFREC_PROCEED", "Proceed to the file import script tool." );

define( "TFREC_SUCCESS_1", "MP3 file(s) have been successfully queued for import." );
define( "TFREC_SUCCESS_2", "No errors were found." );
define( "TFREC_SUCCESS_3", "total MP3 file(s) have been successfully queued for import." );

define( "TFREC_INSERT_1", "Inserted" );
define( "TFREC_INSERT_2", "track(s)" );
define( "TFREC_INSERT_3", "artist(s)" );
define( "TFREC_INSERT_4", "album(s)" );
define( "TFREC_INSERT_5", "genre(s)" );

define( "TFREC_FORM_HELP_1", "The following" );
define( "TFREC_FORM_HELP_2", "file(s) did not have any valid ID3 (or similar) tag." );
define( "TFREC_FORM_HELP_3", "To import these files, you will have to provide the mandatory information manually." );
define( "TFREC_FORM_HELP_4", "Only a few files are listed in the form below to limit the size of the current screen." );
define( "TFREC_FORM_BTN", "Create New Import File" );

##################################################

?>
