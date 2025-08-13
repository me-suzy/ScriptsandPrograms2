<?

class db_login_info {
	var $db_type = "mysql";    //  database type (DO NOT CHANGE UNLESS YOU KNOW WHAT YOUR DOING)
	var $db_addr = "localhost";  // database ip addr or localhost depending on your config
        var $db_user = "db_user";   // database username
	var $db_pwd = "db_password";  // database password
	var $db_name = "db_name";  // database name
}

$db_login_info = new db_login_info;

?>
