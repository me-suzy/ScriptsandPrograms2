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
  // THE USER CLICKED THE REGISTER BUTTON
  ////////////////////////////////////////////////
  
  if( $action=="register" )
  {
    $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$login' LIMIT 1" );
  
    if( $login=="" || $name=="" || $url=="" || $email=="" || $password=="" || ( $CONF[link_thumbs] && ($thumb[0]=="" || $thumb[1]=="") ) )
    {
	  $notice = "Error! Some required fields are missing!"; 
	}
	elseif( mysql_num_rows($res_ref)>0 )
	{
	  $notice = "This login is already taken by another referrer";
	}
	elseif( $password!=$password2 )
	{
	  $notice = "The two passwords entered are not the same!";
	}
	elseif( !isemail($email) )
	{
	  $notice = "Please enter a valid email address!";
	}
	elseif( strlen($description)<$CONF[desc_min] )
	{
	  $notice = "The description must be at least {$CONF[desc_min]} characters";
	}
	elseif( strlen($name)<$CONF[name_min] )
	{
	  $notice = "The site name must be at least {$CONF[name_min]} characters";
	}
	else
	{
	  if( $CONF[link_thumbs] )
	    $thumb = implode( "/", $thumb );
	  else
	    $thumb = "/";
	  
	  $login = addslashes( $login );
	  $name = addslashes( $name );
	  $description = addslashes( $description );
	  $thumb = addslashes( $thumb );
	  
	  // find status of new user
	  if( $CONF[verify_new] ) // need to verify email
	    $status = 3;
	  elseif( $CONF[moderate_new] ) // need approval
	    $status = 2;
	  else
	    $status = 1;
		
	  // random code for verification
	  $code = rand_string( 8 );
		
	  mysql_query( "INSERT INTO al_ref SET
	  
	  				login='$login',
					password='$password',
	  				name='$name',
					url='$url',
					description='$description',
					thumb='$thumb',
					email='$email',
					status='$status',
					added=NOW(),
					category='$category',
					fromsite='$sitelogin',
					code='$code'" );
	
	  // send confirmation to referrer?
	  // if moderated, will send later
	  if( $CONF[confirm_new] && !$CONF[moderate_new] && !$CONF[verify_new] )
	  {
	    email_templ( "confirm", $login );
	  }
	  elseif( $CONF[verify_new] ) // if verify, send code
	  {
	    email_templ( "verify", $login );
	  }
	  
	  // send notification to admin?
	  // if verification, send after verification 
	  if( !$CONF[verify_new] && $CONF[notify_new] )
	  {
	    email_templ( "new_ref", $login );
	  }

	  // register login/pass for sessions
	  session_register( "sesslogin" );
      session_register( "sesspass" );

	  $sesslogin = $login;
	  $sesspass = $password;

	  setcookie( "sesslogin", $login );
	  setcookie( "sesspass", $password );

	  // success, go to the second page
	  header( "Location: register2.php?PHPSESSID=$PHPSESSID" );
	}
  }
  
  
  ////////////////////////////////////////////////
  // FIRST TIME DISPLAY
  ////////////////////////////////////////////////  
  
  else
  {
    // unset session login/pass
    session_unset();

	$url = "http://";
  }

  
  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////
  
  $info = $STYLE[reginfo];
  
  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
   
  shownotice( $notice );
  
  showinfo( $info );

?>

<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="action" value="register">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<tr><td>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2' class="formfront">Register New Site</td>
          </tr>
          <tr class="formback">
            <td width="65%">
              <p><b>Site Login</b><br>
              <font size="1">The  login of your account. Don't  use space or special characters.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="login" size="45" value="<?=$login?>" maxlength="16">
            </td>
          </tr>
          <tr class="formback">
            <td width="65%">
              <p><b>Password</b><br>
              <font size="1">The password to protect the referrers area. Enter it twice.</font></p>
            </td>
            <td width="35%">
              <input type="password" name="password" size="20" value="<?=$password?>" maxlength="16">
			  &nbsp;
			  <input type="password" name="password2" size="20" value="<?=$password2?>" maxlength="16">
            </td>
          </tr>
          <tr class="formback">
            <td width="65%">
              <p><b>Site Name</b><br>
              <font size="1">The name of your website. Between 
                <?=$CONF[name_min]?>
                 and 
                <?=$CONF[name_max]?>
                 characters.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="name" size="45" value="<?=$name?>" maxlength="<?=$CONF[name_max]?>">
            </td>
          </tr>
          <tr class="formback">
            <td width="65%">
              <p><b> Site URL</b><br>
              <font size="1">The URL of your website. Please start with http:// only.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="url" size="45" value="<?=$url?>" maxlength="150">
            </td>
          </tr>
          <tr class="formback">
            <td width="65%">
              <p><b> Description</b><br>
              <font size="1">Description of your website. Between 
                <? echo($CONF[desc_min]); ?>
                 and 
                <? echo($CONF[desc_max]); ?>
                 characters.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="description" size="45" value="<?=$description?>" maxlength="<?=$CONF[desc_max]?>">
            </td>
          </tr>
		   
<?
  if( $CONF[link_thumbs] ):
?>

          <tr class="formback">
            <td width="65%">
              <p><b>Thumb Name</b><br>
              <font size="1">Your site name  as it will appear above and below thumb images.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="thumb[]" size="20" value="<?=$thumb[0]?>" maxlength="15">
              &nbsp;
              <input type="text" name="thumb[]" size="20" value="<?=$thumb[1]?>" maxlength="15">
            </td>
          </tr>

<? endif; ?>
			  
          <tr class="formback">
            <td width="65%">
              <p><b>Your Email</b><br>
              <font size="1">Your email address. Keep it always up to date.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="email" size="45" value="<?=$email?>" maxlength="50">
            </td>
          </tr>
		  
<?
  if( !multicats($sitelogin) ):

    // only one category, select it
    echo( "<input type='hidden' name='category' value='{$site[categories]}'>" );
	
  else:
?>
		  
          <tr class="formback">
            <td width="65%">
              <p><b>  Site Category</b><br>
              <font size="1">The category  your website best fits under.</font></p>
            </td>
            <td width="35%">
			  <select name="category">

<? 		
  $catarray = explode( ",", $site[categories] );
  
  while( list($k, $v) = each($catarray) )
  {
    $res_cat = mysql_query( "SELECT * FROM al_cat WHERE id=$v LIMIT 1" );
    $cat = mysql_fetch_array( $res_cat );
	
	if( $cat[selectable] )
	{
	  echo( "<option value='$v'" );
	  if( $v==$category ) echo( " selected" );
	  echo( ">{$cat['name']}</option>" );
	}
  }
?>

              </select>
			</td>
          </tr>
		  
<? endif; ?>
		  
        </table>
      </td>
    </tr>
  </table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td align="center">
        <input type="submit" value="  Continue Registration  " name="submit">
      </td>
    </tr>
  </table>
</td></tr>
</form>
</table>

<? showfooter(); ?>