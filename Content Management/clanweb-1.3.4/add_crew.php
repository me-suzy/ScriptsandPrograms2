<?php
//	-----------------------------------------
// 	$File: add_crew.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2004-12-15
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

  	require ('auth.php');
	require ('_inc/top.inc.php');

  	// Content
?>
   <form name="form1" method="post" action="save_crew.php">
  <div class="welcome">
    <?=$lang_profile?> 
          <input class="textfelt" type="text" name="nickname" value="<?php echo "".$_COOKIE['catcookie'].""; ?>" /><br/>
	<?=$lang_picture?>
		  <input class="textfelt" type="text" name="picture" /> (100x100)

        <table class="table"  width="450" border="0" cellspacing="0" cellpadding="0">
          <tr valign="top" align="right"> 
            <td width="50%"><?=$lang_name?>: 
              <input class="textfelt" type="text" name="name">
            </td>
            <td width="50%"><?=$lang_quote?>: 
              <input class="textfelt" type="text" name="quote">
            </td>
          </tr>
          <tr valign="top" align="right"> 
            <td width="50%"><?=$lang_age?>: 
              <input class="textfelt" type="text" name="age">
            </td>
            <td width="50%"><?=$lang_work?>: 
              <input class="textfelt" type="text" name="work">
            </td>
          </tr>
          <tr valign="top" align="right"> 
            <td width="50%"><?=$lang_sex?> 
              <?php include("$lang/sex.php"); ?>
            </td>
            <td width="50%">&nbsp;</td>
          </tr>
          <tr valign="top" align="right"> 
            <td width="50%"><?=$lang_location?>: 
              <input class="textfelt" type="text" name="location">
            </td>
            <td width="50%"><?=$lang_position?>: 
              <?php include("$lang/position.php"); ?>
            </td>
          </tr>
        </table>
        <table class="table" width="450" border="0" cellspacing="0" cellpadding="0" >
          <tr valign="top"> 
            <td width="50%" align="right"><?=$lang_cpu?>: 
              <input class="textfelt" type="text" name="cpu">
            </td>
            <td width="50%" align="right"><?=$lang_mouse?>: 
              <input class="textfelt" type="text" name="mouse">
            </td>
          </tr>
          <tr valign="top"> 
            <td width="50%" align="right"><?=$lang_gfx?>: 
              <input class="textfelt" type="text" name="gfx">
            </td>
            <td width="50%" align="right"><?=$lang_mousepad?>: 
              <input class="textfelt" type="text" name="mousepad">
            </td>
          </tr>
          <tr valign="top"> 
            <td width="50%" align="right"><?=$lang_screen?>: 
              <input class="textfelt" type="text" name="screen">
            </td>
            <td width="50%" align="right"><?=$lang_resolution?>: 
              <input class="textfelt" type="text" name="resolution">
            </td>
          </tr>
		  <tr valign="top"> 
            <td width="50%" align="right"><?=$lang_memory?>: 
              <input class="textfelt" type="text" name="memory">
            </td>
            <td width="50%" align="right"><?=$lang_os?>: 
              <input class="textfelt" type="text" name="os">
            </td>
          </tr>
          <tr valign="top"> 
            <td width="50%" align="right"><?=$lang_hdd?>: 
              <input class="textfelt" type="text" name="hdd">
            </td>
            <td width="50%" align="right"><?=$lang_email?> 
              <input class="textfelt" type="text" name="mail">
            </td>
          </tr>
        </table>
              <div align="center"> 
                <input type="submit" class="button" name="Submit" value="<?=$lang_add_member?>">
              </div>

<?=$lang_publish_info?>
  </div>
</form>
<br/>
<?php
		// bottom.inc
   		require ('_inc/bottom.inc.php');

?>
