<? 
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.0                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : Stive [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2002
/////////////////////////////////////////////////////////////

  include( $path . "initialize.php" );

  if( $CONF->getval("fonttype") || $CONF->getval("fontsizel") || $CONF->getval("fontsizes") )
  {
    if( $CONF->getval("fonttype") ) $csstype = "font-family: " . $CONF->getval("fonttype") . ";";
    if( $CONF->getval("fontsizel") ) $csssizel = "font-size: " . $CONF->getval("fontsizel") . ";";
    if( $CONF->getval("fontsizes") ) $csssizes = "font-size: " . $CONF->getval("fontsizes") . ";";
  
	$stylel = "style='$csstype $csssizel'";
	$styles = "style='$csstype $csssizes'";
  }
  
  if( $CONF->getval("limitsize") )
    $maxsize = $CONF->getval("sizemax")*1024;
  else
    $maxsize = 30 * 1024 * 1024;

  if( isset($_GET['id']) )
  {
	if( $UPLOAD->getval("uploaded",$_GET['id'])==$_GET['date'] )
	{
	  $uploadid = $_GET['id'];
	  $uploaddate = $_GET['date'];
	}
  }

  $sitepath = substr( $path, strlen($DOCUMENT_ROOT) );
?>

<table border="0" cellspacing="0" cellpadding="2" align="<?=$CONF->getval("tablealign")?>">
<SCRIPT SRC="<?=$sitepath?>checkfields.php" LANGUAGE="JavaScript"></SCRIPT>
<form name="uploadfrm" method="post" action="<?=$sitepath?>upload.php" enctype="multipart/form-data" onSubmit="return checkfields(uploadfrm);">
<input type="hidden" name="formurl" value="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$maxsize?>">

<? if( isset($uploadid) ): ?>

<input type="hidden" name="id" value="<?=$uploadid?>"> 

<?
  endif;
 
  $FIELD->sortdata( "order", "asc" );
 
  $fields = $FIELD->get();
  
  foreach( $fields AS $field )
  {
    echo( "<tr>" );
	
	$fname = "f" . $field['id'];
	  
    // find the default value
	if( isset($uploadid) )
	{
	  if( $field['id']==$CONF->getval("emailfield") )
	    $field['default'] = $UPLOAD->getval( "email", $uploadid );
	  else
	    $field['default'] = $UPLOAD->getval( $field['name'], $uploadid );
	}
	
	if( $field['type']=="textarea" )
	  $valign = "top";
	else
	  $valign = "middle";
  
  	if( $CONF->getval("displayinfo")!="none" )
	{
	  echo( "<td valign='$valign'><b><font $stylel>{$field['name']}</b>" );
	  if( $CONF->getval("showrequired") && $field['required'] ) echo("*");
	  if( $CONF->getval("displayinfo")=="description" ) echo( "<br><font $styles>" . wordwrap( $field['description'], 25, "<br>" ) . "</font>" );
	  echo( "</font></td><td width='5'></td>" );
	}
	
	echo( "<td>" );
	
	if( $field['type']=="password" || $field['type']=="text" )
	{
	  echo( "<input type='{$field['type']}' name='$fname' size='".$CONF->getval("fieldsize")."' maxlength='{$field['maxchars']}' value='{$field['default']}' $stylel>" );
	}
	elseif( $field['type']=="textarea" )
	{
	  echo( "<textarea name='$fname' cols='".($CONF->getval("fieldsize")-1)."' rows='7' $stylel>{$field['default']}</textarea>" );
	}
	elseif( $field['type']=="file" )
	{
	  echo( "<input type='file' name='$fname' size='".($CONF->getval("fieldsize")-12)."' $stylel>" );
	}
	elseif( $field['type']=="dropbox" )
	{
	  echo( "<select name='$fname' $stylel>" );
	  echo( "<option value=''> </option>" );
	
	  $options = $OPTION->queryrows( $field['id'], "field" );

	  foreach( $options AS $option )
	  {
	    echo( "<option value='{$option['value']}'" );
		if( $option['value']==$field['default'] ) echo( " selected" );
		echo( ">{$option['value']}</option>" );
	  }
	  
	  echo( "</select>" );
	}
	
	echo( "</td></tr>" );
  }
?>
  <tr>

<? if( $CONF->getval("displayinfo")!="none" ): ?>
    <td></td><td width="5"></td>
<? endif; ?>

    <td align="center" valign="bottom" height="35">
	  <input type="submit" name="submit" value="Upload Files" <?=$stylel?>> 
	</td>
  </tr>
</form>
</table>