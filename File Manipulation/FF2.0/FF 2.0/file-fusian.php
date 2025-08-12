<?php
# ---------------------------------------------------------------------
# eFusian
# Copyright (C) 2002 by the Fusian Development Team.
# http://www.efusian.co.uk
# ---------------------------------------------------------------------
# Author:              Oliver James Ibbotson Esq.
# Contact:             mail@efusian.co.uk
# Date:                21st November 2002
# ---------------------------------------------------------------------
# Script Name:         File-Fusian
# Script Version:      2.0.1
#
# Description:         This script uses a form to upload to a 
#                      designated folder on the web server.
#
# Revision History:
# ---------------------------------------------------------------------
?>



<?php

require ('config.php');										# Include configuration file.
require ('common.php');										# Include common file with functions in.

?>


<html>
<head><title>..:: File-Fusian v2.0.0 ::..</title>

<?php

echo("<link href='$skin' rel='stylesheet' type='text/css' />");

?>

</head>





<body>


<!--- HTML Submission Form Section --->
 
<!--- Master Table Start --->

<table class="main-table">
    
  <tr>
  <td class="main-table">
  
  
  <table class="logo-table">
  
  <tr>
  <td class="logo-table">
  
  <?php
  
  echo "<img src='$logo_file'>";

  ?>
  
  </tr>
  </td>
  
  </table>
  
  
  <table class="nav-table">
  
  <tr>
  <td class="nav-table">
  
  <span id="link" onclick="javascript: void(window.open('common.php?id=dirlist','','width=480,height=700,toolbar=no,menubar=no'));"> Directory Listing</span>  ::  <span id="link" onclick="javascript: void(window.open('http://www.efusian.co.uk/warehouse/bugtraq','','width=455,height=600,toolbar=no,menubar=no'));">Report Bug</span>  ::  <a href="http://www.efusian.co.uk/forum" target="_blank">FileFusian Forum</a>
  
  </td>
  </tr>
  
  </table>  
  
  
  
  <table class="upform-table">
  
  <tr>
  <td class="upform-table">
        
  <!--- Actual Form --->
  
  <form enctype="multipart/form-data" action"<?php print $PHP_SELF ?>" method="POST">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo ($max_file_size); ?>">
  <input type="file" name="fupload">
  <br><br>
  <input type="submit" value="Upload File">
  <br>
  </form>
  
  <!--- End Form --->
  
  </td>
  </tr>
  
  </table>
  
  
  <table class="base-table">
  
  <tr>
  <td class="base-table">
  
  <?php
  
  $fileaccept = "";
  if($filetype1 != "NULL")
  {
			
  $fileaccept = $fileaccept . $filetype1;
					
  }
		
  if($filetype2 != "NULL")
  {
			
  $fileaccept = $fileaccept . ", " . $filetype2;	
					
  }
		
  if($filetype3 != "NULL")
  {
			
  $fileaccept = $fileaccept . ", " . $filetype3;
			
  }
		
  if($filetype4 != "NULL")
  {
			
  $fileaccept = $fileaccept . ", " . $filetype4;
						
  }
		
  if($filetype5 != "NULL")
  {
			
  $fileaccept = $fileaccept . ", " . $filetype5;
						
  }
  
  
  $upload_size = $max_file_size/1024;
  
  $upload_size = round($upload_size, 1);
  
  
  echo ("Your Current IP Address Is:  <font color='FF3300'> $REMOTE_ADDR </font><a href='$logfile' target='_blank'> (View Log)</a>");
  echo ("<br>");
  echo ("Maximum Allowed File Size:  $upload_size KB");
  echo ("<br>");
  echo ("Allowed File Types: $fileaccept");
  
  
  ?>
  
  </td>
  </tr>
  
  </table>
  
        
        
  </td>
  </tr>
      
</table>
    
<!--- Master Table End --->


</body>
</html>