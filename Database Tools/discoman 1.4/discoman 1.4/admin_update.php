<?php // UPDATE DES DIFFERENTES TABLES

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Admin update');
LAYERS2();

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

$choix="".@$_GET[choix]."";
$level="".@$_GET[level]."";
$curlevel="".@$_GET[curlevel]."";

//$id_artiste="".@$_GET[id_artiste]."";//en test
$nom="".@$_GET[nom]."";
//$type="".@$_GET[type]."";
//$nom_pays="".@$_GET[nom_pays]."";
//$abrege="".@$_GET[abrege]."";
$nom_utilisateur="".@$_GET[nom_utilisateur]."";
$privilege="".@$_GET[privilege]."";

switch ($choix) {

CASE "1": // UPDATE ARTIST

	 echo "
		  <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		  	<tr>
      			<th colspan=2>$txt_maj_artiste</th>
       		</tr>
    	  </table>
    	  <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	  	<tr>
    			<td colspan='2'>$txt_choisir_artiste_maj</td>
     		</tr>
          </table>";

include ("main10.php");
INCL(1,'%',$curlevel);

break;

CASE "2": //UPDATE FORMATS

 echo "
	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
      		<th colspan=2>Update formats</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>Choisissez le format à modifier.</td>
     	</tr>
	</table>";

include ("main10.php");
INCL(2,'%',$curlevel);

break;

CASE "3":  // UPDATE COUNTRY

 echo "
	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
      		<th colspan=2>Update country</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>Choisissez le pays à modifier.</td>
     	</tr>
	</table>";

include ("main10.php");
INCL(3,'%',$curlevel);

break;

CASE "4":  // UPDATE USER

 echo "
	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
      		<th colspan=2>Update user</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>Choisissez l'utilisateur à modifier.</td>
     	</tr>
	</table>";

include ("main10.php");
INCL(4,'%',$curlevel);

break;

//CASE "5": // UPDATE RECORD - CHOIX DE L'ARTISTE remplacé par record_update

//	echo "
//		 <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
//		 	<tr>
//      			<th colspan=2>Update records</th>
//       		</tr>
//    	 </table>
//    	 <table class=\"Stable\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
//    	 	<tr>
//    			<td colspan='3'>Choisissez l'artiste dont vous souhaitez modifier les enregistrements.</td>
//     		</tr>";

//include ("main10.php");
//INCL(5,'%',$curlevel);

//break;

//CASE "6": // UPDATE RECORD - CHOIX DE L'ENREGISTREMENT => remplacé par record_update.php

//	echo "id artiste : $id_artiste";
//	echo "
//		 <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
//		 	<tr>
//      			<th colspan=2>Update records</th>
//       		</tr>
//    	 </table>
//    	 <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
//    	 	<tr>
//    			<td>Choisissez l'enregistrement à modifier.</td>
//     		</tr>
//		 </table>";

//include ("main10.php");
//if ($id_artiste !='') INCL(6,$id_artiste,$curlevel);

//break;

CASE "7": // UPDATE INFOS

	echo "
		 <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		 	<tr>
      			<th colspan=2>Update infos</th>
       		</tr>
    	 </table>
    	 <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	 	<tr>
    			<td>Choisissez l'enregistrement à modifier.</td>
     		</tr>
		 </table>";

include ("main10.php");
INCL(7,'%',$curlevel);

break;
}

BASPAGEWEB2();

?>