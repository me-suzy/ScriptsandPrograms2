<?PHP
$time22 = getdate();
$hour = $time22[hours];
$hour = $hour;
$min = $time22[minutes];
$sec = $time22[seconds];
$currenttime = "$hour$min$sec"; 
$today = date("Ymd");
$findcount = mysql_query ("SELECT * FROM ListMembers
WHERE nl LIKE '$nl'
AND email != ''
AND active LIKE '0'
");
$countdata = mysql_num_rows($findcount);
$Content=urldecode($Content);
$Content = addslashes($Content);
$Text=urldecode($Text);
$Text = addslashes($Text);
$subject=urldecode($subject);
$subject = addslashes($subject);
mysql_query ("INSERT INTO Messages (mfrom, subject, textmesg, htmlmesg, mdate, mtime, nl, amt, type, tlinks, link1n, link1t, completed) VALUES ('$from' ,'$subject' ,'$Text' ,'$Content' ,'$today' ,'$currenttime' ,'$nlpass' ,'SAVED' ,'$type' ,'$links' ,'$link1n' ,'$link1t' ,'1')");  
mysql_query ("DELETE FROM Messages
 			WHERE id LIKE '$savid'
			");

$id = scode1;
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_453; ?></strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_454; ?></font></p>
</body>
</html>
