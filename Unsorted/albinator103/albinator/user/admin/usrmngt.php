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

	if($dowhat == "index")
	{

	 if($Config_makelogs == "1")
	 $csr->MakeAdminLogs( $uid, "Entered the User Management :: $dowhat", "2");

       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr", '', '', 'onload');
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
?>

<p>&nbsp;</p>
<form method=post action=usrmngt.php>
<table width="85%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#333333">
  <tr bgcolor="#006699"> 
    <td colspan=2>
      <div align="right" class="tn"><b><font color="#FFFFFF"><?php echo("$strAdminUsrmngtOpt1 $strUser"); ?>&nbsp;</font></b></div>
    </td>
  </tr>
  <tr bgcolor="#EEEEEE"> 
    <td colspan=2> 
      <div align="center">
        <br><span class="tn"><?php echo $strAdminUsrmngtCmt1 ?></span><br><br>
        <table width="70%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td> 
              <div align="center"></div>
              <form name="form2" action=usrmngt.php method=post>
                <div align="left">
                  <table width="100%" border="0" cellspacing="1" cellpadding="2">
                    <tr> 
                      <td height="10" width="45%" align=right><span class="tn"><b><?php echo $strUsername ?></b>&nbsp; 
                        </span></td>
                      <td height="10" width="62%"><span class="tn"> 
                        <input type="text" name="username" maxlength="15">
                        <input type="hidden" name="dowhat" value="show">
                        </span></td>
                      <td height="10" width="25"><span class="tn"> 
                        <input type="submit" name="Submit" value=" <?php echo $strGo ?> ">
                        </span></td>
                    </tr>
                  </table>
                </div>
              </form>
              <form name="form2" action=usrmngt.php method=post>
	                  <table width="100%" border="0" cellspacing="1" cellpadding="2">
                    <tr> 
                      <td height="10" width="45%" align=right><span class="tn"><b><?php echo $strEmail ?></b>&nbsp; 
                        </span> </td>
                      <td height="10" width="60%"><span class="tn"> 
                        <input type="text" name="email_id" maxlength="70">
                        <input type="hidden" name="dowhat" value="show">
                        </span></td>
                      <td height="10" width="30"><span class="tn">
                        <input type="submit" name="Submit" value=" <?php echo $strGo ?> ">
                        </span></td>
                    </tr>
                  </table>
                </div>
              </form>
              <form name="form2" action=usrmngt.php method=post>
                  <table width="100%" border="0" cellspacing="1" cellpadding="2">
                    <tr> 
                      <td height="10" width="45%" align=right><span class="tn"><b><?php echo("$strAdminUsrmngtOpt1 $strAdminUsrmngtOpt3"); ?></b>&nbsp; 
                        </span> </td>
                      <td height="10" width="60%"><span class="tn"> 
                        <input type="text" name="realname" maxlength="100">
                        <input type="hidden" name="dowhat" value="search">
                        </span></td>
                      <td height="10" width="30" valign="top"><span class="tn"> 
                        <input type="submit" name="Submit" value=" <?php echo $strGo ?> ">
                        </span></td>
                    </tr>
                  </table>
              </form>
	     </td>
          </tr>
	<tr>
	 <td colspan=2 align=center class=tn>~&nbsp;<a href="usrmngt.php?dowhat=all" class=wtn><?php echo($strAMenusAllUsr); ?></a>&nbsp;::&nbsp;<a href="usrmngt.php?dowhat=allact" class=wtn><?php echo($strAdminNotifyOpt4); ?></a> ::&nbsp;<a href="unact.php?dowhat=show" class=wtn><?php echo($strAdminNotifyOpt2); ?></a> :: <a href=usrmngt.php?dowhat=blockshow><?php echo($strAdminNotifyOpt3); ?></a> ~<br>~ <a href=usrmngt.php?dowhat=recent><?php echo $strAdminUsrmngtOpt2 ?></a> :: <a href=usrmngt.php?dowhat=import><?php echo $strAdminUsrmngtOpt11 ?></a> ~</td>
	</tr>
        </table>
      </td>
  </tr>
</table>
<p>&nbsp;</p>
                  
<?php
	}

else if($dowhat == "search")
{
	$realname_list = explode(" ", $realname);
	$realname_list_show = ("uname LIKE '%".strtolower($realname_list[0])."%'");

	$i = 1;
	while($realname_list[$i])
	{ $realname_list_show .= (" || uname LIKE '%".strtolower($realname_list[$i])."%'"); $i++; }

	$rs = new PagedResultSet("SELECT * FROM $tbl_userinfo WHERE $realname_list_show",$page_maker);
	$nr  = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat&realname=$realname");

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAdminUserNotFound</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 closeDB();
	 exit;
	}

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	echo("\n<p>&nbsp;</p><div align=center>$nr $strAdminUserFound<br><span class='ts'>$nav</span><br><br>\n");
	while($row = $rs->fetchArray())
	{
	 	echo("<a href=usrmngt.php?username=$row[uid]&dowhat=show class=tn>$row[uname], ($row[uid])</a><br>\n");
	}			
	echo("\n</div>\n<p>&nbsp;</p>\n");
}

else if($dowhat == "show")
{
	if(!$username && !$email_id)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAdminUserNotFound</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 closeDB();
	 exit;
	}

	if($username)
 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$username'" );
	else if($email_id)
 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$email_id'" );

	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAdminUserNotFound</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}

	 $row = mysql_fetch_array( $result );
	 list($slimit, $alimit, $plimit, $rlimit) = split('[|]', $row[limits]);

	 mysql_free_result( $result );
	
 	 $result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$username'" );
	 $nr = mysql_num_rows( $result );
	 $numalb = $nr;

 	 $result = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$username'" );
	 $nr = mysql_num_rows( $result );
	 $numreminder = $nr;

	 $sused = $csr->calcSpaceVal( $row[sused] );

	 if($Config_makelogs == "1")
	 $csr->MakeAdminLogs( $uid, "User Management for $username", "2");

       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
?>

