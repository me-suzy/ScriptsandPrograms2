<?php


if($showerror!=1){
ini_set(display_errors,off);
}else{
ini_set(display_errors,on);
}



if($_GET[p]==logout){

setcookie ("email", "none", time() - 3600);
setcookie ("pass", "none", time() - 3600);

setcookie ("email");
setcookie ("pass");

setcookie ("email");
setcookie ("pass");
}


include("mysql.php");
include("config.php");
$email=$_COOKIE[email];
$pass=$_COOKIE[pass];
$stat = mysql_fetch_array(mysql_query("select * from users where email='$email' and pass='$pass'"));
if(empty($stat[user])){
$email="guest";
$pass="guest";
$stat = mysql_fetch_array(mysql_query("select * from users where email='$email' and pass='$pass'"));
}

include("header.php");


include("functions.php");
include("stimits.php");





$stat = mysql_fetch_array(mysql_query("select * from users where email='$email' and pass='$pass'"));

if($stat[rank]=="Admin"||$stat[rank]=="Artist"||$stat[rank]=="Mod"||$stat[rank]=="Coder"||$stat[rank]=="Staff"){
      $staff="yes";
      ini_set(display_errors,on);
}else{
      $staff="HELL NO FOOL";
}





if(!$p){
$p=updates;
}

if(!file_exists("$p.$stat[world].php")&&!file_exists("$p.001.php")){
$oldp=$p;
$p="404";
}

include("logged.php");


if(file_exists("$p.$stat[world].php")){
include("$p.$stat[world].php");
}else{
include("$p.001.php");
}

include("footer.php");
?>