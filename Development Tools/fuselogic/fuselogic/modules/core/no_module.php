<?php
$FL_ERROR_MESSAGE = '<b>There is no module "'.$FuseLogic->module.'"</b>.';
if($FuseLogic->FILE != '') $FL_ERROR_MESSAGE .= '<br>FILE = '.$FuseLogic->FILE.' ';
if($FuseLogic->LINE != '') $FL_ERROR_MESSAGE .= '<br>LINE = '.$FuseLogic->LINE.'<br>';
$FuseLogic->setErrorMessage($FL_ERROR_MESSAGE);			                
require(dirname(__FILE__).'/404.php');

?>
