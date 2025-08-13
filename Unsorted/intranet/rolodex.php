<html>
<head></head>
<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
	{
$dn = getenv(REMOTE_HOST);
$hd = getenv(HOME);
$browser = getenv(HTTP_USER_AGENT);
$previous = getenv(HTTP_REFERER);
$computer_email = getenv(HTTP_FROM);
$server = getenv(HTTP_HOST);
$coloration = "#E5DDDD";
$altcolor = "#EEEEEE";
?>

<?php
$appheaderstring='Rolodex';
include("header.php");

if ($searchdata != "") {
	if ($searchtype == "phone") { $searchlabel = "phone number"; }
	if ($searchtype == "lastname") { $searchlabel = "last name"; }
	if ($searchtype == "firstname") { $searchlabel = "first name"; }
	if ($searchtype == "zipcode") { $searchlabel = "zip code"; }
	if ($searchtype == "company") { $searchlabel = "company"; }
	if ($searchtype == "letter") { $searchlabel = "first letter of last name"; }
	if ($searchtype == "id") { $searchlabel = "record number"; }
	dbconnect($dbusername,$dbuserpasswd);
	$sqlquery="select lastname, firstname, title, company, address1, address2, address3, city, state, zipcode, zipplus4, phone, altphone, altphonetype, fax, email, website, comment, id, editby, mediacontact from rolodex where ";
                        if ($searchtype == "letter")   { $sqlquery = $sqlquery .  "left(lastname, 1) = '$searchdata'"; }
                 	if ($searchtype == "lastname") { $sqlquery = $sqlquery . "lastname LIKE '%" . $searchdata . "%'"; }
                 	if ($searchtype == "firstname") { $sqlquery = $sqlquery . "firstname LIKE '%" . $searchdata . "%'"; }
                 	if ($searchtype == "company") { $sqlquery = $sqlquery . "company LIKE '%" . $searchdata . "%'"; }
                 	if ($searchtype == "zipcode") { $sqlquery = $sqlquery . "zipcode LIKE '%" . $searchdata . "%'"; }
                 	if ($searchtype == "phone") { $sqlquery = $sqlquery . "phone = '%" . $searchdata . "%'"; }			
                 	if ($searchtype == "id") { $sqlquery = $sqlquery . "id = '$searchdata'"; }
// if ($contacttype!='Both')
//	{
//      $sqlquery = $sqlquery . " and (contacttype='" . $contacttype . "' or contacttype='Both')";
//	}		

$sqlquery = $sqlquery . " order by lastname";
$result = mysql_query($sqlquery);
$number = mysql_num_rows($result);
echo "<center>";
echo "<center><table border='0' cellpadding='0' cellspacing='0'><tr>";
if ($number > 1) { echo "<td colspan='2'>"; } else { echo "<td>"; }

	echo "You searched for <b>", $searchlabel, "</b> is <b>", $searchdata, "</b>, ";
if ($number == 1) { echo " and 1 matching record was found.<form name='doover' action='rolodex.php' method='post'><input type='submit' value='Search Again'></form>"; }
	elseif ($number == 0)  { echo " and no matching records were found.<form name='doover' action='rolodex.php' method='post'><input type='submit' value='Search Again'></form>"; }
	else { echo " and ", $number, " matching records were found.<form name='doover' action='rolodex.php' method='post'><input type='submit' value='Search Again'></form>"; }
					
	if ($number == "1") {
while ($row = mysql_fetch_array($result))  {
				$row[17] = nl2br($row[17]);
				echo "<tr><td bgcolor='666666'><font color='white'><b>", $row[1], " ", $row[0], "</td><td align='right' bgcolor='666666'><font size='2' color='white'>[<a href='editrolodex.php?edittype=edit&medit=no&id=", $row[18], "'><font size='2' color='white'>EDIT</font</a>]</font></tr>";
				if ($row[2] != "") { echo "<tr><td colspan='2'>", $row[2], "</td></tr>"; }
				if ($row[3] != "") { echo "<tr><td colspan='2'>", $row[3], "</td></tr>"; }
				if ($row[4] != "") { echo "<tr><td colspan='2'>", $row[4], "</td></tr>"; }
				if ($row[5] != "") { echo "<tr><td colspan='2'>", $row[5], "</td></tr>"; }
				if ($row[6] != "") { echo "<tr><td colspan='2'>", $row[6], "</td></tr>"; }
				if ($row[7] != "") { echo "<tr><td colspan='2'>", $row[7], ", ", $row[8], "</td></tr>"; }
				if ($row[9] != "") { echo "<tr><td colspan='2'>", $row[9];
                                                if ($row[10] !="") { echo "-", $row[10]; }
						echo "</td></tr>";
						}
				if ($row[11] != "") { echo "<tr><td colspan='2'>Phone: ", $row[11]; }

				if ($row[12] != "") { echo " or ", $row[12], " (", $row[13], ")"; }
				if ($row[11] != "") { echo "</td></tr>"; }

				if ($row[14] != "") { echo "<tr><td colspan='2'>Fax: ", $row[14], "</td></tr>"; }
				if ($row[15] != "") { echo '<tr><td colspan="2"><a href="mailto:', $row[15], '">', $row[15], '</a></td></tr>'; }
				if ($row[16] != "") { echo "<tr><td colspan='2'><a href='", $row[16], "'>", $row[16], "</a></td></tr>"; }
				if ($row[17] != "") { echo "<tr><td colspan='2'>", $row[17], "</td></tr>"; }
				if ($row[mediacontact] == "y") { echo "<tr><td colspan='2'><b>Media Contact</b></td></tr>"; }
				echo "<tr><td align='right'><font size='1'>[", $row[19], "]</td></tr>";
			    }
			}
	if ($number > 1) {
		echo "<tr><td bgcolor='666666' colspan='2'><font color='white'>Click on the name to see more.</font></td></tr>";
		while ($row = mysql_fetch_row($result))
				{
				echo "<tr><td> &nbsp; &nbsp; &nbsp; </td><td><a href='rolodex.php?searchtype=id&searchdata=", $row[18], "&contacttype=Both'>", $row[0], ", ", $row[1], "</a> (", $row[2], ", ", $row[3], ")</td></tr>";
				}
                            }
			} else {
echo "<center><table border='0' cellpadding='0' cellspacing='0'><tr><td>";
echo "<form name=bongo action='rolodex.php' method='post'>";
echo "<select name='searchtype'><option value='lastname'>Last Name<option value='firstname'>First Name<option value='phone'>Phone Number<option value='company'>Company<option value='zipcode'>Zip Code</select>";
/* echo "<input type='text' value='lastname' name='searchtype'>";  */
echo " <b>is</b> <input type='text' value='' name='searchdata'> ";
// echo "<br>Limit to contacts who are <select name='contacttype'><option value='Normal'>normal<option value='Prospect'>prospects<option value=Both'>in the rolodex</select>";
echo "<input type='submit' value='Search'></form>";
echo "<font size='", $setting[heading_fontsize], "' face='", $setting[heading_fontface], "'><b>";
echo "<blockquote><a href='rolodex.php?searchtype=letter&searchdata=A'>A</a> &nbsp;";
echo "<a href='rolodex.php?searchtype=letter&searchdata=B&contacttype=normal'>B</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=C&contacttype=normal'>C</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=D&contacttype=normal'>D</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=E&contacttype=normal'>E</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=F&contacttype=normal'>F</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=G&contacttype=normal'>G</a><p>";
echo "<a href='rolodex.php?searchtype=letter&searchdata=H&contacttype=normal'>H</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=I&contacttype=normal'>I</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=J&contacttype=normal'>J</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=K&contacttype=normal'>K</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=L&contacttype=normal'>L</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=M&contacttype=normal'>M</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=N&contacttype=normal'>N</a><p>";
echo "<a href='rolodex.php?searchtype=letter&searchdata=O&contacttype=normal'>O</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=P&contacttype=normal'>P</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=Q&contacttype=normal'>Q</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=R&contacttype=normal'>R</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=S&contacttype=normal'>S</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=T&contacttype=normal'>T</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=U&contacttype=normal'>U</a><p>";
echo "<a href='rolodex.php?searchtype=letter&searchdata=V&contacttype=normal'>V</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=W&contacttype=normal'>W</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=X&contacttype=normal'>X</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=Y&contacttype=normal'>Y</a> &nbsp; ";
echo "<a href='rolodex.php?searchtype=letter&searchdata=Z&contacttype=normal'>Z</a></b></font>";
echo "<p align='right'><form action='editrolodex.php' method='post' name='fern'><input type='hidden' name='edittype' value='new'><input type='hidden' name='medit' value='no'><input type='submit' value='Add a Record'></form></p></blockquote>";
				}
?>
</td></tr></table></center></td></tr></table></center></body></html>
<?php
	} ?>
