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
  include( "initialize.php" );

  if( isset($_POST['uploadid']) )
  {
    $url = $CONF->getval("redirecturl");

    if( !strstr($url,"?") )
      $url .= "?uploadid=" . $_POST['uploadid'];
    else
      $url .= "&uploadid=" . $_POST['uploadid'];

    header( "Location: $url" );
  }

?>