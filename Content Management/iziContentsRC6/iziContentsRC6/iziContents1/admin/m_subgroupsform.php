<?php

/***************************************************************************

 m_subgroupsform.php
 --------------------
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
$GLOBALS["form"] = 'subgroups';
$validaccess = VerifyAdminLogin3("SubGroupName");

includeLanguageFiles('admin','subgroups');


$ImageFileTypes = array( 'gif', 'jpg', 'jpeg', 'png');

// If we've been passed the request from the content list, then we
//    read content data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["SubGroupName"] != '') {
   $_POST["SubGroupName"] = $_GET["SubGroupName"];
   $_POST["page"] = $_GET["page"];
   $_POST["filtergroupname"] = $_GET["filtergroupname"];
   GetGlobalData();
} else {
   if ($_GET["filtergroupname"] != '') {
      $GLOBALS["fsGroupName"] = $_GET["filtergroupname"];
   }
   $GLOBALS["fbSubMenuVisible"] = 'Y';
   $GLOBALS["fsAuthorId"]       = $EZ_SESSION_VARS["UserID"];
   $GLOBALS["fsOpenInPage"] = 'Y';
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   if (bCheckForm()) {
      AddSubGroup();
      Header("Location: ".BuildLink('m_subgroups.php')."&page=".$_POST["page"]."&filtergroupname=".$_POST["filtergroupname"]);
   } else {
      GetFormData();
   }
}
frmSubGroupsForm();


function frmSubGroupsForm()
{
   global $_POST, $EZ_SESSION_VARS;

   adminformheader();
   adminformopen('subgroupname');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuRef","subgroupname"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="subgroupname" size="32" value="<?php echo $GLOBALS["fsSubGroupName"]; ?>" maxlength="100"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SubmenuTitle","subgroupdesc"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="subgroupdesc" size="70" value="<?php echo $GLOBALS["fsSubGroupDesc"]; ?>" maxlength="100"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ParentMenu","groupname"); ?>
       <td valign="top" colspan="3" class="content">
           <select name="groupname" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><OPTION value="0">-- <?php echo $GLOBALS["tNoParent"]; ?> --<?php
               RenderGroups($GLOBALS["fsGroupName"]);
               ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuHover","hovertitle"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="hovertitle" size="70" value="<?php echo $GLOBALS["fsHoverTitle"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ShowMenu","submenuvisible"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="checkbox" name="submenuvisible" value="Y" <?php if($GLOBALS["fbSubMenuVisible"] == 'Y') echo "checked"?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thGraphics"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage1","submenuimage1"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="submenuimage1" size="64" value="<?php echo $GLOBALS["fsSubMenuImage1"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('submenuimage1',$GLOBALS["fsSubMenuImage1"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage2","submenuimage2"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="submenuimage2" size="64" value="<?php echo $GLOBALS["fsSubMenuImage2"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('submenuimage2',$GLOBALS["fsSubMenuImage2"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage3","submenuimage3"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="submenuimage3" size="64" value="<?php echo $GLOBALS["fsSubMenuImage3"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('submenuimage3',$GLOBALS["fsSubMenuImage3"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage4","submenuimage4"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="submenuimage4" size="64" value="<?php echo $GLOBALS["fsSubMenuImage4"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('submenuimage4',$GLOBALS["fsSubMenuImage4"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thLinks"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuLink","subgrouplink"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="subgrouplink" size="64" value="<?php echo $GLOBALS["fsSubGroupLink"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminmoduledisplay('subgrouplink'); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("OpenMenuLink","openinpage"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="checkbox" name="openinpage" value="Y" <?php if($GLOBALS["fsOpenInPage"] == 'Y') echo "checked"?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thSequence"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("OrderBy","submenuorderby"); ?>
       <td valign="top" colspan="3" class="content">
           <select name="submenuorderby" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="1" <?php if($GLOBALS["fsOrderBy"] == "1") echo "selected"; ?>><?php echo $GLOBALS["toOrderID"]; ?>
               <option value="2" <?php if($GLOBALS["fsOrderBy"] == "2") echo "selected"; ?>><?php echo $GLOBALS["toPublished"]; ?>
               <option value="3" <?php if($GLOBALS["fsOrderBy"] == "3") echo "selected"; ?>><?php echo $GLOBALS["toModified"]; ?>
               <option value="4" <?php if($GLOBALS["fsOrderBy"] == "4") echo "selected"; ?>><?php echo $GLOBALS["toAlphabetic"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("OrderDir","submenuorderdir"); ?>
       <td valign="top" colspan="3" class="content">
           <select name="submenuorderdir" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="A" <?php if($GLOBALS["fsOrderDir"] == "A") echo "selected"; ?>><?php echo $GLOBALS["tAscending"]; ?>
               <option value="D" <?php if($GLOBALS["fsOrderDir"] == "D") echo "selected"; ?>><?php echo $GLOBALS["tDescending"]; ?>
           </select>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thAccess"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MLoginReq","loginreq"); ?>
       <td valign="top" class="content">
           <input type="checkbox" name="loginreq" value="Y" <?php if($GLOBALS["fsLoginReq"] == 'Y') echo "checked"?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
       <?php FieldHeading("Usergroups",19); ?>
       <td valign="top" class="content" rowspan="2" >
           <select name="usergroups[]" multiple size="4"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderUsergroups($GLOBALS["fsUsergroups"]); ?></select>
       </td>
   </tr>
   <?php if ($EZ_SESSION_VARS["UserGroup"] == 'administrator') { $sFieldStatus = ''; } else { $sFieldStatus = ' DISABLED'; } ?>
   <tr class="tablecontent">
       <?php FieldHeading("Author",20); ?>
       <td valign="top" class="content">
           <select name="AuthorId" size="1"<?php echo $sFieldStatus; ?>><?php RenderAuthors($GLOBALS["fsAuthorId"]); ?></select>
       </td>
       <td valign="top" class="content">
           &nbsp;
       </td>
   </tr>
   <?php
   fadminformsavebar(4,'m_subgroups.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="subgroupid" value="<?php echo $GLOBALS["fsSubGroupID"]; ?>"><?php
      ?><input type="hidden" name="SubGroupName" value="<?php echo $_POST["SubGroupName"]; ?>"><?php
      ?><input type="hidden" name="oldgroupname" value="<?php echo $GLOBALS["OldGroupName"]; ?>"><?php
      ?><input type="hidden" name="subgrouporderid" value="<?php echo $GLOBALS["fsSubGroupOrderID"]; ?>"><?php
      ?><input type="hidden" name="filtergroupname" value="<?php echo $_POST["filtergroupname"]; ?>"><?php
   }
   adminformclose();
} // function frmSubGroupsForm()


function AddSubGroup()
{
   global $_POST, $EZ_SESSION_VARS;

   if ($_POST["AuthorId"] == '') { $_POST["AuthorId"] = $_POST["authorid"]; }

   $sSubGroupDesc  = dbString($_POST["subgroupdesc"]);
   $sHoverTitle    = dbString($_POST["hovertitle"]);

   $sUserGroups = '';
   if (isset($_POST["usergroups"])) {
      reset ($_POST["usergroups"]);
      while (list ($userkey, $userval) = each ($_POST["usergroups"])) {
         $sUserGroups .= ','.$userval;
      }
   }

   if ($_POST["groupname"] == '0') { $_POST["groupname"] = ''; }
   if ($_POST["SubGroupName"] != '') {
      // Update any foreign language copies of this group as well
      $strQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET groupname='".$_POST["groupname"]."', subgrouplink='".$_POST["subgrouplink"]."', subgrouporderid='".$_POST["subgrouporderid"]."', submenuvisible='".$_POST["submenuvisible"]."', submenuorderby='".$_POST["submenuorderby"]."', submenuorderdir='".$_POST["submenuorderdir"]."', openinpage='".$_POST["openinpage"]."', loginreq='".$_POST["loginreq"]."', usergroups='".$sUserGroups."', subgroupname='".$_POST["subgroupname"]."', authorid='".$_POST["AuthorId"]."' WHERE subgroupname='".$_POST["SubGroupName"]."' AND language<>'".$GLOBALS["gsLanguage"]."'";
      $result = dbExecute($strQuery,true);
      $strQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET groupname='".$_POST["groupname"]."', subgroupdesc='".$sSubGroupDesc."', subgrouplink='".$_POST["subgrouplink"]."', subgrouporderid='".$_POST["subgrouporderid"]."', submenuimage1='".$_POST["submenuimage1"]."', submenuimage2='".$_POST["submenuimage2"]."', submenuvisible='".$_POST["submenuvisible"]."', submenuorderby='".$_POST["submenuorderby"]."', submenuorderdir='".$_POST["submenuorderdir"]."', hovertitle='".$sHoverTitle."', openinpage='".$_POST["openinpage"]."', loginreq='".$_POST["loginreq"]."', usergroups='".$sUserGroups."', subgroupname='".$_POST["subgroupname"]."', submenuimage3='".$_POST["submenuimage3"]."', submenuimage4='".$_POST["submenuimage4"]."', authorid='".$_POST["AuthorId"]."' WHERE subgroupname='".$_POST["SubGroupName"]."' AND language='".$GLOBALS["gsLanguage"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbSubgroups"]." values('', '".$_POST["groupname"]."', '".$sSubGroupDesc."', '".$_POST["subgrouplink"]."', '".$_POST["subgrouporderid"]."', '".$_POST["submenuimage1"]."', '".$_POST["submenuimage2"]."', '".$_POST["submenuvisible"]."', '".$_POST["submenuorderby"]."', '".$_POST["submenuorderdir"]."', '".$sHoverTitle."', '".$_POST["openinpage"]."', '".$_POST["loginreq"]."', '".$sUserGroups."', '".$_POST["subgroupname"]."', '".$GLOBALS["gsLanguage"]."', '".$_POST["submenuimage3"]."', '".$_POST["submenuimage4"]."', '".$_POST["AuthorId"]."')";
   }
   $result = dbExecute($strQuery,true);
   $dummy = dbInsertValue($GLOBALS["eztbSubgroups"]);
   if ($dummy == 0) { $dummy = 'c'.$_POST["subgroupid"]; }

   // If we've changed the subgroup name, we need to reflect that in any content pages that are attached to that menu
   if (($_POST["SubGroupName"] != '') && ($_POST["subgroupname"] != $_POST["SubGroupName"])) {
      $strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET subgroupname='".$_POST["subgroupname"]."' WHERE subgroupname='".$_POST["SubGroupName"]."'";
      $result = dbExecute($strQuery,true);
   }

   // For new groups:
   //     if no name was specified, set a default
   //     if no orderid was specified, set a default
   if ((($_POST["SubGroupName"] == '') && ($_POST["subgroupname"] == '')) || ($_POST["subgrouporderid"] == '')) {
      if (($_POST["SubGroupName"] == '') && ($_POST["subgroupname"] == '')) {
         $subgroupname = 'c'.$dummy;
      } else { $subgroupname = $_POST["subgroupname"]; }
      if ($_POST["subgrouporderid"] == '') { $subgrouporderid = $dummy;
      } else { $subgrouporderid = $_POST["subgrouporderid"]; }
      $strQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET subgroupname='".$subgroupname."', subgrouporderid='".$subgrouporderid."' WHERE subgroupid='".$dummy."'";
      $result = dbExecute($strQuery,true);
   }

   //  If we've switched this subgroup from one group to another, then we adjust the subgroup count
   //     for those group record (across all languages).
   if ($_POST["groupname"] != $_POST["oldgroupname"]) {
      if ($_POST["oldgroupname"] != '') {
         $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET subgroupcount=subgroupcount - 1 WHERE groupname='".$_POST["oldgroupname"]."'";
         $result = dbExecute($strQuery,true);
      }
      if ($_POST["groupname"] != '') {
         $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET subgroupcount=subgroupcount + 1 WHERE groupname='".$_POST["groupname"]."'";
         $result = dbExecute($strQuery,true);
      }
   }
   dbCommit();
} // function AddSubGroup()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$_GET["SubGroupName"]."' AND language='".$GLOBALS["gsLanguage"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["fsSubGroupID"]      = $rs["subgroupid"];
   $GLOBALS["fsSubGroupName"]    = $rs["subgroupname"];
   $GLOBALS["fsSubGroupDesc"]    = $rs["subgroupdesc"];
   $GLOBALS["fsGroupName"]       = $rs["groupname"];
   $GLOBALS["fsSubGroupLink"]    = $rs["subgrouplink"];
   $GLOBALS["fsSubGroupOrderID"] = $rs["subgrouporderid"];
   $GLOBALS["fsSubMenuImage1"]   = $rs["submenuimage1"];
   $GLOBALS["fsSubMenuImage2"]   = $rs["submenuimage2"];
   $GLOBALS["fsSubMenuImage3"]   = $rs["submenuimage3"];
   $GLOBALS["fsSubMenuImage4"]   = $rs["submenuimage4"];
   $GLOBALS["fbSubMenuVisible"]  = $rs["submenuvisible"];
   $GLOBALS["fsOrderBy"]         = $rs["submenuorderby"];
   $GLOBALS["fsOrderDir"]        = $rs["submenuorderdir"];
   $GLOBALS["fsHoverTitle"]      = $rs["hovertitle"];
   $GLOBALS["fsOpenInPage"]      = $rs["openinpage"];
   $GLOBALS["fsLoginReq"]        = $rs["loginreq"];
   $GLOBALS["fsUsergroups"]      = $rs["usergroups"];
   $GLOBALS["OldGroupName"]      = $rs["groupname"];
   $GLOBALS["fsAuthorId"]        = $rs["authorid"];

   $_POST["authorid"] = $rs["authorid"];
   if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }

   $_POST["SubGroupName"]    = $_GET["SubGroupName"];
   $_POST["filtergroupname"] = $_GET["filtergroupname"];
   dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
   global $EZ_SESSION_VARS, $_POST;

   $GLOBALS["fsSubGroupID"]      = $_POST["subgroupid"];
   $GLOBALS["fsSubGroupName"]    = $_POST["subgroupname"];
   $GLOBALS["fsSubGroupDesc"]    = $_POST["subgroupdesc"];
   $GLOBALS["fsGroupName"]       = $_POST["groupname"];
   $GLOBALS["fsSubGroupLink"]    = $_POST["subgrouplink"];
   $GLOBALS["fsSubGroupOrderID"] = $_POST["subgrouporderid"];
   $GLOBALS["fsSubMenuImage1"]   = $_POST["submenuimage1"];
   $GLOBALS["fsSubMenuImage2"]   = $_POST["submenuimage2"];
   $GLOBALS["fsSubMenuImage3"]   = $_POST["submenuimage3"];
   $GLOBALS["fsSubMenuImage4"]   = $_POST["submenuimage4"];
   $GLOBALS["fbSubMenuVisible"]  = $_POST["submenuvisible"];
   $GLOBALS["fsOrderBy"]         = $_POST["submenuorderby"];
   $GLOBALS["fsOrderDir"]        = $_POST["submenuorderdir"];
   $GLOBALS["fsHoverTitle"]      = $_POST["hovertitle"];
   $GLOBALS["fsOpenInPage"]      = $_POST["openinpage"];
   $GLOBALS["fsLoginReq"]        = $_POST["loginreq"];
   $GLOBALS["fsUsergroups"]      = $_POST["usergroups"];
   $GLOBALS["OldGroupName"]      = $_POST["oldgroupname"];
   $GLOBALS["fsAuthorId"]        = $_POST["authorid"];

   if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function RenderGroups($SubGroupName)
{
   global $EZ_SESSION_VARS;

   if ($GLOBALS["gsShowTopMenu"] == 'Y') {
      if (($GLOBALS["gsSectionSecurity"] == 'Y') && ($GLOBALS["fieldstatus"] != ' disabled') && ($EZ_SESSION_VARS["UserGroup"] != $GLOBALS["gsAdminPrivGroup"])) {
         $sqlQuery = "SELECT g.groupname AS groupname,g.groupdesc AS groupdesc,t.topgroupdesc AS topgroupdesc FROM ".$GLOBALS["eztbGroups"]." g LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE g.language='".$GLOBALS["gsLanguage"]."' AND g.grouplink='' AND g.authorid='".$EZ_SESSION_VARS["UserID"]."' ORDER BY t.topgrouporderid,g.grouporderid";
      } else {
         $sqlQuery = "SELECT g.groupname AS groupname,g.groupdesc AS groupdesc,t.topgroupdesc AS topgroupdesc FROM ".$GLOBALS["eztbGroups"]." g LEFT JOIN ".$GLOBALS["eztbTopgroups"]." t ON t.topgroupname=g.topgroupname AND t.language=g.language WHERE g.language='".$GLOBALS["gsLanguage"]."' AND g.grouplink='' ORDER BY t.topgrouporderid,g.grouporderid";
      }
   } else {
      if (($GLOBALS["gsSectionSecurity"] == 'Y') && ($GLOBALS["fieldstatus"] != ' disabled') && ($EZ_SESSION_VARS["UserGroup"] != $GLOBALS["gsAdminPrivGroup"])) {
         $sqlQuery = "SELECT groupname,groupdesc FROM ".$GLOBALS["eztbGroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND grouplink='' AND authorid='".$EZ_SESSION_VARS["UserID"]."' ORDER BY grouporderid";
      } else {
         $sqlQuery = "SELECT groupname,groupdesc FROM ".$GLOBALS["eztbGroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND grouplink='' ORDER BY grouporderid";
      }
   }
   $result = dbRetrieve($sqlQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      echo '<option ';
      if ($SubGroupName == $rs["groupname"]) { echo 'selected '; }
      echo 'value="'.$rs["groupname"].'">';
      if ($GLOBALS["gsShowTopMenu"] == 'Y') { echo $rs["topgroupdesc"].' - '; } 
      echo $rs["groupdesc"];
   }
   dbFreeResult($result);
} // function RenderGroups()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if (bRecordExists('eztbSubgroups','subgroupname',$_POST["subgroupname"],'subgroupid')) {
      $GLOBALS["strErrors"][] = $GLOBALS["eMenuExists"];
   }
   if ($_POST["subgroupname"] <> urlencode($_POST["subgroupname"]))
   {
      $GLOBALS["strErrors"][] = $GLOBALS["eInvalidName"];
   }
   if (is_numeric($_POST["subgroupname"])) {
      $GLOBALS["strErrors"][] = $GLOBALS["eInvalidName"];
   }

   if($_POST["subgroupdesc"] == "") {
      $GLOBALS["strErrors"][] = $GLOBALS["eTitleEmpty"];
   }

   //if(!$bFormOK) { $GLOBALS["strErrors"] = $strMessage; }
   return $bFormOK;
} // function bCheckForm()


function RenderUsergroups($GroupNames)
{
   $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' ORDER BY usergroupname";
   $result = dbRetrieve($sqlQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      echo '<option ';
      if (strpos($GroupNames, $rs["usergroupname"], 0)) { echo 'selected '; }
      echo 'value="'.$rs["usergroupname"].'">'.$rs["usergroupdesc"];
   }
   dbFreeResult($result);
} // function RenderUsergroups()


function RenderAuthors($AuthorId)
{
   $sqlQuery = "SELECT authorid,authorname FROM ".$GLOBALS["eztbAuthors"]." ORDER BY authorname";
   $result = dbRetrieve($sqlQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      echo '<option ';
      if ($AuthorId == $rs["authorid"]) { echo 'selected '; }
      echo 'value="'.$rs["authorid"].'">'.$rs["authorname"];
   }
   dbFreeResult($result);
} // function RenderAuthors()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
