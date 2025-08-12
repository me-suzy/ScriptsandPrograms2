<?php

	/*=====================================================================
    // $Id: leads4web_install.php,v 1.5 2005/06/06 13:55:14 carsten Exp $
    // copyright evandor media Gmbh 2005
    //=====================================================================*/

    include ("install.php");
    
	
	if (isset($_REQUEST['submit']) && !$info_only) {
		
		echo "<center>";
		echo "<table border=1><tr><td>\n";
		
		executeFile ("create-mysql.sql",           "Creating tables");
		executeFile ("basic_insert_mysql.sql",     "Inserting Data");
		executeFile ("alter_gacl_table_types.sql", "adjusting GACL");
		executeFile ("mandator_mysql.sql",         "Adding Demo Mandator");
		
		
		echo "</td></tr>";
		echo "<tr><td>";		
		echo "If there is no error shown above, the installation was successful.";
		echo "Continue <a href='../index.php'>here</a>";
		echo "</td></tr>\n";
		echo "</table>";
		die();	
	}		

    include ("installHTML.php");	
?>