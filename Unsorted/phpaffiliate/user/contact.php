<?
  session_start();

  // check session variable

  if (session_is_registered("valid_user"))
  {
        include "header.290"; 
        include "../config.php"; 
      echo "<p><font face=arial>
    
    	<a href=details.php>Change Your Details</a><br>
    	<a href=clicks.php>View Your Click Throughs</a><br>
    	<a href=sales.php>View Your Sales</a><br>
    	<a href=banners.php>Choose A Banner Or Link</a><br>
    	Contact Us<br>";
   
    	
    print "<p><br>Feel free to contact us at anytime by emailing us at <a href=mailto:";
    print $emailinfo;
    print ">";
    print $emailinfo;
    print "</a>.";
    	 	
  }  	
   else
  {
     include "header.290"; 
    echo "<p>Only logged in members may see this page.</p>";
  }

  


    include "footer.290"; 
    
    ?>