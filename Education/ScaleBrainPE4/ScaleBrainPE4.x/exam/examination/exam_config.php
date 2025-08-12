<?
@extract($_COOKIE);
@extract($_ENV);
@extract($_REQUEST);
@extract($_FILES);
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
@extract($_SESSION);

$apppath="/exampack/personal";
$basepath = "E:/Documents and Settings/pawan/Desktop/web";
$webpath="http://localhost/pawan";
$webpath1="http://www.localhost/pawan";

$DBHost = "localhost";
$DBUser = "pawan";
$DBPassword = "pawan";

$DBName = "test_personal";
$db=mysql_connect("$DBHost", "$DBUser", "$DBPassword");
$result = mysql_select_db ("$DBName");
?>