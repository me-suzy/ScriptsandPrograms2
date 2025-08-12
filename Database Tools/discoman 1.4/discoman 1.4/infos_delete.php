<?php // DELETE INFOS
require("config.inc.php");
require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Infos delete');
LAYERS2();
include("link.inc.php");

$id_infos="".@$_GET["id_infos"]."";
$sujet="".@$_GET["sujet"]."";
$curlevel="".@$_GET[curlevel]."";
$choix="".@$_GET[choix]."";
$delete="".@$_GET[delete]."";
$image="".@$_GET["image"]."";

	if ($delete != "") {

		echo "
			<script language='javascript'>
				if (window.confirm(\"Désirez-vous réellement supprimer l'info $id_infos ?\")) {
					location.href='delete.php?cas=7&id_infos=$id_infos&curlevel=$curlevel&choix=$choix&image=$image';
        			}
    		else {
        	  	history.go(-1);
        		}
			</script>";
    	}
	else {

		$query = "
        	SELECT
        		id_infos,
            	date,
            	sujet,
            	texte,
                image
        	FROM
              	disco_infos
            WHERE
              	id_infos LIKE '$id_infos'";

	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
    mysql_close($link);
    $row['texte']=eregi_replace("\n","\n<br>",$row['texte']);//
	if ($lang=="fr") {
   		list($year, $month, $day) = explode("-", $row['date']);
   		$row['date'] = "$day/$month/$year";
        }
    $image=$row['image'];

	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>Infos Result</th>
    		</tr>
  		</table>";
LAYERS4();
	echo "
    	<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    		<tr>
    			<td width='5%'>Id infos :</td>
        		<td>".$row['id_infos']."</td>
        		<input name=\"id_infos\" type=\"hidden\" value=".$row['id_infos'].">
    		</tr>
    		<tr>
    			<td width='15%'>Date :</td>
				<td>".$row['date']."</td>
    		</tr>
    		<tr>
    			<td width='20%'>Sujet :</td>
        		<td>".$row['sujet']."</td>
    		</tr>
			<tr>
            	<td width='20%'>Image :</td>
                <td>";
            if ($row['image']!="")	echo "<div align=\"center\"><img src=\"images_infos/".$row['id_infos'].$row['image']."\" width=\"200\" alt=\"\" border=0 style=\"cursor:move;\" onClick='location=\"title2.php?valeur=".$row["id_infos"].$row["image"]."&upload_dir=images_infos&mode=1\"'></div>";
            echo "</td>
		  	</tr>
    		<tr>
    			<td>Infos :</td>
				<td>".$row['texte']."</td>
    		</tr>
			<tr>
				<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"Delete\" name=\"delete\"></div></td>
    		</tr>
        	<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
            <input name=\"choix\" type=\"hidden\" value=\"$choix\">
            <input name=\"image\" type=\"hidden\" value=\"$image\">
		</FORM>
		</table></div></div>\n";

LAYERPAGEDEB3(-1);

}

BASPAGEWEB2();
?>