<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: DDL.php,v 1.10 2004/12/01 14:46:42 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/

/**
* Defines methods for parsing, generating ddl files, getting database schema
* details.
*
* @package MyObjectsCompiler
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
*/
class DDL {
    
    /**
    * @var array $databases DDLDatabase objects registered with this class
    */
    protected $databases;
    
    /**
    * Constructs a new DDL instance
    *
    * @return void
    */
    public function __construct() {
        $this->databases = array();
    }
    
    /**
    * Returns the requested DDLDatabase object
    *
    * @param string $database Name of DDLDatabase object that is requested
    * @return DDLDatabase The requested DDLDatabase object
    */
    public function getDatabase($database) {
        foreach ($this->databases as $d) {
            if($d->getName() == $database) {
                return $d;
            }
        }
        return false;
    }
    
    /**
    * Adds the given DDLDatabase object to DDL
    *
    * @param DDLDatabase $database The DDLDatabase object that will be added
    * @return void
    */
    public function addDatabase(DDLDatabase $database) {
        array_push($this->databases, $database);
    }
    
    /**
    * Removes the DDLDatabase object from DDL
    *
    * @param string $database DDLDatabase object to be removed
    * @return void
    */
    public function removeDatabase($database) {
        foreach ($this->databases as $d) {
            if($d->getName() == $database) {
                $this->databases = array_diff($this->databases, array($d));
            }
        }
    }
    
    /**
    * Returns databases registered with DDL object
    *
    * @return array Array of DDLDb objects
    */
    public function getDatabases() {
        return $this->databases;
    }
    
    /**
    * Queries the database and generates corresponding objects describing
    * the database schema. Maps the generated objects to the supplied Xml Document.
    *
    * @param DOMDocument $doc Xml Document object that will be used to store
    * database schema.
    * @param mysqli $db mysqli object that will be used to query database
    * @param string $database Database name that's going to be examined
    * @return void
    */
    public function getDatabaseInfo(DOMDocument $doc, mysqli $db, $database,
                                    $verbose = false) {
        $ddlDb = new DDLDatabase($database);
        
        if($verbose) {
            echo "Checking database tables...\n";;
        }
        
        $result = $db->query("show tables");
        

        while($row = $result->fetch_row()) {
            if($verbose) {
                echo "Getting field information for table: " . $row[0] . "... ";
            }
            $ddlTable = new DDLTable($ddlDb, $row[0]);
            $ddlDb->addTable($ddlTable);
            $this->getTableInfo($db, $ddlTable);
            if($verbose) {
                echo "Done\n";
            }
        }
        
        $result->close();
        $ddlDb->createXmlElement($doc);
        $this->databases[] = $ddlDb;
    }
    
    /**
    * Parses the specified ddl file and creates DDL objects
    *
    * @return void
    */
    public function parse(DOMDocument $doc) {
        
        unset($this->databases);
        $this->databases = array();
        
        // Get the root element of the xml file
        $ddl = $doc->documentElement;
        $databases = $ddl->childNodes;
        
        foreach ($databases as $database) {
            if ($database instanceof DOMElement) {
                // Process databases defined in the ddl file
                $this->processDatabase($database);
            }
        }
    }
    
    /**
    * Queries the database table and stores the information
    * in the supploed DDLTable object.
    *
    * @param mysqli $db The mysqli object that will be used for database access
    * @param DDLTable $ddlTable The DDLTable object that will be filled with
    * information. The supplied objects 'name' property should be set before
    * this method is called
    * @return void
    */
    private function getTableInfo(mysqli $db, DDLTable $ddlTable) {
        $result = $db->query("describe `" . $ddlTable->getName() . '`');
        if(mysqli_error($db)) {
            error(mysqli_error($db));
        }
        while($field = $result->fetch_assoc()) {
            $ddlField = new DDLField($ddlTable, $field['Field']);
            $ddlTable->addField($ddlField);
            
            if($field['Null'] != 'YES') {
                $ddlField->setRequired(true);
            } else {
                $ddlField->setRequired(false);
            }
            
            if($field['Key'] == 'PRI') {
                $ddlField->setPrimaryKey(true);
                if($field['Extra'] == 'auto_increment') {
                    $ddlField->setAutoIncrement(true);
                }
            }
            elseif ($field['Key'] == 'UNI') {
                $ddlField->setUnique(true);
            }
            
            $this->getFieldInfo($ddlField, $field['Type'], $field['Default']);
        }
        $result->close();
    }

    /**
    * Examines the type string and stores the field information on the supplied
    * DDLField object
    *
    * Uses the string describing the field that is returned from the MySql server
    * and fills the supplied DDLField object with this information.
    *
    * @param DDLField $ddlField The DDLField object that will be filled with info
    * @param string $type The string returned from MySql server describing the field
    * @param string $defaultValue The string returned from MySql server describing
    * the default value for the field
    * @return void
    */
    private function getFieldInfo(DDLField $ddlField, $type, $defaultValue) {
        $pattern = "'([A-Za-z]+)\((\'([^\']*(\'\')*)+\'(\s?,\'([^\']*(\'\')*)+\')*)\)'i";
        if(preg_match($pattern, $type, $matches) > 0) {
            if($matches[1] == 'enum') {
                $ddlData = new DDLEnumData($ddlField);
                preg_match_all("'\'([^\']*(\'\')*)+\''", $matches[2], $values);
                
                $values = $values[0];
                if(count($values) == 2) {
                    if($values[0] == "'Y'" && $values[1] == "'N'") {
                        $ddlData->setBoolean(true);
                        $ddlData->addValue(new DDLEnumValue('Y', true));
                        $ddlData->addValue(new DDLEnumValue('N', false));
                    } else {
                        $ddlData->addValue(new DDLEnumValue(substr($values[0], 1, strlen($values[0]) - 2)));
                        $ddlData->addValue(new DDLEnumValue(substr($values[1], 1, strlen($values[1]) - 2)));
                    }
                } else {
                    foreach ($values as $value) {
                    	$value = substr($value, 1, strlen($value) - 2);
                        $ddlData->addValue(new DDLEnumValue($value));
                    }
                }
            } else {
                $ddlData = new DDLSetData($ddlField);
                preg_match_all("'\'([^\']*(\'\')*)+\''", $matches[2], $values);
                
                $values = $values[0];
                foreach ($values as $value) {
                	$value = substr($value, 1, strlen($value) - 2);
                    $ddlData->addValue($value);
                }
            }
            $ddlField->setData($ddlData);
        }
        else {
            $pattern = "'([A-Za-z]+)(\(([0-9]+,?[0-9]*)\))?\s?([A-Za-z]+)?'i";
            preg_match($pattern, $type, $matches);
            $dataType = $matches[1];
            if(isset($matches[3])) {
                $size = $matches[3];
            } else {
                $size = false;
            }
            
            if(isset($matches[4])) {
                $unsigned = true;
            } else {
                $unsigned = false;
            }
            
            switch ($dataType) {
                case 'tinyint':
                case 'smallint':
                case 'mediumint':
                case 'int':
                case 'bigint':
                case 'float':
                case 'double':
                case 'decimal':
                    $data = new DDLNumericData($ddlField);
                    if($size) {
                        $data->setSize($size);
                    }
                    if($dataType == 'tinyint') {
                        if($unsigned) {
                            $data->setUnsigned(true);
                            $data->setMinimumValue(0);
                            $data->setMaximumValue(255);
                        } else {
                            $data->setMinimumValue(-128);
                            $data->setMaximumValue(127);
                        }
                    }
                    elseif($dataType == 'smallint') {
                        if($unsigned) {
                            $data->setUnsigned(true);
                            $data->setMinimumValue(0);
                            $data->setMaximumValue(65535);
                        } else {
                            $data->setMinimumValue(-32768);
                            $data->setMaximumValue(32767);
                        }
                    }
                    elseif($dataType == 'mediumint') {
                        if($unsigned) {
                            $data->setUnsigned(true);
                            $data->setMinimumValue(0);
                            $data->setMaximumValue(16777215);
                        } else {
                            $data->setMinimumValue(-8388608);
                            $data->setMaximumValue(8388607);
                        }
                    }
                    elseif($dataType == 'int') {
                        if($unsigned) {
                            $data->setUnsigned(true);
                            $data->setMinimumValue(0);
                            $data->setMaximumValue(4294967295);
                        } else {
                            $data->setMinimumValue(-2147483648);
                            $data->setMaximumValue(2147483647);
                        }
                    }
                    elseif($dataType == 'bigint') {
                        if($unsigned) {
                            $data->setUnsigned(true);
                            $data->setMinimumValue(0);
                            $data->setMaximumValue(1844674073709551615);
                        } else {
                            $data->setMinimumValue(-9223372036854775808);
                            $data->setMaximumValue(9223372036854775807);
                        }
                    }

                    $data->setDataType($dataType);
                    if($defaultValue != null) {
                        $data->setDefaultValue($defaultValue);
                    }
                    $ddlField->setData($data);
                    break;
                
                case 'char':
                case 'varchar':
                case 'tinyblob':
                case 'blob':
                case 'mediumblob':
                case 'longblob':
                case 'tinytext':
                case 'text':
                case 'mediumtext':
                case 'longtext':
                case 'varbinary':
                
                    if($dataType == 'varbinary') $dataType = 'varchar';
                    $data = new DDLTextData($ddlField);
                    if($size) {
                        $data->setSize($size);
                        $data->setMaximumLength($size);
                    }
                    
                    if($ddlField->isRequired()) {
                        $data->setMinimumLength(1);
                    }
                    
                    $data->setDataType($dataType);
                    if($defaultValue != null) {
                        $data->setDefaultValue($defaultValue);
                    }
                    
                    $ddlField->setData($data);
                    break;
                    
                case 'date':
                case 'time':
                case 'datetime':
                case 'timestamp':
                case 'year':
                    $data = new DDLTimeData($ddlField);
                    $data->setType($dataType);
                    if($defaultValue == 'CURRENT_TIMESTAMP') {
                        $data->setAuto(true);
                    } else {
                        if($defaultValue != null) {
                            $data->setDefaultValue($defaultValue);
                        }
                    }
                    
                    $ddlField->setData($data);
                    break;
            }
        }
    }
    
