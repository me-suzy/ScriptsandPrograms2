<?php     //page d'accueil de DiscoMan
require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_trad.inc.php";
require($lang_filename);
require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Home');
LAYERS2();

include("link.inc.php");

	$result = mysql_query("
    	SELECT
        COUNT(id_disque) AS id2
        FROM
        	disco_disques") or die(mysql_error());

	$row = mysql_fetch_assoc($result);

	mysql_free_result($result);

 	$query = "
    	SELECT
        	sujet,
      		disco_infos.texte,
            disco_infos.date,
            disco_infos.id_infos,
            image
		FROM
			disco_infos
		ORDER BY
        	disco_infos.date DESC, id_infos DESC
		LIMIT
			0,10";

    $result2 = mysql_query($query) or die(mysql_error());
    $row2['texte']=eregi_replace("\n","\n<br>",$row2['texte']);//
    mysql_close($link);

	echo "
    	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    		<th>$txt_infos</th>
    	</table>
    	<table class=\"Stable\" border=\"1\" style=\"border-color:#000000;\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    		<tr>
        		<td>
        			$txt_enregistrements <b>".$row["id2"]."</b> <br><br>
            	</td>
			</tr>
	</table>\n";

LAYERINTERNE4(320);

	echo "
         <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";

	while ($row2 = mysql_fetch_assoc($result2)) {
    	if ($lang=="fr") {
   			list($year, $month, $day) = explode("-", $row2['date']);
   			$row2['date'] = "$day/$month/$year";
        	}
    	echo "<tr>
        		<td width='40%'>".$row2["date"]."</td>
                <td><b>".$row2["sujet"]."</b></td>
             </tr>\n";
        echo "<tr><td colspan='2'>---------------------------------------------------</td></tr>\n
        	 <tr>
             	<td>";
        if ($row2["image"]!="")	echo "<img src=\"images_infos/".$row2["id_infos"].$row2["image"]."\" width=\"200\" alt=\"\" border=0 style=\"cursor:move;\" onClick='location=\"title2.php?valeur=".$row2["id_infos"].$row2["image"]."&upload_dir=images_infos&mode=1\"'>";
        echo "</td>";
                $row2['texte']=eregi_replace("\n","\n<br>",$row2['texte']);
        echo "	<td valign='top'>".$row2["texte"]."</td>\n";
        echo "<tr><td colspan='2'><b><big>____________________________________________________</big></b></td></tr>\n";
    }

    echo "</table></div></div>\n";

BASPAGEWEB2();
?>