<?php
	include_once("checksession.php");
?>
<html>
	<head>
		<title>Dumping Excess Data for Selected Hardware Property</title>
		<?php
			//Intval will prevfent SQL injection
			$q = "select * from " . DB_PREFIX . "excess where id = " . intval($_GET['ID']);
			$s = mysql_query($q) or die(mysql_error());
			$r = mysql_fetch_assoc($s);
			
			//Assignment
			$id      = $r['ID'];
			$fname   = $r['FirstName'];
			$lname   = $r['LastName'];
			$PartNum = $r['partNum'];
			$serial  = $r['serial'];
			$location= $r['location'];
			$descrip = $r['descrip'];
			$date    = $r['date'];
			$price	 = $r['price'];
		?>
	</head>
	
	<body>
		<h1>Dumping Data for Part No. <?php echo $PartNum; ?></h1>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>First Name:</td>
				<td><?php echo $fname; ?></td>
			</tr>
			<tr>
				<td>Last Name:</td>
				<td><?php echo $lname; ?></td>
			</tr>
			<tr>
				<td>Part Number:</td>
				<td>#<?php echo $PartNum; ?></td>
			</tr>
			<tr>
				<td>Serial:</td>
				<td>#<?php echo $serial; ?></td>
			</tr>
			<tr>
				<td>Location:</td>
				<td><?php echo $location; ?></td>
			</tr>
			<tr>
				<td valign="top">Description:</td>
				<td valign="top"><?php echo stripslashes($descrip); ?></td>
			</tr>
			<tr>
				<td>Date:</td>
				<td><?php echo $date; ?></td>
			</tr>
			<tr>
				<td>Price:</td>
				<td>$<?php echo number_format($price); ?>
			</tr>
		</table>
		<div align="center">
			<a href="DataAccess.php">Return to HelpDesk Main</a>
		</div>
	</body>
</html>