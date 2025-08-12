<?php

/***************************************************************************

 m_topgroupsform.php
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
$GLOBALS["form"] = 'topgroups';
$validaccess = VerifyAdminLogin3("TopGroupName");

includeLanguageFiles('admin','topgroups');


$ImageFileTypes = array('gif', 'jpg', 'jpeg', 'png');

// If we've been passed the request from the content list, then we
//    read content data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["TopGroupName"] != '') {
   $_POST["TopGroupName"] = $_GET["TopGroupName"];
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
      AddTopGroup();
      Header("Location: ".BuildLink('m_topgroups.php')."&page=".$_POST["page"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmTopGroupsForm();


function frmTopGroupsForm()
{
   global $_POST, $EZ_SESSION_VARS;

   adminformheader();
   adminformopen('topgroupname');
   adminformtitle(4,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuRef","topgroupname"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="topgroupname" size="32" value="<?php echo $GLOBALS["fsTopGroupName"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuTitle","topgroupdesc"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="topgroupdesc" size="70" value="<?php echo $GLOBALS["fsTopGroupDesc"]; ?>" maxlength="100"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuHover","tophovertitle"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="tophovertitle" size="70" value="<?php echo $GLOBALS["fsHoverTitle"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ShowMenu","topmenuvisible"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="checkbox" name="topmenuvisible" value="Y" <?php if($GLOBALS["fbMenuVisible"] == 'Y') echo "checked"?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
		<td><b><? echo $GLOBALS["tCurrentTheme"]; ?>:</b></td>
       <td valign="top" colspan="3" class="content">
           <select name="toptheme">
               <option value=""><? echo $GLOBALS["tDefaultTheme"]; ?></option><?php
               $strQuery = "SELECT themecode,themename FROM ".$GLOBALS["eztbThemes"]." WHERE themeenabled='1'";
	           $themeresult = dbRetrieve($strQuery,true,0,0);
	           while ($themedata = dbFetch($themeresult)) {
	               if ($GLOBALS["fbTheme"] == $themedata["themecode"]) { $selectedstr = " selected"; } else { $selectedstr = ""; }
	               echo '
               <option value="'.$themedata["themecode"].'"'.$selectedstr.'>'.htmlentities($themedata["themename"]).'</option>';
	           }
	           dbFreeResult($themeresult);
           ?></select>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thGraphics"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage1","topmenuimage1"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="topmenuimage1" size="64" value="<?php echo $GLOBALS["fsMenuImage1"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('topmenuimage1',$GLOBALS["fsMenuImage1"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage2","topmenuimage2"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="topmenuimage2" size="64" value="<?php echo $GLOBALS["fsMenuImage2"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('topmenuimage2',$GLOBALS["fsMenuImage2"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage3","topmenuimage3"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="topmenuimage3" size="64" value="<?php echo $GLOBALS["fsMenuImage3"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('topmenuimage3',$GLOBALS["fsMenuImage3"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MenuImage4","topmenuimage4"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="topmenuimage4" size="64" value="<?php echo $GLOBALS["fsMenuImage4"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('topmenuimage4',$GLOBALS["fsMenuImage4"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thLinks"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("MenuLink","topgrouplink"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="text" name="topgrouplink" size="64" value="<?php echo $GLOBALS["fsTopGroupLink"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminmoduledisplay('topgrouplink'); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("OpenMenuLink","topopeninpage"); ?>
       <td valign="top" colspan="3" class="content">
           <input type="checkbox" name="topopeninpage" value="Y" <?php if($GLOBALS["fsOpenInPage"] == 'Y') echo "checked"?><?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thSequence"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("OrderBy","topmenuorderby"); ?>
       <td valign="top" colspan="3" class="content">
           <select name="topmenuorderby" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="1" <?php if($GLOBALS["fsOrderBy"] == "1") echo "selected"; ?>><?php echo $GLOBALS["toOrderID"]; ?>
               <option value="2" <?php if($GLOBALS["fsOrderBy"] == "2") echo "selected"; ?>><?php echo $GLOBALS["toPublished"]; ?>
               <option value="3" <?php if($GLOBALS["fsOrderBy"] == "3") echo "selected"; ?>><?php echo $GLOBALS["toModified"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("OrderDir","topmenuorderdir"); ?>
       <td valign="top" colspan="3" class="content">
           <select name="topmenuorderdir" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
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
       <?php FieldHeading("Usergroups",18); ?>
       <td valign="top" class="content" rowspan="2">
           <select name="usergroups[]" multiple size="4"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderUsergroups($GLOBALS["fsUsergroups"]); ?></select>
       </td>
   </tr>
   <?php if ($EZ_SESSION_VARS["UserGroup"] == 'administrator') { $sFieldStatus = ''; } else { $sFieldStatus = ' DISABLED'; } ?>
   <tr class="tablecontent">
       <?php FieldHeading("Author",19); ?>
       <td valign="top" class="content">
           <select name="AuthorId" size="1"<?php echo $sFieldStatus; ?>><?php RenderAuthors($GLOBALS["fsAuthorId"]); ?></select>
       </td>
       <td valign="top" class="content">
           &nbsp;
       </td>
   </tr>
   <?php
   adminformsavebar(4,'m_topgroups.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="topgroupid" value="<?php echo $GLOBALS["fsTopGroupID"]; ?>"><?php
      ?><input type="hidden" name="TopGroupName" value="<?php echo $_POST["TopGroupName"]; ?>"><?php
      ?><input type="hidden" name="topgrouporderid" value="<?php echo $GLOBALS["fsTopGroupOrderID"]; ?>"><?php
   }
   adminformclose();
} // function frmTopGroupsForm()


function AddTopGroup()
{
   global $_POST, $EZ_SESSION_VARS;

   if ($_POST["AuthorId"] == '') { $_POST["AuthorId"] = $_POST["authorid"]; }

   $sTopGroupDesc  = dbString($_POST["topgroupdesc"]);
   $sTopHoverTitle = dbString($_POST["tophovertitle"]);

   $sUserGroups = '';
   if (isset($_POST["usergroups"])) {
      reset ($_POST["usergroups"]);
      while (list ($userkey, $userval) = each ($_POST["usergroups"])) {
         $sUserGroups .= ','.$userval;
      }
   }

   if ($_POST["TopGroupName"] != '') {
      if ($GLOBALS["topgrouporderid"] == '') { $GLOBALS["topgrouporderid"] = $GLOBALS["topgroupid"]; }
      // Update any foreign language copies of this group as well
      $strQuery = "UPDATE ".$GLOBALS["eztbTopgroups"]." SET topgrouplink='".$_POST["topgrouplink"]."', topgrouporderid='".$_POST["topgrouporderid"]."', topmenuvisible='".$_POST["topmenuvisible"]."', topmenuorderby='".$_POST["topmenuorderby"]."', topmenuorderdir='".$_POST["topmenuorderdir"]."', topopeninpage='".$_POST["topopeninpage"]."', loginreq='".$_POST["loginreq"]."', usergroups='".$sUserGroups."', topgroupname='".$_POST["topgroupname"]."', authorid='".$_POST["AuthorId"]."' WHERE topgroupname='".$_POST["TopGroupName"]."' AND language<>'".$GLOBALS["gsLanguage"]."'";
      $result = dbExecute($strQuery,true);
      $strQuery = "UPDATE ".$GLOBALS["eztbTopgroups"]." SET topgroupdesc='".$sTopGroupDesc."', topgrouplink='".$_POST["topgrouplink"]."', topgrouporderid='".$_POST["topgrouporderid"]."', topmenuimage1='".$_POST["topmenuimage1"]."', topmenuimage2='".$_POST["topmenuimage2"]."', topmenuvisible='".$_POST["topmenuvisible"]."', topmenuorderby='".$_POST["topmenuorderby"]."', topmenuorderdir='".$_POST["topmenuorderdir"]."', tophovertitle='".$sTopHoverTitle."', topopeninpage='".$_POST["topopeninpage"]."', loginreq='".$_POST["loginreq"]."', usergroups='".$sUserGroups."', topgroupname='".$_POST["topgroupname"]."', topmenuimage3='".$_POST["topmenuimage3"]."', topmenuimage4='".$_POST["topmenuimage4"]."', authorid='".$_POST["AuthorId"]."', toptheme='".$_POST["toptheme"]."' WHERE topgroupname='".$_POST["TopGroupName"]."' AND language='".$GLOBALS["gsLanguage"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbTopgroups"]." VALUES('', '".$sTopGroupDesc."', '".$_POST["topgrouplink"]."', '".$_POST["topgrouporderid"]."', '".$_POST["topmenuimage1"]."', '".$_POST["topmenuimage2"]."', '".$sTopHoverTitle."', '".$_POST["topmenuvisible"]."', '".$_POST["topmenuorderby"]."', '".$_POST["topmenuorderdir"]."', '".$_POST["topopeninpage"]."', '".$_POST["loginreq"]."', '".$sUserGroups."', '".$_POST["topgroupname"]."', '".$GLOBALS["gsLanguage"]."', '".$_POST["topmenuimage3"]."', '".$_POST["topmenuimage4"]."', '".$_POST["AuthorId"]."', '".$_POST["toptheme"]."')";
   }
   $result = dbExecute($strQuery,true);
   $dummy = dbInsertValue($GLOBALS["eztbTopgroups"]);
   if ($dummy == 0) { $dummy = 'a'.$_POST["topgroupid"]; }

   // If we've changed the topgroup name, we need to reflect that in any groups that are attached to the menu
   if (($_POST["TopGroupName"] != '') && ($_POST["topgroupname"] != $_POST["TopGroupName"])) {
      $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET topgroupname='".$_POST["topgroupname"]."' WHERE topgroupname='".$_POST["TopGroupName"]."'";
      $result = dbExecute($strQuery,true);
      // And if it's set as the top-level homepage, we need to change that as well
      if ($_POST["TopGroupName"] == $GLOBALS["gsHomepageTopGroup"]) {
         UpdateSetting($_POST["topgroupname"],'homepagetopgroup');
      }
   }

   // For new groups:
   //     if no name was specified, set a default
   //     if no orderid was specified, set a default
   if ((($_POST["TopGroupName"] == '') && ($_POST["topgroupname"] == '')) || ($_POST["topgrouporderid"] == '')) {
      if (($_POST["TopGroupName"] == '') && ($_POST["topgroupname"] == '')) { $topgroupname = 'a'.$dummy;
      } else { $topgroupname = $_POST["topgroupname"]; }
      if ($_POST["topgrouporderid"] == '') { $topgrouporderid = $dummy;
      } else { $topgrouporderid = $_POST["topgrouporderid"]; }
      $strQuery = "UPDATE ".$GLOBALS["eztbTopgroups"]." SET topgroupname='".$topgroupname."', topgrouporderid='".$topgrouporderid."' WHERE topgroupid='".$dummy."'";
      $result = dbExecute($strQuery,true);
   }
   dbCommit();
} // function AddTopGroup()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$_GET["TopGroupName"]."' AND language='".$GLOBALS["gsLanguage"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
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
   $GLOBALS["fbTheme"]           = $rs["toptheme"];
   $GLOBALS["fsOrderBy"]         = $rs["topmenuorderby"];
   $GLOBALS["fsOrderDir"]        = $rs["topmenuorderdir"];
   $GLOBALS["fsHoverTitle"]      = $rs["tophovertitle"];
   $GLOBALS["fsOpenInPage"]      = $rs["topopeninpage"];
   $GLOBALS["fsLoginReq"]        = $rs["loginreq"];
   $GLOBALS["fsUsergroups"]      = $rs["usergroups"];
   $GLOBALS["fsAuthorId"]        = $rs["authorid"];

   $_POST["authorid"]   = $rs["authorid"];
   if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }

   $_POST["TopGroupName"] = $_GET["TopGroupName"];
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
   $GLOBALS["fbTheme"]           = $_POST["toptheme"];
   $GLOBALS["fsOrderBy"]         = $_POST["topmenuorderby"];
   $GLOBALS["fsOrderDir"]        = $_POST["topmenuorderdir"];
   $GLOBALS["fsHoverTitle"]      = $_POST["tophovertitle"];
   $GLOBALS["fsOpenInPage"]      = $_POST["topopeninpage"];
   $GLOBALS["fsLoginReq"]        = $_POST["loginreq"];
   $GLOBALS["fsUsergroups"]      = $_POST["usergroups"];
   $GLOBALS["fsAuthorId"]        = $_POST["authorid"];

   if ($GLOBALS["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if (bRecordExists('eztbTopgroups','topgroupname',$_POST["topgroupname"],'topgroupid')) {
      $GLOBALS["strErrors"][] = $GLOBALS["eMenuExists"];
   }
   if ($_POST["topgroupname"] <> urlencode($_POST["topgroupname"])) {
      $GLOBALS["strErrors"][] = $GLOBALS["eInvalidName"];
   }
   if (is_numeric($_POST["topgroupname"])) {
      $GLOBALS["strErrors"][] = $GLOBALS["eInvalidName"];
   }
   if ($_POST["topgroupdesc"] == "") {
      $GLOBALS["strErrors"][] = $GLOBALS["eTitleEmpty"];
   }

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

