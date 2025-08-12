<?php

/***************************************************************************

 m_bannersform.php
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


// Localisation variables (used for default values)
// Change these to suit your site preferences
//
$expiryperiod = 'y';	// Time period to calculate the banner expiry date (based on today's date)
$expirynumber = 1;	//   $expiryperiod = 'y' - years
			//   $expiryperiod = 'm' - months
			//   $expiryperiod = 'w' - weeks
			//   $expiryperiod = 'd' - days
			//   $expirynumber - number of $expiryperiod before expiry
$ImageFileTypes = array( 'gif', 'jpg', 'jpeg', 'png');


include_once ("rootdatapath.php");

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'banners';
$validaccess = VerifyAdminLogin3("BannerID");

includeLanguageFiles('admin','banners');


// If we've been passed the request from the banner list, then we
//    read banner data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["BannerID"] != '') {
   $_POST["BannerID"] = $_GET["BannerID"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
   GetGlobalData();
} else {
   $timenow = time();		// Calculate the default expiry date
   $GLOBALS["DefExpDate"] = date('Y-m-d H:i:s',DateAdd($expiryperiod,$expirynumber,$timenow));
   $GLOBALS["fnImpressions"] = 0;
   $GLOBALS["fnClicks"]      = 0;
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AddBanner();
      Header("Location: ".BuildLink('m_banners.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmBannerForm();


function frmBannerForm()
{
   global $_POST;

   adminformheader();
   adminformopen('bannerurl');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thDetails"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("Target","bannerurl"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="bannerurl" size="70" value="<?php echo $GLOBALS["fsBannerUrl"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("BannerImage","image"); ?>
       <td colspan="3" valign="top" class="content">
	   <input type="text" name="image" size="64" value="<?php echo $GLOBALS["fsImage"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
	   <?php adminimagedisplay('image',$GLOBALS["fsImage"],$GLOBALS["tShowBanner"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("BannerHTML","bannerhtml"); ?>
       <td colspan="3" valign="top" class="content">
           <textarea rows="6" name="bannerhtml" cols="64"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["fsBannerHTML"]); ?></textarea>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("AltText","banneralt"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="banneralt" size="70" value="<?php echo htmlspecialchars($GLOBALS["fsBannerAlt"]); ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thStatus"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("PublishDate","PublishDay"); ?>
       <td colspan="3" valign="top" class="content">
			<?php admindatedisplay('Publish',$GLOBALS["fsPublishDate"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ExpireDate","ExpireDay"); ?>
       <td colspan="3" valign="top" class="content">
			<?php admindatedisplay('Expire',$GLOBALS["fsExpireDate"],$GLOBALS["DefExpDate"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("Enabled",8); ?>
       <td colspan="3" valign="top" class="content">
           <input type="radio" value="Y" name="banneractive" <?php if($GLOBALS["fsActive"] == "Y" || $GLOBALS["gsActive"] == "") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tYes"]; ?><br />
           <input type="radio" value="N" name="banneractive" <?php If($GLOBALS["fsActive"] == "N") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tNo"]; ?>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thLog"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("Impressions","impressions"); ?>
       <td valign="top" class="content">
           <input type="text" name="impressions" size="10" value="<?php echo $GLOBALS["fnImpressions"]; ?>" maxlength="10"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
      <?php FieldHeading("Clicks","clicks"); ?>
      <td valign="top" class="content">
          <input type="text" name="clicks" size="10" value="<?php echo $GLOBALS["fnClicks"]; ?>" maxlength="10"<?php echo $GLOBALS["fieldstatus"]; ?>>
      </td>
   </tr>
   <?php
   adminformsavebar(4,'m_banners.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="BannerID" value="<?php echo $_POST["BannerID"]; ?>"><?php
   }
   adminformclose();
} // function frmBannerForm()


function AddBanner()
{
   global $_POST, $EZ_SESSION_VARS;

   $publishisodate = dbDateTime(sprintf("%04d-%02d-%02d 00:00:00", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]));
   $expireisodate  = dbDateTime(sprintf("%04d-%02d-%02d 00:00:00", $_POST["ExpireYear"], $_POST["ExpireMonth"], $_POST["ExpireDay"]));
   $sBannerAlt  = dbString($_POST["banneralt"]);
   $sBannerHTML = dbString($_POST["bannerhtml"]);

   if ($_POST["BannerID"] != '')
   {
      $strQuery = "UPDATE ".$GLOBALS["eztbBanners"]." SET bannerimage='".$_POST["image"]."', bannerurl='".$_POST["bannerurl"]."', banneralt='".$sBannerAlt."', publishdate='".$publishisodate."', expiredate='".$expireisodate."', banneractive='".$_POST["banneractive"]."', bannerhtml='".$sBannerHTML."', impressions=".$_POST["impressions"].", clicks=".$_POST["clicks"]." WHERE bannerid='".$_POST["BannerID"]."'";
   }
   else
   {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbBanners"]."(bannerimage,bannerurl,banneralt,publishdate,expiredate,impressions,clicks,banneractive,bannerhtml,authorid) VALUES('".$_POST["image"]."', '".$_POST["bannerurl"]."', '".$sBannerAlt."', '".$publishisodate."', '".$expireisodate."', '".$_POST["impressions"]."', '".$_POST["clicks"]."', '".$_POST["banneractive"]."', '".$sBannerHTML."', '".$EZ_SESSION_VARS["UserID"]."')";
   }
   $result = dbExecute($strQuery,true);
   dbCommit();
} // function AddBanner()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbBanners"]." WHERE bannerid='".$_GET["BannerID"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["fsBannerUrl"]     = $rs["bannerurl"];
   $GLOBALS["fsBannerAlt"]     = $rs["banneralt"];
   $GLOBALS["fnImpressions"]   = $rs["impressions"];
   $GLOBALS["fnClicks"]        = $rs["clicks"];
   $GLOBALS["fsActive"]        = $rs["banneractive"];
   $GLOBALS["fsPublishDate"]   = $rs["publishdate"];
   $GLOBALS["fsExpireDate"]    = $rs["expiredate"];
   $GLOBALS["fsImage"]         = $rs["bannerimage"];
   $GLOBALS["fsBannerHTML"]    = $rs["bannerhtml"];

   if ($GLOBALS["fsActive"] == '') $GLOBALS["fsActive"] = "Y";
   $_POST["authorid"] = $rs["authorid"];
   if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
   dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
   global $EZ_SESSION_VARS, $_POST;

   $publishisodate = sprintf("%04d-%02d-%02d", $_POST["PublishYear"], $_POST["PublishMonth"], $_POST["PublishDay"]);
   $expireisodate = sprintf("%04d-%02d-%02d", $_POST["ExpireYear"], $_POST["ExpireMonth"], $_POST["ExpireDay"]);

   $GLOBALS["fsBannerUrl"]   = $_POST["bannerurl"];
   $GLOBALS["fsBannerAlt"]   = $_POST["banneralt"];
   $GLOBALS["fnImpressions"] = $_POST["impressions"];
   $GLOBALS["fnClicks"]      = $_POST["clicks"];
   $GLOBALS["fsActive"]      = $_POST["banneractive"];
   $GLOBALS["fsPublishDate"] = $publishisodate;
   $GLOBALS["fsExpireDate"]  = $expireisodate;
   $GLOBALS["fsImage"]       = $_POST["image"];
   $GLOBALS["fsBannerHTML"]  = $_POST["bannerhtml"];

   if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["bannerurl"] == "")						{ $GLOBALS["strErrors"][] = $GLOBALS["eNoURL"]; }
   if (!is_numeric($_POST["impressions"]))					{ $GLOBALS["strErrors"][] = $GLOBALS["eImpressionsNum"]; }
   if (!is_numeric($_POST["clicks"]))						{ $GLOBALS["strErrors"][] = $GLOBALS["eClicksNum"]; }
   if (($_POST["image"] == "") && ($_POST["bannerhtml"] == ""))	{ $GLOBALS["strErrors"][] = $GLOBALS["eNoImage"]; }
   if (($_POST["image"] != "") && ($_POST["bannerhtml"] != ""))	{ $GLOBALS["strErrors"][] = $GLOBALS["eBothImageHTML"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
