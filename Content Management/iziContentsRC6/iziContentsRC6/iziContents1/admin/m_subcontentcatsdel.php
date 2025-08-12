<?php

/***************************************************************************

 m_subcontentcatsdel.php
 ------------------------
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

$GLOBALS["form"] = 'subcontent';
$validaccess = VerifyAdminLogin();


GetSpecialData($_GET["SCID"]);

if ($GLOBALS["candelete"] == False)
{
   Header("Location: ".BuildLink('adminlogin.php'));
}
else
{
   DeleteCat();
   Header("Location: ".BuildLink('m_subcontentcats.php')."&SCID=".$_GET["SCID"]."&page=".$_GET["page"]);
}


function DeleteCat()
{
   global $_GET;

   // Retrieve the cat reference before we delete it so we know its parent if it's a subcategory
   $strQuery="SELECT * FROM ".$GLOBALS["scCatTable"]." WHERE catid='".$_GET["CatID"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);
   $CatRef = $rs["catref"];
   dbFreeResult($result);
   $catparents = explode('.',$CatRef);
   $discard = array_pop($catparents);
   $NewCatRef = implode('.',$catparents);


   // Delete the required record
   $strQuery = "DELETE FROM ".$GLOBALS["scCatTable"]." WHERE catid='".$_GET["CatID"]."'";
   $result = dbExecute($strQuery,true);


   // Move all child categories of the 'now deceased' parent up a level to the new parent tree
   $strQuery = "SELECT * FROM ".$GLOBALS["scCatTable"]." where catref LIKE '".$CatRef.".%'";
   $cresult = dbRetrieve($strQuery,true,0,0);
   while ($crs = dbFetch($cresult))
   {
      $newcatref = $NewCatRef.substr($crs["catref"],strlen($CatRef));
      $strQuery = "UPDATE ".$GLOBALS["scCatTable"]." SET catref='".$newcatref."' WHERE catid='".$crs["catid"]."'";
      $uresult = dbExecute($strQuery,true);
   }
   dbFreeResult($cresult);
   // Update any news articles that might be in this branch of the category hierarchy
   $strQuery = "SELECT ".$_GET["SCID"]."id AS id,catid FROM ".$GLOBALS["scTable"]." where catid='".$CatRef."' or catid LIKE '".$CatRef.".%'";
   $cresult = dbRetrieve($strQuery,true,0,0);
   while ($crs = dbFetch($cresult))
   {
      $newcatref = $NewCatRef.substr($crs["catref"],strlen($CatRef));
      $strQuery = "UPDATE ".$GLOBALS["scTable"]." SET catid='".$newcatref."' WHERE ".$_GET["SCID"]."id='".$crs["id"]."'";
      $uresult = dbExecute($strQuery,true);
   }
   dbFreeResult($cresult);

   dbCommit();
} // function DeleteTag()

?>
