<?php

$Q="INSERT INTO clients SET user_id='$_SESSION[id]',clientDesc='".addslashes($_REQUEST['newClient'])."',dateAdded=NOW()";
mysql_query($Q);

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");

?>
