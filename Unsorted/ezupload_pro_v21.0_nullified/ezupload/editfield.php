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
  $section = "fields";
  include( "initialize.php" );
  
  checklogged();

  if( $_POST['action']=="edit" )
  {
    if( isset($_POST['delete']) )
	{
	  // set email field to none if we deleted the selected field
	  if( $CONF->getval("emailfield")==$_POST['id'] )
	  {
	    $CONF->setval( -1, "emailfield" );
	  }
	  
	  // change subdir if we deleted the selected field
	  if( $CONF->getval("subdir")==$_POST['id'] )
	  {
	    $CONF->setval( "date", "subdir" );
	  }
	  
	  // if dropbox, delete all options
	  if( $_POST['type']=="dropbox" )
	  {
	    $OPTION->deleterows( $_POST['id'], "field" );
	  }
	
      $FIELD->deleterow( $_POST['id'] );
	}
	else
	{
	  if( isset($_POST['add']) )
	    $fieldid = $FIELD->addrow();
	  else
	    $fieldid = $_POST['id'];
	
	  if( !$_POST['name'] ) confirm( "Please enter a name for this field!" );
	
	  // change subdir if this field is not required anymore
	  if( $CONF->getval("subdir")==$fieldid && !$_POST['required'] )
	  {
	    $CONF->setval( "date", "subdir" );
	  }
	
	  if( $_POST['type']=="dropbox" )
	  {
	    // delete option rows and add them back
	    $OPTION->deleterows( $fieldid, "field" );
	  
	    foreach( $_POST['option'] AS $optionval )
	    {
	      if( !$optionval ) continue;
	  
	      $optionid = $OPTION->addrow();
		  $OPTION->setval( $fieldid, "field", $optionid );
		  $OPTION->setval( $optionval, "value", $optionid );
	    }
		
		if( $OPTION->getnumrows($fieldid,"field") == 0 ) confirm( "Please enter at least one value for the options!" );
	  }
	
	  $FIELD->setval( $_POST['name'], "name", $fieldid );
	  $FIELD->setval( $_POST['description'], "description", $fieldid );
	  $FIELD->setval( $_POST['default'], "default", $fieldid );
	  $FIELD->setval( $_POST['minchars'], "minchars", $fieldid );
	  $FIELD->setval( $_POST['maxchars'], "maxchars", $fieldid );
	  $FIELD->setval( $_POST['required'], "required", $fieldid );
	  $FIELD->setval( $_POST['type'], "type", $fieldid );
		
	  if( isset($_POST['add']) )
	  {
	    $FIELD->setval( $fieldid, "order", $fieldid );
	  }
	}
	
    $FIELD->savedata();
	$CONF->savedata();
	$OPTION->savedata();
	
	confirm( "Changes successfully saved", "fields.php" );
  }
	
  // we want to edit the user field
  if( isset($_GET['id']) )
  {
    $newfield = false;
	
	$fieldid = $_GET['id'];
  }
  
  // we want to add a new user field
  elseif( isset($_GET['type']) )
  {
    $newfield = true;
  
    // add new row (not saved yet)
	$fieldid = $FIELD->addrow();
	
    // define some default values
	$FIELD->setval( 0, "minchars", $fieldid );
	$FIELD->setval( 1000, "maxchars", $fieldid );
	$FIELD->setval( 1, "required", $fieldid );
	$FIELD->setval( $_GET['type'], "type", $fieldid );
	
	if( $_GET['type']=="file" )
	{
	  $filenum = $FIELD->getnumrows( "file", "type" );
	  $FIELD->setval( "File #$filenum", "name", $fieldid );
	}
  }
  
  showheader( $section );
  
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">

<? if( $_GET['id'] ): ?>
<input type="hidden" name="id" value="<?=$fieldid?>">
<? endif; ?>

<input type="hidden" name="type" value="<?=$FIELD->getval("type",$fieldid)?>">
<input type="hidden" name="action" value="edit">
  <tr class="header">
    <td colspan="2">Define Form Field</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Field Name</b><br>
      The field name as it will appear on the upload form and files browser.
	</td>
    <td>
      <input type="text" name="name" size="50" value="<?=$FIELD->getval("name",$fieldid)?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Field Description</b><br>
      A short description to help the user (optional)
	</td>
    <td>
      <input type="text" name="description" size="50" value="<?=$FIELD->getval("description",$fieldid)?>">
    </td>
  </tr>
  
<? if( $FIELD->getval("type",$fieldid)=="dropbox" ): ?>

  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Options</b><br>
      The options the user will be able to choose from the dropbox. If there aren't enough options, save these already and you'll be able to add more by editing this field later.
	</td>
    <td>
	
<?
  $options = $OPTION->queryrows( $fieldid, "field" );
  
  $numoptions = 10;
  if( count($options)>5 ) $numoptions = count($options)+5;
  
  for( $i=0; $i<$numoptions; $i++ ):
?>
	
      <input type="text" name="option[<?=$i?>]" size="50" value="<?=$options[$i]['value']?>">

<?
  endfor;
?>

    </td>
  </tr>
  
<?
  endif;

  if( $FIELD->getval("type",$fieldid)=="text" || $FIELD->getval("type",$fieldid)=="textarea" ):
?>
  
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Default Value</b><br>
      A default value for the field (optional)
	</td>
    <td>
      <input type="text" name="default" size="50" value="<?=$FIELD->getval("default",$fieldid)?>">
    </td>
  </tr>
  
<?
  endif;
  
  if( $FIELD->getval("type",$fieldid)=="text" || $FIELD->getval("type",$fieldid)=="password" || $FIELD->getval("type",$fieldid)=="textarea" ):
?>
  
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Max/Min Char</b><br>
      The maximum and minimum number of characters the user may enter for this field
	</td>
    <td>
      <input type="text" name="minchars" size="22" value="<?=$FIELD->getval("minchars",$fieldid)?>">
	  &nbsp;
	  <input type="text" name="maxchars" size="22" value="<?=$FIELD->getval("maxchars",$fieldid)?>">
    </td>
  </tr>
  
<? endif; ?>
  
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Required Field?</b><br>
      Will users have to enter a value for this field?
	</td>
    <td>
      <input type="radio" name="required" value="1" <? if($FIELD->getval("required",$fieldid)) echo("checked"); ?>> Yes
	  <input type="radio" name="required" value="0" <? if(!$FIELD->getval("required",$fieldid)) echo("checked"); ?>> No
    </td>
  </tr>
  <tr align="center" class="header">
    <td colspan="2">
<? if($newfield): ?>
      <input type="submit" name="add" value="Add Form Field">
<? else: ?>
      <input type="submit" name="edit" value="Edit Form Field">
	  <input type="submit" name="delete" value="Delete">
<? endif; ?>
    </td>
  </tr>
</form>
</table>

<? showfooter($section); ?>