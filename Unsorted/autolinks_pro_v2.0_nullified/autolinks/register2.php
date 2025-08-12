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

  $ref = checklogin( false );
  
  // count number of sites which accept category
  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$ref['category']}', categories)>0 ORDER BY name" );
  $numsites = mysql_num_rows( $res_site );

  // no need for advanced linking, go to next page
  if( $numsites<2 && !$CONF[link_banners] && !$CONF[link_buttons] && !$CONF[link_thumbs] )
  {
	header( "Location: register3.php?PHPSESSID=$PHPSESSID" );
  }
  
  ////////////////////////////////////////////////
  // THE USER CLICKED THE REGISTER BUTTON
  ////////////////////////////////////////////////
  
  if( $action=="register2" )
  {
    if( isset($image) )
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
	}
	
	if( isset($urlarray) )
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
	}
	
	if( isset($notice) )
	{
	  // offer user to finish anyway
	  $notice .= "Try again or <a href='register3.php?PHPSESSID=$PHPSESSID' class='highlight'>finish your registration</a> now and you'll be able to change that later.";
	}
	else
	{
	  // no error, go to next page
	  header( "Location: register3.php?PHPSESSID=$PHPSESSID" ); 
	}
  }

  
  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////

  if( $CONF[link_banners] || $CONF[link_buttons] || $CONF[link_thumbs] )
  {
    $info = "The first step of your registration has been successfully completed. ";
    $info .= "Now we invite you to upload your images below. If you send enough hits, your image will be displayed  and you will receive more hits. ";
  }

  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
  
  shownotice( $notice );
  
  showinfo( $info );

?>  

<table cellpadding='0' cellspacing='0' border='0' width='100%'>
<form method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
<input type="hidden" name="action" value="register2">
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
            <td colspan='2' class="formfront">Upload Your Images</td>
          </tr>
		  
		  <? if( $CONF[link_banners] ): ?>
		  
          <tr class="formback">
            <td width="65%">
              <p><b>Banner</b><br>
              <font size="1">A 468x60  image for your site, GIF or JPEG only</font></p>
            </td>
            <td width="35%">
			  <input type="hidden" name="format[]" value="banner">
              <input type="file" name="image[]" size="33">
            </td>
          </tr>
		  
		  <? endif; if( $CONF[link_buttons] ): ?>
		  
          <tr class="formback">
            <td width="65%">
              <p><b>Button</b><br>
              <font size="1">A 88x31  image for your site, GIF or JPEG only</font></p>
            </td>
            <td width="35%">
			  <input type="hidden" name="format[]" value="button">
              <input type="file" name="image[]" size="33">
            </td>
          </tr>
		  
		  <? endif; if( $CONF[link_thumbs] ): ?>
		  
          <tr class="formback">
            <td width="65%">
              <p><b>Thumbnail</b><br>
              <font size="1">A 66x100 image for your site, GIF or JPEG only</font></p>
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
  
<? 
  endif;

  // if there's 1+ site under this category
  if( $numsites>1 ):
?>
  
  <br>
  <br>
  If you use a script like AutoLinks or a toplist to automate the link exchanges on your site, you can specify below to what URL each of our sites will send hits to.
  <br>
  
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
  
<? endif; ?>
  
  <br>
  <br>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td align="center">
        <input type="submit" value="  Finish Registration  " name="submit">
      </td>
    </tr>
  </table>
</td></tr>
</form>
</table>

<? showfooter(); ?>