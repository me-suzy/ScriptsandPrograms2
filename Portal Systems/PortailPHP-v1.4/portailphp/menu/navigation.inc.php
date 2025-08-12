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
    <td width='100%' class='tabTitre'  valign='middle' align='center'>
      <strong><?php echo $Lib_Rub_Navig ; ?></strong>
    </td>
  </tr>
  <tr>
    <td width='100%' class='tabMenu'  valign='top' align='left'>
      <table width='100%' border='0'>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>' target='_top'><?php echo $Rub_Home ; ?></a></td>
        </tr>
        <?php
        if ($_SESSION["Admin"])
        {
        ?>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Admin' target='_top'><?php echo $Rub_Membres ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Mon-Profil' target='_top'><?php echo $Mon_Profil ; ?></a></td>
        </tr>
        <?php
        }
        ?>

        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=News' target='_top'><?php echo "$Rub_News</a> ($nbenr)" ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo "$chemin" ; ?>/index.php?<?php echo $sid ; ?>affiche=News-pluslus' target='_top'><?php echo $SsRub_News_pluslus ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=News-plusrecents' target='_top'><?php echo $SsRub_News_plusrecents ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=File' target='_top'><?php echo "$Rub_File</a> ($nbenr2)" ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=File-plusclics' target='_top'><?php echo $SsRub_File_plusclics ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=File-plusrecents' target='_top'><?php echo $SsRub_File_plusrecents ; ?></a></td>
        </tr>
        <?php
        if($Aut_File)
        {
        ?>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=File-Upload' target='_top'><?php echo $SsRub_File_Proposer ; ?></a></td>
        </tr>
        <?php
        }
        ?>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Liens' target='_top'><?php echo "$Rub_Liens</a> ($nbenr3)" ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Liens-top' target='_top'><?php echo $SsRub_Liens_top ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Liens-plusrecents' target='_top'><?php echo $SsRub_Liens_plusrecents ; ?></a></td>
        </tr>
        <?php
        if ($Aut_Liens)
        {
        ?>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Faq' target='_top'><?php echo "$Rub_Faq</a> ($nbenr4)" ; ?></a></td>
        </tr>
        <?php
        }
        ?>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Faq' target='_top'><?php echo "$Rub_Faq</a> ($nbenr4)" ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Photo-Galerie' target='_top'><?php echo "$Rub_Photos</a> ($nbenr5)" ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Forum-Lire' target='_top'><?php echo "$Rub_Forum</a> ($nbenr6)" ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01-vide.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Forum-box_aj_me' target='_top'><?php echo $Mod_Forum_Ajouter ; ?></a></td>
        </tr>
        <tr>
          <td witdh='1'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' /></td>
          <td width='100%'><a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Search' target='_top'><?php echo $Rub_Search ; ?></a></td>
        </tr>
        <tr>
          <td colspan='2' align='center'><a href='http://www.portailphp.com'><img border='0' src='<?php echo $chemin ; ?>/images/logo-pphp.gif' /></a></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
