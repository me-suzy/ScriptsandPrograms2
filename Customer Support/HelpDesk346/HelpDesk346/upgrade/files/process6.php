<?php
	// connect
	mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpass']);
	mysql_select_db($_SESSION['dbname']);
	 
	 /////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 // check for the blocking file name tables
	 $res = mysql_query("show tables");
	 while ( $row = mysql_fetch_row( $res ) )
	 	$arr[] = $row[0];
	 	
	 if (!in_array($_SESSION['prefix'] . '_blocked_fexts', $arr)) {
	 	$cmd = "create table " . $_SESSION['prefix'] . "_blocked_fexts (
	 		stringValue varchar(30) not null default ''
	 	)";
	 	mysql_query($cmd) or die(mysql_error());	
	 }
	 
	 if (!in_array($_SESSION['prefix'] . '_blocked_fnames', $arr)) {
	 	$cmd = "create table " . $_SESSION['prefix'] . "_blocked_fnames (
	 		stringValue varchar(30) not null default '',
	 		position int(1) not null default '0',
	 		id int not null primary key auto_increment
	 	)";
	 	mysql_query($cmd) or die(mysql_error());	
	 }
	 
	 if (!in_array($_SESSION['prefix'] . '_files', $arr)) {
	 	$cmd = "create table " . $_SESSION['prefix'] . "_files (
	 		id int not null primary key auto_increment,
	 		name varchar(255) not null default ''
	 	)";
	 	mysql_query($cmd) or die(mysql_error());
	 }
	 
	 $arr = array();
	 
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 // analyze the settings table
	 $res = mysql_query("show columns from " . $_SESSION['prefix'] . "_settings") or die(mysql_error());
	 while ( $row = mysql_fetch_assoc( $res ) )
	 	$arr[] = $row['Field'];
	 	
	 // HD_from
	 if (in_array('email_addr', $arr)) {
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings change email_addr HD_from varchar(50) not null default ''");
	 }
	 else if (!in_array('HD_from', $arr)) {
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add HD_from varchar(50) not null default ''");
	 }
	 
	 // hdemail_create
	 if (!in_array('hdemail_create', $arr))
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add hdemail_create int(1) not null default '1'");
	 
	 // hdemail_close
	 if (!in_array('hdemail_close', $arr))
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add hdemail_close int(1) not null default '1'");
	 
	 // ticketAccessModify
	 if (!in_array('ticketAccessModify', $arr))
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add ticketAccessModify int(1) not null default '0'");
	 
	 // show_kb
	if (!in_array('show_kb', $arr))
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add show_kb int(1) not null default '1'");
	 
	 // allow_enduser_reg
	 if (!in_array('allow_enduser_reg', $arr))
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add allow_enduser_reg int(1) not null default '1'");
	 
	 // max_file_size
	 if (!in_array('max_file_size', $arr)) {
	 	include_once "../includes/functions.php";
	 	$size = DetermineSize(ini_get('upload_max_filesize'));
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add max_file_size int not null default '$size'");
	 }
	 
	 // enable_file_blocking
	 if (!in_array('enable_file_blocking', $arr))
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add enable_file_blocking int(1) not null default '1'");
	 
	 // user_defined_priorities
	 if (!in_array('user_defined_priorities', $arr))
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add user_defined_priorities int(1) not null default '0'");
	 
	 // ticket_lookup
	 if (!in_array('ticket_lookup', $arr))
	 	mysql_query("alter table " . $_SESSION['prefix'] . "_settings add ticket_lookup int(1) not null default '1'");
?>