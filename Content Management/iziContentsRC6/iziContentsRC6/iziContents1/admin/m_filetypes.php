<?php

/***************************************************************************

 m_filetypes.php
 ----------------
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

$GLOBALS["form"] = 'filetypes';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','filetypes');


force_page_refresh();
frmFiletypes();


function frmFiletypes()
{
   global $_GET;

   adminheader();
   admintitle(5,$GLOBALS["tFormTitle"]);
   adminbuttons($GLOBALS["tViewFiletype"],$GLOBALS["tAddNewFiletype"],$GLOBALS["tEditFiletype"],$GLOBALS["tDeleteFiletype"]);

   $strQuery = "SELECT filetypeid FROM ".$GLOBALS["eztbFiletypes"];
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

   adminHdFt('filetypes',5,$nCurrentPage,$nPages,'');
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(10,$GLOBALS["tEditDel"],'c');
       adminlistitem(12,$GLOBALS["tFileCategory"],'',1);
       adminlistitem(12,$GLOBALS["tFileType"],'',2);
       adminlistitem(50,$GLOBALS["tMIMEType"],'',3);
       adminlistitem(16,$GLOBALS["tFileIcon"],'c');
       ?>
   </tr>
   <?php

   switch ($_GET["sort"])
   {
      case '1' : $sort = 'filecat,filetype';
                 break;
      case '2' : $sort = 'filetype';
                 break;
      case '3' : $sort = 'mimetype';
                 break;
      default  : $sort = 'filecat,filetype';
   }
   $strQuery = "SELECT * FROM ".$GLOBALS["eztbFiletypes"]." WHERE filecat != 'Modules' and filecat != 'Languages' ORDER BY ".$sort;
   $result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rs = dbFetch($result))
   {
   	 ?>
      <tr class="teasercontent">
         <td align="center" valign="top" class="content">
             <?php admineditcheck('filetypesform','FiletypeID',$rs["filetypeid"],$rs["authorid"]); ?>
             <?php admindeletecheck('Delfiletype','FiletypeID',$rs["filetypeid"]); ?>
         </td>
         <td valign="top" class="content">
             <?php
             $cattype = 'tFileCat'.$rs["filecat"];
             echo $GLOBALS[$cattype];
             ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["filetype"]; ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["mimetype"]; ?>
         </td>
         <td valign="top" align="center" class="content">
             <?php
             if ($rs["fileicon"] != '')
             {
                echo '<img src="'.$GLOBALS["rootdp"].$GLOBALS["image_home"].$rs["fileicon"].'">';
             }
             ?>
         </td>
      </tr>
      <?php
   }
   dbFreeResult($result);

   adminHdFt('filetypes',5,$nCurrentPage,$nPages,'');
   ?>
   </table>
   </body>
   </html>
   <?php
} // function frmFiletypes()


?>
<script language="Javascript" type="text/javascript">
    <!-- Begin
    function Delfiletype(sParams) {
       if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
          location.href='<?php echo BuildLink('m_filetypedel.php'); ?>&' + sParams;
       }
    }
    //  End -->
</script>
