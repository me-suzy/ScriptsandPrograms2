<?php
//	-----------------------------------------
// 	$File: menu.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-05-29
// 	$email: info@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------
?>
<div class="menu">
   <a href="default.php" class="menu2" title="<?=$lang_main?>"><?=$lang_main?></a> 
  <a href="news.php" class="menu2" title="<?=$lang_news?>"><?=$lang_news?></a> 
  <a href="comments.php" class="menu2" title="<?=$lang_reported?>"><?=$lang_reported?></a> 
  <a href="crew.php" class="menu2" title="<?=$lang_crew?>"><?=$lang_crew?></a>
<?php 
	  $sql = $db->query("SELECT user_id FROM " .$db_prefix. "online 
						  WHERE cookiesum = '".$_COOKIE['catcookie']."' LIMIT 1");
	  $read = $db->fetch_array($sql);
						  
      $sql = $db->query("SELECT admin FROM " .$db_prefix. "users 
						  WHERE id = '".$read['user_id']."' LIMIT 1");
	  $read = $db->fetch_array($sql);
			if($read['admin'] == 1) { ?>
  <a href="admins.php" class="menu2" title="<?=$lang_admins?>"><?=$lang_admins?></a>
  <?php } ?>
  <a href="match.php" class="menu2" title="<?=$lang_match?>"><?=$lang_match?></a>
  <a href="sponsor.php" class="menu2" title="Sponsors">Sponsors</a>
  <img src="gfx/dots_white.gif" alt="" /> 
  <a href="upload.php" class="menu2" title="">Upload</a> 
  <a href="browsedir.php" class="menu2" title="<?=$lang_browse_files?>"><?=$lang_browse_files?></a>
  <a href="http://www.clanadmintools.com/?p=faq" class="menu2" target="_blank" title="<?=$lang_faq?>"><?=$lang_faq?></a>  
  <a href="motd.php" class="menu2" title="<?=$lang_motd?>"><?=$lang_motd?></a>
  <img src="gfx/dots_white.gif" alt="" /> 
  <a href="logout.php" class="menu2" title="<?=$lang_logout?>"><?=$lang_logout?></a>
  <img src="gfx/dots_white.gif" alt="" /> 
</div>
