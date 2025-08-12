<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: DefaultClassGenerator.php,v 1.15 2004/12/05 12:54:05 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/
class DefaultClassGenerator implements ClassGenerator {
    protected $database;
    protected $tables;
    protected $counter;
    
    protected $classTemplate;
    protected $varTemplate;
    protected $foreignTemplate;
    
    protected $getters;
    protected $setters;
    protected $author = '';
    protected $version = '1.0';
    protected $package = 'default';
    
    public function __construct($getters = true, $setters = true) {
        $this->getters = $getters;
        $this->setters = $setters;
    }
    
    public function load(DDLDatabase $database) {
        $this->database = $database;
        $this->tables = $database->getTables();
        $this->counter = 0;
    }
    
    public function hasMoreClasses() {
        return $this->counter < count($this->tables);
    }
    
    public function currentClass() {
        return $this->tables[$this->counter]->getClassName();
    }
    
    private function registerTemplates() {
        $this->classTemplate = file_get_contents(MYOBJECTS_ROOT . "/compiler/defaultclasstemplate.tpl");
        $this->varTemplate  = "\n    /**\n";
        $this->varTemplate .= "    * @var {%dataType%} {%description%}\n";
        $this->varTemplate .= "    */\n";
        $this->varTemplate .= '    public ${%fieldName%};' ."\n";

        $this->foreignTemplate = "\n" . '        $o->{%foreignObject%} = {%foreignClass%}::get(array(\'{%foreignKey%}\' => $o->{%foreignKey%}));';
    }
    
    private function templatesRegistered() {
        return $this->classTemplate != null;
    }
    
    public function setAuthor($author) {
        $this->author = $author;
    }
    
    public function setVersion($ver) {
        $this->version = $ver;
    }
    
    public function setPackage($package) {
        $this->package = $package;
    }
    
    public function generateGetClassNameMethod() {
        $template = file_get_contents(MYOBJECTS_ROOT. "/compiler/defaulttablestemplate.tpl");
        $str = '';
        foreach ($this->database->getTables() as $table) {
            $str .= "\n        case '".strtolower($table->getName())."':\n";
            $str .= "            return '".$table->getClassName()."';";
        }
        $template = str_replace('{%tables%}', $str, $template);
        $template = str_replace('{%author%}', $this->author, $template);
        $template = str_replace('{%version%}', $this->version, $template);
        $template = str_replace('{%packageName%}', $this->package, $template);
        return $template;
    }
    
