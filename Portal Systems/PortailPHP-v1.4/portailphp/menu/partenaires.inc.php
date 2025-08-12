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
?>
<table width='102%'>
  <tr>
    <td width='100%' class='tabTitre'  valign='middle' align='center'>
      <strong><?php echo $Lib_Rub_Parten ; ?></strong>
    </td>
  </tr>
  <tr>
    <td width='100%' class='tabMenu'  valign='middle' align='center'>
      <form method='GET' action='http://www.yoopla.net/?'>
        <a href='http://www.yoopla.net' target='_blank'><img border='0' src='<?php echo $chemin ; ?>/images/img_yoopla.gif' /></a><br />
        <input type='text' name='q' size='10'><br /><input type='submit' value='recherche'><br />
        <input type='hidden' name='idsession' value='1'>
        <font size='1'>avec <a href='http://www.yoopla.net' target='_blank'>Yoopla.net</a><br /></font>
      </form>
      <a href='http://www.phpsecure.info' target='_blank'><img border='0' src='<?php echo $chemin ; ?>/images/part_phpsecure.gif' /></a><br /><br />
      <a href='http://php4php.free.fr' target='_blank'><img border='0' src='<?php echo $chemin ; ?>/images/part_php4php.gif' /></a><br /><br />
<?php
// ---------------------------
// Début du code Comscripts
//
$url = "http://partner.comscripts.com/cscount.php?" .
       // L'identifiant de votre script (obligatoire)
       "id=1014";
// Transmission des infos
@readfile($url);
//
// Fin du code Comscripts
// ---------------------------
?>
      <BR>
	  <script language='JavaScript' src='http://www.lalorraine.net/photo.js'></script>
    </td>
  </tr>
</table>
