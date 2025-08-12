<?php

//SCRIPT D'APERCU DES DONNEES EXISTANTES PAR ORDRE ALPHABETIQUE POUR MISE A JOUR

function INCL($choix, $search, $curlevel) {

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

	include("link.inc.php");

	if ($choix==1) {// view artistes

		$query = "
			SELECT
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

 	   		if($numrows == 0) {
     	   		echo "<b>No record found</b>\n";
 				}
 	   		else {

LAYERINTERNE2();

     		echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";

         	echo "<th>".ucfirst("$txt_artistes")."</th>\n";
            echo "<th>".ucfirst("Id")."</th>\n";
     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        		// alternate color
        		if($i%2 == 0) echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"artist_update.php?id_artiste=".$row['id_artiste']."&curlevel=$curlevel&choix=$choix\"'>\n";
        		else echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"artist_update.php?id_artiste=".$row['id_artiste']."&curlevel=$curlevel&choix=$choix\"'>\n";

         		echo "<td>".$row["nom"]."</td>\n";
                echo "<td>".$row["id_artiste"]."</td>\n";
        		echo "</tr>\n";
        		$i++;
     			}
    		echo "</table></div>\n";
 	   		}
		echo "</div>\n";
		}

	if ($choix==2) {// view formats

 		$query = "
        	SELECT
                id_type,
                type,
                des_type
            FROM
        		disco_formats
            WHERE
            	type LIKE '{$_GET['search']}%'
            ORDER BY
      			type ASC";

     	$result = mysql_query($query) or die(mysql_error());
     	$numrows = mysql_num_rows($result);
 		$totres = $numrows;//mémo total résultats

 		if($numrows == 0) {
     		echo "<b>No record found</b>\n";
 			}
 		else {

LAYERINTERNE2();

     		echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
        	echo "<th>".ucfirst("Liste des formats")."</th>\n";
            echo "<th>".ucfirst("Désignation des formats")."</th>\n";
        	echo "<th>".ucfirst("Id")."</th>\n";

     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        // alternate color
        		if($i%2 == 0) echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"format_update.php?id_type=".$row['id_type']."&curlevel=$curlevel&choix=$choix\"'>\n";
        		else echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"format_update.php?id_type=".$row['id_type']."&curlevel=$curlevel&choix=$choix\"'>\n";

        		echo "<td>".$row["type"]."</td>\n";
                echo "<td>".$row["des_type"]."</td>\n";
        		echo "<td>".$row["id_type"]."</td>\n";
        		echo "</tr>\n";
        		$i++;
     			}
    		echo "</table></div>\n";
 	   		}
		echo "</div>\n";
		}

	if ($choix==3) {// view pays

		$query = "
        	SELECT
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

        if($numrows == 0) {
    		echo "<b>No record found</b>\n";
 	 		}
 		else {

LAYERINTERNE2();

     		echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
     		echo "<th>".ucfirst("Liste des pays")."</th>\n
     			<th>".ucfirst("Abreviations")."</th>\n
     			<th>".ucfirst("Id")."</th>\n";

     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        		// alternate color
        		if($i%2 == 0) echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"country_update.php?id_pays=".$row['id_pays']."&curlevel=$curlevel&choix=$choix\"'>\n";
        		else echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"country_update.php?id_pays=".$row['id_pays']."&curlevel=$curlevel&choix=$choix\"'>\n";

        		echo "<td>".$row["nom_pays"]."</td>\n
         			<td>".$row["abrege"]."</td>\n
                	<td>".$row["id_pays"]."</td>\n";

        		echo "</tr>\n";
        		$i++;
     			}
    		echo "</table></div>\n";
 	   		}
		echo "</div>\n";
		}

	if ($choix==4) { // view user

		$query = "
    		SELECT
        		nom_utilisateur,
            	mot_de_passe,
            	privilege,
            	level,
            	id_utilisateur
        	FROM
        		disco_utilisateurs
        	WHERE
        		nom_utilisateur LIKE '{$_GET['search']}%' AND
            	privilege LIKE 'user'
        	ORDER BY
      			nom_utilisateur ASC";

    	$result = mysql_query($query) or die(mysql_error());
    	$numrows = mysql_num_rows($result);
		$totres = $numrows;//mémo total résultats

 		if($numrows == 0) {
     		echo "<b>No record found</b>\n";
 			}
 		else {

LAYERINTERNE2();

     		echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
     		echo "<th>".ucfirst("Liste des utilisateurs")."</th>\n
     			<th>".ucfirst("Mots de passe")."</th>\n
            	<th>".ucfirst("Privilèges")."</th>\n
            	<th>".ucfirst("Niveaux")."</th>\n
     			<th>".ucfirst("Id")."</th>\n";

     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        	// alternate color
        		if($i%2 == 0) echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"users_update.php?id_utilisateur=".$row['id_utilisateur']."&curlevel=$curlevel&choix=$choix&nom_utilisateur=$nom_utlisateur&level=$level\"'>\n";
        		else echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"users_update.php?id_utilisateur=".$row['id_utilisateur']."&curlevel=$curlevel&choix=$choix&nom_utilisateur=$nom_utlisateur&level=$level\"'>\n";

         		echo "<td>".$row["nom_utilisateur"]."</td>\n
         			<td>".$row["mot_de_passe"]."</td>\n
                	<td>".$row["privilege"]."</td>\n
                	<td>".$row["level"]."</td>\n
                	<td>".$row["id_utilisateur"]."</td>\n";

        		echo "</tr>\n";
        		$i++;
     			}
    		echo "</table></div>\n";
 	   		}
		echo "</div>\n";
		}

	//if ($choix==5) {// select artist to modify <= remplacé par record_update.php

