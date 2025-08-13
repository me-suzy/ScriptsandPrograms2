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
admin.link
*/

	checkAdminPage();
/**
input:
$linkID
*/

// cmd
        // check $cmd
        if (!isset($cmd)) $cmd="";

        if ($cmd=="cancel") {
        	Header("Location: "."admin.php?mode=faq&cmd=");
            include(__CFG_PATH_CODE . "unloader.php");
            exit;
        }

// signup
        if ($cmd=="signupedit") {
                editQuestion($result["questionID"], $result);
                $cmd="";
                Header("Location: "."admin.php?mode=faq&cmd=");
                include(__CFG_PATH_CODE . "unloader.php");
                exit;
        }

        if ($cmd == "signupcreate"){
        	$error="";
            $res=createQuestion($result, $error);
                
            // error creating q
            if ($res==0){
            	$msg = $error;
            	$cmd="create";
            	$questionID=0;
            }
            else{
	            $cmd="";
	            Header("Location: "."admin.php?mode=faq&cmd=");
	            include(__CFG_PATH_CODE . "unloader.php");
	            exit;
	        }
        }
	
// load page   
    // categories
    $dbSet->open("SELECT DISTINCT category FROM faq");
    $categories = array();
    while ($row=$dbSet->fetchArray()){
        $categories[]=$row["category"];
    }                      
    $tpl->assign("categoryIDs",$categories);
    $tpl->assign("categoryNames",$categories);
        
// TODO: getLink(...)
	if (!isset($questionID)) $questionID=0;
    $question = getQuestion($questionID);
    
    $tpl->assign("question",$question);

	$tpl->assign("cmd",$cmd);
    
		
    // display template
    $tpl->display("admin/template.admin.faqquestion.php");
?>