<?php

/***************************************************************************

 showlinks.php
 ------------------
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

$GLOBALS["ModuleName"] = 'links';

if (!isset($GLOBALS["gsLanguage"])) { Header("Location: ".$GLOBALS["rootdp"]."module.php?link=".$GLOBALS["modules_home"].$GLOBALS["ModuleRef"]."/showlinks.php"); }

include_once ($GLOBALS["admin_home"]."compile.php");

include_once ($GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include_once ($GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_main.php");
include_languagefile ($GLOBALS["modules_home"].$GLOBALS["ModuleRef"]."/",$GLOBALS["gsLanguage"],"lang_links.php");


SubModuleHeader('',$GLOBALS["tSubmitLink"]);


$strQuery = ModuleDataQuery($_POST["catcode"]);

$countres = dbRetrieve($strQuery,true,0,0);
$lRecCount = dbRowsReturned($countres);
dbFreeResult($countres);

$nCurrentPage = 0;
if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
$nPages = intval(($lRecCount - 0.5) / $GLOBALS["scPerPage"]) + 1;
$lStartRec = $nCurrentPage * $GLOBALS["scPerPage"];
if ($nPages > 1) { SubModuleHdFt($nCurrentPage,$nPages);
} else { echo '<br />'; }


$result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["scPerPage"]);


?>
<center>
<?php
if ($lRecCount == 0) { ModuleNoEntries();
} else {
	while ($rsLink = dbFetch($result)) {
		?>
		<table border="0" width="100%" cellspacing="1" cellpadding="3" class="headercontent">
			<tr><td class="tablecontent">
					<?php echo '<a href="'.$rsLink["linkurl"].'" target="_blank">'.htmlspecialchars($rsLink["linkurl"]).'</a>'; ?>
				</td>
			</tr>
			<?php
			if ($rsLink["linkdescr"] != '') {
				?>
				<tr><td class="tablecontent">
						<?php
						$bEncodeHTML = true;
						echo ext_print(compile($GLOBALS["tqBlock1"].'html'.$GLOBALS["tqBlock2"].$rsLink["linkdescr"].$GLOBALS["tqBlock1"].$GLOBALS["tqCloseBlock"].'html'.$GLOBALS["tqBlock2"], 'N', 'N', 'L'),$bEncodeHTML, 'L');
						?>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<br />
		<?php
	}
}
dbFreeResult($result);

?>
</center>
<?php

if ($nPages > 1) {
	SubModuleHdFt($nCurrentPage,$nPages);
}

?>
