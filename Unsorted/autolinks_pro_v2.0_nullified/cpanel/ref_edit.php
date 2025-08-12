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


  ///////////////////////////////////////////////////
  // DELETE THE REFERRER
  ///////////////////////////////////////////////////
  
  if( $submitted=="editref" && isset($delete) )
  {
    // retrieve status before deletion
    $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$login' LIMIT 1" );
	$ref = mysql_fetch_array( $res_ref );
  
    // notify referrer if configured + wasn't already banned
	if( $CONF[notify_ban] )
	{
	  switch( $ref['status'] ) // ban or mod-refuse?
	  {
        case 1: email_templ( "ban", $login ); break;
		case 2: email_templ( "mod_refuse", $login ); break;
      }
    }
	
	// delete referrer + all statistics, images and redirs
	mysql_query( "DELETE FROM al_ref WHERE login='$login' LIMIT 1" );
	mysql_query( "DELETE FROM al_hitin WHERE ref='$login'" );
	mysql_query( "DELETE FROM al_hitout WHERE ref='$login'" );
	mysql_query( "DELETE FROM al_hitclk WHERE ref='$login'" );
	mysql_query( "DELETE FROM al_stats WHERE ref='$login'" );
	mysql_query( "DELETE FROM al_img WHERE type='referrer' AND login='$login'" );
	mysql_query( "DELETE FROM al_redir WHERE ref='$login'" );
	
	header( "Location: ref_list.php?special=delete" );
  }
  
  
  ///////////////////////////////////////////////////
  // EDIT THE REFERRERS INFO
  ///////////////////////////////////////////////////
  
  elseif( $submitted=="editref" )
  {
  	if( !$name || !$url || !$login || !$email || ( $CONF[link_thumbs] && !$thumb[0] ) || ( $CONF[link_thumbs] && !$thumb[1] ) )
 	{
      $notice = "Error! Some fields are incorrect or missing!";
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
      // add slashes for the database
	  $name = addslashes( $name );
	  $description = addslashes( $description );
	  
	  if( $CONF[link_thumbs] )
	  {
	    $thumbstr = implode( "/", $thumb );
	    $thumbstr = addslashes( $thumbstr );
	  }
	  
	  // get the old status
	  $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$login' LIMIT 1" );
	  $ref = mysql_fetch_array( $res_ref );
	  $oldstatus = $ref['status'];
	  
	  // must update for the email templates
  	  mysql_query( "UPDATE al_ref SET

					name='$name',
					url='$url',
					description='$description',
					thumb='$thumbstr',
					email='$email',
					status='$status',
					category=$category WHERE login='$login'" ); 

	  // get the updated $ref array
	  $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$login' LIMIT 1" );
	  $ref = mysql_fetch_array( $res_ref );

	  if( $status==0 && $oldstatus==1 ) // user was banned
	  {
	    // email only if set in configurations
	    if( $CONF[notify_ban] ) email_templ( "ban", $ref['login'] );
		$notice = "Referrer has been edited and blacklisted.";
	  }
	  elseif( $status==1 && ( $oldstatus==2 || $oldstatus==3 ) ) // user was mod-approved
	  {
	    email_templ( "mod_accept", $ref['login'] );
		$notice = "Referrer has been edited and approved.";
	  }
	  elseif( $status==0 && $oldstatus==2 ) // user was mod-refused
	  {
	    email_templ( "mod_refuse", $ref['login'] );
		$notice = "Referrer has been edited and refused.";
	  }
	  else // user was just edited
	  {
	    $notice = "Referrer successfully edited.";
	  }
	}
  }
  
  
  ///////////////////////////////////////////////////
  // CHANGE THE ADVANCED LINKING OPTIONS
  ///////////////////////////////////////////////////

  elseif( $submitted=="editlinks" )
  {
    while( list($k, $v) = each($urlarray) )
    {
	  // get the site login for this url
	  $sitelogin = $sitearray[$k];

	  // check if a redirection was already defined
      $res_redir = mysql_query( "SELECT * FROM al_redir WHERE ref='$login' AND site='$sitelogin' LIMIT 1" );

      if( mysql_num_rows($res_redir)==1 )
      {
        if( $v!=$defurl && $v!="" ) // non-default and non-null
        {
	      mysql_query( "UPDATE al_redir SET url='$v' WHERE ref='$login' AND site='$sitelogin' LIMIT 1" );
        }
        else // default or null
        {
	      mysql_query( "DELETE FROM al_redir WHERE ref='$login' AND site='$sitelogin' LIMIT 1" );
        }
      }
      else 
      {
        if( $v!=$defurl && $v!="" ) // non-default and non-null
        {
		  mysql_query( "INSERT INTO al_redir SET ref='$login', site='$sitelogin', url='$v'" );
        }
      }
    }
	
	$notice = "Advanced linking options successfully updated.";
  }
  
  ////////////////////////////////////////////////
  // THE USER WANTS TO UPLOAD IMAGES
  ////////////////////////////////////////////////
  
  elseif( $submitted=="editimages" )
  {
    // process the images (if any)
    while( list($k,$v) = each($image) )
	{
	  if( $image[$k]!="none" && $image[$k]!="" )
	  {
	    // update image on database + file
	    $result = uploadimage( $image[$k], $image_type[$k], "referrer", $login, $format[$k] );
	  
	    if( $result != "success" )
	    {
	      switch( $result )
	      {
	        case "errorsize": $notice .= "The {$format[$k]} doesn't have the right dimensions. "; break;
		    case "errortype": $notice .= "The {$format[$k]} must be a GIF or a JPEG image. "; break;
		    case "erroropen": $notice .= "Could not open {$image_name[$k]} for reading. "; break;
		    case "errorwrite": $notice .= "Could not write {$image_name[$k]} (no permission). "; break;
	      }
	    }
	  }
	}
	
	if( !isset($notice) )
	{
	  $notice = "Images successfully uploaded and updated.";
	}
  }
  
  
  ///////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ///////////////////////////////////////////////////
  
  $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$login'" );
  if( !mysql_num_rows($res_ref) ) fatalerr( "Error! No referrer under this login could be found" );
  $ref = mysql_fetch_array( $res_ref );

  // put a message if referrer has been just invited
  if( $special=="new" ) $notice = "Referrer successfully invited. You may setup his images and advanced linking option";

  $thumb = explode( "/", $ref[thumb] );
	
  // load all images for this referrer
  updateimage( "referrer", $login, "banner" );
  updateimage( "referrer", $login, "button" );
  updateimage( "referrer", $login, "thumb" );

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" name="edit" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="editref">
<input type="hidden" name="login" value="<? echo($ref[login]); ?>">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">EDIT REFERRER</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>  Webmaster Email</b><br>
              <font size="1">The email address of the person who maintains this site.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="email" size="35" value="<? echo($ref['email']); ?>">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer Name</b><br>
              <font size="1">The name of the referrer's website.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="name" size="35" value="<? echo($ref['name']); ?>" maxlength="<? echo($CONF[name_max]); ?>">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer URL</b><br>
              <font size="1">The URL of the referrer's website. Starts with http://</font></p>
            </td>
            <td width="35%">
              <input type="text" name="url" size="35" value="<? echo($ref['url']); ?>">
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer Description</b><br>
              <font size="1">The description can be used in various ways in  tags (for description next to text links, below banners and on mouseover).</font></p>
            </td>
            <td width="35%">
              <input type="text" name="description" size="35" value="<? echo($ref[description]); ?>" maxlength="<? echo($CONF[desc_max]); ?>">
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

    // only one category, select it
    echo( "<input type='hidden' name='category' value='{$ref[category]}'>" );
	
  else:
  
?>

          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Referrer Category</b><br>
              <font size="1">The category you wish to assign this referrer.</font></td>
            <td width="35%">
              <select name="category">
<? 		
  
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!='' ORDER BY name" );

  for( $i=0; $i<mysql_num_rows($res_cat); $i++ )
  {
    $cat = mysql_fetch_array( $res_cat );
    echo( "<option value='{$cat['id']}'" );
	if( $cat['id']==$ref['category'] ) echo( " selected" );
	echo( ">{$cat['name']}</option>" );
  }
  
?>
              </select>
            </td>
          </tr>
		  
<? endif; ?>
		  
          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Referrer Status</b><br>
              <font size="1">If you unactivate the referrer, the hits it sends will not be counted anymore and the link will  dissappear within 24 hours. The data and statistics will be kept so you can activate it again later.</font></td>
            <td width="35%">
              <select name="status">
                <option value="1"<? if($ref['status']==1) echo(" selected"); ?>>Active</option>
                <option value="0"<? if($ref['status']==0) echo(" selected"); ?>>Unactive</option>
                <? if($ref['status']==2): ?><option value="2" selected>Pending</option><? endif; ?>
				<? if($ref['status']==3): ?><option value="3" selected>Unverified</option><? endif; ?>
              </select>
              </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
<table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value="  Edit Referrer  " name="submit">
        <input type="submit" value=" Delete Referrer " name="delete" onClick="return confirm('This will erase all data and statistics.\nAre you sure you want to continue?');">
      </td>
  </tr>
</table>
</form>

<?

  // if image linking is enabled
  if( $CONF[link_banners] || $CONF[link_buttons] || $CONF[link_thumbs] ):
  
?>

<br>
<form method="post" name="editlinks" action="<? echo("$PHP_SELF"); ?>" enctype="multipart/form-data">
<input type="hidden" name="submitted" value="editimages">
<input type="hidden" name="login" value="<? echo($ref[login]); ?>">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1">UPLOAD NEW IMAGES</font></td>
          </tr>
          
		  <?
		    if( $CONF[link_banners] ):
		  
		  	  // check if there's a current image
		  	  $res_img = mysql_query( "SELECT extension FROM al_img WHERE type='referrer' AND login='$login' AND format='banner' LIMIT 1" );

			  if( mysql_num_rows($res_img)>0 )
			  {
			  	$img = mysql_fetch_array( $res_img );
			    $current = "(<a href='images/referrer/banner/$login.{$img[extension]}' target='_blank'>Current</a>)";
		  	  }
			  else
			  {
			    $current = "";
			  }
		  ?>
		  
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Banner</b><br>
              <font size="1">A 468x60 image for this site, GIF or JPEG only 
                <?=$current?>
                </font></p>
            </td>
            <td width="35%">
			  <input type="hidden" name="format[]" value="banner">
              <input type="file" name="image[]" size="33">
            </td>
          </tr>
		  
		  <?
		    endif;
		  
		    if( $CONF[link_buttons] ):
		  
		  	  // check if there's a current image
		  	  $res_img = mysql_query( "SELECT extension FROM al_img WHERE type='referrer' AND login='$login' AND format='button' LIMIT 1" );

			  if( mysql_num_rows($res_img)>0 )
			  {
			  	$img = mysql_fetch_array( $res_img );
			    $current = "(<a href='images/referrer/button/$login.{$img[extension]}' target='_blank'>Current</a>)";
		  	  }
			  else
			  {
			    $current = "";
			  }
		  ?>
		  
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Button</b><br>
              <font size="1">A 88x31  image for this site, GIF or JPEG only 
                <?=$current?>
                </font></p>
            </td>
            <td width="35%">
			  <input type="hidden" name="format[]" value="button">
              <input type="file" name="image[]" size="33">
            </td>
          </tr>
		  
		  <?
		    endif;
		  
		    if( $CONF[link_thumbs] ):
		  
		  	  // check if there's a current image
		  	  $res_img = mysql_query( "SELECT extension FROM al_img WHERE type='referrer' AND login='$login' AND format='thumb' LIMIT 1" );

			  if( mysql_num_rows($res_img)>0 )
			  {
			  	$img = mysql_fetch_array( $res_img );
			    $current = "(<a href='images/referrer/thumb/$login.{$img[extension]}' target='_blank'>Current</a>)";
		  	  }
			  else
			  {
			    $current = "";
			  }
		  ?>
		  
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Thumbnail</b><br>
              <font size="1">A 66x100 image for this site, GIF or JPEG only 
                <?=$current?>
                </font></p>
            </td>
            <td width="35%">
			  <input type="hidden" name="format[]" value="thumb">
              <input type="file" name="image[]" size="33">
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
        <input type="submit" value=" Upload New Images " name="editimages">
        </td>
    </tr>
  </table>
</form>

<? 

  endif;

  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$ref['category']}', categories)>0 ORDER BY name" );
  
  // if there's 1+ site under this category
  if( mysql_num_rows($res_site)>1 ):

