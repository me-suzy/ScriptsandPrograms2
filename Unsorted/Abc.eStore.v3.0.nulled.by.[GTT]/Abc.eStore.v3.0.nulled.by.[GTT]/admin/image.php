<?php
//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

	$image = $_GET['image'];
	$dir = $_GET['dir'];
?>

<html>
<head>
<title><? echo "$image"; ?></title>
	  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
	  <link rel='stylesheet' href='../style.css' type='text/css'>
</head>
<body>
<?
echo "<table align='center' width='100%'><tr><td align='center' valign='middle'>
<img src='../images/$dir/$image'><p>$image</p><p><a href = \"javascript:window.close()\">".$lng[626]."</a></p></td></tr></table>
";
?>
</body>
<html>