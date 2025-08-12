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
  
  if( $submitted=="addsite" )
  {
    // check this login isn't already taken
	$res_site = mysql_query( "SELECT * FROM al_site WHERE login='$login' LIMIT 1" );
  
	if( !$name || !$url || !$login || !$alurl || !$updinterval )
 	{
      $notice = "Error! Some fields are incorrect or missing!";
    }
	elseif( mysql_num_rows($res_site) > 0 )
	{
	  $notice = "Error! This login is already taken by another site";
	}
	else
	{
      if( $banner!="none" )
	  {
	    $result = uploadimage( $banner, $banner_type, "website", $login, "banner" );
		
	    if( $result != "success" )
	    {
	      switch( $result )
	      {
	        case "errorsize": $notice .= "The banner doesn't have the right dimensions. "; break;
		    case "errortype": $notice .= "The banner must be a GIF or a JPEG image. "; break;
		    case "erroropen": $notice .= "Could not open {$banner_name} for reading. "; break;
		    case "errorwrite": $notice .= "Could not write {$banner_name} (no permission). "; break;
	      }
	    }
	  }
	  
	  if( !isset($notice) )
	  {
  	    $name = addslashes( $name );

  	    // make sure there's always an ending /
	    $alurl = checkurl( $alurl );

	    $categories = implode( ",", $category );

  	    // all correct, insert site into database
  	    mysql_query( "INSERT INTO al_site SET

				login='$login',
				name='$name',
				url='$url',
				alurl='$alurl',
				status=1,
				categories='$categories',
				updinterval=$updinterval,
				added=NOW()" );
				
	    // redirect to site installation page
	    header( "Location: site_install.php?login=$login" );
	  }
	}
  }
  else
  {
	// get the some default settings
	$url = "http://";
	$alurl = "http://";
	$updinterval = 15;
  }

  // see if a category was already added
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!=''" );
  if( !mysql_num_rows($res_cat) ) fatalerr( "No category has been added yet. This must be done if you want to setup a website. <a href='cat_add.php'>Click here</a> to add a category" );

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
<SCRIPT SRC="autofill.js" LANGUAGE="JavaScript"></script>
</head>
<body>
<? showmessage(); ?>
<form name="addsitefrm" method="post" action="<? echo($PHP_SELF); ?>" enctype="multipart/form-data">
<input type="hidden" name="submitted" value="addsite">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">REQUIRED INFORMATION</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Website Login</b><br>
              <font size="1">The handle that will be used for this website. Do not use special characters or spaces.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="login" size="35" value="<? echo($login); ?>" maxlength="16">
              </td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Website Name</b><br>
              <font size="1">The full name of this website.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="name" size="35" value="<? echo( htmlspecialchars($name, ENT_QUOTES) ); ?>" maxlength="32">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Website URL</b><br>
              <font size="1">The URL of this website where visitors will come after they were referred. You may use a redirection URL or special tags (?name=value) if you wish.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="url" size="35" value="<? echo($url); ?>" maxlength="75" onChange="autofillsettings(addsitefrm);">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>AutoLinks  URL<br>
              </b><font size="1">The  URL of  where AutoLinks is located on this site (this is NOT the control panel). It must be something like http://yoursite.com/autolinks/. Do not use a redirection URL or special tags!</font></td>
            <td width="35%">
              <input type="text" name="alurl" size="35" value="<? echo($alurl); ?>" maxlength="150">
            </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
  <br>
  <br>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">OPTIONAL INFORMATION</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Website Banner</b><br>
              <font size="1">An 468x60 image   that referrers will be able to use to link this site. It must be in JPEG or GIF format.</font></p>
            </td>
            <td width="35%">
              <input type="file" name="banner" enctype="multipart/form-data" size="23">
            </td>
          </tr>
		  
<?
  if( !multicats() ):

    $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!=''" );
	$cat = mysql_fetch_array( $res_cat );
  
    // only one category, select it
    echo( "<input type='hidden' name='category[]' value='{$cat[id]}'>" );
	
  else:
?>
		  
          <tr bgcolor="#F5F5F5">
            <td width="65%" valign="top"><b>Accepted Categories</b><br>
              <font size="1">If you unselect some categories, the referers that are part of those categories will not be able to link this site (the site will not be displayed in the referrers area and their hits won't be counted).</font></td>
            <td width="35%">

<?
    $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!='' ORDER BY name" );

    for( $i=0; $i<mysql_num_rows($res_cat); $i++ )
    {
      $cat = mysql_fetch_array( $res_cat );
      echo( "<input type='checkbox' name='category[]' value='{$cat['id']}'" );
	  if( !isset($submit) || in_array($cat['id'],$category) ) echo(" checked" );
	  echo( ">{$cat['name']}<br>" );
    }
?>

            </td>
          </tr>
		  
<? endif; ?>

          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Update Interval<br>
              </b><font size="1">Set the maximum of minutes to wait between updating the tags. The lower this number is, the more real time the site will be, but if it's too high, it will use a lot of processing power.</font></td>
            <td width="35%">
              <input type="text" name="updinterval" size="35" value="<? echo($updinterval); ?>" maxlength="10">
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value="  Add New Website  " name="submit">
      </td>
  </tr>
</table>
</form>
</body>
</html>
