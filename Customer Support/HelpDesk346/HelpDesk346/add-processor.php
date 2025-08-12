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
  <font size="2" face="Verdana, Arial, Helvetica, sans-serif">Account Setup Complete 
  <?php
  include("config.php"); 
  
  //vars section 
		$userName = $_POST["UserAccount"];
	    $firstName = $_POST["FirstName"];
		$lastName = $_POST["LastName"];
		$password = $_POST["password"];
		$hdPath = $_POST["pathToHelpDesk"];

		$userName2 = $_POST["UserAccount2"];
	    $firstName2 = $_POST["FirstName2"];
		$lastName2 = $_POST["LastName2"];
		$password2 = $_POST["password2"];
	    $hdPath2 = $_POST["pathToHelpDesk2"];
		
		$userName3 = $_POST["UserAccount3"];
	    $firstName3 = $_POST["FirstName3"];
		$lastName3 = $_POST["LastName3"];
		$password3 = $_POST["password3"];
		$hdPath3 = $_POST["pathToHelpDesk3"];
		
		$userName4 = $_POST["UserAccount4"];
	    $firstName4 = $_POST["FirstName4"];
		$lastName4 = $_POST["LastName4"];
		$password4 = $_POST["password4"];
		$hdPath4 = $_POST["pathToHelpDesk4"];
		
		$userName5 = $_POST["UserAccount5"];
	    $firstName5 = $_POST["FirstName5"];
		$lastName5 = $_POST["LastName5"];
		$password5 = $_POST["password5"];
		$hdPath5 = $_POST["pathToHelpDesk5"];
//END Vars Section


//MYSQL DataBase Connection Sectionrequire("config.php");
	   $cnx = mysql_connect($server,$database,$databasePassword); mysql_select_db($databaseName)		//This statement is required to select the database from the mysql server
	      or die("Invalid : " . mysql_error());
//END Database Connection Section
	
  if($userName != null)
  {
      $SQL_query_String = "Insert Into ".$databasePrefix."accounts (User, Pass, FirstName, LastName, ComputerName, HelpDeskAddress)       
		                   Values ('$userName', '$password', '$firstName', '$lastName', 'DemoComputerName', '$hdPath')"; 
    $cur= mysql_query($SQL_query_String )
	or die("Invalid : " . mysql_error());
  }
  
    if($userName2 != null)
  {
      $SQL_query_String = "Insert Into ".$databasePrefix."accounts (User, Pass, FirstName, LastName, ComputerName, HelpDeskAddress)       
		                   Values ('$userName2', '$password2', '$firstName2', '$lastName2', 'DemoComputerName', '$hdPath2')"; 
    $cur= mysql_query($SQL_query_String )
	or die("Invalid : " . mysql_error());
  }
    if($userName3 != null)
  {
      $SQL_query_String = "Insert Into accounts (User, Pass, FirstName, LastName, ComputerName, HelpDeskAddress)       
		                   Values ('$userName3', '$password3', '$firstName3', '$lastName3', 'DemoComputerName', '$hdPath3')"; 
    $cur= mysql_query($SQL_query_String )
	or die("Invalid : " . mysql_error());
  }
    if($userName4 != null)
  {
      $SQL_query_String = "Insert Into ".$databasePrefix."accounts (User, Pass, FirstName, LastName, ComputerName, HelpDeskAddress)       
		                   Values ('$userName4', '$password4', '$firstName4', '$lastName4', 'DemoComputerName', '$hdPath4')"; 
    $cur= mysql_query($SQL_query_String )
	or die("Invalid : " . mysql_error());
  }
    if($userName5 != null)
  {
      $SQL_query_String = "Insert Into ".$databasePrefix."accounts (User, Pass, FirstName, LastName, ComputerName, HelpDeskAddress)       
		                   Values ('$userName5', '$password5', '$firstName5', '$lastName5', 'DemoComputerName', '$hdPath5')"; 
    $cur= mysql_query($SQL_query_String )
	or die("Invalid : " . mysql_error());
  }
mysql_close( $cnx); 
?>
  </font></p>
<p>&nbsp;</p>
<p align="center"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;"></p>
