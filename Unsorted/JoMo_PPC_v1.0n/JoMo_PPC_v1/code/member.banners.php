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
	
	checkMemberPage();
	
	$tpl->assign("pageTitle", __SITE_TITLE."- banners");
	
	$sID->assign("mode","members");
	$sID->assign("memberMode","banners");
/**
$cmd=view,upload,delete
$userfile - the uploading file
*/

    if (!isset($cmd) || empty($cmd))  $cmd="";
    if (!isset($page) || empty($page)) $page=1;
        
    // delete image
    if ($cmd=="delete"){
            delBanner($bannerID);
            $cmd="";
    }

    // upload file
    if ($cmd=="upload"){

         $error = "";
         $res = insertBanner(array("memberID"=>$memberID, "url"=>$url), $error);
         if ($res==0){
         	$msg = $error;
         	$cmd = "";
         }
            $cmd="";
            unset($bannerID);
    }

    if ($cmd=="view"){
            if (!isset($bannerID) || $bannerID==0){
                    $cmd="";
            }
            else{
                    $path=__CFG_PATH_BANNERS;
                    $dbSet->open("SELECT * FROM banners WHERE bannerID=$bannerID");
                    $banner=$dbSet->fetchArray();
                    $banner["name"]=pathToName($banner["path"]);
                    $tpl->assign("banner",$banner);
            }
    }

	// per impression
	$tpl->assign("bannerPerImpression",getOption("bannerPerImpression"));
	$tpl->assign("bannerImpressionBid",getOption("bannerImpressionBid"));

    // member
    $dbSet->open("SELECT * FROM members WHERE memberID=$memberID");
    $member = $dbSet->fetchArray();
    $tpl->assign("member",$member);
     
    // filter
    $where = "1=1 ";
    
    // count
    $start=($page-1)*$linksPerPage;
    $dbSet->open("SELECT COUNT(*) as n
          FROM banners WHERE memberID=$memberID AND ".$where);
    $row=$dbSet->fetchArray();
    $nItems = $row["n"];

	// orderby
    if (!isset($orderby)) $orderby="bannerID";
    if (!isset($orderdir)) $orderdir=" ASC ";		
    $tpl->assign("orderby",$orderby);
    $tpl->assign("orderdir",$orderdir);
    
    // banners
    $banners=array();
    $dbSet->open("SELECT bannerID, url, keywords, 
	    status, adminStatus, accountStatus, isCatchAll, isPerImpression,
	    bid, path, memberID,
        DATE_FORMAT(creationDate,'%Y-%m-%d') as creationDate,
        DATE_FORMAT(modificationDate,'%Y-%m-%d') as modificationDate
        FROM banners WHERE memberID=$memberID ".
        "ORDER BY ".$orderby." ".$orderdir." ".
	    " LIMIT $start, ".$linksPerPage );
        
      $i=0;
      while ($row=$dbSet->fetchArray()){
                $banners[$i]=$row;
                //path
				$banners[$i]["name"]=pathToName($banners[$i]["path"]);
				
                if (!empty($row["path"])){
                	$path = $row["path"];
                	if (!file_exists($path)){
                		$banners[$i]["size"]=0;
                		//$banners[$i]["name"]="file not exists";
                	}
                	else
                		$banners[$i]["size"]=filesize($row["path"]);
                }
  				
  				// activity
  				$banners[$i]["activity"]=getBannerStatus($row["bannerID"]);  
  				
                //catch all
                if ($banners[$i]["isCatchAll"]==1) $banners[$i]["keywords"]="[catch all]";

                // next
                $i++;
      }

        $tpl->assign("cmd",$cmd);
        $tpl->assign("banners",$banners);

        $tpl->assign("items",$items);
		$tpl->assign("nItems",$nItems);		
        
   		// calc pages
		$pages=array(); $prev=$next=0;
		calcPages($nItems,$linksPerPage, $page, &$pages, &$prev,&$next);
		
		$tpl->assign("page",$page);		
		$tpl->assign("pages",$pages);
		$tpl->assign("prev",$prev);
		$tpl->assign("next",$next);


        $tpl->display("template.member.banners.php");
?>