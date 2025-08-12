<?php

/***************************************************************************

 userdata.php
 -------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/


function userdatamain($location)
{
	switch($location) {
		case 'header' : $GLOBALS["userdataalign"] = $GLOBALS["right"];
						$GLOBALS["trclass"] = 'topback';
						break;
		case 'menu'	  : $GLOBALS["userdataalign"] = $GLOBALS["left"];
						$GLOBALS["trclass"] = 'menu';
						break;
	}

	$strQuery = "SELECT userdataname FROM ".$GLOBALS["eztbUserdata"]." WHERE userdataenabled='1' ORDER BY userdataorderid";
	$result = dbRetrieve($strQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		$userdataname			= $rs["userdataname"];
		$userdataloginreq = $rs["userdataloginreq"];
		switch ($userdataname) {
			case 'loginmessage'	  : loginmessage();
									break;
			case 'loginlogout'	  : loginlogout();
									break;
			case 'adminfunctions' : adminfunctions();
                                                			break;
			case 'preferences'	  : preferences();
									break;
			case 'memberlist'	  : memberlist();
									break;
			case 'language'		  : showlanguage();
									break;
			case 'selectlanguage' : selectlanguage();
									break;
			case 'site'			  : showsite();
									break;
			case 'selectsite'	  : selectsite();
									break;
			//case 'theme'		  : showtheme();
			//						break;
			//case 'selecttheme'	  : selecttheme();
			//						break;
			case 'homesite'		  : homesite();
									break;
		}
	}
	dbFreeResult($result);
}


function opentable()
{
	?>
	<tr class="<?php echo $GLOBALS["trclass"]; ?>"><td align="<?php echo $GLOBALS["userdataalign"]; ?>" valign="top">
	<?php
} // function opentable()


function openformtable($formname,$formaction,$prompt,$fieldname)
{
	global $EZ_SESSION_VARS;

	echo '<form name="'.$formname.'" action="'.$formaction.'.php" method="GET" enctype="multipart/form-data">';
	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		?>
		<script>
		<!-- Begin
			function <?php echo $formaction; ?>(sParams) {
				top.ezc.location = '<?php echo BuildLink($formaction.'.php'); ?>&<?php echo $fieldname; ?>=' + sParams;
				return false;
			}
		//  End -->
		</script>
		<?php
	}
	opentable();

	echo $prompt.'&nbsp;:&nbsp;';
	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		echo '<select name="'.$fieldname.'" onChange="'.$formaction.'('.$fieldname.'.value)" size="1" class="smalldropdown">';
	} else {
		echo '<select name="'.$fieldname.'" onChange="submit()" size="1" class="smalldropdown">';
	}
} // function openformtable()


function closetable()
{
	?>
	</td></tr>
	<?php
} // function closetable()


function closeformtable()
{
	global $_SERVER, $EZ_SESSION_VARS;

	echo '</select>&nbsp;';
	if (($GLOBALS["gsUseFrames"] != 'Y') || ($EZ_SESSION_VARS["noframesbrowser"] == True)) {
		//echo '<input type="image" name="submit" src="./'.$GLOBALS["icon_home"].'go.gif" alt="Go" value="Go">';
	} else {
		if (ereg("Opera", $_SERVER["HTTP_USER_AGENT"])) {
			//echo '<input type="image" name="submit" src="./'.$GLOBALS["icon_home"].'go.gif" alt="Go" value="Go">';
		}
	}
	closetable();
	echo '<input type="hidden" name="ezSID" value="'.$GLOBALS["ezSID"].'">';
	echo '<input type="hidden" name="noframesbrowser" value="'.$EZ_SESSION_VARS["noframesbrowser"].'">';
	echo '<input type="hidden" name="topgroupname" value="'.$_GET["topgroupname"].'">';
	echo '<input type="hidden" name="groupname" value="'.$_GET["groupname"].'">';
	echo '<input type="hidden" name="subgroupname" value="'.$_GET["subgroupname"].'">';
	echo '<input type="hidden" name="contentname" value="'.$_GET["contentname"].'">';
	echo '<input type="hidden" name="page" value="'.$_GET["page"].'">';
	echo '<input type="hidden" name="link" value="'.$_GET["link"].'">';
	echo '<input type="hidden" name="ref" value="'.$_GET["ref"].'">';
	echo '<input type="hidden" name="submitted" value="yes">';
	echo '</form>';
} // function closeformtable()


function homesite()
{
	if ($GLOBALS["gsMultiSite"] == 'Y') {
		opentable();
		closetable();
	}
} // function homesite()


function loginmessage()
{
	global $EZ_SESSION_VARS;

	opentable();
	if ($EZ_SESSION_VARS["PasswordCookie"] != '') {
		echo $GLOBALS["tLoggedInAs"].' <b>'.$EZ_SESSION_VARS["UserName"].'</b>';
	} else {
		echo $GLOBALS["tNotLoggedIn"];
	}
	closetable();
} // function loginmessage()


function loginlogout()
{
	global $EZ_SESSION_VARS, $_GET;

	opentable();
	if ($EZ_SESSION_VARS["PasswordCookie"] != '') {
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			echo '<a href="'.BuildLink('logout.php').'&link='.$_GET["link"].'&ref=control.php'.BuildGroupsLink().'" target=ezc';
		} else {
			echo '<a href="'.BuildLink('logout.php').'&link='.$_GET["link"].'&ref=control.php'.BuildGroupsLink().'"';
		}
		echo BuildLinkMouseOver($GLOBALS["tmLogout"]).'>'.$GLOBALS["tmLogout"].'</a><br />';
	} else {
		?><span style="cursor:hand"><a onClick="javascript:window.open('<?php echo BuildLink('login.php'); ?>&topgroupname=<?php echo $_GET["topgroupname"]; ?>&groupname=<?php echo $_GET["groupname"]; ?>&subgroupname=<?php echo $_GET["subgroupname"]; ?>', 'Login', 'width=340,height=180,status=no,resizable=yes,scrollbars=no'); return(false);" <?php echo BuildLinkMouseOver($GLOBALS["tmLogin"]); ?> target="popup"><?php
		echo $GLOBALS["tmLogin"].'</a></span><br />';
	}
	closetable();
} // function loginlogout()


function adminfunctions()
{
	global $EZ_SESSION_VARS;

	if (($EZ_SESSION_VARS["PasswordCookie"] != '') && ($EZ_SESSION_VARS["UserGroup"] == $GLOBALS["gsAdminPrivGroup"])) {
			opentable();
			echo '<a href="'.BuildLink($GLOBALS["admin_home"].'index.php').'" target="_top"';
			echo BuildLinkMouseOver($GLOBALS["tAdminFunctions"]).'>'.$GLOBALS["tAdminFunctions"].'</a><br />';
			closetable();
		}
	                
} // function adminfunctions()


function preferences()
{
	global $EZ_SESSION_VARS;

	opentable();
	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		echo '<a href="'.BuildLink('userinfo.php').'" target="contents"';
	} else {
		echo '<a href="'.BuildLink('control.php').'&link=userinfo.php'.BuildGroupsLink().'"';
	}
	if (($userdataloginreq != 'Y') || ($EZ_SESSION_VARS["PasswordCookie"] != '')) {
		echo BuildLinkMouseOver($GLOBALS["tPreferences"]).'>'.$GLOBALS["tPreferences"].'</a><br />';
	} else {
		echo BuildLinkMouseOver($GLOBALS["tRegister"]).'>'.$GLOBALS["tRegister"].'</a><br />';
	}
	closetable();
} // function preferences()


function memberlist()
{
	global $EZ_SESSION_VARS;

	if ($EZ_SESSION_VARS["PasswordCookie"] != '') {
		opentable();
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			echo '<a href="'.BuildLink('memberlist.php').'" target="contents"';
		} else {
			echo '<a href="'.BuildLink('control.php').'&link=memberlist.php'.BuildGroupsLink().'"';
		}
		echo BuildLinkMouseOver($GLOBALS["tMemberList"]).'>'.$GLOBALS["tMemberList"].'</a>';
		closetable();
	}
} // function memberlist()


function showlanguage()
{
	if ($GLOBALS["gsMultiLanguage"] == 'Y') {
		opentable();
		echo $GLOBALS["tLanguage"].':&nbsp;';
		$strQuery = "SELECT languagename FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$GLOBALS["gsLanguage"]."'";
		$result = dbRetrieve($strQuery,true,0,1);
		if ($rs = dbFetch($result)) { echo $rs["languagename"]; }
		dbFreeResult($result);
		closetable();
	}
} // function showlanguage()


function selectlanguage()
{
	global $EZ_SESSION_VARS, $_GET;

	if ($GLOBALS["gsMultiLanguage"] == 'Y') {
		$strQuery = "SELECT languagecode FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y'";
		$sresult = dbRetrieve($strQuery,true,0,0);
		$sRecCount = dbRowsReturned($sresult);
		dbFreeResult($sresult);

		if ($sRecCount > 0) {
			openformtable('Userdata_Languages','setlanguage',$GLOBALS["tLanguage"],'languagecode');
			$strQuery = "SELECT languagecode,languagename FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y'";
			$result = dbRetrieve($strQuery,true,0,0);
			while ($rs = dbFetch($result)) {
				echo '<option ';
				if (($EZ_SESSION_VARS["Language"] != '') && ($rs["languagecode"] == $EZ_SESSION_VARS["Language"])) {
					echo 'selected ';
				} elseif (($EZ_SESSION_VARS["Language"] == '') && ($rs["languagecode"] == $GLOBALS["gsLanguage"])) {
					echo 'selected ';
				}
				echo 'value="'.$rs["languagecode"].'">'.$rs["languagename"];
			}
			dbFreeResult($result);
			closeformtable();
		} else { showlanguage(); }
	}
} // function selectlanguage()


function showsite()
{
	global $EZ_SESSION_VARS;

	if ($GLOBALS["gsMultiSite"] == 'Y') {
		opentable();
		echo $GLOBALS["tSite"].':&nbsp;';
		if ($EZ_SESSION_VARS["Site"] != '') {
			$strQuery = "SELECT sitename FROM ".$GLOBALS["eztbSites"]." WHERE sitecode='".$EZ_SESSION_VARS["Site"]."'";
			$result = dbRetrieve($strQuery,true,0,1);
			if ($rs = dbFetch($result)) { echo $rs["sitename"]; }
			dbFreeResult($result);
		} else { echo $GLOBALS["tMasterSite"]; }
		closetable();
	}
} // function showsite()


function selectsite()
{
	global $EZ_SESSION_VARS, $_GET;

	if ($GLOBALS["gsMultiSite"] == 'Y') {
		$strQuery = "SELECT sitecode FROM ".$GLOBALS["eztbSites"]." WHERE siteenabled='1'";
		$sresult = dbRetrieve($strQuery,true,0,0);
		$sRecCount = dbRowsReturned($sresult);
		dbFreeResult($sresult);

		if ($sRecCount > 0) {
			openformtable('Userdata_Sites','selectsite',$GLOBALS["tSite"],'Site');
			$strQuery = "SELECT sitecode,sitename FROM ".$GLOBALS["eztbSites"]." WHERE siteenabled='1' ORDER BY sitename";
			$result = dbRetrieve($strQuery,true,0,0);
			echo '<option ';
			if ($EZ_SESSION_VARS["Site"] == '') { echo 'selected '; }
			echo 'value="">'.$GLOBALS["tMasterSite"];
			while ($rs = dbFetch($result)) {
				echo '<option ';
				if ($rs["sitecode"] == $EZ_SESSION_VARS["Site"]) { echo 'selected '; }
				echo 'value="'.$rs["sitecode"].'">'.$rs["sitename"];
			}
			dbFreeResult($result);
			closeformtable();
		} else { showsite(); }
	}
} // function selectsite()


function showtheme()
{
	global $EZ_SESSION_VARS;

	if ($GLOBALS["gsMultiTheme"] == 'Y') {
		opentable();
		echo $GLOBALS["tTheme"].':&nbsp;';
		if ($EZ_SESSION_VARS["Theme"] != '') {
			$strQuery = "SELECT themename FROM ".$GLOBALS["eztbThemes"]." WHERE themecode='".$EZ_SESSION_VARS["Theme"]."'";
			$result = dbRetrieve($strQuery,true,0,1);
			if ($rs = dbFetch($result)) { echo $rs["themename"]; }
			dbFreeResult($result);
		} else { echo $GLOBALS["tDefaultTheme"]; }
		closetable();
	}
} // function showtheme()


function selecttheme()
{
	global $EZ_SESSION_VARS, $_GET;

	if ($GLOBALS["gsMultiTheme"] == 'Y') {
		$strQuery = "SELECT themecode FROM ".$GLOBALS["eztbThemes"]." WHERE themeenabled='1'";
		$sresult = dbRetrieve($strQuery,true,0,0);
		$sRecCount = dbRowsReturned($sresult);
		dbFreeResult($sresult);

		if ($sRecCount > 0) {
			openformtable('Userdata_Themes','selecttheme',$GLOBALS["tTheme"],'Theme');
			$strQuery = "SELECT themecode,themename FROM ".$GLOBALS["eztbThemes"]." WHERE themeenabled='1' ORDER BY themename";
			$result = dbRetrieve($strQuery,true,0,0);
			echo '<option ';
			if ($EZ_SESSION_VARS["Theme"] == '') { echo 'selected '; }
			echo 'value="">'.$GLOBALS["tMasterTheme"];
			while ($rs = dbFetch($result)) {
				echo '<option ';
				if ($rs["themecode"] == $EZ_SESSION_VARS["Theme"]) { echo 'selected '; }
				echo 'value="'.$rs["themecode"].'">'.$rs["themename"];
			}
			dbFreeResult($result);
			closeformtable();
		} else { showtheme(); }
	}
} // function selecttheme()

?>
