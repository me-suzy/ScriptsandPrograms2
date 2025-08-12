<?
function authenticate($uid='') {
	global $pagetitle, $wf;
	if (isset($_SESSION["uid"]) && $_SESSION["type"]=='frontend'){
		loggedin_workflow();
	}
	elseif (new_visit()){
		loggedin_workflow();
	}
}

function logout($uid='') {
	global $uid;
	$uid='';
	$GLOBALS[uid]='';
	$_SESSION[uid]='';
	session_unset();
	session_destroy();
	setcookie(session_name(),"",0,"/");
}

function new_visit() {
	global $annotation;
	if ($_POST[login]<>'' && $_POST[login_password]<>''){
		$sql = "SELECT uid, visit FROM webuser WHERE login = '".addslashes($_POST[login])."' and user_password = '".addslashes($_POST[login_password])."'";
		$res = mysql_query($sql) or die(mysql_error());
		if ($num = mysql_num_rows($res)) {
			$GLOBALS["uid"] = mysql_result($res,0,'uid') ;
			$visit = mysql_result($res,0,'visit') ;
			$visit++;
			$sql = "UPDATE webuser SET visit='" . $visit . "', history=now() WHERE uid='" . intval($GLOBALS["uid"]) . "'";
			session_register("uid");
			session_register("type");
			$_SESSION[type]='frontend';
			$_SESSION[uid]=$GLOBALS["uid"];
			$result = mysql_query($sql) or die(mysql_error());
			return true ;
		}
		elseif ($_POST[login]<>'' || $_POST[login_password]<>''){
			$annotation="Username or password incorrect.";
		}
		//logout();
		login_screen();
		return false ;
	}
	elseif ($_COOKIE[session_name()]){
		$annotation="Enter your username and password.";
		//logout();
		login_screen();
		return false ;
	}
	else {
		if ($_POST[login]<>'' || $_POST[login_password]<>''){
			$annotation="Username or password incorrect.";
		}
		//logout();
		login_screen();
		return false ;
	}
}