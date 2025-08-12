<?
Function unstage_button()
{
?>
               <INPUT TYPE='BUTTON' VALUE='UNSTAGE CHARACTER' onClick='unstage()'></INPUT>
<?
}
?>
<?
Function unstage_command($command, $tag_use, $boot, $incriment_value, $CMD_count){
   if ($tag_use['character']){
      $boot = true;
      return ($incriment_value);
      }
   else {
      echo "document.all.right.background='Characterxx.gif';\n";
      $tag_use['character'] = true;
      return ($incriment_value + strlen($command) + 2);
      }
   }

//reader function to be added later
?>