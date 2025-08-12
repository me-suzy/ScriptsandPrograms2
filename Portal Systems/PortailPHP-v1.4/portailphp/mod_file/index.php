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
$nb_file_page_page = 10 ;

if (isset($_GET["id"]))
{
    $res_file = sql_query("UPDATE $BD_Tab_file SET FI_lect=FI_lect + 1 WHERE FI_uid='" . $_GET["id"] . "'", $sql_id)or die("$Err_Modif") ;
    $req = " AND FI_uid='" . $_GET["id"] . "' " ;
}
else
{
    $req = "" ;
}

if ( $affiche=="File" && isset($_GET["id"]))
{
    $limit = "" ;
}
else if (($affiche == "File") && (!isset($_GET["id"])))
{
    // Calcule du nombre de news
    $q = sql_query("SELECT * FROM $BD_Tab_file", $sql_id) ;
    $nb_fichier = mysql_numrows($q);

    //fixer la limite
    if (isset($_GET["pos"]) && is_numeric($_GET["pos"]))
    {
        $limit1 = $_GET["pos"] * $nb_file_page_page ;
    }
    else
    {
        $limit1 = 0 ;
        $_GET["pos"] = 0 ;
    }

    $limit = "LIMIT $limit1,$nb_file_page_page" ;

    // Calcule le nombre de pages
    $nbre_page = $nb_fichier / $nb_file_page_page ;
}
else
{
    $limit = "LIMIT 0,$nb_file_page_page" ;
}

$res_file = sql_query("SELECT * FROM `$BD_Tab_file` WHERE 1 $req ORDER BY " . $_SESSION["File_Ordre"] . " $limit", $sql_id) ;
echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_File</strong><br /><br />" ;

if(!isset($_GET["id"]) && $affiche == "File")
{
    echo "<div align='left'>" ;
    echo "   <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
    echo "   <tr>" ;
    echo "     <td><strong>$Mod_File_inde_Cat</strong></td>" ;
    echo "     <td><strong>$Mod_File_inde_Titre</strong></td>" ;
    echo "     <td><strong>$Mod_File_inde_Date</strong></td>" ;
    echo "     <td><strong>$Mod_File_inde_Clics</strong></td>" ;

    if ($_SESSION["Admin"])
    {
        echo "     <td><strong>$Mod_News_Actions</strong></td>" ;
    }

    echo "   </tr>" ;
    echo "   <tr>" ;
    echo "     <td><br /></td>" ;
    echo "   </tr>" ;
}
else if (!isset($_GET["id"]) )
{       
    echo "<div align='left'>" ;
    echo "  <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
    echo "    <tr>" ;
    echo "      <td><strong>" ;

    if ($affiche == "File-plusrecents")
    {
        echo "$Mod_File_PlRecent" ;
    }
    else if ($affiche == "File-plusclics")
    {
        echo "$Mod_File_PlLu" ;
    }

    echo "</strong></td>" ;
    echo "    </tr>" ;
    echo "    <tr>" ;
    echo "      <td><br /></td>" ;
    echo "    </tr>" ;
}

while($row = mysql_fetch_object($res_file))
{
    if(!isset($_GET["id"]) && $affiche == "File")
    {
        echo "   <tr>" ;
        echo "     <td><img border='0' src='images/ico_zip.gif' />&nbsp;" . $row->FI_cat . "</td>" ;
        echo "     <td><a href='index.php?" . $sid . "affiche=File&id=" . $row->FI_uid . "'>" . $row->FI_titre . "</a></td>" ;
        echo "     <td>" . $row->FI_date . "</td>" ;
        echo "     <td>" . $row->FI_lect . "</td>" ;

        if ($_SESSION["Admin"])
        {
            echo "<td><a href='index.php?" . $sid . "affiche=Admin&admin=File-Del&action=File-Del&id=" . $row->FI_uid . "&name=" . urlencode($row->FI_nom) . "'>$Mod_News_Supprimer</a></td>" ;
        }

        echo "   </tr>" ;
    }
    else if(!isset($_GET["id"]))
    {
        echo "   <tr>" ;
        echo "      <td><img border='0' src='images/ico_zip.gif' />&nbsp;<strong>" . $row->FI_cat . "</strong>" ;
        
        if ($affiche == "File-plusrecents")
        {
            echo "(" . $row->FI_date . ")" ;
        }
        else if ($affiche == "File-plusclics")
        {
            echo "(" . $row->FI_lect . " " . $Mod_File_inde_Clics . ")" ;
        }
        
        echo " : <a href='index.php?" . $sid . "affiche=File&id=" . $row->FI_uid . "'>" . $row->FI_titre . "</a></td>" ;
        echo "   </tr>" ;
    }
    else if(isset($_GET["id"]))
    {
        echo "<div align='left'>" ;
        echo "   <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><img border='0' src='images/ico_zip.gif' />&nbsp;<strong>" . $row->FI_cat .
             "</strong> : " . $row->FI_titre . "</td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><br />" ;
        echo "     <img border='0' src='images/ico_zip.gif' />&nbsp;<i>( $Mod_File_PostPar " .
             "<a href='mailto:" . $row->FI_mail . "'>" . $row->FI_aut . "</a> $Mod_File_Le " . $row->FI_date . ", " . 
             $row->FI_lect . " $Mod_File_inde_Clics )</i>" ;
        echo "     <br /><br /><br /><br /></td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'>$Mod_File_Telecharger <a href='mod_file/upload/$row->FI_nom'>$row->FI_nom</a></td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><br /><br /><a href='index.php?" . $sid . "affiche=File'>&lt;&lt;&nbsp;Retour</a>" ;

        if ($_SESSION["Admin"])
        {
            echo " - <a href='index.php?" . $sid . "affiche=Admin&admin=File-Del&action=File-Del&id=" . $row->DO_uid . "&name=" . urlencode($row->FI_nom) . "'>$Mod_News_Supprimer</a>" ;
        }

        echo "   </tr>" ;
    }
}

echo "</table>" ;

if ((!isset($_GET["id"])) && ($affiche == "File"))
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
            echo "<a href='index.php?" . $sid . "affiche=File&pos=$i'>" . ($i + 1) ."</a>, " ;
        }
    }
}

echo "</div>" ;
?>