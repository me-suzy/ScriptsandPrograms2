<?php

/***************************************************************************

 m_imageformats.php
 -------------------
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

$GLOBALS["form"] = 'imageformats';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','imageformats');


force_page_refresh();
frmImageformats();


function frmImageformats()
{
   global $_GET;

   adminheader();
   admintitle(5,$GLOBALS["tFormTitle"]);
   adminbuttons($GLOBALS["tViewIFTemplate"],$GLOBALS["tAddNewIFTemplate"],$GLOBALS["tEditIFTemplate"],$GLOBALS["tDeleteIFTemplate"]);

   $strQuery = "SELECT imageformatid FROM ".$GLOBALS["eztbImageformattemplates"];
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

   adminHdFt('imageformats',5,$nCurrentPage,$nPages,'');
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(10,$GLOBALS["tEditDel"],'c');
       adminlistitem(55,$GLOBALS["tName"],'',1);
       adminlistitem(10,$GLOBALS["tBorder"],'r',2);
       adminlistitem(10,$GLOBALS["tAlignment"],'',3);
       adminlistitem(15,$GLOBALS["tColour"],'',4);
       ?>
   </tr>
   <?php

   switch ($_GET["sort"])
   {
      case '1' : $sort = 'imageformatname';
                 break;
      case '2' : $sort = 'ifborder,imageformatname';
                 break;
      case '3' : $sort = 'ifalign,imageformatname';
                 break;
      case '4' : $sort = 'ifbgcolor,imageformatname';
                 break;
      default  : $sort = 'imageformatname';
   }
   $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbImageformattemplates"]." ORDER BY ".$sort;
   $result = dbRetrieve($sqlQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rs = dbFetch($result))
   {
      ?>
      <tr class="teasercontent">
         <td align="center" valign="top" class="content">
             <?php admineditcheck('imageformatsform','ImageformatID',$rs["imageformatid"],$rs["authorid"]); ?>
             <?php admindeletecheck('Delimageformat','ImageformatID',$rs["imageformatid"]); ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["imageformatname"]; ?>
         </td>
         <td valign="top" align="center" class="content">
             <?php echo $rs["ifborder"]; ?>
         </td>
         <td valign="top" class="content">
             <?php if ($rs["ifalign"] == 'R') { echo $GLOBALS["tRight"]; } else { if ($rs["ifalign"] == 'C') { echo $GLOBALS["tCentre"]; } else { echo $GLOBALS["tLeft"]; } } ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["ifbgcolor"]; ?>
         </td>
      </tr>
      <?php
   }
   dbFreeResult($result);

   adminHdFt('imageformats',5,$nCurrentPage,$nPages,'');
   ?>
   </table>
   </body>
   </html>
   <?php
} // function frmImageformats()

?>

<script language="Javascript" type="text/javascript">
    <!-- Begin
    function Delimageformat(sParams) {
       if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
          location.href='<?php echo BuildLink('m_imageformatdel.php'); ?>&' + sParams;
       }
    }
    //  End -->
</script>
