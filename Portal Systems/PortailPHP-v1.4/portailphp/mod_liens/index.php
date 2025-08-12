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
if (isset($_GET["id"]))
{
    $req =" AND LI_uid='" . $_GET["id"] . "'" ;
}

if ($affiche == "Liens" || isset($_GET["id"]))
{
    $limit = "" ;
}
else
{
    $limit = "LIMIT 0,10" ;
}

$res_liens = sql_query("SELECT * FROM `$BD_Tab_liens` WHERE 1 $req ORDER BY " . $_SESSION["Liens_Ordre"] . " $limit", $sql_id) ;
echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_Liens</strong><br /><br />" ;

if(!isset($_GET["id"]) && $affiche == "Liens")
{
    echo "<div align='left'>" ;
    echo "   <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
    echo "   <tr>" ;
    echo "     <td><strong>$Mod_Lien_inde_Categorie</strong></td>" ;
    echo "     <td><strong>$Mod_Liens_inde_Date</strong></td>" ;
    echo "     <td><strong>$Mod_Liens_inde_Titre</strong></td>" ;
    echo "     <td><strong>$Mod_Liens_inde_Auteur</strong></td>" ;
    echo "     <td><strong>$Mod_Liens_inde_Clics</strong></td>" ;
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

    if ($affiche == "Liens-plusrecents")
    {
        echo "$Mod_Liens_PlRecent" ;
    }
    else if ($affiche == "Liens-top")
    {
        echo "$Mod_Liens_Top" ;
    }

    echo "</strong></td>" ;
    echo "    </tr>" ;
    echo "    <tr>" ;
    echo "      <td><br /></td>" ;
    echo "    </tr>" ;    
}


while($row = mysql_fetch_object($res_liens))
{
    if(!isset($_GET["id"]) && $affiche == "Liens")
    {
        echo "   <tr>" ;
        echo "     <td><img border='0' src='images/ico_liens.gif' />&nbsp;" . $row->LI_rub . "</td>" ;
        echo "     <td>" . $row->LI_date . "</td>" ;
        echo "     <td><a href='index.php?" . $sid . "affiche=Liens&id=" . $row->LI_uid . "'>" . $row->LI_suj . "</a></td>" ;
        echo "     <td>" . $row->LI_aut . "</td>" ;
        echo "     <td>" . $row->LI_clic . "</td>" ;
        echo "   </tr>" ;
    }
    else if(!isset($_GET["id"]))
    {
        echo "   <tr>" ;
        echo "      <td><img border='0' src='images/ico_liens.gif' />&nbsp;<strong>" . $row->LI_rub . "</strong> " ;
        
        if ($affiche == "Liens-plusrecents")
        {
            echo "(" . $row->LI_date . ")" ;
        }
        else if ($affiche == "Liens-top")
        {
            echo "(" . $row->LI_clic . " " . $Mod_Liens_inde_Clics . ")" ;
        }
        
        echo " : <a href='index.php?" . $sid . "affiche=Liens&id=" . $row->LI_uid . "'>" . $row->LI_suj . "</a></td>" ;
        echo "   </tr>" ;
    }
    else if(isset($_GET["id"]))
    {
        echo "<div align='left'>" ;
        echo "   <table border='0' cellpadding='0' cellspacing='0' width='600'>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><img border='0' src='images/ico_liens.gif' />&nbsp;<strong>" . $row->LI_rub .
             "</strong> : " . $row->LI_suj . "</td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><br />" ;
        echo "     <img border='0' src='images/ico_liens.gif' />&nbsp;<i>( " . $Mod_News_PostPar .
             "<a href='mailto:" . $row->LI_mail . "'>" . $row->LI_aut . "</a> $Mod_News_Le " . $row->LI_date. ", " .
             $row->LI_clic . " " . $Mod_Liens_Clics . " )</i>" ;
        echo "     <br /><br /><br /><br /></td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'>" . $row->LI_cont . "</td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><a href='mod_liens/go.php?URL=" . $row->LI_lien . "&id=" . $row->LI_uid . "'" .
             " target='_blank'>" . $row->LI_lien . "</a></td>" ;
        echo "   </tr>" ;
        echo "   <tr>" ;
        echo "     <td width='100%'><br /><br /><form><input type=button value='Retour' onclick='history.go(-1) ;'></form</td>" ;
        echo "   </tr>" ;
    }
}

echo "</table>" ;
echo "</div>" ;
?>