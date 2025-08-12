<?php

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Adds');
LAYERS2();

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

$site_name = $_SERVER['HTTP_HOST'];
$url_dir = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

$choix="".@$_GET[choix]."";
$level="".@$_GET[level]."";
$curlevel="".@$_GET[curlevel]."";

$nom="".@$_GET[nom]."";
$type="".@$_GET[type]."";
$des_type="".@$_GET[des_type]."";
$nom_pays="".@$_GET[nom_pays]."";
$abrege="".@$_GET[abrege]."";
$nom_utilisateur="".@$_GET[nom_utilisateur]."";
$privilege="".@$_GET[privilege]."";
$texte="".@$_POST[form_texte]."";//infos
$sujet="".@$_POST[form_sujet]."";//infos
$add2="".@$_POST[Add2]."";//infos
$add="".@$_GET[Add]."";

if ($add != "" || $add2 != "") {

	if ($nom != "") {
    	$nom=strtoupper($nom);
		include("link.inc.php");
		$insert = mysql_query("INSERT INTO disco_artistes
        	(nom)
		VALUES
        	('$nom')");
 		mysql_close($link);
 		$choix='1';
    	}

	else if ($type != "") {
		include("link.inc.php");
		$insert = mysql_query("INSERT INTO disco_formats
        	(type,des_type)
		VALUES
        	('$type','$des_type')");
 		mysql_close($link);
 		$choix='2';
    	}

	else if ($nom_pays != "") {
    	$abrege=strtoupper($abrege);
		include("link.inc.php");
		$insert = mysql_query("INSERT INTO disco_pays
        	(nom_pays,abrege)
		VALUES
        	('$nom_pays','$abrege')");
		mysql_close($link);
 		$choix='3';
    	}

	else if ($nom_utilisateur != "") {
		include("link.inc.php");
		$insert = mysql_query("INSERT INTO disco_utilisateurs
        	(nom_utilisateur,privilege,level)
		VALUES
        	('$nom_utilisateur','$privilege','$level')");
 		mysql_close($link);
 		$choix='4';
    	}

	else if ($texte != "") {

    	$date=date("Y\-m\-j");
		include("link.inc.php");
		$insert = mysql_query("INSERT INTO disco_infos
        	(date,sujet,texte)
		VALUES
        	('$date','$sujet','$texte')");
        $id_infos = mysql_insert_id();//je récupère l'id de l'info

        if ($_FILES['userfile1']['size']>0) {
        	include ('functions.inc.php');
        	do_upload_infos($id_infos);//je télécharge l'image
	   		}
        mysql_close($link);

 		$choix='7';
    	}

	else if ($curlevel != "") {//au cas où aucune des conditions n'est remplie
		echo "$txt_erreur_cases_vides</div>";
		}
	}

switch ($choix) {

CASE "1": // ADD ARTIST

	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n
			<tr>
    			<th colspan=2>$txt_ajout_art</th>
    		</tr>
		</table>
		<table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    		<tr>
    			<td>
    				<table class=\"Stable\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"tab3\">
 						<tr>
        					<td>$txt_nom :</td>
        					<td><input type=\"text\" name=\"nom\" size=\"30\"></td>
        					<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
                            <input name=\"Add\" type=\"hidden\" value=\"1\">
        					<td><i>$txt_aide_01</i></td>
     					</tr>
						<tr>
							<td colspan=3><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_ajouter\" name=\"Add\"></div></td>
     					</tr>
                    	<tr>
                    		<td colspan=3>";
	if ($insert) {
    	echo $txt_lartiste.stripslashes($nom).$txt_enregistre;
    	}
	else echo "&nbsp;";
	echo "					</td>
     					</tr>
	 				</table>
     			</td>
     		</tr>
		</FORM>
	</table>\n";

	include ("main9.php");
	INCL(1,'%');

	echo "</div></div>\n";

	break;

CASE "2": // ADD FORMAT

	echo "
    <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    <FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
		<tr>
       		<th colspan=2>$txt_ajout_for</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    	   	<td>
    			<table class=\"Stable\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"tab3\">
 					<tr>
        				<td>$txt_format :</td>
        				<td><input type=\"text\" name=\"type\" size=\"10\"></td>
        				<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
                        <input name=\"Add\" type=\"hidden\" value=\"1\">
        				<td><i>$txt_aide_02</i></td>
     				</tr>
 					<tr>
        				<td>$txt_designation :</td>
        				<td><input type=\"text\" name=\"des_type\" size=\"40\"></td>
        				<td><i>$txt_aide_03</i></td>
     				</tr>
					<tr>
						<td colspan=3><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_ajouter\" name=\"Add\"></div></td>
     				</tr>
                    <tr>
                    	<td colspan=3>";
	if ($insert) {
    	echo $txt_nouveau_for." ".stripslashes($type)." ".$txt_enregistre;
    	}
	else echo "&nbsp;";
	echo "				</td>
     				</tr>
                </table>
     		</td>
     	</tr>
	</FORM>
	</table>";

	include ("main9.php");
	INCL(2,'%');

	echo "</div></div>\n";

	break;

CASE "3":  // ADD COUNTRY

	echo "
    <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
	<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    	<tr>
      		<th colspan=2>$txt_ajout_pays</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>
    			<table class=\"Stable\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"tab3\">
 					<tr>
        				<td>$txt_pays :</td>
        				<td><input type=\"text\" name=\"nom_pays\" size=\"20\"></td>
        				<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
                        <input name=\"Add\" type=\"hidden\" value=\"1\">
        				<td><i>$txt_aide_04</i></td>
     				</tr>
 					<tr>
        				<td>$txt_abrege :</td>
        				<td><input type=\"text\" name=\"abrege\" size=\"3\"></td>
        				<td><i>$txt_aide_05</i></td>
     				</tr>
					<tr>
						<td colspan=3><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_ajouter\" name=\"Add\"></div></td>
     				</tr>
                    <tr>
                    	<td colspan=3>";
	if ($insert) {
    	echo "Le nouveau pays ".stripslashes($nom_pays)." a été enregistré avec succès.";
    	}
	else echo "&nbsp;";
	echo "				</td>
     				</tr>
                </table>
     		</td>
     	</tr>
	</FORM>
	</table>";

	include ("main9.php");
	INCL(3,'%');

	echo "</div></div>\n";

	break;

CASE "4":  // ADD USER

 echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
 echo "<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
       <tr>
      	<th colspan=2>Add user</th>
       </tr>
       </table>
           <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    <tr>
    	<td>
    <table class=\"Stable\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"tab3\">
 	<tr>
        <td>Utilisateur :</td>
        <td><input type=\"text\" name=\"nom_utilisateur\" size=\"10\"></td>
        <td><i>Maximum : 10 caractères</i></td>
        <input name=\"privilege\" type=\"hidden\" value=\"user\">
        <input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
     </tr>
 	<tr>
        <td>Niveau :</td>
        <td><select size=\"1\" name=\"level\">
  			<option value=\"1\">1</option>
  			<option value=\"2\">2</option>
			</select></td>
        <td><i></i></td>
     </tr>
		<tr>
		<td colspan=3><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"Add\" name=\"Add\"></div></td>
     	</tr>
                    <tr>
                    	<td colspan=3>";
if ($insert) {
    echo "Le nouvel utilisateur ".stripslashes($nom_utilisateur)." a été enregistré avec succès.";
    }
else echo "&nbsp;";
echo "					</td>
     				</tr>
                </table>
     		</td>
     	</tr>
	</FORM>
</table>";

include ("main9.php");
INCL(4,'%');

echo "</div></div>\n";

break;

CASE "7":  // ADD INFOS

require("config.inc.php");

if ($lang=="fr") $date=date("d / m / Y");
else $date=date("Y - m - d");

echo "<FORM METHOD=\"POST\" name=\"upload\" id=\"upload\"  ENCTYPE=\"multipart/form-data\">
    <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    	<tr>
      		<th colspan=2>Add infos</th>
       	</tr>
    </table>
    <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    	<tr>
    		<td>
    			<table class=\"Stable\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"tab3\">
 					<tr>
        				<td width='20%'>Date :</td>
						<td>$date</td>
        				<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
                        <input name=\"Add2\" type=\"hidden\" value=\"1\">
        				<td><i>&nbsp;</i></td>
     				</tr>
 					<tr>
        				<td width='20%'>Sujet :</td>
        				<td><input type=\"text\" name=\"form_sujet\"></td>
        				<td><i>&nbsp;</i></td>
     				</tr>
 					<tr>
        				<td width='20%'>Image :</td>
						<td><input type=\"file\" id=\"userfile1\" name=\"userfile1\"></td>
        				<td><i>&nbsp;</i></td>
     				</tr>
 					<tr>
        				<td>Texte :</td>
        				<td><textarea name=\"form_texte\" cols=\"40\" rows=\"6\"></textarea></td>
        				<td><i>&nbsp;</i></td>
     				</tr>
					<tr>
						<td colspan=3><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"Add\" name=\"Add2\"></div></td>
     				</tr>
                    <tr>
                    	<td colspan=3>";
	if ($insert) {
    	echo "La nouvelle info du \"$date\" a été enregistrée avec succès.";
    	}
	else echo "&nbsp;";
	echo "				</td>
     				</tr>
                </table>
     		</td>
     	</tr>
	</FORM>
	</table>\n";

	include ("main9.php");
	INCL(7,'%');

	echo "</div></div>\n";

	break;
}
LAYERPAGEDEB();

       		echo "
<table width='100%'>
	<tr>
        <td align='left'><a href=\"admin.php?curlevel=$curlevel\">[<< back to admin page] </a></td>
    </tr>
</table>";

LAYERPAGEFIN();
BASPAGEWEB2();

?>