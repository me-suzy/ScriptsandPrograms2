<?
include("functions.php");
include("config.php");
include("loadsettings.php");
include("../language/$settings[language].php"); 

//Instellingen 
$forms = 1;           //Standaard aantal file uploads 
$user = TRUE;         //User kan aangeven dat hij meer files wil uploaden 
$max_size = 0;        //Maximale bestands grootte, 0 voor ongelimiteerd 
$max_thumb = $_POST['width'];     //Maximale hoogte dan wel breedte van de thumb. 
$ext = "jpg jpeg gif";  // Welke extensies kunnen er worden geupload ( als alles mag dan niks invullen ) 

//Script 

// Toegestaande extensies opvragen 
$ext = strtolower($ext); 
$ext = explode(" ", $ext); 
$aantal = count($ext); 

if(isset($_POST['forms']) && $user==TRUE){ 
    $forms=$_POST['forms']; 
} 
?> 
<!doctype html public "-//W3C//DTD HTML 4.0 //EN"> 
<html> 
<head> 
       <title>Zomplog Media Manager</title> 
<link rel="stylesheet" href="style.css" type="text/css" />	   
</head> 
<body> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
  <tr>
    <td><h1>Custom Upload Tool </h1></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>The Custom Upload Tool allows you to use your images more flexible. You decide where your image is placed on the page, its width, and its alignment! After you've uploaded your image, the Custom Upload Tool generates the code for you. Just copy-paste it into your post or page, and you're done! </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><h1>Upload Image</h1></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><? 
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])){ 
    $aantal=count($_FILES['image']['type']); 
    $i=0; 
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

            for ($tel = 0;$tel < $aantal; $tel++){ 
                if ($bestand3 == $ext[$tel]){ 
                    $extfout = "nee"; 
                } 
            } 
                     
            if($type == "image/gif" OR $type == "image/pjpeg" OR $type == "image/x-png" OR $type == "image/jpeg"){ 
                if(!empty($max_size) && $_FILES['image']['size'][$i] > $max_size){ 
                    echo"Bestand is te groot: ".$_FILES['image']['size'][$i]." / ".$max_size."<br>\n"; 
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

                       						// small
                        if ($photo_dimensions_width > $max_thumb OR $photo_dimensions_height > $max_thumb) { 
                          if ($photo_dimensions_width == $photo_dimensions_height) { 
                            $thumb_dimensions_width = $max_thumb; 
                            $thumb_dimensions_height = $max_thumb; 
                          } 

                          elseif ($photo_dimensions_width > $photo_dimensions_height) { 
                            $value = $photo_dimensions_width / $max_thumb; 
                            $thumb_dimensions_width = $max_thumb; 
                            $thumb_dimensions_height = round ($photo_dimensions_height / $value); 
                          } 

                          else { 
                            $value = $photo_dimensions_height / $max_thumb; 
                            $thumb_dimensions_height = $max_thumb; 
                            $thumb_dimensions_width = round ($photo_dimensions_width / $value); 
                          } 
                      }
					                          else { 
                          $thumb_dimensions_width = $photo_dimensions_width; 
                          $thumb_dimensions_height = $photo_dimensions_height; 

                        } 
						


                        $create_thumb = imagecreatetruecolor ($thumb_dimensions_width, $thumb_dimensions_height); 
                        imagecopyresampled ($create_thumb, $photo, 0, 0, 0, 0, $thumb_dimensions_width, $thumb_dimensions_height, $photo_dimensions_width, $photo_dimensions_height);
                        ImageJpeg($create_thumb,'../thumbs/'.$foto_name.".jpg",90); 
 echo"<tr><td><div class='img-shadow'><img src=\"../thumbs/".$foto_name.".jpg\" \></div></tr></td>"; 
						echo "<tr><td>To use the above thumbnail, copy this code into your post or page: <br /><input name='code' type='text' size='43' value='<img src=\"thumbs/".$foto_name.".jpg\" align=\"$_POST[align]\" \>'></tr></td><tr><td>&nbsp;</tr></td>";
						
						// delete original image from folder "upload"
						$originalpath = "../upload/".$foto_name."_temp".".".$bestand3;
						unlink($originalpath);

						
                    }else{ 
                        echo $_FILES['image']['name'][$i]." could not be uploaded.<br \>\n"; 
                    } 
                } 
            }else{ 
                echo "Invalid Filetype: ".$type." Allowed filetypes: jpg and gif<br>\n"; 
            } 
        }else{ 
            echo "Geen file geselecteerd.<br>\n"; 
        } 
    } 
}else{ 
    if($user==TRUE){
	
	?>
        
        <FORM NAME="select" METHOD="POST" action="<?echo$_SERVER['PHP_SELF'];?>"> 
        <? echo "$lang_number_of_images"; ?> <SELECT name="forms" onchange="javascript:document.select.submit();"> 
        <? 
        for($i=1;$i<21;$i++){ 
            ?> 
            <option value="<?=$i?>"><?=$i?></option> 
            <? 
        } 
        ?> 
        </SELECT> 
        </FORM> 
        <? 
    } 
    ?> 
	   <FORM NAME="upload_form" METHOD="POST" ACTION="<?echo$_SERVER['PHP_SELF'];?>"  ENCTYPE="multipart/form-data"> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td>Choose image (jpg only) </td>
  </tr>
  <tr>
    <td><? 
    for($i=0;$i<$forms;$i++){ 
        ?> 
        <INPUT TYPE="file" value="1" NAME="image[<?=$i?>]" \><br \> 
        <? 
    } 
    ?> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Image Width (pixels) </td>
  </tr>
  <tr>
    <td><input type="text" name="width" value="<? echo "$settings[img_fullwidth]"; ?>"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Image Align</td>
  </tr>
  <tr>
    <td><select name="align">
      <option value="left">left</option>
      <option value="center">center</option>
      <option value="right">right</option>
    </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><INPUT TYPE="submit" NAME="submit" VALUE="Upload"></td>
  </tr>
</table>
    </FORM> 
    <? 
} 
?></td>
  </tr>
</table>
 
</body> 
</html> 
