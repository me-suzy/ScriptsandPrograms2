<html>
<body>
<div align=center>


<?
require("mod.ftpdb.php");
$ftpdb = new ftpdb();
?>
<? echo $ftpdb->show_num(1); ?> up<br>
<? echo $ftpdb->show_num(2); ?> down<br>
<? echo $ftpdb->show_num(0); ?> unknown<br>
<? echo $ftpdb->show_num(); ?> total<br>
<br>
ftps: <font color=#008800><? echo $ftpdb->show_num(1); ?></font>/<font color=#cc0000><? echo $ftpdb->show_num(2); ?></font>/<font color=#888888><? echo $ftpdb->show_num(0); ?></font>


</div>
</body>
</html>
