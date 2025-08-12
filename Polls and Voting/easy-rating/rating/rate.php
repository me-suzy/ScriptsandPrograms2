<html>
<head>
<title>Rate my Site.</title>
</head>
<body bgcolor="#d8d8bd">
<script language="javascript" type="text/javascript">
<!--
 window.resizeTo(600,400);
-->
</script>
<?php
	$vote=$_POST["rate"];
	$file="$vote" . ".txt";
	$fh=fopen($file,'r') or die ('Failed to read file');
	$count=fread($fh,filesize($file)) or $count=1;
	fclose($fh);
	$count++;
	
	$fh=fopen($file,'w') or die('could not open file');
	fwrite($fh,$count) or die ('could not write');
	fclose($fh);
?>
<br>
Thank you for Rating my website!.<br>
<hr>
To close this window click Close window button.<br>
To View the Rating results click Results button.<br>
<hr>
<br>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:window.close();">Close window</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="results.php">Rating Results</a><br>
</body>
</html>