<?php
session_start();
include "./config.php";
foreach ($_POST as $var => $val) {
	$_SESSION["s_data"][$var]=$val;
}
header("Location: " . $home_url . "index.php?" . SID);
?>