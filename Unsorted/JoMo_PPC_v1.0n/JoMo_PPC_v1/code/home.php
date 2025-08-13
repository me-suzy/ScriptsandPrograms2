<?
/*
###############################
#
# JoMo Easy Pay-Per-Click Search Engine v1.0
#
#
###############################
#
# Date                 : September 16, 2002
# supplied by          : CyKuH [WTN]
# nullified by         : CyKuH [WTN]
#
#################
#
# This script is copyright L 2002-2012 by Rodney Hobart (JoMo Media Group),
All Rights Reserved.
#
# The use of this script constitutes acceptance of any terms or conditions,
#
# Conditions:
#  -> Do NOT remove any of the copyright notices in the script.
#  -> This script can not be distributed or resold by anyone else than the
author, unless special permisson is given.
#
# The author is not responsible if this script causes any damage to your
server or computers.
#
#################################

*/
?>
<?

	$tpl->assign("pageTitle", __SITE_TITLE."-index");
	
// statistics
	$tpl->assign("viewStatistics", getOption("publicStatistics"));
	
	$tpl->assign("todaySearches", getTodaySearchCount());
	$tpl->assign("avgDaySearches", getAverageDaySearchCount());
	$tpl->assign("monthSearches", getLastMonthSearchCount());
	
	$tpl->assign("members", getMemberCount());
	$tpl->assign("activeLinks", getActiveLinks());
	
	$tpl->assign("onlineVisitors", getOnlineVisitors());
	
// top searched keywords
	$tpl->assign("viewKeywords", getOption("publicTopKeywords"));
	
	$tpl->assign("keywords", getTopSearchedKeywords(10));
	
	// top bidded listings
	$nTopListings = getOption("nTopListings");
	$dbSet->open("SELECT l.*, u.url as linkURL FROM links l
		INNER JOIN urls u ON l.urlID=u.urlID
		WHERE status=1 AND adminStatus=1 AND accountStatus=1
		ORDER BY bid DESC
		LIMIT $nTopListings");
	$links = array();
	while ($row=$dbSet->fetchArray()){
		$links[]=$row;
	}

	$tpl->assign("results", $links);
    $tpl->assign("target",getOption("openLinkInNewWindow")==0?"_self":"_blank");
	
    //
    $tpl->assign("adminEmail",getOption("adminEmail"));
    
  	$tpl->display("template.home.php");
?>
