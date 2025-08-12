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

  
  ////////////////////////////////////////////////
  // THE USER CLICKED THE SIGN IN BUTTON
  ////////////////////////////////////////////////
  
  if( $action=="getpass" )
  {
	// check if login exists
	$res_ref = mysql_query( "SELECT * FROM al_ref WHERE email='$email'" );

	if( mysql_num_rows($res_ref)==0 )
	{
	  $notice = "Could not find an account with this email address";
	}
	else
	{
	  while( $ref = mysql_fetch_array($res_ref) )
	  {
	    email_templ( "pass_send", $ref[login] );
	  }
	  
	  $notice = "Found " . mysql_num_rows($res_ref) . " referrer(s) under this email address.";
	}
  }

  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////
  
  $info = "If you forgot the login or password of your account, enter the email address you used and all the information will be sent to you at this address. If you don't remember what email address you used, please <a href='mailto:{$CONF[admin_email]}'>email us</a>.";
  
  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
   
  shownotice( $notice );

  showinfo( $info );
  
?>

<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="action" value="getpass">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<tr><td>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td>
              <table cellpadding='4' cellspacing='1' border='0' width='100%'>
                <tr>
                  <td colspan='2' class="formfront">Retrieve Password</td>
                </tr>
                <tr class="formback">
                  <td width="65%">
                    <p><b>Webmaster Email</b><br>
              <font size="1">The email address you used to register your account</font></p>
                  </td>
                  <td width="35%">
                    <input type="text" name="email" size="45" maxlength="50">
            </td>
                </tr>
              </table>
      </td>
    </tr>
  </table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td align="center">
              <input type="submit" value="  Retrieve Password  " name="submit">
      </td>
    </tr>
  </table>
</td></tr>
</form>
</table>

<? showfooter(); ?>