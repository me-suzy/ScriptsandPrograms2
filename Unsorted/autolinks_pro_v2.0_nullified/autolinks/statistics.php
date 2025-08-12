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

  include( "ra_initialize.php" );

  $ref = checklogin();
  
  if( !isset($page) ) $page = 1;
  if( !isset($target) ) $target = "_all_";
  
  
  ////////////////////////////////////////////////
  // THE USER CHOSE ANOTHER PAGE
  ////////////////////////////////////////////////
  
  if( isset($previous) )
  {
    $page--;
  }
  elseif( isset($next) )
  {
  	$page++;
  }
  
  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////
  
  // how many entries per page?
  $numperpage = 20;
  
  // build the where SQL to select all sites in category
  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$ref['category']}', categories)>0 ORDER BY name" );
  while( $site = mysql_fetch_array($res_site) )
  {
    if( isset($whereall) ) $whereall .= "OR ";
    $whereall .= "site='{$site[login]}' ";
  }	
  
  $query = "SELECT day, SUM(hitsin) AS totin, SUM(hitsout) AS totout FROM al_stats WHERE ref='$sesslogin'";
  if( $target=="_all_" ) $query .= " AND ( $whereall)";
  else $query .= " AND site='$target'";
  $query .= " GROUP BY day ORDER BY day DESC";

  // for the stats retriaval, we want only some entries
  $res_hit = mysql_query( $query . " LIMIT " . ($page-1)*$numperpage . ", $numperpage" );
  if( mysql_num_rows($res_hit)==0 ) $notice = "No statistics have been recorded for this site yet";
  
  // count number of pages total
  $res_totalhit = mysql_query( $query );
  $numpages = ceil( mysql_num_rows($res_totalhit) / $numperpage );
  
  // information for user
  $info = "This page tells you how many hits you sent (hits in) and how many hits were sent back to you (hits out). You can also see how well you ranked compared to other referrers. All statistics are delayed by 24 hours.";
  
  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
  
  showmenu( "statistics" );
   
  shownotice( $notice );
  
  showinfo( $info );

  if( mysql_num_rows($res_hit)>0 ):
  
?>

<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="action" value="getstats">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<tr><td>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td>
              <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td class="formfront">Day</td>
            <td class="formfront" align="center">Hits In</td>
            <td class="formfront" align="center">Hits Out</td>
			<td class="formfront" align="center">Rank</td>
          </tr>	  
<?
  while( $hit = mysql_fetch_array($res_hit) ):

	// select referers who had, this day, a totin larger than the one of this referrer
    $query = "SELECT SUM(hitsin) AS totin FROM al_stats WHERE day='{$hit[day]}'";
    if( $target=="_all_" ) $query .= " AND ( $whereall)";
	else $query .= " AND site='$target'";
    $query .= " GROUP BY ref HAVING totin>{$hit[totin]}";
	
	// count referrers and add 1
	$res_rankhit = mysql_query( $query );
	$rank = mysql_num_rows($res_rankhit) + 1;
	
?>
          <tr class="formback">
            <td><?=$hit[day]?></td>
                  <td align="center">
                    <?=$hit[totin]?>
                  </td>
                  <td align="center">
                    <?=$hit[totout]?>
                  </td>
                  <td align="center">
                    <? echo("#$rank"); ?>
                  </td>
          </tr>

<? endwhile; ?>

        </table>
      </td>
    </tr>
  </table>
        <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
          <tr>
            <td>
<?
  if( mysql_num_rows($res_site)==1 ):

    echo( "&nbsp;" );

  else:
?>
              <select name="target" onChange="this.form.submit();">
                <option value="_all_">All Sites</option>
				
<?
  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$ref['category']}', categories)>0 ORDER BY name" );

  while( $site = mysql_fetch_array($res_site) )
  {
    echo( "<option value='{$site[login]}'" );
	if( $site[login]==$target ) echo(" selected");
	echo( ">{$site[name]}</option>" );
  }					
?>		
              </select>

<? endif; ?>

            </td>
            <td align="right">
<?

  // display previous/next button?
  if( $page > 1 ) echo( "<input type='submit' value='Previous $numperpage' name='previous'>" );
  if( $page < $numpages ) echo( "<input type='submit' value='Next $numperpage' name='next'>" );

?>
      </td>
          </tr>
        </table>
</td></tr>
</form>
</table>

<? endif; ?>

<? showfooter(); ?>