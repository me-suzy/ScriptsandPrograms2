<?php

/***************************************************************************

 userinfo.php
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

$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");


// This global is empty when framed.
if (($GLOBALS["gsUseFrames"] == '') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	include ($GLOBALS["rootdp"]."include/settings.php");
	include ($GLOBALS["rootdp"]."include/functions.php");
	include ($GLOBALS["rootdp"]."include/banners.php");
	includeLanguageFiles('admin');
} // Frames includes
includeLanguageFiles('preferences');


if ($GLOBALS["gsShowTopMenu"] == 'Y') {
	if (!isset($_GET["groupname"])) {
		if (!isset($_GET["topgroupname"])) { $_GET["topgroupname"] = $GLOBALS["gsHomepageTopGroup"]; }
		$_GET["groupname"] = GetGroupName($_GET["topgroupname"]);
	}
}

if (($EZ_SESSION_VARS["LoginCookie"] != '') && ($_POST["submitted"] != "yes")) {
	if (bVerifyLogin()) { GetGlobalData();
	} else { exit; }
}


$GLOBALS["form"] = 'preferences';
$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
	if (bCheckForm()) {
		UpdatePreferences();
		if($GLOBALS["gsSendConfMail"] == 'Y') {
			SendMail();
		}
		// Timer values for cookies
		// 15 Minutes	= 900;
		// 1 Hour	= 3600;
		// 2 Hours	= 7200;
		// 6 Hours	= 21600;
		// 1 Day	= 86400;
		// 1 Year	= 31622400;
		if ($_POST["retaincookie"] == 'Y') { setcookie ("UserIdCookie", $EZ_SESSION_VARS["LoginCookie"], time()+31622400);
		} else { setcookie ("UserIdCookie", '', time()-31622400); }
		if ($_POST["password"] != '') {
			$sPassword = md5($sPassword);
			$EZ_SESSION_VARS["PasswordCookie"] = $sPassword;
		}
		$EZ_SESSION_VARS["UserName"] = $_POST["authorname"];
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			HTMLHeader('preferences');
			StyleSheet();
			?>
			</head>
			<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback">
			<?php
		} else {
			?>
			<table border="0" cellspacing="5" cellpadding="0" width="100%"><tr><td>
			<?php
		}

		//////////////////////////////////// TO DO - CHANGE HTML OUTPUT //////////////////////////////////
		?>
		<table border="1" cellpadding="1" cellspacing="0" align="center" valign="top" width="98%" class="headercontent">
			<tr><td class="tablecontent">&nbsp;<?php
			if ($EZ_SESSION_VARS["LoginCookie"] != '') {
				echo $GLOBALS["tPrefsUpdated"];
			} else {
				echo $GLOBALS["tPrefsRegistered"];
				$EZ_SESSION_VARS["LoginCookie"] = $_POST["login"];
			}
			?>
			<br />&nbsp;</td></tr>
		</table>
		<?php
		db_session_write();
		exit;
	} else {
		GetFormData();
	}
}

frmAuthorForm();

function frmAuthorForm()
{
	global $EZ_SESSION_VARS;

	$f = 0;
	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		HTMLHeader('userinfo');
		StyleSheet();
		?>
		<script language="JavaScript" type="text/javascript">
		<!-- Begin
		function putFocus(formInst, elementInst) {
			if (document.forms.length > 0) {
				document.forms[formInst].elements[elementInst].focus();
			}
		}
		//  End -->
		</script>
		</head>
		<?php
		if ($EZ_SESSION_VARS["LoginCookie"] != '') {
			?><body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback" onLoad="putFocus('MaintAuthors','password');"><?php
		} else {
			?><body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback" onLoad="putFocus('MaintAuthors','login');"><?php
		}
	}
	?>
	<table border="0" cellspacing="5" cellpadding="0" width="100%">
	<tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
				<form name="MaintAuthors" action="userinfo.php" method="POST" enctype="multipart/form-data">
				<table border="0" width="100%" cellspacing="0" cellpadding="3">
				<tr class="headercontent">
					<td colspan="2" class="header">
						<?php
						if ($EZ_SESSION_VARS["LoginCookie"] != '') { echo $GLOBALS["tFormTitle"];
						} else { echo $GLOBALS["tFormTitle2"]; }
						?>
					</td>
				</tr>
				<tr class="tablecontent">
						<td class="content" colspan="2"><?php echo $GLOBALS["tRegisterIntroText"]; ?><br /><br /></td>
				</tr>
				<?php echo $GLOBALS["strErrors"]; ?>
				<?php
				if($GLOBALS["gsShowHelptexts"] == "Y") {
					?>
					<tr class="tablecontent">
						<td colspan="2" class="helptext"><?php echo $GLOBALS["hLoginName"]; ?></td>
					</tr>
					<?php
				}
				?>
				<tr class="tablecontent">
					<?php
					if ($EZ_SESSION_VARS["LoginCookie"] != '') {
						?><td valign=top class="content"><b><?php echo $GLOBALS["tLoginName"]; ?></b></td><?php
					} else {
						?><td valign=top class="content"><b><?php echo $GLOBALS["tLoginName"]; ?></b></td><?php
					}
					?><td><?php
					if ($EZ_SESSION_VARS["LoginCookie"] != '') {
						?><input type="text" name="login" size="20" value="<?php echo $GLOBALS["fsLogin"]; ?>" maxlength="20" disabled><?php
					} else {
						?><input type="text" name="login" size="20" value="<?php echo $GLOBALS["fsLogin"]; ?>" maxlength="20"><?php
					}
					?></td>
				</tr>
				<?php
				if($GLOBALS["gsShowHelptexts"] == "Y") {
					?>
					<tr class="tablecontent">
						<td colspan="2" class="helptext"><?php echo $GLOBALS["hPassword"]; ?></td>
					</tr>
					<?php
				}
				?>
				<tr class="tablecontent">
					<td class="content" valign=top><b><?php echo $GLOBALS["tPassword"]; ?></b></td>
					<td class="content">
					<input type="password" name="password" size="32" value="" maxlength="32">
					</td>
				</tr>
				<?php
				if($GLOBALS["gsShowHelptexts"] == "Y") {
					?>
					<tr class="tablecontent">
						<td colspan="2" class="helptext"><?php echo $GLOBALS["hRetypePassword"]; ?></td>
					</tr>
					<?php
				}
				?>
				<tr class="tablecontent">
					<td class="content" valign=top><b><?php echo $GLOBALS["tRetypePassword"]; ?></b></td>
					<td class="content">
					<input type="password" name="re_password" size="32" value="" maxlength="32">
					</td>
				</tr>
				<?php
				if($GLOBALS["gsShowHelptexts"] == "Y") {
					?>
					<tr class="tablecontent">
						<td colspan="2" class="helptext"><?php echo $GLOBALS["hAuthorname"]; ?></td>
					</tr>
					<?php
				}
				?>
				<tr class="tablecontent">
					<td class="content" valign=top><b><?php echo $GLOBALS["tAuthorname"]; ?></b></td>
					<td class="content">
						<input type="text" name="authorname" size="50" value="<?php echo $GLOBALS["fsAuthorName"]; ?>" maxlength="50">
					</td>
				</tr>
				<?php
				if($GLOBALS["gsShowHelptexts"] == "Y") {
					?>
					<tr class="tablecontent">
						<td colspan="2" class="helptext"><?php echo $GLOBALS["hEMail"]; ?></td>
					</tr>
					<?php
				}
				?>
				<tr class="tablecontent">
					<td class="content" valign=top><b><?php echo $GLOBALS["tEMail"]; ?></b></td>
					<td class="content">
						<input type="text" name="authoremail" size="50" value="<?php echo $GLOBALS["fsAuthorEmail"]; ?>" maxlength="255" onChange="return emailCheck(this.value);">
					</td>
				</tr>
				<?php
				if($GLOBALS["gsAddressStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hAddress"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tAddress"]; ?></b></td>
						<td class="content">
							<input type="text" name="address" size="50" value="<?php echo $GLOBALS["fsAddress"]; ?>" maxlength="100">
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsCityStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hCity"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tCity"]; ?></b></td>
						<td class="content">
							<input type="text" name="city" size="50" value="<?php echo $GLOBALS["fsCity"]; ?>" maxlength="50">
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsStateStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hState"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tState"]; ?></b></td>
						<td class="content">
							<input type="text" name="state" size="50" value="<?php echo $GLOBALS["fsState"]; ?>" maxlength="50">
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsZipStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hZip"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tZip"]; ?></b></td>
						<td class="content">
							<input type="text" name="zip" size="20" value="<?php echo $GLOBALS["fsZip"]; ?>" maxlength="20">
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsPhoneStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hPhone"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tPhone"]; ?></b></td>
						<td class="content">
							<input type="text" name="phone" size="20" value="<?php echo $GLOBALS["fsPhone"]; ?>" maxlength="20">
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsFaxStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hFax"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tFax"]; ?></b></td>
						<td class="content">
							<input type="text" name="fax" size="20" value="<?php echo $GLOBALS["fsFax"]; ?>" maxlength="20">
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsWebsiteStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hWebsite"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tWebsite"]; ?></b></td>
						<td class="content">
							<input type="text" name="website" size="50" value="<?php echo $GLOBALS["fsWebsite"]; ?>" maxlength="255">
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsNewsletterStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hNewsletter"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tNewsletter"]; ?></b></td>
						<td class="content">
								<input type="checkbox" name="newsletter" value="Y" <?php if($GLOBALS["fbNewsletter"] == 'Y') echo "checked"?><?php echo $GLOBALS["fieldstatus"]; ?>>
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsCommentsStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hComments"]; ?></td>
						</tr>
						<?php
					}
					?><tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tComments"]; ?></b></td>
						<td class="content">
							<textarea rows="4" name="comments" cols="40"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["fsComments"]); ?></textarea>
						</td>
					</tr>
				<?php
				}

				if($GLOBALS["gsCountryStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hCountry"]; ?></td>
						</tr>
						<?php
					}
					?>
					<tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tCountry"]; ?></b></td>
						<td class="content">
								<select name="countrycode" size="1">
								<OPTION value="00">
								<?php RenderCountries($GLOBALS["fsCountryCode"]); ?>
								</select>
						</td>
					</tr>
					<?php
				}

				if($GLOBALS["gsLanguageStatus"] != "N") {
					if($GLOBALS["gsShowHelptexts"] == "Y") {
						?>
						<tr class="tablecontent">
							<td colspan="2" class="helptext"><?php echo $GLOBALS["hLanguage"]; ?></td>
						</tr>
						<?php
					}
					?>
					<tr class="tablecontent">
						<td class="content" valign=top><b><?php echo $GLOBALS["tLanguage"]; ?></b</td>
						<td class="content">
								<select name="languagecode" size="1">
								<OPTION value="00">
								<?php RenderLanguages($GLOBALS["fsLanguageCode"]); ?>
								</select>
						</td>
					</tr>
				<?php
				}

				if($GLOBALS["gsShowHelptexts"] == "Y") {
					?>
					<tr class="tablecontent">
						<td colspan="2" class="helptext"><?php echo $GLOBALS["hRetainCookie"]; ?></td>
					</tr>
					<?php
				}
				?>
				<tr class="tablecontent">
					<td valign=top class="content"><b><?php echo $GLOBALS["tRetainCookie"]; ?></b></td>
					<td class="content">
						<input type="checkbox" name="retaincookie" value="Y" <?php if ($EZ_SESSION_VARS["LoginCookie"] != '') echo "checked"; ?>>
					</td>
				</tr>
				<tr class="tablecontent">
					<td class="content" colspan="2" align="<?php echo $GLOBALS["left"]; ?>">
						<?php
						if ($EZ_SESSION_VARS["LoginCookie"] != '') {
							?><input type="submit" value="<?php echo $GLOBALS["tUpdate"]; ?>" name="submit">&nbsp;<?php
						} else {
							?><input type="submit" value="<?php echo $GLOBALS["tRegister"]; ?>" name="submit">&nbsp;<?php
						}
						?>
						<input type="reset" value="<?php echo $GLOBALS["tReset"]; ?>" name="reset">&nbsp;
					</td>
				</tr>
				</table>
				<input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
				<input type="hidden" name="submitted" value="yes">
				<input type="hidden" name="regdate" value="<?php echo $GLOBALS["fsRegDate"]; ?>">
				<input type="hidden" name="page" value="<?php echo $_POST["page"]; ?>">
			</form>
		</td>
	</tr>
	</table>
	<?php
} // function frmAuthorForm()


function UpdatePreferences()
{
	global $EZ_SESSION_VARS, $_POST;

	$regisodate  = dbDateTime(sprintf("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d")));

	$sLogin			= dbString($_POST["login"]);
	$sPassword		= dbString($_POST["password"]);
	$sAuthorName	= dbString($_POST["authorname"]);
	$sComments		= dbString($_POST["comments"]);
	$sAddress		= dbString($_POST["address"]);
	$sCity			= dbString($_POST["city"]);
	$sState			= dbString($_POST["state"]);
	$sZip			= dbString($_POST["zip"]);
	$sWebSite		= dbString($_POST["website"]);

	if ($_POST["countrycode"] == '00')  { $_POST["countrycode"] = ''; }
	if ($_POST["languagecode"] == '00') { $_POST["languagecode"] = ''; }

	if ($EZ_SESSION_VARS["LoginCookie"] != '') {
		$strQuery = "UPDATE ".$GLOBALS["eztbAuthors"]." SET authorname='".$sAuthorName."', authoremail='".$_POST["authoremail"]."', countrycode='".$_POST["countrycode"]."', language='".$_POST["languagecode"]."', phone='".$_POST["phone"]."', fax='".$_POST["fax"]."', address='".$sAddress."', city='".$sCity."', state='".$sState."', zip='".$sZip."', website='".$sWebSite."', comments='".$sComments."', newsletter='".$GLOBALS["gsNewsletter"]."' WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."'";
	} else {
		$sPassword = md5($sPassword);
		$strQuery = "INSERT INTO ".$GLOBALS["eztbAuthors"]." VALUES('', '".$sLogin."', '".$sPassword."', '".$sAuthorName."','".$_POST["authoremail"]."', '".$regisodate."', '".$GLOBALS["gsPrivDefaultGroup"]."', '".$_POST["countrycode"]."', '".$_POST["languagecode"]."', '".$_POST["phone"]."', '".$_POST["fax"]."', '".$sAddress."', '".$sCity."', '".$sState."', '".$sZip."', '".$sWebSite."', '".$sComments."', '".$_POST["newsletter"]."', 'N', '0')";
	}
	$result = dbExecute($strQuery,true);
	if (($EZ_SESSION_VARS["LoginCookie"] != '') && ($sPassword != "")) {
		$sPassword = md5($sPassword);
		$strQuery = "UPDATE ".$GLOBALS["eztbAuthors"]." SET userpassword ='".$sPassword."' WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."'";
		$result = dbExecute($strQuery,true);
		//  Update password for the session
		$EZ_SESSION_VARS["PasswordCookie"] = $sPassword;
		db_session_write();
	}
	dbCommit();
} // function UpdatePreferences()


function GetGlobalData()
{
	global $EZ_SESSION_VARS;

	$strQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."'";
	$result  = dbRetrieve($strQuery,true,0,0);
	$rs		= dbFetch($result);

	$GLOBALS["fsLogin"]				= $rs["login"];
	$GLOBALS["fsAuthorPassword"]	= "";
	$GLOBALS["fsAuthorName"]		= $rs["authorname"];
	$GLOBALS["fsAuthorEmail"]		= $rs["authoremail"];
	$GLOBALS["fsRegDate"]			= $rs["regdate"];
	$GLOBALS["fsUsergroup"]			= $rs["usergroup"];
	$GLOBALS["fsCountryCode"]		= $rs["countrycode"];
	$GLOBALS["fsLanguageCode"]		= $rs["language"];
	$GLOBALS["fsPhone"]				= $rs["phone"];
	$GLOBALS["fsFax"]				= $rs["fax"];
	$GLOBALS["fsAddress"]			= $rs["address"];
	$GLOBALS["fsCity"]				= $rs["city"];
	$GLOBALS["fsState"]				= $rs["state"];
	$GLOBALS["fsZip"]				= $rs["zip"];
	$GLOBALS["fsWebsite"]			= $rs["website"];
	$GLOBALS["fsComments"]			= $rs["comments"];
	$GLOBALS["fbNewsletter"]		= $rs["newsletter"];

	dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
	global $_POST;

	$GLOBALS["fsLogin"]				= $_POST["login"];
	$GLOBALS["fsAuthorPassword"]	= $_POST["password"];
	$GLOBALS["fsAuthorName"]		= $_POST["authorname"];
	$GLOBALS["fsAuthorEmail"]		= $_POST["authoremail"];
	$GLOBALS["fsRegDate"]			= $_POST["regdate"];
	$GLOBALS["fsUsergroup"]			= $_POST["usergroup"];
	$GLOBALS["fsCountryCode"]		= $_POST["countrycode"];
	$GLOBALS["fsLanguageCode"]		= $_POST["languagecode"];
	$GLOBALS["fsPhone"]				= $_POST["phone"];
	$GLOBALS["fsFax"]				= $_POST["fax"];
	$GLOBALS["fsAddress"]			= $_POST["address"];
	$GLOBALS["fsCity"]				= $_POST["city"];
	$GLOBALS["fsState"]				= $_POST["state"];
	$GLOBALS["fsZip"]				= $_POST["zip"];
	$GLOBALS["fsWebsite"]			= $_POST["website"];
	$GLOBALS["fsComments"]			= $_POST["comments"];
	$GLOBALS["fbNewsletter"]		= $_POST["newsletter"];

} // function GetFormData()


function bCheckForm()
{
	global $EZ_SESSION_VARS, $_POST;

	$bFormOK = true;
	$strMessage = '<tr class="tablecontent"><td colspan="2"><font color="'.$GLOBALS["gsErrFormFontColor"].'"><b>';
	if ($EZ_SESSION_VARS["LoginCookie"] == '') {
		if ($_POST["login"] == "") {
			$strMessage .= $GLOBALS["eLoginEmpty"].'<br />';
			$bFormOK = false;
		} else {
			if (bLoginNameExists()) {
				$strMessage .= $GLOBALS["eLoginExists"].'<br />';
				$bFormOK = false;
			}
		}
	}
	if ($_POST["password"] != $_POST["re_password"]) {
		$strMessage .= $GLOBALS["ePasswordError"].'<br />';
		$bFormOK = false;
	}
	if ($_POST["authorname"] == "") {
		$strMessage .= $GLOBALS["eNameEmpty"].'<br />';
		$bFormOK = false;
	}
	if(!eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $_POST["authoremail"])) {
		$strMessage .= $GLOBALS["eEMailEmpty"].'<br />';
		$bFormOK = false;
	}
	if ($GLOBALS["gsPhoneStatus"] == "M" && $_POST["phone"] == "") {
		$strMessage .= $GLOBALS["ePhoneEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsFaxStatus"] == "M" && $_POST["fax"] == "") {
		$strMessage .= $GLOBALS["eFaxEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsAddressStatus"] == "M" && $_POST["address"] == "") {
		$strMessage .= $GLOBALS["eAddressEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsCityStatus"] == "M" && $_POST["city"] == "") {
		$strMessage .= $GLOBALS["eCityEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsStateStatus"] == "M" && $_POST["state"] == "") {
		$strMessage .= $GLOBALS["eStateEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsZipStatus"] == "M" && $_POST["zip"] == "") {
		$strMessage .= $GLOBALS["eZipEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsCountryStatus"] == "M" && $_POST["countrycode"] == "00") {
		$strMessage .= $GLOBALS["eCountryEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsLanguageStatus"] == "M" && $_POST["languagecode"] == "00") {
		$strMessage .= $GLOBALS["eLanguageEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsWebsiteStatus"] == "M" && $_POST["website"] == "") {
		$strMessage .= $GLOBALS["eWebsiteEmpty"].'<br />';
		$bFormOK = false;
	}

	if ($GLOBALS["gsCommentsStatus"] == "M" && $_POST["comments"] == "") {
		$strMessage .= $GLOBALS["eCommentsEmpty"].'<br />';
		$bFormOK = false;
	}

	$strMessage .= '</font></b><br /></td></tr>';
	if (!$bFormOK) { $GLOBALS["strErrors"] = $strMessage; }
	return $bFormOK;
} // function bCheckForm()


function bLoginNameExists()
{
	global $_POST;

	$strQuery = "SELECT login FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".str_replace("'", "\'", $_POST["login"])."'";
	$result = dbRetrieve($strQuery,true,0,0);
	if (dbRowsReturned($result) > 0) {
		dbFreeResult($result);
		return true;
	}
	dbFreeResult($result);
	return false;
} // function bLoginNameExists()


function RenderCountries($CountryCode)
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbCountries"]." ORDER BY countryname";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		echo "<option ";
		if ($CountryCode == $rs["countrycode"]) { echo "selected "; }
		echo 'value="'.$rs["countrycode"].'">'.$rs["countryname"];
	}
	dbFreeResult($result);
} // function RenderCountries()

function strGetCountry($CountryCode)
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbCountries"]." WHERE countrycode='".$CountryCode."'";
	$result = dbRetrieve($sqlQuery,true,0,0);
	if ($rs = dbFetch($result)) { $strCountryName = $rs["countryname"]; }
	dbFreeResult($result);
	return $strCountryName;
} // function strGetCountry()


function RenderLanguages($LanguageCode)
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y' ORDER BY languagename";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		echo "<option ";
		if ($LanguageCode == $rs["languagecode"]) { echo "selected "; }
		echo "value=\"".$rs["languagecode"]."\">".$rs["languagename"];
	}
	dbFreeResult($result);
} // function RenderLanguages()

function strGetLanguage($LanguageCode)
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$LanguageCode."'";
	$result = dbRetrieve($sqlQuery,true,0,0);
	if ($rs = dbFetch($result)) { $strLanguageName = $rs["languagename"]; }
	dbFreeResult($result);

	return $strLanguageName;

} // function strGetLanguage()

function strGetAdminEmail()
{
	$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE login='admin'";
	$result = dbRetrieve($sqlQuery,true,0,0);
	if ($rs = dbFetch($result)) { $strAdminEmail = $rs["authoremail"]; }
	dbFreeResult($result);
	return $strAdminEmail;
} // function strGetLanguage()

ShowFooterBanner();


function bVerifyLogin()
{
	global $EZ_SESSION_VARS;
	$strQuery = "SELECT login FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."' AND userpassword='".$EZ_SESSION_VARS["PasswordCookie"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs			= dbFetch($result);
	if (dbRowsReturned($result) > 0) {
		dbFreeResult($result);
		return true;
	}
	dbFreeResult($result);

	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		HTMLHeader('userinfo');
		StyleSheet();
		?>
		</head>
		<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback"><?php
	} else { ?><table border="0" cellspacing="5" cellpadding="0" width="100%"><tr><td><?php } ?>

	<table border="1" cellpadding="1" cellspacing="0" align="center" valign="top" width="98%" class="headercontent">
		<tr><td class="tablecontent">&nbsp;<br />
				<?php
				echo $GLOBALS["tMustLogin"];
				if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
					?>
					<a href="<?php echo BuildLink('login.php'); ?>&ref=userdata.php" target="userdata">
					<?php
				} else {
					?>
					<a href="<?php echo BuildLink('control.php'); ?>&topgroupname=<?php echo $GLOBALS["topgroupname"]; ?>&groupname=<?php echo $GLOBALS["groupname"]; ?>&subgroupname=<?php echo $GLOBALS["subgroupname"]; ?>&ulink=login.php&ref=control.php&link=userinfo.php">
					<?php
				}
				?>
				<?php echo $GLOBALS["tLogin"]; ?></a> <?php echo $GLOBALS["tToUpdateDetails"]; ?>.<br />&nbsp;</td>
			</tr>
	</table>
	<?php
	return false;
} // function bVerifyLogin()


?>
</td></tr>
</table>
<?php
if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	?>
	</body>
	</html>
	<?php
}


function GetGroupName($topgroupname)
{
	$gname = $GLOBALS["gsHomepageGroup"];
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE topgroupname='".$topgroupname."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
	$result = dbRetrieve($strQuery,true,0,1);
	if ($rs = dbFetch($result)) { $gname = $rs["groupname"]; }
	dbFreeResult($result);
	return $gname;
} // function GetGroupName()

function SendMail()
{
	global $_POST;

	$strSubmittedData = "\r\n---------------------------------------------------\r\n\r\n".
		$GLOBALS["tLoginName"] . ": ".$_POST["login"]."\r\n".
		$GLOBALS["tPassword"] . ": ".$_POST["password"]."\r\n".
		$GLOBALS["tAuthorname"] . ": ".$_POST["authorname"]."\r\n".
		$GLOBALS["tEMail"] . ": ".$_POST["authoremail"]."\r\n";
		if($GLOBALS["gsPhoneStatus"] != "N" && $_POST["phone"] != "")
			$strSubmittedData .= $GLOBALS["tPhone"] . ": ".$_POST["phone"]."\r\n";
		if($GLOBALS["gsFaxStatus"] != "N" && $_POST["fax"] != "")
			$strSubmittedData .= $GLOBALS["tFax"] . ": ".$_POST["fax"]."\r\n";
		if($GLOBALS["gsAddressStatus"] != "N" && $_POST["address"] != "")
			$strSubmittedData .= $GLOBALS["tAddress"] . ": ".$_POST["address"]."\r\n";
		if($GLOBALS["gsZipStatus"] != "N" && $_POST["zip"] != "")
			$strSubmittedData .= $GLOBALS["tZip"] . ": ".$_POST["zip"]."\r\n";
		if($GLOBALS["gsCityStatus"] != "N" && $_POST["city"] != "")
			$strSubmittedData .= $GLOBALS["tCity"] . ": ".$_POST["city"]."\r\n";
		if($GLOBALS["gsStateStatus"] != "N" && $_POST["state"] != "")
			$strSubmittedData .= $GLOBALS["tState"] . ": ".$_POST["state"]."\r\n";
		if($GLOBALS["gsCountryStatus"] != "N" && $_POST["countrycode"] != "00")
			$strSubmittedData .= $GLOBALS["tCountry"] . ": ".strGetCountry($_POST["countrycode"])."\r\n";
		if($GLOBALS["gsLanguageStatus"] != "N" && $_POST["languagecode"] != "00")
			$strSubmittedData .= $GLOBALS["tLanguage"] . ": ".strGetLanguage($_POST["languagecode"])."\r\n";
		if($GLOBALS["gsWebsiteStatus"] != "N" && $_POST["website"] != "")
			$strSubmittedData .= $GLOBALS["tWebsite"] . ": ".$_POST["website"]."\r\n";
		if($GLOBALS["gsNewsletterStatus"] != "N")
			$strSubmittedData .= $GLOBALS["tNewsletter"] . ": ".$_POST["newsletter"]."\r\n";
		if($GLOBALS["gsCommentsStatus"] != "N" && $_POST["comments"] != "")
			$strSubmittedData .= $GLOBALS["tComments"] . ":\r\n".$_POST["comments"]."\r\n";

	if($GLOBALS["gsServerUserEmail"] != '') {
		$GLOBALS["strServerUserAccount"] = $GLOBALS["gsServerUserEmail"];
	} else {
		$GLOBALS["strServerUserAccount"] = strGetAdminEmail();
	}

	mail($_POST["authoremail"], $GLOBALS["tReturnMailSubject"], "\r\n".$GLOBALS["tReturnMailBody"].$strSubmittedData, "From: ".strGetAdminEmail(), " -f".$GLOBALS["strServerUserAccount"]);
}


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
