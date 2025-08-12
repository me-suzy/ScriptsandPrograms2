<?
Function nxt_chap_list($dB, $chapter) {

?>
               <SELECT NAME='next_chapter'  onChange='NXT_CHAPTER()'>
               <OPTION SELECTED>SELECT NEXT CHAPTER</OPTION>
<?
      $table_name="SIGN_TABLE";
      $SQL = "SELECT * FROM $table_name";
      $result = mysql_query($SQL, $dB) or die("Failed to get data!");
      while ($array = mysql_fetch_array ($result)){
         if ($array[1] == ($chapter + 1)) {
            $tring1 = $array[0];
            $tring2 = $array[2];
            echo "<OPTION VALUE='CHAP_" . $tring1 . "'>" . $tring2 . "</OPTION>" ;
            }
         }
?>
               </SELECT>
<?

}
?>







<?
//reader function to be added later
?>