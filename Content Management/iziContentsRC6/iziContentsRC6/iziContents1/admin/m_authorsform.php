<?php

/***************************************************************************

 m_authorsform.php
 ------------------
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

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'authors';
$validaccess = VerifyAdminLogin3("AuthorID");

includeLanguageFiles('admin','authors');


// If we've been passed the request from the author's list, then we
//    read author data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["AuthorID"] != '') {
	$_POST["AuthorID"] = $_GET["AuthorID"];
	$_POST["filtergroupname"] = $_GET["filtergroupname"];
	$_POST["page"] = $_GET["page"];
	$_POST["sort"] = $_GET["sort"];
	GetGlobalData();
} else {
	if ($_GET["filtergroupname"] != '') {
		$GLOBALS["fsUsergroup"] = $_GET["filtergroupname"];
	}
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
	// User has submitted the data
	if (bCheckForm())    {
		AddAuthor();
		Header("Location: ".BuildLink('m_authors.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]."&filtergroupname=".$_POST["filtergroupname"]);
	} else {
		// Invalid data has been submitted
		GetFormData();
	}
}
frmAuthorForm();


function frmAuthorForm()
{
   global $_POST;

   adminformheader();
   adminformopen('login');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thLogin"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("Login","login"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="login" size="20" value="<?php echo $GLOBALS["fsLogin"]; ?>" maxlength="20"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Password","password"); ?>
       <td valign="top" class="content">
           <input type="password" name="password" size="32" value="" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
       <?php FieldHeading("RetypePassword","re_password"); ?>
       <td valign="top" class="content">
           <input type="password" name="re_password" size="32" value="" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thUser"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("Authorname","authorname"); ?>
       <td valign="top" class="content">
           <input type="text" name="authorname" size="40" value="<?php echo $GLOBALS["fsAuthorName"]; ?>" maxlength="50"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
       <?php FieldHeading("Usergroup","usergroup"); ?>
       <td valign="top" class="content">
           <select name="usergroup" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderUsergroups($GLOBALS["fsUsergroup"]); ?></select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("EMail","authoremail"); ?>
       <td valign="top" class="content">
           <input type="text" name="authoremail" size="40" value="<?php echo $GLOBALS["fsAuthorEmail"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?> onChange="return emailCheck(this.value);">
       </td>
       <?php FieldHeading("PrivateEMail","privateemail"); ?>
       <td valign="top" class="content">
           <input type="checkbox" name="privateemail" value="Y" <?php if ($GLOBALS["fsPrivateEmail"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thConfiguration"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("Country","countrycode"); ?>
       <td valign="top" class="content">
           <select name="countrycode" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><OPTION value="00"><?php RenderCountries($GLOBALS["fsCountryCode"]); ?></select>
       </td>
       <?php FieldHeading("Language","languagecode"); ?>
       <td valign="top" class="content">
           <select name="languagecode" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><OPTION value="00"><?php RenderLanguages($GLOBALS["fsLanguageCode"]); ?></select>
       </td>
   </tr>
   <?php
   if ($GLOBALS["gsAddressStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("Address","address"); ?>
          <td colspan="3" valign="top" class="content">
              <input type="text" name="address" size="70" value="<?php echo $GLOBALS["fsAddress"]; ?>" maxlength="100"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="address" value="<?php echo $GLOBALS["fsAddress"]; ?>"><?php
   }
   if ($GLOBALS["gsCityStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("City","city"); ?>
          <td colspan="3" valign="top" class="content">
              <input type="text" name="city" size="50" value="<?php echo $GLOBALS["fsCity"]; ?>" maxlength="50"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="city" value="<?php echo $GLOBALS["fsCity"]; ?>"><?php
   }
   if ($GLOBALS["gsStateStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("State","state"); ?>
          <td colspan="3" valign="top" class="content">
              <input type="text" name="state" size="50" value="<?php echo $GLOBALS["fsState"]; ?>" maxlength="50"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="state" value="<?php echo $GLOBALS["fsState"]; ?>"><?php
   }
   if ($GLOBALS["gsZipStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("Zip","zip"); ?>
          <td colspan="3" valign="top" class="content">
              <input type="text" name="zip" size="20" value="<?php echo $GLOBALS["fsZip"]; ?>" maxlength="20"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="zip" value="<?php echo $GLOBALS["fsZip"]; ?>"><?php
   }
   if ($GLOBALS["gsPhoneStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("Phone","phone"); ?>
          <td colspan="3" valign="top" class="content">
              <input type="text" name="phone" size="20" value="<?php echo $GLOBALS["fsPhone"]; ?>" maxlength="20"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="phone" value="<?php echo $GLOBALS["fsPhone"]; ?>"><?php
   }
   if ($GLOBALS["gsFaxStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("Fax","fax"); ?>
          <td colspan="3" valign="top" class="content">
              <input type="text" name="fax" size="20" value="<?php echo $GLOBALS["fsFax"]; ?>" maxlength="20"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="fax" value="<?php echo $GLOBALS["fsFax"]; ?>"><?php
   }
   if ($GLOBALS["gsWebsiteStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("Website","website"); ?>
          <td colspan="3" valign="top" class="content">
              <input type="text" name="website" size="70" value="<?php echo $GLOBALS["fsWebsite"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="website" value="<?php echo $GLOBALS["fsWebsite"]; ?>"><?php
   }
   if ($GLOBALS["gsCommentsStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("Comments","comments"); ?>
          <td colspan="3" valign="top" class="content">
              <textarea rows="4" name="comments" cols="66"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["fsComments"]); ?></textarea>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="comments" value="<?php echo $GLOBALS["fsComments"]; ?>"><?php
   }
   if ($GLOBALS["gsNewsletterStatus"] != "N") {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("Newsletter","newsletter"); ?>
          <td colspan="3" valign="top" class="content">
              <input type="checkbox" name="newsletter" value="Y" <?php if ($GLOBALS["fbNewsletter"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   } else {
      ?><input type="hidden" name="newsletter" value="<?php echo $GLOBALS["fbNewsletter"]; ?>"><?php
   }
   fadminformsavebar(4,'m_authors.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="regdate" value="<?php echo $GLOBALS["fsRegDate"]; ?>"><?php
      ?><input type="hidden" name="AuthorID" value="<?php echo $_POST["AuthorID"]; ?>"><?php
      ?><input type="hidden" name="filtergroupname" value="<?php echo $_POST["filtergroupname"]; ?>"><?php
   }
   adminformclose();
} // function frmAuthorForm()


function AddAuthor()
{
	global $_POST, $EZ_SESSION_VARS;

	$regisodate  = dbDateTime(sprintf("%04d-%02d-%02d %02d:%02d:%02d", strftime("%Y"), strftime("%m"), strftime("%d"), strftime("%H"), strftime("%M"), strftime("%S")));

	$sLogin      = dbString($_POST["login"]);
	$sPassword   = dbString($_POST["password"]);
	$sAuthorName = dbString($_POST["authorname"]);
	$sComments   = dbString($_POST["comments"]);
	$sAddress    = dbString($_POST["address"]);
	$sCity       = dbString($_POST["city"]);
	$sState      = dbString($_POST["state"]);
	$sZip        = dbString($_POST["zip"]);
	$sWebSite    = dbString($_POST["website"]);

	if ($_POST["countrycode"] == '00')  { $_POST["countrycode"] = ''; }
	if ($_POST["languagecode"] == '00') { $_POST["languagecode"] = ''; }

	if ($_POST["AuthorID"] != "") {
		$strQuery = "UPDATE ".$GLOBALS["eztbAuthors"]." SET login='".$sLogin."', authorname='".$sAuthorName."', authoremail='".$_POST["authoremail"]."', countrycode='".$_POST["countrycode"]."', usergroup='".$_POST["usergroup"]."', language='".$_POST["languagecode"]."', phone='".$_POST["phone"]."', fax='".$_POST["fax"]."', address='".$sAddress."', city='".$sCity."', state='".$sState."', zip='".$sZip."', website='".$sWebSite."', comments='".$sComments."', newsletter='".$_POST["newsletter"]."', privateemail='".$_POST["privateemail"]."' WHERE authorid='".$_POST["AuthorID"]."'";
	} else {
		$sPassword = md5($sPassword);
		$strQuery = "INSERT INTO ".$GLOBALS["eztbAuthors"]." VALUES('', '".$sLogin."', '".$sPassword."', '".$sAuthorName."','".$_POST["authoremail"]."', '".$regisodate."', '".$_POST["usergroup"]."', '".$_POST["countrycode"]."', '".$_POST["languagecode"]."', '".$_POST["phone"]."', '".$_POST["fax"]."', '".$sAddress."', '".$sCity."', '".$sState."', '".$sZip."', '".$sWebSite."', '".$sComments."', '".$_POST["newsletter"]."', '".$_POST["privateemail"]."', '0')";
	}
	$result = dbExecute($strQuery,true);
	if (($sPassword != "") && ($_POST["AuthorID"] != '')) {
		// Encrypt new password
		$sPassword = md5($sPassword);
		//  Update password on the database
		$strQuery = "UPDATE ".$GLOBALS["eztbAuthors"]." SET userpassword='".$sPassword."' WHERE authorid='".$_POST["AuthorID"]."'";
		$result = dbExecute($strQuery,true);
		if ($_POST["AuthorID"] == $EZ_SESSION_VARS["UserID"]) {
			//  Update password for the session
			$EZ_SESSION_VARS["PasswordCookie"] = $sPassword;
			db_session_write();
		}
	}
	dbCommit();
} // function AddAuthor()


function GetGlobalData()
{
	global $EZ_SESSION_VARS, $_GET, $_POST;

	$strQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE authorid='".$_GET["AuthorID"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs     = dbFetch($result);

	$GLOBALS["fsLogin"]				= $rs["login"];
	$GLOBALS["fsAuthorPassword"]	= "";
	$GLOBALS["fsAuthorName"]		= $rs["authorname"];
	$GLOBALS["fsAuthorEmail"]		= $rs["authoremail"];
	$GLOBALS["fsPrivateEmail"]		= $rs["privateemail"];
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

	$_POST["authorid"] = $rs["authorid"];
	if ($_POST["AuthorID"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}
	dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
	global $EZ_SESSION_VARS, $_POST;

	$GLOBALS["fsLogin"]				= $_POST["login"];
	$GLOBALS["fsAuthorPassword"]	= $_POST["password"];
	$GLOBALS["fsAuthorName"]		= $_POST["authorname"];
	$GLOBALS["fsAuthorEmail"]		= $_POST["authoremail"];
	$GLOBALS["fsPrivateEmail"]		= $_POST["privateemail"];
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

	if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
		$GLOBALS["specialedit"] = True;
		$GLOBALS["fieldstatus"] = '';
	}
} // function GetFormData()


function bCheckForm()
{
	global $_POST;

	$bFormOK = true;
	if (bAuthorExists('login',str_replace("'", "\'", $_POST["login"])))	{ $GLOBALS["strErrors"][] = $GLOBALS["eLoginExists"]; }
	if ($_POST["login"] == "")	{ $GLOBALS["strErrors"][] = $GLOBALS["eLoginEmpty"]; }
	if ($_POST["password"] != $_POST["re_password"])	{ $GLOBALS["strErrors"][] = $GLOBALS["ePasswordError"]; }
	if (bAuthorExists('authorname',str_replace("'", "\'", $_POST["authorname"])))	{ $GLOBALS["strErrors"][] = $GLOBALS["eNameExists"]; }
	if ($_POST["authorname"] == "")	{ $GLOBALS["strErrors"][] = $GLOBALS["eNameEmpty"]; }
	if ($_POST["authoremail"] == "") { $GLOBALS["strErrors"][] = $GLOBALS["eEMailEmpty"];
	} else {
		if (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$', $_POST["authoremail"])) {
			$GLOBALS["strErrors"][] = $GLOBALS["eEmailIncorrect"];
		}
	}

	if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
	return $bFormOK;
} // function bCheckForm()


function bAuthorExists($field,$value)
{
	global $_POST;

	$strQuery = "SELECT login FROM ".$GLOBALS["eztbAuthors"]." WHERE ".$field."='".$value."'";
	if ($_POST["AuthorID"] != '') {
		$strQuery .= " AND authorid <> ".$_POST["AuthorID"];
	}
	$result = dbRetrieve($strQuery,true,0,0);
	$rcheck = dbRowsReturned($result);
	if ($rcheck != 0) {
		dbFreeResult($result);
		return true;
	}
	dbFreeResult($result);
	return false;
} // function bAuthorExists()


function RenderUsergroups($GroupCode)
{
	$sqlQuery = "SELECT usergroupname,usergroupdesc FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' ORDER BY usergroupname";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		echo "<option ";
		if ($GroupCode == $rs["usergroupname"]) { echo 'selected '; }
		echo 'value="'.$rs["usergroupname"].'">'.$rs["usergroupdesc"];
	}
	dbFreeResult($result);
} // function RenderUsergroups()


function RenderCountries($CountryCode)
{
	$sqlQuery = "SELECT countrycode,countryname FROM ".$GLOBALS["eztbCountries"]." order by countryname";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		echo '<option ';
		if ($CountryCode == $rs["countrycode"]) { echo 'selected '; }
		echo 'value="'.$rs["countrycode"].'">'.$rs["countryname"];
	}
	dbFreeResult($result);
} // function RenderCountries()


function RenderLanguages($LanguageCode)
{
	$sqlQuery = "SELECT languagecode,languagename FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y' ORDER BY languagename";
	$result = dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		echo '<option ';
		if ($LanguageCode == $rs["languagecode"]) { echo 'selected '; }
		echo 'value="'.$rs["languagecode"].'">'.$rs["languagename"];
	}
	dbFreeResult($result);
} // function RenderLanguages()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
