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
  // THE USER WANTS TO EMAIL
  ///////////////////////////////
  
  if( $_POST['action']=="email" )
  {
    $uploads = $UPLOAD->get();
	
	$nummessages = 0;
	
	foreach( $uploads AS $upload )
	{
	  if( !isemail($upload['email']) ) continue;

	  sendmessage( $upload['email'], $_POST['title'], $_POST['content'], $CONF->getval("adminname"), $CONF->getval("adminemail") );

	  $nummessages++;
	}

    confirm( "$nummessages messages successfully sent", $_SERVER['PHP_SELF'] );
  }
  
  
  ///////////////////////////////
  // START SHOWING THE PAGE
  ///////////////////////////////
  
  showheader( $section );
  
  $UPLOAD->sortdata( "uploaded", "desc" );
  
  $uploads = $UPLOAD->get();
  
  if( count($uploads)==0 ):
  
    echo( "No uploads recorded yet" );
  
  else:
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
  <tr align="center" class="header">
    <td align="left" colspan="2">Upload Name</td>
    <td>Num. Files</td>
    <td>Date</td>
	<td>Action</td>
  </tr>
  
<?
  foreach( $uploads AS $upload ):
  
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
<input type="hidden" name="action" value="email">
  <tr class="header">
    <td colspan="2">Email All Uploaders</td>
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
      <input type="submit" name="edit" value="Email All Uploaders">
    </td>
  </tr>
</form>
</table>
			
<? showfooter($section); ?>