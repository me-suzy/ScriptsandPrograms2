<html>
<head>
<title></title>
</head>
<body bgcolor="#FFFFFF">
<center>
<form action="mail.php" method ="post">
<textarea  name="body" cols=60 rows=10>Messege goes here.</textarea>
<br>
<input type="submit" name="submit" value="Send"><input type ="reset" />
<br>Powered by The_Infernos NULL News <a href="http://www.nullbranded.tk>nullbranded.tk"</a> Copyrighted The_Inferno 2005<br>

</body>
<center>
</html>
<?php
$mailcount = 0;
if(!isset($_COOKIE['nullnews'])){
echo"<center>";
Echo "<a href=\"newslogon.php\">Please logon first </a>";
echo"</center>";
}
else{
echo"<center>";
echo "<a href=\"newslogon.php?logout=true\">Logout</a>";
echo"</center>";
include("config.php");
if($_COOKIE[nullnews] == md5($pass)){
if(isset($_POST['submit'])){
$dbh=mysql_connect ("localhost", "$username", "$password") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbase");
$query =  "SELECT * FROM emails";
$result = mysql_query($query);
while($tb  = mysql_fetch_array($result)){
$user =$tb['username'];
$to =$tb['email'];
$subject = 'Null_branded newsletter ';
$messege = stripslashes($_POST['body']);
$headers = 'From null_branded news letter';
mail($to,$subject,$messege,$headers);
print "Mail sent to $user at $to <br>";
$mailcount = $mailcount + 1;
}print "<br> Total e-mails sent: $mailcount";
mysql_close();}

}else{Echo "<a href=\"newslogon.php\">Please logon first </a>";}}
?>










































































