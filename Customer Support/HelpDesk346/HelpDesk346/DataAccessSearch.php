<?php
	$path = getcwd();
	chdir('..');
	
	include("checksession.php");
	
	chdir($path);
	
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
	
	
	HTML_Head();
?>
<title>Help Desk View Only My Past Calls</title>
<head><link href="style.css" rel="stylesheet" type="text/css"></head>
<body text="#000000" link="#0000FF" vlink="#0000FF">
<?	


if($contents == 'B')
		include 'dataaccessheader.php';
else
	include 'textnavsystem.php';
?> 

<br />

//$userName = $HTTP_COOKIE_VARS["usr"]; //Required
//$userName = $_GET["usr"];

include_once("config.php"); 

function Error_Handler( $msg, $cnx ) { 
    echo "$msg \n"; 
    mysql_close( $cnx); 
    exit(); 
} 


HTML_Head(); 

include_once ("class.Split.php3");
		
			
//vars section 
$ppage="";

$userName = $_COOKIE["record2"]; //Required

if(!empty($HTTP_GET_VARS))
{	
if ($HTTP_GET_VARS["orderby"] <> "")
	$orderby = $HTTP_GET_VARS["orderby"]; 
if (!empty($HTTP_GET_VARS["order"]))		
	$order = $HTTP_GET_VARS["order"];
}

if(empty($orderby))	 $orderby = "ID";
if(empty($order))    $order = "asc";

//END Vars Section

//MYSQL DataBase Connection Sectionrequire("config.php");
	   $cnx = mysql_connect($server,$database,$databasePassword); mysql_select_db($databaseName);		//This statement is required to select the database from the mysql server
	       if (!$cnx) 
           { 
               Error_handler( "Error: ".mysql_error()."" , $cnx ); 
		       echo "error connecting to the database";
		       mysql_close( $cnx); 
           } 
//END Database Connection Section
	
	$cur= mysql_query("SELECT ID,User,Pass,FirstName,LastName FROM ".$databasePrefix."accounts WHERE User ='$userName'" ) 
    or die("Invalid query: " . mysql_error());
    
   // fetch the succesive result rows 
    while( $row=mysql_fetch_row( $cur ) ) 
		{ 
        $FirstName= $row[3]; // get the field "FirstName" 
        $LastName= $row[4]; // get the field "LastName" 
		}
		
	   $fullName = "$FirstName $LastName";
	   print "<br>This report shows $fullName past help desk calls";

	   $sql="SELECT ID,FirstName, LastName,PCatagory,descrip,Status,Resolution,staff,mainDate,Priority FROM " . DB_PREFIX . "data WHERE Staff ='$userName' order by " . $orderby .  " " . $order ;  
       $arg["userName"]=$userName;
       $arg["order"]=$order;
       $arg["orderby"]=$orderby;
       if (!isset($_GET['ppage'])) $_GET['ppage'] = 1;
	   $ord=@new Split($sql,$result_page, $_GET['ppage'] ,"","",$arg);// Split records by 5 each
	   if($ord->totalrows !=0)
       { 
	    if ($order == "asc") 
              $order = "desc";
        else if ($order == "desc") 
	          $order = "asc";
   	    echo "<table border=1><tr><th><a href='DataAccessSearch.php?orderby=ID&order=" . $order . "&ppage=$ppage'>ID</a></th><th><a href='DataAccessSearch.php?orderby=mainDate&order=" . $order . "&ppage=$ppage'>Time & Date</a></th><th><a href='DataAccessSearch.php?orderby=FirstName&order=" . $order . "&ppage=$ppage'>First Name</a></th>". 
        "<th><a href='DataAccessSearch.php?orderby=LastName&order=" . $order . "&ppage=$ppage'>Last Name</a></th><th><a href='DataAccessSearch.php?orderby=Status&order=" . $order . "&ppage=$ppage'>Help Request Status</a></th><th><a href='DataAccessSearch.php?orderby=PCatagory&order=" . $order . "&ppage=$ppage'>Type of Problem</a></th><th><a href='DataAccessSearch.php?orderby=Priority&order=" . $order . "&ppage=$ppage'>Priority</a></th></tr>\n"; 
		
      // fetch the succesive result rows 
      while( $row=mysql_fetch_row( $ord->rows ) ) { 
        $ID= $row[0]; // get the field "Index" 
        $FirstName= $row[1]; // get the field "FirstName" 
        $LastName= $row[2]; // get the field "LastName" 
        $PCatagory= $row[3]; // get the field "PCatagory" 
		$value= $row[4]; // get the field "describe" <br><br>
		$Status= $row[5]; // get the field "Status" 
		$sysDate= $row[8]; // get the field "Status" 
		$priority= $row[9]; // get the field "Status" 
		if($priority=="")
		 $priority="&nbsp;";
		$url = 'viewDetails.php?record=';
		$url2 = "$ID";
		$url3 = "$url$url2"; 

if($Status=='New')
			{
			 $icon = '<img src="images/red.JPG" width="24" height="23">';
			 $color= 'red'; 
			};
			if($Status=='Open' || $Status=='open') 
			{
			$icon='<img src=images/yellow.JPG width="24" height="23">';
			$color='brown';
			};
			if($Status=='Closed'|| $Status=='Closed')
			{
			 $icon='<img src=images/green.JPG width="24" height="23">';
			 $color='green';
			};
if($req_image==0) $icon="";
				echo "<tr><td><a href=\"$url3\">$ID</a>\n</td><td>$sysDate</td><td>$FirstName</td>". 
				 "<td>$LastName</td><td>$icon&nbsp<font color='$color'>$Status</font></td><td>$PCatagory</td><td>$priority</td></tr>\n"; 
		 } 

            echo "<tr><td colspan=7 align=center><strong>$ord->totalrows entries</strong> </td></tr>"; 
			//Give links to next page
			echo "<tr><td colspan=7 align=right><font face=verdana size=2>$ord->back_link&nbsp;&nbsp;&nbsp;$ord->middle_link&nbsp;&nbsp;&nbsp;$ord->next_link&nbsp;&nbsp;&nbsp;</font></td></tr></table>";
	 }
			// close the connection. important if persistent connection are "On" 
	else//else no records found
	    echo "<div align=center><font size=2 color=#ff0000 face=verdana><strong>No Records Found</strong></font></div>";


HTML_Foot(); 

?>
<br>
<br>
<br>
<br>
<p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
  2005 Help Desk Reloaded<br>
  <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software for Tomorrows 
  Problem.</a></font></p>