    /**
    * Processes the databases defined in the ddl file
    *
    * Generates DDLDatabase objects for all the DOMElements named database
    *
    * @see DDLDatabase
    * @param DOMElement $database DOMElements with the tagName = 'database'
    * @return void
    */
    private function processDatabase(DOMElement $database) {
        // Create DDLDtabase object with the specified name
        $ddlDb = new DDLDatabase($database->getAttribute('name'));
        
        array_push($this->databases, $ddlDb);
        
        $tables = $database->childNodes;
        foreach ($tables as $table) {
            if($table instanceof DOMElement) {
                // Process tables defined in the database
                $this->processTable($table, $ddlDb);
            }
        }
    }
    
    /**
    * Processes the tabes defined in the ddl file
    *
    * Generates DDLTable objects for all the DOMElements named table
    *
    * @see DDLTable
    * @see DDLDatabase
    * @param DOMElement $table DOMElements with the tagName = 'table'
    * @param DDLDatabase $ddlDb The DDLDatabase object that will hold
    * the generated DDLTable object
    * @return void
    */
    private function processTable(DOMElement $table, DDLDatabase $ddlDb) {
        $ddlTable = new DDLTable($ddlDb, $table->getAttribute('name'));
        
        if($table->hasAttribute('className')) {
            $ddlTable->setClassName($table->getAttribute('className'));
        } else {
            $ddlTable->setClassName(ucfirst($ddlTable->getName()));
        }
        
        if($table->hasAttribute('superClassName')) {
            $ddlTable->setSuperClassName($table->getAttribute('superClassName'));
        }
        
        $ddlDb->addTable($ddlTable);
        foreach ($table->childNodes as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }
            switch ($child->tagName) {
                case 'field':
                    $this->processField($child, $ddlTable);
                    break;
                case 'description':
                    $description = trim($child->nodeValue);
                    $ddlTable->setDescription($description);
                    break;
            }
        }
    }
    
    /**
    * Processes the fields defined in the ddl file
    *
    * Generated DDLField object for all the DOLElements named field
    *
    * @see DDLField
    * @see DDLTable
    * @param DOMElement $field DOMElements with the tagName = 'field'
    * @param DDLTable $ddlTable The DDLTable object that will hold the
    * generated DDLField object.
    * @return void
    */
    private function processField(DOMElement $field, DDLTable $ddlTable) {
        $ddlField = new DDLField($ddlTable, $field->getAttribute('name'));
        
        $ddlTable->addField($ddlField);
        if($field->hasAttribute('primaryKey')) {
            $pk = $field->getAttribute('primaryKey');
            if($pk == 'true') {
                $ddlField->setPrimaryKey(true);
            } else {
                $ddlField->setPrimaryKey(false);
            }
            
            if($field->hasAttribute('autoIncrement')) {
                $auto = $field->getAttribute('autoIncrement');
                if($auto == 'true') {
                    $ddlField->setAutoIncrement(true);
                } else {
                    $ddlField->setAutoIncrement(false);
                }
            } else {
                $ddlField->setAutoIncrement(false);
            }
            
        } else {
            $ddlField->setPrimaryKey(false);
        }
        
        if($field->hasAttribute('unique')) {
            $u = $field->getAttribute('unique');
            if($u == 'true') {
                $ddlField->setUnique(true);
            } else {
                $ddlField->setUnique(false);
            }
        } else {
            $ddlField->setUnique(false);
        }
        
        if($field->hasAttribute('required')) {
            $u = $field->getAttribute('required');
            if($u == 'true') {
                $ddlField->setRequired(true);
            } else {
                $ddlField->setRequired(false);
            }
        } else {
            $ddlField->setRequired(false);
        }
        
        if($field->hasAttribute('foreignKeyOf')) {
            $t = $field->getAttribute('foreignKeyOf');
            if(strstr($t, ":")) {
                list($tableName, $fieldName) = explode(":", $t);
                $ddlField->setForeignTable($tableName);
                $ddlField->setForeignKey($fieldName);
            } else {
                $ddlField->setForeignTable($t);
            }
            

            
            if($field->hasAttribute('foreignObject')) {
                $o = $field->getAttribute('foreignObject');
                $ddlField->setForeignObject($o);
            }
        }
        
        foreach ($field->childNodes as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }
            switch ($child->tagName) {
                case 'data':
                    $this->processData($child, $ddlField);
                    break;
                case 'description':
                    $description = trim($child->nodeValue);
                    $ddlField->setDescription($description);
                    break;
                case 'setterFunction':
                    $setter = trim($child->nodeValue);
                    $ddlField->setSetterFunction($setter);
                    break;
                case 'getterFunction':
                    $getter = trim($child->nodeValue);
                    $ddlField->setGetterFunction($getter);
                    break;
            }
        }
        
        if(!$ddlField->getDescription()) {
            $ddlField->setDescription($ddlField->getName());
        }
    }
    
    /**
    * Processes the data elements in the ddl file
    *
    * Generates DDLData objects for all the data elements of the
    * ddl file.
    *
    * @see DDLData
    * @see DDLField
    * @param DOMElement $data DOMElements named data
    * @param DDLField $ddlField DDLField object that will hold the
    * generated DDLData object
    * @return void
    */
    private function processData(DOMElement $data, DDLField $ddlField) {
        $dataField = null;
        foreach ($data->childNodes as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }
            
            switch ($child->tagName) {
                case 'text':
                    $textField = new DDLTextData($ddlField);
                    $ddlField->setData($textField);
                    $this->processTextData($child, $textField);
                    $dataField = $textField;
                    break;
                case 'enum':
                    $enumField = new DDLEnumData($ddlField);
                    $ddlField->setData($enumField);
                    $this->processEnumData($child, $enumField);
                    $dataField = $enumField;
                    break;
                case 'set':
                    $setField = new DDLSetData($ddlField);
                    $ddlField->setData($setField);
                    $this->processSetData($child, $setField);
                    $dataField = $setField;
                    break;
                case 'numeric':
                    $numericField = new DDLNumericData($ddlField);
                    $ddlField->setData($numericField);
                    $this->processNumericData($child, $numericField);
                    $dataField = $numericField;
                    break;
                case 'timeData':
                    $timeField = new DDLTimeData($ddlField);
                    $ddlField->setData($timeField);
                    $this->processTimeData($child, $timeField);
                    $dataField = $timeField;
                    break;
                case 'defaultValue':
                    $default = trim($child->nodeValue);
                    if($dataField instanceof DDLData) {
                        $dataField->setDefaultValue($default);
                    }
                    break;
                case 'storeFunction':
                    $store = trim($child->nodeValue);
                    if($dataField instanceof DDLData ) {
                        $dataField->setStoreFunction($store);
                    }
                    break;
                case 'validationFunction':
                    $validation = trim($child->nodeValue);
                    if($dataField instanceof DDLData) {
                        $dataField->setValidationFunction($validation);
                    }
                    break;
            }
        }
    }
    
    /**
    * Processes the text data elements
    *
    * Gets the information about a Text field from the DOMElement object
    * and sets the corresponding properties of DDLTextData object.
    *
    * @param DOMElement $element The node named <text>
    * @param DDLTextData $textField The object that will store the information
    * @return void
    */
    private function processTextData(DOMElement $element, DDLTextData $textField) {
        if($element->hasAttribute('type')) {
            $textField->setType($element->getAttribute('type'));
        } else {
            $textField->setType('default');
        }
        
        if($element->hasAttribute('mysqltype')) {
            $textField->setDataType($element->getAttribute('mysqltype'));
            if($element->hasAttribute('size')) {
                $textField->setSize($element->getAttribute('size'));
            }
        }

        foreach ($element->childNodes as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }

            switch ($child->tagName) {
                case 'minimumLength':
                    $min = trim($child->nodeValue);
                    $textField->setMinimumLength($min);
                    break;
                case 'maximumLength':
                    $max = trim($child->nodeValue);
                    $textField->setMaximumLength($max);
                    break;
                case 'regexp':
                    $regexp = trim($child->nodeValue);
                    $textField->setRegexp($regexp);
                    break;
            }
        }
    }
    
    /**
    * Processes the enum data elements
    *
    * Gets the information about an Enum field from the DOMElement object
    * and sets the corresponding properties of DDLEnumData object.
    *
    * @param DOMElement $element The node named <enum>
    * @param DDLEnumData $enumField The object that will store the information
    * about the Enum field
    * @return void
    */
    private function processEnumData(DOMElement $element, DDLEnumData $enumField) {
        if($element->hasAttribute('boolean')) {
            $b = $element->getAttribute('boolean');
            if($b == 'true') {
                $enumField->setBoolean(true);
            } else {
                $enumField->setBoolean(false);
            }
        } else {
            $enumField->setBoolean(false);
        }
        
        foreach ($element->childNodes as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }
            
            $value = trim($child->nodeValue);
            if($child->hasAttribute('flag')) {
                $v = $child->getAttribute('flag');
                if($v == 'true') {
                    $enumValue = new DDLEnumValue($value, true);
                } else {
                    $enumValue = new DDLEnumValue($value, false);
                }
            } else {
                $enumValue = new DDLEnumValue($value);
            }
            
            $enumField->addValue($enumValue);
        }
    }

    /**
    * Processes the set data elements
    *
    * Gets the information about an Set field from the DOMElement object
    * and sets the corresponding properties of DDLSetData object.
    *
    * @param DOMElement $element The node named <set>
    * @param DDLSetData $setField The object that will store the information
    * about the Set field
    * @return void
    */
    private function processSetData(DOMElement $element, DDLSetData $setField) {
        foreach ($element->childNodes as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }
            
            $value = trim($child->nodeValue);
            $setField->addValue($value);
        }
    }

    /**
    * Processes the numeric data elements
    *
    * Gets the information about an Numeric field from the DOMElement object
    * and sets the corresponding properties of DDLNumericData object.
    *
    * @param DOMElement $element The node named <numeric>
    * @param DDLNumericData $numericField The object that will
    * store the information about the Numeric field
    * @return void
    */
    private function processNumericData(DOMElement $element,
                                        DDLNumericData $numericField) {
        if($element->hasAttribute('unsigned')) {
            $u = $element->getAttribute('unsigned');
            if($u == 'true') {
                $numericField->setUnsigned(true);
            } else {
                $numericField->setUnsigned(false);
            }
        } else {
            $numericField->setUnsigned(false);
        }
        
        if($element->hasAttribute('mysqltype')) {
            $numericField->setDataType($element->getAttribute('mysqltype'));
            if($element->hasAttribute('size')) {
                $numericField->setSize($element->getAttribute('size'));
            }
        }
        
        foreach ($element->childNodes as $child) {
            if (!($child instanceof DOMElement)) {
                continue;
            }
            
            $value = trim($child->nodeValue);
            switch ($child->tagName) {
                case 'minimumValue':
                    $numericField->setMinimumValue($value);
                    break;
                case 'maximumValue':
                    $numericField->setMaximumValue($value);
                    break;
            }
        }
    }

    /**
    * Processes the time data elements
    *
    * Gets the information about an Time field from the DOMElement object
    * and sets the corresponding properties of DDLTimeData object.
    *
    * @param DOMElement $element The node named <time>
    * @param DDLTimeData $timeField The object that will store the information
    * about the Time field
    * @return void
    */
    private function processTimeData(DOMElement $element, DDLTimeData $timeField) {
        if($element->hasAttribute('auto')) {
            $u = $element->getAttribute('auto');
            if($u == 'true') {
                $timeField->setAuto(true);
            } else {
                $timeField->setAuto(false);
            }
        } else {
            $timeField->setAuto(false);
        }
        
        $type = $element->getAttribute('type');
        $timeField->setType($type);
    }
}

