<?php
session_start();
$_SESSION['Uname'] = "";
$_SESSION['lp'] = "";
header("Location: login.php");
?>