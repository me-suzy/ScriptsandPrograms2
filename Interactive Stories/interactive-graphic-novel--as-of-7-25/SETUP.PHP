<?php
include 'classes/HEADER.PHP';
include 'write_config.php';
$head = "INSTALL MYSQL DB FOR THE SIGN WEB PROGRAMS";
myheader($head);

/*
      THIS BLOCK OF LINES CONNECTS TO THE DATABASE
      AND SETS THE STRINGS FOR TABLE CREATION AND DELETION
*/
/*********************************EDIT ONLY FOR DIFFERENT SERVER**************************/
   $host = $_GET['HOST'];
   $user = $_GET['USER'];
   $pass = $_GET['PASS'];
   $database_name = $_GET['DB_NAME'];
   $admin_USER = $_GET['ADMIN'];
   $admin_pass = $_GET['ADMIN_PASS'];
  
   $database_table = "SIGN_TABLE";
   $conf = "num INT PRIMARY KEY, chapter INT, name TEXT, text MEDIUMTEXT";
   $cnf = "num , chapter , name , text";
   $node_zero = "0, 1, 'TEST POST CHAPTER', 'THIS CHAPTER SHOULD BE VIEWED THROUGH THE SYSTEM'"; 

   $user_table = "USER_TABLE";
   $user_conf = "user TEXT, pass TEXT, authority INT";
   $user_cnf = "user, pass, authority";
   $user_zero = "'$admin_USER', '$admin_pass', 1"; 

   $char_table = "CHARACTER_TABLE";
   $char_conf = "file_name TEXT, name TEXT";

   $spea_table = "SPEAKER_TABLE";
   $spea_conf = "file_name TEXT, name TEXT";

   $bg_table = "BACKGROUND_TABLE";
   $bg_conf = "file_name TEXT, name TEXT";
    
   $dB = @mysql_connect($host, $user, $pass) or die("Connection to server Failed!");
   $conn=mysql_select_db($database_name, $dB) or die("Connection to dB Failed!");
/*****************************************************************************************/

DROP_TABLE($database_table, $dB);
MAKE_TABLE($database_table, $conf, $dB);
READ_FEILDS($database_table, $dB);
FILL_FEILDS($database_table, $node_zero, $dB);
REPORT_FEILD_CONTENTS($database_table, $dB);
?><BR><BR><?
/*****************************************************************************************/

DROP_TABLE($user_table, $dB);
MAKE_TABLE($user_table, $user_conf, $dB);
READ_FEILDS($user_table, $dB);
FILL_FEILDS($user_table, $user_zero, $dB);
REPORT_FEILD_CONTENTS($user_table, $dB);
?><BR><BR><?
/*****************************************************************************************/

DROP_TABLE($char_table, $dB);
MAKE_TABLE($char_table, $char_conf, $dB);
READ_FEILDS($char_table, $dB);

DROP_TABLE($spea_table, $dB);
MAKE_TABLE($spea_table, $spea_conf, $dB);
READ_FEILDS($spea_table, $dB);

DROP_TABLE($bg_table, $dB);
MAKE_TABLE($bg_table, $bg_conf, $dB);
READ_FEILDS($bg_table, $dB);
?><BR><BR><?
/*****************************************************************************************/

 LIST_TABLES($database_name, $dB);
?><BR><BR><?
/*****************************************************************************************/

   function DROP_TABLE($table_name, $dB) {
/*
      THESE LINES DELETE THE INDICATED TABLE IF IT EXISTS
*/
      $SQL="drop table $table_name";                                        
      if ($result=mysql_query($SQL, $dB)) { // or die("Table deletion Failed!<BR>");
         echo "Table \"$table_name\" was deleted<BR>";
         }
      }

   function MAKE_TABLE($table_name, $cnf, $dB) {
/*
      THESE THREE LINES CREATES THE INDICATED TABLE
*/
      $SQL="CREATE TABLE $table_name ($cnf)";                               
      $result=mysql_query($SQL, $dB) or die("Failed to create table \"$table_name\"!");
      echo "Table \"$table_name\" was succesfuly created!<BR>";
      }

   function LIST_TABLES($database_name, $dB) {
/*
      THESE LINES TEST THE CONNECTION TO THE TABLE
*/
      $result = mysql_list_tables($database_name, $dB) or die("Connection to tables Failed!");
      while($array = mysql_fetch_row($result)) {
         echo $array[0] . " should be connected to.<br>\n";
         }
      }

   function READ_FEILDS($table_name, $dB) {
/*
      THESE LINES TEST THE CONNECTION TO THE TABLE FIELDS
*/
      $result = mysql_query("SHOW COLUMNS FROM $table_name") or die("Connection to table's fields Failed!");
      while($array = mysql_fetch_assoc($result)) {
         echo "<B>Field $array[Field] was created.</B><BR>\n";
         }
      }

   function FILL_FEILDS($table_name, $value, $dB) {
/*
      THESE NEXT LINES INSERT TEST VALUES INTO THE BOARD
*/
      $SQL = "INSERT INTO $table_name VALUES ($value)";
      $result = mysql_query($SQL, $dB) or die("Failed to insert data to the row of table \"$table_name\"!");
      echo "Table \"$table_name\" was succesfuly inserted to for testing<BR>";
      }

   function REPORT_FEILD_CONTENTS($table_name, $dB) {
/*
      THESE NEXT LINES SHOW TEST VALUES FROM THE BOARD
*/
      $SQL = "SELECT * FROM $table_name";
      $result = mysql_query($SQL, $dB) or die("Failed to get data!");
      while ($array = mysql_fetch_array ($result)){
         $col = mysql_query("SHOW COLUMNS FROM $table_name") or die("Connection to table's fields Failed!");
         $array_number = 0;
         while($columns = mysql_fetch_assoc($col)) {
            echo "\"$array[$array_number]\" was posted in column \"$columns[Field].\"<br>\n";
               $array_number = $array_number +1;
            }
         }
      }

/*
      THESE NEXT LINES WRITE A FILE TO BE USED AS A CONFIGURATION FILE
*/
write_cnfgdata($_GET['HOST'], $_GET['USER'], $_GET['PASS'], $_GET['DB_NAME']);
?>
<BR>SETUP SCRIPT HAS COMPLETED.
<BR>THE ENITIRE SIGN SYTEM IS READY TO WORK
   </BODY>
</HTML>