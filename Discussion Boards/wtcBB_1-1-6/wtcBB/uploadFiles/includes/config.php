<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ##################### //CONFIG FILE\\ ##################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// the host of your database.. usually "localhost"
// If you are unsure, leave it at "localhost" or contact your host
$host = "localhost";

// database username
// this is NOT your message board username, but the username used to access your database
$db_username = "";

// database password
// this is NOT your message board password, but the password used to access your database
$db_password = "";

// database name
// enter the FULL name of your database here
// you must have a database already created
// the installation will not create the database for you
$db_name = "wtcBB";

// DATABASE ERROR EMAIL ADDRESS
// when a database error occurrs, the error will be sent to this email
// leave it blank if you do not want to receive these emails
$db_email = "";

// SUPER ADMINISTRATOR
// the super admin can limit permissions to regular admins
// enter the userid's of the super administrators.. separate each by a comma
// be careful who you give these rights to!
$super_administrator = "1";

// VIEWING PERMISSIONS FOR ADMIN LOG
// enter the userid's of the user separated by a comma who can view the admin log
$can_view_adminlog = "1";

// PRUNING PERMISSIONS FOR ADMIN LOG
// enter the userid's of the user separated by a comma who can prune the administrator log
$can_prune_adminlog = "1";

// UNEDITABLE USERS
// put the userid of an uneditable user here
// you should use yours for security reasons.. 
// you will also not be able to edit usergroups that have uneditable users in them
// not even you will be able to edit yourself.. you will need to change it here
// separate each userid with a comma
$uneditable_user = "";

?>