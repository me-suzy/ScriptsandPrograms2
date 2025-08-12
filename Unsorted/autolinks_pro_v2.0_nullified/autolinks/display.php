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

  // get the autolinks include path
  $alpath = $DOCUMENT_ROOT . $aldir;

  include( $alpath . "variables.php" );

  $dbcnx = @mysql_connect( $mysql_host, $mysql_user, $mysql_pass );

  @mysql_select_db( $mysql_db );

  
  ////////////////////////////////////////////////
  // UPDATE TAGS AND IMAGES
  ////////////////////////////////////////////////
  
  // check if the site tags/images have been updated today
  $res_site = @mysql_query( "SELECT updinterval, nextupdate FROM al_site WHERE login='$sitelogin' AND nextupdate<NOW() LIMIT 1" );

  if( $res_site && @mysql_num_rows($res_site)>0 )
  {
    // make sure we finish the code
    ignore_user_abort( true );

    include( $alpath . "al_functions.php" );

    // load all the configs in an array
    $CONF = loadconf();
    if( !$CONF ) exit( "Could not load the general configurations from database!" );

    // get the previous update before we change it (for images)
    $site = mysql_fetch_array( $res_site );
    $prevupdate = date( "Y-m-d H:i:s", strtotime($site[nextupdate]) - 900 );

    // set the next update time to avoid double update
    $nextupdate = date( "Y-m-d H:i:s", mktime( date("H"), date("i")+$site[updinterval], date("s"), date("m"), date("d"), date("Y") ) );
    mysql_query( "UPDATE al_site SET nextupdate='$nextupdate' WHERE login='$sitelogin' LIMIT 1" );

    include( $alpath . "updatestats.php" );
    include( $alpath . "updatetags.php" );
    include( $alpath . "updateimages.php" );
  }

  @mysql_close( $dbcnx );


  ////////////////////////////////////////////////
  // SHOW TAG FUNCTION
  ////////////////////////////////////////////////

  function showtag( $id )
  {
    global $alpath;

    @include( $alpath . "tags/$id.php" );
  }

?>