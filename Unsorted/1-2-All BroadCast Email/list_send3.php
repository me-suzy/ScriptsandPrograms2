<?PHP
if($sendval != resend AND $pval != "check"){

 $c = count ($_FILES ['file'] ['name']); 
 for ($i = 0; $i < $c; $i++) { 
 $usrfile_name = $_FILES['file']['name'][$i]; 
 $usrfile_tmp = $_FILES['file']['tmp_name'][$i]; 
 $usrfile_type = $_FILES['file']['type'][$i]; 
 $location = "attach/$usrfile_name"; 
 move_uploaded_file ($usrfile_tmp, $location); 
 $link1n = "$link1n$usrfile_name  .  ";
 $link1t = "$link1t$usrfile_type  .  ";
 } 
 
  mysql_query ("DELETE FROM Messages
  WHERE id LIKE '$savid'
								");

$currenttime = date("H:i:s");
$today = date("Ymd");
$filterdata = "";
if ($filter != ""){
	$prefilter = "AND nl LIKE '$nl'
				AND email != ''
				AND active LIKE '0'"; 
	$ffind = mysql_query ("SELECT * FROM Templates
                         WHERE id LIKE '$filter'
						 LIMIT 1
						");
	$fresult = mysql_fetch_array($ffind);
	$filterdata = stripslashes($fresult["content"]);
	$filterdata = "AND $filterdata";
	$filterdata = str_replace (" DIVIN", "$prefilter", $filterdata);
}
$findcount = mysql_query ("SELECT * FROM ListMembers
WHERE nl LIKE '$nl'
AND email != ''
AND active LIKE '0'
$filterdata
");

// mass multi list mailing id creation

	mysql_query ("INSERT INTO 12all_MesgId (numlist) VALUES ('0')");  
	$mesgidf = mysql_query ("SELECT * FROM 12all_MesgId
                         WHERE numlist = '0'
						 ORDER BY id DESC
						 LIMIT 1
                       ");
	$mesgid = mysql_fetch_array($mesgidf);
	$mesg_id = $mesgid["id"];
	$mesg_numlit = "0";
$countdata = mysql_num_rows($findcount);
$Content=urldecode($Content);
$Content = addslashes($Content);
$Text=urldecode($Text);
$Text = addslashes($Text);
$subject=urldecode($subject);
$subject = addslashes($subject);
$cucc = 1; 
if ($schedule == "yes"){
$status = "4";
}
else{
$status = "0";
}
$s_date = ''.$yearp.'-'.$monthp.'-'.$dayp;
$s_time = ''.$hourp.':'.$minutep.':'.'00';
foreach ($nlbox as $something) 
{
if ($something != "")
{ 

$findcount = mysql_query ("SELECT * FROM ListMembers
WHERE nl LIKE '$something'
AND email != ''
AND active LIKE '0'
$filterdata
");
$countdata = mysql_num_rows($findcount);
$mfromn = addslashes($mfromn);

mysql_query ("INSERT INTO Messages (mfrom, mfromn, subject, textmesg, htmlmesg, mdate, mtime, nl, amt, type, tlinks, link1n, link1t, filter, mesg_id, status, s_date, s_time, user) VALUES ('$from' ,'$fromn' ,'$subject' ,'$Text' ,'$Content' ,'$today' ,'$currenttime' ,'$something' ,'$countdata' ,'$type' ,'$links' ,'$link1n' ,'$link1t' ,'$filter' ,'$mesg_id' ,'$status' ,'$s_date' ,'$s_time' ,'$usernow')");  
$cucc = $cucc + 1;
$mesg_numlit = $mesg_numlit + 1;
} 
}
		mysql_query("UPDATE 12all_MesgId SET numlist = '$mesg_numlit' WHERE id = '$mesg_id'");

$cucc = $cucc - 1;
$nucc = 1;
$id = scode1;
}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
<?PHP
if ($pval != "check"){
?>
<META HTTP-EQUIV="Refresh" CONTENT="4; URL=main.php?nl=<?PHP print $nl; ?>&id=<?PHP print $id; ?>&sendval=<?PHP print $sendval; ?>&subject=<?PHP print $subject; ?>&nlamt=<?PHP print $nlamt; ?>&pval=check&page=list_send3&schedule=<?PHP print $schedule; ?>&cursent=<?PHP print $cursent; ?>&cval=<?PHP print $cval; ?>">
<?PHP
}
?>
</head>

<body bgcolor="#FFFFFF" text="#000000" <?PHP if ($pval != "check"){ ?>onLoad="<?PHP 
require('send_app_settings.inc.php');
if ($sendval != resend){
if ($schedule != "yes"){
$setter = mysql_query ("SELECT * FROM Messages
                         WHERE subject LIKE '$subject'
						 AND init = '0'
						 ORDER BY id DESC
						 LIMIT $nlamt
                       ");
if ($c1 = mysql_num_rows($setter)) {
while($set = mysql_fetch_array($setter)) {

?>
window.open('<?PHP if ($sa_type == "1"){ print "send_app"; } if ($sa_type == "2") { print "send_appm"; } ?>.php?id=<?PHP print $set["id"]; ?>&sendval=<?PHP print $sendval; ?>&nl=<?PHP print $nl; ?>&schedule=<?PHP print $schedule; ?>','<?PHP print $set["id"]; ?>','width=350,height=175,scrollbars,resizable'); <?PHP
}
}
}
}
else{
?>
window.open('<?PHP if ($sa_type == "1"){ print "send_app"; } if ($sa_type == "2") { print "send_appm"; } ?>.php?id=<?PHP print $id; ?>&sendval=<?PHP print $sendval; ?>&nl=<?PHP print $nl; ?>','Send','width=350,height=175,scrollbars,resizable');
<?PHP
}
?>"<?PHP } ?>>
<?PHP 
if ($pval != "check"){
?>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_232; ?></strong></font></p>
<p><b><font face="Arial, Helvetica, sans-serif" size="2"><?PHP print $lang_272; ?></font></b></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_273; ?> 

<?PHP
}
if ($pval == "check"){
?>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_232; ?></strong></font></p>
<p><b><font face="Arial, Helvetica, sans-serif" size="2"><?PHP print $lang_272; ?></font></b></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_273; ?> 
</font></p>
<p><a href="main.php?nl=<?PHP print $nl; ?>&page=send3&st=<?PHP print $status; ?>&schedule=<?PHP print $schedule; ?>"><font color="#FF0000" size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_164; ?></strong></font></a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?PHP 
require('send_app_settings.inc.php');
if ($sendval != resend){
if ($schedule != "yes"){
$setter = mysql_query ("SELECT * FROM Messages
                         WHERE subject LIKE '$subject'
						 AND init = '0'
						 ORDER BY id DESC
						 LIMIT $nlamt
                       ");
if ($c1 = mysql_num_rows($setter)) {
while($set = mysql_fetch_array($setter)) {

$id = $set["id"];
?>
<iframe src="list_send3_p.php?&id=<?PHP print $id; ?>" id="iView<?PHP print $id; ?>" style="width: 350px; height:20px"></iframe>
<?PHP
//include("send_app2.php");
}
}
}
}
else{
?>

<iframe src="list_send3_p.php?&id=<?PHP print $id; ?>&sendval=resend&nl=<?PHP print $nl; ?>" id="iView<?PHP print $id; ?>" style="width: 350px; height:20px"></iframe>
<?PHP
//include("send_app2.php");
}
?>
<META HTTP-EQUIV="Refresh" CONTENT="1; URL=main.php?nl=<?PHP print $nl; ?>&sendval=<?PHP print $sendval; ?>&page=send3&schedule=<?PHP print $schedule; ?>">
<?PHP
}
?>
</body>
</html>