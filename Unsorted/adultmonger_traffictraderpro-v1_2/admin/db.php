<?
function db_open($hostname,$username,$password,$database) { $db = mysql_connect($hostname, $username, $password); if(!$db) { db_error("could not connect to the database");
return 0; } if(!@mysql_select_db($database,$db)) { db_error("could not select $database database"); return 0; } $GLOBALS[dbh] = $db; }
function db_close() { @mysql_close($GLOBALS[dbh]); return 1; } function db_affected() {return @mysql_affected_rows($GLOBALS[dbh]); } function db_last() { return @mysql_insert_id($GLOBALS[dbh]);}
function db_query($query) { if($GLOBALS[output] == 1) { print($query."<br>\n"); return 0; } $results = @mysql_query($query,$GLOBALS[dbh]); $GLOBALS[queries]++; if(!$results) { db_error(mysql_error()); return 0; }return $results;}
function db_error($message) {if($GLOBALS[debug] == 1) {print "db error: $message<br>";}} function db_rows($results,$type = "row") {if($type == "row") {return @mysql_fetch_row($results);}
if($type == "object") {return @mysql_fetch_object($results);} if($type == "assoc") {return @mysql_fetch_assoc($results);} } function db_select($database) {$results = @mysql_select_db($database,$GLOBALS[dbh]);if(!$results) {db_error("cannot select database $database, ".mysql_error());return 0;}}
function db_numrows($results) {return @mysql_num_rows($results);} function db_free($results) {@mysql_free_result($results);}

db_open($config[db_hostname],$config[db_username],$config[db_password],$config[db_database]);
?>
