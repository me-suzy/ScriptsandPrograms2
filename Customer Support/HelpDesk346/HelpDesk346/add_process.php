<?
include("checksession.php"); 
?>
<p><img src="images/help-desk-account-managment.jpg" alt="Help Desk Account Managment" width="594" height="176" border="0" usemap="#Map2"> 
  <map name="Map2">
    <area shape="rect" coords="4,130,70,176" href="reportproblem.php">
    <area shape="rect" coords="80,128,159,174" href="helpDeskAccessAllCalls.php">
    <area shape="rect" coords="173,129,274,173" href="DataAccessSearch.php">
    <area shape="rect" coords="292,126,375,177" href="ocm-first.php">
    <area shape="rect" coords="384,128,447,174" href="search.php">
    <area shape="rect" coords="454,128,544,169" href="DataAccess.php">
  </map>
  <br>
  <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> Account Setup Complete </font> 
  <?php
  include_once("config.php"); 
  
  //vars section 
		$userName = $_POST["UserAccount"];
	    $firstName = $_POST["FirstName"];
		$lastName = $_POST["LastName"];
		$password = $_POST["password"];
		$hdPath = $_POST["pathToHelpDesk"];

	//MYSQL DataBase Connection Sectionrequire("config.php");
	   $cnx = mysql_connect($server,$database,$databasePassword); mysql_select_db($databaseName)		//This statement is required to select the database from the mysql server
	      or die("Invalid : " . mysql_error());
//END Database Connection Section
	
  if($userName != null)
  {
$SQL_query_String = "Insert Into ".$databasePrefix."accounts (User, Pass, FirstName, LastName, ComputerName, HelpDeskAddress)Values('$userName','$password', '$firstName', '$lastName', 'DemoComputerName', '$hdPath')"; 
$cur= mysql_query($SQL_query_String ) or die("Invalid : " . mysql_error());
  }
      
mysql_close( $cnx); 
header("location:view_users.php");
exit;
?>
</p>
<p>&nbsp;</p>
<p align="center"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;"></p>