    public function generateClass() {
        // Load the class templates
        if(!$this->templatesRegistered()) {
            $this->registerTemplates();
        }
        
        // Assign the template contents to a variable
        $class = $this->classTemplate;
        
        // Get the current table that will be proccessed.
        $table = $this->tables[$this->counter];
        
        // The array that will hold the values that will be written
        // to the template file
        $variables = array();   // Template variables
        
        // Get the table name and assign it to the variable
        $variables['tableName'] = $table->getName();
        
        // Get the class name and assign it to the variable
        $variables['className'] = $table->getClassName();
        
        // Get the super class name
        $variables['superClassName'] = $table->getSuperClassName() ? ' extends ' . $table->getSuperClassName() : '';
        
        if($table->getDescription()) {
            // If class description is defined in the xml file
            // then assign it. Add '* ' string to every line for PhpDoc compatiblety
            $variables['classDescription'] = str_replace("\n", "\n* ", $table->getDescription());
        } else {
            // If class description is not defined write the class name as
            // the description
            $variables['classDescription'] = $variables['className'];
            $table->setDescription($variables['className']);
        }
        
        // For the description of template variables see
        // docs/developer/ClassGeneratorTemplateVariables file
        
        // Initialize the template variables
        
        // Primary Key Names
        $variables['primaryKeyName'] = '';
        // Local Variables
        $variables['localVars'] = '';
        // Foreign Variables
        $variables['foreignVars'] = '';
        // Defines the data types of properties in the class
        $variables['properties'] = '';
        // Needen in loadArray method
        $variables['properties2'] = '';
        $variables['objectProperties'] = '';
        // Used in get method
        $variables['registerForeignKeys'] = '';
        // Used in registerForeignKeys method
        $variables['registerForeignKeys2'] = '';
        // Used in isValid method
        $variables['requiredFields'] = '';
        $variables['extensiveCheck'] = '';
        $variables['excludeAutoInc'] = '';
        // Getter functions
        $variables['getters'] = '';
        // Setter functions
        $variables['setters'] = '';
        // Initialization code for default values
        $variables['defaultValues'] = "// Initiate default values";
        
        // Used in normalizedGet method
        $variables['tables'] = '';
        // Used in normalizedGet method
        $variables['tables2'] = '';
        // Used in normalizedGet method
        $variables['relations'] = '';
        // Used in normalizedGet method
        $variables['foreignObjects'] = '';
        // Used in normalizedGet method
        $variables['tableCases'] = '';
        
        // Auto incremented field name
        $variables['autoIncrement'] = '';
        
        $variables['author'] = $this->author;
        $variables['version'] = $this->version;
        $variables['packageName'] = $this->package;
        
        // Process the primary key fields
        $first = true;
        // Get the name of primary key fields
        $primaryFields = $table->getPrimaryKeys();
        foreach ($primaryFields as $key) {
            if(!$first) {
                $variables['primaryKeyName'] .= ', ';
            }
            $first = false;
            $variables['primaryKeyName'] .= '\''. $key . '\'';
            
            // Get the DDLField object with the name $key
            foreach ($table->getFields() as $f) {
                if($f->getName() == $key) {
                    break;
                }
            }
            $auto = false;
            // Check if this field is auto incremented
            if($f->isAutoIncremented()) {
                $auto = $f;
                $variables['autoIncrement'] .= "\n" . '        if($keyName == \'' . $f->getName() . '\') return true;';
            }
        }

        // If there is only one primary key field
        if(count($primaryFields) == 1) {
            
            // Used in the getKeyValue method
            // Because there is only one primary key field return the value of it
            $variables['getKeyValue'] = 'return $this->' . $primaryFields[0] . ';';
            
            // Used in the get method
            $variables['get'] = 'if(!is_array($uniqueValues)) {' . "\n";
            $variables['get'] .= '            $uniqueValues = array(\''. $primaryFields[0] . '\'=>$uniqueValues);' . "\n";
            $variables['get'] .= "        }\n";
            
            // If an autoincremented field exists
            if($auto) {
                $variables['excludeAutoInc']  = "        if(!\$exclude && !is_scalar(\$this->". $auto->getName() .")) {\n";
                $variables['excludeAutoInc'] .= "            return false;\n";
                $variables['excludeAutoInc'] .= "        }\n";
            }
        }
        // If there is no primary key field
        elseif(count($primaryFields) == 0) {
            // Used in getKeyValue method
            // Because there is no primary key value, return null
            $variables['getKeyValue'] = 'return null;';
            
            // Used in get method.
            // Because there is no primary key defined, the get methods
            // first parameter should always be an array. 
            $variables['get'] = '';
        }
        // If there is more then one primary key field
        else {
            // Used in getKeyValue method.
            $variables['getKeyValue'] = 'switch($keyName) {' . "\n";
            foreach ($primaryFields as $key) {
                $variables['getKeyValue'] .= "            case '$key':\n";
                $variables['getKeyValue'] .= '                return $this->' .$key. ";\n";
                $variables['getKeyValue'] .= "                break;\n";
            }

            $variables['getKeyValue'] .= "        }\n";
            $variables['getKeyValue'] .= "        return null;";
            
            // If an autoincremented field exists
            if($auto) {
                $variables['excludeAutoInc']  = "        if(!\$exclude && !is_scalar(\$this->". $auto->getName() .")) {\n";
                $variables['excludeAutoInc'] .= "            return false;\n";
                $variables['excludeAutoInc'] .= "        }\n";
            }
            
           
            // Used in get method
            $variables['get'] = 'if(!is_array($uniqueValues)) {' . "\n";
            $variables['get'] .= '            throw new ObjectNotFoundException(\''.$variables['className'].' object not found with the 
            specified unique values: \' . print_r($uniqueValues, true));' . "\n";
            $variables['get'] .= "        }\n";
        }

        $foreignTableCount = 1;
        $propNames = array();
        foreach ($table->getFields() as $field) {
            // Class Properties Definition ----------------------------------------
            $var = $this->varTemplate; // Get the contents of variable definition template
            // Add the data type of the object property for the phpdoc documentation
            $dt = $field->getData()->getDataType();
            if($dt == 'enum' || $dt == 'set') {
                $dt = 'string';
            }
            $var = str_replace('{%dataType%}', $dt, $var);
            
            // If description for this field is available embed it to the property
            // definition
            if($field->getDescription()) {
                $description = $field->getDescription();
            } else {
                $description = '';
            }
            
            // If this field is a primary key field add a comment
            // acknowledging it. 
            if($field->isPrimaryKey()) {
                $description .= ' (Primary Key)';
                if($field->isAutoIncremented()) {
                    $description .= ' (Auto Increment)';
                }
            }
            
            // If this field is a unique key field add a comment
            // acknowledging it. 
            if($field->isUnique()) {
                $description .= ' (Unique)';
            }
            
            // If this field is a required field add a comment
            // acknowledging it. 
            if($field->isRequired()) {
                $description .= ' (Required)';
            }
            
            // Embed the property description
            $var = str_replace('{%description%}', $description, $var);
            // Embed the property name
            $var = str_replace('{%fieldName%}', $field->getName(), $var);
            // Add the property definition to template tag
            $variables['localVars'] .= $var;
            
            // Used in the property type static method
            $temp  = "\n            case '" . $field->getName() . "':\n";
            $temp .= "                return '" . $field->getData()->getDataType() ."';\n";
            $temp .= "                break;";
            $variables['properties'] .= $temp;
            
            if($field->getSetterFunction()) {
                $function = $field->getSetterFunction();
            } else {
                $function = 'set' . ucfirst($field->getName());
            }
            
            // Used in loadArray method
            $temp  = "\n            case '" . $field->getName() . "':\n";
            $temp .= '                if(!$this->' . $function . '($value)) {' . "\n";
            $temp .= '                    $invalidProperties[] = $key;' . "\n";
            $temp .= "                }\n";
            $temp .= "                break;";
            $variables['properties2'] .= $temp;
            
            $propNames[] = '`' . $field->getName() . '`';
            
            // Check if this field is a foreign key
            // If it is a foreign key a foreign object property should be defined
            // in the class
            if($field->isForeignKey()) {
                // Get the variable template contents
                $var = $this->varTemplate;
                // Data type of class property is now the Class of foreign object so assign it
                $var = str_replace('{%dataType%}', $field->getForeignClass(), $var);
                // Assign the description
                $var = str_replace('{%description%}', 'Foreign ' . $field->getForeignTable() . ' object', $var);
                // Get the foreign object name that will be defined in class
                // This is actually the property name that will be defined.
                if($field->getForeignObject()) {
                    $objName = $field->getForeignObject();
                } else {
                    // If foreign object is not defined, then name the
                    // class property that will hold the foreign object
                    // as the name of the foreign table.
                    $objName = strtolower($field->getForeignTable());
                    $field->setForeignObject($objName);
                }
                // Embed the property name
                $var = str_replace('{%fieldName%}', $objName, $var);
                // Add the property definition to the template tag
                $variables['foreignVars'] .= $var;
                
                $fClass = $field->getForeignClass();
                $fKey = $field->getForeignKey();
                
                /* normalizedGet() Method Generator Block */
                
                
                $foreignTableCount++;
                $variables['tables'] .= 'MyObjectsTable' . $foreignTableCount . '.* , ';
                
                $variables['tables2'] .= '`'. $field->getForeignTable() . '` as MyObjectsTable' . $foreignTableCount. ' , ';

                $variables['foreignObjects'] .= '            $' . $field->getForeignObject() . ' = new ' . $fClass . "();\n";
                $variables['foreignObjects'] .= '            ' . $fClass . '::addInstance($' . $field->getForeignObject() .");\n";
                $variables['foreignObjects'] .= '            $o->' . $field->getForeignObject() . ' = $' . $field->getForeignObject() . ";\n";

                $variables['tableCases'] .= "                    case 'MyObjectsTable".$foreignTableCount."':\n";
                $variables['tableCases'] .= '                        $'.$field->getForeignObject() .'->$fieldName = $row[$i];' . "\n"; 
                $variables['tableCases'] .= "                        break;\n";
                
                $variables['relations'] .= 'MyObjectsTable1.`';
                $variables['relations'] .= $field->getName() . '` = MyObjectsTable' . $foreignTableCount;
                $variables['relations'] .= '.`' . $fKey . '` AND' . "\n                ";
                
                /* End Of normalizedGet() Method Generator Block */
            }
            
            
            
            $this->processField($field, $variables);
        }
        
        /* normalizedGet() Method Generator Block */
        
        $variables['tables'] .= 'MyObjectsTable1.*';
        $variables['tables2'] .= '`'. $field->getParent()->getName() . '` as MyObjectsTable1';
        
        $variables['tableCases'] .= "                    case 'MyObjectsTable1':\n";
        $variables['tableCases'] .= '                        $o->$fieldName = $row[$i];' . "\n"; 
        $variables['tableCases'] .= "                        break;";
        
        /* End Of normalizedGet() Method Generator Block */
        
        $variables['requiredFields'] = substr($variables['requiredFields'], 0, strlen($variables['requiredFields']) - 2);

        $variables['objectProperties'] = join("\n    , ", $propNames);
        $variables['qm'] = '';
        
        for ($i = 0; $i < count($propNames); $i++) {
            if($i > 0) {
                $variables['qm'] .= ', ';
            }
            $variables['qm'] .= '?';
        }
        
        foreach ($variables as $key => $value) {
            $class = str_replace('{%'.$key.'%}', $value, $class);
        }
        
        $this->counter++;
        return $class;
    }
    