/**
* Represents the databases defined in the ddl file
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DDLDatabase {
    /**
    * @var array $tables Array of DDLTables
    */
    protected $tables;
    
    /**
    * @var string $name Name of Database
    */
    protected $name;
    
    /**
    * Constructs a new DDLDatabase object
    *
    * @param string $name Name of the Database
    * @return void
    */
    public function __construct($name) {
        $this->name = $name;
        $this->tables = array();
    }
    
    /**
    * Generates sql representation of the database
    *
    * Generates sql statements that can be used to create the database
    * on a MySql server.
    *
    * @return string Sql representation of the database
    */
    public function toSql() {
        $sql  = 'DROP DATABASE IF EXISTS ' . $this->name . ';' . "\n";
        $sql .= 'CREATE DATABASE ' . $this->name . ';'. "\n";
        $sql .= 'USE ' . $this->name . ';'. "\n\n";
        foreach ($this->tables as $table) {
            $sql .= $table->toSql();
        }
        return $sql;
    }
    
    /**
    * Returns the requested table of this database
    *
    * @param string $table Table name of the requested object
    * @return DDLTable The requested DDLTable object
    */
    public function getTable($table) {
        foreach ($this->tables as $t) {
            if($t->getName() == $table) {
                return $t;
            }
        }
        return false;
    }
    
    /**
    * Removes the table from the database
    *
    * @param string $table Name of table that will be removed
    * @return void
    */
    public function removeTable($table) {
        foreach ($this->tables as $t) {
            if($t->getName() == $table) {
                $this->tables = array_diff($this->tables, array($t));
            }
        }
    }
    
    /**
    * Adds the given DDLTable to the supplied index
    *
    * Adds the table in the supplied index shifting other tables
    * if necessary
    *
    * @param int $n The index that the DDLTable object will be inserted
    * @param DDLTable Table object that will be inserted
    * @return void
    */
    public function addTableAtIndex($n, DDLTable $table) {
        array_push($this->tables, $table);
        if($n == count($this->tables) - 1) {
            return;
        }
        for($i = count($this->tables) - 1; $i > $n; $i--) {
            $this->tables[$i] = $this->tables[$i - 1];
        }
        $this->tables[$n] = $table;
    }
    
    /**
    * Adds the given table to the array of databases
    *
    * @param DDLTable $table The table that will be added
    * @return void
    */
    public function addTable(DDLTable $table) {
        array_push($this->tables, $table);
    }
    
    /**
    * Returns the array of registered DDLTables
    *
    * @return array DDLTables registered with the DDLDatabase
    */
    public function getTables() {
        return $this->tables;
    }
    
    /**
    * Returns the name of the DDLDatabase
    *
    * @return string Name of the DDLDatabase
    */
    public function getName() {
        return $this->name;
    }
    
    /**
    * Sets the name of the DDLDatabase
    *
    * @param string $name Name of the DDLDatabase
    * @return void
    */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
    * Returns the string representation of the database
    *
    * @return string Name of database
    */
    public function __toString() {
        return $this->name;
    }
    
    /**
    * Creates the nodes describing this database on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    */
    public function createXmlElement(DOMDocument $doc) {
        $ddl = $doc->documentElement;
        $database = $doc->createElement('database');
        $database->setAttribute('name', $this->name);
        
        foreach ($this->tables as $table) {
            $table->createXmlElement($doc, $database);
        }
        
        $database = $ddl->appendChild($database);
        return $database;
    }
}

