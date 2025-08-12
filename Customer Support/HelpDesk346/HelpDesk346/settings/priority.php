<?php
	//Revised on May 17, 2005
	//Revised by C.E.
	//Revision Number 2
	$path = getcwd();
	chdir('..');
	include_once "./checksession.php";
	include_once "./includes/settings.php";
	include_once "./includes/classes.php";
?>
<html>
	<head>
		<title>Helpdesk Priority Add</title>
		<link rel="stylesheet" href="../style.css" type="text/css" />
	</head>
	
	<body>
	<?php
		$ppath = '../';
		include_once "./dataaccessheader.php";
		chdir($path);
	?>
	<table cellpadding="0" cellspacing="0" border="0">
	<form method="POST" action="">
	<?php
		//handle submitted commands
		if (isset($_POST['delete']) && isset($_POST['priority'])) {
			$p = new Priority($_POST['priority']);
			$p->delete();
		}
		else if (isset($_POST['increase']) && isset($_POST['priority'])) {
			$p = new Priority($_POST['priority']);
			$p->IncreaseServerity();
		}
		else if (isset($_POST['decrease']) && isset($_POST['priority'])) {
			$p = new Priority($_POST['priority']);
			$p->DecreaseSeverity();
		}
		else if (isset($_POST['edit']) && isset($_POST['priority'])) {
			echo '<input type="hidden" name="pid" value="' . $_POST['priority'] . '" />' . chr(10);
			$p = new Priority($_POST['priority']);
			$name = $p->get('name', 'stripslashes');
		}
		else if (isset($_POST['submit'], $_POST['pid'])) {
			$p = new Priority($_POST['pid']);
			$p->set('name', $_POST['priorityn'], 'mysql_real_escape_string');
			$p->commit();
		}
		else if (isset($_POST['submit'])) {
			$p = new Priority();
			$p->set('name', $_POST['priorityn'], 'mysql_real_escape_string');
			$p->commit();	
		}
	?>
		<tr><th colspan="2" align="left">
			Please Add a New Priority:
		</th></tr>
		<tr>
			<td>Enter a Priority Name:</td>
			<td><input type="text" name="priorityn" size="20" maxlength="40" value="<?php echo isset($name) ? $name : ''; ?>" />
        	<input type="submit" name="submit" value="<?php echo isset($name) ? 'Change' : 'Add'; ?>" class="button" /></td>
		</tr>
		<tr>
      <td colspan="2" align="center">
      </td>
    </tr>
		
		<tr><td height="5"></td></tr>
		<tr><th colspan="2" align="center">
			Stored Priorities
		</th></tr>
		<tr>
			<td align="right" style="padding-right:7px">
				<select name="priority" size="5">
				<?php
					$q = "select pid from " . DB_PREFIX . "priorities order by severity";
					$s = mysql_query($q) or die(mysql_error());
					while ($r = mysql_fetch_assoc($s))
					{
						$p = new Priority($r['pid']);
						echo '<option value="' . $p->get('pid', 'intval') . '">' . $p->get('name', 'stripslashes') . '</option>';
					}
				?>
				</select>
			</td>
			<td valign="top">
				<input type="submit" name="edit" value="Change Name" style="width:150px" /><br/>
				<!--<input type="submit" name="delete" value="Delete" style="width:150px" /><br/>-->
				<input type="submit" name="increase" value="Increase Severity" style="width:150px" /><br/>
				<input type="submit" name="decrease" value="Decrease Severity" style="width:150px" />
			</td>
		</tr>
		<tr><td colspan="2" class="error" align="center">
		<?php echo isset($error_msg) ? $error_msg : ''; ?><br/>
		<a href="../actmgt.php">Return to Control Panel</a>
		</td></tr>
	</form>
	</table>
	</body>
</html>