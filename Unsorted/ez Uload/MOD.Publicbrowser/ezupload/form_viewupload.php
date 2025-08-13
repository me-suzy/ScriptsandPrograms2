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
  ///////////////////////////////
  // START DISPLAYING THE PAGE
  ///////////////////////////////
  
  if( !$UPLOAD->exists($_GET['id']) ) exit( "No upload found with this ID" );
  
  clearfiles();
  
  $files = $FILE->queryrows( $_GET['id'], "upload" );

  if( count($files)>0 ):
?>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr align="center">
    <td align="left" colspan="2"><font <?=$stylel?>><b>File Name</b></font></td>
    <td><font <?=$stylel?>><b>File Size</b></font></td>
    <td><font <?=$stylel?>><b>File Type</b></font></td>
  </tr>
  
<?
  foreach( $files AS $file ):
  
    $filename = $filesdir . $UPLOAD->getval("subdir",$_GET['id']) . $file['name'];
    $filesize = (int) round( $file['size'] / 1024 );

	if( @file_exists($path.$filename) ):
	
	  $fileurl = rawurlencode( $filename );
	  $fileurl = str_replace( "%2F", "/", $filename );
?>
  
  <tr align="center">
    <td width="5"><font <?=$stylel?>><a href="<?=$sitepath?><?=$fileurl?>"><img src="<?=$sitepath?>images/file.gif" border="0"></a></font></td>
    <td align="left"><b><font <?=$stylel?>><a href="<?=$sitepath?><?=$fileurl?>"><?=$file['name']?></a></font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><font <?=$stylel?>><?=$filesize?>Kb</font></td>
    <td><font <?=$stylel?>><?=$file['type']?></font></td>
  </tr>
  
<?  else: ?>

  <tr align="center">
    <td width="5"><img src="<?=$sitepath?>images/nofile.gif" border="0"></td>
    <td align="left"><b><s><font <?=$stylel?>><?=$file['name']?></font></s></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><font <?=$stylel?>><?=$filesize?>Kb</font></td>
    <td><font <?=$stylel?>><?=$file['type']?></font></td>
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

<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td colspan="2"><b><font <?=$stylel?>>Upload Information</font></b></td>
  </tr>
  
<?
  // get user name, if any
  if( $UPLOAD->getval("user", $_GET['id']) )
  {
    $userid = $UPLOAD->getval( "user", $_GET['id'] );
    $infoarray['User Name'] = $USER->getval( "name", $userid );
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

  <tr>
    <td width="135">
      <font <?=$stylel?>><u><?=$field?></u>:</font>
    </td>
    <td>
      <font <?=$stylel?>><?=nl2br($value)?></font>
    </td>
  </tr>
  
<? endwhile; ?>

</table>
