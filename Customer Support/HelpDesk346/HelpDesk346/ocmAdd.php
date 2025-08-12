<?php 
include("checksession.php"); 
//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

function HTML_Head() 
	{ 
    echo " 
    <HTML><HEAD> 
    <TITLE>Property MGT Processing Form</TITLE> 
    </HEAD> 
    <BODY BGCOLOR=\"#D5D5AB\">"; 
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


function Enter_New_Entry() {
$FirstName = mysql_real_escape_string($_SESSION['sess_user']);
$LastName = mysql_real_escape_string("");
$partNum = mysql_real_escape_string($_POST["partNum"]);
$serial = mysql_real_escape_string($_POST["serial"]);
$location = mysql_real_escape_string($_POST["location"]);
$description = mysql_real_escape_string($_POST["description"]);
$price = mysql_real_escape_string($_POST['price']);
$sysDate= date("h:i  M d Y");
print "<br>";
print " Thank you $FirstName.";

// END MYSQL DataBase Connection Section
	$cur= mysql_query("SELECT ID,User,Pass,FirstName,LastName FROM ".DB_PREFIX."accounts WHERE User ='$FirstName'" ) 
    or die("Invalid query: " . mysql_error());
    
   // fetch the succesive result rows 
    while( $row=mysql_fetch_row( $cur ) ) 
		{ 
        $FN= $row[3]; // get the field "FirstName" 
        $LastName= $row[4]; // get the field "LastName" 
		}
	
   $LastName = $FN." ".$LastName;
	
	
	
    $SQL_query_String = "Insert Into ".DB_PREFIX."excess (FirstName, LastName, partNum,serial,location,descrip,date, price) 
            Values ('$FirstName', '$LastName', '$partNum', '$serial', '$location', '$description','$sysDate', " . floatval($price) . ")"; 

    $cur= mysql_query( $SQL_query_String ) 
	    or die("Invalid : " . mysql_error());

  
 }

HTML_Head(); 
Enter_New_Entry(); 
HTML_Foot(); 
print "<br>";

//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

?>

<title>Property Managment Update Page</title> 
<div align="center">
  <p>&nbsp;</p>
  <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please click here 
    to return to the <a href="ocm-first.php">Help Desk Admin Page.</a> </font></strong></p>
  <p>&nbsp;</p>
  <p><a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></p>
</div>
