<?php
//	-----------------------------------------
// 	$File: motd.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-05-29
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

    require ('auth.php');
	require ('_inc/top.inc.php');
	
			$sql = $db->query("SELECT id, motd FROM " .$db_prefix. "motd 
							   WHERE id = '1' LIMIT 1");
			
			$read = $db->fetch_array($sql);
			
				$id = $read['id'];
				$motd = $read['motd'];
			
			  $sql = $db->query("SELECT user_id FROM " .$db_prefix. "online 
						  WHERE cookiesum = '".$_COOKIE['catcookie']."' LIMIT 1");
        	  $read = $db->fetch_array($sql);
        						  
              $sql = $db->query("SELECT username FROM " .$db_prefix. "users 
        						  WHERE id = '".$read['user_id']."' LIMIT 1");
        	  $read = $db->fetch_array($sql);
	// Content
?>
   
<div class="welcome">

      <div style="text-align:left"><h3><?=$lang_add_motd?></h3></div>
        <form name="form2" method="post" action="save_motd.php">
              <input class="textfelt" type="text" name="iusername" size="40" maxlength="75" value="<?php echo $read['username']; ?>" />
              <br />
              <?php
        			 include("./FCKeditor/fckeditor.php") ;
                    
                    $oFCKeditor = new FCKeditor('motd') ;
                    $oFCKeditor->BasePath = './FCKeditor/' ;
					$oFCKeditor->Value		= ''.$motd.'' ;
                    $oFCKeditor->Create() ;
            ?>
              <br />
			  <input class="button" type="submit" name="sends" value="<?=$lang_add_motd?>">
     	</form>      
      </div>
<?php
			// bottom.inc
			$db->close(); require ('_inc/bottom.inc.php');
			
?>

