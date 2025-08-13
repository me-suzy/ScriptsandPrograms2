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
  if( $_POST['action']=="checkpass" )
  {
    $passhash = md5( $_POST['formpass'] );
  
    if( $CONF->getval("formpass")==$passhash )
	{
	  $formpass = $passhash;
      session_register( "formpass" );

	  // use this for PHP 4.0.6 bug
	  $HTTP_SESSION_VARS['formpass'] = $formpass;

	  include( $path . "form_{$_POST['nextmode']}.php" );
	  exit;
	}
    else
	{
	  echo( "This password is not valid" );
	  exit();
	}
  }
  elseif( $_POST['action']=="checkuser" )
  {
	$passhash = md5( $_POST['userpass'] );
    $user = $USER->queryrows( $_POST['useremail'], "email" );
  
    if( !empty($user) && $user[0]['password']==$passhash )
	{
	  $userid = $user[0]['id'];
	  $userpass = $user[0]['password'];
	
	  session_register( "userid" );
      session_register( "userpass" );

	  // use this for PHP 4.0.6 bug
	  $HTTP_SESSION_VARS['userid'] = $userid;
	  $HTTP_SESSION_VARS['userpass'] = $userpass;
	  
	  // reload info from last upload by same user?
	  if( $CONF->getval("reload_info") )
	  {
	    // search for the latest upload by the same user
		$UPLOAD->sortdata( "uploaded", "desc" );
		$uploads = $UPLOAD->queryrows( $userid, "user" );
	    
		if( !empty($uploads) )
		{
		  // take the first entry (last upload)
		  $upload = $uploads[0];
		  
		  // get all upload infos for that upload
		  $uploadinfos = $UPLOADINFO->queryrows( $upload['id'], "upload" );

		  // make a loop through the fields
		  $fields = $FIELD->get();
		  foreach( $fields AS $field )
		  {
		    reset( $uploadinfos );
			
			foreach( $uploadinfos AS $uploadinfo )
			{
			  // if uploadinfo name is the same as field name, put it in
			  // session variable to remember it while displaying the form
			  if( $uploadinfo['name'] == $field['name'] )
			    $HTTP_SESSION_VARS['f'.$field['id']] = $uploadinfo['value'];
			}
		  }
		  
		  // set email field if not already set
		  $emailfield = "f" . $CONF->getval( "emailfield" );
		  if( !isset($HTTP_SESSION_VARS[$emailfield]) )
		  {
		    $HTTP_SESSION_VARS[$emailfield] = $upload['email'];
		  }
		}
	  }

	  include( $path . "form_{$_POST['nextmode']}.php" );
	  exit;
	}
    else
	{
	  echo( "The email/password doesn't match with an user" );
	  exit();
	}
  }
  
  if( $CONF->getval("formprotect")=="pass" ):
?>

<table border="0" cellspacing="0" cellpadding="2">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="checkpass">
<input type="hidden" name="mode" value="password">
<input type="hidden" name="nextmode" value="<?=$nextmode?>">
<? showsession(); ?>
  <tr>
    <td valign="top" height="27" colspan="3" align="center">
	  <font <?=$stylel?>><?=$_LANG['enter_pass']?></font>
	</td>
  </tr>
  <tr> 
    <td align="right"> 
      <input type="password" name="formpass" size="24" <?=$stylel?>>
    </td>
    <td width="10"></td>
    <td> 
      <input type="submit" name="enter" value=" <?=$_LANG['enter']?> " <?=$stylel?>>
    </td>
  </tr>
  <tr>
	<td valign="bottom" height="25" colspan="3" align="center">
	  <font <?=$styles?>><?=$_LANG['forgot_pass']?> <a href="mailto:<?=$CONF->getval("adminemail")?>"><?=$_LANG['email_admin']?></a></font>
	</td>
  </tr>
</form>
</table>

<? else: ?>

<table border="0" cellspacing="0" cellpadding="2">
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="checkuser">
<input type="hidden" name="mode" value="password">
<input type="hidden" name="nextmode" value="<?=$nextmode?>">
<? showsession(); ?>
  <tr>
    <td valign="top" height="33" colspan="3" align="left">
	  <font <?=$stylel?>><?=$_LANG['enter_user']?></font>
	</td>
  </tr>
  <tr> 
    <td><font <?=$stylel?>><b><?=$_LANG['your_email']?>:</b></font></td>
    <td width="10"></td>
    <td align="right"><input type="text" name="useremail" size="24" <?=$stylel?>></td>
  </tr>
  <tr> 
    <td><font <?=$stylel?>><b><?=$_LANG['your_pass']?>:</b></font></td>
    <td width="10"></td>
    <td align="right"><input type="password" name="userpass" size="24" <?=$stylel?>></td>
  </tr>
  <tr> 
    <td></td>
    <td width="10"></td>
    <td align="right"><input type="submit" name="enter" value=" <?=$_LANG['enter']?> " <?=$stylel?>></td>
  </tr>
  <tr>
	<td valign="bottom" height="25" colspan="3" align="center">
	  <font <?=$styles?>><?=$_LANG['forgot_pass']?> <a href="mailto:<?=$CONF->getval("adminemail")?>"><?=$_LANG['email_admin']?></a></font>
	</td>
  </tr>
</form>
</table>

<? endif; ?>