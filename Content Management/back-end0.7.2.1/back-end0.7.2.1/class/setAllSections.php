<?php
   $blocks = "";
   $blocks[0] = "1";
   $blocks[1] = "166";
   $blocks[2] = "150";
   $blocks[3] = "169";
   $blocks[4] = "152";
   $blocks[5] = "153";
   $blocks[6] = "154";
   $blocks[7] = "159";
   $blocks[8] = "157";
   $blocks[9] = "160";
   $blocks[10] = "161";
   $blocks[11] = "168";
   $blocks[12] = "170";
   $blocks[13] = "172";
   $blocks[14] = "171";
   $blocks[15] = "173";
   require_once('../public_html/config.php');
   $db = pslNew('BEDB');
   $insert = pslNew('BEDB');
   $query = "SELECT sectionID FROM be_sections";
   $db->query($query);
   while ($db->next_record()) {
      $sectionID = $db->Record['sectionID'];
      $query = "DELETE FROM psl_section_block_lut WHERE section_id = '$sectionID'";
      $insert->query($query);
      foreach($blocks as $key => $blockID) {
         $lutID = generateID("psl_section_block_lut_seq");
         $query = "INSERT INTO psl_section_block_lut(lut_id,block_id,section_id) values('$lutID','$blockID','$sectionID')";
         $insert->query($query);
      }
   }
?>
