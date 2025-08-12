<?php   // SCRIPT D'AFFICHAGE D'UN ENREGISTREMENT DISQUE

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Title delete');

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

$test="".@$_GET["test"]."";
$curlevel="".@$_GET[curlevel]."";
$id="".@$_GET["id"]."";
$delete="".@$_GET[delete]."";
$id_disque="".@$_GET["id_disque"]."";
$nom="".@$_GET["nom"]."";//nom de l'artiste pour retour (forme numérique)
$v1="".@$_GET["v1"]."";
$v2="".@$_GET["v2"]."";
$v3="".@$_GET["v3"]."";
$val="".@$_GET["val"]."";

if ($delete != "") {

	echo "
		<script language='javascript'>
			if (window.confirm(\"Désirez-vous réellement supprimer l'enregistrement $id_disque ?\")) {
				location.href='delete.php?cas=4&curlevel=$curlevel&nom=$nom&id_disque=$id_disque&v1=$v1&v2=$v2&v3=$v3&val=$val';
        		}
    		else {
        	  	history.go(-1);
        		}
		</script>";
    }

else {//if $delete==""

include("link.inc.php");

	$query="
		SELECT
      		disco_artistes.nom,
      		disco_artistes.id_artiste,
            disco_disques.image,
            disco_disques.date,
      		disco_disques.reference,
            disco_disques.commentaire,
            disco_formats.type,
            disco_pays.abrege,
            disco_titres.id_titre,
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
	$row['titre']=eregi_replace("\n","\n<br>",$row['titre']);//
    $val=$row['image'];
//$chansons=eregi_replace("\[B\]","<B>","$chansons");// For bold text support use [B]Text[/B]
//$chansons=eregi_replace("\[/B\]","</B>","$chansons");// For bold text support use [B]Text[/B]
//$chansons=eregi_replace("\[I\]","<I>","$chansons");// For italic text support use [I]Text[/I]
//$chansons=eregi_replace("\[/I\]","</I>","$chansons");// For italic text support use [I]Text[/I]
//$chansons=eregi_replace("\[P\]","<P>","$chansons");// For <p> support use [P]Text[/P]
//$chansons=eregi_replace("\[U\]","<U>","$chansons");// For underscore support use [U]Text[/U]
//$chansons=eregi_replace("\[/U\]","</U>","$chansons");// For underscore support use [U]Text[/U]

LAYERS3();

echo "
<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
	<tr>
    	<th>$txt_suppression</th>
    </tr>
  </table>";

LAYERS4();

echo "
<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
	<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    <tr>
    	<td width='20%'>$txt_artiste :</td>
        <td colspan=\"3\"><b>".stripslashes($row['nom'])."</b></td>
    </tr>
    <tr>
    			<td>$txt_images :</td>";
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
    	$i=0;
    	while ($v = each($row2)) {
        $i=$i+1;
			if ($v["value"]!="") echo "
        		<td width='25%' style=\"cursor:move;\"><img src='upload_files/".$v["value"]."' border=0; width=100; onClick='location=\"title2.php?valeur=".$v["value"]."&upload_dir=upload_files&mode=1\"'></td><input name=\"v$i\" type=\"hidden\" value=\"$v[value]\">";
            else echo "<td width='25%'></td>";
        	}
        }
    else echo "<td colspan=\"3\"></td>";
    echo "</tr>
    <tr>
    	<td>$txt_format :</td>
    	<td colspan=\"3\">".stripslashes($row['type'])."</td>
    </tr>
    <tr>
    	<td>$txt_annee :</td>
    	<td colspan=\"3\">".$row['date']."</td>
    </tr>
    <tr>
    	<td>$txt_pays :</td>
    	<td colspan=\"3\">".stripslashes($row['abrege'])."</td>
    </tr>
    <tr>
    	<td>$txt_ref :</td>
    	<td colspan=\"3\">".stripslashes($row['reference'])."</td>
    </tr>
    <tr>
    	<td>Id :</td>
    	<td colspan=\"3\">$id</td>
    </tr>
    <tr>
    	<td>$txt_com :</td>
    	<td colspan=\"3\">".stripslashes($row['commentaire'])."</td>
    </tr>
    <tr>
    	<td>$txt_com :</td>
    	<td colspan=\"3\">".stripslashes($row['titre'])."</td>
    </tr>
    <tr>
		<td colspan=4><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_effacer\" name=\"delete\"></div></td>
        <input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
        <input name=\"id_disque\" type=\"hidden\" value=\"$id\">
        <input name=\"val\" type=\"hidden\" value=\"$val\">
        <input name=\"nom\" type=\"hidden\" value=\"$nom\">
    </tr>
    </FORM>
		</table></div></div>\n";

LAYERPAGEDEB3(-1);
}

BASPAGEWEB();
?>