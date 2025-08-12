<?php

/***************************************************************************

 m_langfileform.php
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

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'languages';
$validaccess = VerifyAdminLogin3("LanguageCode");

includeLanguageFiles('admin','languages');


// If we've been passed the request from the languages list, then we
//    read the language data from the file for an edit request
if ($_GET["file"] != '') {
   $_POST["lang"] = $_GET["lang"];
   $_POST["file"] = $_GET["file"];
   $_POST["type"] = $_GET["type"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   AddLanguage($_POST["type"],$_POST["file"],$_POST["lang"]);
   Header("Location: ".BuildLink('m_langfiles.php')."&lang=".$_POST["lang"]."&page=".$_POST["sort"]."&page=".$_POST["sort"]);
}
frmLanguageForm();


function frmLanguageForm()
{
   global $_POST;

   $strQuery = "SELECT languagename,charset FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='en'";
   $result = dbRetrieve($strQuery,true,0,0);
   if ($rs = dbFetch($result)) {
      $baselanguagename = $rs["languagename"];
      $languagename = $rs["languagename"];
      $basecharset = $rs["charset"];
      $charset = $rs["charset"];

   }
   dbFreeResult($result);

   if ($_POST["lang"] != 'en') {
      $strQuery = "SELECT languagename,charset FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$_POST["lang"]."'";
      $result = dbRetrieve($strQuery,true,0,0);
      if ($rs = dbFetch($result)) {
         $languagename = $rs["languagename"];
         $charset = $rs["charset"];
      }
      dbFreeResult($result);
   }

   $convertcharsets = ($basecharset != $charset);
   if ($convertcharsets) {
      if (function_exists('mb_convert_encoding')) { adminformheader('UTF-8');
      } else {
         $convertcharsets = false;
         adminformheader($charset);
      }
   } else { adminformheader(); }
   adminformopen('0-text');
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   echo $GLOBALS["strErrors"];
   adminsubheader(2,$GLOBALS["thLanguageGeneral"].'&nbsp;&nbsp;('.$_POST["file"].')');

   $textcount = ReadBaseLanguageFile($_POST["type"],$_POST["file"],'en');
   if ($_POST["lang"] != 'en') {
      ReadLanguageFile($_POST["type"],$_POST["file"],$_POST["lang"],$textcount);
   }

   SectionDropDown($textcount,'TOP','BOTTOM',$GLOBALS["thJumptoBottom"]);

   for ($i = 1; $i < $textcount; $i++) {
      if ($GLOBALS["LanguageTexts"][$i]["type"] == 'C') {
         ?>
         <tr class="topmenuback">
             <td valign="top" class="content">
                 <a name="<?php echo $GLOBALS["LanguageTexts"][$i]["name"]; ?>"></a>
         <?php
      } else {
         ?>
         <tr class="tablecontent">
             <td valign="top" class="content">
                 <b><?php echo $GLOBALS["LanguageTexts"][$i]["name"]; ?></b>
         <?php
      }
      ?>
          <input type="hidden" name="<?php echo $i; ?>-name" value="<?php echo $GLOBALS["LanguageTexts"][$i]["name"]; ?>">
      </td>
      <td valign="top" class="content">
      <?php
      if ($_POST["lang"] == 'en') {
         ?>
         <textarea rows="2" name="<?php echo $i; ?>-text" cols="56"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["LanguageTexts"][$i]["text"]); ?></textarea>
         <input type="hidden" name="<?php echo $i; ?>-type" value="<?php echo $GLOBALS["LanguageTexts"][$i]["type"]; ?>">
         <?php
      } else {
         ?>
         <table border="0" cellpadding="1" cellspacing="0">
             <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                     <b><?php echo charsetText($baselanguagename,$convertcharsets,$basecharset); ?>:</b>
                 </td>
                 <td>
                     <input type="text" name="<?php echo $i; ?>-en" size="64" value="<?php echo htmlspecialchars(charsetText($GLOBALS["LanguageTexts"][$i]["text"],$convertcharsets,$basecharset)); ?>" maxlength="100" readonly>
                 </td>
             </tr>
             <tr><td align="<?php echo $GLOBALS["right"]; ?>" valign="top">
                     <b><?php echo charsetText($languagename,$convertcharsets,$charset); ?>:</b>
                 </td>
                 <td>
                     <textarea rows="2" name="<?php echo $i; ?>-text" cols="44"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars(charsetText($GLOBALS["LanguageTexts"][$i]["langtext"],$convertcharsets,$charset)); ?></textarea>
                     <input type="hidden" name="<?php echo $i; ?>-type" value="<?php echo $GLOBALS["LanguageTexts"][$i]["type"]; ?>">
                 </td>
             </tr>
         </table>
         <?php
      }
      ?>
      </td></tr>
      <?php
   }

   SectionDropDown($textcount,'BOTTOM','TOP',$GLOBALS["thJumptoTop"]);

   langformsavebar(2,'m_langfiles.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(2);
      ?><input type="hidden" name="lang" value="<?php echo $_POST["lang"]; ?>"><?php
      ?><input type="hidden" name="file" value="<?php echo $_POST["file"]; ?>"><?php
      ?><input type="hidden" name="type" value="<?php echo $_POST["type"]; ?>"><?php
      ?><input type="hidden" name="linecount" value="<?php echo $textcount; ?>"><?php
   }
   adminformclose();
} // function frmLanguageForm()


function SectionDropDown($textcount,$curloc,$newloc,$newloctext)
{
   $dropoptions = '';
   $c = 0;
   for ($i = 1; $i < $textcount; $i++) {
      if ($GLOBALS["LanguageTexts"][$i]["type"] == 'C') {
         $dropoptions .= '<option value="#'.$GLOBALS["LanguageTexts"][$i]["name"].'">'.$GLOBALS["LanguageTexts"][$i]["text"];
         $c++;
      }
   }
   echo '<tr class="teaserheadercontent">';
   echo '<td colspan="2" align="'.$GLOBALS["left"].'" nowrap>';
   echo '<a name="'.$curloc.'"></a>';
   echo '<select name="SectionName'.$curloc.'" onChange="location=this.options[this.selectedIndex].value;" size="1"'.$GLOBALS["fieldstatus"].'><option value="#'.$curloc.'">'.$GLOBALS["thJumpRef"].'<option value="#'.$newloc.'">'.$newloctext.$dropoptions.'</select>';
   echo '</td></tr>';
} // function SectionDropDown()


function AddLanguage($type,$filename,$lang)
{
   global $_POST;

   $savedir = getcwd();
   //  Generate the language filename, and put ourselves in the appropriate
   //     directory
   if ($type == 'main') {
      $fullfilename = 'lang_'.$filename.'.php';
      chdir($GLOBALS["rootdp"].$GLOBALS["language_home"]);
      //  If the directory doesn't exist for a main file, then we create it
      if ((!file_exists($lang)) || (!is_dir($lang))) { mkdir ($lang, 0777); }
      chdir($lang);
   } else {
      $fullfilename = 'lang_'.$filename.'_'.$lang.'.php';
      chdir($GLOBALS["rootdp"].$GLOBALS["modules_home"].$filename);
   }

   $workfilename = $fullfilename.'wrk';
   $fp = fopen($workfilename, "wb");
   fwrite($fp,'<?php'.chr(10).chr(10));
   for ($i = 1; $i <= $_POST["linecount"]; $i++) {
      if (isset($_POST[$i.'-type'])) {
         $linetype = $_POST[$i.'-type'];
         $varname = $_POST[$i.'-name'];
         if ($_POST["lang"] != 'en') {
            if ($_POST[$i.'-text'] == '') {
               $_POST[$i.'-text'] = $_POST[$i.'-en'];
            }
         }
         if (get_magic_quotes_gpc() == 0) {
            $sVarText = str_replace("'", "\'", $_POST[$i.'-text']);
         } else { $sVarText = $_POST[$i.'-text']; }
         switch ($linetype) {
            //  Comment in the file
            case 'C' : fwrite($fp,chr(10));
                       $outstr = '//  '.$sVarText;
                       break;
            //  Define normal language variable
            case 'V' : $outstr = '$GLOBALS["'.$varname.'"] = \''.$sVarText.'\';';
                       break;
            //  Define array language variable
            case 'A' : $outstr = '$GLOBALS["'.$varname.'"] = array(\''.$sVarText.'\');';
                       $outstr = str_replace("|", "', '", $outstr);
                       break;
         }
         fwrite($fp,$outstr.chr(10));
      }
   }
   fwrite($fp,chr(10).'?>'.chr(10));
   fclose($fp);

   copy($workfilename,$fullfilename);
   unlink($workfilename);

   chdir($savedir);
} // function AddLanguage()


function ReadBaseLanguageFile($type,$filename,$lang)
{
   $savedir = getcwd();
   //  Generate the language filename, and put ourselves in the appropriate
   //     directory
   if ($type == 'main') {
      $fullfilename = 'lang_'.$filename.'.php';
      chdir($GLOBALS["rootdp"].$GLOBALS["language_home"]);
      //  If the directory doesn't exist for a main file, then we create it
      if ((!file_exists($lang)) || (!is_dir($lang))) { mkdir ($lang, 0777); }
      chdir($lang);
   } else {
      $fullfilename = 'lang_'.$filename.'_'.$lang.'.php';
      chdir($GLOBALS["rootdp"].$GLOBALS["modules_home"].$filename);
   }

   $i = 1;
   $c = 1;
   //  If the language file exists, we read in all the language texts defined in that file
   if (file_exists($fullfilename)) {
      $fp = fopen($fullfilename, "rb");
      while (!feof($fp)) {
         $fstring = trim(fgets($fp,2048));
         if (($fstring != '') && ($fstring != '<?php') && ($fstring != '?>')) {
            if (substr($fstring,0,2) == '//') {
               $GLOBALS["LanguageTexts"][$i]["type"] = 'C';
               $GLOBALS["LanguageTexts"][$i]["name"] = 'C-'.$c;
               $GLOBALS["LanguageTexts"][$i]["text"] = trim(substr($fstring,2,strlen($fstring) - 2));
               $GLOBALS["LanguageTexts"][$i]["langtext"] = $GLOBALS["LanguageTexts"][$i]["text"];
               $c++;
            } else {
               $name = explode('"',$fstring);
               $GLOBALS["LanguageTexts"][$i]["name"] = $name[1];
               if (strpos($fstring,'array(') === False) {
                  $fstringpos = strpos($fstring,"'");
                  $stringval = stripslashes(substr($fstring,$fstringpos + 1,strlen($fstring) - $fstringpos - 3));
                  $GLOBALS["LanguageTexts"][$i]["type"] = 'V';
                  $GLOBALS["LanguageTexts"][$i]["text"] = $stringval;
                  $GLOBALS["LanguageTexts"][$i]["langtext"] = $GLOBALS["LanguageTexts"][$i]["text"];
               } else {
                  $fstringpos = strpos($fstring,"array(");
                  $stringval = substr($fstring,$fstringpos + 7,strlen($fstring) - $fstringpos - 10);
                  $stringval = str_replace("', '","|",$stringval);
                  $GLOBALS["LanguageTexts"][$i]["type"] = 'A';
                  $GLOBALS["LanguageTexts"][$i]["text"] = $stringval;
                  $GLOBALS["LanguageTexts"][$i]["langtext"] = $GLOBALS["LanguageTexts"][$i]["text"];
               }
            }
            $i++;
         }
      }
      fclose($fp);
   }
   chdir($savedir);

   return $i;
} // function ReadBaseLanguageFile()


function ReadLanguageFile($type,$filename,$lang,$textcount)
{
   $savedir = getcwd();
   //  Generate the language filename, and put ourselves in the appropriate
   //     directory
   if ($type == 'main') {
      $fullfilename = 'lang_'.$filename.'.php';
      chdir($GLOBALS["rootdp"].$GLOBALS["language_home"]);
      //  If the directory doesn't exist for a main file, then we create it
      if ((!file_exists($lang)) || (!is_dir($lang))) { mkdir ($lang, 0777); }
      chdir($lang);
   } else {
      $fullfilename = 'lang_'.$filename.'_'.$lang.'.php';
      chdir($GLOBALS["rootdp"].$GLOBALS["modules_home"].$filename);
   }

   //  If the language file exists, we read in all the language texts defined in that file
   //     and try to store them in the appropriate array element
   $c = 1;
   if (file_exists($fullfilename)) {
      $fp = fopen($fullfilename, "rb");
      while (!feof($fp)) {
         $fstring = trim(fgets($fp,2048));
         if (($fstring != '') && ($fstring != '<?php') && ($fstring != '?>')) {
            if (substr($fstring,0,2) == '//') {
               $i = 1;
               while ($GLOBALS["LanguageTexts"][$i]["name"] != 'C-'.$c) { $i++; }
               $GLOBALS["LanguageTexts"][$i]["langtext"] = trim(substr($fstring,2,strlen($fstring) - 2));
               $c++;
            } else {
               $name = explode('"',$fstring);
               $i = 1;
               while ($GLOBALS["LanguageTexts"][$i]["name"] != $name[1]) { $i++; }
               if ($i <= $textcount) {
                  if (strpos($fstring,'array(') === False) {
                     $fstringpos = strpos($fstring,"'");
                     $stringval = stripslashes(substr($fstring,$fstringpos + 1,strlen($fstring) - $fstringpos - 3));
                     $GLOBALS["LanguageTexts"][$i]["langtext"] = $stringval;
                  } else {
                     $fstringpos = strpos($fstring,"array(");
                     $stringval = substr($fstring,$fstringpos + 7,strlen($fstring) - $fstringpos - 10);
                     $stringval = str_replace("', '","|",$stringval);
                     $GLOBALS["LanguageTexts"][$i]["langtext"] = $stringval;
                  }
               }
            }
         }
      }
      fclose($fp);
   }
   chdir($savedir);
} // function ReadLanguageFile()


function langformsavebar($colspan,$cancelref)
{
   global $_GET, $_POST;

   if ($_POST["page"] == '') { $_POST["page"] = $_GET["page"]; }
   if ($_POST["sort"] == '') { $_POST["sort"] = $_GET["sort"]; }
   ?>
   <tr class="topmenuback">
       <td colspan="<?php echo $colspan; ?>" align="<?php echo $GLOBALS["left"]; ?>">
           <?php
           if ($GLOBALS["specialedit"] == True) {
           // Save privilege
              ?>
              <input type="submit" value="<?php echo $GLOBALS["tSave"]; ?>" name="submit">&nbsp;
              <input type="reset" value="<?php echo $GLOBALS["tReset"]; ?>" name="reset">&nbsp;
              <?php
           }
           ?>
           <input type="button" value="<?php echo $GLOBALS["tCancel"]; ?>" onClick="javascript:document.location.href='<?php echo BuildLink($cancelref); ?>&page=<?php echo $_POST["page"]; ?>&sort=<?php echo $_POST["sort"]; ?>&lang=<?php echo $_POST["lang"]; ?>'" name="cancel">
       </td>
   </tr>
   <?php
} // function langformsavebar()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
