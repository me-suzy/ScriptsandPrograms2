<?php
/*

crackerDB v1.0
Database Manager

- this is a very limited, barely visual database manager
- it was designed as an 'include' file, to be used within any template already built
- users will need to know the SQL statements to locate the necessary data within the database
- a link to MySQL SQL Syntax is provided
- it has been tested as far as was needed to ensure it served its intended purpose

- it assumes an existing connection to a database:
	$db = mysql_connect($dbHost,$dbRoot,$dbPassword);

There's nothing secure about this, so you'll want to make sure it's in a secure location on your
server. If you want a login script, you'll want to use crackerDB v1.0s (db_manager_secure.zip) .
Otherwise, take it, do what you want to it. If you use it, please make mention of me. Good Luck!

- Big Cracker

*/
?>

<STYLE>

/* crackerDB styles - use of classes so as not to interfere with other styles on page */

.std, .hdr1, .hdr2, .btn { font-family: Verdana, Arial, Helvetica, sans-serif }
P.std, THEAD.std, TBODY.std { font-size: 9pt }

H1.std { color: #000080; font-size: 14pt }
H2.std { color: #800000; font-size: 12pt }
H3.std { font-size: 10pt }

A.std:link { font-size: 9pt; color: #000080; text-decoration: none }
A.std:visited { font-size: 9pt; color: #800000; text-decoration: none }
A.std:hover { font-size: 9pt; color: #000080; text-decoration: underline overline }

TABLE.std_b { border: #C0C0C0 solid 1px }
THEAD.std { font-weight: bold; color: #FFFFFF; background-color: #000080 }
TD.std { font-size: 9pt }
TD.hdr1 { font-size: 9pt; font-weight: bold; color: #FFFFFF; background-color: #000080 }
TD.hdr2 { font-size: 9pt; background-color: #BFCEE0 }
INPUT.btn { font-size: 9pt; color: #FFFFFF; background-color: #800000; font-weight: bold; border: #000000 1px solid }

</style>

<H1 class="std">crackerDB v1.0</h1>

<TABLE width="100%">
	<TR>
		<TD width="200" rowspan="2" valign="top" nowrap>

			<TABLE class="std_b" width="100%">
				<TR>
					<TD class="hdr1" align="center" valign="top">Databases</td>
				</tr>
				<TR>
					<TD class="std">

<?php

$result = @mysql_list_dbs($db);

if (!$result) {
	echo "DB Error, could not list databases<BR>\n";
	echo 'MySQL Error: ' . mysql_error();
}
else {
	while ($row = mysql_fetch_row($result)) {
		if ($row[0] != $_GET['db']) {
			echo "						<A class=\"std\" href=\"" . $_SERVER['PHP_SELF'] . "?db=" . $row[0] . "\">" . $row[0] . "</a><BR>\n";
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

			<TABLE class="std_b" width="100%">
				<TR>
					<TD class="hdr1" align="center" valign="top">Tables</td>
				</tr>
				<TR>
					<TD class="std">

	<?php

	$result = mysql_list_tables($_GET['db']);

	if (!$result) {
		echo "DB Error, could not list tables<BR>\n";
		echo 'MySQL Error: ' . mysql_error();
	}
	else {
		while ($row = mysql_fetch_row($result)) {
			if ($row[0] != $_GET['tbl']) {
				echo "						<A class=\"std\" href=\"" . $_SERVER['PHP_SELF'] . "?db=" . $_GET['db'] . "&tbl=" . $row[0] . "\">" . $row[0] . "</a><BR>\n";
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

}

?>

			<P><A class="std" href="#sql" title="To SQL Command section, if much lower on page">SQL Command</a></p>

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
			echo "<H1 class=\"std\">" . $_GET['tbl'] . $comment . "</h1>";

		?>

			<TABLE width="100%">
				<THEAD class="std">
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

	echo "			<H1 class=\"std\">" . $init_sql . "</h1>\n";

	if ($result = mysql_query($init_sql)) {

		if ($rows = @mysql_fetch_assoc($result)) {
			echo "			<TABLE width=\"100%\">\n";
			echo "				<THEAD class=\"std\">\n";
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
			echo "					<TD class=\"std\">" . $rows . " records were affected by the query.</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
		}
		else {
			echo "			<TABLE width=\"100%\">\n";
			echo "				<TR>\n";
			echo "					<TD class=\"std\">The query generated no records.</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
		}

	}

	else {

		echo "			<TABLE width=\"100%\">\n";
		echo "				<TR>\n";
		echo "					<TD class=\"std\">MySQL Error: " . mysql_error() . "</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";

	}

}

else {

	if ($_GET['db']) { $item = "table"; } else { $item = "database"; }

	?>

			<TABLE width="100%">
				<TR>
					<TD class="std">Select a <?php echo $item ?> on the left</td>
				</tr>
			</table>

	<?php

}

if ($_GET['db']) $action = $_SERVER['PHP_SELF'] . "?db=" . $_GET['db'];

?>

			<A name="sql">
			<P><BR></p>
			<TABLE width="100%">
				<TR>
					<TD><H1 class="std">SQL Command&nbsp;&nbsp;&nbsp;<SPAN style="color:#000080;font-size:10pt">&lt;<A class="std" href="http://dev.mysql.com/doc/mysql/en/SQL_Syntax.html" target="_blank">MySQL Manual - SQL Syntax</a>&gt;</span></h1></td>
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
			<P><A class="std" href="#top" title="Back to top of page">Back to Top</a></p>
		</td>
	</tr>
</table>