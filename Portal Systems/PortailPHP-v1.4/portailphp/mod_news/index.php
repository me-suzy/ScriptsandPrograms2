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
// Configure le nombre de news par page
$nb_news_page_page = 10 ;


if (isset($_GET["id"]))
{
    $res_news = sql_query("UPDATE $BD_Tab_docs SET DO_lect=DO_lect + 1 WHERE DO_uid ='" . $_GET["id"] . "'", $sql_id)or die("$Err_Modif") ;
    $req =" AND DO_uid='" . $_GET["id"] . "'";
}
else
{
    $req = "" ;
}

if (($affiche == "News") && isset($_GET["id"]))
{
    $limit = "" ;
}
else if (($affiche == "News") && (!isset($_GET["id"])))
{
    // Calcule du nombre de news
    $q = sql_query("SELECT * FROM $BD_Tab_docs", $sql_id) ;
    $nb_news = mysql_numrows($q);

    //fixer la limite
    if (isset($_GET["pos"]) && is_numeric($_GET["pos"]))
    {
        $limit1 = $_GET["pos"] * $nb_news_page_page ;
    }
    else
    {
        $limit1 = 0 ;
        $_GET["pos"] = 0 ;
    }

    $limit = "LIMIT $limit1,$nb_news_page_page" ;

    // Calcule le nombre de pages
    $nbre_page = $nb_news / $nb_news_page_page ;

}
else
{
    $limit = "LIMIT 0,$nb_news_page_page" ;
}

$res_news = sql_query("SELECT * FROM `$BD_Tab_docs` WHERE 1 $req ORDER BY " . $_SESSION["News_Ordre"] . " $limit", $sql_id) ;
echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_News</strong><br /><br />" ;

if ((!isset($_GET["id"])) && ($affiche == "News"))
{
    echo "<div align='left'>" ;
    echo "   <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
    echo "   <tr>" ;
    echo "     <td><strong>$Mod_News_inde_Categorie</strong></td>" ;
    echo "     <td><strong>$Mod_News_inde_Date</strong></td>" ;
    echo "     <td><strong>$Mod_News_inde_Sujets</strong></td>" ;
    echo "     <td><strong>$Mod_News_inde_Auteurs</strong></td>" ;
    echo "     <td><strong>$Mod_News_inde_Lectures</strong></td>" ;

    if ($_SESSION["Admin"])
    {
        echo "     <td><strong>$Mod_News_Actions</strong></td>" ;
    }

    echo "   </tr>" ;
    echo "   <tr>" ;
    echo "     <td><br /></td>" ;
    echo "   </tr>" ;
}
else if (!isset($_GET["id"]))
{      
    echo "<div align='left'>" ;
    echo "  <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
    echo "    <tr>" ;
    echo "      <td><strong>" ;

    if ($affiche == "News-plusrecents")
    {
        echo $Mod_News_PlRecent ;
    }
    else if ($affiche == "News-pluslus")
    {
        echo $Mod_News_PlLu ;
    }

    echo "</strong></td>" ;
    echo "    </tr>" ;
    echo "    <tr>" ;
    echo "      <td><br /></td>" ;
    echo "    </tr>" ;
}


while($row = mysql_fetch_object($res_news))
{
    if ((!isset($_GET["id"])) && ($affiche == "News"))
    {
        echo "   <tr>" ;
        echo "     <td><img border='0' src='images/ico_news.gif' />&nbsp;" . $row->DO_rub . "</td>" ;
        echo "     <td>$row->DO_date</td>" ;
        echo "     <td><a href='index.php?affiche=News&id=" . $row->DO_uid . "'>" . $row->DO_suj . "</a></td>" ;
        echo "     <td>" . $row->DO_aut . "</td>" ;
        echo "     <td>" . $row->DO_lect . "</td>" ;

        if ($_SESSION["Admin"])
        {
            echo "     <td><a href='index.php?affiche=Admin&admin=News-Edit&id=" . $row->DO_uid . "'>$Mod_News_Modifier</a> - " ;
            echo "<a href='index.php?affiche=Admin&admin=News-Del&id=" . $row->DO_uid . "&titre=" . urlencode($row->DO_suj) . "'>$Mod_News_Supprimer</a></td>" ;
        }

        echo "   </tr>" ;
    }
    else if(!isset($_GET["id"]))
    {
        echo "   <tr>" ;
        echo "      <td><img border='0' src='images/ico_news.gif' />&nbsp;<strong>" . $row->DO_rub. "</strong> " ;
        
        if ($affiche == "News-plusrecents")
        {
            echo "($row->DO_date)" ;
        }
        else if ($affiche == "News-pluslus")
        {
            echo "($row->DO_lect $Mod_News_inde_Lectures)" ;
        }
        
        echo " : <a href='index.php?affiche=News&id=" . $row->DO_uid . "'>" . $row->DO_suj . "</a></td>" ;
        echo "   </tr>" ;
    }
    else if (isset($_GET["id"]))
    {
        echo "<div align='left'>" ;
        echo "   <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><img border='0' src='images/ico_news.gif' />&nbsp;<strong>" . $row->DO_rub .
             "</strong> : " . $row->DO_suj . "</td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><br />" ;
        echo "     <img border='0' src='images/ico_news.gif' />&nbsp;<i>( $Mod_News_PostPar <a" .
             " href='mailto:" . $row->DO_mail . "'>" . $row->DO_aut . "</a> $Mod_News_Le " . $row->DO_date . ", " .
             $row->DO_lect . " $Mod_News_inde_Lectures )</i>" ;
        echo "     <br /><br /><br /><br /></td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'>" . $row->DO_cont . "</td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><br /><br /><a href='index.php?affiche=News'>&lt;&lt;&nbsp;Retour</a>" ;

        if ($_SESSION["Admin"])
        {
            echo " - <a href='index.php?affiche=Admin&admin=News-Edit&id=" . $row->DO_uid . "'>$Mod_News_Modifier</a> - " ;
            echo "<a href='index.php?affiche=Admin&admin=News-Del&id=" . $row->DO_uid . "&titre=" . urlencode($row->DO_suj) . "'>$Mod_News_Supprimer</a>" ;
        }

        echo "</td>\n   </tr>" ;
    }
}

echo "</table>" ;

if ((!isset($_GET["id"])) && ($affiche == "News"))
{
    // pos -> position en cours
    echo '<br />Page : ' ;

    for ($i = 0; $i < $nbre_page; $i++)
    {
        if ($_GET["pos"] == $i)
        {
            echo "<strong>" . ($i + 1) . "</strong>, " ;
        }
        else
        {
            echo "<a href='index.php?affiche=News&pos=$i'>" . ($i + 1) ."</a>, " ;
        }
    }
}

echo "</div>" ;
?>