    private function processField(DDLField $field, &$variables) {
        
        if(!$field->isAutoIncremented()) {
            if($field->isRequired()) {
                 $variables['requiredFields'] .= "'". $field->getName() . "', ";
            }
        }
        
        if($field->isForeignKey()) {
            $f = $this->foreignTemplate;
            
            $tables = $field->getParent()->getParent()->getTables();
            $found = false;
            foreach ($tables as $table) {
                if($table->getName() == $field->getForeignTable()) {
                    $found = true;
                    $tableName = $table->getName();
                    $className = $table->getClassName();
                    break;
                }
            }
            
            if($found) { // Table is found search the field
                $found = false;
                $fields = $table->getFields();
                foreach($fields as $fi) {
                    if($fi->getName() == $field->getForeignKey()) {
                        $found = true;
                        
                        break;
                    }
                }
            }
            
            $fieldName = $field->getForeignTable() . ':' . $field->getForeignKey();
            
            if(!$found) {
                $ex = new InvalidForeignKeyException('Foreign key not found', $fieldName, $field->getName());
                throw $ex;
            }
            
            $f = str_replace('{%foreignObject%}', $field->getForeignObject(), $f);
            $f = str_replace('{%foreignClass%}', $className, $f);
            $f = str_replace('{%foreignKey%}', $field->getName(), $f);
            $variables['registerForeignKeys'] .= $f;
            $f = str_replace('                ', '        ', $f);
            $variables['registerForeignKeys2'] .= str_replace('$o->', '$this->', $f);
        }
        
        $data = $field->getData();
        
        if($field->getSetterFunction()) {
            $function = $field->getSetterFunction();
        } else {
            $function = 'set' . ucfirst($field->getName());
        }
        
        if(!is_null($data->getDefaultValue())) {
            $default = "\n";
            if($data->getDefaultValue() == 'NULL') {
                $default .= '        $this->'. $field->getName() .' = null;';
            } else {
                $default .= '        $this->'. $field->getName() .' = \''. $field->getData()->getDefaultValue() .'\';';
            }
            $variables['defaultValues'] .= $default;
        } elseif ($data instanceof DDLTimeData && $data->isAuto()) {
          	$variables['defaultValues'] .= "\n" . '        $this->' . $function . "time();";
        }
        
        if($this->getters) {
            $this->addGetter($field, $variables);
        }
        
        if($this->setters) {
            
            $variables['extensiveCheck'] .= "\n                if(is_scalar(\$this->".$field->getName().")) \$this->"
            .$function."(\$this->".$field->getName().");";
            
            $this->addSetter($field, $variables);
        }
    }
    
