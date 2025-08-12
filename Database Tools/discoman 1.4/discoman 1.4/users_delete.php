<?php // DELETE USERS

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Users delete');
LAYERS2();

$id_utilisateur="".@$_GET["id_utilisateur"]."";
$level="".@$_GET["level"]."";
$nom_utilisateur="".@$_GET["nom_utilisateur"]."";
$curlevel="".@$_GET[curlevel]."";
$choix="".@$_GET[choix]."";
$delete="".@$_GET[delete]."";

if ($delete != "") {

	echo "
		<script language='javascript'>
			if (window.confirm(\"Désirez-vous réellement supprimer l'utilisateur $nom_utilisateur ?\")) {
				location.href='delete.php?cas=6&id_utilisateur=$id_utilisateur&nom_utilisateur=$nom_utilisateur&curlevel=$curlevel&choix=$choix';
        		}
    		else {
        	  	history.go(-1);
        		}
		</script>";
    	}
	else {

	include("link.inc.php");
		$query = "
    		SELECT
    			nom_utilisateur,
        		mot_de_passe,
        		privilege,
        		level,
        		id_utilisateur
    		FROM
        		disco_utilisateurs
    		WHERE
    			id_utilisateur LIKE '$id_utilisateur'";

		$result = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
    	mysql_close($link);

		echo "
			<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
    				<th>User Result</th>
    			</tr>
  			</table>
			<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    			<tr>
    				<td width='50%'>Id utilisateur :</td>
        			<td>".$row['id_utilisateur']."</td>
        			<input name=\"id_utilisateur\" type=\"hidden\" value=".$row['id_utilisateur'].">
    			</tr>
    			<tr>
    				<td>Nom utilisateur :</td>
        			<td>".$row['nom_utilisateur']."</td>
        			<input name=\"nom_utilisateur\" type=\"hidden\" value=".$row['nom_utilisateur'].">
    			</tr>
    			<tr>
    				<td>Mot de passe :</td>
        			<td>".$row['mot_de_passe']."</td>
    			</tr>
    			<tr>
    				<td>Privilege : </td>
        			<td>".$row['privilege']."</td>
    			</tr>
    			<tr>
    				<td>Niveau :</td>
        			<td>".$row['level']."</td>
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

BASPAGEWEB();
?>