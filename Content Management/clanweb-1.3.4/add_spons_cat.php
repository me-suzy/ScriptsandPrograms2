<?php
/********************************************************
								add_news.php
								-----------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-22 by ArreliuS
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
      <h3>Add sponsors category</h3>
      <form name="form2" method="post" action="save_spons_cat.php">
		<strong>Category name</strong>
              <input class="textfelt" type="text" name="spons_type" size="40" maxlength="75" />
        	  <br /> 
              <input class="button" type="submit" name="sends" value="Add category" />
      </form>      
    <br />
    </div>
<?php
				// bottom
				require ('_inc/bottom.inc.php');

?>
