<?
ob_start();
/* Written by Gerben Schmidt, http://scripts.zomp.nl */
include_once("functions.php");
include('config.php');
include('session.php');
include('header.php');
include("../language/$language");


if($_GET[id]){
$query = mysql_query("DELETE FROM $_GET[tablename] WHERE (id = $_GET[id]) LIMIT 1");

if($_GET[tablename] = $table_users)
{
$query = mysql_query("DELETE FROM $table WHERE (userid = $_GET[id])");
}
}

if($_POST[table] == $table){ //table for posts
foreach($_POST[id] as $id){

// delete images that belong to this post
$query = "SELECT * FROM $table WHERE (id = $id)";
$result = mysql_query ($query, $link) or die("Died getting info from db.  Error returned if any: ".mysql_error());
$entry = mysql_fetch_array($result,MYSQL_ASSOC);

$images = explode(";", $entry[image]);
foreach($images as $image){
$path1 = "../thumbs/$image";
$path2 = "../upload/$image";
unlink($path1);
unlink($path2);
}

// delete post
$query = mysql_query("DELETE FROM $table WHERE (id = $id)");
}
}

if($_POST[table] == $table_pages){ //table for pages
foreach($_POST[id] as $id){
$query = mysql_query("DELETE FROM $table_pages WHERE (id = $id)");
}
}

if($_POST[table] == $table_comments){ //table for comments
foreach($_POST[id] as $id){
$query = mysql_query("DELETE FROM $table_comments WHERE (id = $id)");
}
}

header("Location: members.php?message=3");
ob_end_flush();

include('footer.php');
?>