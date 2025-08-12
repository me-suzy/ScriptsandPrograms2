<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
* @version $Id: Base.php,v 1.6 2004/12/07 19:30:02 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsRuntime
*/

require_once(MyObjectsRuntimePath . '/Core.php');
require_once(MyObjectsRuntimePath . '/Exceptions.php');
require_once(MyObjectsRuntimePath . '/StringValidator.php');
require_once(MyObjectsRuntimePath . '/View.php');

require_once(MyObjectsClassPath . '/tables.php');

function __autoload($name) {
    include_once (MyObjectsClassPath . '/' . $name . '.php');
}

/**
* Base
*
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @version 1.0
* @package MyObjectsRuntime
*/
class MyObjectsBase {
    /**
    * @var DatabaseConnection Mysqli database connection
    */
    private $db;
    
    /**
    * @var Config Configuration object that will be used during sessions
    */
    private $config;
    
    /**
    * @var long The start time of proccess in milliseconds
    */
    private $startTime;
    
    /**
     * @var MyObjectsBase $instance Singleton MyObjectsBase instance
     */
    private static $instance = false;
    
    /**
    * Creates the MyObjectsBase instance
    *
    * @param Config $config Config object that will be used for MyObjectsBase instance
    * @param boolean $connect If set true establishes a database connection
    * @return void
    */
    private function __construct($config, $connect = true) {
        $this->startTime = microtime(1);
        $this->config = $config;
        if($connect) {
            $this->db = new DatabaseConnection($config->getDbHost(),
                                               $config->getDbUser(),
                                               $config->getDbPassword(),
                                               $config->getDbName());
            if (mysqli_connect_errno()) {
                throw new DatabaseConnectionException(mysqli_error($this->db),
                                                      mysqli_errno($this->db));
            }
        }
    }
    
    
    /**
    * Closes the database connections
    *
    * @return void
    */
    function __destruct() {
        if($this->isConnected()) {
            // Close the database connection
            $this->db->close();
        }
    }
    
    /**
    * Checks whether there is a valid database connection
    *
    * @return boolean Returns true if there is a valid database connection
    */
    public function isConnected() {
        return ($this->db != null && $this->db instanceof DatabaseConnection);
    }
    
    /**
    * Returns the available database connection
    *
    * @return DatabaseConnection
    */
    public function getDbConnection() {
        if($this->isConnected()) {
            return $this->db;
        } else {
            throw new DatabaseConnectionException('No database connection available');
        }
    }
    
    /**
    * Establishes a database connection
    *
    * @return void
    */
    public function dbConnect() {
        if(!$this->isConnected()) {
            $this->db = new DatabaseConnection($config->getDbHost(),
                                               $config->getDbUser(),
                                               $config->getDbPassword(),
                                               $config->getDbName());
            if (mysqli_connect_errno()) {
                throw new DatabaseConnectionException(mysqli_error($this->db),
                                                      mysqli_errno($this->db));
            }
        }
    }
    
    /**
    * Returns the MyObjectsBase instance
    *
    * @param Config $config Config object that will be used
    * @param boolean $connect Should be passed true to connect to database
    * @return MyObjectsBase The MyObjectsBase instance
    */
    public static function getInstance($config = null, $connect = true) {
        if(!MyObjectsBase::$instance) {
            if($config == null) {
                $config = new MyObjectsDefaultConfig(MyObjectsRuntimeSettings::DB_HOST,
                MyObjectsRuntimeSettings::DB_USER, MyObjectsRuntimeSettings::DB_PASSWORD,
                MyObjectsRuntimeSettings::DB_NAME);
            }
            MyObjectsBase::$instance = new MyObjectsBase($config, $connect);
        }
        return MyObjectsBase::$instance;
    }
    
    /**
    * Returns the elapsed time since the MyObjectsBase object was created
    *
    * @return float Elapsed Time in milliseconds
    */
    public function getElapsedTime() {
        return microtime(1) - $this->startTime;
    }
}

/**
* Database Connection
*
* This class extends mysqli class to provide database acces.
*
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @version 1.0
* @package MyObjectsRuntime
*/
class DatabaseConnection extends mysqli {

	private $queryCount = 0;
	private $log;
	
    /**
    * Makes a database query and throws QueryException in case of an error
    *
    * @param string $query Sql query that will be made
    * @return mysqli_result MySql Resultset Object
    */
    public function query($query) {
    	$this->queryCount++;
    	
    	if(MyObjectsDebugMode) {
    		$this->log .= "---- <b>Begin Sql Query " . $this->queryCount . "</b> : " . 
    		date('Y-m-j H:m:s', time()) . " ----<br>\n";
    		$this->log .= "\n<pre>" . $query . "</pre>";
    		$this->log .= "\n---- <b>End Sql Query</b> ----<br>\n<br>\n";
    	}
    	
        /* Call mysqli's query() method */
        $result = parent::query($query);
        if(mysqli_error($this)) {
            throw new QueryException(mysqli_error($this) . "\n in query:\n " .
                                     $query . "\n", mysqli_errno($this));
        }
        return $result;
    }
    
    /**
    * Checks if the database supports prepared statements
    *
    * This method determines if the MySql prepared statements are supported
    * by checking the version number of the MySql server. If the version
    * is 4.1 series or above prepared statements are supported.
    *
    * @return boolean True if the database supports prepared statements
    */
    public function preparedStatsSupport() {
        return $this->server_version > 40100;
    }
    
    /**
    * Returns the number of queries that were run so far.
    *
    * @return int Number of runed queries
    */
    public function getQueryCount() {
    	return $this->queryCount;
    }
    
    /**
    * Dumps the sql queris that were run so far.
    *
    * @return string The sql queries that were run
    */
    public function dumpLog() {
    	return $this->log;
    }
}
?>