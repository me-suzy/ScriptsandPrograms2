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
notifications
*/

	checkAdminPage();
/**
input:
$cmd = 
*/

// cmd
        // check $cmd
        if (!isset($cmd)) $cmd="";

        if ($cmd == "update") {        
        	$dbSet->execute("UPDATE notifications SET isEnable=$isEnable, freq='".$freq."'
        		WHERE notifyName='".$notifyName."'");
        	$msg = "notifications were updated";
        	$cmd="";
        }
        
        if ($cmd == "cron") {        
        	checkAllNotifyTasks();
        	
        	$msg = "tasks have been performed";
        	$cmd="";
        }
        
        if ($cmd == "enableall" || $cmd == "disableall") {
        	$isEnable= $cmd == "enableall" ? 1 : 0;
        	
        	$dbSet->execute("UPDATE notifications SET isEnable=$isEnable");
        	$msg = "all notifications become ".($isEnable==1 ? "enabled": "disabled");
        	$cmd="";
        }
	
		if ($cmd == "disableall") {    
        	$dbSet->execute("UPDATE notifications SET isEnable=1");
        	$msg = "all notifications become enabled";
        	$cmd="";
        }
        
        
// load page
	
	$items=array();
	$dbSet->open("SELECT * FROM notifications");
	while ($row=$dbSet->fetchArray()){
		$items[]=$row;
	}
	
	//$tpl -> clear_compiled_tpl();
	$tpl->assign("items",$items);
	
	if (!isset($msg)) $msg="";
	$tpl->assign("msg",$msg);

	      
    $tpl->display("admin/template.admin.notifications.php");
?>