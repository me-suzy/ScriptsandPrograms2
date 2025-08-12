<?
Function ne_chap_list($dB, $chapter) {

?>
               <SELECT NAME='ne_chapter'  onChange='NE_CHAPTER()'>
               <OPTION SELECTED>SELECT NEXT CHAPTER</OPTION>
<?
      $table_name="SIGN_TABLE";
      $SQL = "SELECT * FROM $table_name";
      $result = mysql_query($SQL, $dB) or die("Failed to get data!");
      while ($array = mysql_fetch_array ($result)){
         $tring1 = $array[0];
         $tring2 = $array[2];
         echo "<OPTION VALUE='CH_" . $tring1 . "'>" . $tring2 . "</OPTION>" ;
         }
?>
               </SELECT>
<?

}
?>







<?
//reader function to be added later
?>