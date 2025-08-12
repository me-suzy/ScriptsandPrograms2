<html>
<head>
<title>Checkbook</title>
</head>
<body>
<center>
<big>Checkbook</big><p>
<?

#########
# MYSQL #
#########

// Include the configuration file
include ("config.php");

// Connect to MySQL
$Link = mysql_connect($sql_host, $sql_user, $sql_pass) or die(mysql_error());

// Select the database
mysql_select_db($sql_db);

#################
# TOTAL BALANCE  #
#################

// Find the total balance
$totalbalance = 0;
$SelectAllQuery = "SELECT * FROM checkbook ORDER BY id";
$AllResult = mysql_query($SelectAllQuery);
while ($Row = mysql_fetch_array($AllResult)) {

	if ($Row[Type] == "Deficit") {
		$totalbalance =  $totalbalance - $Row[Number];
	} else {
		$totalbalance = $totalbalance + $Row[Number];
	}
}

###############
# MORE MYSQL #
###############

// Find how many transactions there have been
$NumRows = mysql_num_rows($AllResult);

if (!$show) {
	$show = "10";
}

if ($NumRows <= 10) {
	$LimitStart = "0";
} else {
	$LimitStart = $NumRows - $show;
}

// Create the query to get the transaction's information from the database
$SelectQuery = "SELECT * FROM checkbook ORDER BY id LIMIT $LimitStart, $show";


// Run the query to get the transaction's information from the database
$Result = mysql_query($SelectQuery);

###################
# VIEWING OPTIONS #
###################

$CurrentNumRows = mysql_num_rows($Result);
echo "<font size=\"2\">Currently viewing last <b>$CurrentNumRows</b> transactions.</font><br>\n";

if (($NumRows >= 5) && ($show != "5") && ($CurrentNumRows != "5"))  {
	echo "<font size=\"2\"><a href=\"?show=5\">Show last 5 transactions</a></font>\n ";
}

if (($NumRows >= 10) && ($show != "10") && ($CurrentNumRows != "10")) {
	echo " <font size=\"2\"><a href=\"?show=10\">Show last 10 transactions</a></font>\n ";
}

if (($NumRows >= 20) && ($show != "20") && ($CurrentNumRows != "20")) {
	echo " <font size=\"2\"><a href=\"?show=20\">Show last 20 transactions</a></font>\n ";
}

if (($NumRows >= 30) && ($show != "30") && ($CurrentNumRows != "30")) {
	echo " <font size=\"2\"><a href=\"?show=30\">Show last 30 transactions</a></font><p>\n";
}

if (($NumRows >= 40) && ($show != "40") && ($CurrentNumRows != "40")) {
	echo " <font size=\"2\"><a href=\"?show=40\">Show last 40 transactions</a></font><p>\n";
}

if (($NumRows >= 50) && ($show != "50") && ($CurrentNumRows != "50")) {
	echo " <font size=\"2\"><a href=\"?show=50\">Show last 50 transactions</a></font><p>\n";
}

if (($NumRows >= 60) && ($show != "60") && ($CurrentNumRows != "60")) {
	echo " <font size=\"2\"><a href=\"?show=60\">Show last 60 transactions</a></font><p>\n";
}

##############
# BEGIN TABLE #
##############

// Begin the table to show transaction information
echo "<table align=\"center\" border=\"1\" bordercolor=\"#000000\">\n";
echo "<tr>\n";
echo "<td><b>Date</b></td>\n";
echo "<td><b>To The Order Of</b></td>\n";
echo "<td><b>Amount</b></td>\n";

// Notify user if no transactions are found in the checkbook
if ($NumRows == "0") {
	echo "<tr>\n";
	echo "<td align=\"center\" colspan=\"4\"><i>There are currently no transactions in your checkbook.</i></td>\n";
	echo "</tr>\n";
}

// Show a table row for each transaction containing each one's information
while ($Row = mysql_fetch_array($Result)) {

	echo "<tr>\n";
	echo "<td>$Row[Date]</td>\n";
	echo "<td>$Row[For]</td>\n";

	// Change the font color depending on if the transaction was credit or deficit
	if ($Row[Type] == "Deficit") {
		echo "<td><font color=\"red\">- \$$Row[Number]</font></td>\n";
	} else {
		echo "<td><font color=\"green\">+ \$$Row[Number]</font></td>\n";
	}

	echo "</tr>\n";
}

#############
# END TABLE  #
#############

// Make sure there's two numbers in the amount of change (not something like $1.5)
if (eregi("^(-)?[0-9]+\..$", $totalbalance)) {
	$totalbalance .= "0";
}

// Show total number of transactions
echo "<tr>\n";
echo "<td align=\"center\" colspan=\"4\">Total Transactions: <b>$NumRows</b></td>\n";
echo "</tr>\n";

// Show the current overall balance based on all the transactions
echo "<tr>\n";
echo "<td align=\"center\" colspan=\"4\">\n";
if ($totalbalance < "0") {
	echo "Current Balance: <font color=\"red\"><b>\$$totalbalance</b></font>\n";
} elseif ($totalbalance == "0") {
	echo "Current Balance: <font color=\"orange\"><b>\$$totalbalance</b></font>\n";
} else {
	echo "Current Balance: <font color=\"green\"><b>\$$totalbalance</b></font>\n";
}
echo "</td>\n";
echo "</tr>\n";

// Finish the table
echo "</table><p><br>\n";

#######
# END #
#######

// Close the MySQL connection
mysql_close($Link);

?>
<a href="javascript:location='<? echo "$PHP_SELF"; ?>'; window.open('add.php', 'characters', 'height=300, width=300, scrollbars=no, resizable=yes, toolbar=no, status=Edit Note, menubar=no')">&lt; Add Transaction &gt;</a><br>
<a href="<? echo "$PHP_SELF"; ?>">&lt; Refresh Page &gt;</a><p><br>
</center>
</body>
</html>