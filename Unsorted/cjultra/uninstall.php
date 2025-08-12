<?php
// Cjultra v2.1

if (ini_get('register_globals') != 1) {
    $supers = array('_REQUEST','_ENV','_SERVER','_COOKIE','_GET','_POST');
    foreach ($supers as $__s) {
        if (is_array($$__s) == true) extract($$__s, EXTR_OVERWRITE);
    }
    unset($supers);
}

include("./common.php");
$linkid = db_connect();
if (!$linkid) error_message(sql_error());

if ($action == "uninstall") uninstall();
?>
<html>

<head>
<title>CJULTRA SETUP</title>
</head>

<body bgcolor="#555555" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<center>
<p>
<form method="POST">
<input type="hidden" name="action" value="uninstall">
Password: <input type="password" size="20" name="b12"><br>
ARE YOU SURE YOU WANT TO UNINSTALL CJULTRA AND DELETE ALL DATA?<br>
This will work only if you already setup the script before
<br>
<input type="submit" name="confirm" value="UNINSTALL">
</form>
</p>
</body>
</html>

<?php
function uninstall()
{
global $b12;
$query = "Select b12 from settings";
$result = mysql_query($query);
if(!$result) error_message(sql_error());
$data = mysql_fetch_array($result);
if (!(ereg("^aa",$data["b12"]))) {
    $ps = crypt($data["b12"],"aa");
    $data["b12"] = $ps;
    $query = "update settings set b12 = '$ps'";
    $result = mysql_query($query);
    if(!$result) error_message(sql_error());
}
if (crypt($b12,"aa") != $data["b12"]) die ("WRONG PASS!!");

$query = "drop table trade";
$result = mysql_query($query);
$query = "drop table settings";
$result = mysql_query($query);
$query = "drop table links";
$result = mysql_query($query);
$query = "drop table lastupdate";
$result = mysql_query($query);
$query = "drop table blacklist";
$result = mysql_query($query);
$query = "drop table day";
$result = mysql_query($query);
echo "Uninstall complete, all data is lost";
exit;
}

