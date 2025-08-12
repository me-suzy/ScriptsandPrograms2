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
  
  if( $action=="signin" )
  {
	// check if login exists
	$res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$login' LIMIT 1" );

    if( $login=="" || $password=="" )
    {
	  $notice = "Error! Some required fields are missing!"; 
	}
	elseif( mysql_num_rows($res_ref)==0 )
	{
	  $notice = "No referrer with this login found in database";
	}
	else
	{
	  // get the referrer status
	  $ref = mysql_fetch_array( $res_ref );
	  
	  if( $ref[password] != $password )
	  {
	    $notice = "The password for this login is incorrect";
	  }
	  elseif( $ref[status]==0 )
	  {
	  	$notice = "Your account is not active anymore";
	  }
	  elseif( $ref[status]==2 )
	  {
	    $notice = "Your account is pending for moderation";
	  }
	  elseif( $ref[status]==3 )
	  {
	    $notice = "You haven't verified your email address";
	  }
	  else
	  {
	    // register login/pass for sessions
	    session_register( "sesslogin" );
        session_register( "sesspass" );
	  
	    $sesslogin = $login;
	    $sesspass = $password;

	    setcookie( "sesslogin", $login );
	    setcookie( "sesspass", $password );

	    if( isset($from) && $from!="" )
		{
		  header( "Location: $from?PHPSESSID=$PHPSESSID" );
		}
		else
		{
		  header( "Location: editinfo.php?PHPSESSID=$PHPSESSID" );
		}
	  }
	}
  }

  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////
  
  $info = "Please enter the login and password of your account below. If you don't have an account yet, you must <a href='register.php?PHPSESSID=$PHPSESSID'>register</a> first. If you already have an account but forgot it, <a href='getpass.php?PHPSESSID=$PHPSESSID'>click here</a> to get your login information by email.";
  
  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
   
  shownotice( $notice );

  showinfo( $info );
  
?>

<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="action" value="signin">
<input type="hidden" name="from" value="<?=$from?>">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<tr><td>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2' class="formfront">Sign into Account</td>
          </tr>
          <tr class="formback">
            <td width="65%">
              <p><b>Site Login</b><br>
              <font size="1">The login of your account.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="login" size="45" maxlength="16">
            </td>
          </tr>
          <tr class="formback">
            <td width="65%">
              <p><b>Password</b><br>
              <font size="1">The password of your account.</font></p>
            </td>
            <td width="35%">
              <input type="password" name="password" size="45" maxlength="16">
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td align="center">
        <input type="submit" value="  Sign in Now  " name="submit">
      </td>
    </tr>
  </table>
</td></tr>
</form>
</table>

<? showfooter(); ?>