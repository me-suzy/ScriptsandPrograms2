<?php

/***************************************************************************

 m_languages.php
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

//  Base language is English (en). If a file doesn't exist in English, we're not interested in it.
//  Retrieve a list of all English language files in /languages/en
// deprecated with new installation routine
/*
$savedir = getcwd();
chdir($GLOBALS["rootdp"].$GLOBALS["language_home"].'en');
$i = 0;
if ($handle = @opendir('.'))
{
   while ($file = readdir($handle))
   {
      $filename = $file;
      if (is_file($filename))
      {
         $fileparts = pathinfo($filename);
         $file_ext = strtolower($fileparts["extension"]);
         $file_name = strtolower($fileparts["basename"]);
  	 if ($file_ext == 'php')
         {
            $filename=explode('.',$file_name);
            $filename=explode('_',$filename[0]);
            $GLOBALS["mainfiles"][$i]["filename"] = $filename[1];
            $i++;
         }
      }
   }
   closedir($handle);
}
chdir($savedir);
*/

$GLOBALS["form"] = 'languages';
$GLOBALS["validaccess"] = VerifyAdminLogin();


includeLanguageFiles('admin','languages');

if($_GET["release"] == "toggle"){
 toggleLanguage($_GET["languagecode"], $_GET["toggleto"]);
}

force_page_refresh();
frmLanguages();


