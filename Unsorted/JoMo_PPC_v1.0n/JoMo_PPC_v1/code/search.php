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
/**
input:
affiliateID
str
page
format = none, customHTML, customXML
aff_header,aff_footer
from, maxcount
*/

 // SET PPC_USER_ID if need.
	if (empty($HTTP_COOKIE_VARS["PPC_USER_ID"])) {
		$ppc_user_id = md5(generate_password(8));
		setcookie("PPC_USER_ID", $ppc_user_id, mktime(0,0,0,1,1,2010));
	} else {
		$ppc_user_id = $HTTP_COOKIE_VARS["PPC_USER_ID"];
	}
	
	
  if (!isset($affiliateID))      $affiliateID=0;
  if (!isset($str)) $str="";
  if (!isset($format)) $format="HTML";
  

  $tpl->assign("str",$str);
	
  $startTime = getmicrotime();

  /**
  log searched keyword
  */
  addKeywordToLog($str);
  
  /**
  banners
  */
  $banner=findBanners($str);
  // top banner
  //print_r($banner);
  $tpl->assign("istopbanner",1);
  if (!isset($banner["top"])||empty($banner["top"]))
  	$tpl->assign("istopbanner",0);
  else
  	$tpl->assign("topbanner",$banner["top"]);
  
  // bottom banner
  $tpl->assign("isbottombanner",1);
  if (!isset($banner["bottom"])||empty($banner["bottom"]))
  	$tpl->assign("isbottombanner",0);
  else{
	    $tpl->assign("bottombanner",$banner["bottom"]);
	    if ($banner["bottom"]["isPerImpression"]==1){
	    	$bannerID = $banner["bottom"]["bannerID"];
		    $bid=getOption("bannerImpressionBid");
		    $b = getBanner($bannerID);
		 	$memberID = $b["memberID"];
			
			if ($memberID!=0 && $affiliateID==0){
			    
			    if (isClickUnique($bannerID, $affiliateID,$ppc_user_id,"impression"))
			    changeAccountBalance("member",$memberID, -$bid,"impression",1);
			    
			    addImpressionBannerToLog($banner["bottom"]["bannerID"], $affiliateID, $ppc_user_id, $bid);
		    }
		}
   }
  

  /**
  pages
  */
  if (!isset($page)) $page=1;
  if ($page<=0) $page=1;

  if (!isset($count))
  	$count=getOption("LinksPerPage");
  $start=($page-1)*$count;
  
  //dprint("count = $count");
  
  /**
     search results
     $results[linkID, linkURL, title, description, bid]
  */
  $results = findLinks($str, $start, $count);

  $totalLinks = $results["totalLinks"];
  $foundLinks = $results["count"];
//  $totalLinks = 0;
	
	//dprint("before sf:");
	//dprint("total=$totalLinks, found=$foundLinks");

	//dprint("start=$start, count=$count");

// CyKuH [WTN]
	if (getOption("SearchFeed")==1){
		$sfStart=$start-$totalLinks; if ($sfStart<0) $sfStart=0;
        $sfCount = $count - $foundLinks;
        if ($sfCount <=0) $sfCount = 3;
        if ($sfCount>$count) $sfCount=$count;
		
		$nl = $count;
		$sfPage = floor($sfStart/$count)+1;
		
		//{{debug
		$nl=$sfStart+$sfCount+100;
		//$nl=1000;
		$sfPage=1;
		//}}debug
		
		//dprint("sf: start=$sfStart, count=$sfCount, nl=$nl, page=$sfPage");
		
		$error = "";
		//dprint("nl=$nl, page=$page, start=$sfStart, count=$sfCount");
		
	  	$res = parseSearchFeedLinks($str, $nl,$sfPage, $text, $error);	
	  	if ($res){
	  	$sfLinks = $res["links"];
	  	$sfResCount = $res["count"];
	  	
	  	//dprint("sf found:".$sfResCount);
	  	//print_r($sfLinks);
	  	
  	
	  	//print_r($sfLinks);
	    $i=0;
	    $cur=0;
	    foreach ($sfLinks as $sfLink){
	    	//dprint("------");
	    	 if ($cur<$sfStart){ $cur++; continue;}
	    	 if ($cur>=$sfStart+$sfCount) break;
	    	 
	         $results["links"][] = $sfLink;
	         //dprint("add i=$i");
	         $cur++;
	         $i++;
	    }
	    //dprint("count=".$sfResCount);
        $totalLinks += $sfResCount;
        $foundLinks += $i;
        }
	}


