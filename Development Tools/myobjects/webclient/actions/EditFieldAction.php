<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: EditFieldAction.php,v 1.12 2004/11/15 22:30:18 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class EditFieldAction extends Action {
    
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
            $this->updateField();
            return;
        }
        
        if(!isset($_GET['database']) || !isset($_GET['table']) || !isset($_GET['field'])) {
            header("Location: index.php");
            return;
        }
        
        $field = $this->ddl->getDatabase($_GET['database'])->getTable($_GET['table'])->getField($_GET['field']);
        if(!$field) {
            header("Location: index.php");
            return;
        }
        $data = $field->getData();
        
        $dataType = $this->getDataRow($data->getDataType());
        
        $template = new Template();
        
        $template->assign('toolBar', WebClient::getToolBar()->__toString());
        $template->assign('databaseName', $_GET['database']);
        $template->assign('tableName', $_GET['table']);
        $template->assign('fieldName', $field->getName());
        $template->assign('dataType', $dataType);
        $template->assign('description', $field->getDescription() ? stripslashes($field->getDescription()) : '');
        $template->assign('required', $field->isRequired() ? 'checked="checked"' : '');
        $template->assign('auto_increment', $field->isAutoIncremented() ? 'checked="checked"' : '');
        $template->assign('primaryKey', $field->isPrimaryKey() ? 'checked="checked"' : '');
        $template->assign('unique', $field->isUnique() ? 'checked="checked"' : '');
        $template->assign('foreignKey', $field->isForeignKey() ? 'checked="checked"' : '');
        $template->assign('foreignTableName', $field->isForeignKey() ? $field->getForeignTable() : '');
        $template->assign('foreignKeyName', $field->isForeignKey() ? $field->getForeignKey() : '');
        $template->assign('foreignObjectName', $field->isForeignKey() ? $field->getForeignObject() : '');
        $template->assign('getterFunction', $field->getGetterFunction() ? $field->getGetterFunction() : '');
        $template->assign('setterFunction', $field->getSetterFunction() ? $field->getSetterFunction() : '');
        $template->assign('storeFunction', $data->getStoreFunction() ? $data->getStoreFunction() : '');
        $template->assign('validationFunction', $data->getValidationFunction() ? $data->getValidationFunction() : '');
        $template->assign('defaultValue', !is_null($data->getDefaultValue()) ? stripslashes($data->getDefaultValue()) : '');
        $template->assign('restriction', '0');
        
        if($data instanceof DDLNumericData) {
            $this->numericFieldOptions($data, $template);
        }
        elseif ($data instanceof DDLTextData) {
            $this->textFieldOptions($data, $template);
        }
        elseif ($data instanceof DDLEnumData) {
            $this->enumFieldOptions($data, $template);
        }
        elseif ($data instanceof DDLSetData) {
            $this->setFieldOptions($data, $template);
        }
        elseif ($data instanceof DDLTimeData ){
            $this->timeFieldOptions($data, $template);
        }
        
        $this->assignSchema($template);
        $template->display('editfield.html');
    }
    
    private function numericFieldOptions(DDLNumericData $data, Template $template) {
        $template->assign('unsigned', $data->isUnsigned() ? 'checked="checked"' : '');
        $template->assign('numericSize', !is_null($data->getSize()) ? $data->getSize() : '');
        $template->assign('minimumValue', !is_null($data->getMinimumValue()) ? $data->getMinimumValue() : '');
        $template->assign('maximumValue', !is_null($data->getMaximumValue()) ? $data->getMaximumValue() : '');
    }
    
    private function textFieldOptions(DDLTextData $data, Template $template) {

        switch ($data->getType()) {
            case 'date' : $template->assign('restriction', '1');
            break;
            case 'time' : $template->assign('restriction', '2');
            break;
            case 'datetime' : $template->assign('restriction', '3');
            break;
            case 'year' : $template->assign('restriction', '4');
            break;
            case 'email' : $template->assign('restriction', '5');
            break;
            case 'cleanText' : $template->assign('restriction', '6');
            break;
            case 'word' : $template->assign('restriction', '7');
            break;
            case 'alpha' : $template->assign('restriction', '8');
            break;
            case 'numeric' : $template->assign('restriction', '9');
            break;
            default : $template->assign('restriction', '0');
        }
        
        $template->assign('textSize', $data->getSize() ? $data->getSize() : '');
        $template->assign('minimumLength', !is_null($data->getMinimumLength()) ? $data->getMinimumLength() : '');
        $template->assign('maximumLength', !is_null($data->getMaximumLength()) ? $data->getMaximumLength() : '');
        $template->assign('regexp', $data->getRegexp() ? $data->getRegexp() : '');
    }
    
    private function enumFieldOptions(DDLEnumData $data, Template $template) {
        $template->assign('enumBoolean', $data->isBoolean() ? 'checked="checked"' : '');
        if($data->isBoolean()) {
            $template->assign('enumValues', '');
            $values = $data->getValues();
            $template->assign('enumValue1', "'" . stripslashes($values[0]->getText()) . "'");
            $template->assign('enumValue2', "'" . stripslashes($values[1]->getText()) . "'");
            $template->assign('firstChecked', $values[0]->getFlag() ? 'checked="checked"' : '');
            $template->assign('secondChecked', $values[1]->getFlag() ? 'checked="checked"' : '');
        } else {
            $str = '';
            $first = true;
            foreach ($data->getValues() as $enumValue) {
                if(!$first) {
                    $str .= ', ';
                }
                $first = false;
                $str .= "'" . $enumValue->getText() . "'";
            }
            
            $template->assign('enumValues', stripslashes($str));
            $template->assign('enumValue1', '');
            $template->assign('enumValue2', '');
            $template->assign('firstChecked', '');
            $template->assign('secondChecked', '');
        }
    }
    
    private function setFieldOptions(DDLSetData $data, Template $template) {
        $first = true;
        foreach ($data->getValues() as $setValue) {
            if(!$first) {
                $str .= ', ';
            }
            $first = false;
            $str .= "'" . $setValue . "'";
        }
        $template->assign('setValues', stripslashes($str));
    }
    
    private function timeFieldOptions(DDLTimeData $data, Template $template) {
        $template->assign('timeAuto', $data->isAuto() ? 'checked="checked"' : '');
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
    
    private function updateField() {
        if(!isset($_POST['database']) || !isset($_POST['table']) || !isset($_POST['field'])) {
            header("Location: index.php");
            return;
        }
        
        $oldField = $this->ddl->getDatabase($_POST['database'])->getTable($_POST['table'])->getField($_POST['field']);
        if(!$oldField) {
            header("Location: index.php");
            return;
        }
        
        $field = new DDLField($oldField->getParent(), $_POST['fieldName']);
        
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
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
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
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
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
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
            }
            if(isset($_POST['isBoolean'])) {
                $data->setBoolean(true);
                $flag = $_POST['binaryFlag'];
                if($flag = 'first') {
                    $f = true;
                } else {
                    $f = false;
                }
                
                $_POST['enumValue1'] = stripslashes($_POST['enumValue1']);
                $_POST['enumValue2'] = stripslashes($_POST['enumValue2']);
                
                $enumValue = new DDLEnumValue(substr($_POST['enumValue1'], 1, strlen($_POST['enumValue1']) - 2), $f);
                $data->addValue($enumValue);
                $enumValue = new DDLEnumValue(substr($_POST['enumValue2'], 1, strlen($_POST['enumValue2']) - 2), !$f);
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
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
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
            if($_POST['validationFunction'] != '') {
                $data->setValidationFunction($_POST['validationFunction']);
            }
            if($_POST['storeFunction'] != '') {
                $data->setStoreFunction($_POST['storeFunction']);
            }
            if($_POST['defaultValue'] != '') {
                $data->setDefaultValue($_POST['defaultValue']);
            }
            if(isset($_POST['auto'])) {
                $data->setAuto(true);
            }
            $data->setType($_POST['dataType']);
        }
        
        $field->setData($data);
        $i = 0;
        foreach ($field->getParent()->getFields() as $f) {
            if($f === $oldField) break;
            $i++;
        }
        
        $field->getParent()->setField($i, $field);
        
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
        header("Location: index.php?editField&database=".$_POST['database']."&table=".$_POST['table']."&field=".$_POST['fieldName']);
    }
}
?>