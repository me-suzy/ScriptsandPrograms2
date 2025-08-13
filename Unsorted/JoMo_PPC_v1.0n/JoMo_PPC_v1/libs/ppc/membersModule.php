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
members module
	member info and accounts
affiliates  module
	affiliate info and accounts
	
*/

/** 
members table
	all fields - names in table
	all field names - title // as field appeares in forms
	mandatory fields
	
*/
// $memberFields = array ("fieldName"=>array(type=0(optional)| 1 -mandatory | 2-service ,title,[defaultValue]));

$memberFields = array(
	"memberID" =>array(2, "ID",0), 
	"login"=>array(1, "login",""), 
	"password"=>array(1, "password",""), 
	"repassword"=>array(1, "retype password",""),
	"firstName"=>array(1, "first name",""), 
	"lastName"=>array(1, "last name",""), 
	"companyName"=>array(0, "company name",""), 
	"address"=>array(1, "address",""), 
	"zip"=>array(1, "zip code",""), 
	"country"=>array(1, "country",224), 
	"email"=>array(1, "e-mail",""), 
	"phone"=>array(0, "phone",""), 
	"fax"=>array(0, "fax",""), 
	"foundus"=>array(0, "How you found us",""),
	"creationDate" =>array(2, "creation date",""), 
	"modificationDate" =>array(2, "modification date",""), 
);

$affFields = array(
	"affiliateID" =>array(2, "ID",0), 
	"login"=>array(1, "login",""), 
	"password"=>array(1, "password",""), 
	"repassword"=>array(1, "retype password",""),
	"firstName"=>array(1, "first name",""), 
	"lastName"=>array(1, "last name",""), 
	"companyName"=>array(0, "company name",""), 
	"address"=>array(1, "address",""), 
	"zip"=>array(1, "zip code",""), 
	"country"=>array(1, "country",224), 
	"email"=>array(1, "e-mail",""), 
	"phone"=>array(0, "phone",""), 
	"fax"=>array(0, "fax",""), 
	"foundus"=>array(0, "How you found us",""),
	"creationDate" =>array(2, "creation date",""), 
	"modificationDate" =>array(2, "modification date","")
);


function getMemberTables($type,&$table, &$acctable, &$columnID){
		$table = $type."s";
		$acctable = $type."accounts";
		$columnID = $type."ID";
}
	
  
  
function emptyMember($type="member"){
	global $memberFields, $affFields;
	
	$member = array();
	$fields = $type=="member"?$memberFields:$affFields;
	foreach ($fields as $key=>$val){
		$member[$key] = $val[2];
	}
	/*
		$member=array("memberID"=>0, "login"=>"", "password"=>"", "firstName"=>"", "lastName"=>"", "companyName"=>"", 
			"address"=>"", "zip"=>"", "country"=>"", "email"=>"",  "phone"=>"", "fax"=>"", "foundus"=>"");
		*/
		return $member;
	}
	
  function getMember($memberID, $type="member"){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);

		$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);
	
	   $dbSet->open("SELECT * FROM $table t WHERE t.".$columnID."=$memberID");
	   $member["info"] = $dbSet->fetchArray();
	   $dbSet->open("SELECT * FROM $acctable as ta WHERE ta.".$columnID."=$memberID");
	   $member["account"]=$dbSet->fetchArray();
	   return $member;
  }
  
  // return memberID if member exists otherwise 0
  function isMemberExist($login, $pwd, $type="member"){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	   
	   	$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);

	   $dbSet->open("SELECT $columnID FROM $table WHERE login='".$login."' AND password='".$pwd."'");
	   $n = $dbSet->numRows();
	   if ($n==0) return 0;
	   $row = $dbSet->fetchArray();
	   return $row[$columnID];
  }
	
	// $operation = "register"|"update"
	function checkMember($result, $operation, &$error, $type="member"){
		global $memberFields, $affFields;
		global $dbObj ;
		$dbSet=new xxDataset($dbObj);
		
		$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);

		if (isset($result[$columnID]) && !empty($result[$columnID]))	$$columnID = $result[$columnID];
		else	$$columnID=0;
		
		// mandatory fields
		// if update => skip fields
		$skipFields = array();
		if ($operation=="update"){
			$skipFields = array("password","repassword");
		}
		
		$fields = $type=="member"?$memberFields:$affFields;
		foreach ($fields as $key=>$val){
			if ($val[0]==2 || $val[0]==0) continue;
			
			if ($key=="password" || $key=="repassword"){
				if ($operation=="update"){
					if (isBlank($result[$key])) continue;
				}
			}
			
			if (isBlank($result[$key])){
				$error = "field ".($val[1])." cannot be blank";
				return 0;
			}
		}
		
		// check unique login
		if (isset($result["login"]) && !empty($result["login"])){
			$login=$result["login"]; 
			if ($login==""){
				$error = "login cannot be blank";	return 0;
			}
			
			$dbSet->open("SELECT COUNT(*) as c FROM $table WHERE login='".$login."' AND $columnID<>".$$columnID);
			$r = $dbSet->fetchArray();
			$c = $r["c"];
			if ($c>0){
				$error = "login ".$login." already exists";	 	return 0;
			}
			
		}
		
		// email	
		if (isset($result["email"]) && !empty($result["email"])){
			$email = $result["email"];
			
			// check correct email
			if (!preg_match ("/.+@.+\..+/",$email)){
				$error = "incorrect email";		 	return 0;
			}
			
			// check unique email
			$dbSet->open("SELECT COUNT(*) as c FROM $table WHERE email='".$email."' AND $columnID<>".$$columnID);
			$r = $dbSet->fetchArray();
			$c = $r["c"];
			if ($c>0){
				$error = "email ".$email." already exists";	 	return 0;	
			}

		}
		
		$error="";
		return 1;	
	}
	

  // return true if successful
  // result - array
  function registerMember($result, &$error, $type="member"){
		global $dbObj ;
		$dbSet=new xxDataset($dbObj);
			
		$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);
            
		// check info
		if (checkMember($result,"register",$error,$type)==0){	return 0;	}
			
			$strColumns=""; $strValues="";
    		makeInsertList($strColumns,$strValues,$result,array("memberID","affiliateID","repassword"));
            
            $id = $dbSet->execute("INSERT INTO ".$table." (".$strColumns.",creationDate,modificationDate) VALUES (".$strValues.",NOW(),NOW())");
            if ($id==0) return 0;
            $dbSet->execute("INSERT INTO $acctable ($columnID, balance,isActive)	VALUES ($id,0,0)");
            
            $error = "all OK"; 
            return $id;
  }

	// return true if successful
  	// result - array
  function updateMember($memberID,$result,&$error,$type="member"){
		global $dbObj ;
		$dbSet=new xxDataset($dbObj);
		
		$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);
            
		// check info
		if (checkMember($result,"update", $error,$type)==0){	
			return 0;	
		}

			$strSet="";
			if ($result["password"]=="")
            	makeUpdateList($strSet,$result,array("memberID", "affiliateID","password","repassword"));
            else
            	makeUpdateList($strSet,$result,array("memberID", "affiliateID","repassword"));
            $id = $dbSet->execute("UPDATE ".$table." SET $strSet WHERE $columnID = $memberID");
            
            return 1;
  }


