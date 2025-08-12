<?php

if ($_SESSION['showNotes']  == $_GET['job_id']) 
	unset($_SESSION['showNotes']);
else
	$_SESSION['showNotes'] = $_GET['job_id'];

if ($_REQUEST['REF'])
{
    header("Location: ".base64_decode($_REQUEST[REF]));
}
else header("Location: $_SERVER[HTTP_REFERER]");

?>
