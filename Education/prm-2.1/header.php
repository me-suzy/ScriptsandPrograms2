<?PHP

echo('

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>'.$GLOBALS['title'].'</title>
	<style>
 	table {font-size:11px;font-family:verdana;background-color:#ffffff;}
	.bordertable {background-color:#8080ff;border: thin solid #dddddd;}
	input, select {font-size:10px;font-family:verdana;background-color:#fefefe}
	.login,.grid {
		border: thin solid #6699cc;
		padding: 5;
		background-color:#fefefe
	}
	a{color:blue}
	a.visited{color:blue}
	.even{
		text-align: left;
		font-family: verdana;
		font-size: 11px;
		color: black;
		background-color: #E5FFCC;
	}/*For alternating rows of information */
	.odd{
		text-align: left;
		font-family: verdana;
		font-size: 11px;
		color: black;
		background-color:#f6f6f6;
	}/*For alternating rows of information*/
	.even:visited{text-align: left; font-family:verdana;font-size: 11px;color:black;background-color:#E5FFCC;}
	.odd:visited{text-align: left; font-family:verdana;font-size: 11px;color:black;background-color:#f6f6f6;}
</style>
</head>

<body bgcolor="#8080ff">

<table width="100%" class="bordertable" cellpadding="5">
<tr><td align="center" width="100%" bgcolor="#ffffff">');

?>