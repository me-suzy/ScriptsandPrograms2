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

  
  /////////////////////////////////////////
  // THE USER WANTS TO DELETE THE FIELD
  /////////////////////////////////////////
  
  if( $_POST['action']=="edit" && isset($_POST['delete']) )
  {
      header( "Location: delete.php?type=user&id=" . $_POST['id'] . "&$SID" );
	  exit;
  } 
  
  
  /////////////////////////////////////////
  // THE USER WANTS TO ADD/EDIT A FIELD
  /////////////////////////////////////////
  
  elseif( $_POST['action']=="edit" )
  {
    if( $demomode ) confirm( "No change can be saved on the demo mode" );
  
	if( isset($_POST['add']) )
	  $userid = $USER->addrow();
	else
	  $userid = $_POST['id'];
	
	if( !$_POST['name'] || !$_POST['email'] ) confirm( "Some required fields are missing" );
	
	if( $_POST['pass1'] )
	{
	  if( $_POST['pass1']!=$_POST['pass2'] ) confirm( "The two passwords entered are different" );
	  $USER->setval( md5($_POST['pass1']), "password", $userid );
	}
	
	$USER->setval( $_POST['name'], "name", $userid );
	$USER->setval( $_POST['email'], "email", $userid );

    $USER->savedata();
	  
	confirm( "Changes successfully saved", "access.php" );
  }
  
  
  ///////////////////////////////
  // SEND EMAIL TO USER
  ///////////////////////////////
  
  elseif( $_POST['action']=="email" )
  {
    if( $demomode ) confirm( "No emails can be sent on the demo mode" );
  
	sendemail( $USER->getval("email", $_POST['id']), $CONF->getval("adminemail"), $CONF->getval("adminname"), $_POST['title'], $_POST['content'] );
  
    confirm( "Message successfully sent", "edituser.php?id=" . $_POST['id'] );
  }
  
  
  
  /////////////////////////////////////////
  // START DISPLAYING THE PAGE
  /////////////////////////////////////////
  
  showheader( $section );
	
  // do we want to add a new user or edit an old one?
  $newuser = !isset( $_GET['id'] );
  $userid = $newuser ? 0 : $_GET['id'];

?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="edituser.php">
<? if( !$newuser ): ?><input type="hidden" name="id" value="<?=$userid?>"><? endif; ?>
<input type="hidden" name="action" value="edit">
<? showsession(); ?>
  <tr class="header">
    <td colspan="2">User Properties</td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Full Name</b><br>
      The name of the user, only used for your reference.
	</td>
    <td>
      <input type="text" name="name" size="50" maxlength="50" value="<?=$USER->getval("name",$userid)?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Email Address</b><br>
      Email address of the user. Will be used as a login.
	</td>
    <td>
      <input type="text" name="email" size="50" maxlength="50" value="<?=$USER->getval("email",$userid)?>">
    </td>
  </tr>
  <tr <?=getaltclass()?>>
    <td width="50%" valign="top">
	  <b>Password</b><br>
      Enter the user password twice.
	</td>
    <td>
      <input type="password" name="pass1" size="23" maxlength="30">
	  <input type="password" name="pass2" size="23" maxlength="30">
    </td>
  </tr>
  <tr align="center" class="header">
    <td colspan="2">
<? if($newuser): ?>
      <input type="submit" name="add" value="Add New User">
<? else: ?>
      <input type="submit" name="edit" value="Edit User">
	  <input type="submit" name="delete" value="Delete">
<? endif; ?>
    </td>
  </tr>
</form>
</table>

<?
  if(!$newuser):

    showspace();
?>

<table width="100%" border="0" cellspacing="1" cellpadding="4" class="formtbl">
<form method="post" action="edituser.php">
<input type="hidden" name="action" value="email">
<input type="hidden" name="id" value="<?=$userid?>">
<? showsession(); ?>
  <tr class="header">
    <td colspan="2">Send Email to User</td>
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