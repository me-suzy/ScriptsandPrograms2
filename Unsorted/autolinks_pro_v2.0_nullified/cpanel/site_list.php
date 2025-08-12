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
  
  $res_site = mysql_query( "SELECT * FROM al_site ORDER BY name" );
  if( !mysql_num_rows($res_site) ) fatalerr( "You haven't added any site yet! You can add one <a href='site_add.php'>here</a>" );
  
  if( $special=="delete" ) $notice = "Website successfully deleted";

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
          <td align="center"><font color="#FFFFFF" size="1">ADDED</font></td>
          <td align="center"><font color="#FFFFFF" size="1">STATUS</font></td>
          <td align="right" width="200"><font color="#FFFFFF" size="1">ACTIONS</font></td>
        </tr>
		
<?
  while( $site = mysql_fetch_array( $res_site ) ):

	switch( $site['status'] )
	{
	  case 0: $status = "Unactive"; break;
	  case 1: $status = "Active"; break;
	}
?>
		
        <tr bgcolor="#F5F5F5">
          <td><b><? echo($site['name']); ?></b></td>
          <td align="center"><? echo($site['added']); ?></td>
          <td align="center"><? echo($status); ?></td>
          <td align="right"><a href="site_edit.php?login=<? echo($site['login']); ?>">Edit</a> | <a href="site_install.php?login=<? echo($site['login']); ?>">Install</a> | <a href="stats_show?listby=day&ref=_all_&site=<? echo($site['login']); ?>&day=_all_"">Statistics</a></td>
        </tr>
		
<? endwhile; ?>
		
      </table>
    </td>
  </tr>
</table>
</body>
</html>
