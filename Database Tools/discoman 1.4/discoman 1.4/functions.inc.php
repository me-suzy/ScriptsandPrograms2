<?php //page regroupant les fonctions commmunes à plusieurs scripts

//fonctions sur le traîtement des images :

function do_upload_infos($id_infos) {//utilisé par admin_adds et title2

	//create upload_files directory if not exist
	//If it does not work, create on your own and change permission.
	if (!is_dir("images_infos")) {
		die ("images_infos directory doesn't exist");
		}

	$donnee = $_FILES['userfile1'];
    global $valeur;

    if ($donnee['size'] > 0) {

		print $message;
	   	$upload_dir = "images_infos/";
	   	$upload_url = $url_dir."/images_infos/";

	   	$message ="";

	   	$temp_name = $donnee['tmp_name'];
	   	$file_name = $donnee['name'];
	   	$file_type = $donnee['type'];
	   	$file_size = $donnee['size'];
	   	$result3   = $donnee['error'];
	   	$file_url  = $upload_url.$file_name;
       	if ($file_type=='image/pjpeg') $file_type2=str_replace('image/pjpeg','.','.jpg');
       	if ($file_type=='image/gif') $file_type2=str_replace('image/','.',$file_type);
       	$valeur=$id_infos.$file_type2;
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
       	$message = ($result3)?"File url <a href=$file_url>$file_url</a>" : "Something is wrong with uploading a file.";

       	include("link.inc.php");

       	$update = mysql_query("
			UPDATE
				disco_infos
			SET
        		image='$file_type2'
			WHERE
				id_infos LIKE '$id_infos'");//met à jour la bdd avec le format de l'image

        mysql_close($link);
		}
	}

?>