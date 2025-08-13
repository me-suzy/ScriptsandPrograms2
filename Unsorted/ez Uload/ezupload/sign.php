<? 
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  include( "initialize.php" );

  if( $_GET['action']=="signout" )
  {
    session_unset();
	session_destroy();
	
    setcookie( "adminpass", "", time()-3600 );
  
    confirm( "You have been successfully signed out", "sign.php" );
  }
  elseif( $_POST['action']=="signin" )
  {
    // encode the password before checking it
    $passhash = md5( $_POST['adminpass'] );
  
    if( $CONF->getval("adminpass")==$passhash )
	{
	  $adminpass = $passhash;
      session_register( "adminpass" );

	  // use this for PHP 4.0.6 bug
	  $HTTP_SESSION_VARS['adminpass'] = $passhash;
	  
	  if( isset($_POST['remember']) )
	  {
	    setcookie( "adminpass", $passhash, time()+15552000, "/", $_SERVER['SERVER_NAME'] );
	  }
	  else
	  {
	    // delete any previous cookie to avoid a conflict
		// between passwords of session/cookie data
	    setcookie( "adminpass", "", time()-3600, "/", $_SERVER['SERVER_NAME'] );
	  }
	  
	  confirm( "You have been successfully signed in", "cpanel.php" );
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
<link rel="stylesheet" href="cpanel.css">
</head>

<body>
<table width="485" border="0" cellspacing="0" cellpadding="0" align="center" height="100%">
  <tr valign="top"> 
    <td align="center" valign="middle">

      <table border="0" cellspacing="0" cellpadding="1">
      <form method="post" action="sign.php">
	  <input type="hidden" name="action" value="signin">
	  <? showsession(); ?>
        <tr> 
          <td height="20" colspan="3" valign="top" align="center"> 
            This area is password-protected.
          </td>
        </tr>
        <tr height="25" valign="middle"> 
          <td align="right" valign="middle"> 
            <input type="password" name="adminpass" size="17">
          </td>
          <td width="5"></td>
          <td valign="middle"> 
            <input type="submit" name="Submit" value="  Enter  ">
          </td>
        </tr>
        <tr> 
          <td colspan="3" valign="middle" align="center"> 
            <input type="checkbox" name="remember" value="yes"> Remember? (requires cookies)
          </td>
        </tr>
	  </form>
      </table>
    </td>
  </tr>
</table>
</body>

</html>