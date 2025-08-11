<?php
include 'conection.php';
import_request_variables("gP", "r_");

$gallshow = mysql_query("SELECT id, thumb, title, description FROM $prefix"."galleries LIMIT 10");

while ( $row = mysql_fetch_array($gallshow) ){
 echo ($row['title']."<br>");
 echo "<a href=gallery.php?gallery=$row[id]><img src='$row[thumb]'></a> <br>";
  echo ($row['description']."<br><br>");

  }


?>
