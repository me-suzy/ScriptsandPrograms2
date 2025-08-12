<?php

	/*=====================================================================
	// $Id: change_language.php,v 1.6 2005/04/03 06:30:09 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

	include ("config/config.inc.php");
	include ("connect_database.php");
	include ("inc/functions.inc.php");
	
    @session_name (SESSION_NAME);
	session_start();

	$user_id  = $_SESSION["user_id"];
	//$language = var_include_int ("language", "SESSION");
	//$login	  = var_include ("login",	 "SESSION");
	$login    = $_SESSION["login"];
	
	//$passwort = var_include ("passwort", "SESSION");
	$passwort = $_SESSION["passwort"];
	//$group	  = var_include_int ("group",	 "SESSION");
	//$sprache  = var_include_int ("sprache", "GET");

	//security_check();
	if ($sprache == "") $sprache = 1;
	$_SESSION['language'] = $sprache;

	$sql = "UPDATE ".TABLE_PREFIX."user_details SET lang='$sprache' WHERE user_id='$user_id'";

	mysql_query ($sql);
	logDBError (__FILE__, __LINE__, mysql_error());


?>
<script language="javascript">
	document.location.href = "frames.php";
</script>