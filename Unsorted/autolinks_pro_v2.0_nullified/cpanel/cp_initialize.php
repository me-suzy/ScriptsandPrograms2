<?
/////////////////////////////////////////////////////////////
// Program Name         : Autolinks Professional            
// Program Version      : 2.0                               
// Program Author       : ScriptsCenter                     
// Supplied by          : CyKuH [WTN] , Stive [WTN]         
// Nullified by         : CyKuH [WTN]                       
// Distribution         : via WebForum and Forums File Dumps
//                   (c) WTN Team `2002
/////////////////////////////////////////////////////////////

  include( "variables.php" );
  include( "cp_functions.php" );

  // remove slashes if magicquotes enabled
  if( !empty($HTTP_POST_VARS) && get_magic_quotes_gpc() )
  {
    $HTTP_POST_VARS = stripslashes_array( $HTTP_POST_VARS );
    extract( $HTTP_POST_VARS );
  }

  $dbcnx = mysql_connect( $mysql_host, $mysql_user, $mysql_pass )
    or exit( "Error! Can't connect to MySQL server. Please check the variables.php files" );

  mysql_select_db( $mysql_db )
    or exit( "Error! Can't find '$mysql_db' database. Please make sure it exists" );

  // load all the configs in an array
  $CONF = loadconf();
  if( !$CONF ) exit( "Could not load the general configurations from database!" );	

  // if password entered, check validity
  if( $CONF[admin_pass]!="" && $CONF[admin_pass]!=$cookieadmin )
  {
    header( "Location: login.php?from=$REQUEST_URI" );
  }

  // no error at first
  $fatalerr = FALSE;
  
?>