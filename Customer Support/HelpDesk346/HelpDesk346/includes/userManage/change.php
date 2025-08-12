<?php
	//Revision Date: July 06, 2005
	//Revised by Jason Farrell
	//Revision Number 2
	
	//More work needs to be done here, this will work for now
	//0 = Reg User
	//1 = Tech
	//2 = Admin
	//Doing this allows for much simpler promotion and demotion functionality
	$user = unserialize($_SESSION['enduser']);
	if (isset($_POST['users']) && (isset($_POST['promote']) || isset($_POST['demote']))) {
		
		//perform the query
		foreach ($_POST['users'] as $id)
		{
			$obj = new User($id);
			if (isset($_POST['promote']))
				$obj->promote();
			else
				$obj->demote();
		}
	}
?>
<table cellpadding="0" cellspacing="0" border="0">
<form method="post" action="">
	<tr><th>
		User Selection:
	</th></tr>
	<tr><td colspan="2">
		Please Select a User:&nbsp;
	</td></tr>
	
	<tr>
		<td valign="top">
			<select name="users[]" size="5" multiple="yes">
			<?php
				$q = "select id from " . DB_PREFIX . "accounts where id <> '" . $user->get('id') . "' order by user";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
				{
					$u = new User($r['id']);
					echo '<option value="' . $u->get('id') . '">' . $u->get('user') . ' [' . $u->getTextSecurityLevel() . ']</option>' . chr(10);
				}
			?>
			</select>
		</td>
		<td colspan="2" align="center">
		<input type="submit" name="promote" value="Promote" class="button" /><br/>
		<input type="submit" name="demote"  value="Demote"  class="button" />
	</td></tr>
</form>
</table>