<html>
<title>Excel -> MySQL Sample - Creating database</title>
<body>
<?php

require 'mysql.inc';

$mysql_link = @mysql_connect($mysql_server,$mysql_username,$mysql_password)
	or die('Could not connect to a database');

$db_exists = @mysql_select_db('exceldb',$mysql_link);
if( !$db_exists ) {
	if( !@mysql_create_db('exceldb',$mysql_link) ) {
		die('Could not create "exceldb" database');
	}
	if( !@mysql_select_db('exceldb',$mysql_link) ) {
		die('Could not select "exceldb" database');
	}
}

mysql_query("create table sheet (id integer unsigned not null, name text not null)",$mysql_link);
mysql_query("create table cell (sheet integer unsigned not null,row integer unsigned not null,col integer unsigned not null,data text)",$mysql_link);

@mysql_close($mysql_link);
?>
</body>
</html>