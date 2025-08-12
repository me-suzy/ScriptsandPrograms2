<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$csr = new ComFunc();
	$showerr = 0;

	#remove unactivated accounts
	$lastdate  = mktime (0,0,0,date("m"),date("d")-$Config_unact_days,date("Y"));
	$curdate = strftime ("%Y%m%d", $lastdate);
	$ik = 0;	
	
	$result_unact = queryDB( "SELECT * FROM $tbl_userwait WHERE adddate < $curdate" );
	while ($row = mysql_fetch_array( $result_unact ))
	{
	  $result_conf = queryDB( "SELECT email, uname FROM $tbl_userinfo WHERE uid = '$row[uid]'" );   
	  $row_conf = mysql_fetch_array( $result_conf );

	  $result_pub = queryDB( "SELECT * FROM $tbl_publist WHERE email = '$row_conf[email]'" );
	  $row_pub = mysql_num_rows( $result_pub );
	  
	  if(!$row_pub)  	  
	  $result = queryDB( "INSERT INTO $tbl_publist VALUES(NULL, '$row_conf[uname]', '$row_conf[email]', 'system');" );

	  $result_del = queryDB( "DELETE FROM $tbl_userinfo WHERE uid = '$row[uid]'" ); 
	}
	mysql_free_result( $result_unact );
	$result_del = queryDB( "DELETE FROM $tbl_userwait WHERE adddate < $curdate" );
	############################

	if($integrate_db && $intergrate_known)
        Header("Location: $db_register_url");

	if ( $HTTP_POST_VARS["confirm"] != '1' )
	{
	}

else
{
$username = strtolower($username);
$username = rtrim($username);

#username check
if(in_array($username, $Config_bad_user_name))
$errDisp .="<b>$strRegisterError7b</b><br>";

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

else
{
$lenname = strlen($uname);
if($lenname < 5)
$errDisp .="<b>$strRegisterError1b</b><br>";
}

if(!$country)
$errDisp .="<b>$strRegisterError3</b>";

if($terms != 1)
$errDisp .="<b>$strRegisterError7f</b>";

if(!$errDisp)
$done = 1;

closeDB();
}

if($done != 1)
{

$usr->HeaderOut('onload');

if($errDisp)
{ 
		$errMsg = "$errDisp";
	      $usr->errMessage( $errMsg, $strError, 'error', '80');
}
?>

<p>&nbsp;</p>
<form method=post action=register.php name="Register"><table width="75%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#003366">
    <tr> 
      <td colspan=2>
        <div align="right"><img src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/registration.gif" width="400" height="32"></div>
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
                    <div align="right" class=tn><?php echo $strUsername ?>&nbsp;</div>
                  </td>
                  <td width="71%" class=ts> 
                    <input type="text" name="username" maxlength=15 value="<?php echo ("$username\"> $strRegisterAdvice1"); ?>
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td bgcolor="#CCCCCC" width="29%"> 
                    <div align="right" class=tn><?php echo $strPassword ?>&nbsp;</div>
                  </td>
                  <td width="71%" class=ts> 
                    <input type="password" name="password" maxlength=15> <?php echo $strRegisterAdvice2 ?>
                  </td>
                </tr>
                <tr bgcolor="#dddddd"> 
                  <td width="29%"> 
                    <div align="right" class=tn><?php echo $strRegisterName6b ?>&nbsp;</div>
                  </td>
                  <td width="71%">
                    <input type="password" name="repassword" maxlength=15>
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%"> 
                    <div align="right" class=tn><?php echo $strRegisterName1 ?>&nbsp;</div>
                  </td>
                  <td width="71%"> 
                    <input type="text" name="uname" value="<?php echo $uname ?>" maxlength="25">
                  </td>
                </tr>
                <tr bgcolor="#dddddd"> 
                  <td width="29%" valign=top> 
                    <div align="right" class=tn><?php echo $strEmail ?>&nbsp;</div>
                  </td>
                  <td width="71%">
                    <input type="text" name="email_id" value="<?php echo $email_id ?>">
			  <br><span class=ts><?php echo $strRegisterAdvice3 ?></span>
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%"> 
                    <div align="right" class=tn><?php echo $strRegisterName3 ?>&nbsp;</div>
                  </td>
                  <td width="71%">
                    <select name="country">
                      <option value="" selected>---- select ----</option>
			    <?php echo($strCountryList) ?>
                    </select>
			  <input type="hidden" name="confirm" value="1">
                    </td>
                </tr>
                <tr bgcolor="#DDDDDD"> 
                  <td width="29%"> 
                    <div align="right" class=tn><?php echo $strSettingsName10 ?>&nbsp;</div>
                  </td>
                  <td width="71%">
      		<select name='login_time'>
	          <option value="0" selected><?php echo $strSettingsName10a ?></option>
                <option value="1"><?php echo $strSettingsName10b ?></option>
                <option value="7"><?php echo $strSettingsName10c ?></option>
                <option value="30"><?php echo $strSettingsName10d ?></option>
                <option value="365"><?php echo $strSettingsName10e ?></option>
	              </select>
                    </td>
                </tr>