    private function addGetter(DDLField $field, &$variables) {
        if($field->getData() instanceof DDLEnumData && $field->getData()->isBoolean()) {
            $this->addEnumGetter($field, $variables);
        } else {
            if($field->getGetterFunction()) {
                $function = $field->getGetterFunction();
            } else {
                $function = 'get' . ucfirst($field->getName());
            }
            $temp  = "\n    /**\n";
            $temp .= "    * Returns " . $field->getDescription() . "\n";
            $temp .= "    *\n";
            $temp .= "    * @return " . $field->getData()->getDataType() . " " . $field->getName() . "\n";
            $temp .= "    */\n";
            $temp .= '    public function '. $function .'() {' . "\n";
            $temp .= '        return $this->'. $field->getName() .';' . "\n";
            $temp .= "    }\n";
            
            $variables['getters'] .= $temp;
            
            if($field->isForeignKey()) {
                
                $function = 'get' . ucfirst($field->getForeignObject());
                
                $className = $field->getForeignClass();
                
                $temp  = "\n    /**\n";
                $temp .= "    * Returns the foreign " . $className . " object\n";
                $temp .= "    *\n";
                $temp .= "    * @return " . $className . " Foreign object\n";
                $temp .= "    */\n";
                $temp .= '    public function '. $function .'() {' . "\n";
                $temp .= '        if($this->' . $field->getForeignObject() . ' instanceof ' . $className . ") {\n";
                $temp .= '            return $this->'. $field->getForeignObject() .';' . "\n";
                $temp .= "        } else {\n";
                $temp .= '            $this->' . $field->getForeignObject() . ' = ' . $className . '::get(array(\''. $field->getForeignKey() . '\'=>$this->'. $field->getName() . "));\n";
                $temp .= '            return $this->'. $field->getForeignObject() .';' . "\n";
                $temp .= "        }\n";
                $temp .= "    }\n";
                
                $variables['getters'] .= $temp;
            }
        }
    }
    
    public function addEnumGetter(DDLField $field, &$variables) {
        if($field->getGetterFunction()) {
            $function = $field->getGetterFunction();
        } else {
            $function = 'is' . ucfirst($field->getName());
        }
        
        $values = $field->getData()->getValues();
        foreach ($values as $value) {
            if($value->getFlag()) {
                break;
            }
        }
        
        $temp  = "\n    /**\n";
        $temp .= "    * Checks for " . $field->getDescription() . "\n";
        $temp .= "    *\n";
        $temp .= "    * @return " . $field->getData()->getDataType() . " " . $field->getName() . "\n";
        $temp .= "    */\n";
        $temp .= '    public function '. $function .'() {' . "\n";
        $temp .= '        return $this->'. $field->getName() .' == \''. $value->getText() .'\';' . "\n";
        $temp .= "    }\n";
        $variables['getters'] .= $temp;
    }
    
    private function addSetter(DDLField $field, &$variables) {
        switch (get_class($field->getData())) {
            case 'DDLNumericData':
                $this->addNumericSetter($field, $variables);
                break;
            case 'DDLTextData':
                $this->addTextSetter($field, $variables);
                break;
            case 'DDLEnumData':
                $this->addEnumSetter($field, $variables);
                break;
            case 'DDLSetData':
                $this->addSetSetter($field, $variables);
                break;
            case 'DDLTimeData':
                $this->addTimeSetter($field, $variables);
                break;
        }
        
        if($field->isForeignKey()) {
            
            $className = $field->getForeignClass();
            
            $objName = $field->getForeignObject();
    
            $temp  = "\n    /**\n";
            $temp .= "    * Sets the foreign " . $className . " object\n";
            $temp .= "    *\n";
            $temp .= "    * @param ".$className. ' $' . $objName . " Foreign " . $className . " object\n";
            $temp .= "    * @throws InvalidValueException Thrown if the primary key value\n";
            $temp .= "    * of the foreign object is not set\n";
            $temp .= "    * @return boolean Returns true if the foreign object is set successfully\n";
            $temp .= "    */\n";
            $temp .= '    public function set' .ucfirst($objName). '(' . $className .' $' . $objName;
            $temp .= ") {\n";
            $temp .= '        if($'. $objName .'->'. $field->getForeignKey() . ' != null) {' . "\n";
            $temp .= '            $this->' . $objName . ' = $' . $objName . ";\n";
            $temp .= '            $this->' . $field->getName() .' = $this->'.$objName.'->'.$field->getForeignKey() . ";\n";
            $temp .= "        } else {\n";
            $temp .= "            if(\$this->verbose) throw new InvalidValueException('" .$objName. "');\n";
            $temp .= "            return false;\n";
            $temp .= "        }\n";
            $temp .= "        return true;\n";
            $temp .= "    }\n";
            
            $variables['setters'] .= $temp;
        }
    }
    
