<?php $tid = isset($_POST['subTickID']) ? $_POST['subTickID'] : 0; ?>
<!-- Ticket Lookup Form Table -->
<table cellpadding="0" cellspacing="0" border="0" align="center">
<form method="post" action="?">
	<tr>
		<td>Enter Ticket Number to Lookup:&nbsp;</td>
		<td><input type="text" name="subTickID" size="5" maxlength="10" value="<?php echo isset($_GET['tickSelect']) ? $_GET['tickSelect'] : ($tid > 0 ? $tid : ''); ?>" /></td>
		<td><input type="submit" name="lookup" value="Lookup Ticket" /></td>
	</tr>
	<tr><td colspan="3"><hr /></td></tr>
</form>
	<tr><td colspan="3" align="center">
	</td></tr>
	<?php
		if (isset($_SESSION['enduser'])) {
			//generate a listing of associated tickets
			$user = unserialize($_SESSION['enduser']);
			$q = "select ID, descrip from " . DB_PREFIX . "data where regUser = " . $user->get('id') . " and ticketVisi = 1";
			$s = mysql_query($q) or die(mysql_error());
			
			if ($tid == 0) {
				$u = unserialize($_SESSION['enduser']);
				if ($u->get('securityLevel', 'intval') == ENDUSER_SECURITY_LEVEL) {
	?>
	<tr><td colspan="3" valign="top">
		<table cellpadding="0" cellspacing="0" border="1" width="350">
			<tr>
				<th>IDs</th>
				<th>Description</th>
			</tr>
			<tr>
				<td valign="top">
				<?php
					//List the IDs of Tickets Registered (or Unregistered - just not the ones explicity registered
					$q = "select id from " . DB_PREFIX . "data where regUser = " . $u->get('id', 'intval') . " or regUser = 0";
					$s = mysql_query($q) or die(mysql_error());
					while ($r = mysql_fetch_assoc($s))
					{
						if (!isset($_GET['tickSelect'])) {
							$_GET['tickSelect'] = $r['id'];
						}
						
						if ($r['id'] == $_GET['tickSelect'])
							echo "<b>";
							
						echo "<a href='?tickSelect=" . $r['id'] . "'>#" . $r['id'] . "</a><br/>\n";
						
						if ($r['id'] == $_GET['tickSelect'])
							echo "</b>";
					}
				?>
				</td>
				<td valign="top">
				<?php
					$t = new Ticket($_GET['tickSelect']);
					echo $t->get('descrip', 'nl2br');
					echo "<br/>&nbsp;\n";
				?>
				</td>
			</tr>
		</table>
	</td></tr>
	<?php
				}
			}
		}
	?>
</table>

