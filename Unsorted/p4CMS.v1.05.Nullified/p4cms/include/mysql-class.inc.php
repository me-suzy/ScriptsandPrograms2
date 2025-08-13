<?
 class MySQLq {
 	var $_handle;
 	var $_result;
 	
 	function MySQLq() {
 		$this->_handle = "";
 		$this->_result = "";
 	}
 	
 	function Query($sql) {
 		global $sql_server;
 		global $sql_passwort;
 		global $sql_user;
 		global $sql_db;
 		
 		$this->_handle = mysql_connect($sql_server,$sql_user,$sql_passwort);
 		if (!$this->_handle) {
 			$this->_mysqlerror();
 			return false;
 		}
 		mysql_select_db($sql_db);
 		$this->_result = mysql_query($sql,$this->_handle);
 		if (!$this->_result) {
 			$this->_mysqlerror();
 			return false;
 		}
 		return true;
 	}		
 	
 	function FetchRow() {
 		return mysql_fetch_object($this->_result);
 	}
	
	function Numrows() {
 		return mysql_numrows($this->_result);
 	}

	function Affected() {
		return mysql_affected_rows($this->_handle);
	}

 	function Close() {
 		@mysql_free_result($this->_result);
 		@mysql_close($this->_handle);
 		$this->_handle = "";
 		$this->_result = "";
 	}
 	
 	function IId() {
 		return mysql_insert_id($this->_handle);
 	}
 	
 	function RowCount() {
 		return mysql_num_rows($this->_result);
 	}

	function FetchArray() {
		return mysql_fetch_array($this->_result);
	}
 	
 	function _mysqlerror() {
 		$fehler = mysql_error($this->_handle);
 		$fehlernr = mysql_errno($this->_handle);
 		print ("<b>Fehler</b><br><br>Fehler beim Zugriff auf die p4cms-Datenbank. Bitte versuchen Sie es später erneut.<br><br>Fehler: ".$fehler."<br>Fehlernummer: ".$fehlernr."<br><br>Probieren Sie es ggf. an einem späteren Zeitpunkt nocheinmal!");
 		die("");
 	}
 }
?>
