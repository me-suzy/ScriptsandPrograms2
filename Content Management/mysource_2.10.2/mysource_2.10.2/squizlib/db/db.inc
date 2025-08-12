<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Generic Include Files -- PHP4 ----------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## Desc: Thin MySQL Database abstracion layer object
## $Source: /home/cvsroot/squizlib/db/db.inc,v $
## $Revision: 2.21 $
## $Author: bvial $
## $Date: 2004/02/25 03:27:13 $
#######################################################################

#---------------------------------------------------------------------#

class Db extends Object {

	var $host     = ''; # Abbr: h
	var $login    = ''; # Abbr: l
	var $password = ''; # Abbr: p
	var $name     = ''; # Abbr: n
	
	var $ptr = ''; # The current database pointer

	var $log_file = ''; # The file to log all potential queries to
	var $charset  = ''; # Used for a little hack (currently), see multibyte_string... below

	 ##################################
	# Constructor
	function Db ($n='', $l = "root", $p = "", $h = "localhost", $log_file="") {
		$this->print_errors = 1;
		if ($n) { # If a db has been specified, connect
			$this->connect($n,$l,$p,$h);
		}
		$this->log_file = $log_file;
	}

	 ###############################
	# Connect to a certain database
	function connect($n='', $l='', $p='', $h='') {
		if (!$l) { # Use last login/password
			$l = $this->login;
			$p = $this->password;
		}
		# If they want to connect to a new server, let em
		if ($h && $h != $this->host) { 
			if ($this->ptr) { # Close old ptr
				mysql_close($this->ptr);
			}
			if (!($this->ptr = mysql_connect($h, $l, $p))) {
				$this->_set_error('Unable to connect to database server ("'.mysql_error()."\"): $h. Login: $l. Password: ".(($p)?'Yes':'No'),__FILE__,__LINE__);
				global $IN_BACKEND;
				if(!$IN_BACKEND && $this->error_reporting()) {
					exit($this->fatal_error());
				}
				return;
			}
			$this->host     = $h;
			$this->login    = $l;
			$this->password = $p;
		}

		# Select specified database name
		if (!$n) {
			$this->_set_error('Unable to select database: no name specified.');
			global $IN_BACKEND;
			if(!$IN_BACKEND && $this->error_reporting()) {
				exit($this->fatal_error());
			}
			return;
		}
		$this->name = $n;
		if(!$this->_use()) {
			$this->name = '';
		}
		return 1;

	}
	 
	 ##########################################
	# Ensures we are using the correct database
	function _use() {
		global $DB_CURRENT_NAME;
		if ($this->name && $this->name == $DB_CURRENT_NAME[$this->host]) return 1;
		if (!mysql_select_db($this->name, $this->ptr)) {
			$this->_set_error("Unable to select database ($this->name): ".mysql_error($this->ptr),__FILE__,__LINE__);
			global $IN_BACKEND;
			if(!$IN_BACKEND && $this->error_reporting()) {
				exit($this->fatal_error());
			}
			return;
		}
		$DB_CURRENT_NAME[$this->host] = $this->name;
		return 1;
	}

	 #####################################
	# Disconnect from the database server
	function disconnect() {
		return mysql_close($this->ptr);
	}

	 #######################################################
	# Returns a list of the databases on the current server
	function db_names() {
		if(!$this->ptr) return;
		if (!($result = mysql_list_dbs($this->ptr))) {
			$this->_set_error("Unable to list database names: ".mysql_error($this->ptr),__FILE__,__LINE__);
			return 0;
		} else {
			$r = array();
			for ($i = 0; $i < mysql_num_rows($result); $i++) {
				$r[] = mysql_result($result,$i,0);
			}
			return $r;
		}
	}

	/**
	* Returns a list of the tables on the current server
	*
	* @access public
	* @return mixed (Mostly array unless something goes wrong)
	*/
	function table_names() {
		if(!$this->ptr || !$this->name) return;
		if (!($result = mysql_list_tables($this->name,$this->ptr))) {
			$this->_set_error("Unable to list table names: ".mysql_error($this->ptr),__FILE__,__LINE__);
			return;
		} else {
			$r = array();
			for ($i = 0; $i < mysql_num_rows($result); $i++) {
				$r[] = mysql_result($result,$i,0);
			}
			return $r;
		}
	}

