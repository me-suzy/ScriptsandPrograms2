<?php
include ("includes/menu.inc");
?>
<html>
<head>
  <title>EZ-Data Search Results</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body background="images/bg.jpg" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<h1>Search Results</h1>
<?
  if (!$searchtype || !$searchterm)
  {
     echo "You have not entered search details.  Please go back and try again.";

     exit;
  }
  
  $searchtype = addslashes($searchtype);
  $searchterm = addslashes($searchterm);

  include("includes/data1.inc");

 if (!$db)
  {
     echo "Error: Could not connect to database.  Please try again later.";

     exit;
  }

include("includes/data2.inc");  
  $query = "select * from ez_data where ".$searchtype." like '%".$searchterm."%'";
  $result = mysql_query($query);

  $num_results = mysql_num_rows($result);

  echo "<p>Number matches found: ".$num_results."</p>";
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

?>

</body>
</html>

<?php
include ("includes/footer.inc");
?>

