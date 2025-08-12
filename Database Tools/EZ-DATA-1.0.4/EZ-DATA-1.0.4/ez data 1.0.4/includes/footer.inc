<?php
echo "<br>";
echo "<hr>";
include ('includes/data1.inc');
include ('includes/data2.inc');


?>

<div align="center">
  <p><a href="http://www.yourcodes.com"><font size="1">Powered by EZ-Data 1.0.4</font></a></p>
  <p><a href="http://www.yourcodes.com"><img src="images/ez_data.gif" width="88" height="31"></a></p>
</div>

<?php
echo "<center>";
echo ("<font size='1'>page generated in ");
$load = microtime();
print (number_format($load,2));
echo (" seconds.</font>");
echo "</center>";
include("includes/data1.inc");

 if (!$db)
  {
     echo "Error: Could not connect to database.  Please try again later.";

     exit;
  }

include ("includes/data1.inc");
include ("includes/data2.inc");

$query = "INSERT INTO ez_counter VALUES('')"; 
  $result = mysql_query($query);

include("includes/data2.inc");  
  $query = "select * from ez_counter";
  $result = mysql_query($query);

  $num_results = mysql_num_rows($result);

  echo "<center><font size='1'>Hits: ".$num_results."</font></center>";

?>

