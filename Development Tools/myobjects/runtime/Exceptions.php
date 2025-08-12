<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: Exceptions.php,v 1.3 2004/12/01 13:23:21 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsRuntime
*/


/**
 * Thrown when a parameter of a Mapable object is tried to be set to an invalid value
 * @package MyObjectsRuntime
 */
class InvalidValueException extends Exception {}

/**
 * Thrown when a database connection cannot be establised
 * @package MyObjectsRuntime
 */
class DatabaseConnectionException extends Exception {}

/**
 * Thrown when an invalid query is tried to be maid
 * @package MyObjectsRuntime
 */
class QueryException extends Exception {}

/**
 * Thrown when an invalid argument is passed to a method
 * @package MyObjectsRuntime
 */
class IllegalArgumentException extends Exception {}

/**
 * Thrown when some of the required fields in the Mapable object are not set
 * @package MyObjectsRuntime
 */
class MapableNotValidException extends Exception {}

/**
 * Thrown when some of the required fields in the Mapable object are not set
 * @package MyObjectsRuntime
 */
class UniqueKeyExistsException extends Exception {}

/**
 * Thrown when the requested object was not found
 * @package MyObjectsRuntime
 */
class ObjectNotFoundException extends Exception {}

/**
* Thrown when an object with no primary key field is tried to be updated
* @package MyObjectsRuntime
*/
class NoPrimaryKeyException extends Exception {}

/**
* Thrown when the xml file that the Mapable object maps to is not valid
* @package MyObjectsRuntime
*/
class MapableFileNotValidException extends Exception {}

/**
* Throws when the supplied model to the view object is null
*
* @see View
* @package MyObjectsRuntime
*/
class ModelNotValidException extends Exception {}

/**
* Thrown by the loadArray() methods of generated Mapable classes.
*
* If the array passed to loadArray method has invalid values in it,
* throws this exception.
* @package MyObjectsRuntime
*/
class LoadArrayException extends Exception {
    
    /**
    * Array of the names of invalid properties
    */
    public $ip;
    
    function __construct($message, $ip) {
        parent::__construct($message);
        $this->ip = $ip;
    }
    
    function getInvalidProperties() {
        return $this->ip;
    }
}
?>