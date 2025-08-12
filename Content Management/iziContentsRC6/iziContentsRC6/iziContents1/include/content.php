<?php

/***************************************************************************

 content.php
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


function strGetAuthorGlobals($lAuthorID)
{
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE authorid='".$lAuthorID."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs = dbFetch($result);
	$GLOBALS["authorname"]	= $rs["authorname"];
	$GLOBALS["authoremail"]  = $rs["authoremail"];
	$GLOBALS["privateemail"] = $rs["privateemail"];
	dbFreeResult($result);
} // function strGetAuthorGlobals()


function ShowHeader($rsContent)
{
	if ($rsContent["headerimage"] != '') {
		$hdr = imagehtmltag($GLOBALS["image_home"],$rsContent["headerimage"],'',0,'L');
	} else {
		$hdr = '<!-- TITLE "'.$rsContent["contentname"].'" -->'.$rsContent["title"].'<!-- /TITLE "'.$rsContent["contentname"].'" -->';
	}
	return $hdr;
} // function ShowHeader()


function ShowContentHeader($rsContent,$style='')
{
	$cs = 1;
	if ($rsContent["authorvisible"] !='Y' && $rsContent["updatedatevisible"] !='Y' && $rsContent["headervisible"] != 'Y') { $cs = 0; }
	?>
	<table border="0" width="100%" cellspacing="<?php echo $cs; ?>" cellpadding="3">
	<?php
	if ($rsContent["headervisible"] =='Y') {
		?>
		<tr class="<?php echo $style; ?>headercontent"><td class="<?php echo $style; ?>header">
		<?php
		$HEADER = ShowHeader($rsContent);
		echo $HEADER;
		?>
		</td></tr>
		<?php
	}
} // function ShowContentHeader()


function ShowBottomLine($rsContent,$class)
{ global $HTTP_HOST, $REQUEST_URI;
	if ((($GLOBALS["gsAllowRatings"] == 'Y') && ($rsContent["canrate"] == 'Y')) || (($GLOBALS["gsAllowComments"] == 'Y') && ($rsContent["cancomment"] == 'Y')) || 
		($rsContent["printerfriendly"] == 'Y') || ($rsContent["pdfprint"] == 'Y') || ($rsContent["tellfriend"] == 'Y')){
		?>
		<tr><td class="<?php echo $class; ?>" valign="top">
		<p align="<?php echo $GLOBALS["right"]; ?>">
		<?php
		if (($GLOBALS["gsAllowRatings"] == 'Y') && ($rsContent["canrate"] == 'Y')) {
			if ($rsContent["ratingvotes"] != 0) {
				$nRating = round($rsContent["ratingtotal"] / $rsContent["ratingvotes"],2);
				$nRatingStars = floor($nRating);
				if ($nRatingStars > 0) {
					if ($GLOBALS["gsRatingImage1"] != '') {
						$star = imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsRatingImage1"],'',0,'');
						echo str_repeat($star,$nRatingStars).'&nbsp;&nbsp;';
					}
				} elseif ($nRatingStars < 0) {
					if ($GLOBALS["gsRatingImage2"] != '') {
						$star = imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsRatingImage2"],'',0,'');
						echo str_repeat($star,abs($nRatingStars)).'&nbsp;&nbsp;';
					}
				}
				echo $GLOBALS["tRating"].' '.$nRating;
			} else {
				echo $GLOBALS["tUnrated"];
			}
			?>
			&nbsp;&nbsp;<span style="cursor:hand"><a class=small onClick="javascript:window.open('<?php echo BuildLink('rateit.php'); ?>&article=<?php echo $rsContent["contentname"]; ?>', 'Ratings', 'width=580,height=460,status=no,resizable=no,scrollbars=no'); return(false);" <?php
			echo BuildLinkMouseOver($GLOBALS["tRate"]).'>';
			echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsRatingIcon"],$GLOBALS["tRate"],0,''); ?>&nbsp;<?php echo $GLOBALS["tRate"]; ?></a></span>&nbsp;|<?php
		}
		if (($GLOBALS["gsAllowComments"] == 'Y') && ($rsContent["cancomment"] == 'Y')) {
			?>
			&nbsp;&nbsp;<span style="cursor:hand"><a class=small onClick="javascript:window.open('<?php echo BuildLink('comments.php'); ?>&article=<?php echo $rsContent["contentname"]; ?>', 'Comments', 'width=600,height=400,status=no,resizable=no,scrollbars=yes'); return(false);" <?php
			echo BuildLinkMouseOver($GLOBALS["tViewComments"]).'>';
			echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsCommentIcon"],$GLOBALS["tViewComments"],0,''); ?>&nbsp;<?php echo $GLOBALS["tViewComments"]; ?></a></span>&nbsp;|<?php
		}
		if ($rsContent["printerfriendly"] == 'Y') {
			?>
			&nbsp;&nbsp;<span style="cursor:hand"><a class=small onClick="javascript:window.open('<?php echo BuildLink('printer.php'); ?>&article=<?php echo $rsContent["contentname"]; ?>', 'Printer', 'width=580,height=450,status=no,resizable=yes,scrollbars=yes'); return(false);" <?php
			echo BuildLinkMouseOver($GLOBALS["tPrinterFriendly"]).'>';
			echo imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsPrintIcon"],$GLOBALS["tPrinterFriendly"],0,''); ?><?php echo $GLOBALS["tPrinterFriendly"]; ?></a></span>
			<?php
		}
        if($rsContent["pdfprint"] == 'Y'){
            ?>
			&nbsp;&nbsp;<span style="cursor:hand"><a class=small onClick="javascript:window.open('<?= BuildLink('pdf.php'); ?>&article=<?= $rsContent["contentname"]; ?>', 'PDF', 'width=580,height=450,status=no,resizable=yes,scrollbars=yes'); return(false);" 
			<?= BuildLinkMouseOver($GLOBALS["tPDFPrint"]).'>';?>
			<?= imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsPDFIcon"],$GLOBALS["tPDFPrint"],0,''); ?><?= $GLOBALS["tPDFPrint"]; ?></a></span>
			<?php
        }
        if($rsContent["tellfriend"] == 'Y'){
	$url = sprintf("%s%s%s",$REQUEST_URI,"?",$_SERVER['QUERY_STRING']);
	$path = rawurlencode($url);
	 
        	?>
			&nbsp;&nbsp;<span style="cursor:hand"><a class=small onClick="javascript:window.open('<?= BuildLink('tellafriend.php'); ?>&article=<?= $path; ?>', 'Tell-a-Friend', 'width=580,height=450,status=no,resizable=yes,scrollbars=yes'); return(false);" 
			<?= BuildLinkMouseOver($GLOBALS["tTellFriend"]).'>';?>
			<?= imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsTellFriendIcon"],$GLOBALS["tTellFriend"],0,''); ?><?= $GLOBALS["tTellFriend"]; ?></a></span>
			<?php
                }
		?></p></td></tr>
		<?php
	}
} // function ShowBottomLine()


function GetPagedContent($page,$pagecount,$contentpages)
{
	if ($pagecount > 1) {
		if ($page > $pagecount) { $contentpage = $contentpages[$pagecount - 1];
		} else { $contentpage = $contentpages[$page - 1]; }
	} else {
		$contentpage = $contentpages[0];
	}

	return $contentpage;
} // function GetPagedContent()


function ShowContentPaging($page,$pagecount)
{
	global $EZ_SESSION_VARS, $_GET;

	if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
		$pLink = BuildLink('showcontents.php').'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"];
		if ($_GET["contentname"] != '') {
			$pLink = BuildLink('showdetails.php').'&contentname='.$_GET["contentname"];
		}
	} else {
		$pLink = BuildLink('control.php').'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"];
		if ($_GET["contentname"] != '') {
			$pLink = BuildLink('control.php').'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"].'&contentname='.$_GET["contentname"];
		}
	}

	if ($GLOBALS["gsDirection"] == 'rtl') {
		$iFirst = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsLastPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tFirstPage"],0);
		$iPrev  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsNextPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tPrevPage"],0);
		$iNext  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsPrevPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tNextPage"],0);
		$iLast  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsFirstPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tLastPage"],0);
	} else {
		$iFirst = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsFirstPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tFirstPage"],0);
		$iPrev  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsPrevPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tPrevPage"],0);
		$iNext  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsNextPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tNextPage"],0);
		$iLast  = lsimagehtmltag($GLOBALS["image_home"],$GLOBALS["gsLastPageIcon"],$GLOBALS["gsLanguage"],$GLOBALS["tLastPage"],0);
	}

	$prevpage = $page - 1;
	$nextpage = $page + 1;
	if ($page > 1) {
		$firstref = '<a href="'.$pLink.'&page=1" '.BuildLinkMouseOver($GLOBALS["tFirstPage"]).'>'.$iFirst.'</a>';
		$prevref = '<a href="'.$pLink.'&page='.$prevpage.'" '.BuildLinkMouseOver($GLOBALS["tPrevPage"]).'>'.$iPrev.'</a>';
	} else {
		$firstref = $iFirst;
		$prevref = $iPrev;
	}
	if ($page < $pagecount) {
		$nextref = '<a href="'.$pLink.'&page='.$nextpage.'" '.BuildLinkMouseOver($GLOBALS["tNextPage"]).'>'.$iNext.'</a>';
		$lastref  = '<a href="'.$pLink.'&page='.$pagecount.'" '.BuildLinkMouseOver($GLOBALS["tLastPage"]).'>'.$iLast.'</a>';
	} else {
		$nextref = $iNext;
		$lastref = $iLast;
	}

	$paging_display = '<tr><td class="tablecontent" valign="top" align="center">'.$firstref.'&nbsp;'.$prevref.'&nbsp;&nbsp;'.$GLOBALS["tPage"].' '.$page.' '.$GLOBALS["tOf"].' '.$pagecount.'&nbsp;&nbsp;'.$nextref.'&nbsp;'.$lastref.'</td></tr>';
	echo $paging_display;
} // function ShowContentPaging()


function ShowTeaserBody($rsContent)
{
	global $EZ_SESSION_VARS, $_GET;

	$bEncodeHTML = true;
	?><tr><td valign="top" class="teasercontent"><?php
	echo '<!-- CONTENT "'.$rsContent["contentname"].'" -->';
	if ($rsContent["image"] != '') {
		echo imagehtmltag($GLOBALS["image_home"],$rsContent["image"],'',0,$rsContent["imagealign"]);
	}
	echo ext_print($rsContent["cteaser"], $bEncodeHTML, 'L', 'Y');
	echo '<!-- /CONTENT "'.$rsContent["contentname"].'" -->';
	if (trim($rsContent["cbody"]) != '') {
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			?><br /><a class="small" href="<?php echo BuildLink('showdetails.php'); ?>&topgroupname=<?php echo $_GET["topgroupname"]; ?>&groupname=<?php echo $_GET["groupname"]; ?>&subgroupname=<?php echo $_GET["subgroupname"]; ?>&contentname=<?php echo $rsContent["contentname"]; ?>"<?php
		} else {
			?><br /><a class="small" href="<?php echo BuildLink('control.php'); ?>&topgroupname=<?php echo $_GET["topgroupname"]; ?>&groupname=<?php echo $_GET["groupname"]; ?>&subgroupname=<?php echo $_GET["subgroupname"]; ?>&contentname=<?php echo $rsContent["contentname"]; ?>"<?php
		}
		echo BuildLinkMouseOver($GLOBALS["tReadMore"]).'>&raquo; ';
		echo $GLOBALS["tReadMore"]; ?></a><?php
	}
	?></td></tr>
	<?php
	ShowBottomLine($rsContent,"teasercontent");
} // function ShowTeaserBody()


function ShowTeaserBodyRCol($rsContent)
{
	global $EZ_SESSION_VARS, $_GET;

	$bEncodeHTML = true;
	?><tr><td class="rcolcontent" valign="top"><?php
	echo '<!-- CONTENT "'.$rsContent["contentname"].'" -->';
	if ($rsContent["image"] != '') {
		echo imagehtmltag($GLOBALS["image_home"],$rsContent["image"],'',0,$rsContent["imagealign"]);
	}
	echo ext_print($rsContent["cteaser"], $bEncodeHTML, 'R', 'Y');
	echo '<!-- /CONTENT "'.$rsContent["contentname"].'" -->';
	if (trim($rsContent["cbody"]) != '') {
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			?><br /><a class="small" href="<?php echo BuildLink('showdetails.php'); ?>&topgroupname=<?php echo $_GET["topgroupname"]; ?>&groupname=<?php echo $_GET["groupname"]; ?>&subgroupname=<?php echo $_GET["subgroupname"]; ?>&contentname=<?php echo $rsContent["contentname"]; ?>"<?php
		} else {
			?><br /><a class="small" href="<?php echo BuildLink('control.php'); ?>&topgroupname=<?php echo $_GET["topgroupname"]; ?>&groupname=<?php echo $_GET["groupname"]; ?>&subgroupname=<?php echo $_GET["subgroupname"]; ?>&contentname=<?php echo $rsContent["contentname"]; ?>"<?php
		}
		echo BuildLinkMouseOver($GLOBALS["tReadMore"]).'>&raquo; ';
		echo $GLOBALS["tReadMore"]; ?></a><?php
	}
	?></td></tr>
	<?php
	ShowBottomLine($rsContent,"rcolcontent");
} // function ShowTeaserBodyRCol()


function ShowContentBody($rsContent)
{
	global $_GET;

	$bEncodeHTML = true;
	?><tr><td class="tablecontent" valign="top"><?php
	echo '<!-- CONTENT "'.$rsContent["contentname"].'" -->';
	if ($rsContent["imagedetails"] != "") {
		echo imagehtmltag($GLOBALS["image_home"],$rsContent["imagedetails"],'',0,$rsContent["imagedetailsalign"]);
	}

	if ($GLOBALS["gsTeaserWithDetails"] == 'Y') {
		echo '<I>';
		echo ext_print($rsContent["cteaser"], $bEncodeHTML, 'L', 'Y');
		echo '</I><P>';
	}

	$page = $_GET["page"];
	if ($page == '') { $page = 1; }
	$contentpages = explode("[pagebreak]",$rsContent["cbody"]);
	$pagecount = count($contentpages);
	$contentpage = GetPagedContent($page,$pagecount,$contentpages);

	echo ext_print($contentpage, $bEncodeHTML, 'L', 'Y');
	echo '<!-- /CONTENT "'.$rsContent["contentname"].'" -->';
	echo '</td></tr>';

	if ($pagecount > 1) { ShowContentPaging($page,$pagecount); }
	?></td></tr>
	<?php
	ShowBottomLine($rsContent,"tablecontent");
} // function ShowContentBody()


function ShowContentBodyRCol($rsContent)
{
	global $_GET;

	$bEncodeHTML = true;
	?><tr><td class="rcolcontent" valign="top"><?php
	echo '<!-- CONTENT "'.$rsContent["contentname"].'" -->';
	if ($rsContent["imagedetails"] != "") {
		echo imagehtmltag($GLOBALS["image_home"],$rsContent["imagedetails"],'',0,$rsContent["imagedetailsalign"]);
	}

	if ($GLOBALS["gsTeaserWithDetails"] == 'R') {
		echo '<I>';
		echo ext_print($rsContent["cteaser"], $bEncodeHTML, 'L', 'Y');
		echo '</I><P>';
	}

	$page = $_GET["page"];
	if ($page == '') { $page = 1; }
	$contentpages = explode("[pagebreak]",$rsContent["cbody"]);
	$pagecount = count($contentpages);
	$contentpage = GetPagedContent($page,$pagecount,$contentpages);

	echo ext_print($contentpage, $bEncodeHTML, 'R', 'Y');
	echo '<!-- /CONTENT "'.$rsContent["contentname"].'" -->';
	echo '</td></tr>';

	if ($pagecount > 1) { ShowContentPaging($page,$pagecount); }
	?>
	</td></tr>
	<?php
	ShowBottomLine($rsContent,"rcolcontent");
} // function ShowContentBodyRCol()


function ShowFooter($updatedatevisible,$authorvisible,$rc)
{
	if ($updatedatevisible == 'Y') {
		if ($rc["updatedate"] > $rc["publishdate"]) {
			echo $GLOBALS["tArticleUpdatedOn"].': '.FormatDate($rc["updatedate"]);
		} else {
			echo $GLOBALS["tArticlePostedOn"].': '.FormatDate($rc["publishdate"]);
		}
	}
	if ($authorvisible == 'Y') {
		$GLOBALS["authorname"] = '';
		$GLOBALS["authoremail"] = '';
		strGetAuthorGlobals($rc["authorid"]);
		if ($updatedatevisible == 'Y') { echo '&nbsp;'.$GLOBALS["tArticleBy"].' ';
		} else { echo $GLOBALS["tArticlePostedBy"].' '; }
		if ($GLOBALS["privateemail"] != 'Y') {
			?>
			<a href="mailto:<?php echo $GLOBALS["authoremail"]; ?>" class="small"><?php echo $GLOBALS["authorname"]; ?></a>
			<?php
		} else { echo $GLOBALS["authorname"]; }
	}
} // function ShowFooter()


function ShowTeaserFooter($updatedatevisible,$authorvisible,$rc)
{
	if (($updatedatevisible == 'Y') || ($authorvisible == 'Y')) {
		?>
		<tr><td align="<?php echo $GLOBALS["right"]; ?>" class="teasercontentfooter">
			<?php ShowFooter($updatedatevisible,$authorvisible,$rc); ?>
		</td></tr>
		<?php
	}
} // function ShowTeaserFooter()


function ShowContentFooter($updatedatevisible,$authorvisible,$rc)
{
	if (($updatedatevisible == 'Y') || ($authorvisible == 'Y')) {
		?>
		<tr><td align="<?php echo $GLOBALS["right"]; ?>" class="tablecontentfooter">
			<?php ShowFooter($updatedatevisible,$authorvisible,$rc); ?>
		</td></tr>
		<?php
	}
} // function ShowContentFooter()


function ShowTeaser($rsContent)
{
	echo '<a name="'.$rsContent["contentname"].'"></a>';
	ShowContentHeader($rsContent,'teaser');
	ShowTeaserBody($rsContent);
	ShowTeaserFooter($rsContent["updatedatevisible"],$rsContent["authorvisible"],$rsContent);
	?></table><br /><?php
} // function ShowTeaser()


function ShowTeaserRCol($rsContent)
{
	echo '<a name="'.$rsContent["contentname"].'"></a>';
	ShowContentHeader($rsContent,'rcol');
	ShowTeaserBodyRCol($rsContent);
//	ShowTeaserFooterRCol($rsContent["updatedatevisible"],$rsContent["authorvisible"],$rsContent);
	?></table><br /><?php
} // function ShowTeaserRCol()


function ShowContent($rsContent)
{
	echo '<a name="'.$rsContent["contentname"].'"></a>';
	ShowContentHeader($rsContent);
	ShowContentBody($rsContent);
	ShowContentFooter($rsContent["updatedatevisible"],$rsContent["authorvisible"],$rsContent);
	?></table><br /><?php
} // function ShowContent()


function ShowContentRCol($rsContent)
{
	echo '<a name="'.$rsContent["contentname"].'"></a>';
	ShowContentHeader($rsContent,'rcol');
	ShowContentBodyRCol($rsContent);
//	ShowContentFooterRCol($rsContent["updatedatevisible"],$rsContent["authorvisible"],$rsContent);
	?></table><br /><?php
} // function ShowContentRCol()


function ShowArticle($rsContent)
{
	if ($rsContent["cteaser"] != '') {
		ShowTeaser($rsContent);
	} else {
		ShowContent($rsContent);
	}
} // function ShowArticle()


function ShowArticleRCol($rsContent)
{
	if ($rsContent["cteaser"] != '') {
		ShowTeaserRCol($rsContent);
	} else {
		ShowContentRCol($rsContent);
	}
} // function ShowArticleRCol()




function ext_print(&$strToPrint, $bEncodeHTML=True, $chColumn='', $secure='N')
{
	if ($chColumn == 'R') { $strHrefClass = 'class="rightcol" '; }
	if ((!isset($GLOBALS["rootdp"])) || ($GLOBALS["rootdp"] == '')) { $GLOBALS["rootdp"] = './'; }

	$nStartlink = 1;
	$bHTML = false;

	if ($strToPrint != '') {
		$tqBlock1		= $GLOBALS["tqBlock1"];
		$tqBlock2		= $GLOBALS["tqBlock2"];
		$tqCloseBlock	= $GLOBALS["tqCloseBlock"];
		$tqSeparator	= $GLOBALS["tqSeparator"];

		$testTag = 'pagelink';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$strToPrint);
				if ($nCurrent > 0) {
					$sLink = substr($strToPrint, $nStartlink + 10, $nEndlink - $nStartlink - 10);
	
					$Linkdata = explode($tqSeparator,$sLink);
					$sPageName = trim($Linkdata[0]);
					$sText = trim($Linkdata[1]);
					$sStatusText = trim($Linkdata[2]);

					if ($sText == '') { $sText = $sPageName; }
					if ($sStatusText == '') { $sStatusText = $sText; }
					if($GLOBALS["gsUseFrames"] == 'Y') {
						$strToPrint = substr_replace ($strToPrint, '<a '.$strHrefClass.'href="'.BuildLink($rootdp.'showdetails.php').'&contentname='.$sPageName.'" target="contents"'.BuildLinkMouseOver($sStatusText).'>'.$sText.'</a>', $nStartlink, $nEndlink - $nStartlink + 11);
					} else {
						$strToPrint = substr_replace ($strToPrint, '<a '.$strHrefClass.'href="'.BuildLink($rootdp.'control.php').'&contentname='.$sPageName.'"'.BuildLinkMouseOver($sStatusText).'>'.$sText.'</a>', $nStartlink, $nEndlink - $nStartlink + 11);
					}
				}
			}
		} // [pagelink] tag


		$testTag = 'image';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style code conversion added for <image> tag
			// Format: [image]<image_file_ref>, <image template ref>, <caption text>[/image]
			//
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$strToPrint);
				if ($nCurrent > 0) {
					$sLink = substr($strToPrint, $nStartlink + 7, $nEndlink - $nStartlink - 7);

					$Linkdata = explode($tqSeparator,$sLink);
					$sImageRef = trim($Linkdata[0]);
					$sTemplate = trim($Linkdata[1]);
					$sText = trim($Linkdata[2]);

					$tborder = 0;
					$talign = 'left';
					$tbgcolor = $GLOBALS["bgcolor_main"];
					$strQuery = "SELECT * FROM ".$GLOBALS["eztbImageformattemplates"]." WHERE imageformatname='".$sTemplate."'";
					$result = dbRetrieve($strQuery,true,0,0);
					while ($rs = dbFetch($result)) {
						$tborder = $rs["ifborder"];
						$talign = $rs["ifalign"];
						$tbgcolor = $rs["ifbgcolor"];
						if ($talign == 'R') { $talign = "right"; } else { if ($talign == 'C') { $talign = "center"; } else { $talign = "left"; } }
					}
					dbFreeResult($result);
					$imagetag = imagehtmltag($GLOBALS["image_home"],$sImageRef,$sText,0,'');
					$strToPrint = substr_replace ($strToPrint, '<table width="1%" border="'.$tborder.'" align="'.$talign.'" bgcolor="'.$tbgcolor.'"><tr><td><table align="center"><tr><td>'.$imagetag.'</tr></td></table><b>'.$sText.'</b></td></tr></table>', $nStartlink, $nEndlink - $nStartlink + 8);
				}
			}
		} // [image] tag


		$testTag = 'sidebar';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style code conversion added for <sidebar> tag
			// Format: [sidebar]<sidebar template>, <sidebar content>[/sidebar]
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$strToPrint);
				if ($nCurrent > 0) {
					$sLink = substr($strToPrint, $nStartlink + 9, $nEndlink - $nStartlink - 9);

					$Linkdata = explode($tqSeparator,$sLink);
					$sTemplate = trim($Linkdata[0]);
					$sText = trim($Linkdata[1]);

					$tborder = 1;
					$talign = 'left';
					$tbgcolor = $GLOBALS["bgcolor_main"];
					$twidth = '50%';
					$strQuery = "SELECT * FROM ".$GLOBALS["eztbSidebartemplates"]." WHERE sidebarname='".$sTemplate."'";
					$result = dbRetrieve($strQuery,true,0,0);
					while ($rs = dbFetch($result)) {
						$tborder = $rs["sbborder"];
						$talign = $rs["sbalign"];
						$tbgcolor = $rs["sbbgcolor"];
						$twidth = $rs["sbwidth"];
						if ($talign == 'R') { $talign = "right"; } else { if ($talign == 'C') { $talign = "center"; } else { $talign = "left"; } }
					}
					dbFreeResult($result);
					if ($twidth == "100%") {
						$strToPrint = substr_replace ($strToPrint, '<table width="'.$twidth.'" border="'.$tborder.'" bgcolor="'.$tbgcolor.'"><tr><td>'.$sText.'</td></tr></table>', $nStartlink, $nEndlink - $nStartlink + 10);
					} else {
						$strToPrint = substr_replace ($strToPrint, '<table width="'.$twidth.'" border="'.$tborder.'" cellpadding=5 align="'.$talign.'" bgcolor="'.$tbgcolor.'"><tr><td>'.$sText.'</td></tr></table>', $nStartlink, $nEndlink - $nStartlink + 10);
					}
				}
			}
		} // [sidebar] tag


		$testTag = 'sitelist';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [sitelist]
			$nCurrent = 0;
			while (($nCurrent >= 0) && ($nCurrent < strlen ($strToPrint))) {
				testSimpleTag($testTag,$nCurrent,$nStartgroup,$strToPrint);
				if ($nCurrent > 0) {
					$strToPrint = substr_replace ($strToPrint, GetSiteList(), $nStartgroup, 10);
				}
			}
		} // [sitelist] tag


		$testTag = 'sitelink';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [sitelink]
			// Format: [sitelink]<sitecode>, <description>[/sitelink]
			$nCurrent = 0;
			while (($nCurrent >= 0) && ($nCurrent < strlen ($strToPrint))) {
				testOpenCloseTag($testTag,$nCurrent,$nStartgroup,$nEndgroup,$strToPrint);
				if ($nCurrent > 0) {
					$sLink = substr($strToPrint, $nStartgroup + 10, $nEndgroup - $nStartgroup - 10);

					$Linkdata = explode($tqSeparator,$sLink);
					$sitecode = trim($Linkdata[0]);
					$sitedesc = trim($Linkdata[1]);

					$strToPrint = substr_replace ($strToPrint, GetSiteLink($sitecode,$sitedesc), $nStartgroup, $nEndgroup - $nStartgroup + 11);
				}
			}
		} // [sitelink] tag


		$testTag = 'menulink';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [menulink]
			// Format: [menulink]<topmenuname>, <description>[/menulink]
			$nCurrent = 0;
			while (($nCurrent >= 0) && ($nCurrent < strlen ($strToPrint))) {
				testOpenCloseTag($testTag,$nCurrent,$nStartgroup,$nEndgroup,$strToPrint);
				if ($nCurrent > 0) {
					$sLink = substr($strToPrint, $nStartgroup + 10, $nEndgroup - $nStartgroup - 10);

					$Linkdata = explode($tqSeparator,$sLink);
					$menucode = trim($Linkdata[0]);
					$menudesc = trim($Linkdata[1]);

					$strToPrint = substr_replace ($strToPrint, GetMenuLink($menucode,$menudesc), $nStartgroup, $nEndgroup - $nStartgroup + 11);
				}
			}
		} // [menulink] tag


		$testTag = 'grouplist';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [grouplist]
			$nCurrent = 0;
			while (($nCurrent >= 0) && ($nCurrent < strlen ($strToPrint))) {
				testOpenCloseTag($testTag,$nCurrent,$nStartgroup,$nEndgroup,$strToPrint);
				if ($nCurrent > 0) {
					$sLink = substr($strToPrint, $nStartgroup + 11, $nEndgroup - $nStartgroup - 11);
					$strToPrint = substr_replace ($strToPrint, GetGroupList($sLink), $nStartgroup, $nEndgroup - $nStartgroup + 12);
				}
			}
		} // [grouplist] tag


		$testTag = 'contentlist';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [contentlist]
			// Format: [contentlist]<groupname>, <subgroupname>[/contentlist]
			$nCurrent = 0;
			while (($nCurrent >= 0) && ($nCurrent < strlen ($strToPrint))) {
				testOpenCloseTag($testTag,$nCurrent,$nStartgroup,$nEndgroup,$strToPrint);
				if ($nCurrent > 0) {
					$sLink = substr($strToPrint, $nStartgroup + 13, $nEndgroup - $nStartgroup - 13);

					$Linkdata = explode($tqSeparator,$sLink);
					$sGroup = trim($Linkdata[0]);
					$sSubgroup = trim($Linkdata[1]);

					$strToPrint = substr_replace ($strToPrint, GetContentList($sGroup,$sSubgroup), $nStartgroup, $nEndgroup - $nStartgroup + 14);
				}
			}
		} // [contentlist] tag


		$testTag = 'year';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [year]
			$nCurrent = 0;
			while (($nCurrent >= 0) && ($nCurrent < strlen ($strToPrint))) {
				testSimpleTag($testTag,$nCurrent,$nStartgroup,$strToPrint);
				if ($nCurrent > 0) {
					$strToPrint = substr_replace ($strToPrint, strftime("%Y"), $nStartgroup, 6);
				}
			}
		} // [year] tag


		// replace any soft returns with an HTML break.
		// we do this _before_ the file tag, so any linked files must do their own break formatting.
		$strToPrint = str_replace($tqBlock1."br/".$tqBlock2, "", $strToPrint);
//		$strToPrint = str_replace("\n", "<br />", $strToPrint);


		$testTag = 'file';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [file]
			// Format: [file]<filename>[/file]
			$nCurrent = 0;
			while ($nCurrent >= 0) {
				testOpenCloseTag($testTag,$nCurrent,$nStartlink,$nEndlink,$strToPrint);
				if ($nCurrent > 0) {
					$fLink = substr($strToPrint, $nStartlink + 6, $nEndlink - $nStartlink - 6);
					$pretext = substr($strToPrint, 0, $nStartlink);
					$posttext = substr($strToPrint, $nEndlink + 7, strlen ($strToPrint) - $nEndlink);

					$LinkData = explode($tqSeparator,$fLink);
					$nLink = $LinkData[0];
					$LinkParams = count($LinkData);
					if ($LinkParams > 1) {
						$i = 1;
						while ($i < $LinkParams) {
							$var = trim($LinkData[$i]); $i++;
							$val = trim($LinkData[$i]); $i++;
							$_POST[$var] = $val;
						}
					}

					ob_start();
					include(trim($rootdp.$nLink));
					$nFileOutput = ob_get_contents();
					ob_end_clean();

					$strToPrint = $pretext.$nFileOutput.$posttext;
				}
			}
		} // [file] tag


		$testTag = 'include';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [include]
			// Format: [include]<articlename>[/include]
			$nCurrent = 0;
			while (($nCurrent >= 0) && ($nCurrent < strlen ($strToPrint))) {
				testOpenCloseTag($testTag,$nCurrent,$nStartcode,$nEndcode,$strToPrint);
				if ($nCurrent > 0) {
					$pretext = substr($strToPrint, 0, $nStartcode);
					echo $pretext;
					$nText = substr($strToPrint, $nStartcode + 9, $nEndcode - $nStartcode - 9);
					$posttext = substr($strToPrint, $nEndcode + 10, strlen ($strToPrint) - $nEndcode);
					if ($nText != '') {
						if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
							$incQuery = "SELECT cbody FROM ".$GLOBALS["eztbContents"]." WHERE contentname='".$nText."' AND language='".$GLOBALS["gsLanguage"]."'";
						} else {
							$lOrder = '';
							if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
							$incQuery = "SELECT cbody FROM ".$GLOBALS["eztbContents"]." WHERE contentname='".$nText."' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY language".$lOrder;
						}
						$iresult = dbRetrieve($incQuery,true,0,0);
						while ($iContent = dbFetch($iresult)) {
							$itext = trim($iContent["cbody"]);
							if ($itext != '') { echo ext_print($itext, $bEncodeHTML, $chColumn, $secure); }
						}
						dbFreeResult($iresult);
					}
					$strToPrint = $posttext;
				}
			}
		} // [include] tag


		$testTag = 'teaserinclude';
		if ((($secure == 'C') && (in_array($testTag,$GLOBALS["PermittedTags"]))) || ($secure == 'Y')) {
			// Additional ubb-style tag for [teaserinclude]
			// Format: [teaserinclude]<articlename>[/teaserinclude]
			$nCurrent = 0;
			while (($nCurrent >= 0) && ($nCurrent < strlen ($strToPrint))) {
				testOpenCloseTag($testTag,$nCurrent,$nStartcode,$nEndcode,$strToPrint);
				if ($nCurrent > 0) {
					$pretext = substr($strToPrint, 0, $nStartcode);
					echo $pretext;
					$nText = substr($strToPrint, $nStartcode + 15, $nEndcode - $nStartcode - 15);
					$posttext = substr($strToPrint, $nEndcode + 16, strlen ($strToPrint) - $nEndcode);
					if ($nText != '') {
						if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
							$incQuery = "SELECT cteaser FROM ".$GLOBALS["eztbContents"]." WHERE contentname='".$nText."' AND language='".$GLOBALS["gsLanguage"]."'";
						} else {
							$lOrder = '';
							if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
							$incQuery = "SELECT cteaser FROM ".$GLOBALS["eztbContents"]." WHERE contentname='".$nText."' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') ORDER BY language".$lOrder;
						}
						$iresult = dbRetrieve($incQuery,true,0,0);
						while ($iContent = dbFetch($iresult)) {
							$itext = trim($iContent["cteaser"]);
							if ($itext != '') { echo ext_print($itext, $bEncodeHTML, $chColumn, $secure); }
						}
						dbFreeResult($iresult);
					}
					$strToPrint = $posttext;
				}
			}
		} // [teaserinclude] tag


		//	Replace any tagging tags (i.e. [[] and []])
		$strToPrint = str_replace($tqBlock1.$tqBlock1.$tqBlock2, $tqBlock1, $strToPrint);
		$strToPrint = str_replace($tqBlock1.$tqBlock2.$tqBlock2, $tqBlock2, $strToPrint);
	}

	return $strToPrint;
} // function ext_print()


// M. Baker (20th December 2001)
// Additional ubb-style tag for [grouplist]
function GetGroupList($grouplist)
{
	global $EZ_SESSION_VARS, $_GET;

	$returnlist = '';

	// M. Baker (16th January 2002)
	// $rootdp (root data path) defined for processing ext_print from within modules
	//
	if (isset($GLOBALS["rootdp"])) { $rootdp = $GLOBALS["rootdp"];
	} else { $rootdp = './'; };

	if ($grouplist == '') { $groupname = $_GET["groupname"];
	} else { $groupname = $grouplist; }

	// We always list all menu items in the default site language; but if the user language is different we
	//		include any menu items in that language as well, sorted so that the user language items will be
	//		processed first.... then we filter out the default site language items in the 'while loop'.
	if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$groupname."' AND language='".$GLOBALS["gsLanguage"]."' AND submenuvisible='Y' ORDER BY subgrouporderid";
	} else {
		$lOrder = '';
		if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbSubgroups"]." WHERE groupname='".$groupname."' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND submenuvisible='Y' ORDER BY subgrouporderid,language".$lOrder;
	}
	$result = dbRetrieve($strQuery,true,0,0);
	$num_rows = dbRowsReturned($result);
	if ($num_rows > 0) {
		$returnlist .= '<UL>';
		$nSubGroupName = '';
		while ($rs = dbFetch($result)) {
			if ($rs["subgroupname"] != $nSubGroupName) {
				$nSubGroupName = $rs["subgroupname"];
				$subgroupname = $rs["subgroupname"];
				$subgroupdesc = $rs["subgroupdesc"];
				$subgrouplink = $rs["subgrouplink"];
				$hovertitle	= $rs["hovertitle"];
				$openinpage	= $rs["openinpage"];
				$openinpage	= $rs["openinpage"];
				$loginreq			= $rs["loginreq"];
				$usergroups	= $rs["usergroups"];

				$hidden = hiddenmenu($rs["loginreq"],$rs["usergroups"]);
				if (!$hidden) {
					// Fudge to handle [ and ] characters within the subgroup descriptions
					$subgroupdesc = str_replace($GLOBALS["tqBlock2"], $GLOBALS["tqBlock1"].$GLOBALS["tqBlock2"].$GLOBALS["tqBlock2"], $subgroupdesc);

					$subgrouplink = privatemenu($loginreq,$usergroups,$subgrouplink);
					if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
						if ($subgrouplink == '') {
							$returnlist .= '<LI><A HREF="'.BuildLink($rootdp.'showcontents.php').'&groupname='.$groupname.'&subgroupname='.$subgroupname.'"';
						} else {
							$returnlist .= '<LI><A HREF="'.BuildLink($rootdp.'module.php').'&link='.$subgrouplink.'"';
						}
					} else {
						if ($subgrouplink == "") {
							$returnlist .= '<LI><B><A HREF="'.BuildLink($rootdp.'control.php').'&groupname='.$groupname.'&subgroupname='.$subgroupname.'"';
						} else {
							$returnlist .= '<LI><B><A HREF="'.BuildLink($rootdp.'control.php').'&link='.$subgrouplink.'&groupname='.$groupname.'&subgroupname='.$subgroupname.'"';
						}
					}
					$returnlist .= BuildLinkMouseOver($subgroupdesc).'>'.$subgroupdesc.'</A>';
					if ($loginreq == 'Y') {
						$returnlist .= '&nbsp;&nbsp;'.imagehtmltag($GLOBALS["image_home"],$GLOBALS["gsSecureIcon"],$GLOBALS["tPrivateOption"],0,'');
					}
					if ($hovertitle != '') { $returnlist .= '<br />'.$hovertitle; }
					$returnlist .= '</LI>';
				}
			}
		}
		$returnlist .= '</UL>';
	}
	dbFreeResult($result);
	return $returnlist;
} // function GetGroupList()


function GetContentList($grouplist,$subgrouplist)
{
	global $EZ_SESSION_VARS, $_GET;

	$returnlist = '';

	if (isset($GLOBALS["rootdp"])) { $rootdp = $GLOBALS["rootdp"];
	} else { $rootdp = './'; };

	if ($grouplist == "") { $groupname = $_GET["groupname"];
	} else { $groupname = $grouplist; }
	if ($subgrouplist == "") { $subgroupname = $_GET["subgroupname"];
	} else { $subgroupname = $subgrouplist; }
	$isodate = sprintf("%04d-%02d-%02d", strftime("%Y"), strftime("%m"), strftime("%d"));

	GetOrderByText($groupname,$subgroupname);
	// We always list all menu items in the default site language; but if the user language is different we
	//		include any menu items in that language as well, sorted so that the user language items will be
	//		processed first.... then we filter out the default site language items in the 'while loop'.
	if ($GLOBALS["gsLanguage"] == $GLOBALS["gsDefault_language"]) {
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE groupname='".$groupname."' AND subgroupname='".$subgroupname."' AND language='".$GLOBALS["gsLanguage"]."' AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' ORDER BY orderid";
	} else {
		$lOrder = '';
		if ($GLOBALS["gsLanguage"] > $GLOBALS["gsDefault_language"]) { $lOrder = ' DESC'; }
		$strQuery = "SELECT * FROM ".$GLOBALS["eztbContents"]." WHERE groupname='".$groupname."' AND subgroupname='".$subgroupname."' AND (language='".$GLOBALS["gsLanguage"]."' OR language='".$GLOBALS["gsDefault_language"]."') AND publishdate<='".$isodate."' AND expiredate>='".$isodate."' ORDER BY orderid,language".$lOrder;
	}

	$result = dbRetrieve($strQuery,true,0,0);
	$num_rows = dbRowsReturned($result);
	$count = 0;
	if ($num_rows > 0) {
		while ($rs = dbFetch($result)) {
			if ((!strstr($rs["cteaser"],$GLOBALS["tqBlock1"].'contentlist'.$GLOBALS["tqBlock2"])) &&
					(!strstr($rs["cbody"],$GLOBALS["tqBlock1"].'contentlist'.$GLOBALS["tqBlock2"]))) {
				if ($count == 0) { $returnlist .= '<UL>'; }
				$ccontentname = $rs["contentname"];
				$ctitle			= $rs["title"];
				$cteaser		= ext_print($rs["cteaser"]);

				// Fudge to handle [ and ] characters within the content title
				$ctitle = str_replace($GLOBALS["tqBlock2"], $GLOBALS["tqBlock1"].$GLOBALS["tqBlock2"].$GLOBALS["tqBlock2"], $ctitle);

				$returnlist  .= '<LI><A HREF="#'.$ccontentname.'" '.BuildLinkMouseOver($ctitle).'"><B>'.$ctitle.'</B></A>';
				if ($cteaser != '') { $returnlist .= '<br />'.$cteaser; }
				$returnlist .= '</LI>';
				$count++;
			}
		}
		if ($count > 0) { $returnlist .= '</UL>'; }
	}
	dbFreeResult($result);
	return $returnlist;
} // function GetContentList()


function GetSiteList()
{
	global $EZ_SESSION_VARS;

	$returnlist = '';

	if ($EZ_SESSION_VARS["Site"] != '') {
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			$returnlist  .= '<LI><A HREF="'.BuildLink('selectsite.php').'&Site=" target="_top" '.BuildLinkMouseOver($GLOBALS["tMasterSite"]).'"><B>'.$GLOBALS["tMasterSite"].'</B></A>';
		} else {
			$returnlist  .= '<LI><A HREF="'.BuildLink('selectsite.php').'&Site="'.BuildLinkMouseOver($GLOBALS["tMasterSite"]).'"><B>'.$GLOBALS["tMasterSite"].'</B></A>';
		}
		$count = 1;
	} else { $count = 0; }
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbSites"]." WHERE siteenabled='1' ORDER BY sitename";
	$result = dbRetrieve($strQuery,true,0,0);
	$num_rows = dbRowsReturned($result);
	if ($num_rows > 0) {
		while ($rs = dbFetch($result)) {
			$ssitecode = $rs["sitecode"];
			if ($EZ_SESSION_VARS["Site"] != $ssitecode) {
				$ssitename = $rs["sitename"];
				$ssitedesc = $rs["sitedescription"];
				if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
					$returnlist  .= '<LI><A HREF="'.BuildLink('selectsite.php').'&Site='.$ssitecode.'" target="_top" '.BuildLinkMouseOver($ssitename).'"><B>'.$ssitename.'</B></A>';
				} else {
					$returnlist  .= '<LI><A HREF="'.BuildLink('selectsite.php').'&Site='.$ssitecode.'" '.BuildLinkMouseOver($ssitename).'"><B>'.$ssitename.'</B></A>';
				}
				$returnlist .= '<br />'.$ssitedesc;
				$returnlist .= '</LI>';
				$count++;
			}
		}
		if ($count > 0) { $returnlist = '<UL>'.$returnlist.'</UL>'; }
	}
	dbFreeResult($result);
	return $returnlist;
} // function GetSiteList()


function GetSiteLink($sitelink,$description)
{
	global $EZ_SESSION_VARS;

	$returnlink = '';

	$strQuery = "SELECT * FROM ".$GLOBALS["eztbSites"]." WHERE siteenabled='1' AND sitecode='".$sitelink."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$num_rows = dbRowsReturned($result);
	if ($num_rows > 0) {
		$rs = dbFetch($result);
		$ssitecode = $rs["sitecode"];
		$ssitename = $rs["sitename"];
		$ssitedesc = $rs["sitedescription"];
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			$returnlink = '<A HREF="'.BuildLink('selectsite.php').'&Site='.$ssitecode.'" target="_top" '.BuildLinkMouseOver($ssitename).'">';
		} else {
			$returnlink = '<A HREF="'.BuildLink('selectsite.php').'&Site='.$ssitecode.'" '.BuildLinkMouseOver($ssitename).'">';
		}
		if ($description != '') { $returnlink .= $description; }
		else { $returnlink .= $ssitedesc; }
		$returnlink .= '</A>';
		$count++;
	}
	else { $returnlink = $description; }
	dbFreeResult($result);
	return $returnlink;
} // function GetSiteLink()


function GetMenuLink($menulink,$description)
{
	global $EZ_SESSION_VARS;

	$returnlink = '';

	$strQuery = "SELECT * FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$menulink."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$num_rows = dbRowsReturned($result);
	if ($num_rows > 0) {
		$rs = dbFetch($result);
		$smenudesc = $rs["topgroupdesc"];
		if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
			$returnlink = '<A HREF="javascript:ChangeFrames(\''.$menulink.'\')" title="'.$smenudesc.'" '.BuildLinkMouseOver($smenudesc).'>';
		} else {
			$returnlink = '<A HREF="'.BuildLink('control.php').'&topgroupname='.$menulink.'" title="'.$smenudesc.'" '.BuildLinkMouseOver($smenudesc).'>';
		}
		if ($description != '') { $returnlink .= $description; }
		else { $returnlink .= $smenudesc; }
		$returnlink .= '</A>';
		$count++;
	}
	else { $returnlink = $description; }
	dbFreeResult($result);
	return $returnlink;
} // function GetMenuLink()


function BreadCrumb()
{
	global $_SERVER, $_GET, $EZ_SESSION_VARS;

	if ($GLOBALS["gsBreadcrumb"] == 'Y') {
		echo '<td align="'.$GLOBALS["left"].'" valign="top">';
		if ($GLOBALS["gsShowTopMenu"] == 'Y') {
			$gname = $gmodule = '';
			$strQuery = "SELECT topgroupdesc,topgrouplink,topmenuvisible FROM ".$GLOBALS["eztbTopgroups"]." WHERE topgroupname='".$_GET["topgroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY topgrouporderid";
			$result = dbRetrieve($strQuery,true,0,1);
			if ($rs = dbFetch($result)) {
				$gname		= $rs["topgroupdesc"];
				$gmodule	= $rs["topgrouplink"];
				$tgvisible	= $rs["topmenuvisible"];
			}
			dbFreeResult($result);
			if ($tgvisible == 'Y') {
				if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
					$breadcrumbref = 'showcontents.php';
				} else { $breadcrumbref = 'control.php'; }
				if ($gmodule == '') {
					echo '<a class=small href="'.BuildLink($breadcrumbref).'&topgroupname='.$_GET["topgroupname"].'" '.BuildLinkMouseOver($gname).'>';
				} else {
					echo '<a class=small href="'.$_SERVER["REQUEST_URI"].'" '.BuildLinkMouseOver($gname).'>';
				}
				echo $gname.'</a>';
			}
		}

		if ($_GET["groupname"] != '') {
			$gname = $gmodule = '';
			$strQuery = "SELECT groupdesc,grouplink,menuvisible FROM ".$GLOBALS["eztbGroups"]." WHERE groupname='".$_GET["groupname"]."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY grouporderid";
			$result = dbRetrieve($strQuery,true,0,1);
			if ($rs = dbFetch($result)) {
				$gname		= $rs["groupdesc"];
				$gmodule	= $rs["grouplink"];
				$gvisible	= $rs["menuvisible"];
			}
			dbFreeResult($result);
			if ($gvisible == 'Y') {
				if (($GLOBALS["gsShowTopMenu"] == 'Y') && ($tgvisible == 'Y')) { echo $GLOBALS["gnBreadcrumbSeparator"]; }
				if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
					$breadcrumbref = 'showcontents.php';
				} else { $breadcrumbref = 'control.php'; }
				if ($gmodule == '') {
					if ($GLOBALS["gsShowTopMenu"] == 'Y') {
						echo '<a class=small href="'.BuildLink($breadcrumbref).'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'" '.BuildLinkMouseOver($gname).'>';
					} else {
						echo '<a class=small href="'.BuildLink($breadcrumbref).'&groupname='.$_GET["groupname"].'" '.BuildLinkMouseOver($gname).'>';
					}
				} else {
					echo '<a class=small href="'.$_SERVER["REQUEST_URI"].'" '.BuildLinkMouseOver($gname).'>';
				}
				echo $gname.'</a>';
			}
		}

		if ($_GET["subgroupname"] != '') {
			$gname = $gmodule = '';
			$strQuery = "SELECT subgroupdesc,subgrouplink,submenuvisible FROM ".$GLOBALS["eztbSubgroups"]." WHERE subgroupname='".$_GET["subgroupname"]."' AND language='".$GLOBALS["gsDefault_language"]."' ORDER BY subgrouporderid";
			$result = dbRetrieve($strQuery,true,0,1);
			if ($rs = dbFetch($result)) {
				$gname		= $rs["subgroupdesc"];
				$gmodule	= $rs["subgrouplink"];
				$sgvisible	= $rs["submenuvisible"];
			}
			dbFreeResult($result);
			if (($GLOBALS["gsUseFrames"] == 'Y') && ($EZ_SESSION_VARS["noframesbrowser"] != True)) {
				$breadcrumbref = 'showcontents.php';
			} else { $breadcrumbref = 'control.php'; }
			if ($sgvisible == 'Y') {
				if ((($GLOBALS["gsShowTopMenu"] == 'Y') && ($tgvisible == 'Y') && ($gvisible != 'Y')) || ($gvisible == 'Y')) { echo $GLOBALS["gnBreadcrumbSeparator"]; }
				if ($gmodule == '') {
					if ($GLOBALS["gsShowTopMenu"] == 'Y') {
						echo '<a class=small href="'.BuildLink($breadcrumbref).'&topgroupname='.$_GET["topgroupname"].'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"].'" '.BuildLinkMouseOver($gname).'>';
					} else {
						echo '<a class=small href="'.BuildLink($breadcrumbref).'&groupname='.$_GET["groupname"].'&subgroupname='.$_GET["subgroupname"].'" '.BuildLinkMouseOver($gname).'>';
					}
				} else {
					echo '<a class=small href="'.$_SERVER["REQUEST_URI"].'" '.BuildLinkMouseOver($gname).'>';
				}
				echo $gname.'</a>';
			}
		}
		echo '</td>';
	}
	return $gname;
}  // function BreadCrumb()


function BookMark($gname)
{
	global $_SERVER;

	if ($GLOBALS["gsBookmark"] == 'Y') {
		echo '<td align="'.$GLOBALS["right"].'" valign="top">';
		if ($GLOBALS["gsSecureServer"] == 'Y') { $bref = 'https:'; } else { $bref = 'http:'; }
		$bref .= '//'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].'/'.$_SERVER["REQUEST_URI"];
		$bref = str_replace("ezSID=".$GLOBALS["ezSID"]."&","", $bref);
		echo '<SCRIPT LANGUAGE="JavaScript" type="text/javascript">';
		echo 'var url = "'.$bref.'";';
		echo 'var txt = "'.$GLOBALS["tBookmarkPage"].'";';
		echo 'var who = "'.$GLOBALS["gsSitetitle"].' - '.$gname.'";';
		echo 'var ver = navigator.appName;';
		echo 'var num = parseInt(navigator.appVersion);';
		echo 'if ((ver == "Microsoft Internet Explorer")&&(num >= 4)) {';
		echo 'document.write(\'<A class=small HREF="javascript:window.external.AddFavorite(url,who);" \');';
		echo 'document.write(\'onMouseOver=" window.status=\');';
		echo 'document.write("txt; return true ");';
		echo 'document.write(\'"onMouseOut=" window.status=\');';
		echo 'document.write("\' \'; return true ");';
		echo 'document.write(\'">\'+ txt + \'</a>\');';
		echo '}else{';
		echo 'txt += "  (Ctrl+D)";';
		echo 'document.write(txt);';
		echo '}';
		echo '</script>';
		echo '</td>';
	}
}  // function BookMark();


function ContentPageHeader()
{
	global $_GET;

	if (($_GET["link"] != 'loginreq.php') && ($_GET["link"] != 'loginreq2.php')) {
		if (($GLOBALS["gsBreadcrumb"] == 'Y') || ($GLOBALS["gsBookmark"] == 'Y')) {
			?>
			<table border="0" cellspacing="4" cellpadding="0" width="100%">
			<tr>
			<?php
			$gname = BreadCrumb();
			BookMark($gname);
			?>
			</tr>
			</table>
			<?php
		}
	}
}  // function ContentPageHeader()
?>
