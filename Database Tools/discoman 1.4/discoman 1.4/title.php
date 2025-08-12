<?php // SCRIPT D'AFFICHAGE D'UN DISQUE

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Titles');
include("link.inc.php");

$id="".@$_GET["id"]."";

$query="
	SELECT
      	disco_artistes.nom,
        disco_disques.image,
        disco_disques.date,
        disco_disques.reference,
        disco_disques.commentaire,
      	disco_formats.type,
      	disco_pays.abrege,
      	disco_titres.titre
	FROM
		disco_artistes,
        disco_disques,
        disco_formats,
        disco_pays,
        disco_titres
	WHERE
      	disco_disques.id_disque LIKE '$id' AND
      	disco_artistes.id_artiste = disco_disques.artiste AND
      	disco_formats.id_type = disco_disques.format AND
      	disco_pays.id_pays = disco_disques.pays AND
      	disco_titres.id_titre = disco_disques.titre";

$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc($result);

$row['commentaire']=eregi_replace("\n","\n<br>",$row['commentaire']);
$row['titre']=eregi_replace("\n","\n<br>",$row['titre']);
$val=$row['image'];
//$chansons=eregi_replace("\[B\]","<B>","$chansons");// For bold text support use [B]Text[/B]
//$chansons=eregi_replace("\[/B\]","</B>","$chansons");// For bold text support use [B]Text[/B]
//$chansons=eregi_replace("\[I\]","<I>","$chansons");// For italic text support use [I]Text[/I]
//$chansons=eregi_replace("\[/I\]","</I>","$chansons");// For italic text support use [I]Text[/I]
//$chansons=eregi_replace("\[P\]","<P>","$chansons");// For <p> support use [P]Text[/P]
//$chansons=eregi_replace("\[U\]","<U>","$chansons");// For underscore support use [U]Text[/U]
//$chansons=eregi_replace("\[/U\]","</U>","$chansons");// For underscore support use [U]Text[/U]

LAYERS2();
echo "
<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
	<tr>
    	<th>Titles Result</th>
    </tr>
  </table>";
LAYERS5();
echo "
<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    <tr>
    	<td width='25%'>Artiste :</td>
        <td colspan=\"3\"><b>".$row['nom']."</b></td>
    </tr>
    <tr>
    	<td>Images :</td>";
        $query2 = "
    		SELECT
    			imagea,
                imageb,
                imagec
    		FROM
    			disco_images
    		WHERE
    			disco_images.id_image = '$val'"; //
    $result2 = mysql_query($query2) or die(mysql_error());
    mysql_close($link);
    $row2 = mysql_fetch_assoc($result2);

    if (is_array($row2)) {
    	while ($v = each($row2)) {
			if ($v["value"]!="") echo "
        		<td width='25%' valign='top' align='center' ><img src='upload_files/".$v["value"]."' border=0; width=100; style=\"cursor:move;\"; onClick='location=\"title2.php?valeur=".$v["value"]."&upload_dir=upload_files&mode=1\"'></td>";
            else echo "<td width='25%'></td>";
        	}
        }
    else echo "<td colspan=\"3\"></td>";

echo "
	</tr>
    <tr>
    	<td>Format :</td>
    	<td colspan=\"3\">".$row['type']."</td>
    </tr>
    <tr>
    	<td>Date :</td>
    	<td colspan=\"3\">".$row['date']."</td>
    </tr>
    <tr>
    	<td>Pays :</td>
    	<td colspan=\"3\">".$row['abrege']."</td>
    </tr>
    <tr>
    	<td>Référence :</td>
    	<td colspan=\"3\">".$row['reference']."</td>
    </tr>
    <tr>
    	<td>Id :</td>
    	<td colspan=\"3\">".$id."</td>
    </tr>
    <tr>
    	<td>Commentaire :</td>
    	<td colspan=\"3\">".$row['commentaire']."</td>
    </tr>
    <tr>
    	<td>Titres :</td>
    	<td colspan=\"3\">".$row['titre']."</td>
    </tr>
</table>
</div></div>\n";

LAYERPAGEDEB3(-1);
LAYERPAGEFIN();
BASPAGEWEB2();
?>