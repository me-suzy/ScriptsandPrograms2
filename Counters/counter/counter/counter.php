<!doctype html public "Kys Scriptomania 2.0">
<html>
<head>
<title>Counter</title>
</head>
<body>
<p align="center">
<?
include("config.php");
$counter = fread(fopen($filename, "r"), filesize ($filename));
$counter++;
fwrite(fopen($filename, "r+"), $counter);

function image($number) {
global $imgdir;
   echo "<img src=\"$imgdir/$number.jpg\">";
}
$counter=strval($counter);
if ($image==0) echo "<font color=\"#000000\" size=2 face=\"Verdana\">$counter</font>";
else {
for ($i=0; $i<strlen($counter); $i++) {
image($counter[$i]);
}
}
?>
</p>
</body>
</html>
