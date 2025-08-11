<?php
/********************************************************
								edit_member.php
								---------------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-24
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
      require ('auth.php');

	  require ('_inc/top.inc.php');
					

					$sql = "SELECT * 
							FROM " .$db_prefix. "members 
							WHERE id='".$_GET['id']."'";
									
					$query = $db->query($sql) or exit('An error occured while retrieving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
					while ($read = $db->fetch_array($query))
					{
						$id			=$read["id"];
						$name		=$read["name"];
						$nick		=$read["nickname"];
						$picture	=$read["picture"];
						$age		=$read["age"];
						$sex		=$read["sex"];
						$work		=$read["work"];
						$quote		=$read["quote"];
						$location	=$read["location"];
						$position	=$read["position"];
						$cpu		=$read["cpu"];
						$mouse		=$read["mouse"];
						$gfx		=$read["gfx"];
						$resolution	=$read["resolution"];
						$screen		=$read["screen"];
						$mousepad	=$read["mousepad"];
						$memory		=$read["memory"];
						$os			=$read["os"];
						$hdd		=$read["hdd"];
						$mail		=$read["mail"];
					}
?>
<form name="update_crew" method="post" action="update_crew.php?id=<?=$id?>">
  <div class="welcome">
        <div align="center"><?=$lang_profile?>
          <input class="textfelt" type="text" name="nickname" value="<?=$nick?>" /><br>
	<?=$lang_picture?>
		  <input class="textfelt" type="text" name="picture" value="<?=$picture?>"> (100x100)
        
        <table class="table" width="450" border="0" cellspacing="0" cellpadding="0">
          <tr valign="top" align="right">
            <td width="50%"><?=$lang_name?>:
              <input class="textfelt" type="text" name="name" value="<?=$name?>">
            </td>
            <td width="50%"><?=$lang_quote?>:
              <input class="textfelt" type="text" name="quote" value="<?=$quote?>">
            </td>
          </tr>
          <tr valign="top" align="right">
            <td width="50%"><?=$lang_age?>:
              <input class="textfelt" type="text" name="age" value="<?=$age?>">
            </td>
            <td width="50%"><?=$lang_work?>:
              <input class="textfelt" type="text" name="work" value="<?=$work?>">
            </td>
          </tr>
          <tr valign="top" align="right">
            <td width="50%"><?=$lang_sex?>
              <?php include("$lang/sex_edit.php"); ?>
            </td>
            <td width="50%">&nbsp;</td>
          </tr>
          <tr valign="top" align="right">
            <td width="50%"><?=$lang_location?>:
              <input class="textfelt" type="text" name="location" value="<?=$location?>">
            </td>
            <td width="50%"><?=$lang_work?>:
              <?php include("$lang/position_edit.php"); ?>
            </td>
          </tr>
        </table>
        <table class="table" width="450" border="0" cellspacing="0" cellpadding="0" >
          <tr valign="top">
            <td width="50%" align="right"><?=$lang_cpu?>:
              <input class="textfelt" type="text" name="cpu" value="<?=$cpu?>">
            </td>
            <td width="50%" align="right"><?=$lang_mouse?>:
              <input class="textfelt" type="text" name="mouse" value="<?=$mouse?>">
            </td>
          </tr>
          <tr valign="top">
            <td width="50%" align="right"><?=$lang_gfx?>:
              <input class="textfelt" type="text" name="gfx" value="<?=$gfx?>">
            </td>
            <td width="50%" align="right"><?=$lang_mousepad?>:
              <input class="textfelt" type="text" name="mousepad" value="<?=$mousepad?>">
            </td>
          </tr>
          <tr valign="top">
            <td width="50%" align="right"><?=$lang_screen?>:
              <input class="textfelt" type="text" name="screen" value="<?=$screen?>">
            </td>
            <td width="50%" align="right"><?=$lang_resolution?>:
              <input class="textfelt" type="text" name="resolution" value="<?=$resolution?>">
            </td>
          </tr>
 		  <tr valign="top">
            <td width="50%" align="right"><?=$lang_memory?>:
              <input class="textfelt" type="text" name="memory" value="<?=$memory?>">
            </td>
            <td width="50%" align="right"><?=$lang_os?>:
              <input class="textfelt" type="text" name="os" value="<?=$os?>">
            </td>
          </tr>
          <tr valign="top">
            <td width="50%" align="right"><?=$lang_hdd?>
              <input class="textfelt" type="text" name="hdd" value="<?=$hdd?>"></td>
            <td width="50%" align="right"><?=$lang_email?>
              <input class="textfelt" type="text" name="mail" value="<?=$mail?>">
            </td>
          </tr>
        </table>
        
              <div align="center">
                <input type="submit" class="button" name="Submit" value="<?=$lang_edit_member?>">
              </div>
			<br/><br/>
        <td><?=$lang_publish_info?></td>
  </div>
</form>
<br/>
<?php
				 require ('_inc/bottom.inc.php');

?>
