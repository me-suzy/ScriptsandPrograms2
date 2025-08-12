<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
* @version $Id: Core.php,v 1.17 2004/12/07 19:30:03 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsRuntime
*/

/**
* Mapable interface will be implemented by all classes that will hold the
* database table values.
*
* The classes that will describe the database tables will implement this interface.
*
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @version 1.0
* @package MyObjectsRuntime
*/
interface  Mapable {
    
    /**
    * Returns value of the specified primary key
    *
    * @param string Primary key name of the object
    * @return mixed Primary key value of the object
    */
    public function getKeyValue($keyName = null);
    
    /**
    * Checks if the current state of the object is valid
    *
    * <p>This method should check the validness of the object. A valid
    * object means all the required properties of that object are not null
    * (set to some values). Validness check is made before store operations
    * to the database.</p>
    *
    * @param boolean $exclude Should be passed true to skip the test of
    * auto_increment primary key field.
    * The primary key field is generally an integer value with an auto_increment
    * property, hence before an insert operation the primary key value
    * does not need to be set.
    * @return boolean Returns true if the object is valid
    */
    public function isValid($exclude = false);
    
    /**
    * Returns the database table name for this type of object
    *
    * Static method that returns the mysql database table name for this type of object.
    * This value is used in database queries.
    *
    * @return string Returns the table name for this type of object
    */
    public static function getTableName();
    
    /**
    * Returns the database tables primary key field names
    *
    * Static method that returns the mysql database tables primary key field names
    * for this type of object. The array of field names that are the primary keys
    * for this table are returned. If there is no primary key defined an array
    * with size 0 is returned.
    *
    * @return array Returns the array of tables primary key fields
    */
    public static function primaryKeys();
    
    /**
    * Factory method for creating a Mapable object using the supplied unique values
    *
    * This factory method is used to get the Mapable object with the specified
    * unique field values. If the user doesn't explicitly pass the second parameter
    * false, this method first checks if the requested object was previously retreived
    * from the database. If it was retreived before, it simply returns that objec.
    * If the requested object was not retreived before, it makes a selection query
    * based on the unique values and creates a new object usign the mysqli_result object.
    * The new object is added to the static array that holds all the instances
    * of this class that was previously retreived from the database.
    *
    * Example Usage:
    * <i>Suppose that this method is implemented by a class named User.</i>
    * <code>$usr = User::get(array('email'=>'erdinc@yilmazel.com'))</code>
    *
    * @param array $uniqueValues Associative array of unique values that will be used
    * in retreiving the object from the database.
    * @param boolean $checkExistance If this is passed as true the method should first check
    * the existance of the requested object. If it is passed false, it skips the
    * existance check and retreives the specified object from the database.
    * @throws ObjectNotFoundException In case the object with the supplied unique
    * values was not found
    * @return The requested Mapable object
    */
    public static function get($uniqueValues, $checkExistance = true);
    
    /**
    * Returns the object instance with the specified unique values,
    * but uses a normalized query for selecting the foreign objects.
    *
    * <p>This factory method is used to get the Mapable object with the specified
    * unique field values. If the user doesn't explicitly pass the second parameter
    * false, this method first checks if the requested object was previously retreived
    * from the database. If it was retreived before, it simply returns that object.
    * If the requested object was not retreived before, it makes a selection query
    * based on the unique values and creates a new object usign the mysqli_result object.
    * The new object is added to the static array that holds all the instances
    * of this class that was previously retreived from the database.</p>
    *
    * <p>The difference of this method from the Mapable::get() method is that
    * this method joins the tables of the foreign objects for this class in the
    * selection query. Because data for all the objects are selected in a single
    * query this method should give better performance then the get method. However
    * unlike get method this method doesn't check if the foreign objects for the
    * requested object have been retreived before. New instances of foreign objects
    * are created when this method is called.</p>
    *
    * Example Usage:
    * <code>$object = User::normalizedGet(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))</code>
    *
    * This method can also be called using a single scalar type as the first variable.
    * This value should be the primary key value for the object.
    * Example Usage:
    * <code>$object = User::normalizedGet(1)</code>
    *
    * @param mixed $uniqueValues Associative array of unique values that will be used
    * in retreiving the object from the database. This value can also be passed as a single
    * scalar type as the primary key for the object.
    * @param boolean $checkExistance If this is passed as true the method should
    * first check the existance of the requested object. If it is passed false,
    * it skips the existance check and retreives the specified object from the database.
    * @throws ObjectNotFoundException In case the object with the supplied unique
    * values was not found
    * @return Mapable The requested Mapable object
    */
    public static function normalizedGet($uniqueValues, $checkExistance = true);
    
    /**
    * Returns the data type of specified property name
    *
    * @param string $propName The name of property
    * @return string The data type of supplied property, If the property
    * is not defined in the class returns false.
    */
    public static function propertyType($propName);
    
    /**
    * Returns the Mapable object instance for this class which has the specified
    * unique values
    *
    * Searchs the static array which holds the instances of that class for the
    * specified unique values. If the object instance is found it returns the
    * requested object. Otherwise it returns false.
    *
    * Example Usage:
    * <i>Suppose that this method is implemented by a class named User.</i>
    * <code>$usr = User::getInstance(array('username'=>'eyilmazel'))</code>
    *
    * @param array $uniqueValues Associative array of unique values that will be used
    * in searching.
    * @return mixed The requested Mapable object is returned if it is found.
    * False otherwise.
    */
    public static function getInstance($uniqueValues);
    
    /**
    * Adds the passed Mapable object to the classes data structure that
    * holds the instances
    *
    * Every class that implements Mapable interface should hold references
    * of the previously registered objects. This method adds the passed
    * Mapable object to the data structure that holds the instances of that
    * class. The method should first check the existance of the object.
    *
    * @param Mapable The object that will be added
    * @return void
    */
    public static function addInstance(Mapable $instance);

    /**
    * Removes tha passed Mapable object from the classes data structure that
    * holds the instances
    *
    * Every class that implements Mapable interface should hold references
    * of the previously registered objects. This method removes the passed
    * Mapable object from the data structure that holds the instances of that
    * class.
    *
    * @param Mapable The object that will be removed
    * @return void
    */
    public static function removeInstance(Mapable $instance);
    
    /**
    * Makes a selection query based on the given unique fields and selects the
    * specified fields
    *
    * This method simply calls the DbModel::select method with the table name for this
    * class as the first parameter.
    *
    * @see DbModel::select
    * @param array $uniqueValues Associated array of unique values that will be
    * used in the where clause.
    * If uniqueValues parameter is not specified no Where clause will be
    * included in the database query.
    * @param string $fields Comma seperated values of fields that will be selected.
    * Default value is *
    * @return mysqli_result Returns the mysqli result set object of the query
    */
    public static function select($uniqueValues = null, $fields = '*');
    
    /**
    * Returns the string representation of the Mapable object
    * using the supplied View.
    *
    * <p>Users can define their own View classes to get different views
    * of the objects. The runtime defines a DefaultMapableView class
    * that just shows the properties of the Mapable objects in a table.</p>
    *
    * Example:
    * <code>
    * $user = User::get(1);
    * $str = $user->getView(new DefaultMapableView());
    * echo $str;
    * </code>
    *
    * <p>MyObjects runtime also defines an XmlView class to view objects
    * as xml strings</p>
    *
    * Example:
    * <code>
    * $user = User::get(1);
    * $str = $user->getView(new XmlView());
    * echo $str;
    * </code>
    *
    * @see View
    * @see DefaultMapableView
    * @see XmlView
    * @param View $view The View object that will handle the view part of the object
    * @return string The view of the object
    */
    public function getView(View $view);
    
