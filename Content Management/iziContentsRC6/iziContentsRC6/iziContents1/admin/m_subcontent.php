<?php

/***************************************************************************

 m_subcontent.php
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

$GLOBALS["form"] = 'subcontent';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','subcontent');

force_page_refresh();
frmSpecialContents();


function frmSpecialContents()
{
   global $_GET, $EzAdmin_Style;

   adminheader();
   admintitle(6,$GLOBALS["tFormTitle"]);
   adminbuttons('',$GLOBALS["tAddNew"],'','');

   $strQuery = "SELECT scid FROM ".$GLOBALS["eztbSpecialcontents"];
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);
   $lRecCount = dbRowsReturned($result);
   dbFreeResult($result);

   $nCurrentPage = 0;
   if ($_GET["page"] != '') { $nCurrentPage = $_GET["page"]; }
   $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
   if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
   $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

   adminHdFt('subcontent',6,$nCurrentPage,$nPages,'');
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(18,$GLOBALS["tEdit"],'');
       adminlistitem(30,$GLOBALS["tModuleTitle"],'');
       adminlistitem(16,$GLOBALS["tModuleName"],'');
       adminlistitem(11,$GLOBALS["tAnonPosting"],'');
       adminlistitem(10,$GLOBALS["tValReq"],'');
       adminlistitem(13,$GLOBALS["tTablename"],'');
       ?>
   </tr>
   <?php

   $strQuery = "SELECT * FROM ".$GLOBALS["eztbSpecialcontents"]." ORDER BY scname";
   $result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rsContent = dbFetch($result))
   {
      ?>
      <tr class="teasercontent">
          <td align="center" valign="top" class="content">
              <?php
              $iEditCfg = lsimagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["EditIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEdit"].' '.$rsContent["scname"].' '.$GLOBALS["tConfig"],0);
				if ($iEditCfg == '') $iEditCfg = lsimagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["EditIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEdit"].' '.$rsContent["scname"].' '.$GLOBALS["tConfig"],0);
              $iEdit    = lsimagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEdit"].' '.$rsContent["scname"],0);
				if ($iEdit == '') $iEdit = lsimagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEdit"].' '.$rsContent["scname"],0);
             $iEditCat = lsimagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["CatIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEdit"].' '.$rsContent["scname"].' '.$GLOBALS["tCategories"],0);
				if ($iEditCat == '') $iEditCat = lsimagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["CatIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEdit"].' '.$rsContent["scname"].' '.$GLOBALS["tCategories"],0);
				 ?>
              <a href="<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["modules_home"].$rsContent["scname"].'/m_'.$rsContent["scname"].'.php'); ?>"<?php echo BuildLinkMouseOver($GLOBALS["tEdit"].' '.$rsContent["scname"]); ?>>
              <?= $iEdit; ?></a>&nbsp;
              <a href="<?php echo BuildLink('m_subcontentform.php'); ?>&SCID=<?= $rsContent["scid"];?>&scdb=<?= $rsContent["scdb"]?>"<?php echo BuildLinkMouseOver($GLOBALS["tEdit"].' '.$rsContent["scname"].' '.$GLOBALS["tConfig"]); ?>>
              <?= $iEditCfg; ?></a>&nbsp;
              <?// sasch@izicontents.com -- adding deactivation 
              modulereleasecheck($rsContent["scname"]); 
              ?>&nbsp;             
              <?php
              if ($rsContent["scusecategories"] == 'Y')
              {
                 ?>
                 <a href="<?php echo BuildLink('m_subcontentcats.php'); ?>&SCID=<?php echo $rsContent["scdb"]; ?>"<?php echo BuildLinkMouseOver($GLOBALS["tEdit"].' '.$rsContent["scname"].' '.$GLOBALS["tCategories"]); ?>>
                 <?php echo $iEditCat; ?></a>&nbsp;
                 <?php
              }
              else { echo $GLOBALS["iBlank"]; }
              ?>
          </td>
          <td valign="top" class="content">
              <?php echo $rsContent["sctitle"]; ?>
          </td>
          <td valign="top" class="content">
              <?php echo $rsContent["scname"]; ?>
          </td>
          <td valign="top" align="center">
              <?php
              if (($rsContent["stextdisplay"] == 'Y') || ($rsContent["sgraphicdisplay"] == 'Y'))
              {
                 $regonly = $rsContent["screg"];
                 if ($regonly == 'Y') { echo $GLOBALS["tNo"]; }
                 else { echo $GLOBALS["tYes"]; }
              }
              else { echo '--'; }
              ?>
          </td>
          <td align="center" valign="top" class="content">
              <?php
              if (($rsContent["stextdisplay"] == 'Y') || ($rsContent["sgraphicdisplay"] == 'Y'))
              {
	         $validate = $rsContent["scvalid"];
                 if ($validate == 'Y') { echo $GLOBALS["tYes"]; }
                 else { echo $GLOBALS["tNo"]; }
              }
              else { echo '--'; }
              ?>
          </td>
          <td valign="top" class="content">
              <?php echo $rsContent["scdb"]; ?>
          </td>
      </tr>
      <?php
   }
   dbFreeResult($result);

   adminHdFt('subcontent',6,$nCurrentPage,$nPages,'');
   ?>
   </table>
   </form>
   </body>
   </html>
   <script language="Javascript" type="text/javascript">
	<!-- Begin
	function RelModule(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmRelease"]; ?>')) {
			location.href='<?php echo BuildLink('m_subcontentdelete.php'); ?>&' + sParams;
		}
	}
	//  End -->
</script>
 
   
   <?php
}

// checks if modules can be released and sets path
function modulereleasecheck($module)
{
        global $_GET;
        
        $GLOBALS["iRelease"] = adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["ReleaseIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tReleaseUser"],0,'rel_button.gif');

        if ($GLOBALS["canedit"] == False) { echo $GLOBALS["iBlank"];
        } else {
                ?>
                &nbsp;<a href="javascript:RelModule('scname=<?php echo $module; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tReleaseModule"]); ?>>
                <?php echo $GLOBALS["iRelease"]; ?></a><?php
        }
} // function modulereleasecheck()

?>
