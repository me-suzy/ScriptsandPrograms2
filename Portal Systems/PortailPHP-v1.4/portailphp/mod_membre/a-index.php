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
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
  <tr>
    <td colspan='2' bgcolor='<?php echo $App_Couleur ; ?>' align='left' valign='top'>
      <a href='index.php' target='_top'><img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/logo.gif' /></a>
    </td>
  </tr>
  <tr>
    <td colspan='2' align="center">
      <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-barre01.gif' /><br />
    </td>
 </tr>
 <tr>
   <td valign="top" align="left">
     <!-- Menu --> 
     <?php echo $Mod_Membres_Menu_General ; ?><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>' target='_top'><?php echo $Rub_Home ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin' target='_top'><?php echo $Rub_Membres ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Admin-Info' target='_top'><?php echo $Mod_Membres_Rub_Info ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='<?php echo $chemin ; ?>/index.php?<?php echo $sid ; ?>affiche=Mon-Profil' target='_top'><?php echo $Mon_Profil ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='mod_membre/script-deconnect.php?<?php echo $sid ; ?>' target='_top'><?php echo $Mod_Membres_Accueil_OFF ; ?></a>
     <br /><br />

     <?php echo $Mod_Membres_Menu_News ; ?><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Cat-News' target='_top'><?php echo $Mod_Membres_Rub_News_Cat ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=News-Add' target='_top'><?php echo $Mod_Membres_Rub_News_Add ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=News-Edit' target='_top'><?php echo $Mod_Membres_Rub_News_Edit ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=News-Del' target='_top'><?php echo $Mod_Membres_Rub_News_Del ; ?></a><br />
     <br /><br />

     <?php echo $Mod_Membres_Menu_Fichiers ; ?><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Cat-File' target='_top'><?php echo $Mod_Membres_Rub_File_Cat ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=File-Upload' target='_top'><?php echo $Mod_Membres_Rub_Upload_File ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=File-Del' target='_top'><?php echo $Mod_Membres_Rub_File_Del ; ?></a><br />
     <br /><br />

     <?php echo $Mod_Membres_Menu_Liens ; ?><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Cat-Liens' target='_top'><?php echo $Mod_Membres_Rub_Liens_Cat ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Liens-Proposer' target='_top'><?php echo $Mod_Membres_Rub_Liens_Edit ; ?></a>
     <br /><br />

     <?php echo $Mod_Membres_Menu_Faq ; ?><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Cat-Faqs' target='_top'><?php echo $Mod_Membres_Rub_Faq_Cat ; ?></a>
     <br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Faq-Add' target='_top'><?php echo $Mod_Membres_Rub_Faq_Add ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Faq-Edit' target='_top'><?php echo $Mod_Membres_Rub_Faq_Edit ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Faq-Del' target='_top'><?php echo $Mod_Membres_Rub_Faq_Del ; ?></a>
     <br /><br />

     <?php echo $Mod_Membres_Menu_Photos ; ?><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Photo-Upload' target='_top'><?php echo $Mod_Membres_Rub_Upload_Photo ; ?></a><br />
     <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-puce01.gif' />
     <a href='index.php?<?php echo $sid ; ?>affiche=Admin&admin=Photo-Del' target='_top'><?php echo $Mod_Membres_Rub_Del_Photo ; ?></a>
     <!-- Fin menu -->
   </td>
   <td valign="top" align="left">  
     <!-- Page -->
     <?php
     switch($HTTP_GET_VARS['admin'])
     {
          case "Admin-Info": 
               include("$chemin/mod_membre/a-info.php");
               break;
          case "Cat-News" :
               include("$chemin/mod_membre/a-b-cat-news.php");
               break;
          case "Cat-File" : 
               include("$chemin/mod_membre/a-b-cat-file.php");
               break;
          case "Cat-Liens" : 
               include("$chemin/mod_membre/a-b-cat-liens.php");
               break;
          case "Cat-Faqs" :
               include("$chemin/mod_membre/a-b-cat-faqs.php");
               break;
          case "News-Add" : 
               include("$chemin/mod_membre/a-b-news.php");
               break;
          case "News-Edit": 
               include("$chemin/mod_membre/a-b-news-edit.php");
               break;
          case "News-Del" : 
               include("$chemin/mod_membre/a-b-news-del.php");
               break;
          case "Faq-Add" : 
               include("$chemin/mod_membre/a-b-faq.php");
               break;
          case "Faq-Edit" : 
               include("$chemin/mod_membre/a-b-faq-edit.php");
               break;
          case "Faq-Del": 
               include("$chemin/mod_membre/a-b-faq-del.php");
               break;
          case "Photo-Upload" : 
               include("$chemin/mod_membre/a-b-photo-upload.php");
               break;
          case "Photo-Del" :
               include("$chemin/mod_membre/a-b-photo-del.php");
               break;
          case "File-Upload" : 
               include("$chemin/mod_membre/a-b-file-upload.php");
               break;
          case "File-Del" :
               include("$chemin/mod_membre/a-b-file-del.php");
               break;
          case "Liens-Proposer" : 
               include("$chemin/mod_membre/a-b-liens-edit.php");
               break;
          default:
               include("$chemin/mod_membre/a-accueil.php");
     }
     ?>     
     <!-- Fin page -->
    </td>
  </tr>
  <tr>
    <td colspan='2' align="center">
      <img border='0' src='<?php echo "themes/" . $_SESSION["App_Theme"]; ?>/ico-barre01.gif' /><br />
    </td>
 </tr>  
</table>