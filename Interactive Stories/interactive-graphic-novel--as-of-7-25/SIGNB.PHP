<?php
include 'classes/HEADER.PHP';
include 'CNFG.php';
$head = "EDIT STORY NODE ";
$head .= $_GET['NUM'];
myheader($head);
   $dB = @mysql_connect($host, $user, $pass) or die("Connection to server Failed!");
   $conn=mysql_select_db($database_name, $dB) or die("Connection to dB Failed!");
   $table_name="SIGN_TABLE";

   $name = 'empty';
   $story = 'empty';
   $chapter = $_GET['CHAPTER'];
   $num = $_GET['NUM'];
      $SQL = "SELECT * FROM $table_name";
      $result = mysql_query($SQL, $dB) or die("Failed to get data!");
      while ($array = mysql_fetch_array ($result)){
         if ($num == $array[0]) {
            $name = $array[2];
            $story = $array[3];
            }
         }

?>
   <FORM NAME='builder' METHOD='POST' ACTION='SEND_TO_DB.PHP'>
   <!--ONSUBMITION POST THE CHAPTER TO A CURENTLY -->

      <TABLE ALIGN='CENTER' BORDER=1 >
      <?
      include 'TAGS/TAG.php';
      button_block($dB, $chapter);
      ?>
        <TR>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
            echo "<INPUT TYPE='HIDDEN' NAME='NUM' VALUE='" . $num . "'>";
            echo "NODE NUMBER " . $num;
            echo "\n";
?>
            </TD>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
            echo "<INPUT TYPE='HIDDEN' NAME='CHAPTER' VALUE='" . $chapter . "'>";
            echo 'CHAPTER ' . $chapter;
            echo "\n";
?>
               
            </TD>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?  
             echo "<INPUT NAME='CHPTRNAME' TYPE='TEXT' VALUE='" . $name . "'></INPUT>\n";
?>
               <!-- FORCE NAME SELECTION-->
            </TD>
         </TR>


         <TR>
            <TD ALIGN='CENTER' COLSPAN=6>
<?
               echo "<TEXTAREA NAME='STORY' COLS=80 ROWS=25 WRAP>" . $story . "</TEXTAREA>";
?>
            </TD>
         </TR>

         <TR>
            <TD ALIGN='CENTER' COLSPAN=6>
               <INPUT TYPE='SUBMIT' VALUE='SEND TO DATABASE'></INPUT>
               <INPUT TYPE='RESET' VALUE='CLEAR FORM' ></INPUT>
            </TD>
         </TR>

      </TABLE>

   </FORM>

   </BODY>
</HTML>