<?php
include 'classes/HEADER.PHP';
include 'CNFG.php';
include 'TAGS/TAG.php';
$head = "CHAPTER:  ";
   $dB = @mysql_connect($host, $user, $pass) or die("Connection to server Failed!");
   $conn=mysql_select_db($database_name, $dB) or die("Connection to dB Failed!");
   $table_name="SIGN_TABLE";
   echo "<!--";
   if ($_GET['NUM'] == NULL){
      $num = 0;
      }
   else
      {
      $num = $_GET['NUM'];
      }
   echo "-->";
      $SQL = "SELECT * FROM $table_name";
      $result = mysql_query($SQL, $dB) or die("Failed to get data!");
      while ($array = mysql_fetch_array ($result)){
         if ($num == $array[0]) {
            $name = $array[2];
            $story = $array[3];
            $story = str_replace("
", "", $story);
            }
         }

      $table_name = "linques";
      $SQL="drop table $table_name";                                        
      if ($result=mysql_query($SQL, $dB)) { // or die("Table deletion Failed!<BR>");
         echo " ";
         }
      $cnf = "num INT, name TEXT";
      $SQL="CREATE TABLE $table_name ($cnf)";                               
      $result=mysql_query($SQL, $dB) or die("Failed to create table \"$table_name\"!");
      echo "";






$head .= $name;
myheader($head);
echo "<SCRIPT LANGUAGE='JavaScript'>\n\n";
echo "function next(script) {\n";

$tag_use[] = 0;

function clear_tags($tag_use) {
   $tag_use['background'] = false;
   $tag_use['speaker'] = false;
   $tag_use['character'] = false;
   }

$js_incriment_value = 1;
$incriment_value = 0;
$story_end = false;
$choice = 0;


while (!($story_end)) {
   echo "if (script==" . $js_incriment_value . ") {\n";
   $boot = true;
   $piece = '';
   clear_tags($tag_use);

   while ($boot) {
      $single_char = substr($story, $incriment_value, 1);
      if ($incriment_value > (strlen($story))) {
         $boot = false;
         $story_end = true;
         }
      if ((strcmp($single_char, '.') == 0) || (strcmp($single_char, '?') == 0) || (strcmp($single_char, '!') == 0)) {
         $boot = false;
         $piece .= $single_char;
         }

      else if (strcmp($single_char, '[') == 0) {
         $command = '';
         $atribute = '';
         $CMD_count = $incriment_value;
         while (strcmp($single_char, ']') != 0) {
            $CMD_count = $CMD_count + 1;
            $single_char = substr($story, $CMD_count, 1);
            if (strcmp($single_char, '_') == 0) {
               while (strcmp($single_char, ']') != 0) {
                  $CMD_count = $CMD_count + 1;
                  $single_char = substr($story, $CMD_count, 1);
                  if (strcmp($single_char, ']') != 0){
                     $atribute .= $single_char;
                     }
                  }
               }
            if (strcmp($single_char, ']') != 0) {
               $command .= $single_char;
               }
            }
         $incriment_value = (command_processor($command, $atribute, $tag_use, $boot, $incriment_value, $CMD_count));
         }

      else {
         if (strcmp($single_char, '\'') == 0) {
            $single_char = "\'";
            }
         if (strcmp($single_char, '\"') == 0) {
            $single_char = '\"';
            }
         $piece .= $single_char;
         }
      $incriment_value = $incriment_value + 1;
      }
   $js_incriment_value = $js_incriment_value + 1;
   echo "document.anchors[0].href = 'javascript:next(" . $js_incriment_value . ")';\n";
   echo "document.all.dialog.innerHTML = '" . $piece . "';\n";
   $piece = '';
   echo "}\n";
   }

echo "if (script==" . $js_incriment_value . ") {\n";

echo "document.anchors[0].href = 'javascript:next(1)';\n";
echo "document.images['character'].src = 'CharacterSpeaking.gif';\n";
echo "document.all.right.background='Characterxx.gif';\n";
echo "}\n}\n";
echo "</SCRIPT>\n\n";
?>
<TABLE ALIGN='CENTER' CELLSPACING='0' CELLPADDING='0' border=1>
   <TR>
      <TD ID='set' background='Setxx.gif'>
      <TABLE VALIGN='BOTTOM' ALIGN='CENTER' CELLSPACING='0' CELLPADDING='0'>
         <TR>
            <TD ID='left' background='Characterxx.gif'>
             <IMG name='leftax' src='Acessory.gif'>
            </TD>
            <TD ID='right' background='Characterxx.gif'>
            <IMG name='rightax' src='Acessory.gif'>
            </TD>
         </TR>
      </TABLE>
      </TD>
   </TR>
   <TR>
      <TD >
      <TABLE ALIGN='LEFT' CELLSPACING='0' CELLPADDING='0' width=100% valign='bottom' >
         <TR>
            <TD >
            <IMG name='character' height=100 width=100 src='CharacterSpeaking.gif'>
            </TD>
            <TD width=100% VALIGN='Top'>
            <div ID="dialog"> </div>
            </TD>
            <TD VALIGN='Bottom'>
            <a name='button' href='javascript:next(1)'><IMG src='Button.gif'></a>
            </TD>
         </TR>
      </TABLE>
      </TD>
   </TR>
</TABLE>


</BODY>
</HTML>
