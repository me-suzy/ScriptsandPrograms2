<?
	include("checksession.php"); 
	?>
<table width="137%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td height="59" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><?
			
if($contents == 'B')
		include 'dataaccessheader.php';
else
//if its not banner it must be text.		
include 'textnavsystem.php';
?>
      <map name="Map">
        <area shape="rect" coords="543,151,611,195" href="DataAccess.php">
        <area shape="rect" coords="480,146,542,198" href="search.php">
         
        <area shape="rect" coords="280,146,362,194" href="actmgt.php">
        <area shape="rect" coords="189,146,277,196" href="ocm-first.htm">
        <area shape="rect" coords="127,148,182,198" href="DataAccessSearch.php">
        <area shape="rect" coords="76,147,122,196" href="helpDeskAccessAllCalls.php">
        <area shape="rect" coords="163,2,248,14" href="DataAccess.php">
        <area shape="rect" coords="2,148,74,200" href="reportproblem.htm">
      </map>
      <div align="left"></div></td>
  </tr>
</table>

<?   

//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com
  
 function HTML_Head() 
	{ 

    echo " 
    <HTML><HEAD> 
    <TITLE>Helpdesk call log details.</TITLE> 
    </HEAD> 
    <BODY BGCOLOR=\"#FFFFFF\">"; 
} 
 //The following code will not be seen
function HTML_Foot() 
	{ 
    echo "</body></html>"; 
	echo '<div align="center">
  <p><br>
    <br>
    <br>
    <br>
  </p>
  <p><br>
</div>
';
} 

HTML_Head(); 

//vars section 
$LastName =""; 

//END MYSQL DataBase Connection Section
if(empty($orderby))	 $orderby = "ID";
if(empty($order))    $order = "asc";
$srch = "";

switch ($_POST['searchType'])
{
	case 'name':
    	// send a simple mysql query . returns an mysql cursor 
    	if( $_POST['firstName'] != "")	 $srch.=" and FirstName LIKE '%" . $_POST['firstName'] . "%' ";
		if( $_POST['lastName'] != "")	 $srch.=" and LastName  LIKE '%" . $_POST['lastName']  . "%' ";
		break;
	case 'email':
		if ($_POST['email'] != "") $srch .= " and EMail = '".$_POST['email']."'";
		break;
	case 'content':
		//create our array of keywords
		$keyArr = split('/, ?/', $_POST['keywords']);
		
		if (count($keyArr))
		{
			switch ($_POST['contentType'])
			{
				case 'desc':
					$srch .= " and (";
					foreach ($keyArr as $kw)
						$srch .= " 	descrip LIKE '%" . $kw . "%' OR ";		//this is buiulding the or clause of our query
						
					//chop off the last or - we know the length so we can hard code this
					$srch = substr($srch, 0, -3);
					$srch .= ")";
					break;
				case 'res':
					$srch .= " and (";
					foreach ($keyArr as $kw)
						$srch .= " 	resolution LIKE '%" . $kw . "%' OR ";		//this is buiulding the or clause of our query
						
					//chop off the last or - we know the length so we can hard code this
					$srch = substr($srch, 0, -3);
					$srch .= ")";
			}
		}
		break;
	default:
}
	if( $_POST['idNum'] != "")  		 $srch.=" and ID ='".$_POST['idNum']."' ";
	if( $_POST['PCatagory'] != "")  	 $srch.=" and PCatagory ='".$_POST['PCatagory']."' ";
	if( $_POST['status'] != "")  	 $srch.=" and status ='".$_POST['status']."' ";
	if( $_POST['ip'] != "")      	 $srch.=" and ipaddress  ='".$_POST['ip']."' ";
	
    $sql= "select ID,FirstName,LastName,PCatagory,descrip, Status, mainDate from ".$databasePrefix."data  
           where (ID!= 0 $srch ) order by $orderby $order " ;
    
	 $cur=mysql_query($sql) or die(mysql_error());
	 if (mysql_num_rows($cur)) {
	 	//We have search results
	 	if ($order == "asc") 
      $order = "desc";
     else if ($order == "desc") 
      $order = "asc";
	  
   	   	//echo "<table border=1><tr><th><a href='find.php?orderby=ID&order=$order&firstName=$firstName&LastName=$LastName&idNum=$idNum&PCatagory=$PCatagory&status=$status'>ID</a></th><th><a href='find.php?orderby=mainDate&order=$order&firstName=$firstName&LastName=$LastName&idNum=$idNum&PCatagory=$PCatagory&status=$status'>Time & Date</a></th><th><a href='find.php?orderby=firstName&order=$order&firstName=$firstName&LastName=$LastName&idNum=$idNum&PCatagory=$PCatagory&status=$status'>First Name</a></th>". 
        //"<th><a href='find.php?orderby=LastName&order=$order&firstName=$firstName&LastName=$LastName&idNum=$idNum&PCatagory=$PCatagory&status=$status'>Last Name<a></th><th><a href='find.php?orderby=status&order=$order&firstName=$firstName&LastName=$LastName&idNum=$idNum&PCatagory=$PCatagory&status=$status'>Help Request Status</a></th><th><a href='find.php?orderby=PCatagory&order=$order&firstName=$firstName&LastName=$LastName&idNum=$idNum&PCatagory=$PCatagory&status=$status'>Type of Problem</a></th></tr>\n"; 
    	$nbrow=0;   //Local variable to count number of rows 
    	// fetch the succesive result rows 
    			while( $row=mysql_fetch_row( $cur ) ) 
				{ 
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
		 		
					//Lets make this easy
?>
	<div align="left" style="color:black; padding-left:5px; font-weight:bold">
		<span style="font-size:14pt"><?php echo "(<a href=\"$url3\">#" . $ID . "</a>) " . $LastName . ", " . $FirstName; ?></span><br/>
		<span style="font-size:12pt; font-weight:normal"><?php echo $value; ?></span><br/>
		<span style="font-size:12pt">
			Help Request Status: <?php echo $Status; ?><br/>
			Time/Date: <?php echo $sysDate; ?><br/>
			Problem Type: <?php echo $PCatagory; ?><br/>
	</div>
	<br/>
<?php
		//			echo "<tr><td><a href=\"$url3\">$ID</a>\n</td><td>$sysDate</td><td>$FirstName</td>". 
        // 			"<td>$LastName</td><td>$Status</td><td>$PCatagory</td></tr>"; 
	 			} 
	 }
	 else {
	 	//No Search Results Returned
	 	echo "No Results Found";
	 }
?>
<div align="center">
<?php
	HTML_Foot(); 
//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

?>
</div>
<p><a href="http://www.helpdeskreloaded.com"><br>
  </a></p>
<p><a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></p> 
</p>
