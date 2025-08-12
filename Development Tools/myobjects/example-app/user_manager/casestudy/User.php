<?PHP
/**
* UserManagerExample
*
* Copyright (c) 2004 Erdinc Yilmazel
*
* This source file is generated using the MyObjects Object Persistence Library
* Class Generater tool.
*
* MyObjects Copyright 2004 Erdinc Yilmazel <erdinc@yilmazel.com>
* http://www.myobjects.org
* 
* @version 1.3
* @author Erdinc Yilmazel
* @package UserManagerExample
*/


/**
* The class that will hold user information
*
* @author Erdinc Yilmazel
* @version 1.3
* @package UserManagerExample
*/
class User implements Mapable {
    
    /* Local Variables */
    
    /**
    * @var int User Id (Primary Key) (Auto Increment) (Unique) (Required)
    */
    public $userid;

    /**
    * @var varchar Name of the User
    */
    public $name;

    /**
    * @var varchar User Name (Unique) (Required)
    */
    public $username;

    /**
    * @var varchar Password (Required)
    */
    public $password;

    /**
    * @var varchar Email of the user (Unique) (Required)
    */
    public $email;

    /**
    * @var string Active flag (Required)
    */
    public $active;

    /**
    * @var string Admin Flag (Required)
    */
    public $admin;

    /**
    * @var datetime Creation Date of the User Account (Required)
    */
    public $creationdate;

    /* Foreign Variables */
    
    /**
    * @var array $instances This array holds the instances of User objects
    */
    public static $instances = array();
    
    /**
    * @var boolean $verbose Flag for the behaviour of the setter methods. If this
    * value is set true, setter methods will throw an exception if they are called with
    * invalid values. Otherwise the setter methods will simply return false if their
    * parameters are invalid.
    */
    private $verbose = true;
    
    /**
    * Constructs a new User object
    *
    * @return void
    */
    function __construct() {
        // Initiate default values
    }
    
    /**
    * Sets the verbose mode of the class.
    * 
    * If verbose mode is true the setter methods will throw an exception when they
    * are passed invalid values. If verbose mode is false, the  setter methods will simpy
    * return false.
    *
    * @return void
    */
    function setVerboseMode($mode) {
        $this->verbose = $mode;
    }
    
    /**
    * Makes a clone of this object.
    *
    * The new clone instance is added to the static array that holds the
    * User instances
    *
    * @return void
    */
    function __clone() {
        User::addInstance($this);
    }

    /**
    * Returns the database table name for this type of object
    *
    * Static method that returns the mysql database table name for this type of objects
    * map to. This value is used in database queries.
    *
    * @return string Returns the table name for this type of object
    */
    public static function getTableName() {
        return 'users';
    }
    
    /**
    * Returns the primary key name of the database table.
    *
    * The primary key name for the table that this classes objects map to
    * is returned. This value is used in database queries.
    *
    * @return string Primary key name
    */
    public static function primaryKeys() {
        return array('userid');
    }
    
    /**
    * Makes a selection query based on the given unique fields and selects
    * the specified fields
    *
    * This method simply calls the DbModel::select method with the table name for this
    * class as the first parameter.
    *
    * @see DbModel::select
    * @param array $uniqueValues Associated array of unique values that
    * will be used in the where clause.
    * If uniqueValues parameter is not specified no Where clause will be included
    * in the database query.
    * In the value part of your arrays you can add "LIKE::", "NOT LIKE::" or "NOT::"
    * prefixes for pattern matching and inverting. See DbModel::select for example usages.
    * @param string $fields Comma seperated values of fields that will be selected.
    * Default value is *
    * @param int $limit This parameter should be passed a value larger than 0 to
    * limit the number of rows returned
    * @param int $offset The offset that will be used in the limit block of the query
    * @return mysqli_result Returns the mysqli result set object of the query
    */
    public static function select($uniqueValues = null, $fields = '*', $limit = 0,
                                  $offset = 0) {
        return DbModel::select('users', $uniqueValues, $fields, $limit, $offset);
    }
    
    /**
    * Prepares a Mysql prepared statement based on the given unique fields
    *
    * This method simply calls the DbModel::prepareSelect method with the table name for this
    * class as the first parameter.
    *
    * @see DbModel::prepareSelect
    * @param array $uniqueValues Associated array of unique values that
    * will be used in the where clause.
    * If uniqueValues parameter is not specified no Where clause will be included
    * in the database query.
    * @param string $fields Comma seperated values of fields that will be selected.
    * Default value is *
    * @param int $limit This parameter should be passed a value larger than 0 to
    * limit the number of rows returned
    * @param int $offset The offset that will be used in the limit block of the query
    * @return mysqli_stmt Returns the mysqli prepared statement object
    */
    public static function prepareSelect($uniqueValues = null, $fields = '*', $limit = 0,
                                  $offset = 0) {
        return DbModel::prepareSelect('users', $uniqueValues, $fields, $limit, $offset);
    }
    
