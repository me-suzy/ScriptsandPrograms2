<?php
$_REQUEST['id'] = intval($_REQUEST['id']);

$Q="DELETE FROM users WHERE id='$_REQUEST[id]'";
mysql_query($Q);

$Q="DELETE FROM notes WHERE user_id='$_REQUEST[id]'";
mysql_query($Q);

$Q="DELETE FROM clients WHERE user_id='$_REQUEST[id]'";
mysql_query($Q);

$Q="DELETE FROM tasks WHERE user_id='$_REQUEST[id]'";
mysql_query($Q);

$Q="DELETE FROM jobs WHERE user_id='$_REQUEST[id]'";
mysql_query($Q);

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");

?>
