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
affiliate area
*/

/**
input:
$tryLogin
login, password
affMode= login, urls, links, keywords(?), statistics, account
session:
        isAffiliateLogin
        affID
*/

	$memberType = "affiliate";
	$tpl->assign("memberType",$memberType);
	
        if (!isset($action)) $action="";


        /** action */
        if ($action=="register"){
        	$error="";
        	$affiliate=emptyMember("affiliate");
             if (($affiliateID=registerMember($result,$error,"affiliate"))>0){
             	
             	if (notifyWelcome($affiliateID, "affiliate")){
                	$msg = "you have been registered. You login and password hae been sent to your email.";
                	$affMode="login";
                }
                else{
                	$msg = "you have been registered. ";
                    $affMode = "login";
                }
             }
             else{
                  $msg=$error.". Try again.";
                  $affMode = "register";
             }
             
             foreach ($result as $key=>$val){
             	$affiliate[$key]=$val;
             }

        }
        else if ($action=="remember"){
             if (!isset($email)){
               $msg="incorrect email. Try again.";
               $affMode="forgot";
             }
             else
             if (sendForgotPassword($email, "affiliate")){
                $msg = "You password has been sent to your email.";
                $affMode="login";
             }
             else{
                  $msg="email not found. Try again.";
                  $affMode="forgot";
             }
        }


        if (!isset($loginMode)) $loginMode = "";

        /** Checkout system login */
        if(!$sID->assigned("isAffiliateLogin")) {
                if(!isset($login)) $login="";
                if(!isset($password)) $password="";
                
                $affiliateID=isMemberExist($login,$password, "affiliate");
                if ($affiliateID>0 && isset($tryLogin))
                {
                        $sID->assign("isAffiliateLogin", true);
                        $sID->assign("affiliateID", $affiliateID);
                        $affMode="main";
                        $loginMode = "affiliate";
                }
                else {
                        if (isset($tryLogin) && $tryLogin==1){
                            $msg="incorrect login or password";
                        }
                }
        }
        else {
                if(empty($affMode))           $affMode=$sID->fetch("affMode");
                if(empty($affiliateID))           $affiliateID=$sID->fetch("affiliateID");
                $loginMode = "affiliate";
        }

        /** Check current mode */
        if(!isset($affMode) || empty($affMode) || $affMode=="logout") {
                $sID->unassign("isAffiliateLogin");
                $sID->unassign("affMode");
                $affMode="login";               
                $loginMode = "";
        }

        /** Assign page to template*/
        $sID->assign("affMode", $affMode);          $tpl->assign("affMode", $affMode);
        $sID->assign("affiliateID", $affiliateID);  $tpl->assign("affiliateID", $affiliateID);
        $sID->assign("loginMode", $loginMode);      $tpl->assign("loginMode", $loginMode);

        // msg
        if (!isset($msg)) $msg="";
        $tpl->assign("msg",$msg);

        /** Get work module */
        if (!isset($affiliate) || empty($affiliate)) 	{
        	$affiliate=emptyMember("affiliate");
        }

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
                
        if ($affMode=="register"){
        	$tpl->assign("result",$affiliate);
            $tpl->display("template.$memberType.register.php");
        }else if ($affMode=="forgot"){
          $tpl->display("template.$memberType.forgot.php");
        }else if($affMode!="login" && $affMode!="logout") {
                include_once(__CFG_PATH_CODE . "affiliate.$affMode.php");
        } else  {
          $tpl->display("template.$memberType.login.php");
        }

        include(__CFG_PATH_CODE . "unloader.php");
?>