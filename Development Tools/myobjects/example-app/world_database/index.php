<?PHP
/**
* WorldExample
*
* Copyright (c) 2004 Erdinc Yilmazel
*
* Example application for MyObjects Object Persistence Library
*
* MyObjects Copyright 2004 Erdinc Yilmazel <erdinc@yilmazel.com>
* http://www.myobjects.org
* 
* @version 1.0
* @author Erdinc Yilmazel
* @package WorldExample
*/

require_once('MyObjectsSettings.php');
require_once(MyObjectsRuntimePath . '/Base.php');

$city = new City();
try {
    $city->loadArray(array('Name'=>'Erdinc', 'Population'=> -34));
} catch (LoadArrayException $ex) {
    var_dump($ex->getInvalidProperties());
}
?>