<?php
 // file: get_thumbnail.php
 // desc: displays thumbnail for specified ID # in database
 // code: jeff b (jeff@univrel.pr.uconn.edu)
 // lic : GPL, v2

include ("config.inc");

openDatabase ();

 $result = $sql->db_query (DB_NAME,
                           "SELECT * FROM images WHERE id='$id'");
 if ($sql->num_rows($result)<1)
   die ("get_thumbnail :: id not retrievable");
 $r = $sql->fetch_array ($result);

 Header ("Content-type: image/jpeg");
 Header ("Pragma: no-cache");
 if ($large==1) { echo $r[largethumbnail]; }
  else          { echo $r[thumbnail];      }

 closeDatabase ();
?>
