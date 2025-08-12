<?php
	include("./include/connection.php");
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title> Banner Manager</title>
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<style type="text/css">
		.copyright {font: 8pt arial}
		.tips {font: italic 8pt arial}
		.copyrightsite {font: bold 8pt verdana}
		.header {font: bold 10pt verdana}
		.label {font: 9pt arial}
		.error {font: italic 8pt arial; color: red}
		body {font: 8pt arial}
		td {font: 8pt arial}
		input {font: 8pt arial}
</style>
</head>
<body bgcolor="white">
Copy and Paste this code to your page where you want the banner to appear:
<br><br>
<i>
&lt;SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="<?php=$site?>/banner/java.php?id=<?php echo $cid; ?>"&gt; 
&lt;/SCRIPT&gt;
</i>

<br>
<br>
<center><form><input type="button" value="Close" onClick="javascript: self.close();"></center>

</body>
</html>
