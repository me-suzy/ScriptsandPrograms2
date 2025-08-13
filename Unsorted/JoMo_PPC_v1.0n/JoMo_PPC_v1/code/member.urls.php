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
urls of member

*/

	checkMemberPage();

/**
urlID
$cmd= delete

*/


    $sID->assign("memberID", $memberID);

    // check $cmd
    if (!isset($cmd)) $cmd="";

    if ($cmd == "delete") {
            deleteURL($urlID);
    }

    // urls
    $urls = array();
    $dbSet->open("SELECT urlID, url, title,description,
     DATE_FORMAT(creationDate,'%Y-%m-%d') as creationDate,
     DATE_FORMAT(modificationDate,'%Y-%m-%d') as modificationDate
     FROM urls WHERE memberID=$memberID" );
    $i=0; 
    while($row = $dbSet->fetchArray()) {
            $urls[$i] = $row;
            $urls[$i]["nLinks"]=countLinksOfUrl($row["urlID"]);
            $i++;
    }

    $tpl->assign("urls",$urls);

    $tpl->display("template.member.urls.php");
?>