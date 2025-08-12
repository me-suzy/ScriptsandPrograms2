<?php 
include("checksession.php"); 
$sysDate= date("h:i  M d Y", mktime());
function HTML_Head() 
		{ 
    echo " 
    <HTML><HEAD> 
    <TITLE>Property Report</TITLE> 
    </HEAD> 
    <BODY BGCOLOR=\"#FFFFFF\"><br><br>"; 
	echo "  <left><p><a href=ocm-first.php>Property Managment Main Page</a></p>
  <br><strong><font size=2 face=Verdana, Arial, Helvetica, sans-serif>O.C.M. Excess 
    I.T. Equipment Report</font></strong></p></left>";
		}
		


function HTML_Foot() 
	{ 
    echo "</body></html>"; 
	} 

function Error_Handler( $msg, $cnx ) 
	{ 
	echo "$msg \n"; 
    mysql_close( $cnx); 
    exit(); 
	} 

HTML_Head(); 

if(!empty($HTTP_GET_VARS))
{	
if ($HTTP_GET_VARS["orderby"] <> "")
	$orderby = $HTTP_GET_VARS["orderby"];
if (!empty($HTTP_GET_VARS["order"]))		
	$order = $HTTP_GET_VARS["order"];
}

if(empty($orderby))	 $orderby = "ID";
if(empty($order))    $order = "asc";

	//vars section 
//END Vars Section
   
//END Database Connection Section

    // send a simple mysql query . returns an mysql cursor 
    $cur= mysql_query( "select ID,FirstName,LastName,partNum,serial,location,descrip,Date,price from " . DB_PREFIX . "excess order by " . $orderby .  " " . $order ) 
        or die("Invalid : " . mysql_error());
  
    $nbrow=0;   //Local variable to count number of rows 
    if(mysql_num_rows($cur))
    {
    if ($order == "asc") 
        $order = "desc";
    else if ($order == "desc") 
	         $order = "asc";
    echo "<table border=1><tr><th><a href='OCMreport.php?orderby=ID&order=$order'>ID</a></th><th><a href='OCMreport.php?orderby=FirstName&order=$order'>Excess Procedure Requested by</a></th><th><a href='OCMreport.php?orderby=partNum&order=$order'>Part Number</a></th><th><a href='OCMreport.php?orderby=serial&order=$order'>Serial Number</a> </th><th><a href='OCMreport.php?orderby=Date&order=$order'>Date</a></th><th><a href='OCMreport.php?orderby=location&order=$order'>location<a></th><th><a href='OCMreport.php?orderby=descrip&order=$order'>Description</a><th><a href='OCMreport.php?orderby=price&order=$order'>Price</a></th><td>Delete ?</td></tr>\n"; 
		
    while( $row=mysql_fetch_row( $cur ))
		{
        $ID= $row[0]; // get the field "Index" 
        $FirstName= $row[1]; // get the field "FirstName" 
        $LastName= $row[2]; // get the field "LastName" 
        $PartNum= $row[3]; // get the field "PCatagory" 
		$serial= $row[4]; // get the field "describe" 
		$location= $row[5]; // get the field "date" 
		$description= $row[6]; // get the field "date" 
		$Date= $row[7]; // get the field "date" 
		$price =$row[8]; // get the price field.
		$nbrow++; 
 		echo "<tr><td>$ID</a>\n</td><td>$FirstName $LastName</td><td>$PartNum</td>". 
         "<td>$serial</td><td>$Date</td><td>$location</td><td>$description</td><td>$price</td><td><a href='ocmDelete.php?key=$ID'>$ID</a></tr></tr>\n"; 
		 }  
         echo "<tr><td colspan=2></td></tr></table>"; 
}
HTML_Foot(); 
print "<br>";

?> 
<div align="center">

  <p>&nbsp;</p>
</div>