<table width="90%" border="0" cellspacing="1" cellpadding="0" align="center" bgcolor="#333333">
  <tr bgcolor="#660000"> 
    <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr>
            <td>
              
            <div align="right"><font color="#CCCCCC"><b><font color="#FFFFFF" class="tn"><?php echo("$strAdminUsrmngtCmt2 $row[uname]");?>&nbsp;</font></b></font></div>
            </td>
          </tr>
        </table>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF" valign="top"> 
    <td height="437"> 
      <div align="center"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr class="tn"> 
            <td bgcolor="#eeeeee" width="25%" height="19"> 
              <div align="center"><a href="usrmngt.php?username=<?php echo $username ?>&dowhat=del"><?php echo $strDelete ?></a></div>
            </td>
            <td bgcolor="#dddddd" width="25%" height="19"> 
              <div align="center"><a href="ecards.php?username=<?php echo $username ?>&dowhat=show&catog=user"><?php echo $strMenusEcards ?></a></div>
            </td>
            <td width="25%" bgcolor="#eeeeee" height="19"> 
              <div align="center"><a href="albums.php?username=<?php echo $username ?>&dowhat=show&catog=user"><?php echo ($strAlbum.$strPuralS); ?></a></div>
            </td>
            <td bgcolor="#dddddd" width="25%" height="19"> 
              <div align="center"><a href="reminders.php?username=<?php echo $username ?>&dowhat=show&catog=user"><?php echo $strMenusReminders ?></a></div>
            </td>
          </tr>
        </table>
        <p>&nbsp;</p>
	  <form method=post action=usrmngt.php>
          <table width="75%" border="0" cellspacing="0" cellpadding="4">
            <tr> 
              <td colspan=2 class="tn">
			<div class=tn align=left><a href=usrmngt.php?dowhat=all>&lt;&lt; <?php echo("$strAMenusAllUsr"); ?></a> :: <a href=adlogs.php?dowhat=show&username=<?php echo $row[uid] ?>&wtshow=theuser><?php echo $strAMenusLogs ?></a> :: <a href=unact.php?dowhat=senddet&username=<?php echo ("$row[uid]>$strAdminUsrmngtCmt3</a>"); ?> :: <a href=notify.php?dowhat=send&sendinfo=users&mailuser[0]=<?php echo $row[uid] ?>&sfrom=usrmngt><?php echo $strMail ?></a> :: <a href=userprofile.php?dowhat=editprf&username=<?php echo ("$row[uid]> $strProfile"); ?></a></div>
              </td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strUsername ?>&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="text" name="new_username" value="<?php echo $row[uid] ?>" maxlength="15">
              </td>
            </tr>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strAdminUsrmngtOpt3 ?>&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="text" name="new_uname" value="<?php echo $row[uname] ?>" maxlength="100">
              </td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td width="35%" class="tn" valign=top> 
                <div align="right"><?php echo $strEmail ?>&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="text" name="new_email" value="<?php echo $row[email] ?>" maxlength="70"><br>
		    <span class="ts"><input type="checkbox" name="sendpass" value="1">
                <?php echo $strAdminUsrmngtCmt4 ?></span> 
              </td>
            </tr>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strRegisterName3 ?>&nbsp;</div>
              </td>
              <td width="65%"> 
                <select name="new_country">
                <option value="<?php echo $row[country]; ?>" selected><?php if($row[country]) echo $row[country]; else echo(" --- $strSelect --- "); ?></option>
			<?php echo($strCountryList) ?>
                </select>
              </td>
            </tr>
<?php
echo("<tr bgcolor=\"#dddddd\"> 
                  <td width=\"35%\" class=\"tn\" align=right> 
                    <div class=tn>$strRegisterName6&nbsp;</div>
                  </td>
                  <td width=\"65%\">
				<select name=\"New_langCode\">");

	$i = 0;
	$sel = '';

	while($Config_list_langCode[$i])
	{ 
	  list($langCode, $langName) = split('[|]', $Config_list_langCode[$i]);

	  if($langCode == $row[langcode])
	  $sel = 'selected';
	  else
	  $sel = '';

	  echo ("<option value=\"$langCode\" $sel>$langName</option>\n");
	  $i++;
	}

