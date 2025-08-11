<?php 
//	-----------------------------------------
// 	$File: browsedir.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-02-23
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

  require ('auth.php');
  require ('_inc/top.inc.php');
  
		echo"<div class=\"welcome\">\n";
				
          $current_dir = 'files';
          $dir = opendir($current_dir);
        
          echo "Current directory is: $current_dir<br/>";
          echo "Directory Listing:<br/><hr noshade size=1px />";
          while ($file = readdir($dir)) 
          {
              echo "<a href=\"$current_dir/$file\" target=\"_blank\">$file</a><br/>";
          }
          echo "<hr noshade size=1px /><br/>"; 
          closedir($dir); 
				
		echo"</div>";
		echo"<br/><br/>";

	// bottom.inc 
	require ('_inc/bottom.inc.php');
 
?>
