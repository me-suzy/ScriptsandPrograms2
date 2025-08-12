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

  function clearhittable( $tablename, $hittype, $before )
  {
    global $sitelogin, $dbcnx;
  
    // select all hits on al_hit which are older than 24 hours
    $res_hit = mysql_query( "SELECT ref, sent FROM $tablename WHERE sent<'$before' AND site='$sitelogin'" );

    while( $hit = mysql_fetch_array($res_hit) )
    {
      $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='{$hit[ref]}' LIMIT 1" );

      if( mysql_num_rows($res_ref)>0 )
      {
        $ref = mysql_fetch_array( $res_ref );
  
        $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$sitelogin' AND FIND_IN_SET('{$ref[category]}', categories)>0 LIMIT 1" );

        // make sure the ref is active and accepted on this site
        if( mysql_num_rows($res_site)>0 && $ref[status]==1 )
        {
          mysql_query( "UPDATE al_stats SET $hittype=$hittype+1 WHERE ref='{$hit[ref]}' AND site='$sitelogin' AND day='{$hit[sent]}' LIMIT 1" );

          if( mysql_affected_rows($dbcnx)==0 )
          {
	    mysql_query( "INSERT INTO al_stats SET $hittype=1, ref='{$hit[ref]}', site='$sitelogin', day='{$hit[sent]}'" );
	  }
        }
      }
    }
	
    // done, delete the old hit entries
    mysql_query( "DELETE FROM $tablename WHERE sent<'$before' AND site='$sitelogin'" );
	
    // optimize the table after clean up
    @mysql_query( "OPTIMIZE TABLE $tablename" );
  }

  // find the time 24 hours ago
  $yesterday = date( "Y-m-d H:i:s", mktime( date("H")-24, date("i"), date("s"), date("m"), date("d"), date("Y") ) );
  
  // put the old values on the al_stats table
  clearhittable( "al_hitin", "hitsin", $yesterday );
  clearhittable( "al_hitout", "hitsout", $yesterday );
  clearhittable( "al_hitclk", "clicks", $yesterday );
  
?>