// GOOGLE results
	$MailReportError = __CFG_NO_ERROR; 
	$LogReportError = __CFG_ALL_ERROR; 
	$DisplayReportError = __CFG_NO_ERROR;


	$minSearchCount = getOption("minSearchCount");
	//dprint($totalLinks);
  if ($totalLinks<$minSearchCount && getOption("isGoogleSearch")==1 && $format!="XML"){
//  if (1==0 && $totalLinks<__SEARCH_MIN_LINKS && $format!="XML"){
//	if (1==1){
          // google results
          /*
          start, start+count
          */

          
          $maxGoogleSearchCount = getOption("maxGoogleSearchCount");
          //dprint("max googl $maxGoogleSearchCount");

          $googleStart=$start-$totalLinks; if ($googleStart<0) $googleStart=0;
          $googleCount = $count - $foundLinks;
          if ($googleCount <=0) $googleCount = 0;
          if ($googleCount>$count) $googleCount=$count;
          
          // {{debug
          //$googleStart=1; $googleCount=10; $str="mail";
          // }}
          //dprint("google: start $googleStart, count $googleCount");
          
          
          $googleResults = findGoogle($str, $googleStart, $googleCount);
          //dprint("<hr>google<hr>");
          
          if (isset($googleResults) && !empty($googleResults)){
          	$i=0;
          	//dprint("google: found before=$foundLinks, count=$count");
              foreach ($googleResults["links"] as $googleLink){
              		if ($googleStart+$i>$maxGoogleSearchCount) break;
                      if ($foundLinks + $i>=$count){
                      	//dprint("google: i=$i, count=$count, found=$foundLinks break");
                      	 break;
                      }
                      
                      //dprint("add google link");
                      $results["links"][] = $googleLink;
                      //if ($i>$maxGoogleSearchCount) break;
                      
                      
                      $i++;
              }
                  //dprint("after add google links: $i");
                  $g = $googleResults["totalLinks"];
                  if ($g>$maxGoogleSearchCount) $g=$maxGoogleSearchCount;
                  //if ($i<$g) $g=$i;
                  $totalLinks += $g;//$googleResults["totalLinks"];
                  $foundLinks += $i;
                  
          }
  }

	//dprint("fount $totalLinks links");
// error levels
	$MailReportError = __CFG_ALL_ERROR; 
	$LogReportError = __CFG_ALL_ERROR; 
	$DisplayReportError = __CFG_ALL_ERROR;


// altavista

	if (getOption("searchAltavista")==1){
//	if (0==1){
		$sfStart=$start + $foundLinks -$totalLinks; if ($sfStart<0) $sfStart=0;
        $sfCount = $count - $foundLinks;        if ($sfCount <=0) $sfCount = 0;        if ($sfCount>$count) $sfCount=$count;
		
		$error = "";
	  	$res = find_altavista($str, $sfStart, $sfCount, $error);	
	  	
	  	if ($res){
			//dprint("<hr>av<hr>");	  	
	  		//print_r($res);
	  		
		  	$sfLinks = $res["links"];
		  	$sfResCount = $res["count"];
		  	
		    $i=0;	    $cur=0;
		    foreach ($sfLinks as $sfLink){
		         $results["links"][] = $sfLink;
		         $cur++;		  $i++;
		    }
	        $totalLinks += $sfResCount;
	        $foundLinks += $i;
        }
	}


//dprint("<hr><hr>");
//print_r($results);

  $tpl->assign("results",$results["links"]);


  /**
  pages
  */
  /*
  $dp=2;
  $totalPages = ceil($totalLinks / $count);
  $pages=array();
  $page1 = $page-$dp;
  $prev=$page1>1 && $page<=$totalPages ? $page1-1 : 0;
  if ($page1<1) $page1=1;
  for ($i=$page1; $i<$page1+$dp*2 && $i<=$totalPages; $i++){
    $pages[]=$i;
  }

  $next=($page1+$dp*2+1<=$totalPages && $page>0) ? $page1+$dp*2+1 : 0;
*/

		// calc pages
		$pages=array(); $prev=$next=0;
		calcPages($totalLinks,$count, $page, &$pages, &$prev,&$next);
		
		$tpl->assign("page",$page);		
		$tpl->assign("pages",$pages);
		$tpl->assign("prev",$prev);
		$tpl->assign("next",$next);

	  $tpl->assign("totalLinks",$totalLinks);

  /**
  indexes
  */
  $indexes=array();

  for ($i=$start; $i<$start+$count+100 /*$totalLinks*/;$i++)
          $indexes[]=$i+1;

  $tpl->assign("indexes",$indexes);

  // time
  $endTime = getmicrotime();
  $searchTime = floor(($endTime-$startTime)*1000)/1000;
  if ($searchTime<0) $searchTime=0;
  $tpl->assign("searchTime",$searchTime);

  /** return results  */
  if ($affiliateID==0){
    $tpl->assign("str", $str);
    $tpl->assign("target",getOption("openLinkInNewWindow")==0?"_self":"_blank");
    $tpl->display("template.search.php");
  }
  else{ // to affiliates
  /**
  $affiliateID!=0 =>
   get header, footer, css from affiliates table
  generate page form affiliate
  */
	$tpl->assign("affiliateID",$affiliateID);
	
	// member
	$member=getMember($affiliateID,"affiliate");
	$tpl->assign("member",$member["info"]);
	
	// customs
	$dbSet->open("SELECT ac.*,acol.colorName, acol.description 
		FROM affcustoms ac
		INNER JOIN affcolors acol ON 1=1
		WHERE ac.affiliateID=$affiliateID");
	$customs = $dbSet->fetchArray();
	
	//$customs = getAffCustoms($affiliateID);
	
	$dbSet->open("SELECT * FROM affcolors");
	$colors=array();
	$i=0;
	while($row=$dbSet->fetchArray()){
		$colors[$i]=$row;
		$colors[$i]["value"]=$customs[$row["colorID"]];
		$i++;
	}

	$tpl->assign("colors",$colors);
	$tpl->assign("customs",$customs);

    // affiliate account
    $tpl->assign("target",$customs["openLinkInNewWindow"]==0?"_self":"_blank");
	
    //
    if (!isset($format)) $format="HTML";
	
    if ($format=="HTML"){
         $header=$customs["header"];
         $footer=$customs["footer"];
         // $style[], $style["textcolor"]
         $tpl->display("template.search.affiliate.php");
		 exit();
    }
    else if ($format=="XML"){
		echo getXMLLinks($affiliateID,$results["links"], $page, $count);
		exit();
    }

  }

?>