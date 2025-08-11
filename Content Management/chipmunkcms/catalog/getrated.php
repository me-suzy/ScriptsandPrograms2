<link rel='stylesheet' href='style.css' type='text/css'>
<?php
include "connect.php";
include "admin/var.php";
print "<center>";
print "<table class='maintable'>";
print "<tr class='headline'><td><center><b>Get rated!</b></center></td></tr>";
print "<tr class='mainrow'><td>Getting rated is easy, just used the link below, change 'your_id' to the id of your tutorial. Ranking of tutorials is based on average votes and number of votes<br><br>";
print "&lt;a href='rate.php?ID=your_id'&gt;rateme&lt;/a&gt;";
print "</td></tr></table>";
?>
