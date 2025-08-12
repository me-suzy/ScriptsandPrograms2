<?php

/***************************************************************************

 m_subcontentcats.php
 ---------------------
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
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','subcontent');


GetSpecialData($_GET["SCID"]);

force_page_refresh();
frmCats();


function frmCats()
{
   global $_GET;

   adminheader();
   admintitle(4,$GLOBALS["tFormTitle3"]);
   adminbuttons($GLOBALS["tViewCat"],$GLOBALS["tAddNewCat"],$GLOBALS["tEditCat"],$GLOBALS["tDeleteCat"]);

   $strQuery = "SELECT catid FROM ".$GLOBALS["scCatTable"];
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);
   $lRecCount = dbRowsReturned($result);
   dbFreeResult($result);

   $nCurrentPage = 0;
   if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
   $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
   if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
   $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

   adminHdFt2('subcontentcats',4,$nCurrentPage,$nPages);
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(10,$GLOBALS["tEditDel"],'');
       adminlistitem(65,$GLOBALS["tCategory"],'');
       adminlistitem(15,$GLOBALS["tCatReference"],'');
       adminlistitem(10,$GLOBALS["tHidden"],'c');
       ?>
   </tr>
   <?php

   $strQuery = "SELECT * FROM ".$GLOBALS["scCatTable"]." ORDER BY catref";
   $result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rs = dbFetch($result))
   {
      ?>
      <tr class="teasercontent">
         <td align="center" valign="top" class="content">
             <?php admineditcheck2('subcontentcatsform','SCID',$_GET["SCID"],'CatID',$rs["catid"],0); ?>&nbsp;
	     <?php admindeletecheck('Delcat','CatID',$rs["catid"]); ?>
         </td>
         <td valign="top" class="content">
             <?php
             $catparents = explode('.',$rs["catref"]);
             $catlevel = count($catparents) - 1;
             echo str_repeat('-->&nbsp;',$catlevel);
             echo $rs["catname"];
             ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["catref"]; ?>
         </td>
         <td valign="top" align="center" class="content">
             <?php
             if ($rs["hiddencat"] == '1')
             {
                echo $GLOBALS["tYes"];
             }
             else
             {
                echo $GLOBALS["tNo"];
             }
             ?>
         </td>
      </tr>
      <?php
   }
   dbFreeResult($result);

   adminHdFt2('subcontentcats',4,$nCurrentPage,$nPages);
   frmModuleReturn(4)
   ?>
   </table>
   </body>
   </html>
   <?php
} // function frmTags()


function adminHdFt2($form,$colspan,$nCurrentPage,$nPages)
{
   global $_GET;

   $pLink = BuildLink('m_'.$form.'.php');
   $fLink = BuildLink('m_'.$form.'form.php');
   $linkmod = '&SCID='.$_GET["SCID"];
   $hlink = '<a href="'.$fLink.'&page='.$nCurrentPage.$linkmod.'" title="'.$GLOBALS["tAddNew"].'" '.BuildLinkMouseOver($GLOBALS["tAddNew"]).'>';
   ?>
   <tr class="topmenuback">
       <td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
           <table height="100%" width="100%" cellspacing="0" cellpadding="0">
               <tr><?php
                   if ($GLOBALS["canadd"] === True)
                   {
                      ?><td align="<?php echo $GLOBALS["left"]; ?>" valign="bottom"><?php
                      echo displaybutton('addbutton',$form,$GLOBALS["tAddNewCat"].'...',$hlink);
                      ?></td><?php
                   }
                   ?>
                   <td align="<?php echo $GLOBALS["right"]; ?>" valign="bottom"><?php
                       if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=0" <?php echo BuildLinkMouseOver($GLOBALS["tFirstPage"]); ?>><?php echo $GLOBALS["iFirst"]; ?></a><?php } else { echo $GLOBALS["iFirst"]; }
                       echo '&nbsp;';
                       if ($nCurrentPage != 0) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tPrevPage"]); ?>><?php echo $GLOBALS["iPrev"]; ?></a><?php } else { echo $GLOBALS["iPrev"]; }
                       $nCPage = $nCurrentPage + 1;
                       echo '&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$nCPage.' '.$GLOBALS["tOf"].' '.$nPages.'&nbsp;&nbsp;';
                       if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nCurrentPage + 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tNextPage"]); ?>><?php echo $GLOBALS["iNext"]; ?></a><?php } else { echo $GLOBALS["iNext"]; }
                       echo '&nbsp;';
                       if ($nCurrentPage + 1 != $nPages) { ?><a href="<?php echo $pLink.$linkmod; ?>&page=<?php echo $nPages - 1; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tLastPage"]); ?>><?php echo $GLOBALS["iLast"]; ?></a><?php } else { echo $GLOBALS["iLast"]; } ?>
                   </td>
               </tr>
           </table>
       </td>
   </tr><?php
} // function adminHdFt2()


function frmModuleReturn($colspan)
{
   ?>
   <tr class="teasercontent">
       <td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
           <a href="<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["admin_home"].'m_subcontent.php'); ?>" <?php echo BuildLinkMouseOver($GLOBALS["tRet_SubContent"]); ?>><?php echo $GLOBALS["tRet_SubContent"]; ?></a>
       </td>
   </tr>
   <?php
} // function frmModuleReturn()


?>
<script language="Javascript" type="text/javascript">
    <!-- Begin
    function Delcat(sParams) {
       if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
          location.href='<?php echo BuildLink('m_subcontentcatsdel.php'); ?>&SCID=<?php echo $_GET["SCID"]; ?>&' + sParams;
       }
    }
    //  End -->
</script>
