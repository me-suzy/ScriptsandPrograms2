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
  include( "ra_functions.php" );

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

  $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$sitelogin' LIMIT 1" );
  if( mysql_num_rows($res_site)==0 ) exit( "Error! Could not find a site with login '$sitelogin' on the database" );
  $site = mysql_fetch_array( $res_site );
  
  // load all the configs in an array
  $CONF = loadconf();
  if( !$CONF ) exit( "Could not load the general configurations from database!" );

  // load all the configs in an array
  $STYLE = loadrefarea();
  if( !$STYLE ) exit( "Could not load the referrers area style from database!" );
  
  session_start();
   
?>