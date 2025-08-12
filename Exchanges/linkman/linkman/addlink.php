<html>
<head>
<title>Linkman</title>
</head>

<body bgcolor="#000000" text="#FFFFFF" link="#FFFFFF" alink="#FF0000" vlink="#CCCCCC">
<?php

// File to were the links are recorded
$file = 'links.txt';

// Dont Edit Below Line
//Make Sure All Info has been submited & Reads Forum
if (empty($_POST['name'])) die('Please make sure you have submited your name!'); else $name=($_POST['name']);
if (empty($_POST['url'])) die('Please enter the URL to the correct site!'); else $url =($_POST['url']);
if (!(preg_match("/(http:\/\/+[\w\-]+\.[\w\-]+)/i",$url))) die('URL can not be found. Please check and try again!');
if (empty($_POST['title'])) die('Please enter a title for your website!'); else $title=($_POST['title']);
if (empty($_POST['description'])) die('Please enter a brief description about your site!'); else $description = $_POST['description'];

//Master URL coding
$master_name = ("<hr size=\"2\" width=\"25%\" align=\"left\" noshade><b /><font face=\"Verdana\" size=\"2\">$name</font></b>");
$master_url = ("<br /><a href=\"$url\">$title</a><br />");
$master_description = ("<br /><font face=\"Verdana\" size=\"1\" color=\"#cccccc\">$description</font><br />");

//Opening up the file & Writting it
$fh = fopen($file, 'a+') or die('Could Not Open File!');
fwrite($fh, $master_name);
fwrite($fh, $master_url);
fwrite($fh, $description) or die('Could Not Submit At This Time!');
fclose($fh);

//Reading The File
$data = file($file) or die('no such file');
foreach ($data as $line) {
	echo $line;
 }
 
?>

</body>
</html>
