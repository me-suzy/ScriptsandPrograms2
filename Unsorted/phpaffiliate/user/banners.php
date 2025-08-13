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
    	Choose A Banner Or Link<br>
    	<a href=contact.php>Contact Us</a><br>";
    	
  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 18)"); 
  $result = mysql_db_query($database, "select * from banners ORDER BY name asc") or die ("Database INSERT Error (line 19"); 
		
  print "<br><br><font face=arial>Here are some links you may like to use: ";
  print "<br><br>";
  
  if (mysql_num_rows($result)) {
    print "<font face=arial><TABLE width=100% align=center>";
    while ($qry = mysql_fetch_array($result)) {
      print "<TR>";
      print "<TD width=10><font size=4>";
      print $qry[number];
      print "</TD>";
      print "<TD><font size=4 align=left>";
      print $qry[name];
      print "</TD></TR><TR>";
      print "<TD colspan=2 align=center><font size=4><center>";
      print "<a href=http://";
      print $domain;
      print "/index.php?ref=$valid_user>";
      print "<img src=http://";
      print $domain;
      print "/images/";
      print $qry[image];
      print "></a></TD>";
      print "</TR>";
      print "<tr><td colspan=2 align=center><center>";
      print "<textarea cols=60 rows=5><a href=http://";
      print $domain;
      print "/index.php?ref=$valid_user><img src=http://";
      print $domain;
      print "/images/";
      print $qry[image];
      print "></a></textarea>";
      print "</td></tr><tr><td><br><br></td><td></td></tr>";
      
    }
    print "</TABLE>";
    print "<br><br>";
    }
  }

   else
  {

    include "header.290"; 	  
	  print "<p>You are not logged in.</p>";
    echo "<p>Only logged in members may see this page.</p>";
  }

  
  include "footer.290"; 
?>

   