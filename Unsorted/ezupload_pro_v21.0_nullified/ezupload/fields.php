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

  if( $_POST['action']=="save" )
  {
    $CONF->setval( $_POST['emailfield'], "emailfield" );
	$CONF->setval( $_POST['takeip'], "takeip" );
  
	$CONF->savedata();
	
	confirm( "Changes successfully saved", $_SERVER['PHP_SELF'] );
  }
  elseif( $_POST['action']=="editfield" && isset($_POST['saveorder']) )
  {
    while( list($k,$v) = each($_POST['order']) )
	{
	  if( $v=="" || !is_numeric($v) ) confirm( "Please enter a number for all order fields" );
      $FIELD->setval( $v, "order", $k );
    }
	
	$FIELD->savedata();
	
	confirm( "Order successfully changed", $_SERVER['PHP_SELF'] );
  }
  elseif( $_POST['action']=="editfield" && isset($_POST['add']) )
  {
	header( "Location: editfield.php?type=" . $_POST['type'] );
  }
  
  showheader( $section );
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="save">
  <tr class="header">
    <td colspan="2">General User Info</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Email Field</b><br>
      Select the field which will be used for the (optional) confirmation email and contact form.
	</td>
    <td>
	  <select name="emailfield">
	  	<option value="-1">Don't Ask for Email</option>

<?
  $FIELD->sortdata( "order", "asc" );

  $fields = $FIELD->get();
  
  foreach( $fields AS $field )
  {
    if( $field['type']=="textarea" || $field['type']=="file" ) continue;
  
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
	  <b>Record IP?</b><br>
      Do you want to record the user IP address?
	</td>
    <td>
      <input type="radio" name="takeip" value="1" <? if($CONF->getval("takeip")) echo("checked"); ?>> Yes
	  <input type="radio" name="takeip" value="0" <? if(!$CONF->getval("takeip")) echo("checked"); ?>> No
    </td>
  </tr>
  <tr align="center" class="header">
    <td colspan="2">
      <input type="submit" name="edit" value="Save Changes">
    </td>
  </tr>
</form>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="editfield">
  <tr align="center" class="header">
    <td>Order</td>
    <td>Field Name</td>
    <td>Type</td>
	<td>Required</td>
    <td>Action</td>
  </tr>
  
<?
  $fieldarray = $FIELD->get();
  
  if( count($fieldarray)>0 ):
  
    while( list($k,$v) = each($fieldarray) ):
?>
  
  <tr align="center" <?=getaltclass()?>>
    <td><input type="text" name="order[<?=$v['id']?>]" size="3" value="<?=$v['order']?>"></td>
	<td><?=$v['name']?></td>
    <td><?=gettypename($v['type'])?></td>
	<td><?=getyesno($v['required'])?></td>
    <td><b><a href="editfield.php?id=<?=$v['id']?>">Edit</a></b></td>
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
	  		</select>
      		<input type="submit" name="add" value="Add Type">
		  </td>
		</tr>
	  </table>
    </td>
  </tr>
</form>
</table>
			
<? showfooter($section); ?>