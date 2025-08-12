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

if($dowhat == "addlang" || $dowhat == "editlang" || $dowhat == "dellang")
{

	if($langid && $dowhat == "dellang")
	{
	$i = 0;
	while($Config_list_langCode[$i])
	{
	list($langCode, $langName) = split('[|]', $Config_list_langCode[$i]);
	$i++;
	}

	if($i == 1)
	{
		$errMsg = "<b>$strConfigLangCmt4</b>";
		$dowhat  = "editlang";
	}

	else
	{
		$i = 0;
		while($Config_list_langCode[$i])
		{
			list($langCode, $langName) = split('[|]', $Config_list_langCode[$i]);
			if($langCode != $langid)
			{
				if(!$New_LangCode_List)		
				$New_LangCode_List .= "$langCode|$langName";
				else
				$New_LangCode_List .= ",$langCode|$langName";
			}
			else
			{
				if($langid == $Config_AdminLangLoad)
				$chgdef = 1;
			}
			$i++;
		}

		$result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$New_LangCode_List' WHERE fname='list_langCode'" );
		$result_confup = queryDB( "SELECT fnvalue FROM $tbl_config WHERE fname='list_langCode'" );
		$row_confup    = mysql_fetch_array( $result_confup );
		$Config_list_langCode = explode(",", $row_confup[fnvalue]);

		list($tmp_langCode, $tmp_langName) = split('[|]', $Config_list_langCode[0]);
		if($chgdef == 1)
		$result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$tmp_langCode' WHERE fname='langCode'" );
		$result_confup = queryDB( "UPDATE $tbl_userinfo SET langcode='$tmp_langCode' WHERE langcode='$langid'" );	

		$dowhat  = "editlang";
		$msgdel  = "<br><b>$strDeleted</b><br>";
	}
}

if($confirm == "1" && $dowhat == "addlang")
{
	 $langCode    = strtolower($langCode);
	 $chklangName = strtolower($langName);

       if(!$langCode)
	 $errMsg .=	"$strNo $strID<br>";

       if(!$langName)
	 $errMsg .=	"$strNo $strName<br>";

	if(!$errMsg)
	{
	 $i = 0;
	 while($Config_list_langCode[$i])
	 {
		$chkold_langName = strtolower($old_langName);
		list($old_langCode, $old_langName) = split('[|]', $Config_list_langCode[$i]);
		if($langCode == $old_langCode || $chklangName == $chkold_langName)
		{
			$errMsg .= "$strID/$strName $strExists<br>";
			break;
		}
	 $i++;
	 }
	}

	if(!$errMsg)
	{
	if(!file_exists("{$dirpath}essential/lang/$langCode.lang.php") || !file_exists($dirpath."essential/lang/$langCode.adminlang.php"))
	$errMsg .= "$strNo $langCode.lang.php / $langCode.adminlang.php<br>";	
	}
}

else if($confirm == "1" && $dowhat == "editlang")
{
$i = 0;
while($Config_list_langCode[$i])
{
	list($langCode, $langName) = split('[|]', $Config_list_langCode[$i]);
	if(!$varcheck)
	{
		$errMsg = "$strMissing<br>";
		break;
	}

$i++;
}

}

if($confirm != "1" || $errMsg)
{
?>
<html>
<head>
<title><?php echo ($Config_SiteTitle ." :: $strAdminstration :: $strAMenusConfiguration :: $strRegisterName6"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" HREF="<?php echo "{$dirpath}essential/{$Config_LangLoad}_default.css"; ?>" type="text/css">
</head>
<body bgcolor="#FFFFFF" background="<?php echo ($dirpath.$Config_imgdir); ?>/design/background.gif" leftmargin="0" topmargin="1" marginwidth="0" marginheight="0">
<?php
		 if($errMsg)
      	 { $usr->errMessage( $errMsg, $strError, 'error', '80' ); echo("<p>&nbsp;</p>"); }
?>
  <div align=center class=tn><?php echo $msgdel ?></div>
  <table width="98%" border="0" cellspacing="1" cellpadding="4" align="center">
    <tr> 
      <td class=tn align=center>
<?php
	if($dowhat == "editlang")
	{
?>

<table width="95%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#666666" class=tn>
  <tr> 
    <td colspan=2><b><font color=#EEEEEE><?php echo("$strEdit $strRegisterName6 [<a href=config.php?dowhat=addlang class=noundertnb>$strAdd</a>]"); ?></font></b></td>
  </tr>
<?php

$i = 0;
while($Config_list_langCode[$i])
{
list($langCode, $langName) = split('[|]', $Config_list_langCode[$i]);
?>
  <tr class=ts> 
    <td width="40%" bgcolor="#EEEEEE"><?php echo ("$strID: $langCode"); ?></td>
    <td width="60%" bgcolor="#EEEEEE">
      <?php echo $langName ?>
      <input type="hidden" name="<?php echo("lid_$langCode") ?>" value="<?php echo $langCode ?>">
	<?php echo("[<a href=config.php?dowhat=dellang&langid=$langCode>$strDelete</a>]"); ?>
    </td>
  </tr>
<?php
$i++;
}
?>
</table>
<?php
	}

	else if($dowhat == "addlang")
	{
?>
<form action=config.php method=post>
<table width="95%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#666666" class=tn>
  <tr> 
    <td colspan=2><b><font color=#EEEEEE><?php echo("$strAdd $strRegisterName6 [<a href=config.php?dowhat=editlang class=noundertnb>$strEdit</a>]"); ?></font></b></td>
  </tr>
  <tr> 
    <td width="40%" bgcolor="#EEEEEE"><?php echo ("$strID $strConfigLangCmt3"); ?></td>
    <td width="60%" bgcolor="#EEEEEE">
      <input type="text" name="langCode" maxlength=3>
    </td>
  </tr>
  <tr> 
    <td width="40%" bgcolor="#EEEEEE"><?php echo ("$strName $strConfigLangCmt2"); ?></td>
    <td width="60%" bgcolor="#EEEEEE">
      <input type="text" name="langName">
      <input type="hidden" name="confirm" value="1">
      <input type="hidden" name="dowhat" value="addlang">
      <input type="submit" name="Submit" value="<?php echo $strAdd ?> &gt;">
    </td>
  </tr>
</table>
</form>
<?php
echo ($strConfigLangCmt1);
}
?>
	</td>
    </tr>
  </table>
</div>
</body>
</html>
<?php
}

else
{
$langCode = strtolower($langCode);

$langCode = ereg_replace("\|", "", $langCode);
$langCode = ereg_replace("\+", "", $langCode);
$langName = ereg_replace("\|", "", $langName);
$langName = ereg_replace("\+", "", $langName);


// add lang
$result_confup = queryDB( "SELECT fnvalue FROM $tbl_config WHERE fname='list_langCode'" );
$row_confup    = mysql_fetch_array( $result_confup );

if($row_confup[fnvalue])
$row_confup[fnvalue] .= ",$langCode|$langName";
else
$row_confup[fnvalue] .= "$langCode|$langName";

$result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$row_confup[fnvalue]' WHERE fname='list_langCode'" );

?>

<html>
<head>
<title><?php echo ($Config_SiteTitle ." :: $strAdminstration :: $strAMenusConfiguration :: $strRegisterName6"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" HREF="<?php echo "{$dirpath}essential/{$Config_LangLoad}_default.css"; ?>" type="text/css">
</head>

<body bgcolor="#FFFFFF" background="<?php echo ($dirpath.$Config_imgdir); ?>/design/background.gif" leftmargin="0" topmargin="1" marginwidth="0" marginheight="0">
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align=center class=tn>
<?php $usr->errMessage( "$strDone [<a href=config.php?dowhat=addlang>$strAdd</a>][<a href=config.php?dowhat=editlang>$strEdit</a>]", $strSuccess, 'tick', '80');
echo("<p>&nbsp;</p><br>\n[<a href=\"javascript:self.close()\">$strClose</a>]"); ?>
</div>
</body>
</html>

<?php

}

exit;
}

else if($dowhat == "show" || !$dowhat)
{
         $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusConfiguration");
     	   echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/config.gif>&nbsp;</div><br>");

?>
<p>&nbsp;</p>
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center" class=tn bgcolor="#E8E6E6">
  <tr bgcolor="#F7F5F5"> 
    <td colspan=3> 
      <div align="right"><b>&lt; <?php echo $strConfigWelcome ?> &gt;&nbsp</b></div>
    </td>
  </tr>
  <tr bgcolor="#eeeeee"> 
    <td bgcolor="#eeeeee" width="33%"><a href="config.php?dowhat=edit&amp;catog=1" class=noundertn><?php echo $strConfigMenus[1] ?></a></td>
    <td width="34%"><a href="config.php?dowhat=edit&amp;catog=2" class=noundertn><?php echo $strConfigMenus[2] ?></a></td>
    <td width="33%"><a href="config.php?dowhat=edit&amp;catog=3" class=noundertn><?php echo $strConfigMenus[3] ?></a></td>
  </tr>
  <tr bgcolor="#eeeeee">
    <td bgcolor="#eeeeee" width="33%"><a href="config.php?dowhat=edit&amp;catog=4" class=noundertn><?php echo $strConfigMenus[4] ?></a></td>
    <td width="34%"><a href="config.php?dowhat=edit&amp;catog=5" class=noundertn><?php echo $strConfigMenus[5] ?></a></td>
    <td width="33%"><a href="config.php?dowhat=edit&amp;catog=6" class=noundertn><?php echo $strConfigMenus[6] ?></a></td>
  </tr>
</table>
<p>&nbsp;</p>

<?php
}

else if($dowhat == "edit")
{
if($confirm == 1)
$def_link = "&nbsp;<a href=config.php?dowhat=edit&catog=$catog>&lt;&lt; $strBack $strDefault</a><br>";

$usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusConfiguration :: $strConfigMenus[$catog]");
echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/config.gif>&nbsp;</div><br>");

$form_start =<<<__HTML_END_
<form action=config.php name=config method=post>
<table width="95%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr class=ts>
    <td width="45%" height="2"> 
      <div align="left" class=tn><a href=config.php?dowhat=show>$strAMenusConfiguration</a> &gt; <b>$strConfigMenus[$catog]</b></div>
    </td>
    <td width="60%" height="2" align=right>$def_link</td>
  </tr>
  <tr>
    <td width="45%" class="ts" height="2"><b class=impShow>$strConfigMenusAdvice[$catog]</b></td>
    <td width="60%" height="2" align=right>
<a href=#bottom><img src="$dirpath$Config_imgdir/design/icon_bottom.gif" border=0 alt="go to bottom of page"></a>&nbsp;&nbsp;<img src="$dirpath$Config_imgdir/{$Config_LangLoad}_headers/buttons/reset.gif" width="53" height="19" border="0" onclick="document.config.reset();">&nbsp;&nbsp;<input type="image" name="submit" src="$dirpath$Config_imgdir/{$Config_LangLoad}_headers/buttons/change.gif" width="53" height="19" border="0">
    </td>
  </tr>
__HTML_END_;

$form_end =<<<__HTML_END_
  <tr>
    <td width="45%" class="ts" height="2">[<a href="http://www.albinator.com/manual/admin_config.php" target=_blank>$strConfigOpenManual</a>]</td>
    <td width="60%" height="2" align=right>
      <input type="hidden" name="dowhat" value="edit">
      <input type="hidden" name="catog" value="$catog">
      <input type="hidden" name="confirm" value="1">
<img src="$dirpath$Config_imgdir/{$Config_LangLoad}_headers/buttons/reset.gif" width="53" height="19" border="0" onclick="document.config.reset();">&nbsp;&nbsp;<input type="image" name="submit" src="$dirpath$Config_imgdir/{$Config_LangLoad}_headers/buttons/change.gif" width="53" height="19" border="0">
    </td>
  </tr>
</table>
</form>
<a name=#bottom></a>
__HTML_END_;


if($catog == 1) // Name Variables
{
	if($confirm == 1)
	{
       if(empty($frm_Config_adminname))
	 $errMsg .=	"$strConfigCmt3<br>";

       if(!$frm_Config_adminmail || !CheckEmail($frm_Config_adminmail))
	 $errMsg .=	"$strConfigCmt4<br>";
	}

	if($confirm != 1 || $errMsg)
	{
	      if($errMsg)
	      {
      		$usr->errMessage( $errMsg, $strError, 'error', '70' );
			echo("<P>&nbsp;</P>");
	
			$frm = "frm_";
	      }   

echo($form_start);
?>

  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">sysName : Ver</td>
    <td width="60%" class="tn" bgcolor="#DDDDDD"><b>Albinator <?php echo $Config_system_version; ?></b></td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt1 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_adminname" maxlength="100" size="40" value="<?php echo ${$frm.Config_adminname}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt2 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_adminmail" size="40" maxlength="200" value="<?php echo ${$frm.Config_adminmail}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt3 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
<input type="text" name="frm_Config_sitename" size="40" value="<?php echo ${$frm.Config_sitename}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt4 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_systemname" size="40" value="<?php echo ${$frm.Config_systemname}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt5 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_buyline" size="40" value="<?php echo ${$frm.Config_buyline}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt6 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_SiteTitle" size="40" value="<?php echo ${$frm.Config_SiteTitle}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt12 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
   <input type="text" name="frm_Config_table_size" maxlength="3" size="40" value="<?php echo ${$frm.Config_table_size}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt13 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
  <input type="text" name="frm_Config_main_bgcolor" size="40" maxlength="7" value="<?php echo ${$frm.Config_main_bgcolor} ?>">
    </td>
  </tr>
<?php
echo($form_end);

	}

	else
	{
       if(!$frm_Config_systemname)
	 $frm_Config_systemname .= "Albinator";

	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_adminname' WHERE fname='adminname'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_table_size' WHERE fname='table_size'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_adminmail' WHERE fname='adminmail'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_sitename' WHERE fname='sitename'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_systemname' WHERE fname='systemname'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_buyline' WHERE fname='buyline'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_SiteTitle' WHERE fname='SiteTitle'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_main_bgcolor' WHERE fname='main_bgcolor'" );

	 ConfigSaved( $catog );
	}
}

else if($catog == 2)
{
	if($confirm == 1)
	{
       if(!$frm_Config_mainurl)
	 $errMsg .=	"$strConfigCmt5<br>";

       if(!$frm_Config_datapath)
	 $errMsg .=	"$strConfigCmt6<br>";

       if(!$frm_Config_cgidir)
	 $errMsg .=	"$strConfigCmt7<br>";

       if(!$frm_Config_imgdir)
	 $errMsg .=	"$strConfigCmt8<br>";

       if(!$frm_Config_abuse_link)
	 $errMsg .=	"$strConfigCmt21<br>"; 
	}

	if($confirm != 1 || $errMsg)
	{
	      if($errMsg)
	      {
      		$usr->errMessage( $errMsg, $strError, 'error', '70' );
			echo("<P>&nbsp;</P>");
	
			$frm = "frm_";
	      }   

echo($form_start);
?>

  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt8 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_rootdir" size="40" value="<?php echo ${$frm.Config_rootdir}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt7 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_mainurl" size="40" value="<?php echo ${$frm.Config_mainurl}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt9 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_cgidir" size="40" value="<?php echo ${$frm.Config_cgidir}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt10 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_imgdir" size="40" value="<?php echo ${$frm.Config_imgdir}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt11 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_datapath" size="40" value="<?php echo ${$frm.Config_datapath}; ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt14 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_main_bgimage" size="40" value="<?php echo ${$frm.Config_main_bgimage} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt28 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_abuse_link" size="40" value="<?php echo ${$frm.Config_abuse_link} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt35 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input name="frm_Config_buylink" size="40" value="<?php echo ${$frm.Config_buylink} ?>">
    </td>
  </tr>


<?php
echo($form_end);

	}

	else
	{
	 $result_confup = queryDB( "SELECT fnvalue FROM $tbl_config WHERE fname='datapath'" );
	 $row_datapath = mysql_fetch_array( $result_confup );

	 if($row_datapath[fnvalue] != $frm_datapath)
	 rename($dirpath.$row_datapath[fnvalue], $dirpath.$frm_Config_datapath);
	 
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_rootdir' WHERE fname='rootdir'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_mainurl' WHERE fname='mainurl'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_imgdir' WHERE fname='imgdir'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_cgidir' WHERE fname='cgidir'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_datapath' WHERE fname='datapath'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_main_bgimage' WHERE fname='main_bgimage'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_abuse_link' WHERE fname='abuse_link'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_buylink' WHERE fname='buylink'" );

	 ConfigSaved( $catog );
	}
}

else if($catog == 3)
{
	if($confirm == 1)
	{
       if((!$frm_Config_default_space && $frm_Config_default_space != "0") || $frm_Config_default_space < 0)
	 $errMsg .=	"$strConfigCmt12<br>"; 

       if((!$frm_Config_default_photo && $frm_Config_default_photo != "0") || $frm_Config_default_photo < 0)
	 $errMsg .=	"$strConfigCmt12b<br>"; 

       if((!$frm_Config_default_album && $frm_Config_default_album != "0") || $frm_Config_default_album < 0)
	 $errMsg .=	"$strConfigCmt13<br>"; 

       if((!$frm_Config_default_remind && $frm_Config_default_remind != "0") || $frm_Config_default_remind < 0)
	 $errMsg .=	"$strConfigCmt14<br>"; 
	}

	if($confirm != 1 || $errMsg)
	{
	      if($errMsg)
	      {
      		$usr->errMessage( $errMsg, $strError, 'error', '70' );
			echo("<P>&nbsp;</P>");
	
			$frm = "frm_";
	      }   

echo($form_start);
?>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt17 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_default_space" maxlength="3" size="40" value="<?php echo ${$frm.Config_default_space} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt17b ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_default_photo" maxlength="3" size="40" value="<?php echo ${$frm.Config_default_photo} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt18 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_default_album" maxlength="3" size="40" value="<?php echo ${$frm.Config_default_album} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt19 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_default_remind" maxlength="3" size="40" value="<?php echo ${$frm.Config_default_remind} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt29 ?> (<a href=http://www.albinator.com/manual/admin1.php#prefs target=_blank>details</a>)</td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_dprefs" size="40" value="<?php echo ${$frm.Config_dprefs} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt47 ?></td>
    <td width="60%" bgcolor="#DDDDDD">
      <input type="text" name="frm_Config_default_uvalid" size="40" value="<?php echo ${$frm.Config_default_uvalid} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo ("$strConfigOpt45 [<a href=config.php?dowhat=addlang target=terms onclick=\"openwin()\">$strAdd</a>] [<a href=config.php?dowhat=editlang target=terms onclick=\"openwin()\">$strEdit</a>]"); ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
	<select name="frm_New_langCode">
<?php
	if(!$frm)
	$New_langCode = $Config_AdminLangLoad;

	$i = 0;
	$sel = '';

	while($Config_list_langCode[$i])
	{ 
	  list($langCode, $langName) = split('[|]', $Config_list_langCode[$i]);

	  if(${$frm.New_langCode} == $langCode)
	  $sel = 'selected';
	  else
	  $sel = '';

	  echo ("<option value=\"$langCode\" $sel>$langName</option>\n");
	  $i++;
	}
?>
      </select>
      &nbsp;&nbsp;<input type="checkbox" name="frm_Config_langCodeForce" value="1" <?php if(${$frm.Config_langCodeForce} == "1") echo "checked"; ?>> <?php echo $strConfigOpt45b ?>
    </td>
  </tr>
  <tr> 
    <td colspan=2 class=ts><span class=impShow><?php echo("[<b>$strNote</b>: $strConfigCmt30]"); ?></span>
    </td>
  </tr>

<?php
echo($form_end);

	}

	else
	{
       if(!$frm_Config_langCodeForce)
	 $frm_Config_langCodeForce = "0";
	 
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_default_space' WHERE fname='default_space'");
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_default_photo' WHERE fname='default_photo'");
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_default_album' WHERE fname='default_album'");
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_default_remind' WHERE fname='default_remind'");
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_dprefs' WHERE fname='dprefs'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_default_uvalid' WHERE fname='default_uvalid'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_New_langCode' WHERE fname='langCode'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_langCodeForce' WHERE fname='langCodeForce'");

	 ConfigSaved( $catog );
	}
}

else if($catog == 4)
{
	if($confirm == 1)
	{
       if(!$frm_Config_show_min || $frm_Config_show_min < 0)
	 $errMsg .=	"$strConfigCmt15<br>"; 

       if(!$frm_Config_maxshow || $frm_Config_maxshow < 0)
	 $errMsg .=	"$strConfigCmt16<br>"; 

       if(!$frm_Config_remind_msg_max || $frm_Config_remind_msg_max < 0)
	 $errMsg .=	"$strConfigCmt17<br>"; 

       if(!$frm_Config_ecard_days || $frm_Config_ecard_days < 0)
	 $errMsg .=	"$strConfigCmt19<br>"; 

       if(!$frm_Config_unact_days || $frm_Config_unact_days < 0)
	 $errMsg .=	"$strConfigCmt20<br>"; 

       if((!$frm_Config_allowed_size && $frm_Config_allowed_size != "0") || $frm_Config_allowed_size < 0)
	 $errMsg .=	"$strConfigCmt18<br>"; 
	}

	if($confirm != 1 || $errMsg)
	{
	      if($errMsg)
	      {
      		$usr->errMessage( $errMsg, $strError, 'error', '70' );
			echo("<P>&nbsp;</P>");
	
			$frm = "frm_";
	      }   

echo($form_start);
?>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt20 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_show_min" size="40" maxlength="2" value="<?php echo ${$frm.Config_show_min} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt21 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_remind_msg_max" size="40" maxlength="10" value="<?php echo ${$frm.Config_remind_msg_max} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt22 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_maxshow" size="40" maxlength="10" value="<?php echo ${$frm.Config_maxshow} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt24 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_ecard_days" size="40" maxlength="3" value="<?php echo ${$frm.Config_ecard_days} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt25 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_unact_days" size="40" maxlength="3" value="<?php echo ${$frm.Config_unact_days} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt23 ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
      <input type="text" name="frm_Config_allowed_size" size="40" maxlength="10" value="<?php echo ${$frm.Config_allowed_size} ?>"> &nbsp;&nbsp;<?php echo ("0 = $strNoLimit") ?>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt53 ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=ts> 
      <input type="text" name="frm_Config_rateRecentNum" size="40" maxlength="10" value="<?php echo ${$frm.Config_rateRecentNum} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt54 ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
   <input type="text" name="frm_Config_topRatedNum" size="40" maxlength="10" value="<?php echo ${$frm.Config_topRatedNum} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt52 ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=ts> 
      <input type="text" name="frm_Config_slideShowTime" size="40" maxlength="10" value="<?php echo ${$frm.Config_slideShowTime} ?>">
    </td>
  </tr>

<?php
echo($form_end);

	}

	else
	{
       if(!$frm_Config_rateRecentNum || $frm_Config_rateRecentNum < 0)
	 $frm_Config_rateRecentNum = $Config_rateRecentNum;

       if(!$frm_Config_topRatedNum || $frm_Config_topRatedNum < 0)
	 $frm_Config_topRatedNum = $Config_topRatedNum;

       if(!$frm_Config_slideShowTime || $frm_Config_slideShowTime < 0)
	 $frm_Config_slideShowTime = $Config_slideShowTime;

	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_show_min' WHERE fname='show_min'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_maxshow' WHERE fname='maxshow'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_ecard_days' WHERE fname='ecard_days'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_unact_days' WHERE fname='unact_days'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_allowed_size' WHERE fname='allowed_size'" );
	 $result_confup =queryDB("UPDATE $tbl_config SET fnvalue='$frm_Config_remind_msg_max' WHERE fname='remind_msg_max'");

	 // Addon
	 $result_confup =queryDB("UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_rateRecentNum' WHERE fname='rateRecentNum'");
	 $result_confup =queryDB("UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_topRatedNum' WHERE fname='topRatedNum'");
	 $result_confup =queryDB("UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_slideShowTime' WHERE fname='slideShowTime'");
	 //

	 ConfigSaved( $catog );
	}
}

else if($catog == 5)
{
	if($confirm == 1)
	{
       if((!$frm_Config_allowed_size && $frm_Config_allowed_size != "0") || $frm_Config_allowed_size < 0)
	 $errMsg .=	"$strConfigCmt18<br>"; 

       if(!$frm_Config_tbwidth_short)
	 $errMsg .=	"$strConfigCmt24<br>"; 
       if(!$frm_Config_tbheight_short)
	 $errMsg .=	"$strConfigCmt25<br>"; 
       if(!$frm_Config_tbwidth_long)
	 $errMsg .=	"$strConfigCmt26<br>"; 
       if(!$frm_Config_tbheight_long)
	 $errMsg .=	"$strConfigCmt27<br>"; 

       if((!$frm_Config_exceed_width && $frm_Config_exceed_width != "0") || $frm_Config_exceed_width < 0)
	 $errMsg .=	"$strConfigCmt28<br>"; 
       if((!$frm_Config_exceed_height && $frm_Config_exceed_height != "0") || $frm_Config_exceed_height < 0)
	 $errMsg .=	"$strConfigCmt29<br>"; 

       if(!$frm_Config_allow_types)
	 $errMsg .=	"$strConfigCmt22<br>"; 

       if(!$frm_Config_allow_types_show)
	 $errMsg .=	"$strConfigCmt23<br>"; 
	}

	if($confirm != 1 || $errMsg)
	{
	      if($errMsg)
	      {
      		$usr->errMessage( $errMsg, $strError, 'error', '70' );
			echo("<P>&nbsp;</P>");
	
			$frm = "frm_";
	      }   

echo($form_start);
?>

  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt23 ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
 <input type="text" name="frm_Config_allowed_size" size="40" maxlength="10" value="<?php echo ${$frm.Config_allowed_size} ?>">
 &nbsp;&nbsp;<?php echo ("0 = $strNoLimit") ?>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo $strConfigOpt40 ?></td>
    <td width="60%" bgcolor="#DDDDDD" height="2" class=ts> 
      <input type="text" name="frm_Config_exceed_width" size="40" value="<?php echo ${$frm.Config_exceed_width} ?>">
 &nbsp;&nbsp;<?php echo ("0 = $strNoLimit") ?>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo $strConfigOpt41 ?></td>
    <td width="60%" bgcolor="#CCCCCC" height="2" class=ts> 
      <input type="text" name="frm_Config_exceed_height" size="40" value="<?php echo ${$frm.Config_exceed_height} ?>">
 &nbsp;&nbsp;<?php echo ("0 = $strNoLimit") ?>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt42 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
	<select name="frm_Config_forceSize">
	<option value="1" <?php if(${$frm.Config_forceSize} == "1") echo "selected"; ?>><?php echo $strYes ?></option>
	<option value="0" <?php if(${$frm.Config_forceSize} == "0") echo "selected"; ?>><?php echo $strNo ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo $strConfigOpt43 ?></td>
    <td width="60%" bgcolor="#CCCCCC" height="2" class=ts> 
	<select name="frm_Config_ResizeBy">
	<option value="1" <?php if(${$frm.Config_ResizeBy} == "1") echo "selected"; ?>>GD Library</option>
	<option value="2" <?php if(${$frm.Config_ResizeBy} == "2") echo "selected"; ?>>Imagemagick</option>
	<option value="3" <?php if(${$frm.Config_ResizeBy} == "3") echo "selected"; ?>>GD 1.6 (256 colors)</option>
      </select>&nbsp;&nbsp;<?php echo("<br>$strConfigCmt31"); ?>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt30a ?> [<a href=http://www.albinator.com/manual/admin_config_5.php#a5 target=_blank><?php echo $strList ?></a>]</td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_allow_types" size="40" value="
<?php

if(!$frm)
{
	$i = 0;

	while($Config_allow_types[$i])
	{ 
	  if($i != 0)
	  echo (",");

	  echo ($Config_allow_types[$i]);
	  $i++;
	}
}

else
echo ${$frm.Config_allow_types};

?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt31 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_allow_types_show" size="40" value="<?php echo ${$frm.Config_allow_types_show} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo $strConfigOpt44 ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=tn> 
	Orignal + <select name="frm_Config_spaceScheme">
	<option value="0" <?php if(${$frm.Config_spaceScheme} == "0") echo "selected"; ?>><?php echo $strNone ?></option>
	<option value="A" <?php if(${$frm.Config_spaceScheme} == "A") echo "selected"; ?>><?php echo $strConfigOpt44a ?></option>
	<option value="B" <?php if(${$frm.Config_spaceScheme} == "B") echo "selected"; ?>><?php echo $strConfigOpt44b ?></option>
	<option value="AB" <?php if(${$frm.Config_spaceScheme} == "AB") echo "selected"; ?>><?php echo $strConfigOpt44c ?></option>
      </select>
	<input type=hidden name=Old_spaceScheme value="<?php echo $Config_spaceScheme ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt55 ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
	<select name="frm_Config_rateRules">
	<option value="0" <?php if(${$frm.Config_rateRules} == "0") echo "selected"; ?>><?php echo $strAll ?></option>
	<option value="1" <?php if(${$frm.Config_rateRules} == "1") echo "selected"; ?>><?php echo $strUser ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt56 ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=ts> 
	<select name="frm_Config_rateDisplayRules">
	<option value="1" <?php if(${$frm.Config_rateDisplayRules} == "1") echo "selected"; ?>><?php echo ("$strConfigOpt56a"); ?></option>
	<option value="2" <?php if(${$frm.Config_rateDisplayRules} == "2") echo "selected"; ?>><?php echo ("$strConfigOpt56b"); ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">&nbsp;</td>
    <td width="60%" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><b><?php echo $strConfigOpt57 ?></b></td>
    <td width="60%" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt57a ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=ts> 
      <input type="text" name="frm_Config_photoCmtMaxLimit" size="40" value="<?php echo ${$frm.Config_photoCmtMaxLimit} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt57b ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
	<select name="frm_Config_photoCmtUrlParse">
	<option value="0" <?php if(${$frm.Config_photoCmtUrlParse} == "0") echo "selected"; ?>><?php echo ("$strYes"); ?></option>
	<option value="1" <?php if(${$frm.Config_photoCmtUrlParse} == "1") echo "selected"; ?>><?php echo ("$strNo"); ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt57c ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=ts> 
	<select name="frm_Config_photoCmtRulesAdd">
	<option value="0" <?php if(${$frm.Config_photoCmtRulesAdd} == "0") echo "selected"; ?>><?php echo ("$strAll"); ?></option>
	<option value="1" <?php if(${$frm.Config_photoCmtRulesAdd} == "1") echo "selected"; ?>><?php echo ("$strUser"); ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt57d ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
	<select name="frm_Config_photoCmtRulesModerate">
	<option value="0" <?php if(${$frm.Config_photoCmtRulesModerate} == "0") echo "selected"; ?>><?php echo ("$strYes"); ?></option>
	<option value="1" <?php if(${$frm.Config_photoCmtRulesModerate} == "1") echo "selected"; ?>><?php echo ("$strNo"); ?></option>
      </select>
    </td>
  </tr>
  <tr>
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt57e ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=ts> 
	<select name="frm_Config_photoCmtRulesAddDual">
	<option value="0" <?php if(${$frm.Config_photoCmtRulesAddDual} == "0") echo "selected"; ?>><?php echo ("$strYes"); ?></option>
	<option value="1" <?php if(${$frm.Config_photoCmtRulesAddDual} == "1") echo "selected"; ?>><?php echo ("$strNo"); ?></option>
      </select>
    </td>
  </tr>

  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE">&nbsp;</td>
    <td width="60%" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><b><?php echo $strConfigOpt34a ?></b></td>
    <td width="60%" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><i><?php echo $strConfigOpt34 ?></i></td>
    <td width="60%" bgcolor="#EEEEEE">&nbsp; </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt38 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_tbwidth_short" size="40" value="<?php echo ${$frm.Config_tbwidth_short} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt39 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_tbheight_short" size="40" value="<?php echo ${$frm.Config_tbheight_short} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><i><?php echo $strConfigOpt37 ?></i></td>
    <td width="60%" bgcolor="#EEEEEE">&nbsp;</td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt38 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_tbwidth_long" size="40" value="<?php echo ${$frm.Config_tbwidth_long} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt39 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_tbheight_long" size="40" value="<?php echo ${$frm.Config_tbheight_long} ?>">
    </td>
  </tr>
<?php
echo($form_end);

	}

	else
	{
       $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_tbwidth_long' WHERE fname='tbwidth_long'" );
       $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_tbheight_long' WHERE fname='tbheight_long'");
       $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_tbwidth_short' WHERE fname='tbwidth_short'");
       $result_confup =queryDB("UPDATE $tbl_config SET fnvalue='$frm_Config_tbheight_short' WHERE fname='tbheight_short'");
       $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_exceed_width' WHERE fname='exceed_width'" );
       $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_exceed_height' WHERE fname='exceed_height'");
       $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_ResizeBy' WHERE fname='ResizeBy'" );
       $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_forceSize' WHERE fname='forceSize'" );
       $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_spaceScheme' WHERE fname='spaceScheme'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_allowed_size' WHERE fname='allowed_size'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_allow_types' WHERE fname='allow_types'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_allow_types_show' WHERE fname='allow_types_show'" );

	 // Addon
	 $result_confup = queryDB( "UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_rateRules' WHERE fname='rateRules'" );
	 $result_confup = queryDB( "UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_rateDisplayRules' WHERE fname='rateDisplayRules'" );

	 $result_confup = queryDB( "UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_photoCmtMaxLimit' WHERE fname='photoCmtMaxLimit'" );
	 $result_confup = queryDB( "UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_photoCmtUrlParse' WHERE fname='photoCmtUrlParse'" );
	 $result_confup = queryDB( "UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_photoCmtRulesAdd' WHERE fname='photoCmtRulesAdd'" );
	 $result_confup = queryDB( "UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_photoCmtRulesModerate' WHERE fname='photoCmtRulesModerate'" );
	 $result_confup = queryDB( "UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_photoCmtRulesAddDual' WHERE fname='photoCmtRulesAddDual'" );
	 //

	 if($frm_Config_spaceScheme != $Old_spaceScheme)
	 $spaceSchemeAlert = "<br><b>$strNote</b>: $strConfigCmt2, <a href=revise.php target=terms onclick=\"openwin()\">$strClickhere</a><br>";

	 ConfigSaved( $catog, $spaceSchemeAlert );
	}
}


else if($catog == 6)
{
	if($confirm == 1)
	{
	}

	if($confirm != 1 || $errMsg)
	{
	      if($errMsg)
	      {
      		$usr->errMessage( $errMsg, $strError, 'error', '70' );
			echo("<P>&nbsp;</P>");
	
			$frm = "frm_";
	      }   

echo($form_start);
?>
  <tr bgcolor=#EEEEEE> 
    <td width="45%" class="tn"><b><?php echo $strConfigType1 ?></b></td>
    <td width="60%">&nbsp;</td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt33 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <textarea name="frm_Config_msgfooter" cols="35"><?php echo ${$frm.Config_msgfooter} ?></textarea>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt33b ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <textarea name="frm_Config_site_msg" cols="35" rows="5"><?php echo ${$frm.Config_site_msg} ?></textarea>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt33c ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <textarea name="frm_Config_blockmsg" cols="35" rows="5"><?php if(${$frm.Config_blockmsg}) echo ${$frm.Config_blockmsg} ?></textarea>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt33d ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <textarea name="frm_Config_blockmsgEar" cols="35" rows="5"><?php if(${$frm.Config_blockmsgEar}) echo ${$frm.Config_blockmsgEar} ?></textarea>
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td width="45%">&nbsp;</td>
    <td width="60%">&nbsp;</td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td width="45%" class="tn"><b><?php echo $strConfigType2 ?></b></td>
    <td width="60%">&nbsp;</td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt27 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
	<select name="frm_Config_shut_logoff">
        <option value="1" <?php if(${$frm.Config_shut_logoff} == 1) echo "selected"; ?>><?php echo $strYes ?></option>
        <option value="0" <?php if(${$frm.Config_shut_logoff} == 0) echo "selected"; ?>><?php echo $strNo ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt16 ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
      <input type="text" name="frm_Config_p" size="40" maxlength="2" value="<?php echo ${$frm.Config_p} ?>">
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt15 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
      <input type="text" name="frm_Config_logout_time" size="40" value="<?php echo ${$frm.Config_logout_time} ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td width="45%">&nbsp;</td>
    <td width="60%">&nbsp;</td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td width="45%" class="tn"><b><?php echo $strConfigType3 ?></b></td>
    <td width="60%">&nbsp;</td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo $strConfigOpt46 ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=tn> 
	<select name="frm_Config_showProcessTime">
	<option value="1" <?php if(${$frm.Config_showProcessTime} == "1") echo "selected"; ?>><?php echo $strYes ?></option>
	<option value="0" <?php if(${$frm.Config_showProcessTime} == "0") echo "selected"; ?>><?php echo $strNo ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo ("$strConfigOpt50") ?></td>
    <td width="60%" bgcolor="#CCCCCC"> 
	<select name="frm_Config_dlist_priv_show">
        <option value="1" <?php if(${$frm.Config_dlist_priv_show} == 1) echo "selected"; ?>><?php echo $strYes ?></option>
        <option value="0" <?php if(${$frm.Config_dlist_priv_show} == 0) echo "selected"; ?>><?php echo $strNo ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt26 ?></td>
    <td width="60%" bgcolor="#DDDDDD"> 
	<select name="frm_Config_makelogs">
        <option value="1" <?php if(${$frm.Config_makelogs} == 1) echo "selected"; ?>><?php echo $strYes ?></option>
        <option value="0" <?php if(${$frm.Config_makelogs} == 0) echo "selected"; ?>><?php echo $strNo ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo $strConfigOpt48 ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=tn> 
	<select name="frm_Config_BlockNotify">
	<option value="0" <?php if(${$frm.Config_BlockNotify} == "0") echo "selected"; ?>><?php echo $strNone ?></option>
	<option value="1" <?php if(${$frm.Config_BlockNotify} == "1") echo "selected"; ?>><?php echo $strUser ?></option>
	<option value="2" <?php if(${$frm.Config_BlockNotify} == "2") echo "selected"; ?>><?php echo $strAdmin ?></option>
	<option value="3" <?php if(${$frm.Config_BlockNotify} == "3") echo "selected"; ?>><?php echo ("$strUser + $strAdmin") ?></option>
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo $strConfigOpt49 ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=tn> 
      <input type="text" name="frm_Config_BlockNotifyDay" size="35" maxlength="5" value="<?php echo ${$frm.Config_BlockNotifyDay} ?>">
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE"><?php echo $strConfigOpt32 ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=ts> 
      <input type="text" name="frm_Config_bad_user_name" size="35" value="
<?php


if(!$frm)
{
	$i = 0;

	while($Config_bad_user_name[$i])
	{ 
	  if($i != 0)
	  echo (",");

	  echo ($Config_bad_user_name[$i]);
	  $i++;
	}
}

else
echo ${$frm.Config_bad_user_name};

?>"> <?php echo $strConfigOpt32b ?>

    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo $strConfigOpt51 ?></td>
    <td width="60%" bgcolor="#DDDDDD" class=tn> 
      <input type="text" name="frm_Config_ban_sterms" size="35" value="<?php echo ${$frm.Config_ban_sterms} ?>">
      </select>
    </td>
  </tr>
  <tr> 
    <td width="45%" class="tn" bgcolor="#EEEEEE" height="2"><?php echo ("$strProfile $strSearch") ?></td>
    <td width="60%" bgcolor="#CCCCCC" class=tn> 
	<input type="radio" name="frm_Config_pSearch" value="1" <?php if(${$frm.Config_pSearch} == "1") echo "checked"; ?>>
	pulldown&nbsp;&nbsp;
	<input type="radio" name="frm_Config_pSearch" value="2" <?php if(${$frm.Config_pSearch} == "2") echo "checked"; ?>>
	checkbox&nbsp;&nbsp;
    </td>
  </tr>
<?php
echo($form_end);

	}

	else
	{
       if(!$frm_Config_bad_user_name)
	 $frm_Config_bad_user_name = "temp,system";
	 else
	 {
		$temparr = explode(",", $frm_Config_bad_user_name);
		if(!in_array("system", $temparr))
		$frm_Config_bad_user_name .= ",system";
		if(!in_array("temp", $temparr))
		$frm_Config_bad_user_name .= ",temp";
	 }

	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_logout_time' WHERE fname='logout_time'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_p' WHERE fname='p'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_makelogs' WHERE fname='makelogs'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_shut_logoff' WHERE fname='shut_logoff'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_bad_user_name' WHERE fname='bad_user_name'");
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_msgfooter' WHERE fname='msgfooter'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_site_msg' WHERE fname='site_msg'" );

	 $result_confup =queryDB("UPDATE $tbl_config SET fnvalue='$frm_Config_BlockNotifyDay' WHERE fname='BlockNotifyDay'");
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_blockmsgEar' WHERE fname='blockmsgEar'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_blockmsg' WHERE fname='blockmsg'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_BlockNotify' WHERE fname='BlockNotify'" );

	 $frm_Config_ban_sterms = strtolower($frm_Config_ban_sterms);
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_ban_sterms' WHERE fname='ban_sterms'" );

	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_dlist_priv_show' WHERE fname='dlist_priv_show'" );
	 $result_confup = queryDB( "UPDATE $tbl_config SET fnvalue='$frm_Config_showProcessTime' WHERE fname='showProcessTime'" );

	 // Addon
	 $result_confup = queryDB( "UPDATE IGNORE $tbl_config SET fnvalue='$frm_Config_pSearch' WHERE fname='pSearch'" );
	 //

	 ConfigSaved( $catog );
	}
}

else
{
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
}
}

else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration $strAMenusConfiguration");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/config.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 


function ConfigSaved( $category, $sp = '' )
{
global $usr, $csr, $uid, $Config_makelogs, $Config_LangLoad, $dirpath, $Config_imgdir, $strConfigCmt1, $strChange, $strAMenusConfiguration;

	   if($Config_makelogs == "1")
	   $csr->MakeAdminLogs( $uid, "$strConfigCmt1", "2"); 

	   $errMsg = "<b>$strConfigCmt1</b>, [<a href=config.php?dowhat=edit&catog=$category>$strChange</a> :: <a href=config.php?dowhat=show>$strAMenusConfiguration</a>]$sp\n";
	   $usr->errMessage( $errMsg, '', 'tick', '60' );
	   echo("<BR>");
}

?>