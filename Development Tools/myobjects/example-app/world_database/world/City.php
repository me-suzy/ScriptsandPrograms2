<?PHP
/**
* WorldExample
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
* @package WorldExample
*/


/**
* Cities
*
* @author Erdinc Yilmazel
* @version 1.3
* @package WorldExample
*/
class City implements Mapable {
    
    /* Local Variables */
    
    /**
    * @var int City Id (Primary Key) (Auto Increment)
    */
    public $ID;

    /**
    * @var char Name of the city (Required)
    */
    public $Name;

    /**
    * @var char Country Code (Unique) (Required)
    */
    public $CountryCode;

    /**
    * @var char District of the city (Required)
    */
    public $District;

    /**
    * @var int Population of the city (Required)
    */
    public $Population;

    /* Foreign Variables */
    
    /**
    * @var Country Foreign Country object
    */
    public $country;

    /**
    * @var array $instances This array holds the instances of City objects
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
    * Constructs a new City object
    *
    * @return void
    */
    function __construct() {
        // Initiate default values
        $this->Population = '0';
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
    * City instances
    *
    * @return void
    */
    function __clone() {
        City::addInstance($this);
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
        return 'City';
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
        return array('ID');
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
        return DbModel::select('City', $uniqueValues, $fields, $limit, $offset);
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
        return DbModel::prepareSelect('City', $uniqueValues, $fields, $limit, $offset);
    }
    
    /**
    * Prepares an insertion query for the object
    *
    * @return mysqli_stmt Prepared mysqli statement
    */
    public static function prepareInsert() {
        $sql  = "INSERT INTO City (`ID`
    , `Name`
    , `CountryCode`
    , `District`
    , `Population`
        ) VALUES (?, ?, ?, ?, ?)";
        return DbModel::prepare($sql);
    }
    
