<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
if($_COOKIE[adID]) {
	setcookie("adID","",0,"/");
	header("Location: ./");
	die;
}else{
	header("Location: ./login.php");
	die;
}
?>