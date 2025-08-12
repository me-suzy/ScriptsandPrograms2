<?php

session_destroy();

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");


?>
