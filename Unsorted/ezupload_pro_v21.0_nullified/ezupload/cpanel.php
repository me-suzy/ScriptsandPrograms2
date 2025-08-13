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
  $section = "home";
  include( "initialize.php" );

  checklogged();
  
  if( $_POST['action']=="save" )
  {
    if( $_POST['adminpass'] != $_POST['adminpass2'] ) confirm( "The two passwords entered are different!" );
  
    $CONF->setval( $_POST['adminname'], "adminname" );
	$CONF->setval( $_POST['adminemail'], "adminemail" );
	
	if( !empty($_POST['adminpass']) )
	{
	  $passhash = md5( $_POST['adminpass'] );
	  $CONF->setval( $passhash, "adminpass" );
	  setcookie( "adminpass", $passhash );
	}
	
	$CONF->savedata();
	
	confirm( "Changes successfully saved", $_SERVER['PHP_SELF'] );
  }
  
  showheader( $section );
  
?>

<table width="100%">
<tr><td width="65%">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td width="30%" valign="top">
	  <b><a href="fields.php">Form Fields</a></b> ><br>
	  Define the type of information you want to take from the user.
	</td>
	<td width="10"></td>
    <td width="30%" valign="top">
	  <b><a href="results.php">Upload Results</a></b> ><br>
	  What you want the script to do after the form has been submitted.
	</td>
	<td width="10"></td>
    <td width="30%" valign="top">
	  <b><a href="customize.php">Customize Form</a></b> ><br>
	  Customize the upload form and easily include it on your site.
	</td>
  </tr>
  <tr height="15">
    <td></td>
  </tr>
  <tr>
    <td width="30%" valign="top">
	  <b><a href="filter.php">Files Filter</a></b> ><br>
	  Define the files you accept based on their extension and size.
	</td>
	<td width="10"></td>
    <td width="30%" valign="top">
	  <b><a href="browser.php">Files Browser</a></b> ><br>
	  View, download and delete the files users have uploaded.
	</td>
	<td width="10"></td>
    <td width="30%" valign="top">
	  <b><a href="index.php">Default Form</a></b> ><br>
	  The default upload form, for you to see the changes you made.
	</td>
  </tr>
</table>

</td><td valign="top" width="35%">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" valign="top">
	  <img src="logo.gif" border="0"><br><br>
	</td>
  </tr>
</table>

</td></tr>
</table>

<? 

  // clear the files variables
  include( "clearfiles.php" );

  $UPLOAD->sortdata( "uploaded", "desc" );

  $uploads = $UPLOAD->get();
  
  if( count($uploads)>0 ):
  
    showspace();
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr align="center" class="header">
    <td align="left" colspan="2">Latest Uploads</td>
    <td>Num. Files</td>
    <td>Date</td>
	<td>Action</td>
  </tr>
  
<?
  // take only 10 values from array
  $uploads = array_slice( $uploads, 0 , 10 );

  foreach( $uploads AS $upload ):
  
    if( empty($uploads[$i]) ) break;
  
    $upload = $uploads[$i];
  
    $numfiles = $FILE->getnumrows( $upload['id'], "upload" );
	
	if( $upload['subdir']=="" )
	  $subdir = "Upload #" . $upload['id'];
	else
	  $subdir = substr( $upload['subdir'], 0, (strlen($upload['subdir'])-1) );
?>
  
  <tr align="center" <?=getaltclass()?>>
    <td width="5"><a href="viewfiles.php?id=<?=$upload['id']?>"><img src="folder.gif" border="0"></a></td>
    <td align="left"><b><a href="viewfiles.php?id=<?=$upload['id']?>"><?=$subdir?></a></b></td>
	<td><?=$numfiles?> Files</td>
    <td><?=date( "m/d/y H:i", $upload['uploaded'] )?></td>
	<td><b><a href="viewfiles.php?id=<?=$upload['id']?>">View</a></b> | <b><a href="delete.php?id=<?=$upload['id']?>&type=upload" <? if($safemode): ?> onClick="return confirm('Since you\'re running in safe mode,\nthis will NOT delete the uploaded files.\nYou\'ll have to do this with a FTP client.');" <? endif; ?>>Delete</a></b></td>
  </tr>
  
<? endforeach; ?>
  
</table>
	
<? endif; ?>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="save">
  <tr class="header">
    <td colspan="2">Administrator Information</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Admin Name</b><br>
      The name of the aministrator as it will appear on the emails sent from EzUpload.
	</td>
    <td>
      <input type="text" name="adminname" size="50" value="<?=$CONF->getval("adminname")?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Admin Email</b><br>
      The email address used for notifications as well as for the return address of sent email.
	</td>
    <td>
      <input type="text" name="adminemail" size="50" value="<?=$CONF->getval("adminemail")?>">
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Admin Password</b><br>
      The password to access the control panel, very important for your security. You'll need cookies to be able to login. Enter it twice.
	</td>
    <td>
      <input type="password" name="adminpass" size="22">
	  &nbsp;
	  <input type="password" name="adminpass2" size="22">
	</td>
  </tr>
  <tr align="center" class="header">
    <td colspan="2">
      <input type="submit" name="edit" value="Save Changes">
    </td>
  </tr>
</form>
</table>
			
<? showfooter($section); ?>