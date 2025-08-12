<?php
$mysql = mysql_connect($tinybb_mysql_host,$tinybb_mysql_user,$tinybb_mysql_password);
mysql_select_db($tinybb_mysql_db,$mysql);

if ($mysql == 0) {
  header("Location: error_mysql.php");
}
?>