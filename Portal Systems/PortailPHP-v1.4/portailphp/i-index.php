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
// Module : News
$resultat = sql_query("SELECT DO_uid FROM `$BD_Tab_docs`", $sql_id) ;
$enr      = mysql_fetch_array($resultat) ;
$nbenr    = mysql_num_rows($resultat) ;

// Module : File
$resultat2 = sql_query("SELECT FI_uid FROM `$BD_Tab_file`", $sql_id) ;
$enr2      = mysql_fetch_array($resultat2) ;
$nbenr2    = mysql_num_rows($resultat2) ;

// Module : Liens
$resultat3 = sql_query("SELECT LI_uid FROM `$BD_Tab_liens`", $sql_id) ;
$enr3      = mysql_fetch_array($resultat3) ;
$nbenr3    = mysql_num_rows($resultat3) ;

// Module : Faq
$resultat4 = sql_query("SELECT FA_uid FROM `$BD_Tab_faq`", $sql_id) ;
$enr4      = mysql_fetch_array($resultat4) ;
$nbenr4    = mysql_num_rows($resultat4) ;

// Module : Photos
$resultat5 = sql_query("SELECT PO_uid FROM `$BD_Tab_photos`", $sql_id) ;
$enr5      = mysql_fetch_array($resultat5) ;
$nbenr5    = mysql_num_rows($resultat5) ;