/**
* Represents tables defined in the ddl file
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DDLTable {
    
    /**
    * @var DDLDatabase $parent The database that has this table
    */
    protected $parent;
    
    /**
    * @var array $fields Array of DDLFields registered in this table
    */
    protected $fields;
    
    /**
    * @var string $name Name of this DDLTable
    */
    protected $name;
    
    /**
    * @var string $className Name of the class that will map to this table
    */
    protected $className;
    
    /**
    * @var string $superClassName Name of the super class
    */
    protected $superClassName;
    
    /**
    * @var string $description Description of this DDLTable (Optional)
    */
    protected $description;
    
    /**
    * Construcs a new DDLTable
    *
    * @param DDLDatabase $parent The parent database that has this table
    * @param string $name Name of the table
    */
    public function __construct(DDLDatabase $parent, $name) {
        $this->name = $name;
        $this->parent = $parent;
        $this->fields = array();
    }
    
    /**
    * Generates sql representation of the table
    *
    * Generates sql statements that can be used to create the table
    * on a MySql server.
    *
    * @return string Sql representation of the table
    */
    public function toSql() {
        $sql  = 'DROP TABLE IF EXISTS '. $this->name .';' ."\n";
        $sql .= 'CREATE TABLE '. $this->name .' (' . "\n";
        $first = true;
        foreach ($this->fields as $field) {
            if(!$first) $sql .= ",\n";
            $first = false;
            $sql .= '    ' . $field->toSql();
        }
        $pk = array();
        $uk = array();
        foreach ($this->fields as $field) {
            if($field->isPrimaryKey()) {
                $pk[] = '`'. $field->getName().'`';
            }
            if($field->isUnique()) {
                $uk[] = '    UNIQUE KEY `'. $field->getName().'` (`'.
                $field->getName().'`)';
            }
        }
        if(count($pk) > 0) {
            $sql .= ",\n    PRIMARY KEY  (" . implode(", ", $pk) . ')';
        }
        if(count($uk) > 0) {
            $sql .= ",\n" . implode(",\n", $uk);
        }
        $sql .= "\n);\n\n";
        return $sql;
    }
    
    /**
    * Returns the requested DDLField object
    *
    * Returns the DDLField object of this table named $field
    *
    * @param string $field Name of the requested field
    * @return mixed The requested field object or false if it doesn't exist.
    */
    public function getField($field) {
        foreach ($this->fields as $f) {
            if($f->getName() == $field) {
                return $f;
            }
        }
        return false;
    }
    
    /**
    * Removes the field from this table
    *
    * Removes the field that has the supplied name from this table
    *
    * @param string $field The name of the field that will be removed
    */
    public function removeField($field) {
        foreach ($this->fields as $f) {
            if($f->getName() == $field) {
                $this->fields = array_diff($this->fields, array($f));
            }
        }
    }
    
    /**
    * Sets the parent database of this table
    *
    * @param DDLDatabase $database The parent database
    * @return void
    */
    public function setParent(DDLDatabase $database) {
        $this->parent = $database;
    }
    
    /**
    * Returns the paren Database
    *
    * @return DDLDatabase Parent database
    */
    public function getParent() {
        return $this->parent;
    }
    
    /**
    * Adds the given field to the array of DDLFields registered for this table
    *
    * @param DDLField $field The field that will be added
    * @return void
    */
    public function addField(DDLField $field) {
        array_push($this->fields, $field);
    }
    
    /**
    * Adds the given DDLField to the supplied index
    *
    * Adds the field in the supplied index shifting other fields
    * if necessary
    *
    * @param int $n The index that the DDLField object will be inserted
    * @param DDLField Field object that will be inserted
    * @return void
    */
    public function addFieldAtIndex($n, DDLField $field) {
        array_push($this->fields, $field);
        if($n == count($this->fields) - 1) {
            return;
        }
        for($i = count($this->fields) - 1; $i > $n; $i--) {
            $this->fields[$i] = $this->fields[$i - 1];
        }
        $this->fields[$n] = $field;
    }
    
    /**
    * Replaces the DDLField object in the given index
    *
    * @param int $n The index of the object that will be replaces
    * @param DDLField $field The DDLField object that will be put in the
    * supplied index
    */
    public function setField($n, DDLField $field) {
        $this->fields[$n] = $field;
    }
    
    /**
    * Returns the array of fields registered with this table
    *
    * @return array DDLFields registered with this table
    */
    public function getFields() {
        return $this->fields;
    }
    
    /**
    * Returns the name of the table
    *
    * @return string Name of the table
    */
    public function getName() {
        return $this->name;
    }
    
    /**
    * Sets the name of the table
    *
    * @param string $name Name of the table
    * @return void
    */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
    * Returns the Class name
    *
    * @return string Class name
    */
    public function getClassName() {
        return $this->className;
    }
    
    /**
    * Returns the name of super class
    *
    * @return string Super Class name
    */
    public function getSuperClassName() {
        return $this->superClassName;
    }
    
    /**
    * Sets the Class name
    *
    * @param string $className Class name
    * @return void
    */
    public function setClassName($className) {
        $this->className = $className;
    }
    
    /**
    * Sets the name of Super Class name
    *
    * @param string $sClassName Super Class name
    * @return void
    */
    public function setSuperClassName($sClassName) {
        $this->superClassName = $sClassName;
    }
    
    /**
    * Returns the description of the table
    *
    * @return string Description of the table
    */
    public function getDescription() {
        return $this->description;
    }
    
    /**
    * Sets the description of the table
    *
    * @param string $desc Description of the table
    * @return void
    */
    public function setDescription($desc) {
        $this->description = $desc;
    }
    
    /**
    * Returns the primary key fields of this table
    *
    * @return array The primary key fields
    */
    public function getPrimaryKeys() {
        $fields = array();
        foreach ($this->fields as $field) {
            if($field->isPrimaryKey()) {
                $fields[] = $field->getName();
            }
        }
        return $fields;
    }
    
    /**
    * Returns the primary key name for this table
    *
    * @return string Primary key name
    */
    public function getPrimaryKey() {
        foreach ($this->fields as $field) {
            if($field->isPrimaryKey()) {
                return $field->getName();
            }
        }
    }
    
    /**
    * Returns the string representation of the table
    *
    * @return string Name of table and the parent database
    */
    public function __toString() {
        return $this->parent->__toString() . ' : ' . $this->name . "\n";
    }
    
    /**
    * Creates the nodes describing this database on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    * @param DOMElement $database Parent node in xml object
    */
    public function createXmlElement(DOMDocument $doc, DOMElement $database) {
        $table = $doc->createElement('table');
        $table->setAttribute('name', $this->name);
        if($this->className) {
            $table->setAttribute('className', $this->className);
        }
        if($this->superClassName) {
            $table->setAttribute('superClassName', $this->superClassName);
        }
        
        foreach ($this->getFields() as $field) {
            $field->createXmlElement($doc, $table);
        }
        
        if($this->description) {
            $description = $doc->createElement('description');
            $des = $doc->createCDATASection($this->getDescription());
            $description->appendChild($des);
            $table->appendChild($description);
        }
        
        $database->appendChild($table);
    }
    
}

