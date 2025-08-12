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
<table width='100%'>
  <tr>
    <td width='100%' class='tabTitre' valign='middle' align='center'>
      <strong><?php echo $Lib_Rub_Param ; ?></strong>
    </td>
  </tr>
  <tr>
    <td  class='tabMenu' valign='top' align='center'>
      <form name='chx_theme' action='<?php echo $_SESSION["Page_Courante"] ; ?>' method='post'>
        <select name='New_Theme'>
        <?php
          $les_themes = dir("./themes") ;

          while ($le_theme=$les_themes->read())
          {
              if (($le_theme != ".") && ($le_theme != ".."))
              {
                  if ($le_theme == $_SESSION["App_Theme"])
                  {
                      echo "<option value=\"$le_theme\" selected>$le_theme</option>" ;
                  }
                  else
                  {
                      echo "<option value=\"$le_theme\">$le_theme</option>" ;
                  }
              }
          }
          
          $les_themes->close() ;
        ?>          
        </select>&nbsp;<input type='submit' value='OK'><br />
        <a href='<?php echo $_SESSION["Page_Courante"] ; ?>&New_Langue=lang-french' ><img border='0' src='<?php echo $chemin ; ?>/images/flag_fr.png' /></a>
        <a href='<?php echo $_SESSION["Page_Courante"] ; ?>&New_Langue=lang-english' ><img border='0' src='<?php echo $chemin ; ?>/images/flag_uk.png' /></a>
        <a href='<?php echo $_SESSION["Page_Courante"] ; ?>&New_Langue=lang-spanish' ><img border='0' src='<?php echo $chemin ; ?>/images/flag_es.png' /></a>
        <a href='<?php echo $_SESSION["Page_Courante"] ; ?>&New_Langue=lang-italian' ><img border='0' src='<?php echo $chemin ; ?>/images/flag_it.png' /></a>
        <a href='<?php echo $_SESSION["Page_Courante"] ; ?>&New_Langue=lang-portuguese' ><img border='0' src='<?php echo $chemin ; ?>/images/flag_po.png' /></a>
        <a href='<?php echo $_SESSION["Page_Courante"] ; ?>&New_Langue=lang-dutch' ><img border='0' src='<?php echo $chemin ; ?>/images/flag_du.png' /></a>
        <br />
        <?php echo $Date ; ?>&nbsp;-&nbsp;<strong><?php echo $App_Me_Titre ; ?></strong><br /><?php echo $App_version ; ?>
      </form>  
    </td>       
  </tr>
</table>
