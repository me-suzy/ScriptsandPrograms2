<?
include("checksession.php"); 
?>
<p align="left"><a href="images/customer-support-remove-user.JPG"><img src="images/customer-support-remove-user.JPG" alt="Remove Help Desk User" width="461" height="223" border="0"></a></p>
<? include("head.php") ?>
<br>
<?php

function userOut() { 
 //vars section 

//end vars section

//MYSQL DataBase Connection Sectionrequire("config.php");
	   $cnx = mysql_connect($server,$database,$databasePassword); mysql_select_db($databaseName)		//This statement is required to select the database from the mysql server
	      or die("Invalid : " . mysql_error());
		  
 	    $cur= mysql_query("select ID,User from ".$databasePrefix."accounts")
	    or die("Invalid query: " . mysql_error());
//END Database Connection Section

//Database Work Section :: fetch the succesive result rows        
    while( $row=mysql_fetch_row( $cur ) ) 
		{ 
		$ID= $row[0]; // get the field "UserName" 
		$UserName= $row[1]; // get the field "UserName" 
		echo "<br> Help Desk Active User Account: ";
		echo $UserName;
		echo "   ::ID Tag: ";
		echo $ID;
		}

 
    if (!$cur) { 
        Error_handler( "Error: ".mysql_error()."" , $cnx ); 
		mysql_close( $cnx); 
    } 
	mysql_close( $cnx); 
}
userOut();
?>
<link href="style.css" rel="stylesheet" type="text/css">
<p>To Delete a Help Desk User Account enter the user name <strong>ID Tag</strong> 
  below and click on the delete button.</p>
<form name="form1" method="post" action="user-delete.php">
  <p>Delete UserName <strong>ID Tag</strong> 
    <input name="userName" type="text" id="userName">
  </p>
  <p>
    <input type="submit" name="Submit" value="Delete" class="button">
  </p>
</form>
<p>Return to the <a href="DataAccess.php">Help Desk Main Page</a></p>
