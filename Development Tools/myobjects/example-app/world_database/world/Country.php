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
* Countries
*
* @author Erdinc Yilmazel
* @version 1.3
* @package WorldExample
*/
class Country implements Mapable {
    
    /* Local Variables */
    
    /**
    * @var char Country Code (Primary Key)
    */
    public $Code;

    /**
    * @var char Country Name (Required)
    */
    public $Name;

    /**
    * @var string Continent of the country (Required)
    */
    public $Continent;

    /**
    * @var char Region of the country (Required)
    */
    public $Region;

    /**
    * @var float Surface area of the country (Required)
    */
    public $SurfaceArea;

    /**
    * @var smallint Independence Year
    */
    public $IndepYear;

    /**
    * @var int Population of the country (Required)
    */
    public $Population;

    /**
    * @var float Life expectancy of the country
    */
    public $LifeExpectancy;

    /**
    * @var float GNP
    */
    public $GNP;

    /**
    * @var float GNPOld
    */
    public $GNPOld;

    /**
    * @var char Local name of the country (Required)
    */
    public $LocalName;

    /**
    * @var char Government form of the country (Required)
    */
    public $GovernmentForm;

    /**
    * @var char Head of state of the country
    */
    public $HeadOfState;

    /**
    * @var int Capital
    */
    public $Capital;

    /**
    * @var char Code2 (Required)
    */
    public $Code2;

    /* Foreign Variables */
    
