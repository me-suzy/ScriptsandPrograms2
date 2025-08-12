<?php

/***************************************************************************

 banners.php
 ------------
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


function ShowHeaderBanner()
{
	if ($GLOBALS["gsShowBanners"] == "U" || $GLOBALS["gsShowBanners"] == "2") {
		echo '</tr><tr>';
		if (($GLOBALS["gsHomepageLogo"] != '') && ($GLOBALS["gsTopHtml"] != '')) {
			echo '<td colspan="2" valign="middle">';
		} else {
			echo '<td valign="middle">';
		}
		?>
		<table width="100%"><tr><td align="center">
		<?php
		if (GetRandomBanner($bHasImage,$sBannerID,$sBannerImage,$sBannerAlt)) {
			if ($bHasImage) {
				?><a href="<?php echo BuildLink('bannerclick.php'); ?>&id=<?php echo $sBannerID; ?>" target="_blank" <?php echo BuildLinkMouseOver($sBannerAlt).'>'.imagehtmltag($GLOBALS["image_home"],$sBannerImage,$sBannerAlt,0,''); ?></a><?php
			} else { echo $sBannerImage; }
		}
		?>
		</td></tr></table>
		</td>
		<?php
	}
} // function ShowHeaderBanner()


function ShowFooterBanner()
{
	if ($GLOBALS["gsShowBanners"] == "D" || $GLOBALS["gsShowBanners"] == "2") {
		?>
		<tr><td height="100%" valign="bottom">
		<table width="100%"><tr><td align="center" valign="bottom">
		<?php
		if (GetRandomBanner($bHasImage,$sBannerID,$sBannerImage,$sBannerAlt)) {
			if ($bHasImage) {
				?><a href="<?php echo BuildLink('bannerclick.php'); ?>&id=<?php echo $sBannerID; ?>" target="_blank" <?php echo BuildLinkMouseOver($sBannerAlt).'>'.imagehtmltag($GLOBALS["image_home"],$sBannerImage,$sBannerAlt,0,''); ?></a><?php
			} else { echo $sBannerImage; }
		}
		?>
		</td></tr></table>
		</td></tr>
		<?php
	}
} // function ShowFooterBanner()


function GetRandomBanner(&$bHasImage,&$sBannerID,&$sBannerImage,&$sBannerAlt)
{
	$isodate = sprintf ("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));

	$strQuery = "SELECT * FROM ".$GLOBALS["eztbBanners"]." WHERE publishdate<='".$isodate."' AND expiredate>='".$isodate."' AND banneractive='Y'";
	$result = dbRetrieve($strQuery,true,0,0);

	while ($rs = dbFetch($result)) {
		$i++;
		$nBanners[$i]["sBannerID"] = $rs["bannerid"];
		if ($rs["bannerimage"] != '') {
			$nBanners[$i]["sBannerImage"] = $rs["bannerimage"];
			$nBanners[$i]["bHasImage"] = true;
		} else {
			$nBanners[$i]["sBannerImage"] = $rs["bannerhtml"];
			$nBanners[$i]["bHasImage"] = false;
		}
		$nBanners[$i]["sBannerAlt"] = $rs["banneralt"];
		$nBanners[$i]["nImpressionCount"] = $rs["impressions"];
	}
	dbFreeResult($result);

	if ($i == 0) return false;

	$rand_array = array_rand ($nBanners);

	$sBannerID    = $nBanners[$rand_array]["sBannerID"];
	$sBannerImage = $nBanners[$rand_array]["sBannerImage"];
	$sBannerAlt   = $nBanners[$rand_array]["sBannerAlt"];
	$bHasImage    = $nBanners[$rand_array]["bHasImage"];

	UpdateImpression($GLOBALS["sBannerID"]);

	return true;
} // function GetRandomBanner()


function UpdateImpression($nBannerID)
{
	$strQuery = "UPDATE ".$GLOBALS["eztbBanners"]." SET impressions=impressions+1 WHERE bannerid='".$nBannerID."'";
	$result = dbExecute($strQuery,true,0,0);
	dbCommit();
} // function UpdateImpression()

?>
