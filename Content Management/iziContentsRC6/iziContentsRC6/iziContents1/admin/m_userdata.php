<?php

/***************************************************************************

 m_userdata.php
 ---------------
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

$GLOBALS["form"] = 'userdata';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','userdata');


force_page_refresh();
frmUserdata();


function frmUserdata()
{
   global $_GET;

   adminheader();
   admintitle(4,$GLOBALS["tFormTitle"]);
   adminbuttons('','',$GLOBALS["tToggleUserdata"],'');
   $GLOBALS["iToggle"] = lsimagehtmltag($GLOBALS["icon_home"],'rel_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tToggleUserdata"],0);

   $lRecCount = 0;
   $strQuery = "SELECT * FROM ".$GLOBALS["eztbUserdata"];
   $result = dbRetrieve($strQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      $userdatavar     = $rs["userdatavar"];
      $userdatatype    = $rs["userdatatype"];
      $userdatavalue   = $rs["userdatavalue"];
      if (($userdatavar != '') && ($userdatatype != '')) {
         switch ($userdatatype) {
            case '!='  : if ($GLOBALS[$userdatavar] != $userdatavalue) { $lRecCount++; }
                         break;
            case '=='  :
            default    : if ($GLOBALS[$userdatavar] == $userdatavalue) { $lRecCount++; }
                         break;
         }
      } else { $lRecCount++; }
   }
   dbFreeResult($result);

   $nCurrentPage = 0;
   if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
   if ($_GET["sort"] == '') { $_GET["sort"] = 1; }
   $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
   if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
   $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

   adminHdFt('userdata',4,$nCurrentPage,$nPages,'');
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(10,$GLOBALS["tEdit"],'c');
       adminlistitem(70,$GLOBALS["tUserdataname"],'');
       adminlistitem(15,$GLOBALS["tEnabled"],'c');
       adminlistitem(5,'&nbsp;','');
       ?>
   </tr>
   <?php

   $count = 0;
   $pcount = 0;
   $strQuery = "SELECT * FROM ".$GLOBALS["eztbUserdata"]." ORDER BY userdataorderid";
   $result = dbRetrieve($strQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      $count++;
      $userdatavar     = $rs["userdatavar"];
      $userdatatype    = $rs["userdatatype"];
      $userdatavalue   = $rs["userdatavalue"];
      $userdatadisplay = True;
      if (($userdatavar != '') && ($userdatatype != '')) {
         $userdatadisplay = False;
         switch ($userdatatype) {
            case '!='  : if ($GLOBALS[$userdatavar] != $userdatavalue) { $userdatadisplay = True; }
                         break;
            case '=='  :
            default    : if ($GLOBALS[$userdatavar] == $userdatavalue) { $userdatadisplay = True; }
                         break;
         }
      }
      if (($userdatadisplay) && ($count > $lStartRec) && ($pcount < $GLOBALS["RECORDS_PER_PAGE"])) {
         $pcount++;
         ?>
         <tr class="teasercontent">
            <td align="center" valign="top" class="content">
                <?php
                  $fLink = BuildLink('m_userdatatoggle.php').'&UserdataName='.$rs["userdataname"].'&page='.$_GET["page"];
                  if ($GLOBALS["canedit"] == True) {
                     ?>
                     <a href="<?php echo $fLink; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tToggleUserdata"]); ?>>
                     <?php echo $GLOBALS["iToggle"]; ?></a><?php
                  } else {
                     // No privileges
                     echo $GLOBALS["iBlank"];
                  }
                  ?>
            </td>
            <td valign="top" class="content">
                <?php echo $GLOBALS["td".$rs["userdataname"]]; ?>
            </td>
            <td valign="top" align="center" class="content">
                <?php
                if ($rs["userdataenabled"] == '1') { echo $GLOBALS["tYes"];
                } else { echo $GLOBALS["tNo"]; }
                ?>
            </td>
            <td valign="top" class="content">
                <?php adminmovecheck('up','UserdataMove','UserdataName',$rs["userdataname"]); ?>
                <?php adminmovecheck('down','UserdataMove','UserdataName',$rs["userdataname"]); ?>
            </td>
         </tr>
         <?php
      }
   }
   dbFreeResult($result);

   adminHdFt('userdata',4,$nCurrentPage,$nPages,'');
   ?>
   </table>
   </body>
   </html>
   <?php
} // function frmUserdata()

?>
<script language="Javascript" type="text/javascript">
    <!-- Begin
    function UserdataMove(sParams) {
       location.href='<?php echo BuildLink('m_userdatamove.php'); ?>&' + sParams;
    }
    //  End -->
</script>
