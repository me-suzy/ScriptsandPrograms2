<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  $section = "filter";
  include( "initialize.php" );
  
  checklogged();

  if( $_POST['action']=="save" )
  {
    if( $demomode ) confirm( "No change can be saved on the demo mode" );
  
    if( $_POST['limitsize'] && ( $_POST['sizemin']=="" || $_POST['sizemax']=="" ) ) confirm( "Please enter the file size min/max!" );
    if( $_POST['limitdim'] && ( $_POST['widthmin']=="" || $_POST['widthmax']=="" ) ) confirm( "Please enter the image width min/max!" );
    if( $_POST['limitdim'] && ( $_POST['heightmin']=="" || $_POST['heightmax']=="" ) ) confirm( "Please enter the image height min/max!" );
    if( $_POST['extmode']=="only" && $_POST['extensions']=="" ) confirm( "Please enter at least one extension you accept!" );
  
    $CONF->setval( strtolower($_POST['extensions']), "extensions" );
	$CONF->setval( $_POST['extmode'], "extmode" );
	$CONF->setval( $_POST['limitsize'], "limitsize" );
	$CONF->setval( $_POST['sizemin'], "sizemin" );
	$CONF->setval( $_POST['sizemax'], "sizemax" );
	$CONF->setval( $_POST['limitdim'], "limitdim" );
	$CONF->setval( $_POST['widthmin'], "widthmin" );
	$CONF->setval( $_POST['widthmax'], "widthmax" );
	$CONF->setval( $_POST['heightmin'], "heightmin" );
	$CONF->setval( $_POST['heightmax'], "heightmax" );
	
	$CONF->savedata();
	
	confirm( "Changes successfully saved", "filter.php" );
  }
  
  showheader( $section );
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form method="post" action="filter.php">
<input type="hidden" name="action" value="save">
<? showsession(); ?>
<tr><td>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Extensions</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Extensions Mode</b><br>
      Select how you want the extensions above to be treated. Attention, allowing all extensions is a potential security hole!
	</td>
    <td>
	  <input type="radio" name="extmode" value="all" <? if($CONF->getval("extmode")=="all") echo("checked"); ?>> All file extensions (not recommended)<br>
      <input type="radio" name="extmode" value="only" <? if($CONF->getval("extmode")=="only") echo("checked"); ?>> Only the file extensions below<br>
	  <input type="radio" name="extmode" value="except" <? if($CONF->getval("extmode")=="except") echo("checked"); ?>> All extensions except the ones below
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Extensions</b><br>
      Enter the extensions separated by a space.
	</td>
    <td>
      <input type="text" name="extensions" size="50" value="<?=$CONF->getval("extensions")?>">
    </td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">File Size</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Limit File Size?</b><br>
      Do you want to set a limit to the file size? Attention, file limitation is also dependant on your PHP settings.
	</td>
    <td>
      <input type="radio" name="limitsize" value="1" <? if($CONF->getval("limitsize")) echo("checked"); ?>> Yes
	  <input type="radio" name="limitsize" value="0" <? if(!$CONF->getval("limitsize")) echo("checked"); ?>> No
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Size Min/Max</b><br>
      If you selected 'Yes' above, enter the minimum and maximum file size (in Kb)
	</td>
    <td>
      <input type="text" name="sizemin" size="22" value="<?=$CONF->getval("sizemin")?>">
	  &nbsp;
	  <input type="text" name="sizemax" size="22" value="<?=$CONF->getval("sizemax")?>">
    </td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Image Dimensions</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Limit Image Dimensions?</b><br>
      If the file is an image, do you want to limit its size? This only works for JPEG, GIF and PNG.
	</td>
    <td>
      <input type="radio" name="limitdim" value="1" <? if($CONF->getval("limitdim")) echo("checked"); ?>> Yes
	  <input type="radio" name="limitdim" value="0" <? if(!$CONF->getval("limitdim")) echo("checked"); ?>> No
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Width Min/Max</b><br>
      The maximum and minimum of the image width. Enter two times the same value if you only want ONE width.
	</td>
    <td>
      <input type="text" name="widthmin" size="22" value="<?=$CONF->getval("widthmin")?>">
	  &nbsp;
	  <input type="text" name="widthmax" size="22" value="<?=$CONF->getval("widthmax")?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Height Min/Max</b><br>
      The maximum and minimum of the image height. Enter two times the same value if you only want ONE height.
	</td>
    <td>
      <input type="text" name="heightmin" size="22" value="<?=$CONF->getval("heightmin")?>">
	  &nbsp;
	  <input type="text" name="heightmax" size="22" value="<?=$CONF->getval("heightmax")?>">
    </td>
  </tr>
</table>

<? showspace(); ?>
		
<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr align="center" class="header">
    <td colspan="2">
      <input type="submit" name="edit" value="Save Changes">
    </td>
  </tr>
</table>

</td></tr>
</form>
</table>
			
<? showfooter($section); ?>