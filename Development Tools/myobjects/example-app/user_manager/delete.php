<?PHP
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

// Validate session
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

// Create a User object with the given user id.
$user = new User();
// We shouldn't use $user->setUserid() method here because it thows
// an exception. (The object with the specified user id will probably exist)
$user->userid = $_GET['userid'];

// Delete the user
DbModel::delete($user);

// Redirect to home page
header("Location: index.php");
?>