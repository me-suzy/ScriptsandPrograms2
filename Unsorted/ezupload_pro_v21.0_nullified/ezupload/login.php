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
  include( "initialize.php" );

  if( $_POST['action']=="checkpass" )
  {
    $passhash = md5( $_POST['adminpass'] );
  
    if( $CONF->getval("adminpass")==$passhash )
	{
      setcookie( "adminpass", $passhash );
      confirm( "You have been successfully logged in", "cpanel.php" );
	}
    else
	{
	  confirm( "This password is not valid" );
	}
  }
?>

<html>
<head>
<title>This Area is Password-Protected</title>
<link rel="stylesheet" href="main.css">
</head>

<body>
<table width="485" border="0" cellspacing="0" cellpadding="0" align="center" height="100%">
  <tr valign="top"> 
    <td align="center" valign="middle">
	  This area is password protected.
	  
	  <? showspace(7); ?>
	  
      <table border="0" cellspacing="0" cellpadding="0">
      <form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	  <input type="hidden" name="action" value="checkpass">
        <tr> 
          <td align="right"> 
            <input type="password" name="adminpass" size="17">
          </td>
          <td width="10"></td>
          <td> 
            <input type="submit" name="Submit" value="  Enter  ">
          </td>
        </tr>
	  </form>
      </table>
      <p>&nbsp;</p>
    </td>
  </tr>
</table>
</body>

</html>