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
include("./common.php");
$linkid = db_connect();
if (!$linkid) error_message(sql_error());
$day = date("w");
$yday = date("w", time() - 86400);
$hour = date("G");

if ($HTTP_X_FORWARDED_FOR) $REMOTE_ADDR = $HTTP_X_FORWARDED_FOR;
if ($HTTP_REFERER) {
$url = parse_url($HTTP_REFERER);
$from = eregi_replace("www\.", "", $url["host"]);
$from = addslashes($from);
if (!$from) $from = "noref";
}
else $from = "noref";

?>
<script language="JavaScript">
<!--
document.cookie='from=<?php echo $from; ?>; expires=<?php echo date("l, j-M-y G:i:s ", time() + 86400); ?>GMT;';
//-->
</script>
<?php
if (blacklisted($from) or blacklisted($REMOTE_ADDR)) exit;
dbadd($from);
if (is_raw_hit($REMOTE_ADDR)) $is_raw = 1;
else $is_raw = 0;
hit_in($from,$is_raw);

$query = "select * from lastupdate";
$result = mysql_query($query);
$data = mysql_fetch_array($result);
if ($hour != $data["i1"]) $hourlyreset = 1;
if (abs(time() - $data["i2"]) > 60) calculate();
//////
/////
function calculate() {
global $dailyreset, $hourlyreset;
$day = date("w");
$min = date("i");
$yday = date("w", time() - 86400);
$hour = date("G");
if ($hour > 0) $yhour = $hour - 1;
else $yhour = 23;
$time = time();
$query5 = "update lastupdate set i2 = '$time'";
$result5 = mysql_query($query5);
$query = "select * from trade";
$result = mysql_query($query);
while ($data = mysql_fetch_array($result)) {

$query2 = "select * from day where z = '" . $data["a1"] . "'";
$result2  = mysql_query($query2);

if (!mysql_num_rows($result2) == 0) {
$data2 = mysql_fetch_array($result2);
for ($i = 0; $i <= 23; $i++) {
$data["a22"] += $data2["zr$i"];
$data["a23"] += $data2["zu$i"];
$data["a24"] += $data2["zo$i"];
$data["a25"] += $data2["zc$i"];
}
}
else {
$data["a22"] = $data["a23"] = $data["a24"] = $data["a25"] = 0;
}
$data["a26"] = $data2["zr$hour"];
$data["a27"] = $data2["zu$hour"];
$data["a28"] = $data2["zo$hour"];
$data["a29"] = $data2["zc$hour"];


    $d = $data["a1"];
    if ($data["a25"]) $p = 1000 * (($data["a25"] + 1) / ($data["a24"] + 1));
    else $p = 0;
    $p = $p * sqrt(($data["a22"] + 1)/ ($data["a24"] + 1));
    if (!($data["a9"] == -1 && $hour > 0 && $data2["zr$hour"] + $data2["zr$yhour"] == 0) && $data["a18"] > $data["a28"]) $p += 3000 * (($data["a18"] - $data["a28"]) / ($data["a28"] + 1));
    if ($data["a15"] == -1 && $data["a24"] > 10 && (($data["a24"] + 1) / ($data["a22"] + 1) * 100 > $data["a8"])) $p = -1000000;
    if ($data["a16"] == -1) $p = -1000000;
	
    $p = ceil($p);

    $query3 = "update trade set a19 = '$p' where a1 = '$d'";
    $result3 = mysql_query($query3);
    if(!$result3) error_message(sql_error());
	
}

if(!$result5) error_message(sql_error());
   $query14 = "select a1 from trade";
   $result14 = mysql_query($query14);
   while ($data14 = mysql_fetch_array($result14)) 
       dbadd($data14["a1"]);

include("toplistnew.php");
}
///
if ($hourlyreset) {
if ($hour == 0) {
    $dy = date("Ymd",time() - 6000);
    $dy = $dy . ".txt";
    if (!(file_exists("./cjstats/$dy"))){
        $query = "select * from day";
        $result = mysql_query($query);
        $fp = fopen("./cjstats/$dy","w");
        while ($data = mysql_fetch_array($result)) {
              $str = $data["z"];
              for ($i = 0; $i <= 23; $i ++) {
                  $str = "$str|" . $data["zr$i"];
              }
              for ($i = 0; $i <= 23; $i ++) {
                  $str = "$str|" . $data["zu$i"];
              }
              for ($i = 0; $i <= 23; $i ++) {
                  $str = "$str|" . $data["zo$i"];
              }
              for ($i = 0; $i <= 23; $i ++) {
                  $str = "$str|" . $data["zc$i"];
              }
              $str = "$str\n";
              fwrite($fp, $str);
        }
        fclose($fp);
    }
}

   $query11 = "update day set zr$hour = '0', zu$hour = '0', zo$hour = '0', zc$hour = '0'";
   $result11 = mysql_query($query11);
   $query12 = "update lastupdate set i1 = '$hour'";
   $result12 = mysql_query($query12);
   if(!$result12) error_message(sql_error());
if ($hour % 6 == 0) {
            $fp = fopen("./iplog.txt" , "w");
            fwrite($fp, "0000\n");
            fclose($fp);
}
$query = "delete from day where ";
for ($i = 0; $i < 24; $i++)
{
    $query .= "zr$i = 0 and zu$i = 0 and zo$i = 0 and zc$i = 0";
    if ($i < 23) $query .= " and ";
}
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
}
///


function is_raw_hit($ip) {
$ip_array = file("./iplog.txt");
if (in_array("$ip\n", $ip_array) || !$ip) return true;
else {
    if ($fp = fopen("./iplog.txt" , "a+")) {
            fwrite($fp, "$ip\n");
            fclose($fp);
            }
            return false;
    }
}

function hit_in($from,$is_raw)
{
    global $day,$hour,$is_raw;
    $tm = time();
    if ($is_raw) $query = "update trade set a6 = '$tm', a10 = a10 + 1 where a1 = '$from'";
    else $query = "update trade set a6 = '$tm', a10 = a10 + 1, a11 = a11 + 1 where a1 = '$from'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
    dbadd($d);
    if ($is_raw) $query = "update day set zr$hour = zr$hour + 1 where z = '$from'";
    else $query = "update day set zr$hour = zr$hour + 1, zu$hour = zu$hour + 1 where z = '$from'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
}

function dbadd($d)
{
    $day = date("w");
    $query = "select z from day where z = '$d'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
    if ((mysql_num_rows($result) == 0) and $d) {
        $query2 = "insert into day values('$d'";
        for ($i = 0; $i < 96; $i++) {
            $query2 = $query2 . ",'0'";
        }
        $query2 = $query2 . ")";
        $result2 = mysql_query($query2);
        if(!$result2) error_message(sql_error());
}
}
function blacklisted($from)
{
    $query = "select * from blacklist where e1 = '$from'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
    return (mysql_num_rows($result) > 0);
}
mysql_close($linkid);
