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
  
  
  ////////////////////////////////////////////////
  // THE USER WANTS TO EDIT HIS INFO
  ////////////////////////////////////////////////
  
  if( $action=="editinfo" )
  {
    if( $name=="" || $url=="" || $email=="" || ( $CONF[link_thumbs] && ($thumb[0]=="" || $thumb[1]=="") ) )
    {
	  $notice = "Error! Some required fields are missing!"; 
	}
	elseif( isset($password) && $password!="" && $password!=$password2 )
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
	  // update thumb if necessary
	  if( $CONF[link_thumbs] )
	  {
	    $login = addslashes( $login );
	    $thumb = implode( "/", $thumb );
		mysql_query( "UPDATE al_ref SET	thumb='$thumb' WHERE login='$sesslogin'" );
	  }
	  
	  // update password if necessary
	  if( isset($password) && $password!="" )
	  {
	    $sesspass = $password;
		mysql_query( "UPDATE al_ref SET	password='$password' WHERE login='$sesslogin'" );
		$sesspass = $password;
	  }
	  
	  $name = addslashes( $name );
	  $description = addslashes( $description );
	  
	  mysql_query( "UPDATE al_ref SET
	  
	  				name='$name',
					url='$url',
					description='$description',
					email='$email' WHERE login='$sesslogin'" );

	  $notice = "Information successfully edited.";
	}
  }


  ////////////////////////////////////////////////
  // THE USER WANTS TO SEND A MESSAGE TO ADMIN
  ////////////////////////////////////////////////
  
  if( $action=="sendmessage" )
  {
  	if( $title=="" || $content=="" )
	{
	  $notice = "Error! Some required fields are missing.";
	}
	else
	{
	  $title = stripslashes( $title );
	  $content = stripslashes( $content );
	
	  // add a footer to the email content
	  $content .= "\n\n\nSent by the webmaster of {$ref[name]} (login: {$ref[login]})";
	 
	  mail( $CONF[admin_email], $title, $content, "From: {$ref[name]} Webmaster <{$ref[email]}>\nReply-To: {$ref[email]}" );
	
	  $notice = "Message successfully sent to administrator.";
	  
	  // clear variables to avoid display
	  $title = ""; $content = "";
	}
  }
  
  
  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////
  
  // get again the data is updated
  $ref = checklogin();
  
  $info = "Welcome to our referrer's area! On those pages you will be able to grab the latest code to link our site(s), check your statistics and edit your account. If you have any question, you may contact us using the form below.";
  
  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
  
  showmenu( "editinfo" );
   
  shownotice( $notice );
  
  showinfo( $info );

?>

<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="action" value="editinfo">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<tr><td>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td>
              <table cellpadding='4' cellspacing='1' border='0' width='100%'>
                <tr>
                  <td colspan='2' class="formfront">Edit Information</td>
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
                    <input type="text" name="name" size="45" value="<?=$ref[name]?>" maxlength="<?=$CONF[name_max]?>">
            </td>
                </tr>
                <tr class="formback">
                  <td width="65%">
                    <p><b> Site URL</b><br>
              <font size="1">The URL of your website. Please start with http:// only.</font></p>
                  </td>
                  <td width="35%">
                    <input type="text" name="url" size="45" value="<?=$ref[url]?>" maxlength="150">
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
                    <input type="text" name="description" size="45" value="<?=$ref[description]?>" maxlength="<?=$CONF[desc_max]?>">
            </td>
                </tr>
		   
                <?
  if( $CONF[link_thumbs] ):
  
  	$thumb = explode( "/", $ref[thumb] );
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
              <font size="1">Your email address. Please keep it always up to date.</font></p>
                  </td>
                  <td width="35%">
                    <input type="text" name="email" size="45" value="<?=$ref[email]?>" maxlength="50">
            </td>
                </tr>
                <tr class="formback">
                  <td width="65%">
                    <p><b>Password</b><br>
              <font size="1">To  change your password, enter it here twice (optional).</font></p>
                  </td>
                  <td width="35%">
                    <input type="password" name="password" size="20" value="" maxlength="16">
              &nbsp;
                    <input type="password" name="password2" size="20" maxlength="16">
            </td>
                </tr>
          
              </table>
      </td>
    </tr>
  </table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td align="center">
        <input type="submit" value="  Edit Information  " name="submit">
      </td>
    </tr>
  </table>
</td></tr>
</form>
</table>
<br>
<br>
<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="action" value="sendmessage">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<tr><td>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td>
              <table cellpadding='4' cellspacing='1' border='0' width='100%'>
                <tr>
                  <td colspan='2' class="formfront">Contact Administrator</td>
                </tr>
                <tr class="formback">
                  <td width="50%">
                    <p><b>Email Title</b></p>
                  </td>
                  <td width="50%" valign="top">
                    <input type="text" name="title" size="60" value="<?=$title?>">
            </td>
                </tr>
                <tr class="formback" valign="top">
                  <td width="50%">
                    <p><b> Email Content</b></p>
                  </td>
                  <td width="50%">
                    <textarea name="content" cols="59" rows="10"><?=$content?></textarea>
            </td>
                </tr>
		   
                <?
  if( $CONF[link_thumbs] ):
  
  	$thumb = explode( "/", $ref[thumb] );
?>

                <? endif; ?>
              </table>
      </td>
    </tr>
  </table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td align="center">
              <input type="submit" value="  Send Message  " name="submit">
      </td>
    </tr>
  </table>
</td></tr>
</form>
</table>

<? showfooter(); ?>