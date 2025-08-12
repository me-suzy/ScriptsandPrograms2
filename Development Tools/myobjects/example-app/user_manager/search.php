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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Search</title>
</head>
<body>
<?php
echo "Wellcome, " . $loggedUser->getName() . " [ <a href=\"index.php\">Home</a> ] [ <a href=\"logout.php\">Logout</a> ] [ <a href=\"new.php\">Create New User</a> ] [ <a href=\"xmldump.php\">Dump Database To Xml</a> ]<br/><br/>";
echo "<form name=\"search\" action=\"search.php\" method=\"post\">Search: <input type=\"text\" name=\"username\"/> <input type=\"submit\" value=\"Go\"/></form>";

if(isset($_POST['username'])) {
    $user = new User();
    $user->username = $_POST['username'];
    
    if($result = DbModel::getSimilar($user)) {
        $view = new DefaultMapableView();
        if($result instanceof User) {
            echo $result->getView($view);
            echo "<a href=\"edit.php?userid=".$result->getUserid()."\">Edit</a>";
            echo " <a href=\"delete.php?userid=".$result->getUserid()."\">Delete</a>";
        }
    } else {
        echo "User: " . $_POST['username'] . " not found";
    }
}
?>
</body>
</html>