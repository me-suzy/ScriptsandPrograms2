<?PHP

//---------------------------------------------------------
// EVENTIVE
// Version v0.1
//
// Written by Andrew Whitehead
// (c) Andrew Whitehead 2004
//
// THIS TAG MUST NOT BE REMOVED
//---------------------------------------------------------

require('./config.php');                                        //include the config file




if ( isset($_GET['act'])) {                                     //Check the mode
$act = $_GET['act'];
} else {
$act = "view";                                                  //Otherwise let the mode = view
}


if ( isset($_GET['format'])) {                                  //Check the number formatting
$format = $_GET['format'];
setcookie('format', $format, time()+3600 );                     //Set the cookie
} else {                                                        //If number formatting isnt set
if ( isset($_COOKIE['format'])) {                               //check for cookies
$format = $_COOKIE['format'];
} else {
$format = $defaultformat;                                       //If unset then set cookie
setcookie('format', $format, time()+3600 );
}
}





$today = mktime();                                              //Get todays date/time
$today2 = mktime(0,0,0);

echo <<<tophtml
<html>
<head>
<title>Eventive</title>
<link rel=stylesheet href="./style.css" type="text/css">
</head>
<body>
<table width=100% cellspacing=1 cellpadding=3px>
tophtml;

                                                                //Connect to the database
mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname) or die("Unable to select database");





if ($act == "add"){                                             //What to do if we are adding the data to the db

$name = $_POST['name'];                                         //Put the inputs into the variables
$eventday = $_POST['day'];
$eventmonth = $_POST['month'];
$year = $_POST['year'];

$eventname = strip_tags($name);                                 //Clean up the text
$eventyear = strip_tags($year);


$valid = checkdate($eventmonth, $eventday, $eventyear);         //Check for valid date
$eventstamp = mktime(0,0,0,$eventmonth,$eventday,$eventyear);   //Create the tomestamp


if ($eventname == "" OR $eventday == '0' OR $eventmonth='0' OR $eventyear == ""){ //Check all fields completed
echo "<tr><td class=error><b>Error: </b>You must complete all fields.</td></tr>";
} elseif ( $valid == False ) {                                                    //Check for valid date
echo "<tr><td class=error><b>Error: </b>The entered date is not a valid date.</td></tr>";
} elseif ( $eventyear>"2069" ) {                                                //Check if the year is too far away
echo "<tr><td class=error><b>Error: </b>Dates past 2069 are not allowed.</td></tr>";
} elseif ( $eventstamp < $today2 ) {                                              //Check the event is not in the past
echo "<tr><td class=error><b>Error: </b>Dates in the past are not allowed.</td></tr>";
} elseif ( strlen($eventname)>30 ) {                                              //Check the name is not too long
echo "<tr><td class=error><b>Error: </b>The maximum length of the event name is 30 characters.</td></tr>";
} elseif ( strlen($eventyear)<>4) {                                               //Check for 4 digits in year
echo "<tr><td class=error><b>Error: </b>The year must contain 4 digits.</td></tr>";
} else {


$query = "INSERT INTO events (event,date) VALUES ('$eventname','$eventstamp')";
mysql_query($query) or die(mysql_error());

if (mysql_error() == NULL) {
echo "<tr><td class=success><b>Success!: </b>Your event has been added.</td></tr>";
} else {
echo "<tr><td class=error><b>Error: </b>Your event could not be added.</td></tr>";
}


}
}








    $query = "SELECT * FROM `events` ORDER BY 'date' ASC";     //Select the dates, earlyest first
    $result = mysql_query($query) or die(mysql_error());


