<html>
<body>
<div align=center>


<?
require("mod.ftpdb.php");
$ftpdb = new ftpdb();

if (!empty($host) && !empty($port) && !empty($user) && !empty($descr))
  $ftpdb->add_res($host, $port, $user, $pass, $descr);
else
  $ftpdb->add_form();
?>


</div>
</body>
</html>