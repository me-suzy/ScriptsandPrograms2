<?php
include 'classes/HEADER.PHP';
include 'CNFG.php';
$head = "ID NODE FOR CHAPTER ";
$head .= $_GET['CHAPTER'];
myheader($head);

   $dB = @mysql_connect($host, $user, $pass) or die("Connection to server Failed!");
   $conn=mysql_select_db($database_name, $dB) or die("Connection to dB Failed!");
   $table_name="SIGN_TABLE";

   $chapter = $_GET['CHAPTER'];
echo "<INPUT TYPE='HIDDEN' NAME='CHAPTER' VALUE='";
echo $chapter;
echo "'></INPUT>";
?>

PLACE LINK TO CREATE NODE NUMBER FOR NEW NODE AS WELL AS PLACE THE CHAPTER VALUE IN THE TOP<BR>
<?

$link = "<A HREF='SIGNB.PHP?CHAPTER=";
$link .= $chapter;
$link .= '&NUM=';

      $high_node = 0;
      $SQL = "SELECT * FROM $table_name";
      $result = mysql_query($SQL, $dB) or die("Failed to get data!");
      while ($array = mysql_fetch_array ($result)){
         if ($high_node < $array[0]) {
            $high_node = $array[0];
            }
         }
      $high_node = $high_node + 1;
      $value = "$high_node, $chapter, 'NEW CHAPTER', 'NEW CHAPTER TEXT SHOULD BE PLACED IN HERE'"; 
      $SQL = "INSERT INTO $table_name VALUES ($value)";
      $result = mysql_query($SQL, $dB) or die("Failed to insert data to the row of table \"$table_name\"!");
      echo $link . $high_node . "'>CHAPTER " . $chapter . " NEW NODE</A><BR>";




?>
   </BODY>
</HTML>