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
  $section = "access";
  include( "initialize.php" );
  
  checklogged();


  ///////////////////////////////////////////
  // THE USER WANTS TO SAVE THE SETTINGS
  ///////////////////////////////////////////
  
  if( $_POST['action']=="save" )
  {
    if( $demomode ) confirm( "No change can be saved on the demo mode" );
  
    // user authentification has been disabled
    if( $CONF->getval("formprotect")=="user" && $_POST['formprotect']!="user" )
	{
	  // change subdir if it used an user field
	  if( $CONF->getval("subdir")=="user" )
	    $CONF->setval( "date", "subdir" );
	}
  
    $CONF->setval( $_POST['formprotect'], "formprotect" );
	$CONF->setval( $_POST['formpass'], "formpass" );
	$CONF->setval( $_POST['takeip'], "takeip" );
	$CONF->setval( $_POST['banned_ips'], "banned_ips" );
  
	$CONF->savedata();
	
	confirm( "Changes successfully saved", "access.php" );
  }
  
  ///////////////////////////////////////////
  // THE USER WANTS TO ADD A USER
  ///////////////////////////////////////////
  
  elseif( isset($_POST['add']) )
  {
	header( "Location: edituser.php?$SID" );
  }
  
  
  ///////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ///////////////////////////////////////////
  
  showheader( $section );
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="access.php">
<input type="hidden" name="action" value="save">
<? showsession(); ?>
  <tr class="header">
    <td colspan="2">Upload Form Access</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Protection Method</b><br>
      Select the degree of protection of the upload form. The user management is only activated if the second option is selected.
	</td>
    <td>
	  <input type="radio" name="formprotect" value="none" <? if($CONF->getval("formprotect")=="none") echo("checked"); ?>> Allows everyone to access the upload form<br>
	  <input type="radio" name="formprotect" value="user" <? if($CONF->getval("formprotect")=="user") echo("checked"); ?>> Requires to login under a valid user account<br>
	  <input type="radio" name="formprotect" value="pass" <? if($CONF->getval("formprotect")=="pass") echo("checked"); ?>> Requires everyone to enter this password:<br>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="password" name="formpass" size="15"> <input type="password" name="formpass" size="15">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>IP/host Recording</b><br>
      Do you want ezUpload to record anything?
	</td>
    <td>
	  <input type="radio" name="takeip" value="0" <? if($CONF->getval("takeip")=="0") echo("checked"); ?>> Nothing
      <input type="radio" name="takeip" value="1" <? if($CONF->getval("takeip")=="1") echo("checked"); ?>> IP address
	  <input type="radio" name="takeip" value="2" <? if($CONF->getval("takeip")=="2") echo("checked"); ?>> Host (Resolved IP)
	</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Banned IPs</b><br>
      Enter one IP (complete) or host (partial or complete) per line. To detect hosts, the option "Host" must be selected above.
	</td>
    <td>
	  <textarea name="banned_ips" cols="49" rows="4"><?=$CONF->getval("banned_ips")?></textarea>
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
<form method="post" action="access.php">
<? showsession(); ?>
  <tr align="center" class="header">
    <td>User Name</td>
    <td>Email Address</td>
	<td>Num. Uploads</td>
    <td width="120">Action</td>
  </tr>
  
<?
  $USER->sortdata( "name", "asc" );

  $userarray = $USER->get();
  
  if( count($userarray)==0 ):
?>
  
  <tr align="left" <?=getaltclass()?>>
	<td colspan="4">No users have been defined</td>
  </tr>
  
<?
  else:

    foreach( $userarray AS $user ):
	
	  $numuploads = $UPLOAD->getnumrows( $user['id'], "user" );
	  
	  if( $numuploads==0 )
	    $numuploads = "0 Uploads";
	  else
	    $numuploads = "<a href='browser.php?user={$user['id']}&$SID'>$numuploads Uploads</a>";
?>
  
  <tr align="center" <?=getaltclass()?>>
	<td><?=$user['name']?></td>
    <td><a href="mailto:<?=$user['email']?>"><?=$user['email']?></a></td>
	<td><?=$numuploads?></td>
    <td width="120"><b><a href="edituser.php?id=<?=$user['id']?>&<?=$SID?>">Edit</a></b> | <b><a href="delete.php?type=user&id=<?=$user['id']?>&<?=$SID?>">Delete</a></b></td>
  </tr>
  
<?
    endforeach; 

  endif;
?>
  
  <tr align="center" class="header">
    <td colspan="5">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
		  <td align="right">
      		<input type="submit" name="add" value="Add New User">
		  </td>
		</tr>
	  </table>
    </td>
  </tr>
</form>
</table>
			
<? showfooter($section); ?>