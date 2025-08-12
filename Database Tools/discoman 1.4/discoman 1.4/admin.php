<?
require("presentation.inc.php");
HAUTPAGEWEB('artists add');
LAYERS2();

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

echo "<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       <tr>
      	<th>".$txt_admin."</th>
       </tr>
</table>";

$nom_utilisateur="".@$_GET[name]."";
$passe="".@$_GET[password]."";
$privilege="".@$_GET[privilege]."";
$level="".@$_GET[level]."";
$curlevel="".@$_GET[curlevel]."";

if ($curlevel != "") {
	include ("admin_menu.php");
    affiche_menu($curlevel);
    }
if ($nom_utilisateur == "" && $passe == "" && $curlevel == "") testadmin();
if ($nom_utilisateur != "" && $passe != "" && $privilege == "") verif_passe($nom_utilisateur, $passe);
if ($nom_utilisateur != "" && $passe != "" && $privilege == "user") creation_user($nom_utilisateur, $passe);
if ($nom_utilisateur != "" && $passe == "") test_user($nom_utilisateur);
if ($privilege =="admin") creation_admin($nom_utilisateur, $passe, $privilege, $level);
if ($privilege =="user" && $passe == "") saisie_user($nom_utilisateur);
if ($nom_utilisateur == "" && $passe != "") echo "<b>".$txt_saisir."</b>
         		<br>
         		<br>
         		<a href=\"javascript:history.back();\">[<< back to previous page]</a>\n";

function testadmin() { //teste si l'admin a été créé

	include("link.inc.php");

	$query = "
    	SELECT
    		*
    	FROM
    		disco_utilisateurs
    	WHERE
    		privilege LIKE 'admin'";
    $result = mysql_query($query) or die(mysql_error());
    $select = mysql_fetch_row($result);

    if ($select == NULL) saisie_admin();//demande nom + mot de passe pour créer l'admin
    else saisie_nom();

   	mysql_free_result($result);
   	mysql_close($link);
	}

function saisie_admin() {//demande nom + mot de passe pour créer l'admin

	echo "<table class=\"Stable\" border=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">
		<tr>
    		<td>


    			<div align=\"center\"><br>
					<form name=\"FormName\" action=\"$PHP_SELF\" method=\"get\">
    				Création de l'admin :<br>
						<input name=\"name\" type=\"text\" value=\"\"><br>
						<input name=\"password\" type=\"password\" value=\"\"><br>
        				<input name=\"privilege\" type=\"hidden\" value=\"admin\">
        				<input name=\"level\" type=\"hidden\" value=\"3\">
        				<input type=\"submit\" id=\"style1\" value=\"Send\"><br>
					</form>
            	</div>
			</td>
    	</tr>
	</table>";
	}