    /**
    * @var array $instances This array holds the instances of Country objects
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
    * Constructs a new Country object
    *
    * @return void
    */
    function __construct() {
        // Initiate default values
        $this->Continent = 'Asia';
        $this->SurfaceArea = '0.00';
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
    * Country instances
    *
    * @return void
    */
    function __clone() {
        Country::addInstance($this);
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
        return 'Country';
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
        return array('Code');
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
        return DbModel::select('Country', $uniqueValues, $fields, $limit, $offset);
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
        return DbModel::prepareSelect('Country', $uniqueValues, $fields, $limit, $offset);
    }
    
    /**
    * Prepares an insertion query for the object
    *
    * @return mysqli_stmt Prepared mysqli statement
    */
    public static function prepareInsert() {
        $sql  = "INSERT INTO Country (`Code`
    , `Name`
    , `Continent`
    , `Region`
    , `SurfaceArea`
    , `IndepYear`
    , `Population`
    , `LifeExpectancy`
    , `GNP`
    , `GNPOld`
    , `LocalName`
    , `GovernmentForm`
    , `HeadOfState`
    , `Capital`
    , `Code2`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return DbModel::prepare($sql);
    }
    
    /**
    * Factory method for creating a Country object using the supplied unique values
    *
    * This factory method is used to get the Country object with the specified
    * unique field values. If the user doesn't explicitly pass the second parameter
    * false, this method first checks if the requested object was previously retreived
    * from the database. If it was retreived before, it simply returns that object.
    * If the requested object was not retreived before, it makes a selection query
    * based on the unique values and creates a new object usign the mysqli_result object.
    * The new object is added to the static array that holds all the instances of
    * this class that was previously retreived from the database.
    *
    * Example Usage:
    * <code>$object = Country::get(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))</code>
    *
    * This method can also be called using a single scalar type as the first variable.
    * This value should be the primary key value for the object.
    * Example Usage:
    * <code>$object = Country::get(1)</code>
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
    * @return Country The requested Country object
    */
    public static function get($uniqueValues, $checkExistance = true,
                               $foreignKeys = false) {

        if(!is_array($uniqueValues)) {
            $uniqueValues = array('Code'=>$uniqueValues);
        }


        if($checkExistance) {
            if($o = Country::getInstance($uniqueValues)) return $o;
        }
        
        $o = new Country();
        if(DbModel::load($o, Country::select($uniqueValues))) {
            if($foreignKeys) {
            	try {
                /* Register foreign keys */
            	} catch (ObjectNotFoundException $ex) {}
            }
            return $o;
        } else {
            throw new ObjectNotFoundException('Country object not found with the
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
            case 'Code':
                return 'char';
                break;
            case 'Name':
                return 'char';
                break;
            case 'Continent':
                return 'enum';
                break;
            case 'Region':
                return 'char';
                break;
            case 'SurfaceArea':
                return 'float';
                break;
            case 'IndepYear':
                return 'smallint';
                break;
            case 'Population':
                return 'int';
                break;
            case 'LifeExpectancy':
                return 'float';
                break;
            case 'GNP':
                return 'float';
                break;
            case 'GNPOld':
                return 'float';
                break;
            case 'LocalName':
                return 'char';
                break;
            case 'GovernmentForm':
                return 'char';
                break;
            case 'HeadOfState':
                return 'char';
                break;
            case 'Capital':
                return 'int';
                break;
            case 'Code2':
                return 'char';
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
    * <p>This factory method is used to get the Country object with the specified
    * unique field values. If the user doesn't explicitly pass the second parameter
    * false, this method first checks if the requested object was previously retreived
    * from the database. If it was retreived before, it simply returns that object.
    * If the requested object was not retreived before, it makes a selection query
    * based on the unique values and creates a new object usign the mysqli_result object.
    * The new object is added to the static array that holds all the instances of this
    * class that was previously retreived from the database.</p>
    *
    * <p>The difference of this method from the Country::get() method is that
    * this method joins the tables of the foreign objects for this class in the
    * selection query. Because data for all the objects are selected in a single
    * query this method should give better performance then the get method. However
    * unlike get method this method doesn't check if the foreign objects for the
    * requested object have been retreived before. New instances of foreign objects
    * are created when this method is called.</p>
    *
    * Example Usage:
    * <code>
    * $object = Country::normalizedGet(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))
    * </code>
    *
    * <p>This method can also be called using a single scalar type as the
    * first variable. This value should be the primary key value for the object.</p>
    * Example Usage:
    * <code>$object = Country::normalizedGet(1)</code>
    *
    * @param mixed $uniqueValues Associative array of unique values that
    * will be usedin retreiving the object from the database. This value can also be
    * passed as a single scalar type as the primary key for the object.
    * @param boolean $checkExistance If this is passed as true the method should
    * first check the existance of the requested object. If it is passed false,
    * it skips the existance check and retreives the specified object from the database.
    * @throws ObjectNotFoundException In case the object with the supplied unique values
    * was not found
    * @return Country The requested Country object
    */
    public static function normalizedGet($uniqueValues, $checkExistance = true) {
        
        if(!is_array($uniqueValues)) {
            $uniqueValues = array('Code'=>$uniqueValues);
        }

        
        if($checkExistance) {
            if($o = Country::getInstance($uniqueValues)) return $o;
        }
        
        $db = MyObjectsBase::getInstance()->getDbConnection();
        
        $sql = 'SELECT 
                MyObjectsTable1.*
                FROM `Country` as MyObjectsTable1
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

            $o = new Country();
            Country::addInstance($o);

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
            throw new ObjectNotFoundException('Country object not
            found with the specified unique values: ' . print_r($uniqueValues, true));
        }
    }


    /**
    * Returns the Country object instance for this class which has the
    * specified unique values
    *
    * <p>Searchs the static array which holds the instances of that class for the
    * specified unique values. If the object instance is found it returns the
    * requested object. Otherwise it returns false.</p>
    *
    * Example Usage:
    * <code>
    * $object = Country::getInstance(array('UNIQUE_FIELD'=>'UNIQUE_VALUE'))
    * </code>
    *
    * @param array $uniqueValues Associative array of unique values that will be used
    * in searching.
    * @return mixed The requested Country object is returned if it is found.
    * False otherwise.
    */
    public static function getInstance($uniqueValues) {
        return DbModel::getInstance(Country::$instances, $uniqueValues);
    }
    
    /**
    * Adds the passed Country object to the classes data structure that holds the
    * instances
    *
    * Every class that implements Mapable interface should hold references
    * of the previously registered objects. This method adds the passed
    * Mapable object to the data structure that holds the instances of that
    * class. The method should first check the existance of the object.
    *
    * @param Mapable The object that will be added
    * @throws IllegalArgumentException Thrown if the parameter passed is not a valid
    * Country object.
    * @return void
    */
    public static function addInstance(Mapable $o) {
        if(!($o instanceof Country)) {
            throw new IllegalArgumentException('The object passed to
            Country::addInstance should be a Country object');
        }
        
        if(!in_array($o, Country::$instances, true)) {
            array_push(Country::$instances, $o);
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
        if(!($o instanceof Country)) {
            throw new IllegalArgumentException('The object passed to
            Country::removeInstance should be a Country object');
        }
        
        if(in_array($o, Country::$instances)) {
            foreach(Country::$instances as $key => $value) {
                if($value === $o) {
                    unset(Country::$instances[$key]);
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
        return $this->Code;
    }

    /**
    * Returns true if the supplied key is auto incremented
    *
    * @param string $keyName Name of primary key field
    * @return boolean True if the supplied key is auto incremented
    */
    public function isAutoIncremented($keyName) {
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
    
        $required = array('Name', 'Continent', 'Region', 'SurfaceArea', 'Population', 'LocalName', 'GovernmentForm', 'Code2');
        foreach($required as $property) {
            if(!is_scalar($this->$property)) {
                return false;
            }
        }
        
        
        if($extensive) {
            try {
                if(is_scalar($this->Code)) $this->setCode($this->Code);
                if(is_scalar($this->Name)) $this->setName($this->Name);
                if(is_scalar($this->Continent)) $this->setContinent($this->Continent);
                if(is_scalar($this->Region)) $this->setRegion($this->Region);
                if(is_scalar($this->SurfaceArea)) $this->setSurfaceArea($this->SurfaceArea);
                if(is_scalar($this->IndepYear)) $this->setIndepYear($this->IndepYear);
                if(is_scalar($this->Population)) $this->setPopulation($this->Population);
                if(is_scalar($this->LifeExpectancy)) $this->setLifeExpectancy($this->LifeExpectancy);
                if(is_scalar($this->GNP)) $this->setGNP($this->GNP);
                if(is_scalar($this->GNPOld)) $this->setGNPOld($this->GNPOld);
                if(is_scalar($this->LocalName)) $this->setLocalName($this->LocalName);
                if(is_scalar($this->GovernmentForm)) $this->setGovernmentForm($this->GovernmentForm);
                if(is_scalar($this->HeadOfState)) $this->setHeadOfState($this->HeadOfState);
                if(is_scalar($this->Capital)) $this->setCapital($this->Capital);
                if(is_scalar($this->Code2)) $this->setCode2($this->Code2);
                return true;
            } catch (InvalidValueException $e) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
    * Returns the string representation of the Country object
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
    * Loads the values stored in an associative array into the Country
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
            case 'Code':
                if(!$this->setCode($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'Name':
                if(!$this->setName($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'Continent':
                if(!$this->setContinent($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'Region':
                if(!$this->setRegion($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'SurfaceArea':
                if(!$this->setSurfaceArea($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'IndepYear':
                if(!$this->setIndepYear($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'Population':
                if(!$this->setPopulation($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'LifeExpectancy':
                if(!$this->setLifeExpectancy($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'GNP':
                if(!$this->setGNP($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'GNPOld':
                if(!$this->setGNPOld($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'LocalName':
                if(!$this->setLocalName($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'GovernmentForm':
                if(!$this->setGovernmentForm($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'HeadOfState':
                if(!$this->setHeadOfState($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'Capital':
                if(!$this->setCapital($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            case 'Code2':
                if(!$this->setCode2($value)) {
                    $invalidProperties[] = $key;
                }
                break;
            }
        }
        $this->verbose = $mode;
        if(count($invalidProperties) > 0) {
            throw new LoadArrayException('Country', $invalidProperties);
        } else {
            return true;
        }
    }
    
    /**
    * Returns Country Code
    *
    * @return char Code
    */
    public function getCode() {
        return $this->Code;
    }

    /**
    * Returns Country Name
    *
    * @return char Name
    */
    public function getName() {
        return $this->Name;
    }

    /**
    * Returns Continent of the country
    *
    * @return enum Continent
    */
    public function getContinent() {
        return $this->Continent;
    }

    /**
    * Returns Region of the country
    *
    * @return char Region
    */
    public function getRegion() {
        return $this->Region;
    }

    /**
    * Returns Surface area of the country
    *
    * @return float SurfaceArea
    */
    public function getSurfaceArea() {
        return $this->SurfaceArea;
    }

    /**
    * Returns Independence Year
    *
    * @return smallint IndepYear
    */
    public function getIndepYear() {
        return $this->IndepYear;
    }

    /**
    * Returns Population of the country
    *
    * @return int Population
    */
    public function getPopulation() {
        return $this->Population;
    }

    /**
    * Returns Life expectancy of the country
    *
    * @return float LifeExpectancy
    */
    public function getLifeExpectancy() {
        return $this->LifeExpectancy;
    }

    /**
    * Returns GNP
    *
    * @return float GNP
    */
    public function getGNP() {
        return $this->GNP;
    }

    /**
    * Returns GNPOld
    *
    * @return float GNPOld
    */
    public function getGNPOld() {
        return $this->GNPOld;
    }

    /**
    * Returns Local name of the country
    *
    * @return char LocalName
    */
    public function getLocalName() {
        return $this->LocalName;
    }

    /**
    * Returns Government form of the country
    *
    * @return char GovernmentForm
    */
    public function getGovernmentForm() {
        return $this->GovernmentForm;
    }

    /**
    * Returns Head of state of the country
    *
    * @return char HeadOfState
    */
    public function getHeadOfState() {
        return $this->HeadOfState;
    }

    /**
    * Returns Capital
    *
    * @return int Capital
    */
    public function getCapital() {
        return $this->Capital;
    }

    /**
    * Returns Code2
    *
    * @return char Code2
    */
    public function getCode2() {
        return $this->Code2;
    }

    
    
    /**
    * Sets Country Code
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Country Code
    * @param string $Code Country Code
    * @return boolean Returns true if Country Code is set successfully
    */
    public function setCode($Code) {
        // Check for validness
        if(!StringValidator::isAlpha($Code, 3, 3)) {
            if($this->verbose) throw new InvalidValueException('Code');
            return false;
        }

        // Assign the value
        $this->Code = $Code;
        return true;
    }

    /**
    * Sets Country Name
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Country Name
    * @param string $Name Country Name
    * @return boolean Returns true if Country Name is set successfully
    */
    public function setName($Name) {
        // Check for validness
        if(!StringValidator::isLengthValid($Name, 0, 52)) {
            if($this->verbose) throw new InvalidValueException('Name');
            return false;
        }

        // Assign the value
        $this->Name = $Name;
        return true;
    }

    /**
    * Sets Continent of the country
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Continent of the country
    * @param string $Continent Continent of the country
    * @return boolean Returns true if Continent of the country is set successfully
    */
    public function setContinent($Continent = 'Asia') {
        switch($Continent) {
            case 'Asia':
                $this->Continent = $Continent;
                return true;
            case 'Europe':
                $this->Continent = $Continent;
                return true;
            case 'North America':
                $this->Continent = $Continent;
                return true;
            case 'Africa':
                $this->Continent = $Continent;
                return true;
            case 'Oceania':
                $this->Continent = $Continent;
                return true;
            case 'Antarctica':
                $this->Continent = $Continent;
                return true;
            case 'South America':
                $this->Continent = $Continent;
                return true;
        }
        if($this->verbose) throw new InvalidValueException('Continent');
        return false;
    }

    /**
    * Sets Region of the country
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Region of the country
    * @param string $Region Region of the country
    * @return boolean Returns true if Region of the country is set successfully
    */
    public function setRegion($Region) {
        // Check for validness
        if(!StringValidator::isLengthValid($Region, 0, 26)) {
            if($this->verbose) throw new InvalidValueException('Region');
            return false;
        }

        // Assign the value
        $this->Region = $Region;
        return true;
    }

    /**
    * Sets Surface area of the country
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Surface area of the country
    * @param float $SurfaceArea Surface area of the country
    * @return boolean Returns true if Surface area of the country is set successfully
    */
    public function setSurfaceArea($SurfaceArea = '0.00') {

        // Check if the value is unsigned
        if($SurfaceArea < 0) {
            if($this->verbose) throw new InvalidValueException('SurfaceArea');
            return false;
        }

        // Assign the value
        $this->SurfaceArea = $SurfaceArea;
        return true;
    }

    /**
    * Sets Independence Year
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Independence Year
    * @param smallint $IndepYear Independence Year
    * @return boolean Returns true if Independence Year is set successfully
    */
    public function setIndepYear($IndepYear) {

        // Check if the value is unsigned
        if($IndepYear < 0) {
            if($this->verbose) throw new InvalidValueException('IndepYear');
            return false;
        }

        // Assign the value
        $this->IndepYear = $IndepYear;
        return true;
    }

    /**
    * Sets Population of the country
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Population of the country
    * @param int $Population Population of the country
    * @return boolean Returns true if Population of the country is set successfully
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

    /**
    * Sets Life expectancy of the country
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Life expectancy of the country
    * @param float $LifeExpectancy Life expectancy of the country
    * @return boolean Returns true if Life expectancy of the country is set successfully
    */
    public function setLifeExpectancy($LifeExpectancy) {

        // Check if the value is unsigned
        if($LifeExpectancy < 0) {
            if($this->verbose) throw new InvalidValueException('LifeExpectancy');
            return false;
        }

        // Assign the value
        $this->LifeExpectancy = $LifeExpectancy;
        return true;
    }

    /**
    * Sets GNP
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for GNP
    * @param float $GNP GNP
    * @return boolean Returns true if GNP is set successfully
    */
    public function setGNP($GNP) {

        // Check if the value is unsigned
        if($GNP < 0) {
            if($this->verbose) throw new InvalidValueException('GNP');
            return false;
        }

        // Assign the value
        $this->GNP = $GNP;
        return true;
    }

    /**
    * Sets GNPOld
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for GNPOld
    * @param float $GNPOld GNPOld
    * @return boolean Returns true if GNPOld is set successfully
    */
    public function setGNPOld($GNPOld) {

        // Check if the value is unsigned
        if($GNPOld < 0) {
            if($this->verbose) throw new InvalidValueException('GNPOld');
            return false;
        }

        // Assign the value
        $this->GNPOld = $GNPOld;
        return true;
    }

    /**
    * Sets Local name of the country
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Local name of the country
    * @param string $LocalName Local name of the country
    * @return boolean Returns true if Local name of the country is set successfully
    */
    public function setLocalName($LocalName) {
        // Check for validness
        if(!StringValidator::isLengthValid($LocalName, 0, 45)) {
            if($this->verbose) throw new InvalidValueException('LocalName');
            return false;
        }

        // Assign the value
        $this->LocalName = $LocalName;
        return true;
    }

    /**
    * Sets Government form of the country
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Government form of the country
    * @param string $GovernmentForm Government form of the country
    * @return boolean Returns true if Government form of the country is set successfully
    */
    public function setGovernmentForm($GovernmentForm) {
        // Check for validness
        if(!StringValidator::isCleanText($GovernmentForm, 0, 45)) {
            if($this->verbose) throw new InvalidValueException('GovernmentForm');
            return false;
        }

        // Assign the value
        $this->GovernmentForm = $GovernmentForm;
        return true;
    }

    /**
    * Sets Head of state of the country
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Head of state of the country
    * @param string $HeadOfState Head of state of the country
    * @return boolean Returns true if Head of state of the country is set successfully
    */
    public function setHeadOfState($HeadOfState) {
        // Check for validness
        if(!StringValidator::isCleanText($HeadOfState, 0, 60)) {
            if($this->verbose) throw new InvalidValueException('HeadOfState');
            return false;
        }

        // Assign the value
        $this->HeadOfState = $HeadOfState;
        return true;
    }

    /**
    * Sets Capital
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Capital
    * @param int $Capital Capital
    * @return boolean Returns true if Capital is set successfully
    */
    public function setCapital($Capital) {

        // Check if the value is unsigned
        if($Capital < 0) {
            if($this->verbose) throw new InvalidValueException('Capital');
            return false;
        }

        // Assign the value
        $this->Capital = $Capital;
        return true;
    }

    /**
    * Sets Code2
    *
    * @throws InvalidValueException Thrown if the supplied value
    * is not valid for Code2
    * @param string $Code2 Code2
    * @return boolean Returns true if Code2 is set successfully
    */
    public function setCode2($Code2) {
        // Check for validness
        if(!StringValidator::isAlpha($Code2, 2, 2)) {
            if($this->verbose) throw new InvalidValueException('Code2');
            return false;
        }

        // Assign the value
        $this->Code2 = $Code2;
        return true;
    }

}
?>