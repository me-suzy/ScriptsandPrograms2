<?



/******** Config Section ********/

$user_passwords = array (
	// The first value is the username, the second the password. 
	// is that users password. Add and remove lines as desired.
	"news" => "demo"
	);

// Designate the name of the "Logged Out" php page.
$logout_page = "logout.php";

// Designate the name of the "Login" php page.
$login_page = "login.php";

// Designate the name of the "Invalid Login Name or Password" php page. enters an 
$invalidlogin_page = "invalidlogin.php";

/******** End Config ********/



if ($action == "logout")
{
	Setcookie("logincookie[pwd]","",time() -86400);
	Setcookie("logincookie[user]","",time() - 86400);
	include($logout_page);
	exit;
}
else if ($action == "login")
{
	if (($loginname == "") || ($password == ""))
	{
		include($invalidlogin_page);
		exit;
	}
	else if (strcmp($user_passwords[$loginname],$password) == 0)
	{
		Setcookie("logincookie[pwd]",$password,time() + 86400);
		Setcookie("logincookie[user]",$loginname,time() + 86400);
	}
	else
	{
		include($invalidlogin_page);
		exit;
	}
}
else
{
	if (($logincookie[pwd] == "") || ($logincookie[user] == ""))
	{
		include($login_page);
		exit;
	}
	else if (strcmp($user_passwords[$logincookie[user]],$logincookie[pwd]) == 0)
	{
		Setcookie("logincookie[pwd]",$logincookie[pwd],time() + 86400);
		Setcookie("logincookie[user]",$logincookie[user],time() + 86400);
	}
	else
	{
		include($invalidlogin_page);
		exit;
	}
}
?>