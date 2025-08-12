<?php   // SCRIPT D'AFFICHAGE D'UN ENREGISTREMENT DISQUE POUR MODIFICATION

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Title update');

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

//LAYERS3();
include("form.inc.php");
include("link.inc.php");

$nom="".@$_GET[stripslashes(form_artiste)]."";
$id_artiste="".@$_GET[form_id_artiste]."";
$type="".@$_GET[form_formats]."";
$an2="".@$_GET[form_annee2]."";
$pays="".@$_GET[form_pays]."";
$ref="".@$_GET[form_ref]."";
$com="".@$_GET[form_com]."";
$titre2="".@$_GET[form_titres]."";//le titre proposé dans le formulaire
$id_titre="".@$_GET[form_id_titre]."";
$id_disque="".@$_GET[id_disque]."";

$test="".@$_GET["test"]."";
$curlevel="".@$_GET[curlevel]."";
$nbre_images=0;//compte le nbre d'images à afficher pour suppression

if ($test==1) {

    $query = "
    		SELECT
    			titre
    		FROM
    			disco_titres
    		WHERE
    			id_titre = '$id_titre'";//teste si modif du titre
    $result = mysql_query($query) or die(mysql_error());
    $result = mysql_fetch_row($result);
    if ($result!= NULL) $titre1= $result[0];

	$titre2=stripslashes($titre2);

    if ($titre1 == $titre2) {//si aucune modif des titres

	   $update = mysql_query("
	   		UPDATE
				disco_disques
			SET
	           	format='$type',
            	titre='$id_titre',
            	pays='$pays',
            	commentaire='$com',
            	reference='$ref',
            	date='$an2'
			WHERE
				id_disque LIKE '$id_disque'");
        	}

    if ($titre1 != $titre2) {//s'il y a modif des titres

	$result = mysql_query("
    	SELECT
        COUNT(id_disque) AS id
        FROM
        	disco_disques
        WHERE
        	disco_disques.titre = '$id_titre'") or die(mysql_error());

	$row1 = mysql_fetch_assoc($result);//vérifie combien d'enregistrements comportent le titre non modifié

	mysql_free_result($result);

    $titre2=addslashes($titre2);

	$result = mysql_query("
    	SELECT
        COUNT(id_titre) AS id
        FROM
        	disco_titres
        WHERE
        	disco_titres.titre = '$titre2'") or die(mysql_error());

	$row2 = mysql_fetch_assoc($result);//vérifie si le titre modifié existe déjà dans la base

	mysql_free_result($result);

		if ($row1[id] == 1 && $row2[id] == 0) {//si aucun autre enregistrement n'utilisait le titre non modifié et s'il n'existe pas déjà dans la base => update titre OK

    	   $update = mysql_query("
				UPDATE
					disco_titres
				SET
        			titre='$titre2'
				WHERE
					id_titre LIKE '$id_titre'");
        }

		if ($row1[id] == 1 && $row2[id] > 0) {//si aucun autre enregistrement n'utilise le titre non modifié et si le titre modifié existe déjà dans la base => update disque + delete titre

            $query = mysql_query("
    			SELECT
    				id_titre
    			FROM
    				disco_titres
    			WHERE
    				titre = '$titre2'");//je récupére l'id du titre existant déjà dans la base
    		$query = mysql_fetch_row($query);
            if ($query!= NULL) $id_titre2= $query[0];//id_titre existant

			$update = mysql_query("
				UPDATE
					disco_disques
				SET
            		format='$type',
            		titre='$id_titre2',
            		pays='$pays',
            		commentaire='$com',
            		reference='$ref',
            		date='$an2'
				WHERE
					id_disque LIKE '$id_disque'");

			$delete = mysql_query("
				DELETE FROM
					disco_titres
				WHERE
					id_titre = '$id_titre'");//j'efface l'enregistrement inutilisé
       		}

		if ($row1[id] > 1 && $row2[id] == 0) {//si d'autres enregistrements utilisent le titre non modifié et que le titre du formulaire n'existe pas => création du nouveau titre dans la base titres + update de la base disques

    		$insert = mysql_query("
    			INSERT INTO disco_titres
        			(titre)
				VALUES
        			('$titre2')");

$id_titre2 = mysql_insert_id();//Je récupére l'id du nouveau titre

			$update = mysql_query("
				UPDATE
					disco_disques
				SET
            		format='$type',
            		titre='$id_titre2',
            		pays='$pays',
            		commentaire='$com',
            		reference='$ref',
            		date='$an2'
				WHERE
					id_disque LIKE '$id_disque'");
        }

		if ($row1[id] > 1 && $row2[id] > 0) {//si d'autres enregistrements utilisent le titre non modifié et que le titre du formulaire existe déjà => update uniquement de la base disques

            $query = mysql_query("
    			SELECT
    				id_titre
    			FROM
    				disco_titres
    			WHERE
    				titre = '$titre2'");//je récupére son id
    		$query = mysql_fetch_row($query);
            if ($query!= NULL) $id_titre2= $query[0];//le nouveau id_titre

			$update = mysql_query("
				UPDATE
					disco_disques
				SET
            		format='$type',
            		titre='$id_titre2',
            		pays='$pays',
            		commentaire='$com',
            		reference='$ref',
            		date='$an2'
				WHERE
					id_disque LIKE '$id_disque'");
        }
    }
    //mysql_free_result($result);
	mysql_close($link);

LAYERS2();

	if ($update) echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>Titles update</th>
    		</tr>
    	</table>
    	<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
				<td>Fiche disque $id_disque de ".stripslashes($nom)." mise à jour.</td>
    		</tr>
    	</table></div>";

LAYERPAGEDEB2();

    echo "
    	<table>
			<tr>
       			<td align='left'><a href=\"record_update.php?curlevel=$curlevel&form_artiste=$id_artiste\">[<< back to records list page] </a></td>
    		</tr>
		</table>";

LAYERPAGEFIN();
    }

else {//if $test!=1

	$query="
	 	SELECT
      		disco_artistes.nom,
      		disco_artistes.id_artiste,
            disco_disques.image,
            disco_disques.date,
            disco_disques.reference,
            disco_disques.commentaire,
      		disco_formats.type,
      		disco_pays.abrege,
            disco_titres.id_titre,
      		disco_titres.titre
      	FROM
	 		disco_artistes,
        	disco_disques,
        	disco_formats,
        	disco_pays,
        	disco_titres
		WHERE
      		disco_disques.id_disque LIKE '$id_disque' AND
      		disco_artistes.id_artiste = disco_disques.artiste AND
      		disco_formats.id_type = disco_disques.format AND
      		disco_pays.id_pays = disco_disques.pays AND
      		disco_titres.id_titre = disco_disques.titre";

		$result = mysql_query($query) or die(mysql_error());
		$row4 = mysql_fetch_assoc($result);
        $str=htmlspecialchars($row4['reference'],ENT_QUOTES);

LAYERS3();

	echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>$txt_maj_disque</th>
    		</tr>
  		</table>";
LAYERS4();
	echo "
    	<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<FORM METHOD=\"GET\" ACTION=\"$PHP_SELF\">
    		<tr>
    			<td width='25%'>$txt_artiste :</td>
        		<td colspan=\"3\"><b>".stripslashes($row4['nom'])."</b></td>
        		<input name=\"form_artiste\" type=\"hidden\" value=`".stripslashes($row4['nom'])."`>
        		<input name=\"form_id_artiste\" type=\"hidden\" value=".$row4['id_artiste'].">
        		<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
    		</tr>
    		<tr>
    			<td>$txt_images :</td>";

        $query2 = "
    		SELECT
    			imagea,
                imageb,
                imagec
    		FROM
    			disco_images
    		WHERE
    			disco_images.id_image = '$row4[image]'"; //
    $result2 = mysql_query($query2) or die(mysql_error());
    mysql_close($link);
    $row2 = mysql_fetch_assoc($result2);

    if (is_array($row2)) {
    	while (list($indice,$valeur) = each($row2)) {
			if ($valeur!="") {
            	$nbre_images=$nbre_images+1;
            	echo "
        			<td width='25%' valign='top'><div align=\"center\"><img src='upload_files/".$valeur."' border=0; width=100; style=\"cursor:move;\"; onClick='location=\"title2.php?valeur=".$valeur."&upload_dir=upload_files&mode=1\"'></div></td>";
                }
            else echo "<td width='25%'></td>";
        	}

		echo "</tr>
        	<tr><td><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"$txt_raffraichir\" onClick='javascript:window.location.reload()'></div></td>";

        reset($row2);

    	while (list($indice, $valeur) = each($row2)) {
			if ($valeur!="") echo "
        		<td width='25%'><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"$txt_modifier\" onClick='location=\"title2.php?valeur=".$valeur."&upload_dir=upload_files&mode=2\"'></div></td>";
            else echo "<td width='25%'><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"$txt_ajouter\" onClick='location=\"title2.php?indice=".$indice."&id_image=".$row4['image']."&id_disque=$id_disque&upload_dir=upload_files&mode=4\"'></div></td>";
        	}

		echo "</tr>
        	<tr><td></td>";

        reset($row2);

    	while (list($indice, $valeur) = each($row2)) {
			if ($valeur!="") echo "
        			<td width='25%'><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"$txt_effacer\" onClick='location=\"title2.php?valeur=".$valeur."&id_image=".$row4['image']."&nbre_images=$nbre_images&id_disque=".$id_disque."&upload_dir=upload_files&mode=3\"'></div></td>";
            else echo "<td width='25%'></td>";
        	}
        }
    	else echo "
            	<td colspan=3></td>
			</tr>
        	<tr>
            	<td><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"$txt_raffraichir\" onClick='javascript:window.location.reload()'></div></td>
        		<td width='25%'><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"$txt_ajouter\" onClick='location=\"title2.php?indice=imagea&id_disque=$id_disque&upload_dir=upload_files&mode=5\"'></div></td>
    			<td width='25%'><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"$txt_ajouter\" onClick='location=\"title2.php?indice=imageb&id_disque=$id_disque&upload_dir=upload_files&mode=5\"'></div></td>
				<td width='25%'><div align=\"center\"><input type=\"button\" id=\"style1\" value=\"$txt_ajouter\" onClick='location=\"title2.php?indice=imagec&id_disque=$id_disque&upload_dir=upload_files&mode=5\"'></div></td>";

   	echo "	</tr>
    		<tr>
    			<td>$txt_format :</td>
                <td colspan=\"3\">";
        		$typ=$row4['type'];
        		affichformat(3,$typ);
	echo "		</td>
    		</tr>
    		<tr>
    			<td>$txt_annee :</td>
    			<td colspan=\"3\"><input name=\"form_annee2\" type=\"text\" value=".$row4['date']."></td>
    		</tr>
    		<tr>
    			<td>$txt_pays :</td>
                <td colspan=\"3\">";
        		$pay=$row4['abrege'];
        		affichpays(3,$pay);
	echo "   	</td>
    		</tr>
    		<tr>
    			<td>$txt_ref :</td>
        		<td colspan=\"3\"><input name=\"form_ref\" type=\"text\" value='$str'></td>
    		</tr>
    		<tr>
    			<td>Id :</td>
    			<td colspan=\"3\">$id_disque</td>
        		<input name=\"id_disque\" type=\"hidden\" value=\"$id_disque\">
   			</tr>
    		<tr>
    			<td>$txt_com :</td>
        		<td colspan=\"3\"><textarea name=\"form_com\" cols=\"40\" rows=\"3\">".stripslashes($row4['commentaire'])."</textarea></td>
    		</tr>
    		<tr>
    			<td>$txt_titre :</td>
        		<td colspan=\"3\"><textarea name=\"form_titres\" cols=\"40\" rows=\"8\">".$row4['titre']."</textarea></td>
        		<input name=\"form_id_titre\" type=\"hidden\" value=".$row4['id_titre'].">
    		</tr>
    		<tr>
				<td colspan=4><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_envoyer\" name=\"Add\"></div></td>
        		<input name=\"test\" type=\"hidden\" value=\"1\">
    		</tr>
    	</FORM>
		</table>
	</div></div>";

LAYERPAGEDEB3(-1);
}

BASPAGEWEB2();
?>