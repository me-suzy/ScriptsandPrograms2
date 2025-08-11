<?php
//	-----------------------------------------
// 	$File: add_match.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-02-21
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

      require ('auth.php');
 	  require ('_inc/top.inc.php');

	// Content
?>
<div class="welcome">

      <div align="left"><h3><?=$lang_add_match?></h3></div>

      <form name="form2" method="post" action="save_match.php">
        <table class="table" width="105" border="0" cellspacing="0" cellpadding="0">
          <tr align="left" valign="top"> 
            <td width="27%"><b><?=$lang_team_one?>:</b></td>
            <td width="73%" class="body"> 
              <input class="textfelt" type="text" name="team1" style="width:150px;" maxlength="75">
            </td>
          </tr>          
		  <tr align="left" valign="top"> 
            <td width="27%"><b><?=$lang_team_two?>:</b></td>
            <td width="73%" class="body"> 
              <input class="textfelt" type="text" name="team2" style="width:150px;" maxlength="75">
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="27%"><b><?=$lang_type?>:</b></td>
            <td width="73%" class="body"> 
              <select name="type" class="textfelt" style="width:100px;">
                <option selected>PCW</option>
                <option>ClanBase</option>
                <option>Cup</option>
              </select>
            </td>
          </tr>
	  <tr align="left" valign="top"> 
            <td width="27%"><b><?=$lang_point_one?>:</b></td>
            <td width="73%" class="body"> 
              <input class="textfelt" type="text" name="point1" style="width:30px;" maxlength="75">
            </td>
          </tr>
	  <tr align="left" valign="top"> 
            <td width="27%"><b><?=$lang_point_two?>:</b></td>
            <td width="73%" class="body"> 
              <input class="textfelt" type="text" name="point2" style="width:30px;" maxlength="75">
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="27%"><b><?=$lang_map?>:</b></td>
            <td width="73%" class="body"> 
              <input class="textfelt" type="text" name="map" style="width:300px;" maxlength="75">
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="27%"><b><?=$lang_lineup?>:</b></td>
            <td width="73%" > 
              <input class="textfelt" type="text" name="lineup" style="width:300px;" maxlength="75">
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="27%"><b><?=$lang_report?>:</b></td>
            <td width="73%" > 
              <textarea class="textfelt" name="report" style="width:300px;" rows="7"></textarea>
            </td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="27%" ></td>
            <td width="73%" >&nbsp;</td>
          </tr>
          <tr align="left" valign="top"> 
            <td width="27%" ></td>
            <td width="73%" >
              <input class="button" type="submit" name="sends" value="<?=$lang_add_match?>">
              </td>
          </tr>
        </table>
      </form>
      
<br/><br/> 
      <?php include "$lang/help_match.php";?>

</div>
<?php
				// bottom.inc

				require ('_inc/bottom.inc.php');

?>
