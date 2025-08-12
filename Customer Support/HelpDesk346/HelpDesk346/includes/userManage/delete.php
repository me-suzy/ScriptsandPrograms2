<?php
	//Revision Date: May 04, 2005
	//Revised by Jason Farrell
	//Revision Number 1
	
	if (isset($_POST['confirm'])) {
		$user = new User($_POST['toDelete']);
		$user->delete();
		echo "User Delete Successfully<br/>\n";
		echo '<a href="view_users.php">Click Here to Return to User Control</a>' . chr(10);
	}
	elseif (isset($_POST['submit'])) {
		//obtain the username based on the passed id
		$name = mysql_result(
					mysql_query("select user from " . DB_PREFIX . "accounts where id = " . intval($_POST['toDelete']) . " LIMIT 1"),
					0,
					'user'
				);
?>
	<table cellpadding="0" cellspacing="0" border="0">
	<form method="post" action="">
		<input type="hidden" name="toDelete" value="<?php echo $_POST['toDelete']; ?>" />
		<tr><th>
			Confirm Delete of User: <?php echo $name; ?>
		</th></tr>
		<tr><td align="center">
			<input type="submit" name="confirm" value="Confirm Delete" class="button" />
		</td></tr>
	</form>
	</table>
<?php
		
	}
	else {
?>
<table cellpadding="0" cellspacing="0" border="0">
<form method="post" action="">
	<tr>
		<th align="left">Select a User to Delete:&nbsp;</th>
		<td>
			<select name="toDelete" size="1">
			<?php
				$q = "select id, user from " . DB_PREFIX . "accounts where user <> '" . mysql_real_escape_string($_COOKIE['record2']) . "' order by User";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
					echo '<option value="' . $r['id'] . '">' . $r['user'] . '</option>' . chr(10);	
			?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2" align="center">
		<input type="submit" name="submit" value="Delete User" class="button" />
	</td></tr>
</form>
</table>
<?php
	}
?>