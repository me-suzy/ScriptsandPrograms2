<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

	if(!$db = @mysql_connect("$dbhost", "$dbuser", "$dbpass"))
	die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check $dbhost, $dbuser, and $dbpass in config.php.');

	if(!@mysql_select_db("$dbname",$db))
	die("<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>$dbname</b> on your MySQL server.");

if (isset($dj)) {
$dj = NULL;
}

$query="SELECT * FROM currentdj";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
        $did = $row['dj'];
        $aliasa = $row['alias1'];
		$aliasb = $row['alias2'];
		$aliasc = $row['alias3'];
		$daim = $row['aim'];
		$dicq = $row['icq'];
		if ((isset($aliasa)) && (stristr($servertitle, $aliasa) !== FALSE)) {
		$setby = "Alias1";
		$dj = $did;
		}
		if ((isset($aliasb)) && (stristr($servertitle, $aliasb) !== FALSE)) {
		$setby = "Alias2";
		$dj = $did;
		}
		if ((isset($aliasc)) && (stristr($servertitle, $aliasc) !== FALSE)) {
		$setby = "Alias3";
		$dj = $did;
		} 
		if ((isset($daim) && $daim) && (stristr($aim, $daim) !== FALSE)) {
		$setby = "Server AIM Entry";
		$dj = $did;
		}
		if ((isset($dicq) && $dicq) && ($icq == $dicq)) {
		$setby = "Server ICQ Entry";
		$dj = $did;
		} 
    }
?>