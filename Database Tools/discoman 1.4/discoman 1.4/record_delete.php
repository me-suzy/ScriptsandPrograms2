<?php //script d'affichage de tous les enregistrements pour un artiste donné à fin de suppression
require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Record delete');
LAYERS3();

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

$nom="".@$_GET[form_artiste]."";
$curlevel="".@$_GET[curlevel]."";

if ($nom != "") {

	include("link.inc.php");
    	$query = "
			SELECT
      			disco_disques.id_disque,
      			disco_formats.type,
      			disco_disques.date,
      			disco_pays.abrege,
      			disco_disques.reference,
      			disco_titres.titre,
      			disco_disques.commentaire
			FROM
				disco_artistes,
        		disco_disques,
        		disco_formats,
        		disco_pays,
        		disco_titres
            WHERE
          		disco_artistes.id_artiste = '$nom' AND
      			disco_disques.artiste = '$nom' AND
				disco_disques.format = disco_formats.id_type AND
				disco_disques.pays = disco_pays.id_pays AND
				disco_disques.titre = disco_titres.id_titre
			ORDER BY
            	disco_disques.date ASC, disco_formats.type ASC, disco_pays.abrege ASC, disco_titres.titre ASC, disco_disques.reference ASC";

     	$result = mysql_query($query) or die(mysql_error());
     	$numrows = mysql_num_rows($result);
        $totres = $numrows;//mémo total résultats

        	$query2 = "
            SELECT
            	disco_artistes.nom
            FROM
            	disco_artistes
            WHERE
            	disco_artistes.id_artiste = '$nom' ";

        $result2 = mysql_query($query2) or die(mysql_error());
        $row2 = mysql_fetch_array($result2);

        echo "
        <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>$txt_resultat ".$row2["nom"]."</th>
       		</tr>";


        if($numrows == 0) {

    		echo "
            <tr><td  bgcolor=\"#FFFFFF\"><b>$txt_result_0</b></td></tr></table>\n";
 	 		}
 		else {
       		echo "
            <tr>
       			<td  bgcolor=\"#FFFFFF\">$txt_choisir_disque_sup :</td>
       		</tr>
       	</table>";
LAYERINTERNE();

     	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";

         echo "<th width=\"5%\">".ucfirst("$txt_annees")."</th>\n";
         echo "<th width=\"5%\">".ucfirst("$txt_formats")."</th>\n";
         echo "<th width=\"5%\">".ucfirst("$txt_payss")."</th>\n";
         echo "<th width=\"15%\">".ucfirst("$txt_refs")."</th>\n";
         echo "<th width=\"40%\">".ucfirst("$txt_titres")."</th>\n";
         echo "<th width=\"25%\">".ucfirst("$txt_coms")."</th>\n";
         echo "<th width=\"5%\">".ucfirst("Id")."</th>\n";

     		$i = 0;

     		while ($row = mysql_fetch_assoc($result)) {
        		// alternate color
        		if($i%2 == 0) echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"title_delete.php?id=".$row['id_disque']."&curlevel=$curlevel&nom=$nom\"'>\n";
        		else echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"title_delete.php?id=".$row['id_disque']."&curlevel=$curlevel&nom=$nom\"'>\n";

        echo "<td>".$row["date"]."</td>\n";
        echo "<td>".$row["type"]."</td>\n";
        echo "<td>".$row["abrege"]."</td>\n";
        echo "<td>".$row["reference"]."</td>\n";
		$row['titre']=eregi_replace("\n","\n<br>",$row['titre']);//
        echo "<td>".$row["titre"]."</td>\n";
		$row['commentaire']=eregi_replace("\n","\n<br>",$row['commentaire']);//
        echo "<td>".$row["commentaire"]."</td>\n";
        echo "<td>".$row["id_disque"]."</td>\n";
		echo "</tr>\n";
        $i++;
     	}
    		echo "</table></div>\n";
			}
echo "</div></div>";
LAYERPAGEDEB();

echo "<table width='100%'>
	<tr>
        <td align='left'><a href=\"record_delete.php?curlevel=$curlevel\">[<< back to choose artist] </a></td>
    </tr>
</table>";

	mysql_free_result($result);
    mysql_close($link);
	}

else {
		echo "
			<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
      				<th>$txt_suppression</th>
       			</tr>
    		</table>
    		<table class=\"Stable\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
            <FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    			<tr>
    				<td colspan='3'>$txt_choisir_artiste_eff</td>
     			</tr>
    	<tr>
       		<td>$txt_artiste :</td>
        	<td>";
        		include ("form.inc.php");
				affichartiste("");
    echo "      <input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
    			<input name=\"nom\" type=\"hidden\" value=\"$nom\"></td>
			<td><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_envoyer\" name=\"Add\"></div></td>
    	</tr>
    </form>
	</table>\n";

echo "</div></div>";//fermeture du layer6
LAYERPAGEDEB2();

echo "<table width='100%'>
	<tr>
        <td align='left'><a href=\"admin.php?curlevel=$curlevel\">[<< back to admin page] </a></td>
    </tr>
</table>";
}

//LAYERPAGEFIN();
BASPAGEWEB();
?>