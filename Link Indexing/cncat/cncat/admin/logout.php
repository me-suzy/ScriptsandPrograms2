<?
session_register("cncatsid");
$_SESSION["cncatsid"]="thisiswrongstring";
session_destroy();

header("Location: ../");
?>