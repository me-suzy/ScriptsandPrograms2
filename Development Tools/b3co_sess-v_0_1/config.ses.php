<?
/*
 config.ses.php
 Copyright (C) 2003 
 Alberto Alcocer Medina-Mora
 root@b3co.com

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

/**********start configuration*************/

/*
	database settings
*/

$_db_user = "user";
$_db_pass = "password";
$_db_url  = "url";
$_db_name = "database";

/*
	if you dont have any table with the following names
	you can leave next two variables as are
*/

$_db_table= "session_global";		// name of the table containing all user defined variables
$_db_table_config = "session_master";	// name of the table that will contain session data

/*
	if _session_timeout is 0, then session will expire until browser is closed
	otherwise session will expire un _session_timeout minutes
*/

$_session_timeout = 0;

/*
	this variable is used to generate a more secure sid, because this
	string is only known by you
*/
$secure_key = "mysecurekey";

/**********end configuration*************/

/*
	conect to db
*/
$_db = mysql_connect($_db_url, $_db_user, $_db_pass)
	or die("error de coneccion a la base de datos");
mysql_select_db($_db_name) or die(mysql_error());

/*
	session functions
*/

function new_session(){
	global $_db_table,$_db_table_config,$_session_timeout;
	list($m,$s) = explode(" ",microtime());
	$sess = md5(rand(0,1000).substr($m,2).$secure_key);
	if($_session_timeout != 0){
		setcookie('sess',$sess,time()+($_session_timeout*60));
	}else{
		setcookie('sess',$sess);
	}
	$sql = "insert into $_db_table_config(sid,start,ip) values('$sess',NOW(),'".$_SERVER['REMOTE_ADDR']."')";
	$res = query($sql);
	return $sess;
}

function update_session(){
	global $_db_table_config;
	$sess = $_COOKIE['sess'];
	$sql = "select logout from $_db_table_config where sid = '$sess'";
	$res = query($sql);
	$row = mysql_fetch_array($res);
	if($row[0]!=1){
		$sql = "update $_db_table_config set last = NOW() where sid = '$sess'";
		query($sql);
		if(mysql_affected_rows() < 0){
			return new_session();
		}else{
			return $sess;
		}
	}else{
		return new_session();
	}
}

function set_session_var($name,$value){
	global $_db_table,$sess,$_session;
	$keys = array_keys($_session);
	if(index_of($name,$keys) == -1){
		$sql = "insert into $_db_table values('$sess','$name','$value')";
	}else{
		$sql = "update $_db_table set value = '$value' where sid = '$sess' and variable = '$name'";
	}
	query($sql);
	// this line is to avoid another query to db
	$_session[$name]=$value;
	return mysql_affected_rows();
}

function get_session_vars(){
	global $_db,$_db_table,$sess;
	$_session = Array();
	$sql = "select variable, value from $_db_table where sid = '$sess'";
	$res = query($sql);
	while($row = mysql_fetch_array($res)){
		$_session[$row['variable']]=$row['value'];		
	}
	return $_session;
}

function session_flush(){
	global $sess,$_db_table,$_db_table_config;
	session_kill();
	$sql ="delete from $_db_table where sid ='$sess';";
	query($sql);
	$sql = "delete from $_db_table_config where sid = '$sess'";
	query($sql);
	return (mysql_error()!=""?false:true);
}

function session_kill(){
	global $sess,$_db_table_config;
	$sql = "update $_db_table_config set logout=1";
	query($sql);
	setcookie('sess',0,time()-500);
}

function get_session_length(){
	global $sess,$_db_table_config;
	$sql = "select NOW()-start from $_db_table_config where sid = '$sess'";
	$res = query($sql);
	$row = mysql_fetch_array($res);	
	return $row[0];
}


/*
	miscelaneous functions
*/

function query($sql){
	global $_db;
	$res = mysql_query($sql,$_db);
	if(mysql_error()!=""){
		echo "<hr><br>".mysql_error()."<br>".$sql."<hr>";
	}
	return $res;
}

function getmicrotime(){ 
	list($usec, $sec) =  explode(" ", microtime()); 
	return ((double)$usec + (double) $sec); 
}

function index_of($value,$array){
	$i = 0;
	while($array[$i] != $value && $i < count($array)){
		$i++;
	}
	return $array[$i]==$value?$i:-1;
}


?>