	/**
	* Returns whether the system can tell if a table exists for sure or not
	*
	* @access public
	* @return boolean
	*/
	function table_exists($table_name) {
		$existing_tables = $this->table_names();
		if (is_array($existing_tables)) {
			return in_array($table_name, $existing_tables);
		} else {
			return false;
		}
	}

	 ################################################################
	# Returns a list of the fields in a table in the current database
	function field_names($table='') {
		if(!$this->ptr || !$this->name || !$table) return;
		if (!($result = mysql_list_fields($this->name,$table,$this->ptr))) {
			$this->_set_error("Unable to list field names: ".mysql_error($this->ptr),__FILE__,__LINE__);
			return;
		} else {
			$r = array();
			for ($i = 0; $i < mysql_num_fields($result); $i++) {
				$r[] = mysql_field_name($result,$i);
			}
			return $r;
		}
	}



	 ##########################################
	# Returns the version number of the server
	function server_version() {
		$this->_log_query("SELECT VERSION()");
		return mysql_get_server_info($this->ptr);
	}


	  #############################################
	 # select from database, return query handle
	function select($query='') {
		if (!$query) return false;
		$this->_log_query($query);
		if (!$this->_use()) return 0;
		if (!($result = mysql_query($query, $this->ptr))) {
			$this->_set_error("Unable to SELECT [$query]: ".mysql_error($this->ptr),__FILE__,__LINE__);
			return 0;
		} else {
			return $result;
		}
	}


	  ##################################################################################
	 # insert into database, return value of auto_increment field, or the rows affected
	function insert($query='') {
		if (!$query) return false;
		$this->_log_query($query);
		if (!$this->_use()) return 0;
		if (!mysql_query($query, $this->ptr)) {
			$this->_set_error("Unable to INSERT [$query]: ".mysql_error($this->ptr),__FILE__,__LINE__);
		} else {
			if ($id = mysql_insert_id($this->ptr)) {
				return $id;
			} else {
				 return mysql_affected_rows($this->ptr);
			}
		}
		return false;
	}


	  ##################################################################################
	 # replace row in database, return value of auto_increment field, or the rows affected
	function replace($query='') {
		if (!$query) return false;
		$this->_log_query($query);
		if (!$this->_use()) return 0;
		if (!mysql_query($query, $this->ptr)) {
			$this->_set_error("Unable to REPLACE [$query]: ".mysql_error($this->ptr),__FILE__,__LINE__);
		} else {
			if ($id = mysql_insert_id($this->ptr)) {
				return $id;
			} else {
				 return mysql_affected_rows($this->ptr);
			}
		}
		return false;
	}


	  ########################################
	 # update database, return rows changed 
	function update($query='') {
		if (!$query) return false;
		$this->_log_query($query);
		if (!$this->_use()) return 0;
		if (!mysql_query($query, $this->ptr)) {
			$this->_set_error("Unable to UPDATE [$query]: ".mysql_error($this->ptr),__FILE__,__LINE__);
		} else {
			return mysql_affected_rows($this->ptr);
		}
		return false;
	}


	  #############################################
	 # delete from database, return rows deleted
	function delete($query='') {
		if (!$query) return false;
		$this->_log_query($query);
		if (!$this->_use()) return 0;
		if (!mysql_query($query, $this->ptr)) {
			$this->_set_error("Unable to DELETE [$query]: ".mysql_error($this->ptr),__FILE__,__LINE__);
		} else {
			return mysql_affected_rows($this->ptr);
		}
		return false;
	}


	  ################################################
	 # Combined auto-detect database query function
	function query($query='') {
		if (!$query) return false;
		if (strtoupper(substr(ltrim($query), 0, 6)) == "SELECT") return $this->select($query);
		if (strtoupper(substr(ltrim($query), 0, 6)) == "UPDATE") return $this->update($query);
		if (strtoupper(substr(ltrim($query), 0, 6)) == "INSERT") return $this->insert($query);
		if (strtoupper(substr(ltrim($query), 0, 7)) == "REPLACE") return $this->replace($query);
		if (strtoupper(substr(ltrim($query), 0, 6)) == "DELETE") return $this->delete($query);
		$this->_set_error("Unable to determine query type [$query].",__FILE__,__LINE__);
	}

