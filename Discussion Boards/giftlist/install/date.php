<?php
include "header.php";
if ($logged_in == 0) {
	die('You are not logged in so you can not view giftlists');
}
?>

<html>
<head>
<title>Date</title>
</head>
<body>



<form method="POST" action="adddate.php?fileId=<?php echo "$fileId"; ?>">



Insert Date the gift will be given.</td><br>
If you are unsure of the exact date, enter 1 week after you expect to give the gift<br><br>
<p align="center"><font size="3" color="#FFFFFF"> The gift will still show on the owners `Gift List` until after the below date.</p>





<? 


echo "<select name=\"dropday\">"; 
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


echo "<select name=\"dropmonth\">"; 
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

echo "<select name=\"dropyear\">"; 
echo "<option value=\"2004\">2004</option> "; 
echo "<option value=\"2005\">2005</option>"; 
echo "<option value=\"2006\">2006</option>";
echo "<option value=\"2007\">2007</option>";
echo "<option value=\"2008\">2008</option>";

echo "</select> "; 
?> 





<br><br><br>
Please enter the name of the actual person giving this gift</td><br>

<input type=text name="giver" size=20></td><br>

This information is used to track who gave the gift on the gift managemnet screen.

<p><input type="submit" /></p>
</form>



</body>
</html>
