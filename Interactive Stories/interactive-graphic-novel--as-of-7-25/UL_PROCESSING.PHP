<?php
include 'classes/HEADER.PHP';
include 'CNFG.php';
$head = "CHARACTER UPLOADED";
myheader($head);

   $dB = @mysql_connect($host, $user, $pass) or die("Connection to server Failed!");
   $conn=mysql_select_db($database_name, $dB) or die("Connection to dB Failed!");

   $file_name = $_FILES['IMG']['name'];
   $char_name = $_POST['NAME'];
   $directory = $_POST['LOAD'];
   $table_name = $directory . "_TABLE";
   $file_name = str_replace('/', '', $file_name);
   $file_name = str_replace('..', '.', $file_name);
   $destination = "$directory/" . $file_name;
   move_uploaded_file($_FILES['IMG']['tmp_name'], $destination);
      $value = "'$file_name', '$char_name'"; 
      $SQL = "INSERT INTO $table_name VALUES ($value)";
      $result = mysql_query($SQL, $dB) or die("Failed to insert data to the row of table \"$table_name\"!");
      echo "uploaded '$char_name' from '$file_name'";
?>

