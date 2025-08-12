<?php

session_start();

/*

crackerDB v1.0s
Database Manager

- this is a very limited, barely visual database manager
- users will need to know the SQL statements to locate the necessary data within the database
- a link to MySQL SQL Syntax is provided
- it has been tested as far as was needed to ensure it served its intended purpose

Take it, do what you want to it. If you use it, please make mention of me. Good Luck!

- Big Cracker

*/

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>

<TITLE>crackerDB v1.0s</title>

<STYLE>

/* crackerDB styles */

BODY, THEAD, TBODY, TD, A, H1, H2, H3 { font-family: Verdana, Arial, Helvetica, sans-serif }
BODY, THEAD, TBODY { font-size: 9pt }

H1 { color: #000080; font-size: 14pt }
H2 { color: #800000; font-size: 12pt }
H3 { font-size: 10pt }

A:link { font-size: 9pt; color: #000080; text-decoration: none }
A:visited { font-size: 9pt; color: #800000; text-decoration: none }
A:hover { font-size: 9pt; color: #000080; text-decoration: underline overline }

TABLE.border { border: #C0C0C0 solid 1px }
THEAD { font-weight: bold; color: #FFFFFF; background-color: #000080 }
TD { font-size: 9pt }
TD.hdr1 { font-size: 9pt; font-weight: bold; color: #FFFFFF; background-color: #000080 }
TD.hdr2 { font-size: 9pt; background-color: #BFCEE0 }
INPUT.btn { font-size: 9pt; color: #FFFFFF; background-color: #800000; font-weight: bold; border: #000000 1px solid }

</style>

<SCRIPT language="javascript">
//form validation for mandatory fields
function mandatory() {
	var msg = '';
	frm = mandatory.arguments[0];
	for (i = 1; i < mandatory.arguments.length; i = i + 3) {
		elem = mandatory.arguments[i];
		text = mandatory.arguments[i+1];
		fldType = mandatory.arguments[i+2];
		if (frm.elements[elem].value == '') {
			msg += text + ' is not complete\n';
		}
		else if (fldType == 'email') {
			if (!isEmail(frm.elements[elem].value)) {
				msg += text + ' is not a valid email address\n';
			}
		}
	}
	if (msg != '') {
		alert('The follwing errors occurred:\n' + msg);
		return false
	}
}
</script>

</head>

<BODY>

<H1>crackerDB v1.0s</h1>

<?php

if (((!$_REQUEST) && (!$_SESSION)) || ($_GET['action'] == "logout")) {

	if ($_GET['action'] == "logout") {
		$_SESSION = array();
		session_destroy;
		$connected = false;
	}
?>


<?php

}

else {
	
	if ($_POST['action'] == "login") {

		$dbHost = $_POST['db_host'];
		$dbRoot = $_POST['db_root'];
		$dbPassword = $_POST['db_password'];

		$_SESSION['dbHost'] = $dbHost;
		$_SESSION['dbRoot'] = $dbRoot;
		$_SESSION['dbPassword'] = $dbPassword;

	}

	else if ($_SESSION) {

		$dbHost = $_SESSION['dbHost'];
		$dbRoot = $_SESSION['dbRoot'];
		$dbPassword = $_SESSION['dbPassword'];

	}

	$db = @mysql_connect($dbHost,$dbRoot,$dbPassword);
	$result = @mysql_list_dbs($db);
	if (!$result) {
		$connected = false;
		$message = true;
	}
	else {
		$connected = true;
	}

	if ($_GET['db']) {
		mysql_select_db($_GET['db'],$db);
		$result = mysql_list_tables($_GET['db']);
		if (!$result) {
			$connected = false;
			$message = true;
		}
		else {
			$connected = true;
		}
	}

}

?>

<?php
	
if (!$connected) {

	if ($message) echo "<P style=\"color:#D00000;font-weight:bold\">Access Denied</p>\n";

?>

<FORM name="db_login" action="db_manager_secure.php" method="post" onSubmit="return mandatory(this,'db_host','DB Host','text','db_root','DB Root','text')">

	<TABLE cellpadding="3">
		<TR>
			<TD class="hdr1">db Host</td>
			<TD class="hdr2">
				<INPUT type="text" name="db_host" value="localhost">
			</td>
		</tr>
		<TR>
			<TD class="hdr1">db Root</td>
			<TD class="hdr2">
				<INPUT type="text" name="db_root" value="">
			</td>
		</tr>
		<TR>
			<TD class="hdr1">db Password</td>
			<TD class="hdr2">
				<INPUT type="password" name="db_password" value="">
			</td>
		</tr>
		<TR>
			<TD class="hdr1">db Name</td>
			<TD class="hdr2">
				<INPUT type="text" name="db_name" value="">&nbsp;<I>(optional)</i>
			</td>
		</tr>
		<TR>
			<TD>&nbsp;</td>
			<TD align="right">
				<INPUT type="hidden" name="action" value="login">
				<INPUT class="btn" type="submit" name="submit" value="Login">
			</td>
		</tr>
	</table>

</form>

<?php
	
}

else {
	
	?>

<P><A href="db_manager_secure.php?action=logout">Logout</a></p>

<TABLE width="100%">
	<TR>
		<TD width="200" rowspan="2" valign="top" nowrap>

			<TABLE class="border" width="100%">
				<TR>
					<TD class="hdr1" align="center" valign="top">Databases</td>
				</tr>
				<TR>
					<TD>

	<?php

	$result = @mysql_list_dbs($db);

	if (!$result) {
		echo "DB Error, could not list databases<BR>\n";
		echo 'MySQL Error: ' . mysql_error();
	}
	else {
		while ($row = mysql_fetch_row($result)) {
			if ($row[0] != $_GET['db']) {
				echo "						<A href=\"db_manager_secure.php?db=" . $row[0] . "\">" . $row[0] . "</a><BR>\n";
			}
			else {
				echo "						" . $row[0] . "<BR>\n";
			}
		}
	}
	mysql_free_result($result);

	?>

					</td>
				</tr>
			</table>

	<?php

	if ($_GET['db']) {

		mysql_select_db($_GET['db'],$db);

		?>

			<P><BR></p>

			<TABLE class="border" width="100%">
				<TR>
					<TD class="hdr1" align="center" valign="top">Tables</td>
				</tr>
				<TR>
					<TD>

		<?php

		$result = mysql_list_tables($_GET['db']);

		if (!$result) {
			echo "DB Error, could not list tables<BR>\n";
			echo 'MySQL Error: ' . mysql_error();
		}
		else {
			while ($row = mysql_fetch_row($result)) {
				if ($row[0] != $_GET['tbl']) {
				echo "						<A href=\"db_manager_secure.php?db=" . $_GET['db'] . "&tbl=" . $row[0] . "\">" . $row[0] . "</a><BR>\n";
				}
				else {
				echo "						" . $row[0] . "<BR>\n";
			}
		}
		mysql_free_result($result);

		?>

					</td>
				</tr>
			</table>

		<?php

		}

	}

	?>

			<P><A href="#sql" title="To SQL Command section, if much lower on page">SQL Command</a></p>

		</td>
		<TD valign="top" style="padding-left:25px">

	<?php

	$init_sql = "";

	if ($_GET['tbl']) {
	
		mysql_select_db($_GET['db'],$db);

		$init_sql = "SELECT * FROM " . $_GET['tbl'];

		$result = mysql_query("SHOW TABLE STATUS");
		while ($data = mysql_fetch_assoc($result)) {
			if ($data['Name'] == $_GET['tbl']) {
				if ($data['Comment']) {
					$comment = " (" . $data['Comment'] . ")";
				}
			}
		}
	
		mysql_free_result($result);

		$result = mysql_query("SHOW COLUMNS FROM " . $_GET['tbl']);
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
		}
		else {
			if (mysql_num_rows($result) > 0) {
				echo "<H1>" . $_GET['tbl'] . $comment . "</h1>";

			?>

			<TABLE width="100%">
				<THEAD>
					<TR>
						<TD>Field</td>
						<TD>Type</td>
						<TD>Null</td>
						<TD>Key</td>
						<TD>Default</td>
						<TD>Extra</td>
					</tr>
				</thead>

			<?php
				while ($row = mysql_fetch_assoc($result)) {
					echo "				<TR>\n";
					echo "					<TD class=\"hdr2\">" . $row['Field'] . "</td>";
					echo "					<TD class=\"hdr2\">" . $row['Type'] . "</td>";
					echo "					<TD class=\"hdr2\">" . $row['Null'] . "</td>";
					echo "					<TD class=\"hdr2\">" . $row['Key'] . "</td>";
					echo "					<TD class=\"hdr2\">" . $row['Default'] . "</td>";
					echo "					<TD class=\"hdr2\">" . $row['Extra'] . "</td>\n";
					echo "				</tr>\n";
				}
				echo "			</table>\n";
			}
			mysql_free_result($result);
		}
	}

	else if ($_POST['action'] == "submitted") {

		$init_sql = stripslashes($_POST['sql']);

		echo "			<H1>" . $init_sql . "</h1>\n";

		if ($result = mysql_query($init_sql)) {

			if ($rows = @mysql_fetch_assoc($result)) {
				echo "			<TABLE width=\"100%\">\n";
				echo "				<THEAD>\n";
				echo "					<TR>\n";
				foreach ($rows as $key => $val) {
					echo "						<TD>" . $key . "</td>\n";
				}
				echo "					</tr>\n";
				echo "				</thead>\n";

				mysql_free_result($result);

				$result = mysql_query($init_sql);

				while ($rows = mysql_fetch_assoc($result)) {
					echo "				<TR>\n";
					foreach ($rows as $key => $val) {
						echo "					<TD class=\"hdr2\">" . $val . "</td>\n";
					}
					echo "				</tr>\n";
				}
				echo "			</table>\n";
				mysql_free_result($result);
			}
			else if ($rows = @mysql_affected_rows($result)) {
				echo "			<TABLE width=\"100%\">\n";
				echo "				<TR>\n";
				echo "					<TD>" . $rows . " records were affected by the query.</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
			}
			else {
				echo "			<TABLE width=\"100%\">\n";
				echo "				<TR>\n";
				echo "					<TD>The query generated no records.</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
			}

		}

		else {

			echo "			<TABLE width=\"100%\">\n";
			echo "				<TR>\n";
			echo "					<TD>MySQL Error: " . mysql_error() . "</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";

		}

	}

	else {

		if ($_GET['db']) { $item = "table"; } else { $item = "database"; }
	
		?>

			<TABLE width="100%">
				<TR>
					<TD>Select a <?php echo $item ?> on the left</td>
				</tr>
			</table>

		<?php

	}

	if ($_GET['db']) $action = "db_manager_secure.php?db=" . $_GET['db'];

	?>

			<A name="sql">
			<P><BR></p>
			<TABLE width="100%">
				<TR>
					<TD><H1>SQL Command&nbsp;&nbsp;&nbsp;<SPAN style="color:#000080;font-size:10pt">&lt;<A href="http://dev.mysql.com/doc/mysql/en/SQL_Syntax.html" target="_blank">MySQL Manual - SQL Syntax</a>&gt;</span></h1></td>
				</tr>
				<TR>
					<TD>
						<FORM name="db_sql" action="<?php echo $action ?>" method="post">
							<TEXTAREA name="sql" rows="8" cols="50"><?php echo $init_sql ?></textarea><BR>
							<INPUT type="hidden" name="action" value="submitted">
							<INPUT class="btn" type="submit" name="submit" value="Submit">
						</form>
					</td>
				</tr>
			</table>
			<P><A href="#top" title="Back to top of page">Back to Top</a></p>
		</td>
	</tr>
</table>

<?php

}

?>

</body>
</html>