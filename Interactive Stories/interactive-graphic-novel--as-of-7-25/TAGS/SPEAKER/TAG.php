<?
Function char_SPEAK_list($dB)
{

?>
               <SELECT NAME='CHTK'  onChange='character_SPEAK()'>
               <OPTION SELECTED>SELECT TALKING CHARACTER</OPTION>
<?
$char_table = "SPEAKER_TABLE";
$SQL = "SELECT * FROM $char_table";
$result = mysql_query($SQL, $dB) or die("Failed to get data!");
while ($array = mysql_fetch_array ($result)){
   $tring1 = $array[0];
   $tring2 = $array[1];
   echo "<OPTION VALUE='CSPK_" . $tring1 . "'>" . $tring2 . "</OPTION>" ;
   }


?>
               </SELECT>
<?

}
?>







<?
Function speaker_command($command, $atribute, $tag_use, $boot, $incriment_value, $CMD_count){
   if ($tag_use['speaker']){
      $boot = true;
      return ($incriment_value);
      }
   else {
      echo "document.images['character'].src = 'SPEAKER/" . $atribute . "';\n";
      $tag_use['speaker'] = true;
      return ($incriment_value + strlen($command) + strlen($atribute) + 3);
      }
   }
//reader function to be added later
?>