/**
* Represents fields of tables defined in the ddl file
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DDLField {
    
    /**
    * @var DDLTable $table Table that has this field
    */
    protected $parent;
    
    /**
    * @var string $name Name of the field
    */
    protected $name;
    
    /**
    * @var boolean $required Required flag for the field
    */
    protected $required;
    
    /**
    * @var boolean $unique Unique flag for the field
    */
    protected $unique;
    
    /**
    * @var boolean $primaryKey Primary Key flag for the field
    */
    protected $primaryKey;
    
    /**
    * @var boolean $autoIncrement Auto increment flag for the field
    */
    protected $autoIncrement;
    
    /**
    * @var string $foreignTable Name of the foreign table if this is a foreign key
    */
    protected $foreignTable;
    
    /**
    * @var string $foreignKey Name of the foreign tables key element that this field
    * is linked to
    */
    protected $foreignKey;
    
    /**
    * @var string $foreignObject Name of the variable that will hold the foreign object
    */
    protected $foreignObject;
    
    /**
    * @var DDLData $data Data model of this field
    */
    protected $data;
    
    /**
    * @var string $description Description for this field (Optional)
    */
    protected $description;
    
    /**
    * @var string $setterFunction Name of the setter function
    */
    protected $setterFunction;
    
    /**
    * @var string $getterFunction Name of the getter function
    */
    protected $getterFunction;
    
    /**
    * Constructs a DDLField
    *
    * @param DDLTable $parent The table of this field
    * @param string $name Name of the field
    * @return void
    */
    public function __construct(DDLTable $parent, $name) {
        $this->parent = $parent;
        $this->name = $name;
    }
    
    /**
    * Generates the sql representation of this field
    *
    * @return string Sql representation of this field
    */
    public function toSql() {
        $sql = '`' .$this->name . '` ' . $this->data->toSql();
        if($this->required) {
            $sql .= ' NOT NULL';
        }
        
        if(!is_null($this->data->getDefaultValue())) {
            if($this->data->getDefaultValue() == 'NULL') {
                $sql .= ' DEFAULT NULL';
            } else {
                $sql .= ' DEFAULT \'' . addcslashes(
                $this->data->getDefaultValue(), "'") . "'";
            }
        }
        
        if($this->autoIncrement) {
            $sql .= ' auto_increment';
        }
        return $sql;
    }
    
    /**
    * Sets the parent table of this field
    *
    * @param DDLTable $table Parent table of the field
    * @return void
    */
    public function setParent(DDLTable $table) {
        $this->parent = $table;
    }
    
    /**
    * Returns the parent Table
    *
    * @return DDLTable The parent table
    */
    public function getParent() {
        return $this->parent;
    }

    /**
    * Sets the data model for this field
    *
    * @param DDLData $data Datamodel for the field
    * @return void
    */
    public function setData(DDLData $data) {
        $this->data = $data;
    }
    
    /**
    * Returns the data model for this field
    *
    * @return DDLData data model for this field
    */
    public function getData() {
        return $this->data;
    }
    
    /**
    * Sets the name of this field
    *
    * @param string $name Name of this field
    * @return void
    */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
    * Returns the name of this field
    *
    * @return string Name of the field
    */
    public function getName() {
        return $this->name;
    }
    
    /**
    * Sets if this field is a required field
    *
    * @param boolean Should be passed true if this field is a required field
    * @return void
    */
    public function setRequired($flag) {
        $this->required = $flag;
    }
    
    /**
    * Returns true if this field is a required field
    *
    * @return boolean True if the field is required
    */
    public function isRequired() {
        return $this->required;
    }
    
    /**
    * Sets if this field is a unique field
    *
    * @param boolean Should be passed true if this field is a unique field
    * @return void
    */
    public function setUnique($flag) {
        $this->unique = $flag;
    }
    
    /**
    * Returns true if this field is a unique field
    *
    * @return boolean True if the field is unique
    */
    public function isUnique() {
        return $this->unique;
    }
    
    /**
    * Sets if this field is the primary key
    *
    * @param boolean Should be passed true if this field is the primary key
    * @return void
    */
    public function setPrimaryKey($flag) {
        $this->primaryKey = $flag;
    }
    
    /**
    * Sets if this field is an auto incremented primary key
    *
    * @param boolean Should be passed true if this field is auto incremented
    * @return void
    */
    public function setAutoIncrement($flag) {
        $this->autoIncrement = $flag;
    }
    
    /**
    * Checks if the field is auto incremented
    *
    * @return boolean True if the field is auto incremented
    */
    public function isAutoIncremented() {
        return $this->autoIncrement;
    }
    
    /**
    * Returns true if this field is the primary key
    *
    * @return boolean True if the field is the primary key
    */
    public function isPrimaryKey() {
        return $this->primaryKey;
    }
    
    /**
    * Sets the name of foreign table if this is a foreign key
    *
    * @param string $table Name of foreign table
    * @return void
    */
    public function setForeignTable($table) {
        $this->foreignTable = $table;
    }
    
    /**
    * Returns the foreign table name of this field
    *
    * @return string Foreign table name
    */
    public function getForeignTable() {
        return $this->foreignTable;
    }
    
    /**
    * Returns the class name of the foreign table
    *
    * @return string Class name of the foreign table
    */
    public function getForeignClass() {
        $tables = $this->getParent()->getParent()->getTables();
        
        foreach ($tables as $table) {
            if($table->getName() == $this->getForeignTable()) {
                break;
            }
        }
        
        return $table->getClassName();
    }
    
    /**
    * Returns the foreign key name defined in the foreign
    * class that this field is linked to.
    *
    * @return Foreign key name defined in the foreign table
    */
    public function getForeignKey() {
        if($this->foreignKey) {
            return $this->foreignKey;
        }
        
        $tables = $this->getParent()->getParent()->getTables();
        
        foreach ($tables as $table) {
            if($table->getName() == $this->getForeignTable()) {
                break;
            }
        }
        return $table->getPrimaryKey();
    }
    
    /**
    * Sets the foreign key name
    *
    * @param string $foreignKey Name of foreign tables key
    * @return void
    */
    public function setForeignKey($foreignKey) {
        $this->foreignKey = $foreignKey;
    }
    
    
    /**
    * Sets the name of the variable that will hold the foreign object
    *
    * @param string $name Name of variable
    * @return void
    */
    public function setForeignObject($name) {
        $this->foreignObject = $name;
    }
    
    /**
    * Returns the name of variable that will hold the foreign object name
    *
    * @return string Variable name for the foreign object
    */
    public function getForeignObject() {
        return $this->foreignObject;
    }
    
    /**
    * Sets the description of this field
    *
    * @param string $description Description for this field
    * @return void
    */
    public function setDescription($description) {
        $this->description = $description;
    }
    
    /**
    * Returns the description of this field
    *
    * @return string Description of this field
    */
    public function getDescription() {
        return $this->description;
    }
    
    /**
    * Checks if this is a foreign key
    *
    * @return boolean Returns true if this is a foreign key
    */
    public function isForeignKey() {
        return $this->foreignTable != null;
    }
    
    /**
    * Sets the setter function name of this field
    *
    * @param string $function Name of the setter function
    * @return void
    */
    public function setSetterFunction($function) {
        $this->setterFunction = $function;
    }
    
    /**
    * Returns the setter function name of this field
    *
    * @return string Setter function name
    */
    public function getSetterFunction() {
        return $this->setterFunction;
    }
    
    /**
    * Sets the getter function of this method
    *
    * @param string $function Name of the getter function
    * @return void
    */
    public function setGetterFunction($function) {
        $this->getterFunction = $function;
    }
    
    /**
    * Returns the getter function name of this field
    *
    * @return string Getter function name
    */
    public function getGetterFunction() {
        return $this->getterFunction;
    }
    
    /**
    * Returns the string representation of the field
    *
    * @return string Name of field and the parent table
    */
    public function __toString() {
        return $this->parent->__toString() . ' : ' . $this->name . "\n";
    }
    
    /**
    * Creates the nodes describing this database on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    * @param DOMElement $table Parent node in xml object
    */
    public function createXmlElement(DOMDocument $doc, DOMElement $table) {
        $field = $doc->createElement('field');
        $field->setAttribute('name', $this->name);
        if($this->primaryKey) {
            $field->setAttribute('primaryKey', 'true');
            if($this->autoIncrement) {
                $field->setAttribute('autoIncrement', 'true');
            }
        }
        
        if($this->foreignKey) {
            $field->setAttribute('foreignKeyOf', $this->foreignTable . ':' .
                                 $this->foreignKey);
            if($this->foreignObject) {
                $field->setAttribute('foreignObject', $this->foreignObject);
            }
        }
        
        if($this->required) {
            $field->setAttribute('required', 'true');
        }
        
        if($this->unique) {
            $field->setAttribute('unique', 'true');
        }
        
        $this->data->createXmlElement($doc, $field);

        if($this->setterFunction) {
            $setterFunction = $doc->createElement('setterFunction');
            $setter = $doc->createTextNode($this->setterFunction);
            $setterFunction->appendChild($setter);
            $field->appendChild($setterFunction);
        }
        
        if($this->getterFunction) {
            $getterFunction = $doc->createElement('getterFunction');
            $getter = $doc->createTextNode($this->getterFunction);
            $getterFunction->appendChild($getter);
            $field->appendChild($getterFunction);
        }
        
        if($this->description) {
            $description = $doc->createElement('description');
            $desc = $doc->createCDATASection($this->getDescription());
            $description->appendChild($desc);
            $field->appendChild($description);
        }
        
        $table->appendChild($field);
    }
}

