<?php
	//Revised on May 25, 2005
	//Revised by JF
	//Revision Number 1

	$path = getcwd();
	chdir('..');
	include_once './config.php';
	include_once "./includes/classes.php";
	chdir($path);
	
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
	
	include_once "./includes/functions.php";
	#checkKBVisibility();
	
	$q = "select id from " . DB_PREFIX . "data where id = " . intval($_GET['id']) . " LIMIT 1";
	$s = mysql_query($q) or die(mysql_error());
	
	if (mysql_num_rows($s)) {
		//data extraction
		$t = new Ticket(mysql_result($s, 0));
		
		//update the pageview for the ticket
		$cmd = "update " . DB_PREFIX . "data set pageView = pageView + 1 where id = " . $t->get('id', 'intval');
		mysql_query($cmd) or die("update error");
	}
	else {
		die("Invalid ID Number Submitted");	
	}
?>
<html>
	<head>
		<title>Viewing Ticket #<?php echo $t->get('id', 'intval'); ?></title>
		<link rel="stylesheet" href="./styles.css" type="text/css" />
		<style type="text/css">
			td {
				vertical-align: top;
				border-bottom: 1px solid black;
			}
			
			BODY {
				background-color: white;
			}
			
			.spacer {
				width: 20px;
			}
		</style>
	</head>
	
	<body>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr><th align="left" colspan="3">
				Viewing Information for Ticket #<?php echo $t->get('id', 'intval'); ?>
			</th></tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>First Name:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo printData($t->get('FirstName', 'stripslashes')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Last Name:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo printData($t->get('LastName', 'stripslashes')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>E-Mail Address:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo printData($t->get('EMail', 'stripslashes')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Phone Number:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo formatPhone($t->get('phoneNumber', 'intval')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Phone Extension:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo printData($t->get('phoneExt', 'intval')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Ticket Status:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<?php $status = $t->get('status'); ?>
				<td class="data"><?php echo printData($status->get('name', 'stripslashes')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Ticket Priority:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<?php $priority = $t->get('priority'); ?>
				<td class="data"><?php echo printData($priority->get('name', 'stripslashes')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Date of Ticket:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo date("l, F jS Y - h:ia", strtotime($t->get('mainDate','stripslashes'), time())); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr><th align="left" colspan="3">
				Ticket Technical Information
			</th></tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Problem Catagory:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo printData($t->get('PCatagory', 'stripslashes')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Associated Part:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo printNumber($t->get('partNo', 'intval')); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td>Problem Description:&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data"><?php echo stripslashes(printData($t->get('descrip', 'nl2br'))); ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			
			<tr>
				<td><b>Resolution(s):&nbsp;</td>
				<td class="spacer">&nbsp;</td>
				<td class="data">
				<?php
					$q = "select solution, resDate from " . DB_PREFIX . "resolution where id = " . $t->get('id', 'intval');
					$s = mysql_query($q) or die("resolution query failed");
					if (!mysql_num_rows($s)) {
						echo '<span style="color: red">No Resolutions Found</span>' . chr(10);	
					}
					else {
						while ($r = mysql_fetch_assoc($s))
						{
							echo nl2br($r['solution']) . "<br/>\n";
							echo '<span style="color: black;">' . date("M/j/Y h:ia", strtotime($r['resDate'], time())) . '</span>';
							echo "<br/><br/>\n\n";
						}
					}
				?>
				</td>
			</tr>
			<tr><td height="5"></td></tr>
			<tr><td colspan="3" align="center">
				<a href="index.php?type=key">Return to Knowledge Base Main</a>
			</td></tr>
		</table>
	</body>
</html>
<?php
	function printData($val)
	{
		return (empty($val)) ? 'Unknown' : $val;	
	}
	
	function formatPhone($input)
	{
		if (intval($input) <= 0) return 'Unknown';
		return substr($input, 0, 3) . "-" . substr($input, 3, 3) . "-" . substr($input, 6);
	}
	
	function printNumber($num)
	{
		if (intval($num) <= 0) return 'Unknown';
		return $num;
	}
?>