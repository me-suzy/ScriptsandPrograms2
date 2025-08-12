<?php
// FishCart: an online catalog management / shopping system
// Copyright (C) 1997-2002  FishNet, Inc.

require('../functions.php');
require('../public.php');
require('../flags.php');

if ($databaseeng=='odbc') {
	$db=$dialect;
} else {
	$db=$databaseeng;
}
if ($db=='mysql') {
	parse_sql_file('sql_mysql.sql');
	if ($db_root) {
		if ($db_root=='true') {
			parse_sql_file('sql_mysql_users.sql');
		}
	}
} else if ($db=='pgsql') {
	parse_sql_file('sql_pgsql.sql');
} else if ($db=='odbc') {
	parse_sql_file('sql_solid.sql');
} else if ($db=='oracle') {
	parse_sql_file('sql_oracle.sql');
} else if ($db=='mssql') {
	parse_sql_file('sql_mssql.sql');
}

echo("SQL finished.");
?>
