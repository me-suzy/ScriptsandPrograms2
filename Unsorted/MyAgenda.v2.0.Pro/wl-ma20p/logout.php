<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
if( $_COOKIE[asID] && $_COOKIE[auID] ) {
	setcookie("asID","",0,"/");
	setcookie("auID","",0,"/");
	header("Location: index.php");
	die;
}else{
	header("Location: register.php");
	die;
}
?>