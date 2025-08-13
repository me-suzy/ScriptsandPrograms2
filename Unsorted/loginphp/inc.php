<?php
ob_start();
//include the header
require("top.php");
//check if the session Uname is in use
if($_SESSION['Uname'] == '' || $_SESSION['lp'] == '')
{
header("Location: login.php");
exit;
}
