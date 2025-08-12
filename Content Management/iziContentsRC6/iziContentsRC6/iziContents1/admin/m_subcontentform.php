<?php

/***************************************************************************

 m_subcontentform.php
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
include_once ("m_subcontentinst.php");

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'subcontent';
$validaccess = VerifyAdminLogin2();

includeLanguageFiles('admin','subcontent');
force_page_refresh();

$GLOBALS["tabindex"] = 1024;

if (!isset($_POST["SCID"])) $_POST["SCID"] = $_GET["SCID"];
if (!isset($_POST["scdb"])) $_POST["scdb"] = $_GET["scdb"];
if (!isset($_POST["page"])) $_POST["page"] = $_GET["page"];
if (!isset($_POST["sort"])) $_POST["sort"] = $_GET["sort"];
if (!isset($_POST["submitted"])) $_POST["submitted"] = $_GET["submitted"];
if (!isset($_POST["mname"])) $_POST["mname"] = $_GET["mname"];

if ($_POST["submitted"] == "yes") {
   if ($_POST["SCID"] != '') {
      // User has submitted the data
      if (bCheckForm()) {
      	AddSubContent();
      	if(isset($GLOBALS["strErrors"])){
        	admhdr();
        	admintitle(4,"module");
        	formError(1); 
      	} else{ Header("Location: ".BuildLink('m_subcontent.php')."&page=".$_POST["page"]);}
      } else {
         // Invalid data has been submitted
         GetFormData();
      }
   } else {
      if ($_POST["mname"] != '') {
         AddNewModule($_POST["mname"]);
      }
      if(isset($GLOBALS["strErrors"])){
      	admhdr();
        	admintitle(4,"module");
        	formError(1); 
      } else {Header("Location: ".BuildLink('m_subcontent.php')."&page=".$_POST["page"]);}
   }
} else {
   if ($_GET["SCID"] != '') {
      // First visit to the form
      GetGlobalData();
   }
}

if ($_GET["SCID"] != '') {
   frmSubContentForm();
} else {
   frmNewModuleForm();
}

function frmSubContentForm()
{
   global $_POST;

   adminformheader();
   adminformopen('mname');
   adminformtitle(4,$GLOBALS["tFormTitle2"]);
   if (isset($GLOBALS["strErrors"])) { formError(4); }
   adminsubheader(4,$GLOBALS["thGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("ModuleName","mname"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="mname" size="32" value="<?php echo htmlspecialchars($GLOBALS["fsName"]) ?>" maxlength=32>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ModuleTitle","title"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="title" size="70" value="<?php echo htmlspecialchars($GLOBALS["fsTitle"]) ?>" maxlength=255>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thSubmissions"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("SText","stext"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="stext" size="70" value="<?php echo htmlspecialchars($GLOBALS["fsText"]) ?>" maxlength=255>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("STextDisplay","stextdisplay"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="checkbox" name="stextdisplay" value="Y" <?php if($GLOBALS["fsTextDisplay"] == 'Y') echo "checked"?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SGraphic","sgraphic"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="sgraphic" size="64" value="<?php echo $GLOBALS["fsGraphic"]; ?>" maxlength=255>
	   <?php adminimagedisplay('sgraphic',$GLOBALS["fsGraphic"],$GLOBALS["tShowBanner"]); ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SGraphicDisplay","sgraphicdisplay"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="checkbox" name="sgraphicdisplay" value="Y" <?php if($GLOBALS["fsGraphicDisplay"] == 'Y') echo "checked"?>>
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thSubmissionRules"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("RegOnly","registered"); ?>
       <td valign="top" class="content">
           <input type="checkbox" name="registered" value="Y" <?php if($GLOBALS["fsRegistered"] == 'Y') echo "checked"?>>
       </td>
       <?php FieldHeading("Usergroups",6); ?>
       <td valign="top" class="content" rowspan="2">
           <select name="usergroups[]" multiple size="4"<?php echo $GLOBALS["fieldstatus"]; ?>><?php RenderUsergroups($GLOBALS["fsUsergroups"]); ?></select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("ValReq","validate"); ?>
       <td valign="top" class="content">
           <input type="checkbox" name="validate" value="Y" <?php if($GLOBALS["fsValidate"] == 'Y') echo "checked"?>>
       </td>
       <td valign="top" class="content">
           &nbsp;
       </td>
   </tr>
   <?php adminsubheader(4,$GLOBALS["thDisplay"]); ?>
   <tr class="tablecontent">
       <?php FieldHeading("PerPage","perpage"); ?>
       <td colspan="3" valign="top" class="content">
           <input type="text" name="perpage" size="4" value="<?php echo $GLOBALS["fsPerPage"]; ?>" maxlength="4">
       </td>
   </tr>
   <tr class="tablecontent">
   <?php 
   		
   		if(check_cats($_POST["scdb"])){
       	FieldHeading("UseCategories","usecategories"); ?>
       <td valign="top" class="content">
           <input type="checkbox" name="usecategories" value="Y" <?php if($GLOBALS["fsUseCategories"] == 'Y') echo "checked"?>>
       <?php } else { ?>
       <td valign="top" class="content" colspan="2">
       <input type="hidden" name="usecategories" value="N">
       <?php } ?>
       </td>
       <?php FieldHeading("OrderBy","orderby"); ?>
       <td valign="top" class="content">
           <select name="orderby" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="C" <?php if($GLOBALS["fsOrderBy"] == "C") echo "selected"; ?>><?php echo $GLOBALS["toCategory"]; ?>
               <option value="D" <?php if($GLOBALS["fsOrderBy"] != "C") echo "selected"; ?>><?php echo $GLOBALS["toDate"]; ?>
           </select>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("PostedBy","postedby"); ?>
       <td valign="top" class="content">
           <input type="checkbox" name="postedby" value="Y" <?php if($GLOBALS["fsPostedBy"] == 'Y') echo "checked"?>>
       </td>
       <?php FieldHeading("PostedDate","posteddate"); ?>
       <td valign="top" class="content">
           <input type="checkbox" name="posteddate" value="Y" <?php if($GLOBALS["fsPostedDate"] == 'Y') echo "checked"?>>
       </td>
   </tr>
   <?php
   adminformsavebar(4,'m_subcontent.php');
   if ($GLOBALS["specialedit"] == True) {
      adminhelpmsg(4);
      ?><input type="hidden" name="SCID" value="<?php echo $_POST["SCID"]; ?>"><?php
   }
   adminformclose();
    
} // function frmSubContentForm()

function check_cats($moduleid){
$strQuery="SELECT hascats FROM ".$GLOBALS["eztbModules"]." WHERE moduledirectory='".$moduleid."'";
$result = dbRetrieve($strQuery,true,0,1);
$rs     = dbFetch($result);
if($rs["hascats"] == 'Y'){
	return true;
} else { return false;}

}


function frmNewModuleForm()
{
   global $_POST, $EzAdmin_Style; 
   
   adminformheader();
   adminformopen('mname');
   adminformtitle(3,$GLOBALS["tFormTitle2"]);
   adminbuttons('',$GLOBALS["tAddNew"],'','');
   $GLOBALS["iRelease"] = adminbuttontest($GLOBALS["theme_home"],$EzAdmin_Style["ReleaseIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tReleaseUser"],0,'rel_button.gif');
?>
  <tr class="teaserheadercontent">
  <?
       adminlistitem(12,$GLOBALS["tEdit"],'');
       adminlistitem(12,'Active','');
       adminlistitem(32,$GLOBALS["tModuleName"],'');
    ?>
    </tr>
    <?  
   $savedir = getcwd();
   chdir($GLOBALS["rootdp"].'modules');
   $count = 0;
   if ($handle = @opendir('.')) {
      while ($filename = readdir($handle)) {
         if ((is_dir($filename)) && (!($filename == '..') && !($filename == '.')) && !($filename == 'CVS')) {
            $strQuery="SELECT * FROM ".$GLOBALS["eztbModules"]." WHERE moduledirectory='".$filename."'";
            $result = dbRetrieve($strQuery,true,0,1);
            $rs     = dbFetch($result);
            $lRecCount = dbRowsReturned($result);
            
            echo "<tr class=\"teaserheadercontent\"><td valign=\"top\" align=\"center\" class=\"content\">";
            if ($lRecCount == 0) {
               admindeletecheck('DelDir','dirname',$filename);
               echo "&nbsp;";
               modulereleasecheck($filename);
               ?>
               </td><td align="center"><img src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>red_dot.gif"> </td><td align="center"><?= $filename?></td><?          
               $count++;
            }
             else{
               //admindeletecheck('DelModule','name',$filename);
               moduledeletecheck($filename);
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
   
   //adminformsavebar(3,'m_subcontent.php');
   adminformclose();
   admintitle(2,"Upload module");
   getUploadform();   
   
   ?>
   <script language="Javascript" type="text/javascript">
	<!-- Begin
	function DelDir(sParams) {
		if (window.confirm('<?php echo $GLOBALS["tConfirmDeletion"]; ?>')) {
			location.href='<?php echo BuildLink('m_subcontentdelete.php'); ?>&' + sParams;
		}
	}
        
        function DelModule(sParams){
                if (window.confirm('<?php echo $GLOBALS["tConfirmRelease"]; ?>')) {
			location.href='<?php echo BuildLink('m_subcontentdelete.php'); ?>&' + sParams;
		}
        }
        
        function RelModule(sParams){
        if (window.confirm('<?php echo $GLOBALS["tConfirmRelease"]; ?>')) {
			location.href='<?php echo BuildLink('m_subcontentform.php'); ?>&' + sParams;
		}
        }
	//  End -->
</script><?
} // function frmNewModuleForm()


function AddSubContent()
{
   global $_POST;

   $sTitle = dbString($_POST["title"]);
   $sSText = dbString($_POST["stext"]);

   $sUserGroups = '';
   if (isset($_POST["usergroups"])) {
      reset ($_POST["usergroups"]);
      while (list ($userkey, $userval) = each ($_POST["usergroups"])) {
         $sUserGroups .= ','.$userval;
      }
   }

   $strQuery = "UPDATE ".$GLOBALS["eztbSpecialcontents"]." SET scname='".$_POST["mname"]."', sctitle='".$sTitle."', screg='".$_POST["registered"]."', scvalid='".$_POST["validate"]."', stext='".$sSText."', stextdisplay='".$_POST["stextdisplay"]."', sgraphic='".$_POST["sgraphic"]."', sgraphicdisplay='".$_POST["sgraphicdisplay"]."', usergroups='".$sUserGroups."', scusecategories='".$_POST["usecategories"]."', orderby='".$_POST["orderby"]."', showpostedby='".$_POST["postedby"]."', showposteddate='".$_POST["posteddate"]."', perpage='".$_POST["perpage"]."' WHERE scid=".$_POST["SCID"];
   $result = dbExecute($strQuery,true);
   dbCommit();
} // function AddSubContent()


function create_module($Module,$DBPrefix,&$Status)
{

   $Status = True;
   $fp = fopen('./'.$Module.'/install.sql', "r");
   if (!$fp) {
      //install_message('red','Unable to open '.$Module.' install.sql file');
       $GLOBALS["strErrors"][] = 'Unable to find needed install.sql in Modulefolder';
      $Status = False;
   } else {
      $file = fread($fp, filesize('./'.$Module.'/install.sql'));
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
            if ((substr($sqlQuery,0,4) == 'DROP') || (substr($sqlQuery,0,6) == 'CREATE') || (substr($sqlQuery,0,6) == 'INSERT')) {
               if (substr($sqlQuery,0,4) == 'DROP') {
                  $sqlQuery = str_replace('DROP TABLE IF EXISTS ','DROP TABLE IF EXISTS '.$DBPrefix, $sqlQuery);
               }
               if (substr($sqlQuery,0,6) == 'CREATE') {
                  $sqlQuery = str_replace('CREATE TABLE ','CREATE TABLE '.$DBPrefix, $sqlQuery);
               }
               if (substr($sqlQuery,0,6) == 'INSERT') {
                  $sqlQuery = str_replace('INSERT INTO ','INSERT INTO '.$DBPrefix, $sqlQuery);
               }
               if ($GLOBALS["Log"] == 'Y') { dbWriteLog($sqlQuery); }
               $result = dbExecute($sqlQuery,true);
               if (!$result) { $Status = False; }
            }
         }
      }
      $fp = fclose($fp);
   }
	
} // function create_module()


function AddNewModule($modulename)
{
	$Status = True;
	$savedir = getcwd();
	chdir($GLOBALS["rootdp"].'modules');
	create_module($modulename,$GLOBALS["eztbMasterPrefix"],$Status);
	if (($Status) && ($GLOBALS["eztbMasterPrefix"] != '')) {
		$sqlQuery = "UPDATE ".$GLOBALS["eztbSpecialcontents"]." SET scuseprefix='Y' WHERE scname='".$modulename."'";
		$result = dbExecute($sqlQuery,true);
		if (!$result) { $Status = False; }
	}
	if ($GLOBALS["gsMultiSite"] == 'Y') {
		if ($Status) {
			$strQuery = "SELECT sitecode FROM ".$GLOBALS["eztbSites"];
			$sresult = dbRetrieve($strQuery,true,0,0);
			while (($Status) && ($rs = dbFetch($sresult))) {
				create_module($modulename,$rs["sitecode"],$Status);
				if ($Status) {
					if ($GLOBALS["eztbMasterPrefix"] != '') {
						$sqlQuery = "UPDATE ".$rs["sitecode"].substr($GLOBALS["eztbSpecialcontents"],strlen($GLOBALS["eztbMasterPrefix"]))." SET scuseprefix='Y' WHERE scname='".$modulename."'";
					} else {
						$sqlQuery = "UPDATE ".$rs["sitecode"].$GLOBALS["eztbSpecialcontents"]." SET scuseprefix='Y' WHERE scname='".$modulename."'";
					}
					$result = dbExecute($sqlQuery,true);
					if (!$result) { $Status = False; }
				}
			}
			dbFreeResult($sresult);
		}
	}
	chdir($savedir);
} // function AddNewModule()


function GetGlobalData()
{
   global $_GET;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbSpecialcontents"]." WHERE scid='".$_GET["SCID"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["fsName"]           = $rs["scname"];
   $GLOBALS["fsTitle"]          = $rs["sctitle"];
   $GLOBALS["fsRegistered"]     = $rs["screg"];
   $GLOBALS["fsValidate"]       = $rs["scvalid"];
   $GLOBALS["fsText"]           = $rs["stext"];
   $GLOBALS["fsTextDisplay"]    = $rs["stextdisplay"];
   $GLOBALS["fsGraphic"]        = $rs["sgraphic"];
   $GLOBALS["fsGraphicDisplay"] = $rs["sgraphicdisplay"];
   $GLOBALS["fsUsergroups"]     = $rs["usergroups"];
   $GLOBALS["fsUseCategories"]  = $rs["scusecategories"];
   $GLOBALS["fsOrderBy"]        = $rs["orderby"];
   $GLOBALS["fsPostedBy"]       = $rs["showpostedby"];
   $GLOBALS["fsPostedDate"]     = $rs["showposteddate"];
   $GLOBALS["fsPerPage"]        = $rs["perpage"];
   dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
   global $_POST;

   $GLOBALS["fsName"]           = $_POST["mname"];
   $GLOBALS["fsTitle"]          = $_POST["title"];
   $GLOBALS["fsRegistered"]     = $_POST["screg"];
   $GLOBALS["fsValidate"]       = $_POST["validate"];
   $GLOBALS["fsText"]           = $_POST["stext"];
   $GLOBALS["fsTextDisplay"]    = $_POST["stextdisplay"];
   $GLOBALS["fsGraphic"]        = $_POST["sgraphic"];
   $GLOBALS["fsGraphicDisplay"] = $_POST["sgraphicdisplay"];
   $GLOBALS["fsUsergroups"]     = $_POST["usergroups"];
   $GLOBALS["fsUseCategories"]  = $_POST["usecategories"];
   $GLOBALS["fsOrderBy"]        = $_POST["orderby"];
   $GLOBALS["fsPostedBy"]       = $_POST["postedby"];
   $GLOBALS["fsPostedDate"]     = $_POST["posteddate"];
   $GLOBALS["fsPerPage"]        = $_POST["perpage"];
} // function GetFormData()


function RenderUsergroups($GroupNames)
{
   $sqlQuery = "SELECT * FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' ORDER BY usergroupname";
   $result = dbRetrieve($sqlQuery,true,0,0);
   while ($rs = dbFetch($result)) {
      echo '<option ';
      if (strpos($GroupNames, $rs["usergroupname"], 0)) { echo 'selected '; }
      echo 'value="'.$rs["usergroupname"].'">'.$rs["usergroupdesc"];
   }
   dbFreeResult($result);
} // function RenderUsergroups()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if (!is_numeric($_POST["perpage"])) {
      $GLOBALS["strErrors"][] = $GLOBALS["ePerPageNumber"];
   } else {
      if ($_POST["perpage"] == 0) {
         $GLOBALS["strErrors"][] = $GLOBALS["ePerPageZero"];
      }
   }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()

// checks if modules can be released and sets path
function modulereleasecheck($module)
{
        global $_GET;

        if ($GLOBALS["canedit"] == False) { echo $GLOBALS["iBlank"];
        } else {
                ?>
                &nbsp;<a href="javascript:RelModule('mname=<?php echo $module; ?>&submitted=yes');" <?php echo BuildLinkMouseOver($GLOBALS["tReleaseModule"]); ?>>
                <?php echo $GLOBALS["iRelease"]; ?></a><?php
        }
} // function modulereleasecheck()

// checks if modules can be deleted and sets path
function moduledeletecheck($module)
{
        global $_GET;

        if ($GLOBALS["canedit"] == False) { echo $GLOBALS["iBlank"];
        } else {
                ?>
                &nbsp;<a href="javascript:DelModule('scname=<?php echo $module; ?>');" <?php echo BuildLinkMouseOver($GLOBALS["tReleaseModule"]); ?>>
                <?php echo $GLOBALS["iRelease"]; ?></a><?php
        }
} // function moduledeletecheck()

include($GLOBALS["rootdp"]."include/javafuncs.php");


?>
