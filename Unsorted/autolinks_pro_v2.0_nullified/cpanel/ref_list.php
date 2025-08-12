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
  
  if( isset($category) )
  {
    $where = "WHERE category='$category'";
	
    $res_cat = mysql_query( "SELECT * FROM al_cat WHERE id=$category LIMIT 1" );
    $cat = mysql_fetch_array( $res_cat );
	
	$info = "Showing all referrers in category {$cat[name]}";
  }

  // check at least one referrer is on the database  
  $res_ref = mysql_query( "SELECT * FROM al_ref $where ORDER BY name" );
  if( !mysql_num_rows($res_ref) ) fatalerr( "No referrers signed up or have been invited yet." );

  if( $special=="delete" ) $notice = "Referrer successfully deleted";

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

<? if( multicats() ): ?>
          <td align="center"><font color="#FFFFFF" size="1">CATEGORY</font></td>
<? endif; ?>
		  
          <td align="center"><font color="#FFFFFF" size="1">ADDED</font></td>
          <td align="center"><font color="#FFFFFF" size="1">STATUS</font></td>
          <td align="right"><font color="#FFFFFF" size="1">ACTIONS</font></td>
        </tr>
<?
  for( $i=0; $i<mysql_num_rows($res_ref); $i++ ):

	$ref = mysql_fetch_array( $res_ref );
	
	$res_cat = mysql_query( "SELECT * FROM al_cat WHERE id='{$ref['category']}' LIMIT 1" );
	$cat = mysql_fetch_array( $res_cat );
	
	switch( $ref['status'] )
	{
	  case 0: $status = "Unactive"; break;
	  case 1: $status = "Active"; break;
	  case 2: $status = "Pending"; break;
	  case 3: $status = "Unverified"; break;
	}
?>
		
        <tr bgcolor="#F5F5F5">
          <td><b>
            <? echo($ref['name']); ?>
            </b></td>
<? if( multicats() ): ?>
          <td align="center">
            <? echo($cat['name']); ?>
          </td>
<? endif; ?>
          <td align="center">
            <? echo($ref['added']); ?>
          </td>
          <td align="center">
            <? echo($status); ?>
          </td>
          <td align="right"><a href="ref_edit.php?login=<? echo($ref['login']); ?>">Edit</a> | <a href="<? echo($ref[url]); ?>" target="_blank">Visit</a> | <a href="stats_show.php?listby=day&ref=<? echo($ref['login']); ?>&site=_all_&day=_all_">Statistics</a></td>
        </tr>
		
        <? endfor; ?>
		
      </table>
    </td>
  </tr>
</table>
</body>
</html>
