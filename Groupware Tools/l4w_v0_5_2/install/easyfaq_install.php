<?php

	/*=====================================================================
    // $Id: easyfaq_install.php,v 1.4 2005/04/09 06:07:25 carsten Exp $
    // copyright evandor media Gmbh 2005
    //=====================================================================*/

    include ("install.php");
    
	
	if (isset($_REQUEST['submit']) && !$info_only) {
		
        echo "<center>";
		echo "<table border=1><tr><td>\n";
		
		executeFile ("create-mysql.sql",       "Creating tables");
		executeFile ("basic_insert_mysql.sql", "Inserting Data");
		executeFile ("adjust_easyfaq.sql",     "Adjusting Database", "~|~");
		if (ALLOW_GUEST_USER) {
		    executeFile ("add_guest_user.sql",     "Adding guest user");
		}
		
		echo "</td></tr>";
		echo "<tr><td>";		
		echo "If there is no error shown above, the installation was successful.<br>";
		echo "Continue <a href='../main.php?login_given=superadmin'>here</a>";
		echo "</td></tr>\n";
		echo "</table>";
		die();	

	}		

    include ("installHTML.php");	
?>