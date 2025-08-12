<?php
//=======================
//=User defined settings=
//=======================

//Path to where the requested files should be(needs trailing slash);
$path="downloads/";

//=======================
//=The script, dont edit=
//=======================

//Just in case, function
if (!function_exists('mime_content_type')) {
   function mime_content_type($f) {
       $f = escapeshellarg($f);
       return trim( `file -bi $f` );
   }
}

//Get the requested file
$file = $_GET['file'];

//Stop the script if file selected is invalid
if( (  !$file ) or  ( !file_exists( $path . $file ) ) ) {
  die( "File wasnt set or it didnt exist" );
}

//What type of file is this.
$filetype=mime_content_type($path.$file);

//Set the filename
header("Content-Disposition: attachment; filename=\"$file\"");

//Set the content type
header('Content-type: '.$filetype);

//Read the file into the browser.
readfile( $path . $file );

?>