<?
Function char_list($dB)
{

?>
               <SELECT  NAME='chater' onChange='character()'>
               <OPTION SELECTED >SELECT CHARACTER</OPTION>
<?
$char_table = "CHARACTER_TABLE";
$SQL = "SELECT * FROM $char_table";
$result = mysql_query($SQL, $dB) or die("Failed to get data!");
while ($array = mysql_fetch_array ($result)){
   $tring1 = $array[0];
   $tring2 = $array[1];
   echo "<OPTION VALUE='CHAR_" . $tring1 . "'>" . $tring2 . "</OPTION>" ;
   }


?>
               </SELECT>
<?

}
?>







<?
Function character_command($command, $atribute, $tag_use, $boot, $incriment_value, $CMD_count){
   if ($tag_use['character']){
      $boot = true;
      return ($incriment_value);
      }
   else {
      echo "document.all.right.background='CHARACTER/" . $atribute . "';\n";
      $tag_use['character'] = true;
      return ($incriment_value + strlen($command) + strlen($atribute) + 3);
     }
   }
//reader function to be added later
?>