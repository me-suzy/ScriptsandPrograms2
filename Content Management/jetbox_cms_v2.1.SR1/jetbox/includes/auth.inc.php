<?
function authenticate($uid='') {
	global $pagetitle, $_SETTINGS, $order_column;
	//ob_start();
	session_start();
	//header('P3P: CP="'.$_SETTINGS['ADMIN_P3P'].'"'); 
	if (isset($_SESSION["uid"])){
		//follow this path when active sorting in the overview list
		if ($order_column<>'' && $_REQUEST["param"]<>'') {
			login_do_sort();
			exit();
		}
		else{
			jetstream_header($pagetitle);
			loggedin_workflow();
		}
	}
	elseif (new_visit()){
		jetstream_header($pagetitle);
		loggedin_workflow();
	}
}

function logout($uid='') {
	global $uid;
	$uid='';
	$_SESSION["uid"]='';
	destroysession();
}

// Unset all session vars
function destroysession(){
	global $_SETTINGS;
	session_unset();
	session_destroy();
	//header('P3P: CP="'.$_SETTINGS['ADMIN_P3P'].'"'); 	
	setcookie(session_name(),"",0,"/");
} // end func

// Primairy authentication function
// Creates a new session for a new successfull login
function new_visit() {
	global $annotation;
	if (isset($_POST["login"]) && isset($_POST["login_password"]) && $_POST["login"]<>'' && $_POST["login_password"]<>''){
		$sql = "SELECT * FROM user WHERE login = '".addslashes($_POST["login"])."' AND user_password = '".addslashes($_POST["login_password"])."' AND active=1";
		$res = mysql_prefix_query($sql) or die(mysql_error());
		if ($num = mysql_num_rows($res)) {
	
			session_register("browser");
			session_register("maj_ver");
			session_register("min_ver");
			session_register("uid");
			session_register("user_type");
			session_register("email");

			$uid = mysql_result($res,0,'uid') ;
			$visit = mysql_result($res,0,'visit') ;
			$user_type = mysql_result($res,0,'type') ;
			$email = mysql_result($res,0,'email') ;
			$visit++;
			$sql = "UPDATE user SET visit='" . $visit . "', history=now() WHERE uid='" . intval($uid) . "'";
			$result = mysql_prefix_query($sql) or die(mysql_error());

			$sniffer_settings = array('check_cookies'=>$cc, 'default_language'=>$dl, 'allow_masquerading'=>$am);
			$client = new phpSniff($UA,$sniffer_settings);

			$_SESSION["browser"]= $client->property('browser');
			$_SESSION["maj_ver"]= $client->property('maj_ver');
			$_SESSION["min_ver"]= $client->property('min_ver');
			$_SESSION["user_type"]= $user_type;
			$_SESSION["uid"]=$uid;
			$_SESSION["email"]=$email;


			return true;
		}
		elseif ((isset($_POST["login"]) && $_POST["login"]<>'') || (isset($_POST["login_password"]) && $_POST["login_password"]<>'')){
			$annotation="Username or password incorrect.";
		}
		destroysession();
		jetstream_header("Log in", false);
		login_screen();
		return false;
	}
	else {
		if ((isset($_POST["login"]) && $_POST["login"]<>'') || (isset($_POST["login_password"]) && $_POST["login_password"]<>'')){
			$annotation="Username or password incorrect.";
		}
		destroysession();
		jetstream_header("Log in", false);
		login_screen();
		return false;
	}
}