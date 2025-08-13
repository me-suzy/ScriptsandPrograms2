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
general
*/
  $debug=1;
  function dprint($msg){
   global $debug;
   if ($debug==1)
    echo "debug: $msg<BR>";

  }

  function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
  }

  function generate_password($plength = 8) {
		srand((double)microtime()*1000000);
		$password_letters = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
		$maxlet = strlen($password_letters)-1;
		$password = "";
		for($i = 1; $i < $plength; $i++) {
			$password .= $password_letters{rand(0,$maxlet)};
		}
		return $password;
	} 

function checkMemberPage($redirectURL=""){
	global $memberID;
	global $sID, $tpl;
	
	if ($redirectURL=="")
		$redirectURL = "index.php?mode=members&memberMode=login";
	
	 if (!$sID->assigned("isMemberLogin")) {
                header("Location: $redirectURL");
                exit;
	}

  	// memberID
    if (empty($memberID))
        if ($sID->assigned("memberID"))              $memberID = $sID->fetch("memberID");
        else           $memberID = 0;
    if ($memberID==0){
        header("Location: $redirectURL");
        exit;
    }
    
    $sID->assign("memberID",$memberID);
	$sID->assign("mode","members");

}

function checkAffiliatePage($redirectURL=""){
	global $sID, $tpl, $affiliateID;
	
	if ($redirectURL=="")
		$redirectURL = "index.php?mode=affiliates&affMode=login";
	
    if (!$sID->assigned("isAffiliateLogin")) {
            header("Location: $redirectURL");
            exit;
    }

  	// affiliateID
        if (empty($affiliateID))
            if ($sID->assigned("affiliateID"))              $affiliateID = $sID->fetch("affiliateID");
            else           $affiliateID = 0;
        if ($affiliateID==0){
                header("Location: $redirectURL");
                exit;
        }
        
        $sID->assign("mode","affiliates");
        $sID->assign("affiliateID",$affiliateID);
}

function checkAdminPage($redirectURL=""){
	global $sID, $tpl;
	
	if ($redirectURL=="")
		$redirectURL = "admin.php?mode=login";

    if (!$sID->assigned("isAdminLogin")) {
            header("Location: $redirectURL");
            exit;
    }
}

/**
check if in demo mode in admin area, i.e. admin logon in demo mode.
*/
function isDemoAdmin(){
	global $sID, $tpl;
	
	if (__CFG_GUEST_ADMIN_ENABLE == 0) return false;
    if (!$sID->assigned("demoAdmin"))
	   return false;
	$da = $sID->fetch("demoAdmin");
	return $da;
}

/**
29-oct-2002.
check permissions
if guest admin, then disable operations (return 0)
else return 1.
*/
function allow($operation=""){
	global $sID, $tpl;
	
	// if admin performs operation
	if ($sID->assigned("isAdminLogin")){
		// logon as demo admin
		if ($sID->assigned("demoAdmin")){
			$da = $sID->fetch("demoAdmin");	
			if ($da) return 0;
			else return 1;
		}
		else return 1;
	}
	
	// allow for everyone
	return 1;
}


 function delRow($table, $IDName, $ID){
    global $dbObj ;
    $dbSet=new xxDataset($dbObj);
    $dbSet->execute("DELETE FROM ".$table." WHERE ".$IDName."='".$ID."'");
  }

  function makeInsertList(&$strColumns,&$strValues, $result,$breakColumns=array()){
                $strColumns=""; $strValues="";
                foreach($result as $key => $val)
                {
                 if (in_array($key,$breakColumns)) continue;
                 $v = addslashes($val);
                 $strValues .=  " '$v' ,";
                 $strColumns .= " $key ,";
                }
                $strValues = substr($strValues,0,strlen($strValues)-1);
                $strColumns = substr($strColumns,0,strlen($strColumns)-1);
  }

  function makeUpdateList(&$strSet, $result,$breakColumns=array()){
	    $strSet="";
	    foreach($result as $key => $val)
	    {	
	           if (in_array($key,$breakColumns)) continue;
	           $v = addslashes($val);
	           $strSet .= " $key = '$v' ,";
	
	    }
	    $strSet = substr($strSet,0,strlen($strSet)-1);

  }

  function calcPages($nItems,$itemsPerPage, $page, &$pages, &$prev,&$next){
	if ($page<=0) $page=1;
	$totalPages = ceil($nItems / $itemsPerPage);
	if ($page>$totalPages) $page = $totalPages;
	
	$dp=2;
	
	$pages=array();
	$page1 = $page-$dp;
	$prev=$page1>1 && $page<=$totalPages ? $page1-1 : 0;
	if ($page1<1) $page1=1;
	for ($i=$page1;$i<$page1+$dp*2 && $i<=$totalPages;$i++){
    	$pages[]=$i;
	}

	$next=($page1+$dp*2<=$totalPages && $page>0) ? $page1+$dp*2 : 0;

  }
  
  function getOption($option){
   global $dbObj;
   $dbSet=new xxDataset($dbObj);

   $dbSet->open("SELECT * FROM adminoptions WHERE optionName='".$option."'");
   $row = $dbSet->fetchArray();
   return $row["value"];
  }

	// return true if successful
  function setOption($option,$value,$description){
   	global $dbObj;
   	$dbSet=new xxDataset($dbObj);

	// check permissions
	if (isDemoAdmin()) return false;
		
   	$dbSet->open("SELECT * FROM adminoptions WHERE optionName='".$option."'");
   	$n=$dbSet->numRows();
   	if ($n==0) return false;

   	
   	//$dbSet->open("UPDATE adminoptions SET value='".$value."', description='".$description."' WHERE optionName='".$option."'");
   	$dbSet->open("UPDATE adminoptions SET value='".$value."' WHERE optionName='".$option."'");
   	
   	return true;
  }
  
  function isBlank($v){
  	//if (!isset($v) || empty($v) || $v=="" || $v==0){
  	if (!isset($v) || empty($v) || $v==""){
  		return true;
  	}
  	return false;
  }
  
  function array_kv($a, $key){
  	$res=array();
  	
  	foreach ($a as $e){
  		$res[]=$e[$key];
  	}
  	return $res;
  }
?>