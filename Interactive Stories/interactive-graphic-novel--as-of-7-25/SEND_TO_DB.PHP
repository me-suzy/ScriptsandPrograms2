
<?php
include 'classes/HEADER.PHP';
include 'CNFG.php';
$head = "Submit data to the database";
myheader($head);

   $dB = @mysql_connect($host, $user, $pass) or die("Connection to server Failed!");
   $conn=mysql_select_db($database_name, $dB) or die("Connection to dB Failed!");
   $table_name="SIGN_TABLE";

            $chapter = $_POST['CHAPTER'];
            $name = $_POST['CHPTRNAME'];
            $story = $_POST['STORY'];
            $num = $_POST['NUM'];

      $value = "$num, $chapter, '$name', '$story'"; 
      $SQL = "REPLACE INTO $table_name VALUES ($value)";
      $result = mysql_query($SQL, $dB) or die("Failed to insert data to the row of table \"$table_name\"!");

?>

      <TABLE ALIGN='CENTER' BORDER=1 >
         <TR>
            <TD ALIGN='RIGHT'>
<?          echo "CHAPTER ";
            echo $chapter;
            echo  ":";
            ?>
            </TD>
            <TD ALIGN='RIGHT'>
<?          echo "<I>";
            echo $name;
            echo "</I>";
            ?>
            </TD>
         </TR>
         <TR>
            <TD COLSPAN = 2>
            <TEXTAREA NAME='STORY' COLS=80 ROWS=25 WRAP>
<?
            echo $story;
            ?>
            </TEXTAREA>
            </TD>
         </TR>
         <TR>
            <TD COLSPAN = 2>
            THIS DATA HAS BEEN PUT IN RECORD
<?          echo $num;
?>
            </TD>
         </TR>
      </TABLE>
   <A HREF='ID_CHAPTER.PHP'><P ALIGN='CENTER'>SELECT CHAPTER TO EDIT</P></A>
   </BODY>
</HTML>