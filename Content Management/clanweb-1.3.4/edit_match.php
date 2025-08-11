<?php
/********************************************************
								edit_match.php
								--------------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-03
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
 	  require ('auth.php'); 	

				// top.inc
				require ('_inc/top.inc.php');
	
				$sql = "SELECT * 
						FROM " .$db_prefix. "game 
						WHERE id='".$_GET['id']."'";
						
				$sql = $db->query($sql) or exit('An error occured while retrieving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
				
				while ($read=$db->fetch_array($sql))
				{
					$id		= $read['id'];
					$team1	= $read['team1'];
					$team2	= $read['team2'];
					$type	= $read['type'];
					$point1	= $read['point1'];
					$point2	= $read['point2'];
					$report	= $read['report'];
					$lineup	= $read['lineup'];
					$map	= $read['map'];
					$dates	= $read['dates'];
				}

				// Content
?>
<div class="welcome">
      <div align="left"><?=$lang_edit_match?></div>
    
      <form name="form2" method="post" action="save_edit_match.php?id=<?=$id?>">
        <table class="table" width="105" border="0" cellspacing="0" cellpadding="0">
          <tr align="left" valign="top">
            <td width="27%"><b><?=$lang_team_one?></b></td>
            <td width="73%">
              <input class="textfelt" type="text" name="team1" style="width:150px;" maxlength="75" value="<?=$team1?>">
            </td>
          </tr>
		  <tr align="left" valign="top">
            <td width="27%"><b><?=$lang_team_two?></b></td>
            <td width="73%">
              <input class="textfelt" type="text" name="team2" style="width:150px;" maxlength="75" value="<?=$team2?>">
            </td>
          </tr>
          <tr align="left" valign="top">
            <td width="27%"><b><?=$lang_type?></b></td>
            <td width="73%" >
                <select name="type" class="textfelt" style="width:100px;">
                <option selected><?=$type?></option>
                <option>PCW</option>
                <option>ClanBase</option>
                <option>Cup</option>
              </select>
            </td>
          </tr>
		  <tr align="left" valign="top">
            <td width="27%"><b><?=$lang_point_one?></b></td>
            <td width="73%" >
              <input class="textfelt" type="text" name="point1" style="width:30px;" maxlength="75" value="<?=$point1?>">
            </td>
          </tr>
	<tr align="left" valign="top">
            <td width="27%"><b><?=$lang_point_two?></b></td>
            <td width="73%" >
              <input class="textfelt" type="text" name="point2" style="width:30px;" maxlength="75" value="<?=$point2?>">
            </td>
          </tr>
          <tr align="left" valign="top">
            <td width="27%"><b><?=$lang_map?></b></td>
            <td width="73%" >
              <input class="textfelt" type="text" name="map" style="width:300px;" maxlength="75" value="<?=$map?>">
            </td>
          </tr>
          <tr align="left" valign="top">
            <td width="27%"><b><?=$lang_lineup?></b></td>
            <td width="73%">
              <input class="textfelt" type="text" name="lineup" style="width:300px;" maxlength="75" value="<?=$lineup?>">
            </td>
          </tr>
          <tr align="left" valign="top">
            <td width="27%"><b><?=$lang_report?></b></td>
            <td width="73%" >
              <textarea class="textfelt" name="report" style="width:300px;" rows="7"><?=$report?></textarea>
            </td>
          </tr>
          <tr align="left" valign="top">
            <td width="27%"><b><?=$lang_date?></b></td>
            <td width="73%"><?=$dates?></td>
          </tr>
          <tr align="left" valign="top">
            <td width="27%" ></td>
            <td width="73%" >
              <input class="button" type="submit" name="sends" value="<?=$lang_edit_match?>">
              </td>
          </tr>
        </table>
      </form>
	  <?php include "$lang/help_match.php"; ?> 
</div>
<?php
				// bottom.inc
				require ('_inc/bottom.inc.php');

 ?>
