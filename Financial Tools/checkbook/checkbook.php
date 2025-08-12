<?php
error_reporting (E_ERROR | E_WARNING | E_PARSE );

/* 
Feel free do use this program for anything except commercial use. 
If you want to incorporate this code into a product you wish to sell, please contact me first.
eltonwilson.com
ewilson@Hitready.com

Must edit the db1.php for your specific database


*/



include 'db1.php';
$today = date("n/d/y");
$option = "entry";
if (($deposit) && ($withdrawal)) {
	echo "<h4>silly, you cant have a deposit and withdrawal</h4>";
	exit;
	}


if (isset($entry)) {
if ((!$deposit) && (!$withdrawal)) {
	echo "<h4>silly, You need to enter a deposit or withdrawal</h4>";
	exit;
	}


$option = "entry";
$query="SELECT * FROM checkbook ORDER BY entry";
$result=mysql_query($query);
$num=mysql_num_rows($result);

for($i=0; $i<$num; $i++)  {
	$subbalance=mysql_result($result,$i,"balance");
}
	if ($withdrawal) {
		$withdrawal =  ereg_replace(",","",$withdrawal);
		$balance = $subbalance - $withdrawal;
		} elseif ($deposit) {
		$deposit = ereg_replace(",","",$deposit);
		$balance = $subbalance + $deposit;
	}

$query = mysql_query("INSERT INTO checkbook (date, number, subject, payee, withdrawal, deposit, balance)
	VALUES ('$date', '$number', '$subject', '$payee', '$withdrawal', '$deposit', '$balance')") 
	or die (mysql_error()); 
} elseif ($delete) {
$query = mysql_query("DELETE FROM checkbook WHERE entry = '$id'")
	or die (mysql_error()); 
	$delete = update_balance($id);
	if ($delete != true) {
		echo "update failed";
		exit;
	}
} elseif ($edit) {
$option = "editnow";	
$query="SELECT * FROM checkbook WHERE entry = '$id'";
$result=mysql_query($query);
$num=mysql_num_rows($result);
if (!$num) {echo "error retrieving entry $id";}

	for($i=0; $i<$num; $i++)  {
		$entry=mysql_result($result,$i,"entry");
		$date=mysql_result($result,$i,"date");
		$number=mysql_result($result,$i,"number");
		$subject=mysql_result($result,$i,"subject");
		$payee=mysql_result($result,$i,"payee");
		$withdrawal=mysql_result($result,$i,"withdrawal");
		$deposit=mysql_result($result,$i,"deposit");
		$balance=mysql_result($result,$i,"balance");	
		
	}
} elseif ($editnow) {
if ((!$deposit) && (!$withdrawal)) {
	echo "<h4>silly, You need to enter a deposit or withdrawal</h4>";
	exit;
	}
$query = mysql_query("UPDATE checkbook SET date = '$date', 
	number = '$number', 
	subject = '$subject',
	payee = '$payee',
	withdrawal = '$withdrawal',
	deposit = '$deposit'
	WHERE entry = '$id'")
or die (mysql_error()); 	
	$delete = update_balance($id);
	if ($delete != true) {
		echo "update failed";
		exit;
	}
} elseif ($delete1) {
	echo "<h4>Are you sure you want to<a href=\"checkbook.php?delete=delete1&id=$id\">DELETE</a> $id<br><br>";
	exit;
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">    

<LINK REL=STYLESHEET TYPE="text/css" HREF="checkbook.css">


<head>
<title>My Checkbook</title>


</head>
<body>
<a href="checkbook.php">home</a><br>


<form action="checkbook.php" method="get">
<table id="enter" border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td>Date</td>
		<td>Number</td>
		<td>Subject</td>
		<td>Payee</td>
		<td>Withdrawal</td>
		<td>Deposit</td>
		
	</tr>
	<tr>
		<td><input type="text" name="date" value="<? echo "$today"; ?>" size="6"></td>
		<td><input type="text" name="number" size="4" value="<? echo "$number"; ?>"></td>
		<td><input type="text" name="subject" size="15" value="<? echo "$subject"; ?>"></td>
		<td><input type="text" name="payee" size="15" value="<? echo "$payee"; ?>"></td>
		<td><input type="text" name="withdrawal" size="10" value="<? echo "$withdrawal"; ?>"></td>
		<td><input type="text" name="deposit" size="10" value="<? echo "$deposit"; ?>"></td>
		<input type="hidden" name="id" value="<? echo "$entry"; ?>">
		<td><input type="submit" name="<? echo "$option"; ?>" value="go" class="entry"></td>
	</tr>
	
	

</table>	
</form>
	
<div id="display">
<table id="tabledisplay" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>Entry</td>
		<td>Date</td>
		<td>Number</td>
		<td>Subject</td>
		<td>Payee</td>
		<td>Withdrawal</td>
		<td>Deposit</td>
		<td>Balance</td>
	</tr>
		

<?
//Grab entriest to Checkbook
$query="SELECT * FROM checkbook ORDER BY entry DESC LIMIT 100";
$result=mysql_query($query);
$num=mysql_num_rows($result);
for($i=0; $i<$num; $i++) {
	$entry=mysql_result($result,$i,"entry");
	$date=mysql_result($result,$i,"date");
	$number=mysql_result($result,$i,"number");
	$subject=mysql_result($result,$i,"subject");
	$payee=mysql_result($result,$i,"payee");
	$withdrawal=sprintf("%01.2f",mysql_result($result,$i,"withdrawal"));
	$deposit=sprintf("%01.2f",mysql_result($result,$i,"deposit"));
	$balance=sprintf("%01.2f",mysql_result($result,$i,"balance"));	

	if ($z == 0) {
		$class = "color1";
		$z = 1;
	} else {
		$class = "color2";
		$z = 0;
	}
	
	
 echo"
 <tr class=\"$class\">
 	<td>$entry</td>
 	<td>$date</td>
 	<td>$number</td>
 	<td>$subject</td>
 	<td>$payee</td>
 	<td>$withdrawal</td>
 	<td>$deposit</td>
 	<td><b>$$balance</b></td>
 	<td><font size=1><a href=\"checkbook.php?edit=edit&id=$entry\">Edit</a></font></td>
 	<td><font size=1><a href=\"checkbook.php?delete1=delete1&id=$entry\">Delete</a></font></td>
 </tr>";
}

?>
</table>
</div>
</body>
</html>


<?
function update_balance() {
	$lastbalance[0]=0;
	$query="SELECT * FROM checkbook ORDER BY entry";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	
	for($i=0; $i<$num; $i++)  {
		$entry=mysql_result($result,$i,"entry");
		$subbalance=mysql_result($result,$i,"balance");
		$withdrawal=mysql_result($result,$i,"withdrawal");
		$deposit=mysql_result($result,$i,"deposit");
		    if ($i != 0) {
		   	   	$k = $i-1;
		   	}
			if ($withdrawal) {
					
					$withdrawal =  ereg_replace(",","",$withdrawal);
					$balance = $lastbalance[$k] - $withdrawal;
				} elseif ($deposit) {
					
					$deposit = ereg_replace(",","",$deposit);
					$balance = $lastbalance[$k] + $deposit;
				}
		$query = mysql_query("UPDATE checkbook SET balance = '$balance' 
			WHERE entry = '$entry'")
			or die (mysql_error()); 
		//echo "Number $i <br> lb:$lastbalance[$k] and d:$deposit w:$withdrawal B: $balance<br>";	
	
	$lastbalance[$i] = $balance;
	$deposit = "";
	$withdrawal = "";	
	
	
	}
	return true;
	
}

?>

