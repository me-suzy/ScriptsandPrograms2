<?php

if($showerror!=1){
ini_set(display_errors,off);
}else{
ini_set(display_errors,on);
}




include("mysql.php");
include("config.php");
include("usercheck.php");




include("functions.php");
include("stimits.php");



if($user[position]=="Admin"||$user[position]=="Staff"||$user[position]=="Moderator"){
      $staff="yes";
      ini_set(display_errors,on);
}else{
      $staff="HELL NO FOOL";
}




?>