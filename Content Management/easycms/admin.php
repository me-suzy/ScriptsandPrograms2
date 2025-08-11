<?php

  session_start() ;
import_request_variables("gP", "r_");

if  ($_SESSION["aut"]<> 1){
   echo "You are not allowed to view this page<br>";
   exit();}


 ?>


<html>
 <head>
 <title>
 Easy CMS Administration
 </title>
 </head>
 <frameset  cols="22%,* ">
 
   <frame src="navegation.php" name="nav">
   <frame src="newspost.php" name="main">

    <noframes>
 Your browser dosn´t support frames
   </noframes>

</frameset>

</html>