    private function addTextSetter(DDLField $field, &$variables) {
        if($field->getSetterFunction()) {
            $function = $field->getSetterFunction();
        } else {
            $function = 'set' . ucfirst($field->getName());
        }
        
        $currClass = $field->getParent()->getClassName();
        
        $data = $field->getData();
        
        $temp  = "\n    /**\n";
        $temp .= "    * Sets " . $field->getDescription() . "\n";
        $temp .= "    *\n";
        if($data->getValidationFunction() || $data->getMinimumLength() || $data->getMaximumLength() ||
        $data->getRegexp()) {
            $temp .= "    * @throws InvalidValueException Thrown if the supplied value\n";
            $temp .= "    * is not valid for ". $field->getDescription(). "\n";
        }
        if($field->isUnique()) {
            $temp .= "    * @throws UniqueKeyExistsException Thrown if the supplied value exists\n";
            $temp .= "    * as a " . $field->getDescription() . " property of another $currClass object\n";
        }
        $temp .= "    * @param string \$" . $field->getName() . ' ' . $field->getDescription() . "\n";
        $temp .= "    * @return boolean Returns true if ".$field->getDescription()." is set successfully\n";
        $temp .= "    */\n";
        $temp .= '    public function ' .$function. '($' . $field->getName();
        
        if(!is_null($data->getDefaultValue())) {
            if($data->getDefaultValue() == 'NULL') {
                $temp .= ' = null';
            } else {
                $temp .= ' = \'' . $data->getDefaultValue() . '\'';
            }
        }

        $min = $data->getMinimumLength();
        $max = $data->getMaximumLength();
        $regexp = $data->getRegexp();
        $validMethod = false;
        switch ($data->getType()) {
            case 'date':
                $validMethod = 'isDate';
                break;
            case 'time':
                $validMethod = 'isTime';
                break;
            case 'datetime':
                $validMethod = 'isDateTime';
                break;
            case 'year':
                $validMethod = 'isYear';
                break;
            case 'email':
                $validMethod = 'isEmail';
                break;
            case 'cleanText':
                $validMethod = 'isCleanText';
                break;
            case 'word':
                $validMethod = 'isWord';
                break;
            case 'alpha':
                $validMethod = 'isAlpha';
                break;
            case 'numeric':
                $validMethod = 'isNumeric';
                break;
            default:
                if($min || $max) {
                    $validMethod = 'isLengthValid';
                }
                break;
        }
        
        if($field->isUnique()) {
            $temp .= ', $uniqueTest = true';
        }
        
        $temp .= ") {\n";
        
        if($validMethod) {
            $temp .= "        // Check for validness\n";

           
            $temp .= '        if(!StringValidator::' . $validMethod .'($'. $field->getName();
            if($min) {
                $temp .= ', ' . $min;
                if($max) {
                    $temp .= ', ' . $max;
                }
            } else {
                if($max) {
                    $temp .= ', 0, ' . $max; 
                }
            }
            $temp .= ")) {\n";
            $temp .= "            if(\$this->verbose) throw new InvalidValueException('" . $field->getName() . "');\n";
            $temp .= "            return false;\n";
            $temp .= "        }\n";
        }
        
        if($data->getValidationFunction()) {
            $temp .= "\n        // Check for validness using the validation function\n";
            $temp .= '        if(!' . $data->getValidationFunction() . '($' . $field->getName() . ")) {\n";
            $temp .= "            if(\$this->verbose) throw new InvalidValueException('" . $field->getName() . "');\n";
            $temp .= "            return false;\n";
            $temp .= "        }\n";
        }
        
        if($data->getRegexp()) {
            $temp .= "\n        // Check for the regular expression\n";
            $temp .= '        if(!StringValidator::isValid($' . $field->getName() . ', 0, 0, \''.$data->getRegexp().'\'';
            $temp .= ")) {\n";
            $temp .= "            if(\$this->verbose) throw new InvalidValueException('" . $field->getName() . "');\n";
            $temp .= "            return false;\n";
            $temp .= "        }\n";
        }
        
        if($field->isUnique()) {
        	$temp .= "\n" . '        if($uniqueTest) {' . "\n";
            $temp .= "\n            // Check if the unique key ". $field->getName() ." exists\n";
            $temp .= '            $result = ' . $field->getParent()->getClassName() . '::select(';
            $temp .= 'array(\'' . $field->getName() . '\'=>$' . $field->getName() . '), \''.$field->getName() . '\');' . "\n";
            $temp .= '            if($row = $result->fetch_row()) {' . "\n";
            $temp .= '                if($this->'.$field->getName().' != $row[0]) throw new UniqueKeyExistsException(\''. $field->getName() . '\');' . "\n";
            $temp .= "            }\n";
            $temp .= "        }\n";
        }
        
        $temp .= "\n        // Assign the value\n";
        if($data->getStoreFunction()) {
            $temp .= '        $this->' . $field->getName() . ' = ' . $data->getStoreFunction() . '($'. $field->getName() .');' . "\n";
        } else {
            $temp .= '        $this->' . $field->getName() . ' = $'. $field->getName() .";\n";
        }
        
        $temp .= "        return true;\n";
        $temp .= "    }\n";
        
        $variables['setters'] .= $temp;
    }
    
