<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: MyObjectsSettings.php,v 1.4 2004/11/26 15:33:35 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsRuntime
*/

// Runtime library path. This is the path of the directory named 'runtime'.
// Without a trailing slash
define('MyObjectsRuntimePath', '{%runtimePath%}');

// The path of generated classes
// Without a trailing slash
define('MyObjectsClassPath', '{%classPath%}');

// Set this constant true if you want to enable debug mode.
// Debug mode logs the sql queries that were run
define('MyObjectsDebugMode', false);

/**
* MyObjects Runtime Settings
*
* This class defines some configuration constants
*
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @version 1.0
* @package MyObjectsRuntime
*/
class MyObjectsRuntimeSettings {
    
    // This Section Should Be Edited -------------------------------------------
    
    // Database Host Name
    const DB_HOST =             '{%dbHost%}';
    // Database User Name
    const DB_USER =             '{%dbUser%}';
    // Database Password
    const DB_PASSWORD =         '{%dbPassword%}';
    // Database Name
    const DB_NAME =             '{%dbName%}';
    
    // End Of Editing Section. Do not change anything below this unless you know
    // what you are doing.
}

/**
* Default Config
*
* Default Config implementation that uses the constants defined
* in Settings class
*
* @see Settings
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @version 1.0
* @package MyObjectsRuntime
*/
class MyObjectsDefaultConfig implements MyObjectsConfig {
    
    /**
    * @var string $dbHost Database Server Host Name
    */
    protected $dbHost;
    /**
    * @var string $dbHost Database Server User Name
    */
    protected $dbUser;
    /**
    * @var string $dbHost Database Server Password
    */
    protected $dbPassword;
    /**
    * @var string $dbHost Database Name
    */
    protected $dbName;
    
    /**
    * Builds a new Config object
    *
    * @param string $dbHost Database Host Name
    * @param string $dbUser Database User Name
    * @param string $dbPassword Database Password
    * @param string $dbName Database Name
    * @return void
    */
    function __construct($dbHost, $dbUser, $dbPassword, $dbName) {
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
    }
    
    /**
    * Sets the database server host name
    *
    * @return void
    * @param string $dbHost Database host name
    */
    public function setDbHost($dbHost) {
        $this->dbHost = $dbHost;
    }
    
    /**
    * Sets the database user name
    * 
    * @return void
    * @param string $dbUser Database user name
    */
    public function setDbUser($dbUser) {
        $this->dbUser = $dbUser;
    }
    
    /**
    * Sets the database password
    *
    * @return void
    * @param string $dbPassword The database password
    */
    public function setDbPassword($dbPassword) {
        $this->dbPassword = $dbPassword;
    }
    
    /**
    * Sets the database name
    *
    * @return void
    * @param string $dbName Database name
    */
    public function setDbName($dbName) {
        $this->dbName = $dbName;
    }
    
    /**
    * Returns the database server host name
    *
    * @return string Database server host name
    */
    public function getDbHost() {
        return $this->dbHost;
    }
    
    /**
    * Returns the database user name
    *
    * @return string Database user name
    */
    public function getDbUser() {
        return $this->dbUser;
    }
    
    /**
    * Returns the database password
    *
    * @return string Database password
    */
    public function getDbPassword() {
        return $this->dbPassword;
    }
    
    /**
    * Returns the databse name
    *
    * @return string Database name
    */
    public function getDbName() {
        return $this->dbName;
    }
}

/**
* Config
*
* This interface defines some methods that should be implemented by all
* classes that provide Config options
*
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @version 1.0
* @package MyObjectsRuntime
*/
interface MyObjectsConfig {
    public function setDbHost($dbHost);
    public function setDbUser($dbUser);
    public function setDbPassword($dbPassword);
    public function setDbName($dbName);
    public function getDbHost();
    public function getDbUser();
    public function getDbPassword();
    public function getDbName();
}
?>