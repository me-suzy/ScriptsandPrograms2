<?php
/********************************************************
								edit_spons.php
								-------------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-27
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
					FROM " .$db_prefix. "spons 
					WHERE id='".$_GET['id']."'";
					
			$sql = $db->query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
			
				while ($read=$db->fetch_array($sql))
				{
					$id			= $read['id'];
					$spons_name	= $read['spons_name'];
					$spons_cat  = $read['spons_cat'];
					$spons_info	= stripslashes($read['spons_info']);
				}
?>

<div class="welcome">

      <div align="left"><h3>Edit sponsors information</h3></div>
   
      <form name="form2" method="post" action="save_edit_spons.php?id=<?=$id?>">
        <b>Sponsors name</b><br/>
              <input class="textfelt" value="<?=$spons_name?>" type="text" name="spons_name" size="40" maxlength="75">
			<br/>
        <b>Sponsor info</b><br/>
        <?php
			 include("./FCKeditor/fckeditor.php") ;

            $oFCKeditor = new FCKeditor('spons_info') ;
            $oFCKeditor->BasePath = './FCKeditor/';
            $oFCKeditor->Value		= ''.$spons_info.'' ;
            $oFCKeditor->Create() ; 
            ?><br/>
              <input class="button" type="submit" name="sends" value="Edit sponsor info">
      </form>

</div>
<?php
			// bottom.inc
			require ('_inc/bottom.inc.php');

?>
