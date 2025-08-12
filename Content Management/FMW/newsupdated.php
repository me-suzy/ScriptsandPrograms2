<?php
include "header.php";

session_start();
if (($_SESSION['perm'] < "3"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}


$news_id = $_POST['news_id'];

$news = $_POST['news'];
$hyperlink_text1 = $_POST['hyperlink_text1'];
$hyperlink_text2 = $_POST['hyperlink_text2'];
$hyperlink_url1 = $_POST['hyperlink_url1'];
$hyperlink_url2 = $_POST['hyperlink_url2'];
$topic_image = $_POST['topic'];
$news_title = $_POST['news_title'];



$query="UPDATE news SET news='$news', news_title='$news_title', topic_image='$topic_image', hyperlink_text1='$hyperlink_text1', hyperlink_text2='$hyperlink_text2', hyperlink_url1='$hyperlink_url1', hyperlink_url2='$hyperlink_url1'  WHERE news_id='$news_id'"; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 
?><meta HTTP-EQUIV="Refresh" CONTENT="0; URL=main.php">