	 ##########################################################################
	# For systems using multi-byte character sets, there seems to be a problem
	# with MySQL in that if a CHAR(x)/VARCHAR(x) field gets assigned a string
	# that is too long, MySQL will truncate it at the offending byte, rather
	# than at the offending character. This leaves your string with bits of
	# multi-byte character at the end. This function it intended to provide
	# cheap (coding-time-wise, not resources-wise by any mean) protection
	# against such shenannigans. Requires PHP's multi-byte string module
	# to be installed.
	function multibyte_string_protection_charset($charset='') {
		if($charset && !extension_loaded("mbstring")) {
			$this->_set_error("Multibyte String Protection requires the PHP mbstring module to be installed.",__FILE__,__LINE__);
		}
		$this->charset = $charset;
	}


	 #####################################################
	# Makes sure string pulled out of the database nice
	# and clean. (no slashes etc)
	function clean_string(&$str) {
		if($this->_need_to_clean_string()) {
			$this->_clean_string($str);
		}
	}

	function _need_to_clean_string() {
		return ($this->charset && extension_loaded("mbstring"));
	}
	function _clean_string(&$str) {
		$str = mb_convert_encoding($str,$this->charset,$this->charset);
	}


	  ################################################
	 # mysql_fetch_array except with slashstripping
	function row($result='') {
		if (!$result) return false;
		$row = mysql_fetch_array($result);

		if ($this->_need_to_clean_string()) {
			foreach($row as $key => $val) {
				if (is_string($val)) $this->_clean_string($val);
				$row[$key] = $val;
			}
			reset($row);
		}
		return $row;
	}

	function numrows($result='') {
		if (!$result) return false;
		return mysql_num_rows($result);
	}

	  #####################################################################
	 # takes a query for a single row and returns the row, slashstripped 
	function single_row($query='') {
		if (!$query) return false;
		$mq = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$result = $this->select($query);
		set_magic_quotes_runtime($mq);
		$row = $this->row($result);
		mysql_free_result($result);
		return $row;
	}


	  #####################################################################################
	 # takes a reseult and return a stripslashed array of values in a column
	function column($result='', $index=0) {
		if (!$result) return false;
		if (!$index) $index = 0;
		$array = array();
		$need_to_clean = $this->_need_to_clean_string();
		while($row = mysql_fetch_row($result)) {
			$val = $row[$index];
			if ($need_to_clean && is_string($val)) $this->_clean_string($val);
			$array[] = $val;
		}
		return $array;
	}

	  
	  #####################################################################
	 # takes a query for a single column and returns it
	function single_column($query='') {
		if (!$query) return false;
		$mq = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$result = $this->select($query);
		set_magic_quotes_runtime($mq);
		$column = $this->column($result);
		mysql_free_result($result);
		return $column;
	}


	  ####################################################################
	 # takes a query for a single element and returns it, slashstripped
	function single_element($query='') {
		if (!$query) return false;
		$mq = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$result = $this->select($query);
		set_magic_quotes_runtime($mq);
		if (mysql_num_rows($result) > 0) {
			$element = mysql_result($result,0,0);
			if (is_string($element)) $this->clean_string($element);
		}
		mysql_free_result($result);
		return $element;
	}

	   ################################################
	  # Like single row, but returns only the keys with 
	 # fieldnames, not the column number
	 function associative_row($query='') {
		if (!$query) return false;
		$row = $this->single_row($query);
		for(reset($row); NULL !== ($key = key($row)); next($row)) {
			if (is_int($key)) unset($row[$key]);
		}
		return $row;
	 }

