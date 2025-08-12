<?php
session_start();
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

$chemin="..";

include "$chemin/include/config.php";
include "$chemin/include/" . $_SESSION["App_Langue"] ;
include "$chemin/include/fonctions.php";

$login = AuAddSlashes($_POST["login"]) ;
$pass = $_POST["pass"] ;

if($login == '' || $pass == '')
{
    die(header("Location: $chemin/index.php?" . $sid . "affiche=Admin&erreur=1"));
}

// on recupère le password de la table qui correspond au login du visiteur
$sql_id = sql_connect($BD_host, $BD_name, $BD_user, $BD_pass) ;

$req = mysql_query("select * from $BD_Tab_user where US_pseudo='$login'") or die("Erreur SQL !<br />" . $sql . "<br />" . mysql_error());

$data = mysql_fetch_array($req);

@mysql_close() ;

if (!$is_gd)
{
    $_SESSION["sc_code"] = "zut !!!" ;
    // Simule un envoie du code
    $_POST[$_SESSION["sc_field_name"]] = $_SESSION["sc_code"] ;
    $_SESSION["sc_time"] = time() ;
}

if (($data['US_pwd'] == md5($pass)) && ($_POST[$_SESSION["sc_field_name"]] == $_SESSION["sc_code"]) && (($_SESSION["sc_time"] + 300) > time()))
{
    $_SESSION["Admin_Nom"] = $data['US_nom'] ;
    $_SESSION["Admin_Mail"] = $data['US_mail'] ;
    $_SESSION["Admin_Pseudo"] = $data['US_pseudo'];
    $_SESSION["Admin_Droit"] = $data['US_droit'];
    $_SESSION["Admin_Img"] = $data['US_img'];
    $_SESSION["Admin_Web"] = $data['US_web'] ;
    $_SESSION["Admin"] = true ;
    $_SESSION["Admin_RegDatel"] = $data['US_regdate'] ;

    die(header("Location: $chemin/index.php?" . $sid . "affiche=Admin"));
}
else
{
    die(header("Location: $chemin/index.php?" . $sid . "affiche=Admin&erreur=1"));
}

if ($registerGlobalsOn)
{
//    session_register('_SESSION') ;
}
?>

