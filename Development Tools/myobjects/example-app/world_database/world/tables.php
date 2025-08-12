<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version 1.3
* @author Erdinc Yilmazel
* @package WorldExample
*/

/**
* Returns the class name for the table
*
* @param string $tableName The name of table
* @return string Class name for the table
*/
function getClassName($tableName) {
    $tableName = strtolower($tableName);
    switch ($tableName) {
        case 'city':
            return 'City';
        case 'country':
            return 'Country';
        case 'countrylanguage':
            return 'Language';
        default:
            return false;
    }
}
?>