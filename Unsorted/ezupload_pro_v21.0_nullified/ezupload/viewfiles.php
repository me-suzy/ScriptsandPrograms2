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
  $section = "browser";
  include( "initialize.php" );
  
  checklogged();
  
  // clear the files variables
  include( "clearfiles.php" );
  
  
  ///////////////////////////////
  // SEND EMAIL TO UPLOADER
  ///////////////////////////////
  
  if( $_POST['action']=="email" )
  {
	sendmessage( $UPLOAD->getval("email", $_POST['id']), $_POST['title'], $_POST['content'], $CONF->getval("adminname"), $CONF->getval("adminemail") );
  
    confirm( "Message successfully sent", "viewfiles.php?id=" . $_POST['id'] );
  }
  
  
  ///////////////////////////////
  // DELETE THE UPLOAD
  ///////////////////////////////
  
  if( $_POST['action']=="edit" && isset($_POST['delete']) )
  {
    header( "Location: delete.php?type=upload&id=" . $_POST['id'] );
  }
  
  
  ///////////////////////////////
  // START DISPLAYING THE PAGE
  ///////////////////////////////
  
  showheader( $section );
  
  $files = $FILE->queryrows( $_GET['id'], "upload" );

  if( count($files)==0 ):
  
    echo( "No files found under this upload" );
	
  else:
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr align="center" class="header">
    <td align="left" colspan="2">File Name</td>
    <td>File Size</td>
	<td>File Type</td>
	<td>Action</td>
  </tr>
  
<?
  foreach( $files AS $file ):
  
    $filename = $filesdir . $UPLOAD->getval("subdir",$_GET['id']) . $file['name'];
	
	$filesize = (int) round( $file['size'] / 1024 );
?>
  
  <tr align="center" <?=getaltclass()?>>
    <td width="5"><a href="<?=$filename?>" target="_blank"><img src="file.gif" border="0"></a></td>
    <td align="left"><b><a href="<?=$filename?>" target="_blank"><?=$file['name']?></a></b></td>
	<td><?=$filesize?>Kb</td>
	<td><?=$file['type']?></td>
	<td><b><b><a href="delete.php?id=<?=$file['id']?>&type=file">Delete</a></b></td>
  </tr>
  
<? endforeach; ?>
  
</table>

<? endif; ?>

<? showspace(); ?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="id" value="<?=$_GET['id']?>">
  <tr class="header">
    <td colspan="2">Upload Information</td>
  </tr>
  
<?
  $upload = $UPLOAD->getrow( $_GET['id'] );

  while( list($field,$value) = each($upload) ):

    if( !$value || $field=="id" || $field=="subdir" ) 
	{
	  continue;
	}
	elseif( $field=="email" )
	{
	  $field = "Email Address";
	  $value = "<a href='mailto:$value'>$value</a>";
	}
    elseif( $field=="uploaded" )
	{
	  $field = "Uploaded On";
	  $value = date( "m/d/y @ H:i", $value );
	}
	
?>

  <tr <?=getaltclass()?>>
    <td width="35%" valign="top">
	  <b><?=$field?></b>
	</td>
    <td>
      <?=nl2br($value)?>
    </td>
  </tr>
  
<? endwhile; ?>
  
  <tr align="center" class="header">
    <td colspan="2">
<? if( !$safemode ): ?>
	<input type="submit" name="delete" value="Delete Information & Files">
<? else: ?>
	<input type="submit" name="delete" value="Delete Upload Information" onClick="return confirm('Since you\'re running in safe mode,\nthis will NOT delete the uploaded files.\nYou\'ll have to do this with a FTP client.');">
<? endif; ?>
    </td>
  </tr>
</form>
</table>

<?
  if( $UPLOAD->getval("email",$_GET['id']) ): 

    showspace();
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="email">
<input type="hidden" name="id" value="<?=$_GET['id']?>">
  <tr class="header">
    <td colspan="2">Send Message</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
	  <b>Title</b><br>
      The title of the message to send
	</td>
    <td>
      <input type="text" name="title" size="65">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="40%" valign="top">
	  <b>Content</b><br>
      The content of the message to send
	</td>
    <td>
      <textarea name="content" cols="64" rows="10"></textarea>
    </td>
  </tr>
  <tr align="center" class="header">
    <td colspan="2">
      <input type="submit" name="edit" value="Send Message">
    </td>
  </tr>
</form>
</table>

<? endif; ?>
	
<? showfooter($section); ?>