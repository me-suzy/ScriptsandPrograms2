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
  
  if( $submitted=="invite" )
  {
    // search for existing refs with this login
    $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$login'" );
  
    if( !$name || !$url || !$login || !$email || ( $CONF[link_thumbs] && !$thumb[0] ) || ( $CONF[link_thumbs] && !$thumb[1] ) )
    {
      $notice = "Error! Some fields are incorrect or missing!";
    }
    elseif( mysql_num_rows($res_ref)>0 )
    {
      $notice = "Error! The login you entered already exist!";
    }
	elseif( strlen($description)<$CONF[desc_min] )
	{
	  $notice = "Error! The description is less than {$CONF[desc_min]} characters!";
	}
	elseif( strlen($name)<$CONF[name_min] )
	{
	  $notice = "Error! The referrer name is less than {$CONF[name_min]} characters!";
	}
    else
    {
      // generate random password
      $password = rand_string( 8 );
	 
      // add slashes for the database 
      $name = addslashes( $name );
      $description = addslashes( $description );

	  if( $CONF[link_thumbs] )
	  {
	    $thumbstr = implode( "/", $thumb );
	    $thumbstr = addslashes( $thumbstr );
	  }
	  else
	  {
	    $thumbstr = "/";
	  }
	
	  // remove the special characters and spaces
	  $login = str_replace( " ", "", $login );
	  $login = str_replace( "'", "", $login );
	  $login = str_replace( "\'", "", $login );
	  $login = str_replace( "-", "", $login );
	  
	  // take the first site which accepts this category
	  // since only categories accepted in 1+ site can be
	  // selected, we are sure that we will find a site
	  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('$category', categories)>0 LIMIT 1" );
	  $site = mysql_fetch_array( $res_site );

      // add to database before filling variables
      mysql_query( "INSERT INTO al_ref SET

			login='$login',
			password='$password',
			name='$name',
			url='$url',
			description='$description',
			thumb='$thumbstr',
			email='$email',
			status=1,
			added=NOW(),
			category=$category,
			fromsite='{$site['login']}'" );

      email_templ( "invite", $login );

      header( "Location: ref_edit.php?login=$login&special=new" );
    }
  }
  else
  {
    // set default variables
	$url = "http://";
  }

  // see if a website site was already added
  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1" );
  if( mysql_num_rows($res_site)==0 ) fatalerr( "No website has been added yet. This must be done before you can invite a referrer. <a href='site_add.php'>Click here</a> to add a website" );

  $info = "This feature lets you invite new referrers to link your site(s). It generates an account for them with a password so that they don't have to signup. All they have to do is use the links provided in the email!";

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="invite">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">INVITE NEW REFERRER</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>  Webmaster Email</b><br>
              <font size="1">The email address of the person who maintains this site.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="email" size="35" value="<? echo($email); ?>" maxlength="50">
              </td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer Login</b><br>
              <font size="1">The login you wish to assign for this referrer.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="login" size="35" value="<? echo($login); ?>" maxlength="16">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer Name</b><br>
              <font size="1">The name of the referrer's website. Between <?=$CONF[name_min]?> and <?=$CONF[name_max]?> characters.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="name" size="35" value="<? echo($name); ?>" maxlength="<? echo($CONF[name_max]); ?>">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer URL</b><br>
              <font size="1">The URL of the referrer's website. Starts with http://.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="url" size="35" value="<? echo($url); ?>" maxlength="150">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer Description</b><br>
              <font size="1">The description can be used in various ways in  tags (for description next to text links, below banners and on mouseover). Between <?=$CONF[desc_min]?> and <?=$CONF[desc_max]?> characters.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="description" size="35" value="<? echo($description); ?>" maxlength="<? echo($CONF[desc_max]); ?>">
              </td>
          </tr>
		  
<?
  if( $CONF[link_thumbs] ):
?>
		  
          <tr bgcolor="#F5F5F5">
            <td width="65%" bgcolor="#F5F5F5">
              <p><b>Thumb Name</b><br>
              <font size="1">Those 2 fields will be used for the description above and below  thumbs images. If you do not wish to use thumbnails, you can turn them off on the settings and those fields will dissappear.</font></p>
            </td>
            <td width="35%">
              <table width="230" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <input type="text" name="thumb[]" size="15" value="<? echo($thumb[0]); ?>" maxlength="15">
                    &nbsp;
                    <input type="text" name="thumb[]" size="15" value="<? echo($thumb[1]); ?>" maxlength="15">
                                        </td>
                </tr>
              </table>
            </td>
          </tr>

<?

  endif; 
  
  if( !multicats() ):

    // take the only category available
	$res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!=''" );
	$cat = mysql_fetch_array( $res_cat );
  
    // only one category, select it
    echo( "<input type='hidden' name='category' value='{$cat[id]}'>" );
	
  else:

?>

          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Referrer Category</b><br>
              <font size="1">The category you wish to assign this referrer. On the invitation email, only the sites that accept this category of referers will be shown.</font></td>
            <td width="35%">
              <select name="category">

<?
  
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!='' ORDER BY name" );

  for( $i=0; $i<mysql_num_rows($res_cat); $i++ )
  {
    $cat = mysql_fetch_array( $res_cat );
	
	// check if category is accepted in 1+ site
	$res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$cat['id']}', categories)>0" );
	
	if( mysql_num_rows($res_site)>0 )
	{
      echo( "<option value='{$cat['id']}'" );
	  if( $cat['id']==$category ) echo( " selected" );
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
<table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value="  Invite Referrer  " name="submit">
      </td>
  </tr>
</table>
</form>
</body>
</html>
