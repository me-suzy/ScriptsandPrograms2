<html>
<head>
<title>Checkbook - Add Transaction</title>
</head>
<body>
<center>
<big>Add Transaction</big><p>
<?

// Get the current date [YYYY-MM-DD]
$TheDate = date("Y-m-d");

// Process the form if it was submitted
if (($do == "add") && (is_numeric($Amount)) && ($Date) && ($Type) && ($For)) {

	// Include the configuration file
	include ("config.php");

	// Connect to MySQL
	$Link = mysql_connect($sql_host, $sql_user, $sql_pass) or die(mysql_error());

	// Select the database
	mysql_select_db($sql_db);

	// Create the query to add the transaction
	$AddQuery = "INSERT INTO checkbook VALUES ('0', '$Type', '$Date', '$For', '$Amount')";

	// Run the query to add the transaction
	if (mysql_query($AddQuery)) {
		echo "The transaction ($For) was successfully added to your checkbook.\n";
	} else {
		echo "The transaction ($For) could not be added to your checkbook.<p>\nmysql_error()\n";
	}

	// Close the MySQL connection
	mysql_close($Link);

} else {
?>
<form method="post" action="">
<table>

<tr>
<td>Date</td>
<td><input type="text" name="Date" value="<? echo "$TheDate"; ?>" maxlength="10"></td>
</tr>

<tr>
<td>Type</td>
<td>
<select name="Type">
<option></option>
<option value="Deficit">Deficit</option>
<option value="Credit">Credit</option>
</select>
</td>
</tr>

<tr>
<td>To The Order Of</td>
<td><input type="text" name="For" maxlength="50"></td>
</tr>

<tr>
<td>Amount</td>
<td><input type="text" name="Amount" value="0.00"></td>
</tr>

<tr>
<input type="hidden" name="do" value="add">
<td colspan="2"><input type="submit"value="Add Transaction"></td>
</tr>

</table>
</form>
<? } ?><p><br>
<a href="" onclick="window.close();"><font size="1">&lt; Close Window &gt;</font></a>
</center>
</body>
</html>