    private function addNumericSetter(DDLField $field, &$variables) {
        if($field->getSetterFunction()) {
            $function = $field->getSetterFunction();
        } else {
            $function = 'set' . ucfirst($field->getName());
        }
        
        $data = $field->getData();
        $currClass = $field->getParent()->getClassName();
        
        $temp  = "\n    /**\n";
        $temp .= "    * Sets " . $field->getDescription() . "\n";
        $temp .= "    *\n";
        if($data->getValidationFunction() || $data->getMinimumValue() || $data->getMaximumValue() ||
        $data->isUnsigned()) {
            $temp .= "    * @throws InvalidValueException Thrown if the supplied value\n";
            $temp .= "    * is not valid for ". $field->getDescription(). "\n";
        }
        if($field->isUnique()) {
            $temp .= "    * @throws UniqueKeyExistsException Thrown if the supplied value exists\n";
            $temp .= "    * as a " . $field->getDescription() . " property of another $currClass object\n";
        }
        $temp .= "    * @param ".$data->getDataType()." \$" . $field->getName() . ' ' . $field->getDescription() . "\n";
        $temp .= "    * @return boolean Returns true if ".$field->getDescription()." is set successfully\n";
        $temp .= "    */\n";
        $temp .= '    public function ' .$function. '($' . $field->getName();
        
        if(!is_null($data->getDefaultValue())) {
            if($data->getDefaultValue() == 'NULL') {
                $temp .= ' = null';
            } else {
                $temp .= ' = \'' . $data->getDefaultValue() . '\'';
            }
        }
        
        if($field->isUnique()) {
            $temp .= ', $uniqueTest = true';
        }
        
        $temp .= ") {\n";
        
        if($data->isUnsigned()) {
            $temp .= "\n        // Check if the value is unsigned\n";
            $temp .= '        if($' . $field->getName() . ' < 0';
            $temp .= ") {\n";
            $temp .= "            if(\$this->verbose) throw new InvalidValueException('" . $field->getName() . "');\n";
            $temp .= "            return false;\n";
            $temp .= "        }\n";
        }
        
        $min = $data->getMinimumValue();
        $max = $data->getMaximumValue();
        
        if($min || $max) {
            $temp .= "\n        // Check if the value is in range\n";
            $temp .= '        if(';
            if($min) {
                $temp .= '$' . $field->getName() . ' < ' . $min;
            }
            if($min && $max) {
                $temp .= ' || ';
            }
            if($max) {
                $temp .= '$' . $field->getName() . ' > ' . $max;
            }
            
            $temp .= ") {\n";
            $temp .= "            if(\$this->verbose) throw new InvalidValueException('" . $field->getName() . "');\n";
            $temp .= "            return false;\n";
            $temp .= "        }\n";
        }
        
        if($field->isUnique()) {
        	$temp .= "\n" . '        if($uniqueTest) {' . "\n";
            $temp .= "\n            // Check if the unique key ". $field->getName() ." exists\n";
            $temp .= '            $result = ' . $field->getParent()->getClassName() . '::select(';
            $temp .= 'array(\'' . $field->getName() . '\'=>$' . $field->getName() . '), \''.$field->getName() . '\');' . "\n";
            $temp .= '            if($row = $result->fetch_row()) {' . "\n";
            $temp .= '                if($this->'.$field->getName().' != $row[0]) throw new UniqueKeyExistsException(\''. $field->getName() . '\');' . "\n";
            $temp .= "            }\n";
            $temp .= "        }\n";
        }
        
        $temp .= "\n        // Assign the value\n";
        if($data->getStoreFunction()) {
            $temp .= '        $this->' . $field->getName() . ' = ' . $data->getStoreFunction() . '($'. $field->getName() .');' . "\n";
        } else {
            $temp .= '        $this->' . $field->getName() . ' = $'. $field->getName() .";\n";
        }
        $temp .= "        return true;\n";
        $temp .= "    }\n";
        $variables['setters'] .= $temp;
    }
    