echo("</select>
         </td>
       </tr>");
?>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right">status&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="hidden" name="old_status" value="<?php echo $row[status] ?>">
                <select name="new_status">
                  <option value="1" <?php if($row[status] == "1") echo "selected" ?>><?php echo $strAdminNotifyOpt4 ?></option>
                  <option value="2" <?php if($row[status] == "2") echo "selected" ?>><?php echo $strAdminNotifyOpt3 ?></option>
                  <option value="0" <?php if($row[status] == "0") echo "selected" ?>><?php echo $strAdminNotifyOpt2 ?></option>
                </select>
              </td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strSettingsName3 ?>&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="text" name="new_plimit" size="5" value="<?php echo $plimit ?>">
                <span class="ts"> <?php echo ("$row[pused] $strPhoto$strPuralS"); ?></span>
              </td>
            </tr>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strSettingsName4 ?>&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="text" name="new_allimit" size="5" value="<?php echo $alimit ?>">
                <span class="ts"> <?php echo ("$numalb $strAlbum$strPuralS"); ?></span>
              </td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo ("$strSettingsName6 $byteUnits[2]"); ?>&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="text" name="new_slimit" size="5" value="<?php echo $slimit ?>">
                <span class="ts"> <?php echo("$sused $strUsed"); ?></span>
              </td>
            </tr>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strSettingsName5 ?>&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="text" name="new_rlimit" size="5" value="<?php echo $rlimit ?>">
                <span class="ts"> <?php echo("$numreminder $strMenusReminders"); ?></span>
              </td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td width="35%" class="tn"> 
                <div align="right">Prefs&nbsp;</div>
              </td>
              <td width="65%"> 
                <input type="text" name="new_prefs" maxlength="25" value="<?php echo $row[prefs] ?>">
              </td>
            </tr>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strAdminUsrmngtOpt12 ?>&nbsp;</div>
              </td>
              <td width="65%" class="ts">
		  
    <select name="valid_year">
<?php
	$valid_year = substr($row[validity], 0, 4);
	$valid_month = substr($row[validity], 4, 2);
	$valid_date = substr($row[validity], 6, 2);

	$today = getdate(); 
	$today_year = $today['year'];

	if($row[validity] == '0')
	echo("      <option value=\"0\" selected>$strNone</option>\n");
	else
	echo("      <option value=\"0\">$strNone</option>\n");

	if($valid_year < $today_year && $valid_year)
	echo("      <option value=\"$valid_year\" selected>$valid_year</option>\n");


	for($i=1;$i<=10;$i++)
	{
		if($valid_year == $today_year)
		$sel = 'selected';
		else
		$sel  = '';

		echo("      <option value=\"$today_year\" $sel>$today_year</option>\n");
		$today_year++;
	}

?>
    </select>
    <select name="valid_month">
<?php
	$today_month = $today['mon'];
	$sel = '';

	if($row[validity] == '0')
	echo("      <option value=\"0\" selected>$strNone</option>\n");
	else
	echo("      <option value=\"0\">$strNone</option>\n");

	for($i=1;$i<=12;$i++)
	{
		if($valid_month == $i)
		$sel = 'selected';
		else
		$sel  = '';

		if($i < 10)
		$j = 0;
		else
		$j = '';

		echo("      <option value=\"$j$i\" $sel>$date_show[$i]</option>\n");
	}

?>
      </select>
      <select name="valid_date">
<?php
	$today_date = $today['mday'] + 1;
	$sel = '';

	if($row[validity] == '0')
	echo("      <option value=\"0\" selected>$strNone</option>\n");
	else
	echo("      <option value=\"0\">$strNone</option>\n");

	for($i=1;$i<=31;$i++)
	{
		if($valid_date == $i)
		$sel = 'selected';
		else
		$sel  = '';

		if($i < 10)
		$j = 0;
		else
		$j = '';

		echo("      <option value=\"$j$i\" $sel>$i</option>\n");
	}

?>
      </select>
		  

		  </td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td class="tn"> 
                <div align="right"><?php echo $strAdmin ?>&nbsp;</div>
              </td>
              <td> 
                <select name="new_admin">
                  <option value="1" <?php if($row[admin] == "1") echo "selected" ?>><?php echo $strYes ?></option>
                  <option value="0" <?php if($row[admin] == "0") echo "selected" ?>><?php echo $strNo ?></option>
                </select>
              </td>
            </tr>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strSettingsName7a ?>&nbsp;</div>
              </td>
              <td width="65%" class="ts">
                <input type="password" name="new_password" maxlength="15">
		  </td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strSettingsName7b ?>&nbsp;</div>
              </td>
              <td width="65%" class="ts">
                <input type="password" name="new_password_re" maxlength="15">&nbsp;<span class="ts"><input type="checkbox" name="sendpassmail" value="1"> <?php echo("$strMail"); ?></span> 

		  </td>
            </tr>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strAdminUserStatus ?>&nbsp;</div>
              </td>
              <td width="65%" class="tn"> 
		  <?php 
			$checktime = $row[sessiontime] + $Config_logout_time;
			$nowtime = time();

			if($checktime < $nowtime)
			$checktime = 0;

			if($checktime != 0) 
			echo "$strAdminUsrmngtCmt5 <span class=ts>[<a href=sysstat.php?dowhat=logoff&username=$row[uid] class=nounderts>$strLogoff</a>]</span>";
			else 
			echo "$strAdminUsrmngtCmt6";
		  ?>
		 </td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strAdminUsrmngtOpt4 ?>&nbsp;</div>
              </td>
              <td width="65%" class="ts"><?php if($row[lastip] == '0') $row[lastip] = '-'; echo $row[lastip]; ?></td>
            </tr>
            <tr bgcolor="#eeeeee"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strAdminUsrmngtOpt5 ?>&nbsp;</div>
              </td>
    <td width="65%" class="ts">
<?php
   if($row[status] == "0") { echo ("$strAdminUsrmngtOpt6 [<a href='unact.php?dowhat=activate&username=$row[uid]'>$strActivate</a>] [<a href='unact.php?dowhat=code&username=$row[uid]'>$strSendCode</a>]"); } 
   else if($row[status] != "0" && !$row[adddate]) { echo ("-"); }
   else
   echo($csr->DisplayDate($row[adddate]));

?>
</td>
            </tr>
            <tr bgcolor="#dddddd"> 
              <td width="35%" class="tn"> 
                <div align="right"><?php echo $strAdminUsrmngtOpt7 ?>&nbsp;</div>
              </td>
              <td width="65%" class="ts"><?php echo (date ("l dS of F Y h:i:s A")); ?></td>
            </tr>
            <tr> 
              <td width="35%" class=ts>&nbsp;<?php echo $strAdminUsrmngtOpt8 ?></td>
              <td width="65%"> 
                <div align="right"> 
		      <input type="hidden" name="username" value="<?php echo $username ?>">
		      <input type="hidden" name="dowhat" value="change">
                  <input type="reset" name="clear" value="reset">&nbsp;
                  <input type="submit" name="Submit" value="<?php echo $strUpdate ?> &gt;&gt;">
                </div>
              </td>
            </tr>
          </table>
        </form>
        <p>&nbsp;</p>
        </div>
      </td>
  </tr>
</table>
<p>&nbsp;</p>

<?php	 
	}


else if($dowhat == "recent")
{
	$recent_days = 5;

	$lastdate  = mktime (0,0,0,date("m"),date("d")-$recent_days,date("Y"));
	$curdate = strftime ("%Y%m%d", $lastdate);

	$rs = new PagedResultSet("SELECT uid, uname, adddate FROM $tbl_userinfo WHERE adddate >= $curdate && status!='0' ORDER BY adddate DESC",$page_maker);
	$nr  = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat");

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
	echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");

	echo("\n<p>&nbsp;</p><div align=center>".$csr->LangConvert($strAdminUsrmngtOpt9, "$nr user(s)", $recent_days)."<br><span class='ts'>$nav</span><p><table width=60% align=center cellpadding=4 cellspacing=0 border=0>\n");

	while($row = $rs->fetchArray())
	{
		if($i == 1)
		{ $i=0; $rowcolor = "#dddddd"; }
		else
		{ $i++; $rowcolor = "#eeeeee"; }

?>

<tr bgcolor=<?php echo $rowcolor ?>>
<td width=50% class=tn><a href=usrmngt.php?username=<?php echo $row[uid] ?>&dowhat=show class=noundertn><?php echo $row[uname] ?>, (<?php echo $row[uid] ?>)</a></td>
<td class=ts align=right>
<?php
   if(($row[adddate] == "0" || !$row[adddate]) && ($row[status] == "0")) { echo ("$strAdminUsrmngtOpt6"); } 
   else if($row[status] != "0" && !$row[adddate]) { echo ("-"); }
   else
   {
	$reg_year = substr($row[adddate], 0, 4);
	$reg_month = substr($row[adddate], 4, 2);
	$reg_date = substr($row[adddate], 6, 2);
	echo date ("M d, Y", mktime (0,0,0,$reg_month,$reg_date,$reg_year));
   }

?>
</td>
</tr>

<?php
	}			
	echo("\n</table></div>\n<p>&nbsp;</p>\n");
}

