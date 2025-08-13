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
    	View Your Sales<br>
    	<a href=banners.php>Choose A Banner Or Link</a><br>
    	<a href=contact.php>Contact Us</a><br>";
    	
  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 18)"); 
  $result = mysql_db_query($database, "select * from sales where refid = '$valid_user' ORDER BY date and time") or die ("Database INSERT Error"); 
		
  print "<br><br><font face=arial>Sales From Your Site: ";
  print mysql_num_rows($result);
  print "<br><br>";
  
  if (mysql_num_rows($result)) {
    print "<font face=arial><TABLE width=300>";
    print "<TR><TH><font align=left>Date</TH><TH><font align=left>Time</TH>";
    print "<TH><font align=left>You Earned</TH></TR>";
    while ($qry = mysql_fetch_array($result)) {
      print "<TR>";
      print "<TD><font size=3>";
      print $qry[date];
      print "</TD>";
      print "<TD><font size=3>";
      print $qry[time];
      print "</TD>";
      print "<TD><font size=3>";
      print $qry[payment];
      print " ";
      print $currency; 
      print "</TD>";
      print "</TR>";
    }
    print "</TABLE>";
    }
    print "<br><br>Your Total Earnings Are: ";
    mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 47)"); 
    $set = mysql_db_query($database, "select SUM(payment) AS total from sales where refid = '$valid_user'") or die ("Database INSERT Error (line 48)"); 
    $row = mysql_fetch_array( $set );
    print $row['total'];
	print " "; 
	print $currency; 
	print "<br><br>";
    }
  
      
   else
  {
            include "header.290"; 
    echo "<p>Only logged in members may see this page.</p>";
  }

 
          include "footer.290";  
?>
 