// Module : Forum
$resultat6 = sql_query("SELECT id FROM `$BD_Tab_forum` WHERE reponse_a_id='0'", $sql_id) ;
$enr6      = mysql_fetch_array($resultat6) ;
$nbenr6    = mysql_num_rows($resultat6) ;
?>
<div align='left'>
<!-- TABLEAU GENERAL -->
  <table border='0' cellpadding='0' cellspacing='3' width='100%'>
    <tr>
      <!-- TABLEAU GAUCHE DES RUBRIQUES -->
      <td width='150' valign='top' align='center'>
        <table>
          <tr>
            <td width='100%'>
              <?php include "$chemin/menu/navigation.inc.php" ; ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <?php
          // Rubrique : X derniers messages
          if ($App_Affiche_messages)
          {
          ?>
          <tr>
            <td width='100%'>
              <?php include "$chemin/menu/xder.inc.php" ; ?>
            </td>
          </tr>            
          <tr>
            <td>&nbsp;</td>
          </tr>
          <?php
          }
  
          // Rurique : News Externe
          if ($App_Affiche_News_TL)
          {
          ?>
          <tr>
            <td width='100%'>
              <?php include "$chemin/menu/news.inc.php" ; ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <?php
          }
          
          // Rubrique : Webring
          if ($App_Affiche_Webring)
          {
          ?>
          <tr>
            <td width='100%'>
              <?php include "$chemin/menu/webring.inc.php" ; ?>
            </td>
          </tr>            
          <tr>
            <td>&nbsp;</td>
          </tr>
          <?php
          }
          ?>
        </table>
      </td>
      <!-- FIN TABLEAU GAUCHE DES RUBRIQUES -->
      <td width='*' valign='top' align='left'>
        <table width='100%'>
          <tr>
            <td>
              <!-- Logo du site -->
              <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                <tr>
                  <td bgcolor='<?php echo $App_Couleur ; ?>' align='center' valign='top'>
                    <a href='index.php' target='_top'><img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/logo.gif' /></a>
                  </td>
               </tr>
             </table>
             
             <div align='center'>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />
               <a href='index.php?<?php echo $sid ; ?>' target='_top'><?php echo $Rub_Home ; ?></a>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />

               <?php
               if (!$_SESSION["Admin"])
               {
               ?>
               <a href='index.php?<?php echo $sid ; ?>affiche=Admin' target='_top'><?php echo $Mod_Membres_Login ; ?></a>
               <?php
               }
               else
               {
               ?>
               <a href='mod_membre/script-deconnect.php?<?php echo $sid ; ?>' target='_top'><?php echo $Mod_Membres_Accueil_OFF ; ?></a>
               <?php
               }
               ?>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />
               <a href='index.php?<?php echo $sid ; ?>affiche=News' target='_top'><?php echo $Rub_News ; ?></a>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />
               <a href='index.php?<?php echo $sid ; ?>affiche=File' target='_top'><?php echo $Rub_File ; ?></a>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />
               <a href='index.php?<?php echo $sid ; ?>affiche=Liens' target='_top'><?php echo $Rub_Liens ; ?></a>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />
               <a href='index.php?<?php echo $sid ; ?>affiche=Faq' target='_top'><?php echo $Rub_Faq ; ?></a>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />
               <a href='index.php?<?php echo $sid ; ?>affiche=Forum-Lire' target='_top'><?php echo $Rub_Forum ; ?></a>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />
               <a href='index.php?<?php echo $sid ; ?>affiche=Photo-Galerie' target='_top'><?php echo $Rub_Photos ; ?></a>
               <img border='0' src='<?php echo $chemin ; ?>/themes/<?php echo $_SESSION["App_Theme"] ; ?>/ico-puce01.gif' />
               <a href='index.php?<?php echo $sid ; ?>affiche=Search' target='_top'><?php echo $Rub_Search ; ?></a>
               <br /><br /><br />
             </div>
           </td>
         </tr>
         <tr>
           <td>
           <?php  
               switch($_GET['affiche'])
               {
                   case "News": 
                               $_SESSION["News_Ordre"] = "DO_suj ASC" ;
                               include("$chemin/mod_news/index.php") ;
                               break ;
                   case "News-plusrecents" :
                                            $_SESSION["News_Ordre"] = "DO_date DESC,DO_suj ASC" ;
                                            include("$chemin/mod_news/index.php") ;
                                            break ;
                   case "News-pluslus" : 
                                        $_SESSION["News_Ordre"] = "DO_lect DESC,DO_suj ASC" ;
                                        include("$chemin/mod_news/index.php") ;
                                        break ;
                   case "File" : 
                                $_SESSION["File_Ordre"] = "FI_titre ASC" ;
                                include("$chemin/mod_file/index.php") ;
                                break ;
                   case "File-plusclics" : 
                                          $_SESSION["File_Ordre"] = "FI_lect DESC,FI_titre ASC" ;
                                          include("$chemin/mod_file/index.php") ;
                                          break ;
                   case "File-plusrecents" : 
                                             $_SESSION["File_Ordre"] = "FI_date DESC,FI_titre ASC" ;
                                             include("$chemin/mod_file/index.php") ;
                                             break ;
                   case "File-Upload" : 
                                       include("$chemin/mod_file/upload.php") ;
                                       break ;
                   case "Liens" : 
                                 $_SESSION["Liens_Ordre"] = "LI_suj ASC" ;
                                 include("$chemin/mod_liens/index.php") ;
                                 break ;
                   case "Liens-plusrecents" :
                                             $_SESSION["Liens_Ordre"] = "LI_date DESC,LI_suj ASC" ;
                                             include("$chemin/mod_liens/index.php") ;
                                             break ;
                   case "Liens-top" : 
                                     $_SESSION["Liens_Ordre"] = "LI_clic DESC,LI_suj ASC" ;
                                     include("$chemin/mod_liens/index.php") ;
                                     break ;
                   case "Liens-Proposer" : 
                                           include("$chemin/mod_liens/liens.php") ;
                                           break ;
                   case "Faq" : 
                                include("$chemin/mod_faq/index.php") ;
                                break ;
                   case "Search" : 
                                  include("$chemin/mod_search/index.php") ;
                                  break ;
                   case "Photo-Galerie" : 
                                         include("$chemin/mod_photos/galerie.php") ;
                                         break ;
                   case "Photo-Photo" : 
                                       include("$chemin/mod_photos/affichage.php") ;
                                       break ;    //read_mess.php3
                   case "Forum-Lire":
                                     include("$chemin/mod_forum/index.php") ;
                                     break ;
                   case "Forum-box_aj_me":
                                          include("$chemin/mod_forum/box_aj_me.php") ;
                                          break ;
                   case "Forum-read_mess" :
                                           include("$chemin/mod_forum/read_mess.php") ;
                                           break ;
                   case "Forum-ajouter" :
                                         include("$chemin/mod_forum/ajouter.php") ;
                                         break ;
                   case "Forum-box_aj_rep" :
                                             include("$chemin/mod_forum/box_aj_rep.php") ;
                                             break ;
                   case "Forum-archives" :
                                           include("$chemin/mod_forum/archives.php") ;
                                           break ;
                   case "Forum-box_arc":
                                        include("$chemin/mod_forum/box_arc.php") ;
                                        break ;
                   case "Admin" : 
                                 include("$chemin/mod_membre/login.php") ;
                                  break ;
                   case "Mon-Profil" :
                                 include("$chemin/mod_membre/my-profil.php") ;
                                  break ;
                   case "Del-Msg-Forum" :
                                 include("$chemin/mod_forum/del-msg.php") ;
                                  break ;
                   default:
                           include("$chemin/i-accueil.php") ;
               }
               
               $chemin = "." ;
           ?>    
           </td>
         </tr>
       </table>
     </td>
     <!-- TABLEAU DROIT DES RUBRIQUES -->
     <td width='150' valign='top' align='center'>
       <table>
         <?php
             // Rubrique : Partenaires
             if($App_Affiche_Param)
             {
         ?>
         <tr>
           <td width='100%'>
             <?php include "$chemin/menu/parametres.inc.php" ; ?>
           </td>
         </tr>            
         <tr>
           <td>&nbsp;</td>
         </tr>
         <?php
             }
  
             // Rubrique : Partenaires
             if ($App_Affiche_Pub)
             {
         ?>    
         <tr>
           <td width='100%'>
             <?php include "$chemin/menu/partenaires.inc.php" ; ?>
           </td>
         </tr>            
         <tr>
           <td>&nbsp;</td>
         </tr>
         <?php
             }
           
             // Rubrique : PinUp
             if ($App_Affiche_PinUp)
             {
         ?>
         <tr>
           <td width='100%'>
             <?php include "$chemin/menu/pinup.inc.php" ; ?>
           </td>
         </tr>
         <tr>
           <td>&nbsp;</td>
         </tr>
         <?php
            }
         ?>
       </table>
     </td>
     <!-- FIN TABLEAU DROIT DES RUBRIQUES -->
  </tr>
  <tr>
    <td colspan="3" align="center">
      <?php include("$chemin/licence.php") ; ?>
    </td>
  </tr>
</table>
<!-- FIN : TABLEAU GENERAL -->
</div>
