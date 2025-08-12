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
$ID = $_GET["ID"] ;

echo "<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Rub_Photos</strong><br /><br />" ;
echo "<div align='center'>" .
     "  <table border='0' cellpadding='0' cellspacing='0'>" .
     "    <tr>" .
     "      <td><img border='0' src='mod_photos/copyright.php?ID=$ID'></td>" .
     "    </tr>" .
     "    <tr>" ;

$Photo = mysql_db_query($BD_name,"SELECT * FROM $BD_Tab_photos WHERE PO_code='$ID'") ;
$row = mysql_fetch_object($Photo) ;

echo "      <td><img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;" . $row->PO_titre . "<br />" .
     "          <img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;" . $row->PO_date . "<br />" .
     "          <img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;" . $row->PO_text . "<br />" .
     "      </td>" .
     "    </tr>" .
     "  </table>" .
     "<form>" .
     "  <input type=button value='Retour' onclick='history.go(-1) ;'>" .
     "</form>" .
     "</div>" ;
?>