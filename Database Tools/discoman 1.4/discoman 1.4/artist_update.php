<?php // MISE A JOUR DE LA TABLE ARTISTES

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Artist update');
LAYERS2();

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

include("link.inc.php");

$id_artiste="".@$_GET["id_artiste"]."";
$nom="".@$_GET["nom"]."";
$curlevel="".@$_GET[curlevel]."";
$choix="".@$_GET[choix]."";

if ($nom != "") {

	$nom=strtoupper($nom);

	$update = mysql_query("
		UPDATE
			disco_artistes
		SET
        	nom='$nom'
		WHERE
			id_artiste LIKE '$id_artiste'");

if ($update) {

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>$txt_maj_artiste</th>
       		</tr>
         </table>
         <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>$txt_fiche <b>".stripslashes($nom)."</b> $txt_maj</td>
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
        	id_artiste,
        	nom
    	FROM
        	disco_artistes
    	WHERE
    		id_artiste LIKE '$id_artiste'";

	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$str = stripslashes($row['nom']);

  	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>$txt_maj_artiste</th>
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
        		<td><input name=\"nom\" type=\"text\" value=`$str`></td>
    		</tr>
			<tr>
				<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_envoyer\" name=\"Add\"></div></td>
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