else if($dowhat == "all" || $dowhat == "allact")
{
      $result_temp = queryDB( "SELECT * FROM $tbl_userinfo WHERE status='1'"); 
	$act_users = mysql_num_rows( $result_temp );
      $result_temp = queryDB( "SELECT * FROM $tbl_userinfo WHERE status='0'"); 
	$unact_users = mysql_num_rows( $result_temp );
      $result_temp = queryDB( "SELECT * FROM $tbl_userinfo WHERE status='2'"); 
	$blocked_users = mysql_num_rows( $result_temp );
	mysql_free_result( $result_temp );
      $result_temp = queryDB( "SELECT * FROM $tbl_userinfo"); 
	$nr = mysql_num_rows( $result_temp );

      if($dowhat == "all")
	$showbar = "$nr $strUser ~ <a href=\"usrmngt.php?dowhat=allact\">$act_users $strAdminNotifyOpt4</a> ~ ";
	else
      {
      $showbar = "<a href=\"usrmngt.php?dowhat=all\">$nr user(s)</a> ~ <b>$act_users $strAdminNotifyOpt4</b> ~ ";
	$whereinfo = "WHERE status='1'"; 
      }

	if($sort == "uid" || !$sort)
	$rs = new PagedResultSet("SELECT * FROM $tbl_userinfo $whereinfo ORDER BY uid",$page_maker);
	else
	$rs = new PagedResultSet("SELECT * FROM $tbl_userinfo $whereinfo ORDER BY uname",$page_maker);
      
	$nr  = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat&sort=$sort");

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");

	echo("\n<p>&nbsp;</p><div align=center>$showbar <a href=unact.php?dowhat=show>$unact_users $strAdminNotifyOpt2</a> ~ <a href=usrmngt.php?dowhat=blockshow>$blocked_users $strAdminNotifyOpt3</a> ~ <a href=usrmngt.php?dowhat=recent>$strAdminUsrmngtOpt2</a> (<a href=usrmngt.php?dowhat=index>$strAdminUsrmngtOpt1</a>)<br><br><span class=ts>$nav</span></div><br><br>\n");
?>

<table width="80%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr class="tn">
<?php echo("
    <td width=\"20%\"><b><a href=usrmngt.php?dowhat=$dowhat&sort=uid>$strUsername</a></b></td>
    <td width=\"30%\"><b><a href=usrmngt.php?dowhat=$dowhat&sort=uname>$strAdminUsrmngtOpt3</a></b></td>
    <td width=\"27%\"><b>$strAdminUsrmngtOpt5</b></td>
    <td width=\"28%\">&nbsp;</td>
"); ?>
  </tr>

<?php

$i = 0;

	while($row = $rs->fetchArray())
	{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }


	if($row[status] == "0")
	$Confirm_link = "[<a href=unact.php?dowhat=activate&username=$row[uid]&sendurl=usrmngt>$strActivate</a>]";
	else
	$Confirm_link = "";
?>
  <tr class="tn" bgcolor="<?php echo $rowcolor ?>">
    <td><?php echo("<a href=usrmngt.php?username=$row[uid]&dowhat=show class=noundertn>$row[uid]</a>"); ?></td>
    <td><?php echo("$row[uname]"); ?></td>
    <td>
<?php
   if(($row[adddate] == "0" || !$row[adddate]) && ($row[status] == "0")) { echo ("not confirmed"); } 
   else if($row[status] != "0" && !$row[adddate]) { echo ("-"); }
   else
   {
	$reg_year = substr($row[adddate], 0, 4);
	$reg_month = substr($row[adddate], 4, 2);
	$reg_date = substr($row[adddate], 6, 2);
	echo date ("M d, Y", mktime (0,0,0,$reg_month,$reg_date,$reg_year));
   }

?>
</td>
    <td class=ts>[<a href="<?php echo "usrmngt.php?username=$row[uid]&dowhat=show"; ?>" class=nounderts><?php echo $strAdminOpen ?></a>] <?php echo $Confirm_link ?></td>
  </tr>

<?php
	}			
	echo("</table><p>&nbsp;</p>");
}

else if($dowhat == "blockshow" || $dowhat == "unblock")
{
	if($dowhat == "blockshow")
	{
	$rs = new PagedResultSet("SELECT * FROM $tbl_userinfo WHERE status='2'",$page_maker);
	$nr  = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat");

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strNo $strAdminNotifyOpt3 $strUser</b>\n";
       $usr->errMessage( $errMsg, '', 'error' );
	 echo("<BR>");
   	 $usr->Footer();

	 closeDB();
	 exit;
	}
	
      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	echo("\n<p>&nbsp;</p><div align=center>$nr $strUser $strAdminNotifyOpt3<br><span class='ts'>$nav</span><p>
	<table width=60% align=center cellpadding=4 cellspacing=0 border=0>\n");

	$i = 0;

	while($row = $rs->fetchArray())
	{
		if($i == 1)
		{ $i=0; $rowcolor = "#dddddd"; }
		else
		{ $i++; $rowcolor = "#eeeeee"; }

	 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$row[uid]'" );
		$row_user = mysql_fetch_array( $result_user );
?>

<tr bgcolor=<?php echo $rowcolor ?>>
<td width=50% class=tn><a href=usrmngt.php?username=<?php echo $row[uid] ?>&dowhat=show class=noundertn><?php echo $row_user[uname] ?>, (<?php echo $row[uid] ?>)</a></td>
<td class=ts align=right>[<a href=usrmngt.php?dowhat=unblock&sername=<?php echo("$row[uid]>$strAdminUsrmngtOpt10"); ?></a>]&nbsp;</td>
</tr>

<?php
	}			

	echo("\n</table></div>\n<p>&nbsp;</p>\n");
}

}

