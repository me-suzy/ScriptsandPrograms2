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
  
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!='' ORDER BY name" );

  if( mysql_num_rows($res_cat)==0 )
  {
  	fatalerr( "You haven't added any category yet! You can add one <a href='cat_add.php'>here</a>" );
  }
  
  if( $special=="new" ) $notice = "Category successfully added";
  if( $special=="delete" ) $notice = "Category successfully deleted";

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
          <td width="20%"><font color="#FFFFFF" size="1">NAME</font></td>
          <td align="center"><font color="#FFFFFF" size="1">REFERRERS ON   CATEGORY</font></td>
          <td align="right"><font color="#FFFFFF" size="1">ACTIONS</font></td>
        </tr>
<?

  while( $cat = mysql_fetch_array( $res_cat ) ):

	$res_ref = mysql_query( "SELECT * FROM al_ref WHERE category={$cat['id']}" );
	
	if( mysql_num_rows($res_ref)>0 )
	{
	  $referrers = "<a href='ref_list.php?category={$cat[id]}'>" . mysql_num_rows($res_ref) . " referrer(s)</a>";
	}
	else
	{
	  $referrers = "0 referrer(s)";
	}
?>
		
        <tr bgcolor="#F5F5F5">
          <td><b>
            <? echo($cat['name']); ?>
            </b></td>
          <td align="center"><? echo($referrers); ?></td>
          <td align="right"><a href="cat_edit.php?id=<? echo($cat['id']); ?>">Edit</a><a href="stats_site.php?login=<? echo($site['login']); ?>"></a></td>
        </tr>
		
<? endwhile; ?>
		
      </table>
    </td>
  </tr>
</table>
</body>
</html>
