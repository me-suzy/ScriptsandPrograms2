<?php

/***************************************************************************

 m_langfiles.php
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

$GLOBALS["form"] = 'languages';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','languages');


force_page_refresh();
frmLangFiles();


function frmLangFiles()
{
   global $_SERVER, $_GET, $EzAdmin_Style;

   $strQuery = "SELECT languagename FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$_GET["lang"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);
   $languagename = $rs["languagename"];
   dbFreeResult($result);

   adminheader();
   admintitle(2,$GLOBALS["tFormTitle"]);
   adminbuttons($GLOBALS["tViewFile"],'',$GLOBALS["tEditFile"],'');

   ?>
   <tr class="topmenuback">
      <td colspan="2" align="<?php echo $GLOBALS["left"]; ?>"><b><?php echo $languagename; ?>&nbsp;&nbsp;(<?php echo $_GET["lang"]; ?>)</b></td>
   </tr>

   <?php safeModeWarning(2); ?>


   <?php adminsubheader(2,$GLOBALS["thMainFiles"]); ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(5,$GLOBALS["tEdit"],'c');
       adminlistitem(85,$GLOBALS["tFileName"],'');
       ?>
   </tr>
   <?php

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

   sort($GLOBALS["mainfiles"]);
   while (list($i,$val) = each($GLOBALS["mainfiles"]))
   {
      ?>
      <tr class="teasercontent">
          <td valign="top" align="center" class="content">
              <?php
              chdir($savedir);
              if ($GLOBALS["canedit"] == False)
              {
                 if ($GLOBALS["canedit"] == False)
                 {
                    echo $GLOBALS["iBlank"];
                 }
                 else
                 {
                    $iViewFiles = lsimagehtmltag('./'.$GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tViewFile"],0);
                    if ($iViewFiles == '') $iViewFiles = lsimagehtmltag('./'.$GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tViewFile"],0);
                    ?>
                    <a href="<?php echo BuildLink('m_langfileform.php'); ?>&type=main&file=<?php echo $GLOBALS["mainfiles"][$i]["filename"]; ?>&lang=<?php echo $_GET["lang"]; ?>"<?php echo BuildLinkMouseOver($GLOBALS["tViewFile"]); ?>>
                    <?php echo $iViewFiles; ?></a>&nbsp;<?php
                 }
              }
              else
              {
                 $iEditFiles = lsimagehtmltag('./'.$GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEditFile"],0);
                    if ($iEditFiles == '') $iEditFiles = lsimagehtmltag('./'.$GLOBALS["style_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tViewFile"],0);
                     ?>
                 <a href="<?php echo BuildLink('m_langfileform.php'); ?>&type=main&file=<?php echo $GLOBALS["mainfiles"][$i]["filename"]; ?>&lang=<?php echo $_GET["lang"]; ?>"<?php echo BuildLinkMouseOver($GLOBALS["tEditFile"]); ?>>
                 <?php echo $iEditFiles; ?></a>&nbsp;<?php
              }
              ?>
          </td>
          <td valign="top" class="content">
              <?php echo $GLOBALS["mainfiles"][$i]["filename"]; ?>
          </td>
      </tr>
      <?php
   }

   chdir($savedir);


   adminsubheader(2,$GLOBALS["thModuleFiles"]);
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(5,$GLOBALS["tEdit"],'c');
       adminlistitem(85,$GLOBALS["tFileNameM"],'');
       ?>
   </tr>
   <?php

   chdir($GLOBALS["rootdp"].$GLOBALS["modules_home"]);
   $i = 0;
   if ($handle = @opendir('.'))
   {
      while ($file = readdir($handle))
      {
         $filename = $file;
         if (is_dir($filename))
         {
            if (($filename != '.') && ($filename != '..'))
            {
               if (file_exists($filename.'/lang_'.$filename.'_en.php'))
               {
                  $GLOBALS["modulefiles"][$i]["filename"] = $filename;
                  $i++;
               }
            }
         }
      }
      closedir($handle);
   }

   sort($GLOBALS["modulefiles"]);
   while (list($i,$val) = each($GLOBALS["modulefiles"]))
   {
      ?>
      <tr class="teasercontent">
          <td valign="top" align="center" class="content">
              <?php
              chdir($savedir);
              if ($GLOBALS["canedit"] == False)
              {
                 if ($GLOBALS["canedit"] == False)
                 {
                    echo $GLOBALS["iBlank"];
                 }
                 else
                 {
                    $iViewFiles = lsimagehtmltag('./'.$GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tViewFile"],0); ?>
                    <a href="<?php echo BuildLink('m_langfileform.php'); ?>&type=module&file=<?php echo $GLOBALS["modulefiles"][$i]["filename"]; ?>&lang=<?php echo $_GET["lang"]; ?>"<?php echo BuildLinkMouseOver($GLOBALS["tViewFile"]); ?>>
                    <?php echo $iViewFiles; ?></a>&nbsp;<?php
                 }
              }
              else
              {
                 $iEditFiles = lsimagehtmltag('./'.$GLOBALS["theme_home"],$EzAdmin_Style["FolderIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tEditFile"],0); ?>
                 <a href="<?php echo BuildLink('m_langfileform.php'); ?>&type=module&file=<?php echo $GLOBALS["modulefiles"][$i]["filename"]; ?>&lang=<?php echo $_GET["lang"]; ?>"<?php echo BuildLinkMouseOver($GLOBALS["tEditFile"]); ?>>
                 <?php echo $iEditFiles; ?></a>&nbsp;<?php
              }
              ?>
          </td>
          <td valign="top" class="content">
              <?php echo $GLOBALS["modulefiles"][$i]["filename"]; ?>
          </td>
          <?php
          chdir($GLOBALS["rootdp"].$GLOBALS["modules_home"]);
          ?>
      </tr>
      <?php
   }
   chdir($savedir);
   frmModuleReturn(2)

   ?>
   </table>
   </body>
   </html>
   <?php
} // function frmLangFiles()


function frmModuleReturn($colspan)
{
   global $_GET;
   ?>
   <tr class="teasercontent">
       <td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
           <a href="<?php echo BuildLink($GLOBALS["rootdp"].$GLOBALS["admin_home"].'m_languages.php'); ?>&page=<?php echo $_GET["page"]; ?>&sort=<?php echo $_GET["sort"]; ?>" <?php echo BuildLinkMouseOver($GLOBALS["tRet_Languages"]); ?>><?php echo $GLOBALS["tRet_Languages"]; ?></a>
       </td>
   </tr>
   <?php
} // function frmModuleReturn()


?>
