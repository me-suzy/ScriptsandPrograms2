<?php


if (isset($_POST["a"])){


$db->compress(array($_POST["backupdb"]), true); //compress to a .gz
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Backed-Up the database (<b>".$_POST["backupdb"]."</b>) sucessfuly!<br />
   Right Click [ <a href=\"".$_POST["backupdb"].".gz\">".$_POST["backupdb"]."</a> ] and click 'Save Target As' to download.<br />If this does not work, log on to your server via FTP and download it from there.</font></div>\r\n");
}; //end isset

echo ("<form method=\"post\" action=\"\">");
table_header("Backup Database");
  echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr>
     <td  colspan=\"1\" width=\"100%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">Please select the database to be backed up.<br /><br /><select name=\"backupdb\">");

     $files = $db->readDir("db", array("config.php", "style.css", ".htaccess", "index.html"));
     $tounset = count($files) -1;
     unset($files["$tounset"]);
     foreach ($files as $file){
     $dbs = $db->getFileSize("db/$file", true);
      echo ("<option value=\"db/$file\">db/$file - $dbs</option>\r\n");
     }; //end foreach

     echo ("</select> <input type=\"submit\" name=\"a\" value=\"BackUP Selected\"></font></td>
  </tr>
</table>");
table_footer();

echo ("</form>");

?>
