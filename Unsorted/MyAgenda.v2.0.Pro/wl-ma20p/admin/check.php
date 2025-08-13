<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("../includes/config.php");
include("../includes/functions.php");

if(empty($_COOKIE[adID])) {
	header("Location: login.php");
	die;
}
?>