    private function addEnumSetter(DDLField $field, &$variables) {
        $data = $field->getData();
        $values = $data->getValues();
        
        if($data->isBoolean()) {
            
            if(count($values) != 2) {
                throw new InvalidBooleanFieldException($field->__toString());
            }
            
            if($field->getSetterFunction()) {
                $function = $field->getSetterFunction();
            } else {
                $function = 'set' . ucfirst($field->getName());
            }
            
            $temp  = "\n    /**\n";
            $temp .= "    * Sets " . $field->getDescription() . "\n";
            $temp .= "    *\n";
            $temp .= "    * @param boolean \$" . $field->getName() . ' ' . $field->getDescription() . "\n";
            $temp .= "    * @return boolean Returns true if ".$field->getDescription()." is set successfully\n";
            $temp .= "    */\n";
            $temp .= '    public function ' .$function. '($' . $field->getName();
            
            $found = false;
            $flag = true;
            foreach ($values as $value) {
                if($value->getText() == $data->getDefaultValue()) {
                    if($value->getFlag()) {
                        $defalt = 'true';
                    } else {
                        $defalt = 'false';
                    }
                    $found = true;
                }
                if(is_null($value->getFlag())) {
                    $flag = false;
                } else {
                    if($value->getFlag()) {
                        $trueValue = $value;
                    } else {
                        $falseValue = $value;
                    }
                }
            }
            
            if(!$data->getDefaultValue()) {
                $found = true;
            }
            if(!$flag || !$found) {
                $ex = new BooleanFlagNotSetException('Boolean flag not set', $field->__toString());
                throw $ex; 
            }
            
            if($data->getDefaultValue()) {
                $temp .= ' = ' . $defalt;
            }
            
            $temp .= ") {\n";

            $temp .= '        if($' . $field->getName() . ") {\n";
            $temp .= '            $this->'.$field->getName().' = \'' . $trueValue->getText() . "';\n";
            $temp .= "        } else {\n";
            $temp .= '            $this->'.$field->getName().' = \'' . $falseValue->getText() . "';\n";
            $temp .= "        }\n";
            $temp .= "    }\n";
        }
        else {
            if($field->getSetterFunction()) {
                $function = $field->getSetterFunction();
            } else {
                $function = 'set' . ucfirst($field->getName());
            }
            
            $temp  = "\n    /**\n";
            $temp .= "    * Sets " . $field->getDescription() . "\n";
            $temp .= "    *\n";
            $temp .= "    * @throws InvalidValueException Thrown if the supplied value\n";
            $temp .= "    * is not valid for ". $field->getDescription(). "\n";
            $temp .= "    * @param string \$" . $field->getName() . ' ' . $field->getDescription() . "\n";
            $temp .= "    * @return boolean Returns true if ".$field->getDescription()." is set successfully\n";
            $temp .= "    */\n";
            $temp .= '    public function ' .$function. '($' . $field->getName();
            
            if(!is_null($data->getDefaultValue())) {
                if($data->getDefaultValue() == 'NULL') {
                    $temp .= ' = null';
                } else {
                    $temp .= ' = \'' . $data->getDefaultValue() . '\'';
                }
            }
            
            $temp .= ") {\n";
            $temp .= '        switch($' . $field->getName() . ") {\n";
            foreach ($values as $value) {
                $temp .= "            case '". $value->getText() . "':\n";
                $temp .= '                $this->'.$field->getName().' = $' . $field->getName() . ";\n";
                $temp .= "                return true;\n";
            }
            $temp .= "        }\n";
            $temp .= '        if($this->verbose) throw new InvalidValueException(\''. $field->getName() . '\');' . "\n";
            $temp .= "        return false;\n";
            $temp .= "    }\n";
        }
        $variables['setters'] .= $temp;
    }
    
    private function addSetSetter(DDLField $field, &$variables) {
        $data = $field->getData();
        $values = $data->getValues();
        
        if($field->getSetterFunction()) {
            $function = $field->getSetterFunction();
        } else {
            $function = 'set' . ucfirst($field->getName());
        }
        
        $temp  = "\n    /**\n";
        $temp .= "    * Sets " . $field->getDescription() . "\n";
        $temp .= "    *\n";
        $temp .= "    * @throws InvalidValueException Thrown if the supplied value\n";
        $temp .= "    * is not valid for ". $field->getDescription(). "\n";
        $temp .= "    * @param string \$" . $field->getName() . ' ' . $field->getDescription() . "\n";
        $temp .= "    * @return boolean Returns true if ".$field->getDescription()." is set successfully\n";
        $temp .= "    */\n";
        $temp .= '    public function ' .$function. '($' . $field->getName();
        
        if(!is_null($data->getDefaultValue())) {
            if($data->getDefaultValue() == 'NULL') {
                $temp .= ' = null';
            } else {
                $temp .= ' = \'' . $data->getDefaultValue() . '\'';
            }
        }
        
        $temp .= ") {\n";
        $val = '';
        $addComma = false;
        foreach ($values as $value) {
            if($addComma) {
                $val .= ', ';
            }
            $addComma = true;
            $val .= '\'' . $value . '\'';
        }
        $temp .= '        if(!is_array($' . $field->getName(). ")) {\n";
        $temp .= '            $'. $field->getName() .' = array($'. $field->getName() .');' . "\n";
        $temp .= "        }\n\n";
        $temp .= '        $arr = array(' . $val . ");\n\n";
        $temp .= '        $found = true;' . "\n";
        $temp .= '        foreach($'. $field->getName() .' as $value) {' . "\n";
        $temp .= '            if(!in_array($value, $arr)) $found = false; break;' . "\n";
        $temp .= "        }\n\n";
        $temp .= '        if($found) $this->'. $field->getName(). ' = implode(\', \',$'. $field->getName() . ");\n";
        $temp .= "        else {\n";
        $temp .= '            if($this->verbose) throw new InvalidValueException(\''.$field->getName()."');\n";
        $temp .= "            return false;\n";
        $temp .= "        }\n";
        $temp .= "    }\n";
        $variables['setters'] .= $temp;
    }
    
