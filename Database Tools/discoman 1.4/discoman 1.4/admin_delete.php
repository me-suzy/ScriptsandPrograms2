<?php

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Admin delete');
LAYERS3();

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

$choix="".@$_GET[choix]."";
$level="".@$_GET[level]."";
$curlevel="".@$_GET[curlevel]."";

$nom="".@$_GET[nom]."";
$type="".@$_GET[type]."";
$nom_pays="".@$_GET[nom_pays]."";
$abrege="".@$_GET[abrege]."";
$nom_utilisateur="".@$_GET[nom_utilisateur]."";
$privilege="".@$_GET[privilege]."";

switch ($choix)
{
CASE "1": // DELETE ARTIST

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    	<tr>
      		<th colspan=2>$txt_sup_artist</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>$txt_choisir_artiste_sup</td>
     	</tr>
</table>";

include ("main11.php");
INCL(1,'%',$curlevel);

break;

CASE "2": // DELETE FORMAT

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    	<tr>
      		<th colspan=2>Delete format</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>Choisissez le format à supprimer.</td>
     	</tr>
</table>";

include ("main11.php");
INCL(2,'%',$curlevel);

if ($insert) {
    echo "Le nouveau format \"$type\" a été enregistré avec succès.\n";
    }
else echo "&nbsp;<br>\n";

break;

CASE "3":  // DELETE COUNTRY

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    	<tr>
      		<th colspan=2>Delete country</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>Choisissez le pays à supprimer.</td>
     	</tr>
</table>";

include ("main11.php");
INCL(3,'%',$curlevel);

break;

CASE "4":  // DELETE USER

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    	<tr>
      		<th colspan=2>Delete user</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>Choisissez l'utilisateur à supprimer.</td>
     	</tr>
</table>";

include ("main11.php");
INCL(4,'%',$curlevel);

break;

CASE "7":  // DELETE INFOS

//LAYERS3();

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    	<tr>
      		<th colspan=2>Delete infos</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>Choisissez l'info à supprimer.</td>
     	</tr>
</table>";

LAYERS4();

include ("main11.php");
INCL(7,'%',$curlevel);

break;
}

BASPAGEWEB2();

?>