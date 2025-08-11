<?php
/********************************************************
								add_spons.php
								-----------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-25
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
	  require ('auth.php');
	  
	  require ('_inc/top.inc.php');

	// Content
?>
   	<div class="welcome">
      <h3>Add sponsor</h3>
      <form name="form2" method="post" action="save_spons.php">
      		<input type="hidden" name="spons_cat" value="<?php echo $_GET['scatID']; ?>" />
        <strong>Sponsor name</strong> 
              <input class="textfelt" type="text" name="spons_name" size="40" maxlength="75" />
        	  <br /> 
        <strong>Enter sponsor information</strong><br />
        <?php 
			 include("./FCKeditor/fckeditor.php") ;
            
            $oFCKeditor = new FCKeditor('spons_info') ;
            $oFCKeditor->BasePath = './FCKeditor/' ;
            $oFCKeditor->Value		= '' ;
            $oFCKeditor->Create() ; 
            ?>
            <br />
              <input class="button" type="submit" name="sends" value="Add sponsor" />
      </form>      
    <br />
    </div>
<?php
				// bottom
				require ('_inc/bottom.inc.php');

?>
