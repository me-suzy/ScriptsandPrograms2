<?php
  include 'settings.php';
//database conection
$conection = @mysql_connect($hostname, $username, $password);
if (!$conection) {
  echo( "<P>Unable to connect to the " .
        "database server at this time.</P>" );
  exit();
}



if (! @mysql_select_db($databasename) ) {
  echo( "<P>Unable to locate the '$databasename' " .
        "database at this time.</P>" );
  exit();
}



?>
