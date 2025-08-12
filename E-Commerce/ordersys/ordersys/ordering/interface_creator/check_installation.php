
<?php
if ( !table_exists($table_list_name) ){ // if the $table_list_name table doesn't exist it means that DaDaBIK has not been installed
	echo "<p><b>[03] Error:</b> it appears that the interface creator is being used for the first time with this MySQL database. When the interface creator is set up, it creates new tables in the database named with the dadabik_prefix. Such tables are not there in the database. It is also possible that they may have been accidentally deleted. Anyway, please go to the <a href=\"install.php\">installation</a> page.";
	exit;
} // end
?>