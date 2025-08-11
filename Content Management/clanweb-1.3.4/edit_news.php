<?php
/********************************************************
								edit_news.php
								-------------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-03
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
      require ('auth.php');
	  require ('_inc/top.inc.php');

			// Content

        	$sql = "SELECT * 
					FROM " .$db_prefix. "news 
					WHERE id='".$_GET['id']."' LIMIT 1";
					
			$sql = $db->query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
			
				while ($read=$db->fetch_array($sql))
				{
					$id			= $read['id'];
					$nickname		= $read['nickname'];
					$topic		= $read['topic'];
					$newspost	= $read['newspost'];
					$newstype	= $read['newstype'];
					$dates		= $read['dates'];
				}
?>

<div class="welcome">

      <div align="left"><h3><?=$lang_edit_news?></h3></div>
   
      <form name="form2" method="post" action="save_edit_news.php?id=<?=$id?>">
        <b><?=$lang_name?></b>
              <input class="textfelt" value="<?=$nickname?>" type="hidden" name="nickname" size="40" maxlength="75">
							<?php echo"$nickname"; ?><br/>
        <b><?=$lang_topic?></b></td>
              <input class="textfelt" type="text" name="topic" size="40" maxlength="75" value="<?=$topic?>"> <?php include("$lang/select_edit.php"); /* Includes the menu file */ ?><br/>
        <b><?=$lang_enter_news?></b><br/>
        <?php
			 include("./FCKeditor/fckeditor.php") ;
            
            $oFCKeditor = new FCKeditor('newspost') ;
            $oFCKeditor->BasePath = './FCKeditor/' ;
            $oFCKeditor->Value		= ''.$newspost.'' ;
            $oFCKeditor->Create() ;
            ?>
        <b><?=$lang_date?></b><?=$dates?><br/>
              <input class="button" type="submit" name="sends" value="<?=$lang_edit_news?>">
      </form>

</div>
<?php
			// bottom.inc
			require ('_inc/bottom.inc.php');

?>
