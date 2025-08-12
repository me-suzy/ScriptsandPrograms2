<?php

/***************************************************************************

 m_sidebarsform.php
 -------------------
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
$GLOBALS["form"] = 'sidebars';
$validaccess = VerifyAdminLogin3("SidebarID");

includeLanguageFiles('admin','sidebars');


// If we've been passed the request from the tags list, then we
//    read the tag data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["SidebarID"] != "") {
   $_POST["SidebarID"] = $_GET["SidebarID"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
   GetGlobalData();
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      GetFormData();
      Addsidebar();
      Header("Location: ".BuildLink('m_sidebars.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmSidebarForm();


function frmSidebarForm()
{
   global $_POST;

   adminformheader();
   adminformopen('sidebarname');
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,$GLOBALS["thSBGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("SBName","sidebarname"); ?>
       <td valign="top" class="content">
           <input type="text" name="sidebarname" size="50" value="<?php echo $GLOBALS["gsSidebarname"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SBAlign",1); ?>
       <td valign="top" class="content">
           <input type="radio" value="L" name="sbalign" <?php if($GLOBALS["gsSidebaralign"] == "L" || $GLOBALS["gsSidebaralign"] == "") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tLeft"]; ?><br />
           <input type="radio" value="R" name="sbalign" <?php If($GLOBALS["gsSidebaralign"] == "R") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tRight"]; ?><br />
           <input type="radio" value="C" name="sbalign" <?php If($GLOBALS["gsSidebaralign"] == "C") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tCentre"]; ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SBBorder","sbborder"); ?>
       <td valign="top" class="content">
           <select name="sbborder" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderBorders($GLOBALS["gsSidebarborder"]); ?></select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SBWidth","sbwidth"); ?>
       <td valign="top" class="content">
           <select name="sbwidth" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderWidths($GLOBALS["gsSidebarwidth"]); ?></select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SBBackground","sbbgcolor"); ?>
       <td valign="top" class="content">
			<?php ColourField('sbbgcolor',$GLOBALS["gsSidebarbgcolor"]); ?>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'m_sidebars.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(2);
      ?><input type="hidden" name="SidebarID" value="<?php echo $_POST["SidebarID"]; ?>"><?php
   }
   adminformclose();
} // function frmSidebarForm()


function Addsidebar()
{
   global $_POST, $EZ_SESSION_VARS;

   if ($_POST["SidebarID"] != '') {
      $strQuery = "UPDATE ".$GLOBALS["eztbSidebartemplates"]." SET sidebarname='".$GLOBALS["gsSidebarname"]."', sbalign='".$GLOBALS["gsSidebaralign"]."', sbborder='".$GLOBALS["gsSidebarborder"]."', sbbgcolor='".$GLOBALS["gsSidebarbgcolor"]."', sbwidth='".$GLOBALS["gsSidebarwidth"]."' WHERE sidebarid='".$_POST["SidebarID"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbSidebartemplates"]." VALUES('', '".$GLOBALS["gsSidebarname"]."', '".$GLOBALS["gsSidebaralign"]."', '".$GLOBALS["gsSidebarborder"]."','".$GLOBALS["gsSidebarbgcolor"]."','".$GLOBALS["gsSidebarwidth"]."', ".$EZ_SESSION_VARS["UserID"].")";
   }
   $result = dbExecute($strQuery,true);
   dbCommit();
} // function Addsidebar()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery = "SELECT * FROM ".$GLOBALS["eztbSidebartemplates"]." WHERE sidebarid=".$_GET["SidebarID"];
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["gsSidebarname"]    = $rs["sidebarname"];
   $GLOBALS["gsSidebaralign"]   = $rs["sbalign"];
   $GLOBALS["gsSidebarborder"]  = $rs["sbborder"];
   $GLOBALS["gsSidebarbgcolor"] = $rs["sbbgcolor"];
   $GLOBALS["gsSidebarwidth"]   = $rs["sbwidth"];

   $_POST["authorid"] = $rs["authorid"];
   if ($rs["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
   dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
   global $_POST, $EZ_SESSION_VARS;

   $GLOBALS["gsSidebarname"]    = $_POST["sidebarname"];
   $GLOBALS["gsSidebaralign"]   = $_POST["sbalign"];
   $GLOBALS["gsSidebarborder"]  = $_POST["sbborder"];
   $GLOBALS["gsSidebarbgcolor"] = $_POST["sbbgcolor"];
   $GLOBALS["gsSidebarwidth"]   = $_POST["sbwidth"];

   if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["sidebarname"] == "") {
      $GLOBALS["strErrors"][] = $GLOBALS["eNoName"];
   }
   if(substr($_POST["sbbgcolor"],0,1) == '#' && strlen($_POST["sbbgcolor"]) != 7) {
      $GLOBALS["strErrors"][] = $GLOBALS["eColourWrong"];
   }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function RenderBorders($sBorder)
{
   for ($i=0; $i<5; $i++) {
      echo "<option";
      if ($sBorder == $i) { echo " selected"; }
      echo ">".$i;
   }
} // function RenderBorders()


function RenderWidths($sWidth)
{
   for ($i=5; $i<=100; $i += 5) {
      echo "<option";
      if ($sWidth == $i) { echo " selected"; }
      echo ">".$i."%";
   }
} // function RenderWidths()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
