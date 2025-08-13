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
  $maxsize = 1000 * 1024 * 1024;

  $newupload = true;
  
  if( isset($HTTP_SESSION_VARS['uploadid']) )
  {
    if( $UPLOAD->exists($HTTP_SESSION_VARS['uploadid']) )
	{
	  $newupload = false;
	}
  }
  
?>

<table border="0" cellspacing="0" cellpadding="2">
<SCRIPT SRC="<?=$sitepath?>checkfields.php" LANGUAGE="JavaScript"></SCRIPT>
<form name="uploadfrm" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onSubmit="return checkfields(uploadfrm);">
<input type="hidden" name="formurl" value="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$maxsize?>">
<input type="hidden" name="mode" value="upload">
<? showsession(); ?>

<?
  $FIELD->sortdata( "order", "asc" );
 
  $fields = $FIELD->get();
  
  foreach( $fields AS $field )
  {
    echo( "<tr>" );
	
	$fname = "f" . $field['id'];
	  
	// check if data are saved in the sessions
	// (if "try again" or "upload more" was pressed)
	// replace the default value if this is the case
	if( isset( $HTTP_SESSION_VARS[$fname] ) )
	{
	  $field['default'] = $HTTP_SESSION_VARS[$fname];
	}
	
	// only the text area is aligned at the top
	if( $field['type']=="textarea" || $field['type']=="checkbox" || $field['type']=="radio" )
	  $valign = "top";
	else
	  $valign = "middle";
  
  	if( $CONF->getval("displayinfo")!="none" )
	{
	  echo( "<td valign='$valign'><b><font $stylel>{$field['name']}</b>" );
	  if( $CONF->getval("showrequired") && $field['required'] ) echo("*");
	  if( $CONF->getval("displayinfo")=="description" ) echo( "<br><font $styles>" . nl2br( wordwrapnew( $field['description'], 25 ) ) . "</font>" );
	  echo( "</font></td><td width='5'></td>" );
	}
	
	echo( "<td $stylel valign='$valign'>" );
	
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
	  if( $field['default']=="" ) echo( "<option value=''> </option>" );
	
	  $options = $OPTION->queryrows( $field['id'], "field" );

	  foreach( $options AS $option )
	  {
	    echo( "<option value='".addslashes($option['value'])."'" );
		if( $option['value']==$field['default'] ) echo( " selected" );
		echo( ">".addslashes($option['value'])."</option>" );
	  }
	  
	  echo( "</select>" );
	}
	elseif( $field['type']=="checkbox" )
	{
	  $options = $OPTION->queryrows( $field['id'], "field" );

	  $field['default'] = explode( "\n", $field['default'] );
	  
	  $i = 0;
	  
	  foreach( $options AS $option )
	  {
	    echo( "<input type='checkbox' name='{$fname}[]' value='".addslashes($option['value'])."'" );
		if( in_array( $option['value'], $field['default'] ) ) echo( " checked" );
		echo( "> ".addslashes($option['value'])."<br />" );
		$i++;
	  }
	}
	elseif( $field['type']=="radio" )
	{
	  $options = $OPTION->queryrows( $field['id'], "field" );

	  foreach( $options AS $option )
	  {
	    echo( "<input type='radio' name='$fname' value='".addslashes($option['value'])."'" );  
		if( $option['value']==$field['default'] ) echo( " checked" );
		echo( "> ".addslashes($option['value'])."<br>" );
	  }
	}
	
	echo( "</td></tr>" );
  }

  // the user must select an upload directory
  if( $newupload && $CONF->getval("subdir")=="select" && getnumdirs($path.$filesdir)>0 )
  {
    echo( "<tr>" );
  
    if( $CONF->getval("displayinfo")!="none" )
    {
      echo( "<td valign='$valign'><b><font $stylel>{$_LANG['upload_folder']}</b>" );
	  if( $CONF->getval("showrequired") ) echo("*");
	  echo( "</td><td width='5'></td>" );
    }
  
    echo( "<td><select name='subdirlist' $stylel>" );

	showdirlist( $path.$filesdir );
	  
	echo( "</select></td></tr>" );
  }
  
?>

  <tr>

<? if( $CONF->getval("displayinfo")!="none" ): ?>
    <td></td><td width="5"></td>
<? endif; ?>

    <td align="center" valign="bottom" height="35">
	  <input type="submit" name="submit" value="<?=$_LANG['upload_files']?>" <?=$stylel?>> 
	</td>
  </tr>
</form>
</table>