<?php // SCRIPT D'AFFICHAGE D'UN DISQUE

require("presentation.inc.php");
HAUTPAGEWEB('Discoman - Titles');
LAYERS2();

require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_admin.inc.php";
require($lang_filename);

$site_name = $_SERVER['HTTP_HOST'];
$url_dir = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

$mode="".@$_GET[mode]."";//1=visu, 2=modif, 3=supprimer, au moins 2 images, 9=supprimer, une seule image, 4=ajouter, une entrée existe déjà, 5=ajouter, aucune entrée, 6=changer image info, 7=ajout d'une image info, 8=suppression d'une image info
$id_image="".@$_GET[id_image]."";//id_image
$test2="".@$_POST[test2]."";//si l'on clique sur update
$nombre=-1;//pour revenir à la page précédente
$indice="".@$_GET[indice]."";//nom du champ de la table images
$valeur="".@$_GET[valeur]."";//contenu du champ de la table images
$id_disque="".@$_GET[id_disque]."";//id_disque de la table disques
$id_infos="".@$_GET[id_infos]."";//ajout d'une image à une info
$upload_dir="".@$_GET[upload_dir]."";
$nbre_images="".@$_GET[nbre_images]."";

function do_upload($id_disque,$mode,$indice,$id_image) {

	$donnee = $_FILES['userfile1'];

    if ($donnee['size'] > 0) {

       print $message;
	   $upload_dir = "upload_files/";
	   $upload_url = $url_dir."/upload_files/";

	   $message ="";

	   $temp_name = $donnee['tmp_name'];
	   $file_name = $donnee['name'];
	   $file_type = $donnee['type'];
	   $file_size = $donnee['size'];
	   $result3   = $donnee['error'];
	   $file_url  = $upload_url.$file_name;
       if ($file_type=='image/pjpeg') $file_type2=str_replace('image/pjpeg','.','.jpg');
       if ($file_type=='image/gif') $file_type2=str_replace('image/','.',$file_type);
       $valeur=substr($indice,-1);//extrait la lettre de l'indice (ex : imagea)
       $valeur=$id_disque.$valeur;//nom de l'image sans extension
       $valeur=$valeur.$file_type2;//nom de l'image avec extension
       //$valeur=$image1;
	   $file_path = $upload_dir.$valeur;

	   //File Name Check
       if ( $file_name =="") {
    		$message = "Invalid File Name Specified";
    		return $message;
    		}
       //File Size Check
       else if ( $file_size > 300000) {
        	$message = "The file size is over 300K.";
        	return $message;
    		}
       //File Type Check
       else if ( $file_type != "image/gif" && $file_type != "image/pjpeg") {
        	$message = "Sorry, you cannot upload any other file than gif, jpeg or jpg" ;
        	return $message;
    		}

       $result3  =  move_uploaded_file($temp_name, $file_path);
       //$message = ("")?"File url <a href=$file_url>$file_url</a>" : "Something is wrong with uploading a file.";

       if ($mode==4) update($id_image,$indice,$valeur,$mode);
       if ($mode==5) insert($valeur,$indice,$id_disque);
       }
    }

