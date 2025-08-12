<?php

	/*=====================================================================
    // $Id: leads4web_upgrade.php,v 1.1 2005/07/05 10:45:02 carsten Exp $
    // copyright evandor media Gmbh 2005
    //=====================================================================*/

    include ("upgrade.php");    

    list ($name, $versionFromIniFile) = getInstalledApplication("../");

	if (isset($_REQUEST['submit'])) {
		
		echo "<center>";
		echo "<table border=1><tr><td>\n";
		
		//executeFile ("create-mysql.sql",           "Creating tables");
		//executeFile ("basic_insert_mysql.sql",     "Inserting Data");
		//executeFile ("alter_gacl_table_types.sql", "adjusting GACL");
		//executeFile ("mandator_mysql.sql",         "Adding Demo Mandator");
		include     ("upgrade/".$version."_to_".$versionFromIniFile."/gacl.update.php");
		include     ("upgrade/".$version."_to_".$versionFromIniFile."/config.php");
		executeFile ("upgrade/".$version."_to_".$versionFromIniFile."/upgrade.sql", "Upgrading Database");
		
	    ?>	
		
		</td></tr>
		<tr><td>
		If there is no error shown above, the installation was successful.<br><br>
		<br><br>
		<b><font color='red'>Important:</font>
		Adjust Version in Config File (set $version to "<?=$versionFromIniFile?>")
		<br><br>
		</b>
		After that you can
		continue <a href='../index.php'>here</a>
		</td></tr>
		</table>
		
		<?php
		die();	
	}		

    include ("upgradeHTML.php");	
?>