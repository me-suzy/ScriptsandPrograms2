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
  $section = "customize";
  include( "initialize.php" );
  
  checklogged();
  
  if( $_POST['action']=="save" )
  {
    if( $demomode ) confirm( "No change can be saved on the demo mode" );
  
    if( !$_POST['fieldsize'] ) confirm( "Please enter a size for the fields!" );
    if( intval($_POST['fieldsize'])<25 ) confirm( "The fields size must at least 25 characters!" );
  
    $CONF->setval( $_POST['header'], "header" );
	$CONF->setval( $_POST['footer'], "footer" );
  
    $CONF->setval( $_POST['fieldsize'], "fieldsize" );
	$CONF->setval( $_POST['displayinfo'], "displayinfo" );
	$CONF->setval( $_POST['showrequired'], "showrequired" );
	$CONF->setval( $_POST['fonttype'], "fonttype" );
	$CONF->setval( $_POST['fontsizel'], "fontsizel" );
	$CONF->setval( $_POST['fontsizes'], "fontsizes" );
  
	$CONF->savedata();
	
	confirm( "Changes successfully saved", "customize.php" );
  }
  
  showheader( $section );
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form method="post" action="customize.php">
<input type="hidden" name="action" value="save">
<? showsession(); ?>
<tr><td>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td>Put the Form on Your Site</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td>
	  Although a default upload page is provided <a href="index.php">here</a> and you can
	  customize it below, you can also put the upload form on your website design by copy/pasting the code
	  below on a page on your site. This page must have a .php extension.
	  It will create a table in real time with all the upload fields and if you made 
	  changes to the settings here, the upload form will be automatically updated.
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td>
	  <input type="text" name="phpcode" size="104" value="&lt;?php $path = &quot;<?=$_SERVER['DOCUMENT_ROOT']?><?=dirname($_SERVER['PHP_SELF'])?>/&quot;; include( $path . &quot;form.php&quot; ); ?&gt;">
	</td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Customize Header & Footer</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Header</b><br>
      The HTML code for the top of the <a href="index.php?<?=$SID?>">upload form</a> and upload results page. If you put the form on your site you don't need to change this.
	</td>
    <td>
      <textarea name="header" cols="49" rows="10"><?=$CONF->getval("header")?></textarea>
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Footer</b><br>
      The HTML code for the bottom of the <a href="index.php?<?=$SID?>">upload form</a> and upload results page. If you put the form on your site you don't need to change this.
	</td>
    <td>
      <textarea name="footer" cols="49" rows="10"><?=$CONF->getval("footer")?></textarea>
    </td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Upload Form Appearance</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Fields Size</b><br>
      The number of characters of the text fields. The larger this number is, the wider the form will be. Minimum 25.
	</td>
    <td>
	  <input type="text" name="fieldsize" size="50" value="<?=$CONF->getval("fieldsize")?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Information Display</b><br>
      What do you want to show to the user?
	</td>
    <td>
      <input type="radio" name="displayinfo" value="none" <? if($CONF->getval("displayinfo")=="none") echo("checked"); ?>> Nothing (only the form fields)<br>
	  <input type="radio" name="displayinfo" value="name" <? if($CONF->getval("displayinfo")=="name") echo("checked"); ?>> The field name<br>
	  <input type="radio" name="displayinfo" value="description" <? if($CONF->getval("displayinfo")=="description") echo("checked"); ?>> The field name and description (if any)
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Show Required?</b><br>
      Do you want the required fields to be marked with a star (*) next to the field name? 
	</td>
    <td>
      <input type="radio" name="showrequired" value="1" <? if($CONF->getval("showrequired")) echo("checked"); ?>> Yes
	  <input type="radio" name="showrequired" value="0" <? if(!$CONF->getval("showrequired")) echo("checked"); ?>> No
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Font Type</b><br>
      The font to be used on the form. If you leave this blank, the default font will be used.
	</td>
    <td>
	  <input type="text" name="fonttype" size="50" value="<?=$CONF->getval("fonttype")?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Font Size</b><br>
      The font size (CSS-type) of (1) the fields and fields name and (2) fields description. Leave blank to use default size.
	</td>
    <td>
      <input type="text" name="fontsizel" size="22" value="<?=$CONF->getval("fontsizel")?>">
	  &nbsp;
	  <input type="text" name="fontsizes" size="22" value="<?=$CONF->getval("fontsizes")?>">
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