#!/usr/bin/php -q
<?php
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// | The file was further modified to accomodate BackEnd specific changes.|
// | In particular, the config file contains variables; and some          |
// | alterations of the generated files are required. Note that the       |
// | generated be7.ini is still invalid, and should be diffmerged by hand |
// | after regeneration to allow for BE_timestamps; also note that you    |
// | have to specify yourself if one of your classes extends              |
// | BE_LanguageDataObject.                                               |
// +----------------------------------------------------------------------+
// | Author:  Alan Knowles <alan@akbkhome.com>
// | Modified by: Marc-Antoine Parent <maparent@acm.org>
// +----------------------------------------------------------------------+
//
// $Id: createTables.php,v 1.1 2005/03/23 21:22:55 maparent Exp $
//

$pwd = getcwd();
chdir('../../public_html');
require('./config.php');
chdir($pwd);

define('DB_DATAOBJECT_NO_OVERLOAD',true);

require_once 'DB/DataObject/Generator.php';

class BE_Generator extends DB_DataObject_Generator {
    function _generateClassTable($input = '') {
        global $_PSL;
        $output = parent::_generateClassTable($input);
        $matches = array();
        if (!preg_match("/\bclass (\w+) extends (\w+)\b/", $output, $matches)) {
            return $output;
        }
        $classname = $matches[1];
        $parentClass = $matches[2];
        $parentClassLoc = preg_quote($_PSL['classdir'] . '/' . $parentClass);
        print $parentClassLoc."\n";
        $output = preg_replace("|require_once '$parentClassLoc.class';|","global \$_PSL;\nrequire_once(\$_PSL['classdir'] . '/$parentClass.class');",$output);
        if ($parentClass == "BE_LanguageDataObject") {
            $output = preg_replace("/DB_DataObject::staticGet/","BE_LanguageDataObject::staticGet",$output);
        }
        return $output;
    }
}

global $_PSL;
$config = parse_ini_file($_PSL['classdir'] . '/DO/BE_DO.ini', true);

if (!$config) {
    PEAR::raiseError("\nERROR: could not read ini file\n\n", null, PEAR_ERROR_DIE);
    exit;
}

$db_name = $_PSL['DB_Database'];
$config['DB_DataObject']["ini_".$db_name] = $_PSL['classdir'] . '/DO/be7.ini';
foreach($config as $class=>$values) {
    foreach ($values as $k=>$v) {
        //hackery because of the names
        @eval("\$v = \"$v\";");
        $values[$k] = $v;
    }
    $options = &PEAR::getStaticProperty($class,'options');
    $options = $values;
}


DB_DataObject::debugLevel(1);
$generator = new BE_Generator;
$generator->start();

?>