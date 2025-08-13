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
<?php
/**
count clicks
redirect to advertiser's site
*/

/**
input:
linkID, linkURL, position
affiliateID
*/

 if (!isset($affiliateID) || empty($affiliateID)) $affiliateID=0;

 if (!isset($position)) $position=0;
 
 // SET PPC_USER_ID if need.
	if (empty($HTTP_COOKIE_VARS["PPC_USER_ID"])) {
		$ppc_user_id = md5(generate_password(8));
		setcookie("PPC_USER_ID", $ppc_user_id, mktime(0,0,0,1,1,2010));
	} else {
		$ppc_user_id = $HTTP_COOKIE_VARS["PPC_USER_ID"];
	}

// banner
 if (isset($bannerID) && $bannerID!=0){
	$banner=getBanner($bannerID); 	
	$urlpath=$banner["url"];
 	$memberID = $banner["memberID"];
	$bid = $banner["bid"];
 	
	if ($memberID==0){
 		Header("Location: $urlpath");
 		exit;
 	}
 	// log	
 	if ($banner["isPerImpression"]==0){
 	
	 	if (isClickUnique($bannerID, $affiliateID, $ppc_user_id, "banner"))
	  		changeAccountBalance("member",$memberID, -$bid,"click",0);
	 
 		addToLog($bannerID,$affiliateID, $ppc_user_id,$banner["bid"],"banner",0);
 	}
 	
	if ($banner["isPerImpression"]==0){
	 	Header("Location: $urlpath");
	}
	exit;
 	
 }
 
// link 
 if (!isset($linkID) || empty($linkID)){
  // results from Google
  $linkID=0;
  $urlpath=$linkURL;
  //dprint("link is empty");
  Header("Location: $urlpath");
  exit();
 }

 $urlID = getUrlOfLink($linkID);
 $url = getUrl($urlID);
 $link = getLink($linkID);
 $memberID = $url["memberID"];
 $bid = $link["bid"];
 $urlpath = $url["url"];

 // count clicks

 $cost=-$bid; 
 $affcost=0;
 if ($affiliateID!=0){
 	$affcost = getAffiliateBid($linkID);
 }

 // member account
 if (isClickUnique($linkID, $affiliateID, $ppc_user_id,"link")){
 	changeAccountBalance("member",$memberID, $cost,"click",0);

   if ($affiliateID!=0){
   //dprint("affcost=$affcost");

	   changeAccountBalance("affiliate",$affiliateID, $affcost,"click",0);
	}
  }
 
 // logs, statistics
 addToLog($linkID,0, $ppc_user_id, abs($cost), "link", $position);
 if ($affiliateID!=0)
 	addToLog($linkID,$affiliateID, $ppc_user_id, abs($affcost), "link", $position);
 
 
 
 // redirect to url
 /*
 if (__CFG_IS_DEBUG){
	 Header("Location: http://localhost");
 }
 else
 */ 
	 Header("Location: $urlpath");

?>