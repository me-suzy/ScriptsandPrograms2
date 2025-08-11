<?php
//	-----------------------------------------
// 	$File: add_news.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-01-22
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------
	  require ('auth.php');
	  
	  require ('_inc/top.inc.php');

	// Content
?>
   	<div class="welcome">
      <h3><?=$lang_add_news?></h3>
      <form name="form2" method="post" action="save_news.php">
        <strong><?=$lang_name?></strong> 
              <input class="textfelt" type="hidden" name="name" size="40" maxlength="75" value="<?php echo"".$_COOKIE['catcookie'].""; ?>" />
			  <?php echo"".$_COOKIE['catcookie'].""; ?>
              <br />
		<strong><?=$lang_topic?></strong>
              <input class="textfelt" type="text" name="topic" size="40" maxlength="75" /> <?php include("$lang/select.php"); /* Includes the menu file */ ?>
        	  <br /> 
        <strong><?=$lang_enter_news?></strong><br />
        <?php
			 include("./FCKeditor/fckeditor.php") ;
            
            $oFCKeditor = new FCKeditor('newspost') ;
            $oFCKeditor->BasePath = './FCKeditor/' ;
            $oFCKeditor->Value		= '' ;
            $oFCKeditor->Create() ;
            ?>
            <br />
              <input class="button" type="submit" name="sends" value="<?=$lang_add_news?>" />
      </form>      
    <br />
    </div>
<?php
				// bottom
				require ('_inc/bottom.inc.php');

?>
