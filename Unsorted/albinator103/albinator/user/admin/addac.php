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
	 { $csr->MakeAdminLogs( $uid, "Denid Access to the Admin Panel :: $SCRIPT_NAME", "2"); }

       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	mysql_free_result( $result );	


if($dowhat == "add")
{
$username = strtolower($username);
$username = rtrim($username);

#username check
if(in_array($username, $Config_bad_user_name))
$errDisp .="<b>$strRegisterError7</b><br>";

else if (!preg_match ("/^([a-z]|[0-9])*$/", $username))
$errDisp .="<b>$strRegisterError7b</b><br>";

$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$username'" );
$nr = mysql_num_rows( $result );

if($nr || !$username)
$errDisp .="<b>$strRegisterError7d</b><br>";
mysql_free_result($result);

$usr_len = strlen($username);

if($usr_len < 4 || $usr_len > 15)
$errDisp .="<b>$strRegisterError7c</b><br>";

# email check
$result = CheckEmail($email_id);
if(!$result || !$email_id)
$errDisp .="<b>$strRegisterError2b</b><br>";

$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$email_id'" );
$nr = mysql_num_rows( $result );

if($nr)
$errDisp .="<b>$strRegisterError6</b><br>";
mysql_free_result($result);

if($password != $repassword)
$errDisp .="<b>$strRegisterError4</b><br>";

$lenpass = strlen($password);
if($lenpass < 6 || $lenpass > 15)
$errDisp .="<b>$strRegisterError5b</b><br>";

if(!$uname)
$errDisp .="<b>$strRegisterError1</b><br>";

$lenname = strlen($uname);
if($lenname < 5)
$errDisp .="<b>$strRegisterError1b</b><br>";

if(!$country)
$errDisp .="<b>$strRegisterError3</b><br>";

if(!$errDisp)
$done = 1;

closeDB();
}

