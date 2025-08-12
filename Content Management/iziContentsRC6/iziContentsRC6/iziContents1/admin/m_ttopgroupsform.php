<?php

/***************************************************************************

 m_ttopgroupsform.php
 ---------------------
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
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = $GLOBALS["cantranslate"] = False;
$GLOBALS["fieldstatus"] = '';


// Validate the user's level of access for this form.
$GLOBALS["form"] = 'topgroups';
$validaccess = VerifyAdminLogin3("TopGroupName");
if ($GLOBALS["cantranslate"] == True) {
   $GLOBALS["specialedit"] = True;
   $GLOBALS["fieldstatus"] = '';
}


includeLanguageFiles('admin','topgroups');


$ImageFileTypes = array('gif', 'jpg', 'jpeg', 'png');

// If we've been passed the request from the content list, then we
//    read content data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["TopGroupName"] != '') {
   $_POST["TopGroupName"] = $_GET["TopGroupName"];
   $_POST["LanguageCode"] = $_GET["LanguageCode"];
   $_POST["page"] = $_GET["page"];
   GetGlobalData();
} else {
   GetFormData();
}


$strQuery = "SELECT languagename,charset FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$GLOBALS["gsDefault_language"]."'";
$result = dbRetrieve($strQuery,true,0,0);
if ($rs = dbFetch($result)) {
   $baselanguagename = $rs["languagename"];
   $basecharset = $rs["charset"];
}
dbFreeResult($result);

$strQuery = "SELECT languagename,charset FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$_POST["LanguageCode"]."'";
$result = dbRetrieve($strQuery,true,0,0);
if ($rs = dbFetch($result)) {
   $languagename = $rs["languagename"];
   $charset = $rs["charset"];
}
dbFreeResult($result);


$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AddTopGroup($basecharset,$charset);
      Header("Location: ".BuildLink('m_topgroups.php')."&page=".$_POST["page"]."&filterlangname=".$_POST["LanguageCode"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmTopGroupsForm($baselanguagename,$basecharset,$languagename,$charset);


function frmTopGroupsForm($baselanguagename,$basecharset,$languagename,$charset)
{
   global $_POST;

   $convertcharsets = ($basecharset != $charset);
   if ($convertcharsets) {
      if (function_exists('mb_convert_encoding')) { adminformheader('UTF-8'); }
      else {
         $convertcharsets = false;
         adminformheader($charset);
      }
   }
   else { adminformheader(); }

   adminformopen('topgroupdesc');
   adminformtitle(2,charsetText($GLOBALS["tFormTitle2"],$convertcharsets,$GLOBALS["gsCharset"]).' - '.charsetText($languagename,$convertcharsets,$GLOBALS["gsCharset"]));
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,charsetText($GLOBALS["thGeneral"],$convertcharsets,$GLOBALS["gsCharset"]));
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuRef","topgroupname"); ?>
       <td valign="top" class="content">
           <input type="text" name="topgroupname" size="32" value="<?php echo $GLOBALS["fsTopGroupName"]; ?>" maxlength="32" readonly>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuTitle","topgroupdesc"); ?>
       <td valign="top" class="content">
           <table border="0" cellpadding="1" cellspacing="0">
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="basetopgroupdesc" size="72" value="<?php echo charsetText($GLOBALS["bsTopGroupDesc"],$convertcharsets,$charset); ?>" maxlength="100" readonly>
                   </td>
               </tr>
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($languagename,$convertcharsets,$basecharset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="topgroupdesc" size="72" value="<?php echo charsetText($GLOBALS["fsTopGroupDesc"],$convertcharsets,$basecharset); ?>" maxlength="100"<?php echo $GLOBALS["fieldstatus"]; ?>>
                   </td>
               </tr>
           </table>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuHover","tophovertitle"); ?>
       <td valign="top" class="content">
           <table border="0" cellpadding="1" cellspacing="0">
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b>
                   </td>
                   <td>
                       <textarea rows="3" name="basetophovertitle" cols="66" readonly><?php echo htmlspecialchars(charsetText($GLOBALS["bsHoverTitle"],$convertcharsets,$basecharset)); ?></textarea>
                   </td>
               </tr>
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($languagename,$convertcharsets,$charset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="tophovertitle" size="72" value="<?php echo charsetText($GLOBALS["fsHoverTitle"],$convertcharsets,$charset); ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
                   </td>
               </tr>
           </table>
       </td>
   </tr>
   <?php adminsubheader(2,charsetText($GLOBALS["thGraphics"],$convertcharsets,$GLOBALS["gsCharset"])); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage1","topmenuimage1"); ?>
       <td valign="top" class="content">
           <input type="text" name="topmenuimage1" size="80" value="<?php echo $GLOBALS["fsMenuImage1"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('topmenuimage1',$GLOBALS["fsMenuImage1"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage2","topmenuimage2"); ?>
       <td valign="top" class="content">
           <input type="text" name="topmenuimage2" size="80" value="<?php echo $GLOBALS["fsMenuImage2"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('topmenuimage2',$GLOBALS["fsMenuImage2"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage3","topmenuimage3"); ?>
       <td valign="top" class="content">
           <input type="text" name="topmenuimage3" size="80" value="<?php echo $GLOBALS["fsMenuImage3"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('topmenuimage3',$GLOBALS["fsMenuImage3"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage4","topmenuimage4"); ?>
       <td valign="top" class="content">
           <input type="text" name="topmenuimage4" size="80" value="<?php echo $GLOBALS["fsMenuImage4"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('topmenuimage4',$GLOBALS["fsMenuImage4"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'m_topgroups.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(2);
      ?><input type="hidden" name="topgroupid" value="<?php echo $GLOBALS["fsTopGroupID"]; ?>"><?php
      ?><input type="hidden" name="TopGroupName" value="<?php echo $_POST["TopGroupName"]; ?>"><?php
      ?><input type="hidden" name="LanguageCode" value="<?php echo $_POST["LanguageCode"]; ?>"><?php
      ?><input type="hidden" name="topgrouporderid" value="<?php echo $GLOBALS["fsTopGroupOrderID"]; ?>"><?php

      ?><input type="hidden" name="topmenuvisible" value="<?php echo $GLOBALS["fbMenuVisible"]; ?>"><?php
      ?><input type="hidden" name="topgrouplink" value="<?php echo $GLOBALS["fsTopGroupLink"]; ?>"><?php
      ?><input type="hidden" name="topopeninpage" value="<?php echo $GLOBALS["fsOpenInPage"]; ?>"><?php
      ?><input type="hidden" name="grouporderid" value="<?php echo $GLOBALS["fsGroupOrderID"]; ?>"><?php
      ?><input type="hidden" name="topmenuorderby" value="<?php echo $GLOBALS["fsOrderBy"]; ?>"><?php
      ?><input type="hidden" name="topmenuorderdir" value="<?php echo $GLOBALS["fsOrderDir"]; ?>"><?php
      ?><input type="hidden" name="loginreq" value="<?php echo $GLOBALS["fsLoginReq"]; ?>"><?php
      ?><input type="hidden" name="usergroups" value="<?php echo $GLOBALS["fsUsergroups"]; ?>"><?php

      ?><input type="hidden" name="edittype" value="<?php echo $GLOBALS["fsEditType"]; ?>"><?php

   }
   adminformclose();
} // function frmTopGroupsForm()


function AddTopGroup($basecharset,$charset)
{
   global $_POST, $EZ_SESSION_VARS;

   $convertcharsets = ($basecharset != $charset);
   if ($convertcharsets) {
      if (!(function_exists('mb_convert_encoding'))) {
         $convertcharsets = false;
      }
   }

   $sTopGroupDesc  = dbString(UTF8Text($_POST["topgroupdesc"],$convertcharsets,$charset));
   $sTopHoverTitle = dbString(UTF8Text($_POST["tophovertitle"],$convertcharsets,$charset));

   if ($_POST["edittype"] != 'add') {
      $strQuery = "UPDATE ".$GLOBALS["eztbTopgroups"]." SET topgroupdesc='".$sTopGroupDesc."', topmenuimage1='".$_POST["topmenuimage1"]."', topmenuimage2='".$_POST["topmenuimage2"]."', tophovertitle='".$sTopHoverTitle."', topmenuimage3='".$_POST["topmenuimage3"]."', topmenuimage4='".$_POST["topmenuimage4"]."' WHERE topgroupname='".$_POST["TopGroupName"]."' AND language='".$_POST["LanguageCode"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbTopgroups"]." VALUES('', '".$sTopGroupDesc."', '".$_POST["topgrouplink"]."', '".$_POST["topgrouporderid"]."', '".$_POST["topmenuimage1"]."', '".$_POST["topmenuimage2"]."', '".$sTopHoverTitle."', '".$_POST["topmenuvisible"]."', '".$_POST["topmenuorderby"]."', '".$_POST["topmenuorderdir"]."', '".$_POST["topopeninpage"]."', '".$_POST["loginreq"]."', '".$_POST["usergroups"]."', '".$_POST["TopGroupName"]."', '".$_POST["LanguageCode"]."', '".$_POST["topmenuimage3"]."', '".$_POST["topmenuimage4"]."', '".$EZ_SESSION_VARS["UserID"]."', '".$_POST["toptheme"]."')";
   }
   $result = dbExecute($strQuery,true);

   dbCommit();
} // function AddTopGroup()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery = "SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$_GET["TopGroupName"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["bsTopGroupDesc"]    = $rs["topgroupdesc"];
   $GLOBALS["bsHoverTitle"]      = $rs["tophovertitle"];

   $GLOBALS["fsTopGroupID"]      = $rs["topgroupid"];
   $GLOBALS["fsTopGroupName"]    = $rs["topgroupname"];
   $GLOBALS["fsTopGroupDesc"]    = $rs["topgroupdesc"];
   $GLOBALS["fsTopGroupLink"]    = $rs["topgrouplink"];
   $GLOBALS["fsTopGroupOrderID"] = $rs["topgrouporderid"];
   $GLOBALS["fsMenuImage1"]      = $rs["topmenuimage1"];
   $GLOBALS["fsMenuImage2"]      = $rs["topmenuimage2"];
   $GLOBALS["fsMenuImage3"]      = $rs["topmenuimage3"];
   $GLOBALS["fsMenuImage4"]      = $rs["topmenuimage4"];
   $GLOBALS["fbMenuVisible"]     = $rs["topmenuvisible"];
   $GLOBALS["fsOrderBy"]         = $rs["topmenuorderby"];
   $GLOBALS["fsOrderDir"]        = $rs["topmenuorderdir"];
   $GLOBALS["fsHoverTitle"]      = $rs["tophovertitle"];
   $GLOBALS["fsOpenInPage"]      = $rs["topopeninpage"];
   $GLOBALS["fsLoginReq"]        = $rs["loginreq"];
   $GLOBALS["fsUsergroups"]      = $rs["usergroups"];


   $strQuery="SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$_GET["TopGroupName"]."' AND language='".$_GET["LanguageCode"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   if (dbRowsReturned($result) != 0) {
      $rs     = dbFetch($result);

      $GLOBALS["fsTopGroupID"]      = $rs["topgroupid"];
      $GLOBALS["fsTopGroupName"]    = $rs["topgroupname"];
      $GLOBALS["fsTopGroupDesc"]    = $rs["topgroupdesc"];
      $GLOBALS["fsTopGroupLink"]    = $rs["topgrouplink"];
      $GLOBALS["fsTopGroupOrderID"] = $rs["topgrouporderid"];
      $GLOBALS["fsMenuImage1"]      = $rs["topmenuimage1"];
      $GLOBALS["fsMenuImage2"]      = $rs["topmenuimage2"];
      $GLOBALS["fsMenuImage3"]      = $rs["topmenuimage3"];
      $GLOBALS["fsMenuImage4"]      = $rs["topmenuimage4"];
      $GLOBALS["fbMenuVisible"]     = $rs["topmenuvisible"];
      $GLOBALS["fsOrderBy"]         = $rs["topmenuorderby"];
      $GLOBALS["fsOrderDir"]        = $rs["topmenuorderdir"];
      $GLOBALS["fsHoverTitle"]      = $rs["tophovertitle"];
      $GLOBALS["fsOpenInPage"]      = $rs["topopeninpage"];
      $GLOBALS["fsLoginReq"]        = $rs["loginreq"];
      $GLOBALS["fsUsergroups"]      = $rs["usergroups"];
      $GLOBALS["fsEditType"]        = 'update';
   } else {
      $GLOBALS["fsEditType"]        = 'add';
   }

   $_POST["authorid"] = $rs["authorid"];
   if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }

   $_POST["TopGroupName"] = $_GET["TopGroupName"];
   $_POST["LanguageCode"] = $_GET["LanguageCode"];
} // function GetGlobalData()


function GetFormData()
{
   global $EZ_SESSION_VARS, $_POST;

   $GLOBALS["fsTopGroupID"]      = $_POST["topgroupid"];
   $GLOBALS["fsTopGroupName"]    = $_POST["topgroupname"];
   $GLOBALS["fsTopGroupDesc"]    = $_POST["topgroupdesc"];
   $GLOBALS["fsTopGroupLink"]    = $_POST["topgrouplink"];
   $GLOBALS["fsTopGroupOrderID"] = $_POST["topgrouporderid"];
   $GLOBALS["fsMenuImage1"]      = $_POST["topmenuimage1"];
   $GLOBALS["fsMenuImage2"]      = $_POST["topmenuimage2"];
   $GLOBALS["fsMenuImage3"]      = $_POST["topmenuimage3"];
   $GLOBALS["fsMenuImage4"]      = $_POST["topmenuimage4"];
   $GLOBALS["fbMenuVisible"]     = $_POST["topmenuvisible"];
   $GLOBALS["fsOrderBy"]         = $_POST["topmenuorderby"];
   $GLOBALS["fsOrderDir"]        = $_POST["topmenuorderdir"];
   $GLOBALS["fsHoverTitle"]      = $_POST["tophovertitle"];
   $GLOBALS["fsOpenInPage"]      = $_POST["topopeninpage"];
   $GLOBALS["fsLoginReq"]        = $_POST["loginreq"];
   $GLOBALS["fsUsergroups"]      = $_POST["usergroups"];

   $GLOBALS["edittype"]          = $_POST["edittype"];

   if ($GLOBALS["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["topgroupdesc"] == "") {
      $GLOBALS["strErrors"][] = $GLOBALS["eTitleEmpty"];
   }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


$GLOBALS["eztbTable"] = $GLOBALS["eztbTopgroups"];
$GLOBALS["eztbKeyField"] = 'topgroupname';
$GLOBALS["keyfieldval"] = $_POST["TopGroupName"];
include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
