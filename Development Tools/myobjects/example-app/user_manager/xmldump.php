<?php
/**
* User Manager
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
* @package UserManagerExample
*/

require_once('MyObjectsSettings.php');
require_once(MyObjectsRuntimePath . '/Base.php');

session_start();

if(isset($_SESSION['userId'])) {
    try {
        $loggedUser = User::get($_SESSION['userId']);
    } catch (ObjectNotFoundException $e) {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}

$users = DbModel::get('Select * From users');
$doc = XmlModel::createDoc();

foreach ($users as $user) {
    XmlModel::store($user, $doc);
}

$doc->save('tmp.xml');
$fp = fopen('tmp.xml', 'rb');
header("Cache-Control: ");// leave blank to avoid IE errors
header("Pragma: ");// leave blank to avoid IE errors
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"users.xml\"");
header("Content-length:".(string)(filesize('tmp.xml')));
fpassthru($fp);
?>