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
require("mod_forum/config.inc.php");

$date = date("d/m/Y");
$dateus = date("Y-m-d");
$heure = date("H:i");
$date_verif = $dateus . " ". $heure . ":00" ;

$nom = AuAddSlashes(trim($_POST["nom"]));
$email = AuAddSlashes(strtolower(trim($_POST["email"])));

$titre = htmlspecialchars($_POST["titre"]);
$titre = AuAddSlashes($titre);
$titre = ucfirst($titre);

$message = htmlspecialchars($_POST["message"]);
$message = AuAddSlashes($message);
$message = nl2br($message);

$reponse_a_id = AuAddSlashes($_POST["reponse_a_id"]) ;

mysql_query("INSERT INTO $BD_Tab_forum VALUES('','$nom','$email','$date_verif','$date','$heure','$message','$reponse_a_id','$REMOTE_ADDR','0','$titre')");

if ($reponse_a_id == 0)
{
    include("mod_forum/index.php") ;
}
else
{
    $_GET["id"] = $reponse_a_id ;
    include("mod_forum/read_mess.php") ;
}
?>