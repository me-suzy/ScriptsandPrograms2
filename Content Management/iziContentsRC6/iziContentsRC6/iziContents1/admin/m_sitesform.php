<?php

/***************************************************************************

 m_sitesform.php
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

// Security vet.
// Start by setting all security access to false.
// Do it in the code to overwrite any spurious values that a hacker may
//    have tried to pass in the url
$GLOBALS["specialedit"] = $GLOBALS["canedit"] = $GLOBALS["canadd"] = $GLOBALS["canview"] = False;
$GLOBALS["fieldstatus"] = '';

// Validate the user's level of access for this form.
$GLOBALS["form"] = 'sites';
$validaccess = VerifyAdminLogin3("SiteID");

includeLanguageFiles('admin','sites');


// If we've been passed the request from the sites list, then we
//    read the site data from the database for an edit request, or skip
//    if this is an 'add new' request
if ($_GET["SiteCode"] != '') {
   $_POST["SiteCode"] = $_GET["SiteCode"];
   $_POST["page"] = $_GET["page"];
   $_POST["sort"] = $_GET["sort"];
   GetGlobalData();
}

$GLOBALS["tabindex"] = 1024;

if ($_POST["submitted"] == "yes") {
   // User has submitted the data
   if (bCheckForm()) {
      AddSite();
      Header("Location: ".BuildLink('m_sites.php')."&page=".$_POST["page"]."&sort=".$_POST["sort"]);
   } else {
      // Invalid data has been submitted
      GetFormData();
   }
}
frmSiteForm();


function frmSiteForm()
{
   global $_POST;

   adminformheader();
   if ($_POST["SiteCode"] != '') {
      adminformopen('sitename');
   } else {
      adminformopen('sitecode');
   }
   adminformtitle(2,$GLOBALS["tFormTitle"]);
   if (isset($GLOBALS["strErrors"])) { formError(2); }
   adminsubheader(2,$GLOBALS["thSiteGeneral"]);
   ?>
   <tr class="tablecontent">
       <?php FieldHeading("SiteCode","sitecode"); ?>
       <td valign="top" class="content">
           <?php
           if ($_POST["SiteCode"] != '') {
              ?><input type="text" name="sitecode" size="32" value="<?php echo $GLOBALS["gsSiteCode"]; ?>" maxlength="32" disabled><?php
           } else {
              ?><input type="text" name="sitecode" size="32" value="<?php echo $GLOBALS["gsSiteCode"]; ?>" maxlength="32"<?php echo $GLOBALS["fieldstatus"]; ?>><?php
           }
           ?>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SiteName","sitename"); ?>
       <td valign="top" class="content">
           <input type="text" name="sitename" size="32" value="<?php echo $GLOBALS["gsSiteName"]; ?>" maxlength="64"<?php echo $GLOBALS["fieldstatus"]; ?>>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SiteDescription","sitedescription"); ?>
       <td valign="top" class="content">
           <textarea name="sitedescription" rows="6" cols="64"<?php echo $GLOBALS["fieldstatus"]; ?>><?php echo htmlspecialchars($GLOBALS["gsSiteDescription"]); ?></textarea>
       </td>
   </tr>
   <tr class="tablecontent">
       <?php FieldHeading("SiteEnabled","siteenabled"); ?>
       <td valign="top" class="content">
           <select name="siteenabled" size="1"<?php echo $GLOBALS["fieldstatus"]; ?>>
               <option value="1" <?php if($GLOBALS["gsSiteEnabled"] == "1") echo "selected"; ?>><?php echo $GLOBALS["tYes"]; ?>
               <option value="0" <?php if($GLOBALS["gsSiteEnabled"] != "1") echo "selected"; ?>><?php echo $GLOBALS["tNo"]; ?>
           </select>
       </td>
   </tr>
   <?php
   adminformsavebar(2,'m_sites.php');
   adminhelpmsg(2);
   ?><input type="hidden" name="SiteCode" value="<?php echo $_POST["SiteCode"]; ?>"><?php
   adminformclose();
} // function frmSiteForm()


function tablenamechange($tablename,$newtablename,$dbstring)
{
   global $_POST;

   $modQuery = str_replace('TABLE '.$tablename, 'TABLE '.$_POST["sitecode"].$newtablename, $dbstring);
   return $modQuery;
}


function AddSite()
{
   global $_POST, $EZ_SESSION_VARS;

   $sSiteName        = dbString($_POST["sitename"]);
   $sSiteDescription = dbString($_POST["sitedescription"]);

   if ($_POST["SiteCode"] != '') {
      $strQuery = "UPDATE ".$GLOBALS["eztbSites"]." SET sitename='".$sSiteName."', sitedescription='".$sSiteDescription."', siteenabled='".$_POST["siteenabled"]."' WHERE sitecode='".$_POST["SiteCode"]."'";
   } else {
      $strQuery = "INSERT INTO ".$GLOBALS["eztbSites"]."(sitecode,sitename,sitedescription,siteenabled) VALUES('".$_POST["sitecode"]."', '".$sSiteName."', '".$sSiteDescription."', '".$_POST["siteenabled"]."')";
   }
   $result = dbExecute($strQuery,true);

   //  If this is a brand new site, we need to set up the directories, config file,
   //     and new database tables
   if ($_POST["SiteCode"] == '') {
      $savedir = getcwd();

      chdir($GLOBALS["rootdp"].$GLOBALS["sites_home"]);
      //  Create the site-specific config file
      $fullfilename = 'config.'.$_POST["sitecode"].'.php';
      $fp = fopen($fullfilename, "wb");
      fwrite($fp,'<?php'.chr(10).chr(10));
      fwrite($fp,'$GLOBALS["image_home"]'.chr(9).'= "'.$GLOBALS["sites_home"].$_POST["sitecode"].'/contentimage/";'.chr(9).'// user images'.chr(10));
      fwrite($fp,'$GLOBALS["downloads_home"]'.chr(9).'= "'.$GLOBALS["sites_home"].$_POST["sitecode"].'/downloads/";'.chr(9).'// user downloads'.chr(10));
      fwrite($fp,'$GLOBALS["script_home"]'.chr(9).'= "'.$GLOBALS["sites_home"].$_POST["sitecode"].'/scripts/";'.chr(9).'// external php script files'.chr(10));
      fwrite($fp,'$GLOBALS["themes_home"]'.chr(9).'= "'.$GLOBALS["sites_home"].$_POST["sitecode"].'/themes/";'.chr(9).'// themes'.chr(10).chr(10).chr(10));
      fwrite($fp,'// ezContents Table Names'.chr(10));
      fwrite($fp,'$GLOBALS["eztbPrefix"]'.chr(9).'= "'.$_POST["sitecode"].'";'.chr(10));
      if ($GLOBALS["gsMultiSiteAuthors"] != 'Y') {
         fwrite($fp,'$GLOBALS["eztbAuthors"]'.chr(9).'= $GLOBALS["eztbPrefix"]."authors";'.chr(10));
      }
      fwrite($fp,'$GLOBALS["eztbSettings"]'.chr(9).'= $GLOBALS["eztbPrefix"]."settings";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbThemes"]'.chr(9).'= $GLOBALS["eztbPrefix"]."themes";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbUserdata"]'.chr(9).'= $GLOBALS["eztbPrefix"]."userdata";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbBanners"]'.chr(9).'= $GLOBALS["eztbPrefix"]."banners";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbTopgroups"]'.chr(9).'= $GLOBALS["eztbPrefix"]."topgroups";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbGroups"]'.chr(9).'= $GLOBALS["eztbPrefix"]."groups";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbSubgroups"]'.chr(9).'= $GLOBALS["eztbPrefix"]."subgroups";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbContents"]'.chr(9).'= $GLOBALS["eztbPrefix"]."contents";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbRatings"]'.chr(9).'= $GLOBALS["eztbPrefix"]."ratings";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbSpecialcontents"]'.chr(9).'= $GLOBALS["eztbPrefix"]."specialcontents";'.chr(10));
      fwrite($fp,'$GLOBALS["eztbModuleSettings"]'.chr(9).'= $GLOBALS["eztbPrefix"]."modulesettings";'.chr(10).chr(10).chr(10));
      fwrite($fp,'// Other Configuration Variables'.chr(10));
      fwrite($fp,'$GLOBALS["RECORDS_PER_PAGE"]'.chr(9).'= '.$GLOBALS["RECORDS_PER_PAGE"].';'.chr(9).'// number of lines of content on admin list pages'.chr(10));
      fwrite($fp,chr(10).'?>'.chr(10));
      fclose($fp);


      //  Create a set of subdirectories under the multi-site home directory
      //  If a directory doesn't exist for a site, then we create it
      if ((!file_exists($_POST["sitecode"])) || (!is_dir($_POST["sitecode"]))) { mkdir ($_POST["sitecode"], 0777); }
      chdir($_POST["sitecode"]);
      if ((!file_exists('contentimage')) || (!is_dir('contentimage'))) { mkdir ('contentimage', 0777); }
      if ((!file_exists('downloads')) || (!is_dir('downloads'))) { mkdir ('downloads', 0777); }
      if ((!file_exists('scripts')) || (!is_dir('scripts'))) { mkdir ('scripts', 0777); }
      if ((!file_exists('themes')) || (!is_dir('themes'))) { mkdir ('themes', 0777); }

      $fp = fopen('index.html', "wb");
      fwrite($fp,'<html>'.chr(10));
      fwrite($fp,'<head>'.chr(10));
      fwrite($fp,'    <title>HTML REDIRECT</title>'.chr(10));
      fwrite($fp,'    <meta HTTP-EQUIV="REFRESH" CONTENT="0; URL=../../index.php?Site='.$_POST["sitecode"].'">'.chr(10));
      fwrite($fp,'</head>'.chr(10).chr(10));
      fwrite($fp,'<body bgcolor="#000000" text="#FFFFFF" link="#FF9900">'.chr(10));
      fwrite($fp,'<p>&nbsp;</p>'.chr(10));
      fwrite($fp,'   <table width="100%" border="0" align="center">'.chr(10));
      fwrite($fp,'       <tr><td align="center">'.chr(10));
      fwrite($fp,'       &nbsp;<br>'.chr(10));
      fwrite($fp,'       You will be automatically redirected to the correct page in a few seconds.... if your browser supports it,<br>'.chr(10));
      fwrite($fp,'       Otherwise, click on the link below.<br>&nbsp;'.chr(10));
      fwrite($fp,'       </td></tr>'.chr(10));
      fwrite($fp,'       <tr><td align="center">'.chr(10));
      fwrite($fp,'       <a href="../../index.php?Site='.$_POST["sitecode"].'">Click here if you are not automatically redirected to the correct page.</a>'.chr(10));
      fwrite($fp,'       </td></tr>'.chr(10));
      fwrite($fp,'   </table>'.chr(10));
      fwrite($fp,'</body>'.chr(10));
      fwrite($fp,'</html>'.chr(10));
      fclose($fp);

      chdir($savedir);


      //  Create the data tables for this site
      //  For each table, we generate a 'create' definition from the existing table,
      //      then simply modify the table name for the new site and execute the script.
      if ($GLOBALS["gsMultiSiteAuthors"] != 'Y') {
         //  AUTHORS
         $tableString = dbTableDef($GLOBALS["eztbAuthors"]);
         $sqlString = tablenamechange($GLOBALS["eztbAuthors"],"authors",$tableString);
         $result = dbExecute($sqlString,true);

         //  Create user records for the creator of this site, and any admin users.
         $sqlString = "INSERT INTO ".$_POST["sitecode"]."authors SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."' OR usergroup='".$GLOBALS["gsAdminPrivGroup"]."'";
         $r = dbExecute($sqlString,true);
      }

      //  SETTINGS
      $tableString = dbTableDef($GLOBALS["eztbSettings"]);
      $sqlString = tablenamechange($GLOBALS["eztbSettings"],"settings",$tableString);
      $result = dbExecute($sqlString,true);
      
      //  Copy all settings from the current master site
      $sqlString = "INSERT INTO ".$_POST["sitecode"]."settings SELECT * FROM ".$GLOBALS["eztbSettings"];
      $r = dbExecute($sqlString,true);
      
      //  MODULES
      $tableString = dbTableDef($GLOBALS["eztbModules"]);
      $sqlString = tablenamechange($GLOBALS["eztbModules"],"modules",$tableString);
      $result = dbExecute($sqlString,true);

      //  Copy all modules from the current master site
      $sqlString = "INSERT INTO ".$_POST["sitecode"]."modules SELECT * FROM ".$GLOBALS["eztbModules"];
      $r = dbExecute($sqlString,true);

      //  THEMES
      $tableString = dbTableDef($GLOBALS["eztbThemes"]);
      $sqlString = tablenamechange($GLOBALS["eztbThemes"],"themes",$tableString);
      $result = dbExecute($sqlString,true);

      //  USERDATA SETTINGS
      $tableString = dbTableDef($GLOBALS["eztbUserdata"]);
      $sqlString = tablenamechange($GLOBALS["eztbUserdata"],"userdata",$tableString);
      $result = dbExecute($sqlString,true);

      //  Copy all userdata settings from the current master site
      $sqlString = "INSERT INTO ".$_POST["sitecode"]."userdata SELECT * FROM ".$GLOBALS["eztbUserdata"];
      $r = dbExecute($sqlString,true);

      //  SPECIALCONTENTS
      $tableString = dbTableDef($GLOBALS["eztbSpecialcontents"]);
      $sqlString = tablenamechange($GLOBALS["eztbSpecialcontents"],"specialcontents",$tableString);
      $result = dbExecute($sqlString,true);

      //  Copy all specialcontent settings from the current master site, and create tables for them
      $sqlString = "INSERT INTO ".$_POST["sitecode"]."specialcontents SELECT * FROM ".$GLOBALS["eztbSpecialcontents"];
      $r = dbExecute($sqlString,true);
      $sqlString = "UPDATE ".$_POST["sitecode"]."specialcontents SET scuseprefix='Y'";
      $r = dbExecute($sqlString,true);

      $sqlString = "SELECT * FROM ".$GLOBALS["eztbSpecialcontents"];
      $result = dbRetrieve($sqlString,true,0,0);
      while ($rs = dbFetch($result)) {
			create_module($rs["scname"],$_POST["sitecode"]);
      }
      dbFreeResult($result);

      //  MODULE SETTINGS
      $tableString = dbTableDef($GLOBALS["eztbModuleSettings"]);
      $sqlString = tablenamechange($GLOBALS["eztbModuleSettings"],"modulesettings",$tableString);
      $result = dbExecute($sqlString,true);
      $sqlString = "INSERT INTO ".$_POST["sitecode"]."modulesettings SELECT * FROM ".$GLOBALS["eztbModuleSettings"];
      $r = dbExecute($sqlString,true);

      //  BANNERS
      $tableString = dbTableDef($GLOBALS["eztbBanners"]);
      $sqlString = tablenamechange($GLOBALS["eztbBanners"],"banners",$tableString);
      $result = dbExecute($sqlString,true);

      //  TOPGROUPS
      $tableString = dbTableDef($GLOBALS["eztbTopgroups"]);
      $sqlString = tablenamechange($GLOBALS["eztbTopgroups"],"topgroups",$tableString);
      $result = dbExecute($sqlString,true);

      //  GROUPS
      $tableString = dbTableDef($GLOBALS["eztbGroups"]);
      $sqlString = tablenamechange($GLOBALS["eztbGroups"],"groups",$tableString);
      $result = dbExecute($sqlString,true);

      //  SUBGROUPS
      $tableString = dbTableDef($GLOBALS["eztbSubgroups"]);
      $sqlString = tablenamechange($GLOBALS["eztbSubgroups"],"subgroups",$tableString);
      $result = dbExecute($sqlString,true);

      //  CONTENTS
      $tableString = dbTableDef($GLOBALS["eztbContents"]);
      $sqlString = tablenamechange($GLOBALS["eztbContents"],"contents",$tableString);
      $result = dbExecute($sqlString,true);

      //  RATINGS
      $tableString = dbTableDef($GLOBALS["eztbRatings"]);
      $sqlString = tablenamechange($GLOBALS["eztbRatings"],"ratings",$tableString);
      $result = dbExecute($sqlString,true);

      $EZ_SESSION_VARS["Site"] = $_POST["sitecode"];
      RebuildStyleSheet();
   }

   dbCommit();
} // function AddSite()


function create_module($Module,$DBPrefix)
{
	$savedir = getcwd();
	chdir($GLOBALS["rootdp"].'modules');

	$Status = True;
	$fp = fopen('./'.$Module.'/install.sql', "r");
	if ($fp) {
		$file = fread($fp, 10485760);
		$file = str_replace("\r", "", $file);
		$query = explode(";\n",$file);
		for ($i=0; $i < count($query) - 1; $i++) {
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
				//if ($GLOBALS["Log"] == 'Y') { dbWriteLog($sqlQuery); }
				$result = dbExecute($sqlQuery,false);
			}
		}
		$fp = fclose($fp);
	}
	chdir($savedir);
} // function create_module()


function bCheckForm()
{
   global $_POST;

   $bFormOK = true;
   if ($_POST["SiteCode"] == '') {
      if ($_POST["sitecode"] == "") {
         $GLOBALS["strErrors"][] = $GLOBALS["eNoCode"];
      } elseif ($_POST["sitecode"] <> urlencode($_POST["sitecode"])) {
         $GLOBALS["strErrors"][] = $GLOBALS["eInvalidCode"];
      } elseif ($_POST["sitecode"] == $GLOBALS["eztbMasterPrefix"]) {
         $GLOBALS["strErrors"][] = $GLOBALS["eMasterCode"];
      } else {
         $strQuery="SELECT * FROM ".$GLOBALS["eztbSites"]." WHERE sitecode='".$_POST["sitecode"]."'";
         $sresult = dbRetrieve($strQuery,true,0,0);
         $sRecCount = dbRowsReturned($sresult);
         dbFreeResult($sresult);
         if ($sRecCount <> 0) { $GLOBALS["strErrors"][] = $GLOBALS["eCodeInUse"];
         } else {
	         $strQuery="SELECT * FROM ".$GLOBALS["eztbThemes"]." WHERE themecode='".$_POST["sitecode"]."'";
	         $sresult = dbRetrieve($strQuery,true,0,0);
	         $sRecCount = dbRowsReturned($sresult);
	         dbFreeResult($sresult);
	         if ($sRecCount <> 0) { $GLOBALS["strErrors"][] = $GLOBALS["eCodeInUse"]; }
         }
      }
   }
   if ($_POST["sitename"] == "") {
      $strMessage .= $GLOBALS["eNoName"].'<br />';
      $bFormOK = false;
   }
   if ($_POST["sitedescription"] == "") {
      $strMessage .= $GLOBALS["eNoDescription"].'<br />';
      $bFormOK = false;
   }

   if (isset($GLOBALS["strErrors"])) { $bFormOK = false; }
   return $bFormOK;
} // function bCheckForm()


function GetGlobalData()
{
   global $EZ_SESSION_VARS, $_GET, $_POST;

   $strQuery="SELECT * FROM ".$GLOBALS["eztbSites"]." WHERE sitecode='".$_GET["SiteCode"]."'";
   $result = dbRetrieve($strQuery,true,0,0);
   $rs     = dbFetch($result);

   $GLOBALS["gsSiteCode"]        = $rs["sitecode"];
   $GLOBALS["gsSiteName"]        = $rs["sitename"];
   $GLOBALS["gsSiteDescription"] = $rs["sitedescription"];
   $GLOBALS["gsSiteEnabled"]     = $rs["siteenabled"];
   dbFreeResult($result);
} // function GetGlobalData()


function GetFormData()
{
   global $_POST, $EZ_SESSION_VARS;

   $GLOBALS["gsSiteCode"]        = $_POST["sitecode"];
   $GLOBALS["gsSiteName"]        = $_POST["sitename"];
   $GLOBALS["gsSiteDescription"] = $_POST["sitedescription"];
   $GLOBALS["gsSiteEnabled"]     = $_POST["siteEnabled"];
} // function GetFormData()


include($GLOBALS["rootdp"]."include/javafuncs.php");

?>