    /**
    * Prepares an insertion query for the object
    *
    * @return mysqli_stmt Prepared mysqli statement
    */
    public static function prepareInsert() {
        $sql  = "INSERT INTO users (`userid`
    , `name`
    , `username`
    , `password`
    , `email`
    , `active`
    , `admin`
    , `creationdate`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        return DbModel::prepare($sql);
    }
    
    /**
    * Factory method for creating a User object using the supplied unique values
    *
    * This factory method is used to get the User object with the specified
    * unique field values. If the user doesn't explicitly pass the second parameter
    * false, this method first checks if the requested object was previously retreived
    * from the database. If it was retreived before, it simply returns that object.
    * If the requested object was not retreived before, it makes a selection query
    * based on the unique values and creates a new object usign the mysqli_result object.
    * The new object is added to the static array that holds all the instances of
    * this class that was previously retreived from the database.
    *
    * Example Usage:
    * <code>$object = User::get(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))</code>
    *
    * This method can also be called using a single scalar type as the first variable.
    * This value should be the primary key value for the object.
    * Example Usage:
    * <code>$object = User::get(1)</code>
    *
    * @see normalizedGet
    * @param mixed $uniqueValues Associative array of unique values that will be used
    * in retreiving the object from the database. This value can also be passed as a
    * single scalar type as the primary key for the object.
    * @param boolean $checkExistance If this is passed as true the method should
    * first check the existance of the requested object. If it is passed false,
    * it skips the existance check and retreives the specified object from the database.
    * @throws ObjectNotFoundException In case the object with the supplied unique values
    * was not found
    * @return User The requested User object
    */
    public static function get($uniqueValues, $checkExistance = true,
                               $foreignKeys = false) {

        if(!is_array($uniqueValues)) {
            $uniqueValues = array('userid'=>$uniqueValues);
        }


        if($checkExistance) {
            if($o = User::getInstance($uniqueValues)) return $o;
        }
        
        $o = new User();
        if(DbModel::load($o, User::select($uniqueValues))) {
            if($foreignKeys) {
            	try {
                /* Register foreign keys */
            	} catch (ObjectNotFoundException $ex) {}
            }
            return $o;
        } else {
            throw new ObjectNotFoundException('User object not found with the
            specified unique values: ' . print_r($uniqueValues, true));
        }
    }
    
    /**
    * Returns the data type of specified property name
    *
    * @param string $propName The name of property
    * @return string The data type of supplied property, If the property
    * is not defined in the class returns false.
    */
    public static function propertyType($propName) {
        switch ($propName) {
            case 'userid':
                return 'int';
                break;
            case 'name':
                return 'varchar';
                break;
            case 'username':
                return 'varchar';
                break;
            case 'password':
                return 'varchar';
                break;
            case 'email':
                return 'varchar';
                break;
            case 'active':
                return 'enum';
                break;
            case 'admin':
                return 'enum';
                break;
            case 'creationdate':
                return 'datetime';
                break;
        }
        return false;
    }
    
    /**
    * Registers the foreign key objects
    *
    * The foreign objects linked to this object are created, so they can
    * be accessed.
    *
    * Example:
    * <code>
    * $usr = new User();
    * $usr->userid = 2;
    * $usr->permissionId = 15; // permissionId is a foreign key of Permissions table
    * $usr->registerForeignKeys();
    * $permission = $usr->getPermission(); // Get the foreign object
    * </code>
    */
    public function registerForeignKeys() {
        /* Register foreign keys */
    }
    
    /**
    * Returns the object instance with the specified unique values,
    * but uses a normalized query for selecting the foreign objects.
    *
    * <p>This factory method is used to get the User object with the specified
    * unique field values. If the user doesn't explicitly pass the second parameter
    * false, this method first checks if the requested object was previously retreived
    * from the database. If it was retreived before, it simply returns that object.
    * If the requested object was not retreived before, it makes a selection query
    * based on the unique values and creates a new object usign the mysqli_result object.
    * The new object is added to the static array that holds all the instances of this
    * class that was previously retreived from the database.</p>
    *
    * <p>The difference of this method from the User::get() method is that
    * this method joins the tables of the foreign objects for this class in the
    * selection query. Because data for all the objects are selected in a single
    * query this method should give better performance then the get method. However
    * unlike get method this method doesn't check if the foreign objects for the
    * requested object have been retreived before. New instances of foreign objects
    * are created when this method is called.</p>
    *
    * Example Usage:
    * <code>
    * $object = User::normalizedGet(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))
    * </code>
    *
    * <p>This method can also be called using a single scalar type as the
    * first variable. This value should be the primary key value for the object.</p>
    * Example Usage:
    * <code>$object = User::normalizedGet(1)</code>
    *
    * @param mixed $uniqueValues Associative array of unique values that
    * will be usedin retreiving the object from the database. This value can also be
    * passed as a single scalar type as the primary key for the object.
    * @param boolean $checkExistance If this is passed as true the method should
    * first check the existance of the requested object. If it is passed false,
    * it skips the existance check and retreives the specified object from the database.
    * @throws ObjectNotFoundException In case the object with the supplied unique values
    * was not found
    * @return User The requested User object
    */
    public static function normalizedGet($uniqueValues, $checkExistance = true) {
        
        if(!is_array($uniqueValues)) {
            $uniqueValues = array('userid'=>$uniqueValues);
        }

        
        if($checkExistance) {
            if($o = User::getInstance($uniqueValues)) return $o;
        }
        
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        $sql = 'SELECT 
                MyObjectsTable1.*
                FROM `users` as MyObjectsTable1
                WHERE
                ';
        
        $and = false;
        foreach ($uniqueValues as $key => $value) {
            if($and) {
                $sql .= ' AND ';
            } else {
                $and = true;
            }
            $sql .= ' MyObjectsTable1.`' . $key . '` = \''.
            $db->real_escape_string($value) .'\'';
        }
        
        $sql .= ' LIMIT 0, 1';
        
        if($result = $db->query($sql)) {
            /* Get field information for all columns */
            $fieldInfo = $result->fetch_fields();
            /* Fetch the returned data into an array */
            $row = $result->fetch_row();

            $o = new User();
            User::addInstance($o);

            for ($i=0; $i < count($fieldInfo); $i++) {
                $fieldName = $fieldInfo[$i]->name;
                switch ($fieldInfo[$i]->table) {
                    case 'MyObjectsTable1':
                        $o->$fieldName = $row[$i];
                        break;
                }
            }
            return $o;
        } else {
            throw new ObjectNotFoundException('User object not
            found with the specified unique values: ' . print_r($uniqueValues, true));
        }
    }


    /**
    * Returns the User object instance for this class which has the
    * specified unique values
    *
    * <p>Searchs the static array which holds the instances of that class for the
    * specified unique values. If the object instance is found it returns the
    * requested object. Otherwise it returns false.</p>
    *
    * Example Usage:
    * <code>
    * $object = User::getInstance(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))
    * </code>
    *
    * @param array $uniqueValues Associative array of unique values that will be used
    * in searching.
    * @return mixed The requested User object is returned if it is found.
    * False otherwise.
    */
    public static function getInstance($uniqueValues) {
        return DbModel::getInstance(User::$instances, $uniqueValues);
    }
    
    /**
    * Adds the passed User object to the classes data structure that holds the
    * instances
    *
    * Every class that implements Mapable interface should hold references
    * of the previously registered objects. This method adds the passed
    * Mapable object to the data structure that holds the instances of that
    * class. The method should first check the existance of the object.
    *
    * @param Mapable The object that will be added
    * @throws IllegalArgumentException Thrown if the parameter passed is not a valid
    * User object.
    * @return void
    */
    public static function addInstance(Mapable $o) {
        if(!($o instanceof User)) {
            throw new IllegalArgumentException('The object passed to
            User::addInstance should be a User object');
        }
        
        if(!in_array($o, User::$instances, true)) {
            array_push(User::$instances, $o);
        }
    }
    
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
    public static function removeInstance(Mapable $o) {
        if(!($o instanceof User)) {
            throw new IllegalArgumentException('The object passed to
            User::removeInstance should be a User object');
        }
        
        if(in_array($o, User::$instances)) {
            foreach(User::$instances as $key => $value) {
                if($value === $o) {
                    unset(User::$instances[$key]);
                }
            }
        }
    }
    
    /**
    * Returns value of the specified primary key
    *
    * @param string Primary key name of the object
    * @return mixed Primary key value of the object
    */
    public function getKeyValue($keyName = null) {
        return $this->userid;
    }

    /**
    * Returns true if the supplied key is auto incremented
    *
    * @param string $keyName Name of primary key field
    * @return boolean True if the supplied key is auto incremented
    */
    public function isAutoIncremented($keyName) {
        if($keyName == 'userid') return true;
        return false;
    }

    /**
    * Checks if the current state of the object is valid
    *
    * This method should check the validness of the object. A valid
    * object means all the required properties of that object are set to valid
    * information. Validness check is made before store operations
    * to the database. If the extensive flag is set true, all the object properties
    * will be checked using the generated setter methods for validness.
    *
    * @param boolean $exclude Should be passed true to skip the test of
    * auto_increment primary key field.
    * The primary key field is generally an integer value with an
    * auto_increment property, hence before an insert operation the
    * primary key value does not need to be set.
    * @param boolean $extensive If passed true an extensive validness check is made
    * on each object property that is not null. The validation is made using the
    * generated setter methods.
    * @return boolean Returns true if the object is valid
    */
    public function isValid($exclude = false, $extensive = false) {
    
        $required = array('username', 'password', 'email', 'active', 'admin', 'creationdate');
        foreach($required as $property) {
            if(!is_scalar($this->$property)) {
                return false;
            }
        }
        
        if(!$exclude && !is_scalar($this->userid)) {
            return false;
        }
        
        if($extensive) {
            try {
                if(is_scalar($this->userid)) $this->setUserid($this->userid);
                if(is_scalar($this->name)) $this->setName($this->name);
                if(is_scalar($this->username)) $this->setUsername($this->username);
                if(is_scalar($this->password)) $this->setPassword($this->password);
                if(is_scalar($this->email)) $this->setEmail($this->email);
                if(is_scalar($this->active)) $this->setActive($this->active);
                if(is_scalar($this->admin)) $this->setAdmin($this->admin);
                if(is_scalar($this->creationdate)) $this->setCreationdate($this->creationdate);
                return true;
            } catch (InvalidValueException $e) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
    * Returns the string representation of the User object
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
    * @param View $view The View object that will handle the view part of the object
    * @return string The view of the object
    */
    public function getView(View $view) {
        $view->setModel($this);
        return $view->__toString();
    }
    
    /**
    * Loads the values stored in an associative array into the User
    * object. 
    * 
    * This method can be used to automatically call the setter methods for each
    * value defined in the array.
    *
    * @param array $array Associative array of values that will be loaded.
    * @return boolean True on success, false on failure
    * @throws InvalidValueException if the setter methods return an error this
    * exception is thrown
    */    
    public function loadArray($array, $nonEmpty = true) {
        if(!is_array($array)) return false;
        $mode = $this->verbose;
        $this->verbose = false;
        $invalidProperties = array();
        foreach ($array as $key => $value) {
            if($nonEmpty && empty($value)) continue;
            switch ($key) {
            case 'userid':
                if(!$this->setUserid($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'name':
                if(!$this->setName($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'username':
                if(!$this->setUsername($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'password':
                if(!$this->setPassword($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'email':
                if(!$this->setEmail($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'active':
                if(!$this->setActive($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'admin':
                if(!$this->setAdmin($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'creationdate':
                if(!$this->setCreationdate($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            }
        }
        $this->verbose = $mode;
        if(count($invalidProperties) > 0) {
            throw new LoadArrayException('User', $invalidProperties);
        } else {
            return true;
        }
    }
    
    /**
    * Returns User Id
    *
    * @return int userid
    */
    public function getUserid() {
        return $this->userid;
    }

    /**
    * Returns Name of the User
    *
    * @return varchar name
    */
    public function getName() {
        return $this->name;
    }

    /**
    * Returns User Name
    *
    * @return varchar username
    */
    public function getUsername() {
        return $this->username;
    }

    /**
    * Returns Password
    *
    * @return varchar password
    */
    public function getPassword() {
        return $this->password;
    }

    /**
    * Returns Email of the user
    *
    * @return varchar email
    */
    public function getEmail() {
        return $this->email;
    }

    /**
    * Checks for Active flag
    *
    * @return enum active
    */
    public function isActive() {
        return $this->active == 'Y';
    }

    /**
    * Checks for Admin Flag
    *
    * @return enum admin
    */
    public function isAdmin() {
        return $this->admin == 'Y';
    }

    /**
    * Returns Creation Date of the User Account
    *
    * @return datetime creationdate
    */
    public function getCreationdate() {
        return $this->creationdate;
    }

    
    
    /**
    * Sets User Id
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for User Id
    * @throws UniqueKeyExistsException Thrown if the supplied value exists
    * as a User Id property of another User object
    * @param int $userid User Id
    * @return boolean Returns true if User Id is set successfully
    */
    public function setUserid($userid, $uniqueTest = true) {

        // Check if the value is unsigned
        if($userid < 0) {
            if($this->verbose) throw new InvalidValueException('userid');
            return false;
        }

        if($uniqueTest) {

            // Check if the unique key userid exists
            $result = User::select(array('userid'=>$userid), 'userid');
            if($row = $result->fetch_row()) {
                if($this->userid != $row[0]) throw new UniqueKeyExistsException('userid');
            }
        }

        // Assign the value
        $this->userid = $userid;
        return true;
    }

    /**
    * Sets Name of the User
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Name of the User
    * @param string $name Name of the User
    * @return boolean Returns true if Name of the User is set successfully
    */
    public function setName($name) {
        // Check for validness
        if(!StringValidator::isCleanText($name, 4, 80)) {
            if($this->verbose) throw new InvalidValueException('name');
            return false;
        }

        // Assign the value
        $this->name = $name;
        return true;
    }

    /**
    * Sets User Name
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for User Name
    * @throws UniqueKeyExistsException Thrown if the supplied value exists
    * as a User Name property of another User object
    * @param string $username User Name
    * @return boolean Returns true if User Name is set successfully
    */
    public function setUsername($username, $uniqueTest = true) {
        // Check for validness
        if(!StringValidator::isWord($username, 3, 32)) {
            if($this->verbose) throw new InvalidValueException('username');
            return false;
        }

        if($uniqueTest) {

            // Check if the unique key username exists
            $result = User::select(array('username'=>$username), 'username');
            if($row = $result->fetch_row()) {
                if($this->username != $row[0]) throw new UniqueKeyExistsException('username');
            }
        }

        // Assign the value
        $this->username = $username;
        return true;
    }

    /**
    * Sets Password
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Password
    * @param string $password Password
    * @return boolean Returns true if Password is set successfully
    */
    public function setPassword($password) {
        // Check for validness
        if(!StringValidator::isLengthValid($password, 4, 32)) {
            if($this->verbose) throw new InvalidValueException('password');
            return false;
        }

        // Assign the value
        $this->password = md5($password);
        return true;
    }

    /**
    * Sets Email of the user
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Email of the user
    * @throws UniqueKeyExistsException Thrown if the supplied value exists
    * as a Email of the user property of another User object
    * @param string $email Email of the user
    * @return boolean Returns true if Email of the user is set successfully
    */
    public function setEmail($email, $uniqueTest = true) {
        // Check for validness
        if(!StringValidator::isEmail($email, 0, 255)) {
            if($this->verbose) throw new InvalidValueException('email');
            return false;
        }

        if($uniqueTest) {

            // Check if the unique key email exists
            $result = User::select(array('email'=>$email), 'email');
            if($row = $result->fetch_row()) {
                if($this->email != $row[0]) throw new UniqueKeyExistsException('email');
            }
        }

        // Assign the value
        $this->email = $email;
        return true;
    }

    /**
    * Sets Active flag
    *
    * @param boolean $active Active flag
    * @return boolean Returns true if Active flag is set successfully
    */
    public function setActive($active) {
        if($active) {
            $this->active = 'Y';
        } else {
            $this->active = 'N';
        }
    }

    /**
    * Sets Admin Flag
    *
    * @param boolean $admin Admin Flag
    * @return boolean Returns true if Admin Flag is set successfully
    */
    public function setAdmin($admin) {
        if($admin) {
            $this->admin = 'Y';
        } else {
            $this->admin = 'N';
        }
    }

    /**
    * Sets Creation Date of the User Account
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not in 'Y-m-j H:i:s' format that is valid for Creation Date of the User Account
    * @param string $creationdate Creation Date of the User Account
    * @return boolean Returns true if Creation Date of the User Account is set successfully
    */
    public function setCreationdate($creationdate) {
        // Check for validness
        if(!StringValidator::isDateTime($creationdate)) {
            if($this->verbose) throw new InvalidValueException('creationdate');
            return false;
        }

        // Assign the value
        $this->creationdate = $creationdate;
        return true;
    }

    /**
    * Sets Creation Date of the User Account using an integer unix timestamp
    *
    * @param int $creationdate Creation Date of the User Account in unix timestamp
    * @return boolean Returns true if Creation Date of the User Account is set successfully
    */
    public function setCreationdatetime($creationdate) {
        if($creationdate == "NOW") {
            $creationdate = time();
        }
        // Check for validness
        if(!is_int($creationdate)) {
            if($this->verbose) throw new InvalidValueException('creationdate');
            return false;
        }

        // Assign the value
        $this->creationdate = date('Y-m-j H:i:s', $creationdate);
        return true;
    }

}
?>