<?php
#############################################################################
# myAgenda v1.1																#
# =============																#
# Copyright (C) 2002  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#																			#
# This program is free software. You can redistribute it and/or modify		#
# it under the terms of the GNU General Public License as published by 		#
# the Free Software Foundation; either version 2 of the License.       		#
#############################################################################
include("files/functions.php");

if ($HTTP_POST_VARS[username] && $HTTP_POST_VARS[password])
{

	$strSQL = mysql_query("Select uid From ".$myAgenda_tbl_users." Where username = '".Trim($HTTP_POST_VARS[username])."' And password = '".Trim($HTTP_POST_VARS[password])."'") or die (mysql_error());
	if (!mysql_num_rows($strSQL)){

		header("Location: login.php?location=$location&EI=1");
		exit;
	}
	$row = mysql_fetch_array($strSQL);

	$asID = create_sid();
	$auID = $row[uid];
	$la = time();

	$strSQL = mysql_query("Update ".$myAgenda_tbl_users." Set sid = '".$asID."', lastaccess = '".$la."' Where uid = '".$auID."'") or die(mysql_error());

	setcookie("asID",$asID,0,"/");
	setcookie("auID",$auID,0,"/");

	if (!empty($location)) {
		header("Location:$location");
	}else{
		header("Location: ./");
	}
	exit;

}elseif ($HTTP_COOKIE_VARS[asID] && $HTTP_COOKIE_VARS[auID])
{

	$strSQL = mysql_query("Select lastaccess From ".$myAgenda_tbl_users." Where sid = '".$HTTP_COOKIE_VARS[asID]."'") or die(mysql_error());

	if (!mysql_num_rows($strSQL))
	{
		header("Location: login.php?location=$location&EI=2");
		exit;
	}
	
	$row = mysql_fetch_array($strSQL);
	
	if ( ($row[lastaccess]+$TimeOut) < ($TimeOffSet) )
	{
		header("Location: login.php?location=$location&EI=2");
		exit;
	}else{

		$la = $TimeOffSet;
		$strSQL = mysql_query("Update ".$myAgenda_tbl_users." Set lastaccess = '".$la."' Where uid = '".$HTTP_COOKIE_VARS[auID]."'") or die(mysql_error());
	}

}else{
	$location = getenv('SCRIPT_NAME')."?".getenv('QUERY_STRING');
	header("Location: ".$myAgenda_url."/login.php?location=$location");
	exit;

}
?>