<?PHP
	if(!class_exists("xxObject")) {
		echo "<H1>Class xxObject not found!</H1>";
		exit;
	}
	//-- 10.17.2001: Error message define
	define("__ERROR_OPEN_DATABASE", "Not open database");
	define("__ERROR_CLOSE_DATABASE", "Not close database");
	define("__ERROR_SET_DATABASE", "Database not found");
	define("__ERROR_OPEN_QUERY", "Not open query. Statement error: ");
	define("__ERROR_CLOSE_QUERY", "Not close query");
	define("__ERROR_EXECUTE_COMMAND", "Not execute command. Statement error: ");
	define("__ERROR_FETCH_OBJECT", "Not fetch object");
	define("__ERROR_FETCH_ARRAY", "Not fetch array");
	define("__ERROR_ROWS_COUNT", "Not rows count");

	class xxDatabase extends xxObject{
		var $__cID=0;
		var $__host;
		var $__user;
		var $__pswd;
		var $__base;
		
		function xxDatabase($host, $user, $pswd, $base){
			//-- 10.17.2001: Create database connect object
			$this->__host=$host;
			$this->__user=$user;
			$this->__pswd=$pswd;
			$this->__base=$base;
		}
		
		function open(){
			//-- 10.17.2001: Open connection to database
			if($this->isOpen()) return $this->getConnectId();
			$this->__cID=mysql_pconnect($this->__host, $this->__user, $this->__pswd);
			if($this->getConnectId()) if(!$this->setDatabase($this->__base)) $this->setError(__ERROR_OPEN_DATABASE);
			return $this->getConnectId();
		}
		
		function close(){
			//-- 10.17.2001: Close connection to database
			if(!$this->isOpen()) return true;
			$result=mysql_close($this->getConnectId());
			if(!$result) $this->setError(__ERROR_CLOSE_DATABASE);
			$this->__cID=0;
			return $result;
		}
		
		function setDatabase($base){
			//-- 10.17.2001: change database
			$this->__base=$base;
			if(!($this->isOpen() && $base)) return false;
			$result=mysql_select_db($base, $this->getConnectId());
			if(!$result) $this->setError(__ERROR_SET_DATABASE);
			return $result;
		}
		
		function getDatabase(){
			//-- 10.17.2001: Get database name
			return $this->__base;
		}
		
		function isOpen(){
			//-- 10.17.2001: Return TRUE if to database connect active
			return ($this->__cID) ? true : false;
		}
		
		function getConnectId(){
			//-- 10.17.2001: Get connect ID
			return $this->__cID;
		}
		
		function setError($message=null){
			//-- 10.17.2001: Generate error message
			$errorMessage=$message.mysql_error().mysql_errno();
			xxObject::setError($errorMessage);
		}
	}

	class xxDataset extends xxObject{
		var $__cID;
		var $__qID=0;
		var $__statement;
		
		function xxDataset(&$connectId){
			//-- 10.17.2001: Create dataset object
			$this->__cID=$connectId;
		}
		
		function open($statement = ""){
			//-- 10.17.2001: Open select query
			if($this->isOpen()) $this->close();
			if ($statement) $this->__statement=$statement;
			$this->__cID->setDatabase($this->__cID->getDatabase());
			$this->__qID=mysql_query($this->__statement);
			if(!$this->__qID) {
				$err_txt = "Error: " . mysql_errno().": ".mysql_error();
				$this->setError(__ERROR_OPEN_QUERY . "\n\t" . $this->__statement . "\n\t" . $err_txt);
			}
			return $this->__qID;
		}
		
		function close(){
			//-- 10.17.2001: Close query result
			if(!$this->isOpen()) return true;
			$result=mysql_free_result($this->__qID);
			if(!$result) $this->setError(__ERROR_CLOSE_QUERY);
			$this->__qID=0;
			return $result;
		}
		
		function execute($statement){
			//-- 10.17.2001: Execute insert/update/delete statement
			$this->__cID->setDatabase($this->__cID->getDatabase());
			$result=mysql_query($statement);
			if(!$result) {
				$err_txt = "Error: " . mysql_errno().": ".mysql_error();
				$this->setError(__ERROR_EXECUTE_COMMAND . "\n\t" . $this->__statement . "\n\t" . $err_txt);
			}
			$newID=mysql_insert_id();
			if(!empty($newID)) return $newID;
			return $this->getAffectedRows();
		}
		
		function fetchObject(){
			//-- 10.17.2001: Fetch next object row
			if(!$this->isOpen()) return false;
			return mysql_fetch_object($this->__qID, MYSQL_ASSOC);
		}
		
		function fetchArray(){
			//-- 10.17.2001: Fetch next array row
			if(!$this->isOpen()) return false;
			return mysql_fetch_array($this->__qID, MYSQL_ASSOC);
		}
		
		function fetchColsAll(){
			//-- 07.20.2002: Fetch all array row in accociative array
			if(!$this->isOpen()) return false;
			$ret = array();
			for ($i = 0; $i < $this->numFields(); $i++) {
				$fieldName = $this->fetchFieldName($i);
				$ret[$fieldName] = array();
			}
			while ($r = $this->fetchObject()) {
				for ($i = 0; $i < $this->numFields(); $i++) {
					$fieldName = $this->fetchFieldName($i);
					$ret[$fieldName][] = $r->$fieldName;	
				}
			}
			return $ret;
		}
		
		function fetchRowsAll($as = "array"){
			//-- 07.20.2002: Fetch all array row in accociative array
			if(!$this->isOpen()) return false;
			$ret = array();
			switch ($as) {
				case "array": 
					while ($ret[] = $this->fetchArray()) {	}
					break;
				case "object": 
					while ($ret[] = $this->fetchObject()) {	}
					break;
			}
			
			return $ret;
		}
		
		function fetchPair($fld1, $fld2){
			//-- 07.20.2002: Fetch all array row in accociative array
			if(!$this->isOpen()) return false;
			$ass_arr = array();
			while ($r = $this->fetchObject()) {
				$ass_arr[$r->$fld1] = $r->$fld2;	
			}
			return $ass_arr;
		}
		
		function getCountries(){
			if(!$this->tableExist("country")) return false;
			$this->open("SELECT * FROM country ORDER BY name");
			return $this->fetchPair("country_id", "name");
		}
		
		function getStates(){
			if(!$this->tableExist("state")) return false;
			$this->open("SELECT * FROM state ORDER BY name");
			return $this->fetchPair("state_id", "name");
		}
		
		function tableExist($tblName) {
			$this->open("SHOW TABLES");
			$fld_name = $this->fetchFieldName(0);
			while ($r = $this->fetchObject()) {
				if ($r->$fld_name == $tblName)
					return true;
			}		
			return false;
		}
		
		function fetchRow() {
			//-- 11.20.2001: Fetch next row
			if(!$this->isOpen()) return false;
			return mysql_fetch_row($this->__qID);
		}
		
		function fetchFieldName($index) {
			//-- 11.28.2001: Fetch fields list
			if(!$this->isOpen()) return false;
			return mysql_field_name($this->__qID, $index);
		}
		
		function numRows(){
			//-- 10.17.2001: Get rows count
			if(!$this->isOpen()) return false;
			return mysql_num_rows($this->__qID);
		}
		
		function numFields() {
			//-- 11.28.2001: Get fields count
			if(!$this->isOpen()) return false;
			return mysql_num_fields($this->__qID);
		}
		
		function getAffectedRows() {
			//--12.05.2001: Get affected rows
			$result=mysql_affected_rows();
			return $result;
		}
		
		function isOpen(){
			//-- 10.17.2001: Is query running?
			return ($this->__qID) ? true : false;
		}
	}

?>