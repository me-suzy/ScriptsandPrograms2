<?php

/***************************************************************************

 search.php
 -----------
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

global $_SERVER;
if ( (substr($_SERVER["PHP_SELF"],-11) == 'control.php') ||
	 (substr($_SERVER["PHP_SELF"],-10) == 'module.php') ||
	 (substr($_SERVER["PHP_SELF"],-16) == 'showcontents.php') ) {
	 require_once('../moduleSec.php');
} else {
	require_once('../moduleSec.php');
}

if (!isset($GLOBALS["gsLanguage"])) { Header("Location: ".$GLOBALS["rootdp"]."module.php?link=".$GLOBALS["modules_home"]."search/search.php"); }

include_once ($GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include_once ($GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_main.php");
include_languagefile ($GLOBALS["modules_home"]."search/",$GLOBALS["gsLanguage"],"lang_search.php");


$SAVE_VARS["topgroupname"] = $_GET["topgroupname"];
$SAVE_VARS["groupname"] = $_GET["groupname"];
$SAVE_VARS["subgroupname"] = $_GET["subgroupname"];

define('searchin_title',0);
define('searchin_teaser',1);
define('searchin_content',2);

if (!$_POST["submit"]) {
	// Set a few default values
	$_POST["keywords"] = "";
	if ($GLOBALS["search"]["addterms"] == '') { $GLOBALS["search"]["addterms"] = "any"; }
	$_POST["addterms"] = $GLOBALS["search"]["addterms"];
	$_POST["searchin_array"] = array(searchin_title,searchin_content);
}
$SearchString = stripslashes($_POST["keywords"]);

SubFormHeader('Search');
?>
<table border="1" cellpadding="1" cellspacing="0" align="center" valign="top" width="100%" class="headercontent">
	<tr><td class="header"><?php echo $GLOBALS["module_title"]; ?></td>
	</tr>
	<tr><td class="tablecontent">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td colspan=2 width="50%" align="<?php echo $GLOBALS["right"]; ?>" valign="middle"><b><?php echo $GLOBALS["search_keywords"]; ?>:</b>&nbsp;</td>
					<td colspan=2 width="50%" valign="top"><input type="text" size="42" name="keywords" value="<?php echo htmlspecialchars($SearchString); ?>"></td>
				</tr>
				<tr><td width="40%" align="<?php echo $GLOBALS["right"]; ?>" valign="middle"><b><?php echo $GLOBALS["search_OR"]; ?>:</b>&nbsp;</td>
					<td width="10%" valign="top"><input type="radio" name="addterms" value="any"<?php if ($_POST["addterms"] == "any") { echo " checked"; } ?>></td>
					<td width="40%" align="<?php echo $GLOBALS["right"]; ?>" valign="middle"><b><?php echo $GLOBALS["search_titles"]; ?>:</b>&nbsp;</td>
					<td width="10%" valign="top"><input type="checkbox" name="searchin_array[]" value="<?php echo searchin_title; ?>"<?php if ((isset($_POST["searchin_array"])) && (in_array(searchin_title, $_POST["searchin_array"]))) echo " checked"; ?>></td>
				</tr>
				<tr><td width="40%" align="<?php echo $GLOBALS["right"]; ?>" valign="top"><b><?php echo $GLOBALS["search_AND"]; ?>:</b>&nbsp;</td>
					<td width="10%" valign="top"><input type="radio" name="addterms" value="all"<?php if ($_POST["addterms"] == "all") { echo " checked"; } ?>></td>
					<td width="40%" align="<?php echo $GLOBALS["right"]; ?>" valign="top"><b><?php echo $GLOBALS["search_teasers"]; ?>:</b>&nbsp;</td>
					<td width="10%" valign="top"><input type="checkbox" name="searchin_array[]" value="<?php echo searchin_teaser; ?>"<?php if ((isset($_POST["searchin_array"])) && (in_array(searchin_teaser, $_POST["searchin_array"]))) echo " checked"; ?>></td>
				</tr>
				<tr><td colspan=3 width="90%" align="<?php echo $GLOBALS["right"]; ?>" valign="top"><b><?php echo $GLOBALS["search_contents"]; ?>:</b>&nbsp;</td>
					<td width="10%" valign="top"><input type="checkbox" name="searchin_array[]" value="<?php echo searchin_content; ?>"<?php if ((isset($_POST["searchin_array"])) && (in_array(searchin_content, $_POST["searchin_array"]))) echo " checked"; ?>></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td class="header"><input type="Submit" name="submit" value="<?php echo $GLOBALS["search_button"]; ?>"></td>
	</tr>
</table>
<?php
SubFormFooter();


$isodate = sprintf ("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));
if ($_POST["submit"]) {
	$querytitle = $querycontent = $queryteaser = "";
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE ";

	if (isset($SearchString) && $SearchString != "") {
		//	Parse the input string, separating word strings and sentence strings (delimited by ") into two separate arrays
		preg_match_all('!("(.*)")!iU',$SearchString,$sentences);
		$single_words = preg_split('!("(.*)")!iU',$SearchString);
		foreach($single_words as $text) {
		    if (!trim($text)) continue;
		    $temp = explode(' ',trim($text));
		    if (is_array($temp)) { $Tokens=array_merge($temp,$Tokens); } else { $Tokens[]=trim($temp); }
		}
		//	Merge the sentences and words arrays
		$Tokens=array_merge($sentences[2],$Tokens);
		//	Remove duplicate search entries
		$Tokens=array_unique ($Tokens);
		//	Drop blank entries created by removal of duplicates
		reset ($Tokens);
		while (list ($key, $val) = each ($Tokens)) {
		    $ValidTokens[] = addslashes($val);
		}

		$querytitle .= "(title LIKE '%$ValidTokens[0]%'";
		$querycontent .= "(cbody LIKE '%$ValidTokens[0]%'";
		$queryteaser .= "(cteaser LIKE '%$ValidTokens[0]%'";
		if ($_POST["addterms"] == "any") { $andor = "OR"; } else { $andor = "AND"; }
		$nbrterms = sizeof($ValidTokens);
		for ($i=1; $i<$nbrterms; $i++) {
			$querytitle .= " $andor title LIKE '%$ValidTokens[$i]%'";
			$querycontent .= " $andor cbody LIKE '%$ValidTokens[$i]%'";
			$queryteaser .= " $andor cteaser LIKE '%$ValidTokens[$i]%'";
		}
		$querytitle .= ")";
		$querycontent .= ")";
		$queryteaser .= ")";
	} else {
		?>
		<table border="1" cellpadding="1" cellspacing="0" align="center" valign="top" width="100%" class="headercontent">
			<tr><td class="tablecontent">&nbsp;<br /><?php echo $GLOBALS["eEnterKeywords"]; ?><br />&nbsp;</td></tr>
		</table>
		<?php
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			?>
			</body>
			</html>
			<?php
		} else {
			?>
			</td></tr>
			</table>
			<?php
		}
		exit;
	}

	$whereset = false;
	if (isset($_POST["searchin_array"])) {
		$strQuery .= '(';
		if (in_array(searchin_title, $_POST["searchin_array"])) {
			$strQuery .= "$querytitle";
			$whereset = true;
		}
		if (in_array(searchin_teaser, $_POST["searchin_array"])) {
			if ($whereset) { $strQuery .= " OR "; }
			$strQuery .= $queryteaser;
			$whereset = true;
		}
		if (in_array(searchin_content, $_POST["searchin_array"])) {
			if ($whereset) { $strQuery .= " OR "; }
			$strQuery .= $querycontent;
			$whereset = true;
		}
		$strQuery .= ") AND contentactive='1' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."'";
	}

	if ($whereset == false) {
		?>
		<table border="1" cellpadding="1" cellspacing="0" align="center" valign="top" width="100%" class="headercontent">
			<tr><td class="tablecontent">&nbsp;<br /><?php echo $GLOBALS["eEnterElements"]; ?><br />&nbsp;</td></tr>
		</table>
		<?php
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			?>
			</body>
			</html>
			<?php
		} else {
			?>
			</td></tr>
			</table>
			<?php
		}
		exit;
	}

	if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) { $strQuery .= " AND language='".$GLOBALS["gsLanguage"]."'  AND searchvisible !='' ORDER BY contentname";
	} else {
		$lOrder = '';
		if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
		$strQuery .= " AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."')  AND searchvisible !='' ORDER BY contentname,language".$lOrder;
	}

	?>
	<center>
	<?php
	$result = dbRetrieve($strQuery,true,0,0);
	$results_found = dbRowsReturned($result);
	$bArticleCount = 0;

	if ($results_found != 0) {
		$nContentName = '';
		while ($rsContent = dbFetch($result)) {
			if ($rsContent["contentname"] != $nContentName) {
				if (ModArticleSecurity($nContentName,$rsContent)) {
					ShowArticle($rsContent);
					$bArticleCount++;
				}
				$nContentName = $rsContent["contentname"];
			}
		}
	}

	if ($bArticleCount == 0) {
		?>
		<table border="1" cellpadding="1" cellspacing="0" align="center" valign="top" width="100%" class="headercontent">
			<tr><td class="tablecontent">&nbsp;<br /><?php echo $GLOBALS["eNoResults"]; ?><br />&nbsp;</td></tr>
			</table>
		<?php
	}
	?>
	</center>
	<?php
	dbFreeResult($result);
}

$_GET["topgroupname"] = $SAVE_VARS["topgroupname"];
$_GET["groupname"] = $SAVE_VARS["groupname"];
$_GET["subgroupname"] = $SAVE_VARS["subgroupname"];

?>
