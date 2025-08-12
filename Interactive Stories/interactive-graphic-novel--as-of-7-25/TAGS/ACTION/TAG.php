<?
Function action_button()
{
?>
               <INPUT TYPE='BUTTON' VALUE='ACTION TEXT' onClick='actions()'></INPUT>
<?
}
?>
<?
Function action_command($command, $tag_use, $boot, $incriment_value, $CMD_count){
   if ($tag_use['speaker']){
      $boot = true;
      return ($incriment_value);
      }
   else {
      echo "document.images['character'].src = 'CharacterSpeaking.gif';\n";
      $tag_use['speaker'] = true;
      return ($incriment_value + strlen($command) + 2);
      }
   }
//reader function to be added later
?>