    /**
    * Factory method for creating a City object using the supplied unique values
    *
    * This factory method is used to get the City object with the specified
    * unique field values. If the user doesn't explicitly pass the second parameter
    * false, this method first checks if the requested object was previously retreived
    * from the database. If it was retreived before, it simply returns that object.
    * If the requested object was not retreived before, it makes a selection query
    * based on the unique values and creates a new object usign the mysqli_result object.
    * The new object is added to the static array that holds all the instances of
    * this class that was previously retreived from the database.
    *
    * Example Usage:
    * <code>$object = City::get(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))</code>
    *
    * This method can also be called using a single scalar type as the first variable.
    * This value should be the primary key value for the object.
    * Example Usage:
    * <code>$object = City::get(1)</code>
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
    * @return City The requested City object
    */
    public static function get($uniqueValues, $checkExistance = true,
                               $foreignKeys = false) {

        if(!is_array($uniqueValues)) {
            $uniqueValues = array('ID'=>$uniqueValues);
        }


        if($checkExistance) {
            if($o = City::getInstance($uniqueValues)) return $o;
        }
        
        $o = new City();
        if(DbModel::load($o, City::select($uniqueValues))) {
            if($foreignKeys) {
            	try {
                /* Register foreign keys */
        $o->country = Country::get(array('CountryCode' => $o->CountryCode));
            	} catch (ObjectNotFoundException $ex) {}
            }
            return $o;
        } else {
            throw new ObjectNotFoundException('City object not found with the
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
            case 'ID':
                return 'int';
                break;
            case 'Name':
                return 'char';
                break;
            case 'CountryCode':
                return 'char';
                break;
            case 'District':
                return 'char';
                break;
            case 'Population':
                return 'int';
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
        $this->country = Country::get(array('CountryCode' => $this->CountryCode));
    }
    
    /**
    * Returns the object instance with the specified unique values,
    * but uses a normalized query for selecting the foreign objects.
    *
    * <p>This factory method is used to get the City object with the specified
    * unique field values. If the user doesn't explicitly pass the second parameter
    * false, this method first checks if the requested object was previously retreived
    * from the database. If it was retreived before, it simply returns that object.
    * If the requested object was not retreived before, it makes a selection query
    * based on the unique values and creates a new object usign the mysqli_result object.
    * The new object is added to the static array that holds all the instances of this
    * class that was previously retreived from the database.</p>
    *
    * <p>The difference of this method from the City::get() method is that
    * this method joins the tables of the foreign objects for this class in the
    * selection query. Because data for all the objects are selected in a single
    * query this method should give better performance then the get method. However
    * unlike get method this method doesn't check if the foreign objects for the
    * requested object have been retreived before. New instances of foreign objects
    * are created when this method is called.</p>
    *
    * Example Usage:
    * <code>
    * $object = City::normalizedGet(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))
    * </code>
    *
    * <p>This method can also be called using a single scalar type as the
    * first variable. This value should be the primary key value for the object.</p>
    * Example Usage:
    * <code>$object = City::normalizedGet(1)</code>
    *
    * @param mixed $uniqueValues Associative array of unique values that
    * will be usedin retreiving the object from the database. This value can also be
    * passed as a single scalar type as the primary key for the object.
    * @param boolean $checkExistance If this is passed as true the method should
    * first check the existance of the requested object. If it is passed false,
    * it skips the existance check and retreives the specified object from the database.
    * @throws ObjectNotFoundException In case the object with the supplied unique values
    * was not found
    * @return City The requested City object
    */
    public static function normalizedGet($uniqueValues, $checkExistance = true) {
        
        if(!is_array($uniqueValues)) {
            $uniqueValues = array('ID'=>$uniqueValues);
        }

        
        if($checkExistance) {
            if($o = City::getInstance($uniqueValues)) return $o;
        }
        
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        $sql = 'SELECT 
                MyObjectsTable2.* , MyObjectsTable1.*
                FROM `Country` as MyObjectsTable2 , `City` as MyObjectsTable1
                WHERE
                MyObjectsTable1.`CountryCode` = MyObjectsTable2.`Code` AND
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

            $o = new City();
            City::addInstance($o);
            $country = new Country();
            Country::addInstance($country);
            $o->country = $country;

            for ($i=0; $i < count($fieldInfo); $i++) {
                $fieldName = $fieldInfo[$i]->name;
                switch ($fieldInfo[$i]->table) {
                    case 'MyObjectsTable2':
                        $country->$fieldName = $row[$i];
                        break;
                    case 'MyObjectsTable1':
                        $o->$fieldName = $row[$i];
                        break;
                }
            }
            return $o;
        } else {
            throw new ObjectNotFoundException('City object not
            found with the specified unique values: ' . print_r($uniqueValues, true));
        }
    }


    /**
    * Returns the City object instance for this class which has the
    * specified unique values
    *
    * <p>Searchs the static array which holds the instances of that class for the
    * specified unique values. If the object instance is found it returns the
    * requested object. Otherwise it returns false.</p>
    *
    * Example Usage:
    * <code>
    * $object = City::getInstance(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))
    * </code>
    *
    * @param array $uniqueValues Associative array of unique values that will be used
    * in searching.
    * @return mixed The requested City object is returned if it is found.
    * False otherwise.
    */
    public static function getInstance($uniqueValues) {
        return DbModel::getInstance(City::$instances, $uniqueValues);
    }
    
    /**
    * Adds the passed City object to the classes data structure that holds the
    * instances
    *
    * Every class that implements Mapable interface should hold references
    * of the previously registered objects. This method adds the passed
    * Mapable object to the data structure that holds the instances of that
    * class. The method should first check the existance of the object.
    *
    * @param Mapable The object that will be added
    * @throws IllegalArgumentException Thrown if the parameter passed is not a valid
    * City object.
    * @return void
    */
    public static function addInstance(Mapable $o) {
        if(!($o instanceof City)) {
            throw new IllegalArgumentException('The object passed to
            City::addInstance should be a City object');
        }
        
        if(!in_array($o, City::$instances, true)) {
            array_push(City::$instances, $o);
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
        if(!($o instanceof City)) {
            throw new IllegalArgumentException('The object passed to
            City::removeInstance should be a City object');
        }
        
        if(in_array($o, City::$instances)) {
            foreach(City::$instances as $key => $value) {
                if($value === $o) {
                    unset(City::$instances[$key]);
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
        return $this->ID;
    }

    /**
    * Returns true if the supplied key is auto incremented
    *
    * @param string $keyName Name of primary key field
    * @return boolean True if the supplied key is auto incremented
    */
    public function isAutoIncremented($keyName) {
        if($keyName == 'ID') return true;
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
    
        $required = array('Name', 'CountryCode', 'District', 'Population');
        foreach($required as $property) {
            if(!is_scalar($this->$property)) {
                return false;
            }
        }
        
        if(!$exclude && !is_scalar($this->ID)) {
            return false;
        }
        
        if($extensive) {
            try {
                if(is_scalar($this->ID)) $this->setID($this->ID);
                if(is_scalar($this->Name)) $this->setName($this->Name);
                if(is_scalar($this->CountryCode)) $this->setCountryCode($this->CountryCode);
                if(is_scalar($this->District)) $this->setDistrict($this->District);
                if(is_scalar($this->Population)) $this->setPopulation($this->Population);
                return true;
            } catch (InvalidValueException $e) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
    * Returns the string representation of the City object
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
    * Loads the values stored in an associative array into the City
    * object. 
    * 
    * This method can be used to automatically call the setter methods for each
    * value defined in the array.
    *
    * @param array $array Associative array of values that will be loaded.
    * @return boolean True on success, false on failure
    * @param boolean $nonempty If this parameter is passed as true the empty values
    * of the array will not be loaded into the City object
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
            case 'ID':
                if(!$this->setID($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'Name':
                if(!$this->setName($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'CountryCode':
                if(!$this->setCountryCode($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'District':
                if(!$this->setDistrict($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'Population':
                if(!$this->setPopulation($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            }
        }
        $this->verbose = $mode;
        if(count($invalidProperties) > 0) {
            throw new LoadArrayException('City', $invalidProperties);
        } else {
            return true;
        }
    }
    
    /**
    * Returns City Id
    *
    * @return int ID
    */
    public function getID() {
        return $this->ID;
    }

    /**
    * Returns Name of the city
    *
    * @return char Name
    */
    public function getName() {
        return $this->Name;
    }

    /**
    * Returns Country Code
    *
    * @return char CountryCode
    */
    public function getCountryCode() {
        return $this->CountryCode;
    }

    /**
    * Returns the foreign Country object
    *
    * @return Country Foreign object
    */
    public function getCountry() {
        if($this->country instanceof Country) {
            return $this->country;
        } else {
            $this->country = Country::get(array('Code'=>$this->CountryCode));
            return $this->country;
        }
    }

    /**
    * Returns District of the city
    *
    * @return char District
    */
    public function getDistrict() {
        return $this->District;
    }

    /**
    * Returns Population of the city
    *
    * @return int Population
    */
    public function getPopulation() {
        return $this->Population;
    }

    
    
    /**
    * Sets City Id
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for City Id
    * @param int $ID City Id
    * @return boolean Returns true if City Id is set successfully
    */
    public function setID($ID) {

        // Check if the value is unsigned
        if($ID < 0) {
            if($this->verbose) throw new InvalidValueException('ID');
            return false;
        }

        // Assign the value
        $this->ID = $ID;
        return true;
    }

    /**
    * Sets Name of the city
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Name of the city
    * @param string $Name Name of the city
    * @return boolean Returns true if Name of the city is set successfully
    */
    public function setName($Name) {
        // Check for validness
        if(!StringValidator::isAlpha($Name, 0, 35)) {
            if($this->verbose) throw new InvalidValueException('Name');
            return false;
        }

        // Assign the value
        $this->Name = $Name;
        return true;
    }

    /**
    * Sets Country Code
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Country Code
    * @throws UniqueKeyExistsException Thrown if the supplied value exists
    * as a Country Code property of another City object
    * @param string $CountryCode Country Code
    * @return boolean Returns true if Country Code is set successfully
    */
    public function setCountryCode($CountryCode, $uniqueTest = true) {
        // Check for validness
        if(!StringValidator::isAlpha($CountryCode, 3, 3)) {
            if($this->verbose) throw new InvalidValueException('CountryCode');
            return false;
        }

        if($uniqueTest) {

            // Check if the unique key CountryCode exists
            $result = City::select(array('CountryCode'=>$CountryCode), 'CountryCode');
            if($row = $result->fetch_row()) {
                if($this->CountryCode != $row[0]) throw new UniqueKeyExistsException('CountryCode');
            }
        }

        // Assign the value
        $this->CountryCode = $CountryCode;
        return true;
    }

    /**
    * Sets the foreign Country object
    *
    * @param Country $country Foreign Country object
    * @throws InvalidValueException Thrown if the primary key value
    * of the foreign object is not set
    * @return boolean Returns true if the foreign object is set successfully
    */
    public function setCountry(Country $country) {
        if($country->Code != null) {
            $this->country = $country;
            $this->CountryCode = $this->country->Code;
        } else {
            if($this->verbose) throw new InvalidValueException('country');
            return false;
        }
        return true;
    }

    /**
    * Sets District of the city
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for District of the city
    * @param string $District District of the city
    * @return boolean Returns true if District of the city is set successfully
    */
    public function setDistrict($District) {
        // Check for validness
        if(!StringValidator::isLengthValid($District, 0, 20)) {
            if($this->verbose) throw new InvalidValueException('District');
            return false;
        }

        // Assign the value
        $this->District = $District;
        return true;
    }

    /**
    * Sets Population of the city
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Population of the city
    * @param int $Population Population of the city
    * @return boolean Returns true if Population of the city is set successfully
    */
    public function setPopulation($Population = '0') {

        // Check if the value is unsigned
        if($Population < 0) {
            if($this->verbose) throw new InvalidValueException('Population');
            return false;
        }

        // Assign the value
        $this->Population = $Population;
        return true;
    }

}
?>