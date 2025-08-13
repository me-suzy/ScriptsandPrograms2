<?php
//Read in config file
$thisfile = "users_e-mail";
$admin = 1;
include("../includes/config.php");;
include("../includes/hierarchy_lib.php");
include("../includes/admin_email_lib.php");
include("../includes/admin_users_lib.php");
if($submit==$la_button_preview){$e_preview="yes";}
elseif($submit=="Send"){$e_send="yes";totalusers();}
elseif($back=="Back"){delemail($e_id);$e_preview="no";}
else{$e_preview="no";}
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon9-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav3 ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#useremail"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
<?php
	if($sid && $session_get)
		$att_sid="?sid=$sid";
	$nav_names_admin=array($la_title_user_list,$la_title_email);
	$nav_links_admin[$la_title_user_list]="users.php$att_sid";
	$nav_links_admin[$la_title_email]="users_e-mail.php$att_sid";
	if($e_send!="yes"){echo display_admin_nav($la_title_email, $nav_names_admin, $nav_links_admin);}
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666">
      <?php if($e_send=="yes"){echo "Sending ".$tes." emails";}else{echo $la_title_email;}
	if($e_preview=="yes"){echo " $la_button_preview";} ?>
    </td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
<?php  
	if($e_send=="yes"){
		if(!$e_lim){$e_lim=0;}
		$e_send=send_email($e_lim,$e_id);
		if(!$e_send){echo "Email was not send because of an internal error!";}
		else{echo $e_send;}
	}else{	
		$email_toprint="<form method='post' action='users_e-mail.php$att_sid'>
      	  <table width='100%' border='0' cellspacing='0' cellpadding='4'>
      	    <tr bgcolor='#F6F6F6' valign='middle'> 
      	      <td class='text'>";
			  if($e_preview=="yes"){$email_toprint.="$la_to:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$email_to<input type='hidden' name='email_to' value='$email_to'";}
			else{$email_toprint.="$la_to:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<select size=1 name='email_to'>
			<option value='$la_mailing_list'>$la_mailing_list</option>
			<option value='$la_title_pending_users'>$la_title_pending_users</option>
			<option value='$la_all_users'>$la_all_users</option>
			<option value='$la_admin'>$la_admin</option>
			</select>";}
			$email_toprint.="
			  </td>
      	    </tr>

			<tr bgcolor='#DEDEDE' valign='middle'> 
      	      <td class='";
		  	if($e_preview=="yes" && strlen($email_from)<1){$email_toprint=$email_toprint."error";}else{$email_toprint=$email_toprint."text";}
		$email_toprint=$email_toprint."'>".$la_from.":";
		if($e_preview=="yes"){$email_toprint=$email_toprint."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='hidden' name='email_from' value=\"$email_from\">".$email_from;}
		else{$email_toprint=$email_toprint."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='email_from' value=\"$ses_first $ses_last\">";}
		$email_toprint=$email_toprint."
		  </td>
      	    </tr>
			 <tr bgcolor='#F6F6F6' valign='middle'> 
      	      <td class='";
		if($e_preview=="yes" && strlen($email_reply)<1){$email_toprint=$email_toprint."error";}else{$email_toprint=$email_toprint."text";}
		$email_toprint=$email_toprint."'>".$la_reply_to.":";
		if($e_preview=="yes"){$email_toprint=$email_toprint."&nbsp;&nbsp;&nbsp;<input type='hidden' name='email_reply' value=\"$email_reply\">".$email_reply;}
		else{$email_toprint=$email_toprint."&nbsp;&nbsp;&nbsp;<input type='text' name='email_reply' value=\"$ses_email\">";}
		$email_toprint=$email_toprint."
			</td>
      	    </tr>
      	    <tr bgcolor='#DEDEDE' valign='middle'> 
      	      <td class='";
		if($e_preview=="yes" && strlen($email_subject)<1){$email_toprint=$email_toprint."error";}else{$email_toprint=$email_toprint."text";}
		$email_toprint=$email_toprint."'>".$la_subject;
		if($e_preview=="yes")
		{
			$email_toprint=$email_toprint."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$email_subject;
			$email_toprint=$email_toprint."<input type='hidden' name='email_subject' value='$email_subject'>";
		}
		else{
			$email_toprint=$email_toprint."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='email_subject' value='$email_subject'>";
		}
		$email_toprint=$email_toprint."
      	      </td>
      	    </tr>
      	    <tr bgcolor='#F6F6F6' valign='middle'> 
      	      <td class='";
		if($e_preview=="yes" && strlen($email_body)<1){$email_toprint=$email_toprint."error";}else{$email_toprint=$email_toprint."text";}
		$email_toprint=$email_toprint."'> <p>".$la_body."</p><p>";
		if($e_preview=="yes")
		{
			$email_body_temp=ereg_replace("<","&lt;",$email_body);
			$email_body_temp=ereg_replace(">","&gt;",$email_body_temp);
			$email_body_temp=ereg_replace("\"","&quot;",$email_body_temp);
			$email_toprint=$email_toprint."&nbsp;&nbsp;<pre>".nl2br ($email_body_temp)."</pre>";
			$email_toprint=$email_toprint."<input type='hidden' name='email_body' value='$email_body'>";
		}
      	else{
			$email_toprint=$email_toprint."<textarea name='email_body' rows='10' cols='40'>$email_body</textarea>";
		}
      	$email_toprint=$email_toprint."</p>    
      	      </td>
      	    </tr>
      	  </table>
      	  <input type='submit' name='submit' value='";
		if(strlen($email_subject)>0 && $e_preview=="yes" && strlen($email_body)>0 && strlen($email_from)>0 && strlen($email_reply)>0){
			$u=create_email($email_body,$email_subject,$email_from, $email_reply, $email_to);
			$email_toprint=$email_toprint."Send' class='button'><input type='submit' name='back' value='Back' class='button'>";
			$email_toprint=$email_toprint."<input type='hidden' name='e_id' value='".$u."'>";	
		}
		elseif($e_preview=="yes"){$email_toprint=$email_toprint."Back' class='button' onClick=\"history.back();\">";}
		else{$email_toprint=$email_toprint.$la_button_preview."' class='button'><input type='submit' name='cancel' value='".$la_button_cancel."' class='button' onClick=\"history.back();\">";}
		$email_toprint=$email_toprint."<br>
     		 </form>";
		echo $email_toprint;
	}
?>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
<?php 
if(strlen($email_redirect)>0){echo $email_redirect;}
?> 
</body>
</html>

