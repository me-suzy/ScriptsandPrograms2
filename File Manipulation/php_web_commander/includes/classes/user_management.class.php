<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : user_management.class.php                   |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   last edit            : 23/08/2004 14:18                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//



class user_management {

	function user_management () {

		
		$this -> error = '';
		if (session_id() == '') {

			session_start();
		}

		$this ->sessid = session_id();
		
		$this -> link  = @mysql_connect(PHPWC_DB_HOST, PHPWC_DB_USER, PHPWC_DB_PASS);
		if (!$this -> link) {

			$this -> acc_lev = 0;
			die('Could not connect to database !');
		}
		$this -> db_sel = @mysql_select_db(PHPWC_DB_NAME);
		if (!$this -> db_sel) {

			$this -> acc_lev = 0;
			die('Could not select database !');
		}
	}
	
	function login ($username, $password) {

		$sql_query  = 'SELECT * FROM `phpwc_users` WHERE 1 AND `username` = \'' . $username . '\' AND `password` = \'' . $password . '\'  ';
		$sql_result = mysql_query($sql_query);
		if (mysql_num_rows($sql_result) != 1) {

			$this -> acc_lev = 0;
			user_management::login_form();
			
		} else {

			$user_data = mysql_fetch_row($sql_result);
			$_SESSION['phpwc_files_user'] = $username;
			$_SESSION['phpwc_files_pass'] = $password;
			$this -> acc_lev = $user_data[3];
		}
	}
	
	function login_form() {

		global $template;
		$template -> set_file('body', 'login.tpl');
		$template -> set_var('ACTION', '');
		$template -> parse('out', 'body');
		$template -> p('out');
		die();
	}
}



?>