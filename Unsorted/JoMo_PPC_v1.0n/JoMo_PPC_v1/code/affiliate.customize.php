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
<?

	checkAffiliatePage();

        $sID->assign("mode","affiliates");
        $sID->assign("affMode","customize");
        
        createAffiliateCustoms($affiliateID);

/**
$cmd=

*/
        
    if (!isset($cmd) || empty($cmd))  $cmd="";

        if ($cmd=="save"){
                updateAffiliateCustoms($affiliateID, $result);
        }

        // member
        $member=getMember($affiliateID,"affiliate");
        $tpl->assign("member",$member["info"]);
        
        // customs
        $dbSet->open("SELECT ac.*,acol.colorName, acol.description 
                FROM affcustoms ac
                INNER JOIN affcolors acol ON 1=1
                WHERE ac.affiliateID=$affiliateID");
        $customs = $dbSet->fetchArray();
        
        //$customs = getAffCustoms($affiliateID);

        $dbSet->open("SELECT * FROM affcolors");
        $colors=array();
        $i=0;
        while($row=$dbSet->fetchArray()){
                $colors[$i]=$row;
                $colors[$i]["value"]=$customs[$row["colorID"]];
                $i++;
        }

        /*
        dprint("result:");
        print_r($result);
        */
        if (isset($result) && !empty($result)){
                foreach ($result as $key=>$v){
                        $customs[$key]=$v;
                }
                $i=0;
                foreach ($colors as $color){
                        if (isset($result[$color["colorID"]]))
                                $color["value"]=$result[$color["colorID"]];
                        $colors[$i]=$color;
                        $i++;
                }
        }

        $tpl->assign("colors",$colors);
        $tpl->assign("customs",$customs);

    $tpl->assign("cmd",$cmd);

        if ($cmd=="preview"){
                $tpl->display("template.affiliate.customizepreview.php");
        }
        else
            $tpl->display("template.affiliate.customize.php");
?>