<?php // MISE A JOUR DE LA TABLE PAYS

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Country update');
LAYERS2();
include("link.inc.php");

$id_pays="".@$_GET["id_pays"]."";
$abrege="".@$_GET["abrege"]."";
$nom_pays="".@$_GET["nom_pays"]."";
$curlevel="".@$_GET[curlevel]."";
$choix="".@$_GET[choix]."";

if ($abrege != "" && $nom_pays !="") {

	$update = mysql_query("
		UPDATE
			disco_pays
		SET
        	nom_pays='$nom_pays',
        	abrege='$abrege'
		WHERE
			id_pays LIKE '$id_pays'");

if ($update) {

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
         </table>
         <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche de <b>".stripslashes($nom_pays)."</b> mise à jour</td>
            </tr>
        </table></div>\n";

LAYERPAGEDEB2();

	echo "<table width='100%'>
			<tr>
        		<td align='left'><a href=\"admin_update.php?curlevel=$curlevel&choix=$choix\">[<< back to admin update page] </a></td>
    		</tr>
		</table>";
LAYERPAGEFIN();
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
    $str=stripslashes($row['nom_pays']);
    $str2=stripslashes($row['abrege']);

	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>Countries Result</th>
    		</tr>
  		</table>
		<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    		<tr>
    			<td width='50%'>Id pays :</td>
        		<td>".$row['id_pays']."</td>
        		<input name=\"id_pays\" type=\"hidden\" value=".$row['id_pays'].">
    		</tr>
    		<tr>
    			<td>Nom du pays :</td>
        		<td><input name=\"nom_pays\" type=\"text\" value=`$str`></td>
    		</tr>
    		<tr>
    			<td>Abrege :</td>
        		<td><input name=\"abrege\" type=\"text\" value=`$str2`></td>
    		</tr>
			<tr>
				<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"Update\" name=\"Add\"></div></td>
    		</tr>
        	<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
        	<input name=\"choix\" type=\"hidden\" value=\"$choix\">
		</FORM>
		</table></div>";

LAYERPAGEDEB3(-1);
}
mysql_close($link);
BASPAGEWEB2();
?>