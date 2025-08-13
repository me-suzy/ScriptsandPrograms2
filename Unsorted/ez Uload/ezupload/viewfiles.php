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
  $section = "browser";
  include( "initialize.php" );
  
  checklogged();

  
  ///////////////////////////////
  // SEND EMAIL TO UPLOADER
  ///////////////////////////////
  
  if( $_POST['action']=="email" )
  {
    if( $demomode ) confirm( "No emails can be sent on the demo mode" );
  
	sendemail( $UPLOAD->getval("email", $_POST['id']), $CONF->getval("adminemail"), $CONF->getval("adminname"), $_POST['title'], $_POST['content'] );
  
    confirm( "Message successfully sent", "viewfiles.php?id=" . $_POST['id'] );
  }
  
  
  ///////////////////////////////
  // DELETE THE UPLOAD
  ///////////////////////////////
  
  if( $_POST['action']=="edit" && isset($_POST['delete']) )
  {
    header( "Location: delete.php?type=upload&id=" . $_POST['id'] . "&$SID" );
	exit;
  }
  
  
  ///////////////////////////////
  // START DISPLAYING THE PAGE
  ///////////////////////////////
  
  if( !$UPLOAD->exists($_GET['id']) ) confirm( "No upload found with this ID" );

  showheader( $section );
  
  clearfiles();
  
  $files = $FILE->queryrows( $_GET['id'], "upload" );

  if( count($files)==0 ):
  
    showmessage( "No associated files found with the upload information." );
	
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
	
	if( @file_exists($filename) ):
	
	  $fileurl = rawurlencode( $filename );
	  $fileurl = str_replace( "%2F", "/", $filename );
?>
  
  <tr align="center" class="altsecond" onMouseOver="this.className='altfirst'; this.style.cursor='hand';" onMouseOut="this.className='altsecond'" onClick="window.location.href='<?=$fileurl?>'">
    <td width="5"><a href="<?=$fileurl?>"><img src="images/file.gif" border="0"></a></td>
    <td align="left"><b><a href="<?=$fileurl?>"><?=$file['name']?></a></b></td>
	<td><?=$filesize?>Kb</td>
	<td><?=$file['type']?></td>
	<td><b><b><a href="delete.php?id=<?=$file['id']?>&type=file&<?=$SID?>">Delete</a></b></td>
  </tr>
  
<?  else: ?>

  <tr align="center" class="altsecond" onMouseOver="this.className='altfirst';" onMouseOut="this.className='altsecond'">
    <td width="5"><img src="images/nofile.gif" border="0"></td>
    <td align="left"><b><s><?=$file['name']?></s></b></td>
	<td><?=$filesize?>Kb</td>
	<td><?=$file['type']?></td>
	<td><b><b><a href="delete.php?id=<?=$file['id']?>&type=file&<?=$SID?>">Delete</a></b></td>
  </tr>
  
<?
    endif;

  endforeach;
?>
  
</table>

<?
    showspace();
	
  endif;
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="viewfiles.php">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="id" value="<?=$_GET['id']?>">
<? showsession(); ?>
  <tr class="header">
    <td colspan="2">Upload Information</td>
  </tr>
  
<?
  // get user name, if any
  if( $UPLOAD->getval("user", $_GET['id']) )
  {
    $userid = $UPLOAD->getval( "user", $_GET['id'] );
    $username = $USER->getval( "name", $userid );
    $infoarray['User Name'] = "<a href='edituser.php?id=$userid&$SID'>$username</a>";
  }

  // get date and email
  $infoarray['Uploaded On'] = userdate( $UPLOAD->getval("uploaded", $_GET['id']) );
  if( $UPLOAD->getval("email", $_GET['id']) ) $infoarray['Email Address'] = $UPLOAD->getval("email", $_GET['id']);
   
  // get other user info from UPLOADINFO table
  $infos = $UPLOADINFO->queryrows( $_GET['id'], "upload" );
  foreach( $infos AS $info )
  {
    $infoarray[$info['name']] = $info['value'];
  }

  while( list($field,$value) = each($infoarray) ):

	$value = preg_replace( "/(https?:\/\/|ftp:\/\/|mailto:|www\.)([a-z0-9\._@]+)/i", "<a href=\"\\1\\2\" target=\"_blank\">\\2</a>", $value ); 
    $value = preg_replace( "/href=\"www\./i", "href=\"http://www.", $value );
    $value = ereg_replace( "([-a-z0-9_]+(.[_a-z0-9-]+)*@([a-z0-9-]+(.[a-z0-9-]+)+))", "<a href=\"mailto:\\1\">\\1</a>", $value );
	
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
	  <input type="submit" name="delete" value="Delete Upload">
    </td>
  </tr>
</form>
</table>

<?
  if( $UPLOAD->getval("email",$_GET['id']) ): 

    showspace();
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="viewfiles.php">
<input type="hidden" name="action" value="email">
<input type="hidden" name="id" value="<?=$_GET['id']?>">
<? showsession(); ?>
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