<?
  session_start();

  // check session variable

  if (session_is_registered("valid_user"))
  {
    
        include "header.290"; 
        include "../config.php"; 
        
      echo "<p><font face=arial>
    
    	<a href=details.php>Change Your Details</a><br>
    	View Your Click Throughs<br>
    	<a href=sales.php>View Your Sales</a><br>
    	<a href=banners.php>Choose A Banner Or Link</a><br>
    	<a href=contact.php>Contact Us</a><br>";
    	
  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 20)"); 
  $result = mysql_db_query($database, "select * from clickthroughs where refid = '$valid_user' ORDER BY date and time") or die ("Database INSERT Error"); 
		
  print "<br><br><font face=arial>Clickthroughs From Your Site: ";
  print mysql_num_rows($result);
  print "<br><br>";
  
  if (mysql_num_rows($result)) {
    print "<font face=arial><TABLE>";
    print "<TR><TH>Date</TH><TH>Time</TH>";
    print "<TH>Referred From</TH></TR>";
    while ($qry = mysql_fetch_array($result)) {
      print "<TR>";
      print "<TD><font size=2>";
      print $qry[date];
      print "</TD>";
      print "<TD><font size=2>";
      print $qry[time];
      print "</TD>";
      print "<TD><font size=2>";
      print $qry[refferalurl];
      print "</TD>";
      print "</TR>";
    }
    print "</TABLE>";
  }

      }
   else
  {
        include "header.290"; 
        echo "<p>Only logged in members may see this page.</p>";
  }

  include "footer.290"; 
  
?>
