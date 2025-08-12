<?	include("checksession.php");
	?>
<title>Help Desk View All Calls</title>
<head><link href="style.css" rel="stylesheet" type="text/css"></head>
<body link="#0000FF" vlink="#0000FF">
<p>
  <?
			
if($contents == "B")
	include_once('dataaccessheader.php');
else
//if its not banner it must be text.		
include 'textnavsystem.php';
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

//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

function HTML_Head() { 
    echo " 
    <HTML><HEAD> 
    <TITLE>Processing Form</TITLE> 
    </HEAD> 
    <BODY BGCOLOR=\"#FFFFFF\">"; 
	} 

function HTML_Foot() { 
    echo "</body></html>"; 
} 


function Error_Handler( $msg, $cnx ) { 
    echo "$msg \n"; 
    mysql_close( $cnx); 
    exit(); 
} 

HTML_Head();  
include_once ("class.Split.php3");//include split class to get links to next pages

//vars section 
$ppage = 1;
$nbrow="";
$priority ="";
//END Vars Section
if(!empty($HTTP_GET_VARS))
{	
if ($HTTP_GET_VARS["orderby"] <> "")
	$orderby = $HTTP_GET_VARS["orderby"];
if (!empty($HTTP_GET_VARS["order"]))		
	$order = $HTTP_GET_VARS["order"];
}

if(empty($orderby))	 $orderby = "ID";
if(empty($order))    $order = "asc";


//MYSQL DataBase Connection Sectionrequire("config.php");
	   $cnx = mysql_connect($server,$database,$databasePassword); mysql_select_db($databaseName);		//This statement is required to select the database from the mysql server
	       if (!$cnx) 
           { 
               Error_handler( "Error: ".mysql_error()."" , $cnx ); 
		       echo "error connecting to the database";
		       mysql_close( $cnx); 
           } 
//END Database Connection Section

    // send a simple mysql query . returns an mysql cursor 
    $sql="select ID,FirstName,LastName,PCatagory,descrip, Status,mainDate from ".$databasePrefix."data order by " . $orderby .  " " . $order ;
	$arg["order"]=$order;
    $arg["orderby"]=$orderby;
    if (!isset($_GET['ppage'])) $_GET['ppage'] = 1;
    //this code is as bad as chad is at tekken
    
    $ord=@new Split($sql,$result_page,$_GET['ppage'],"","",$arg);// Split records by 5 each
   if($ord->totalrows !=0)//If there exists any record 
    {
	 if ($order == "asc") 
         $order = "desc";
    else if ($order == "desc") 
          $order = "asc";
    echo "<table border=1><tr><th><a href='helpDeskAccessAllCalls.php?orderby=ID&order=" . $order . "&ppage=$ppage'>ID</a></th><th><a href='helpDeskAccessAllCalls.php?orderby=mainDate&order=" . $order . "&ppage=$ppage'>Time & Date</a></th><th><a href='helpDeskAccessAllCalls.php?orderby=FirstName&order=" . $order . "&ppage=$ppage'>First Name</a></th>". 
        "<th><a href='helpDeskAccessAllCalls.php?orderby=LastName&order=" . $order . "&ppage=$ppage'>Last Name</a></th><th><a href='helpDeskAccessAllCalls.php?orderby=Status&order=" . $order . "&ppage=$ppage'>Help Request Status</a></th><th><a href='helpDeskAccessAllCalls.php?orderby=PCatagory&order=" . $order . "&ppage=$ppage'>Type of Problem</a></th></tr>\n"; 
	// fetch the succesive result rows 
    while( $row= mysql_fetch_array($ord->rows)) {
        $nbrow++; 
        $ID= $row[0]; // get the field "Index" 
        $FirstName= $row[1]; // get the field "FirstName" 
        $LastName= $row[2]; // get the field "LastName" 
        $PCatagory= $row[3]; // get the field "PCatagory" 
		$value= $row[4]; // get the field "describe" <br><br>
		$Status= $row[5]; // get the field "Status" 
		$sysDate= $row[6]; // get the field "Status" 
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

    echo "<tr><td colspan=6 align=center><strong>$ord->totalrows entries</strong> </td></tr>"; 
	//Give links to next page
	echo "<tr><td colspan=6 align=left><font face=verdana size=2>$ord->back_link&nbsp;&nbsp;&nbsp;$ord->middle_link&nbsp;&nbsp;&nbsp;$ord->next_link&nbsp;&nbsp;&nbsp;</font></td></tr></table>";
    }
    // close the connection. important if persistent connection are "On" 
else//else no records found
  echo "<div align=center><font size=2 color=#ff0000 face=verdana><strong>No Records Found</strong></font></div>";


HTML_Foot(); 

//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

?>
  <br>
  <br>
  <br>
  <br>
  <br>
</p>
<p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
  2005 Help Desk Reloaded<br>
  <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software for Tomorrows 
  Problem.</a></font></p>
