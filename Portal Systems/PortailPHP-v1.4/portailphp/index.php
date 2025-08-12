<?php
session_start();
// On change d'id à chaque fois
session_regenerate_id() ;
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

// Vérifie si les session PHP sont transmise pas URL et si elles sont
// automatiquement ajoutée
$sid = "" ;

if (get_cfg_var("session.use_cookies") == 0)
{
    if (get_cfg_var("session.use_trans_sid") == 0)
    {
        $sid = SID . "&";
    }
}


// Vérifie si on est en RegisterGlobals = On
$registerGlobalsOn = (get_cfg_var("register_globals") == 1) ;

$chemin = "." ;

include "$chemin/include/config.php" ;
include "$chemin/include/fonctions.php" ;

//recupération du thème choisi
if (!isset($_SESSION["App_Theme"]))
{
    $_SESSION["App_Theme"] = $App_Theme_Defaut ;
}

if (isset($_POST["New_Theme"]))
{
    if (!(!(strpos($_POST["New_Theme"], "..") === false) || eregi("[\|<>& \"\']+", $_POST["New_Theme"]) ||
        ereg("^/.+", $_POST["New_Theme"])))
    {
        $_SESSION["App_Theme"] = $_POST["New_Theme"] ;
    }
    else
    {
        $_SESSION["App_Theme"] = $App_Theme_Defaut ;
    }
}

//recupération de la langue choisie
if (!isset($_SESSION["App_Langue"]))
{
    $_SESSION["App_Langue"] = $App_Langue_Defaut ;
}

if (isset($_GET["New_Langue"]))
{
    // On peut lire un fichier en utilisant le lien ci-dessous
    // http://localhost/portailphp/index.php?affiche=&admin=&New_Langue=../../gpl.txt%00

    // Prend en compte le paramètre si dans la langue, il n'y a pas /, |, >,<,&,
    //    un espace, ", ' et pas de .. et ne commence pas par /
    if (!(!(strpos($_GET["New_Langue"], "..") === false) || eregi("[\|<>& \"\']+", $_GET["New_Langue"]) ||
        ereg("^/.+", $_GET["New_Langue"])))
    {
        $_SESSION["App_Langue"] = $_GET["New_Langue"] . ".php" ;
    }
    else
    {
        $_SESSION["App_Langue"] = $App_Langue_Defaut ;
    }
}

if (!isset($_GET["affiche"]))
{
    $_GET["affiche"] = "" ;
}

if (!isset($_GET["admin"]))
{
    $_GET["admin"] = "0" ;
}

$_SESSION["Page_Courante"] = "$chemin/index.php?" . $sid . "affiche=" . $_GET["affiche"] . "&admin=" . $_GET["admin"] ;

include "$chemin/include/" . $_SESSION["App_Langue"] ;
include "$chemin/mod_faq/lang/lang-mod_faq-" . $_SESSION["App_Langue"] ;
include "$chemin/mod_file/lang/lang-mod_file-" . $_SESSION["App_Langue"] ;
include "$chemin/mod_forum/lang/lang-mod_forum-" . $_SESSION["App_Langue"] ;
include "$chemin/mod_liens/lang/lang-mod_liens-" . $_SESSION["App_Langue"] ;
include "$chemin/mod_membre/lang/lang-mod_membre-" . $_SESSION["App_Langue"] ;
include "$chemin/mod_news/lang/lang-mod_news-" . $_SESSION["App_Langue"] ;
include "$chemin/mod_photos/lang/lang-mod_photos-" . $_SESSION["App_Langue"] ;
include "$chemin/mod_publicite/lang/lang-mod_publicite-" . $_SESSION["App_Langue"] ;
include "$chemin/mod_search/lang/lang-mod_search-" . $_SESSION["App_Langue"] ;

// $sql_id identifiant de connection peut être utilisé dans toutes les pages
$sql_id = sql_connect($BD_host, $BD_name, $BD_user, $BD_pass) ;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title><?php echo $App_Me_Titre ?></title>
  <base href="<?php echo $App_Me_URL ; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="description" content="<?php echo $App_Me_Desc ; ?>" />
  <meta name="keywords" lang="fr" content="<?php echo $App_Me_KeyWords ; ?>" />
  <META HTTP-EQUIV="Content-Language" content="fr" />
  <meta name="reply-to" content="<?php echo $App_Me_WebMmail ; ?>" />
  <meta name="category" content="Internet" />
  <meta name="robots" content="index, follow" />
  <meta name="distribution" content="global" />
  <meta name="revisit-after" content="7 days" />
  <meta name="author" lang="fr" content="<?php echo $App_Me_WebM ; ?>" />
  <meta name="copyright" content="<?php echo $App_Me_Copyright ; ?>" />
  <meta name="generator" content="PortailPHP,PHPCoder,EasyPHP" />
  <meta name="identifier-url" content="<?php echo $App_Me_URL ; ?>" />
  <meta name="expires" content="never" />
  <meta name="Date-Creation-yyyymmdd" content="<?php echo $App_Me_DateC ; ?>" />
  <meta name="Date-Revision-yyyymmdd" content="<?php echo $App_Me_DateM ; ?>" />
  <link rel="stylesheet" type="text/css" href="<?php echo "$chemin/themes/" . $_SESSION["App_Theme"] . "/" ; ?>global.css" />
</head>
<body bgcolor="<?php echo $App_Couleur ; ?>">
<!-- Copyright (C) 2002 CLAIRE Cédric cedric.claire@safari-msi.com http://www.portailphp.com/-->
<!-- Modifié par Martineau Emeric Copyright (C) 2004 -->
<!-- Ce programme est un logiciel libre ; vous pouvez le redistribuer et/ou le modifier
     conformément aux dispositions de la Licence Publique Générale GNU, telle que publiée
     par la Free Software Foundation ; version 2 de la licence, ou encore (à votre choix)
     toute version ultérieure. -->
<!-- Ce programme est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE
     GARANTIE ; sans même la garantie implicite de COMMERCIALISATION ou D'ADAPTATION A
     UN OBJET PARTICULIER. Pour plus de détail, voir la Licence Publique Générale GNU . -->
<!-- Vous devez avoir reçu un exemplaire de la Licence Publique Générale GNU en même
     temps que ce programme ; si ce n'est pas le cas, écrivez à la Free Software
     Foundation Inc., 675 Mass Ave, Cambridge, MA 02139, Etats-Unis. -->
<!-- Portail PHP -->
<!-- La présente Licence Publique Générale n'autorise pas le concessionnaire à incorporer
     son programme dans des programmes propriétaires. Si votre programme est une bibliothèque
     de sous-programmes, vous pouvez considérer comme plus intéressant d'autoriser une
     édition de liens des applications propriétaires avec la bibliothèque. Si c'est ce que
     vous souhaitez, vous devrez utiliser non pas la présente licence, mais la Licence
     Publique Générale pour Bibliothèques GNU. -->
<?php
//if ($HTTP_SESSION_VARS['Session_Admin'] && $_GET['affiche'] == "Admin")
if ($_SESSION["Admin"] && $_GET['affiche'] == "Admin")
{
    include "$chemin/mod_membre/a-index.php" ;
}
else
{
    include "$chemin/i-index.php" ;
}

@mysql_close() ;

if ($registerGlobalsOn)
{
    session_register('_SESSION') ;
}
?>
</body>
</html>
