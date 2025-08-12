<?php
$user_email = $_POST['email'];
$user_username = $_POST['username'];
$user_password = MD5($_POST['password']);
if ($user_email == "" or $user_username == "" or $user_password == "" )
{
print "Please enter a correct email address, username, and password. <br>";
}
else {
if(strpos($user_email,"@") == false){
echo "Please enter a <b>vaild email</b>";
die();}
else{
$user_username = strip_tags($user_username);
$user_email = strip_tags($user_email);
include("config.php");
$dbh=mysql_connect ("localhost", "$username", "$password") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbase");
$query =  "SELECT username,email FROM emails";
$result = mysql_query($query);
$num = mysql_num_rows($result);
while($row = mysql_fetch_array($result)){
if($row['username'] == $user_username)
{
Echo "Sorry that username already in use. <br>Please go back and select another.";
exit;
}
if($row['email'] == $user_email )
{
Echo "Sorry, that E-mail already in use. <br>Please go back and select another.";
exit;
}
}
$query = "INSERT INTO emails(email,username,password) VALUES ('$user_email','$user_username','$email_password')";
mysql_query($query);
$usertest = $_POST['username'];
$emailtest = $_POST['email'];
if($usertest != $user_username || $emailtest !=$user_email){
Echo "!asshat";
die();}
print "Thank you $user_username, $user_email has been added to our list.";
mail($user_email,"Thank you for joining, the Null_branded newsletter","Thank you for joining the Null_Branded newsletter,\nif you have any problems, please feel free to contact me at: lost_tiwce@hotmail.com, \n Please tell you're friends. \n Thanks,\n-The_Inferno, nullbranded.tk");
mysql_close();
}
}
?>

<html>
<head>
<title></title>
</head>


<body bgcolor="#FFFFFF">

</body>

<form action="sub.php" method ="post">
Email: <input type="text" name="email" value=""><br>
Username: <input type="text" name="username" value=""><br>
Password: <input type="text" name="password" value=""><br>
<input type="submit" />
<br>
<a href=javascript:history.go(-1)> Please click here to go back. </a><br><a href="unsub.php">Click here to unsubscribe</a><br>
<br>Powered by The_Infernos NULL News <a href="http://www.nullbranded.tk>nullbranded.tk"</a> Copyrighted The_Inferno 2005<br><br>
Subscribed users:<br><br>
</html>
<?php
include("config.php");
$dbh=mysql_connect ("localhost", "$username", "$password") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbase");
$query =  "SELECT username FROM emails";
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




















































































