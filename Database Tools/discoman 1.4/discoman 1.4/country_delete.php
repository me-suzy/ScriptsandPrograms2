<?php // DELETE COUNTRY

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Country delete');
LAYERS2();
include("link.inc.php");

$id_pays="".@$_GET["id_pays"]."";
$abrege="".@$_GET["abrege"]."";
$nom_pays="".@$_GET["nom_pays"]."";
$curlevel="".@$_GET[curlevel]."";
$choix="".@$_GET[choix]."";
$delete="".@$_GET[delete]."";

if ($delete != "") {

	$query = "
    	SELECT
    		id_disque
    	FROM
        	disco_disques
    	WHERE
    		disco_disques.pays LIKE '$id_pays'";

	$result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);

    if ($numrows > 0) {

        echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Attention !</th>
       		</tr></table>
        <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>";
		while ($row = mysql_fetch_assoc($result)) {
        	echo $row['id_disque']." -";
            }
        echo "</td>
            </tr>
            <tr>
            	<td>Des enregistrements contiennent des références à cette donnée. Suppression impossible tant que les enregistrements n'ont pas été modifiés en conséquence.</td>
            </tr>
        </table></div>\n";

LAYERPAGEDEB();
echo "<table width='100%'>
			<tr>
        		<td align='left'><a href=\"admin_delete.php?curlevel=$curlevel&choix=$choix\">[<< back to admin update page] </a></td>
    		</tr>
		</table></div>";
        }

    else {

echo "
	<script language='javascript'>
		if (window.confirm(\"Désirez-vous réellement supprimer le pays $nom_pays ?\")) {
			location.href='delete.php?cas=3&id_pays=$id_pays&nom_pays=$nom_pays&curlevel=$curlevel&choix=$choix';
        	}
    		else {
        	  	history.go(-1);
        		}
	</script>";

    }
}

	else {

	$query = "
    	SELECT
    		nom_pays,
        	id_pays,
        	abrege
    	FROM
        	disco_pays
    	WHERE
    		id_pays LIKE '$id_pays'";

	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);

	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>Countries Result</th>
    		</tr>
  		</table>\n";

LAYERS4();

	echo "
		<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    			<tr>
    				<td width='50%'>Id pays :</td>
        			<td>".$row['id_pays']."</td>
        			<input name=\"id_pays\" type=\"hidden\" value=".$row['id_pays'].">
    			</tr>
    			<tr>
    				<td>Nom pays :</td>
        			<td>".$row['nom_pays']."</td>
                    <input name=\"nom_pays\" type=\"hidden\" value=".$row['nom_pays'].">
			    </tr>
    			<tr>
    				<td>abrege :</td>
        			<td>".$row['abrege']."</td>
    			</tr>
				<tr>
					<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"Delete\" name=\"delete\"></div></td>
    			</tr>
        	<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
        	<input name=\"choix\" type=\"hidden\" value=\"$choix\">
			</FORM>
		</table></div></div></div>";
LAYERPAGEDEB3(-1);

}
mysql_close($link);
BASPAGEWEB();
?>