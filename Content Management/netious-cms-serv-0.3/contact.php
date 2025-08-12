<?

/* **************************************************************
Inclusion of the 'include' file containing the functions' definitions
and the db file which stores the DB parameters. Afterwards: 
Call of the DBInfo function which makes the DB pars accessible
for the script and connection to the DB.
************************************************************** */

require("include.php");
require("db.php");

DBInfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");


if (!isset($email)) $email="";
if (!isset($name)) $name="";
if (!isset($message)) $message="";
if (!isset($action)) $action="0";



/* Create the page */

commonheader($pageid,$title,$keywords,$description,$forcedid);
bodybegin($width, $bodyposition);
logobar($logoname,$textlogo);


if ($model!="vv") {mainmenu($pageid,$model);

$contwidth="100%"; 
$side="no";} else {$contwidth="80%";
			$side="yes";}





echo "
<table width=\"100%\">
	<tr>";
	
if ($side=="yes")
	{echo "
		<td width=\"20%\" align=\"center\" valign=\"top\">";
		if ($model=="vv") mainmenu($pageid,$model);
		echo "<br />";
		echo"
		</td>";
	}
	

echo " 
		<td width=\"$contwidth\" align=\"center\" valign=\"top\">
		<table width=\"90%\">
			<tr>
				<td valign=\"top\" align=\"center\">
				<br/>


				<h1 align=\"center\">Contact us</h1>";

if ($action=="1") echo "<b style=\"color:red\">Write your name</b>";
if ($action=="2") echo "<b style=\"color:red\">What is your e-mail address?</b>";
if ($action=="3") echo "<b style=\"color:red\">Invalid e-mail</b>";
if ($action=="4") echo "<b style=\"color:red\">The message is empty!</b>";

	echo "
	<form action=\"mail.php\" method=\"post\">
	<table width=\"70%\" cellspacing=\"5\" cellpadding=\"5\">
	<tr>
	<td align=\"left\" style=\"border:solid 1px #cccccc\" width=\"100px\">
	Your name:
	</td>
	<td align=\"left\" style=\"border:solid 1px #cccccc\">
	<input type=\"text\" name=\"name\" value=\"$name\" size=\"40\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\" style=\"border:solid 1px #cccccc\">
	Your e-mail:
	</td>
	<td align=\"left\" style=\"border:solid 1px #cccccc\">
	<input type=\"text\" name=\"email\" value=\"$email\" size=\"40\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\" style=\"border:solid 1px #cccccc\">
	Message text:
	</td>
	<td align=\"left\" style=\"border:solid 1px #cccccc\">
	<textarea cols=\"40\" rows=\"8\" name=\"message\">$message</textarea>
	</td>
	</tr>
	<tr>
	<td colspan=\"2\" align=\"center\">
	<input type=\"submit\" value=\"Send\" />
	</td>
	</tr>
	</table>
	</form>



				<br /><br />
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
";




bodyend($thisurl);
commonfooter();



?>