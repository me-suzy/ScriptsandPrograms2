<?php

/***************************************************************************

 m_subcontentcatsform.php
 -------------------------
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
$GLOBALS["form"] = 'subcontent';
$validaccess = VerifyAdminLogin3("CatID");

includeLanguageFiles('admin','subcontent');


// If we've been passed the request from the tags list, then we
//    read the tag data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_POST["SCID"] == '') { $_POST["SCID"] = $_GET["SCID"]; }
GetSpecialData($_POST["SCID"]);
if ($_GET["CatID"] != '') {
   $_POST["CatID"] = $_GET["CatID"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
   GetGlobalData();
}


$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AddCat();
      Header("Location: ".BuildLink('m_subcontentcats.php')."&SCID=".$_POST["SCID"]."&page=".$_POST["page"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmCatForm();


function frmCatForm()
{
   global $_POST;

   adminformheader();
   adminformopen('catname');
   adminformtitle(2,$GLOBALS["tFormTitle3"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("CatParent","catparent"); ?>
       <td valign="top" class="content">
           <select name="catparent" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>><OPTION value="0">-- <?php echo $GLOBALS["tNoParent"]; ?> --<?php
               RenderCats($GLOBALS["gsCatParent"]); ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("CatName","catname"); ?>
       <td valign="top" class="content">
           <input type="text" name="catname" size="32" value="<?php echo $GLOBALS["gsCatName"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("HiddenCat","3"); ?>
       <td valign="top" class="content">
           <input type="radio" value="1" name="hiddencat" <?php if($GLOBALS["gsHiddenCat"] == "1") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tYes"]; ?><br />
           <input type="radio" value="0" name="hiddencat" <?php If($GLOBALS["gsHiddenCat"] != "1") echo "checked" ?><?php echo $GLOBALS["fieldstatus"]; ?>><?php echo $GLOBALS["tNo"]; ?>
       </td>
   </tr>
   <?php
   catformsavebar(2,'m_subcontentcats.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(2);
      ?><input type="hidden" name="CatID" value="<?php echo $_POST["CatID"]; ?>"><?php
      ?><input type="hidden" name="oldcatref" value="<?php echo $GLOBALS["gsOldCatRef"]; ?>"><?php
      ?><input type="hidden" name="oldhiddencat" value="<?php echo $GLOBALS["gsOldHiddenCat"]; ?>"><?php
      ?><input type="hidden" name="SCID" value="<?php echo $_POST["SCID"]; ?>"><?php
   }
   adminformclose();
} // function frmCatForm()


function AddCat()
{
   global $_POST, $EZ_SESSION_VARS;

   $sCatname = dbString($_POST["catname"]);

   if ($_POST["CatID"] != '') {
      $strQuery = "UPDATE ".$GLOBALS["scCatTable"]." SET catname='".$_POST["catname"]."',hiddencat='".$_POST["hiddencat"]."' WHERE catid='".$_POST["CatID"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["scCatTable"]."(catname,hiddencat) VALUES('".$_POST["catname"]."','".$_POST["hiddencat"]."')";
   }
   $result = dbExecute($strQuery,true);

   // Set the cat reference to include parent category details
   $CatID = dbInsertValue($GLOBALS["scCatTable"]);
   if ($CatID == 0) { $CatID = $_POST["CatID"]; }
   if ($_POST["catparent"] == '0') { $catref = $CatID; }
   else { $catref = $_POST["catparent"].'.'.$CatID; }
   $strQuery = "UPDATE ".$GLOBALS["scCatTable"]." SET catref='".$catref."' WHERE catid='".$CatID."'";
   $result = dbExecute($strQuery,true);

   // We've changed the parent category
   if ($catref != $_POST["oldcatref"]) {
      // Move all child categories to the new parent tree
      $strQuery = "SELECT * FROM ".$GLOBALS["scCatTable"]." where catref LIKE '".$_POST["oldcatref"].".%'";
      $cresult = dbRetrieve($strQuery,true,0,0);
      while ($crs = dbFetch($cresult)) {
         $newcatref = $catref.substr($crs["catref"],strlen($_POST["oldcatref"]));
         $strQuery = "UPDATE ".$GLOBALS["scCatTable"]." SET catref='".$newcatref."' WHERE catid='".$crs["catid"]."'";
         $uresult = dbExecute($strQuery,true);
      }
      dbFreeResult($cresult);
      // Update any news articles that might be in this branch of the category hierarchy
      $strQuery = "SELECT ".$_POST["SCID"]."id AS id,catid FROM ".$GLOBALS["scTable"]." where catid='".$_POST["oldcatref"]."' or catid LIKE '".$_POST["oldcatref"].".%'";
      $cresult = dbRetrieve($strQuery,true,0,0);
      while ($crs = dbFetch($cresult)) {
         $newcatref = $catref.substr($crs["catref"],strlen($_POST["oldcatref"]));
         $strQuery = "UPDATE ".$GLOBALS["scTable"]." SET catid='".$newcatref."' WHERE ".$_POST["SCID"]."id='".$crs["id"]."'";
         $uresult = dbExecute($strQuery,true);
      }
      dbFreeResult($cresult);
   }

   // We've changed the hidden status, so all child categories for this parent also inherit the same hidden status
   if ($_POST["hiddencat"] != $_POST["oldhiddencat"]) {
      $strQuery = "SELECT * FROM ".$GLOBALS["scCatTable"]." where catref LIKE '".$_POST["oldcatref"].".%'";
      $cresult = dbRetrieve($strQuery,true,0,0);
      while ($crs = dbFetch($cresult)) {
         $strQuery = "UPDATE ".$GLOBALS["scCatTable"]." SET hiddencat='".$_POST["hiddencat"]."' WHERE catid='".$crs["catid"]."'";
         $uresult = dbExecute($strQuery,true);
      }
      dbFreeResult($cresult);
   }

   dbCommit();
} // function AddTag()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["catname"] == "") { $GLOBALS["strErrors"][] = $GLOBALS["eNoCat"]; }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function GetGlobalData()
{
   global $_GET;

   $strQuery="SELECT * FROM ".$GLOBALS["scCatTable"]." WHERE catid='".$_GET["CatID"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["gsCatName"]      = $rs["catname"];
   $GLOBALS["gsCatParent"]    = $rs["catref"];
   $GLOBALS["gsOldCatRef"]    = $rs["catref"];
   $GLOBALS["gsHiddenCat"]    = $rs["hiddencat"];
   $GLOBALS["gsOldHiddenCat"] = $rs["hiddencat"];

   $GLOBALS["specialedit"] = True;
   $GLOBALS["fieldstatus"] = '';
} // function GetGlobalData()


function GetFormData()
{
   global $_POST;

   $GLOBALS["gsCatName"]      = $_POST["catname"];
   $GLOBALS["gsCatParent"]    = $_POST["catparent"];
   $GLOBALS["gsOldCatRef"]    = $_POST["oldcatref"];
   $GLOBALS["gsHiddenCat"]    = $_POST["hiddencat"];
   $GLOBALS["gsOldHiddenCat"] = $_POST["oldhiddencat"];

   $GLOBALS["specialedit"] = True;
   $GLOBALS["fieldstatus"] = '';
} // function GetFormData()


function catformsavebar($colspan,$cancelref)
{
   global $_GET, $_POST;

   if ($_POST["page"] == '') { $_POST["page"] = $_GET["page"]; }
   if ($_POST["sort"] == '') { $_POST["sort"] = $_GET["sort"]; }
   ?>
   <tr class="topmenuback">
       <td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
           <?php if ($GLOBALS["specialedit"] == True)
           // Save privilege
           {
              ?>
              <input type="submit" value="<?php echo $GLOBALS["tSave"]; ?>" name="submit">&nbsp;
              <input type="reset" value="<?php echo $GLOBALS["tReset"]; ?>" name="reset">&nbsp;
              <?php
           }
           ?>
           <input type="button" value="<?php echo $GLOBALS["tCancel"]; ?>" onClick="javascript:document.location.href='<?php echo BuildLink($cancelref); ?>&page=<?php echo $_POST["page"]; ?>&sort=<?php echo $_POST["sort"]; ?>&filterlangname=<?php echo $_POST["LanguageCode"]; ?>&SCID=<?php echo $_POST["SCID"]; ?>'" name="cancel">
       </td>
   </tr>
   <?php
} // function catformsavebar()


function RenderCats($CatRef)
{
   $currentparents = explode('.',$CatRef);
   $discard = array_pop($currentparents);
   $parentref = implode('.',$currentparents);

   $strQuery = "SELECT * FROM ".$GLOBALS["scCatTable"]." WHERE catref NOT LIKE '".$CatRef.".%' And catref != '".$CatRef."' ORDER BY catref";
   $cresult = dbRetrieve($strQuery,true,0,0);
   while ($crs = dbFetch($cresult))
   {
      $catparents = explode('.',$crs["catref"]);
      $catlevel = count($catparents) - 1;
      echo '<option value="'.$crs["catref"].'"';
      if ($crs["catref"] == $parentref) { echo ' selected'; }
      echo '>';
      echo str_repeat('-->&nbsp;',$catlevel);
      echo $crs["catname"];
   }
   dbFreeResult($cresult);
} // function RenderCats()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
