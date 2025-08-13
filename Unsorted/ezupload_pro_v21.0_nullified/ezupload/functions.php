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

  function getipaddress()
  {
    if( getenv('HTTP_X_FORWARDED_FOR') )
    {
      $iparray = explode( ",", getenv('HTTP_X_FORWARDED_FOR') );
      return $iparray[0];
    }
    else
    {
      return getenv('REMOTE_ADDR');
    }
  }
  
  function checklogged()
  {
    global $_COOKIE, $CONF;
	
	if( $CONF->getval("adminpass")!="" )
	{
	  if( $CONF->getval("adminpass") != $_COOKIE['adminpass'] )
	  {
	    header( "Location: login.php" );
	  }
	}
  }

  function stripslashes_array($arr = array())
  {
    $rs = array();
    while (list($key,$val) = each($arr)) {
      if (is_array($val)) {
        $rs[$key] = stripslashes_array($val);
      } else {
        $rs[$key] = stripslashes($val);
      }
    }
    return $rs;
  }
  
  function rand_string( $length )
  { 
    $allchar = "abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ"; $str = ""; 
    mt_srand ((double) microtime() * 1000000); 
    for ( $i=0; $i<$length; $i++ ) $str .= substr( $allchar, mt_rand(0,52), 1 ); 
    return $str; 
  }
  
  function confirm( $msg, $url="" )
  {
    $msg = urlencode( $msg );
	$url = urlencode( $url );
  
    header( "Location: confirm.php?msg=$msg&url=$url" );
	exit;
  }

  function isemail( $email )
  {
    return ereg( '^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.  '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email );
  }
  
  function sendmessage( $mailto, $title, $content, $name, $email )
  {
    $name = stripslashes( $name );
	$title = stripslashes( $title );
	$content = stripslashes( $content );
	
	mail( $mailto, $title, $content, "From: $name <$email>\nReply-To: $email" );
  }
  
  function showheader( $section )
  {
	include( "header.php" );
  }
  
  function showfooter( $section )
  {
    include( "footer.php" );
  }
  
  function showspace( $height=25 )
  {
    echo( "<table width='100%' border='0' cellspacing='0' cellpadding='0' height='" . $height . "'>" );
    echo( "<tr><td><img src='../dot.gif' width='1' height='1'></td></tr>" );
    echo( "</table>" );
  }
  
  function getsqldate( $datetime )
  {
    // convert to unix timestamp
	$timestamp = strtotime ( $datetime );
  
	echo( date("m/d/y", $timestamp) );
  }
  
  function getaltclass()
  {
    static $row = 0;
  
    $row++;
  
	if( $row % 2 ) 
	  return "class='altfirst'";
	else
	  return "class='altsecond'";
  }
  
  function getmenuclass( $section, $cursection )
  {
    if( $section==$cursection )
	  return "class='menusel'";
	else
	  return "class='menunosel'";
  }
  
  function gettypename( $type )
  {
    switch( $type )
	{
	  case "file": $typename = "File Field"; break;
	  case "text": $typename = "Simple Text"; break;
	  case "password": $typename = "Password"; break;
	  case "textarea": $typename = "Text Area"; break;
	  case "dropbox": $typename = "Drop Box"; break;
	}
	
	return $typename;
  }
  
  function getyesno( $bool )
  {
    return $bool ? "Yes" : "No";
  }
  
  function getstatus( $status )
  {
    if( $status )
	  return "True";
	else
	  return "False";
  }
  
?>