	   ###############################################################
	  # Returns an array of rows, using the first column as the key #
	 # if column count > 2, values are an array, else just a value
	# The force argument fforces the array to only use the first
	# two columns.
	function associative_array($query='',$force=0) {
		if (!$query) return false;
		$mq = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$result = $this->select($query);
		set_magic_quotes_runtime($mq);
		$need_to_clean = $this->_need_to_clean_string();
		$array = array();
		if (mysql_num_fields($result) > 2 && !$force) {
			$first_col_key = "";
			while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if (!$first_col_key) {
					reset($row);
					$first_col_key = key($row);
				}
				if ($need_to_clean) {
					for(reset($row); NULL !== ($k = key($row)); next($row)) {
						if (is_string($row[$k])) $this->_clean_string($row[$k]);
					}
					reset($row);
				}#end if
				$array[$row[$first_col_key]] = $row;
			}#end while
		} else {
			while($row = mysql_fetch_row($result)) {
				if ($need_to_clean) {
					if(is_string($row[0])) $this->_clean_string($row[0]);
					if(is_string($row[1])) $this->_clean_string($row[1]);
				}
				$array[$row[0]] = $row[1];
			}
		}
		mysql_free_result($result);
		return $array;
	}


	  ###############################################################
	 # As above, but the first TWO columns are treated as keys in an
	# array. This will produce a 2- (possily 3-) dimentional array
	# EG SELECT buildingid, personid, name FROM person_position
	# will produce array($buildingid => array($personid => $name, ...), ...)
	function associative_array2($query='',$force=0) {
		if (!$query) return false;
		$mq = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$result = $this->select($query);
		set_magic_quotes_runtime($mq);
		$need_to_clean = $this->_need_to_clean_string();
		$array = array();
		if (mysql_num_fields($result) > 3 && !$force) {
			while($row = mysql_fetch_array($result)) {
				if ($need_to_clean) {
					for(reset($row); NULL !== ($k = key($row)); next($row)) {
						if (is_string($row[$k])) $this->_clean_string($row[$k]);
					}
					reset($row);
				}#end if
				$array[$row[0]][$row[1]] = $row;
			}
		} else {
			while($row = mysql_fetch_row($result)) {
				if ($need_to_clean) {
					if(is_string($row[0])) $this->_clean_string($row[0]);
					if(is_string($row[1])) $this->_clean_string($row[1]);
					if(is_string($row[2])) $this->_clean_string($row[2]);
				}#end if
				$array[$row[0]][$row[1]] = $row[2];
			}
		}
		mysql_free_result($result);
		return $array;
	}


	  #############################################################################
	 # As above, but the first THREE (YES THREE) columns are treated as keys in an
	# array. This will produce a 2- (possily 3-) dimentional array
	# EG SELECT buildingid, floorid, personid, name FROM person_position
	# will produce array($buildingid => array($floorid => array($personid => $name, ...), ...), ...)
	function associative_array3($query='',$force=0) {
		if (!$query) return false;
		$mq = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$result = $this->select($query);
		set_magic_quotes_runtime($mq);
		$need_to_clean = $this->_need_to_clean_string();
		$array = array();
		if (mysql_num_fields($result) > 4 && !$force) {
			while($row = mysql_fetch_array($result)) {
				if ($need_to_clean) {
					for(reset($row); NULL !== ($k = key($row)); next($row)) {
						if (is_string($row[$k])) $this->_clean_string($row[$k]);
					}
					reset($row);
				}
				$array[$row[0]][$row[1]][$row[2]] = $row;
			}
		} else {
			while($row = mysql_fetch_row($result)) {
				if ($need_to_clean) {
					if(is_string($row[0])) $this->_clean_string($row[0]);
					if(is_string($row[1])) $this->_clean_string($row[1]);
					if(is_string($row[2])) $this->_clean_string($row[2]);
					if(is_string($row[3])) $this->_clean_string($row[3]);
				}
				$array[$row[0]][$row[1]][$row[2]] = $row[3];
			}
		}
		mysql_free_result($result);
		return $array;
	}


	 #################################################################
	# Returns an array indexed by first column and an array of values
	function associative_column($query='') {
		if (!$query) return false;
		$mq = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		$result = $this->select($query);
		set_magic_quotes_runtime($mq);
		$need_to_clean = $this->_need_to_clean_string();
		$array = array();
		if (mysql_num_fields($result) > 2) {
			while($row = mysql_fetch_array($result)) {
				if ($need_to_clean) {
					for(reset($row); NULL !== ($k = key($row)); next($row)) {
						if (is_string($row[$k])) $this->_clean_string($row[$k]);
					}
					reset($row);
				}#end if
				$array[$row[0]][] = $row;
			}
		} else {
			while($row = mysql_fetch_row($result)) {
				if ($need_to_clean) {
					if(is_string($row[0])) $this->_clean_string($row[0]);
					if(is_string($row[1])) $this->_clean_string($row[1]);
				}#end if
				$array[$row[0]][] = $row[1];
			}
		}
		mysql_free_result($result);
		return $array;
	}


	 ########################################################
	# Converts MySQL date strings to timestamps etc and back
	function date_to_timestamp($date='') {
		if (!$date) return false;
		return mktime(0,0,0,substr($date,5,7),substr($date,8),substr($date,0,4));
	}
	function datetime_to_timestamp($date='') {
		if (!$date) return false;
		return mktime(
			substr($date,11,2),
			substr($date,14,2),
			substr($date,17,2),
			substr($date,5,2),
			substr($date,8),
			substr($date,0,4)
		);
	}
	function timestamp_to_date($timestamp='') {
		if (!$timestamp) return false;
		return date("Y-m-d",$timestamp);
	}
	function timestamp_to_datetime($timestamp='') {
		if (!$timestamp) return false;
		return date("Y-m-d H:i:s",$timestamp);
	}

	 ########################################################
	# if we have a log file then append this query to it
	function _log_query($query='') {
		if (!$query) return false;
		if ($this->log_file) {
			if (!$fp = fopen($this->log_file,"a")) {
				$this->_set_error("Unable to open file: ".$this->log_file,__FILE__,__LINE__);
				return false;
			}
			$query = $this->name . ":\n". $query . ";\n\n";
			fputs($fp,$query,strlen($query));
			if (!fclose($fp)) {
				$this->_set_error("Unable to close file: ".$this->log_file,__FILE__,__LINE__);
				return false;
			}
		}
		return true;
	}


	/**
	* Print this if we get a fatal db error
	*
	* @returns void
	* @access public
	* @since MySource 2.3.11
	*/
	function fatal_error() {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/config.inc");
		$conf = &get_config("MySource");
		$relpath = $conf->backend_suffix;
		?>
		<html>
			<head>
				<title>Database Error</title>
				<style type="text/css">
					.one {
						font-family: arial, verdana, sans-serif; 
						font-size: 60px; 
						font-weight: bold; 
						color: #dddddd;
					}
					.two {
						font-family: arial, verdana, sans-serif; 
						font-size: 30px; 
						font-weight: bold; 
						color: #eeeeee;
					}
				</style>
			</head>
			<body bgcolor="#ffffff" background="<?=$relpath?>/images/brushed_metal.gif">
			<table width="100%" height="100%">
				<tr>
					<td align="center" class="one">Website Temporarily Unavailable<br><span class="two">Please check back later</span></td>
				</tr>
				<tr>
					<td align="center" class="two">MySource</td>
				</tr>
			</table>
			<!-- 
				Add some padding text so that IE actually prints out our page not found page
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, 
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, 
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT,
				PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, PADDING TEXT, 
			-->
			</body>
		</html>
		<?
	}

	/**
	* Returns an array of mysql field objects from the given table
	*
	* @param string $table_name The table you want to get the fields from
	* @param boolean $primarys_only Set to true if you only want it to return fields that are primary keys
	* @returns &array
	* @access public
	*/
	function &get_table_fields($table_name='', $primarys_only=false) {
		if (!$table_name) return array();
		if (!$this->table_exists($table_name)) return array();
		$result = $this->query("SELECT * FROM $table_name LIMIT 1");
		$fields = array();
		$i = 0;
		$num_fields = mysql_num_fields($result);

		# Doing the whole boolean $add thing so it's easy to add other things to it in the future
		while($i < $num_fields) {
			$add = true;
			$field = &$this->get_field_object($result, $i);
			if ($add && $primarys_only) {
				$add = $field->primary_key;
			}

			if ($add) {
				$fields[$i] = &$field;
			}
			$i++;
		}

		mysql_free_result($result); 
		return $fields;
	}

	/**
	* Returns a field object given a result object
	*
	* @param int $result The mysql resultid
	* @param int $index The mysql field id
	* @returns &object
	* @access public
	*/
	function &get_field_object($result='', $index=0) {
		if (!$result) return false;
		if (!$index) $index = 0;
		return mysql_fetch_field($result, $index);
	}
}#end class
?>