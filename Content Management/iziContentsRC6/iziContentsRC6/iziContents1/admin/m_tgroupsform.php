<?php

/***************************************************************************

 m_tgroupsform.php
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
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = $GLOBALS["cantranslate"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'groups';
$validaccess = VerifyAdminLogin3("GroupName");

includeLanguageFiles('admin','groups');


$ImageFileTypes = array('gif', 'jpg', 'jpeg', 'png');

// If we've been passed the request from the content list, then we
//    read content data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["GroupName"] != '') {
   $_POST["GroupName"] = $_GET["GroupName"];
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
      AddGroup($basecharset,$charset);
      Header("Location: ".BuildLink('m_groups.php')."&page=".$_POST["page"]."&filterlangname=".$_POST["LanguageCode"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmGroupsForm($baselanguagename,$basecharset,$languagename,$charset);


function frmGroupsForm($baselanguagename,$basecharset,$languagename,$charset)
{
   global $_POST;

   $convertcharsets = ($basecharset != $charset);
   if ($convertcharsets) {
      if (function_exists('mb_convert_encoding')) { adminformheader('UTF-8');
      } else {
         $convertcharsets = false;
         adminformheader($charset);
      }
   } else { adminformheader(); }

   adminformopen('groupdesc');
   adminformtitle(2,charsetText($GLOBALS["tFormTitle2"],$convertcharsets,$GLOBALS["gsCharset"]).' - '.charsetText($languagename,$convertcharsets,$GLOBALS["gsCharset"]));
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,charsetText($GLOBALS["thGeneral"],$convertcharsets,$GLOBALS["gsCharset"]));
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuRef","groupname"); ?>
       <td valign="top" class="content">
           <input type="text" name="groupname" size="32" value="<?php echo $GLOBALS["fsGroupName"]; ?>" maxlength="32" readonly>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuTitle","groupdesc"); ?>
       <td valign="top" class="content">
           <table border="0" cellpadding="1" cellspacing="0">
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="basegroupdesc" size="72" value="<?php echo charsetText($GLOBALS["bsGroupDesc"],$convertcharsets,$basecharset); ?>" maxlength="100" readonly>
                   </td>
               </tr>
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($languagename,$convertcharsets,$charset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="groupdesc" size="72" value="<?php echo charsetText($GLOBALS["fsGroupDesc"],$convertcharsets,$charset); ?>" maxlength="100"<?php echo $GLOBALS["fieldstatus"]; ?>>
                   </td>
               </tr>
           </table>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuHover","hovertitle"); ?>
       <td valign="top" class="content">
           <table border="0" cellpadding="1" cellspacing="0">
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b>
                   </td>
                   <td>
                       <textarea rows="3" name="basehovertitle" cols="66" readonly><?php echo htmlspecialchars(charsetText($GLOBALS["bsHoverTitle"],$convertcharsets,$basecharset)); ?></textarea>
                   </td>
               </tr>
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($languagename,$convertcharsets,$charset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="hovertitle" size="72" value="<?php echo charsetText($GLOBALS["fsHoverTitle"],$convertcharsets,$charset); ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
                   </td>
               </tr>
           </table>
       </td>
   </tr>
   <?php adminsubheader(2,charsetText($GLOBALS["thGraphics"],$convertcharsets,$GLOBALS["gsCharset"])); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage1","menuimage1"); ?>
       <td valign="top" class="content">
           <input type="text" name="menuimage1" size="80" value="<?php echo $GLOBALS["fsMenuImage1"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('menuimage1',$GLOBALS["fsMenuImage1"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage2","menuimage2"); ?>
       <td valign="top" class="content">
           <input type="text" name="menuimage2" size="80" value="<?php echo $GLOBALS["fsMenuImage2"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('menuimage2',$GLOBALS["fsMenuImage2"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage3","menuimage3"); ?>
       <td valign="top" class="content">
           <input type="text" name="menuimage3" size="80" value="<?php echo $GLOBALS["fsMenuImage3"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('menuimage3',$GLOBALS["fsMenuImage3"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage4","menuimage4"); ?>
       <td valign="top" class="content">
           <input type="text" name="menuimage4" size="80" value="<?php echo $GLOBALS["fsMenuImage4"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('menuimage4',$GLOBALS["fsMenuImage4"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'m_groups.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(2);
      ?><input type="hidden" name="groupid" value="<?php echo $GLOBALS["fsGroupID"]; ?>"><?php
      ?><input type="hidden" name="GroupName" value="<?php echo $_POST["GroupName"]; ?>"><?php
      ?><input type="hidden" name="LanguageCode" value="<?php echo $_POST["LanguageCode"]; ?>"><?php
      ?><input type="hidden" name="grouporderid" value="<?php echo $GLOBALS["fsGroupOrderID"]; ?>"><?php

      ?><input type="hidden" name="topgroupname" value="<?php echo $GLOBALS["fsTopgroupName"]; ?>"><?php
      ?><input type="hidden" name="menuvisible" value="<?php echo $GLOBALS["fbMenuVisible"]; ?>"><?php
      ?><input type="hidden" name="grouplink" value="<?php echo $GLOBALS["fsGroupLink"]; ?>"><?php
      ?><input type="hidden" name="openinpage" value="<?php echo $GLOBALS["fsOpenInPage"]; ?>"><?php
      ?><input type="hidden" name="grouporderid" value="<?php echo $GLOBALS["fsGroupOrderID"]; ?>"><?php
      ?><input type="hidden" name="menuorderby" value="<?php echo $GLOBALS["fsOrderBy"]; ?>"><?php
      ?><input type="hidden" name="menuorderdir" value="<?php echo $GLOBALS["fsOrderDir"]; ?>"><?php
      ?><input type="hidden" name="loginreq" value="<?php echo $GLOBALS["fsLoginReq"]; ?>"><?php
      ?><input type="hidden" name="usergroups" value="<?php echo $GLOBALS["fsUsergroups"]; ?>"><?php
      ?><input type="hidden" name="subgroupcount" value="<?php echo $GLOBALS["fsSubgroupCount"]; ?>"><?php

      ?><input type="hidden" name="edittype" value="<?php echo $GLOBALS["fsEditType"]; ?>"><?php

   }
   adminformclose();
} // function frmGroupsForm()


function AddGroup($basecharset,$charset)
{
   global $_POST, $EZ_SESSION_VARS;

   $convertcharsets = ($basecharset != $charset);
   if ($convertcharsets) {
      if (!(function_exists('mb_convert_encoding'))) {
         $convertcharsets = false;
      }
   }

   $sGroupDesc  = dbString(UTF8Text($_POST["groupdesc"],$convertcharsets,$charset));
   $sHoverTitle = dbString(UTF8Text($_POST["hovertitle"],$convertcharsets,$charset));

   if ($_POST["edittype"] != 'add') {
      $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET groupdesc='".$sGroupDesc."', menuimage1='".$_POST["menuimage1"]."', menuimage2='".$_POST["menuimage2"]."', hovertitle='".$sHoverTitle."', menuimage3='".$_POST["menuimage3"]."', menuimage4='".$_POST["menuimage4"]."' WHERE groupname='".$_POST["GroupName"]."' AND language='".$_POST["LanguageCode"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbGroups"]." VALUES('', '".$sGroupDesc."', '".$_POST["grouplink"]."', '".$_POST["grouporderid"]."', '".$_POST["menuimage1"]."', '".$_POST["menuimage2"]."', '".$_POST["menuvisible"]."', '".$_POST["menuorderby"]."', '".$_POST["menuorderdir"]."', '".$sHoverTitle."', '".$_POST["openinpage"]."', '".$_POST["topgroupname"]."', '".$_POST["loginreq"]."', '".$_POST["usergroups"]."', '".$_POST["GroupName"]."', '".$_POST["LanguageCode"]."', '".$_POST["menuimage3"]."', '".$_POST["menuimage4"]."', '".$EZ_SESSION_VARS["UserID"]."', '".$_POST["subgroupcount"]."')";
   }
   $result = dbExecute($strQuery,true);

   dbCommit();
} // function AddGroup()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$_GET["GroupName"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["bsGroupDesc"]     = $rs["groupdesc"];
   $GLOBALS["bsHoverTitle"]    = $rs["hovertitle"];

   $GLOBALS["fsGroupName"]     = $rs["groupname"];
   $GLOBALS["fsGroupID"]       = $rs["groupid"];
   $GLOBALS["fsTopgroupName"]  = $rs["topgroupname"];
   $GLOBALS["fsGroupDesc"]     = $rs["groupdesc"];
   $GLOBALS["fsGroupLink"]     = $rs["grouplink"];
   $GLOBALS["fsGroupOrderID"]  = $rs["grouporderid"];
   $GLOBALS["fsMenuImage1"]    = $rs["menuimage1"];
   $GLOBALS["fsMenuImage2"]    = $rs["menuimage2"];
   $GLOBALS["fsMenuImage3"]    = $rs["menuimage3"];
   $GLOBALS["fsMenuImage4"]    = $rs["menuimage4"];
   $GLOBALS["fbMenuVisible"]   = $rs["menuvisible"];
   $GLOBALS["fsOrderBy"]       = $rs["menuorderby"];
   $GLOBALS["fsOrderDir"]      = $rs["menuorderdir"];
   $GLOBALS["fsHoverTitle"]    = $rs["hovertitle"];
   $GLOBALS["fsOpenInPage"]    = $rs["openinpage"];
   $GLOBALS["fsLoginReq"]      = $rs["loginreq"];
   $GLOBALS["fsUsergroups"]    = $rs["usergroups"];
   $GLOBALS["fsSubgroupCount"] = $rs["subgroupcount"];


   $strQuery="SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$_GET["GroupName"]."' AND language='".$_GET["LanguageCode"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   if (dbRowsReturned($result) != 0) {
      $rs = dbFetch($result);

      $GLOBALS["fsGroupName"]     = $rs["groupname"];
      $GLOBALS["fsGroupID"]       = $rs["groupid"];
      $GLOBALS["fsTopgroupName"]  = $rs["topgroupname"];
      $GLOBALS["fsGroupDesc"]     = $rs["groupdesc"];
      $GLOBALS["fsGroupLink"]     = $rs["grouplink"];
      $GLOBALS["fsGroupOrderID"]  = $rs["grouporderid"];
      $GLOBALS["fsMenuImage1"]    = $rs["menuimage1"];
      $GLOBALS["fsMenuImage2"]    = $rs["menuimage2"];
      $GLOBALS["fsMenuImage3"]    = $rs["menuimage3"];
      $GLOBALS["fsMenuImage4"]    = $rs["menuimage4"];
      $GLOBALS["fbMenuVisible"]   = $rs["menuvisible"];
      $GLOBALS["fsOrderBy"]       = $rs["menuorderby"];
      $GLOBALS["fsOrderDir"]      = $rs["menuorderdir"];
      $GLOBALS["fsHoverTitle"]    = $rs["hovertitle"];
      $GLOBALS["fsOpenInPage"]    = $rs["openinpage"];
      $GLOBALS["fsLoginReq"]      = $rs["loginreq"];
      $GLOBALS["fsUsergroups"]    = $rs["usergroups"];
      $GLOBALS["fsSubgroupCount"] = $rs["subgroupcount"];
      $GLOBALS["fsEditType"]      = 'update';
   } else {
      $GLOBALS["fsEditType"]     = 'add';
   }

   $_POST["authorid"] = $rs["authorid"];
   if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }

   $_POST["GroupName"] = $_GET["GroupName"];
   $_POST["LanguageCode"] = $_GET["LanguageCode"];
} // function GetGlobalData()


function GetFormData()
{
   global $EZ_SESSION_VARS, $_POST;

   $GLOBALS["fsGroupID"]       = $_POST["groupid"];
   $GLOBALS["fsGroupName"]     = $_POST["groupname"];
   $GLOBALS["fsGroupDesc"]     = $_POST["groupdesc"];
   $GLOBALS["fsGroupLink"]     = $_POST["grouplink"];
   $GLOBALS["fsGroupOrderID"]  = $_POST["grouporderid"];
   $GLOBALS["fsMenuImage1"]    = $_POST["menuimage1"];
   $GLOBALS["fsMenuImage2"]    = $_POST["menuimage2"];
   $GLOBALS["fsMenuImage3"]    = $_POST["menuimage3"];
   $GLOBALS["fsMenuImage4"]    = $_POST["menuimage4"];
   $GLOBALS["fbMenuVisible"]   = $_POST["menuvisible"];
   $GLOBALS["fsOrderBy"]       = $_POST["menuorderby"];
   $GLOBALS["fsOrderDir"]      = $_POST["menuorderdir"];
   $GLOBALS["fsHoverTitle"]    = $_POST["hovertitle"];
   $GLOBALS["fsOpenInPage"]    = $_POST["openinpage"];
   $GLOBALS["fsLoginReq"]      = $_POST["loginreq"];
   $GLOBALS["fsUsergroups"]    = $_POST["usergroups"];
   $GLOBALS["fsSubgroupCount"] = $_POST["subgroupcount"];

   $GLOBALS["edittype"]        = $_POST["edittype"];

   if ($GLOBALS["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["groupdesc"] == "") { $GLOBALS["strErrors"][] = $GLOBALS["eTitleEmpty"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


$GLOBALS["eztbTable"] = $GLOBALS["eztbGroups"];
$GLOBALS["eztbKeyField"] = 'groupname';
$GLOBALS["keyfieldval"] = $_POST["GroupName"];
include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
