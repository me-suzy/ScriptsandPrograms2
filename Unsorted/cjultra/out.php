<?php
// Cjultra v2.1

if (ini_get('register_globals') != 1) {
    $supers = array('_REQUEST','_ENV','_SERVER','_COOKIE','_GET','_POST');
    foreach ($supers as $__s) {
        if (is_array($$__s) == true) extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
}

ignore_user_abort(true);
srand((double)microtime()*1000000);
include("./common.php");
$linkid = db_connect();
if (!$linkid) error_message(sql_error());
//
$day = date("w");
$yday = date("w", time() - 86400);
$hour = date("G");
//
$link = addslashes($link);
$from = addslashes($from);;
//
if ($link) {
    linkadd($link);
}
//
if (!$from && !$to) {
    $from = "nocookie";
}
$from = addslashes($from);
dbadd($from);
//
if ($url) {
        if (((strlen($s) > 0) and (rand(0,100) < $s)) or ((strlen($s) == 0) and (rand(0,100) < 50)) or (($first) and (!$firstc or $first > $firstc)) or ($from == "nocookie")) {
        if ($from == "nocookie") setcookie("to", "first", time() + 864000);
		$firstcplus = $firstc + 1;
		setcookie("firstc", "$firstcplus", time() + 86400);
        $url = header_check($url);
        header("Location: $url");
        $query2 = "update day set zc$hour = zc$hour + 1 where z = '$from'";
        $result2 = mysql_query($query2);
        if(!$result2) error_message(sql_error());
        $query3 = "update trade set a13 = a13 + 1 where a1 = '$from'";
        $result3 = mysql_query($query3);
        if(!$result3) error_message(sql_error());
        mysql_close($linkid);
        exit;
        }
}
//
if (blacklisted($from) or blacklisted($REMOTE_ADDR)) exit;
//
if ($perm) {
	$perm = addslashes($perm);
    $query = "select * from trade where a1 = '$perm'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
    if (mysql_num_rows($result)) {
       $data = mysql_fetch_array($result);
       $d = $data["a1"];
       $u = $data["a2"];
       hit();
       exit;
    }
}


$query = "select * from trade where a19 > -100000 order by a19 desc";
$result = mysql_query($query);
if(!$result) error_message(sql_error());

while ($data = mysql_fetch_array($result)) {
      $d = $data["a1"];
      $u = $data["a2"];
      if (!$to) {
         $to = $d;
         setcookie("to", $to, time() + 86400);
         hit();
         exit;
      }
      else {
           $to_array = explode("|", $to);
           if (!(in_array($d, $to_array)) and $d != $from) {
              array_push($to_array, "$d");
              $to = implode("|", $to_array);
              setcookie("to", $to, time() + 86400);
              hit();
              exit;
           }
      }
}

    

$query = "select b5 from settings";
$result = mysql_query($query);
$data = mysql_fetch_array($result);
$e = $data["b5"];
dbadd("exout");
$query2 = "update day set zo$hour = zo$hour + 1 where z = 'exout'";
$result2 = mysql_query($query2);
if(!$result2) error_message(sql_error());
header("Location: $e");
mysql_close($linkid);
exit;
##################
function hit()
{
      global $d, $from, $u, $hour, $linkid;
      $query2 = "update trade set a12 = a12 + 1 where a1 = '$d'";
      $result2 = mysql_query($query2);

      dbadd($d);

      $query2 = "update day set zo$hour = zo$hour + 1 where z = '$d'";
      $result2 = mysql_query($query2);
      if(!$result2) error_message(sql_error());
      $u = header_check($u);
      header("Location: $u");
      $query2 = "update day set zc$hour = zc$hour + 1 where z = '$from'";
      $result2 = mysql_query($query2);
      if(!$result2) error_message(sql_error());
      $query2 = "update trade set a13 = a13 + 1 where a1 = '$from'";
      $result2 = mysql_query($query2);
      if(!$result2) error_message(sql_error());
      mysql_close($linkid);
      exit;
}

##################
function dbadd($d)
{
    $day = date("w");
    $query3 = "select z from day where z = '$d'";
    $result3 = mysql_query($query3);
    if(!$result3) error_message(sql_error());
    if ((mysql_num_rows($result3) == 0) and $d) {
        $query4 = "insert into day values('$d'";
        for ($i = 0; $i < 96; $i++) {
            $query4 = $query4 . ",'0'";
        }
        $query4 = $query4 . ")";
        $result4 = mysql_query($query4);
        if(!$result4) error_message(sql_error());
        }
}
########################
function linkadd($link)
{
$query = "select c1 from links where c1 = '$link'";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
if (mysql_num_rows($result) == 0) {
    $query = "insert into links values('$link','1')";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
}
else {
    $query = "update links set c2 = c2 + 1 where c1 = '$link'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
}
}
#######################
function blacklisted($from)
{
    $query = "select e1 from blacklist where e1 = '$from'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
    return (mysql_num_rows($result) > 0);
}
###############

mysql_close($linkid);
