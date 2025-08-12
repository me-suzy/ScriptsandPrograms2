<form action="lostpass.php" method ="post">
Email: <input type="text" name="email" value=""><br>
<input type="submit" />
<br>
<a href=javascript:history.go(-1)> Please click here to go back. </a><br><br>
<br>Powered by The_Infernos NULL News <a href="http://www.nullbranded.tk>nullbranded.tk"</a> Copyrighted to The_Inferno<br>
<br>
</html>
<?php
$user_email = $_POST['email'];
if($user_email != "")
{
include("config.php");
$dbh=mysql_connect ("localhost", "$username", "$password") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbase");
$newpass= chr(rand(50,127)) and chr(rand(50,127)) and chr(rand(50,127)) and chr(rand(50,127));
$query = "UPDATE `emails` SET `password` = '$newpass' WHERE `email` ='$user_email'";
mysql_query($query);
$query = "SELECT * from emails";
$data = mysql_query($query);
$result = mysql_fetch_array($data);
$to = $result['email'];
$user_username= $result['username'];
mail($to,"Lost pass request","Dear $user_username your new password is $newpass. Thank you, /n The Null_Branded staff");
mysql_close();
}
else{Echo"Please enter a correct email address <br>";}
include("config.php");
$dbh=mysql_connect ("localhost", "$username", "$password") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbase");
$query =  "SELECT username FROM emails ";
$result = mysql_query($query);
$num = mysql_num_rows($result);
$i = 0;
while($row = mysql_fetch_array($result)){
echo $row['username'];
echo "<br>";
$i++;}
print "<br> A total of $i user in the database.";
mysql_close();
?>

































