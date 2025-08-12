<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();
	$csr = new ComFunc();

      if ( !$ucook->LoggedIn() )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();

          exit;
      }

	if($dowhat == "showconf")
	{
		$usr->Header($Config_SiteTitle ." :: $strMenusSettings");
		echo("<p>&nbsp;</p>");
	      $errMsg = "<b>$strSettingsSaved</b>, <a href=settings.php?re=1>$strSettingsChgAgain</a>\n";
	      $usr->errMessage( $errMsg, $strSuccess, 'tick' );
            $usr->Footer();
	      exit;
	}

	if($change == 1)
	{
		$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$newemail' && uid != '$uid'" );
		$nr_email = mysql_num_rows( $result );
		if($nr_email)
		{ $err .= "$strRegisterError6<br>"; }
		mysql_free_result( $result );

		$result = queryDB( "SELECT password FROM $tbl_userinfo WHERE uid = '$uid'" );
		$nr = mysql_num_rows( $result );
		$row = mysql_fetch_array( $result );
		mysql_free_result ( $result );

		if($row[password] != md5($currpassword))
		{ $err .= "$strRegisterError6b<br>"; }

		closeDB();

		if(!$newuname)
		{ $err .= "$strRegisterError1<br>"; }
		else if(strlen($newuname) < 5)
		{ $err .= "$strRegisterError1b<br>"; }

		if(!CheckEmail($newemail) || !$newemail)
		{ $err .= "$strRegisterError2<br>"; }
		if(!$newcountry)		
		{ $err .= "$strRegisterError3<br>"; }


		if($newpassword || $re_newpassword)
		{ 
			$passlen = strlen($newpassword);
			if($newpassword != $re_newpassword)
			{ $err .="$strRegisterError4<br>"; }
			else if($passlen < 6 || $passlen > 15)
			{ $err .="$strRegisterError5b<br>"; }
		}		
		
	}

	if($err || $change != 1)
	{

     	      $usr->Header($Config_SiteTitle ." :: $strMenusSettings");

		if($err)
		{
		    $errMsg = "<b>$err</b><br>\n";
		    $usr->errMessage( $errMsg, $strError, 'error', '60' );
		}
		else
		{ echo ("<p>&nbsp;</p>"); }

		$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
		$row = mysql_fetch_array( $result );
		list($slimit, $alimit, $plimit, $rlimit) = split('[|]', $row[limits]);
		$sused = $row[sused]; $pused = $row[pused];
		mysql_free_result ( $result );

		$sused = $csr->calcSpaceVal( $sused );

		if(preg_match("/B/", $row[prefs]))
		$border_checka = "checked";
		else
		$border_checkb = "checked";

		if(preg_match("/L/", $row[prefs]))
		$sa = "checked";
		else if(preg_match("/l/", $row[prefs]))
		$sb = "checked";
		else
		$sc = "checked";

		if($row[validity] != '0')
		{
		$valid_year = substr($row[validity], 0, 4);
		$valid_month = substr($row[validity], 4, 2);
		$valid_date = substr($row[validity], 6, 2);
		$uvalidity = date ("F jS, Y", mktime (0,0,0,$valid_month,$valid_date,$valid_year));
		}
		else
		$uvalidity = $strNone;
		

?>

<form method=post action=settings.php name=settings>
  <table width="75%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#666666">
    <tr bgcolor="#666666"> 
      <td align="right" height="2"><img src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/settings.gif" width="223" height="41"></td>
    </tr>
  </table>
  <table width="75%" border="0" cellspacing="1" cellpadding="4" bgcolor="#666666" align="center">
    <tr bgcolor="#CCCCCC"> 
      <td>
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr> 
            <td width="40%" class="tn"><?php echo $strRegisterName1 ?></td>
            <td width="60%">
              <input type="text" name="newuname" value="<?php echo $row[uname] ?>" class="fieldsa">
            </td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strRegisterName2 ?></td>
            <td width="60%">
              <input type="text" name="newemail" value="<?php echo $row[email] ?>" class="fieldsa">
            </td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strRegisterName3 ?></td>
            <td width="60%"> 
              <select name="newcountry" class="fieldsa">
                <option value="<?php echo $row[country]; ?>" selected><?php if($row[country]) echo $row[country]; else echo(" --- $strSelect --- "); ?></option>
		    <?php echo($strCountryList) ?>
              </select>
            </td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo ($strSettingsName10); ?></td>
            <td width="70%"> 
              <input type="hidden" name="oldlogintime" value="<?php echo($row[logintime]); ?>">
              <select name="newlogintime" class="fieldsa">
                <option value="0" <?php if($row[logintime] == '0') echo("selected"); ?>><?php echo $strSettingsName10a ?></option>
                <option value="1" <?php if($row[logintime] == '1') echo("selected"); ?>><?php echo $strSettingsName10b ?></option>
                <option value="7" <?php if($row[logintime] == '7') echo("selected"); ?>><?php echo $strSettingsName10c ?></option>
                <option value="30" <?php if($row[logintime] == '30') echo("selected"); ?>><?php echo $strSettingsName10d ?></option>
                <option value="365" <?php if($row[logintime] == '365') echo("selected"); ?>><?php echo $strSettingsName10e ?></option>
              </select>
            </td>
          </tr>
<?php
if($Config_langCodeForce != "1")
{
echo("<tr> 
                  <td width=\"40%\" class=\"tn\"> 
                    <div class=tn>$strRegisterName6&nbsp;</div>
                  </td>
                  <td width=\"60%\">
				<select name=\"New_langCode\" class=\"fieldsa\">");


	if(!$New_langCode)
	$New_langCode = $Config_LangLoad;

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
?>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName1 ?></td>
            <td width="60%" class="tn">
              <input type="radio" name="listing" value="1" <?php echo $sa ?>> <?php echo $strSettingsName1a ?>&nbsp;&nbsp;<input type="radio" name="listing" value="2" <?php echo $sb ?>> <?php echo $strSettingsName1b ?>&nbsp;&nbsp;<input type="radio" name="listing" value="0" <?php echo $sc ?>> <?php echo $strSettingsName1c ?>
            </td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName2 ?></td>
            <td width="60%" class="tn">
              <input type="radio" name="border" value="1" <?php echo $border_checka ?>> <?php echo $strSettingsName2a ?>&nbsp;&nbsp;<input type="radio" name="border" value="0" <?php echo $border_checkb ?>> <?php echo $strSettingsName2b ?>
            </td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName9 ?></td>
            <td width="60%" class="tn"> <?php echo($uvalidity); ?></td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName3 ?></td>
            <td width="60%" class="tn"> <?php if(!$plimit) echo("$strNoLimit"); else echo "$plimit <font size=1> [<a href=\"$Config_buylink\">$strBuySentence</a>]</font>"; ?></td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName4 ?></td>
            <td width="60%" class="tn"> <?php if(!$alimit) echo("$strNoLimit"); else echo "$alimit <font size=1> [<a href=\"$Config_buylink\">$strBuySentence</a>]</font>"; ?></td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName5 ?></td>
            <td width="60%" class="tn"> <?php if(!$rlimit) echo("$strNoLimit"); else echo "$rlimit <font size=1> [<a href=\"$Config_buylink\">$strBuySentence</a>]</font>"; ?></td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName6 ?></td>
            <td width="60%" class="tn"> <?php if(!$slimit) echo("$strNoLimit"); else echo "$slimit $byteUnits[2] ($sused $strUsed) <font size=1> [<a href=\"$Config_buylink\">$strBuySentence</a>]</font>"; ?></td>
          </tr>
          <tr> 
            <td width="40%" class="tn">&nbsp;</td>
            <td width="60%">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan=2 class="tn" bgcolor="#dddddd"><?php echo $strSettingsName7 ?></td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName7a ?></td>
            <td width="60%">
              <input type="password" name="newpassword" maxlength="15" class="fieldsa">
            </td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName7b ?></td>
            <td width="60%">
              <input type="password" name="re_newpassword" maxlength="15" class="fieldsa">
            </td>
          </tr>
          <tr> 
            <td width="40%" class="tn">&nbsp;</td>
            <td width="60%">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan=2 bgcolor="#dddddd" class="tn"><?php echo $strSettingsName7c ?></td>
          </tr>
          <tr> 
            <td width="40%" class="tn"><?php echo $strSettingsName7d ?></td>
            <td width="60%"> 
		  <input type="password" name="currpassword" maxlength="15" class="fieldsa">
            </td>
          </tr>
          <tr> 
            <td width="40%" class="tn">&nbsp;</td>
            <td width="60%">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan=2> 
              <div align="center">
                <input type="hidden" name="change" value="1"><img src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/reset.gif" width="53" height="19" border="0" onclick="document.settings.reset();">&nbsp;&nbsp;<input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/change.gif" width="53" height="19" border="0">
              </div>
            </td>
          </tr>
          <tr> 
            <td colspan=2>&nbsp;</td>
          </tr>
          <tr bgcolor="#eeeeee"> 
            <td colspan=2><b><font color=#666666><span class="ts"><?php echo $strNote ?></span></b><span class="ts">: 
              <?php echo $strSettingsName8 ?></font></span></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>

<?php
            $usr->Footer();
		exit;
  }

  else
  {
		$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
		$nr = mysql_num_rows( $result );
		$row = mysql_fetch_array( $result );
		mysql_free_result ( $result );

		$user_realname = $row[uname];
		$prefs = "$row[prefs]";

		if($border == "0" && preg_match("/B/", $prefs))
		$prefs = ereg_replace ("B", "", $prefs);

		if($border == "1" && !preg_match("/B/", $prefs))
		$prefs .= "B";
		
		if($listing == "1" && !preg_match("/L/", $prefs))
		{
			if(preg_match("/l/", $prefs))
			$prefs = ereg_replace ("l", "", $prefs);

		$prefs .= "L";
		}
		if($listing == "2" && !preg_match("/l/", $prefs))
		{
			if(preg_match("/L/", $prefs))
			$prefs = ereg_replace ("L", "", $prefs);

		$prefs .= "l";
		}
		else if($listing == "0")
		{
			if(preg_match("/L/", $prefs) || preg_match("/l/", $prefs))
			$prefs = eregi_replace ("L", "", $prefs);
		}

		if($row[email] != $newemail)
		{ 
		 srand((double)microtime()*100);
		 $randpass = rand();
		 $randpass = crypt ($randpass, $Config_p);
		 $randpass = ereg_replace ("/", "", $randpass);
		 $randpass = ereg_replace ('\.', "", $randpass);

		 $enc_newpassword = md5($randpass);
		 $result = queryDB( "UPDATE $tbl_userinfo SET password = '$enc_newpassword' WHERE uid = '$uid'" );

		 if($integrate_db && $intergrate_known == 'vb')
		 $result = queryDB( "UPDATE $tbl_user_alter SET password = '$enc_newpassword', email = '$newemail' WHERE $fld_uid_name = '$uid'" );

		 $inform_mail = 1;
		}
		else if($newpassword)
		{ 
		 $enc_newpassword = md5($newpassword); 
		 $result = queryDB( "UPDATE $tbl_userinfo SET password = '$enc_newpassword' WHERE uid = '$uid'" );

		 if($integrate_db && $intergrate_known == 'vb')
		 $result = queryDB( "UPDATE $tbl_user_alter SET password = '$enc_newpassword' WHERE $fld_uid_name = '$uid'" );

		 $relogin = 1;
		}

		if($newlogintime != $oldlogintime)
		$relogin = 1;

		$newuname = stripslashes(preg_replace("/'/", " ", $newuname));
		$newuname = ucwords($newuname);

		$result = queryDB( "UPDATE $tbl_userinfo SET uname = '$newuname', email = '$newemail', country = '$newcountry', prefs='$prefs', langcode='$New_langCode', logintime='$newlogintime' WHERE uid = '$uid'" );		 
	
		if($inform_mail == 1)
		{
		$Config_sitename_url = $Config_mainurl;
		$subject = $strSettingsName7a;
		$recnameto = $user_realname;
		$recemailto = $newemail;
		$name = $Config_sitename;
		$email = $Config_adminmail;

		$premessage = $csr->LangConvert($strSettingsChgMail, $recnameto, $randpass);;

		$endmessage = "$Config_msgfooter";
		$sendmessage = "$premessage \n $message \n $endmessage";

		$inform_mail_msg = "<br>$strSettingsNotify <a href=".$dirpath."login.php>$strLogin</a>";

		$mailheader = "From: $name <$email>\nX-Mailer: $strSettingsName7a :: $Config_sitename\nContent-Type: text/plain";
		mail("$recemailto","$subject","$sendmessage","$mailheader");
		}

		else
		$inform_mail_msg = "<a href=settings.php?re=1>$strSettingsChgAgain</a>";
				

		closeDB();

		if($inform_mail == 1)
		{ $ucook->Logout(); }


if($relogin == 1)
{
		    if($integrate_db)
		    {
		    if($intergrate_known == 'vb')
	   	    $result_db = queryDB( "SELECT $fld_uid FROM $tbl_user_alter WHERE username='".addslashes(htmlspecialchars($uid))."'" );

		    $row_db = mysql_fetch_array( $result_db );
		    $uid_id = $row_db[$fld_uid];
		    }
		    else
		    $uid_id = $uid;

		    $result_log = queryDB( "SELECT logintime,password FROM $tbl_userinfo WHERE uid='$uid'" );
		    $row_log    = mysql_fetch_array( $result_log );

		    $login_time = $row_log[logintime]*60*60*24;
		    if(!$login_time)
		    $login_time = $Config_logout_time;

                Header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                Header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                Header ("Cache-Control: no-cache, must-revalidate");
                Header ("Pragma: no-cache");

                $ucook->Login( $uid_id, $row_log[password], $uid, $login_time );
                Header("Refresh: 1;url=settings.php?dowhat=showconf");

echo <<< _HTML_END_

<div align="center" style="font-family: Verdana; font-size: 10pt; font-weight: bold; color: #990000;">
$strSettingsSaving...
<div>

_HTML_END_;

exit;
}

		$usr->Header($Config_SiteTitle ." :: $strMenusSettings");
		echo("<p>&nbsp;</p>");
	      $errMsg = "<b>$strSettingsSaved</b>, $inform_mail_msg\n";

		if($inform_mail == 1)
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '80' );
		else
	      $usr->errMessage( $errMsg, $strSuccess, 'tick' );

            $usr->Footer();

		exit;
   }

?>