<?php

/***************************************************************************

 m_groupsform.php
 -----------------
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
$GLOBALS["form"] = 'groups';
$validaccess = VerifyAdminLogin3("GroupName");

includeLanguageFiles('admin','groups');


$ImageFileTypes = array('gif', 'jpg', 'jpeg', 'png');


// If we've been passed the request from the tags list, then we
//    read the tag data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["GroupName"] != '') {
   $_POST["GroupName"] = $_GET["GroupName"];
   $_POST["page"] = $_GET["page"];
   GetGlobalData();
} else {
   $GLOBALS["fbMenuVisible"] = 'Y';
   $GLOBALS["fsAuthorId"]    = $EZ_SESSION_VARS["UserID"];
   $GLOBALS["fsOpenInPage"] = 'Y';
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AddGroup();
      Header("Location: ".BuildLink('m_groups.php')."&page=".$_POST["page"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmGroupsForm();


function frmGroupsForm()
{
   global $_POST, $EZ_SESSION_VARS;

   adminformheader();
   adminformopen('groupname');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuRef","groupname"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="groupname" size="32" value="<?php echo $GLOBALS["fsGroupName"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuTitle","groupdesc"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="groupdesc" size="70" value="<?php echo $GLOBALS["fsGroupDesc"]; ?>" maxlength="100"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php
   if ($GLOBALS["gsShowTopMenu"] == 'Y') {
      ?>
      <tr class="tablecontent">
          <?php FieldHeading("ParentMenu","topgroupname"); ?>
          <td valign="top" colspan="3" class="content">
              <select name="topgroupname" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><OPTION value="0">-- <?php echo $GLOBALS["tNoParent"]; ?> --<?php
                  RenderTopGroups($GLOBALS["fsTopGroupName"]);
                  if ($GLOBALS["fsTopGroupName"] == '999999999') {
                     ?><OPTION value="999999999" selected>-- <?php echo $GLOBALS["tAllParents"]; ?> --<?php
                  } elseif (($GLOBALS["gsSectionSecurity"] != 'Y') || ($GLOBALS["fieldstatus"] == ' disabled') || ($EZ_SESSION_VARS["UserGroup"] == $GLOBALS["gsAdminPrivGroup"])) {
                     ?><OPTION value="999999999">-- <?php echo $GLOBALS["tAllParents"]; ?> --<?php
                  }
                  ?>
              </select>
          </td>
      </tr>
      <?php
   } else {
      ?>
      <input type="hidden" name="topgroupname" value="0">
      <?php
   }
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuHover","hovertitle"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="hovertitle" size="70" value="<?php echo $GLOBALS["fsHoverTitle"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ShowMenu","menuvisible"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="checkbox" name="menuvisible" value="Y" <?php if ($GLOBALS["fbMenuVisible"] == 'Y') echo "checked"; ?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thGraphics"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage1","menuimage1"); ?>
       <td valign=top colspan="3" class="content">
           <input type="text" name="menuimage1" size="64" value="<?php echo $GLOBALS["fsMenuImage1"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('menuimage1',$GLOBALS["fsMenuImage1"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage2","menuimage2"); ?>
       <td valign=top colspan="3" class="content">
           <input type="text" name="menuimage2" size="64" value="<?php echo $GLOBALS["fsMenuImage2"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('menuimage2',$GLOBALS["fsMenuImage2"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage3","menuimage3"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="menuimage3" size="64" value="<?php echo $GLOBALS["fsMenuImage3"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('menuimage3',$GLOBALS["fsMenuImage3"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage4","menuimage4"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="menuimage4" size="64" value="<?php echo $GLOBALS["fsMenuImage4"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('menuimage4',$GLOBALS["fsMenuImage4"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thLinks"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuLink","grouplink"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="grouplink" size="64" value="<?php echo $GLOBALS["fsGroupLink"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminmoduledisplay('grouplink'); ?>
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
       <?php FieldHeading("OrderBy","menuorderby"); ?>
       <td valign="top" colspan="3" class="content">
           <select name="menuorderby" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="1" <?php if($GLOBALS["fsOrderBy"] == "1") echo "selected"; ?>><?php echo $GLOBALS["toOrderID"]; ?>
               <option value="2" <?php if($GLOBALS["fsOrderBy"] == "2") echo "selected"; ?>><?php echo $GLOBALS["toPublished"]; ?>
               <option value="3" <?php if($GLOBALS["fsOrderBy"] == "3") echo "selected"; ?>><?php echo $GLOBALS["toModified"]; ?>
               <option value="4" <?php if($GLOBALS["fsOrderBy"] == "4") echo "selected"; ?>><?php echo $GLOBALS["toAlphabetic"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("OrderDir","menuorderdir"); ?>
       <td valign="top" colspan="3" class="content">
           <select name="menuorderdir" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
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
       <td valign="top" class="content" rowspan="2">
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
   adminformsavebar(4,'m_groups.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="groupid" value="<?php echo $GLOBALS["fsGroupID"]; ?>"><?php
      ?><input type="hidden" name="GroupName" value="<?php echo $_POST["GroupName"]; ?>"><?php
      ?><input type="hidden" name="grouporderid" value="<?php echo $GLOBALS["fsGroupOrderID"]; ?>"><?php
   }
   adminformclose();
} // function frmGroupsForm()


function AddGroup()
{
   global $_POST, $EZ_SESSION_VARS;

   if ($_POST["AuthorId"] == '') { $_POST["AuthorId"] = $_POST["authorid"]; }

   $sGroupDesc  = dbString($_POST["groupdesc"]);
   $sHoverTitle = dbString($_POST["hovertitle"]);

   $sUserGroups = '';
   if (isset($_POST["usergroups"])) {
      reset ($_POST["usergroups"]);
      while (list ($userkey, $userval) = each ($_POST["usergroups"])) {
         $sUserGroups .= ','.$userval;
      }
   }

   if ($_POST["topgroupname"] == '0') { $_POST["topgroupname"] = ''; }
   if ($_POST["GroupName"] != '') {
      // Update any foreign language copies of this group as well
      $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET grouplink='".$_POST["grouplink"]."', grouporderid='".$_POST["grouporderid"]."', menuvisible='".$_POST["menuvisible"]."', menuorderby='".$_POST["menuorderby"]."', menuorderdir='".$_POST["menuorderdir"]."', openinpage='".$_POST["openinpage"]."', topgroupname='".$_POST["topgroupname"]."', loginreq='".$_POST["loginreq"]."', usergroups='".$sUserGroups."', groupname='".$_POST["groupname"]."', authorid='".$_POST["AuthorId"]."' WHERE groupname='".$_POST["GroupName"]."' AND language<>'".$GLOBALS["gsLanguage"]."'";
      $result = dbExecute($strQuery,true);
      $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET groupdesc='".$sGroupDesc."', grouplink='".$_POST["grouplink"]."', grouporderid='".$_POST["grouporderid"]."', menuimage1='".$_POST["menuimage1"]."', menuimage2='".$_POST["menuimage2"]."', menuvisible='".$_POST["menuvisible"]."', menuorderby='".$_POST["menuorderby"]."', menuorderdir='".$_POST["menuorderdir"]."', hovertitle='".$sHoverTitle."', openinpage='".$_POST["openinpage"]."', topgroupname='".$_POST["topgroupname"]."', loginreq='".$_POST["loginreq"]."', usergroups='".$sUserGroups."', groupname='".$_POST["groupname"]."', menuimage3='".$_POST["menuimage3"]."', menuimage4='".$_POST["menuimage4"]."', authorid='".$_POST["AuthorId"]."' WHERE groupname='".$_POST["GroupName"]."' AND language='".$GLOBALS["gsLanguage"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbGroups"]." VALUES('', '".$sGroupDesc."', '".$_POST["grouplink"]."', '".$_POST["grouporderid"]."', '".$_POST["menuimage1"]."', '".$_POST["menuimage2"]."', '".$_POST["menuvisible"]."', '".$_POST["menuorderby"]."', '".$_POST["menuorderdir"]."', '".$sHoverTitle."', '".$_POST["openinpage"]."', '".$_POST["topgroupname"]."', '".$_POST["loginreq"]."', '".$sUserGroups."', '".$_POST["groupname"]."', '".$GLOBALS["gsLanguage"]."', '".$_POST["menuimage3"]."', '".$_POST["menuimage4"]."', '".$_POST["AuthorId"]."', 0)";
   }
   $result = dbExecute($strQuery,true);
   $dummy = dbInsertValue($GLOBALS["eztbGroups"]);
   if ($dummy == 0) { $dummy = 'b'.$_POST["groupid"]; }

   // If we've changed the group name, we need to reflect that in any subgroups or content pages that are attached
   // to that menu
   if (($_POST["GroupName"] != '') && ($_POST["groupname"] != $_POST["GroupName"])) {
      $strQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET groupname='".$_POST["groupname"]."' WHERE groupname='".$_POST["GroupName"]."'";
      $result = dbExecute($strQuery,true);
      $strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET groupname='".$_POST["groupname"]."' WHERE groupname='".$_POST["GroupName"]."'";
      $result = dbExecute($strQuery,true);
      // And if it's set as the homepage group, we need to change that as well
      if ($_POST["GroupName"] == $GLOBALS["gsHomepageGroup"]) {
         UpdateSetting($_POST["groupname"],'homepagegroup');
      }
   }

   // For new groups:
   //     if no name was specified, set a default
   //     if no orderid was specified, set a default
   if ((($_POST["GroupName"] == '') && ($_POST["groupname"] == '')) || ($_POST["grouporderid"] == '')) {
      if (($_POST["GroupName"] == '') && ($_POST["groupname"] == '')) {
         $groupname = 'b'.$dummy;
      } else {
         $groupname = $_POST["groupname"];
      }
      if ($_POST["grouporderid"] == '') {
         $grouporderid = $dummy;
      } else {
         $grouporderid = $_POST["grouporderid"];
      }
      $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET groupname='".$groupname."', grouporderid='".$grouporderid."' WHERE groupid='".$dummy."'";
      $result = dbExecute($strQuery,true);
   }
   dbCommit();
} // function AddGroup()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$_GET["GroupName"]."' AND language='".$GLOBALS["gsLanguage"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["fsGroupID"]      = $rs["groupid"];
   $GLOBALS["fsGroupName"]    = $rs["groupname"];
   $GLOBALS["fsGroupDesc"]    = $rs["groupdesc"];
   $GLOBALS["fsGroupLink"]    = $rs["grouplink"];
   $GLOBALS["fsGroupOrderID"] = $rs["grouporderid"];
   $GLOBALS["fsMenuImage1"]   = $rs["menuimage1"];
   $GLOBALS["fsMenuImage2"]   = $rs["menuimage2"];
   $GLOBALS["fsMenuImage3"]   = $rs["menuimage3"];
   $GLOBALS["fsMenuImage4"]   = $rs["menuimage4"];
   $GLOBALS["fbMenuVisible"]  = $rs["menuvisible"];
   $GLOBALS["fsOrderBy"]      = $rs["menuorderby"];
   $GLOBALS["fsOrderDir"]     = $rs["menuorderdir"];
   $GLOBALS["fsHoverTitle"]   = $rs["hovertitle"];
   $GLOBALS["fsOpenInPage"]   = $rs["openinpage"];
   $GLOBALS["fsTopGroupName"] = $rs["topgroupname"];
   $GLOBALS["fsLoginReq"]     = $rs["loginreq"];
   $GLOBALS["fsUsergroups"]   = $rs["usergroups"];
   $GLOBALS["fsAuthorId"]     = $rs["authorid"];

   $_POST["authorid"] = $rs["authorid"];
   if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }

   $_POST["GroupName"] = $_GET["GroupName"];
   dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
   global $EZ_SESSION_VARS, $_POST;

   $GLOBALS["fsGroupID"]      = $_POST["groupid"];
   $GLOBALS["fsGroupName"]    = $_POST["groupname"];
   $GLOBALS["fsGroupDesc"]    = $_POST["groupdesc"];
   $GLOBALS["fsGroupLink"]    = $_POST["grouplink"];
   $GLOBALS["fsGroupOrderID"] = $_POST["grouporderid"];
   $GLOBALS["fsMenuImage1"]   = $_POST["menuimage1"];
   $GLOBALS["fsMenuImage2"]   = $_POST["menuimage2"];
   $GLOBALS["fsMenuImage3"]   = $_POST["menuimage3"];
   $GLOBALS["fsMenuImage4"]   = $_POST["menuimage4"];
   $GLOBALS["fbMenuVisible"]  = $_POST["menuvisible"];
   $GLOBALS["fsOrderBy"]      = $_POST["menuorderby"];
   $GLOBALS["fsOrderDir"]     = $_POST["menuorderdir"];
   $GLOBALS["fsHoverTitle"]   = $_POST["hovertitle"];
   $GLOBALS["fsOpenInPage"]   = $_POST["openinpage"];
   $GLOBALS["fsTopGroupName"] = $_POST["topgroupname"];
   $GLOBALS["fsLoginReq"]     = $_POST["loginreq"];
   $GLOBALS["fsUsergroups"]   = $_POST["usergroups"];
   $GLOBALS["fsAuthorId"]     = $_POST["authorid"];

   if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function RenderTopGroups($GroupName)
{
   global $EZ_SESSION_VARS;

   if (($GLOBALS["gsSectionSecurity"] == 'Y') && ($GLOBALS["fieldstatus"] != ' disabled') && ($EZ_SESSION_VARS["UserGroup"] != $GLOBALS["gsAdminPrivGroup"])) {
      $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND topgrouplink='' AND (authorid='".$EZ_SESSION_VARS["UserID"]."' OR topgroupname='".$GroupName."') ORDER BY topgrouporderid";
   } else {
      $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' AND topgrouplink='' ORDER BY topgrouporderid";
   }
   $result = dbRetrieve($sqlQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      echo '<option ';
      if ($GroupName == $rs["topgroupname"]) { echo 'selected '; }
      echo 'value="'.$rs["topgroupname"].'">'.$rs["topgroupdesc"];
   }
   dbFreeResult($result);
} // function RenderTopGroups()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if (bRecordExists('eztbGroups','groupname',$_POST["groupname"],'groupid'))	{ $GLOBALS["strErrors"][] = $GLOBALS["eMenuExists"]; }
   if ($_POST["groupname"] != urlencode($_POST["groupname"]))		{ $GLOBALS["strErrors"][] = $GLOBALS["eInvalidName"]; }
   if (is_numeric($_POST["groupname"])) { $GLOBALS["strErrors"][] = $GLOBALS["eInvalidName"]; }
   if ($_POST["groupdesc"] == "")						{ $GLOBALS["strErrors"][] = $GLOBALS["eTitleEmpty"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
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

