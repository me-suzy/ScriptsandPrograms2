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
PPC
*/

$defaultColors=array(
	"backgroundcolor"=>"white",
	"evenbackcolor"=>"#eeeeee",
	"oddbackcolor"=>"#cccccc",
	"textcolor"=>"black",
	"formcolor"=>"darkgray",
	"linkcolor"=>"lightblue",
	"vlinkcolor"=>"blue",
	
);
/**
create entry in "affcustoms" table if not exists
*/
function createAffiliateCustoms($affiliateID){
	global $dbObj;
	global $defaultColors;
	$dbSet=new xxDataset($dbObj);
	
	$dbSet->open("SELECT * FROM affcustoms
		WHERE affiliateID=$affiliateID");
	$n=$dbSet->numRows();
	if ($n!=0) return;
	
	$result=$defaultColors;
	$result["header"]="";
	$result["footer"]="";	
	
	$strColumns=$strValues="";
	makeInsertList($strColumns, $strValues, $result);
	$table = "affcustoms";
	
	$dbSet->execute("INSERT INTO $table (affiliateID, ".$strColumns.") VALUES ($affiliateID, ".$strValues.")");
   
}


function getAffCustoms($affiliateID){
	global $dbObj;
	$dbSet=new xxDataset($dbObj);

	$dbSet->open("SELECT * FROM affcustoms WHERE affiliateID=$affiliateID");
	return $dbSet->fetchArray();
}

/**
*/
function updateAffiliateCustoms($affiliateID, $result){
	global $dbObj;
	$dbSet=new xxDataset($dbObj);

	createAffiliateCustoms($affiliateID);
	
	$strSet="";
	makeUpdateList($strSet, $result);
	$table = "affcustoms";
	$dbSet->execute("UPDATE $table SET $strSet");
	
	
}

function getAffiliateBid($linkID){
	global $dbObj;
	$dbSet=new xxDataset($dbObj);

	$link = getLink($linkID);
	
	// percent
	$percent = getOption("affiliatePercent");
	
	return ($link["bid"]*($percent/100));
}

function getXMLformat($affiliateID=0){
	$s = "<?xml version=\"1.0\" encoding=\"windows-1251\"?>\n";
	$s.="<root>"."\n";
	$s.="<count>2</count>"."\n";
	$s.="<affiliateID>$affiliateID</affiliateID>"."\n";	
	
	$s.="<result>"."\n";
	
	for ($i=1;$i<=2;$i++){
		//$s.="\t<site position=\"$i\">"."\n";

		$s.="\t\t<linktitle>title1</linktitle>"."\n";		
		$s.="\t\t<url>http://www.abc.com</url>"."\n";
		$s.="\t\t<description>description of the site</description>"."\n";		
		$s.="\t\t<bid>0.01</bid>"."\n";		

		$s.="\t</site>"."\n";
	}
	
	$s.="</result>"."\n";	
	$s.="</root>"."\n";	

	return $s;
}

function getSampleXMLURL($affiliateID){
	global $PHP_SELF, $DOCUMENT_ROOT, $SERVER_NAME;
	
	$p = $PHP_SELF;
	$p = preg_replace("/admin/","index",$p);
	$selfURL = "http://".$SERVER_NAME."".$p;
	return "$selfURL?str=SEARCHINGSTR&mode=search&affiliateID=$affiliateID&format=XML&page=1&count=10";
}

function addAffiliateRequest($affiliateID, $paymentType, $comments){
    global $PHP_SELF, $DOCUMENT_ROOT, $SERVER_NAME;
    global $dbObj;
    $dbSet=new xxDataset($dbObj);
    
    $comments = addslashes($comments);
    
    // check payment type
    
    
    $table = "affrequests";
    if (isAffiliateRequest($affiliateID)){
        $dbSet->execute("UPDATE $table SET lastRequestDate=NOW() WHERE affiliateID=$affiliateID");
    }
    else{
        $dbSet->execute("INSERT INTO $table (affiliateID, lastRequestDate, paymentType, comments) 
            VALUES ($affiliateID, NOW(), '".$paymentType."', '".$comments."')");
    }                    
    return sendAffiliateRequest($affiliateID);
}                                                         
                                         
/**
return false if error
*/
function sendAffiliateRequest($affiliateID){
    global $dbObj;
    $dbSet=new xxDataset($dbObj);
                           
    $table = "affrequests";
    
 	$member=getMember($affiliateID, "affiliate");
 	$info=$member["info"]; $account=$member["account"];
	$email=$info["email"];
    $request = getAffiliateRequest($affiliateID);
	
    // send email                       
    /*
    $html = "Request from affiliate.<br> "."date:".$request["lastRequestDate"].
        "affiliate:".$info["firstName"]." ".$info["lastName"].".<BR>"."e-mail:".$email.
        ".<br>account balance: $".$account["balance"].".<br>payment type:".$request["paymentType"].".<br>".$request["comments"];
    $text = "Request from affiliate.\n "."date:".$request["lastRequestDate"].
        "affiliate:".$info["firstName"]." ".$info["lastName"].".\n"."e-mail:".$email.
        ".\naccount balance: $".$account["balance"].".\npayment type:".$request["paymentType"].".\n".$request["comments"];
    sendMailProfile(__CFG_ADMIN_EMAIL, $email,"ppc admin",$html,$text,array(),array());
       */

    return true;
}

function isAffiliateRequest($affiliateID){
    global $dbObj;
    $dbSet=new xxDataset($dbObj);
                           
    $table = "affrequests";
    $dbSet->open("SELECT * FROM $table WHERE affiliateID=$affiliateID");    
    return $dbSet->numRows()>0;
    
}
 
/**
return empty array() if not exists
*/
function getAffiliateRequest($affiliateID){
    global $dbObj;
    $dbSet=new xxDataset($dbObj);
                           
    $table = "affrequests";
    $dbSet->open("SELECT * FROM $table WHERE affiliateID=$affiliateID");    
    if ($dbSet->numRows()==0) return array();

    return $dbSet->fetchArray();

}

function delAffiliateRequest($affiliateID){
    global $dbObj;
    $dbSet=new xxDataset($dbObj);
                           
    $table = "affrequests";
    $dbSet->open("DELETE FROM $table WHERE affiliateID=$affiliateID");    
    return true;
}

function transferAffiliateMoney($affiliateID, $memberID, $amount, &$error){
    global $dbObj;
    $dbSet=new xxDataset($dbObj);
                                                                  
    if ($amount<=0){ $error ="amount must be greater than 0"; return false;}
    $minAffBalance = getOption("minAffiliateBalance");  
    $a = getMember($affiliateID, "affiliate");
    $aa = $a["account"];
    if ($amount>$aa["balance"]){ $error ="incorrect amount"; return false;}
    
    changeAccountBalance("affiliate", $affiliateID, -$amount, "transfer",0); 
    changeAccountBalance("member", $memberID, $amount, "transfer",0); 
    
    $error = "";    
    return true;
}

?>