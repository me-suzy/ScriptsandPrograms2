<?

/*
 * $Id: session.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$sdbh = "";
$expire =  900;
function sess_open($save_path, $session_name){
global $dbhost, $dbuser, $dbpasswd, $sdbh;
	if (! $sdbh = mysql_pconnect($dbhost, $dbuser, $dbpasswd)){
		echo mysql_error();
		exit;
	}
	return true;
}
function sess_close(){
	return true;
}
function sess_read($key){
global $sdbh, $dbname, $tb_sessions;
	$query = "
		select
			data
		from
			$tb_sessions
		where
			id = '$key'
		and
			expire > UNIX_TIMESTAMP()
	";
	$result = sql_query($query);
	if($record = mysql_fetch_row($result))
		return $record[0];
	else
		return false;
}
function sess_write($key, $val){
global $sdbh, $dbname, $tb_sessions, $expire;
	$value = addslashes($val);
	$query = "
		replace into 
			$tb_sessions
		values (
			'$key',
			'$value',
			UNIX_TIMESTAMP() + $expire
		)
	";
	$result = sql_query($query);
	echo mysql_error();
	return $result;
}
function sess_destroy($key){
global $sdbh, $dbname, $tb_sessions;
	$query = "
		delete from
			$tb_sessions
		where
			id = '$key'
	";
	$result = sql_query($query);
	return $result;
}
function sess_gc($maxlifetime){
global $sdbh, $dbname, $tb_sessions;
	$query = "
		delete from
			$tb_sessions
		where
			expire < UNIX_TIMESTAMP()
	";
	$result = sql_query($query);
	return mysql_affected_rows($sdbh);
}
session_set_save_handler("sess_open","sess_close","sess_read","sess_write","sess_destroy","sess_gc");
session_start();
$sn = session_name();
$sid = session_id();

/*
 * $Id: session.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>