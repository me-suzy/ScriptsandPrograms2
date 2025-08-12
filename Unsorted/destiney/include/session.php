<?php

ini_set("session.use_trans_sid", 1);

$sdbh = "";

function sess_open($save_path, $session_name){
global $dbhost, $dbuser, $dbpasswd, $sdbh;
	if (! $sdbh = mysql_pconnect($dbhost, $dbuser, $dbpasswd)){
		echo mysql_error();
		exit();
	}
	return true;
}

function sess_close(){
	return true;
}

function sess_read($key){
global $sdbh, $dbname, $tb_sessions, $base_url;

	$sql = "
		select
			data
		from
			$tb_sessions
		where
			id = '$key'
		and
			expire > UNIX_TIMESTAMP()
	";
	$query = mysql_query($sql) or die(mysql_error());

	if(mysql_num_rows($query)){
		return mysql_result($query, 0, "data");
	}
	
	return false;
}

function sess_write($key, $val){
global $tb_sessions, $online_expire;
	
	$ip = $_SERVER["REMOTE_ADDR"];

	$value = addslashes($val);

	$sql = "
		replace into $tb_sessions (
				id,
				data,
				expire
		) values (
			'$key',
			'$value',
			UNIX_TIMESTAMP() + $online_expire
		)
	";
	return mysql_query($sql) or die(mysql_error());
}

function sess_destroy($key){
global $tb_sessions;
	$sql = "
		delete from
			$tb_sessions
		where
			id = '$key'
	";
	return mysql_query($sql) or die(mysql_error());
}

function sess_gc(){
global $tb_sessions;
	$sql = "
		delete from
			$tb_sessions
		where
			expire < UNIX_TIMESTAMP()
	";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_affected_rows();
}
session_set_save_handler("sess_open","sess_close","sess_read","sess_write","sess_destroy","sess_gc");
session_start();

?>