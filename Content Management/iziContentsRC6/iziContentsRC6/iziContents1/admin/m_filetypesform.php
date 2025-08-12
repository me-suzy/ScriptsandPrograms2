<?php

/***************************************************************************

 m_filetypesform.php
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
$GLOBALS["form"] = 'filetypes';
$validaccess = VerifyAdminLogin3("FiletypeID");

includeLanguageFiles('admin','filetypes');


// If we've been passed the request from the filetypes list, then we
//    read the filetype data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["FiletypeID"] != '')
{
   $_POST["FiletypeID"] = $_GET["FiletypeID"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
   GetGlobalData();
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes")
{
   // User has submitted the data
   if (bCheckForm())
   {
      AddFiletype();
      Header("Location: ".BuildLink('m_filetypes.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]);
   }
   else
   {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmFiletypeForm();


function frmFiletypeForm()
{
   global $_POST;

   adminformheader();
   adminformopen('filetypename');
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,$GLOBALS["thFiletypeGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("FileCategory","filetypecat"); ?>
       <td valign="top" class="content">
           <select name="filetypecat" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderCats($GLOBALS["gsFiletypeCat"]); ?></select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("FileType","filetypename"); ?>
       <td valign="top" class="content">
           <input type="text" name="filetypename" size="32" value="<?php echo $GLOBALS["gsFiletypeName"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("MIMEType","mimetype"); ?>
       <td valign="top" class="content">
           <input type="text" name="mimetype" size="40" value="<?php echo $GLOBALS["gsMIMEType"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("FileIcon","filetypeicon"); ?>
       <td valign=top class="content">
           <input type="text" name="filetypeicon" size="64" value="<?php echo $GLOBALS["gsFiletypeIcon"]; ?>" maxlength="255"<?php echo $GLOBALS["fieldstatus"]; ?>>
           <?php adminimagedisplay('filetypeicon',$GLOBALS["gsFiletypeIcon"],$GLOBALS["tShowImage"]); ?>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'m_filetypes.php');
   if ($GLOBALS["specialedit"] == True)
   {
      adminhelpmsg(2);
      ?><input type="hidden" name="FiletypeID" value="<?php echo $_POST["FiletypeID"]; ?>"><?php
   }
   adminformclose();
} // function frmFiletypeForm()


function AddFiletype()
{
   global $_POST, $EZ_SESSION_VARS;

   if ($_POST["FiletypeID"] != '')
   {
      $strQuery = "UPDATE ".$GLOBALS["eztbFiletypes"]." SET filetype='".$_POST["filetypename"]."', filecat='".$_POST["filetypecat"]."', mimetype='".$_POST["mimetype"]."', fileicon='".$_POST["filetypeicon"]."' WHERE filetypeid='".$_POST["FiletypeID"]."'";
   }
   else
   {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbFiletypes"]."(filecat,filetype,mimetype,fileicon,authorid) VALUES('".$_POST["filetypecat"]."', '".$_POST["filetypename"]."', '".$_POST["mimetype"]."', '".$_POST["fileicon"]."', '".$EZ_SESSION_VARS["UserID"]."')";
   }
   $result = dbExecute($strQuery,true);

   dbCommit();
} // function AddFiletype()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["filetypename"] == "")	{ $GLOBALS["strErrors"][] = $GLOBALS["eNoFiletype"]; }
   if ($_POST["mimetype"] == "")	{ $GLOBALS["strErrors"][] = $GLOBALS["eNoMIMEType"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbFiletypes"]." WHERE filetypeid='".$_GET["FiletypeID"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["gsFiletypeCat"]  = $rs["filecat"];
   $GLOBALS["gsFiletypeName"] = $rs["filetype"];
   $GLOBALS["gsMIMEType"]     = $rs["mimetype"];
   $GLOBALS["gsFiletypeIcon"] = $rs["fileicon"];

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

   $GLOBALS["gsFiletypeCat"]  = $_POST["filetypecat"];
   $GLOBALS["gsFiletypeName"] = $_POST["filetypename"];
   $GLOBALS["gsMIMEType"]     = $_POST["mimetype"];
   $GLOBALS["gsFiletypeIcon"] = $_POST["filetypeicon"];

   if ($_POST["authorid"] == $EZ_SESSION_VARS["UserID"]) {
      $GLOBALS["specialedit"] = True;
      $GLOBALS["fieldstatus"] = '';
   }
} // function GetFormData()


function RenderCats($cat)
{
   echo '<option ';
   if ($cat == $GLOBALS["tFileCatBackup"]) { echo 'selected '; }
   echo 'value="'.$GLOBALS["tFileCatBackup"].'">'.$GLOBALS["tFileCatBackup"];
   echo '<option ';
   if ($cat == $GLOBALS["tFileCatDownload"]) { echo 'selected '; }
   echo 'value="'.$GLOBALS["tFileCatDownload"].'">'.$GLOBALS["tFileCatDownload"];
   echo '<option ';
   if ($cat == $GLOBALS["tFileCatImage"]) { echo 'selected '; }
   echo 'value="'.$GLOBALS["tFileCatImage"].'">'.$GLOBALS["tFileCatImage"];
   echo '<option ';
   if ($cat == $GLOBALS["tFileCatScript"]) { echo 'selected '; }
   echo 'value="'.$GLOBALS["tFileCatScript"].'">'.$GLOBALS["tFileCatScript"];
} // function RenderCats()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
