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
member area
*/

/**
input:
$tryLogin
memberLogin, memberPassword
memberMode= login, urls, links, keywords(?), statistics, account
session:
        isMemberLogin
        memberID
*/
		
		// memberType
		/*
		if (!isset($memberType)){
			if ($sID->assigned("memberType"))
				$memberType = $sID->fetch("memberType");
			else
				$memberType="member";
		}
		$sID->assign("memberType",$memberType);
		*/
		$memberType="member";
		$tpl->assign("memberType",$memberType);
		
		// action
        if (!isset($action)) $action="";

        /** action */
        if ($action=="register"){
        	$error="";
        	$member=emptyMember($memberType);
             if (($memberID=registerMember($result,$error,$memberType))>0){
             	
             	if (notifyWelcome($memberID,$memberType)){
                	$msg = "you have been registered. You login and password has been sent to your email.";
                	$memberMode="login";
                }
                else{
                	$msg = "you have been registered. ";
                    $memberMode = "login";
                }
             }
             else{
                  $msg=$error.". Try again.";
                  $memberMode = "register";
             }
             
             foreach ($result as $key=>$val){
             	$member[$key]=$val;
             }

        }
        else if ($action=="remember"){
             if (!isset($email)){
               $msg="incorrect email. Try again.";
               $memberMode="forgot";
             }
             else
             if (sendForgotPassword($email,$memberType)){
                $msg = "You password has been sent to your email.";
                $memberMode="login";
             }
             else{
                  $msg="email not found. Try again.";
                  $memberMode="forgot";
             }

        }

    if (!isset($loginMode)) $loginMode = "";
    
    /** Checkout system login */
    if(!$sID->assigned("isMemberLogin")) {
            if(!isset($memberLogin)) $memberLogin="";
            if(!isset($memberPassword)) $memberPassword="";
      
            $memberID=isMemberExist($memberLogin,$memberPassword,$memberType);
            
            if ($memberID>0 && isset($tryLogin))
            {
                    $sID->assign("isMemberLogin", true);
                    $sID->assign("memberID", $memberID);
                    $loginMode = "member";
                    $memberMode="main";
            }
            else {
                    if (isset($tryLogin) && $tryLogin==1){
                        $msg="incorrect login or password";
                    }
            }
    }
    else {
        if(empty($memberMode))           $memberMode=$sID->fetch("memberMode");
        if(empty($memberID))           $memberID=$sID->fetch("memberID");      
        $loginMode="member";
    }
    
    /** Check current mode */
    if(!isset($memberMode) || empty($memberMode) || $memberMode=="logout") {
        $sID->unassign("isMemberLogin");
        $sID->unassign("memberMode");
        $sID->unassign("loginMode");
        $memberMode="login";        
        $loginMode = "";
    }
    

    /** Assign page to template*/
    $sID->assign("memberMode", $memberMode);    $tpl->assign("memberMode", $memberMode);    
    $sID->assign("memberID", $memberID);        $tpl->assign("memberID", $memberID);
    $sID->assign("loginMode", $loginMode);      $tpl->assign("loginMode", $loginMode);

    if (!isset($msg)) $msg="";
    $tpl->assign("msg",$msg);

    /** Get work module */
    if (!isset($member) || empty($member)) $member=emptyMember($memberType);

// countries
        $dbSet->open("SELECT DISTINCT country_id, code as country_code, name as country_name FROM country");
        $country_ids=array();
        $country_names=array(); 
        while ($row=$dbSet->fetchArray()){
                $country_ids[]=$row["country_id"];
                $country_names[]=$row["country_name"];          
        }
        
        $tpl->assign("countryListValues", $country_ids);
        $tpl->assign("countryListOutput", $country_names);
        

// go        
                
        if ($memberMode=="register"){
        	$tpl->assign("result",$member);
            $tpl->display("template.$memberType.register.php");
        }
        else if ($memberMode=="forgot"){
          $tpl->display("template.$memberType.forgot.php");
        }
        else if($memberMode!="login" && $memberMode!="logout") {
                include_once(__CFG_PATH_CODE . "$memberType.$memberMode.php");
        } 
        else  {
          $tpl->display("template.$memberType.login.php");
        }

        include(__CFG_PATH_CODE . "unloader.php");
?>