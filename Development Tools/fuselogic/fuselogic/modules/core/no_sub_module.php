<?php
$FL_ERROR_MESSAGE = 'Module <b>'.$FuseLogic->module.'</b> does not have sub module : <b>"'.$FuseLogic->getSubModule().'"</b>';
if($FuseLogic->FILE != '') $FL_ERROR_MESSAGE .= '<br>FILE = '.$FuseLogic->FILE.' ';
if($FuseLogic->LINE != '') $FL_ERROR_MESSAGE .= '<br>LINE = '.$FuseLogic->LINE.'<br>';
$FuseLogic->setErrorMessage($FL_ERROR_MESSAGE);			                
require(dirname(__FILE__).'/404.php');
?>												