/**
* Represents the data model of the fields.
*
* This class represents the data models for the DDLFields. This
* class can not be initiated. It's subclasses must be used to
* create DDLData objects.
*
* @version 1.0
* @see DDLField
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
abstract class DDLData {
    
    /**
    * @var string $defaultValue Default value of the field
    */
    protected $defaultValue;
    
    /**
    * @var string $validationFunction The validation function that will be used
    * before values are stored on the object. Used in class generation
    */
    protected $validationFunction;
    
    /**
    * @var string $storeFunction The store function that will be called
    * before values are stored on the object. Used in class generation
    */
    protected $storeFunction;
    
    /**
    * @var DDLField $parent The field that will hold this DDLData object
    */
    protected $parent;
    
    /**
    * Generates a new DDLData object
    *
    * @param DDLField $field Parent DDLField object
    * @return void
    */
    public function __construct(DDLField $field) {
        $this->parent = $field;
    }
    
    /**
    * Sets the parent
    *
    * @param DDLField $field New parent object
    * @return void
    */
    public function setParent(DDLField $field) {
        $this->parent = $field;
    }

    /**
    * Returns the parent field
    *
    * @return DDLField Parent field
    */    
    public function getParent() {
        return $this->parent;
    }
    
    /**
    * Sets the default value for this data
    *
    * @param string $default The default value for the data
    * @return void
    */
    public function setDefaultValue($default) {
        $this->defaultValue = $default;
    }
    
    /**
    * Returns the default value of this data
    *
    * @return string Default value of the data
    */
    public function getDefaultValue() {
        return $this->defaultValue;
    }
    
    /**
    * Sets the validation function for this data
    *
    * @param string $validationFunctin Name of validation function
    * @return void
    */
    public function setValidationFunction($validationFunction) {
        $this->validationFunction = $validationFunction;
    }
    
    /**
    * Returns the validation function
    *
    * @return string Validation function name
    */
    public function getValidationFunction() {
        return $this->validationFunction;
    }
    
    /**
    * Sets the store function for this data
    *
    * @param string $storeFunctin Name of store function
    * @return void
    */
    public function setStoreFunction($storeFunction) {
        $this->storeFunction = $storeFunction;
    }
    
    /**
    * Returns the store function
    *
    * @return string Store function name
    */
    public function getStoreFunction() {
        return $this->storeFunction;
    }
    
    /**
    * Returns the MySql data type for the DDLData object
    */
    public abstract function getDataType();
    
    /**
    * Generates sql representation of the DDLData object
    */
    public abstract function toSql();
    
    /**
    * Appends DDLData specific elements to supplied XML Document object
    *
    * @param DOMDocument $doc XMLDocument that will be used
    * @param DOMElement $data Parent xml node
    * @return void
    */
    public function appendXmlElement(DOMDocument $doc, DOMElement $data) {
        if(!is_null($this->defaultValue)) {
            $defaultValue = $doc->createElement('defaultValue');
            $default = $doc->createCDATASection($this->defaultValue);
            $defaultValue->appendChild($default);
            $data->appendChild($defaultValue);
        }
        
        if($this->storeFunction) {
            $storeFunction = $doc->createElement('storeFunction');
            $store = $doc->createTextNode($this->storeFunction);
            $storeFunction->appendChild($store);
            $data->appendChild($storeFunction);
        }
        
        if($this->validationFunction) {
            $validationFunction = $doc->createElement('validationFunction');
            $valid = $doc->createTextNode($this->validationFunction);
            $validationFunction->appendChild($valid);
            $data->appendChild($validationFunction);
        }
    }
}

