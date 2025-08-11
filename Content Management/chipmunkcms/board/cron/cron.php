<?php
include '../connect.php';
$deletelogs="Delete from b_rps where accept='1'";
mysql_query($deletelogs) or die("Could not get logs");
?>







