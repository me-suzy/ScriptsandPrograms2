<?php

/*

CLASS
-----
USER


PROPERTIES
----------
loggedIn: true or false current user
sysAdmin: true or false current user
owner: current user owns current course true or false
first_name: current user
last_name: current user
email: current user
user_id: current user
usersArray: list of users for this course


METHODS
-------
logout()
register()
remove()
email()
getUserInfo()
setUserInfo()
getUsers() 

*/
class user {

 var $email;
 var $first_name;
 var $last_name;
 var $user_id;
 var $security_code;
 var $ul; 
 
 function user(){
  if(isset($_COOKIE['loggedin'])){
  
  $userdata=unserialize(stripslashes($_COOKIE['loggedin']));
  $this->user_id = $userdata['user_id'];
  $this->email = $userdata['email'];
  $this->first_name = $userdata['first_name'];
  $this->last_name = $userdata['last_name'];
  $this->security_code = $userdata['security_code'];
  return true;
  } else {
  return false;
  }
 }


	function login(){
				
				$security_code = md5(uniqid(rand(), true));	
				$result = $GLOBALS['db']->execQuery("select user_id,first_name,last_name,email from users where email = '" . strtolower(trim(urldecode($_POST['email']))) . "' and pword = '" . strtolower(trim(urldecode($_POST['password']))) . "'");
				if(mysql_num_rows($result) > 0){
				//log'em in!
					while ($myrow = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$userdata['user_id'] = addslashes(trim($myrow['user_id']));
					$userdata['first_name'] = addslashes(trim($myrow['first_name']));
					$userdata['last_name'] = addslashes(trim($myrow['last_name']));
					$userdata['email'] = addslashes(trim($myrow['email']));
					$userdata['security_code'] = $security_code;
					}
					
					$GLOBALS['db']->execQuery("update users set security_code = '".$userdata['security_code']."' where user_id = " .$userdata['user_id']);
					if(isset($_POST['agree'])){
					setcookie("loggedin", serialize($userdata),time()+60*60*24*120);
					}else{
					setcookie("loggedin", serialize($userdata));
					}
					return "index.php";
	
				}else{
				//not in the database
					$GLOBALS['error'][0] = 'Your email / password combination \ndoes not exist in the database\n\nRe-enter your information or register a new account';
				}					
		}
}

?>