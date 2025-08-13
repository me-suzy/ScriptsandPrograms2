<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: db_mysql.php,v $ - $Revision: 1.27 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
define('LONG_QUERY_TIME', 0.015);
define('SHOWSQL', (((defined('DEBUG') and DEBUG === true) or (defined('HIVE_DEV') and HIVE_DEV === true)) and $_REQUEST['showsql'] == 1));

// ############################################################################
// Calculates microtime difference from $start
function microdiff($start) {
	return realmicrotime() - realmicrotime($start);
}

// ############################################################################
// Returns a real timestamp from a microtime
function realmicrotime($time = null) {
	if ($time === null) {
		$time = microtime();
	}
	$timebits = explode(' ', $time);
	return $timebits[0] + $timebits[1];
}
// (These two functions have to be here and not in functions.php)

// ############################################################################
class DB_MySQL {
	var $config		= array();
	var $shutdown	= array();
	var $tables		= array();
	var $showerror	= true;
	var $link_id	= false;
	var $skipcount	= false;
	var $queries	= 0;
	var $fullscans	= 0;
	var $slowcount	= 0;
	var $conntime	= 0;
	var $sqltime	= 0;

	// ########################################################################
	function DB_MySQL(&$config, $connect = true, $new_link = false) {
		$this->config = $config;
		if ($connect) {
			$this->connect($new_link);
		}
		unset($config);
	}

	// ########################################################################
	function connect($new_link = false) {
		$beforetime = microdiff(STARTTIME);
		if (SHOWSQL) {
			echo "<blockquote><pre>&nbsp;\n<span style=\"font-family: Tahoma, sans-serif; font-size: 11px;\"><b>Connecting to database:</b> (<a href=\"http://$_SERVER[HTTP_HOST]".str_replace('showsql=1', '', $_SERVER['REQUEST_URI'])."\">no details</a>)\nTime before:\t$beforetime\n";
		}

		if ($this->config['persistent']) {
			$mysql_connect = 'mysql_pconnect';
		} else {
			$mysql_connect = 'mysql_connect';
		}

		if ($new_link and function_exists('version_compare') and version_compare(phpversion(), '4.2.0') >= 0) {
			// mysql_pconnect() does not support new_link yet
			$this->link_id = @mysql_connect($this->config['server'], $this->config['username'], $this->config['password'], true) or $this->quit('Connection failed.');
		} else {
			$this->link_id = $mysql_connect($this->config['server'], $this->config['username'], $this->config['password']) or $this->quit('Connection failed.');
		}

		if (!empty($this->config['database'])) {
			@$this->select_db($this->config['database']);
		}

		$this->config['username'] = $this->config['password'] = '';

		$aftertime = microdiff(STARTTIME);
		$this->conntime = $aftertime - $beforetime;
		if (SHOWSQL) {
			echo "Time after:\t$aftertime\nTime taken:\t".($this->conntime)."\n</pre>\n<hr noshade=\"noshade\" size=\"1\" color=\"black\" />\n</span></blockquote>\n";
		}
	}

	// ########################################################################
	function close() {
		mysql_close($this->link_id);
	}

	// ########################################################################
	function select_db($database) {
		mysql_select_db($database, $this->link_id) or $this->quit("Cannot use database: '$database'.");
	}

