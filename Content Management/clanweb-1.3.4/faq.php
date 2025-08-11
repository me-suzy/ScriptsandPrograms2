<?php
/********************************************************
								faq.php
								-----------
					$Copyright: (c) ClanAdmin Tools 2003, 2004
					$Last modified: 2004-07-14 by ArreliuS
					$email: cat@error-404.se

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
        require ('cfg.php');
 	      require ('auth.php');
 	      

				// top.inc 
				require ('_inc/top.inc.php');
  			require ("$lang/faq.php");
  			echo"<table cellspacing=\"2px\" cellpadding=\"0\" class=\"welcome\">\n<tr>
              <td>";
  			echo "<p><strong>".$faq['Installation']."</strong>\n";
  			echo "".$faq['Specs']."\n";
  			echo "<p><strong>".$faq['Misc']."</strong>\n";
  			echo "".$faq['How_to_add']."\n";
  			echo "".$faq['How_to_edit']."\n";
  			echo "".$faq['Who']."\n";
  			echo "".$faq['Purpose']."\n";
  			echo "<p><strong>".$faq['Bugreport']."</strong>";
  			echo "".$faq['What_to_do']."\n";
  			echo "".$faq['Mysql_prob']."\n";
  			echo "<p><strong>".$faq['Develope']."</strong>";
  			echo "".$faq['Idea']."\n";
  			echo"<br/>";
  			echo"</td></tr></table><br/>";
  			// bottom.inc
  			require ('_inc/bottom.inc.php');


?>
