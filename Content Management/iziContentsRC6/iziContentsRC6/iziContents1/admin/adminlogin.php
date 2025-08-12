<?php

/***************************************************************************

 adminlogin.php
 ---------------
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
include_once ("rootdatapath.php");

includeLanguageFiles('admin');


if ($_POST["submitted"] == "yes") {
	if ((bCheckForm()) && (bVerifyAuthor())) {
		Header("Location: ".BuildLink('control2.php'));
	} else {
		force_page_refresh();
		frmLoginForm(false);
	}
} else {
	force_page_refresh();
	frmLoginForm(true);
}


function frmLoginForm($bAccess)
{
	global $EZ_SESSION_VARS, $_SERVER, $_POST, $_COOKIE, $admin_login;

	HTMLHeader('login');
	if ($GLOBALS["gsAdminStyle"] != '') { ?><LINK HREF="<?php echo $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"]; ?>/vs.css" REL=STYLESHEET TYPE="text/css"><?php
	} else { StyleSheet(); }
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
	if ($_COOKIE["UserIdCookie"] != '') {
		?>
		<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback" onLoad="putFocus(0,1);">
		<?php
	} else {
		?>
		<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0" class="mainback" onLoad="putFocus(0,0);">
		<?php
	}
	?>

	<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
		<tr><td align="center" valign="middle">
			<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" width="100%">
					<form name="LoginAuthors" action="adminlogin.php" method="POST" enctype="multipart/form-data">
						<table border="0" cellspacing="3" cellpadding="3">
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
								</td>
							</tr>
							<?php
							if ($GLOBALS["strErrors"] != '') { echo $GLOBALS["strErrors"]; }
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
						<?php
						if ($GLOBALS["gsMultiSite"] == 'Y') {
							echo '<tr class="tablecontent">';
							echo '<td valign=top><b>'.$GLOBALS["tCurrentSite"].'</b></td>';
							echo '<td>';
							$strQuery = "SELECT sitecode,sitename FROM ".$GLOBALS["eztbSites"]." WHERE siteenabled='1' ORDER BY sitename";
							$result = dbRetrieve($strQuery,true,0,0);
							echo '<select name="Site" size="1">';
							echo '<option selected ';
							echo 'value="">'.$GLOBALS["tMasterSite"];
							while ($rs = dbFetch($result)) {
								echo '<option ';
								if ($rs["sitecode"] == $EZ_SESSION_VARS["Site"]) { echo 'selected '; }
								echo 'value="'.$rs["sitecode"].'">'.$rs["sitename"];
							}
							dbFreeResult($result);
							echo '</select></td></tr>';
						}
						?>
						<tr class="topmenuback">
							<td colspan=2 align="center">
								<input type="submit" name="submit" value="<?php echo $GLOBALS["tLoginText"]; ?>">
							</td>
						</tr>
						</table>
						<input type="hidden" name="browser" id="browser">
						<input type="hidden" name="version" id="version">
						<input type="hidden" name="platform" id="platform">
						<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
						<input type="hidden" name="submitted" value="yes">
					</form>
				</td>
			</tr>

			</table>
		</td></tr>
	</table>

	</body>
	</html>
	<?php
} // function frmLoginForm()


function bVerifyAuthor()
{
	global $EZ_SESSION_VARS, $_POST;

	$sPassword = md5($_POST["password"]);

	if ($_POST["Site"] != '') {
		if ($GLOBALS["gsMultiSiteAuthors"] != 'Y') {
			$strQuery = "SELECT * FROM ".$_POST["Site"]."authors WHERE login='".$_POST["login"]."' AND userpassword='".$sPassword."'";
		} else {
			$strQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$_POST["login"]."' AND userpassword='".$sPassword."'";
		}
	} else {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$_POST["login"]."' AND userpassword='".$sPassword."'";
	}
	$result = dbRetrieve($strQuery,true,0,0);
	$rs		= dbFetch($result);
	if (($rs["login"] == $_POST["login"]) && ($rs["disuser"] != '1')) {
		$EZ_SESSION_VARS["LoginCookie"]		= $_POST["login"];
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
		if ($GLOBALS["gsMultiSite"] == 'Y') {
			$EZ_SESSION_VARS["Site"]		= $_POST["Site"];
		}
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