function creation_admin($nom_utilisateur, $passe, $privilege, $level) {// création de l'admin

	if ($nom_utilisateur != "" && $passe != "" && $privilege != "") {

		include("link.inc.php");

    	$insert = mysql_query("
    		INSERT INTO disco_utilisateurs
        		(nom_utilisateur,mot_de_passe,privilege,level)
			VALUES
        		('$nom_utilisateur', '$passe', '$privilege', '$level')");

		mysql_close($link);
    	}

	$nom_utilisateur="";
	$passe="";
    $privilege="";

	saisie_nom();
	}

function test_user($nom_utilisateur) { //teste si l'user a été créé

	include("link.inc.php");

    $query = "
    	SELECT
    		nom_utilisateur,
            mot_de_passe
    	FROM
    		disco_utilisateurs
    	WHERE
           	nom_utilisateur = '$nom_utilisateur' AND
    		privilege LIKE 'user'";
    $result = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_assoc($result);

    if ($row["nom_utilisateur"]!="" && $row["mot_de_passe"]!="") echo "<b>Veuillez saisir votre  mot de passe.</b>
         		<br>
         		<br>
         		<a href=\"javascript:history.back();\">[<< back to previous page]</a>\n";
    if ($row["nom_utilisateur"]!="" && ($row["mot_de_passe"]=="")) saisie_user($nom_utilisateur);//demande mot de passe pour compléter la base
    if ($row["nom_utilisateur"]=="") echo "<b>$nom_utilisateur, vous n'êtes pas un utilisateur enregistré.</b>
         		<br>
         		<br>
         		<a href=\"javascript:history.back();\">[<< back to previous page]</a>\n";
	mysql_free_result($result);
	mysql_close($link);
}

function saisie_user($nom_utilisateur) {//demande mot de passe pour compléter l'user au 1er accès

	echo "<table class=\"Stable\" border=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">
		<table class=\"Stable\" border=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">
    		<div align=\"center\">
				<form name=\"FormName\" action=\"$PHP_SELF\" method=\"get\">
					<tr>
    					<td colspan='2'>Bienvenue $nom_utilisateur. Merci de compléter votre enregistrement :</td>
            		</tr>
            		<tr>
    					<td>Mot de passe :</td>
                		<td><input name=\"password\" type=\"password\" value=\"\"></td>
                		<input name=\"name\" type=\"hidden\" value=\"$nom_utilisateur\">
        				<input name=\"privilege\" type=\"hidden\" value=\"user\">
            		</tr>
            		<tr>
            			<td align=\"center\" colspan=\"2\"><input type=\"submit\" id=\"style1\" value=\"Send\"></td>
    				</tr>
				</form>
    		</div>
    	</table>
	</table>";
	}

function creation_user($nom_utilisateur, $passe) {// accès d'un user et ajout du mot de passe de son choix lors de la 1ère visite

	if ($nom_utilisateur != "" && $passe != "") {
	   include("link.inc.php");

    	$insert = mysql_query("
			UPDATE
				disco_utilisateurs
			SET
        		mot_de_passe='$passe'
			WHERE
				nom_utilisateur LIKE '$nom_utilisateur'");

		mysql_close($link);
    	}

	$name="";
	$passe="";

	saisie_nom();
	}

function saisie_nom() { // formulaire de demande de nom + mot de passe

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

	echo "<table class=\"Stable\" border=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">
		<table class=\"Stable\" border=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">
    		<div align=\"center\">
				<form name=\"FormName\" action=\"$PHP_SELF\" method=\"get\">
        			<tr>
            			<td colspan=2>".$txt_saisir."</td>
            		</tr>
            		<tr>
            			<td>&nbsp;</td>
                		<td>&nbsp;</td>
            		</tr>
					<tr>
    					<td>".$txt_nom." :</td>
                		<td><input name=\"name\" type=\"text\" value=\"\"></td>
            		</tr>
            		<tr>
    					<td>".$txt_mdp." :</td>
                		<td><input name=\"password\" type=\"password\" value=\"\"></td>
            		</tr>
            		<tr>
            			<td align=\"center\" colspan=\"2\"><input type=\"submit\" id=\"style1\" value=\"".$txt_envoyer."\"></td>
    				</tr>
            		<tr>
            			<td>&nbsp;</td>
                		<td>&nbsp;</td>
            		</tr>
				</form>
    		</div>
    	</table>
	</table>";
	}

function verif_passe($nom_utilisateur, $passe) { //vérifie le mot de passe

	include("link.inc.php");

    $query = "
    		SELECT
    			nom_utilisateur,
                mot_de_passe,
                privilege,
                level
    		FROM
    			disco_utilisateurs
    		WHERE
    			nom_utilisateur LIKE '$nom_utilisateur'";
                // AND
                //mot_de_passe LIKE '$password'";
    $result = mysql_query($query) or die(mysql_error());
    $numrows = mysql_num_rows($result);

	if ($numrows == 0) {
       	echo "<b>$nom_utilisateur, vous n'êtes pas un utilisateur enregistré.</b>
         		<br>
         		<br>
         		<a href=\"javascript:history.back();\">[<< back to previous page]</a>\n";
			}


    if ($numrows > 0) {
		$row = mysql_fetch_assoc($result);
        $curlevel=$row["level"];

       if ($row["mot_de_passe"]==$passe) acces_admin($curlevel);
       if ($row["mot_de_passe"]=="") creation_user($nom_utilisateur, $passe);
       if ($row["mot_de_passe"]!=$passe && $row["mot_de_passe"]!="") {

    	  echo "<b>Mot de passe incorrect.</b>
         			<br>
         			<br>
         			<a href=\"javascript:history.back();\">[<< back to previous page]</a>\n";
          }
       }

   	mysql_free_result($result);
	mysql_close($link);
	}

function acces_admin($curlevel) { // accès au menu admin

	include ("admin_menu.php");

	affiche_menu($curlevel);
	}
echo "</div>";
BASPAGEWEB2();
?>
