<?php
 // file: generate_thumbnail.php
 // desc: generates thumbnails of an image
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

if (!defined(__GENERATE_THUMBNAIL_PHP__)) {

define(__GENERATE_THUMBNAIL_PHP__, true);

function generate_thumbnail ($image, $isLarge = false) {
 global $convert_exec, $djpeg_exec, $cjpeg_exec, $pnmscale_exec;

 // determine size
 if ($isLarge) { $imgsize = 240; }
  else         { $imgsize = 120; }

 // check for existance of proper executables
 switch (CONVERT_STYLE) {
  case "imagemagick":
   if (!strpos($convert_exec, "convert"))
     DIE("generate_thumbnail :: convert could not be found on your system");
   break;
  case "unix":
   if (!strpos($cjpeg_exec, "cjpeg"))
     DIE("generate_thumbnail :: cjpeg could not be found on your system");
   if (!strpos($djpeg_exec, "djpeg"))
     DIE("generate_thumbnail :: djpeg could not be found on your system");
   if (!strpos($pnmscale_exec, "pnmscale"))
     DIE("generate_thumbnail :: pnmscale could not be found on your system");
   break;
 } // end checking CONVERT_STYLE

 // check to see if a file is provided
 if (!file_exists ($image))
    die ("generate_thumbnail :: file doesn't exist!!");

 // get temporary file name...
 $temporary_file = tempnam ("/tmp", "tn") . ".jpg";

 // convert to temporary file name
 switch (CONVERT_STYLE) {
  case "unix":
   $execute_command =
      exec ("$djpeg_exec \"$image\" | ".
            "$pnmscale_exec -height $imgsize | ".
            "$cjpeg_exec -outfile \"".$temporary_file."\"");
   break;
  case "imagemagick":
  default:
   $execute_command =
      exec ("$convert_exec -geometry ".
            $imgsize."x".$imgsize." \"".$image."\" \"".$temporary_file."\"");
  break;
 } // end checking CONVERT_STYLE

 if (file_exists ($temporary_file)) {
   // read in temporary data
   $thumb_handle   = fopen ($temporary_file, "r");
   $thumbnail_data = fread ($thumb_handle, filesize ($temporary_file));
   fclose ($thumb_handle);

   // remove the temporary thumbnail
   unlink ($temporary_file);
 } else {
   echo "[thumbnail creation failed, execute command = $execute_command] ";
 } // if the file exists

 return $thumbnail_data;
} // end function generate_thumbnail

// EPS documents have different thumbnail generation requirements
function generate_eps_thumbnail ($image, $isLarge = false) {
 global $convert_exec;

 // determine size
 if ($isLarge) { $imgsize = 240; }
  else         { $imgsize = 120; }

 // ImageMagick convert is required to handle EPS documents
 if (CONVERT_STYLE != "imagemagick")
   DIE("generate_eps_thumbnail :: ImageMagick convert required to handle EPS documents");

 // check for existance of proper executable
 if (!strpos($convert_exec, "convert"))
   DIE("generate_eps_thumbnail :: convert could not be found on your system");

 // check to see if a file is provided
 if (!file_exists ($image))
    die ("generate_eps_thumbnail :: file doesn't exist!!");

 // get temporary file name...
 $temporary_file = tempnam ("/tmp", "tn") . ".jpg";
 
 // convert to temporary file name
 // NOTES: convert handles multi-part images differently
 //        on different versions / compilations
 //        appending "[0]" to original image file name
 //        forces output of only one, and in all of my
 //        documents, the right one.
 //        might be a better way to deal with this?
 $execute_command =
    exec ("$convert_exec -geometry ".
           $imgsize."x".$imgsize." \"".$image."[0]\" \"".$temporary_file."\"");

 if (file_exists ($temporary_file)) {
   // read in temporary data
   $thumb_handle   = fopen ($temporary_file, "r");
   $thumbnail_data = fread ($thumb_handle, filesize ($temporary_file));
   fclose ($thumb_handle); 
    
   // remove the temporary thumbnail
   unlink ($temporary_file);  
 } else {
   echo "[thumbnail creation failed] ";
 } // if the file exists
 
 return $thumbnail_data;
} // end function generate_eps_thumbnail

} // end if not defined

?>
