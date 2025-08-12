<?php

include 'config.php';



$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
        or die(mysql_error());


        /* Creating user table. */
echo "Creating user table in database......";
$query = "CREATE TABLE `$userstable` (username VARCHAR(255),email VARCHAR(255),password VARCHAR(255),realname VARCHAR(255),realage VARCHAR(255),gender VARCHAR(255),location VARCHAR(255),favouritecolour VARCHAR(255),homepage VARCHAR(255),link VARCHAR(255),vcode VARCHAR(255))";
if(mysql_query($query)){
echo "Sucessfully created uers table in database.<br>";
} else {
echo "Error, tables have not been created.<br>";
}

        /* Creating admin table. */
echo "Creating admin table in database......";
$query2 = "CREATE TABLE `$admintable` (username VARCHAR(255),email VARCHAR(255),password VARCHAR(255),vcode VARCHAR(255))";
if(mysql_query($query2)){
echo "Sucessfully created admin table in database.";
} else {
echo "Error, tables have not been created.";
}

        /* Inserting admin username and password into table. */
echo "<br>Inserting admin username and password into database......";
$query3 = "INSERT INTO `$admintable` (username,password,email,vcode)
                VALUES ('$AdminUsername','$AdminPass','$AdminEmail','')";
if(mysql_query($query3)){
echo "<font color=\"FF0000\" size=\"4\">Sucessfully added admin into database.<br> <b>Now remove this file from the server!</font></b>";
} else {
echo "Error, Admin has not been added.<br>";
}
?>