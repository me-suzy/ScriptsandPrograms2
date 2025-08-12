<?php

/***************************************************************************

 m_poll.php
 -----------
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

$GLOBALS["ModuleName"] = 'poll';
include("moduleref.php");

$GLOBALS["rootdp"] = '../../';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

include ($GLOBALS["rootdp"]."include/access.php");

$GLOBALS["form"] = 'subcontent';
$GLOBALS["validaccess"] = VerifyAdminLogin();


include ($GLOBALS["rootdp"]."include/settings.php");
include ($GLOBALS["rootdp"]."include/functions.php");
include ($GLOBALS["rootdp"].$GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include_languagefile ($GLOBALS["modules_home"].$GLOBALS["ModuleRef"].'/',$GLOBALS["gsLanguage"],'lang_poll.php');
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminbutton.php");
include ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");


GetSpecialData($GLOBALS["ModuleRef"]);

frmPoll();


function frmPoll()
{
   global $_GET;

   adminheader();
   admintitle(7,$GLOBALS["tFormTitle"]);

   // Generate image tags for the different images that appear on the page
   adminbuttons($GLOBALS["tViewPoll"],$GLOBALS["tAddNewPoll"],$GLOBALS["tEditPoll"],$GLOBALS["tDeletePoll"]);
   $GLOBALS["iRelease"] = lsimagehtmltag($GLOBALS["icon_home"],'rel_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tReleasePoll"],0);
   $GLOBALS["iOptions"] = lsimagehtmltag($GLOBALS["icon_home"],'cat_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tEditOptions"],0);

   $strQuery = "SELECT pollid FROM ".$GLOBALS["scTable"];
   $result = dbRetrieve($strQuery,true,0,0);
   $lRecCount = dbRowsReturned($result);
   dbFreeResult($result);

   $nCurrentPage = 0;
   if ($_GET["sort"] == '') { $_GET["sort"] = 5; }
   if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
   $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
   $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

   frmModuleHdFt(7,$nCurrentPage,$nPages);
   ?>
   <tr class="teaserheadercontent">
   <?php
       adminlistitem(12,$GLOBALS["tEditDelRel"],'');
       adminlistitem(32,$GLOBALS["tQuestion"],'',1);
       adminlistitem(19,$GLOBALS["tPostedBy"],'',2);
       adminlistitem(8,$GLOBALS["tPollType"],'');
       adminlistitem(8,$GLOBALS["tPublishDate"],'',3);
       adminlistitem(8,$GLOBALS["tExpiryDate"],'',4);
       adminlistitem(8,$GLOBALS["tStatus"],'',5);
   ?>
   </tr>
   <?php

   switch ($_GET["sort"])
   {
      case '1' : $sort = 'question,publishdate DESC';
                 break;
      case '2' : $sort = 'authorid,publishdate DESC';
                 break;
      case '3' : $sort = 'publishdate DESC';
                 break;
      case '4' : $sort = 'expiredate DESC';
                 break;
      case '5' : $sort = 'activeentry,publishdate DESC,expiredate';
                 break;
      default  : $sort = 'activeentry,publishdate DESC,expiredate';
   }
   $strQuery = "SELECT * FROM ".$GLOBALS["scTable"]." ORDER BY ".$sort;
   $result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rsPoll = dbFetch($result))
   {
      ?>
      <tr class="teasercontent">
          <td align="center" valign="top" class="content">
              <?php admineditcheck('pollform','PollID',$rsPoll["pollid"],$rsPoll["authorid"]); ?>
              <?php admindeletecheck('DelEntry','PollID',$rsPoll["pollid"]); ?>&nbsp;
              <?php adminreleasecheck('RelEntry','PollID',$rsPoll["pollid"]); ?>&nbsp;
              <a href="<?php echo BuildLink('m_polloptions.php'); ?>&PollID=<?php echo $rsPoll["pollid"]; ?>"<?php echo BuildLinkMouseOver($GLOBALS["tEditOptions"]); ?>>
              <?php echo $GLOBALS["iOptions"]; ?></a>
          </td>
          <td valign="top" class="content"><?php echo $rsPoll["question"]; ?></td>
          <td valign="top" class="content"><?php echo lGetAuthorName($rsPoll["authorid"]); ?></td>
          <td valign="top" class="content"><?php if ($rsPoll["polltype"] == 'M') { echo $GLOBALS["tMultiVotes"]; } else { echo $GLOBALS["tSingleVote"]; } ?></td>
          <td valign="top" class="content"><?php echo substr($rsPoll["publishdate"], 0, 10); ?></td>
          <td valign="top" class="content"><?php echo substr($rsPoll["expiredate"], 0, 10); ?></td>
          <td valign="top" class="content"><?php if ($rsPoll["activeentry"] == 1) { echo $GLOBALS["tReleased"]; } else { echo $GLOBALS["tPending"]; } ?></td>
      </tr>
      <?php
   }

   dbFreeResult($result);

   frmModuleHdFt(7,$nCurrentPage,$nPages);
   frmModuleReturn(7);
   ?>
   </table>
   </form>
   </body>
   </html>
   <?php
}

frmModuleJs();

?>
