<?php

/***************************************************************************

 m_privilegesdel.php
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

$GLOBALS["form"] = 'privileges';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["candelete"] == False)
{
   Header("Location: ".BuildLink('adminlogin.php'));
}
else
{
   // Can't delete the administrator group or the default for new registrants
   if (($GLOBALS["gsPrivDefaultGroup"] != 'administrator') && ($GLOBALS["gsPrivDefaultGroup"] != $_GET["UsergroupName"]))
   {
      $GLOBALS["UserList"] = '';
      $err_ret = DeleteUserGroups();
   }
   Header("Location: ".BuildLink('m_privileges.php')."&page=".$_GET["page"]."&errmess=".$err_ret."&errqual=".urlencode($GLOBALS["UserList"]));
}


function DeleteUserGroups()
{
   global $_GET;

   $err_ret = '';

   // We can't delete the administrator group, because this is used as a template
   //    for creating new groups. It also means we always have at least one set of
   //    privileges on the database (though a user can still screw himself up by
   //    editing that).
   // Nor can we delete the default privilege group.
   if (($_GET["UsergroupName"] != $GLOBALS["gsAdminPrivGroup"]) && ($_GET["UsergroupName"] != $GLOBALS["gsPrivDefaultGroup"]))
   {
      // A usergroup shouldn't be deleted while there are still users allocated to that group
      $sqlQuery = "SELECT authorname FROM ".$GLOBALS["eztbAuthors"]." WHERE usergroup='".$_GET["UsergroupName"]."'";
      $tresult = dbRetrieve($sqlQuery,true,0,0);
      $uCount = dbRowsReturned($tresult);
      if ($uCount != 0)
      {
         $err_ret = 'eUsersExist';
         while ($rs = dbFetch($tresult))
         {
            $GLOBALS["UserList"] .= ','.$rs["authorname"];
         }
         dbFreeResult($tresult);
         $GLOBALS["UserList"] = substr($GLOBALS["UserList"],1);
      }
      else
      {
         dbFreeResult($tresult);
         // Remove this usergroup from any restricted access top-menu, menu and sub-menu items that it has been applied to
         $sqlQuery = "SELECT topgroupname,language,usergroups FROM ".$GLOBALS["eztbTopgroups"]." WHERE usergroups LIKE '%,".$_GET["UsergroupName"]."%'";
         $tresult = dbRetrieve($sqlQuery,true,0,0);
         while ($rs = dbFetch($tresult))
         {
            $language     = $rs["language"];
            $topgroupname = $rs["topgroupname"];
            $usergroups   = $rs["usergroups"];
            $usergroups   = str_replace(','.$_GET["UsergroupName"],'',$usergroups);
            $sqlQuery = "UPDATE ".$GLOBALS["eztbTopgroups"]." SET usergroups='".$usergroups."' WHERE topgroupname='".$topgroupname."' AND language='".$language."'";
            $result = dbExecute($sqlQuery,true);
         }
         dbFreeResult($tresult);
         $sqlQuery = "SELECT groupname,language,usergroups FROM ".$GLOBALS["eztbGroups"]." WHERE usergroups LIKE '%,".$_GET["UsergroupName"]."%'";
         $tresult = dbRetrieve($sqlQuery,true,0,0);
         while ($rs = dbFetch($tresult))
         {
            $language   = $rs["language"];
            $groupname  = $rs["groupname"];
            $usergroups = $rs["usergroups"];
            $usergroups = str_replace(','.$_GET["UsergroupName"],'',$usergroups);
            $sqlQuery = "UPDATE ".$GLOBALS["eztbGroups"]." SET usergroups='".$usergroups."' WHERE groupname='".$groupname."' AND language='".$language."'";
            $result = dbExecute($sqlQuery,true);
         }
         dbFreeResult($tresult);
         $sqlQuery = "SELECT subgroupname,language,usergroups FROM ".$GLOBALS["eztbSubgroups"]." WHERE usergroups LIKE '%,".$_GET["UsergroupName"]."%'";
         $tresult = dbRetrieve($sqlQuery,true,0,0);
         while ($rs = dbFetch($tresult))
         {
            $language     = $rs["language"];
            $subgroupname = $rs["subgroupname"];
            $usergroups   = $rs["usergroups"];
            $usergroups   = str_replace(','.$_GET["UsergroupName"],'',$usergroups);
            $sqlQuery = "UPDATE ".$GLOBALS["eztbSubgroups"]." SET usergroups='".$usergroups."' WHERE subgroupname='".$subgroupname."' AND language='".$language."'";
            $result = dbExecute($sqlQuery,true);
         }
         dbFreeResult($tresult);


         // Remove this usergroup from any restricted access special content items that it has been applied to
         $qlQuery = "SELECT scid,usergroups FROM ".$GLOBALS["eztbSpecialcontents"]." WHERE usergroups LIKE '%,".$_GET["UsergroupName"]."%'";
         $tresult = dbRetrieve($sqlQuery,true,0,0);
         while ($rs = dbFetch($tresult))
         {
            $scid       = $rs["scid"];
            $usergroups = $rs["usergroups"];
            $usergroups = str_replace(','.$_GET["UsergroupName"],'',$usergroups);
            $sqlQuery = "UPDATE ".$GLOBALS["eztbSpecialcontents"]." SET usergroups='".$usergroups."' WHERE scid='".$scid."'";
            $result = dbExecute($sqlQuery,true);
         }
         dbFreeResult($tresult);


         // Delete the usergroup translations and the privilege set
         $sqlQuery = "DELETE FROM ".$GLOBALS["eztbUsergroups"]." WHERE usergroupname='".$_GET["UsergroupName"]."'";
         $result = dbExecute($sqlQuery,true);
         $sqlQuery = "DELETE FROM ".$GLOBALS["eztbPrivileges"]." WHERE usergroupname='".$_GET["UsergroupName"]."'";
         $result = dbExecute($sqlQuery,true);

         dbCommit();
      }
   }
   return $err_ret;
} // function DeleteUserGroups()

?>

