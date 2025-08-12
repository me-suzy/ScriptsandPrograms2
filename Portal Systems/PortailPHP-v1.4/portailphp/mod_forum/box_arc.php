<?php
/*******************************************************************************
 * Copyright (C) 2004 Martineau Emeric
 *
 * Script original LightForum v1.0 © Octobre 2000 - Thaal-Rasha 
 *
 * Rewritten from scratch by Martineau Emeric
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
 * La présente Licence Publique Générale n'autorise pas le concessionnaire à
 * incorporer son programme dans des programmes propriétaires. Si votre programme
 * est une bibliothèque de sous-programmes, vous pouvez considérer comme plus
 * intéressant d'autoriser une édition de liens des applications propriétaires
 * avec la bibliothèque. Si c'est ce que vous souhaitez, vous devrez utiliser non
 * pas la présente licence, mais la Licence Publique Générale pour Bibliothèques GNU.
 ***********************************************************************************/
?>
<form action="index.php?<?php echo $sid ; ?>affiche=Forum-archives" method="post">
  <div align="<?php echo $align_boxarc ; ?>">
    <?php echo $Mod_Forum_Search ; ?> : <input type="text" name="search" size="20" value="tapez un mot" style="font-size: 8 pt; background-color: <?php echo $bgcolor_box ; ?>"> <input type="submit" value="ok" style="font-size: 8 pt">
  </div>
</form>