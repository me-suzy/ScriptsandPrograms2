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

  include( "al_initialize.php");

  if( $CONF[count_clicks] )
  {
    $uniquehit = true;
  
    $ip = getipaddress();
  
    if( $CONF[unique_ip] )
	{
      // make sure a referrer doesnt click his links several times
      $res_hit = mysql_query( "SELECT id FROM al_hitclk WHERE site='$sitelogin' AND toref='$o' AND ip='$ip' LIMIT 1" );
      if( mysql_num_rows($res_hit)>0 ) $uniquehit = false;
	}
	
	if( $uniquehit )
	{
      // use cookie if we can
      if( isset($fromref) )
	  {
	    $referredby = $fromref;
	  }
      else
	  {
        $res_hit = mysql_query( "SELECT ref FROM al_hitin WHERE site='$sitelogin' AND ip='$ip' LIMIT 1" );

        if( mysql_num_rows($res_hit)>0 )
        {
          $hit = mysql_fetch_array( $res_hit );
	      $referredby = $hit[ref];
        } 
	  }
	
	  if( isset($referredby) && $referredby!=$o )
	  {
	    if( $CONF[find_host] )
        {
    	  $host = gethostbyaddr( $ip );
    	  $host = "*" . strstr( $host, "." );
        }

	    mysql_query( "INSERT INTO al_hitclk SET sent=NOW(), ref='$referredby', toref='$o', site='$sitelogin', ip='$ip', host='$host'" );
	  }
	}
  }
  
  mysql_query( "INSERT INTO al_hitout SET sent=NOW(), ref='$o', site='$sitelogin'" );

  // check if a redirection exists
  $res_redir = mysql_query( "SELECT url FROM al_redir WHERE ref='$o' AND site='$sitelogin' LIMIT 1" );

  if( mysql_num_rows($res_redir)==0 )
  {
    $res_ref = mysql_query( "SELECT url FROM al_ref WHERE login='$o' LIMIT 1" );
	$ref = mysql_fetch_array( $res_ref );
    header( "Location: " . $ref[url] );
  }
  else
  {
    $redir = mysql_fetch_array( $res_redir );
    header( "Location: " . $redir[url] );
  }

?>