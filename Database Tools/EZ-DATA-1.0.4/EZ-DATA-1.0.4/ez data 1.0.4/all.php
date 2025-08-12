<?php
include ("includes/menu.inc");
?>

<html>
<head>
  <title>EZ-Data</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body background="images/bg.jpg" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<h1>All Records</h1>
<?
  


  @ $db = mysql_pconnect("localhost", "dzhyde", "circus1978");

  if (!$db)
  {
     echo "Error: Could not connect to database.  Please try again later.";
include ("includes/footer.inc");
     exit;
  }

  mysql_select_db("dzhyde_ezdata");
  $query = "select * from ez_data";
  $result = mysql_query($query);

  $num_results = mysql_num_rows($result);

  echo "<p>Total entries: ".$num_results."</p>";
  echo "<hr>";

  for ($i=0; $i <$num_results; $i++)
  {
     $row = mysql_fetch_array($result);
     echo "<p><strong># ";
     echo stripslashes($row["id"]);
	 echo "<p><strong>Name: ";
     echo stripslashes($row["name"]);
	 echo "<p><strong>Street: ";
     echo stripslashes($row["street"]);
	 echo "<p><strong>City: ";
     echo stripslashes($row["city"]);
	 echo "<p><strong>State: ";
     echo stripslashes($row["state"]);
	 echo "<p><strong>Zip Code: ";
     echo stripslashes($row["zip"]);
	 echo "<p><strong>Email: ";
     echo stripslashes($row["email"]);
      echo "<hr>";
     echo "</p>";
  }
include ("includes/footer.inc");
?>

</body>
</html>
