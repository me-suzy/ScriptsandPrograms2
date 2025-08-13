<?php
 // file: get_image.php
 // desc: retrieves image for specified ID # in database
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include "config.inc";

openDatabase ();

 // break apart parameters
 $params = explode ("/", $GLOBALS[PATH_INFO]);

 // assume no encoding
 $enc = "";

 //echo "params(1) = $params[1]<BR>\n";

 for ($i=0;$i<count($params);$i++) {
   if (strpos ($params[$i], "=")) {
     $this_param = explode ("=", $params[$i]);
     switch ($this_param[0]) {
      case "enc" :   $enc  = $this_param[1]; break;
      case "id"  :   $id   = $this_param[1]; break;
      case "mime":   $mime = $this_param[1]; break;
     } // end of case for param
   } // end if checking for =
 } // end for loop

 $this_mime = ( (strlen($mime) > 3) ? $mime : "file" );

 $result = $sql->db_query (DB_NAME,
   "SELECT * FROM images WHERE id='$id'");
 if ($sql->num_rows($result)<1)
   die ("get_image :: id not retrievable");
 $r = $sql->fetch_array ($result);

 // check encoding...
 switch ($enc) {
  case "binhex": // if binhex encoding is neccesary...
   $content = "";
   switch ($r["imagetype"]) {
    case "GIF":            $this_type = "    ";     break;
    case "TIFF":           $this_type = "TIFF";    break;
    case "JPEG": default:  $this_type = "JFIF";    break;
   } // end of type switch
   Header ("Content-type: application/mac-binhex40");
   Header ("Pragma: no-cache");
   $command = $binhex_exec." -r -c 8BIM -t $this_type \"$r[fullfilename]\"";
   echo `$command`;
   break; // end action binhex

  case "zip": // zip encoding...
   Header ("Content-type: application/x-zip-compressed");
   Header ("Pragma: no-cache");
   $command = $zip_exec." -j - \"$r[fullfilename]\" | cat";
   echo `$command`;
   break;

  default: // default action is to send plain
   $content = "";
   switch ($r["imagetype"]) {
    case "GIF":            $content = "$this_mime/gif";     break;
    case "TIFF":           $content = "$this_mime/tiff";    break;
    case "JPEG": default:  $content = "$this_mime/jpeg";    break;
   } // end of type switch

   Header ("Content-type: $content");
   Header ("Pragma: no-cache");
   readfile ($r["fullfilename"]);
   break;
 } // end of check encoding switch

 closeDatabase ();
?>
