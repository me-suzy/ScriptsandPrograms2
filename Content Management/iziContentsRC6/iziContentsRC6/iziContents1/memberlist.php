<?php

/***************************************************************************

 memberlist.php
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
$GLOBALS["rootdp"] = './';
require_once ($GLOBALS["rootdp"]."include/config.php");
require_once ($GLOBALS["rootdp"]."include/db.php");
require_once ($GLOBALS["rootdp"]."include/session.php");

if (($GLOBALS["gsUseFrames"] == '') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	include ($GLOBALS["rootdp"]."include/settings.php");
	include ($GLOBALS["rootdp"]."include/functions.php");
	include ($GLOBALS["rootdp"]."include/banners.php");
	includeLanguageFiles('admin','main');
}
includeLanguageFiles('preferences');

if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	HTMLHeader('memberlist');
	StyleSheet();

	if ($GLOBALS["gsShowTopMenu"] == 'Y') {
		if (!isset($_GET["groupname"])) {
			if (!isset($_GET["topgroupname"])) {
				$_GET["topgroupname"] = $GLOBALS["gsHomepageTopGroup"];
			}
			$_GET["groupname"] = GetGroupName($_GET["topgroupname"]);
		}
	}

	?>
	</head>
	<body marginwidth="0" marginheight="0" leftmargin="5" rightmargin="5" topmargin="10" class="mainback">
	<?php
}
?>
<table border="0" cellspacing="5" cellpadding="0" width="100%">
	<tr class="headercontent">
		<td colspan="4" class="header"><?php echo $GLOBALS["tMemberList"] ?></td>
	</tr>

	<tr><td>
		<?php


		$strQuery = "SELECT authorid FROM ".$GLOBALS["eztbAuthors"];
		$result = dbRetrieve($strQuery,true,0,0);
		$lRecCount = dbRowsReturned($result);
		dbFreeResult($result);

		$nCurrentPage = 0;
		if ($_GET["page"] != '') {
			$nCurrentPage = $_GET["page"];
		}
		$nPages = intval(($lRecCount - 0.5) / $GLOBALS["RECORDS_PER_PAGE"]) + 1;
		$lStartRec = $nCurrentPage * $GLOBALS["RECORDS_PER_PAGE"];

		?>
		<table border="0" cellspacing="0" cellpadding="3" width="100%" class="headercontent">
			<tr class="headercontent">
				<td width="30%">
					<b><?php echo $GLOBALS["tAuthorname"]; ?></b>
				</td>
				<td width="45%">
					<b><?php echo $GLOBALS["tEMail"]; ?></b>
				</td>
				<td width="5%">
					<b>&nbsp;</b>
				</td>
				<td width="20%" colspan="2">
					<b><?php echo $GLOBALS["tCountry"]; ?></b>
				</td>
			</tr>
			<?php
			$sqlQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." a LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=a.countrycode ORDER BY a.authorname";
			$result = dbRetrieve($sqlQuery,true,$lStartRec,$GLOBALS["RECORDS_PER_PAGE"]);
			$count == 0;
			while ($rs = dbFetch($result)) {
				$count++;
				if ($count % 2) {
					?><tr class="tablecontent"><?php
				} else {
					?><tr class="teasercontent"><?php
				}
				?>
				<td valign="top">
					<?php echo $rs["authorname"]; ?>
				</td>
				<td valign="top">
					<?php if ($rs["privateemail"] == 'Y') { echo $GLOBALS["tPrivateEMail"]; } else { echo $rs["authoremail"]; } ?>
				</td>
				<td align="center" valign="middle">
					<?php if ($rs["flag"] != '') { echo imagehtmltag($GLOBALS["icon_home"].'flags/',$rs["flag"].'_small.gif',$rs["countryname"],0,''); }  else { echo '&nbsp'; } ?>
				</td>
				<td valign="top">
					<?php if ($rs["countryname"] != '') { echo $rs["countryname"]; } else { echo '&nbsp'; } ?>
				</td>
				</tr>
				<?php
			}
			dbFreeResult($result);
			pagedHdFtSite('memberlist',4,$nCurrentPage,$nPages);
			?>
		</table>
		<?php


		ShowFooterBanner();

		?>
	</td></tr>
</table>
<?php

if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
	?>
	</body>
	</html>
	<?php
}


function GetGroupName()
{
	$gname = '';
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbGroups"]." WHERE topgroupname='".$GLOBALS["topgroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
	$result = dbRetrieve($strQuery,true,0,1);
	if ($rs = dbFetch($result)) { $gname = $rs["groupname"]; }
	dbFreeResult($result);
	return $gname;
} // function GetGroupName()

?>
