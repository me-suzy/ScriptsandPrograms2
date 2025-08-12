<?php

/***************************************************************************

 m_news.php
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

$GLOBALS["ModuleName"] = 'links';
include("moduleref.php");

$GLOBALS["rootdp"] = '../../';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

include ($GLOBALS["rootdp"]."include/access.php");

$GLOBALS["form"] = 'subcontent';
$GLOBALS["validaccess"] = VerifyAdminLogin();


include ($GLOBALS["rootdp"]."include/settings.php");
include ($GLOBALS["rootdp"]."include/functions.php");
include ($GLOBALS["rootdp"].$GLOBALS["language_home"].$GLOBALS["gsLanguage"]."/lang_admin.php");
include_languagefile ($GLOBALS["modules_home"].$GLOBALS["ModuleRef"].'/',$GLOBALS["gsLanguage"],'lang_links.php');
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminfunctions.php");
include ($GLOBALS["rootdp"].$GLOBALS["admin_home"]."adminbutton.php");
include ($GLOBALS["rootdp"].$GLOBALS["modules_home"]."modfunctions.php");


GetSpecialData($GLOBALS["ModuleRef"]);

frmLinks();

function frmLinks()
{
	global $_GET;

	adminheader();
	admintitle(6,$GLOBALS["tFormTitle"]);

	// Generate image tags for the different images that appear on the page
	adminbuttons($GLOBALS["tViewLink"],$GLOBALS["tAddNewLink"],$GLOBALS["tEditLink"],$GLOBALS["tDeleteLink"]);
	$GLOBALS["iRelease"] = lsimagehtmltag($GLOBALS["icon_home"],'rel_button.gif',$GLOBALS["gsLanguage"],$GLOBALS["tReleaseLink"],0);

	$strQuery = "SELECT linksid FROM ".$GLOBALS["scTable"];
	$result = dbRetrieve($strQuery,true,0,0);
	$lRecCount = dbRowsReturned($result);
	dbFreeResult($result);

	$nCurrentPage = 0;
	if ($_GET["sort"] == '') { $_GET["sort"] = 5; }
	if ($_GET["page"] != "") { $nCurrentPage = $_GET["page"]; }
	$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
	$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

	frmModuleHdFt(6,$nCurrentPage,$nPages);

	?>
	<tr class="teaserheadercontent">
	<?php
		adminlistitem(12,$GLOBALS["tEditDelRel"],'');
		adminlistitem(27,$GLOBALS["tWebURL"],'',1);
		adminlistitem(27,$GLOBALS["tDescription"],'',2);
		adminlistitem(14,$GLOBALS["tPostedBy"],'',3);
		adminlistitem(12,$GLOBALS["tPublishDate"],'',4);
		adminlistitem(8,$GLOBALS["tStatus"],'',5);
	?>
	</tr>
	<?php

	switch ($_GET["sort"]) {
		case '1' : $sort = 'linkurl,publishdate DESC';
                 break;
		case '2' : $sort = 'linkdescr,publishdate DESC';
                 break;
		case '3' : $sort = 'authorid,publishdate DESC';
                 break;
		case '4' : $sort = 'publishdate DESC';
                 break;
		case '5' : $sort = 'activeentry,publishdate DESC';
                 break;
		default  : $sort = 'activeentry,publishdate DESC';
	}
	$strQuery = "SELECT * FROM ".$GLOBALS["scTable"]." ORDER BY ".$sort;
	$result = dbRetrieve($strQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
	while ($rsLink = dbFetch($result)) {
		?>
		<tr class="teasercontent">
			<td align="center" valign="top" class="content">
				<?php admineditcheck('linksform','LinkID',$rsLink["linksid"],$rsLink["authorid"]); ?>
				<?php admindeletecheck('DelEntry','LinkID',$rsLink["linksid"]); ?>&nbsp;
				<?php adminreleasecheck('RelEntry','LinkID',$rsLink["linksid"]); ?>&nbsp;
			</td>
			<td valign="top" class="content"><?php echo $rsLink["linkurl"]; ?></td>
			<td valign="top" class="content"><?php echo $rsLink["linkdescr"]; ?></td>
			<td valign="top" class="content"><?php echo lGetAuthorName($rsLink["authorid"]); ?></td>
			<td valign="top" class="content"><?php echo substr($rsLink["publishdate"], 0, 10); ?></td>
			<td valign="top" class="content"><?php if ($rsLink["activeentry"]== 1) { echo $GLOBALS["tReleased"]; } else { echo $GLOBALS["tPending"]; } ?></td>
		</tr>
		<?
	}

	dbFreeResult($result);

	frmModuleHdFt(6,$nCurrentPage,$nPages);
	frmModuleReturn(6);
	?>
	</table>
	</form>
	</body>
	</html>
	<?php
}

frmModuleJs();

?>

