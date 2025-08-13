<?php

##################################################

# /admin/tabfile-upload.php

##################################################

define( "TFUPL_HEADER", "TAB-DELIMITED FILE UPLOAD" );

define( "TFUPL_ERROR", "Error with the file upload." );
define( "TFUPL_ERROR_NOTXT", "Sorry, but this is not considered as a text file" );

define( "TFUPL_PROCEED", "Proceed to the file import tool" );
define( "TFUPL_RETURN", "Return to the upload form" );

define( "TFUPL_COLS_TR", "Title" );
define( "TFUPL_COLS_AR", "Artist" );
define( "TFUPL_COLS_AL", "Album" );
define( "TFUPL_COLS_GE", "Genre" );
define( "TFUPL_COLS_FS", "Size" );
define( "TFUPL_COLS_TI", "Time" );
define( "TFUPL_COLS_TN", "Track Number" );
define( "TFUPL_COLS_TC", "Track Count" );
define( "TFUPL_COLS_YR", "Year" );
define( "TFUPL_COLS_DT", "Date" );
define( "TFUPL_COLS_DA", "Date Added" );
define( "TFUPL_COLS_BR", "Bit Rate" );
define( "TFUPL_COLS_SR", "Sample Rate" );
define( "TFUPL_COLS_VA", "Volume Adjustment" );
define( "TFUPL_COLS_FK", "Kind" );
define( "TFUPL_COLS_CT", "Comments" );
define( "TFUPL_COLS_LC", "Location" );

define( "TFUPL_CAPTION_1", "Uploaded files to be imported must be tab delimited, and have the following columns" );
define( "TFUPL_CAPTION_2", "The import script will replace all \":\" found in the location field, if the latter does not contain \"://\", by \"/\" to best cope with the Mac OS directory separators, as this feature was originally designed for the C&G Soundjam and Apple iTunes text export format. Be aware that <i>some software products</i> have a tendency to rename filenames in the location column that are greater than 31 characters..." );
define( "TFUPL_CAPTION_3", "There is a 2MB filesize limit for the upload." );
define( "TFUPL_CAPTION_4", "The netjuke does not (yet) allow for audio file uploads because posting large files from a web page can be extremely unreliable." );

define( "TFUPL_BTN", "Upload Your Tab-Delimited Files" );

##################################################

?>