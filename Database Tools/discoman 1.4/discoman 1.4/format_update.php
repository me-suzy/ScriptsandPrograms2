<?php // MISE A JOUR DE LA TABLE PAYS

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Format update');
LAYERS2();
include("link.inc.php");

$id_type="".@$_GET["id_type"]."";
$type="".@$_GET["type"]."";
$des_type="".@$_GET["des_type"]."";
$curlevel="".@$_GET[curlevel]."";
$choix="".@$_GET[choix]."";

if ($type != "") {

	$update = mysql_query("
		UPDATE
			disco_formats
		SET
        	type='$type',
            des_type='$des_type'
		WHERE
			id_type LIKE '$id_type'");

	if ($update) {

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
         </table>
         <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche de <b>".stripslashes($type)."</b> mise à jour.</td>
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
        	id_type,
            des_type,
        	type
    	FROM
        	disco_formats
    	WHERE
    		id_type LIKE '$id_type'";

	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$str=stripslashes($row['type']);
	$str2=stripslashes($row['des_type']);

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
    			<td>Nom du format :</td>
        		<td><input name=\"type\" type=\"text\" value=`$str`></td>
    		</tr>
    		<tr>
    			<td>Désignation du format :</td>
        		<td><input name=\"des_type\" type=\"text\" value=`$str2`></td>
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