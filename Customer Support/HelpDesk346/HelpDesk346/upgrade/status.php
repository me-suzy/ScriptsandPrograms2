<?php
	session_start();
	
	mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpass']);
	mysql_select_db($_SESSION['dbname']);
	
	define('DB_PREFIX', $_SESSION['prefix'] . "_");
	
	$path = getcwd();
	chdir('..');
	include_once "./includes/classes.php";
	chdir($path);
	
	include_once "./files/process3.php";
?>
<html>
	<head>
		<title>Performing Stage 3 of 6 - Status Modification</title>
	</head>
	
	<body>
		<div>
			The old system used a text based storage method for the status of a ticket. While a workable solution it does not allow for a lot of
			flexibility which is critical to allowing precise control over how things are handled. The new system uses a numeric based
			storage system that solves this problem.<br/>
			<br/>
			Your job is to give appropriate names for the stored statuses as they appear in the current database. You can use the
			position measure to verify they correctly represent your Helpdesk Ticket Resolution procedure.
		</div>
		<hr/>
		<table cellpadding="0" cellspacing="0" border="0">
		<form method="post" action="">
			<tr>
				<td valign="top">
					<b>Gathered Statuses:</b><br/>
					<select name="selNames" size="5">
					<?php
						$q = "select id from " . $_SESSION['prefix'] . "_status order by position";
						$s = mysql_query($q) or die(mysql_error());
						while ($r = mysql_fetch_assoc($s))
						{
							$stat = new Status($r['id']);
							echo '<option value="' . $stat->get('id', 'intval') . '">' . $stat->get('name', 'stripslashes') . ' [' . $stat->get('icon', 'stripslashes') . '] [' . $stat->get('color', 'stripslashes') . ']</option>' . chr(10);
						}
					?>
					</select>
				</td>
				<td width="10"></td>
				<td valign="top">
					<br/>
					<input type="submit" name="edit" value="Edit Status Label" /><br/>
					<input type="submit" name="up" value="Increase Position" /><br/>
					<input type="submit" name="down" value="Decrease Position" /><br/>
					<input type="submit" name="iconlink" value="Link to Icon" /><br/>
					<input type="submit" name="colorlink" value="Link to Color" /><br/>
					<input type="submit" name="move" value="Proceed to Next Step" />
				</td>
				<td width="10"></td>
				
				<td valign="top">
					<b>Available Icons:</b><br/>
					<select name="selIcons" size="5">
					<?php
						foreach ( $iconArray as $icon)
							echo '<option value="' . $icon . '">' . $icon . '</option>' . chr(10);	
					?>
					</select>
				</td>
				<td width="10"></td>
				
				<td valign="top">
					<b>Available Colors:</b><br/>
					<select name="selColors" size="5">
					<?php
						foreach ($colorArray as $color)
							echo '<option value="' . $color . '">' . $color . '</option>' . chr(10);
					?>
					</select>
				</td>
			</tr>
			<?php
				if (isset($_POST['selNames'], $_POST['edit'])) {
			?>
			<Tr><td height="7"></td></tr>
			<tr><td colspan="6">
				<input type="text" name="newName" size="20" maxlength="50" value="<?php $stat->get('name', 'stripslashes'); ?>" />
				<input type="submit" name="add" value="Update Label" />
				<input type="hidden" name="id" value="<?php echo $_POST['selNames']; ?>" />
			</td></tr>
			<?php	
				}
			?>
			<tr><td colspan="3" align="center" style="color:red">
			<?php echo isset($error_msg) ? $error_msg : ''; ?>
			</td></tr>
		</form>
		</table>
		<hr/>
	</body>
</html>