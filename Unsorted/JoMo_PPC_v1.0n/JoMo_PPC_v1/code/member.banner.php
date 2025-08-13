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
<?PHP

/**
banner of member
*/

	checkMemberPage();
	
    $sID->assign("memberID",$memberID);
	$sID->assign("mode","members");
	$sID->assign("memberMode","banner");
	        
/**
input parameters:
$cmd
$bannerID
*/
    if (!isset($bannerID) || empty($bannerID)) $bannerID = 0;
    if (!isset($cmd) || empty($cmd))   $cmd="";

	// cancel
    if ($cmd=="cancel"){
    	if (isset($bannerID) && isset($bannerName) && $bannerName=="") delBanner($bannerID);
    	Header("Location: "."index.php?mode=members&memberMode=banners&cmd=");
        include(__CFG_PATH_CODE . "unloader.php");
        exit;
    }

	if (!isset($result)) $result = array();
	
// signup
    if ($cmd=="signupedit") {
    	$error = "";
    	$res = editBanner($result["bannerID"], $result, $error);
    	
    	if ($res==0){
    		$cmd="edit";
    		$msg = $error;
    		$banner = getBanner($result["bannerID"]);
    		foreach ($result as $key=>$val)
    			$banner[$key]=$val;
    	}
    	else{
			$msg = "banner has been updated";
			$cmd="";
			Header("Location: "."index.php?mode=members&memberMode=banners&cmd=&msg=$msg");
			include(__CFG_PATH_CODE . "unloader.php");
			exit;
        }
    }
	else if ($cmd == "signupcreate"){
        	$error = "";
        	$bannerID = insertBanner($result, $error);
        	
        	if ($bannerID==0){
        		$cmd="create";
    			$msg=$error;
    			foreach ($result as $key=>$val)
    				$banner[$key]=$val;
        	}
        	else{
    			$cmd="";
                $msg = "banner was added";
                Header("Location: "."index.php?mode=members&memberMode=banners&cmd=&msg=$msg");
                include(__CFG_PATH_CODE . "unloader.php");
                exit;
	        }
        }

// load page

   // urls
    $dbSet->open("SELECT * FROM urls WHERE memberID=$memberID" );
    $urls = $dbSet->fetchColsAll();
    $tpl->assign("urlIDs",$urls["urlID"]);
    $tpl->assign("urlNames",$urls["url"]);
    $tpl->assign("urlTitles",$urls["title"]);


	// get banner
	
    $banner = getBanner($bannerID);
    if ($cmd == "create")
    	$banner["memberID"]=$memberID;
    	
    if (isset($result))
		foreach ($result as $key=>$val)
			$banner[$key]=$val;
			
    // status
    $tpl->assign("statusMsg",getBannerStatus($bannerID, $memberID));
	
	// per impression
	$tpl->assign("bannerPerImpression",getOption("bannerPerImpression"));
	$tpl->assign("bannerImpressionBid",getOption("bannerImpressionBid"));
	
    $tpl->assign("banner",$banner);
    
    $tpl->assign("cmd",$cmd);
    $tpl->assign("memberID",$memberID);
    
    if (!isset($msg)) $msg="";
    $tpl->assign("msg",$msg); 
    
    $tpl->display("template.member.banner.php");
?>