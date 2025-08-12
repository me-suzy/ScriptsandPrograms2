<?  

$auth = true;

$pagetitle="Export subscribers";

//require ("../../includes/includes.inc.php");  

//$front_end = true;  

require("functions.php");

sidehoved();

# -- Show list of all subbscribers to Ebate E-zine (Ebate Long + Ebate Short)

		

	$query_longlist = "SELECT Ebate.epostadresse AS longlist FROM Ebate WHERE godkendt='1'";

	$result_longlist = mysql_query($query_longlist) or die (mysql_error());

	

	$query_shortlist = "SELECT EbateShort.epostadresse AS shortlist FROM EbateShort WHERE godkendt='1'";	

	$result_shortlist = mysql_query($query_shortlist) or die (mysql_error());



	# Non approved's longlist

	$query_non_app_longlist = "SELECT Ebate.epostadresse AS nonapplonglist FROM Ebate WHERE godkendt='0'";	

	$result_non_app_longlist = mysql_query($query_non_app_longlist) or die (mysql_error());



	# Non approved's shortlist

	$query_non_app_shortlist = "SELECT EbateShort.epostadresse AS nonappshortlist FROM EbateShort WHERE godkendt='0'";	

	$result_non_app_shortlist = mysql_query($query_non_app_shortlist) or die (mysql_error());







	# Approved's longlist

	$s0 = "SELECT count(godkendt) AS count_long_approved FROM Ebate WHERE godkendt='1'";

	$r0 = mysql_query($s0) or die (mysql_error());

	$n0 = mysql_fetch_array($r0);



	# Approved's shortlist

	$s00 = "SELECT count(godkendt) AS count_short_approved FROM EbateShort WHERE godkendt='1'";

	$r00 = mysql_query($s00) or die (mysql_error());

	$n00 = mysql_fetch_array($r00);



	# Total count

	$s1 = "SELECT count(godkendt) AS rowcount_long FROM ebate.Ebate WHERE godkendt='1'";

	$r1 = mysql_query($s1) or die (mysql_error());

	$n1 = mysql_fetch_array($r1);

	$s2 = "SELECT count(godkendt) AS rowcount_short FROM ebate.EbateShort WHERE godkendt='1'";

	$r2 = mysql_query($s2) or die (mysql_error());

	$n2 = mysql_fetch_array($r2);



	# Total count non approved's

	$s2 = "SELECT count(godkendt) AS rowcount_long_non_app FROM ebate.Ebate WHERE godkendt='0' LIMIT 0, 30";

	$r2 = mysql_query($s2) or die (mysql_error());

	$n2 = mysql_fetch_array($r2);

	$s3 = "SELECT count(godkendt) AS rowcount_short_non_app FROM ebate.EbateShort WHERE godkendt='0' LIMIT 0, 30";

	$r3 = mysql_query($s3) or die (mysql_error());

	$n3 = mysql_fetch_array($r3);



	



	   echo "\n<br><table border=\"0\" width=\"600\">";

	   echo "\n<tr><td colspan=\"2\" valign=\"top\">";

	   

	   echo "\n<br><table border=\"0\" width=\"300\">";

	   echo "\n<tr><td valign=\"top\">";

	   echo "Non-approved's longlist: ";

	   echo "</td>";

	   echo "\n<td valign=\"top\">";

	   echo $n2["rowcount_long_non_app"];

	   echo "\n</td></tr>";

   	   echo "\n<tr><td valign=\"top\">";

	   echo "Non-approved's shortlist: ";

	   echo "</td>";

	   echo "<td>";

	   echo $n3["rowcount_short_non_app"];

       echo "\n</td></tr>";

	   echo "\n<tr>";

	   echo "\n<td>Total Non-approved's:</td>";

       echo "\n<td valign=\"top\"><b>";

	   echo $n2["rowcount_long_non_app"] +$n3["rowcount_short_non_app"];

       echo "\n</b></td></tr>";

	   

	   echo "\n<tr>";

	   echo "\n<td colspan=\"2\">&nbsp;</td>";

       echo "\n</tr>";



	   echo "\n<tr>";

	   echo "\n<td>Approved's Longlist:</td>";

       echo "\n<td valign=\"top\">";

	   echo $n0["count_long_approved"];

       echo "\n</td></tr>";

	   

	   echo "\n<tr>";

	   echo "\n<td>Approved's Shortlist:</td>";

       echo "\n<td valign=\"top\">";

	   echo $n00["count_short_approved"];

       echo "\n</td></tr>";

	   

	   echo "\n<tr><td valign=\"top\">";			   

   	   echo "Total Approved's: "; 

	   echo "</td>";

	   echo "<td><b>";

	   echo $n0["count_long_approved"] + $n00["count_short_approved"];

	   echo "\n</b></td></tr>";



       echo "\n<tr>";

	   echo "\n<td colspan=\"2\">&nbsp;</td>";

       echo "\n</tr>";



	   echo "\n<tr><td valign=\"top\">";			   

   	   echo "Grandtotal: "; 

	   echo "</td>";

	   echo "<td><b>";

	   echo $n0["count_long_approved"] + $n00["count_short_approved"] + $n2["rowcount_long_non_app"] +$n3["rowcount_short_non_app"] ;

	   echo "\n</b></td></tr>";

		



	   echo "</table>";



	   echo "\n</td></tr>";

	   echo "\n<tr><td valign=\"top\">";



	   # Show non approved's of longlist

	   echo "\n<br><br><table border=\"0\" width=\"100%\">";

	   echo "\n<tr><td bgcolor=\"#E4E4E4\"><b>Longlist Non Approved's</b></td></tr>";

	   while ($long_non_app = mysql_fetch_array($result_non_app_longlist)) {

	   echo "\n<tr><td>" . $long_non_app['nonapplonglist'] . "</td></tr>"; 

	   }

	   echo "\n</table>";	   



	   echo "\n<td valign=\"top\">";

	  

		# Show non approved's of shortlist

	   echo "\n<br><br><table border=\"0\" width=\"100%\">";

	   echo "\n<tr><td bgcolor=\"#E4E4E4\"><b>Shortlist Non Approved's</b></td></tr>";

	   while ($short_non_app = mysql_fetch_array($result_non_app_shortlist)) {

	   echo "\n<tr><td>" . $short_non_app['nonappshortlist'] . "</td></tr>"; 

	   }

	   echo "\n</table>";	  



	   echo "\n</td>";

       echo "\n<tr><td>";



	   # Show subsbribers of Long List

	  

	   echo "\n<br><br><table border=\"0\" width=\"100%\">";

	   echo "\n<tr><td bgcolor=\"#E4E4E4\"><b>Longlist</b></td></tr>";

	   while ($long = mysql_fetch_array($result_longlist)) {

	   echo "\n<tr><td>" . $long['longlist'] . "</td></tr>"; 

	   }

	   echo "\n</table>";	   

	

	   echo "\n</td><td valign=\"top\">";



	   # Show subsbribers of Short List

	   echo "\n<br><br><table border=\"0\" width=\"100%\">";

	   echo "\n<tr><td bgcolor=\"#E4E4E4\"><b>Shortlist<b></td></tr>";

	   while ($short = mysql_fetch_array($result_shortlist)) {

  	   echo "\n<tr><td>" . $short['shortlist'] . "</td></tr>"; 

	   }

	   echo "\n</table>";	   



	   echo "\n</td></tr></table>";



sidefod();

?>			

