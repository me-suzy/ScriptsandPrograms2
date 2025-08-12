<?php
$_GET['logout'];
if($_GET['logout'] == "true")
{
setcookie("nullnews","",time() - 6000);
}
$mailcount = 0;
$pass = "passwordabc123";
$ip =$_SERVER['REMOTE_ADDR'];
if(!isset($_COOKIE['news.00100'])){
if ($_POST["password"] == $pass){
setcookie("nullnews",md5($pass), time() + 3600);
echo"<a href=\"mail.php\">Please click here to send the news letter</a>";
}
if ($_POST["password"] != $pass){
echo "Please enter a vaild password";
}
}
?>
<html>
<head>

<title></title>
</head>


<body bgcolor="#FFFFFF">

</body>
<form action="newslogon.php" method ="post">
Password:  <input type="password" name="password"> <br/>
<input type="submit" value="Send"/><input type ="reset" />
<br>Powered by The_Infernos NULL News <a href="http://www.nullbranded.tk>nullbranded.tk</a> Copyrighted The_Inferno 2005<br>
</body>


</html>








