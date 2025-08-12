<?php
include("checksession.php"); 
                                                                   
{ 
 //vars section 
	$userName = $_POST["userName"];
//end vars section

//END Database Connection Section
			$cur= mysql_query( "DELETE FROM ".$databasePrefix."accounts WHERE ID = $userName")
			or die("Invalid query: " . mysql_error());
			print ("ID $userName was removed from the Help Desk!");
			echo"<br>";
}
?>

<div align="center"><a href="DataAccess.php"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Click 
  Here to Return to the Help Desk</font></a></div>