//		echo "
//       		<tr>
//       			<td>Artiste :</td>
//        		<td>";
//        		include ("form.inc.php");
//				affichartiste();
//		echo "	</td>
//        		<td><a href=\"admin_update.php?id_artiste=$id_artiste&choix=6&curlevel=$curlevel\">Page suivante >></a></td>
// 			</tr>
//		</table>\n";
//		}

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
            	disco_infos.date DESC, id_infos DESC
            LIMIT
            	0,10";

     	$result = mysql_query($query) or die(mysql_error());
     	$numrows = mysql_num_rows($result);
        $totres = $numrows;//mémo total résultats

        if($numrows == 0) {
    		echo "<b>No record found</b>\n";
 	 		}
 		else {
     		//echo "<div id='Layer7' style='position:relative; width:550px; height:200px; z-index:1; left: 0; top:0; border: 1px none #000000; overflow: auto;'>";
LAYERINTERNE2();
     	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
        echo "<th width=\"15%\">".ucfirst("date")."</th>\n";
        echo "<th width=\"20%\">".ucfirst("sujet")."</th>\n";
        echo "<th width=\"60%\">".ucfirst("texte")."</th>\n";
        echo "<th width=\"5%\">".ucfirst("id")."</th>\n";

     	$i = 0;

     	while ($row = mysql_fetch_assoc($result)) {
        		// alternate color
        	if($i%2 == 0) echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"infos_update.php?id_infos=".$row['id_infos']."&curlevel=$curlevel&choix=$choix\"'>\n";
        	else echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"infos_update.php?id_infos=".$row['id_infos']."&curlevel=$curlevel&choix=$choix\"'>\n";

            require("config.inc.php");

            if ($lang=="fr") {
   			   list($year, $month, $day) = explode("-", $row['date']);
   			   $row['date'] = "$day/$month/$year";
        	   }

        echo "<td>".$row["date"]."</td>\n";
        echo "<td>".$row["sujet"]."</td>\n";
        $row['texte']=eregi_replace("\n","\n<br>",$row['texte']);//
        echo "<td>".$row["texte"]."</td>\n";
        echo "<td>".$row["id_infos"]."</td>\n";

        		echo "</tr>\n";
        		$i++;
     			}
    		echo "</table></div>\n";
 	   		}
		echo "</div>\n";
		}

//	if ($choix != 5)  {
     	mysql_free_result($result);
     	mysql_close($link);
//     	}

LAYERPAGEDEB();

echo "<table width='100%'>
	<tr>
        <td align='left'><a href=\"admin.php?curlevel=$curlevel\">[<< back to admin page] </a></td>
    </tr>
</table>";

LAYERPAGEFIN();
}
?>