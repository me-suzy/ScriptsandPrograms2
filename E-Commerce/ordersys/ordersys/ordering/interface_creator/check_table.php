
<?php
// installed and allowed tables

if ($enable_admin_authentication === 1 and $_SESSION['logged_user_infos_ar']['user_type_user'] === $users_table_user_type_administrator_value)
{$tables_names_ar = build_tables_names_array(0,1,1);}
else 
{$tables_names_ar = build_tables_names_array();}

if (count($tables_names_ar) == 0){ // no tables installed and allowed
	echo "<p><b>[04] Error:</b> cannot run the interface creator, probably because none of the MySQL database tables have been <i>installed</i> for use with the interface creator; go to the <a href=\"admin.php\">administration home page</a> and <i>install</i> tables.</p>";
	exit;
} // end
else{
	if (!isset($_GET["table_name"])){
		$table_name = $tables_names_ar[0];
	} // end if
	else{
		$table_name = $_GET["table_name"];
		if ( !in_array($table_name, $tables_names_ar) ) { // someone try to manage a not-allowed table by changing the url
			echo "<p><b>[05] Error:</b> you are attemping to manage a restricted  table. Please go to the <a href=\"admin.php\">administration home page</a> to let the table be used. It is also possible that the table is a users table, as defined in config.php, to which only administrators have access.</p>";
			exit;
		}
		/*
		if (!table_allowed($conn, $table_name)){ // someone try to manage a not-allowed table by changing the url
			exit;
		} // end if
		*/
	} // end else
	$enabled_features_ar = build_enabled_features_ar($table_name);

	$enable_insert = $enabled_features_ar["insert"];
	$enable_edit = $enabled_features_ar["edit"];
	$enable_delete = $enabled_features_ar["delete"];
	$enable_details = $enabled_features_ar["details"];

	$table_internal_name = $prefix_internal_table.$table_name;
} // end else
?>