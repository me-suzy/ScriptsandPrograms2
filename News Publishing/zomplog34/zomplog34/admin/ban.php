<?
ob_start();
/* Written by Gerben Schmidt, http://scripts.zomp.nl */
include_once("functions.php");
include('config.php');
include('session.php');
include('header.php');
include("../language/$language");


if($_GET[ip]){
$query="INSERT INTO $table_banned (ip) VALUES ('$_GET[ip]')";
	$result=mysql_query($query, $link) or die("Died inserting login info into db.  Error returned if any: ".mysql_error());
}

if($_GET[undo]){
$query = mysql_query("DELETE FROM $table_banned WHERE ip = '$_GET[undo]'");
}

header("Location: comments.php?message=13");
ob_end_flush();
?>