if($done != 1)
{

$usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAddAccount", '', '', "onload");
echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/addac.gif>&nbsp;</div><br>");

if($errDisp)
{ 
	      $usr->errMessage( $errDisp, $strError, 'error', '70');
}
?>

<script language="JavaScript">
<!--
function disWin() {
        disWin=window.open("","disWin","status=no,resize=no,toolbar=no,scrollbars=no,width=430,height=40,maximize=no");
}
// -->
</script>
<p>&nbsp;</p>
<form method=post action=addac.php name="Register"><table width="75%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#003366">
    <tr> 
      <td colspan=2>
        <div align="right"><img src="<? echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/registration.gif" width="400" height="32"></div>
      </td>
    </tr>
    <tr> 
      <td colspan=2> 
        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
                <tr bgcolor="#dddddd"> 
                  <td width="29%"> 
                    <div align="right" class=tn><? echo $strRegisterName4 ?>&nbsp;</div>
                  </td>
                  <td width="71%"> 
                    <input type="text" name="username" maxlength=15 value="<? echo $username ?>">
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td bgcolor="#CCCCCC" width="29%"> 
                    <div align="right" class=tn><? echo $strPassword ?>&nbsp;</div>
                  </td>
                  <td width="71%"> 
                    <input type="password" name="password" maxlength=15> <a class=ts href="randpass.php" onclick="disWin()" target=disWin>random pass</a>

                  </td>
                </tr>
                <tr bgcolor="#dddddd"> 
                  <td width="29%"> 
                    <div align="right" class=tn><? echo $strSettingsName7b ?>&nbsp;</div>
                  </td>
                  <td width="71%" class=tn>
                    <input type="password" name="repassword" maxlength=15>
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%"> 
                    <div align="right" class=tn><? echo $strName ?>&nbsp;</div>
                  </td>
                  <td width="71%"> 
                    <input type="text" name="uname" value="<? echo $uname ?>" maxlength="25">
                  </td>
                </tr>
                <tr bgcolor="#dddddd"> 
                  <td width="29%"> 
                    <div align="right" class=tn><? echo $strEmail ?>&nbsp;</div>
                  </td>
                  <td width="71%" class=tn> 
                    <input type="text" name="email_id" value="<? echo $email_id ?>">
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%"> 
                    <div align="right" class=tn><? echo $strRegisterName3 ?>&nbsp;</div>
                  </td>
                  <td width="71%">
                    <select name="country">
                      <option value="" selected>--- select ---</option>
			    <? echo($strCountryList) ?>
                    </select>			 
			  <input type="hidden" name="dowhat" value="add">
                    </td>
                </tr>
<?
if($Config_langCodeForce != "1")
{
$nextRowColor = "#CCCCCC";
echo("<tr bgcolor=\"#dddddd\"> 
                  <td width=\"29%\"> 
                    <div align=\"right\" class=tn>$strRegisterName6&nbsp;</div>
                  </td>
                  <td width=\"71%\">
				<select name=\"New_langCode\">");

	if(!$New_langCode)
	$New_langCode = $Config_AdminLangLoad;

	$i = 0;
	$sel = '';

	while($Config_list_langCode[$i])
	{ 
	  list($langCode, $langName) = split('[|]', $Config_list_langCode[$i]);

	  if($langCode == $New_langCode)
	  $sel = 'selected';
	  else
	  $sel = '';

	  echo ("<option value=\"$langCode\" $sel>$langName</option>\n");
	  $i++;
	}

echo("</select>
         </td>
       </tr>");
}

else
$nextRowColor = '#DDDDDD';

?>
                <tr bgcolor="<? echo $nextRowColor ?>"> 
                  <td width="29%"> 
                    <div align="right" class=tn><? echo $strRegisterName5 ?>&nbsp;</div>
                  </td>
                  <td width="71%" class=tn>
                    <input type="radio" name="status" value="1"><? echo ("$strMail $strDetails"); ?> <input type="radio" name="status" value="2"> <? echo $strRegisterName5b ?> <input type="radio" name="status" value="3" checked> <? echo $strNone ?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr bgcolor="#003366"> 
      <td colspan=2> 
          
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr> 
            <td width="29%"> 
              <div align="right">&nbsp;</div>
            </td>
            <td width="71%">&nbsp;<input type="image" name="submit" src="<? echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/register.gif" width="53" height="19" align="bottom" border="0"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<script>
<!--
function openwin()
{
terms=window.open("","terms","status=no,resize=no,toolbar=no,scrollbars=yes,width=500,height=360,maximize=no");
}
//-->
</script>
<?

$usr->footer();

}

else
{
	$add_date = "0";

	$username = strtolower($username);
	$username = strip_tags($username);

	$uname = strip_tags($uname);

	$code_conf = md5(uniqid ($Config_p));
	$code_conf = substr($code_conf, 0, 10);

	$adddate = $now_date;

	$encpass = md5($password);
	$uname = ucwords($uname);

      $LastTimeDate = date ("l dS of F Y h:i:s A");
      $lastinfo = "Admin Make $LastTimeDate";

	if($status == "2")
	{ $result = queryDB( "INSERT INTO $tbl_userwait VALUES('$username', '$code_conf', '$adddate')" ); $uwait = "0"; $add_date_conf = 0; }
	else
	{ $uwait = "1"; $add_date = date("Ymd"); $add_date_conf = $now_date; }

	if(!$New_langCode)
	$New_langCode = $Config_AdminLangLoad;

	if($Config_default_uvalid != '0')
	{
		$lastdate  = mktime (0,0,0,date("m"),date("d")+$Config_default_uvalid,date("Y"));
		$curdate = strftime ("%Y%m%d", $lastdate);

		$user_validity = $curdate;
	}
	else
	$user_validity = '0';

	$result = queryDB( "INSERT INTO $tbl_userinfo VALUES('$username', '$encpass', '$uname', '$email_id', '$country', '0', '$lastinfo', '0', '$uwait', '$Config_dprefs', '0*0|', '$add_date_conf', '$Config_default_space|$Config_default_album|$Config_default_photo|$Config_default_remind', '0', '0', '$New_langCode', '$user_validity', '0' )" );

	if($status == "2" || $status == "1")
	{
	$name = "$Config_sitename";
	$email = "$Config_adminmail";
	$recnameto = $uname;
	$recemailto = $email_id;
	$Config_sitename_url = "$Config_mainurl";
	$Config_sitename_url_code = "$Config_mainurl/confirm.php?uid=$username&code=$code_conf";
	$subject = $strRegisterMail1;
	$putmsg = "\n$Config_site_msg";

	if($status == "2")
	$premessage = $csr->LangConvert($strRegisterMail2, $Config_sitename, $Config_sitename_url_code, $Config_unact_days, $putmsg);

	else
	$premessage = $csr->LangConvert($strRegisterMail3, $Config_sitename, $username, $password, $Config_mainurl, $putmsg);

	$endmessage = "$Config_msgfooter";
	$sendmessage = "$premessage $endmessage";

	$mailheader = "From: $name <$email>\nX-Mailer: $strRegisterMail1\nContent-Type: text/plain";
	mail("$recemailto","$subject","$sendmessage","$mailheader");
	}

if($status == "1" || $status == "3")
mkdir("$dirpath"."$Config_datapath/$username", 0777);

$usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAddAccount");
echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/addac.gif>&nbsp;</div><br>");
echo("<br>");
$errMsg = "<br><b>$strRegisterName7, <a href=addac.php>$strAdd $strMore</a></b>\n";
$usr->errMessage( $errMsg, $strSuccess, 'tick', '85' );
echo("<br>");
$usr->footer();
}

?>