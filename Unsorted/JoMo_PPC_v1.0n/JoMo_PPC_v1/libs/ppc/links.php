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
links, urls
*/

  function getUrlOfLink($linkID){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	   $dbSet->open("SELECT urlID FROM links
	    WHERE linkID=$linkID");
	   if ($dbSet->numRows()==0){
	    //dprint("no such url: linkid=$linkID");
	    return 0;
	   }
	   $row=$dbSet->fetchArray();
	   $res = $row["urlID"];
	   return $res;
  }

  function getUrl($urlID){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	   $dbSet->open("SELECT * FROM urls
	    WHERE urlID=$urlID");
	   $row=$dbSet->fetchArray();
	   return $row;
  }

  function getLink($linkID){
   global $dbObj;
   $dbSet=new xxDataset($dbObj);
   $dbSet->open("SELECT * FROM links
    	WHERE linkID=$linkID");
   $row=$dbSet->fetchArray();
   if (empty($row)) return emptyLink();
   return $row;
  }


// change account status of links and banners
	function changeListingsAccountStatus($memberID, $active=1){
		   global $dbObj;
		   $dbSet=new xxDataset($dbObj);

		   $urls="0";
		   $dbSet->open("SELECT urlID FROM urls WHERE memberID=$memberID");
		   while ($row=$dbSet->fetchArray()){
		    $urls.=",".$row["urlID"];
		   }
		   
		   $dbSet->execute("UPDATE links SET accountStatus=$active
		    	WHERE urlID IN (".$urls.")");		
		    	
		   $dbSet->execute("UPDATE banners SET accountStatus=$active WHERE memberID=$memberID");		
	}
		

	function isKeywordExist($keywordName){
	    global $dbObj ;
	    $dbSet=new xxDataset($dbObj);
	    $dbSet->open("SELECT * FROM keywords WHERE keywordName='".$keywordName."'");
	    if ($dbSet->numRows()==0) return false;
	    return true;
	}

        function getKeywordByName($keywordName){
                global $dbObj ;
                $dbSet=new xxDataset($dbObj);
                $dbSet->open("SELECT * FROM keywords WHERE keywordName='".$keywordName."'");
                if ($dbSet->numRows()==0) return array();
                return $dbSet->fetchArray();
        }

        function getKeyword($id){
                global $dbObj ;
                $dbSet=new xxDataset($dbObj);
                $dbSet->open("SELECT * FROM keywords WHERE keywordID=$id");
                if ($dbSet->numRows()==0) return array();
                return $dbSet->fetchArray();
        }

        function createKeyword($result){
                global $dbObj ;
                $dbSet=new xxDataset($dbObj);
                $result["keywordName"]=trim($result["keywordName"]);
                if ($result["keywordName"]=="") return 0;
                
                $strColumns=""; $strValues="";
                makeInsertList($strColumns,$strValues,$result,array("keywordID"));
                $table="keywords";
                $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.") VALUES (".$strValues.")");
                return $id;
        }
        
        function addKeywordsFromString($str, $separator){
		     global $dbObj ;
		     $dbSet=new xxDataset($dbObj);
		     
		     $keywords = preg_split ("/[\n,]+/", $str);
			
			foreach ($keywords as $key){
		     if (!isKeywordExist($key)){
		     	//dprint("create ".$key);
		     	createKeyword(array("keywordName"=>$key, "categoryID"=>0));
		     }
		    }	
		     
		   }

        function editKeyword($keywordID, $result){
                global $dbObj ;
                $dbSet=new xxDataset($dbObj);
                $strSet="";
                makeUpdateList($strSet,$result,array("keywordID"));

                $table="keywords";
                $linkID = $dbSet->execute("UPDATE ".$table." SET $strSet WHERE keywordID = $keywordID");

                return $linkID;
        }

        function deleteKeyword($keywordID){
                 delRow("keywords","keywordID",$keywordID);
        }

		function countLinksOfUrl($urlID){
                global $dbObj ;
                $dbSet=new xxDataset($dbObj);

                $dbSet->open("SELECT COUNT(*) as n FROM links WHERE urlID=$urlID");
				$row = $dbSet->fetchArray();
                return $row["n"];
        }
        
        function editUrl($urlID, $result){
                global $dbObj ;
                $dbSet=new xxDataset($dbObj);

                // compose $strSet
                $strSet="";
                makeUpdateList($strSet,$result,array("urlID"));

                $table="urls";
                $urlID = $dbSet->execute("UPDATE ".$table." SET $strSet, modificationDate=NOW() WHERE urlID = " . $result["urlID"]);

                return $urlID;
        }
        

/**
create new url
*/
        function createUrl($result){
                global $dbObj ;
                $dbSet=new xxDataset($dbObj);

                $strColumns=""; $strValues="";
                makeInsertList($strColumns,$strValues,$result,array("urlID"));

                $table="urls";
                $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.",creationDate,modificationDate) VALUES (".$strValues.",NOW(),NOW())");
                return $id;
        }


    function deleteURL($urlID){
        delRow("urls","urlID",$urlID);
    }

	function getMemberOfURL($urlID){
	           global $dbObj ;
                $dbSet=new xxDataset($dbObj);

				$dbSet->open("SELECT * FROM urls WHERE urlID=$urlID");
				$row=$dbSet->fetchArray();
				return $row["memberID"];
	}

  function getMemberOfLink($linkID){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	   
	   $urlID = getUrlOfLink($linkID);
	   $memberID = getMemberOfURL($urlID);
	   
	   return $memberID;
  }
	
	function emptyLink(){
		$link["linkID"]=0;
		$link["urlID"]=0;
		$link["keywordName"]="";
		$link["title"]="";
		$link["description"]="";
		$link["bid"]=getOption("minBidBalue");
		$link["status"]=1;
		$link["adminStatus"]=getOption("approveListing")==1?0:1;
		$link["accountStatus"]=0;
		$link["creationDate"]="";
		$link["modificationDate"]="";
		
		return $link;
	}
	
	function getLinkStatus($linkID){
		global $dbObj ;
        $dbSet=new xxDataset($dbObj);
        
        $urlID = getUrlOfLink($linkID);
		$memberID = getMemberOfURL($urlID);
		if ($memberID==0) return "";
		
		$accountStatus = isAccountActive($memberID)?1:0;
		$link = getLink($linkID);
		
	    $adminStatus = $link["adminStatus"];
		$status = $link["status"];
		if ($accountStatus==0) 	$statusMsg = "no money";
	    else if ($adminStatus==0)   	$statusMsg = "disabled by admin";
		else if ($status==0)	$statusMsg = "not active";
		else $statusMsg = "active";
		
		return $statusMsg;
	}
	
    function createLink($result, &$error){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);
                                
        // TODO: check urlID
        
        // check keyword
        $k = getKeywordByName($result["keywordName"]);
        $kid=0;
        if (!isset($k) || empty($k)){
            $kid=createKeyword(array("keywordName"=>$result["keywordName"]));
            $k = getKeyword($kid);
        }
        else
            $kid = $k["keywordID"];
    
        if ($kid==0) {
        	$error = "invalid keyword";
        	return 0;
        }

        $result["keywordID"]=$k["keywordID"];

        // account status
        $memberID=getMemberOfURL($result["urlID"]);
        $dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
        $row=$dbSet->fetchArray();
        $result["accountStatus"] = $row["isActive"];

        // admin status
        if (!isset($result["adminStatus"]))
            $result["adminStatus"]=getOption("approveListing")==1?0:1;
                
        // status
   		if (!isset($result["status"]))
            $result["status"]=0;

        $strColumns=""; $strValues="";
        makeInsertList($strColumns,$strValues,$result,array("linkID","keywordName"));
        $table="links";
        $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.", creationDate,modificationDate) VALUES (".$strValues.", NOW(),NOW())");
        $error = "";
        return $id;
    }

    function editLink($linkID, $result){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);

        // check keyword
        $k = getKeywordByName($result["keywordName"]);
        $kid=0;
        if (!isset($k) || empty($k)){
            $kid=createKeyword(array("keywordName"=>$result["keywordName"]));
            $k = getKeyword($kid);
        }
        else
        	$kid = $k["keywordID"];

        if ($kid==0) {
        	$error = "invalid keyword";
        	return 0;
        }

        $result["keywordID"]=$k["keywordID"];
                
        // account status
        $dbSet->open("SELECT * FROM links WHERE linkID=$linkID");
        $row=$dbSet->fetchArray();
        $urlID=$row["urlID"];

        $memberID=getMemberOfURL($urlID);
        $dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
        $row=$dbSet->fetchArray();
        $result["accountStatus"]=$row["isActive"];
        
        // admin status
        //$adminStatus=getOption("approveListing")==1?0:1;

        $strSet="";
        makeUpdateList($strSet,$result,array("linkID","keywordName"));
        
        $table="links";
        $linkID = $dbSet->execute("UPDATE ".$table." 
        	SET $strSet, modificationDate=NOW() 
        	WHERE linkID = $linkID");
    
        return $linkID;
    }

        function deleteLink($linkID){
                 delRow("links","linkID",$linkID);
        }

