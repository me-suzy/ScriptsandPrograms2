<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  // make sure the file is not included twice
  if( !defined('INITIALIZE_INCLUDED') )
  {
    define( 'INITIALIZE_INCLUDED', 1 );
	
    error_reporting( E_ALL ^ E_NOTICE );

	// include common functions and classes
    include( $path . "functions.php" );
    include( $path . "classes.php" );

	// for versions prior to 4.1
    $_GET = $HTTP_GET_VARS;
    $_POST = $HTTP_POST_VARS;
    $_FILES = $HTTP_POST_FILES;
    $_SERVER = $HTTP_SERVER_VARS;
    $_COOKIE = $HTTP_COOKIE_VARS;
  
    // remove slashes if magicquotes enabled
    if( get_magic_quotes_gpc() )
    {
	  $_GET = stripslashes_array( $_GET );
      $_POST = stripslashes_array( $_POST );
	  $_COOKIE = stripslashes_array( $_COOKIE );
    }
  
    // defines some variables
    $filesdir = "files/";
    $safemode = ini_get( "safe_mode" );
    $uploadon = ini_get( "file_uploads" );
	$ezu_version = "2.2.0";
	$ezuploadurl = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);
	$demomode = false;
	
	if( !file_exists($path.$filesdir) )
	{
	  echo( "Error, the script couldn't find the upload directory ($filesdir). Please make sure it exists." );
	  exit;
	}
  
    // load the settings
    $CONF = new tablefile( $path . "var_settings.php" );
	
	// check if the script has just been installed or upgraded
	if( $CONF->getval("version")!=$ezu_version )
		  header( "Location: install/index.php" );
	
	if( $CONF->getval("storage_method")=="file" )
	{
	  $FIELD = new tablefile( $path . "var_fields.php" );
	  $UPLOAD = new tablefile( $path . "var_uploads.php" );
	  $UPLOADINFO = new tablefile( $path . "var_uploadinfos.php" );
	  $FILE = new tablefile( $path . "var_files.php" );
	  $OPTION = new tablefile( $path . "var_options.php" );
	  $USER = new tablefile( $path . "var_users.php" );
	}
	else
	{
	  $dbcnx = mysql_connect( $CONF->getval("dbhost"), $CONF->getval("dbuser"), $CONF->getval("dbpass") );
	  if( !$dbcnx ) exit( "Cannot connect to MySQL database" );
	  
	  $results = mysql_select_db( $CONF->getval("dbname"), $dbcnx );
	  if( !$results ) exit( "Cannot find database " . $CONF->getval("dbname") );
	  
	  $FIELD = new tablesql( $dbcnx, "ezu_fields" );
	  $UPLOAD = new tablesql( $dbcnx, "ezu_uploads" );
	  $UPLOADINFO = new tablesql( $dbcnx, "ezu_uploadinfos" );
	  $FILE = new tablesql( $dbcnx, "ezu_files" );
	  $OPTION = new tablesql( $dbcnx, "ezu_options" );
	  $USER = new tablesql( $dbcnx, "ezu_users" );
	}
	
    if( isset($_GET['PHPSESSID']) )
      $PHPSESSID = $_GET['PHPSESSID'];
    elseif( isset($_POST['PHPSESSID']) )
      $PHPSESSID = $_POST['PHPSESSID'];
  
    // setup the session
    @session_start();
    $SID = session_name() . "=" . session_id();
  }
  
?>