?>

<br>
<form method="post" name="editlinks" action="<? echo("$PHP_SELF"); ?>">
<input type="hidden" name="submitted" value="editlinks">
<input type="hidden" name="login" value="<? echo($ref['login']); ?>">
<input type="hidden" name="defurl" value="<? echo($ref['url']); ?>">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1">ADVANCED LINKING (FOR TOPLISTS- &amp;  AUTOLINKS-BASED SITES)
              </font></td>
          </tr>
					<?
					  for( $i=0; $i<mysql_num_rows($res_site); $i++ ):

						$site = mysql_fetch_array( $res_site );

						$res_redir = mysql_query( "SELECT * FROM al_redir WHERE ref='{$ref['login']}' AND site='{$site['login']}' LIMIT 1" );

						if( mysql_num_rows($res_redir) > 0 )
						{
						  $redir = mysql_fetch_array( $res_redir );
						  $redirurl = $redir['url'];
						}
						else
						{
						  $redirurl = $ref['url'];
						}
					?>
                      
          <tr bgcolor="#F5F5F5"> 
            <td width="50%" bgcolor="#F5F5F5"><b>
              <? echo($site['name']); ?>
               Out
              </b><br>
              <font size="1">
              <? echo($site['name']); ?>
               will send hits to this referrer using this URL.</font></td>
            <td  width="50%" bgcolor="#F5F5F5"> 
			  <input type="hidden" name="sitearray[]" value="<? echo($site['login']); ?>">
              <input type="text" name="urlarray[]" size="55" value="<? echo($redirurl); ?>" maxlength="150">
            </td>
          </tr>

<? endfor; ?>
		  
        </table>
      </td>
    </tr>
  </table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td align="center">
        <input type="submit" value=" Change Linking Options " name="editlinks">
        </td>
    </tr>
  </table>
</form>

<? endif; ?>

</body>
</html>
