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

/**
* View implementation that will view the user datas in a table row
* This class will view every property of User objects in a different
* table row.
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package UserManagerExample
*/
class BasicUserView extends DefaultMapableView {
    
    /**
    * Overrides the proccessModel function of DefaultMapableView
    * Shows the properties of the user in a table row.
    *
    * @return string The string that will be printed as the view
    */
    protected function processModel() {
        // The Model for this view must be an instance of User
        if(!($this->model instanceof User)) {
            throw new Exception('BasicUserView can be used only with User objects!');
        }
        
        $str = "
        <tr><td><b>Name:</b></td><td>".$this->model->getName()."</td></tr>
        <tr><td><b>User Name:</b></td><td>".$this->model->getUsername()."</td></tr>
        <tr><td><b>Email:</b></td><td>".$this->model->getEmail()."</td></tr>
        <tr><td><b>Creation Date:</b></td><td>".$this->model->getCreationdate()."</td></tr>
        <tr><td><b>Active:</b></td><td>";
        if($this->model->isActive()) $str .= "Yes";
        else $str .= "No";
        $str .= "</td></tr>
        <tr><td><b>Admin:</b></td><td>";
        if($this->model->isAdmin()) $str .= "Yes";
        else $str .= "No";
        $str .= "</td></tr>";
        
        $loggedUser = getLoggedUser();
        
        if($loggedUser instanceof User) {
            $str .= "<tr><td colspan=\"2\">";
            $str .= "<a href=\"edit.php?userid=".$this->model->getUserid()."\">Edit</a>";
            $str .= " | <a href=\"delete.php?userid=".$this->model->getUserid()."\">Delete</a>";
            $str .= " | <a href=\"new.php\">Create New User</a>";
            $str .= "</td></tr>";
        }        
        
        return $str;
    }
}

/**
* Returns the User object for the logged user
*
* @return User logged user or false if there is no valid session
*/
function getLoggedUser() {
    // Check if the user has logged in
    if(isset($_SESSION['userId'])) {
        try {
            $loggedUser = User::get($_SESSION['userId']);
            return $loggedUser;
        } catch (ObjectNotFoundException $e) {
            return false;
        }
    } else {
        return false;
    }
}

// Create a new view instance
$view = new BasicUserView();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User List</title>
</head>
<body>
<?php
$loggedUser = getLoggedUser();
// If there is no valid session display the login form
if(!$loggedUser) {?>
<form name="loginForm" id="loginForm" method="post" action="login.php">
  <table width="100%"  border="0" cellspacing="0" cellpadding="3" style="font-family: Tahoma, Arial; font-size:12px; margin-bottom:10px;">
    <tr>
      <td><b>User Name:</b></td>
      <td><input name="username" type="text" id="username" /></td>
    </tr>
    <tr>
      <td><b>Password:</b></td>
      <td><input name="password" type="password" id="password" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Login" /> 
        <a href="register.php">Register for a new account</a> </td>
    </tr>
  </table>
</form>
<?PHP
} else {
    
    // Display admin menu
    echo "Wellcome, " . $loggedUser->getName() . " [ <a href=\"logout.php\">Logout</a> ] [ <a href=\"new.php\">Create New User</a> ] [ <a href=\"xmldump.php\">Dump Database To Xml</a> ]<br/><br/>";
    echo "<form name=\"search\" action=\"search.php\" method=\"post\">Search: <input type=\"text\" name=\"username\"/> <input type=\"submit\" value=\"Go\"/></form>";
}
?>
<b>User Accounts:</b>
<table width="100%" border="1" cellpadding="3" cellspacing="0" style="font-family: Tahoma, Arial; font-size:12px; margin-bottom:10px;">
<?php
// Get all user objects
$users = DbModel::get('Select * FROM users');

foreach ($users as $user) {
    // View the user object using the BasicUserView implementation
    echo $user->getView($view);
}
?>
</table>
</body>
</html>