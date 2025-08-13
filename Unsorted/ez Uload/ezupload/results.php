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
  $section = "results";
  include( "initialize.php" );
  
  checklogged();
  
  if( $_POST['action']=="save" )
  {
    if( $demomode ) confirm( "No change can be saved on the demo mode" );
  
    if( $_POST['sendconfirmation'] && ( !$_POST['confirmtitle'] || !$_POST['confirmmsg'] ) ) confirm( "Please enter the confirmation title and content!" );
    if( !$_POST['thankyoumsg'] ) confirm( "Please enter a thank you message!" );
    if( $_POST['attachments']=="only" && !$_POST['attachmaxsize'] ) confirm( "Please enter a minimum size for the attachements!" );
  
    $CONF->setval( $_POST['subdir'], "subdir" );
	$CONF->setval( $_POST['subdir_field'], "subdir_field" );
	$CONF->setval( $_POST['subdir_user'], "subdir_user" );
	$CONF->setval( $_POST['existing'], "existing" );
  
    $CONF->setval( $_POST['thankyoumsg'], "thankyoumsg" );
	$CONF->setval( $_POST['redirecturl'], "redirecturl" );
	$CONF->setval( $_POST['moreuploads'], "moreuploads" );
	
	$CONF->setval( $_POST['sendconfirmation'], "sendconfirmation" );
	$CONF->setval( $_POST['confirmtitle'], "confirmtitle" );
	$CONF->setval( $_POST['confirmmsg'], "confirmmsg" );

	$CONF->setval( $_POST['notification'], "notification" );
	$CONF->setval( $_POST['includelinks'], "includelinks" );
	$CONF->setval( $_POST['attachments'], "attachments" );
	$CONF->setval( $_POST['attachmaxsize'], "attachmaxsize" );
	$CONF->setval( $_POST['notifyemails'], "notifyemails" );
  
	$CONF->savedata();
	
	confirm( "Changes successfully saved", "results.php" );
  }
  
  showheader( $section );
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form method="post" action="results.php">
<input type="hidden" name="action" value="save">
<? showsession(); ?>
<tr><td>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr class="header">
    <td colspan="2">File Location</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Upload Subdirectory</b><br>
<? if( !$safemode ): ?>
      With subdirectories you can more easily find the files using a FTP client. If none are used, all files will be uploaded on the /files directory.
<? else: ?>
      <font color="red">Since your server is running in safe mode, no sub-directory can be created by the script.</font>
<? endif; ?>
	  <br><br>If you let the user select a sub-directory, make sure to have created some on the /files directory otherwise none will be selected.
	</td>
    <td valign="top">
	  <input type="radio" name="subdir" value="none" <? if($CONF->getval("subdir")=="none") echo("checked"); ?>> Don't use a subdirectory (upload on /files)<br>
      <input type="radio" name="subdir" value="select" <? if($CONF->getval("subdir")=="select") echo("checked"); ?>> Let user select existing subdirectory<br>

<? if( !$safemode ): ?>

	  <input type="radio" name="subdir" value="date" <? if($CONF->getval("subdir")=="date") echo("checked"); ?>> Use the upload date and a number<br>
	  <input type="radio" name="subdir" value="random" <? if($CONF->getval("subdir")=="random") echo("checked"); ?>> Use a random alphanumeric name<br>
	  <input type="radio" name="subdir" value="field" <? if($CONF->getval("subdir")=="field") echo("checked"); ?>> Use a form field:&nbsp;
	  <select name="subdir_field">
	    <option value="-1">Upload ID</option>

<?
    $fields = $FIELD->get();
   
    foreach( $fields AS $field )
    {
      // make sure the field can fit for a directory name
	  if( !$field['required'] || $field['type']=="textarea" || $field['type']=="file" || $field['type']=="checkbox" ) continue;
  
      echo( "<option value='{$field['id']}'" );
	  if( $field['id']==$CONF->getval("subdir_field") ) echo( " selected" );
	  echo( ">{$field['name']}</option>" );
    }
?>

	  </select><br>
	
