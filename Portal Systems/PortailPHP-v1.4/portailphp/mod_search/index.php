<?php
/*******************************************************************************
 * Copyright (C) 2002 CLAIRE Cédric claced@m6net.fr
 * http://www.yoopla.net/portailphp/
 *
 * Modifié par Martineau Emeric Copyright (C) 2004
 *
 * Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le
 * modifier conformément aux dispositions de la Licence Publique Générale GNU,
 * telle que publiée par la Free Software Foundation ; version 2 de la licence,
 * ou encore (à votre choix) toute version ultérieure.
 *
 * Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE
 * GARANTIE ; sans même la garantie implicite de COMMERCIALISATION ou
 * D'ADAPTATION A UN OBJET PARTICULIER. Pour plus de détail, voir la Licence
 * Publique Générale GNU .
 *
 * Vous devez avoir reçu un exemplaire de la Licence Publique Générale GNU en
 * même temps que ce programme ; si ce n'est pas le cas, écrivez à la Free
 * Software Foundation Inc., 675 Mass Ave, Cambridge, MA 02139, Etats-Unis.
 *
 * Portail PHP
 * La présente Licence Publique Générale n'autorise pas le concessionnaire à
 * incorporer son programme dans des programmes propriétaires. Si votre programme
 * est une bibliothèque de sous-programmes, vous pouvez considérer comme plus
 * intéressant d'autoriser une édition de liens des applications propriétaires
 * avec la bibliothèque. Si c'est ce que vous souhaitez, vous devrez utiliser non
 * pas la présente licence, mais la Licence Publique Générale pour Bibliothèques GNU.
 ***********************************************************************************/

/* XSS AND SQL HOLES PATCHED BY WWW.PHPSECURE.ORG */

if (isset($_GET["rech"]))
{
    $rech = $_GET["rech"] ;
}
else
{
    $rech = "" ;
}

if (isset($_GET["action"]))
{
    $action = $_GET["action"] ;
}
else
{
    $action = "" ;
}

$rech = str_replace("'", '', $rech) ;                   // protection anti SQL injection
$BD_Tab_docs = str_replace('`', '', $BD_Tab_docs) ;     // idem
$BD_Tab_file = str_replace('`', '', $BD_Tab_file) ;     // idem
$BD_Tab_liens = str_replace('`', '', $BD_Tab_liens) ;   // idem
$BD_Tab_faq = str_replace('`', '', $BD_Tab_faq) ;       // idem

$chemin = htmlentities($chemin) ;                       // anti XSS
$Rub_Search = htmlentities($Rub_Search) ;               // idem
$Rub_News = htmlentities($Rub_News) ;                   // idem
$Rub_File = htmlentities($Rub_File) ;                   // idem
$Rub_Liens = htmlentities($Rub_Liens) ;                 // idem
$Rub_Faq = htmlentities($Rub_Faq) ;                     // idem

/* END OF SECURITY PATCH */

$chemin = "." ;
echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_Search</strong><br /><br />" ;

echo $Mod_Search_Descript ;
echo "<form name='formsearch' method='post' action='$chemin/index.php?" . $sid . "affiche=Search&action=Search' >" ;
echo "<div align='left'>" ;
echo "  <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
echo "    <tr>" ;
echo "      <td><p align='left'><input type='text' name='rech' size='40'>&nbsp;<input type='submit' name='Submit' value='OK'></td>" ;
echo "    </tr>" ;
echo "  </table>" ;
echo "</div>" ;
echo "</form>" ;

if ($action == "Search")
{
    $res_search = sql_query("SELECT DISTINCT * FROM `$BD_Tab_docs` WHERE DO_cont LIKE '%$rech%' OR DO_suj LIKE '%$rech%'", $sql_id) ;
  
    echo "<img border='0' src='mod_search/images/ico-search.gif' />&nbsp;<strong>$Rub_News</strong><br />" ;
  
    while ($row = mysql_fetch_object($res_search))
    {
        echo "<div align='left'>" ;
        echo "  <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
        echo "   <tr>" ;
        echo "     <td width='100'>&nbsp;&nbsp;<img border='0' src='images/ico_news.gif' />&nbsp;" . $row->DO_date . "</td>" ;
        echo "     <td width='290'><a href='index.php?" . $sid . "affiche=News&id=" . $row->DO_uid . "'>" . $row->DO_suj . "</a></td>" ;
        echo "   </tr>" ;
        echo "  </table>" ;
        echo "</div>" ;
    }
    
    echo "<br /><br />" ;
    
    $res_search = sql_query("SELECT DISTINCT * FROM `$BD_Tab_file` WHERE FI_nom LIKE '%$rech%' OR FI_titre LIKE '%$rech%'", $sql_id) ;
  
    echo "<img border='0' src='mod_search/images/ico-search.gif' />&nbsp;<strong>$Rub_File</strong><br />" ;
  
    while ($row = mysql_fetch_object($res_search))
    {
        echo "<div align='left'>" ;
        echo "  <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
        echo "   <tr>" ;
        echo "     <td width='100'>&nbsp;&nbsp;<img border='0' src='images/ico_zip.gif' />&nbsp;" . $row->FI_date . "</td>" ;
        echo "     <td width='290'><a href='index.php?" . $sid . "affiche=File&id=" . $row->FI_uid . "'>" . $row->FI_titre . "</a></td>" ;
        echo "   </tr>" ;
        echo "  </table>" ;
        echo "</div>" ;
    }
    
    echo "<br /><br />" ;
    
    $res_search = sql_query("SELECT DISTINCT * FROM `$BD_Tab_liens` WHERE LI_cont LIKE '%$rech%' OR LI_suj LIKE '%$rech%'", $sql_id);
    
    echo "<img border='0' src='mod_search/images/ico-search.gif' />&nbsp;<strong>$Rub_Liens</strong><br />" ;

    while($row = mysql_fetch_object($res_search))
    {
        echo "<div align='left'>" ;
        echo "  <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
        echo "   <tr>" ;
        echo "     <td width='100'>&nbsp;&nbsp;<img border='0' src='images/ico_liens.gif' />&nbsp;" . $row->LI_date . "</td>" ;
        echo "     <td width='290'><a href='index.php?" . $sid . "affiche=Liens&id=" . $row->LI_uid . "'>" . $row->LI_suj . "</a></td>" ;
        echo "   </tr>" ;
        echo "  </table>" ;
        echo "</div>" ; 
    }
    
    echo "<br /><br />" ;
    
    $res_search = sql_query("SELECT DISTINCT * FROM `$BD_Tab_faq` WHERE FA_que LIKE '%$rech%' OR FA_rep LIKE '%$rech%'", $sql_id) ;
  
    echo "<img border='0' src='mod_search/images/ico-search.gif' />&nbsp;<strong>$Rub_Faq</strong><br />" ;

    while($row = mysql_fetch_object($res_search))
    {
        echo "<div align='left'>" ;
        echo "  <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
        echo "   <tr>" ;
        echo "     <td width='15'>&nbsp;&nbsp;<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-cat01.gif' /></td>" ;
        echo "     <td width='290'><a href='index.php?" . $sid . "affiche=Faq&id=" . $row->FA_uid . "'>" . $row->FA_que . "</a></td>" ;
        echo "   </tr>" ;
        echo "  </table>" ;
        echo "</div>" ; 
    }
}
?>