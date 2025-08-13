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
/**
faq
input:
[$category]
*/

	$tpl->assign("pageTitle", __SITE_TITLE."-faq");
    
    if (!isset($category)) $category="";
    
    $dbSet->open("SELECT DISTINCT category FROM faq");
    $categories = array();
    while ($row=$dbSet->fetchArray()){
        $categories[]=$row["category"];
    }                      
            
    $tpl->assign("categories",$categories);        

    $where = "1=1 ";
    //if ($category!="") $where.=" AND category='".$category."'";

    $q = array();
    $i=0;    
    foreach ($categories as $c){
        //if ($category!="")       
        $q[$i]=array();
        $dbSet->open("SELECT * FROM faq WHERE category='".$c."'");
        while ($row=$dbSet->fetchArray()){
            $q[$i][]=$row;
        }        
        $i++;
         
    }
    
/*    
    $dbSet->open("SELECT * FROM faq WHERE ".$where." ORDER BY category ASC");
    $q = array();
    while ($row=$dbSet->fetchArray()){
        $q[]=$row;
    }                      
    */
    
    $tpl->assign("questions",$q);
    $tpl->assign("category",$category);
    
    $tpl->display("template.faq.php");
    
?>