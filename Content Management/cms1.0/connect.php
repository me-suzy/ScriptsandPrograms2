<?php
//enter your database connection information
$_database="cms2";
$_username1 = "root";
$_password1 = "";
$_hostname1 = "localhost";      

//set html wysiwyg editor. "on" or "off"
$editor = "off";

$con=mysql_connect($_hostname1,$_username1)or die("coud not connect");
$select=mysql_select_db($_database,$con) or die(" could not choose database");
?>