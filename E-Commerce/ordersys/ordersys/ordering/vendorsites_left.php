<?php 
session_start();
header("Cache-control: private");
include 'config.php';
$connection = mysql_connect($host, $user, $pass) or
die("<p>ERROR: Could not connect to the MySQL server. Either it may be down or the settings specified in config.php are incorrect. The server may have sent an error message (below).<br /><br />".mysql_error()."</p>"); 
$selected = mysql_select_db($db_name, $connection) or die("<p>ERROR: Could not select the database on the MySQL server. Either the MySQL user account for which  settings are specified in config.php are incorrect (the user may not have access privileges), or the database does not exist. The server may have sent an error message (below).<br /><br />".mysql_error()."</p>"); 
$queryvendor = "SELECT `Name`,`website` FROM `vendor` ORDER BY `Name`"; 
$resultvendor = mysql_query($queryvendor);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?php
echo ('<title>'.$mainsite_name.' - '.$parentsite_name.'</title><link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<p>
<span style="font-size:10px;"><a href="'.$parentsite_url.'index.php" onclick="javascript:top.location.href = \''.$parentsite_url.'\';return true;">'.$parentsite_name.'</a> | 
<a href="'.$mainsite_url.'" onclick="javascript:top.location.href = \''.$mainsite_url.'\';return true;">'.$mainsite_name.'</a><br /><br />');

while ($row=mysql_fetch_array($resultvendor)) {  
$name=$row["Name"];
$site=$row["website"];

echo ('
*<a href="' . $site . '" target="right">' . $name . '</a><br />'); 
}
echo ('<br />
<a href="'.$parentsite_url.'index.php" onclick="javascript:top.location.href = \''.$parentsite_url.'\';return true;">'.$parentsite_name.'</a> | 
<a href="'.$mainsite_url.'" onclick="javascript:top.location.href = \''.$mainsite_url.'\';return true;">'.$mainsite_name.'</a>');
?>
</span>
</p>
</body>
</html>