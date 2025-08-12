<?php

require("presentation.inc.php");
require("config.inc.php");
$lang_filename = "lang/".$lang."/".$lang."_trad.inc.php";
require($lang_filename);

HAUTPAGEWEB('Discoman - Record add');
LAYERS2();

include("form.inc.php");

$nom="".@$_POST[form_artiste]."";
$memonom=$nom;
$type="".@$_POST[form_formats]."";
$an2="".@$_POST[form_annee2]."";
$pays="".@$_POST[form_pays]."";
$ref="".@$_POST[form_ref]."";
$com="".@$_POST[form_com]."";
$titre="".@$_POST[form_titres]."";
$curlevel="".@$_GET[curlevel]."";

$site_name = $_SERVER['HTTP_HOST'];
$url_dir = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

//create upload_files directory if not exist
//If it does not work, create on your own and change permission.
if (!is_dir("upload_files")) {
	die ("upload_files directory doesn't exist");
}

if ($nom != "" && $type != "" && $an2 != "" && $pays != "" && $ref != "" && $titre != "") {

	include("link.inc.php");

    $query = "
    	SELECT
    		id_titre
    	FROM
    		disco_titres
    	WHERE
    		titre = '$titre'"; //si le titre existe déjà dans la base, je récupére son id
    $result = mysql_query($query) or die(mysql_error());
    $select = mysql_fetch_row($result);
    if ($select!= NULL) $titre= $select[0];
    else {
    	$insert = mysql_query("
    		INSERT INTO disco_titres
        		(titre)
			VALUES
        		('$titre')");// s'il n'existe pas, je le crée

    	$titre = mysql_insert_id();//je récupére l'id du titre créé
        }

    //mysql_free_result($result);

	$insert2 = mysql_query("
    INSERT INTO disco_disques
        (artiste,format,date,pays,reference,commentaire,titre)
	VALUES
        ('$nom','$type','$an2','$pays','$ref','$com','$titre')");//crée le disque

    $id_disque = mysql_insert_id();

	if ($_FILES['userfile1']['size']>0 || $_FILES['userfile2']['size']>0 || $_FILES['userfile3']['size']>0) {
		do_upload($id_disque);
		}

        mysql_close($link);
	}

function do_upload($id_disque) {

$image_array=array();

    for ($i=0; $i<3; $i++) {

    	if ($i == 0) {
        	$donnee = $_FILES['userfile1'];
            $lettre = 'a';
            }
        if ($i == 1) {
        	$donnee = $_FILES['userfile2'];
            $lettre = 'b';
            }
        if ($i == 2) {
        	$donnee = $_FILES['userfile3'];
            $lettre = 'c';
            }

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
    		$image1=$id_disque.$lettre.$file_type2;
			$file_path = $upload_dir.$image1;

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

    		$image_array[$i] = $image1;
    	}

    	if ($i==2) insert1($image_array, $id_disque);
    }
}

function insert1($image_array, $id_disque) {

	include("link.inc.php");

	$insert = mysql_query("
    	INSERT INTO disco_images
        	(imagea, imageb, imagec)
		VALUES
        	('$image_array[0]', '$image_array[1]', '$image_array[2]')");

	$id_image = mysql_insert_id();//je récupére l'id de l'image

    $update = mysql_query("
		UPDATE
			disco_disques
		SET
        	image='$id_image'
		WHERE
			id_disque LIKE '$id_disque'");
    }

echo "<FORM METHOD=\"POST\" name=\"upload\" id=\"upload\"  ENCTYPE=\"multipart/form-data\">
	 <table class=\"Mtable\" border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
       <tr>
      	<th colspan=2>".$txt_ajouter_disque."</th>
       </tr>
       </table>
           <table class=\"Stable\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber2\">
    <tr>
    	<td>
    <table class=\"Stable\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"tab3\">
	<tr>
    	<td colspan=2>";
if ($insert2) {
    echo $txt_le_disque." ".$id_disque." ".$txt_succes;
    }
else echo "&nbsp;";
echo "
		</td>
     </tr>
     <tr>
       	<td>
$txt_artiste :
 		</td>
        <td>";
		affichartiste($memonom);
echo "</td>
 	</tr>
 	<tr>
    	<td>
$txt_image 1 :
		</td>
        <td>
			<input type=\"file\" id=\"userfile1\" name=\"userfile1\">
		</td>
 	</tr>
 	<tr>
    	<td>
$txt_image 2 :
		</td>
        <td>
			<input type=\"file\" id=\"userfile2\" name=\"userfile2\">
		</td>
 	</tr>
 	<tr>
    	<td>
$txt_image 3 :
		</td>
        <td>
			<input type=\"file\" id=\"userfile3\" name=\"userfile3\">
		</td>
 	</tr>
 	<tr>
        <td>
$txt_format :
		</td>
        <td>";
        affichformat(2,0);
echo "  </td>
     </tr>
     <tr>
        <td>
$txt_annee :
		</td>
        <td>
              <input type=\"text\" name=\"form_annee2\" size=\"4\"></td>
     </tr>
     <tr>
        <td>
$txt_pays :
		</td>
        <td>";
		affichpays(2,0);
echo"   </td>
     </tr>
     			<tr>
            	<td>$txt_ref :</td>
                <td><input type=\"text\" name=\"form_ref\" maxlength=\"40\"></td>
            </tr>
            <tr>
            	<td>$txt_com :</td>
                <td><textarea name=\"form_com\" cols=\"40\" rows=\"3\"></textarea></td>
            </tr>
            <tr>
            	<td>$txt_titres :</td>
                <td><textarea rows=\"8\" name=\"form_titres\" cols=\"40\"></textarea></td>
        		<input name=\"curlevel\" type=\"hidden\" value=\"$curlevel\">
            </tr>";

echo "<tr>
		<td colspan=2><div align=\"center\"><input type=\"submit\" id=\"style1\" value=$txt_ajouter name=\"upload\"></div></td>
        </tr></table></td></tr></FORM></table>
     </div>\n";

LAYERPAGEDEB();

echo "<table width='100%'>
	<tr>
        <td align='left'><a href=\"admin.php?curlevel=$curlevel\">[<< back to admin page] </a></td>
    </tr>
</table>";

LAYERPAGEFIN();

BASPAGEWEB2();
?>