function update ($id_image,$indice,$valeur,$mode) {//mise à jour de la table images

	include("link.inc.php");

    $update = mysql_query("
		UPDATE
			disco_images
		SET
        	$indice='$valeur'
		WHERE
			id_image LIKE '$id_image'");

	mysql_close($link);

	if ($mode==4) {
    	$upload_dir='upload_files';
    	affich_confirm_ajout($valeur,$upload_dir);
    	}
	}

function update_infos ($id_infos) {//mise à jour de la table infos

	include("link.inc.php");

    $update = mysql_query("
		UPDATE
			disco_infos
		SET
        	image=''
		WHERE
			id_infos LIKE '$id_infos'");

	mysql_close($link);
	}

function update_disque ($id_disque) {//mise à jour de la table disques

	include("link.inc.php");

    $update = mysql_query("
		UPDATE
			disco_disques
		SET
        	image=''
		WHERE
			id_disque LIKE '$id_disque'");

	mysql_close($link);
	}

function affich_confirm_ajout($valeur, $upload_dir) {
    echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>Confirmation</th>
    		</tr>
  		</table>";
		LAYERS5();
	echo "
    	<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    		<tr>
    			<td>L'image ".$valeur." a été ajoutée.</td>
    		</tr>
    		<tr>
    			<td><div align=\"center\"><img src='".$upload_dir."/".$valeur."' width=100 border=0></div></td>
    		</tr>
		</table>
	</div></div>\n";
    }

function insert($valeur, $indice, $id_disque) {

	include("link.inc.php");

	$insert = mysql_query("
    	INSERT INTO disco_images
        	($indice)
		VALUES
        	('$valeur')");

	$id_image = mysql_insert_id();//je récupére l'id de l'image

    $update = mysql_query("
		UPDATE
			disco_disques
		SET
        	image='$id_image'
		WHERE
			id_disque LIKE '$id_disque'");
    mysql_close($link);
    $upload_dir='upload_files';
    affich_confirm_ajout($valeur,$upload_dir);
    }

function delete_images($id_image) {//supprime l'entrée images dans la table images s'il n'y avait plus qu'une seule image

	include("link.inc.php");

	$delete = mysql_query("
		DELETE FROM
			disco_images
		WHERE
			id_image LIKE '$id_image'");

    mysql_close($link);
    }

function confirmation_suppression($valeur) {

    echo "
		<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
    			<th>Confirmation</th>
    		</tr>
  		</table>";
		LAYERS5();
	echo "
    	<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    		<tr>
    			<td>L'image ".$valeur." a été supprimée.</td>
    		</tr>
		</table>
	</div></div>\n";
    }

if ($test2==1 && $mode==2) {//remplacement d'une image disque par une autre
	if ($_FILES['userfile1']['size']>0) {
       	$picture="upload_files/".$valeur;
	   	@unlink($picture);
        $val2=strtok($valeur,".");
		do_upload($val2,$mode,'','');
        $test2=0;
        $nombre=-2;//pour que le previous page retourne à la fiche disque
		}
    }

if ($test2==1 && $mode==6) {//remplacement d'une image info par une autre
	if ($_FILES['userfile1']['size']>0) {
       	$picture=$upload_dir."/".$valeur;
	   	@unlink($picture);
        //$val2=strtok($val,".");
        include ('functions.inc.php');
		do_upload_infos($id_infos);
        $test2=0;
        $nombre=-2;//pour que le previous page retourne à la fiche disque
		}
    }

if ($test2==1 && $mode==3) {//suppression d'une image disque
	$picture="upload_files/".$valeur;
   	@unlink($picture);
    $val1=strtok($valeur,".");
    $val1=substr($val1,-1);
    $indice="image".$val1;//champ à effacer

    if ($nbre_images>1) update ($id_image,$indice,'',$mode);
    if ($nbre_images==1) {
    	update_disque($id_disque);
        delete_images($id_image);
        }
   	$nombre=-2;//pour que le previous page retourne à la fiche disque
    confirmation_suppression($valeur);
    }

if ($test2==1 && $mode==8) {//suppression d'une image info
	$picture=$upload_dir."/".$valeur;
   	@unlink($picture);
    update_infos ($id_infos);
   	$nombre=-2;//pour que le previous page retourne à la fiche disque
    confirmation_suppression($valeur);
    }

if ($test2==1 && $mode==4 || $test2==1 && $mode==5) {//ajout d'une image pour un disque
	if ($_FILES['userfile1']['size']>0) {
		do_upload($id_disque,$mode,$indice,$id_image);
        $nombre=-2;//pour que le previous page retourne à la fiche disque
		}
    }

if ($test2==1 && $mode==7) {//ajout d'une image pour une info
	if ($_FILES['userfile1']['size']>0) {
		include ('functions.inc.php');
		do_upload_infos($id_infos);
        affich_confirm_ajout($valeur,$upload_dir);
        $nombre=-2;//pour que le previous page retourne à la fiche disque
		}
    }

if ($test2!=1) {

switch ($mode) {

CASE "1" : //visu de l'image disque

	echo "
	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
    		<th>$txt_image ".$valeur."</th>
    	</tr>
  	</table>";
	LAYERS5();
	echo "
	<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    	<tr>
    		<td><div align=\"center\"><img src='".$upload_dir."/".$valeur."' border=0></div></td>
    	</tr>
	</table>
	</div></div>\n";
	break;

CASE "2" : //modif image disque
CASE "6" : //modif image info

	echo "
	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
    		<th>$txt_image ".$valeur."</th>
    	</tr>
  	</table>";
	LAYERS5();
	echo "
	<FORM METHOD=\"POST\" name=\"upload\" id=\"upload\"  ENCTYPE=\"multipart/form-data\">
		<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    		<tr>
    			<td><div align=\"center\"><img src='".$upload_dir."/".$valeur."' width=100 border=0></div></td>
        		<td><div align=\"center\"><input type=\"file\" id=\"userfile1\" name=\"userfile1\"></div></td>
    		</tr>
    		<tr>
				<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_envoyer\" name=\"Update\"></div></td>
            	<input name=\"test2\" type=\"hidden\" value=\"1\">
			</tr>
		</table>
	</FORM>
	</div></div>\n";
	break;

CASE "3" : //supprimer image disque
CASE "8" : //supprimer image info

	echo "
	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
    		<th>$txt_image ".$valeur."</th>
    	</tr>
  	</table>";
	LAYERS5();
echo "
	<FORM METHOD=\"POST\" name=\"delete\" id=\"delete\"  ENCTYPE=\"multipart/form-data\">
		<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    		<tr>
    			<td><div align=\"center\"><img src='".$upload_dir."/".$valeur."' width=100 border=0 style=\"cursor:move;\" onClick='location=\"title2.php?valeur=".$valeur."&upload_dir=".$upload_dir."&mode=1\"'></div></td>
    		</tr>
    		<tr>
				<td><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_effacer\" name=\"Delete\"></div></td>
            	<input name=\"test2\" type=\"hidden\" value=\"1\">
			</tr>
		</table>
	</FORM>
</div></div>\n";
	break;

CASE "4": //ajout d'une seconde ou 3ème image disque
CASE "5": //ajout d'une 1ère image disque
CASE "7": //ajout d'une image info

	echo "
	<table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
		<tr>
    		<th>Ajout image</th>
    	</tr>
  	</table>";
	LAYERS5();
	echo "
	<FORM METHOD=\"POST\" name=\"upload\" id=\"upload\" ENCTYPE=\"multipart/form-data\">
		<table class=\"Stable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
    		<tr>
        		<td><div align='center'><input type=\"file\" id=\"userfile1\" name=\"userfile1\"></div></td>
    		</tr>
    		<tr>
				<td><div align=\"center\"><input type=\"submit\" id=\"style1\" value=\"$txt_ajouter\" name=\"Ajouter\"></div></td>
            	<input name=\"test2\" type=\"hidden\" value=\"1\">
			</tr>
		</table>
	</FORM>
	</div></div>\n";
	break;

	}
}

LAYERPAGEDEB3($nombre);
LAYERPAGEFIN();
BASPAGEWEB2();
?>