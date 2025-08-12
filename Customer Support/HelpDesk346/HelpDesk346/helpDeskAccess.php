<?php 
//include("checksession.php"); 
//Revision Date May 08, 2005
//Revised by Jason Farrell
//Revision Number 1

session_start();
require_once('phpSniff.class.php');

//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

function HTML_Head() { 
    echo " 
    <HTML><HEAD> 
    <TITLE>HelpDeskReloaded.com Trouble Ticket Created.</TITLE> 
    </HEAD> 
    <BODY BGCOLOR=\"#D5D5AB\">"; 

		}
		


function HTML_Foot() { 
    echo "</body></html>"; 
} 

HTML_Head(); 


include_once("config.php");
mysql_connect($server, $databaseName, $databasePassword);
mysql_select_db($database);

include_once "./includes/settings.php";
//MYSQL DataBase Connection Sectionrequire("config.php");

$security1 = $OBJ->get('helpdesk');
$contents  = $OBJ->get('navigation');
$result_page = $OBJ->get('result_page');
$hdticket  = $OBJ->get('hdticket');

// initialize some vars
$GET_VARS = isset($_GET) ? $_GET : $HTTP_GET_VARS;
$POST_VARS = isset($_POST) ? $_GET : $HTTP_POST_VARS;
if(!isset($GET_VARS['UA'])) $GET_VARS['UA'] = '';
if(!isset($GET_VARS['cc'])) $GET_VARS['cc'] = '';
if(!isset($GET_VARS['dl'])) $GET_VARS['dl'] = '';
if(!isset($GET_VARS['am'])) $GET_VARS['am'] = '';

$sniffer_settings = array('check_cookies'=>$GET_VARS['cc'],
                          'default_language'=>$GET_VARS['dl'],
                          'allow_masquerading'=>$GET_VARS['am']);
$client =& new phpSniff($GET_VARS['UA'],$sniffer_settings);

//vars section 
//END Vars Section


    // send a simple mysql query . returns an mysql cursor 
    $cur= mysql_query("select * from ".$databasePrefix."data where status ='Open' " )
	or die("Invalid query: " . mysql_error());   
	$nocall=mysql_num_rows($cur); 
    /*$nbrow=0;   //Local variable to count number of rows 
   
    while( $row=mysql_fetch_row( $cur ) ) 
	{ 
       	    $ID= $row[0]; // get the field "Index" 
        	$FirstName= $row[1]; // get the field "FirstName" 
        	$LastName= $row[2]; // get the field "LastName" 
        	$PCatagory= $row[3]; // get the field "PCatagory" 
			$value= $row[4]; // get the field "describe" <br><br>
			$Status= $row[5]; // get the field "Status" 
			$sysDate= $row[8]; // get the field "Status" 
			$nbrow++; 
	}*/
    // close the connection. important if persistent connection are "On" 
    
	//$userName = $_COOKIE["record2"]; //Required
	$userName = 'Any One';
	
	$status = 'New';
	$FirstName = mysql_real_escape_string($_POST['FirstName']);
	$LastName = mysql_real_escape_string($_POST['LastName']);
	$describe= mysql_real_escape_string($_POST['describe']);
	$describe1 = ereg_replace("\'","`",$describe);
    $sysDate= date("h:i  M d Y", mktime());
	$describe1 = ereg_replace("\'","`",$describe);
	$value = mysql_real_escape_string($_POST['PCatagory']);
	$eMailAddress=mysql_real_escape_string($_POST['eMail']);
	$phoneNum = str_replace("-", "", $_POST['phone']);
	$phoneNum = intval($phoneNum);
	$phoneExt = intval($_POST['ext']);

//END Database Connection Section
$ua=$client->get_property('ua');
$browser=$client->property('browser');
$bversion=$client->property('version');
$platform=$client->property('platform');
$os=$client->property('os');
$ip=$client->property('ip');

$SQL_query_String = "Insert Into ".$databasePrefix."data (FirstName, EMail, LastName, PCatagory,descrip,Status,mainDate,staff,
platform ,os ,ipaddress,browser ,bversion ,uastring, phoneNumber, phoneExt ) 
Values ('$FirstName', '$eMailAddress', '$LastName', '$value', '$describe1', '$status','$sysDate','$userName',
'$platform','$os','$ip','$browser','$bversion','$ua', '$phoneNum', '$phoneExt')"; 

$cur= mysql_query($SQL_query_String ) or die("Invalid : " . mysql_error());
$ticketno=mysql_insert_id();

//now send the emails - this is a test line of code, I have no way to confirm it
//include the function definition
include_once "./ruleDeterimination.php";
PerformCreateAction(
	mysql_result( mysql_query("select hdemail_create from " . DB_PREFIX . "settings LIMIT 1"), 0, 'hdemail_create' ),
	$FirstName . " " . $LastName, $eMailAddress, $sysDate, $describe1,
	mysql_result( mysql_query( "select email_type from " . DB_PREFIX . "settings LIMIT 1" ), 0, 'email_type' )
);


print "<br>";
print " Thank you $FirstName. Your question has been sent to the help desk staff.";
print "<br>";
print "Please print this page as a reciept.";

print "<br><br>";
if($hdticket)
print "Your help desk ticket number is $ticketno With $nocall Number of calls ahead of you";


HTML_Foot(); 
print "<br>";

//Code CopyRight HelpDeskReloaded  2004
//For more details see ReadMe.txt or go to http://www.HelpDeskReloaded.com

?>
<title>Update Help Desk Database With Ticket</title> 
<head><link href="style.css" rel="stylesheet" type="text/css"></head>
<div align="center"> 
  <table width="35%" height="346" border="1" align="left" cellspacing="0">
    <tr>
      <td valign="top">
<p align="center"><strong>Have you restarted your computer lately?</strong> The 
          Help Desk Staff recommends restarting your computer when trouble arises, 
          if this corrects the problem please contact the help desk in order to 
          close your help desk ticket.</p>
        <p align="center"><strong>Please click here to return to the <a href="index.php">Help 
          Desk Main Page.</a> </strong></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p align="center"><strong><a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></strong><font size="1"><br>
          <a href="http://www.helpdeskreloaded.com"><em>Help Desk Reloaded</em></a></font></p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
