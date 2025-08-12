<?php
include("connect.php");

$link = "CREATE DATABASE newsletterdb";
$res = mysql_query($link) or die(mysql_error());

mysql_select_db("newsletterdb");

$link = "
CREATE TABLE `newsletters` (
  `name` text NOT NULL,
  `content` text NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1;";
$res = mysql_query($link) or die(mysql_error());

$link = "
CREATE TABLE `users` (
  `name` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `date` text NOT NULL,
  `status` text NOT NULL,
  `unsubscribed` text NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1;";
$res = mysql_query($link) or die(mysql_error());

if ($res)
die("<p>Succesfully made database. Please delete this file for security reasons.</p>");
?>



