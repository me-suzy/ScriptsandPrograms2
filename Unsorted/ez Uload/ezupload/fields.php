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
  $section = "fields";
  include( "initialize.php" );
  
  checklogged();

  
  ///////////////////////////////////////////
  // THE USER WANTS TO SAVE THE SETTINGS
  ///////////////////////////////////////////
  
  if( $_POST['action']=="save" )
  {
    if( $demomode ) confirm( "No change can be saved on the demo mode" );
  
    $CONF->setval( $_POST['emailfield'], "emailfield" );
	$CONF->setval( $_POST['namefield'], "namefield" );
	$CONF->setval( $_POST['reload_info'], "reload_info" );
  
	$CONF->savedata();
	
	confirm( "Changes successfully saved", "fields.php" );
  }
  
  
  ///////////////////////////////////////////
  // CHANGE THE ORDER OF THE FIELDS
  ///////////////////////////////////////////
  
  elseif( $_POST['action']=="editfield" && isset($_POST['saveorder']) )
  {
    if( $demomode ) echo( "No change can be saved on the demo mode" );
  
    while( list($k,$v) = each($_POST['order']) )
	{
	  if( $v=="" || !is_numeric($v) ) confirm( "Please enter a number for all order fields" );
      $FIELD->setval( $v, "order", $k );
    }
	
	$FIELD->savedata();
	
	confirm( "Order successfully changed", "fields.php" );
  }
  
  
  ///////////////////////////////////////////
  // THE USER WANTS TO ADD A FIELD
  ///////////////////////////////////////////
  
  elseif( $_POST['action']=="editfield" && isset($_POST['add']) )
  {
	header( "Location: editfield.php?type=" . $_POST['type'] . "&$SID" );
  }
  
  
  ///////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ///////////////////////////////////////////
  
  showheader( $section );
  
  if( !$demomode )
  {
    if( $FIELD->getnumrows("file", "type") == 0 )
    {
      showmessage( "Warning, you don't have any file field, users won't be able to upload files!" );
    }
    else
    {
      showmessage( "To add a new field, select a field type at the bottom of the list and click on \"Add Field\"" );
    }
  }
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="fields.php">
<input type="hidden" name="action" value="editfield">
<? showsession(); ?>
  <tr align="center" class="header">
    <td width="60">Order</td>
    <td>Field Name</td>
    <td>Field Type</td>
	<td>Required?</td>
    <td width="120">Action</td>
  </tr>
  
<?
  $FIELD->sortdata( "order", "asc" );

  $fieldarray = $FIELD->get();
  
  if( count($fieldarray)>0 ):
  
    while( list($k,$v) = each($fieldarray) ):
?>
  
  <tr align="center" <?=getaltclass()?>>
    <td width="60"><input type="text" name="order[<?=$v['id']?>]" size="3" value="<?=$v['order']?>"></td>
	<td><?=$v['name']?></td>
    <td><?=gettypename($v['type'])?></td>
	<td><?=getyesno($v['required'])?></td>
    <td width="120"><b><a href="editfield.php?id=<?=$v['id']?>&<?=$SID?>">Edit</a></b> | <b><a href="delete.php?type=field&id=<?=$v['id']?>&<?=$SID?>">Delete</a></b></td>
  </tr>
  
<?
    endwhile; 

  endif;
?>
  
  <tr align="center" class="header">
    <td colspan="5">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
		  <td align="left">
      		<input type="submit" name="saveorder" value="Change Order">
		  </td>
		  <td align="right">
	  		<select name="type">
			  <option value="file">File Field</option>
	  		  <option value="text">Simple Text</option>
			  <option value="password">Password</option>
			  <option value="textarea">Text Area</option>
			  <option value="dropbox">Drop Box</option>
			  <option value="checkbox">Check Box</option>
			  <option value="radio">Radio Buttons</option>
	  		</select>
      		<input type="submit" name="add" value="Add Field">
		  </td>
		</tr>
	  </table>
    </td>
  </tr>
</form>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="fields.php">
<input type="hidden" name="action" value="save">
<? showsession(); ?>
  <tr class="header">
    <td colspan="2">Fields Association</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Email Field</b><br>
      Select the field used for the confirmation email and contact form. If no field is set and user authentification is activated, the user's email will be used.
	</td>
    <td>
	  <select name="emailfield">
	  	<option value="-1">There's no email field</option>

<?
  $FIELD->sortdata( "order", "asc" );

  $fields = $FIELD->get();
  
  foreach( $fields AS $field )
  {
    if( $field['type']!="text" ) continue;
  
    echo( "<option value='{$field['id']}'" );
	if( $field['id']==$CONF->getval("emailfield") ) echo( " selected" );
	echo( ">{$field['name']}</option>" );
  }
?>

	  </select>
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Upload Name Field</b><br>
      Select the field for the name of the upload. It will help you find and identify uploads on the file browser. Only required fields can be used.
	</td>
    <td>
	  <select name="namefield">
	  	<option value="-1">Upload ID (default)</option>

<?
  $FIELD->sortdata( "order", "asc" );

  $fields = $FIELD->get();
  
  foreach( $fields AS $field )
  {
    if( !$field['required'] || $field['type']=="textarea" || $field['type']=="file" || $field['type']=="checkbox" ) continue;
  
    echo( "<option value='{$field['id']}'" );
	if( $field['id']==$CONF->getval("namefield") ) echo( " selected" );
	echo( ">{$field['name']}</option>" );
  }
?>

	  </select>
    </td>
  </tr>
  
<? if( $CONF->getval("formprotect")=="user" ): ?>
  
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Remember Upload Infos?</b><br>
      Do you want the fields to be automatically filled with the information from the last upload by the same user? (only if user authentification is enabled)
	</td>
    <td>
      <input type="radio" name="reload_info" value="1" <? if($CONF->getval("reload_info")) echo("checked"); ?>> Yes
	  <input type="radio" name="reload_info" value="0" <? if(!$CONF->getval("reload_info")) echo("checked"); ?>> No
    </td>
  </tr>
  
<? endif; ?>
  
  <tr align="center" class="header">
    <td colspan="2">
      <input type="submit" name="edit" value="Save Changes">
    </td>
  </tr>
</form>
</table>
			
<? showfooter($section); ?>