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
search boxes
*/

        if (!$sID->assigned("isAdminLogin")) {
                header("Location: admin.php?mode=login");
                exit;
        }
/**
input:
$itemID
*/

// cmd
$table = "affsearchboxes";
$tableShort = "asb";
$itemColumnID = "searchBoxID";

        // check $cmd
        if (!isset($cmd)) $cmd="";
		$msg="";

        if ($cmd == "delete") {
        	$dbSet->execute("DELETE FROM affsearchboxes WHERE searchBoxID=$itemID");
        	$msg = "search box was deleted";
        	$cmd="";
        }
        
        if ($cmd == "create") {
        	createBoxFromResult($result);
        	$msg = "search box was created";
        	$cmd="";
        }

        if ($cmd == "save") {
        	updateBoxFromResult($itemID,$result);
        	$msg = "search box was saved";
        	$cmd="";
        }

        if ($cmd == "activate" || $cmd=="deactivate") {
        
			/*
			$dbSet->execute("UPDATE ".$table." 
				SET adminStatus=$adminStatus 
				WHERE ".$itemColumnID."=$itemID");
				*/
		}


// load page
		
		// boxes
        $tpl->assign("items",getSearchBoxes());
		
		$tpl->assign("msg",$msg);
	
		$tpl->assign("smode","admin");
				
        // display template
        $tpl->display("admin/template.admin.searchboxes.php");
?>