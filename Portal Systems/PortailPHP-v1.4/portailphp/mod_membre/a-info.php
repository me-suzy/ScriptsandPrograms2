<?php
/*
Copyright (C) 2002 CLAIRE Cédric claced@m6net.fr http://www.yoopla.net/portailphp/
Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier conformément aux dispositions de la Licence Publique Générale GNU, telle que publiée par la Free Software Foundation ; version 2 de la licence, ou encore (à votre choix) toute version ultérieure.
Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE GARANTIE ; sans même la garantie implicite de COMMERCIALISATION ou D'ADAPTATION A UN OBJET PARTICULIER. Pour plus de détail, voir la Licence Publique Générale GNU .
Vous devez avoir reçu un exemplaire de la Licence Publique Générale GNU en même temps que ce programme ; si ce n'est pas le cas, écrivez à la Free Software Foundation Inc., 675 Mass Ave, Cambridge, MA 02139, Etats-Unis.
Portail PHP
La présente Licence Publique Générale n'autorise pas le concessionnaire à incorporer son programme dans des programmes propriétaires. Si votre programme est une bibliothèque de sous-programmes, vous pouvez considérer comme plus intéressant d'autoriser une édition de liens des applications propriétaires avec la bibliothèque. Si c'est ce que vous souhaitez, vous devrez utiliser non pas la présente licence, mais la Licence Publique Générale pour Bibliothèques GNU.
*/
if (!$_SESSION["Admin"]) die("<strong>INTERDIT</strong>") ;

echo("<img border='0' src='themes/" . $_SESSION["App_Theme"] . "/ico-puce01.gif' />&nbsp;<strong>$Mod_Membres_Rub_Info</strong><br /><br />");
echo("<br />$Mod_Membres_InfoUser<br /><br />");
echo("<div align='left'>");
echo("  <table border='0' cellpadding='0' cellspacing='0' width='600'>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Nom</strong></td>");
echo("      <td width='450'>" . $_SESSION["Admin_Nom"] . "</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Pseudo</strong></td>");
echo("      <td width='450'><a href='mailto:" . $_SESSION["Admin_Mail"] . "'>" . $_SESSION["Admin_Pseudo"] . "</a></td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Mail</strong></td>");
echo("      <td width='450'>" . $_SESSION["Admin_Mail"] . "</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Date</strong></td>");
echo("      <td width='450'>" . $_SESSION["Admin_RegDatel"] . "</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Droit</strong></td>");
echo("      <td width='450'>" . $_SESSION["Admin_Droit"] . "</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Web</strong></td>");
echo("      <td width='450'><a href='" . $_SESSION["Admin_Web"] . "' target='_blank'>" . $_SESSION["Admin_Web"] . "</a></td>");
echo("    </tr>");
echo("  </table>");
echo("</div>");
echo("<br />$Mod_Membres_Info<br /><br />");
echo("<div align='left'>");
echo("  <table border='0' cellpadding='0' cellspacing='0' width='600'>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Info_Theme</strong></td>");
echo("      <td width='450'>" . $_SESSION["App_Theme"] . "</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Info_Langue</strong></td>");
echo("      <td width='450'>" . $_SESSION["App_Langue"] . "</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Info_Titre</strong></td>");
echo("      <td width='450'>$App_Me_Titre</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Info_Version</strong></td>");
echo("      <td width='450'>$App_version</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Info_Couleur</strong></td>");
echo("      <td width='450'>$App_Couleur</td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Info_URL</strong></td>");
echo("      <td width='450'><a href='$App_Me_URL' target='_blank'>$App_Me_URL</a></td>");
echo("    </tr>");
echo("    <tr>");
echo("      <td width='150'><strong>$Mod_Membres_Info_UploadLimite</strong></td>");
echo("      <td width='450'>$Mod_File_Taille</td>");
echo("    </tr>");
echo("  </table>");
echo("</div>");
echo("<br />$Mod_Membres_Serveur_Info<br /><br />");

phpinfo();

?>