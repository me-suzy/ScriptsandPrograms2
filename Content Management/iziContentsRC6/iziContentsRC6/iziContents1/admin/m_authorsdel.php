<?php

/***************************************************************************

 m_authorsdel.php
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

$GLOBALS["form"] = 'authors';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["candelete"] == False)
{
   Header("Location: ".BuildLink('adminlogin.php'));
}
else
{
   DeleteAuthor();
   Header("Location: ".BuildLink('m_authors.php')."&page=".$_GET["page"]."&sort=".$_GET["sort"]."&filtergroupname=".$_GET["filtergroupname"]);
}


function DeleteAuthor()
{
   global $_GET;

   $authorId = GetTransferAuthor();

   if (ValidateDelete($_GET["AuthorID"]))
   {
      // If this author has created any data, we transfer ownership of those entries to admin
      $strQuery = "UPDATE ".$GLOBALS["eztbTopgroups"]." SET authorid='".$authorId."' WHERE authorid='".$_GET["AuthorID"]."'";
      $result = dbExecute($strQuery,true);

      $strQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET authorid='".$authorId."' WHERE authorid='".$_GET["AuthorID"]."'";
      $result = dbExecute($strQuery,true);

      $strQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET authorid='".$authorId."' WHERE authorid='".$_GET["AuthorID"]."'";
      $result = dbExecute($strQuery,true);

      $strQuery = "UPDATE ".$GLOBALS["eztbContents"]." SET authorid='".$authorId."' WHERE authorid='".$_GET["AuthorID"]."'";
      $result = dbExecute($strQuery,true);

      // Only then do we delete the author
      $strQuery = "DELETE FROM ".$GLOBALS["eztbAuthors"]." WHERE authorid='".$_GET["AuthorID"]."'";
      $result = dbExecute($strQuery,true);
      dbCommit();
   }
} // function DeleteAuthor()


function GetTransferAuthor()
{
   $authorId = 1;
   $strQuery = "SELECT authorid,usergroup FROM ".$GLOBALS["eztbAuthors"]." WHERE usergroup='".$GLOBALS["gsAdminPrivGroup"]."'";
   $result = dbRetrieve($strQuery,true,0,1);
   if ($rs = dbFetch($result))
   {
      if ($rs["usergroup"] != $GLOBALS["gsAdminPrivGroup"]) { $authorId = $rs["authorid"]; }
   }
   dbFreeResult($result);
   return $authorId;
} // function sGetGroupName()


function ValidateDelete($authorId)
{
   $valid = True;
   $strQuery = "SELECT usergroup FROM ".$GLOBALS["eztbAuthors"]." WHERE authorid='".$authorId."'";
   $result = dbRetrieve($strQuery,true,0,1);
   if ($rs = dbFetch($result))
   {
      if ($rs["usergroup"] == $GLOBALS["gsAdminPrivGroup"]) { $valid = False; }
   }
   dbFreeResult($result);
   return $valid;
} // function sGetGroupName()


?>
