<?php

	/*=====================================================================
	// $Id: change_compress.php,v 1.4 2005/04/03 06:30:09 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

	include ("inc/pre_include_standard.inc.php");

	// --- GET / POST -----------------------------------------------
	$aktuell = $_SESSION ["aktuell"];

	if ($aktuell == "checked") {
		mysql_query ("UPDATE user_details SET ".TABLE_PREFIX."compression='false' WHERE user_id='$user_id'");
		logDBError (__FILE__, __LINE__, mysql_error());
	}
	else
		mysql_query ("UPDATE user_details SET ".TABLE_PREFIX."compression='true' WHERE user_id='$user_id'");
	logDBError (__FILE__, __LINE__, mysql_error());
?>
<html>
<head>
	<script language=javascript>
		document.location.href='configleiste.php';
	</script>
</head>
<body>
</body>
</html>