	// ########################################################################
	// phpMyAdmin's split_sql_file() function
	function explode_queries(&$exploded, $queries) {
		$queries = trim($queries);
		$queries_len = strlen($queries);
		$char = '';
		$string_start = '';
		$in_string = false;

		for ($i = 0; $i < $queries_len; ++$i) {
			$char = $queries[$i];

			if ($in_string) {
				while (1) {
					$i = strpos($queries, $string_start, $i);
					if (!$i) {
						$exploded[] = $queries;
						return true;
					} elseif ($string_start == '`' or $queries[$i-1] != '\\') {
						$string_start = '';
						$in_string = false;
						break;
					} else {
						$j = 2;
						$escaped_backslash = false;
						while ($i-$j > 0 and $queries[$i-$j] == '\\') {
							$escaped_backslash = !$escaped_backslash;
							$j++;
						}
						if ($escaped_backslash) {
							$string_start = '';
							$in_string = false;
							break;
						} else {
							$i++;
						}
					}
				}
			} elseif ($char == ';') {
				$exploded[] = substr($queries, 0, $i);
				$queries = ltrim(substr($queries, min($i + 1, $queries_len)));
				$queries_len = strlen($queries);
				if ($queries_len) {
					$i = -1;
				} else {
					return true;
				}
			} elseif ($char == '"' or $char == '\'' or $char == '`') {
				$in_string = true;
				$string_start = $char;
			} elseif ($char == '#' or ($char == ' ' and $i > 1 and $queries[$i-2].$queries[$i-1] == '--')) {
				$start_of_comment = iif($queries[$i] == '#',$i, $i-2);
				$end_of_comment = iif(strpos(' '.$queries, "\012", $i+2), strpos(' '.$queries, "\012", $i+2), strpos(' '.$queries, "\015", $i+2));
				if (!$end_of_comment) {
					$exploded[] = trim(substr($queries, 0, $i-1));
					return true;
				} else {
					$queries = substr($queries, 0, $start_of_comment).ltrim(substr($queries, $end_of_comment));
					$queries_len = strlen($queries);
					$i--;
				}
			} elseif ($release < 32270 and $char == '!' and $i > 1 and $queries[$i-2].$queries[$i-1] == '/*') {
				$queries[$i] = ' ';
			}
		}

		if (!empty($queries) and ereg('[^[:space:]]+', $queries)) {
			$exploded[] = $queries;
		}

		return true;
	}

	// ########################################################################
	function select($table, $fields, $where = '1 = 1', $joins = array(), $orderby = '', $limit = '') {
		$query_string = "SELECT $fields\nFROM hive_$table AS $table\n";
		foreach ($joins as $jointable => $joinclause) {
			if (strpos($joinclause, ' ') === false) {
				$joinclause = "USING ($joinclause)";
			} else {
				$joinclause = "ON ($joinclause)";
			}
			$query_string .= "LEFT JOIN hive_$jointable AS $jointable $joinclause\n";
		}
		$query_string .= "WHERE $where\n";
		if (!empty($orderby)) {
			$query_string .= "ORDER BY $orderby\n";
		}
		if (!empty($limit)) {
			$query_string .= "LIMIT $limit\n";
		}

		return $this->query($query_string);
	}

	// ########################################################################
	function insert($table, $fields) {
		if ($this->auto_query($table, $fields)) {
			return mysql_affected_rows();
		} else {
			return false;
		}
	}

	// ########################################################################
	function update($table, $fields, $where) {
		// Didn't add a default value to $where so if you want to update all
		// records in a table, you need to understand what you're doing first
		if ($this->auto_query($table, $fields, $where)) {
			return mysql_affected_rows();
		} else {
			return false;
		}
	}

	// ########################################################################
	function delete($table, $where) {
		// Didn't add a default value to $where so if you want to delete all
		// records in a table, you need to understand what you're doing first
		if ($this->query("DELETE FROM hive_$table WHERE $where")) {
			return mysql_affected_rows();
		} else {
			return false;
		}
	}