/**
* Represents the data model for the numeric fields.
*
* This class represents the numeric data models for the DDLFields.
*
* @version 1.0
* @see DDLField
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DDLNumericData extends DDLData {
    
    /**
    * @var decimal $minimumValue Minimum value for this data
    */
    protected $minimumValue;
    
    /**
    * @var decimal $maximumValue Maximum value for this data
    */
    protected $maximumValue;
    
    /**
    * @var boolean $unsigned Unsigned flag
    */
    protected $unsigned;
    
    /**
    * @var string $dataType MySql data type
    */
    protected $dataType;
    
    /**
    * @var int $size MySql data size
    */
    protected $size;
    
    /**
    * Returns the sql representation
    *
    * @return string Sql representation of the object
    */
    public function toSql() {
        $sql = $this->dataType . '('.$this->size.')';
        if($this->unsigned) {
            $sql .= ' unsigned';
        }
        return $sql;
    }
    
    /**
    * Sets the minimum value
    *
    * @param decimal $min Minimum value
    * @return void
    */
    public function setMinimumValue($min) {
        $this->minimumValue = $min;
    }
    
    /**
    * Returns the minimum value
    *
    * @return decimal Minimum Value
    */
    public function getMinimumValue() {
        return $this->minimumValue;
    }
    
    /**
    * Sets the maximum value
    *
    * @param decimal $max Maximum value
    * @return void
    */
    public function setMaximumValue($max) {
        $this->maximumValue = $max;
    }
    
    /**
    * Returns the maximum value
    *
    * @return decimal Maximum Value
    */
    public function getMaximumValue() {
        return $this->maximumValue;
    }
    
    /**
    * Sets the unsigned flag
    *
    * @param boolean $flag Unsigned flag
    * @return void
    */
    public function setUnsigned($flag) {
        $this->unsigned = $flag;
    }
    
    /**
    * Returns if the data is unsigned
    *
    * @return boolean True if the data is unsigned, false oterwise
    */
    public function isUnsigned() {
        return $this->unsigned;
    }
    
    /**
    * Sets the MySql data type
    *
    * @param string $dataType MySql Data Type
    * @return void
    */
    public function setDataType($dataType) {
        $this->dataType = $dataType;
    }
    
    /**
    * Returns the MySql data type of this data
    *
    * @return string MySql data type
    */
    public function getDataType() {
        if(!$this->dataType) {
            return 'numeric';
        } else {
            return $this->dataType;
        }
    }
    
    /**
    * Sets the MySql data size of this data
    *
    * @param decimal $size MySql fields data size
    * @return void
    */
    public function setSize($size) {
        $this->size = $size;
    }
    
    /**
    * Returns the MySql data size
    *
    * @return decimal MySql data size
    */
    public function getSize() {
        return $this->size;
    }
    
    /**
    * Creates the nodes describing this data on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    * @param DOMElement $table Parent node in xml object
    */
    public function createXmlElement(DOMDocument $doc, DOMElement $field) {
        $data = $doc->createElement('data');

        $numeric = $doc->createElement('numeric');
        
        if($this->unsigned) {
            $numeric->setAttribute('unsigned', 'true');
        }
        
        if($this->dataType) {
            $numeric->setAttribute('mysqltype', $this->dataType);
            if($this->size) {
                $numeric->setAttribute('size', $this->size);
            }
        }
        
        if($this->minimumValue) {
            $min = $doc->createTextNode($this->minimumValue);
            $minValue = $doc->createElement('minimumValue');
            $minValue->appendChild($min);
            $numeric->appendChild($minValue);
        }
        
        if($this->maximumValue) {
            $max = $doc->createTextNode($this->maximumValue);
            $maxValue = $doc->createElement('maximumValue');
            $maxValue->appendChild($max);
            $numeric->appendChild($maxValue);
        }
        
        $data->appendChild($numeric);
        $this->appendXmlElement($doc, $data);
        $field->appendChild($data);
    }
}

/**
* Represents the data model for the text fields.
*
* This class represents the text data models for the DDLFields.
*
* @version 1.0
* @see DDLField
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DDLTextData extends DDLData {
    
    /**
    * @var int $minimumLength Minimum allowed length of the data
    */
    protected $minimumLength;
    
    /**
    * @var int $maximumLength Maximum allowed length of the data
    */
    protected $maximumLength;
    
    /**
    * @var string $regexp The regular expression to match this string against
    */
    protected $regexp;
    
    /**
    * @var string $type The restriction type of the data
    */
    protected $type;

    /**
    * @var string $dataType MySql data type
    */    
    protected $dataType;
    
    /**
    * @var int $size MySql data size
    */
    protected $size;
    
    /**
    * Returns the sql representation
    *
    * @return string Sql representation of the object
    */
    public function toSql() {
        $sql = $this->dataType;
        if(!is_null($this->size)) {
            $sql .= '('.$this->size.')';
        }
        return $sql;
    }
    
    /**
    * Sets the minimum allowed length
    *
    * @param int $min Minimum allowed length
    * @return void
    */
    public function setMinimumLength($min) {
        $this->minimumLength = $min;
    }
    
    /**
    * Returns the minimum allowed length
    *
    * @return int Minimum allowed length
    */
    public function getMinimumLength() {
        return $this->minimumLength;
    }
    
    /**
    * Sets the maximum allowed length
    *
    * @param int $max Maximum allowed length
    * @return void
    */
    public function setMaximumLength($max) {
        $this->maximumLength = $max;
    }
    
    /**
    * Returns the maximum allowed length
    *
    * @return int maximum allowed length
    */
    public function getMaximumLength() {
        return $this->maximumLength;
    }
    
    /**
    * Sets the regular expression to match against
    *
    * @param string $regexp The regular expression string
    * @return void
    */
    public function setRegexp($regexp) {
        $this->regexp = $regexp;
    }
    
    /**
    * Returns the regular expression to match against
    *
    * @return string Regular expression
    */
    public function getRegexp() {
        return $this->regexp;
    }
    
    /**
    * Sets the restriction type
    *
    * @param string $type Restriction type
    * @return void
    */
    public function setType($type) {
        $this->type = $type;
    }
    
    /**
    * Returns the restriction type
    *
    * @return string Restriction type
    */
    public function getType() {
        return $this->type;
    }
    
    /**
    * Sets MySql data type
    *
    * @param string $dataType MySql data type
    * @return void
    */
    public function setDataType($dataType) {
        $this->dataType = $dataType;
    }
    
    /**
    * Returns the MySql data type
    *
    * @return string MySql data type
    */
    public function getDataType() {
        if(!$this->dataType) {
            return 'string';
        } else {
            return $this->dataType;
        }
    }
    
    /**
    * Sets MySql data size
    *
    * @param int $size MySql data size
    * @return void
    */
    public function setSize($size) {
        $this->size = $size;
    }
    
    /**
    * Returns MySql data size
    *
    * @return int MySql data size
    */
    public function getSize() {
        return $this->size;
    }
    
    /**
    * Creates the nodes describing this data on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    * @param DOMElement $table Parent node in xml object
    */
    public function createXmlElement(DOMDocument $doc, DOMElement $field) {
        $data = $doc->createElement('data');

        $text = $doc->createElement('text');
        
        if($this->type) {
            $text->setAttribute('type', $this->type);
        }
        
        if($this->dataType) {
            $text->setAttribute('mysqltype', $this->dataType);
            if($this->size) {
                $text->setAttribute('size', $this->size);
            }
        }
        
        if($this->minimumLength) {
            $min = $doc->createTextNode($this->minimumLength);
            $minLength = $doc->createElement('minimumLength');
            $minLength->appendChild($min);
            $text->appendChild($minLength);
        }
        
        if($this->maximumLength) {
            $max = $doc->createTextNode($this->maximumLength);
            $maxLength = $doc->createElement('maximumLength');
            $maxLength->appendChild($max);
            $text->appendChild($maxLength);
        }
        
        if($this->regexp) {
            $regexp = $doc->createElement('regexp');
            $r = $doc->createCDATASection($this->regexp);
            $regexp->appendChild($r);
            $text->appendChild($regexp);
        }
        
        $data->appendChild($text);
        
        $this->appendXmlElement($doc, $data);
        $field->appendChild($data);
    }
}

