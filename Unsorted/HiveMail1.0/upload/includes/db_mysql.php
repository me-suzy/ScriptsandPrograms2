<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: db_mysql.php,v $
// | $Date: 2002/11/11 16:29:32 $
// | $Revision: 1.25 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
class DB_MySQL {
	var $config		= array();
	var $shutdown	= array();
	var $tables		= array();
	var $showerror	= true;
	var $link_id	= false;
	var $queries	= 0;

	// ########################################################################
	function DB_MySQL(&$config, $connect = true) {
		$this->config = $config;
		if ($connect) {
			$this->connect();
		}
		unset($config);
	}

	// ########################################################################
	function connect() {
		if ($this->config['persistent']) {
			$mysql_connect = 'mysql_pconnect';
		} else {
			$mysql_connect = 'mysql_connect';
		}

		if ($this->config['password'] == '') {
			$this->link_id = @$mysql_connect($this->config['server'], $this->config['username']) or $this->quit('Connection failed.');
		} else {
			$this->link_id = @$mysql_connect($this->config['server'], $this->config['username'], $this->config['password']) or $this->quit('Connection failed.');
		}

		if (!empty($this->config['database'])) {
			@$this->select_db($this->config['database']);
		}

		$this->config['username'] = $this->config['password'] = '';
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
	function query($query_string) {
		$this->queries++;
		if (!empty($this->config['prefix'])) {
			$query_string = $this->add_prefix($query_string);
		}
		$result = mysql_query($query_string, $this->link_id) or $this->quit("Invalid query: $query_string");
		return $result;
	}

	// ########################################################################
	function auto_query($table, $fields, $where = '') {
		if (empty($where)) {
			$query_string = "INSERT INTO $table SET ";
		} else {
			$query_string = "UPDATE $table SET ";
		}

		foreach ($fields as $field => $value) {
			if (!is_numeric($field)) {
				if (is_numeric($value)) {
					$query_string .= "$field = $value, ";
				} else {
					$query_string .= "$field = '".addslashes($value)."', ";
				}
			}
		}
		$query_string = substr($query_string, 0, -2);

		if (!empty($where)) {
			$query_string .= " WHERE $where";
		}

		return $this->query($query_string);
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
	function fetch_array(&$result, $query_string = '') {
		if (!is_resource($result) and !empty($query_string)) {
			$result = $this->query($query_string);
		}
		return mysql_fetch_array($result);
	}

	// ########################################################################
	function get_field($query_string, $field = 0) {
		$result = $this->query($query_string);
		return mysql_result($result, 0, $field);
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
	function quit($msg) {
		if ($this->showerror == true) {
			$message =	"Database error in HiveMail:\n\n$msg\n\n".
						"MySQL error: ".mysql_error()."\n".
						"MySQL error number: ".mysql_errno()."\n".
						"Date: ".date('F j, Y, g:i a')."\n".
						"Script: ".getenv('REQUEST_URI');

			?><html>
			<head>
			<title>Database Error in HiveMail</title>
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
}

?>