<!-- Ticket Form Data Table -->
<?php
	if ($tid > 0)
	{
		if(!is_numeric($tid)) {
			echo "<center><font color='red'>You must enter a correct ticket number</font></center>";
			//header("location:ticketLookup_display.php");
			die;
		}
		//now we need to check if we can see another persons ticket
		if ($OBJ->get('ticketAccessModify'))
			$t = new PublishedTicket($tid);
		else {
			if (isset($_SESSION['enduser']))
				$u = unserialize($_SESSION['enduser']);
			else
				$u = new User();
			
			if (!is_null($u->get('securityLevel') && $u->get('securityLevel') > ENDUSER_SECURITY_LEVEL)) 
				$t = new Ticket($tid);
			else
				$t = new PublishedTicket($tid, intval($u->get('id')));
		}
		
		print "<pre>";
		
		if (isset($_POST['submit'])) {	
			include_once "./ticketLookup_process.php";
		}
		
		if ($t->get('results'))
		{
?>
<table cellpadding="0" cellspacing="0" border="0">
<form method="post" action="" enctype="multipart/form-data">
<?php
	if (isset($_POST['subTickID'])) {
?>
	<input type="hidden" name="subTickID" value="<?php echo $tid; ?>" />
<?php
	}
?>
	<tr>
		<th align="left">Date of Submission:&nbsp;</th>
		<td><?php echo $t->get('mainDate'); ?></td>
	</tr>
	<tr>
		<th align="left">Ticket Reference Number:&nbsp;</th>
		<td>#<?php echo $t->get('id'); ?></td>
	</tr>
	<tr>
		<th align="left">First Name of Submitter:&nbsp;</th>
		<td><?php echo $t->get('FirstName'); ?></td>
	</tr>
	<tr>
		<th align="left">Last Name of Submitter:&nbsp;</th>
		<td><?php echo $t->get('LastName'); ?></td>
	</tr>
	<tr>
		<th align="left">Email Address of Submitter:&nbsp;</th>
		<td><?php echo $t->get('EMail'); ?></td>
	</tr>
	<tr>
		<th align="left">Phone Number of Submitter:&nbsp;</td>
		<td>
		<?php
			//we are going to use an embeeded ternary operation to extrat the data and display it
			//if you dont know what this does dont touch it
			echo (intval($t->get('phoneNumber')) > 0)
					? (intval($t->get('phoneExt')) > 0)
						? "(" . substr($t->get('phoneNumber'), 0, 3) . ") " . substr($t->get('phoneNumber'), 3, 3) . "-" . substr($t->get('phoneNumber'), 6) . " ext." . $phoneExt
						: "(" . substr($t->get('phoneNumber'), 0, 3) . ") " . substr($t->get('phoneNumber'), 3, 3) . "-" . substr($t->get('phoneNumber'), 6)
					: 'None Provided';
		?>
		</td>
	</tr>
	<tr>
		<th align="left">Problem Catagory:&nbsp;</th>
		<td><?php
				$c = $t->get('PCatagory');
				echo printValue($c->get('name', 'stripslashes'));
			?>
		</td>
	</tr>
	<tr>
		<th align="left">Ticket Status:&nbsp;</th>
		<td>
		<?php
			if (isset($_SESSION['enduser'])) $user = unserialize($_SESSION['enduser']);
			$stat = $t->get('status');
			if (isset($user) && $t->get('regUser') == $user->get('id')) {
		?>
			<select name="status" size="1">
			<?php
				$q = "select id as status from " . DB_PREFIX . "status order by position";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
				{
					$_stat = new Status($r['status']);
					if ($_stat->get('id', 'intval') == $stat->get('id', 'intval')) {
						if (!$_stat->get('id', 'intval'))
							echo "<option value=\"\" selected=\"selected\">None</option>\n";
						else 
							echo "<option value=\"" . $_stat->get('id', 'intval') . "\" selected=\"selected\">" . $_stat->get('name', 'stripslashes') . "</option>\n";
					}
					elseif (!$_stat->get('id', 'intval'))
						echo "<option value=\"\">None</option><br/>\n";
					else 
						echo "<option value=\"" . $_stat->get('id', 'intval') . "\">" . $_stat->get('name', 'stripslashes') . "</option>\n";
				}
			?>
			</select>
		<?php
			}
			else {
				echo $stat->get('name', 'stripslashes');
				echo "<input type=\"hidden\" name=\"status\" value=\"" . $stat->get('id', 'intval') . "\" />\n";
			}
		?></td>
	</tr>
	<tr>
		<th align="left">Assigned Technician:&nbsp;</th>
		<td><?php
				$objUser = $t->get('staff');
				echo is_null($objUser->get('user')) ? 'Unassigned' : $objUser->get('user');
			?>
		</td>
	</tr>
	<tr>
		<th align="left">Ticket Priority:&nbsp;</td>
		<td><?php
				if ($OBJ->get('user_defined_priorities') && isset($user) && $user->get('id') == $t->get('regUser')) {
			?>
			<select name="priority" size="1">
			<?php
				$q = "select pid from " . DB_PREFIX . "priorities order by severity";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
				{
					$p = $t->get('priority');
					$_p = new Priority($r['pid']);
					if ($p->get('pid') == $r['pid']) $sup = " selected=\"selected\"";
					else $sup = "";
					
					echo '<option value="' . $r['pid'] . '"' . $sup . '>' . $_p->get('name', 'stripslashes') . '</option>' . chr(10);
				}
			?>
			</select>
			<?php
				}
				else {
					$p = $t->get('priority');
					echo $p->get('name', 'stripslashes');			
				}
			?>
		</td>
	</tr>
	<tr>
		<th align="left">Associated Part Number:&nbsp;</th>
		<td><?php echo intval($t->get('partNo')) > 0 ? '#' . $t->get('partNo') : 'None Given'; ?></td>
	</tr>
	<tr><td colspan="2" height="5"></td></tr>
	<tr><th colspan="2" align="left" style="color:blue">
		Ticket Technical Data
	</th></tr>
	<tr>
		<th align="left">Submitters Operating System:&nbsp;</th>
		<td><?php echo $t->get('os', 'printValue'); ?></td>
	</tr>
	<tr>
		<th align="left">Submitters Web Browser Code:&nbsp;</th>
		<td><?php echo $t->get('os', 'printValue'); ?></td>
	</tr>
	<tr>
		<th align="left">UA String Code:&nbsp;</th>
		<td><?php echo $t->get('uastring', 'printValue'); ?></td>
	</tr>
	<tr><th colspan="2" align="left">
		Problem Description:
	</th></tr>
	<tr><td valign="top" colspan="2">
		<textarea cols="55" rows="7" name="descrip"><?php echo stripslashes($t->get('descrip', 'printValue')); ?></textarea>
	</td></tr>
	<tr><td colspan="3">
		<?php
			include_once "../includes/upload_form.php";
			
			if (count($t->get('fileList'))) {
				echo "<br/>\n<b>Associated Files</b><br/>\n";
				foreach ($t->get('fileList') as $file)
					echo $file . "<br/>\n";
			}
		?>
		
	</td></tr>
	<tr><td height="5"></td></tr>
	
	<tr><td colspan="2" align="center">
		<input type="submit" name="submit" value="Update" class="button" />
	</td></tr>
</form>
</table>
<?php
		}
		else {
			echo '<div align="center" style="color:red; font-weight:bold">Ticket Cannot be Viewed - Marked as Held, Does Not Exist or Cannot be Viewed</div>' . chr(10);
		}
	}
?>