<?php

class db { // class db

	var $db_connect_id = 0; // declare vars
	var $dbname = "";
	var $query_result = "";
	var $num_queries = 0;

	function db ($server, $user, $password, $database) {// class constructor

		$this->db_connect_id = @mysql_connect ($server, $user, $password); // attempt to connect to MySQL

		if ($this->db_connect_id) { // if MySQL connected successfully
			if ($database != "") { // check for database var

				$this->dbname = $database;
				$dbselect = @mysql_select_db ($database);

				if (!$dbselect) { // successful database select
					@mysql_close ($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}

			}

			return $this->db_connect_id;

		} else {
			return FALSE;
		}
	}

	function query ($query = "") { // function to query MySQL

		unset ($this->query_result); // unset current MySQL result

		if ($query != "") { // check for query

			$this->num_queries++; // add one to total queries
			$this->query_result = @mysql_query ($query); // query MySQL

			if ((!$this->query_result) && (defined ("AFTER_MAIN"))) {
				error ("mysql_error_{$this->num_queries}", "MysQL Error: <b>" . mysql_error () . "</b><br />Query was <b>" . htmlspecialchars ($query) . "</b>", 2);
			}

		}

		if ($this->query_result) { // if successful
			return $this->query_result;
		} else { // not successful
			return FALSE;
		}

	}

	function rows ($result_id) { // function to get results of select

		$rows = @mysql_fetch_array ($result_id, MYSQL_NUM);

		if (is_array ($rows)) {
			foreach ($rows AS $key => $value) {
				$value = ((ini_get ("magic_quotes_gpc")) ? stripslashes (mysql_real_escape_string ($value, $this->db_connect_id)) : mysql_real_escape_string ($value, $this->db_connect_id));
				$row_return[$key] = $value;
			}
		} else {
			$row_return = $rows;
		}

		return ($row_return);

	}

	function num ($result_id) { // function to get MySQL number of rows from query
		$nums = @mysql_num_rows ($result_id);
		return $nums;
	}

	function free ($result_id) { // function to free MySQL result
		@mysql_free_result ($result_id);
		return TRUE;
	}

	function close () { // function to close MySQL

		if ($this->db_connect_id) { // if connected
			if ($this->query_result) {
				@mysql_free_result ($this->query_result);
			}

			$result = @mysql_close ($this->db_connect_id);
			return $result;
		} else {
			return FALSE;
		}
	}

}

?>