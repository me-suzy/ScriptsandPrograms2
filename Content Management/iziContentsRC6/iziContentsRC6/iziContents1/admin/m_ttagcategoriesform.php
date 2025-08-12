<?php

/***************************************************************************

 m_ttagcategoriesform.php
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
$GLOBALS["form"] = 'ttagcategories';
$validaccess = VerifyAdminLogin3("CatName");

includeLanguageFiles('admin','tagcategories');


if ($_GET["CatName"] != '') {
   $_POST["CatName"] = $_GET["CatName"];
   $_POST["page"] = $_GET["page"];
}
RenderLanguages();
GetGlobalData($_POST["CatName"]);
$convertcharsets = false;
if ($GLOBALS["groups"]) reset($GLOBALS["groups"]);
while (list($i,$val) = each($GLOBALS["groups"])) {
   if ($i == 0) { $cs = $GLOBALS["groups"][$i]["charset"]; }
   if ($GLOBALS["groups"][$i]["charset"]  != $cs) { $convertcharsets = true; }
}
if (!(function_exists('mb_convert_encoding'))) { $convertcharsets = false; }


$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      UpdateTagcategories($convertcharsets);
      Header("Location: ".BuildLink('m_ttagcategories.php')."&page=".$_POST["page"]);
   }
}
frmTagCategoryForm($convertcharsets);


function frmTagCategoryForm($convertcharsets)
{
   global $_POST;

   if ($convertcharsets) { adminformheader('UTF-8'); }
   else { adminformheader(); }
   adminformopen($GLOBALS["groups"][0]["languagecode"]);
   adminformtitle(2,charsetText($GLOBALS["tFormTitle"],$convertcharsets,$GLOBALS["gsCharset"]));
   if ($GLOBALS["strErrors"] != '') { echo '<tr bgcolor=#900000><td colspan=2><b>'.charsetText($GLOBALS["strErrors"],$convertcharsets,$GLOBALS["gsCharset"]).'</b></td></tr>'; }

   if ($GLOBALS["groups"]) reset($GLOBALS["groups"]);
   while (list($i,$val) = each($GLOBALS["groups"])) {
      $catdesc = charsetText($GLOBALS["groups"][$i]["catdesc"],$convertcharsets,$GLOBALS["groups"][$i]["charset"]);
      ?>
      <tr class="tablecontent">
          <?php uFieldHeading($GLOBALS["groups"][$i]["languagename"],$convertcharsets,$GLOBALS["groups"][$i]["charset"]); ?>
          <td valign="top" class="content">
              <input type="text" name="<?php echo $GLOBALS["groups"][$i]["languagecode"]; ?>" size="48" value="<?php echo $catdesc; ?>" maxlength="48"<?php echo $GLOBALS["fieldstatus"]; ?>>
          </td>
      </tr>
      <?php
   }

   adminformsavebar(2,'m_ttagcategories.php',$convertcharsets);
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(2);
      ?><input type="hidden" name="CatName" value="<?php echo $_POST["CatName"]; ?>"><?php
   }
   adminformclose();
} // function frmTagCategoryForm()


function UpdateTagcategories($convertcharsets)
{
   global $_POST;

   reset($GLOBALS["groups"]);
   while (list($i,$val) = each($GLOBALS["groups"])) {
      $languagecode = $GLOBALS["groups"][$i]["languagecode"];
      $sCatDesc = dbString(UTF8Text($_POST[$languagecode],$convertcharsets,$GLOBALS["groups"][$i]["charset"]));

      $strQuery = "UPDATE ".$GLOBALS["eztbTagCategories"]." SET catdesc='".$sCatDesc."' WHERE catname='".$_POST["CatName"]."' AND language='".$languagecode."'";
      $result = dbExecute($strQuery,true);
   }
   dbCommit();
} // function UpdateTagcategories()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   reset($GLOBALS["groups"]);
   while (list($i,$val) = each($GLOBALS["groups"])) {
      if (!(isset($GLOBALS["strErrors"]))) {
         $languagecode = $GLOBALS["groups"][$i]["languagecode"];
         if ($_POST[$languagecode] == "") {
            $GLOBALS["strErrors"][] = $GLOBALS["eNoTagcategoryDesc"];
         }
      }
   }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function GetGlobalData($CatName)
{
   $strQuery = "SELECT * FROM ".$GLOBALS["eztbTagCategories"]." WHERE catname='".$CatName."'";
   $result = dbRetrieve($strQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      reset($GLOBALS["groups"]);
      while (list($i,$val) = each($GLOBALS["groups"])) {
         if ($GLOBALS["groups"][$i]["languagecode"] == $rs["language"]) {
            $GLOBALS["groups"][$i]["catdesc"] = $rs["catdesc"];
         }
      }
   }
   dbFreeResult($result);
} // function GetGlobalData()


function RenderLanguages()
{
   $strQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE enabled='Y' ORDER BY languagename";
   $result = dbRetrieve($strQuery,true,0,0);
   $i = 0;
   while ($rs = dbFetch($result)) {
      $GLOBALS["groups"][$i]["languagecode"] = $rs["languagecode"];
      $GLOBALS["groups"][$i]["languagename"] = $rs["languagename"];
      $GLOBALS["groups"][$i]["charset"] = $rs["charset"];
      $GLOBALS["groups"][$i]["catdesc"] = '';
      $i++;
   }
   dbFreeResult($result);
} // function RenderLanguages()


function uFieldHeading($field,$convertcharset,$fromcharset)
{
   $displayfield = charsetText($field,$convertcharset,$fromcharset);
   ?>
   <td valign="top" class="content">
       <b><?php echo $displayfield; ?>:</b>
   </td>
   <?php
} // function uFieldHeading()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
