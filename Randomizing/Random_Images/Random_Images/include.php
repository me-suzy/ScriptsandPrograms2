/*script written by Skorch the most extreme cliff jumper from http://12feetunder.com. This script is free and can be modified anyway you see fit. You can contact me, with ideas, new versions or help installing or customizing your script, through my website. if you like this script i would appreciate a link back to my site. I'm trying to stimulate a nautral linking campaign so pick your favorite page and hook it up with some anchor text and, if I'm lucky, a description so it has "content"*/

<?php
$host = "localhost";/*the domain that the script runs on, localhost is usually fine*/
$user = "db_user";/*this should be a fully privleged user*/
$pass = "Your_db_password";/*This is set when a new db is created*/
$db = "Your_db_name";/*replace with the db you installed*/

/*DO NOT CHANGE*/
$conn = mysql_connect("$host", "$user", "$pass")
or die ("Could not connect : " . mysql_error());
$db_select = mysql_select_db("$db", $conn)
or die ("Could not select db : " . mysql_error());
$url='your url';/*unsupported as of V 1.3*/
?>