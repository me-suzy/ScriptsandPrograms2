<?php

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Delete');
LAYERS2();
include("link.inc.php");

$cas="".@$_GET["cas"]."";

$id_artiste="".@$_GET["id_artiste"]."";
$nom="".@$_GET["nom"]."";//nom de l'artiste pour retour

$id_type="".@$_GET["id_type"]."";
$type="".@$_GET["type"]."";

$id_pays="".@$_GET["id_pays"]."";
$nom_pays="".@$_GET["nom_pays"]."";

$id_utilisateur="".@$_GET["id_utilisateur"]."";
$nom_utilisateur="".@$_GET["nom_utilisateur"]."";

$id_infos="".@$_GET["id_infos"]."";//table infos
$image="".@$_GET["image"]."";//table infos

$curlevel="".@$_GET["curlevel"]."";
$choix="".@$_GET["choix"]."";

$id_disque="".@$_GET["id_disque"]."";//table disques - id_disque
$v1="".@$_GET["v1"]."";//table disques - image 1 + extension
$v2="".@$_GET["v2"]."";//table disques
$v3="".@$_GET["v3"]."";//table disques
$val="".@$_GET["val"]."";//table disques - id_image

switch ($cas) {

CASE 1: // DELETE ARTIST

	$delete = mysql_query("
		DELETE FROM
			disco_artistes
		WHERE
			id_artiste LIKE '$id_artiste'");

	if ($delete) echo "
       <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
       </table>
       <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche de ".stripslashes($nom)." effacée</td>
            </tr>
       </table></div>";

	break;

CASE 2: // DELETE FORMAT

	$delete = mysql_query("
		DELETE FROM
			disco_formats
		WHERE
			id_type LIKE '$id_type'");

	if ($delete) echo "
       <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
       </table>
       <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche de <b>".stripslashes($type)."</b> effacée</td>
            </tr>
       </table></div>";

	break;

CASE 3: // DELETE PAYS
	$delete = mysql_query("
		DELETE FROM
			disco_pays
		WHERE
			id_pays LIKE '$id_pays'");

	if ($delete) echo "
       <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
       </table>
       <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche de <b>".stripslashes($nom_pays)."</b> effacée</td>
            </tr>
       </table></div>";

	break;

CASE 4: // DELETE RECORD
	$delete = mysql_query("
		DELETE FROM
			disco_disques
		WHERE
			id_disque LIKE '$id_disque'");

	if ($v1!="") {
    	$v=strtok($jpg,".");
       	$picture="upload_files/".$v1;
	   	@unlink($picture);
       	}
	if ($v2!="") {
       	$picture="upload_files/".$v2;
	   	@unlink($picture);
       	}
	if ($v3!="") {
       	$picture="upload_files/".$v3;
	   	@unlink($picture);
       	}

	if ($val!="") {
		$delete = mysql_query("
			DELETE FROM
				disco_images
			WHERE
				id_image LIKE '$val'");
        }

	if ($delete) echo "
       <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
       </table>
       <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche <b>$id_disque</b> effacée</td>
            </tr>
       </table></div>";

	break;

CASE 6: // DELETE USERS

	$delete = mysql_query("
		DELETE FROM
			disco_utilisateurs
		WHERE
			id_utilisateur LIKE '$id_utilisateur'");

	if ($delete) echo "
       <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
       </table>
       <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche utilisateur de <b>".stripslashes($nom_utilisateur)."</b> effacée</td>
            </tr>
       </table>
       </div>";

	break;

CASE 7: // DELETE INFOS

	$delete = mysql_query("
		DELETE FROM
			disco_infos
		WHERE
			id_infos LIKE '$id_infos'");

    $picture="images_infos/".$id_infos.$image;
    @unlink($picture);

	if ($delete) echo "
       <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       		<tr>
      			<th colspan=2>Résultat</th>
       		</tr>
       </table>
       <table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
            	<td>Fiche infos n° <b>$id_infos</b> effacée</div></td>
            </tr>
        </table></div>\n";

	break;
	}
LAYERPAGEDEB();

	if ($cas == 4) {
 		echo "
    		<table>
		 		<tr>
         			<td align='left'><a href=\"record_delete.php?curlevel=$curlevel&form_artiste=$nom\">[<< back to admin delete page] </a></td>
    	 		</tr>
			</table>";
		}

	else {
		echo "
    		<table>
		 		<tr>
         			<td align='left'><a href=\"admin_delete.php?curlevel=$curlevel&choix=$choix\">[<< back to admin delete page] </a></td>
    	 		</tr>
			</table>";
    	}
LAYERPAGEFIN();
mysql_close($link);
BASPAGEWEB2();
?>