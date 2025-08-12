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
  
  // count number of sites which accept category
  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$ref['category']}', categories)>0 ORDER BY name" );
  $numsites = mysql_num_rows( $res_site );
  
  
  ////////////////////////////////////////////////
  // THE USER WANTS TO UPLOAD IMAGES
  ////////////////////////////////////////////////
  
  if( $action=="editimages" )
  {
    // process the images (if any)
    while( list($k,$v) = each($image) )
	{
	  if( $image[$k]!="none" && $image[$k]!="" )
	  {
	    // update image on database + file
	    $result = uploadimage( $image[$k], $image_type[$k], "referrer", $ref[login], $format[$k] );
	  
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
  
  
  ////////////////////////////////////////////////
  // THE USER WANTS TO EDIT THE LINKS
  ////////////////////////////////////////////////
  
  elseif( $action == "editlinks" )
  {
	// process the url (if any)
    while( list($k, $v) = each($urlarray) )
    {
	  // check if a redirection was already defined
      $res_redir = mysql_query( "SELECT * FROM al_redir WHERE ref='{$ref[login]}' AND site='{$sitearray[$k]}' LIMIT 1" );

      if( mysql_num_rows($res_redir)>0 )
      {
        if( $v!=$ref[url] && $v!="" ) // non-default and non-null
        {
	      mysql_query( "UPDATE al_redir SET url='$v' WHERE ref='{$ref[login]}' AND site='{$sitearray[$k]}' LIMIT 1" );
        }
        else // default or null
        {
	      mysql_query( "DELETE FROM al_redir WHERE ref='{$ref[login]}' AND site='{$sitearray[$k]}' LIMIT 1" );
        }
      }
      else 
      {
        if( $v!=$ref[url] && $v!="" ) // non-default and non-null
        {
		  mysql_query( "INSERT INTO al_redir SET ref='{$ref[login]}', site='{$sitearray[$k]}', url='$v'" );
        }
      }
    }
	
	$notice = "Multi-url linking options updated.";
  }

  
  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////

  if( $CONF[link_banners] || $CONF[link_buttons] || $CONF[link_thumbs] )
  {
    $info .= "On this page you can upload new images for your site. If you send enough hits, your image might be displayed and you will receive more hits. ";
  }
  
  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
  
  showmenu( "advanced" );
  
  shownotice( $notice );
  
  showinfo( $info );

?>  
  
<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
<input type="hidden" name="action" value="editimages">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<tr><td>

<?

  // if image linking is enabled
  if( $CONF[link_banners] || $CONF[link_buttons] || $CONF[link_thumbs] ):
  
?>

  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2' class="formfront">Upload New Images</td>
          </tr>

		  <?
		    if( $CONF[link_banners] ):
		  
		  	  // check if there's a current image
		  	  $res_img = mysql_query( "SELECT extension FROM al_img WHERE type='referrer' AND login='$sesslogin' AND format='banner' LIMIT 1" );

			  if( mysql_num_rows($res_img)>0 )
			  {
			  	$img = mysql_fetch_array( $res_img );
			    $current = "(<a href='images/referrer/banner/$sesslogin.{$img[extension]}' target='_blank'>Current</a>)";
		  	  }
			  else
			  {
			    $current = "";
			  }
		  ?>
		  
          <tr class="formback">
            <td width="65%">
              <p><b>Banner</b><br>
              <font size="1">A 468x60  image for your site, GIF or JPEG only <?=$current?></font></p>
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
		  	  $res_img = mysql_query( "SELECT extension FROM al_img WHERE type='referrer' AND login='$sesslogin' AND format='button' LIMIT 1" );

			  if( mysql_num_rows($res_img)>0 )
			  {
			  	$img = mysql_fetch_array( $res_img );
			    $current = "(<a href='images/referrer/button/$sesslogin.{$img[extension]}' target='_blank'>Current</a>)";
		  	  }
			  else
			  {
			    $current = "";
			  }
		  ?>
		  
          <tr class="formback">
            <td width="65%">
              <p><b>Button</b><br>
              <font size="1">A 88x31  image for your site, GIF or JPEG only <?=$current?></font></p>
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
		  	  $res_img = mysql_query( "SELECT extension FROM al_img WHERE type='referrer' AND login='$sesslogin' AND format='thumb' LIMIT 1" );

			  if( mysql_num_rows($res_img)>0 )
			  {
			  	$img = mysql_fetch_array( $res_img );
			    $current = "(<a href='images/referrer/thumb/$sesslogin.{$img[extension]}' target='_blank'>Current</a>)";
		  	  }
			  else
			  {
			    $current = "";
			  }
		  ?>
		  
          <tr class="formback">
            <td width="65%">
              <p><b>Thumbnail</b><br>
              <font size="1">A 66x100 image for your site, GIF or JPEG only <?=$current?></font></p>
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
  <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td align="center">
        <input type="submit" value="  Upload New Images  " name="submit">
      </td>
    </tr>
  </table>
</td></tr>
</form>
</table>

<? 

    // if both images and links enabled, add split
    if( $numsites>1 ) echo( "<br>" );
  
  endif;


  // if there's 1+ site under this category
  if( $numsites>1 ):
?>

If you run a toplist or a site powered with a link exchange script such as AutoLinks, you can specify the URL where each of our sites will sent hits too.
<br>
<br>
  
<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="action" value="editlinks">
<input type="hidden" name="PHPSESSID" value="<?=$PHPSESSID?>">
<tr><td>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
  <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2' class="formfront">Multi-URL Linking</td>
          </tr>
		  
<?
  for( $i=0; $i<mysql_num_rows($res_site); $i++ ):

  	$site = mysql_fetch_array( $res_site );

  	$res_redir = mysql_query( "SELECT * FROM al_redir WHERE ref='{$ref[login]}' AND site='{$site[login]}' LIMIT 1" );

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
					
          <tr class="formback">
            <td width="50%">
              <p><b>
                <?=$site[name]?>
                Out</b><br>
              <font size="1">
                <?=$site[name]?>
               will send hits  using this URL.</font></p>
            </td>
            <td width="50%">
			  <input type="hidden" name="sitearray[]" value="<?=$site[login]?>">
              <input type="text" name="urlarray[]" size="55" value="<?=$redirurl?>" maxlength="150">
            </td>
          </tr>
		  
<? endfor; ?>

        </table>
      </td>
    </tr>
  </table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td align="center">
        <input type="submit" value=" Update Multi-URL Linking " name="submit">
      </td>
    </tr>
  </table>
</td></tr>
</form>
</table>

<? endif; ?>

<? showfooter(); ?>