	// ########################################################################
	function query($query_string) {
		$beforetime = microdiff(STARTTIME);
		if (SHOWSQL) {
			echo "<blockquote><pre><span style=\"font-family: Tahoma, sans-serif; font-size: 11px;\">Query:\n<blockquote><i>".htmlspecialchars(preg_replace('#^[^\#\w]*#m', '', $query_string))."</i></blockquote>\nTime before:\t$beforetime\n";
		}
		if (!$this->skipcount) {
			$this->queries++;
		}
		if (!empty($this->config['prefix'])) {
			$query_string = $this->add_prefix($query_string);
		}
		$result = mysql_query($query_string, $this->link_id) or $this->quit("Invalid query: $query_string");

		$aftertime = microdiff(STARTTIME);
		$timetaken = $aftertime - $beforetime;
		$this->sqltime += $timetaken;
		if ($timetaken > LONG_QUERY_TIME) {
			$this->slowcount++;
		}
		if (((defined('DEBUG') and DEBUG === true) or (defined('HIVE_DEV') and HIVE_DEV === true)) and substr(trim(strtoupper($query_string)), 0, 6) == 'SELECT') {
			$beforetime = microdiff(STARTTIME);
			$explain_id = mysql_query("EXPLAIN $query_string", $this->link_id);
			$aftertime = microdiff(STARTTIME);
			if (SHOWSQL) {
				echo "Time after:\t$aftertime\nTime taken:\t$timetaken".(($timetaken > LONG_QUERY_TIME) ? ("\t<font color=\"red\"><b>SLOW!</b></font>") : (''))."\nExplain time:\t".($aftertime - $beforetime)."\n</pre>";
				echo "\n".'
					<table width="90%" border="0" cellpadding="0" cellspacing="0" style="border: 1px solid black; font-family: Tahoma, sans-serif; font-size: 11px; padding: 3px;">
					<tr>
					<td><b>Table</b></td>
					<td><b>Type</b></td>
					<td><b>Possible_Keys</b></td>
					<td><b>Key</b></td>
					<td><b>Key_Len</b></td>
					<td><b>Ref</b></td>
					<td><b>Rows</b></td>
					<td><b>Extra</b></td>
					</tr>
				';
			}
			while ($info = mysql_fetch_array($explain_id)) {
				$fullscan = ($fullscan or ($info['table'] != 'hive_setting' and strtolower($info['type']) == 'all'));
				if (SHOWSQL) {
					echo "
						<tr>
						<td>$info[table]&nbsp;</td>
						<td>".(($info['table'] != 'hive_setting' and strtolower($info['type']) == 'all') ? ('<font color="red" size="2"><b>ALL!</b></font>') : ($info['type']))."&nbsp;</td>
						<td>$info[possible_keys]&nbsp;</td>
						<td>$info[key]&nbsp;</td>
						<td>$info[key_len]&nbsp;</td>
						<td>$info[ref]&nbsp;</td>
						<td>$info[rows]&nbsp;</td>
						<td>$info[Extra]&nbsp;</td>
						</tr>
					";
				}
			}
			if ($fullscan) {
				$this->fullscans++;
			}
			if (SHOWSQL) {
				echo "</table>\n<br /><hr noshade=\"noshade\" size=\"1\" color=\"black\" />\n\n</span></blockquote>";
			}
		} elseif (SHOWSQL) {
			echo "Time after:\t$aftertime\nTime taken:\t$timetaken".(($timetaken > LONG_QUERY_TIME) ? ("\t<font color=\"red\" size=\"2\"><b>SLOW!</b></font>") : (''))."\n</pre>\n<hr noshade=\"noshade\" size=\"1\" color=\"black\" />\n</span></blockquote>";
		}

		return $result;
	}

	// ########################################################################
	function auto_query($table, $fields, $where = '') {
		return $this->query($this->build_query($table, $fields, $where));
	}

	// ########################################################################
	function build_query($table, $fields, $where = '') {
		if (empty($where)) {
			$query_string = "INSERT INTO hive_$table\nSET ";
		} else {
			$query_string = "UPDATE hive_$table\nSET ";
		}

		foreach ($fields as $field => $value) {
			if (!is_numeric($field)) {
				if (is_numeric($value)) {
					$query_string .= "$field = $value,\n";
				} else {
					$query_string .= "$field = '".addslashes($value)."',\n";
				}
			}
		}
		$query_string = substr($query_string, 0, -2);

		if (!empty($where)) {
			$query_string .= "\nWHERE $where";
		}

		return $query_string;
	}

	// ########################################################################
	function add_prefix($query_string) {
		return $query_string;

		if (empty($this->tables)) {
			$tables = mysql_list_tables($this->database, $this->link_id);
			while (list($table) = mysql_fetch_array($tables)) {
				if (substr($table, 0, strlen($this->config['prefix'])) == $this->config['prefix']) {
					$this->tables[substr($table, strlen($this->config['prefix']))] = $table;
				}
			}
		}

		$query_string = " $query_string ";
		foreach ($this->tables as $old => $new) {
			if (substr(trim($query_string), 0, 11) == 'DELETE FROM') {
				$query_string = preg_replace('#FROM(.*)([ ,(]+)('.$old.')#i', "FROM\\1\\2$new", $query_string);
			} else {
				$finds = array(
					'#FROM(.*)([ ,(]+)('.$old.')( AS [a-z_0-9]+)#i',
					'#FROM( [a-z_0-9]+( AS [a-z_0-9]+)?)?([ ,(]+)('.$old.')([^a-z0-9_])#i',
					'#JOIN( )('.$old.')( AS [a-z_0-9]+)#i',
					'#JOIN( )('.$old.')#i',
					'#((INTO)|(UPDATE))(.*)('.$old.')#im',
				);
				$replaces = array(
					"FROM\\1\\2$new\\4",
					"FROM\\1\\3$new AS \\4\\5",
					"JOIN\\1$new\\3",
					"JOIN\\1$new AS \\2",
					"\\1\\4$new ",
				);
				$original = $query_string;
				for ($i = 0; $i < 5; $i++) {
					$query_string = preg_replace($finds["$i"], $replaces["$i"], $query_string);
					if ($original != $query_string) {
						break;
					}
				}
			}
		}
		return $query_string;
	}

