<?php // DELETE FORMATS

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Format delete');
LAYERS2();
include("link.inc.php");

$id_type="".@$_GET["id_type"]."";
$type="".@$_GET["type"]."";
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
    		disco_disques.format LIKE '$id_type'";//je vérifie avant suppression que le format n'est pas utilisé dans un enregistrement

	$result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);

    if ($numrows > 0) {//s'il est utilisé, je crée une liste des id d'enregistrements qui l'utilisent

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
		if (window.confirm(\"Désirez-vous réellement supprimer le format $type ?\")) {
			location.href='delete.php?cas=2&id_type=$id_type&type=$type&curlevel=$curlevel&choix=$choix';
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
    		type,
        	des_type,
        	id_type
    	FROM
        	disco_formats
    	WHERE
    		id_type LIKE '$id_type'";

	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);

	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>Formats Result</th>
    		</tr>
  		</table>
    	<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    			<tr>
    				<td width='50%'>Id format :</td>
        			<td>".$row['id_type']."</td>
        			<input name=\"id_type\" type=\"hidden\" value=".$row['id_type'].">
    			</tr>
    			<tr>
    				<td>Nom format :</td>
        			<td>".$row['type']."</td>
                    <input name=\"type\" type=\"hidden\" value=".$row['type'].">
			    </tr>
    			<tr>
    				<td>Désignation format :</td>
        			<td>".$row['des_type']."</td>
    			</tr>
				<tr>
					<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"Delete\" name=\"delete\"></div></td>
    			</tr>
        	<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
        	<input name=\"choix\" type=\"hidden\" value=\"$choix\">
			</FORM>
		</table></div>\n";
LAYERPAGEDEB3(-1);
	}
mysql_close($link);
BASPAGEWEB();
?>