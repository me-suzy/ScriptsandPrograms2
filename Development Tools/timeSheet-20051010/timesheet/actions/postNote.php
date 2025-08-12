<?php

$_REQUEST['job_id'] = intval($_REQUEST['job_id']);

$Q="INSERT INTO notes SET user_id='$_SESSION[id]', job_id='$_REQUEST[job_id]', notes='".addslashes($_REQUEST['note'])."',datePosted=NOW()";
mysql_query($Q);

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");


?>
