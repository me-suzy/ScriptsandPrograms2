<?php 

//May 22 2005
//Revised by JF
//Revision Number: 3

include("checksession.php");
include_once("ruleDeterimination.php");
include_once "./includes/classes.php";
include_once "./includes/settings.php";


if (!isset($_COOKIE['record1'])) die("Your Browser is not accepting cookies, cookies must be enabled to use this application");
//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

//Jason Farrell
function HTML_Head() { 
    echo " 
    <HTML><HEAD> 
    <TITLE>Processing Form</TITLE> 
    </HEAD> 
    <BODY BGCOLOR=\"#CCCCCC\">"; 
} 

function HTML_Foot() { 
    echo "</body></html>"; 
} 

$strOldEntries = "Previous Entries in database"; 
$strNewEntries = "Updated version of databse (after entries)"; 

HTML_Head();

/*
	Since this page is capable of both delete and update operations, we will allow delete to override update
	Thus if we see an id to be deleted - we will delete the id that is given - however for security the id sent
	over as the id of the ticket ($_POST['id']) must match, otherwise an error will be generated.
	
	From this point, a simple update query is all that is needed to update the neccesary fields.
*/
mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
$u = unserialize($_SESSION['enduser']);
$currentUser = $u->get('user');

$t = new Ticket($_POST['ID']);
if (empty($_POST['idDelete'])) {
	//update
	if (empty($_POST['id'])) exit("No ID provided");		//this is a critical security hole to enter this if statement
    
	//perform the command query
	$t->set('ticketVisi', $_POST['visibility'], 'intval');
	$t->set('status', new Status($_POST['status']));
	$t->set('PCatagory', new Category($_POST['PCatagory']));
	$t->set('staff', new User($_POST['itstaff']));
	$t->set('priority', new Priority($_POST['priority']));
	if (!empty($_POST['partNo'])) $t->set('partNo', $_POST['partNo'], 'intval');
	$t->commit();
	#die(var_dump($cmd));
	
	//this is something of a transaction, though unless an acid type table is used, this is nothing but a sham
	//conenct and select
	
	//we assume that the query completed
	if (!empty($_POST['Resolution'])) {
		//insert a new resolution entry
		$cmd  = "insert into " . DB_PREFIX . "resolution(id, solution, resdate) ";
		$cmd .= "values(" . intval($_POST['id']) . ", '" . mysql_real_escape_string($_POST['Resolution']) . "', '" . date("h:i  M d Y") . "')";
		
		//insert
		if (mysql_query($cmd) === false) {
			die("Resolution Value did not insert correctly");
		}
	}
						
	//now we want to check for a ticket being closed, this is simple
	//just do a string comparison with the status
	echo "Thank You " . $currentUser . "<br/>\n";
	$q = "select id from " . DB_PREFIX . "status order by position desc limit 1"; 
	$s = mysql_query($q) or die(mysql_error());
	$statNum = mysql_result($s, 0);
	
	if ($_POST['status'] == $statNum) {
		//output
		echo "Ticket #" . $_POST['id'] . " has been Closed\n";
	    PerformCloseAction(mysql_result(mysql_query("select hdemail_close from " . DB_PREFIX . "settings LIMIT 1"), 0),
	                       mysql_result(mysql_query("select email_type from " . DB_PREFIX . "settings LIMIT 1"), 0),
	    				   $_POST['id'], '');
	}
	else {
		//output
		echo "Ticket #" . $_POST['id'] . " has been Updated\n";
		
		//get neccesary infromation for email send
		$t = new Ticket($_POST['id']);
		
		$FirstName = $t->get('FirstName', 'stripslashes');
		$describe  = $t->get('descrip', 'nl2br');
		$mainDate  = date("M d Y h:ia", $t->get('mainDate', 'intval'));
			
		PerformUpdateAction(mysql_result(mysql_query("select hdemail_up from  " . DB_PREFIX . "settings LIMIT 1"), 0), $_POST['id'],
							mysql_result(mysql_query("select email_type from " . DB_PREFIX . "settings LIMIT 1"), 0),
							$_POST['itstaff'], $describe, $mainDate, $_POST['Resolution'], $FirstName);
	}
}
else {
	echo "Ticket #" . $_POST['idDelete'] . " has been Deleted\n";
	
	//delete
	$t->delete();
	$update = false;
}

//obtain an email address and other information	   
/*$cur= mysql_query( "SELECT ID,FirstName,EMail, LastName,PCatagory,descrip,staff,mainDate FROM ".$databasePrefix."data WHERE ID =$dataVar" )   or die("Invalid query: " . mysql_error());  
if(mysql_num_rows($cur)) { 
$row=mysql_fetch_row( $cur );
$ID= $row[0]; // get the field "Index" 
	$FirstName= $row[1]; // get the field "FirstName" 

			Chad Please Look Here
				The email address being used here - does not seem correct. It is coming from the data table - when I am storing
				the email in the setting table.
				Is this an error or did I edit the wrong area - let me know

			$eMail = $row[2];  // get the email field.
			$LastName= $row[3]; 
			$PCatagory= $row[4]; 
			$describe= $row[5]; 
			$staff= $row[6]; 
			$mainDate= $row[7]; 
		 }	

$reseid=mysql_insert_id();
	   
//Chad I am adding the email supression feature here as well - remove it if you like
//if($hdemail_up)		//Old Line
if($hdemail && !isset($_POST['supressEmail'])) {
	  $cur1= mysql_query( "SELECT solution FROM ".$databasePrefix."resolution WHERE resid ='$reseid' " )   
	  or die("Invalid query: " . mysql_error());  
	  $row1=mysql_fetch_row( $cur1 ); 
	  $res=stripslashes($row1[0]);
	  
	  //call update rule here
  }//End Email Flag
*/
HTML_Foot();

function Error_handler($error , $cnx){
echo $error;
echo $cnx;
}

//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

?> 
<div align="center">
  <p>&nbsp;</p>
  <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please click here 
    to return to the <a href="DataAccess.php">Help Desk Page.</a> </font></strong></p>
  <p>&nbsp;</p>
  <p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
    2005 Help Desk Reloaded<br>
    <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software for Tomorrows 
    Problem.</a></font></p>
  <p>&nbsp;</p>
</div>
<?php mysql_close(); ?>