<?
	include 'config.ses.php';
	
	$sql = "CREATE TABLE `$_db_table` (
		`sid` VARCHAR( 100 ) NOT NULL ,
		`variable` VARCHAR( 255 ) NOT NULL ,
		`value` VARCHAR( 255 ) NOT NULL ,
		INDEX ( `sid` ) 
	);";
	mysql_query($sql);
	if(mysql_error()!=""){
		die("An error ocurred creating table:<i>$_db_table_config</i><br>".mysql_error());
	}else{
		echo "<i>$_db_table_config</i> has been created<br>";
	}

	$sql = "CREATE TABLE `$_db_table_config` (
		`id` INT NOT NULL AUTO_INCREMENT ,
		`sid` VARCHAR( 255 ) NOT NULL ,
		`start` DATETIME NOT NULL ,
		`last` DATETIME NOT NULL ,
		`ip` VARCHAR( 20 ) NOT NULL ,
		`logout` TINYINT( 0 ) NOT NULL ,
		PRIMARY KEY ( `sid` ) ,
		UNIQUE (`id`)
	);";
	mysql_query($sql);
	if(mysql_error()!=""){
		die("An error ocurred creating table:<i>$_db_table</i><br>".mysql_error());
	}else{
		echo "<i>$_db_table</i> has been created<br>";
	}
	echo "<br><b>b3co</b> session handler is ready to start working!!!!!!";
	echo "<br>root@b3co.com";
?>