/**
* Represents the data model for the enum fields.
*
* This class represents the enum data models for the DDLFields.
*
* @version 1.0
* @see DDLField
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DDLEnumData extends DDLData {
    /**
    * @var boolean $boolean
    */
    protected $boolean;
    
    /**
    * @var array $values Array of DDLEnumValue objects
    */
    protected $values;
    
    /**
    * Generates sql representation of the enum field
    *
    * @return string Sql representation of the enum field
    */
    public function toSql() {
        $sql = 'enum(';
        $first = true;
        foreach ($this->values as $value) {
            if(!$first) $sql .= ',';
            $first = false;
            $sql .= "'" .addcslashes($value->getText(), "'") . "'";
        }
        $sql .= ')';
        return $sql;
    }
    
    /**
    * Sets the boolean flag for the enum field
    *
    * @param boolean $flag Boolean flag
    * @return void
    */
    public function setBoolean($flag) {
        $this->boolean = $flag;
    }
    
    /**
    * Checks if this enum field is boolean
    *
    * @return boolean Returns true if the field is boolean
    */
    public function isBoolean() {
        return $this->boolean;
    }
    
    /**
    * Add a new value to the enum field
    *
    * @param DDLEnumValue $value The new value to be added
    * @return void
    */
    public function addValue(DDLEnumValue $value) {
        if(!is_array($this->values)) {
            $this->values = array();
        }
        array_push($this->values, $value);
    }
    
    /**
    * Returns the values assigned to this enum field
    *
    * @return array The value objects
    */
    public function getValues() {
        return $this->values;
    }
    
    /**
    * Returns MySql data type
    *
    * @return string Return enum
    */
    public function getDataType() {
        return 'enum';
    }
    
    /**
    * Creates the nodes describing this data on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    * @param DOMElement $table Parent node in xml object
    */
    public function createXmlElement(DOMDocument $doc, DOMElement $field) {
        $data = $doc->createElement('data');

        $enum = $doc->createElement('enum');
        
        if($this->boolean) {
            $enum->setAttribute('boolean', 'true');
        }
        
        foreach ($this->values as $value) {
            $value->createXmlElement($doc, $enum);
        }
        
        $data->appendChild($enum);
        
        $this->appendXmlElement($doc, $data);
        $field->appendChild($data);
    }
}

/**
* Represents the values that enum data objects can have
*
* @package MyObjectsCompiler
*/
class DDLEnumValue {
    
    /**
    * @var string $text The value text
    */
    protected $text;
    
    /**
    * @var boolean $flag The boolean flag for the value
    */
    protected $flag;
    
    /**
    * Constructs a new DDLEnumValue object
    *
    * @param string $text The value text
    * @param boolean $flag The boolean flag
    */
    public function __construct($text, $flag = null) {
        $this->text = $text;
        $this->flag = $flag;
    }
    
    /**
    * Sets the text of this value
    *
    * @param string $text The text that will be assigned
    * @return void
    */
    public function setText($text) {
        $this->text = $text;
    }
    
    /**
    * Return the text of the value
    * 
    * @return string Text of the value
    */
    public function getText() {
        return $this->text;
    }
    
    /**
    * Sets the flag of the value
    * 
    * @param boolean $flag Flag of the value
    * @return boolean
    */
    public function setFlag($flag) {
        $this->flag = $flag;
    }
    
    /**
    * Returns the flag of the value
    * 
    * @return boolean Flag of the value
    */
    public function getFlag() {
        return $this->flag;
    }
    
    /**
    * Creates the nodes describing this data on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    * @param DOMElement $table Parent node in xml object
    */
    public function createXmlElement(DOMDocument $doc, DOMElement $enum) {
        $enumValue = $doc->createElement('enumValue');
        
        if($enum->hasAttribute('boolean')) {
            if($this->flag) {
                $enumValue->setAttribute('flag', 'true');
            } else {
                $enumValue->setAttribute('flag', 'false');
            }
        }
        
        $val = $doc->createTextNode($this->text);
        $enumValue->appendChild($val);
        $enum->appendChild($enumValue);
    }
}

/**
* Represents the data model for the set fields.
*
* This class represents the set data models for the DDLFields.
*
* @version 1.0
* @see DDLField
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DDLSetData extends DDLData {
    
    /**
    * @var array possible values for this set field
    */
    protected $values;
    
    /**
    * Generates sql representation of this set field
    *
    * @return string Sql representatin of this Set field
    */
    public function toSql() {
        $sql = 'set(';
        $first = true;
        foreach ($this->values as $value) {
            if(!$first) $sql .= ',';
            $first = false;
            $sql .= "'" .addcslashes($value, "'") . "'";
        }
        $sql .= ')';
        return $sql;
    }
    
    /**
    * Adds the supplied value
    *
    * @return void
    * @param string $value
    */
    public function addValue($value) {
        if(!is_array($this->values)) {
            $this->values = array();
        }
        array_push($this->values, $value);
    }
    
    /**
    * Returns the assigned values
    *
    * @return array Assigned values
    */
    public function getValues() {
        return $this->values;
    }
    
    /**
    * Returns MySql data type
    *
    * @return string Returns set
    */
    public function getDataType() {
        return 'set';
    }
    
    /**
    * Creates the nodes describing this data on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    * @param DOMElement $table Parent node in xml object
    */
    public function createXmlElement(DOMDocument $doc, DOMElement $field) {
        $data = $doc->createElement('data');

        $set = $doc->createElement('set');
        
        foreach ($this->values as $value) {
            $setValue = $doc->createElement('setValue');
            $setValue->appendChild($doc->createTextNode($value));
            $set->appendChild($setValue);
        }
        
        $data->appendChild($set);
        
        $this->appendXmlElement($doc, $data);
        $field->appendChild($data);
    }
}

/**
* Represents the data model for the time fields.
*
* This class represents the time data models for the DDLFields.
*
* @version 1.0
* @see DDLField
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DDLTimeData extends DDLData {
    
    /**
    * @var string $type MySql data type
    */
    protected $type;
    
    /**
    * @var boolean $auto Auto flag
    */
    protected $auto;
    
    /**
    * Generates sql representation of this time field
    *
    * @return string Sql representatin of this Time field
    */
    public function toSql() {
        $sql = $this->type;
        if($this->type == 'year') {
            $sql .= '(4)';
        }
        return $sql;
    }
    
    /**
    * Sets the MySql data type
    *
    * @param string $type MySql data type
    * @return void
    */
    public function setType($type) {
        $this->type = $type;
    }
    
    /**
    * Returns MySql data type
    *
    * @return string MySql data type
    */
    public function getType() {
        return $this->type;
    }
    
    /**
    * Sets Auto flag
    *
    * @param boolean $flag Auto flag
    * @return void
    */
    public function setAuto($flag) {
        $this->auto = $flag;
    }
    
    /**
    * Checks if this field is auto
    *
    * @return boolean True if it is auto
    */
    public function isAuto() {
        return $this->auto;
    }

    /**
    * Returns MySql data type
    *
    * @return string MySql data type
    */
    public function getDataType() {
        return $this->type;
    }
    
    /**
    * Creates the nodes describing this data on the supplied Xml document
    *
    * @param DOMDocumend $doc The Xml document object that will be used
    * @param DOMElement $table Parent node in xml object
    */
    public function createXmlElement(DOMDocument $doc, DOMElement $field) {
        $data = $doc->createElement('data');

        $timeData = $doc->createElement('timeData');
        
        $timeData->setAttribute('type', $this->type);
        
        if($this->auto) {
            $timeData->setAttribute('auto', 'true');
        }
        
        $data->appendChild($timeData);
        
        $this->appendXmlElement($doc, $data);
        $field->appendChild($data);
    }
}
?>