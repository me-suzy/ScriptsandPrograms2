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
  $section = "results";
  include( "initialize.php" );
  
  checklogged();
  
  if( $_POST['action']=="save" )
  {
    if( $_POST['sendconfirmation'] && ( !$_POST['confirmtitle'] || !$_POST['confirmmsg'] ) ) confirm( "Please enter the confirmation title and content!" );
    if( !$_POST['thankyoumsg'] ) confirm( "Please enter a thank you message!" );
    if( $_POST['attachments']=="only" && !$_POST['attachmaxsize'] ) confirm( "Please enter a minimum size for the attachements!" );
  
    $CONF->setval( $_POST['subdir'], "subdir" );
	$CONF->setval( $_POST['existing'], "existing" );
  
    $CONF->setval( $_POST['thankyoumsg'], "thankyoumsg" );
	$CONF->setval( $_POST['redirecturl'], "redirecturl" );
	$CONF->setval( $_POST['moreuploads'], "moreuploads" );
	
	$CONF->setval( $_POST['sendconfirmation'], "sendconfirmation" );
	$CONF->setval( $_POST['confirmtitle'], "confirmtitle" );
	$CONF->setval( $_POST['confirmmsg'], "confirmmsg" );

	$CONF->setval( $_POST['notification'], "notification" );
	$CONF->setval( $_POST['attachments'], "attachments" );
	$CONF->setval( $_POST['attachmaxsize'], "attachmaxsize" );
  
	$CONF->savedata();
	
	confirm( "Changes successfully saved", $_SERVER['PHP_SELF'] );
  }
  
  showheader( $section );
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="save">
<tr><td>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">File Location</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Upload Subdirectory</b><br>
      With a subdir you can more easily find the files using a FTP client. If you use an user field, make sure the name will always work.
<? if( $safemode ): ?>
      <font color="red">Since you're running in safe mode, no sub-directory can be used.<!--CyKuH--></font>
<? endif; ?>
	</td>
    <td>
	  <select name="subdir">
	  	<option value="none" <? if( $CONF->getval("subdir")=="none" ) echo( " selected" ); ?>>Don't create a sub-directory</option>

<? if( !$safemode ): ?>

		<option value="date" <? if( $CONF->getval("subdir")=="date" ) echo( " selected" ); ?>>Use upload date and a number</option>
		<option value="random" <? if( $CONF->getval("subdir")=="random" ) echo( " selected" ); ?>>Generate a random name</option>

<?
    $fields = $FIELD->get();
   
    foreach( $fields AS $field )
    {
      // make sure the field is required
	  if( !$field['required'] || $field['type']=="textarea" || $field['type']=="file" || $field['type']=="dropbox" ) continue;
  
      echo( "<option value='{$field['id']}'" );
	  if( $field['id']==$CONF->getval("subdir") ) echo( " selected" );
	  echo( ">Use {$field['name']}</option>" );
    }
	
  endif;
?>

	  </select>    
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Existing Files</b><br>
      What do you want the script to do if an uploaded file already exist in the same directory?
	</td>
    <td>
<? if( !$safemode ): ?>
      <input type="radio" name="existing" value="overwrite" <? if($CONF->getval("existing")=="overwrite") echo("checked"); ?>> Overwrite with new file<br>
<? endif; ?>
	  <input type="radio" name="existing" value="skip" <? if($CONF->getval("existing")=="skip") echo("checked"); ?>> Skip / ignore new file<br>
      <input type="radio" name="existing" value="addnumber" <? if($CONF->getval("existing")=="addnumber") echo("checked"); ?>> Add a number at the end of the file
    </td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Result Page</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Thank You Message</b><br>
      The message which will be displayed after a successful upload.
	</td>
    <td>
      <textarea name="thankyoumsg" cols="49" rows="7"><?=$CONF->getval("thankyoumsg")?></textarea>
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Redirection URL</b><br>
      If you want the user to go to a page after a successful upload, enter
	  it here. Otherwise leave it blank.
	</td>
    <td>
      <input type="text" name="redirecturl" size="50" value="<?=$CONF->getval("redirecturl")?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
      <b>More Uploads Allowed?</b><br>
      Offer user to upload more files after a successful upload?
    </td>
    <td>
      <input type="radio" name="moreuploads" value="1" <? if($CONF->getval("moreuploads")) echo("checked"); ?>> Yes
	  <input type="radio" name="moreuploads" value="0" <? if(!$CONF->getval("moreuploads")) echo("checked"); ?>> No
    </td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">User Confirmation</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Send Confirmation?</b><br>
      Send an email to user after successful upload? Only if
	  you require the user to enter an email address
	</td>
    <td>
      <input type="radio" name="sendconfirmation" value="1" <? if($CONF->getval("sendconfirmation")) echo("checked"); ?>> Yes
	  <input type="radio" name="sendconfirmation" value="0" <? if(!$CONF->getval("sendconfirmation")) echo("checked"); ?>> No
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Email Title & Content</b><br>
      The content and the title of the email sent automatically to an user after successful upload
	</td>
    <td>
	  <input type="text" name="confirmtitle" size="50" value="<?=$CONF->getval("confirmtitle")?>">
      <textarea name="confirmmsg" cols="49" rows="7"><?=$CONF->getval("confirmmsg")?></textarea>
    </td>
  </tr>
</table>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">Admin Notification</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Notification</b><br>
      Send an email to admin after upload? The HTML format require a MIME-compatible email reader. AOL email reader is not compatible.
	</td>
    <td>
      <input type="radio" name="notification" value="none" <? if($CONF->getval("notification")=="none") echo("checked"); ?>> Don't send any notification<br>
	  <input type="radio" name="notification" value="text" <? if($CONF->getval("notification")=="text") echo("checked"); ?>> Send notification in plain text format<br>
      <input type="radio" name="notification" value="html" <? if($CONF->getval("notification")=="html") echo("checked"); ?>> Send notification in HTML (MIME) format
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
      <b>Attachments?</b><br>
      Do you want the uploaded files to be also attached on the email? Require a MIME-compatible email reader.
    </td>
    <td>
      <input type="radio" name="attachments" value="never" <? if($CONF->getval("attachments")=="never") echo("checked"); ?>> Never attach files (use links)<br>
	  <input type="radio" name="attachments" value="always" <? if($CONF->getval("attachments")=="always") echo("checked"); ?>> Always attach files<br>
      <input type="radio" name="attachments" value="only" <? if($CONF->getval("attachments")=="only") echo("checked"); ?>> Only if total file size is below <input type="text" name="attachmaxsize" size="7" value="<?=$CONF->getval("attachmaxsize")?>">Kb
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