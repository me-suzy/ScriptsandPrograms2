<?php

	/*=====================================================================
    // $Id: ticketmanager_install.php,v 1.1 2005/07/18 13:10:19 carsten Exp $
    // copyright evandor media Gmbh 2005
    //=====================================================================*/

    include ("install.php");
    	
	if (isset($_REQUEST['submit']) && !$info_only) {
		
        echo "<center>";
		echo "<table border=1><tr><td>\n";
		
		executeFile ("create-mysql.sql",         "Creating tables");
		executeFile ("basic_insert_mysql.sql",   "Inserting Data");
		executeFile ("adjust_ticketmanager.sql", "Adjusting Database");
		//if (ALLOW_GUEST_USER) {
		//    executeFile ("add_guest_user.sql", "Adding guest user");
		//}
		
		echo "</td></tr>";
		echo "<tr><td>";		
		echo "If there is no error shown above, the installation was successful.<br>";
		echo "To login as superuser, go <a href='../".LOGIN_PAGE."'>here</a><br>";
        //if (ALLOW_GUEST_USER) {
    	//	echo "To login as guest without password, go <a href='../guest.php'>here</a><br>";
		//}
		echo "</td></tr>\n";
		echo "</table>";
		die();	

	}		

    include ("installHTML.php");	
?>