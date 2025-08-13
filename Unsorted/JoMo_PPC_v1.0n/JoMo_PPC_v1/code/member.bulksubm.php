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
links of member
*/

	checkMemberPage();
		
		$sID->assign("memberID",$memberID);
		$tpl->assign("memberID",$memberID);
		
        // check $cmd
        if (!isset($cmd)) $cmd="";
        
        if ($cmd=="cancel") {
        	Header("Location: index.php?mode=members&memberMode=links");
        	exit();
        }

		// urls
		$dbSet->open("SELECT * FROM urls WHERE memberID=$memberID" );
        $urlNames = $urlIDs = array();
		while ($r = $dbSet->fetchObject())	{
			$urlNames[] 	= $r->url;
			$urlIDs[]  	= $r->urlID;
		}
        $tpl->assign("urlNames",$urlNames);
        $tpl->assign("urlIDs",$urlIDs);
		
		//
		$msg = "Upload text file with keywords, separated by delimiter.<br>";	
		
		if ($cmd == "uplkeywords") {
			$flag = 1;
			
			//$filename = $result["filename"];
			$title = $result["title"];
			
			if($filename == "") {
				$msg .= "Please input file name.";
				$flag = 0;
			}
			
			if($title == "") {
				$msg = $msg."Please input title for your keywords.";
				$flag = 0;
			}
			
			$delimiter = $result["delimiter"];
			
			if($flag) {
				//dprint("start upload");
				if(strstr($filename_type,"text/plain")) {
					$fp = fopen ($filename, "r");
					while (!feof ($fp)) {
					    $str = fgets($fp, 4096);
					    $keywords = explode ($delimiters[$delimiter]["value"], $str);
					    //print_r($keywords);
					    $msg_counter=0;
					    for($i=0; $i<sizeof($keywords); $i++) {
					    	if (strlen($keywords[$i])==0)
					    		continue;
					    	//dprint("add ".($keywords[$i]));
					    	$reslink = array(
						    	"urlID" 		=> $result["urlID"],
						    	"keywordName" 	=> $keywords[$i],
						    	"bid"		 	=> $result["bid"],
						    	"title"			=> $result["title"]
					    	);
					    	$error = ""; 
					    	//print_r($reslink);
					    	if(createLink($reslink, &$error)>0){
					    		//dprint("create link");
					    		$msg_counter++;
					    	}
					    	else{
					    		//dprint("link create error:".$error);
					    	}
					    }
					    $msg = "You added ".$msg_counter." links";
					}
					fclose ($fp);
					Header("Location: index.php?mode=members&memberMode=links&msg=$msg");
					exit();
				}
			} else {
				//$msg = $msg."Your Keywords was not upload!!";
			}
		}


		$r = array("filename"=>"", "bid"=>"0.01", "title"=>"", "urlID"=>"", "delimiter"=>0);
		
		if (isset($result)) {		
		foreach ($result as $key=>$val){
			$r[$key] = $val;
		}
		}
		
		$tpl->assign("result",$r);
        
        //$tpl -> clear_compiled_tpl();
        $tpl->assign("minBidValue", getOption("minBidValue"));
        
        $tpl->assign("delimiters",$delimiters);
        
		if (!isset($msg)) $msg="";
		$tpl->assign("msg",$msg);

        $tpl->display("template.member.bulksubm.php");
?>