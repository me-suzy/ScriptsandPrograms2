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
if (isset($_GET["min"]) && is_numeric($_GET["min"]))
{
    $min = $_GET["min"] ;
}
else
{
    $min = 0 ;
}

if (isset($_GET["tri"]))
{
    // Sécurité
    switch ($_GET["tri"])
    {
        case "PO_titre" : $tri = "PO_titre" ;
                     break ;
        case "PO_code" : $tri = "PO_code" ;
                     break ;
        case "PO_date" : $tri = "PO_date" ;
                     break ;
        default : $tri = "PO_code" ;
    }

    $_SESSION["Photo_Ordre"] = $tri ;
}
else
{
    $tri = "PO_code" ;
}

if (isset($_GET["dispoL"]) && is_numeric($_GET["dispoL"]))
{
    $dispoL = $_GET["dispoL"] ;
}

if (isset($_GET["dispoH"]) && is_numeric($_GET["dispoH"]))
{
    $dispoH = $_GET["dispoH"] ;
}

if (isset($dispoH) && isset($dispoL))
{
    $_SESSION["Photo_H"] = $dispoH ;
    $_SESSION["Photo_L"] = $dispoL ;
}
 
echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_Photos</strong><br /><br />" ;
echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Photos_Disposition</strong> : " ;
echo "<a href='" . $_SESSION["Page_Courante"] . "&dispoH=2&dispoL=2'>&nbsp;4</a> -" ;
echo "<a href='" . $_SESSION["Page_Courante"] . "&dispoH=2&dispoL=3'>&nbsp;6</a> -" ;
echo "<a href='" . $_SESSION["Page_Courante"] . "&dispoH=2&dispoL=4'>&nbsp;8</a> -" ;
echo "<a href='" . $_SESSION["Page_Courante"] . "&dispoH=3&dispoL=3'>&nbsp;9</a> -" ;
echo "<a href='" . $_SESSION["Page_Courante"] . "&dispoH=3&dispoL=4'>&nbsp;12</a> -" ;
echo "<a href='" . $_SESSION["Page_Courante"] . "&dispoH=4&dispoL=4'>&nbsp;16</a>" ;
echo "<br />" ;
echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Photos_Tri</strong> " ;
echo "<a href='./index.php?affiche=Photo-Galerie&tri=PO_date'>$Mod_Photos_DatP</a> - " ;
echo "<a href='./index.php?affiche=Photo-Galerie&tri=PO_code'>$Mod_Photos_DatM</a> - " ;
echo "<a href='./index.php?affiche=Photo-Galerie&tri=PO_titre'>$Mod_Photos_Titre</a>" ;

if (!$_SESSION["Photo"])
{
    $_SESSION["Photo_Condition"] = "1" ;
    $_SESSION["Photo_Ordre"] = "PO_code" ;
    
    $Photos = mysql_query("SELECT COUNT(*) AS Nb FROM $BD_Tab_photos WHERE " . $_SESSION["Photo_Condition"]) ;
    $row = mysql_fetch_object($Photos) ;
    
    $_SESSION["Photo_Nb"] = $row->Nb ;
    $_SESSION["Photo_H"] = $Photos_VignetteH ;
    $_SESSION["Photo_L"] = $Photos_VignetteL ;
    $_SESSION["Photo"] = true ;
}

$max = $min + ($_SESSION["Photo_H"] * $_SESSION["Photo_L"]) ;

echo "<div align='center'>" ;

$Photos = mysql_query("SELECT * FROM $BD_Tab_photos WHERE " . $_SESSION["Photo_Condition"] . " ORDER BY " . $_SESSION["Photo_Ordre"] . " LIMIT" .
          " $min, $max ") ;

if ($Photos)
{
    $NbVignette = mysql_num_rows($Photos) ;
}
else
{
    $NbVignette = 0 ;
}

echo "<table border='0' cellspacing='30' cellpadding='0'>" ;

$VH = 1 ;
$VL = 1 ; 

while ($VH <= $_SESSION["Photo_H"])
{
    echo "<tr>" ;

    while ($VL <= $_SESSION["Photo_L"])
    {
        if ($row = mysql_fetch_object($Photos))
        {
            $Photo = $row->PO_code . ".jpg" ;
            $Signe = $row->PO_type . ".png" ;
            $size = getimagesize("$chemin/mod_photos/photos/$Photo") ;
            $Dimensions = "[" . $size[0] . "x" . $size[1] . "]" ;
            
            $Taille = filesize("$chemin/mod_photos/photos/$Photo") . " octets" ;

            echo "<td valign='top' align='left'>" .
                 "  <a href='index.php?" . $sid . "affiche=Photo-Photo&ID=" . $row->PO_code . "' ><img border=0 " .
                 " src='mod_photos/vignettes/$Photo' /></a><br />" .
                 "<font size='1'>" . $row->PO_titre . "<br />" .
                 "$Dimensions<br />$Taille</font>" . 
                 "</td>" ;
        }
        
        $VL++ ;
    }

    $VL = 1 ;
    $VH++ ;

    echo "</tr>" ;
}

echo "</table>" ;

echo "Page : " ;

for ($i = 0; $i < $_SESSION["Photo_Nb"]; $i++)
{
    if ($_GET["min"] == $i)
    {
        echo "<strong>" . ($i + 1) . "</strong>, " ;
    }
    else
    {
        echo "<a href='index.php?" . $sid . "affiche=Photo-Galerie&min=" . $i . "'>" . ($i + 1) ."</a>, " ;
    }
}

echo "</div>" ;
?>