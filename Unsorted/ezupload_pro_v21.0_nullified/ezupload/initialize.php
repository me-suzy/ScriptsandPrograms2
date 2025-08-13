<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.0                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : Stive [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2002
/////////////////////////////////////////////////////////////

  error_reporting( E_ALL ^ E_NOTICE );

  include( $path . "functions.php" );
  include( $path . "classes.php" );

  $_GET = $HTTP_GET_VARS;
  $_POST = $HTTP_POST_VARS;
  $_FILES = $HTTP_POST_FILES;
  $_SERVER = $HTTP_SERVER_VARS;
  $_COOKIE = $HTTP_COOKIE_VARS;
  
  // remove slashes if magicquotes enabled
  if( get_magic_quotes_gpc() )
  {
    $_POST = stripslashes_array( $_POST );
	$_GET = stripslashes_array( $_GET );
  }
  
  $filesdir = "files/";
  $safemode = ini_get( "safe_mode" );
  
  $CONF = new tablefile( $path . "var_settings.php" );
  $FIELD = new tablefile( $path . "var_fields.php" );
  $UPLOAD = new tablefile( $path . "var_uploads.php" );
  $FILE = new tablefile( $path . "var_files.php" );
  $OPTION = new tablefile( $path . "var_options.php" );
  
?>