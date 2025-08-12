<?php // DELETE ARTISTS

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Artist delete');
LAYERS2();
include("link.inc.php");

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

$id_artiste="".@$_GET["id_artiste"]."";
$nom="".@$_GET["nom"]."";
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
    		disco_disques.artiste LIKE '$id_artiste'";

	$result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);

    if ($numrows > 0) {

        echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>$txt_attention</th>
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
            	<td>$txt_sup_impos</td>
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
				if (window.confirm(\"Désirez-vous réellement supprimer l'artiste $nom ?\")) {
location.href='delete.php?cas=1&id_artiste=$id_artiste&nom=$nom&curlevel=$curlevel&choix=$choix';
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
    		nom,
        	id_artiste
    	FROM
        	disco_artistes
    	WHERE
    		id_artiste LIKE '$id_artiste'";

$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_assoc($result);

	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>$txt_sup_artist</th>
    		</tr>
  		</table>
		<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    			<tr>
    				<td width='50%'>Id :</td>
        			<td>".$row['id_artiste']."</td>
        			<input name=\"id_artiste\" type=\"hidden\" value=".$row['id_artiste'].">
    			</tr>
    			<tr>
    				<td>$txt_artiste :</td>
        			<td>".$row["nom"]."</td>
                    <input name=\"nom\" type=\"hidden\" value=".$row['nom'].">
			    </tr>
				<tr>
					<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_effacer\" name=\"delete\"></div></td>
    			</tr>
        	<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
        	<input name=\"choix\" type=\"hidden\" value=\"$choix\">
			</FORM>
		</table></div>\n";

LAYERPAGEDEB3(-1);
}
mysql_close($link);
BASPAGEWEB2();
?>