<?php

	/*=====================================================================
    // $Id: connect_database.php,v 1.3 2005/04/09 06:07:25 carsten Exp $
    // copyright evandor media Gmbh 2003
    //=====================================================================*/

	$db = mysql_connect($db_host, $db_user, $db_passwd);
	mysql_select_db($db_name, $db);
		

?>