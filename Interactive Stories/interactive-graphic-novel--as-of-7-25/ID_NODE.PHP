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

/*
      THESE NEXT LINES SHOW TEST VALUES FROM THE BOARD
*/
      $SQL = "SELECT * FROM $table_name";
      $result = mysql_query($SQL, $dB) or die("Failed to get data!");
      echo "<TABLE BORDER=1 ALIGN='CENTER'>\n";
      echo "<TR>\n";
      echo "<TD COLSPAN=5 ALIGN='CENTER'>\n";
      echo "SELECT A CHAPTER NODE TO EDIT <BR><BR>\n";
      echo "</TD>\n";
      echo "</TR>\n";
      echo "<TR>\n";
      $columns = 0;
      while ($columns <5) {
         echo "<TD ALIGN='CENTER'>\n";      
         echo "<TABLE BORDER=1>\n";      
         $count = 0;
         while (($array = mysql_fetch_array ($result)) && ($count < 10)){
            if ($chapter == $array[1]) {
               echo "<TR>\n";
               echo "<TD ALIGN='CENTER'>\n";
               echo $link . $array[0] . "'>" . $array[2] . "</A><BR>";
               echo "</TD>\n";
               echo "</TR>\n";
               $count = $count + 1;
               }
            }
         echo "</TABLE>\n";
         echo "</TD>\n";      
         $count = 0;
         $columns = $columns + 1;
         }
      echo "</TR>\n";      
      echo "<TR>\n";      
      echo "<TD COLSPAN=5 ALIGN='CENTER'>\n";      
      echo "<A HREF='NEW_NODE.PHP?CHAPTER=" . $chapter .  "''>NEW CHAPTER NODE</A>";
      echo "</TD>\n";      
      echo "</TR>\n";      
      echo "</TABLE>\n";


?>
   </BODY>
</HTML>