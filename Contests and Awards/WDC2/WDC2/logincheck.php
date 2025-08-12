<?php



if($_GET[p]==logout){

setcookie ("email", "none", time() - 3600);
setcookie ("pass", "none", time() - 3600);

setcookie ("email");
setcookie ("pass");

setcookie ("email");
setcookie ("pass");
}

if($p=login&&$login==yes){
if($remember){
	setcookie("email",$_POST['email'],time()+10000000);
	setcookie("pass",$_POST['pass'],time()+10000000);
}else{
	setcookie("email",$_POST['email']);
	setcookie("pass",$_POST['pass']);
}
}
$email=$_COOKIE['email'];
$pass=$_COOKIE['pass'];
if($md5pass==1){
$pass=md5($pass);
}




?>