<?php
if($Config_langCodeForce != "1")
{
$nextRowColor = "#dddddd";
echo("<tr bgcolor=\"#CCCCCC\"> 
                  <td width=\"29%\" valign=top> 
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
$nextRowColor = '#CCCCCC';

?>
                <tr bgcolor="<?php echo $nextRowColor ?>"> 
                  <td width="29%" valign=top> 
                    <div align="right" class=tn>&nbsp;</div>
                  </td>
                  <td width="71%">
                    <input type="checkbox" name="terms" value="1"> <span class=tn><?php echo $strRegisterError7g ?></span>
                  </td>
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
            <td width="71%">&nbsp;<input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/register.gif" width="53" height="19" align="bottom" border="0"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr> 
            <td class=ts align=center class=ts><?php echo $strRegisterError7h ?></td>
          </tr>
        </table>

<?php

$usr->footerOut();

}

else
{
	$username = strtolower($username);
	$username = strip_tags($username);

	$uname = strip_tags($uname);

	$code_conf = md5(uniqid ($Config_p));
	$code_conf = substr($code_conf, 0, 10);

	$adddate = date("Ymd");

	$encpass = md5($password);
	$uname = ucwords($uname);

      $LastTimeDate = date ("l dS of F Y h:i:s A");
      $lastinfo = "$REMOTE_ADDR, $LastTimeDate";

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

	$uname = stripslashes(preg_replace("/'/", " ", $uname));

	$result = queryDB( "INSERT INTO $tbl_userinfo VALUES('$username', '$encpass', '$uname', '$email_id', '$country', '0', '$lastinfo', '0', '0', '$Config_dprefs', '0*0|', '0', '$Config_default_space|$Config_default_album|$Config_default_photo|$Config_default_remind', '0', '0', '$New_langCode', '$user_validity', '$login_time')" );

	$result = queryDB( "INSERT INTO $tbl_userwait VALUES('$username', '$code_conf', '$adddate')" );
	$result = queryDB( "DELETE FROM $tbl_publist WHERE email='$email_id'" );


$name = "$Config_sitename";
$email = "$Config_adminmail";
$recnameto = $uname;
$recemailto = $email_id;
$Config_sitename_url = "$Config_mainurl";
$Config_sitename_url_code = "$Config_mainurl/confirm.php?uuid=$username&code=$code_conf";
$subject = $strRegisterMail1;

$putmsg = "\n$Config_site_msg";

$premessage = $csr->LangConvert($strRegisterMail2, $Config_sitename, $Config_sitename_url_code, $Config_unact_days, $putmsg);
$endmessage = "$Config_msgfooter";
$sendmessage = "$premessage\n\n$endmessage";

$mailheader = "From: $name <$email>\nX-Mailer: $strRegisterMail1\nContent-Type: text/plain";
mail("$recemailto","$subject","$sendmessage","$mailheader");

$usr->HeaderOut($Config_SiteTitle ." :: $strMenusSignup");
echo("<br>");
$errMsg = "<br><b>$strRegisterAdvice4</b>\n";
$usr->errMessage( $errMsg, $strSuccess, 'tick', '85' );
echo("<BR>");
$usr->footerOut();
}

?>