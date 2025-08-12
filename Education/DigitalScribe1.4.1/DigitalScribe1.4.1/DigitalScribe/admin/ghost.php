<?
require("checkpass2.php");
require("../teacher/checkpass.php");

$HTTP_SESSION_VARS['secure_ghost']=$HTTP_GET_VARS['ID'];   

if ($HTTP_GET_VARS[announce]==1) {
header("location: ../teacher/announceadmin.php");
}
else  {
header("location: ../teacher/teacheradmin.php");
}
?>