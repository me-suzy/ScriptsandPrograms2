<?php

/***************************************************************************

 m_tusergroups.php
 ------------------
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

$GLOBALS["form"] = 'tusergroups';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','usergroups');


force_page_refresh();
frmUsergroups();


function frmUsergroups()
{
   global $_GET;

   adminheader();
   admintitle(2,$GLOBALS["tFormTitle"]);
   adminbuttons($GLOBALS["tViewPrivilege"],'',$GLOBALS["tEditPrivilege"],'');

   $strQuery = "SELECT usergroupname FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);
   $lRecCount = dbRowsReturned($result);
   dbFreeResult($result);

   $nCurrentPage = 0;
   if($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
   $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
   if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
   $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

   adminHdFt('tusergroups',2,$nCurrentPage,$nPages,'');
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(10,$GLOBALS["tEdit"],'');
       adminlistitem(90,$GLOBALS["tUsergroupname"],'');
       ?>
   </tr>
   <?php

   $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' ORDER BY usergroupname";
   $result = dbRetrieve($sqlQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rs = dbFetch($result)) { ?>
   <tr class="teasercontent">
      <td align="center" valign="top" class="content">
         <?php admineditcheck('tusergroupsform','UsergroupName',$rs["usergroupname"],$rs["authorid"]); ?>
      </td>
      <td valign="top" class="content"><?php echo $rs["usergroupdesc"]; ?></td>
   </tr><?php
   }
   dbFreeResult($result);

   adminHdFt('tusergroups',2,$nCurrentPage,$nPages,'');
   ?>
   </table>
   </body>
   </html>
   <?php
} // function frmUsergroups()

?>
