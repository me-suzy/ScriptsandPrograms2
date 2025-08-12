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

  include( "al_initialize.php" );

  $ip = getipaddress();
  
  if( $CONF[find_host] )
  {
    $host = gethostbyaddr( $ip );
    $host = "*" . strstr( $host, "." );
  }
  
  $uniquehit = true;
  
  if( $CONF[unique_ip] )
  {
    $res_hit = mysql_query( "SELECT id FROM al_hitin WHERE site='$sitelogin' AND ip='$ip' LIMIT 1" );
    if( mysql_num_rows($res_hit)>0 ) $uniquehit = false;
  }
  
  if( $CONF[unique_cookie] )
  {
    if( isset($fromref) ) $uniquehit = false;
	setcookie( "fromref", $i, time() + 86400, "/" );
  }
  
  if( $uniquehit )
  {
    mysql_query( "INSERT INTO al_hitin SET sent=NOW(), ref='$i', site='$sitelogin', ip='$ip', host='$host'" );
  }
  
  $res_site = mysql_query( "SELECT url FROM al_site WHERE login='$sitelogin' LIMIT 1" );
  $site = mysql_fetch_array( $res_site );
  
  header( "Location: " . $site[url] );

?>