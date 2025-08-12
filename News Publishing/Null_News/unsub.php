<?php
$user_email = $_POST['email'];
$user_username = $_POST['username'];
$user_password = MD5($_POST['password']);
if ($user_email == "" or $user_username == "" or $user_password == "" )
{
print "Please enter a correct email address, username, and password, to unsubscribe :(. <br>";
}
else {
if(strpos($user_email,"@") == false){
echo "Please enter a <b>vaild email</b>";
die();}
else{$user_name = strip_tags($user_username);
$user_email = strip_tags($user_email);
include("config.php");
$dbh=mysql_connect ("localhost", "$username", "$password") or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ("$dbase");
$query = "DELETE FROM `emails` WHERE `username` = '$user_username' AND `email` = '$user_email' AND `password` = '$user_password'";
mysql_query($query);
$usertest = $_POST['username'];
$emailtest = $_POST['email'];
if($usertest != $user_username || $emailtest !=user_email){
Echo "!asshat";
die();}
print "Thank you $user_username, $user_email has been removed added to our list.";
mail($email,"Thank you your email has been removed, the Null_branded newsletter","Thank you, your name has been removed from the Null_Branded newsletter,\nif you have any problems, please feel free to contact me at: lost_tiwce@hotmail.com, \n Please tell you're friends. \n Thanks,\n-The_Inferno, nullbranded.tk");
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

<form action="unsub.php" method ="post">
Email: <input type="text" name="email" value=""><br>
Username: <input type="text" name="username" value=""><br>
Password: <input type="text" name="password" value=""><br>
<input type="submit" />
<br>
<a href=javascript:history.go(-1)> Please click here to go back. </a><br><a href="lostpass.php">Lost your password? </a><br>
<br>Powered by The_Infernos NULL News <a href="http://www.nullbranded.tk>nullbranded.tk"</a> Copyrighted to The_Inferno<br><br>
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














































































