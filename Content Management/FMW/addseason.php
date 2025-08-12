<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$date=date("Y-m-d");

$year = substr("$date", 0, 4);
$month = substr("$date", 5,-3);
$day = substr("$date", 8);

$nextyear = $year + 1;
$prevyear = $year -1;

if (isset($_POST['submit'])) { 


$season = $_POST['season'];
$startday = $_POST['startday'];
$startmonth = $_POST['startmonth'];
$startyear = $_POST['startyear'];
$endday = $_POST['endday'];
$endmonth = $_POST['endmonth'];
$endyear = $_POST['endyear'];

$season_start = ("$startyear-$startmonth-$startday");
$season_end = ("$endyear-$endmonth-$endday");


$query="INSERT INTO seasons (season_name, season_start, season_end) 
VALUES ('$season', '$season_start', '$season_end') ";
mysql_query($query); 

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=admin.php"> <?php


}
?>

<HTML>
<HEAD>
<TITLE>Add Season</TITLE>
</HEAD>
<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

<font color="#<?php echo $col_text ?>">

Enter Name For Season<br>

<input id="season" size="20" name="season"><br>

<BR><BR>
Season Start Date
<br>

<?php
echo "<select name=\"startday\">"; 
echo "<option value=\"01\">1</option> "; 
echo "<option value=\"02\">2</option>";
echo "<option value=\"03\">3</option>"; 
echo "<option value=\"04\">4</option>"; 
echo "<option value=\"05\">5</option>"; 
echo "<option value=\"06\">6</option>"; 
echo "<option value=\"07\">7</option>"; 
echo "<option value=\"08\">8</option>"; 
echo "<option value=\"09\">9</option>"; 
echo "<option value=\"10\">10</option>"; 
echo "<option value=\"11\">11</option>"; 
echo "<option value=\"12\">12</option>"; 
echo "<option value=\"13\">13</option>"; 
echo "<option value=\"14\">14</option>"; 
echo "<option value=\"15\">15</option>"; 
echo "<option value=\"16\">16</option>"; 
echo "<option value=\"17\">17</option>"; 
echo "<option value=\"18\">18</option>"; 
echo "<option value=\"19\">19</option>"; 
echo "<option value=\"20\">20</option>"; 
echo "<option value=\"21\">21</option>"; 
echo "<option value=\"22\">22</option>"; 
echo "<option value=\"23\">23</option>"; 
echo "<option value=\"24\">24</option>"; 
echo "<option value=\"25\">25</option>"; 
echo "<option value=\"26\">26</option>"; 
echo "<option value=\"27\">27</option>"; 
echo "<option value=\"28\">28</option>"; 
echo "<option value=\"29\">29</option>"; 
echo "<option value=\"30\">30</option>"; 
echo "<option value=\"31\">31</option>"; 
 
echo "</select> "; 


echo "<select name=\"startmonth\">"; 
echo "<option value=\"01\">Jan</option> "; 
echo "<option value=\"02\">Feb</option>"; 
echo "<option value=\"03\">Mar</option>";
echo "<option value=\"04\">Apr</option>";
echo "<option value=\"05\">May</option>";
echo "<option value=\"06\">Jun</option>";
echo "<option value=\"07\">Jul</option>";
echo "<option value=\"08\">Aug</option>";
echo "<option value=\"09\">Sep</option>";
echo "<option value=\"10\">Oct</option>";
echo "<option value=\"11\">Nov</option>";
echo "<option value=\"12\">Dec</option>";
echo "</select> "; 

echo "<select name=\"startyear\">"; 
echo "<option value=\"$prevyear\">$prevyear</option>"; 
echo "<option value=\"$year\">$year</option>"; 
echo "<option value=\"$nextyear\">$nextyear</option>";

echo "</select> "; 

?>
<BR><BR>
Season End Date
<br>

<?php
echo "<select name=\"endday\">"; 
echo "<option value=\"01\">1</option> "; 
echo "<option value=\"02\">2</option>";
echo "<option value=\"03\">3</option>"; 
echo "<option value=\"04\">4</option>"; 
echo "<option value=\"05\">5</option>"; 
echo "<option value=\"06\">6</option>"; 
echo "<option value=\"07\">7</option>"; 
echo "<option value=\"08\">8</option>"; 
echo "<option value=\"09\">9</option>"; 
echo "<option value=\"10\">10</option>"; 
echo "<option value=\"11\">11</option>"; 
echo "<option value=\"12\">12</option>"; 
echo "<option value=\"13\">13</option>"; 
echo "<option value=\"14\">14</option>"; 
echo "<option value=\"15\">15</option>"; 
echo "<option value=\"16\">16</option>"; 
echo "<option value=\"17\">17</option>"; 
echo "<option value=\"18\">18</option>"; 
echo "<option value=\"19\">19</option>"; 
echo "<option value=\"20\">20</option>"; 
echo "<option value=\"21\">21</option>"; 
echo "<option value=\"22\">22</option>"; 
echo "<option value=\"23\">23</option>"; 
echo "<option value=\"24\">24</option>"; 
echo "<option value=\"25\">25</option>"; 
echo "<option value=\"26\">26</option>"; 
echo "<option value=\"27\">27</option>"; 
echo "<option value=\"28\">28</option>"; 
echo "<option value=\"29\">29</option>"; 
echo "<option value=\"30\">30</option>"; 
echo "<option value=\"31\">31</option>"; 
 
echo "</select> "; 


echo "<select name=\"endmonth\">"; 
echo "<option value=\"01\">Jan</option> "; 
echo "<option value=\"02\">Feb</option>"; 
echo "<option value=\"03\">Mar</option>";
echo "<option value=\"04\">Apr</option>";
echo "<option value=\"05\">May</option>";
echo "<option value=\"06\">Jun</option>";
echo "<option value=\"07\">Jul</option>";
echo "<option value=\"08\">Aug</option>";
echo "<option value=\"09\">Sep</option>";
echo "<option value=\"10\">Oct</option>";
echo "<option value=\"11\">Nov</option>";
echo "<option value=\"12\">Dec</option>";
echo "</select> "; 

echo "<select name=\"endyear\">"; 
echo "<option value=\"$prevyear\">$prevyear</option>"; 
echo "<option value=\"$year\">$year</option>"; 
echo "<option value=\"$nextyear\">$nextyear</option>";

echo "</select> "; 

?>



<BR><BR>
<input type="Submit" name="submit" value="submit">
</form>



</BODY> 
</HTML>