$c=0;
while ( $i = mysql_fetch_array($result) ) {

$secs = $i['date'] - $today;                                    //Find the difference between the dates

if (($c%2)==1){
$class = "row1";
} else {
$class = "row2";
}

if ($today2 == $i['date'])                                      //Check to see if the event is today
{
echo "<tr><td class=$class>Today is <b>".$i['event']."</b></td></tr>";
$c++;
}
elseif (($i['date']-86400)==$today2)
{
echo "<tr><td class=$class>Tomorrow is <b>".$i['event']."</b></td></tr>";
$c++;
}
elseif (($today2-86400)==$i['date'])
{
echo "<tr><td class=$class>Yesterday was <b>".$i['event']."</b></td></tr>";
$c++;
}
elseif (($today2-86400)>$i['date'])                             //If the event has passed delete it
{
$query = "DELETE FROM events WHERE id=".$i['id']." LIMIT 1";
mysql_query($query) or die(mysql_error());
}
else
{                                                               //If everything's ok go on to print the countdowns




echo "<tr><td class=$class><b>".$i['event']."</b> is in <i>";

if ($format==0){
calcyears($secs);
calcmonths($rem);
calcweeks($rem);
calcdays($rem);
}
elseif ($format==1){
calcmonths($secs);
calcweeks($rem);
calcdays($rem);
}
elseif ($format==2){
calcweeks($secs);
calcdays($rem);
}
elseif ($format==3){
calcdays($secs);
}
else {
calcyears($secs);
calcmonths($rem);
calcweeks($rem);
calcdays($rem);
}

echo "</i>(".date("l jS of F Y", $i['date']).")</td></tr>";            //Print the date of the event
                                                                       //eg. Friday 6th of October 2006
$c++;
}

}

$thisyear = date("Y");

echo <<<formhtml
<tr><td class=format>
<a href="./index.php?format=0">Years</a> | 
<a href="./index.php?format=1">Months</a> |
<a href="./index.php?format=2">Weeks</a> |
<a href="./index.php?format=3">Days</a>
</td></tr>
</table>
<center>
<form action="index.php?act=add" method=POST>
Event name:<br><input type=text name=name size=30>
<br><i>eg. Bob's Birthday</i><br>
<select name=day size=1>
<option value=0 selected>Day</option>
<option value=1>1</option>
<option value=2>2</option>
<option value=3>3</option>
<option value=4>4</option>
<option value=5>5</option>
<option value=6>6</option>
<option value=7>7</option>
<option value=8>8</option>
<option value=9>9</option>
<option value=10>10</option>
<option value=11>11</option>
<option value=12>12</option>
<option value=13>13</option>
<option value=14>14</option>
<option value=15>15</option>
<option value=16>16</option>
<option value=17>17</option>
<option value=18>18</option>
<option value=19>19</option>
<option value=20>20</option>
<option value=21>21</option>
<option value=22>22</option>
<option value=23>23</option>
<option value=24>24</option>
<option value=25>25</option>
<option value=26>26</option>
<option value=27>27</option>
<option value=28>28</option>
<option value=29>29</option>
<option value=30>30</option>
<option value=31>31</option>
</select> /
<select name=month size=1>
<option value=0 selected>Month</option>
<option value=1>January</option>
<option value=2>Febuary</option>
<option value=3>March</option>
<option value=4>April</option>
<option value=5>May</option>
<option value=6>June</option>
<option value=7>July</option>
<option value=8>August</option>
<option value=9>September</option>
<option value=10>October</option>
<option value=11>November</option>
<option value=12>December</option>
</select> /
<input type=text name=year size=3 value={$thisyear}>
<br><br><input type=submit value=Submit>
</form>
Eventive v0.1 &copy; <a href=http://www.andrew-w.co.uk>andrew-w.co.uk</a></center>
formhtml;

mysql_close();


//--------------------------------------------------------------------------
// Functions for calculating the different units from the number of seconds
//--------------------------------------------------------------------------

function calcyears($secs) {                                     //Calculate the years
GLOBAL $rem;                                                    //Declare the global variable
$years = floor($secs/60/60/24/365);
$rem = ($secs/60/60/24/365 - $years)*60*60*24*365;              //Calculate the remaining time in seconds
if ($years == 1){
echo "$years year ";                                            //Output the number of units, remembering s if needed
} elseif ($years <> 0){
echo "$years years ";
}
}
function calcmonths($secs) {
GLOBAL $rem;
$months = floor($secs/60/60/24/30);
$rem = ($secs/60/60/24/30 - $months)*60*60*24*30;
if ($months == 1){
echo "$months month ";
} elseif ($months <> 0){
echo "$months months ";
}
}
function calcweeks($secs) {
GLOBAL $rem;
$weeks = floor($secs/60/60/24/7);
$rem = ($secs/60/60/24/7 - $weeks)*60*60*24*7;
if ($weeks == 1){
echo "$weeks week ";
} elseif ($weeks <> 0){
echo "$weeks weeks ";
}
}
function calcdays($secs) {
GLOBAL $rem;
$days = floor($secs/60/60/24);
$rem = ($secs/60/60/24 - $days)*60*60*24;
if ($days == 1){
echo "$days day ";
} elseif ($days <> 0){
echo "$days days ";
}
}





?>
