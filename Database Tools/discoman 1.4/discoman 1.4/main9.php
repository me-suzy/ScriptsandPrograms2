<?php

//SCRIPT D'APERCU DES DONNEES EXISTANTES PAR ORDRE ALPHABETIQUE POUR AJOUT

function INCL($choix, $search) {

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

	include("link.inc.php");

	if ($choix==1) {// view artistes

     		$query = "SELECT
                     	nom,
                     	id_artiste
                     FROM
                     	disco_artistes
            		 WHERE
                 	 	nom LIKE '{$_GET['search']}%'
                     ORDER BY
      				 	nom ASC";

     		$result = mysql_query($query) or die(mysql_error());
     		$numrows = mysql_num_rows($result);
            $totres = $numrows;//mémo total résultats
LAYERINTERNE4(250);

 	   		if($numrows == 0) {
     	   		echo "<b>$txt_result_0</b>\n";
 				}
 	   		else {

     		echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";

         	echo "<th>".ucfirst($txt_artistes)."</th>\n";
            echo "<th>".ucfirst("id")."</th>\n";
     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        		// alternate color
        		if($i%2 == 0)
               		echo "<tr class=\"TRalter\">\n";
        		else
        			echo "<tr class=\"main\">\n";

         		echo "<td>".$row["nom"]."</td>\n";
                echo "<td>".$row["id_artiste"]."</td>\n";
        		echo "</tr>\n";
        		$i++;
     			}
     		echo "</table>\n";
 	   		}
		}

	if ($choix==2) {// view formats

 		$query = "
        	SELECT
                type,
                des_type,
                id_type
            FROM
            	disco_formats
            WHERE
            	type LIKE '{$_GET['search']}%'
            ORDER BY
      			type ASC";

     	$result = mysql_query($query) or die(mysql_error());
     	$numrows = mysql_num_rows($result);
 		$totres = $numrows;//mémo total résultats
LAYERINTERNE4(220);

 		if($numrows == 0) {
     		echo "<b>$txt_result_0</b>\n";
 			}
 		else {
     		echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
        	echo "<th>".ucfirst("$txt_formats")."</th>\n";
            echo "<th>".ucfirst("$txt_designations")."</th>\n";
        	echo "<th>".ucfirst("Id")."</th>\n";

     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        // alternate color
        		if($i%2 == 0) echo "<tr class=\"TRalter\">\n";
        		else
        		echo "<tr class=\"main\">\n";
        		echo "<td>".$row["type"]."</td>\n";
                echo "<td>".$row["des_type"]."</td>\n";
        		echo "<td>".$row["id_type"]."</td>\n";

        		echo "</tr>\n";
        		$i++;
     			}

     		echo "</table>\n";
 			}
		}

if ($choix==3) {// view pays

 		$query = "SELECT
                 	nom_pays,
                    abrege,
                    id_pays
                 FROM
                 	disco_pays
            	 WHERE
                 	nom_pays LIKE '{$_GET['search']}%'
                 ORDER BY
      			 	nom_pays ASC";

     	$result = mysql_query($query) or die(mysql_error());
     	$numrows = mysql_num_rows($result);
        $totres = $numrows;//mémo total résultats
LAYERINTERNE4(220);

        if($numrows == 0) {
    		echo "<b>No record found</b>\n";
 	 		}
 		else {
     		echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
     		echo "<th>".ucfirst("Liste des pays")."</th>\n
     			<th>".ucfirst("Abreviations")."</th>\n
     			<th>".ucfirst("Id")."</th>\n";

     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        		// alternate color
        		if($i%2 == 0) echo "<tr class=\"TRalter\">\n";
        		else
        		echo "<tr class=\"main\">\n";

        		echo "<td>".$row["nom_pays"]."</td>\n
         			<td>".$row["abrege"]."</td>\n
                	<td>".$row["id_pays"]."</td>\n";

        		echo "</tr>\n";
        		$i++;
     		}
     	echo "</table>\n";
		}
	}

if ($choix==4) { // view user

	$query = "SELECT
             	nom_utilisateur,
                mot_de_passe,
                privilege,
                level,
                id_utilisateur
             FROM
             	disco_utilisateurs
             WHERE
             	nom_utilisateur LIKE '{$_GET['search']}%'
             ORDER BY
      		 	nom_utilisateur ASC";

    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);
	$totres = $numrows;//mémo total résultats
LAYERINTERNE4(220);

 	if($numrows == 0) {
     	echo "<b>No record found</b>";
 		}
 	else {
     	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
     	echo "<th>".ucfirst("Liste des utilisateurs")."</th>\n
     		<th>".ucfirst("Mots de passe")."</th>\n
            <th>".ucfirst("Privilèges")."</th>\n
            <th>".ucfirst("Niveaux")."</th>\n
     		<th>".ucfirst("Id")."</th>\n";

     		$i = 0;

     	while ($row = mysql_fetch_assoc($result)) {
        	// alternate color
        	if($i%2 == 0) echo "<tr class=\"TRalter\">\n";
        	else echo "<tr class=\"main\">\n";

         	echo "<td>".$row["nom_utilisateur"]."</td>\n
         		<td>".$row["mot_de_passe"]."</td>\n
                <td>".$row["privilege"]."</td>\n
                <td>".$row["level"]."</td>\n
                <td>".$row["id_utilisateur"]."</td>\n";

        	echo "</tr>\n";
        	$i++;
     		}
     	echo "</table>\n";
    	}
	}

if ($choix==7) {// view infos

 		$query = "
        	SELECT
      			disco_infos.id_infos,
                sujet,
      			disco_infos.texte,
                disco_infos.date
			FROM
				disco_infos
            WHERE
            	id_infos LIKE '{$_GET['search']}%'
			ORDER BY
            	disco_infos.date DESC, id_infos DESC";

     	$result = mysql_query($query) or die(mysql_error());
     	$numrows = mysql_num_rows($result);
        $totres = $numrows;//mémo total résultats
LAYERINTERNE4(90);

        if($numrows == 0) {
    		echo "<b>No record found</b>\n";
 	 		}
 		else {
     		echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
     		echo "<th width='15%'>".ucfirst("Dates")."</th>\n
                <th width='20%'>".ucfirst("Sujets")."</th>\n
     			<th width='60%'>".ucfirst("Textes")."</th>\n
     			<th width='5%'>".ucfirst("Id")."</th>\n";

     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        		// alternate color
        		if($i%2 == 0) echo "<tr class=\"TRalter\">\n";
        		else
        		echo "<tr class=\"main\">\n";
                require("config.inc.php");
                if ($lang=="fr") {
   					list($year, $month, $day) = explode("-", $row['date']);
   					$row['date'] = "$day/$month/$year";
        			}
        		echo "<td>".$row["date"]."</td>\n";
                echo "<td>".$row["sujet"]."</td>\n";
                $row['texte']=eregi_replace("\n","\n<br>",$row['texte']);//
                echo "<td>".$row["texte"]."</td>\n
                	<td>".$row["id_infos"]."</td>\n";
        		echo "</tr>\n";
        		$i++;
     		}
     	echo "</table>\n";
		}
	}

     mysql_free_result($result);
     mysql_close($link);
}

?>