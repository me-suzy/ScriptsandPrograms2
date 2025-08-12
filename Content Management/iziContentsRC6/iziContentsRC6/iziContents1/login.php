<?php

/***************************************************************************

 login.php
 ----------
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");
if ((!isset($_GET["ezSID"])) && (isset($_POST["ezSID"]))) $_GET["ezSID"] = $_POST["ezSID"];

include_once ($GLOBALS["rootdp"]."include/settings.php");
include_once ($GLOBALS["rootdp"]."include/functions.php");
include_once ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");

includeLanguageFiles('admin','main');


if (!isset($_POST["ezSID"])) $_POST["ezSID"] = $_GET["ezSID"];
if (!isset($_POST["topgroupname"])) $_POST["topgroupname"] = $_GET["topgroupname"];
if (!isset($_POST["groupname"])) $_POST["groupname"] = $_GET["groupname"];
if (!isset($_POST["subgroupname"])) $_POST["subgroupname"] = $_GET["subgroupname"];
if (!isset($_POST["link"])) $_POST["link"] = $_GET["link"];


if ($_POST["submitted"] == "yes") {
	if ((bCheckForm()) && (bVerifyAuthor())) {
		$valid = frmLoginHeader(true);
		if ($valid) { echo $GLOBALS["tLoginSuccessful"].' '; }
		else {
			$GLOBALS["strErrors"] = $GLOBALS["tMustRegister"].' '.$GLOBALS["tLogin"].' '.$GLOBALS["tHigherPriv"].' '.$GLOBALS["tToAccessPrivate"];
			frmLoginForm(false);
		}
	} else {
		$valid = frmLoginHeader(false);
		frmLoginForm(false);
	}
} else {
	$_POST["topgroupname"] = $_GET["topgroupname"];
	$_POST["groupname"]	= $_GET["groupname"];
	$_POST["subgroupname"] = $_GET["subgroupname"];
	$_POST["contentname"]  = $_GET["contentname"];
	$_POST["link"]			= $_GET["link"];
	$_POST["ref"]			= $_GET["ref"];
	frmLoginHeader(false);
	frmLoginForm(true);
}


?>
</body>
</html>
<?php
End_Gzip();



function frmLoginHeader($refresh)
{
	global $_POST, $_COOKIE;

	Start_Gzip();
	force_page_refresh();
	HTMLHeader('module');
	StyleSheet();
	?>
	<script language="JavaScript" type="text/javascript">
		<!-- Begin
		function putFocus(formInst, elementInst) {
			var browserName = navigator.appName;
			var browserVersion = navigator.appVersion;
			var userPlatform = navigator.platform;

			if (browserName == "Microsoft Internet Explorer") {
				var brw_array;
				brw_array = browserVersion.split("MSIE ");
				brw_array = brw_array[1].split(";");
				browserVersion = brw_array[0];
			} else {
				if (navigator.product == "Gecko") {
					browserName = navigator.product;
					browserVersion = navigator.productSub;
				}
			}

			document.LoginAuthors.browser.value = browserName;
			document.LoginAuthors.version.value = browserVersion;
			document.LoginAuthors.platform.value = userPlatform;

			if (document.forms.length > 0) {
				document.forms[formInst].elements[elementInst].focus();
			}
		}
		//  End -->
	</script>
	</head>
	<?php
	$sqlQuery = '';
	if ($_POST["subgroupname"] > '') {
		$sqlQuery="SELECT loginreq,usergroups,subgrouplink AS link FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$_POST["subgroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
	} elseif ($_POST["groupname"] > '') {
		$sqlQuery="SELECT loginreq,usergroups,grouplink AS link FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$_POST["groupname"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
	} elseif ($_POST["topgroupname"] > '') {
		$sqlQuery="SELECT loginreq,usergroups,topgrouplink AS link FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$_POST["topgroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
	}
	if ($sqlQuery > '') {
		$result = dbRetrieve($sqlQuery,true,0,0);
		$rs	= dbFetch($result);
		$link = privatemenu($rs["loginreq"],$rs["usergroups"],$rs["link"]);
		dbFreeResult($result);
		if (($link == 'loginreq.php') || ($link == 'loginreq2.php')) {
			$refresh = false;
		}
	}

	if ($refresh) {
		$onload = 'opener.parent.location.href=\''.BuildLink('control.php').'&topgroupname='.$_POST["topgroupname"].'&groupname='.$_POST["groupname"].'&subgroupname='.$_POST["subgroupname"].'\'; self.close();';
	} else {
		if ($_COOKIE["UserIdCookie"] != '') {
			$onload = 'putFocus(\'LoginAuthors\',\'password\');';
		} else {
			$onload = 'putFocus(\'LoginAuthors\',\'login\');';
		}
	}
	?>
	<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback" onload="<?php echo $onload; ?>">
	<?php
	return $refresh;
}

function frmLoginForm($bAccess)
{
	global $EZ_SESSION_VARS, $_SERVER, $_POST, $_COOKIE, $admin_login;

	?>
	<form name="LoginAuthors" action="login.php" method="POST" enctype="multipart/form-data">
		<table border="0" width="100%" cellspacing="3" cellpadding="3">
			<tr class="headercontent">
				<td colspan="2" align="center">
					<b><?php echo $GLOBALS["tLoginTitle"]; ?></b>
				</td>
			</tr>
			<?php
			if (!$bAccess) {
				?>
				<tr bgcolor="#900000">
					<td align="center" colspan="2">
						<b><?php echo $GLOBALS["tInvalidLogin"]; ?></b>
						<?php if ($GLOBALS["strErrors"] != '') { echo $GLOBALS["strErrors"]; } ?>
					</td>
				</tr>
				<?php
			}
			?>
			<tr class="tablecontent">
				<td valign=top><b><?php echo $GLOBALS["tUsernameText"]; ?>:</b></td>
				<td>
					<input type="text" name="login" size="20" value="<?php echo $_COOKIE["UserIdCookie"]; ?>" maxlength="20">
				</td>
			</tr>
			<tr class="tablecontent">
				<td valign=top><b><?php echo $GLOBALS["tPasswordText"]; ?>:</b></td>
				<td>
					<input type="password" name="password" size="20" value="<?php echo $GLOBALS["gsPassword"]; ?>" maxlength="20">
				</td>
			</tr>
			<tr class="topmenuback">
				<td colspan=2 align="center">
					<input type="submit" name="submit" value="<?php echo $GLOBALS["tLoginText"]; ?>">
				</td>
			</tr>
		</table>
		<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
		<input type="hidden" name="browser" id="browser">
		<input type="hidden" name="version" id="version">
		<input type="hidden" name="platform" id="platform">
		<input type="hidden" name="noframesbrowser" value="<?php echo $EZ_SESSION_VARS["noframesbrowser"]; ?>">
		<input type="hidden" name="topgroupname" value="<?php echo $_POST["topgroupname"]; ?>">
		<input type="hidden" name="groupname" value="<?php echo $_POST["groupname"]; ?>">
		<input type="hidden" name="subgroupname" value="<?php echo $_POST["subgroupname"]; ?>">
		<input type="hidden" name="contentname" value="<?php echo $_POST["contentname"]; ?>">
		<input type="hidden" name="link" value="<?php echo $_POST["link"]; ?>">
		<input type="hidden" name="submitted" value="yes">
	</form>
	<?php
} // function frmLoginForm()


function bVerifyAuthor()
{
	global $EZ_SESSION_VARS, $_POST;

	$sPassword = md5($_POST["password"]);

	$strQuery = "SELECT * from ".$GLOBALS["eztbAuthors"]." WHERE login='".$_POST["login"]."' AND userpassword='".$sPassword."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs	= dbFetch($result);
	if (($rs["login"] == $_POST["login"]) && ($rs["disuser"] != '1')) {
		$EZ_SESSION_VARS["LoginCookie"]	= $_POST["login"];
		$EZ_SESSION_VARS["PasswordCookie"]	= $sPassword;
		$EZ_SESSION_VARS["UserID"]			= $rs["authorid"];
		$EZ_SESSION_VARS["UserName"]		= $rs["authorname"];
		$EZ_SESSION_VARS["UserGroup"]		= $rs["usergroup"];
		$EZ_SESSION_VARS["Language"]		= $rs["language"];
		$EZ_SESSION_VARS["Country"]			= $rs["countrycode"];
		$EZ_SESSION_VARS["Browser"]			= $_POST["browser"];
		$EZ_SESSION_VARS["BrowserVersion"]	= $_POST["version"];
		$EZ_SESSION_VARS["Platform"]		= $_POST["platform"];
		dbFreeResult($result);
		db_session_write();
		return true;
	}
	dbFreeResult($result);
	$EZ_SESSION_VARS["LoginCookie"]		= '';
	$EZ_SESSION_VARS["PasswordCookie"]	= '';
	$EZ_SESSION_VARS["UserID"]			= 0;
	$EZ_SESSION_VARS["UserName"]		= '';
	$EZ_SESSION_VARS["UserGroup"]		= '';
	$EZ_SESSION_VARS["Language"]		= '';
	$EZ_SESSION_VARS["Country"]			= '';
	$EZ_SESSION_VARS["Browser"]			= $_POST["browser"];
	$EZ_SESSION_VARS["BrowserVersion"]	= $_POST["version"];
	$EZ_SESSION_VARS["Platform"]		= $_POST["platform"];
	db_session_write();
	return false;
} // function bVerifyAuthor()


function bCheckForm()
{
	global $_POST;

	$bFormOK = true;
	$strMessage = '';
	$_POST["login"] = trim($_POST["login"]);
	$pos = strpos($_POST["login"], "%");
	if ($pos === false) {
		if ($_POST["login"] == "") {
			$strMessage .= '<br />'.$GLOBALS["eNoLogin"];
			$bFormOK = false;
		}
	} else {
		$strMessage .= '<br />'.$GLOBALS["eNoLogin"];
		$bFormOK = false;
	}

	if ($_POST["password"] == "") {
		$strMessage .= '<br />'.$GLOBALS["eNoPassword"];
		$bFormOK = false;
	}

	if (!$bFormOK) {
		$GLOBALS["strErrors"] = '<b>'.$strMessage.'</b>';
	}
	return $bFormOK;
} // function bCheckForm()

?>