/*************************************
// member/affiliate accounts
************************************/

	
	// when member deposit money
  function changeAccountBalance($accountType, $accountID,$value, $operationType="deposit", $save=1){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);

	if (allow("balance")==0) return 0;
	
	if ($accountType=="member"){ 
		$table = "memberaccounts"; 
		$columnID="memberID";
	}
	else { 
		$table = "affiliateaccounts"; 
		$columnID="affiliateID";
	}
	$tabletrans = "transactions"; 
	
	$member = getMember($accountID, $accountType);
	$account = $member["account"];
	
	// change money
	if ($value>0)
   		$dbSet->execute("UPDATE ".$table." SET balance=balance+".$value." WHERE ".$columnID."=$accountID");
    else{
    	$v=-$value;
    	$dbSet->execute("UPDATE ".$table." SET balance=balance-".$v." WHERE ".$columnID."=$accountID");
    }

	// save to transactions
	if ($save==1){
		$dbSet->execute("INSERT INTO ".$tabletrans."(accountID, accountType,value, transactionDate,transactionType) 
   		 VALUES ($accountID, '".$accountType."', $value,NOW(), '".$operationType."')");	
   	}
	
	// check member account
    $minBalance = getOption("minBalance");
			
		if ($accountType=="member"){
			
			// notify
			if ($operationType=="admin"){
				notifyAdminAdd($accountID, $value);
			}
			if ($operationType=="deposit"){
				notifyDeposit($accountID, $value);
			}
			
			
			$memberID = $accountID;
	   		$dbSet->open("SELECT * FROM memberaccounts WHERE memberID=$memberID");
	   		$account = $dbSet->fetchArray();
	
		   // activate/deactivate account
		   if ($account["balance"]>=$minBalance){
		    	$dbSet->execute("UPDATE memberaccounts SET isActive=1 WHERE memberID=$memberID");
		    	changeListingsAccountStatus($memberID, 1);
		   }
		   else{	
		   		$dbSet->execute("UPDATE memberaccounts SET isActive=0 WHERE memberID=$memberID");
		    	changeListingsAccountStatus($memberID, 0);
		   }
	    }
	   
  }

  function isAccountActive($memberID, $type="member"){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	   
		$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);
	
	   $dbSet->open("SELECT isActive FROM $acctable ta WHERE $columnID=$memberID");
	   $row = $dbSet->fetchArray();
	   return $row["isActive"]==1;
  }

  function getAccountStatus($memberID, $type="member"){
	   global $dbObj;
	   $dbSet=new xxDataset($dbObj);
	   
		$table = $acctable=$columnID="";
		getMemberTables($type, $table,$acctable,$columnID);
	
	   $dbSet->open("SELECT isActive FROM $acctable ta WHERE $columnID=$memberID");
	   $row = $dbSet->fetchArray();
	   
	   return $row["isActive"]==1;
  }
  
?>