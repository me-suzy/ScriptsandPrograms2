<?php

/***************************************************************************

 m_imageformatsform.php
 -----------------------
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
$GLOBALS["form"] = 'imageformats';
$validaccess = VerifyAdminLogin3("ImageformatID");

includeLanguageFiles('admin','imageformats');


// If we've been passed the request from the tags list, then we
//    read the tag data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["ImageformatID"] != "") {
   $_POST["ImageformatID"] = $_GET["ImageformatID"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
   GetGlobalData();
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      Addimageformat();
      Header("Location: ".BuildLink('m_imageformats.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmImageformatForm();


function frmImageformatForm()
{
   global $_POST;

   adminformheader();
   adminformopen('imageformatname');
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,$GLOBALS["thIFGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("IFName","imageformatname"); ?>
       <td valign="top" class="content">
           <input type="text" name="imageformatname" size="50" value="<?php echo $GLOBALS["gsImageformatname"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("IFAlign",1); ?>
       <td valign="top" class="content">
           <input type="radio" value="L" name="ifalign" <?php if($GLOBALS["gsImageformatalign"] == "L" || $GLOBALS["gsImageformatalign"] == "") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tLeft"]; ?><br />
           <input type="radio" value="R" name="ifalign" <?php If($GLOBALS["gsImageformatalign"] == "R") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tRight"]; ?><br />
           <input type="radio" value="C" name="ifalign" <?php If($GLOBALS["gsImageformatalign"] == "C") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tCentre"]; ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("IFBorder","ifborder"); ?>
       <td valign="top" class="content">
           <select name="ifborder" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderBorders($GLOBALS["gsImageformatborder"]); ?></select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("IFBackground","ifbgcolor"); ?>
       <td valign="top" class="content">
			<?php ColourField('ifbgcolor',$GLOBALS["gsImageformatbgcolor"]); ?>
   </tr>
   <?php
   adminformsavebar(2,'m_imageformats.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(2);
      ?><input type="hidden" name="ImageformatID" value="<?php echo $_POST["ImageformatID"]; ?>"><?php
   }
   adminformclose();
} // function frmImageformatForm()


function Addimageformat()
{
   global $_POST, $EZ_SESSION_VARS;

   if ($_POST["ImageformatID"] != '') {
      $strQuery = "UPDATE ".$GLOBALS["eztbImageformattemplates"]." SET imageformatname='".$_POST["imageformatname"]."', ifalign='".$_POST["ifalign"]."', ifborder='".$_POST["ifborder"]."', ifbgcolor='".$_POST["ifbgcolor"]."' WHERE imageformatid='".$_POST["ImageformatID"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbImageformattemplates"]." VALUES('', '".$_POST["imageformatname"]."', '".$_POST["ifalign"]."', '".$_POST["ifborder"]."','".$_POST["ifbgcolor"]."', ".$EZ_SESSION_VARS["UserID"].")";
   }
   $result = dbExecute($strQuery,true);
   dbCommit();
} // function Addimageformat()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbImageformattemplates"]." WHERE imageformatid='".$_GET["ImageformatID"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["gsImageformatname"]    = $rs["imageformatname"];
   $GLOBALS["gsImageformatalign"]   = $rs["ifalign"];
   $GLOBALS["gsImageformatborder"]  = $rs["ifborder"];
   $GLOBALS["gsImageformatbgcolor"] = $rs["ifbgcolor"];

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

   $GLOBALS["gsImageformatname"]    = $_POST["imageformatname"];
   $GLOBALS["gsImageformatalign"]   = $_POST["ifalign"];
   $GLOBALS["gsImageformatborder"]  = $_POST["ifborder"];
   $GLOBALS["gsImageformatbgcolor"] = $_POST["ifbgcolor"];

   if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["imageformatname"] == "")							{ $GLOBALS["strErrors"][] = $GLOBALS["eNoName"]; }
   if (substr($_POST["ifbgcolor"],0,1) == '#' && strlen($_POST["ifbgcolor"]) != 7)	{ $GLOBALS["strErrors"][] = $GLOBALS["eColourWrong"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function RenderBorders($sBorder)
{
   for ($i=0; $i<8; $i++) {
      echo "<option";
      if ($sBorder == $i) { echo " selected"; }
      echo ">".$i;
   }
} // function RenderBorders()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
