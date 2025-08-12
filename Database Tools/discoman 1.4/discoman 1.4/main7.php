<?php //30 DERNIERS ENREGISTREMENTS

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - 30 lasts');
LAYERS();
include("link.inc.php");
require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_trad.inc.php";
require($lang_filename);

$query = "
	SELECT
    	disco_artistes.nom,
      	disco_disques.id_disque,
      	disco_formats.type,
      	disco_disques.date,
      	disco_pays.abrege,
      	disco_disques.reference,
      	disco_titres.titre
	FROM
		disco_artistes,
        disco_disques,
        disco_formats,
        disco_pays,
        disco_titres
	WHERE
		disco_artistes.id_artiste = disco_disques.artiste AND
    	disco_formats.id_type = disco_disques.format AND
    	disco_pays.id_pays = disco_disques.pays AND
    	disco_titres.id_titre = disco_disques.titre
	ORDER BY
		disco_disques.id_disque DESC
	LIMIT
		0,30";

$result = mysql_query($query) or die(mysql_error());
$numrows = mysql_num_rows($result); // result of count query
$totres = $numrows;//mémo total résultats

// ************** pager **************************
include ("pager.inc.php");
// ************** end of pager **************************

if($numrows == 0) {
	LAYERINTERNE();
	echo "
    	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>".$txt_results."</th>
       		</tr>
            <tr>
            	<td  bgcolor=\"#FFFFFF\"><b>".$txt_no."</b></td>
            </tr>
        </table></div></div>\n";
	}
 	else {
 		$query = "
			SELECT
      			disco_artistes.nom,
      			disco_disques.id_disque,
      			disco_formats.type,
      			disco_disques.date,
      			disco_pays.abrege,
      			disco_disques.reference,
      			disco_titres.titre
			FROM
				disco_artistes,
        		disco_disques,
        		disco_formats,
        		disco_pays,
        		disco_titres
			WHERE
				disco_artistes.id_artiste = disco_disques.artiste AND
    			disco_formats.id_type = disco_disques.format AND
    			disco_pays.id_pays = disco_disques.pays AND
    			disco_titres.id_titre = disco_disques.titre
			ORDER BY
				disco_disques.id_disque DESC";
  // ************* search *****************/

    $memolimit=$limit;//pour rappeler le $limit après sa modification
	if ($last > 30) $limit = 30 - (($pages - 1) * $limit);//pour ne pas dépasser les 30 résultats affichés sur la dernière page

     $query .= " LIMIT ".$_GET['page'].", $limit"; // add query LIMIT

     $result = mysql_query($query) or die(mysql_error());
     $numrows = mysql_num_rows($result);

     //echo our table

     	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
         echo "<th width=\"30%\">".$txt_artistes."</th>\n";
         echo "<th width=\"5%\">".$txt_formats."</th>\n";
         echo "<th width=\"5%\">".$txt_annees."</th>\n";
         echo "<th width=\"5%\">".$txt_payss."</th>\n";
         echo "<th width=\"15%\">".$txt_refs."</th>\n";
         echo "<th width=\"40%\">".$txt_titres."</th>\n";

     $i = 0;

     while ($row = mysql_fetch_assoc($result))
     {
        // alternate color
        if($i%2 == 0)
               echo "<tr class=\"TRalter\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"#F0F0F0\"' onClick='location=\"title.php?id=".$row['id_disque']."\"'>\n";
        else
        echo "<tr class=\"main\" onMouseOver='this.style.background=\"#66CCFF\"' onMouseOut='this.style.background=\"white\"' onClick='location=\"title.php?id=".$row['id_disque']."\"'>\n";

         echo "<td>".$row["nom"]."</td>\n";
         echo "<td>".$row["type"]."</td>\n";
         echo "<td>".$row["date"]."</td>\n";
         echo "<td>".$row["abrege"]."</td>\n";
         echo "<td>".$row["reference"]."</td>\n";
         $row['titre']=eregi_replace("\n","\n<br>",$row['titre']);//
         echo "<td>".$row["titre"]."</td>\n";

        echo "</tr>\n";
        $i++;
     }
     echo "</table></div>\n";

     mysql_free_result($result);
 }
$limit=$memolimit;//remet $limit à sa bonne valeur

// ************** bottom pager  **************************
LAYERPAGEDEB2();

include ("bottompager.inc.php");
if ($numrows==0) echo "<a href=\"javascript:history.back();\">[<< ".txt_prec."]</a>\n";

LAYERPAGEFIN();
// ************** end of bottom pager **************************

mysql_close($link);

BASPAGEWEB2();
?>