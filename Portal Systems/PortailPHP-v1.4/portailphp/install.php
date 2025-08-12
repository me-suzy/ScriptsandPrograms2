<?php
/*******************************************************************************
 * Copyright (C) 2004 Martineau Emeric
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title>Installation de Portail PHP Secure</title>
    <style>
body
{
font-family: Arial; font-size: 12px; color: #1C2D67;
}
TD
{
font-family: Arial; font-size: 12px; color: #1C2D67;
}
A
{
font-family:Arial;font-size:12px;color:#FFFFFF;text-decoration:none;
}
A:link
{
font-family:Arial;font-size:12px;color:#003FFF;
}
A:visited
{
font-family:Arial;font-size:12px;color:#003FFF;
}
A:hover
{
font-family:Arial;font-size:12px;color:#333399;
}
A.off
{
font-family:Arial;font-size:12px;color:#C0D780;
}

.tabMenu
{
PADDING-TOP: 0px;
PADDING-BOTTOM: 0px;
PADDING-LEFT: 0px;
PADDING-RIGHT: 0px;
BORDER-TOP: #1266BE 1px solid;
BORDER-BOTTOM: #1266BE 1px solid;
BORDER-LEFT: #1266BE 1px solid;
BORDER-RIGHT: #1266BE 1px solid;
FLOAT: none;
MARGIN: 0px; 
BACKGROUND-COLOR: #f1f1f1;
}

.tabTitre
{
font-family: Arial;
font-size: 14px;
color: #ffffff;
PADDING-TOP: 0px;
PADDING-BOTTOM: 0px;
PADDING-LEFT: 0px;
PADDING-RIGHT: 0px;
BORDER-TOP: #1266BE 1px solid;
BORDER-BOTTOM: #1266BE 1px solid;
BORDER-LEFT: #1266BE 1px solid;
BORDER-RIGHT: #1266BE 1px solid;
FLOAT: none;
MARGIN: 0px; 
BACKGROUND-COLOR: #B0C8E0 ;
}  
  </style>
  
</head>

<body>

<table width='600' align='center' cellpadding='0' cellspacing='3'>
  <tr>
    <td width='100%' class='tabTitre'  valign='middle' align='center'>
      <strong><font size=4>I</font>NSTALLATION <font size=4>D</font>E <font size=4>P</font>ORTAILPHP</strong>
    </td>
  </tr>
  <tr>
    <td width='100%' class='tabMenu' valign='middle' align='left'>
      <table align='center'>
        <tr>
          <td>
            <form action='install.php' method='post'>
          <?php
//          if (!file_exists("include/config.php"))
          if (1)
          {
              if (isset($_POST["valid_db_config"]))
              {
                  // Essai de connection
                  if (@mysql_connect($_POST["host"], $_POST["login"], $_POST["pass"]))
                  {
                      // Essai de sélection de la base
                      if (@mysql_select_db($_POST["db"]))
                      {
                          $fd = fopen("include/config.tmp", "w+") ;

                          if ($fd)
                          {
                              fwrite($fd, '<') ;
                              fwrite($fd, "?php\n") ;
                              fwrite($fd, "\n");
                              fwrite($fd, "set_magic_quotes_runtime(0);\n") ;
                              fwrite($fd, "\n");
                              fwrite($fd, "// Paramètre de connexion à la base de données\n") ;
                              fwrite($fd, "\$BD_host='" . $_POST["host"] . "';\n") ;
                              fwrite($fd, "\$BD_user='" . $_POST["login"] . "';\n") ;
                              fwrite($fd, "\$BD_pass='" . $_POST["pass"] . "';\n") ;
                              fwrite($fd, "\$BD_name='" . $_POST["db"] . "';\n") ;
                              fwrite($fd, "\n") ;

                              fclose($fd) ;

                              // Affichage du formulaire de config
                              ?>
                              <strong>Saisissez les noms des tables :</strong>
                              <table>
                                <tr>
                                  <td>
                                    Tables des comptes utilisateurs  :
                                  </td>
                                  <td>
                                    <input type='text' name='user_table' value='pphp_user' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des articles  :
                                  </td>
                                  <td>
                                    <input type='text' name='docs_table' value='pphp_docs' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des cat&eacute;gories des articles  :
                                  </td>
                                  <td>
                                    <input type='text' name='cat_docs_table' value='pphp_docs_cat' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des t&eacute;l&eacute;chargements  :
                                  </td>
                                  <td>
                                    <input type='text' name='file_table' value='pphp_file' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des cat&eacute;gories des t&eacute;l&eacute;chargements  :
                                  </td>
                                  <td>
                                    <input type='text' name='cat_file_table' value='pphp_file_cat' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des liens  :
                                  </td>
                                  <td>
                                    <input type='text' name='links_table' value='pphp_liens' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des cat&eacute;gories des liens  :
                                  </td>
                                  <td>
                                    <input type='text' name='cat_links_table' value='pphp_liens_cat' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des faq  :
                                  </td>
                                  <td>
                                    <input type='text' name='faq_table' value='pphp_faq' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des cat&eacute;gories des faq  :
                                  </td>
                                  <td>
                                    <input type='text' name='cat_faq_table' value='pphp_faq_cat' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables des photos  :
                                  </td>
                                  <td>
                                    <input type='text' name='photos_table' value='pphp_photos' />
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Tables du forum  :
                                  </td>
                                  <td>
                                    <input type='text' name='forum_table' value='pphp_forum' />
                                  </td>
                                </tr>
                              </table>
                              <input name='valid_table_db_config' type='submit' value='Valider' />
                              <?php
                          }
                          else
                          {
                              ?>
                              Erreur ! V&eacute;rifier les droits d'acc&egrave;s au r&eacute;pertoire <strong>include</strong> et v&eacute;rifier que le fichier <strong>config.db.conf</strong> n'existe pas au qu'il y ai les droits d'acc&egrave;s.<br />
                              Installation annul&eacute;e.
                              <?php
                          }
                      }
                      else
                      {
                          ?>
                          Le nom de la base de données fournie est incorrecte.<br /><br />
                          <input type='hidden' name='login' value='<?php echo (isset($_POST["login"]) ? $_POST["login"] : "") ; ?>' />
                          <input type='hidden' name='pass' value='<?php echo (isset($_POST["pass"]) ? $_POST["pass"] : "") ; ?>' />
                          <input type='hidden' name='host' value='<?php echo (isset($_POST["host"]) ? $_POST["host"] : "") ; ?>' />
                          <input type='hidden' name='db' value='<?php echo (isset($_POST["db"]) ? $_POST["db"] : "") ; ?>' />
                          <input name='e' type='submit' value='Retour' />
                          <?php
                      }
                  }
                  else
                  {
                      ?>
                      Les informations (mot de passe, login, host) pour la connexion &agrave; la base de donn&eacute;es sont &eacute;ronn&eacute;es.<br /><br />
                      <input type='hidden' name='login' value='<?php echo (isset($_POST["login"]) ? $_POST["login"] : "") ; ?>' />
                      <input type='hidden' name='pass' value='<?php echo (isset($_POST["pass"]) ? $_POST["pass"] : "") ; ?>' />
                      <input type='hidden' name='host' value='<?php echo (isset($_POST["host"]) ? $_POST["host"] : "") ; ?>' />
                      <input type='hidden' name='db' value='<?php echo (isset($_POST["db"]) ? $_POST["db"] : "") ; ?>' />
                      <input name='e' type='submit' value='Retour' />
                      <?php
                  }
              }
              elseif (isset($_POST["valid_table_db_config"]))
              {
                  $fd = @fopen("include/config.tmp", "a") ;

                  if ($fd)
                  {
                      fwrite($fd, "// Paramètre des tables de la base de données\n") ;
                      fwrite($fd, "\$BD_Tab_user='" . $_POST["user_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_docs='" . $_POST["docs_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_docs_cat='" . $_POST["cat_docs_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_file='" . $_POST["file_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_file_cat='" . $_POST["cat_file_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_liens='" . $_POST["links_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_liens_cat='" . $_POST["cat_links_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_faq='" . $_POST["faq_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_faq_cat='" . $_POST["cat_faq_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_photos='" . $_POST["photos_table"] . "';\n") ;
                      fwrite($fd, "\$BD_Tab_forum='" . $_POST["forum_table"] . "';\n") ;
                      fwrite($fd, "\n") ;

                      fclose($fd) ;

                      // Affichage formulaire
                      ?>
                      <strong>Configurer les options du portail :</strong>
                      <table>
                        <tr>
                          <td>
                            Format de la date du jour  :
                          </td>
                          <td>
                            <input type='text' name='format_date_du_jour' value='d/m/Y' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Th&egrave;me par d&eacute;faut  :
                          </td>
                          <td>
                            <select name='theme_par_defaut'>
                            <?php
                            $handle = opendir("themes") ;

                            if ($handle)
                            {
                                while ($file = readdir($handle))
                                {
                                    if (!ereg("^\.", $file))
                                    {
                                        echo "<option value='" . htmlentities($file) . "'>" . htmlentities($file) . "</option>" ;
                                    }
                                }
                            }
                            ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Langue par d&eacute;faut  :
                          </td>
                          <td>
                            <select name='lang_par_defaut'>
                            <?php
                            $handle = opendir("include") ;

                            if ($handle)
                            {
                                while ($file = readdir($handle))
                                {
                                    if (ereg("^lang-", $file))
                                    {
                                        echo "<option value='" . htmlentities($file) . "'>" ;

                                        echo htmlentities(eregi_replace("lang-([[:alnum:]]+)\.php", "\\1", $file)) ;

                                        echo "</option>" ;
                                    }
                                }
                            }
                            ?>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Version de l'application  :
                          </td>
                          <td>
                            <input type='text' name='version_application' value='v1.2.1' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Couleur du fond de page  :
                          </td>
                          <td>
                            <input type='text' name='app_couleur' value='#FFFFFF' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Nombre de vignette en hauter  :
                          </td>
                          <td>
                            <input type='text' name='nb_vignetteH' value='2' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Nombre de vignette en largeur  :
                          </td>
                          <td>
                            <input type='text' name='nb_vignetteL' value='3' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Niveau de report d'erreur  :
                          </td>
                          <td>
                            <input type='text' name='eror_level' value='E_ALL & ~E_NOTICE' />
                          </td>
                        </tr>
                        <tr>
                          <td colspan='2'>
                            <br /><strong>Param&egrave;tre Meta du site :</strong>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Titre du site  :
                          </td>
                          <td>
                            <input type='text' name='titre_site' value='Portail PHP Secure' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Description du site  :
                          </td>
                          <td>
                            <input type='text' name='description_site' value='Portail PHP Secure est un projet de portail CMS écrit entièrement en PHP lancé par Bubule (CMS signifie Content Management System ( système de gestion de contenu ), c'est à dire que la gestion du contenu se fait en ligne).' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Mots clefs  :
                          </td>
                          <td>
                            <input type='text' name='keyword_site' value='portail,dynamique,php,php4,portailphp,linux,cms,CMS,news,nuke,xoops,hacker,gestion,contenu,download,software,linux,windows,easyphp,phpcoder,gpl,gratuit,open,libre,scripts,nuke' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Copyright  :
                          </td>
                          <td>
                            <input type='text' name='copyright_site' value='www.portailphp.com' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Url du site  :
                          </td>
                          <td>
                            <input type='text' name='url_site' value='http://www.portailphp.com' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Date de cr&eacute;ation du site  :
                          </td>
                          <td>
                            <input type='text' name='dateC_site' value='<?php echo date("Ymd") ; ?>' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Date de modification du site  :
                          </td>
                          <td>
                            <input type='text' name='dateM_site' value='<?php echo date("Ymd") ; ?>' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Nom du webmaster  :
                          </td>
                          <td>
                            <input type='text' name='webmaster_site' value='MARTINEAU Emeric' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            E-mail du webmaster  :
                          </td>
                          <td>
                            <input type='text' name='webmaster_email_site' value='your_e_mail_adress@your_domain.com' />
                          </td>
                        </tr>
                        <tr>
                          <td colspan='2'>
                            <br /><strong>Param&egrave;tre d'affichage des divers &eacute;l&eacute;ments du site :</strong>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher les news  :
                          </td>
                          <td>
                            <select name='news'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher les t&eacute;l&eacute;chargements  :
                          </td>
                          <td>
                            <select name='download'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher les liens  :
                          </td>
                          <td>
                            <select name='links'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher le moteur de recherche du site  :
                          </td>
                          <td>
                            <select name='search'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher la publicit&eacute;  :
                          </td>
                          <td>
                            <select name='publicite'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher la PinUp (conseill&eacute; :-)  :
                          </td>
                          <td>
                            <select name='pinup'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher le menu partenaires  :
                          </td>
                          <td>
                            <select name='pub'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher le webring :
                          </td>
                          <td>
                            <select name='webring'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher les derniers messages du forum  :
                          </td>
                          <td>
                            <select name='messages'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher les param&egrave;tres  :
                          </td>
                          <td>
                            <select name='param'><option value='1' selected='selected'>Oui</option><option value='0'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Afficher les news TooLinux :
                          </td>
                          <td>
                            <select name='tl'><option value='1'>Oui</option><option value='0' selected='selected'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Autoriser l'ajout de liens (dangereux) :
                          </td>
                          <td>
                            <select name='aut_lien'><option value='1'>Oui</option><option value='0' selected='selected'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Autoriser l'ajout de fichiers (dangereux) :
                          </td>
                          <td>
                            <select name='aut_fichier'><option value='1'>Oui</option><option value='0' selected='selected'>Non</option></select>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='2'>
                            <input type='submit' name='valid_config' value='Valider' />
                          </td>
                        </tr>
                      </table>
                      <?php
                  }
                  else
                  {
                      ?>
                      Erreur ! V&eacute;rifier les droits d'acc&egrave;s au r&eacute;pertoire <strong>include</strong> et v&eacute;rifier que le fichier <strong>config.db.conf</strong> n'existe pas au qu'il y ai les droits d'acc&egrave;s.<br />
                      Installation annul&eacute;e.
                      <?php
                  }
              }
              elseif (isset($_POST["valid_config"]))
              {
                  $fd = @fopen("include/config.tmp", "a") ;

                  if ($fd)
                  {
                      fwrite($fd, "\$Date=date('" . $_POST["format_date_du_jour"] . "');\n") ;
                      fwrite($fd, "\$App_Theme_Defaut='" . $_POST["theme_par_defaut"] . "';\n") ;
                      fwrite($fd, "\$App_Langue_Defaut='" . $_POST["lang_par_defaut"] . "';\n") ;
                      fwrite($fd, "\$App_version='" . $_POST["version_application"] . "';\n") ;
                      fwrite($fd, "\$App_Couleur='" . $_POST["app_couleur"] . "';\n") ;
                      fwrite($fd, "\$Photos_VignetteH=" . $_POST["nb_vignetteH"] . " ;\n") ;
                      fwrite($fd, "\$Photos_VignetteL=" . $_POST["nb_vignetteL"] . " ;\n") ;

                      fwrite($fd, "error_reporting(" . $_POST["eror_level"] . ");\n") ;

                      fwrite($fd, "\$App_Me_Titre='" . $_POST["titre_site"] . "';\n") ;
                      fwrite($fd, "\$App_Me_Desc='" . $_POST["description_site"] . "';\n") ;
                      fwrite($fd, "\$App_Me_KeyWords='" . $_POST["keyword_site"] . "';\n") ;
                      fwrite($fd, "\$App_Me_Copyright='" . $_POST["copyright_site"] . "';\n") ;

                      if (ereg("^.+/\$", $_POST["url_site"] ) != 1)
                      {
                          $_POST["url_site"]  = $_POST["url_site"]  . "/" ;
                      }

                      fwrite($fd, "\$App_Me_URL='" . $_POST["url_site"] . "';\n") ;

                      fwrite($fd, "\$App_Me_DateC='" . $_POST["dateC_site"] . "';\n") ;
                      fwrite($fd, "\$App_Me_DateM='" . $_POST["dateM_site"] . "';\n") ;
                      fwrite($fd, "\$App_Me_WeM='" . $_POST["webmaster_email_site"] . "';\n") ;
                      fwrite($fd, "\$App_Me_WeMmail='" . $_POST["webmaster_email_site"] . "';\n") ;

                      fwrite($fd, "\$App_Affiche_News=" . $_POST["news"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_File=" . $_POST["download"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_Liens=" . $_POST["links"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_Search=" . $_POST["search"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_publicite=" . $_POST["publicite"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_PinUp=" . $_POST["pinup"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_Pub=" . $_POST["pub"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_Webring=" . $_POST["webring"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_messages=" . $_POST["messages"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_Param=" . $_POST["param"] . " ;\n") ;
                      fwrite($fd, "\$App_Affiche_News_TL=" . $_POST["tl"] . " ;\n") ;
                      fwrite($fd, "\$Aut_Liens=" . $_POST["aut_lien"] . " ;\n") ;
                      fwrite($fd, "\$Aut_File=" . $_POST["aut_fichier"] . " ;\n") ;

                      fwrite($fd, "\n") ;

                      fwrite($fd, '?') ;
                      fwrite($fd, '>') ;

                      fclose($fd) ;

                      if (!rename("include/config.tmp", "include/config.php"))
                      {
                          echo "Impossible de renommer le fichier <strong>config.tmp</strong> en <strong>config.php</strong> dans le r&eacute;pertoire include/.<br />Vous dever le faire manuellement." ;
                      }

                      include("include/config.php") ;

                      $sql = mysql_connect($BD_host,$BD_user,$BD_pass);
                      mysql_select_db($BD_name,$sql);
                      mysql_query("CREATE TABLE $BD_Tab_docs (DO_uid int(8) NOT NULL auto_increment,DO_date date NOT NULL default '0000-00-00',DO_aut varchar(50) NOT NULL default '',DO_rub varchar(50) NOT NULL default '',DO_suj varchar(50) NOT NULL default '',DO_cont text NOT NULL,DO_lect int(8) NOT NULL default '1',DO_mail varchar(25) NOT NULL default '',PRIMARY KEY  (DO_uid),KEY DO_rub (DO_rub,DO_suj)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_docs_cat (DOC_uid int(8) NOT NULL auto_increment,DOC_nom varchar(30) NOT NULL default '',PRIMARY KEY  (DOC_uid),KEY DOC_nom (DOC_nom)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_faq (FA_uid int(8) NOT NULL auto_increment,FA_que varchar(200) NOT NULL default '',FA_rep varchar(200) NOT NULL default '',FA_cat varchar(30) NOT NULL default '',PRIMARY KEY  (FA_uid),KEY FA_que (FA_que,FA_rep)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_faq_cat (FAC_uid int(8) NOT NULL auto_increment,FAC_nom varchar(30) NOT NULL default '',PRIMARY KEY  (FAC_uid),KEY FAC_nom (FAC_nom)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_file (FI_uid int(8) NOT NULL auto_increment,FI_cat varchar(50) NOT NULL default '',FI_nom varchar(100) NOT NULL default '',FI_titre varchar(100) NOT NULL default '',FI_date date NOT NULL default '0000-00-00',FI_lect int(8) NOT NULL default '0',FI_aut varchar(50) NOT NULL default '',FI_mail varchar(25) NOT NULL default '',PRIMARY KEY  (FI_uid),KEY FI_cat (FI_cat),KEY FI_titre (FI_titre)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_file_cat (FIC_uid int(8) NOT NULL auto_increment,FIC_nom varchar(30) NOT NULL default '',PRIMARY KEY  (FIC_uid),KEY FIC_nom (FIC_nom)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_forum (id int(11) NOT NULL auto_increment,nom varchar(255) NOT NULL default '',email varchar(255) NOT NULL default '',date_verif datetime NOT NULL default '0000-00-00 00:00:00',date varchar(10) NOT NULL default '',heure varchar(5) NOT NULL default '',texte text NOT NULL,reponse_a_id int(11) NOT NULL default '0',addr varchar(255) NOT NULL default '',lect int(11) NOT NULL default '0',titre varchar(255) NOT NULL default '- no title -',PRIMARY KEY  (id),UNIQUE KEY id_2 (id),KEY id (id)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_liens (LI_uid int(8) NOT NULL auto_increment,LI_date date NOT NULL default '0000-00-00',LI_aut varchar(50) NOT NULL default '',LI_mail varchar(30) NOT NULL default '',LI_lien varchar(50) NOT NULL default '',LI_rub varchar(50) NOT NULL default '',LI_suj varchar(50) NOT NULL default '',LI_cont varchar(200) NOT NULL default '',LI_clic int(11) NOT NULL default '0',PRIMARY KEY  (LI_uid),KEY LI_uid (LI_uid),KEY LI_suj (LI_suj)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_liens_cat (LIC_uid int(8) NOT NULL auto_increment,LIC_nom varchar(30) NOT NULL default '',PRIMARY KEY  (LIC_uid),KEY LIC_nom (LIC_nom)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_photos (PO_uid int(11) NOT NULL auto_increment,PO_code varchar(12) NOT NULL default '0',PO_date date NOT NULL default '0000-00-00',PO_titre varchar(50) NOT NULL default '',PO_text text,PRIMARY KEY  (PO_uid),KEY PO_date (PO_date)) TYPE=MyISAM;");
                      mysql_query("CREATE TABLE $BD_Tab_user (US_uid int(5) NOT NULL auto_increment,US_nom varchar(60) NOT NULL default '',US_mail varchar(40) NOT NULL default '',US_pseudo varchar(30) NOT NULL default '',US_pwd varchar(100) NOT NULL default '',US_regdate date NOT NULL default '0000-00-00',US_droit varchar(30) NOT NULL default '',US_img varchar(30) NOT NULL default '',US_web varchar(100) NOT NULL default '',PRIMARY KEY  (US_uid),UNIQUE KEY US_pseudo (US_pseudo),KEY US_nom (US_nom)) TYPE=MyISAM;");

                      echo "Si aucunes erreurs n'est apparues, c'est que la création des tables dans la base de données a réussi.<br />" ;

                      ?>
                      <strong>Entrer les informations pour le compte administrateur :</strong>
                      <table>
                        <tr>
                          <td>
                            Nom  :
                          </td>
                          <td>
                            <input type='text' name='admin_nom' value='Webmaster' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Login/Pseudo  :
                          </td>
                          <td>
                            <input type='text' name='admin_login' value='webmaster' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Mot de passe  :
                          </td>
                          <td>
                            <input type='password' name='admin_pass' value='' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            E-mail  :
                          </td>
                          <td>
                            <input type='text' name='admin_email' value='you_email@your_domaine.com' />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            Site web  :
                          </td>
                          <td>
                            <input type='text' name='admin_web' value='http://your_domaine.com' />
                          </td>
                        </tr>
                        <tr>
                          <td colspan='2'><input name='admin_user' type='submit' value='Valider' /></td>
                        </tr>
                      <?php
                  }
                  else
                  {
                      ?>
                      Erreur ! V&eacute;rifier les droits d'acc&egrave;s au r&eacute;pertoire <strong>include</strong> et v&eacute;rifier que le fichier <strong>config.db.conf</strong> n'existe pas au qu'il y ai les droits d'acc&egrave;s.<br />
                      Installation annul&eacute;e.
                      <?php
                  }
              }
              elseif (isset($_POST["admin_user"]))
              {
                  include("include/config.php") ;

                  $sql = mysql_connect($BD_host,$BD_user,$BD_pass);
                  mysql_select_db($BD_name,$sql);
                  mysql_query("INSERT INTO $BD_Tab_user VALUES (1,'" . $_POST["admin_name"] . "','" . $_POST["admin_email"] .
                              "','" . $_POST["admin_login"] . "',md5('" . $_POST["admin_pass"] . "'),now(),'1','','" .
                              $_POST["admin_web"] . "');");

                 echo "Si aucune erreur n'apparait, c'est que l'installation s'est d&eacute;roul&eacute;e avec succ&egrave;s.<br />Cliquer sur le bouton <strong>Terminer</strong> pour finir l'installation et supprimer le fichier d'installation.<br /><br />" ;
                 echo "<input type='submit' name='delete_file' value='Terminer' />" ;
              }
              elseif (isset($_POST["delete_file"]))
              {
                  unlink("install.php") ;
                  echo "Installation termin&eacute;e." ;
              }
              else
              {
                  ?>
                  <strong>Entrer les informations concernants les informations de connexion &agrave; la base de donn&eacute;es :<strong><br />
                  <table>
                    <tr>
                      <td>
                        Login :
                      </td>
                      <td>
                        <input type='text' name='login' value='<?php echo (isset($_POST["login"]) ? $_POST["login"] : "") ; ?>' />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Mot de passe : 
                      </td>
                      <td>
                        <input type='password' name='pass' value='<?php echo (isset($_POST["pass"]) ? $_POST["pass"] : "") ; ?>' />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Nom de la base de données : 
                      </td>
                      <td>
                        <input type='text' name='db' value='<?php echo (isset($_POST["db"]) ? $_POST["db"] : "") ; ?>' />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Host : 
                      </td>
                      <td>
                        <input type='text' name='host' value='<?php echo (isset($_POST["host"]) ? $_POST["host"] : "") ; ?>' />
                      </td>
                    </tr>
                    <tr>
                      <td colspan='2'>
                        <input type='submit' name='valid_db_config' value='Valider' />
                      </td>
                    </tr>
                  </table>

                  <?php
              }
          }
          else
          {
              ?>
              L'installation a d&eacute;j&agrave; &eacute;t&eacute; effectu&eacute;e. Si vous souhaitez la relancer, supprimez le fichier <strong>config.php</strong>.
              <?php
          }

          @mysql_close() ;
          ?>
            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</body>
</html>