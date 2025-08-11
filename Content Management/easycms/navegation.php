<?php

  session_start() ;
import_request_variables("gP", "r_");

if  ($_SESSION["aut"]<> 1){
   echo "You are not allowed to view this page<br>";
   exit();}


 ?>
<html>
<head>
<title>Navegation</title>
<link rel="stylesheet" type="text/css"
href="style.css" />
</head>
<body bgcolor="#66CCFF">
<h3>Site Administration</h3>

  <a href='newspost.php' target="main"><b>News Manager</a><br><br>
 <a href='newsformat.php' target="main"><b>News Format</a><br><br>

  
 <a href='pagemanager.php' target="main"><b>Page Manager</a><br><br>
 <a href='template.php' target="main"><b>Template Manager</a><br><br>

  <a href='newgallery.php' target="main"><b>New Gallery</a><br><br>
<a href='galleryformat.php' target="main"><b>Gallery Format</a><br><br>
<a href='commentmanager.php' target="main"><b>Comments Manager</a><br><br>
<a href='logout.php' target="_top"><b>Log Out</a><br><br>
   </body>
 </html>
 