    /**
    * Loads the values stored in an associative array into the Mapable
    * object. 
    * 
    * This method can be used to automatically call the setter methods for each
    * value defined in the array.
    *
    * @param array $array Associative array of values that will be loaded.
    * @param boolean $nonempty If this parameter is passed as true the empty values
    * of the array will not be loaded into the Mapable object
    * @return boolean True on success, false on failure
    * @throws InvalidValueException if the setter methods return an error this
    * exception is thrown
    */   
    public function loadArray($array, $nonempty = true);
}

/**
* DbModel class provides static methods that will act as a bridge between
* the database and the objects
*
* This class will be used for retreving data from the database and writing
* back the data from the objects to the database. It defines some methods
* for generic select, insert, update and delete queries.
*
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @version 1.0
* @package MyObjectsRuntime
*/
class DbModel {
	
    /**
    * @var array Stack that will hold the locked objects.
    * This value is used for preventing recursive update calls between the objects.
    */
    private static $lock = array();
    
    /**
    * Makes a selection query.
    *
    * <p>Makes a selection query based on the provided table name. It uses the supplied
    * uniqueValues in the where clause of the sql query and selects the provided fields.</p>
    *
    * Example usage :
    * <code>
    * $result = DbModel::select('users', array('username'=>'eternity'), 'userid, name');
    * $users = DbModel::get($result);
    * </code>
    *
    * <p>If you want to add pattern matching in your query you can add "LIKE::"
    * and "NOT LIKE::" keywords as prefixes to the values in your arrays.
    * For example if you'd like to select users whose name starts with E:</p>
    * <code>
    * $result = DbModel::select('users', array('name'=>'LIKE::E%'));
    * $users = DbModel::get($result);
    * </code>
    * For the users whose names do not start with E
    * <code>
    * $result = DbModel::select('users', array('name'=>'NOT LIKE::E%'));
    * $users = DbModel::get($result);
    * </code>
    * If you want to add != to the query you can use the 'NOT::' prefix
    * <code>
    * $result = DbModel::select('users', array('username'=>'NOT::erdinc'));
    * $users = DbModel::get($result);
    * </code>
    *
    * If you want to add OR statements in your sql, you can write the value part of
    * your unique values array also as an array.
    * Here is an example:
    * <code>
    * $result = DbModel::select('users', array('name'=>array('LIKE::E%', 'LIKE::F%')));
    * $users = DbModel::get($result);
    * </code>
    * The above code will select the users whose names begin with E or F.
    * <p>You can add more than one constraints for a single field too:</p>
    * <code>
    * $result = DbModel::select('users', array('name'=>'LIKE::E%', 'name'=>'NOT::Erdinc'));
    * $users = DbModel::get($result);
    * </code>
    * @param string $tableName The table name that will be used for selection query
    * @param array $uniqueValues Associated array of unique values that will be used
    * in the where clause.
    * If uniqueValues parameter is not specified no Where clause will be included
    * in the database query.
    * In the value part of your arrays you can add "LIKE::", "NOT LIKE::" or "NOT::"
    * prefixes for pattern matching and inverting.
    * @param string $fields Comma seperated values of fields that will be selected.
    * Default value is *
    * @param int $limit This parameter should be passed a value larger than 0 to
    * limit the number of rows returned
    * @param int $offset The offset that will be used in the limit block of the query
    * @return mysqli_result Returns the mysqli result set object of the query
    */
    public static function select($tableName, $uniqueValues = null, $fields = "*",
                                  $limit = 0, $offset = 0) {
        // Check if the supplied $uniqueValues variable is an array
        if(!is_null($uniqueValues) && !is_array($uniqueValues)) {
            throw new IllegalArgumentException('The second parameter passed to 
            DbModel::select should be an associative array');
        }
        // Get the database connection object
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        // Create the sql query
        $sql = 'SELECT ' . $fields . ' FROM `' . $tableName.'`';
        if (count($uniqueValues) > 0 ) {
            $sql .= ' WHERE ';
            $addAnd = false;
            foreach ($uniqueValues as $key=>$value) {
            	if(!is_array($value)) {
            		$value = array($value);
            	}
            	if($addAnd) $sql .= ' AND';
            	$sql .= ' ( ';
            	$addOr = false;
            	foreach ($value as $val) {
	            	if(strpos($val, 'LIKE::') === 0) {
	            		$equalityType = 'LIKE';
	            		$val = substr($val, 6);
	            	}
	            	elseif (strpos($val, 'NOT LIKE::') === 0) {
	            		$equalityType = 'NOT LIKE';
	            		$val = substr($val, 10);
	            	}
	            	elseif (strpos($val, 'NOT::') === 0) {
	            		$equalityType = '!=';
	            		$val = substr($val, 5);
	            	}
	            	else {
	            		$equalityType = '=';
	            	}
	            	if($addOr) {
	            		$sql .= ' OR ';
	            	}
	            	$sql .= '`' . $key . '` '.$equalityType.' \'' . $db->real_escape_string($val) . '\' ';
	            	$addOr = true;
            	}
            	$sql .= ' ) ';
            	$addAnd = true;
            }
        }
        
        // If the limit parameter is passed add the limit statement to the query
        if($limit > 0) {
            $sql .= ' LIMIT ' . $offset . ', ' . $limit; 
        }

        // Execute the query and return the result set
        return $db->query($sql);
    }
    
    /**
    * Prepares a selection query
    *
    * <p>Prepares a selection query based on the provided table name. It uses the supplied
    * uniqueValues in the where clause of the sql query and selects the provided fields.</p>
    *
    * Example usage :
    * <code>
    * $stmt = DbModel::prepareSelect('users', array('username'=>'eternity'), 'userid, name');
    * $stmt->execute();
    * $user = new User();
    * DbModel::bind($user, $stmt);
    * $stmt->close();
    * </code>
    *
    * @param string $tableName The table name that will be used for selection query
    * @param array $uniqueValues Associated array of unique values that will be used
    * in the where clause.
    * If uniqueValues parameter is not specified no Where clause will be included
    * in the database query.
    * @param string $fields Comma seperated values of fields that will be selected.
    * Default value is *
    * @param int $limit This parameter should be passed a value larger than 0 to
    * limit the number of rows returned
    * @param int $offset The offset that will be used in the limit block of the query
    * @throws PreparedStatementsNotSupportedException Thrown if the MySql version is older than 4.1
    * @return mysqli_stmt Returns prepared Mysql statement.
    */
    public static function prepareSelect($tableName, $uniqueValues = null, $fields = "*",
                                          $limit = 0, $offset = 0) {

        // Get the database connection object
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        if(!$db->preparedStatsSupport()) {
            throw new PreparedStatementsNotSupportedException('Your version of MySql server does not
            support prepared statements. Upgrade to MySql 4.1 or above to use this method.');
        }
                                              
        // Check if the supplied $uniqueValues variable is an array
        if(!is_null($uniqueValues) && !is_array($uniqueValues)) {
            throw new IllegalArgumentException('The second parameter passed to 
            DbModel::select should be an associative array');
        }
        
        // Create the sql query
        $sql = 'SELECT ' . $fields . ' FROM `' . $tableName.'`';
        if (count($uniqueValues) > 0 ) {
            $sql .= ' WHERE ';
            $first = true;
            foreach ($uniqueValues as $key=>$value) {
                if($first) {
                    $sql .= '`' . $key . '` = \'' . $db->real_escape_string($value) . '\' ';
                    $first = false;
                } else {
                    $sql .= 'AND `' . $key . '` = \'' . $db->real_escape_string($value) . '\' ';
                }
            }
        }
        
        // If the limit parameter is passed add the limit statement to the query
        if($limit > 0) {
            $sql .= ' LIMIT ' . $offset . ', ' . $limit; 
        }
        
        $stmt = $db->prepare($sql);
        return $stmt;
    }
    
    /**
    * Selects similar records on the database based on the specified objects properties
    *
    * <p>The similar objects are the ones that share the same properties with the suplied
    * object. The primary key fields of the suplied object are discarded.</p>
    * 
    * Example usage:
    * <code>
    * $usr = new User();
    * $user->setAdmin(true);
    * $result = DbModel::selectSimilar($object);
    * $admins = DbModel::load($result);
    * </code>
    *
    * @param Mapable $object The object that will be used for finding
    * similar objects
    * @param string $fields The fields that will be returned in the mysqli_result object
    * @param int $limit This parameter should be passed a value larger than 0 to
    * limit the number of rows returned
    * @param int $offset The offset that will be used in the limit block of the query
    * @return mysqli_result Resultset of similar object records
    */
    public static function selectSimilar(Mapable $object, $fields = '*', $limit = 0,
                                         $offset = 0) {
        // Get the database connection object
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        // Generate the sql query
        $sql = 'SELECT ' .$fields . ' FROM `' . $object->getTableName() . '`';
        
        $first = true;
        foreach ($object as $paramName => $paramValue) {
            if(!in_array($paramName, $object->primaryKeys())) {
                // Check if the property exists in the database and the property has a value
                if($object->propertyType($paramName) && is_scalar($paramValue)) {
                    if($first) {
                        $sql .= ' WHERE `' . $paramName . '` = \'' . 
                        $db->real_escape_string($paramValue) . '\'';
                        $first = false;
                    } else {
                        $sql .= ' AND `' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                    }
                }
            }
        }
        
        // If the limit parameter is passed add the limit statement to the query
        if($limit > 0) {
            $sql .= ' LIMIT ' . $offset . ', ' . $limit; 
        }
        // Execute the query and return the result set
        return $db->query($sql);
    }
    
    /**
    * Selects the objects that have different properties than the supplied object
    *
    * <p>Makes a database query depending ths supplied Mapable object data to select
    * different objects from the database.</p>
    *
    * <p>Example Code</p>
    * <code>
    * $user = User::get(array('name'=>'foo'));
    * $result = DbModel::selectDifferent($user);
    * $differentUsers = DbModel::get($result);
    *
    * foreach($differentUsers as $u) {
    *    // Some operations on the $u object
    * }
    * </code>
    *
    * @param Mapable $object Reference object
    * @param string $fields (Optional) The fields that will be selected. The field names
    * should be seperated with comma. Default is '*'
    * @param int $limit (Optional) Limits the number of objects that will be returned
    * in the mysqli resultset
    * @param int $offset (Optional) The offset that will be used in query
    * @return mysqi_result The resultset
    */
    public static function selectDifferent(Mapable $object, $fields = '*',
                                           $limit = 0, $offset = 0) {
        // Get the database connection object
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        // Generate the sql query
        $sql = 'SELECT ' .$fields . ' FROM `' . $object->getTableName() . '`';
        
        $first = true;
        foreach ($object as $paramName => $paramValue) {
            if(!in_array($paramName, $object->primaryKeys())) {
                // Check if the property exists in the database and the property has a value
                if($object->propertyType($paramName) && is_scalar($paramValue)) {
                    if($first) {
                        $sql .= ' WHERE `' . $paramName . '` != \'' .
                        $db->real_escape_string($paramValue) . '\'';
                        $first = false;
                    } else {
                        $sql .= ' AND `' . $paramName . '` != \'' .
                        $db->real_escape_string($paramValue) . '\'';
                    }
                }
            }
        }
        
        // If the limit parameter is passed add the limit statement to the query
        if($limit > 0) {
            $sql .= ' LIMIT ' . $offset . ', ' . $limit; 
        }
        
        // Execute the query and return the result set
        return $db->query($sql);
    }
    
    /**
    * Returns the similar objects that share the same properties
    *
    * Example usage:
    * <code>
    * $usr = new User();
    * $usr->setAdmin(true);
    * $admins = DbModel::getSimilar($usr);
    * </code>
    *
    * @see get
    * @see selectSimilar
    * @param Mapable $object The object whose similar objects will be retreived
    * @param string $fields The fields that will be retreived from the database for
    * for the objects
    * @param int $limit Maximum number of objects that will be returned
    * @param int $offset The offset that will be added to the database query
    * @return mixed Array of similar objects
    */
    public static function getSimilar(Mapable $object, $fields = '*',
                                      $limit = 0, $offset = 0) {
        // Call DbModel::selectSimilar to make a database query for
        // getting similar objects
        $result = DbModel::selectSimilar($object, $fields, $limit, $offset);
        // Create objects from the returned resultset and return the array
        // of objects that are created
        return DbModel::get($result);
    }
    
    /**
    * Counts the number of similar objects in the database that have the same
    * properties with the specified object
    *
    * Example Usage
    * <code>
    * $usr = new User();
    * $usr->setAdmin(true);
    * $adminCount = DbModel::countSimilar($usr);
    * </code>
    *
    * @param Mapable $object The object that will be used for finding similar objects
    * @return int The numer of similar object records
    */
    public static function countSimilar(Mapable $object) {
        // Get the database connection object
        $db = MyObjectsBase::getInstance()->getDbConnection();
        // Generate the sql query
        $sql = 'SELECT count(*) as count FROM `' . $object->getTableName(). '`';
        
        $first = true;
        foreach ($object as $paramName => $paramValue) {
            if(!in_array($paramName, $object->primaryKeys())) {
                // Check if the property exists in the database and the property has a value
                if($object->propertyType($paramName) && is_scalar($paramValue)) {
                    if($first) {
                        $sql .= ' WHERE `' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                        $first = false;
                    } else {
                        $sql .= ' AND `' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                    }
                }
            }
        }
        
        // Run the query
        $result = $db->query($sql);
        // Fetch the result set into an array
        $row = $result->fetch_row();
        // Close the result set
        $result->close();
        // Return the number of similar objects
        return $row[0];
    }

    /**
    * Loads the values stored in the mysqli_result to the specified Mapable object
    *
    * This method iterates through all the properties of the specified Mapable object.
    * If a field name in the mysqli resultset object is same with the property name
    * of the object, the value taken from the result set is assigned the the property
    * value of the object. The properties of the Mapable object shouldn't be private
    * or protected for the object iteration to work properly.
    *
    * This method can work with both a mysqli_result object as the second parameter
    * and an sql statement that will be sent to the database.
    *
    * Example Usage:
    * <code>
    * $usr = new User();
    * DbModel::load($usr, 'Select * From users Where userid=1');
    * </code>
    * Or the same method can be called as follows
    * <code>
    * $usr = new User();
    * $result = $db->query('Select * From users Where userid=1');
    * DbModel::load($usr, $result);
    * </code>
    *
    * @param Mapable The object that will be filled with the values retreived
    * from the database
    * @param mixed $result Resultset object that will be used for retreiving data,
    * or the sql string that will be sent to the mysql server.
    * @return boolean Returns true if the load is successful
    */
    public static function load(Mapable $object, $result) {
        // Check if a mysqli_result object is supplied.
        // If not, then assume that the $result parameter is an sql string
        // and run it.
        if(!($result instanceof mysqli_result)) {
            if(is_string($result)) {
                $db = MyObjectsBase::getInstance()->getDbConnection();
                try {
                    if(!($result = $db->query($result))) {
                        return false;
                    }
                } catch (QueryException $e) {
                    return false;
                }
            } else {
                return false;
            }
        }
        
        // Fetch the result set into an associated array
        if($row = $result->fetch_assoc()) {
            // For each object property if the property name equals the
            // resultsets current field name, assign the value in the
            // resultset object to the property of the object
            foreach ($object as $propName => $propValue) {
                if(isset($row[$propName])) {
                    $object->$propName = $row[$propName];
                }
            }
            // Close the resultset
            $result->close();
            // Because we know that the object exists in the database
            // add it to the instances hold by the class of that object
            $object->addInstance($object);
            return true;
        }
        // Close the resultset
        $result->close();
        return false;
    }

    /**
    * Prepares a Mysqli_stmt object using the supplied sql query
    *
    * @param string $query Sql query that will be used
    * @throws PreparedStatementsNotSupportedException Thrown if the MySql version is older than 4.1
    * @return mysqli_stmt Prepared mysqli statement
    */
    public static function prepare($query) {
        $db = MyObjectsBase::getInstance()->getDbConnection();
        if(!$db->preparedStatsSupport()) {
            throw new PreparedStatementsNotSupportedException('Your version of MySql server does not
            support prepared statements. Upgrade to MySql 4.1 or above to use this method.');
        }
        return $db->prepare($query);
    }
    
    /**
    * Binds the supplied Mapable object to the supplied prepared statement
    *
    * This method binds the supplied Mapable object to the Mysql prepared statement
    * My benchmark tests show that using the old style selects and using the load
    * method to bind the resultset to object is much faster, but incase you need
    * to bind a Mapable object to a prepared statement here is an example usage
    * of this method :
    * <code>
    * $stmt = DbModel::prepareSelect('city', array('Name'=>'Istanbul'));
    * $city = new City();
    * $stmt->execute();
    * DbModel::bind($city, $stmt); // Fetches a row
    * $stmt->close();
    * echo $city->getPopulation();
    * </code>
    * You can get the same result in old fashioned but faster way :
    * <code>
    * $result = DbModel::select('city', array('Name'=>'Istanbul'));
    * $city = new City();
    * DbModel::load($city, $result);
    * echo $city->getPopulation();
    * </code>
    * @return boolean|null True if the data fetch from the prepared statement is successful, false
    * if an error occured and null if the there's no row/data in the prepared statement.
    */
    public function bind(Mapable $object, mysqli_stmt $stmt) {
        $result = $stmt->result_metadata();
        $fields = $result->fetch_fields();
        $arr = array();
        $arr[] = $stmt;
        foreach ($fields as $field) {
            $fieldName = $field->name;
            $arr[] = &$object->$fieldName;
        }

        call_user_func_array('mysqli_stmt_bind_result', $arr);
        return $stmt->fetch();
    }
    
    /**
    * Converts the mysqli_result to the Mapable objects
    *
    * <p>This method creates objects from the mysqli_result object and
    * returnes these objects as an array. The first parameter can also be an sql string
    * that will be run and converted to a mysqli_result object.</p>
    * <p>The returned type of this object depends on the characteristics of the MySql result set.</p>
	* <p>If the result set contains only one kind of object type; that is, only columns from one
	* table are returned, the returned type will be a regular array. If the result set contains
	* more than one kind of object type; that is, columns from different tables are returned, the
	* returned type will be a multidimensional array.</p>
	* <p>If only a one row is returned from the database, by passing the third parameter $compact
	* as true, you can make the method return a more compact data structure. For instance, if only
	* one row and a single type of object is returned, by passing the third parameter true , you can
	* make the method return the object instance that is returned from the database.</p>
    * <p>If the resultset contains no rows false is returned.</p>
    * <p>The second parameter $arfk (Auto Register Foreign Keys) should be passed true
    * for registering the foreign objects automatically.</p>
    * <p>Here are some examples:</p>
    * <code>
    * // the third parameter is left as default, which is false.
	* $objects = DbModel::get("SELECT * FROM city LIMIT 5");
	* </code>
	* <p>In this case the number of rows returned is 5. Number of object types that is
	* returned is 1. (City) So the $objects variable will be a regular array of City objects.<br/>
	* Result set: 5 Rows * 1 Column (1 type of object)<br/>
	* Returned value: A regular array of 5 City objects.</p>
	* <code>
	* // the third parameter is left as default, which is false.
	* $objects = DbModel::get("SELECT * FROM city LIMIT 1");
	* </code>
	* <p>In this case the number of rows returned is 1. Number of object types that is
	* returned is also 1. (A City object) So the $objects variable will be a regular array
	* containing one City object.<br/>
	* Result set: 1 Row * 1 Column (1 type of object)<br/>
	* Returned value: A regular array of 1 City object.</p>
	* <code>
	* // the third parameter is passed as true (Compact mode)
	* $objects = DbModel::get(SELECT * FROM city LIMIT 1, false, true);
	* </code>
	* <p>
	* In this case the number of rows returned is 1. Number of object types that
	* is returned is also 1. (A City object) Because we passed the compact parameter
	* as true instead of returning an array, only a City object instance will be returned.<br/>
	* Result set: 1 Row * 1 Column (1 type of object)<br/>
	* Returned value: A City object.</p>
	* <code>
	* // the third parameter is left as default, which is false.
	* $objects = DbModel::get("SELECT city.*, country.* FROM city, country WHERE
	* city.CountryCode = country.Code LIMIT 2");
	* </code>
	* <p>In this case the number of rows returned is 2. Number of object types
	* that is returned is 2. (A City object and a Country object). Because there are
	* more than one type of objects that is returned, the returned value will be
	* multidimensional array.</p>
	* <p>The first dimension of the array corresponds to the rows of the result set.
	* ($objects[0] is the first row, $objects[1] is the second row.) Each row are also
	* associative arrays in this case. $objects[0][city] is the City object for the first
	* row in the result set and $objects[0][country] is the Country object for the first row
	* in the result set.</p>
	* <p>If only one row returns from the database in this kind of a query, the result will
	* be again a multidimensional array as it is in this example.<br/>
	* Result set: n Row * m Column (1 or more rows and more than one type of objects. m > 1)<br/>
	* Returned value: A multidimensional array of objects.</p>
	* <code>
	* // the third parameter is passed as true. (Compact mode)
	* $objects = DbModel::get("SELECT city.*, country.* FROM city, country WHERE
	* city.CountryCode = country.Code LIMIT 1", false, true);
	* </code>
	* <p>If only one row is returned from the database and if you pass the third parameter
	* as true where more than one type of objects are returned from the database, an associative
	* array will be returned.</p>
	* <p>In this case $objects['city'] will be a City object and $objects['country'] will be a Country object.<br/>
	* Result set: 1 Row * m Column (1 row and more than one type of objects. m > 1)<br/>
	* Returned value: An associative array of objects</p>
	*
    * @param mixed $result A mysqli_result object or an sql string that will select
    * records from the database
    * @param boolean $arfk (Auto Register Foreign Keys) When this parameter is passed true,
    * the foreign key objects for the generated objects are registered automatically
    * @param boolean $compact When this parameter is passed true, if the resultset contains
    * only one row the returned data structure will be compacted.
    * @return mixed See the method documentation for details of the return types.
    */
    public static function get($result, $arfk = false, $compact = false) {
        // Check if the supplied parameter is a mysqli_result object
        // if it is not, then assume it is a sql query and run it
        if(!($result instanceof mysqli_result)) {
            if(is_string($result)) {
                $db = MyObjectsBase::getInstance()->getDbConnection();
                try {
                    if(!($result = $db->query($result))) {
                        return false;
                    }
                } catch (QueryException $e) {
                    return false;
                }
            } else {
                return false;
            }
        }
        
        
        // Get field information for all columns
        $fieldInfo = $result->fetch_fields();
        // Create a multidimensional array that will hold the created objects
        $objects = array();
		
        if (mysqli_num_rows($result) == 0) {
        	$result->close();
        	return false;
        }
        
        // For each row in the result set
        for($i = 0; $i < mysqli_num_rows($result); $i++) {
            // Fetch the returned data into an array
            $row = $result->fetch_row();
    
            $objects[$i] = array();
            
            for ($j=0; $j < count($fieldInfo); $j++) {
                // Get the returned field name
                $fieldName = $fieldInfo[$j]->name;
                // Get the returned fields table name
                $fieldTable = $fieldInfo[$j]->table;
                
                if(!isset($objects[$i][$fieldTable])) {
                    // Use the getClassName function to determine the
                    // name of Class that corresponds the returned table name
                    if($className = getClassName($fieldTable)) {
                        $objects[$i][$fieldTable] = new $className;
                    }
                }
                if(isset($objects[$i][$fieldTable])) {
                    // Assign the objects properties
                    $objects[$i][$fieldTable]->$fieldName = $row[$j];
                }
            }
            if(isset($objects[$i][$fieldTable])) {
                $objects[$i][$fieldTable]->addInstance($objects[$i][$fieldTable]);
                if($arfk) {
                	try {
                		$objects[$i][$fieldTable]->registerForeignKeys();
                	} catch (ObjectNotFoundException $e) {}
                }
            }
        }
        
        // Close the result set
        $result->close();
        
        // If there is only a single object in the $objects array
        // return the instance that is held in the array
        if(count($objects) == 1 && count($objects[0]) == 1 && $compact) {
            return current($objects[0]);
        }
        // If there is only a single object in the $objects array and the $compact parameter is set
        // false, return an array holding the object instance.
        elseif (count($objects) == 1 && count($objects[0]) == 1 && !$compact) {
        	return array(current($objects[0]));
        }
        // If only one row is returned from the database and $compact parameter is set true
        // Return the array of objects that are created using that row.
        elseif ((count($objects) == 1 && count($objects[0] > 1)) && $compact) {
            return $objects[0];
        }
        // If only one row is returned from the database and $compact parameter is set false
        // Return a multidimensional array of objects. (Only one row and several columns
        // depending on the number of object types will be returned)
        elseif ((count($objects) == 1 && count($objects[0] > 1)) && !$compact) {
        	return $objects;
        }
        // If more than one row are returned from the database but
        // the created objects are all the same kind, create an other
        // regular array of the created objects and return it.
        elseif (count($objects) > 1 && count($objects[0]) == 1) {
            $array = array();
            foreach ($objects as $a) {
                $array[] = current($a);
            }
            return $array;
        }
        // If more than one row is returned and each row of result set
        // has more than one type of object then return the multidimensional
        // array of created objects.
        // The first dimension is the rows corresponding to each row of result set
        // and the second dimension is the different type of objects.
        elseif (count($objects) > 1 && count($objects[0]) > 1) {
            return $objects;
        } else {
            return false;
        }
    }
    
    /**
    * Stores the passed Mapable object in the database.
    *
    * <p>This function first checks the existance of the object  in the database
    * by checking the primary key field of the object. If the object exists in the
    * database it updates the record of the object, otherwise it inserts the new
    * values to the database.</p>
    *
    * <p>The foreign objects of this object can also be stored if the second parameter
    * is passed true. This means each object that are referenced from this object are
    * stored to the database.</p>
    *
    * Example Usage:
    * <code>
    * try {
    *     $usr = new User();
    *     $usr->setUsername('erdinc');
    *     $usr->setName('Erdinc Yilmazel');
    *     $usr->setEmail('erdinc@yilmazel.com');
    *     DBModel::store($usr);
    * } catch (InvalidValueException $e) {
    *       echo 'Invalid Value ' . $e->getValue();
    * } catch (UniqueKeyExistsException $e) {
    *       echo 'Unique key exists ' . $e->getValue();
    * }
    * </code>
    *
    * @param Mapable $object The object that will be stored in the database.
    * @param boolean $recursive If set true recursively store all foreign objects
    * @return boolean Returns true if the store is successful
    */
    public static function store(Mapable $object, $recursive = false) {
        // Check if all the required properties of the object are set
        if($object->isValid()) {
            // Check if the object exists in the database
            // If it exists then call the update method, otherwise call
            // the insert method
            if(DbModel::exists($object)) {
                return DbModel::update($object, $recursive);
            } else {
                return DbModel::insert($object, $recursive);
            }
        } else {
            throw new MapableNotValidException("Can't store invalid object: " .
            print_r($object, true));
        }
    }
    
    
    /**
    * Updates the database record of the object.
    *
    * This object first checks the validness of the object. An object is valid
    * if all the required fields of the object are set. If the object is valid
    * it makes an update query to the database using all the properties of the object.
    *
    * <p>The foreign objects of this object can also be stored if the second parameter
    * is passed true. This means each object that are referenced from this object are
    * stored to the database.</p>
    *
    * Example Usage:
    * <code>
    * try {
    *     $usr = User::get(array('username'=>'erdinc'));
    *     $usr->setName('Erdinc Yilmazel');
    *     $usr->setEmail('erdinc@yilmazel.com');
    *     DBModel::store($usr);
    * } catch (InvalidValueException $e) {
    *       echo 'Invalid Value ' . $e->getValue();
    * } catch (UniqueKeyExistsException $e) {
    *       echo 'Unique key exists ' . $e->getValue();
    * }
    * </code>
    *
    * @param Mapable $object The object that will be updated in the database.
    * @param boolean $recursive If set true recursively store all foreign objects
    * @throws MapableNotValidException In case the object specified is not valid.
    * @return boolean Returns true if the update is successful
    */
    public static function update(Mapable $object, $recursive = false) {
        // Check if all the required fields of the object are set
        if($object->isValid()) {
            if($recursive) {
                // Push this object into the stack to track update method calls
                // and avoid recursive cycles
                array_push(DbModel::$lock, $object);
            }
            
            // Get the database connection object
            $db = MyObjectsBase::getInstance()->getDbConnection();
            
            // Generate the sql query
            $sql = 'Update `' . $object->getTableName() . '` set ';
            $first = true;
            foreach ($object as $paramName => $paramValue) {
                // For each object's properties add a field in the sql query
                if($object->propertyType($paramName)) {
                    if($first) {
                        $sql .= '`' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                        $first = false;
                    } else {
                        $sql .= ', `' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                    }
                }
                // If the recursive parameter is set and a property of object
                // is instance of Mapable update that object too
                elseif ($recursive && $object->$paramName instanceof Mapable) {
                    if(!in_array($object->$paramName, DbModel::$lock)) {
                        try {
                            DbModel::store($object->$paramName, true);
                        } catch (NoPrimaryKeyException $e) {}
                    } else {
                        return true;
                    }
                }
            }

            $sql .= ' Where ';
            $first = true;
            foreach ($object->primaryKeys() as $key) {
                if(!$first) {
                    $sql .= ' AND';
                }
                $sql .= ' `' . $key . '` = \'' .
                $db->real_escape_string($object->getKeyValue($key)) . '\'';
                $first = false;
            }
            
            // If the object has no primary keys, we can't update the value.
            if($first) {
                throw new NoPrimaryKeyException('Can\'t update object.
                The given object does not have a primary key');
            }

            if($recursive) {
                array_pop(DbModel::$lock);
            }
            
            if($db->query($sql)) {
                $object->addInstance($object);
            } else {
                return false;
            }
        } else {
            throw new MapableNotValidException("Can't update invalid object: " .
            print_r($object, true));
        }
    }
    
    /**
    * Updates records in the database that are similar to reference object
    * to the values stored in target object
    *
    * Example Usage:<br/>
    * <code>
    * $usr = new User();
    * $usr->setEmail('erdinc@yilmazel.com');
    * $usr->setName('Erdinc Yilmazel');
    * $usr2 = new User();
    * $usr2->setEmail('deneme@deneme.com');
    * DbModel::updateSimilar($usr, $usr2);
    * </code><br/>
    * The generated sql for the above code is as follows:
    * <code>
    * Update `users` set `email` = 'deneme@deneme.com';
    * WHERE name = 'Erdinc Yilmazel' AND email = 'erdinc@yilmazel.com'
    * </code><br/>
    * Note that the primary key fields of the objects are ignored.
    *
    * @param Mapable $reference The reference object that will be used in where clause
    * @param Mapable $target The target object that the values will be taken from
    * @return boolean Return true if the update is successful
    */
    public static function updateSimilar(Mapable $reference, Mapable $target) {
        // Check if the supplied objects are instances of the same class
        if(get_class($reference) != get_class($target)) {
            return false;
        }
        
        // Get the database connection object
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        // Generate sql query
        $sql = 'Update `' . $target->getTableName() . '` set ';
        
        // Get the primary keys of the object
        $primaryKeys = $target->primaryKeys();
        
        $first = true;
        foreach ($target as $paramName => $paramValue) {
            if(!in_array($paramName, $primaryKeys)) {
                if($target->propertyType($paramName) && is_scalar($paramValue)) {
                    if($first) {
                        $sql .= '`' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                        $first = false;
                    } else {
                        $sql .= ', `' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                    }
                }
            }
        }
        
        $sql .= ' WHERE ';
        $first = true;
        foreach ($reference as $paramName => $paramValue) {
            if(!in_array($paramName, $primaryKeys)) {
                if($reference->propertyType($paramName) && is_scalar($paramValue)) {
                    if($first) {
                        $sql .= '`' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                        $first = false;
                    } else {
                        $sql .= ' AND `' . $paramName . '` = \'' .
                        $db->real_escape_string($paramValue) . '\'';
                    }
                }
            }
        }
        return $db->query($sql);
    }
    
    /**
    * Inserts the passed Mapable object to the database
    *
    * <p>This method iterates through all the properties of the object to form
    * an insertion query. The method first checks the validness of the object.
    * If the object is valid (all its required fields are set) it insersts the
    * object to the database.</p>
    *
    * <p>If the object is valid all its properties should be set to correct values.</p>
    *
    * <p>The foreign objects of this object can also be stored if the second parameter
    * is passed true. This means each object that are referenced from this object are
    * stored to the database.</p>
    *
    * Example Usage:
    * <code>
    * try {
    *     $usr = new User();
    *     $usr->setUsername('erdinc');
    *     $usr->setName('Erdinc Yilmazel');
    *     $usr->setEmail('erdinc@yilmazel.com');
    *     DBModel::insert($usr);
    * } catch (InvalidValueException $e) {
    *       echo 'Invalid Value ' . $e->getValue();
    * } catch (UniqueKeyExistsException $e) {
    *       echo 'Unique key exists ' . $e->getValue();
    * }
    * </code>
    *
    * @param Mapable $object The object that will be inserted to the database.
    * @param boolean $recursive If set true recursively store all foreign objects
    * @throws MapableNotValidException If the object is not valid
    * this exception is thrown
    * @return mixed Returns the insert id if the query is successful, false otherwise
    */
    public static function insert(Mapable $object, $recursive = false) {
        // Check if all the required fields of the object are set
        // The primary key fields are generally auto_increment fields and get the
        // value after the insert operation is made.
        if($object->isValid(true)) {
            if($recursive) {
                array_push(DbModel::$lock, $object);
            }
            $db = MyObjectsBase::getInstance()->getDbConnection();
            $sql = 'Insert into `' . $object->getTableName() . '` (';
            $sql2 = '';
            $first = true;
            foreach ($object as $paramName => $paramValue) {
                if($object->propertyType($paramName)) {
                    if($first) {
                        $sql .= '`' . $paramName . '`';
                        $sql2 .= '\'' . $db->real_escape_string($paramValue) . '\'';
                        $first = false;
                    } else {
                        $sql .= ', `' . $paramName . '`';
                        $sql2 .= ', \'' . $db->real_escape_string($paramValue) . '\'';
                    }
                }
                elseif ($recursive && $object->$paramName instanceof Mapable) {
                    if(!in_array($object->$paramName, DbModel::$lock)) {
                        DbModel::store($object->$paramName, true);
                    } else {
                        return true;
                    }
                }
            }
            
            $sql .= ') Values (' . $sql2 . ')';
            if($recursive) {
                array_pop(DbModel::$lock);
            }
            
            if($db->query($sql)) {
                $object->addInstance($object);
                $pk = $object->primaryKeys();
                if($object->isAutoIncremented($pk[0])) {
                    $object->$pk[0] = $db->insert_id;
                    return $db->insert_id;
                }
                return true;
            } else {
                return false;
            }
        } else {
            throw new MapableNotValidException("Can't insert invalid object: " .
            print_r($object, true));
        }
    }
    
    /**
    * Removes the object from the database
    *
    * <p>This method makes a deletion query to remove the passed object
    * from the database. It also removes the instance from the data structure
    * in the objects class.</p>
    *
    * Example Usage:
    * <code>
    * $usr = User::get(array('username'=>'erdinc'));
    * DBModel::delete($usr);
    * </code>
    *
    * @param Mapable $object The object that will be removed
    * @param boolean $recursive Make delete calls for each property defined as
    * a foreign key object.
    * @throws MapableNotValidException If the primary key value is not set
    * this exception is thrown
    * @return boolean Returns true if the deletion is successful
    */
    public static function delete(Mapable $object, $recursive = false) {
        if($recursive) {
            array_push(DbModel::$lock, $object);
        }
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        $sql = 'Delete From `' . $object->getTableName() . '` Where ';
        $first = true;
        foreach ($object->primaryKeys() as $key) {
            $value = $object->getKeyValue($key);
            if(is_null($value)) {
                throw new MapableNotValidException("Can't delete object,
                primary key: $key is not set: " . print_r($object, true));
            }
            if(!$first) {
                $sql .= ' AND';
            }
            $sql .= ' `' . $key . '` = \'' . $db->real_escape_string($value) . '\'';
            $first = false;
        }
        
        // If the object has no primary keys, we can't update the value.
        if($first) {
            throw new NoPrimaryKeyException('Can\'t delete the object. 
            The given object does not have a primary key.
            Use DbModel::deleteSimilar() instead.');
        }

        if($recursive) {
            foreach ($object as $paramName => $paramValue) {
                if ($object->$paramName instanceof Mapable) {
                    if(!in_array($object->$paramName, DbModel::$lock)) {
                        DbModel::delete($object->$paramName, true);
                    } else {
                        return true;
                    }
                }
            }

            array_pop(DbModel::$lock);
        }
        
        if($db->query($sql)) {
            $object->removeInstance($object);
            return true;
        } else {
            return false;
        }
    }
    
    /**
    * Deletes the similar objects which have the same properties as the
    * specified object
    *
    * <p>If the specified object exists in the database, it is deleted too.</p>
    *
    * Example Usage:
    * <code>
    * $usr = new User();
    * $usr->setUsername('erdinc');
    * DBModel::deleteSimilar($usr);
    * </code>
    *
    * @param Mapable $object The object that will be used to find similar records
    * @return boolean Returns true if the deletion is successful
    */
    public static function deleteSimilar(Mapable $object) {
        $db = MyObjectsBase::getInstance()->getDbConnection();
        $sql = 'Delete from `' . $object->getTableName() . '`';
        
        $first = true;
        foreach ($object as $paramName => $paramValue) {
            if($object->propertyType($paramName) && is_scalar($paramValue)) {
                if($first) {
                    $sql .= ' WHERE `' . $paramName . '` = \'' .
                    $db->real_escape_string($paramValue) . '\'';
                    $first = false;
                } else {
                    $sql .= ' AND `' . $paramName . '` = \'' .
                    $db->real_escape_string($paramValue) . '\'';
                }
            }
        }
        $object->removeInstance($object);
        return $db->query($sql);
    }

    /**
    * Checks the database for the existance of the object
    *
    * <p>This method looks up to the database for the supplied objects existance.
    * It basically checks if the primary key values for the object exists in
    * the table for this type of object.</p>
    *
    * Example Usage:
    * <code>
    * $usr = new User();
    * $usr->setUsername('erdinc');
    * if(DbModel::exists($usr)) {
    *     echo 'User erdinc exists';
    * }
    * </code>
    *
    * @param Mapable $object The object that will be looked up
    * @throws MapableNotValidException If the primary key value is not set
    * this exception is thrown
    * @return boolean Returns true if the object exists in database
    */
    public static function exists(Mapable $object) {
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        $sql = 'Select count(*) as count From `' . $object->getTableName() . '` Where ';
        $first = true;
        foreach ($object->primaryKeys() as $key) {
            $value = $object->getKeyValue($key);
            if(is_null($value)) {
                throw new MapableNotValidException("Can't lookup object,
                primary key: $key is not set: " . print_r($object, true));
            }
            if(!$first) {
                $sql .= ' AND';
            }
            $sql .= ' `' . $key . '` = \'' . $db->real_escape_string($value) . '\'';
            $first = false;
        }
        
        // If the object has no primary keys, we can't update the value.
        if($first) {
            throw new NoPrimaryKeyException('Can\'t delete the object. 
            The given object does not have a primary key.
            Use DbModel::deleteSimilar() instead.');
        }

        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
    
    /**
    * Checks the equality of the two Mapable objects
    *
    * <p>This method first checks if the supplied objects are instances of
    * same class, then it compares each primary key value. If all the primary keys
    * are same true is returned.</p>
    * <p>If no primary keys defined in the object no equality check can be made.
    * Hence false is returned</p>
    * @param Mapable $object1 The first object that will be checked for equality
    * @param Mapable $object2 The second object that will be checked for equality
    * @return boolean Returns true if the supplied objects are equal
    */
    public static function isEqual(Mapable $object1, Mapable $object2) {
        $equal = true;
        
        if(get_class($object1) != get_class($object2)) {
            return false;
        }
        
        $p = false;
        foreach ($object1->primaryKeys() as $key) {
            $p = true;
            if($object1->getKeyValue($key) != $object2->getKeyValue($key)) {
                $equal = false;
                break;
            }
        }
        
        if(!$p) {
            return false;
        }
        return $equal;
    }
    
    /**
    * Searchs the static array for the object with specified unique values
    *
    * This method searchs the array of object instances for the specified 
    * unique values. If an object is found the object is returned, otherwise
    * the method returns false.
    *
    * @param array $array Array of instances of the class
    * @param array $uniqueValues Associated array of unique values that will be
    * used for search
    * @return mixed Returns the object if it is found in the array. False 
    * otherwise
    */
    public static function getInstance($array, $uniqueValues) {
        // Check for every registered Class instance
        foreach ($array as $instance) {
            $return = true; // Assume that this instance is the one we are looking for
            foreach ($uniqueValues as $key=>$value) {
                $valid = false; // Checks the validness of supplied $key=>$value pair
                foreach ($instance as $propName => $propValue) {
                    if($propName != $key) continue;
                    $valid = true;
                    if($propValue != $value) {
                        $return = false;
                        break;
                    }
                }
                if(!$return) break;
            }
            if($return && $valid) {
                return $instance;
            }
        }
        return false;
    }
    
    /**
    * Runs the specified sql query
    *
    * @param string $sql The query that will be run
    * @return mysqli_result The returned resultset object
    */
    public static function query($sql) {
        // Get the database connection object
        $db = MyObjectsBase::getInstance()->getDbConnection();
        return $db->query($sql);
    }
}
/**
* Provides methods for dumping the Mapable objects to xml, and creating
* objects from the xml files.
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsRuntime
*/
class XmlModel {
    
    /**
    * Stack that will be used to avoid recursive calls of store method
    */
    private static $lock = array();
    
    /**
    * Loads the specified Xml File and creates the objects referenced in the file
    *
    * <p>This method siply creates a DOMDocument instance and passes that instance
    * to XmlModel::loadXmlDoc method</p>
    *
    * @see loadXmlDoc
    * @param string $xmlFile Name of xml file that will be loaded. The supplied
    * xml file should be in Mapable format defined by mapable.xsd Xml Schema
    * @return array Objects that are defined in xml file. See loadXmlDoc method
    * for detailed documentation
    */
    public static function load($xmlFile) {
        $doc = new DOMDocument();
        $doc->load($xmlFile);
        return XmlModel::loadXmlDoc($doc);
    }
    
    /**
    * Loads the supplied Xml String and creates the objects referenced in the string
    *
    * This method siply creates a DOMDocument instance and passes that instance
    * to XmlModel::loadXmlDoc method
    *
    * @see loadXmlDoc
    * @param string $xml Xml String in Mapable format that will be used.
    * @return array Objects that are defined in xml string. See loadXmlDoc
    * method for detailed documentation
    */
    public static function loadXml($xml) {
        $doc = new DOMDocument();
        $doc->loadXml($xml);
        return XmlModel::loadXmlDoc($doc);
    }
    
    /**
    * Generates objects specified in the DOMDocument object
    *
    * The supplied DOMDocument object is first validated against
    * the Mapable XML Schema file. This file can be found in runtime folder.
    * The array of objects referenced in the xml file are returned.
    *
    * @param DOMDocument $doc The XML Dom object that will be used to create
    * objects
    * @return array Array of mapable objects defined in the Xml file
    */
    public static function loadXmlDoc(DOMDocument $doc) {
        // Validate the specified ddl file using the XML Schema
        if(!$doc->schemaValidate(MyObjectsRuntimePath . '/mapable.xsd')) {
            throw new MapableFileNotValidException($xmlFile .
            ' is not a valid Mapable file');
        }
        
        $mapable = $doc->documentElement;
        $objects = array();
        foreach($mapable->childNodes as $objectElement) {
            if(!($objectElement instanceof DOMElement)) {
                continue;
            }
            if($objectElement->hasAttribute('id')) {
                $id = $objectElement->getAttribute('id');
                $objects[$id] = XmlModel::getObject($objectElement);
            }
        }
        return $objects;
    }
    
    /**
    * Retreives data from a DOMElement with the <object> tag and creates the object
    * that is defined.
    *
    * @param DOMElement $objectElement The node with tha tagName <object>
    * @param array $objects The <object> tag can have an attribute named ref.
    * When this attribute is set the referenced object is searched within this
    * array. If it is found then it is assigned to the corresponding property of
    * the requesting object
    * @return Mapable Object that is defined in the DOMElement
    */
    public static function getObject(DOMElement $objectElement, $objects = null) {
        
        if($objectElement->hasAttribute('ref')) {
            if(is_array($objects)) {
                return $objects[$objectElement->getAttribute('ref')];
            }
        }
        
        $className = $objectElement->getAttribute('instanceof');
        if(class_exists($className)) {
            $object = new $className();
            foreach ($objectElement->childNodes as $node) {
                if (!($node instanceof DOMElement)) {
                    continue;
                }
                if($node->tagName == 'primaryKeys') {
                    foreach ($node->childNodes as $property) {
                        if(!($property instanceof DOMElement)) {
                            continue;
                        }
                        $propName = $property->getAttribute('name');
                        foreach ($property->childNodes as $valueElement) {
                            if(!($valueElement instanceof DOMElement)) {
                                continue;
                            }
                            $value = trim($valueElement->nodeValue);
                            $object->$propName = $value;
                        }
                    }
                }
                elseif($node->tagName == 'property') {
                    $propName = $node->getAttribute('name');
                    foreach ($node->childNodes as $valueElement) {
                        if(!($valueElement instanceof DOMElement)) {
                            continue;
                        }
                        if($valueElement->tagName != 'object') {
                            $value = trim($valueElement->nodeValue);
                            $object->$propName = $value;
                        } else {
                            if(is_null($objects)) {
                                $objects = array();
                            }
                            $objects[$objectElement->getAttribute('id')] = $object;
                            $foreignObject = XmlModel::getObject($valueElement,
                                                                 $objects);
                            $object->$propName = $foreignObject;
                        }
                    }
                }
            }
            return $object;
        }
    }
    
    /**
    * Checks if the data type of an object property is a text type
    *
    * Used to determine if the property will be embeded in a CDATA section
    *
    * @return boolean True if the property is a text type
    */
    private static function isTextType($type) {
        return $type == 'string' || $type == 'char' || $type == 'varchar' ||
               $type == 'tinyblob' || $type == 'blob' || $type == 'mediumblob' ||
               $type == 'tinytext' || $type == 'text' || $type == 'mediumtext' ||
               $type == 'longblob' || $type == 'longtext' || $type == 'enum' ||
               $type == 'set';
    }
    
    /**
    * Creates a DOMDocument object and initializes it in Mapable format
    *
    * @return DOMDocument Created DOMDocument object
    */
    public static function createDoc() {
        $doc = new DOMDocument('1.0', 'utf-8');
        $mapable = $doc->createElement('mapable');
        $mapable->setAttribute('xmlns:xsi',
                               'http://www.w3.org/2001/XMLSchema-instance');
        $mapable->setAttribute('xsi:noNamespaceSchemaLocation',
                               MyObjectsRuntimePath . '/mapable.xsd');
        $mapable = $doc->appendChild($mapable);
        return $doc;
    }
    
    /**
    * Dumps the object to xml
    *
    * Dumps the object into xml that is in Mapable format (Defined in mapable.xsd file)
    *
    * @return mixed If a DOMDocument is supplied as the second parameter, this
    * method returns a DOMElement named 'object', if a DOMDocument is not supplied
    * a DOMDocument object is created and returned.
    */
    public static function store(Mapable $object, $doc = null, $foreignKeys = true) {
        
        $returnDoc = false;
        if(!($doc instanceof DOMDocument)) {
            // If a DOMDocument is not supplied create one
            $returnDoc = true;
            $doc = XmlModel::createDoc();
        }
        
        // Create object id
        $id = get_class($object);
        $primaryKeys = $object->primaryKeys();
        foreach ($primaryKeys as $key) {
            $id .= '-' . $object->getKeyValue($key);
        }
        
        $ob = $doc->createElement('object');
        $ob = $doc->documentElement->appendChild($ob);
        if(!in_array($object, XmlModel::$lock)) {
            if($foreignKeys) {
                // Push this object into the stack to track store method calls
                // and avoid recursive cycles
                array_push(XmlModel::$lock, $object);
            }
            
            $ob->setAttribute('id', $id);
            $ob->setAttribute('instanceof', get_class($object));
            if(count($primaryKeys) > 0) {
                $pk = $doc->createElement('primaryKeys');
                foreach ($primaryKeys as $key) {
                    if($object->$key) {
                        $property = $doc->createElement('property');
                        $property->setAttribute('name', $key);
                        $type = $object->propertyType($key);
                        $typeElement = $doc->createElement($type);
                        if(XmlModel::isTextType($type)) {
                            $value = $doc->createCDATASection($object->$key);
                            $typeElement->appendChild($value);
                        } else {
                            $value = $doc->createTextNode($object->$key);
                            $typeElement->appendChild($value);
                        }
                        $property->appendChild($typeElement);
                        $pk->appendChild($property);
                    }
                }
                $ob->appendChild($pk);
            }
            
            $x = false;
            foreach ($object as $propName => $propValue) {
                $type = $object->propertyType($propName);
                if(!in_array($propName, $primaryKeys) && $type && is_scalar($object->$propName)) {
                    $property = $doc->createElement('property');
                    $property->setAttribute('name', $propName);

                    $typeElement = $doc->createElement($type);
                    if(XmlModel::isTextType($type)) {
                        $value = $doc->createCDATASection($propValue);
                        $typeElement->appendChild($value);
                    } else {
                        $value = $doc->createTextNode($propValue);
                        $typeElement->appendChild($value);
                    }
                    $property->appendChild($typeElement);
                    $ob->appendChild($property);
                }
                if($foreignKeys) {
                    if($object->$propName instanceof Mapable) {
                        $fo = XmlModel::store($object->$propName, $doc);
                        $property = $doc->createElement('property');
                        $property->setAttribute('name', $propName);
                        $property->appendChild($fo);
                        $ob->appendChild($property);
                    }
                }
            }
            
        } else {
            $ob->setAttribute('ref', $id);
            $ob->setAttribute('instanceof', get_class($object));
            return $ob;
        }
        
        if($foreignKeys) {
            array_pop(XmlModel::$lock);
        }
        
        if($returnDoc) {
            return $doc;
        } else {
            return $ob;
        }
    }
    
    /**
    * Saves the supplied object in the specified file
    *
    * @param Mapable $object The object that will be stored
    * @param string $file The file that the object will be stored
    * @doc DOMDocument|null If a DOMDocument object is supplied the nodes
    * describing the object will be appended to it and it will be used to save
    * the Xml file. If a DOMDocument object is not supplied it will be created.
    * @param boolean $foreignKeys If passed true the objects that are referenced
    * from the supplied Mapable object will also be saved to the xml file.
    */
    public static function storeFile(Mapable $object, $file, $doc = null,
                                     $foreignKeys = true) {
        $doc = XmlModel::store($object, $doc, $foreignKeys);
        $doc->save($file);
    }
}
?>