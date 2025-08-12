<?php
/*******************************************************************************
 * Copyright (C) 2002 CLAIRE Cédric cedric.claire@safari-msi.com
 * http://www.portailphp.com/
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

$chemin = "." ;

if(isset($_GET["id"]))
{
    $contrainte = "FA_uid='" . $_GET["id"] . "'" ;
}
else
{
    $contrainte = 1 ;
}

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_Faq</strong><br /><br />" ;

$res_faq = sql_query("SELECT * FROM `$BD_Tab_faq` WHERE $contrainte ORDER BY FA_cat ASC,FA_que ASC,FA_rep ASC", $sql_id) ;
$nbenr = mysql_num_rows($res_faq) ;

if ($nbenr == 0)
{
    echo "( $nbenr $Rub_Faq )" ;
}
else
{
    echo "( $nbenr $Rub_Faq )<br /><br />" ;
    
    while($row = mysql_fetch_object($res_faq))
    {
        if($categorie_courante != $row->FA_cat)
        {
            echo "<p><img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-cat01.gif' />&nbsp;<strong>" .
                 $row->FA_cat . "</strong></p>" ;
            $categorie_courante = $row->FA_cat ;
        }

        echo "&nbsp;&nbsp;&nbsp;<strong>Q : " . $row->FA_que . "</strong><br />" ;
        echo "&nbsp;&nbsp;&nbsp;<strong>R : </strong>" . $row->FA_rep . "<br /><br />" ;
    }
}
  
if(isset($_GET["id"]))
{
    echo "<br /><br /><form><input type=button value='Retour' onclick='history.go(-1);'></form>" ;
}
?>
