<?php
	$path = getcwd();
	chdir('..');
	
	include_once "checksession.php";
	include_once "./classes/status.php";
	include_once "./includes/settings.php";
	
	chdir($path);
	
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
?>
<html>
	<head>
		<title>Helpdesk Status Management Program</title>
		<link rel="stylesheet" type="text/css" href="../style.css" />
	</head>
	
	<body>
		<table cellpadding="0" cellspacing="0" border="0">
		<form method="post" action="">
		<?php
			if (isset($_POST['add'], $_POST['status'])) {
				if (strlen($_POST['statname']))	{
					$stat = new Status($_POST['status']);
					$stat->set('name', $_POST['statname'], 'mysql_real_escape_string');
					$stat->commit();
				}
				else {
					$error_msg = "An Invalid Status Name has been Submitted";	
					echo '<input type="hidden" name="status" value="' . $_POST['status'] . '">' . chr(10);
				}
			}
			else if (isset($_POST['add'])) {
				if (strlen($_POST['statname']))	{
					$stat = new Status();
					$stat->set('name', $_POST['statname'], 'mysql_real_escape_string');
					$stat->commit();
				}
				else {
					$error_msg = "An Invalid Status Name has been Submitted";
				}	
			}
			else if (isset($_POST['change'])) {
				if (isset($_POST['statID'])) {
					$stat = new Status($_POST['statID']);
					$name = $stat->get('name', 'stripslashes');
					echo '<input type="hidden" name="status" value="' . $_POST['statID'] . '">' . chr(10);
				}
				else {
					$error_msg = "You Must Select a Status to Change";
				}
			}
			else if (isset($_POST['delete'])) {
				if (isset($_POST['statID'])) {
					$stat = new Status($_POST['statID']);
					$stat->delete();
				}
				else {
					$error_msg = "You Must Select a Status to Delete";	
				}
			}
			else if (isset($_POST['up']) || isset($_POST['down'])) {
				if (isset($_POST['statID'])) {
					$stat = new Status($_POST['statID']);
					if (isset($_POST['up'])) $stat->moveUp();
					else $stat->moveDown();
				}
				else {
					$error_msg = "You Must Select a Status to Move";	
				}	
			}
			else if (isset($_POST['linkc'])) {	
				if (isset($_POST['statID'], $_POST['color'] )) {
					$stat = new Status($_POST['statID']);
					$stat->set('color', $_POST['color'], 'mysql_real_escape_string');
					$stat->commit();
				}
				else {
					$error_msg = "You Must Select a Status and a Color to Link it With";	
				}
			}
			else if (isset($_POST['linki'])) {	
				if (isset($_POST['statID'], $_POST['icon'] )) {
					$stat = new Status($_POST['statID']);
					$stat->set('icon', $_POST['icon'], 'mysql_real_escape_string');
					$stat->commit();
				}
				else {
					$error_msg = "You Must Select a Status and a Icon to Link it With";
				}
			}
		?>
			<tr><td align="center" colspan="3">
			<?php
				$ppath = '../';
				if ($OBJ->get('navigation') == 'B') {
					include_once "../dataaccessheader.php";
				}
				else {
					include_once "../textnavsystem.php";
				}
			?><br/>
				<strong>Helpdesk Tickst Status Management </strong>
			</td></tr>
			<tr><td height="5"></td></tr>
			
			<tr><td colspan="3" class="formtext">
				Enter a Status Name to Add:&nbsp;
				<input type="text" name="statname" size="20" maxlength="30" value="<?php echo isset($name) ? $name : ''; ?>" />&nbsp;
				<input type="submit" name="add" value="<?php echo isset($name) ? 'Change' : 'Add'; ?>" />
			</td></tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td valign="top" align="right">
					<strong>Stored Status Values:&nbsp;&nbsp;</strong><br/>
					<select name="statID" size="7">
					<?php
						$q = "select id from " . DB_PREFIX . "status order by position";
						$s = mysql_query($q) or die(mysql_error());
						if (mysql_num_rows($s))
						{
							while ($r = mysql_fetch_assoc($s))
							{
								$stat = new Status($r['id']);
								echo '<option value="' . $stat->get('id') . '">' . $stat->get('name', 'stripslashes') . ' [' . $stat->get('icon', 'stripslashes') . '] [' . $stat->get('color', 'stripslashes') . ']</option>' . chr(10);
							}	
						}
						else
							echo '<option value="">No Stored Status\'</option>' . chr(10);
					?>
					</select>
				<td valign="top" width="152">
					<input type="submit" name="change" value="Change Name" style="width:150px" /><br/>
					<input type="submit" name="delete" value="Delete Name" style="width:150px" /><br/>
					<input type="submit" name="linki" value="Link Icon" style="width:150px" /><br/>
					<input type="submit" name="linkc" value="Link Color" style="width:150px" /><br/>
					<input type="submit" name="up" value="Move Up" style="width:150px" /><br/>
					<input type="submit" name="down" value="Move Down" style="width:150px" />
				</td>
				<td valign="top">
					<strong>Icons/Colors</strong><BR/>
					<select name="icon" size="3">
						<option value="red.jpg">red.jpg</option>
						<option value="yellow.jpg">yellow.jpg</option>
						<option value="green.jpg">green.jpg</option>
					</select>&nbsp;
					<select name="color" size="3">
						<option value="red">red</option>
						<option value="yellow">yellow</option>
						<option value="green">green</option>
					</select>
				</td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr><td colspan="3" align="center">
				<i>Status are Ordered based on their position in the answer call process</i><br/>
				<?php echo isset($error_msg) ? $error_msg : ''; ?><br/>
				<a href="../actmgt.php">Return to Control Panel</a>
			</td></tr>
		</form>
		</table>
	</body>
</html>