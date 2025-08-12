
<?php
/*
version 0.1
Build by chris mccabe- under the gpl license
for updates and news or if you have feedback
http://scripts.maxersmix.com

page used to add informaltion the database
*/


include("dbinfo.php");
$EX_title = $_POST['Link_title'];
$EX_link = $_POST['Link'];
$IN_date = date("Y-m-d");

if(empty($EX_title) || empty($EX_link)){

   echo "no data entered";
}
else
{

$insert_query = "INSERT INTO `links` (`id`, `url`, `title`, `date`) VALUES ('', '".$EX_link."', '".$EX_title."', '".$IN_date."');";

$result = mysql_query($insert_query);
echo "link added <a href=\"index.php\">Back</a> Please link to our demo.";
}
mysql_close();
?>

