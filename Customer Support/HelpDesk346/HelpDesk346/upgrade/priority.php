<?php
	session_start();
	
	mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpass']);
	mysql_select_db($_SESSION['dbname']);
	
	define('DB_PREFIX', $_SESSION['prefix'] . "_");
	
	$path = getcwd();
	chdir('..');
	include_once "./includes/classes.php";
	chdir($path);
	
	include_once "./files/process2.php";
?>
<html>
	<head>
		<title>Performing Stage 2 of 6 - Priority Modification</title>
	</head>
	
	<body>
		<div>
			The old system used a text based storage method for priorities. While a workable solution it does not allow for a lot of
			flexibility which is critical to allowing precise control over how things are handled. The new system uses a numeric based
			storage system that solves this problem.<br/>
			<br/>
			Your job is to give appropriate names for the stored priorities as they appear in the current database. You can use the
			severity measure to order them in whatever order you see fit.  The higher the severity the higher the priority. This allows
			you to filter out critical and non-critical problems
		</div>
		<hr/>
		<table cellpadding="0" cellspacing="0" border="0">
		<form method="post" action="">
			<tr>
				<td valign="top">
					<b>Gathered Priorities:</b><br/>
					<select name="selNames" size="5">
					<?php
						$q = "select pid from " . $_SESSION['prefix'] . "_priorities order by severity";
						$s = mysql_query($q) or die(mysql_error());
						while ($r = mysql_fetch_assoc($s))
						{
							$p = new Priority($r['pid']);
							echo '<option value="' . $p->get('pid', 'intval') . '">' . $p->get('name', 'stripslashes') . '</option>' . chr(10);
						}
					?>
					</select>
				</td>
				<td width="10"></td>
				<td valign="top">
					<br/>
					<input type="submit" name="edit" value="Edit Priority Label" /><br/>
					<input type="submit" name="up" value="Increase Severity" /><br/>
					<input type="submit" name="down" value="Decrease Severity" /><br/>
					<input type="submit" name="move" value="Proceed to Next Step" />
				</td>
			</tr>
			<?php
				if (isset($_POST['selNames'], $_POST['edit'])) {
			?>
			<Tr><td height="7"></td></tr>
			<tr><td colspan="3">
				<input type="text" name="newName" size="20" maxlength="50" value="<?php $p->get('name', 'stripslashes'); ?>" />
				<input type="submit" name="add" value="Update Label" />
				<input type="hidden" name="pid" value="<?php echo $_POST['selNames']; ?>" />
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