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
  if( !isset($path) ) $path = "";
  include( $path . "initialize.php" );
  $sitepath = substr( $path, strlen($_SERVER['DOCUMENT_ROOT']) );
  
  // make sure cookies aren't used
  if( !isset($_GET['PHPSESSID']) && !isset($_POST['PHPSESSID']) )
  {
    session_unset();
    @session_start();
  }

  // already create the font styles used on all pages
  if( $CONF->getval("fonttype") || $CONF->getval("fontsizel") || $CONF->getval("fontsizes") )
  {
    if( $CONF->getval("fonttype") ) $csstype = "font-family: " . $CONF->getval("fonttype") . ";";
    if( $CONF->getval("fontsizel") ) $csssizel = "font-size: " . $CONF->getval("fontsizel") . ";";
    if( $CONF->getval("fontsizes") ) $csssizes = "font-size: " . $CONF->getval("fontsizes") . ";";
  
	$stylel = "style='$csstype $csssizel'";
	$styles = "style='$csstype $csssizes'";
  }
  
  // change the ezupload url to include the path
  if( $path!="" ) $ezuploadurl = "http://" . $_SERVER['SERVER_NAME'] . substr( $sitepath, 0, -1 );
  
  // include the language pack
  include( $path . "lang/" . $CONF->getval("language_pack") );
  
  if( !canaccessform() ) $_POST['mode'] = "password";
  
  if( isset($_POST['mode']) )
    $mode = $_POST['mode'];
  elseif( isset($_GET['mode']) )
    $mode = $_GET['mode'];
  else
    $mode = "show";

  include( "{$path}form_{$mode}.php" );
  
?>