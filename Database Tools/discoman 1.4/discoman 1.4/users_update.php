<?php

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Users update');
LAYERS2();
include("link.inc.php");

$id_utilisateur="".@$_GET["id_utilisateur"]."";
$level="".@$_GET["level"]."";
$nom_utilisateur="".@$_GET["nom_utilisateur"]."";
$curlevel="".@$_GET[curlevel]."";
$choix="".@$_GET[choix]."";

if ($level != "") {

	$update = mysql_query("
		UPDATE
			disco_utilisateurs
		SET
        	level='$level'
		WHERE
			id_utilisateur LIKE '$id_utilisateur'");

if ($update) {

	echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
         </table>
         <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche utilisateur de <b>".stripslashes($nom_utilisateur)."</b> mise à jour</td>
            </tr>
        </table></div>\n";

LAYERPAGEDEB2();

	echo "
        <table width='100%'>
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
        <td><select size=\"1\" name=\"level\">";

if ($row['level']==1) {
	echo "
  			<option value=\"1\" selected>1</option>
  			<option value=\"2\">2</option>";
            }
    else {
	echo "
  			<option value=\"1\">1</option>
  			<option value=\"2\" selected>2</option>";
            }
    echo "
			</select></td>
    </tr>
	<tr>
		<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"Update\" name=\"Add\"></div></td>
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