<? if( $CONF->getval("formprotect")=="user" ): ?>
	
	  <input type="radio" name="subdir" value="user" <? if($CONF->getval("subdir")=="user") echo("checked"); ?>> Use an user field:&nbsp;
	  <select name="subdir_user">
	    <option value="id">User ID</option>
	    <option value="name">User Name</option>
		<option value="email">User Email</option>
	  </select>
<?
    endif;

  endif;
?>

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
      You may want the user to go to a page after a successful upload. The upload ID is POSTed to this URL if you want to use it for a script.
	</td>
    <td>
      <input type="text" name="redirecturl" size="50" value="<?=$CONF->getval("redirecturl")?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
      <b>More Uploads Allowed?</b><br>
      Do you want allow the user to upload more files after a successful upload? If you choose to remember the upload information, then new uploaded files will be counted as part of the same upload.
    </td>
    <td>
	  <input type="radio" name="moreuploads" value="0" <? if($CONF->getval("moreuploads")=="0") echo("checked"); ?>> No, just one upload by default<br>
      <input type="radio" name="moreuploads" value="1" <? if($CONF->getval("moreuploads")=="1") echo("checked"); ?>> Yes, and remember info from first upload<br>
	  <input type="radio" name="moreuploads" value="2" <? if($CONF->getval("moreuploads")=="2") echo("checked"); ?>> Yes, and clear info from first upload
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
	  you require the user to enter an email.
	</td>
    <td>
      <input type="radio" name="sendconfirmation" value="1" <? if($CONF->getval("sendconfirmation")) echo("checked"); ?>> Yes
	  <input type="radio" name="sendconfirmation" value="0" <? if(!$CONF->getval("sendconfirmation")) echo("checked"); ?>> No
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Email Content</b><br>
      The title and content of the email sent to an user after successful upload.<br><br>You may insert the <b>[links]</b> text which will be automatically changed to include the links to the files uploaded by the user.
	</td>
    <td>
	  <input type="text" name="confirmtitle" size="50" value="<?=$CONF->getval("confirmtitle")?>">
      <textarea name="confirmmsg" cols="49" rows="10"><?=$CONF->getval("confirmmsg")?></textarea>
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
	  <b>Notification Type</b><br>
      Send an email after upload? The HTML format require a MIME-compatible email reader. AOL email reader is not compatible.
	</td>
    <td>
      <input type="radio" name="notification" value="none" <? if($CONF->getval("notification")=="none") echo("checked"); ?>> Don't send any notification<br>
	  <input type="radio" name="notification" value="text" <? if($CONF->getval("notification")=="text") echo("checked"); ?>> Send notification in plain text format<br>
      <input type="radio" name="notification" value="html" <? if($CONF->getval("notification")=="html") echo("checked"); ?>> Send notification in HTML (MIME) format
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
      <b>Include Links?</b><br>
      Do you want direct links to the uploaded files to be included in the notification?
    </td>
    <td>
      <input type="radio" name="includelinks" value="1" <? if($CONF->getval("includelinks")) echo("checked"); ?>> Yes
	  <input type="radio" name="includelinks" value="0" <? if(!$CONF->getval("includelinks")) echo("checked"); ?>> No
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
      <b>Attachments</b><br>
      Do you want the uploaded files to be also attached on the email? Require a MIME-compatible email reader.
    </td>
    <td>
      <input type="radio" name="attachments" value="never" <? if($CONF->getval("attachments")=="never") echo("checked"); ?>> Never attach files to notifications<br>
	  <input type="radio" name="attachments" value="always" <? if($CONF->getval("attachments")=="always") echo("checked"); ?>> Always attach files to notifications<br>
      <input type="radio" name="attachments" value="only" <? if($CONF->getval("attachments")=="only") echo("checked"); ?>> Only if total file size is below <input type="text" name="attachmaxsize" size="7" value="<?=$CONF->getval("attachmaxsize")?>">Kb
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
      <b>Additional Email Addresses</b><br>
      If you want the notification to be sent to other people (in addition to the admin), enter their email addresses separated with a coma.
    </td>
    <td>
      <input type="text" name="notifyemails" size="50" value="<?=$CONF->getval("notifyemails")?>">
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