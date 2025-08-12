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
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////
  
  // get the main site autolinks url
  $alurl = $site[alurl];
  
  $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$ref['category']}', categories)>0 ORDER BY name" );

  while( $site = mysql_fetch_array($res_site) )
  {
	$linkurl[$site[name]] = $site[alurl] . "?i=" . $sesslogin;
	
	// search if the site has an image
	$res_img = mysql_query( "SELECT extension FROM al_img WHERE type='website' AND login='{$site[login]}' AND format='banner' LIMIT 1" );
	
	if( mysql_num_rows($res_img)>0 )
	{
	  updateimage( "website", $site[login], "banner" );
	
	  $img = mysql_fetch_array( $res_img );
	
	  $imagesrc[$site[name]] = $alurl . "images/website/banner/" . $site[login] . "." . $img[extension];
	  
	  if( $CONF[hotlink] )
	  {
	    $imageurl[$site[name]] = $imagesrc[$site[name]];
	  }
	  else
	  {
	    $imageurl[$site[name]] = $site[login] . "." . $img[extension];
	  }
	}
  }  
  
  $info = "On this page you can find the code to link our site(s). Please only use those URL or your hits will not be counted and your link will not be displayed! ";
  
  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
  
  showmenu( "getcode" );
   
  shownotice( $notice );
  
  showinfo( $info );

?>

  <table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2' class="formfront">Linking URL</td>
          </tr>

<? while( list($k, $v) = each($linkurl) ): ?>

          <tr class="formback">
            <td width="65%">
              <b><?=$k?></b>
            </td>
            <td width="35%">
              <input type="text" name="linkurl[]" size="55" value="<?=$v?>">
            </td>
          </tr>

<? endwhile; ?>
		  
        </table>
      </td>
    </tr>
  </table>
<br>

<? 
  if( isset($imagesrc) ):

  echo( "You may also link us using the banners below. With a banner, you can send more hits to use and rank higher! " );
  if( !$CONF[hotlink] ) echo( "<font class='highlight'>Do not link the banners directly! Save them on your hard drive and put them on your site.</font>" );
 
?>

<br>
<br>
<table cellpadding='0' cellspacing='0' border='0' width='100%' class="formfront">
  <tr>
    <td>
      <table cellpadding='4' cellspacing='1' border='0' width='100%'>
        <tr>
          <td class="formfront">Banners Code</td>
        </tr>
		
<? while( list($k, $v) = each($imagesrc) ): ?>
		
        <tr class="formback">
          <td width="65%" align="center">
		    <table cellpadding='6' cellspacing='0' border='0' width='100%'>
              <tr><td align="center">
			    <img src="<?=$v?>" width="468" height="60">
                <textarea name="bannercode[]" cols="75" rows="5"><a href="<?=$linkurl[$k]?>" target="_blank"><img src="<?=$imageurl[$k]?>" width="468" height="60" alt="Click here to visit <?=$k?>!"></a></textarea>
              </td></tr>
			</table>
		  </td>
        </tr>
		
<? endwhile; ?>
		
      </table>
    </td>
  </tr>
</table>

<? endif; ?>

<? showfooter(); ?>