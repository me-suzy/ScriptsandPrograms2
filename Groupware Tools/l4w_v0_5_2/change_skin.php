<?php

    /*=====================================================================
	// $Id: change_skin.php,v 1.5 2005/04/03 06:30:09 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

	include ("config/config.inc.php");
	include ("connect_database.php");
	include ("inc/functions.inc.php");
	
    @session_name (SESSION_NAME);
	session_start();

	$user_id  = $_SESSION ["user_id"];
	//$language = var_include_int ("language", "SESSION");
	$login	  = $_SESSION ["login"];
	$passwort = $_SESSION ["passwort"];
	//$group	  = var_include_int ("group",	 "SESSION");
	$skin	  = $_REQUEST ["skin"];

	//security_check();
	if ($skin == "") $skin = 1;

	mysql_query ("UPDATE ".TABLE_PREFIX."user_details SET skin='$skin' WHERE user_id='$user_id'");
    logDBError (__FILE__, __LINE__, mysql_error());

?>
<script language="javascript">
	document.location.href = "frames.php";
</script>