<?php

/***************************************************************************

 m_banners.php
 --------------
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

$GLOBALS["form"] = 'banners';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','banners');


force_page_refresh();
frmBanners();


function frmBanners()
{
   global $_GET;

   adminheader();
   admintitle(6,$GLOBALS["tFormTitle"]);
   adminbuttons($GLOBALS["tViewBanner"],$GLOBALS["tAddNewBanner"],$GLOBALS["tEditBanner"],$GLOBALS["tDeleteBanner"]);

   $strQuery = "SELECT bannerid FROM ".$GLOBALS["eztbBanners"];
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

   adminHdFt('banners',6,$nCurrentPage,$nPages,'');
   ?><tr class="teaserheadercontent"><?php
     adminlistitem(10,$GLOBALS["tEditDel"],'c');
     adminlistitem(37,$GLOBALS["tURL"],'',1);
     adminlistitem(15,$GLOBALS["tImpressions"],'r',2);
     adminlistitem(15,$GLOBALS["tClicks"],'r',3);
     adminlistitem(12,$GLOBALS["tEnabled"],'c');
     adminlistitem(12,$GLOBALS["tActive"],'c');
   ?></tr><?php

   $timenow   = time();
   $isodate   = strftime("%Y-%m-%d",$timenow);
   $iRed      = lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tExpired"],0);
   $iAmber    = lsimagehtmltag($GLOBALS["icon_home"],'orange_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tPending"],0);
   $iGreen    = lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tActive"],0);
   $iEnabled  = lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tEnabled"],0);
   $iDisabled = lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tDisabled"],0);

   switch ($_GET["sort"])
   {
      case '1' : $sort = 'bannerurl';
                 break;
      case '2' : $sort = 'impressions DESC';
                 break;
      case '3' : $sort = 'clicks DESC';
                 break;
      default  : $sort = 'impressions DESC';
   }
   $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbBanners"]." ORDER BY ".$sort;
   $result = dbRetrieve($sqlQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rs = dbFetch($result)) {
      ?>
      <tr class="teasercontent">
         <td align="center" valign="top" class="content">
            <?php admineditcheck('bannersform','BannerID',$rs["bannerid"],$rs["authorid"]); ?>
            <?php admindeletecheck('Delbanner','BannerID',$rs["bannerid"]); ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["bannerurl"]; ?>
         </td>
         <td valign="top" align="<?php echo $GLOBALS["right"]; ?>" class="content">
             <?php echo $rs["impressions"]; ?>
         </td>
         <td valign="top" align="<?php echo $GLOBALS["right"]; ?>" class="content">
             <?php echo $rs["clicks"]; ?>
         </td>
         <td valign="top" align="center" class="content">
             <?php
             if ($rs["banneractive"] == 'Y') { echo $iEnabled; }
             else { echo $iDisabled; }
             ?>
         </td>
         <td valign="top" align="center" class="content">
             <?php
             if (substr($rs["publishdate"], 0, 10) > $isodate) { echo $iAmber; }
             elseif (substr($rs["expiredate"], 0, 10) <= $isodate) { echo $iRed; }
             else { echo $iGreen; }
             ?>
         </td>
      </tr><?php
   }
   dbFreeResult($result);

   adminHdFt('banners',6,$nCurrentPage,$nPages,'');
   ?>
   </table>
   </body>
   </html>
   <?php
}

?>
<script language="Javascript" type="text/javascript">
    <!-- Begin
    function Delbanner(sParams)
    {
       if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>'))
       {
          location.href='<?php echo BuildLink('m_bannerdel.php'); ?>&' + sParams;
       }
    }
    //  End -->
</script>
