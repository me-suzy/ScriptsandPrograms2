<?
/**universal
 * Fichier contenant les paramètres de connexion à la base de données
 * @author Thomas Pequet
 * @version 1.1 
 */

// Type de base de données
$bdType 	= "mysql";

// Variables de connexions
$bdServeur 	= "localhost";
$bdLogin 	= "root";
$bdPassword	= "";
$bdNomBase 	= "mysql";

// Inclusion du fichier correspodant à la base de données
include($rep_par_rapport_racine."lib/db/".$bdType.".php");

// Connexion à la base de données
$bd = new sql_db($bdServeur, $bdLogin, $bdPassword, $bdNomBase, false);
if(!$bd -> db_connect_id)
	die("<FONT FACE=\"verdana\" SIZE=\"1\"><BR><CENTER><B style=\"color:#FFFFFF;background-color:#FF0000\">&nbsp;Erreur: la base de données ne répond pas&nbsp;</B></FONT>");
	
// Destruction des variables maintenant inutiles
unset($bdLogin);
unset($bdPassword);
//unset($bdNomBase);
unset($bdType);
?>