	// ########################################################################
	function fetch_array(&$result, $query_string = '', $type = MYSQL_BOTH) {
		if (!is_resource($result) and !empty($query_string)) {
			$result = $this->query($query_string);
		}
		return mysql_fetch_array($result, $type);
	}

	// ########################################################################
	function get_field($query_string, $field = 0) {
		$result = $this->query($query_string);
		return @mysql_result($result, 0, $field);
	}

	// ########################################################################
	function shut_down($query_string) {
		if (defined('NOSHUTDOWNFUNCS')) {
			return $this->query($query_string);
		} else {
			$this->shutdown[] = $query_string;
		}
	}

	// ########################################################################
	function query_first($query_string) {
		return $this->fetch_array($this->query($query_string));
	}

	// ########################################################################
	function reset(&$result) {
		return @mysql_data_seek($result, 0);
	}

	// ########################################################################
	function num_rows(&$result) {
		return mysql_num_rows($result);
	}

	// ########################################################################
	function insert_id() {
		return mysql_insert_id($this->link_id);
	}

	// ########################################################################
	function escape($string) {
		return mysql_escape_string($string);
	}

	// ########################################################################
	function error() {
		return mysql_error();
	}

	// ########################################################################
	function errno() {
		return mysql_errno();
	}

	// ########################################################################
	function get_server_info() {
		return mysql_get_server_info();
	}

	// ########################################################################
	function quit($msg) {
		if (file_exists('install/index.php')) {
			header('Location: install/index.php');
			exit;
		} elseif ($this->showerror == true) {
			$message =	"MySQL error: ".@mysql_error($this->link_id)."\n".
						"MySQL error number: ".@mysql_errno($this->link_id)."\n".
						"Script: ".getenv('REQUEST_URI')."\n".
						"Date: ".date('F j, Y, g:i a')."\n".
						"\n$msg";

			if (function_exists('log_event')) {
				log_event(EVENT_WARNING, 601, array('msg' => $msg, 'error' => @mysql_error($this->link_id), 'errno' => @mysql_errno($this->link_id), 'script' => getenv('REQUEST_URI')), false);
			}

			?><html>
			<head>
			<title>Database Error in HiveMail&trade;</title>
			<style type="text/css">
			<!--
			body {
				font-family: Tahoma, sans-serif;
				font-size: 11px;
				padding: 20px;
			}
			-->
			</style>
			</head>
			<body>
			<b>There seems to have been a slight problem with the database.</b><br />
			Please try again by pressing the <a href="javascript:window.location = window.location;">refresh</a> button in your browser.<br />
			We apologise for any inconvenience.
			<form><textarea rows="12" cols="60"><?=htmlspecialchars($message); ?></textarea></form>
			</body>
			</html>
			<?php
			exit;
		}
	}

	// ########################################################################
	// Gets the system options from the database and manipulates them as necessary
	function setup_options() {
		global $_options, $POP_Socket_name;

		while ($setting = $this->fetch_array($settings, 'SELECT * FROM hive_setting')) {
			$_options["$setting[variable]"] = $setting['value'];
		}

		// The domain names array
		$_options['domainnames'] = preg_split("#\r?\n#", $_options['domainname']);
		$_options['domainname'] = $_options['domainnames'][0];

		// Filename of index.php
		define('INDEX_FILE', $_options['indexname']);

		// Use IMAP
		$_options['pop3_useimap'] = ($_options['pop3_useimap'] and function_exists('imap_open')); 

		// Which class to use for POP3
		$POP_Socket_name = 'POP_Socket_'.($_options['pop3_useimap'] ? 'IMAP' : 'socket');

		// HivePOP3 enabled? (as opposed to running)
		$_options['hivepop_enabled'] = ($_options['hivepop_enabled'] and HIVEPOP_RUNNING);

		// Extract variables to the global scope
		foreach ($_options as $variable => $value) {
			global $$variable;
			$$variable = $value;
		}
	}
}

?>