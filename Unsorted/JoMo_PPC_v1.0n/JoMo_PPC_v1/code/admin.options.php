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
options
*/

	checkAdminPage();
	
/**
input:
$optionCategory
$cmd = update
	$option, $value, $description
*/

// cmd
        // check $cmd
        if (!isset($cmd)) $cmd="";

        if ($cmd == "update") {        
        	if (setOption($option, $optionValue,$description)){
        		$msg="option $option was updated";
        	}
        	else
        		$msg="error setting value for the option";
        	$cmd="";
        }
	
// load page
	$where="1=1 ";
	if (!isset($optionCategory)) $optionCategory="";
	if ($optionCategory!="" && $optionCategory!="all")
		$where .= " AND optionCategory='".$optionCategory."'";
	
	$dbSet->open("SELECT * FROM adminoptions WHERE ".$where. " ORDER BY optionCategory ASC, optionName ASC");
	$options = array();
	while ($row=$dbSet->fetchArray()){
		$options[]=$row;
	}
	
	$tpl->assign("options",$options);
	$tpl->assign("optionCategory",$optionCategory);
	
	if (!isset($msg)) $msg="";
	$tpl->assign("msg",$msg);

	      
    $tpl->display("admin/template.admin.options.php");
?>