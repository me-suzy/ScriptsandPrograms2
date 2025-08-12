<?

//settings

$forms = 1;  	//standard number of upload fields
$user = TRUE;         //user can decide how many pictures will be uploaded
$max_size = $settings['max_upload'];        //max upload size
$max_thumb = $settings['img_width'];     //max height or width of image
$max_big = $settings['img_fullwidth'];     //max height or width of image 
$ext = "jpg jpeg gif";  //allowed filetypes

//Script 

// fetching allowed extensions
$ext = strtolower($ext); 
$ext = explode(" ", $ext); 
$ext_aantal = count($ext); 

 $i=0;
if(!empty($_FILES['image']['type'][$i])){
$aantal=count($_FILES['image']['type']); 
 

    for($i=0;$i<$aantal;$i++){ 
        $foto_name=rand(1,9999)."_".time(); 
        if(!empty($_FILES['image']['tmp_name'][$i])){ 
            $type= $_FILES['image']['type'][$i]; 

            // Bestands naam opvragen 
            $bestand2 = explode("\\", $_FILES['image']['name'][$i]); 
            $laatste = count($bestand2) - 1; 
            $bestand2 = "$bestand2[$laatste]"; 

            // Extensie van bestand opvragen 
            $bestand3 = explode(".", $bestand2); 
            $laatste = count($bestand3) - 1; 
            $bestand3 = "$bestand3[$laatste]"; 
            $bestand3 = strtolower($bestand3); 

            for ($tel = 0;$tel < $ext_aantal; $tel++){ 
                if ($bestand3 == $ext[$tel]){ 
                    $extfout = "nee"; 
                } 
            } 
                     
            if($type == "image/gif" OR $type == "image/pjpeg" OR $type == "image/x-png" OR $type == "image/jpeg"){ 
                if(!empty($max_size) && $_FILES['image']['size'][$i] > $max_size){ 
                    $messages[]="File too big. Your file: ".$_FILES['image']['size'][$i].". Allowed size: ".$max_size; 
                }elseif(!isset($extfout)){ 
                }else{ 
                     
                    if(move_uploaded_file($_FILES['image']['tmp_name'][$i],  "../upload/".$foto_name."_temp".".".$bestand3)){ 
                       
                        if($type == "image/gif"){ 
                            $photo = imagecreatefromgif("../upload/".$foto_name."_temp".".".$bestand3); 
                        }elseif($type == "image/pjpeg" OR $type == "image/jpeg"){ 
                            $photo = imagecreatefromjpeg ("../upload/".$foto_name."_temp".".".$bestand3); 
                        }elseif($type == "image/x-png"){ 
                            $photo = imagecreatefrompng("../upload/".$foto_name."_temp".".".$bestand3); 
                        } 
                        $photo_dimensions_width = imagesx ($photo); 
                        $photo_dimensions_height = imagesy ($photo); 
						
						// small --> hacked so it only looks at the width, not the height
                        if ($photo_dimensions_width > $max_thumb) { 
                          if ($photo_dimensions_width == $photo_dimensions_height) { 
                            $thumb_dimensions_width = $max_thumb; 
                            $thumb_dimensions_height = $max_thumb; 
                          } 

                          else { 
                            $value = $photo_dimensions_width / $max_thumb; 
                            $thumb_dimensions_width = $max_thumb; 
                            $thumb_dimensions_height = round ($photo_dimensions_height / $value); 
                          } 

                      }
					                          else { 
                          $thumb_dimensions_width = $photo_dimensions_width; 
                          $thumb_dimensions_height = $photo_dimensions_height; 

                        } 
						
						// big
					
					if ($photo_dimensions_width > $max_big OR $photo_dimensions_height > $max_big) { 
                          if ($photo_dimensions_width == $photo_dimensions_height) { 
                            $big_dimensions_width = $max_big; 
                            $big_dimensions_height = $max_big; 
                          } 

                          elseif ($photo_dimensions_width > $photo_dimensions_height) { 
                            $value_big = $photo_dimensions_width / $max_big; 
                            $big_dimensions_width = $max_big; 
                            $big_dimensions_height = round ($photo_dimensions_height / $value_big); 
                          } 

                          else { 
                            $value_big = $photo_dimensions_height / $max_big; 
                            $big_dimensions_height = $max_big; 
                            $big_dimensions_width = round ($photo_dimensions_width / $value_big); 
							

                          } 
						  							
                        } 
						
						else { 
           
						  $big_dimensions_width = $photo_dimensions_width; 
                          $big_dimensions_height = $photo_dimensions_height; 
                        } 
						



                        $create_thumb = imagecreatetruecolor ($thumb_dimensions_width, $thumb_dimensions_height); 
                        imagecopyresampled ($create_thumb, $photo, 0, 0, 0, 0, $thumb_dimensions_width, $thumb_dimensions_height, $photo_dimensions_width, $photo_dimensions_height);
                        ImageJpeg($create_thumb,'../thumbs/'.$foto_name.".jpg",90); 

								
						
						$create_big = imagecreatetruecolor ($big_dimensions_width, $big_dimensions_height); 
                        imagecopyresampled ($create_big, $photo, 0, 0, 0, 0, $big_dimensions_width, $big_dimensions_height, $photo_dimensions_width, $photo_dimensions_height);

						
						ImageJpeg($create_big,'../upload/'.$foto_name.".jpg",90); 
                        Imagedestroy($photo); 
						
						// delete original image from folder "upload"
						$originalpath = "../upload/".$foto_name."_temp".".".$bestand3;
						unlink($originalpath);
						

						

	

                    }else{ 
                        $messages[]="Your file:" . $_FILES['image']['name'][$i]." could not be uploaded"; 
                    } 
                } 
            }else{ 
                $messages[]="Unsupported filetype: ".$type.". Allowed filetypes: jpg and gif"; 
            } 
        }else{ 
            $messages[]="No file selected"; 
        } 
		$images[] .= "$foto_name" . ".jpg";  //create array
		$bigwidth[] .= $big_dimensions_width;  //create array
		$bigheight[] .= $big_dimensions_height;  //create array
    }
	
$image = implode(";", $images); //make images ready for database insertion
$imagewidth = implode(";", $bigwidth); //make image width ready for database insertion
$imageheight = implode(";", $bigheight); //make image width ready for database insertion
}
else
{
// only for editor
$image = $entry[image];
$imagewidth = $entry[imagewidth];
$imageheight = $entry[imageheight];
}


?>