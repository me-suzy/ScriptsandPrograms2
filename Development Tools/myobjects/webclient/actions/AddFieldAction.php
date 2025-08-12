<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: AddFieldAction.php,v 1.8 2004/11/08 00:52:51 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class AddFieldAction extends Action {
    
    protected $post;
    
    public function __construct($post = false) {
        parent::__construct();
        $this->post = $post;
    }
    
    public function handle() {
        
        if(!($this->ddl instanceof DDL)) {
            header("Location: index.php");
            return;
        }
        
        if($this->post) {
            $this->insertField();
            return;
        }
        
        if(!isset($_GET['database']) || !isset($_GET['table']) || !isset($_GET['index'])) {
            header("Location: index.php");
            return;
        }
        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('databaseName', $_GET['database']);
        $template->assign('tableName', $_GET['table']);
        $template->assign('index', $_GET['index']);
        $this->assignSchema($template);
        $template->display('addfield.html');
    }
    
    private function getDataRow($dataType) {
        switch ($dataType) {
            case 'tinyint': return 0;
            case 'smallint': return 1;
            case 'mediumint': return 2;
            case 'int': return 3;
            case 'bigint': return 4;
            case 'float': return 5;
            case 'double': return 6;
            case 'decimal': return 7;
            case 'char': return 8;
            case 'varchar': return 9;
            case 'tinyblob': return 10;
            case 'blob': return 11;
            case 'mediumblob': return 12;
            case 'longblob': return 13;
            case 'tinytext': return 14;
            case 'text': return 15;
            case 'mediumtext': return 16;
            case 'longtext': return 17;
            case 'enum': return 18;
            case 'set': return 19;
            case 'date': return 20;
            case 'time': return 21;
            case 'datetime': return 22;
            case 'timestamp': return 23;
            case 'year': return 24;
            case 'numeric': return 25;
            case 'string': return 26;
        }
    }
    
    private function insertField() {
        if(!isset($_POST['database']) || !isset($_POST['table']) || !isset($_POST['index'])) {
            header("Location: index.php");
            return;
        }
        
        $table = $this->ddl->getDatabase($_POST['database'])->getTable($_POST['table']);
        
        $field = new DDLField($table, $_POST['fieldName']);
        
        if($_POST['description'] != '') {
            $field->setDescription($_POST['description']);
        }
        
        if(isset($_POST['required'])) {
            $field->setRequired(true);
        }
        
        if(isset($_POST['unique'])) {
            $field->setUnique(true);
        }
        
        if(isset($_POST['primaryKey'])) {
            $field->setPrimaryKey(true);
        }
        
        if(isset($_POST['foreignKey'])) {
            $field->setForeignTable($_POST['foreignTableName']);
            if($_POST['foreignKeyName'] != '') {
                $field->setForeignKey($_POST['foreignKeyName']);
            }

            if($_POST['foreignObjectName'] != '') {
            	$field->setForeignObject($_POST['foreignObjectName']);
            }
        }
        
        if($_POST['getterFunction'] != '') {
            $field->setGetterFunction($_POST['getterFunction']);
        }
        
        if($_POST['setterFunction'] != '') {
            $field->setSetterFunction($_POST['setterFunction']);
        }
        
        $n = $this->getDataRow($_POST['dataType']);
        if($n < 8 || $n == 25) {
            $data = new DDLNumericData($field);
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
            }
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            if(isset($_POST['unsigned'])) {
                $data->setUnsigned(true);
            }
            if($_POST['numericSize'] != '') {
                $data->setSize($_POST['numericSize']);
            }
            if($_POST['minimumValue'] != '') {
                $data->setMinimumValue($_POST['minimumValue']);
            }
            if($_POST['maximumValue'] != '') {
                $data->setMaximumValue($_POST['maximumValue']);
            }
            if($field->isPrimaryKey()) {
                if(isset($_POST['auto_increment'])) {
                    $field->setAutoIncrement(true);
                }
            }
            $data->setDataType($_POST['dataType']);
        }
        elseif ($n < 18 || $n == 26) {
            $data = new DDLTextData($field);
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
            }
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            if($_POST['textSize'] != '') {
                $data->setSize($_POST['textSize']);
            }
            if($_POST['minimumLength'] != '') {
                $data->setMinimumLength($_POST['minimumLength']);
            }
            if($_POST['maximumLength'] != '') {
                $data->setMaximumLength($_POST['maximumLength']);
            }
            if($_POST['regexp'] != '') {
                $data->setRegexp($_POST['regexp']);
            }
            $data->setType($_POST['restriction']);
            $data->setDataType($_POST['dataType']);
        }
        elseif ($n == 18) {
            $data = new DDLEnumData($field);
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
            }
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            
            if(isset($_POST['isBoolean'])) {
                $data->setBoolean(true);
                $flag = $_POST['binaryFlag'];
                if($flag = 'first') {
                    $f = true;
                } else {
                    $f = false;
                }
                $enumValue = new DDLEnumValue($_POST['enumValue1'], $f);
                $data->addValue($enumValue);
                $enumValue = new DDLEnumValue($_POST['enumValue2'], !$f);
                $data->addValue($enumValue);
            } else {
                preg_match_all("'\'([^\']*(\'\')*)+\''", stripslashes($_POST['enumValues']), $values);
                
                $values = $values[0];

                foreach ($values as $value) {
                    $value = trim($value);
                    $value = substr($value, 1, strlen($value) - 2);
                    $enumValue = new DDLEnumValue($value);
                    $data->addValue($enumValue);
                }
            }
        }
        elseif($n == 19) {
            $data = new DDLSetData($field);
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
            }
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            preg_match_all("'\'([^\']*(\'\')*)+\''", stripslashes($_POST['setValues']), $values);
                
            $values = $values[0];

            foreach ($values as $value) {
                $value = trim($value);
                $value = substr($value, 1, strlen($value) - 2);
                $data->addValue($value);
            }
        }
        else {
            $data = new DDLTimeData($field);
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
            }
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            if(isset($_POST['auto'])) {
                $data->setAuto(true);
            }
            $data->setType($_POST['dataType']);
        }
        
        $field->setData($data);
        
        $table->addFieldAtIndex($_POST['index'], $field);
        
        $doc = new DOMDocument();
        $root = $doc->createElement('ddl');
        $root->setAttribute('version', '1.0');
        $root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:noNamespaceSchemaLocation', 'http://www.myobjects.org/reference/ddl.xsd');
        $root = $doc->appendChild($root);
        
        foreach ($this->ddl->getDatabases() as $database) {
            $database->createXmlElement($doc);
        }
        
        $doc->save(MYOBJECTS_ROOT . '/webclient/tmp/' . $_SESSION['workFolder'] . '/schema.xml');
        if(isset($_POST['insertAndNew'])) {
            $n = count($table->getFields());
            header("Location: index.php?addField&database=".$_POST['database']."&table=".$_POST['table']. "&index=".$n);
        } else {
            header("Location: index.php?editTable&database=".$_POST['database']."&table=".$_POST['table']);
        }
    }
}
?>