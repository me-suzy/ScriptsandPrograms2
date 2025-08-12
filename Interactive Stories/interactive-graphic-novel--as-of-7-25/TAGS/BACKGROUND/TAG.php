<?
Function bacgrounds_list($dB)
{

?>
               <SELECT  NAME='background' onChange='backgrounding()'>
               <OPTION SELECTED >SELECT BACKGROUND</OPTION>
<?
$char_table = "BACKGROUND_TABLE";
$SQL = "SELECT * FROM $char_table";
$result = mysql_query($SQL, $dB) or die("Failed to get data!");
while ($array = mysql_fetch_array ($result)){
   $tring1 = $array[0];
   $tring2 = $array[1];
   echo "<OPTION VALUE='BG_" . $tring1 . "'>" . $tring2 . "</OPTION>" ;
   }


?>
               </SELECT>

<?

}
?>







<?
Function background_command($command, $atribute, $tag_use, $boot, $incriment_value, $CMD_count){
   if ($tag_use['background']){
      $boot = true;
      return ($incriment_value);
      }
   else {
      echo "document.all.set.background='BACKGROUND/" . $atribute . "';\n";
      $tag_use['background'] = true;
      return ($incriment_value + strlen($command) + strlen($atribute) + 3);
      }
   }
//reader function to be added later
?>