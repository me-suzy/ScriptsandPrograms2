<?
include 'ACTION/TAG.php';
include 'SPEAKER/TAG.php';
include 'CLEAR_BG/TAG.php';
include 'CHARACTER/TAG.php';
include 'BACKGROUND/TAG.php';
include 'NEXT_CHAPTER/TAG.php';
include 'ANY_CHAPTER/TAG.php';

Function button_block($dB, $chapter) {
?>
         <TR>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
               action_button();
               ?>
               <SCRIPT LANGUAGE='JAVASCRIPT' TYPE='text/javascript' SRC='TAGS/ACTION/TAG.js'>
               </SCRIPT>
            </TD>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
               char_list($dB);
               ?>
               <SCRIPT LANGUAGE='JAVASCRIPT' TYPE='text/javascript' SRC='TAGS/CHARACTER/TAG.js'>
               </SCRIPT>
            </TD>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
               nxt_chap_list($dB, $chapter);
               ?>
               <SCRIPT LANGUAGE='JAVASCRIPT' TYPE='text/javascript' SRC='TAGS/NEXT_CHAPTER/TAG.js'>
               </SCRIPT>
               <!-- ONCHANGE PUT A [CH_VALUE] TAG IN THE BODY-->
            </TD>
         </TR>

         <TR>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
               unstage_button($dB);
               ?>
               <SCRIPT LANGUAGE='JAVASCRIPT' TYPE='text/javascript' SRC='TAGS/CLEAR_BG/TAG.js'>
               </SCRIPT>
            </TD>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
               char_SPEAK_list($dB);
               ?>
               <SCRIPT LANGUAGE='JAVASCRIPT' TYPE='text/javascript' SRC='TAGS/SPEAKER/TAG.js'>
               </SCRIPT>
            </TD>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
               ne_chap_list($dB, $chapter);
               ?>
               <SCRIPT LANGUAGE='JAVASCRIPT' TYPE='text/javascript' SRC='TAGS/ANY_CHAPTER/TAG.js'>
               </SCRIPT>
               <!-- ONCHANGE PUT A [CH_VALUE] TAG IN THE BODY-->
            </TD>
         </TR>

         <TR>
            <TD ALIGN='RIGHT' COLSPAN=2>
<?
               bacgrounds_list($dB);
               ?>
               <SCRIPT LANGUAGE='JAVASCRIPT' TYPE='text/javascript' SRC='TAGS/BACKGROUND/TAG.js'>
               </SCRIPT>
            </TD>
            <TD ALIGN='RIGHT' COLSPAN=2>
            <!--BLANK-->
            </TD>
            <TD ALIGN='RIGHT' COLSPAN=2>
            <!--BLANK-->
            </TD>
         </TR>
<?
}
Function command_processor($command, $atribute, $tag_use, $boot, $incriment_value, $CMD_count) {
   strtoupper($command);
   if (strcmp($command, 'ACTION') == 0){
      return (action_command($command, $tag_use, $boot, $incriment_value, $CMD_count) - 1);
      }
   else if (strcmp($command, 'CSPK') == 0){
      return (speaker_command($command, $atribute, $tag_use, $boot, $incriment_value, $CMD_count) - 1);
      }
   else if (strcmp($command, 'UNSTAGE') == 0){
      return (unstage_command($command, $tag_use, $boot, $incriment_value, $CMD_count) - 1);
      }
   else if (strcmp($command, 'CHAR') == 0){
      return (character_command($command, $atribute, $tag_use, $boot, $incriment_value, $CMD_count) - 1);
      }
   else if (strcmp($command, 'BG') == 0){
      return (background_command($command, $atribute, $tag_use, $boot, $incriment_value, $CMD_count) - 1);
      }
   else {
      return ($CMD_count);
      }
   }   
?>
