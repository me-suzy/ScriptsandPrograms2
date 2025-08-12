<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jesper Laursen <jl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

function getVars() {
    global $CONFIG, $system_path, $system_url;
    return array($CONFIG, $system_path, $system_url);
}

class Screen {
    var $haserror = false;
    var $CONFIG = array();
    var $system_path;
    var $system_url;
    
    var $phpversion     = '4.3.3';
    var $mysqlversion   = '4.1.1';
    var $dbfile       = 'db.sql';
    
    function Screen() {
        list($this->CONFIG, $this->system_path, $this->system_url) = getVars();
        require_once($this->system_path . 'adodb.php');
    }
    
    function setError() {
        $this->haserror = true;
    }
}

class Screen1 extends Screen {
   
    function notablesExists() {
        $db =& getdbconn();
        
        $existingTables = $db->getCol('SHOW tables');
        if (!$existingTables) $existingTables = array();
        
        $handle = fopen ($this->dbfile, "r");
        $contents = fread ($handle, filesize ($this->dbfile));
        preg_match_all("/CREATE TABLE `?(\w+)`?/i", $contents, $tables);
        $matches = array_intersect($existingTables, $tables[1]);
        
        if (count($matches) > 0) {
            $this->setError();
            return false;
        } else {
            return true;
        }
    }
    
    function correctPHPversion() {        
        $tmp = version_compare($this->phpversion, phpversion(), '<=');
        if (!$tmp) $this->setError();
        return $tmp;
    }
    
    function correctMySQLversion() {
        $localversion = mysql_get_server_info();
	$mysqlversion = strpos($localversion, '-') !== false ? substr($localversion, 0, strpos($localversion, '-')) : $localversion;
        $tmp = version_compare($this->mysqlversion, $mysqlversion, '<=');
        if (!$tmp) $this->setError();
        return $tmp;
    }
}

class Screen2 extends Screen {
    
    function checkSystemPath() {
		if (!file_exists($this->system_path . 'core/basicclass.php')) {
                $this->setError();
			    return false;
        }
		return true;
	}
    
    function checkSystemUrl() {
        if (empty($this->system_url)) {
            $this->setError();
            return false;
        }
        return true;
    }
    
    function checkadodbexists() {
		return (file_exists($this->system_path . 'core/ado/adodb.inc.php')) ? true : false;
	}
    
    function checkDatabaseConn() {
        $result = false;
		if ($this->checkadodbexists($this->system_path)) {
			include_once($this->system_path . 'core/ado/adodb.inc.php');
			@$conn =& ADONewConnection($this->CONFIG['sql_type']);
			$result = @$conn->connect($this->CONFIG['sql_host'],
			                        $this->CONFIG['sql_user'],
			                        $this->CONFIG['sql_password'],
			                        $this->CONFIG['sql_database']);
            if (!$result) {
                $this->setError();
                return false;
            } else {
                return true;
            }
		} else {
            $this->setError();
            return false;
        }
    }
}

class Screen3 extends Screen {
    
    function sendquery($query ) {
        
        $db =& getdbconn();        
        $array = explode( ';', $query );
        foreach( $array as $value )  {
            $value=trim($value);
            if ($value != "") {
                if( !$result = $db->query( $value )) {
                    $this->setError();
                    break;
                } 
            }
        }
        return $result;
    }
    
    function createsql() {
        echo "Creating tables in database...<BR>";        
        $handle = fopen ($this->dbfile, "r");
        $contents = fread ($handle, filesize ($this->dbfile));
        $this->sendquery($contents);
        fclose ($handle);	
        echo "Tables created!<BR><BR>";
    }
}

class Screen4 extends Screen {
    
}
?>
