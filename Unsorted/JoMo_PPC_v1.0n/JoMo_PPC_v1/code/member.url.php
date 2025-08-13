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
url of member
*/

       checkMemberPage();
       
/**
input parameters:
$urlID
$cmd= create, edit, signupcreate, signupedit, cancel
$result - info about url from form // used when $cmd=signupcreate|signupedit
*/

        if (empty($urlID)) $urlID = 0;

        if (!isset($cmd) || empty($cmd))   $cmd="";


// if user choose "cancel" in form
        if ($cmd=="cancel"){
                Header("Location: "."index.php?mode=members&memberMode=urls&cmd=");
                include(__CFG_PATH_CODE . "unloader.php");
                exit;
        }

// signup
        if ($cmd=="signupedit") {
                editUrl($result["urlID"], $result);
                $cmd="";
                //dprint ("edit");
                Header("Location: "."index.php?mode=members&memberMode=urls&cmd=");
                include(__CFG_PATH_CODE . "unloader.php");
                exit;
        }

        if ($cmd == "signupcreate"){
                createUrl($result, $result["urlID"]);
                $cmd="";
                //dprint("create");
                Header("Location: "."index.php?mode=members&memberMode=urls&cmd=");
                include(__CFG_PATH_CODE . "unloader.php");
                exit;
        }

// load page

   // url
        $dbSet->open("SELECT * FROM urls WHERE urlID=$urlID");
        $url = $dbSet->fetchArray();
        $tpl->assign("url",$url);

        $tpl->assign("cmd",$cmd);

        $tpl->assign("memberID",$memberID);

        $tpl->display("template.member.url.php");
?>


