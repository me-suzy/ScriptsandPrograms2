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
if (!$_SESSION["Admin"]) die("<strong>INTERDIT</strong>") ;
?>
<img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />&nbsp;
<strong><?php echo $Mod_Membres_Rub_Accueil ; ?></strong><br /><br />

<div align='left'>
  <table border='0' cellpadding='0' cellspacing='0' width='600'>
    <tr>
     <td width='150'><strong><?php echo $_SESSION["Admin_Nom "]; ?></strong></td>
      <td width='450'><?php echo $_SESSION["Admin_Nom"] ; ?></td>
    </tr>
    <tr>
      <td width='150'><strong><?php echo $Mod_Membres_Pseudo ; ?></strong></td>
      <td width='450'><a href='mailto:<?php echo $_SESSION["Admin_Mail"] ; ?>'><?php echo $_SESSION["Admin_Pseudo"] ; ?></a></td>
    </tr>
    <tr>
      <td width='150'><strong><?php echo $Mod_Membres_Mail ; ?></strong></td>
      <td width='450'><?php echo $_SESSION["Admin_Mail"] ; ?></td>
    </tr>
    <tr>
      <td width='150'><strong><?php echo $Mod_Membres_Date ; ?></strong></td>
      <td width='450'><?php echo $_SESSION["Admin_RegDatel"] ; ?></td>
    </tr>
    <tr>
      <td width='150'><strong><?php echo $Mod_Membres_Droit ; ?></strong></td>
      <td width='450'><?php echo $_SESSION["Admin_Droit"] ; ?></td>
    </tr>
    <tr>
      <td width='150'><strong><?php echo $Mod_Membres_Web ; ?></strong></td>
      <td width='450'><a href='<?php echo $_SESSION["Admin_Web"] ; ?>' target='_blank'><?php echo $_SESSION["Admin_Web"] ; ?></a></td>
    </tr>
  </table>
</div>