// banners

	function emptyBanner(){
		$banner["bannerID"]=0;
		$banner["url"]="http://";
		$banner["path"]="";
		$banner["memberID"]=0;
		$banner["name"]="";
		$banner["bid"]=getOption("minBidValue");
		$banner["keywords"]="";
		$banner["isCatchAll"]=0;
		$banner["status"]=1;
		$banner["adminStatus"]=getOption("approveListing")==1?0:1;;
		$banner["isPerImpression"]=0;		
		$banner["creationDate"]="";		
		$banner["modificationDate"]="";		
		return $banner;
	}
	
   function getBanner($bannerID){
		global $dbObj ;
        $dbSet=new xxDataset($dbObj);

        $dbSet->open("SELECT *,
	        DATE_FORMAT(creationDate,'%Y-%m-%d') as creationDate,
    	    DATE_FORMAT(modificationDate,'%Y-%m-%d') as modificationDate
        	 FROM banners WHERE bannerID=$bannerID");
        $banner = $dbSet->fetchArray();
        if (empty($banner)) $banner=emptyBanner();
        $banner["name"]=pathToName($banner["path"]);
        
        return $banner;
   }

   function delBanner($bannerID){
                delRow("banners","bannerID",$bannerID);
   }

/*
   function addBanner($url,$memberID,$bid,$keywords,$path){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);
        
        addKeywordsFromString($keywords,",");
        
        // account status
		if ($memberID==0)
			$accountStatus=1;
		else{
			$dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
			$row=$dbSet->fetchArray();
			$accountStatus=$row["isActive"];
		}		
		
		
		$adminStatus=getOption("approveListing")==1?0:1;

        $table="banners";
        $id = $dbSet->execute("INSERT INTO ".$table." (url,memberID,bid,keywords,path,adminStatus, accountStatus,creationDate,modificationDate)
         VALUES ('".$url."','".$memberID."','".$bid."','".$keywords."','".$path."',$adminStatus, $accountStatus,NOW(),NOW())");
        return $id;
   }
*/
/*
   function uploadBanner($memberID,$path,$url){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);

		// account status
		if ($memberID==0){
			$accountStatus=1;
			$status=1;
		}
		else{
			$dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
			$row=$dbSet->fetchArray();
			$accountStatus=$row["isActive"];
			$status=0;
		}		
	
		$adminStatus=getOption("approveListing")==1?0:1;

        $table="banners";
        $id = $dbSet->execute("INSERT INTO ".$table." (url,memberID,bid,keywords,path,status,adminStatus, accountStatus,creationDate,modificationDate)
         VALUES ('".$url."','".$memberID."',0,'','".$path."',$status,$adminStatus, $accountStatus,NOW(),NOW())");
        return $id;
   }
*/
/*
   function addBannerForm($result){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);
		//if (empty($result))	return 0;
		
        // check keywords
        if (!isset($result["isCatchAll"]) || $result["isCatchAll"]==0)
        	addKeywordsFromString($result["keywords"],",");
		
		// account status
		$memberID=$result["memberID"];
		if ($memberID==0)
			$accountStatus=1;
		else{
			$dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
			$row=$dbSet->fetchArray();
			$accountStatus=$row["isActive"];
		}		
		
		if (!isset($result["adminStatus"]))
			$adminStatus=getOption("approveListing")==1?0:1;
		else
			$adminStatus = $result["adminStatus"];

        $strColumns=""; $strValues="";
        makeInsertList($strColumns,$strValues,$result,array("bannerID","adminStatus"));
        $table="banners";
        $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.",adminStatus, accountStatus,creationDate,modificationDate) 
        	VALUES (".$strValues.",$adminStatus, $accountStatus, NOW(),NOW())");
        return $id;
	}
*/

	/**
	created: 30-aug-2002. MMX.
	return id or 0 (if error)
	upload file if result[filename]!=""
	*/
	function insertBanner($result, &$error){
		global $dbObj ;
		global $HTTP_POST_FILES;
        $dbSet=new xxDataset($dbObj);
		
		if (empty($result))	return 0;
		
		if (!isset($result["memberID"]))
			$memberID = 0;
		else
			$memberID=$result["memberID"];
		
        // check keywords
        if (!isset($result["keywords"]))
        	$result["keywords"] = "";
        if (!isset($result["isCatchAll"]) || $result["isCatchAll"]==0)
        	addKeywordsFromString($result["keywords"],",");
		
		// upload file
		//if (!isset($url)) $url="";
        $name=$HTTP_POST_FILES['userfile']['name'];
        $tmpname=$HTTP_POST_FILES['userfile']['tmp_name'];
				
        //check file
        // TODO: check physical exist of file
        if (isset($name) && $name!=""){
				$to = nameToPath($name,$memberID);
                move_uploaded_file($tmpname, $to);
        		$result["path"]=$to;
		}
		else{
			$error = "specify a file";
			return 0;
		}
		
		// check path
	    $path = $result["path"];
	    if (!isset($path) || empty($path) || $path==""){
	    	$error = "image filename is empty";
	    }
	    
        // status
   		if (!isset($result["status"]))
            $result["status"]=0;

		// account status
		if ($memberID==0)		$result["accountStatus"]=1;
		else{
			$dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
			$row=$dbSet->fetchArray();
			$result["accountStatus"]=$row["isActive"];
		}		

        // admin status
        if ($memberID!=0)
			$result["adminStatus"]=getOption("approveListing")==1?0:1;
		if (!isset($result["adminStatus"]))
			$result["adminStatus"] = 0;

        $strColumns=""; $strValues="";
        makeInsertList($strColumns,$strValues,$result,array("bannerID"));
        
        $table="banners";
        $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.", creationDate,modificationDate) 
        	VALUES (".$strValues.", NOW(),NOW())");
        
        $error = "";
        
        return $id;
	}
	
    function editBanner($bannerID, $result, &$error){
        global $dbObj ;
		global $HTTP_POST_FILES;

	    $dbSet=new xxDataset($dbObj);

		$memberID=$result["memberID"];
		
		// upload file if needed
        $name=$HTTP_POST_FILES['userfile']['name'];
        $tmpname=$HTTP_POST_FILES['userfile']['tmp_name'];
				
        //check file
        // TODO: check physical exist of file
        if (isset($name) && $name!=""){
				$to = nameToPath($name,$memberID);
                move_uploaded_file($tmpname, $to);
        		$path = $to;
        		$result["path"]=$path;
		}
		
		/*		
		// check path
	    $path = $result["path"];
	    if (!isset($path) || empty($path) || $path==""){
	    	$error = "image filename is empty";
	    }
	    */
	    
		if (!isset($result["isCatchAll"]) || $result["isCatchAll"]==0)
        	addKeywordsFromString($result["keywords"],",");
		
        $strSet="";
        makeUpdateList($strSet,$result,array("bannerID"));
        
        // account status
		if ($memberID==0)
			$accountStatus=1;
		else{
			$dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
			$row=$dbSet->fetchArray();
			$accountStatus=$row["isActive"];
		}		
		
        $table="banners";
        $bannerID = $dbSet->execute("UPDATE ".$table." 
        	SET $strSet, accountStatus=$accountStatus, modificationDate=NOW() 
        	WHERE bannerID = $bannerID");
		
		$error = "";
        return $bannerID;
    }

	function getBannerStatus($bannerID, $memberID=0){
		global $dbObj ;
        $dbSet=new xxDataset($dbObj);
        
        // memberID
        // if create new banner
        if ($bannerID==0){	
        	if ($memberID == 0) return "POOL";
        }
        else{
        	$dbSet->open("SELECT memberID FROM banners WHERE bannerID=$bannerID");
	        $row = $dbSet->fetchArray();
			$memberID = $row["memberID"];
			if ($memberID==0) return "POOL";
        }
        
		$accountStatus = isAccountActive($memberID)?1:0;
		$banner = getBanner($bannerID);
	    $adminStatus = $banner["adminStatus"];
		$status = $banner["status"];		
		if ($accountStatus==0)	$statusMsg = "no money";
	    else if ($adminStatus==0)    	$statusMsg = "disabled by admin";
		else if ($status==0)	$statusMsg = "not active";
		else $statusMsg = "active";
		
		return $statusMsg;
	}
	        
	function pathToName($path){
		$name1 = basename($path);
		$name=preg_replace("/member[0-9]+-/"," ",$name1);
		return $name;
	}
	
	function nameToPath($name, $memberID){
		$path =__CFG_PATH_BANNERS."member".$memberID."-".$name;
		return $path;
	}
	
	function getMembersOfMaxBidOfKeyword($keyword, &$memberIDs){
	  	global $sID;
	  	global $dbObj;
		$dbSet=new xxDataset($dbObj);
		
	  	$dbSet->open("SELECT MAX(l.bid) as m, memberID
	  		FROM keywords k
	  		INNER JOIN links l ON l.keywordID=k.keywordID
	  		INNER JOIN urls u ON u.urlID=l.urlID
	  		WHERE keywordName LIKE '%".$keyword."%' 
	  			AND l.status=1 and l.adminStatus=1 AND l.accountStatus=1
			GROUP BY l.linkID, u.memberID
			ORDER BY bid DESC");
		$row = $dbSet->fetchArray();
	  	$memberID = $row["memberID"];
		if (!isset($row["m"]) || empty($row["m"])) $bid=0;
		else $bid=$row["m"];
			
	  	$dbSet->open("SELECT l.linkID, memberID
	  		FROM keywords k
	  		INNER JOIN links l ON l.keywordID=k.keywordID
	  		INNER JOIN urls u ON u.urlID=l.urlID
	  		WHERE keywordName LIKE '%".$keyword."%' 
	  			AND l.status=1 and l.adminStatus=1 AND l.accountStatus=1
				AND l.bid=$bid
			ORDER BY bid DESC");

		$n = $dbSet->numRows();
		
		$memberIDs=array();
		while ($row=$dbSet->fetchArray()){
			$memberIDs[]=$row["memberID"];
		}
		
	  	return $bid;
	}
  
	function autobidLink($linkID, $memberID, $maxBid){
	  	global $sID;
	  	global $dbObj;
		$dbSet=new xxDataset($dbObj);
		
		//dprint("try autobid $linkID");
		
		$minBidValue = getOption("minBidValue");
		$add = 0.01;
		
		$dbSet->open("SELECT l.*, u.memberID, k.keywordName
	         FROM links l INNER JOIN urls u ON l.urlID=u.urlID
	         INNER JOIN keywords k ON l.keywordID=k.keywordID
	         WHERE memberID=$memberID AND linkID=$linkID"
        );

		$link = $dbSet->fetchArray();
		$ourbid = $link["bid"];
		
		$memberIDs=array();
		$bid = getMembersOfMaxBidOfKeyword($link["keywordName"], $memberIDs);
		//dprint("our bid=$ourbid, another bid=$bid, min=$minBidValue");
		
		$memberID = $link["memberID"];
		if ((!in_array($memberID,$memberIDs) || sizeof($memberIDs)>1) && $bid>0 && $bid>=$minBidValue){
			if ($bid+$add<=$maxBid || $maxBid==0){
				$dbSet->execute("UPDATE links SET bid=".$bid."+".$add."	WHERE linkID=$linkID");
				return true;
			}
			
		}
		return false;
	}
    
    
    function createQuestion($result, &$error){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);
                                
        $strColumns=""; $strValues="";
        makeInsertList($strColumns,$strValues,$result,array("questionID"));
        $table="faq";
        $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.") VALUES (".$strValues.")");
        $error = "";
        return $id;
    }

    function editQuestion($questionID, $result){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);

        $strSet="";
        makeUpdateList($strSet,$result,array("questionID"));
        
        $table="faq";
        $linkID = $dbSet->execute("UPDATE ".$table." 
        	SET $strSet 
        	WHERE questionID = $questionID");
    
        return $questionID;
    }

    function deleteQuestion($questionID){
             delRow("questions","questionID",$questionID);
    }
    
    function getQuestion($questionID){
        global $dbObj ;
        $dbSet=new xxDataset($dbObj);
        
        if ($questionID==0) return false;
        
        $dbSet->open("SELECT questionID, question, category, CONCAT(SUBSTRING(answer, 1,100),'...') as shortanswer, answer
	         FROM faq f 
	         WHERE questionID = $questionID"
			 );           
        if ($dbSet->numRows()==0) return false;
        return $dbSet->fetchArray();
    }

?>