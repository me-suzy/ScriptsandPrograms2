<?php
include('../includes/db_connect.php');
if(isset($HTTP_GET_VARS['logoff']))
{
	session_unset();
	session_destroy();
	header("Location: $CONST_LINK_ROOT/admin/index.php");
	exit;
}
//if (!isset($HTTP_POST_VARS['GToiYP884'])) exit;
if ($HTTP_POST_VARS['txtPassword'] != $CONST_ADMIN_PASSWORD) {
    echo "<table width=\"100%\"><tr><td align=\"center\">".
         "<span style=\"color: red; font-weight: bold;\">Incorrect Password!</span><br>Please, Try again!<br><a href=\"#\" onclick=\"history.back()\">Back</a>".
         "</td></tr></table>";
    exit;
}    
$password =$HTTP_POST_VARS['txtPassword'];
session_start();
$_SESSION['Sess_Password']=$password;
header("Location: $CONST_LINK_ROOT/admin/index.php?PHPSESSID=".session_id());
?>
