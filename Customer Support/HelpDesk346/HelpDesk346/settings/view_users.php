<?php
	//May 02, 2005 - 11:19p
	//Revised by Jason Farrell
	//Revision Number 1
	
	$path = getcwd();
	chdir('..');
	include("checksession.php");
	include_once "./includes/classes.php";
	include_once "./includes/settings.php";
	chdir($path);
	
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
?>
<title>Help Desk User Management Center</title>
<link href="style.css" rel="stylesheet" type="text/css">
<body link="#0000FF" vlink="#0000FF">
<p>
<?php
	$ppath = '../';
	if($OBJ->get('navigation') == "B")
		include_once('../dataaccessheader.php');
	else
		include '../textnavsystem.php';
?>
  
  <map name="Map">
    <area shape="rect" coords="543,151,611,195" href="DataAccess.php">
    <area shape="rect" coords="480,145,542,197" href="search.php">
     
    <area shape="rect" coords="280,146,362,194" href="actmgt.php">
    <area shape="rect" coords="189,146,277,196" href="ocm-first.php">
    <area shape="rect" coords="127,148,182,198" href="DataAccessSearch.php">
    <area shape="rect" coords="76,147,122,196" href="helpDeskAccessAllCalls.php">
    <area shape="rect" coords="2,147,74,199" href="reportproblem.htm">
    <area shape="rect" coords="163,2,248,14" href="DataAccess.php">
  </map>
  <br>
<?php 
	function HTML_Foot() {
	    echo "</body></html>"; 
	} 
	
	
	function Error_Handler( $msg, $cnx ) { 
	    echo "$msg \n"; 
	    mysql_close( $cnx); 
	    exit(); 
	} 
	
	//first step display the options - we will store the submodules in included files
	chdir('../');
	if (isset($_GET['selection'])) {
		switch ($_GET['selection'])
		{
			case 'addnew':
				include_once "./includes/userManage/addnew.php";
				break;
			case 'delete':
				include_once "./includes/userManage/delete.php";
				break;
			case 'passwd':
				include_once "./includes/userManage/passwd.php";
				break;
			case 'change':
				include_once "./includes/userManage/change.php";
				break;
		}
	}
	else {
		//nothing has been selected - dispaly list of command options
?>
	<b><a href="?selection=addnew">Add a New User</a></b><br/>
	<b><a href="?selection=delete">Delete Existing User</a></b><br/>
	<b><a href="?selection=passwd">Change Passwords</a></b><br/>
	<b><a href="?selection=change">Promote/Demote Users</a></b><br/>
<?php
	}
	chdir($path);
?>
  <br>
  <br>
  <br>
  <br>
<a href="../actmgt.php">Back to help desk control panel.</a><br>
  <a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></p>
<?php HTML_Foot(); mysql_close(); ?>
