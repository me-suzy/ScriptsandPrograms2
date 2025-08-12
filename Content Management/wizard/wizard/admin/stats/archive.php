<?php
/*  
   Archive Visitor Statistics
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

function date_convert($month_number){

switch($month_number){
	case "1": 
		return "January";
		break;
	case "2": 
		return "February";
		break;
	case "3": 
		return "March";
		break;
	case "4": 
		return "April";
		break;
	 case "5": 
		return "May";
		break;
	 case "6": 
		return "June";
		break;
	 case "7": 
		return "July";
		break;
	 case "8": 
		return "August";
		break;
	 case "9": 
		return "September";
		break;
	 case  "10":
		return "October";
		break;
	 case "11": 
		return "November";
		break;
	
	default:
		return "December";
		break;
} // switch


}


?>
<div id="pagelinks" >
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
<tr><td>
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="0" border="0" border-color="c0c0c0" width="100%">
		<tr><td>&nbsp;</td></tr>
	
	<tr><td>
		<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
			<tr class="normalText" bgcolor="#f0f0f0">
	   			<td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>Archive the Visitor Hits Table</b></a></td>
			</tr>
    	</table>
	</td></tr>
	
	
	<tr><td>
	
	
	<br />
<?

$db = new DB();

  // Get number of records
 $db->query("SELECT count(ID) as records FROM ". DB_PREPEND . "hits ");
 $row = $db->next_record();
 $records = $row['records'];
 

 // Get Oldest month in the table
 $db->query("SELECT month(min(Date)) as oldest FROM ". DB_PREPEND . "hits ");
 $row = $db->next_record();
 $oldest_month = $row['oldest'];
 
 
 // Get number of months in table
 $db->query("SELECT count(distinct(month(Date))) as cnt FROM ". DB_PREPEND . "hits ");
 $row = $db->next_record();
 $number_months = $row['cnt'];
 

echo "<div align=\"center\"><table align=\"center\" width=\"50%\">";

if ($message) {
echo "<tr>";
	echo "<td align=\"left\" class=\"message\" colspan=\"2\">$message</td>";
echo "</tr>";
}

echo "<tr>";
	echo "<td class=\"message\" colspan=\"2\">&nbsp;</td>";
echo "</tr>";

echo "<tr>";
	echo "<td width=\"76%\" align=\"left\">Number of records in the database:</td>";
	echo "<td width=\"24%\" align=\"right\">". $records . "</td>";
echo "</tr>";

echo "<tr>";
	echo "<td width=\"76%\" align=\"left\">Number of months in the database:</td>";
	echo "<td width=\"24%\" align=\"right\">". $number_months . "</td>";
echo "</tr>";

echo "<tr>";
	echo "<td width=\"76%\" align=\"left\">Oldest month currently in the database:</td>";
	echo "<td width=\"24%\" align=\"right\">". date_convert($oldest_month) . "</td>";
echo "</tr>";

echo "<tr>";
	echo "<td >&nbsp;</td>";
	echo "<td >&nbsp;</td>";
echo "</tr>";

echo "<form enctype='multipart/form-data' action='".CMS_WWW."/admin.php' method='get'>";
		

echo "<tr>";
	echo "<td width=\"76%\" align=\"left\" >Enter number of months to archive:</td>";
	echo "<td width=\"24%\" align=\"right\" ><input type=\"text\" name=\"archive\" size=\"5\"></td>";
echo "</tr>";

echo "<tr>";
	echo "<td >&nbsp;</td>";
	echo "<td >&nbsp;</td>";
echo "</tr>";

echo "<tr>";

	echo "<td colspan=\"2\" >
	<input type=\"hidden\" name=\"item\" value=\"70\">
	<input type=\"hidden\" name=\"sub\" value=\"39\">
	<input type=\"hidden\" name=\"id\" value=\"2\">
	<input type=\"submit\" name=\"submit\" value=\"submit\"></td>";
echo "</tr>"; 

echo "<tr>";
	echo "<td ></form></td>";
	echo "<td >&nbsp;</td>";
echo "</tr>";
 
 
if ($submit) {

	$month_kept = $_GET['archive'];

	if (!is_memberof(1)) {
	
	  	$message = "Error: Only the webmaster can archive the visitor stats.";
		echo "<span class=\"message\">$message</span>";
		//$location = CMS_WWW . "/admin.php?id=2&item=70&sub=39&message=$message";
		//header("Location: $location");
		exit;
	}

    if (!$month_kept) {
	    
        $message = "Error: You did not enter the number of months to archive.";
		echo "<span class=\"message\">$message</span>";
		//$location = CMS_WWW . "/admin.php?id=2&item=70&sub=39&message=$message";
		//header("Location: $location");
		exit;
    }

	if ($month_kept >= $number_months){
        $message = "Error: You specified too many months.";
		echo "<span class=\"message\">$message</span>";
		//$location = CMS_WWW . "/admin.php?id=2&item=70&sub=39&message=$message";
		//header("Location: $location");
		exit;
    }

 	$today_month = date("n");    // current month
 	$today_year = date("Y");     // current year

   
 	// Start archive if months are different
 	if (($month_kept!= 0) and ($month_kept < $number_months) ) {   //$row[0] is oldest month in the base
      // Get Oldest month in the table
 		$db->query("SELECT month(min(Date)) as oldestmn FROM ". DB_PREPEND . "hits ");
 		$row = $db->next_record();
 		$oldest_month = $row['oldestmn'];

	  
      # Oldest year in the table
      $db->query("SELECT year(min(Date)) as oldestyr FROM ". DB_PREPEND . "hits ");
      $row = $db->next_record();
      $oldest_year = $row['oldestyr'];
	  

      while ( ($oldest_month.$oldest_year != $today_month-($month_kept-1).$today_year) ) {
              # Count hits for the oldest month
              $db->query("SELECT COUNT(*) FROM ". DB_PREPEND . "hits  WHERE MONTH(Date)=$oldest_month and YEAR(Date)=$oldest_year");
              $row = $db->next_record();
              echo "Month: <b>$oldest_month</b> - Year: <b>$oldest_year</b> = <b>$row[0]</b> $msgArchiveCreatedrecords<br><br>";
              $hits = $row[0];

              # Write in history table
              $wDate = $oldest_year."-".$oldest_month."-01";
              $db->query("INSERT INTO ". DB_PREPEND . "hitsArchive  (id, Date, hits) VALUES ('', '$wDate', '$hits')");


              #Clear log table
              $db->query("DELETE FROM ". DB_PREPEND . "hits  WHERE month(Date)=$oldest_month AND year(Date)=$oldest_year");

              $oldest_month = $oldest_month + 1;
              if ($oldest_month == 13) {
                $oldest_month = 1;
                $oldest_year = $oldest_year + 1;
              }
      }  # end while loop
      // echo $msgArchiveCreated."\n";
 }  //end if

} //if month_kept

echo "</table></div>";


  
  
?>
</td></tr>
</table>
<br />
</td></tr>
</table>

</div>