<?php
	$dirpath = "$Config_rootdir"."../../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

      if ( !$ucook->LoggedIn() )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
          exit;
      }

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid' && admin !='0'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
	 if($Config_makelogs == "1")
	 $csr->MakeAdminLogs( $uid, "Denid Access to the Admin Panel :: $SCRIPT_NAME", "2");

       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	mysql_free_result( $result );	

	if($dowhat != "send_conf" && $dowhat != "publdel")
      { 
		$usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusNotify"); 
	      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/notify.gif>&nbsp;</div><br>"); }

if($dowhat == "show")
{   
	if(!$catog)
	{
?>

<table width="55%" border="0" cellspacing="0" cellpadding="4" class=tn align=center>
    <tr> 
      <td colspan=2> 
        <br>
      </td>
    </tr>
    <tr> 
      <td colspan=2 class=ts> 
        <div align="center"><?php echo $strAdminNotifyCmt1 ?></div>
      </td>
    </tr>
    <tr> 
      <td bgcolor="#DDDDDD" width="62%"><b><?php echo $strAMenusAllUsr ?></b></td>
      <td bgcolor="#DDDDDD" width="38%"> 
        <div align="right"><span class=ts>[<a href="notify.php?dowhat=show&amp;catog=all"><?php echo $strSelect ?></a>] 
          [<a href="notify.php?dowhat=send&amp;catog=all"><?php echo $strAdminNotifyOpt1 ?></a>]&nbsp;</span></div>
      </td>
    </tr>
    <tr> 
      <td width="62%" bgcolor="#EEEEEE"><b><?php echo $strAdminNotifyOpt2 ?></b></td>
      <td width="38%" bgcolor="#EEEEEE"> 
        <div align="right"><span class=ts>[<a href="notify.php?dowhat=show&amp;catog=unact"><?php echo $strSelect ?></a>] 
          [<a href="notify.php?dowhat=send&amp;catog=unact"><?php echo $strAdminNotifyOpt1 ?></a>]&nbsp;</span></div>
      </td>
    </tr>
    <tr> 
      <td bgcolor="#DDDDDD" width="62%"><b><?php echo $strAdminNotifyOpt3 ?></b></td>
      <td bgcolor="#DDDDDD" width="38%"> 
        <div align="right"><span class=ts>[<a href="notify.php?dowhat=show&amp;catog=blocked"><?php echo $strSelect ?></a>] 
          [<a href="notify.php?dowhat=send&amp;catog=blocked"><?php echo $strAdminNotifyOpt1 ?></a>]&nbsp;</span></div>
      </td>
    </tr>
    <tr> 
      <td width="62%" bgcolor="#EEEEEE"><b><?php echo $strAdminNotifyOpt4 ?></b></td>
      <td width="38%" bgcolor="#EEEEEE"> 
        <div align="right"><span class=ts>[<a href="notify.php?dowhat=show&amp;catog=oact"><?php echo $strSelect ?></a>] 
          [<a href="notify.php?dowhat=send&amp;catog=oact"><?php echo $strAdminNotifyOpt1 ?></a>]&nbsp;</span></div>
      </td>
    </tr>
    <tr> 
      <td width="62%" bgcolor="#DDDDDD"><b><?php echo $strAdminNotifyOpt5 ?></b></td>
      <td width="38%" bgcolor="#DDDDDD"> 
        <div align="right"><span class=ts>[<a href="notify.php?dowhat=show&amp;catog=publist"><?php echo $strSelect ?></a>] 
          [<a href="notify.php?dowhat=send&amp;catog=publist"><?php echo $strAdminNotifyOpt1 ?></a>]&nbsp;</span></div>
      </td>
    </tr>
    <tr> 
      <td colspan=2> 
        <br>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan=2 class=ts><?php echo ("<b>$strNote</b>: $strAdminNotifyCmt2"); ?></td>
    </tr>
  </table>
<?php
}

else if($catog != "publist")
{
	if($catog == "unact")
	$whereinfo = " WHERE status='0'";
	else if($catog == "oact")
	$whereinfo = " WHERE status='1'";
	else if($catog == "blocked")
	$whereinfo = " WHERE status='2'";

	
 	$result = queryDB( "SELECT * FROM $tbl_userinfo $whereinfo ORDER BY uid, uname" );
	$nr = mysql_num_rows( $result );

	if($nr == 0)
	{
	    $errMsg = "<b>$strAlbumCrErr24</b>\n";
	    $usr->errMessage( $errMsg, '' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
	}
?>

<script language="Javascript">
<!--
function SetChecked(val) 
{
	dml=document.userList;
	len = dml.elements.length;

	var i=0;

	for( i=0 ; i<len ; i++) 
	{
		if (dml.elements[i].name=='mailuser[]') 
		{
			dml.elements[i].checked=val;
		}
	}
}

//-->
</script>

<form action=notify.php method=post name="userList">
<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td colspan=4><br><div align=center class=ts><a href=notify.php?dowhat=show>&lt;&lt; back</a> :: <?php echo $nr ?> users in list :: <a href=javascript:SetChecked(1)><?php echo $strAdminNotifyOpt6 ?></a> ~ <a href=javascript:SetChecked(0)><?php echo $strAdminNotifyOpt7 ?></a></div><br></td>
  </tr>
  <tr class="tn"> 
    <td>&nbsp;</td>
<?php echo("
    <td><b>$strRegisterName4</b></td>
    <td><b>$strName</b></td>
    <td><b>$strEmail</b></td>"); ?>
  </tr>

<?php
$i = 0;

while($row = mysql_fetch_array( $result ))
{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }
?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>"> 
    <td><input type="checkbox" name="mailuser[]" value="<?php echo $row[uid] ?>"></td>
    <td><a href="usrmngt.php?dowhat=show&username=<?php echo $row[uid] ?>" class=noundertn><?php echo $row[uid] ?></a></td>
    <td><?php echo $row[uname] ?></td>
    <td><?php echo $row[email] ?></td>
  </tr>

<?php

}
?>

<tr>
<td colspan=2 class=ts><a href=javascript:SetChecked(1)>Select all</a> ~ <a href=javascript:SetChecked(0)><?php echo $strAdminNotifyOpt7 ?></a></td>
<td colspan=2 class=ts align=right>
<input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/send.gif" width="53" height="19" border="0">
</td>
</tr>
</table>
<input type=hidden name="dowhat" value="send">
<input type=hidden name="sendinfo" value="users">
<input type=hidden name="catog" value="<?php echo $catog ?>">
</form>

<p>&nbsp;</p>

<?php
}

else if($catog == "publist")
{
 	$result = queryDB( "SELECT * FROM $tbl_publist" );
	$nr = mysql_num_rows( $result );

	if($nr == 0)
	{
	    $errMsg = "<b>$strAlbumCrErr24</b>\n";
	    $usr->errMessage( $errMsg, '' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
	}
?>

<script language="Javascript">
<!--
function SetChecked(val) 
{
	dml=document.userList;
	len = dml.elements.length;

	var i=0;

	for( i=0 ; i<len ; i++) 
	{
		if (dml.elements[i].name=='mailuser[]') 
		{
			dml.elements[i].checked=val;
		}
	}
}

//-->
</script>

<form action=notify.php method=post name="userList">
<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td colspan=4>
<?php echo ("<br><div align=center class=ts><a href=notify.php?dowhat=show>&lt;&lt; 
$strBack</a> :: $nr $strUser$strPuralS :: <a href=javascript:SetChecked(1)>$strAdminNotifyOpt6</a> ~ <a href=javascript:SetChecked(0)>$strAdminNotifyOpt7</a></div><br>"); ?>
   </td>
  </tr>
  <tr class="tn"> 
    <td>&nbsp;</td>
<?php echo("
    <td><b>$strName</b></td>
    <td><b>$strEmail</b></td>
    <td><b>$strAdminNotifyOpt8</b></td>"); ?>
  </tr>

<?php
$i = 0;

while($row = mysql_fetch_array( $result ))
{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }
?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>"> 
    <td><input type="checkbox" name="mailuser[]" value="<?php echo $row[email] ?>"></td>
    <td><?php echo $row[name] ?></td>
    <td><?php echo $row[email] ?></td>
    <td>
<?php if($row[userval] != "system")
{ echo("<a href=\"usrmngt.php?dowhat=show&username=$row[userval]\" class=noundertn>$row[userval]</a>"); } ?>
</td>
  </tr>

<?php

}
?>

<tr>
<td colspan=2 class=ts><a href=javascript:SetChecked(1)><?php echo $strAdminNotifyOpt6 ?></a> ~ <a href=javascript:SetChecked(0)><?php echo $strAdminNotifyOpt7 ?></a></td>
<td colspan=2 class=ts align=right>
<?php
echo ("<input type=\"submit\" name=\"submit\" value=\"$strDelete\" class=butfieldc>&nbsp;<input type=\"submit\" name=\"submit\" value=\"$strSend &gt;\" class=butfieldb>");
?>
</td>
</tr>
</table>
<input type=hidden name="dowhat" value="send">
<input type=hidden name="sendinfo" value="users">

<input type=hidden name="catog" value="publist">
</form>

<p>&nbsp;</p>

<?php
}

	else
	{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

	    $errMsg = "<b>$strInvalid catog state</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
	}
}



else if($dowhat == "send")
{
	if($sendinfo == "users")
	{
		$i = 0;
		while($mailuser[$i])
		{
			$i++;
		}

	if($i == 0)
	{
	    $errMsg = "<b>$strNo $strRecipient</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
	}
	}

	if($catog == "publist" && $submit == "delete")
	{ 

		$i = 0;
		while($mailuser[$i])
		{
			$uservals .= "mailuser[]=$mailuser[$i]&";
			$i++;
		}

	    $errMsg = "<b>".$csr->LangConvert($strDelConfirm, "$strFrom $strAdminNotifyOpt5")."</b> <a href=\"notify.php?dowhat=publdel&{$uservals}catog=publist\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
	}

	
	if($catog == "publist")
 	$result = queryDB( "SELECT * FROM $tbl_publist" );
	else 
	{
	if($catog == "unact")
	$whereinfo = " WHERE status='0'";
	else if($catog == "oact")
	$whereinfo = " WHERE status='1'";
	else if($catog == "blocked")
	$whereinfo = " WHERE status='2'";

 	$result = queryDB( "SELECT * FROM $tbl_userinfo $whereinfo ORDER BY uid, uname" );
	}

	$nr = mysql_num_rows( $result );

	if($nr == 0)
	{
	    $errMsg = "<b>$strAlbumCrErr24</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
	}
	
 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
	$row_user = mysql_fetch_array( $result_user );

?>

<form method=post action=notify.php>
<table width="80%" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr> 
  <td class=tn colspan=8><b><?php echo $strFrom ?>:</b></td>
  </tr>
   <tr class=tn bgcolor=#eeeeee>
    <td height="2" width="5"> 
      <p align="center" class="tn"> 
        <input type="radio" name="sendby" value="1" checked>
      </p>
    </td>
    <td height="2"><?php echo $Config_sitename ?><br>
      &lt;<?php echo $Config_adminmail ?>&gt;<br>
    </td>
    <td height="2">&nbsp;</td>
    <td height="2" width="5"> 
      <input type="radio" name="sendby" value="2">
    </td>
    <td height="2"><?php echo $row_user[uname] ?><br>
      &lt;<?php echo $row_user[email] ?>&gt;<br>
    </td>
    <td height="2">&nbsp;</td>
    <td height="2" bgcolor="#eeeeee" width="5"> 
      <input type="radio" name="sendby" value="3">
    </td>
    <td height="2"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tn"><?php echo $strName ?>: 
            <input type="text" name="send_name" size="10" maxlength="50">
          </td>
        </tr>
        <tr>
          <td class="tn"><?php echo $strEmail ?>: 
            <input type="text" name="send_email" size="10" maxlength="100">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<p>&nbsp;</p>

<table width="75%" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr class="tn"> 
    <td height="2" width="205"> 
      <p align="center" class="tn"><b><?php echo $strTo ?></b></p>
    </td>
    <td height="2" width="539">
<?php 
	if($sfrom == "usrmngt" && !$mailuser[1])
	{
	 	$result = queryDB( "SELECT uname FROM $tbl_userinfo WHERE uid='$mailuser[0]'" );	
		$row_name = mysql_fetch_array($result);
		echo("<b>$row_name[uname] ($mailuser[0])</b>");
	}
	else
		echo($strAdminNotifyOpt9);
?>
</td>
  </tr>
  <tr class="tn"> 
    <td height="2" width="205"> 
      <div align="center"><b><?php echo $strSubject ?></b></div>
    </td>
    <td height="2" width="539"> 
      <input type="text" name="subject" size="48" maxlength="100">
    </td>
  </tr>
  <tr class="tn"> 
    <td height="2"> 
      <div align="center"><b><?php echo $strMessage ?></b><?php if($catog != "publist") echo("<br><span class=ts><br>Albi Codes<br><br>[username]<br>[fullname]<br>[uemail]<br>[spacelimit]<br></span>"); ?></div>
    </td>
    <td height="2"> 
      <textarea name="notify_msg" rows="10" cols="42"></textarea>
    </td>
  </tr>
  <tr class="tn"> 
    <td height="2"> 
      <div align="center"><b><?php echo $strAdminNotifyOpt10 ?></b></div>
    </td>
    <td height="2"> 
      <textarea name="msg_footer" rows="10" cols="42"><?php echo $Config_msgfooter ?></textarea>
    </td>
  </tr>
  <tr class="tn">
    <td height="2"><input type=hidden name=sfrom value=<?php echo $sfrom ?>>&nbsp;</td>
    <td height="2"><input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/send.gif" width="53" height="19" border="0">
</td>
  </tr>
</table>

<?php

if($sendinfo == "users")
{
$i = 0;

	while($mailuser[$i])
	{
	echo("<input type=hidden name=mailuser[] value=\"$mailuser[$i]\">\n");
	$i++;	
	}

echo("<input type=hidden name=sendinfo value=\"$sendinfo\">\n");
}
?>

<input type=hidden name=catog value="<?php echo $catog ?>">
<input type=hidden name=dowhat value="send_conf">

</form>
<p>&nbsp;</p>

<?php

}

else if($dowhat == "publdel")
{
$i = 0;

	while($mailuser[$i])
	{
	    $result = queryDB( "DELETE FROM $tbl_publist WHERE email='$mailuser[$i]'"); 
	    $i++;
	}
    
          if($Config_makelogs == "1")
          $csr->MakeAdminLogs( $uid, "Deleted public list emails", "2"); 

	    $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusNotify", '1', "notify.php?dowhat=show&catog=publist");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/notify.gif>&nbsp;</div><br>");
	    $errMsg = "<b>emails Deleted, now redirecting...</b><br>else <a href=\"notify.php?dowhat=show&catog=publist\">click here</a>\n";
	    $usr->errMessage( $errMsg, 'Success', 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "send_conf")
{

if($sendby == "1")
{
$name = "$Config_sitename";
$email = "$Config_adminmail";
}

else if($sendby == "2")
{
$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
$row_user = mysql_fetch_array( $result_user );

$name = "$row_user[uname]";
$email = "$row_user[email]";
}

else if($sendby == "3")
{
$name = "$send_name";
$email = "$send_email";
}

$sendmessage = "$notify_msg\n$msg_footer";

if($sendby == "3")
{
if(!$send_name)
$err .= "<B>$strAdminNotifyCmt3</B><BR>";
if(!CheckEmail($send_email) || !$send_email)
$err .= "<B>$strAdminNotifyCmt4</B><BR>";
}


if(!$subject)
$err .= "<B>$strNo $strSubject</B><BR>";
if(!$notify_msg)
$err .= "<B>$strNo $strMessage</B><BR>";

if($err)
{
	$usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusNotify");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/notify.gif>&nbsp;</div><br>");
	$errMsg = "$err\n";
	$usr->errMessage( $errMsg, $strError, 'error', '70' );
	echo("<BR>");
      $usr->Footer();
	exit;
}

$sendmessage = stripslashes($sendmessage);

if($sendinfo == "users")
{
	$i = 0;
	while($mailuser[$i])
	{
	if($catog == "publist")
	{ $result = queryDB( "SELECT * FROM $tbl_publist WHERE email = '$mailuser[$i]'" );
	  $row    = mysql_fetch_array( $result ); 
	  $mail_send_name  = $row[name];
	  $mail_send_email = $row[email]; 
	  $userid     = $row[uid];
	  $spacelimit = $row[slimit];
	}
	else
	{ $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$mailuser[$i]'" );
	  $row    = mysql_fetch_array( $result );
	  $mail_send_name  = $row[uname];
	  $mail_send_email = $row[email];
	  $userid     = $row[uid];
	  $spacelimit = $row[slimit];
	 }

	  $sendmessage_parse = $sendmessage;
	  if($catog != "publist")
	  $sendmessage_parse = albicodes($sendmessage, $userid, $mail_send_name, $mail_send_email, $spacelimit);

	  $mailheader = "From: $name <$email>\nX-Mailer: $subject\nContent-Type: text/plain";
	  mail("$mail_send_email","$subject","$sendmessage_parse","$mailheader");

	$i++;
	}
}

else
{
	$i = 0;

	if($catog == "publist")
	$result = queryDB( "SELECT * FROM $tbl_publist" );

	else
	{
		if($catog == "unact")
		$whereinfo = " WHERE status='0'";
		else if($catog == "oact")
		$whereinfo = " WHERE status='1'";
		else if($catog == "blocked")
		$whereinfo = " WHERE status='2'";

	$result = queryDB( "SELECT * FROM $tbl_userinfo $whereinfo ORDER BY uid, uname" );
	}

	while($row = mysql_fetch_array( $result ))
	{

	if($catog == "publist")
	{ $mail_send_name = $row[name];
	  $userid = $row[uid];
	  $spacelimit = $row[slimit];
	  $mail_send_email = $row[email]; }

	else
	{ $mail_send_name = $row[uname];
	  $userid = $row[uid];
	  $spacelimit = $row[slimit];
	  $mail_send_email = $row[email]; }

	$sendmessage_parse = $sendmessage;
	if($catog != "publist")
	$sendmessage_parse = albicodes($sendmessage, $userid, $mail_send_name, $mail_send_email, $spacelimit);

	$mailheader = "From: $name <$email>\nX-Mailer: $subject\nContent-Type: text/plain";
	mail("$mail_send_email","$subject","$sendmessage_parse","$mailheader");

	$i++;
	}
}

	if($Config_makelogs == "1")
	$csr->MakeAdminLogs( $uid, "Mail sent to the $sendinfo $catog", "2");

	if($sfrom == "usrmngt")
	$redirect_url = "usrmngt.php?dowhat=show&username=$mailuser[0]";
	else
	$redirect_url = "notify.php?dowhat=show";

	$usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusNotify", '20', "$redirect_url");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/notify.gif>&nbsp;</div><br>");
	$errMsg = "<b>$i $strAdminNotifyCmt5</b><br><a href=\"$redirect_url\">$strClickhere</a>\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	echo("<BR>");
      $usr->Footer();
	exit;

}

else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );

	    echo("<BR>");
}

$usr->Footer(); 


function albicodes($msg, $op1, $op2, $op3, $op4)
{
	  $codes = array ( "/\[username\]/", "/\[fullname\]/", "/\[uemail\]/", "/\[spacelimit\]/" );
	  $rep   = array ( "$op1", "$op2", "$op3", "$op4" );
	  $rMsg = preg_replace( $codes, $rep, $msg );
	  return( $rMsg );
}

?>