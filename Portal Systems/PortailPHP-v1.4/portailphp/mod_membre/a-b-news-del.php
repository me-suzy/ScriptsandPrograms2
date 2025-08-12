<?php
/*******************************************************************************
 * Copyright (C) 2004 MARTINEAU EMERIC
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
if (!$_SESSION["Admin"]) die("<strong>INTERDIT</strong>") ;

if (isset($_POST["supprimer"]) && isset($_GET["id"]))
{
    $q = mysql_query("DELETE FROM $BD_Tab_docs WHERE DO_uid='" . $_GET["id"] . "'") ;

    if ($q)
    {
        echo "<strong>OK</strong>" ;
    }
    else
    {
        echo "<strong>Failure</strong>" ;
    }
}
else if ((!isset($_POST["supprimer"])) && isset($_GET["id"]))
{
    echo $Mod_Membres_Rub_News_question . $_GET["titre"] . "<br />" ;

    echo "<form action='index.php?" . $sid . "affiche=Admin&admin=News-Del&id=" . $_GET["id"] . "' method='post'><input name='supprimer' type='submit' value='$Mod_Membres_Rub_News_Del2' /></form>" ;
}
else
{
    echo $Mod_Membres_Rub_News ;
}
?>