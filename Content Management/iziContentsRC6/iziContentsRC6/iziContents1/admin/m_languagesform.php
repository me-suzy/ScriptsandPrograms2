<?php

/***************************************************************************

 m_languagesform.php
 --------------------
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
include_once ("m_languageinst.php");

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

if ($_GET["submitted"] == "yes") {
   if ($_GET["languagecode"] != '') { AddLanguage($_GET["languagecode"]); }
   Header("Location: ".BuildLink('m_languages.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]);
   exit;
} else {
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
}
frmLanguageForm();


function frmLanguageForm()
{
   global $_POST;

   adminformheader();
   adminformopen('languagecode');
   adminformtitle(3,$GLOBALS["tFormTitle2"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("Language","languagecode"); ?>
       <td valign="top" class="content">
          <?= $GLOBALS["tEnabled"]; ?>
       </td>
       <td><?= $GLOBALS["tLanguageCode"]; ?> </td>
       <tr>   
          <? RenderLanguages($_POST["languagecode"]); ?>
       </tr>
   <?php
   //adminformsavebar(3,'m_languages.php');
   adminformclose();
   admintitle(3,"Upload Language");
   getUploadform(); 
   ?>
   <script language="Javascript" type="text/javascript">
	<!-- Begin
	function RelLang(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tToggleLanguage"]; ?>')) {
			location.href='<?php echo BuildLink('m_languagesform.php'); ?>&' + sParams;
		}
	}
	//  End -->
</script>
<?php
} // function frmLanguageForm()


function AddLanguage($Language)
{
	$Status = true;
	
	$installfile= '../'.$GLOBALS["language_home"].$Language.'/install.sql';
  	$fp = fopen($installfile, "r");
	if (!$fp or !is_readable($installfile)) {
		$GLOBALS["strErrors"][] = 'Unable to open '.$Language.' install.sql file';
	}else {
		$file = file_get_contents($installfile);
		$file = str_replace("\r", "", $file);
		$query = explode(";\n",$file);
		for ($i=0; $i < count($query) - 1; $i++) {
			if ($Status) {
				$sqlQuery = trim($query[$i]);
				$workquery = explode("\n",$sqlQuery);
				for ($j=0; $j < count($workquery) - 1; $j++) {
					$test_workquery = trim($workquery[$j]);
					if (substr($test_workquery,0,1) == '#') { $workquery[$j] = ''; }
				}
				$sqlQuery = implode("",$workquery);
				if ((substr($sqlQuery,0,4) == 'DROP') || (substr($sqlQuery,0,6) == 'CREATE') || (substr($sqlQuery,0,6) == 'UPDATE') || (substr($sqlQuery,0,6) == 'INSERT')) {
					if (substr($sqlQuery,0,4) == 'DROP') {
						$sqlQuery = str_replace('DROP TABLE IF EXISTS ','DROP TABLE IF EXISTS '.$GLOBALS["DBPrefix"], $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'CREATE') {
						$sqlQuery = str_replace('CREATE TABLE ','CREATE TABLE '.$GLOBALS["DBPrefix"], $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'UPDATE') {
						$sqlQuery = str_replace('UPDATE ','UPDATE '.$GLOBALS["DBPrefix"], $sqlQuery);
					}
					if (substr($sqlQuery,0,6) == 'INSERT') {
						$sqlQuery = str_replace('INSERT INTO ','INSERT INTO '.$GLOBALS["DBPrefix"], $sqlQuery);
					}
					$result = dbExecute($sqlQuery,true);
					if (!$result) { $Status = False; }
				}
			}
		}
		$fp = fclose($fp);
	if ($Status) { setusergroup_languages($Language,$GLOBALS["DBPrefix"],'Administrators','administrator',$Status); }
	if ($Status) { setusergroup_languages($Language,$GLOBALS["DBPrefix"],'Contributors','contributor',$Status); }
	if ($Status) { setusergroup_languages($Language,$GLOBALS["DBPrefix"],'Translators','translator',$Status); }
	if ($Status) { setusergroup_languages($Language,$GLOBALS["DBPrefix"],'Members','member',$Status); }
	if ($Status) { setusergroup_languages($Language,$GLOBALS["DBPrefix"],'Probationary Members','probationer',$Status); }

	if ($Status) { settagcategory_languages($Language,$GLOBALS["DBPrefix"],'Table','table',$Status); }
	if ($Status) { settagcategory_languages($Language,$GLOBALS["DBPrefix"],'Titles','title',$Status); }
	if ($Status) { settagcategory_languages($Language,$GLOBALS["DBPrefix"],'List','list',$Status); }
	if ($Status) { settagcategory_languages($Language,$GLOBALS["DBPrefix"],'Text Formatting','text',$Status); }
	if ($Status) { settagcategory_languages($Language,$GLOBALS["DBPrefix"],'Miscellaneous','other',$Status); }
		
		
	}
	
} // function AddLanguage()


function RenderLanguages($LanguageCode)
{
   // Count the number of files required for a full language fileset, based on the default site language
   $savedir = getcwd();
   chdir($GLOBALS["rootdp"].'languages');
   $count = 0;
   if ($handle = @opendir('.')) {
      while ($filename = readdir($handle)) {
         if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.')) && !($filename == 'CVS')) {
            $strQuery="SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$filename."'";
            $result = dbRetrieve($strQuery,true,0,1);
            $rs     = dbFetch($result);
            $lRecCount = dbRowsReturned($result);
            
            echo "<tr class=\"teaserheadercontent\"><td valign=\"top\" align=\"center\" class=\"content\">";
            if ($lRecCount == 0) {
               echo "&nbsp;";
               languagereleasecheck($filename);
               ?>
               </td><td align="center"><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>red_dot.gif"> </td><td align="center"><?= $filename?></td><?          
               $count++;
            }
             else{
               ?>&nbsp;</td><td align="center"><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>green_dot.gif"></td><td align="center"><?= $filename?></td><?          
               $count++;
         
             }
             echo "</tr>";
            
            dbFreeResult($result);
         }
        
      }
      closedir($handle);
   }
   chdir($savedir);
  
} // function RenderLanguages()

function languagereleasecheck($languagecode)
{
        global $_GET;
        
        $GLOBALS["iRelease"] = adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["ReleaseIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tReleaseUser"],0,'rel_button.gif');

        if ($GLOBALS["canedit"] == False) { echo $GLOBALS["iBlank"];
        } else {
                ?>
                &nbsp;<a href="javascript:RelLang('languagecode=<?= $languagecode; ?>&submitted=yes');" <?php echo BuildLinkMouseOver($GLOBALS["tToggleLanguage"]); ?>>
                <?php echo $GLOBALS["iRelease"]; ?></a><?php
        }
} // function modulereleasecheck()

function setusergroup_languages($Language,$DBPrefix,$text,$keyref,&$Status)
{
	$sqlQuery = "INSERT INTO ".$DBPrefix."usergroups (usergroupdesc, usergroupname, language, authorid) VALUES ('".$text."', '".$keyref."', '".$Language."', 1)";
	$result = dbExecute($sqlQuery,true);
	if (!$result) { $Status = False; }
} // function setusergroup_languages()


function settagcategory_languages($Language,$DBPrefix,$text,$keyref,&$Status)
{
	$sqlQuery = "INSERT INTO ".$DBPrefix."tagcategories (catdesc, catname, language, authorid) VALUES ('".$text."', '".$keyref."', '".$Language."', 1)";
	$result = dbExecute($sqlQuery,true);
	if (!$result) { $Status = False; }
} // function settagcategory_languages()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