else if($dowhat == "change")
{

	$username = $HTTP_POST_VARS["username"];
	
 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$username'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr24</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}

	 $row = mysql_fetch_array( $result );
	 $pused = $row[pused];
	 mysql_free_result( $result );
	
 	 $result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$username'" );
	 $nr = mysql_num_rows( $result );
	 $numalb = $nr;

 	 $result = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$username'" );
	 $nr = mysql_num_rows( $result );
	 $numreminder = $nr;

	 $total_size = $row[sused];
	 $slimit = $new_slimit * 1000000;
	
$new_username = strtolower($new_username);
$new_username = strip_tags($new_username);

$new_uname = strip_tags($new_uname);

if(!$new_username)
$err .= "$strNo $strUsername<br>";
else
{
	if($new_username != $row[uid])
	{
      $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$new_username'"); 
	$nr_user = mysql_num_rows( $result_user );

		if(strlen($new_username) < 4 || strlen($new_username) > 15)
		{ $err .= "$strRegisterError7c<br>"; }	
		else if($nr_user > 0)
		{ $err .= "$strRegisterError7d<br>"; }	
		else
		{ $uidchange = 1; }
	}
}

if(!$new_uname)
{ $err .= "$strNo $strAdminUsrmngtOpt3<br>"; }
else if(strlen($new_uname) < 5)
{ $err .= "$strRegisterError1b<br>"; }

if(!$new_email)
{ $err .= "$strRegisterError2b<br>"; }
else
{
	if($new_email != $row[email])
	{ 
      $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE email='$new_email'"); 
	$nr_user = mysql_num_rows( $result_user );

		if(!CheckEmail($new_email))
		{ $err .= "$strRegisterError2b<br>"; }
		else if($nr_user > 0)
		{ $err .= "$strRegisterError6<br>"; }	
		else
		{ $mailchange = 1; }
	}
}

if(!$new_plimit && $new_plimit < 0)
{ $err .= "$strNo $strSettingsName3<br>"; }
else if($new_plimit < $pused && $new_plimit != "0")
{ $err .= $csr->LangConvert($strAdminUsrmngtCmt7, "$strSettingsName3", "$strPhoto$strPurals")."<br>"; }

if(!$new_allimit && $new_allimit < 0)
{ $err .= "$strNo $strSettingsName4<br>"; }
else if($new_allimit < $numalb && $new_allimit != "0")
{ $err .= $csr->LangConvert($strAdminUsrmngtCmt7, "$strSettingsName4", "$strAlbum$strPurals")."<br>"; }

if(!$new_rlimit && $new_rlimit < 0)
{ $err .= "$strNo $strSettingsName5<br>"; }
else if($new_rlimit < $numreminder && $new_rlimit != "0")
{ $err .= $csr->LangConvert($strAdminUsrmngtCmt7, "$strSettingsName4", "$strAMenusReminder")."<br>"; }

if(!$new_slimit && $new_slimit < 0)
{ $err .= "$strNo $strSettingsName6<br>"; }
else if($slimit < $total_size && $new_slimit != "0")
{ $err .= $csr->LangConvert($strAdminUsrmngtCmt7, "$strSettingsName6", "$strSpace $strUsed")."<br>"; }

if(!$new_email)
{ $err .= "$strRegisterError2b<br>"; }
else
{
	if($new_email != $row[email])
	{ 
		if(!CheckEmail($new_email))
		{ $err .= "$strRegisterError2b<br>"; }
		else
		{ $mailchange = 1; }
	}
}

if($new_password || $new_password_re) 
{
	if($new_password != $new_password_re)
	{ $err .= "$strRegisterError4<br>"; }
	else if(strlen($new_password) < 6)
	{ $err .= "$strRegisterError5b<br>"; }
	else
	{ $passchange = 1; }
}

if($valid_date == '0' && $valid_month == '0' && $valid_year == '0')
$uvalidity = '0';
else if($valid_date == '0' || $valid_month == '0' || $valid_year == '0')
$err .= "$strAdminUsrmngtCmt13<br>";
else
$uvalidity = "$valid_year$valid_month$valid_date";


 	 if($err)
	 {
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
       $errMsg = "<B>$err</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}

	$new_plimit  = (int)($new_plimit);
	$new_rlimit  = (int)($new_rlimit);
	$new_allimit = (int)($new_allimit);
	$new_slimit  = (float)($new_slimit);

	if(!$new_rlimit)
	$new_rlimit = 0;
	if(!$new_plimit)
	$new_plimit = 0;
	if(!$new_allimit)
	$new_allimit = 0;
	if(!$new_slimit)
	$new_slimit = 0;

	if($new_status != $old_status)
	{
		if($new_status == '0')
		{
	 	      $result_unact = queryDB("UPDATE $tbl_userinfo SET status = '0', adddate='0' WHERE uid='$username'");

  	 	      $result_chkunact = queryDB("SELECT uid FROM $tbl_userwait WHERE uid = '$username'");
			$row_chkunact    = mysql_fetch_array( $result_chkunact );

			if(!$row_chkunact[0])
			{
			$code_conf = md5(uniqid ($Config_p));
			$code_conf = substr($code_conf, 0, 10);

  	 	      $result_addunact = queryDB("INSERT INTO $tbl_userwait VALUES('$username', '$code_conf', '$now_date')");
			}

	 		if($Config_makelogs == "1")
	 		$csr->MakeAdminLogs( $uid, "$username Unactivated", "2");
		}
		else if($new_status == '1')
		{
	 	     $result_act = queryDB("UPDATE $tbl_userinfo SET status = '1', adddate='$now_date' WHERE uid='$username'");
  	 	      $result_del = queryDB("DELETE FROM $tbl_userwait WHERE uid = '$username'");

	 		error_reporting(0);
	 		mkdir ("$dirpath"."$Config_datapath/$username", 0777);
	 		error_reporting(E_ERROR | E_WARNING);

	 		if($Config_makelogs == "1")
	 		$csr->MakeAdminLogs( $uid, "$username Activated", "2");
		}
	}

      $result = queryDB( "UPDATE $tbl_userinfo SET uname='$new_uname', country='$new_country', admin='$new_admin', status='$new_status', limits='$new_slimit|$new_allimit|$new_plimit|$new_rlimit', prefs='$new_prefs', langcode='$New_langCode', validity='$uvalidity' WHERE uid='$username'" );

	if($mailchange == 1)
	{ 
	if($sendpass == "1")
	{ 
	srand((double)microtime()*100);
	$randpass = rand();
	$randpass = crypt ($randpass, $Config_p);
	$randpass = ereg_replace ("/", "", $randpass);
	$randpass = ereg_replace ('\.', "", $randpass);
	$enc_newpassword = md5($randpass);

	$Config_sitename_url = "$Config_mainurl";
	$subject = "$strSettingsName7a";
	$recnameto = "$new_uname";
	$recemailto = "$new_email";
	$name = "$Config_sitename";
	$email = "$Config_adminmail";

	$premessage = $csr->LangConvert($strAdminUserMail1, $recnameto, $new_username, $randpass);
	$endmessage = "$Config_msgfooter";
	$sendmessage = "$premessage \n $message \n $endmessage";

	$mailheader = "From: $name <$email>\nX-Mailer: $strSettingsName7a\nContent-Type: text/plain";
	mail("$recemailto","$subject","$sendmessage","$mailheader");

      $result = queryDB( "UPDATE $tbl_userinfo SET password='$enc_newpassword' WHERE uid='$username'"); 

	if($integrate_db && $intergrate_known == 'vb') // vb's db password change
      $result = queryDB( "UPDATE $tbl_user_alter SET $fld_password='$enc_newpassword' WHERE $fld_uid_name='$username'");
	}	

      $result = queryDB( "UPDATE $tbl_userinfo SET email='$new_email' WHERE uid='$username'"); 

	if($integrate_db && $intergrate_known == 'vb') // vb's db email change
      $result = queryDB( "UPDATE $tbl_user_alter SET email='$new_email' WHERE $fld_uid_name='$username'");
      }

	if($passchange == 1)
	{ 
	$new_password_enc = md5($new_password);
      $result = queryDB( "UPDATE $tbl_userinfo SET password ='$new_password_enc' WHERE uid='$username'"); 

	if($integrate_db && $intergrate_known == 'vb') // vb's db password change
      $result = queryDB( "UPDATE $tbl_user_alter SET $fld_password='$new_password_enc' WHERE $fld_uid_name='$username'");

	if($sendpass != "1" && $sendpassmail == "1")
	{
	srand((double)microtime()*100);
	$randpass = rand();
	$randpass = crypt ($randpass, $Config_p);
	$randpass = ereg_replace ("/", "", $randpass);
	$randpass = ereg_replace ('\.', "", $randpass);
	$enc_newpassword = md5($randpass);

	$Config_sitename_url = "$Config_mainurl";
	$subject = "New Password";
	$recnameto = "$new_uname";
	$recemailto = "$new_email";
	$name = "$Config_sitename";
	$email = "$Config_adminmail";

	$premessage = $csr->LangConvert($strAdminUserMail2, $recnameto, $new_username, $new_password, $Config_mainurl);
	$endmessage = "$Config_msgfooter";
	$sendmessage = "$premessage \n $message \n $endmessage";

	$mailheader = "From: $name <$email>\nX-Mailer: $strSettingsName7a\nContent-Type: text/plain";
	mail("$recemailto","$subject","$sendmessage","$mailheader");
	}
	}

	if($uidchange == 1)
	{ 
	    $result_user_prf = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$username'" );
	    $row_user_prf = mysql_fetch_array( $result_user_prf );

	    $parray = split ('[|]', $row_user_prf[profile]);
	    $profilenew = "";

          foreach ($parray as $pairval_first) 
	    {
		list ($pfid, $pval) = split ('[*]', $pairval_first);

		if($pfid != "0")
		{ $profilenew .= $pfid."*".$pval."|"; }
		else if($pfid == "0")
		{
		  $newpfile = ereg_replace ($username, $new_username, $pval);
		  $profilenew .= "0"."*".$newpfile."|"; 

		  if($pval != "0")
		  rename($dirpath.$Config_datapath."/".$username."/".$pval, $dirpath.$Config_datapath."/".$username."/".$newpfile); 
		}
	    }

		$profilenew = ereg_replace ("\|\*\|", "\|", $profilenew);
		$result_up = queryDB( "UPDATE $tbl_userinfo SET profile='$profilenew' WHERE uid='$username'" );

	      $result = queryDB( "UPDATE $tbl_userinfo SET uid='$new_username' WHERE uid='$username'"); 
	      $result = queryDB( "UPDATE $tbl_albumlist SET uid='$new_username' WHERE uid='$username'"); 
	      $result = queryDB( "UPDATE $tbl_adlogs SET uid='$new_username' WHERE uid='$username'"); 
	      $result = queryDB( "UPDATE $tbl_reminders SET uid='$new_username' WHERE uid='$username'"); 
	      $result = queryDB( "UPDATE $tbl_ecards SET uid='$new_username' WHERE uid='$username'"); 
	
	if($integrate_db && $intergrate_known == 'vb') // vb's db username change
      $result = queryDB( "UPDATE $tbl_user_alter SET $fld_uid_name='$new_username' WHERE $fld_uid_name='$username'");

	if($row[status] == "0") 
      $result = queryDB( "UPDATE $tbl_userwait SET uid='$new_username' WHERE uid='$username'");

	else
	rename ("$dirpath"."$Config_datapath/$username", "$dirpath"."$Config_datapath/$new_username");
      }
	

	 if($Config_makelogs == 1)
	 { if($mailchange == 1) { if($sendpass == "1") $extrainfo = " mail changed"; else $extrainfo = " mail changed, pass sent"; }
	  if($uidchange == 1) $extrainfo .= ", uid changed ";  
	  if($passchange == 1) $extrainfo .= ", pass changed";  
	  $csr->MakeAdminLogs( $uid, "User Management changend for $username $extrainfo", "2"); 
       }

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr", '1', "usrmngt.php?dowhat=show&username=$new_username");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strSaved, $strRedirecting...</b><br>$strElse <a href=\"usrmngt.php?dowhat=show&username=$new_username\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;

}

else if($dowhat == "del")
{
          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>".$csr->LangConvert($strDelConfirm, "$strUser $username")."</b> <a href=\"usrmngt.php?dowhat=delconf&username=$username\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;

}

else if($dowhat == "delconf")
{
          $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$username'"); 
	    $row = mysql_fetch_array( $result );

	    # profile pic deletion here
	    $parray = split ('[|]', $row[profile]);
          foreach ($parray as $pairval_first) 
	    {
		list ($pfid, $pval) = split ('[*]', $pairval_first);

		if($pfid == "0")
		break;
	    }

	    if (file_exists($dirpath.$Config_datapath."/$username/$pval") && $pval && $pfid == "0" && $pval != "0")
	    unlink($dirpath.$Config_datapath."/$username/$pval");
	    ##

          $result = queryDB( "DELETE FROM $tbl_userinfo WHERE uid='$username'"); 
          $result = queryDB( "DELETE FROM $tbl_adlogs WHERE uid='$username'"); 
          $result = queryDB( "DELETE FROM $tbl_reminders WHERE uid='$username'"); 
	    $result = queryDB( "DELETE FROM $tbl_ecards WHERE uid='$username'"); 
    
          $result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid='$username'"); 
	    while ($row_alb = mysql_fetch_array( $result ))
	    {		
	   	    $result_pics = queryDB( "SELECT pid, pname FROM $tbl_pictures WHERE aid = '$row_alb[aid]'" );
		    while ($row = mysql_fetch_array ( $result_pics ))
	    	    {		
				$csr->editSize( $row[pid], $username, 'del', '2' );
		    }
	    	    mysql_free_result ( $result_pics );
	    }
	    mysql_free_result ( $result );
          $result = queryDB( "DELETE FROM $tbl_albumlist WHERE uid='$username'"); 

	    if($row[status] != "0")
	    { rmdir($dirpath.$Config_datapath."/$username"); }
	    else if($row[status] == "0")
	    $result = queryDB( "DELETE FROM $tbl_userwait WHERE uid='$username'"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr", '1', "usrmngt.php?dowhat=index");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strUser $strDeleted, $strRedirecting...</b><br>$strElse <a href=\"usrmngt.php?dowhat=index\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "import")
{
  	    set_time_limit(0);

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr :: $strAdminUsrmngtOpt11");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    echo("<BR>");

if($confirm == 1)
{
	if(!$fld_dbname)
	$errMsg .= "$strNo $strAdminImportOpt1<br>";

	if(!$fld_tname)
	$errMsg .= "$strNo $strAdminImportOpt7<br>";

	if(!$fld_uid)
	$errMsg .= "$strNo $strRegisterName4<br>";

	if(!$fld_uname)
	$errMsg .= "$strNo $strRegisterName1<br>";

	if(!$fld_email)
	$errMsg .= "$strNo $strRegisterName2<br>";

	if(!$fld_password)
	$errMsg .= "$strNo $strPassword<br>";

	if(!$fld_message && $fld_wmail == "1")
	$errMsg .= "$strAdminUsrmngtCmt12<br>";

	if(!$fld_dbuser)
	$fld_dbuser = '';

	if(!$fld_dbpass)
	$fld_dbpass = '';

	if(!$errMsg)
	{
	// db access
	$errno = 1;

      error_reporting(0);
	$errno = InstallConnectDB($__serName, $fld_dbuser, $fld_dbpass);
      error_reporting(E_ERROR | E_WARNING);

		if($errno != 1)
		$errMsg .= ($strAdminUsrmngtCmt9."<br>");

		// field existence
		else
		{
			if ( empty($__serPort) )
    			$_t_link = $fDBConnect ( $__serName, $fld_dbuser, $fld_dbpass );
	   		else
	    		$_t_link = $fDBConnect ( $__serName.":".$__serPort, $fld_dbuser, $fld_dbpass );

			$db_t_connected = mysql_select_db( $fld_dbname, $_t_link );

			if(!$db_t_connected) $errMsg .= ($strAdminUsrmngtCmt9."<br>");
			else
			{
	   		$result_imp = mysql_query( "SELECT $fld_uid, $fld_uname, $fld_email, $fld_password FROM $fld_tname" );

	   		if (!$result_imp) $errMsg .= ($strAdminUsrmngtCmt10."<br>");
			else if(mysql_num_rows( $result_imp ) < 1) $errMsg .= ($strAdminUsrmngtCmt11."<br>");

			mysql_close( $_t_link );
			}
		}
	}
}

if($confirm != 1 || $errMsg)
{
	if($errMsg)
	{ 
	      $usr->errMessage( $errMsg, $strError, 'error');
		echo("<p>&nbsp;</p>");
	}
?>

<form action=usrmngt.php method=post>
<table width="80%" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#dddddd" class=tn>
  <tr> 
    <td colspan=2 bgcolor="#006699"><font color="#FFFFFF"><?php echo $strAdminUsrmngtCmt8 ?></font></td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><?php echo $strAdminImportOpt1 ?></td>
    <td width="76%" bgcolor="#FFFFFF"> 
      <input type="text" name="fld_dbname" maxlength="25" value="<?php echo ("$HTTP_POST_VARS[fld_dbname]"); ?>" size="30">
    </td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><?php echo $strAdminImportOpt2 ?></td>
    <td width="76%" bgcolor="#FFFFFF"> 
      <input type="text" name="fld_dbuser" maxlength="25" value="<?php echo ("$HTTP_POST_VARS[fld_dbuser]"); ?>" size="30">
    </td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><?php echo $strAdminImportOpt3 ?></td>
    <td width="76%" bgcolor="#FFFFFF"> 
      <input type="text" name="fld_dbpass" maxlength="25" value="<?php echo ("$HTTP_POST_VARS[fld_dbpass]"); ?>" size="30">
    </td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><?php echo $strAdminImportOpt8 ?></td>
    <td width="76%" bgcolor="#FFFFFF"> 
      <input type="text" name="fld_tname" maxlength="25" value="<?php echo ("$HTTP_POST_VARS[fld_tname]"); ?>" size="30">
    </td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee">&nbsp;</td>
    <td width="76%" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><b><?php echo $strAdminImportOpt4 ?></b></td>
    <td width="76%" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><?php echo $strRegisterName4 ?></td>
    <td width="76%" bgcolor="#FFFFFF"> 
      <input type="text" name="fld_uid" maxlength="25" value="<?php echo ("$HTTP_POST_VARS[fld_uid]"); ?>" size="30">
    </td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><?php echo $strRegisterName1 ?></td>
    <td width="76%" bgcolor="#FFFFFF"> 
      <input type="text" name="fld_uname" maxlength="25" value="<?php echo ("$HTTP_POST_VARS[fld_uname]"); ?>" size="30">
    </td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><?php echo $strRegisterName2 ?></td>
    <td width="76%" bgcolor="#FFFFFF"> 
      <input type="text" name="fld_email" maxlength="25" value="<?php echo ("$HTTP_POST_VARS[fld_email]"); ?>" size="30">
    </td>
  </tr>
  <tr> 
    <td width="24%" bgcolor="#eeeeee"><?php echo $strPassword ?></td>
    <td width="76%" bgcolor="#FFFFFF"> 
      <input type="text" name="fld_password" maxlength="25" value="<?php echo ("$HTTP_POST_VARS[fld_password]"); ?>" size="30"> <input type="checkbox" name="fld_md5" value="1" <?php if($HTTP_POST_VARS[fld_md5] == 1) echo("checked"); else if($confirm != 1) echo("checked"); ?>> <?php echo $strAdminImportOpt5 ?></td>
  </tr>
  <tr> 
    <td width="24%" height="2" bgcolor="#eeeeee">&nbsp;</td>
    <td width="76%" height="2" bgcolor="#FFFFFF"> 
      <input type="radio" name="fld_wmail" value="1" <?php if($HTTP_POST_VARS[fld_wmail] == 1) echo("checked"); else if($confirm != 1) echo("checked"); ?>> <?php echo $strAdminImportOpt6 ?>  <input type="radio" name="fld_wmail" value="2" <?php if($HTTP_POST_VARS[fld_wmail] == 2) echo("checked"); ?>> <?php echo $strAdminImportOpt7 ?>  <input type="radio" name="fld_wmail" value="0" <?php if($HTTP_POST_VARS[fld_wmail] == "0") echo("checked"); ?>> <?php echo $strNone ?></td>
  </tr>
  <tr> 
    <td width="24%" height="2" bgcolor="#eeeeee"><?php echo $strMessage ?></td>
    <td width="76%" height="2" bgcolor="#FFFFFF"> 
	<textarea name="fld_message" cols="35" rows="10"><?php echo ("$HTTP_POST_VARS[fld_message]"); ?></textarea>
  </tr>
</table>
<table width="80%" border="0" cellspacing="1" cellpadding="4" align="center" class=tn>
  <tr>
    <td width="24%" height="2">&nbsp;</td>
    <td width="76%" height="2"> 
      <input type="hidden" name="dowhat" value="import">
      <input type="hidden" name="confirm" value="1">
      <input type="submit" name="Submit" value="<?php echo $strAdminUsrmngtOpt11 ?>">
    </td>
  </tr>
</table>
</form>

<?php
}

else
{
connectDB();

$i = 0;
$sendmessage = stripslashes($HTTP_POST_VARS[fld_message]);

	if($Config_default_uvalid != '0')
	{
		$lastdate  = mktime (0,0,0,date("m"),date("d")+$Config_default_uvalid,date("Y"));
		$curdate = strftime ("%Y%m%d", $lastdate);

		$user_validity = $curdate;
	}
	else
	$user_validity = '0';

	while($row_imp = mysql_fetch_array( $result_imp, MYSQL_NUM ))
	{
	$rid = 0;

	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$row_imp[0]'" );
	$nr = mysql_num_rows( $result );
	if($nr || !$row_imp[0])
	{ $rejected_uid .= "$row_imp[0] : $strRegisterError7d<br>\n"; $rid = 1; }

	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$row_imp[2]'" );
	$nr = mysql_num_rows( $result );
	if($nr || !$row_imp[0])
	{ $rejected_uid .= "$row_imp[0] : $strRegisterError6<br>\n"; $rid = 1; }
	else if(!CheckEmail($row_imp[2]))
	{ $rejected_uid .= "$row_imp[0] : $strRegisterError2b<br>\n"; $rid = 1; }

	if(strlen($row_imp[0]) < 4 || strlen($row_imp[0]) > 10)
	{ $rejected_uid .= "$row_imp[0] : $strRegisterError7c<br>\n"; $rid = 1; }
	
	if($rid != 1)
	{
		$i++;

		if($HTTP_POST_VARS[fld_md5] != 1)
		$row_imp[3] = md5($row_imp[3]);

		if($HTTP_POST_VARS[fld_wmail] == "2")
		{
		$uwait = 0;
		$uadd_date = 0;
		$uadmin_make = 0;

		$code_conf = md5(uniqid ($Config_p));
		$code_conf = substr($code_conf, 0, 10);

		$result = queryDB( "INSERT INTO $tbl_userwait VALUES('$row_imp[0]', '$code_conf', '$now_date')" );

		$Config_sitename_url = "$Config_mainurl";
		$Config_sitename_url_code = "$Config_mainurl/confirm.php?uuid=$row_imp[0]&code=$code_conf";
		$subject = $strRegisterMail1;
		$putmsg = "\n$Config_site_msg";
		$premessage = $csr->LangConvert($strRegisterMail2, $Config_sitename, $Config_sitename_url_code, $Config_unact_days, $putmsg);
		$endmessage = "$Config_msgfooter";
		$sendmessage = "$premessage $endmessage";

	      $mailheader = "From: $Config_sitename <$Config_adminmail>\nX-Mailer: $subject\nContent-Type: text/plain";
		mail("$row_imp[2]","$subject","$sendmessage","$mailheader");
		}

		else
		{
		$LastTimeDate = date ("l dS of F Y h:i:s A");
	      $uadmin_make = "Admin Make $LastTimeDate";
		$uwait = 1;
		$uadd_date = $now_date;
	 	mkdir($dirpath."$Config_datapath/$row_imp[0]", 0777);

		if($HTTP_POST_VARS[fld_wmail] == "1")
		{
		$mailheader="From: $Config_sitename <$Config_adminmail>\nX-Mailer: $strIndexWelcome\nContent-Type: text/plain";
		mail("$row_imp[2]","$strIndexWelcome","$sendmessage\n\n$Config_msgfooter","$mailheader");
		}
		}

		$result = queryDB( "INSERT INTO $tbl_userinfo VALUES('$row_imp[0]', '$row_imp[3]', '$row_imp[1]', '$row_imp[2]', '0', '0', '$uadmin_make', '0', '$uwait', '$Config_dprefs', '0*0|', '$uadd_date', '$Config_default_space|$Config_default_album|$Config_default_photo|$Config_default_remind', '0', '0', '$Config_AdminLangLoad', '$user_validity', '0')" );
		$result = queryDB( "DELETE FROM $tbl_publist WHERE email='$row_imp[2]'" );
	}
	}

	if($rejected_uid)
	$rejected_uid = "<p><b>$strAdminImportOpt9</b><br>$rejected_uid</p>";

	$errMsg = "<br><b>$i $strAdded</b>$rejected_uid\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '85' );
	echo("<BR>");
}
          $usr->Footer();
	    exit;
}

else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, $HTTP_REFERER", "2");

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsr");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>