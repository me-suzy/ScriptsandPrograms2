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
  
  
  ////////////////////////////////////////////////
  // THE USER CLICKED THE DELETE BUTTON
  ////////////////////////////////////////////////
  
  if( $submitted=="editsite" && isset($delete) )
  {
    // take all sites who had this site has a 'main site'
	$res_ref = mysql_query( "SELECT * FROM al_ref WHERE fromsite='$login'" );
	
	while( $ref = mysql_fetch_array($res_ref) )
	{
	  // find another site which accepts the same category
	  $res_site = mysql_query( "SELECT * FROM al_site WHERE login!='$login' AND FIND_IN_SET('{$ref['category']}', categories )>0 LIMIT 1" );

	  // no site found, take a random one
	  if( mysql_num_rows($res_site)==0 )
	  {
	    $res_site = mysql_query( "SELECT * FROM al_site WHERE login!='$login' LIMIT 1" );
		
		if( mysql_num_rows($res_site)>0 )
		{
		  $site = mysql_fetch_array( $res_site );
		  mysql_query( "UPDATE al_ref SET fromsite='{$site['login']}' WHERE login='{$ref['login']}' LIMIT 1" );
		}
	  }
	  else
	  {
		$site = mysql_fetch_array( $res_site );
		mysql_query( "UPDATE al_ref SET fromsite='{$site['login']}' WHERE login='{$ref['login']}' LIMIT 1" );
	  }
	}
	
	// delete data, statistics, redirection
	mysql_query( "DELETE FROM al_site WHERE login='$login' LIMIT 1" );
	mysql_query( "DELETE FROM al_hit WHERE site='$login'" );
	mysql_query( "DELETE FROM al_stats WHERE site='$login'" );
	mysql_query( "DELETE FROM al_img WHERE login='$login' AND type='website'" );
	mysql_query( "DELETE FROM al_redir WHERE site='$login'" );
	
	header( "Location: site_list.php?special=delete" );
  }
  
  
  ////////////////////////////////////////////////
  // THE USER CLICKED THE EDIT BUTTON
  ////////////////////////////////////////////////
  
  elseif( $submitted=="editsite" )
  {
	if( !$name || !$url || !$alurl || !$updinterval )
 	{
      $notice = "Error! Some fields are incorrect or missing!";
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

  	  $name = addslashes( $name );

  	  // make sure there's always an ending /
	  $alurl = checkurl( $alurl );
	  
	  $categories = implode( ",", $category );

  	  // all correct, insert site into database
  	  mysql_query( "UPDATE al_site SET

				name='$name',
				url='$url',
				alurl='$alurl',
				status=$status,
				categories='$categories',
				updinterval='$updinterval' WHERE login='$login'" );
				
	  // notify the user
	  $notice = "Website successfully edited.";
	}
  }
  
  
  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////
  
  $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$login' LIMIT 1" );
  if( mysql_num_rows($res_site)==0 ) fatalerr( "Error! No site with this login could be found in the database" );
  $site = mysql_fetch_array( $res_site );
     
  // check if there's a current image
  $res_img = mysql_query( "SELECT extension FROM al_img WHERE type='website' AND login='$login' AND format='banner' LIMIT 1" );

  if( mysql_num_rows($res_img)>0 )
  {
    $img = mysql_fetch_array( $res_img );
    $current = "(<a href='images/website/banner/$login.{$img[extension]}' target='_blank'>Current</a>)";
  }
  else
  {
    $current = "";
  }

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" action="<? echo($PHP_SELF); ?>" enctype="multipart/form-data">
<input type="hidden" name="login" value="<? echo($login); ?>">
<input type="hidden" name="submitted" value="editsite">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">REQUIRED INFORMATION</font></td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Website Name</b><br>
              <font size="1">The full name of this website.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="name" size="35" value="<? echo($site[name]); ?>" maxlength="32">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Website URL</b><br>
              <font size="1">The URL of this website where visitors will come after they were referred. You may use a redirection URL or special tags (?name=value) if you wish.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="url" size="35" value="<? echo($site[url]); ?>" maxlength="75">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>AutoLinks  URL<br>
              </b><font size="1">The  URL of  where AutoLinks is located on this site (this is NOT the control panel). It must be something like http://website.com/autolinks/. Do not use a redirection URL or special tags!</font></td>
            <td width="35%">
              <input type="text" name="alurl" size="35" value="<? echo($site[alurl]); ?>" maxlength="150">
            </td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Site Active?</b><br>
              <font size="1">If you unactivate the site, it will be hiden to all referrers but  the data and statistics will be kept in the database.</font></td>
            <td width="35%">
              <input type="radio" name="status" value="1" <? if($site['status']) echo(" checked"); ?>>
              Yes 
              <input type="radio" name="status" value="0" <? if(!$site['status']) echo(" checked"); ?>>
              No 
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
              <font size="1">If you want to change the current banner, enter a new one here. <?=$current?></font></p>
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
              <font size="1">If you unselect some categories, the referers that are part of those categories will not be able to link this site (the site will hidden in the referrers area and their hits won't be counted).</font></td>
            <td width="35%">
<?
	$category = explode( ",", $site[categories] );
	
    $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!='' ORDER BY name" );

    for( $i=0; $i<mysql_num_rows($res_cat); $i++ )
    {
      $cat = mysql_fetch_array( $res_cat );
      echo( "<input type='checkbox' name='category[]' value='{$cat['id']}'" );
	  if( in_array( $cat['id'], $category ) ) echo(" checked" );
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
              <input type="text" name="updinterval" size="35" value="<? echo($site[updinterval]); ?>" maxlength="10">
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
        <input type="submit" name="edit" value="  Edit Website  ">
        <input type="submit" name="delete" value=" Delete Website " onClick="return confirm('This will erase all data and statistics.\nAre you sure you want to continue?');">
      </td>
  </tr>
</table>
</form>
</body>
</html>