function frmLanguages()
{
   global $_SERVER, $_GET, $EzAdmin_Style;
    	
   adminheader();
   admintitle(6,$GLOBALS["tFormTitle"]);
   if(isset($GLOBALS["strErrors"])){
   	  formError(6);
   }
   adminbuttons($GLOBALS["tViewLanguage"],$GLOBALS["tAddNew"],$GLOBALS["tEditLanguage"],'$GLOBALS["tToggleLanguage"]');
   $GLOBALS["iBuildFiles"] = lsimagehtmltag($GLOBALS["icon_home"],'backup_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tBuildFiles"],0);
	$iVisible	= lsimagehtmltag($GLOBALS["icon_home"],'green_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tVisible"],0);
	$iHidden	= lsimagehtmltag($GLOBALS["icon_home"],'red_dot.gif',$GLOBALS["gsLanguage"],$GLOBALS["tHidden"],0);

   $strQuery = "SELECT languagecode FROM ".$GLOBALS["eztbLanguages"];
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);
   $lRecCount = dbRowsReturned($result);
   dbFreeResult($result);

   $nCurrentPage = 0;
   if ($_GET["page"] != '') { $nCurrentPage = $_GET["page"]; }
   if ($_GET["sort"] == '') { $_GET["sort"] = 2; }
   $nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
   if ($nCurrentPage >= $nPages) { $nCurrentPage = 0; }
   $lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

   adminHdFt('languages',6,$nCurrentPage,$nPages,'');
   ?>
   <tr class="teaserheadercontent">
   <?php
       adminlistitem(10,$GLOBALS["tEdit"],'c');
       adminlistitem(10,$GLOBALS["tLanguageCode"],'',1);
       adminlistitem(30,$GLOBALS["tLanguage"],'',2);
       adminlistitem(30,$GLOBALS["tCharSet"],'',3);
       adminlistitem(10,$GLOBALS["tDirection"],'',4);
       adminlistitem(10,$GLOBALS["tEnabled"],'c');
   ?>
   </tr>
   <?php

   switch ($_GET["sort"])
   {
      case '1' : $sort = 'enabled DESC,languagecode';
                 break;
      case '2' : $sort = 'enabled DESC,languagename';
                 break;
      case '3' : $sort = 'enabled DESC,charset,languagename';
                 break;
      case '4' : $sort = 'enabled DESC,direction,languagename';
                 break;
      default  : $sort = 'enabled DESC,languagename';
   }
   $strQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." ORDER BY ".$sort;
   $result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
   while ($rs = dbFetch($result))
   {
      ?>
      <tr class="teasercontent">
         <td align="center" valign="top" class="content">
            <?php
            if ($GLOBALS["canedit"] == False)
            {
               if ($GLOBALS["canview"] == False) {
                  echo $GLOBALS["iBlank"];
               } else {
                  $iViewFiles = lsimagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tViewFiles"],0);
                  if ($iViewFiles == '') $iViewFiles = lsimagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tViewFiles"],0);
                   ?>
                  <a href="<?php echo BuildLink('m_langfiles.php'); ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&lang=<?php echo $rs["languagecode"]; ?>"<?php echo BuildLinkMouseOver($GLOBALS["tViewFiles"]); ?>>
                  <?php echo $iViewFiles; ?></a>&nbsp;<?php
               }
            } else {
               $iEditFiles = lsimagehtmltag($GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEditFiles"],0);
                  if ($iEditFiles == '') $iEditFiles = lsimagehtmltag($GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tViewFiles"],0);
               ?>
               <a href="<?php echo BuildLink('m_langfiles.php'); ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>&lang=<?php echo $rs["languagecode"]; ?>"<?php echo BuildLinkMouseOver($GLOBALS["tEditFiles"]); ?>>
               <?php echo $iEditFiles; ?></a>&nbsp;<?php
            }
            admineditcheck('languageform','LanguageCode',$rs["languagecode"],$rs["authorid"]);
            // sasch@izicontents.com -- adding deactivation 
           languagereleasecheck($rs["languagecode"], $rs["enabled"]);
            ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["languagecode"]; ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["languagename"]; ?>
         </td>
         <td valign="top" class="content">
             <?php echo $rs["charset"]; ?>
         </td>
         <td align="center" valign="top" class="content">
             <?php
             if ($rs["direction"] == 'rtl') { echo $GLOBALS["tRtLs"]; }
             else { echo $GLOBALS["tLtRs"]; }
             ?>
         </td>
         <td align="center" valign="top" class="content">
             <?php
             if ($rs["enabled"] == 'Y') { echo $iVisible; }
             else { echo $iHidden; }
             ?>
         </td>
      </tr>
      <?php
   }
   dbFreeResult($result);

   adminHdFt('languages',6,$nCurrentPage,$nPages,'');
   ?>
   </table>
   </body>
   </html>
   <script language="Javascript" type="text/javascript">
	<!-- Begin
	function RelLang(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tToggleLanguage"]; ?>')) {
			location.href='<?php echo BuildLink('m_languages.php'); ?>&' + sParams;
		}
	}
	//  End -->
</script>
   <?php
} // function frmLanguages()


// checks if languages can be released 
function languagereleasecheck($languagecode, $toggle)
{
        global $_GET;
        
        $GLOBALS["iRelease"] = adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["ReleaseIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tReleaseUser"],0,'rel_button.gif');

        if ($GLOBALS["canedit"] == False) { echo $GLOBALS["iBlank"];
        } else {
                ?>
                &nbsp;<a href="javascript:RelLang('languagecode=<?= $languagecode; ?>&toggleto=<?= $toggle ;?>&release=toggle');" <?php echo BuildLinkMouseOver($GLOBALS["tToggleLanguage"]); ?>>
                <?php echo $GLOBALS["iRelease"]; ?></a><?php
        }
} // function modulereleasecheck()

function toggleLanguage($languagecode, $toggleto){
	
	//check if lang not default lang
	if($languagecode == $GLOBALS["gsDefault_language"]){
		$GLOBALS["strErrors"][] = $GLOBALS["defaultLangError"];
	}
	else{
		if($toggleto == 'Y'){
			$sqlString = "UPDATE ".$GLOBALS["eztbLanguages"]." SET enabled='N' WHERE languagecode='".$languagecode."'";
   	
		} else{
			$sqlString = "UPDATE ".$GLOBALS["eztbLanguages"]." SET enabled='Y' WHERE languagecode='".$languagecode."'";
		}
	$result = dbExecute($sqlString,true);
	
	}

}
?>
