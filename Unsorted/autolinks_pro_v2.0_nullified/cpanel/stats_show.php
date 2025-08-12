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

  include( "cp_initialize.php" );
  
  $query = "SELECT $listby, SUM(hitsin) AS sumin, SUM(hitsout) AS sumout, SUM(clicks) AS sumclk FROM al_stats ";
  
  // count vars used in query
  $numvars = 0;
  
  if( $ref != "_all_" )
  {
    if($numvars==0) { $query .= "WHERE "; } else { $query .= "AND "; }
	$query .= "ref='$ref' ";
	$numvars++;
	
	// get the referrer name for message
	$res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$ref'" );
	$refarray = mysql_fetch_array( $res_ref );
	$refname = $refarray['name'];
  }
  else
  {
    $refname = "all referrers";
  }
  
  if( $site != "_all_" )
  {
    if($numvars==0) { $query .= "WHERE "; } else { $query .= "AND "; }
	$query .= "site='$site' ";
	$numvars++;
	
	// get the website name for message
	$res_site = mysql_query( "SELECT * FROM al_site WHERE login='$site'" );
	$sitearray = mysql_fetch_array( $res_site );
	$sitename = $sitearray['name'];
  }
  else
  {
    $sitename = "all websites";
  }
  
  if( $day != "_all_" )
  {
    if($numvars==0) { $query .= "WHERE "; } else { $query .= "AND "; }
	$query .= "day='$day' ";
	$numvars++;
  }
 
  switch( $listby )
  {
	case "day": $order = "day DESC"; $total = "All Days"; break;
	case "ref": $order = "sumin DESC"; $total = "All Referrers"; break;
	case "site": $order = "sumin DESC"; $total = "All Sites"; break;
  }
  
  $query .= "GROUP BY $listby ORDER BY $order";
  
  $res_stats = mysql_query( $query );

  if( mysql_num_rows($res_stats)==0 )
  {
  	fatalerr( "No entries could be found for these statistics." );
  }
  else
  {
    $info = "Showing hits sent by $refname to $sitename";
	if( $day!="_all_" ) $info .= " on $day";
  }
  
  $info .= ". All stats are delayed 24 hours.";
  
  // generate the details links (for TOTAL)
  $vars = "day=$day&ref=$ref&site=$site";
  $byday = "<a href='stats_show.php?listby=day&$vars'>Show Days</a>";
  $byref = "<a href='stats_show.php?listby=ref&$vars'>Show Referrers</a>";
  $bysite = "<a href='stats_show.php?listby=site&$vars'>Show Sites</a>";
	
  // count number of details links
  $numlinks = 0;
  $totdetails = "";
	
  if( $listby!="day" && $day=="_all_" )
  {
	$totdetails .= $byday;
	$numlinks++;
  }
	
  if( $listby!="ref" && $ref=="_all_" )
  {
	if( $numlinks>0 ) $totdetails .= " | ";
	$totdetails .= $byref;
	$numlinks++;
  }
	
  if( $listby!="site" && $site=="_all_" )
  {
	if( $numlinks>0 ) $totdetails .= " | ";
	$totdetails .= $bysite;
	$numlinks++;
  }

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
      <table cellpadding='4' cellspacing='1' border='0' width='100%'>
        <tr>
          <td><font color="#FFFFFF" size="1">NAME</font></td>
          <td align="center"><font color="#FFFFFF" size="1">HITS IN</font></td>
          <td align="center"><font color="#FFFFFF" size="1">HITS OUT</font></td>
	  <? if($CONF[count_clicks]): ?><td align="center"><font color="#FFFFFF" size="1">REFERRED CLICKS</font></td><? endif; ?>
        </tr>
<?
  while( $stats = mysql_fetch_array($res_stats) ):
  
    $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='{$stats[ref]}' LIMIT 1" );
	$refarray = mysql_fetch_array( $res_ref );

    $res_site = mysql_query( "SELECT * FROM al_site WHERE login='{$stats[site]}' LIMIT 1" );
	$sitearray = mysql_fetch_array( $res_site );

	switch( $listby )
	{
	  case "day": $name = $stats[day]; $day = $stats[day]; break;
	  case "ref": $name = $refarray['name']; $ref = $stats[ref]; break;
	  case "site": $name = $sitearray['name']; $site = $stats[site]; break;
	}
	
	// generate the details links
	$vars = "day=$day&ref=$ref&site=$site";
	$byday = "<a href='stats_show.php?listby=day&$vars'>Show Days</a>";
	$byref = "<a href='stats_show.php?listby=ref&$vars'>Show Referrers</a>";
	$bysite = "<a href='stats_show.php?listby=site&$vars'>Show Sites</a>";
	
	// count number of details links
	$numlinks = 0;
	$details = "";
	
	if( $listby!="day" && $day=="_all_" )
	{
	  $details .= $byday;
	  $numlinks++;
	}
	
	if( $listby!="ref" && $ref=="_all_" )
	{
	  if( $numlinks>0 ) $details .= " | ";
	  $details .= $byref;
	  $numlinks++;
	}
	
	if( $listby!="site" && $site=="_all_" )
	{
	  if( $numlinks>0 ) $details .= " | ";
	  $details .= $bysite;
	  $numlinks++;
	}
	
	$totalin += $stats[sumin];
	$totalout += $stats[sumout];
	$totalclk += $stats[sumclk];
	
?>
        <tr bgcolor="#F5F5F5">
          <td><? echo($name); ?></td>
          <td align="center">
            <? echo($stats[sumin]); ?>
          </td>
          <td align="center">
            <? echo($stats[sumout]); ?>
          </td>
<? if($CONF[count_clicks]): ?>
          <td align="center">
            <? echo($stats[sumclk]); ?>
          </td>
<? endif; ?>
        </tr>
		
        <? endwhile; ?>
		
        <tr bgcolor="#F5F5F5">
          <td><b><? echo($total); ?></b></td>
          <td align="center">
            <b><? echo($totalin); ?></b>
          </td>
          <td align="center">
            <b><? echo($totalout); ?></b>
          </td>
<? if($CONF[count_clicks]): ?>
          <td align="center">
            <b><? echo($totalclk); ?></b>
          </td>
<? endif; ?>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>