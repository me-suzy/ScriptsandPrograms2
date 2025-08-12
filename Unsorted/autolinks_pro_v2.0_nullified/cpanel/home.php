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
  
  $res_ref = mysql_query( "SELECT * FROM al_ref ORDER BY added DESC LIMIT 15" );

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <p><b>Welcome to the AutoLinks Pro 2.0 Nullified Control Panel !</b> <br>
      On this area you will be able to control all your sites' link exchanges, no matter where they are. You can also customize the way AutoLinks looks and works, as well as get complete statistics reports.</p>
    </td>
    <td align="right" width="230" valign="top">
      </td>
  </tr>
</table>

<? if( mysql_num_rows($res_ref)>0 ): ?>

<br>
<br>
<br>
<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
      <table cellpadding='4' cellspacing='1' border='0' width='100%'>
        <tr>
          <td><font color="#FFFFFF" size="1"> LATEST REFERRERS</font></td>
<? if( multicats() ): ?>
          <td align="center"><font color="#FFFFFF" size="1">CATEGORY</font></td>
<? endif; ?>
          <td align="center"><font color="#FFFFFF" size="1">ADDED</font></td>
          <td align="center"><font color="#FFFFFF" size="1">STATUS</font></td>
          <td align="right"><font color="#FFFFFF" size="1">ACTIONS</font></td>
        </tr>
<?
  while( $ref = mysql_fetch_array($res_ref) ):

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
          <td align="right"><a href="<?=$ref[url]?>" target="_blank">Visit</a> | <a href="ref_edit.php?login=<?=$ref[login]?>">Edit</a></td>
        </tr>
		
<? endwhile; ?>
		
      </table>
    </td>
  </tr>
</table>

<? endif; ?>

</body>
</html>
