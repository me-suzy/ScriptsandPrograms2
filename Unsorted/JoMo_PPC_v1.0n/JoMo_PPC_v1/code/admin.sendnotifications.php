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

	checkAdminPage();
	
	if (!isset($cmd)) $cmd="";
	if (!isset($msg)) $msg="Input subject and content.";
	
	if (!isset($result["subject"])) $result["subject"]="";
	if (!isset($message)) $message="";
	if (!isset($recipient)) $recipient=0;

	if ($cmd == "cancel") {
		header("Location: admin.php?mode=notifications");
        exit;	
	}
		
	if ($cmd == "sendmail") {
    	$flag=1;
    	$msg="";
    	
    	if(strlen($result["subject"]) == 0) {
    		$msg = "Please input subject";
    		$flag=0;
    	}
    	
    	if(strlen($message) == 0) {
    		$msg = $msg."Please input message";
    		$flag=0;
    	}
    	
    	$members = array();
    	if($flag) {
    		if ($recipient==0 || $recipient==1){
	        	$dbSet->open("SELECT firstName,lastName, email FROM members");
				while ($row=$dbSet->fetchArray()){
					$members[]=$row;
				}
    		}
    		if ($recipient==0 || $recipient==2){
	        	$dbSet->open("SELECT firstName,lastName, email FROM affiliates");
				while ($row=$dbSet->fetchArray()){
					$members[]=$row;
				}
    		}
    		
    		if (!isset($result["fromName"]))   			$result["fromName"] = getOption("notificationName");
    		if (!isset($result["fromEmail"]))  			$result["fromEmail"] = getOption("notificationEmail");
    			
    		foreach ($members as $row){
						sendmail_to_user($row['firstName'], $row['lastName'], $row['email'], 
							$result["subject"], $message,$result["fromName"], $result["fromEmail"]);
			}
			
        	$msg = "Your message has been sent";
        	$cmd="";
        	
	       	header("Location: admin.php?mode=notifications&msg=$msg");
        	exit;
        }
    	
    }

	$tpl -> clear_compiled_tpl();
	
	if (isset($result["fromName"]))		$fro = $result["from"];
	if (!isset($result["fromName"]))		$result["fromName"] = getOption("notificationName");
	if (!isset($result["fromEmail"]))		$result["fromEmail"] = getOption("notificationEmail");
	$tpl->assign("fromName", $result["fromName"]);
	$tpl->assign("fromEmail", $result["fromEmail"]);

	
	$tpl->assign("msg",$msg);
	$tpl->assign("subject",$result["subject"]);
	$tpl->assign("message",$message);
	$tpl->assign("recipient",$recipient);

	$tpl->display("admin/template.admin.sendnotifications.php");
?>