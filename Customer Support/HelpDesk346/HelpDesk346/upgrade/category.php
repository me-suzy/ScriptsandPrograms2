<?php
	session_start();
	
	mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpass']);
	mysql_select_db($_SESSION['dbname']);
	
	define('DB_PREFIX', $_SESSION['prefix'] . "_");
	
	$path = getcwd();
	chdir('..');
	include_once "./includes/classes.php";
	chdir($path);
	
	include_once "./files/process4.php";
?>
<html>
	<head>
		<title>Performing Stage 4 of 6 - Category Modification</title>
	</head>
	
	<body>
		<div>
			The old version of the Helpdesk made use of a text based storage system for Categories. Because of this the application had very little
			knowledge about its Categories.  With introduction of the new numeric based storage system we can assign different information
			to various categories. One of these is a default priority attached to each of these the priority is automatically assigned upon creation.
			<br/>
			<br/>
			Please Make sure their are no unassigned priorities, though this should not happen given the nature of the assignment procedure to start.
		</div>
		<hr/>
		<table cellpadding="0" cellspacing="0" border="0">
		<form method="post" action="">
			<tr>
				<td valign="top">
					<b>Gathered Categories:</b><br/>
					<select name="selNames" size="5">
					<?php
						$q = "select id from " . $_SESSION['prefix'] . "_categories order by name";
						$s = mysql_query($q) or die(mysql_error());
						while ( $r = mysql_fetch_assoc( $s ) )
						{
							$c = new Category( $r['id'] );
							$p = $c->get('priority');
							echo '<option value="' . $c->get('id', 'intval') . '">' . $c->get('name', 'stripslashes') . ' [' . $p->get('name', 'stripslashes') . ']</option>' . chr(10);
						}
					?>
					</select>
				</td>
				<td width="10"></td>
				<td valign="top">
					<br/>
					<input type="submit" name="link" value="Link to Priority" /><br/>
					<input type="submit" name="move" value="Proceed to Next Step" />
				</td>
				<td width="10"></td>
				
				<td valign="top">
					<b>Avaialable Priorities</b><br/>
					<select name="selP" size="5">
					<?php
						$q = "select pid from " . $_SESSION['prefix'] . "_priorities order by severity";
						$s = mysql_query($q) or die(mysql_error());
						while ( $r = mysql_fetch_assoc( $s ) )
						{
							$p = new Priority( $r['pid'] );
							echo '<option value="' . $p->get('pid', 'intval') . '">' . $p->get('name', 'stripslashes') . '</option>' . chr(10);
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td colspan="5" align="center" style="color:red">
			<?php echo isset($error_msg) ? $error_msg : ''; ?>
			</td></tr>
		</form>
		</table>
		<hr/>
	</body>
</html>