    private function addTimeSetter(DDLField $field, &$variables) {
        $data = $field->getData();
        
        
        $data->setType(strtolower($data->getType()));
        switch($data->getType()) {
            case 'date':
                $func = 'Date';
                $pattern = 'Y-m-j';
                break;
            case 'time':
                $func = 'Time';
                $pattern = 'H:i:s';
                break;
            case 'datetime':
                $func = 'DateTime';
                $pattern = 'Y-m-j H:i:s';
                break;
            case 'year':
                $func = 'Year';
                $pattern = 'Y';
                break;
            case 'timestamp':
                $pattern = 'YmjHis';
                break;
        }
        
        if($field->getSetterFunction()) {
            $function = $field->getSetterFunction();
        } else {
            $function = 'set' . ucfirst($field->getName());
        }
        
        $temp  = "\n    /**\n";
        $temp .= "    * Sets " . $field->getDescription() . "\n";
        $temp .= "    *\n";
        if($data->getType() != 'timestamp') {
            $temp .= "    * @throws InvalidValueException Thrown if the supplied value\n";
            $temp .= "    * is not in '$pattern' format that is valid for ". $field->getDescription(). "\n";
            $temp .= "    * @param string \$" . $field->getName() . ' ' . $field->getDescription() . "\n";
        } else {
            $temp .= "    * @throws InvalidValueException Thrown if the supplied value\n";
            $temp .= "    * is not an integer that is valid for ". $field->getDescription(). "\n";
            $temp .= "    * @param int \$" . $field->getName() . ' ' . $field->getDescription() . "\n";
        }
        if($data->getValidationFunction()) {
            $temp .= "    * @throws InvalidValueException Thrown if the supplied value\n";
            $temp .= "    * is not valid for ". $field->getDescription(). "\n";
        }
        $temp .= "    * @return boolean Returns true if ".$field->getDescription()." is set successfully\n";
        $temp .= "    */\n";
        $temp .= '    public function ' .$function. '($' . $field->getName();
        
        if(!is_null($data->getDefaultValue())) {
            if($data->getDefaultValue() == 'NULL') {
                $temp .= ' = null';
            } else {
                $temp .= ' = \'' . $data->getDefaultValue() . '\'';
            }
        }
        
        $temp .= ") {\n";
        if($data->getType() != 'timestamp') {
            $temp .= "        // Check for validness\n";
            $temp .= '        if(!StringValidator::is'. $func .'($'. $field->getName().")) {\n";
            $temp .= "            if(\$this->verbose) throw new InvalidValueException('".$field->getName()."');\n";
            $temp .= "            return false;\n";
            $temp .= "        }\n";
        } else {
            $temp .= "        // Check for validness\n";
            $temp .= '        if(!is_int($'. $field->getName().")) {\n";
            $temp .= "            if(\$this->verbose) throw new InvalidValueException('".$field->getName()."');\n";
            $temp .= "            return false;\n";
            $temp .= "        }\n";
        }
        
        $temp .= "\n        // Assign the value\n";
        if($data->getStoreFunction()) {
            $temp .= '        $this->' . $field->getName() . ' = ' . $data->getStoreFunction() . '($'. $field->getName() .');' . "\n";
        } else {
            $temp .= '        $this->' . $field->getName() . ' = $'. $field->getName() .";\n";
        }
        
        $temp .= "        return true;\n";
        $temp .= "    }\n";
        $variables['setters'] .= $temp;
        
        
        $temp  = "\n    /**\n";
        $temp .= "    * Sets " . $field->getDescription() . " using an integer unix timestamp\n";
        $temp .= "    *\n";
        if($data->getValidationFunction()) {
            $temp .= "    * @throws InvalidValueException Thrown if the supplied value\n";
            $temp .= "    * is not valid for ". $field->getDescription(). "\n";
        }
        $temp .= "    * @param int \$" . $field->getName() . ' ' . $field->getDescription() . " in unix timestamp\n";
        $temp .= "    * @return boolean Returns true if ".$field->getDescription()." is set successfully\n";
        $temp .= "    */\n";
        $temp .= '    public function ' .$function. 'time($' . $field->getName();
        
        if($data->isAuto()) {
            $temp .= ' = "NOW"';
        }
        
        $temp .= ") {\n";
        
        $temp .= '        if($'. $field->getName()." == \"NOW\") {\n";
        $temp .= '            $'. $field->getName()." = time();\n";
        $temp .= "        }\n";
        
        $temp .= "        // Check for validness\n";
        $temp .= '        if(!is_int($'. $field->getName().")) {\n";
        $temp .= "            if(\$this->verbose) throw new InvalidValueException('".$field->getName()."');\n";
        $temp .= "            return false;\n";
        $temp .= "        }\n";

        
        $temp .= "\n        // Assign the value\n";
        if($data->getStoreFunction()) {
            $temp .= '        $this->' . $field->getName() . ' = ' . $data->getStoreFunction() . '(date(\''.$pattern.'\', $'. $field->getName() .'));' . "\n";
        } else {
            $temp .= '        $this->' . $field->getName() . ' = date(\''.$pattern.'\', $'. $field->getName() .");\n";
        }
        
        $temp .= "        return true;\n";
        $temp .= "    }\n";
        $variables['setters'] .= $temp;
    }
}
?>