<?php

/***************************************************************************

 m_tags.php
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

include_once ("rootdatapath.php");

$GLOBALS["form"] = 'tags';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','tags');


force_page_refresh();
frmTags();


function frmTags()
{
   global $_GET;

   adminheader();
   admintitle(4,$GLOBALS["tFormTitle"]);
   adminbuttons($GLOBALS["tViewTag"],$GLOBALS["tAddNewTag"],$GLOBALS["tEditTag"],$GLOBALS["tDeleteTag"]);

   $strQuery = "SELECT tagid FROM ".$GLOBALS["eztbTags"];
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);
   $lRecCount = dbRowsReturned($result);
   dbFreeResult($result);

   $nCurrentPage = 0;
   if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
   if ($_GET["sort"] == '') { $_GET["sort"] = 1; }
   $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
   if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
   $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

   adminHdFt('tags',4,$nCurrentPage,$nPages,'');
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(10,$GLOBALS["tEditDel"],'c');
       adminlistitem(15,$GLOBALS["tCategory"],'',1);
       adminlistitem(15,$GLOBALS["tTag"],'',2);
       adminlistitem(60,$GLOBALS["tTranslation"],'',3);
       ?>
   </tr>
   <?php

   switch ($_GET["sort"])
   {
      case '1' : $sort = 'cat ASC,candelete DESC,canedit DESC,tag';
                 break;
      case '2' : $sort = 'tag';
                 break;
      case '3' : $sort = 'translation';
                 break;
      default  : $sort = 'cat ASC,candelete DESC,canedit DESC,tag';
   }
   $strQuery = "SELECT * FROM ".$GLOBALS["eztbTags"]." ORDER BY ".$sort;
   $result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rs = dbFetch($result))
   {
      ?>
      <tr class="teasercontent">
         <td align="center" valign="top" class="content">
             <?php
             if ($rs["canedit"] == 'Y')
             {
                admineditcheck('tagsform','TagID',$rs["tagid"],$rs["authorid"]);
             }
             else
             {
                echo $GLOBALS["iBlank"];
             }
	     if ($rs["candelete"] == 'Y')
	     {
                admindeletecheck('Deltag','TagID',$rs["tagid"]);
             }
             else
             {
                echo $GLOBALS["iBlank"];
             }
             ?>
         </td>
         <td valign="top" class="content">
             <?php echo GetCat($rs["cat"]); ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["tag"]; ?>
         </td>
         <td valign="top" class="content">
             <?php echo htmlspecialchars($rs["translation"]); ?>
         </td>
      </tr>
      <?php
   }
   dbFreeResult($result);

   adminHdFt('tags',4,$nCurrentPage,$nPages,'');
   ?>
   </table>
   </body>
   </html>
   <?php
} // function frmTags()


function GetCat($catcode)
{
   $strQuery = "SELECT catdesc FROM ".$GLOBALS["eztbTagCategories"]." WHERE catname='".$catcode."' AND language='".$GLOBALS["gsLanguage"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rsc = dbFetch($result);
   $catname = $rsc["catdesc"];
   return $catname;
}

?>
<script language="Javascript" type="text/javascript">
    <!-- Begin
    function Deltag(sParams) {
       if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
          location.href='<?php echo BuildLink('m_tagsdel.php'); ?>&' + sParams;
       }
    }
    //  End -->
</script>
