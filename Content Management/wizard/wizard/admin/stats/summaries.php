<?php
/*  
   Summaries
   (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/




?>
<div id="pagelinks" >
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
<tr><td>
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="0" border="0" border-color="c0c0c0" width="100%">
		<tr><td>&nbsp;</td></tr>
	
	<tr><td>
		<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
			<tr class="normalText" bgcolor="#f0f0f0">
	   			<td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>Summary Statistics</b></a></td>
			</tr>
    	</table>
	</td></tr>
	
	
	<tr><td>
	
	
	<br />
<?

$db = new DB();

//get unique visitors
$db->query("SELECT DISTINCT Host as unique_visitors FROM ". DB_PREPEND . "hits GROUP BY Host");
$unique_visitors = 0;
while($i = $db->next_record()){
	$unique = $i['unique_visitors'];
	$unique_visitors++;
} // while

echo "<div align=\"center\"><center>";
echo "<table border=\"0\" width=\"95%\">";

echo "<tr><td valign=\"top\" width=\"33%\"><table border=\"0\"  width=\"100%\" align=\"left\">";


echo "<tr bgcolor=\"#F1F9FA\">";
	echo  "<td class=\"normalText\" width=\"51%\" align=\"left\"><b><font color=\"#DC143C\">Unique Visitors:</font></b></td>"; 
	echo  "<td width=\"49%\" background=\"#F2F3FF\" colspan=\"2\" align=\"right\">$unique_visitors&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
echo "</tr>";


//get total number of hits from hits table
$db->query("SELECT count(id) as total FROM ". DB_PREPEND . "hits ");
$i = $db->next_record();
$total = $i['total'];

   
// Convert dates to numbers using the TO_DAYS() function to find the number of days that have elapsed 
$db->query("SELECT TO_DAYS(MAX(Date)) - TO_DAYS(MIN(Date)) AS days FROM ". DB_PREPEND . "hits"); 
$i = $db->next_record();
$days = $i["days"]; 


// divide the total number of hits by days 
if ($total == 0 || $days == 0 || $total < $days) {
    $avghits = 0;
}
else {
$avghits  = $total / $days; 
}

echo "<tr bgcolor=\"#ffffff\">";
	echo  "<td class=\"normalText\" width=\"51%\" align=\"left\"><b><font color=\"#DC143C\">Avg. Daily Hits:</font></b></td>"; 
	$avghits  = round ($avghits); 
	echo  "<td background=\"#F2F3FF\" colspan=\"2\" width=\"49%\" align=\"right\">$avghits&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>"; 
	
echo "</tr>";

//average hourly hits
echo "<tr bgcolor=\"#F1F9FA\" >";
	echo  "<td class=\"normalText\" width=\"51%\" align=\"left\"><b><font color=\"#DC143C\">Avg. Hourly Hits:</font></b></td>";  
    echo  "<td width=\"49%\" colspan=\"2\" align=\"right\">" . round( $avghits / 24 ). "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
echo  "</tr>" ; 

//second column is Monthly Hits
echo "</table></td>";
echo "<td width=\"33%\" valign=\"top\" ><table border=\"0\" width=\"100%\" align=\"left\">";


// retrieve the count of records where they match 
// the number of the month and the year 
$db->query("SELECT COUNT(*) as cnt, MONTHNAME(Date) as mn, YEAR(Date) as year,
MONTH(Date) as month 
FROM ". DB_PREPEND . "hits 
GROUP BY mn,month 
ORDER BY year, month"); 
 
//Previous Months
echo "<tr>";
	echo  "<td colspan=\"3\" class=\"normalText\" width=\"21%\" align=\"left\"><b><font color=\"#DC143C\">Monthly Hits:</font></b></td>";  
echo  "</tr>" ; 

$bgcolor = "#F2F3FF";

while ( $row = $db->next_record() ) { 
if ($bgcolor == "#F1F9FA") {
    $bgcolor = "#ffffff";
}
else { $bgcolor = "#F1F9FA"; }
echo "<tr bgcolor=\"$bgcolor\">";
	echo  "<td align=\"right\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $row[mn] . "  " .$row[year] . "&nbsp;&nbsp;</td>";  
    echo  "<td colspan=\"2\" width=\"8%\" align=\"right\">".$row[cnt]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";  
echo  "</tr>" ; 
} 

echo "</table></td>";


$db->query("SELECT count(*) as cnt, DAYNAME(Date) as dnr, 
TO_DAYS(Date) as tdr 
FROM ". DB_PREPEND . "hits
WHERE Date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 day ) 
GROUP BY dnr,tdr 
ORDER BY tdr"); 


//third column is Daily Average

echo "<td valign=\"top\" width=\"33%\"><table border=\"0\" width=\"100%\" align=\"left\">";

echo "<tr>";
	echo  "<td colspan=\"3\" class=\"normalText\" width=\"21%\" align=\"left\"><b><font color=\"#DC143C\">Last 7 Days Daily Avg.:</font></b></td>";  
echo  "</tr>" ; 
while ( $row = $db->next_record() ) { 
if ($bgcolor == "#F1F9FA") {
    $bgcolor = "#ffffff";
}
else { $bgcolor = "#F1F9FA"; }
echo "<tr bgcolor=\"$bgcolor\">";
    echo  "<td colspan=\"2\" width=\"58%\" align=\"right\">" . $row[dnr] . "&nbsp;&nbsp;</td>";
	echo  "<td width=\"21%\" align=\"right\">" . $row[cnt] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";  
echo  "</tr>" ; 
} //while
echo "</table></td>";

echo "</tr></table>";






























echo "</table></center></div>";

?>


&nbsp;<br /></td></tr>
</table>
<br />
</td></tr>
</table>

</div>