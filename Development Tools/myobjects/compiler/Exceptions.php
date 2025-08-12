<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: Exceptions.php,v 1.2 2004/11/02 09:39:22 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsCompiler
*/

/**
* Thrown when the DDL file format is not valid
*
* @package MyObjectsCompiler
*/
class DDLFileNotValidException extends Exception {}

/**
* Thrown when the DDL file version is not supported
*
* @package MyObjectsCompiler
*/
class DDLVersionNotSupportedException extends Exception {}

/**
* Thrown when the referenced foreign table does not exist in the ddl file
*
* @package MyObjectsCompiler
*/
class InvalidForeignKeyException extends Exception {
    public $reference;
    public $fieldName;
    
    function __construct($message, $reference, $fieldName) {
        parent::__construct($message);
        $this->reference = $reference;
        $this->fieldName = $fieldName;
    }
}

/**
* Thrown when an enum fields is set boolean but the values of it
* does not contain a boolean flag
*
* @package MyObjectsCompiler
*/
class BooleanFlagNotSetException extends Exception {
    public $fieldName;
    
    function __construct($message, $fieldName) {
        parent::__construct($message);
        $this->fieldName = $fieldName;
    }
}

/**
* Thrown when there is an invalid boolean enum field exists in ddl file
*
* @package MyObjectsCompiler
*/
class InvalidBooleanFieldException extends Exception {}

/**
* Thrown when a compile time error has occured
*
* @package MyObjectsCompiler
*/
class CompileTimeException extends Exception {}
?>