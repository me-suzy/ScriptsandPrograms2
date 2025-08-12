<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

      if ( !$ucook->LoggedIn('1') )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
 	    closeDB();
          exit;
      }

	if($errmsg == "3" && $uid)
	{
	if($confirm != '1')
	{
		if(!preg_match ("/^([a-z]|[0-9])*$/", $uid) || (strlen($uid) < 4 && strlen($uid) > 15) || in_array($uid, $Config_bad_user_name))
		{
		if(!$dbBadUserAllow)
		{
		$usr->HeaderOut();
		$usr->errMessage( "<b>$strRegisterError7b</b> or<br><b>$strRegisterError7</b><br>$strDBIntegrateUidError
					 [<a href='../logout.php'>logout</a>]", $strSorry, 'error', '80');
		$usr->FooterOut();
		exit;	
		}
		else
		{
$uid_fld =<<<__HTML_END_
                <tr bgcolor="#EEEEEE"> 
                  <td width="29%" align="right" class=tn>$strUsername&nbsp;<br>
			<span class='ts'><b>$uid</b> not valid, enter new username</span>
                  <td width="71%"><input type="text" size="26" name="uid_fld_val" value="$uid_fld_val" maxlength="25">
			<input type="hidden" name="old_uid" value="$uid">
			<input type="hidden" name="uid_fld" value="1"></td>
                </tr>
__HTML_END_;
		}
		}

		  if(!$uid_fld)
	        $dupcheck = uidAlreadyReg( $uid );

		  if($dupcheck)
		  {
	          $usr->HeaderOut();
		    $csr->customMessage( 'logout' );
		    $usr->FooterOut();
   
	 	    closeDB();
      	    exit;
		  }
        }

	  if($confirm == '1')
	  {
		if($uid_fld)
		{
$uid_fld =<<<__HTML_END_
                <tr bgcolor="#EEEEEE"> 
                  <td width="29%" align="right" class=tn>$strUsername&nbsp;</td>
                  <td width="71%" class='ts'><input type="text" size="26" name="uid_fld_val" value="$uid_fld_val" maxlength="25">
			<input type="hidden" name="old_uid" value="$old_uid">
			<input type="hidden" name="uid_fld" value="1"></td>
                </tr>
__HTML_END_;

			if(in_array($uid_fld_val, $Config_bad_user_name))
			$local_errMsg .="<b>$strRegisterError7</b><br>";

			else if (!preg_match ("/^([a-z]|[0-9])*$/", $uid_fld_val))
			$local_errMsg .="<b>$strRegisterError7b</b><br>";

	        	$dupcheck = uidAlreadyReg( $uid_fld_val );
			$dupcheckDB = uidAlreadyReg( $uid_fld_val, '1' );

			if($dupcheck || $dupcheckDB || !$uid_fld_val)
			$local_errMsg .="<b>$strRegisterError7d</b><br>";

			$usr_len = strlen($uid_fld_val);
			if($usr_len < 4 || $usr_len > 15)
			$local_errMsg .="<b>$strRegisterError7c</b><br>";
		}

		if($email_fld && (!$email || !CheckEmail( $email )))
		$local_errMsg .="<b>$strRegisterError2b</b><br>";

		if(!$uname)
		$local_errMsg .="<b>$strRegisterError1</b><br>";

		else
		{
		$lenname = strlen($uname);
		if($lenname < 5)
		$local_errMsg .="<b>$strRegisterError1b</b><br>";
		}

		if(!$country)
		$local_errMsg .="<b>$strRegisterError3</b><br>";

		if($terms != 1)
		$local_errMsg .="<b>$strRegisterError7f</b>";
        }
	  if($confirm != 1 || $local_errMsg)
	  {
	  if($intergrate_known == "vb");
	  $result = queryDB("SELECT email FROM $tbl_user_alter WHERE $fld_uid_name='$uid' && usergroupid!='3'");
	  $row_db = mysql_fetch_array( $result );
	  $result = queryDB("SELECT COUNT(*) FROM $tbl_userinfo WHERE email='$row_db[email]'");
	  $row    = mysql_fetch_array( $result );

	  if($row[0])
	  {
$email_field =<<<__HTML_END_
                <tr bgcolor="#DDDDDD"> 
                  <td width="29%" align="right" class=tn>$strEmail&nbsp;</td>
                  <td width="71%"><input type="text" size="26" name="email" value="$email" maxlength="25">
			<input type="hidden" name="email_fld" value="1"></td>
                </tr>
__HTML_END_;
        }

		$usr->HeaderOut();
	  	if($local_errMsg)
	 	{
	      	$usr->errMessage( $local_errMsg, $strError, 'error', '80');
	  	}

	  echo("<p>&nbsp;</p><div align='center' class='tn'>$strDBIntegrateWelcome,</div>");
?>

<form method=post action="dbintegrate_disp.php" name="Register"><table width="75%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#003366">
<input type="hidden" name="errmsg" value="3">
<input type="hidden" name="confirm" value="1">
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
<?php
	if($uid_fld)
	{
		echo($uid_fld);
	}
	else
	{
?>
                <tr bgcolor="#EEEEEE">
                  <td width="29%"> 
                    <div align="right" class=tn><?php echo $strUsername ?>&nbsp;</div>
                  </td>
                  <td width="71%" class=tn> 
				<b><?php echo $uid ?></b> <?php echo("<span class='ts'>[<a href='{$dirpath}logout.php'>$strMenusLogout</a>]"); ?>
                  </td>
                </tr>
<?php
	}
?>
		    <?php echo $email_field ?>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%"> 
                    <div align="right" class=tn><?php echo $strRegisterName1 ?>&nbsp;</div>
                  </td>
                  <td width="71%"> 
                    <input type="text" size="26" name="uname" value="<?php echo $uname ?>" maxlength="25">
                  </td>
                </tr>
                <tr bgcolor="#dddddd"> 
                  <td width="29%"> 
                    <div align="right" class=tn><?php echo $strRegisterName3 ?>&nbsp;</div>
                  </td>
                  <td width="71%">
                    <select name="country">
                      <option value="" selected>---- select ----</option>
			    <?php echo($strCountryList) ?>
                    </select>
                    </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td width="29%"> 
                    <div align="right" class=tn><?php echo $strSettingsName10 ?>&nbsp;</div>
                  </td>
                  <td width="71%">
      		<select name='logintime'>
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
$nextRowColor = "#CCCCCC";
echo("<tr bgcolor=\"#dddddd\"> 
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
$nextRowColor = '#dddddd';

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
$usr->FooterOut();
exit;
	  }

	else
	{
	$uid = strtolower($uid);
	$uid = strip_tags($uid);

	$uname = strip_tags($uname);
	$uname = ucwords($uname);

	  if($intergrate_known == "vb");
	  $result = queryDB("SELECT * FROM $tbl_user_alter WHERE $fld_uid_name='$uid' && usergroupid!='3'");

	  $row    = mysql_fetch_array( $result );
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

	  $login_time_put = $logintime*60*60*24;
	  if(!$login_time_put)
        $login_time_put = $Config_logout_time;

	  if($uid_fld)
	  {
	  $enter_uid = $uid_fld_val;
        $result = queryDB( "UPDATE $tbl_user_alter SET $fld_uid_name='$enter_uid' WHERE $fld_uid_name='$old_uid'" );
	  }
	  else
	  $enter_uid = $uid;

	  $uname = stripslashes(preg_replace("/'/", " ", $uname));

	  if(!$email)
	  $email = $row[email];

        $result = queryDB( "INSERT INTO $tbl_userinfo VALUES('$enter_uid', '$row[password]', '$uname', '$email', '$country', '".(time()+$login_time_put)."', '$lastinfo', '0', '1', '$Config_dprefs', '0*0|', '$now_date', '$Config_default_space|$Config_default_album|$Config_default_photo|$Config_default_remind', '0', '0', '$New_langCode', '$user_validity', '$logintime')" );
	  mkdir ("{$dirpath}$Config_datapath/$uid", 0777);

	  $ucook->Logout();

	  $redurl = $dirpath."login.php";
	  $usr->HeaderOut($Config_SiteTitle, '5', $redurl);
	  $usr->errMessage( "<b>$strDBIntegrateSuccess, $strRedirecting... $strElse <a href='$redurl'>$strClickhere</a></b>", $strSuccess, 'tick', '80');
	  $usr->FooterOut();
	  exit;
	}
	}


		else
		{
		if($errmsg == '1')
		$errMsg = $strLoginError3."<br>[<a href='../logout.php'>$strMenusLogout</a>]";
		else if($errmsg == '2')
		$errMsg = "$strLoginSysShut,<br>$Config_sysmsg";
		else
		$errMsg = "<a href='../login.php'>$strLogin</a>";

		$usr->HeaderOut();
		$usr->errMessage( "<b>$errMsg</b>", "", 'error', '80');
		$usr->FooterOut();
		exit; 
		}

function uidAlreadyReg( $uid_check, $checkDb = '0' )
{
	  global $tbl_userinfo, $tbl_user_alter, $fld_uid_name;

	  if($checkDb == '0')
	  {
	  	$result  = queryDB("SELECT COUNT(*) FROM $tbl_userinfo WHERE uid='$uid_check'");
	  	$row     = mysql_fetch_array( $result );
	  }
	  else if($checkDb == '1')
	  {
	  	$result  = queryDB("SELECT COUNT(*) FROM $tbl_user_alter WHERE $fld_uid_name='$uid_check'");
	  	$row     = mysql_fetch_array( $result );
	  }

	  return( $row[0] );
}

?>