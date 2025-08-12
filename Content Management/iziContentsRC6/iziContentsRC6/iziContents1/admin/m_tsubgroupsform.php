<?php

/***************************************************************************

 m_tsubgroupsform.php
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
$GLOBALS["form"] = 'subgroups';
$validaccess = VerifyAdminLogin3("SubGroupName");

includeLanguageFiles('admin','subgroups');


$ImageFileTypes = array('gif', 'jpg', 'jpeg', 'png');

// If we've been passed the request from the content list, then we
//    read content data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["SubGroupName"] != '')
{
   $_POST["SubGroupName"] = $_GET["SubGroupName"];
   $_POST["LanguageCode"] = $_GET["LanguageCode"];
   $_POST["page"] = $_GET["page"];
   $_POST["filtergroupname"] = $_GET["filtergroupname"];
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

if ($_POST["submitted"] == "yes")
{
   // User has submitted the data
   if (bCheckForm())
   {
      AddSubGroup($basecharset,$charset);
      Header("Location: ".BuildLink('m_subgroups.php')."&page=".$_POST["page"]."&filtergroupname=".$_POST["filtergroupname"]."&filterlangname=".$_POST["LanguageCode"]);
   }
   else
   {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmSubGroupsForm($baselanguagename,$basecharset,$languagename,$charset);


function frmSubGroupsForm($baselanguagename,$basecharset,$languagename,$charset)
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
   adminformopen('subgroupdesc');
   adminformtitle(2,charsetText($GLOBALS["tFormTitle2"],$convertcharsets,$GLOBALS["gsCharset"]).' - '.charsetText($languagename,$convertcharsets,$GLOBALS["gsCharset"]));
   echo $GLOBALS["strErrors"];
   adminsubheader(2,charsetText($GLOBALS["thGeneral"],$convertcharsets,$GLOBALS["gsCharset"]));
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuRef","subgroupname"); ?>
       <td valign="top" class="content">
           <input type="text" name="subgroupname" size="32" value="<?php echo $GLOBALS["fsSubGroupName"]; ?>" maxlength="32" readonly>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuTitle","subgroupdesc"); ?>
       <td valign="top" class="content">
           <table border="0" cellpadding="1" cellspacing="0">
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="basesubgroupdesc" size="72" value="<?php echo charsetText($GLOBALS["bsSubGroupDesc"],$convertcharsets,$basecharset); ?>" maxlength="100" readonly>
                   </td>
               </tr>
               <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                       <b><?php echo charsetText($languagename,$convertcharsets,$charset); ?>:</b>
                   </td>
                   <td>
                       <input type="text" name="subgroupdesc" size="72" value="<?php echo charsetText($GLOBALS["fsSubGroupDesc"],$convertcharsets,$charset); ?>" maxlength="100"<?php echo $GLOBALS["fieldstatus"]; ?>>
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
       <?php FieldHeading("MenuImage1","submenuimage1"); ?>
       <td valign="top" class="content">
           <input type="text" name="submenuimage1" size="80" value="<?php echo $GLOBALS["fsMenuImage1"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('submenuimage1',$GLOBALS["fsMenuImage1"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage2","submenuimage2"); ?>
       <td valign="top" class="content">
           <input type="text" name="submenuimage2" size="80" value="<?php echo $GLOBALS["fsMenuImage2"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('submenuimage2',$GLOBALS["fsMenuImage2"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage3","submenuimage3"); ?>
       <td valign="top" class="content">
           <input type="text" name="submenuimage3" size="80" value="<?php echo $GLOBALS["fsMenuImage3"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('submenuimage3',$GLOBALS["fsMenuImage3"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage4","submenuimage4"); ?>
       <td valign="top" class="content">
           <input type="text" name="submenuimage4" size="80" value="<?php echo $GLOBALS["fsMenuImage4"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('submenuimage4',$GLOBALS["fsMenuImage4"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php
   fadminformsavebar(2,'m_subgroups.php');
   if ($GLOBALS["specialedit"] == True)
   {
      adminhelpmsg(2);
      ?><input type="hidden" name="subgroupid" value="<?php echo $GLOBALS["fsSubGroupID"]; ?>"><?php
      ?><input type="hidden" name="SubGroupName" value="<?php echo $_POST["SubGroupName"]; ?>"><?php
      ?><input type="hidden" name="LanguageCode" value="<?php echo $_POST["LanguageCode"]; ?>"><?php
      ?><input type="hidden" name="subgrouporderid" value="<?php echo $GLOBALS["fsSubGroupOrderID"]; ?>"><?php
      ?><input type="hidden" name="filtergroupname" value="<?php echo $_POST["filtergroupname"]; ?>"><?php

      ?><input type="hidden" name="groupname" value="<?php echo $GLOBALS["fsGroupName"]; ?>"><?php
      ?><input type="hidden" name="submenuvisible" value="<?php echo $GLOBALS["fbMenuVisible"]; ?>"><?php
      ?><input type="hidden" name="subgrouplink" value="<?php echo $GLOBALS["fsSubGroupLink"]; ?>"><?php
      ?><input type="hidden" name="openinpage" value="<?php echo $GLOBALS["fsOpenInPage"]; ?>"><?php
      ?><input type="hidden" name="grouporderid" value="<?php echo $GLOBALS["fsGroupOrderID"]; ?>"><?php
      ?><input type="hidden" name="submenuorderby" value="<?php echo $GLOBALS["fsOrderBy"]; ?>"><?php
      ?><input type="hidden" name="submenuorderdir" value="<?php echo $GLOBALS["fsOrderDir"]; ?>"><?php
      ?><input type="hidden" name="loginreq" value="<?php echo $GLOBALS["fsLoginReq"]; ?>"><?php
      ?><input type="hidden" name="usergroups" value="<?php echo $GLOBALS["fsUsergroups"]; ?>"><?php

      ?><input type="hidden" name="edittype" value="<?php echo $GLOBALS["fsEditType"]; ?>"><?php
   }
   adminformclose();
} // function frmSubGroupsForm()


function AddSubGroup($basecharset,$charset)
{
   global $_POST, $EZ_SESSION_VARS;

   $convertcharsets = ($basecharset != $charset);
   if ($convertcharsets) {
      if (!(function_exists('mb_convert_encoding'))) {
         $convertcharsets = false;
      }
   }

   $sSubGroupDesc  = dbString(UTF8Text($_POST["subgroupdesc"],$convertcharsets,$charset));
   $sHoverTitle    = dbString(UTF8Text($_POST["hovertitle"],$convertcharsets,$charset));

   if ($_POST["edittype"] != 'add') {
      $strQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET subgroupdesc='".$sSubGroupDesc."', submenuimage1='".$_POST["submenuimage1"]."', submenuimage2='".$_POST["submenuimage2"]."', hovertitle='".$sHoverTitle."', submenuimage3='".$_POST["submenuimage3"]."', submenuimage4='".$_POST["submenuimage4"]."' WHERE subgroupname='".$_POST["SubGroupName"]."' AND language='".$_POST["LanguageCode"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbSubgroups"]." VALUES('', '".$_POST["groupname"]."', '".$sSubGroupDesc."', '".$_POST["subgrouplink"]."', '".$_POST["subgrouporderid"]."', '".$_POST["submenuimage1"]."', '".$_POST["submenuimage2"]."', '".$_POST["submenuvisible"]."', '".$_POST["submenuorderby"]."', '".$_POST["submenuorderdir"]."', '".$sHoverTitle."', '".$_POST["openinpage"]."', '".$_POST["loginreq"]."', '".$_POST["usergroups"]."', '".$_POST["SubGroupName"]."', '".$_POST["LanguageCode"]."', '".$_POST["submenuimage3"]."', '".$_POST["submenuimage4"]."', '".$EZ_SESSION_VARS["UserID"]."')";
   }
   $result = dbExecute($strQuery,true);

   dbCommit();
} // function AddSubGroup()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$_GET["SubGroupName"]."' AND language='".$GLOBALS["gsDefault_language"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["bsSubGroupDesc"]    = $rs["subgroupdesc"];
   $GLOBALS["bsHoverTitle"]      = $rs["hovertitle"];

   $GLOBALS["fsSubGroupID"]      = $rs["subgroupid"];
   $GLOBALS["fsSubGroupName"]    = $rs["subgroupname"];
   $GLOBALS["fsGroupName"]       = $rs["groupname"];
   $GLOBALS["fsSubGroupDesc"]    = $rs["subgroupdesc"];
   $GLOBALS["fsSubGroupLink"]    = $rs["subgrouplink"];
   $GLOBALS["fsSubGroupOrderID"] = $rs["subgrouporderid"];
   $GLOBALS["fsMenuImage1"]      = $rs["submenuimage1"];
   $GLOBALS["fsMenuImage2"]      = $rs["submenuimage2"];
   $GLOBALS["fsMenuImage3"]      = $rs["submenuimage3"];
   $GLOBALS["fsMenuImage4"]      = $rs["submenuimage4"];
   $GLOBALS["fbMenuVisible"]     = $rs["submenuvisible"];
   $GLOBALS["fsOrderBy"]         = $rs["submenuorderby"];
   $GLOBALS["fsOrderDir"]        = $rs["submenuorderdir"];
   $GLOBALS["fsHoverTitle"]      = $rs["hovertitle"];
   $GLOBALS["fsOpenInPage"]      = $rs["openinpage"];
   $GLOBALS["fsLoginReq"]        = $rs["loginreq"];
   $GLOBALS["fsUsergroups"]      = $rs["usergroups"];


   $strQuery="SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$_GET["SubGroupName"]."' AND language='".$_GET["LanguageCode"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   if (dbRowsReturned($result) != 0)
   {
      $rs     = dbFetch($result);

      $GLOBALS["fsSubGroupID"]      = $rs["subgroupid"];
      $GLOBALS["fsSubGroupName"]    = $rs["subgroupname"];
      $GLOBALS["fsGroupName"]       = $rs["groupname"];
      $GLOBALS["fsSubGroupDesc"]    = $rs["subgroupdesc"];
      $GLOBALS["fsSubGroupLink"]    = $rs["subgrouplink"];
      $GLOBALS["fsSubGroupOrderID"] = $rs["subgrouporderid"];
      $GLOBALS["fsMenuImage1"]      = $rs["submenuimage1"];
      $GLOBALS["fsMenuImage2"]      = $rs["submenuimage2"];
      $GLOBALS["fsMenuImage3"]      = $rs["submenuimage3"];
      $GLOBALS["fsMenuImage4"]      = $rs["submenuimage4"];
      $GLOBALS["fbMenuVisible"]     = $rs["submenuvisible"];
      $GLOBALS["fsOrderBy"]         = $rs["submenuorderby"];
      $GLOBALS["fsOrderDir"]        = $rs["submenuorderdir"];
      $GLOBALS["fsHoverTitle"]      = $rs["hovertitle"];
      $GLOBALS["fsOpenInPage"]      = $rs["openinpage"];
      $GLOBALS["fsLoginReq"]        = $rs["loginreq"];
      $GLOBALS["fsUsergroups"]      = $rs["usergroups"];
      $GLOBALS["fsEditType"]        = 'update';
   }
   else
   {
      $GLOBALS["fsEditType"]        = 'add';
   }

   $_POST["authorid"] = $rs["authorid"];
   if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"])
   {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }

   $_POST["SubGroupName"] = $_GET["SubGroupName"];
   $_POST["LanguageCode"] = $_GET["LanguageCode"];
} // function GetGlobalData()


function GetFormData()
{
   global $EZ_SESSION_VARS, $_POST;

   $GLOBALS["fsSubGroupID"]      = $_POST["subgroupid"];
   $GLOBALS["fsSubGroupName"]    = $_POST["subgroupname"];
   $GLOBALS["fsGroupName"]       = $_POST["groupname"];
   $GLOBALS["fsSubGroupDesc"]    = $_POST["subgroupdesc"];
   $GLOBALS["fsSubGroupLink"]    = $_POST["subgrouplink"];
   $GLOBALS["fsSubGroupOrderID"] = $_POST["subgrouporderid"];
   $GLOBALS["fsMenuImage1"]      = $_POST["submenuimage1"];
   $GLOBALS["fsMenuImage2"]      = $_POST["submenuimage2"];
   $GLOBALS["fsMenuImage3"]      = $_POST["submenuimage3"];
   $GLOBALS["fsMenuImage4"]      = $_POST["submenuimage4"];
   $GLOBALS["fbMenuVisible"]     = $_POST["submenuvisible"];
   $GLOBALS["fsOrderBy"]         = $_POST["submenuorderby"];
   $GLOBALS["fsOrderDir"]        = $_POST["submenuorderdir"];
   $GLOBALS["fsHoverTitle"]      = $_POST["hovertitle"];
   $GLOBALS["fsOpenInPage"]      = $_POST["openinpage"];
   $GLOBALS["fsLoginReq"]        = $_POST["loginreq"];
   $GLOBALS["fsUsergroups"]      = $_POST["usergroups"];

   if ($GLOBALS["authorid"] == $EZ_SESSION_VARS["UserID"])
   {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   $strMessage = "<tr bgcolor=#900000><td colspan=2><b>";
   if ($_POST["subgroupdesc"] == "") {
      $strMessage .= $GLOBALS["eTitleEmpty"].'<br />';
      $bFormOK = false;
   }
   $strMessage .= "</b></td></tr>";
   if (!$bFormOK) { $GLOBALS["strErrors"] = $strMessage; }
   return $bFormOK;
} // function bCheckForm()


$GLOBALS["eztbTable"] = $GLOBALS["eztbSubgroups"];
$GLOBALS["eztbKeyField"] = 'subgroupname';
$GLOBALS["keyfieldval"] = $_POST["SubGroupName"];
include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
