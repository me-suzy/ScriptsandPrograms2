<?
	/*
	Silentum Boards v1.4.3
	login_logout.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");

	if($page == "login") {

	$displaypage = 1;
	$error = "";
	$write = "";

	if($user_logged_in == 1) {
	$logging = explode(',',$config['record_options']);
	if(in_array(3,$logging)) {
	record("3","%1: Attempted A Double Login [IP: %2]");
	}
	header("Location: index.php");
	exit;
	}

	if($method == "verify") {
	$usernumber = myfile("objects/id_users.txt"); $usernumber = $usernumber[0] + 1;
	$login_pw = mycrypt(mysslashes($login_pw));
	$login_name = mutate($login_name);
	$login_name2 = strtolower($login_name);
	for($i = 1; $i < $usernumber; $i++) {
	if($act_user = myfile("members/$i.txt")) {
	if($login_name2 == strtolower(killnl($act_user[0]))) {
	if(killnl($act_user[4]) == 5) {
	$error = "Invalid user name and/or password.";
	$logging = explode(',',$config['record_options']);
	if(in_array(3,$logging)) {
	record("3","Login With User ID ".killnl($act_user[1])." - ".killnl($act_user[0])." Failed. Reason: User Deleted [IP: %2]");
	}
	break;
	}
	elseif($login_pw != killnl($act_user[2])) {
	$error = "Invalid user name and/or password.";
	$logging = explode(',',$config['record_options']);
	if(in_array(3,$logging)) {
	record("3","Login With User ID ".killnl($act_user[1])." - ".killnl($act_user[0])." Failed. Reason: Invalid Password");
	}
	break;
	}
	else {
	$act_user_id = killnl($act_user[1]);
	$session_user_pw = $login_pw; $session_user_id = $act_user_id;
	session_register("session_user_pw","session_user_id");
	if($stayli == "yes") setcookie("cookie_xbbuser",$act_user_id."\t$login_pw",time()+(3600*24*365),'/');
	else setcookie("cookie_xbbuser",$act_user_id."\t$login_pw",0,'/');
	$displaypage = 0;

	if($config['show_online_users'] == 1) {
	$online_file = myfile("objects/online_users.txt"); $online_file_size = sizeof($online_file);
	for($j = 0; $j < $online_file_size; $j++) {
	$currentr_online = myexplode($online_file[$j]);
	if($currentr_online[1] == $session_online) {
	$write = "ok"; $online_file[$j] = ""; $do_online = "no";
	break;
	}
	}

	if($write == "ok") myfwrite("objects/online_users.txt",$online_file,"w");
	if($beonline == "yes") $session_online = "no";
	else $session_online = $act_user_id;
	session_register("session_online");
	}

	$logging = explode(',',$config['record_options']);
	if(in_array(7,$logging)) {
	record("7","User ID ".$act_user_id." - ".killnl($act_user[0]).": Logged In [IP: %2]");
	}

	if(killnl($act_user[8]) == 1) header("Location: index.php?page=profile&amp;method=edit");
	else {
	if(substr($silentumwhere,0,5) == "index") {
	if($silentumwhere == "index.php") $goto = "index.php";
	else $goto = $silentumwhere."";
	setcookie("silentumwhere","");
	}
	else $goto = "index.php";

	header("Location: $goto");
	}
	exit;
	}
	break;
	}
	}
	}
	if($error == "") {
	$error = "Invalid user name and/or password.";
	$logging = explode(',',$config['record_options']);
	if(in_array(3,$logging)) {
	record("3","Session Timed Out or Login Button Clicked [IP: %2]");
	}
	}
	}

	if($displaypage == 1) {
	include("board_top.php");
	echo navigation("Login");
?>

<form action="index.php?page=login&amp;method=verify" method="post">
<object>
<table cellspacing="<?=$cellspacing?>" class="table" style="width: <?=$twidth?>">
	<tr>
		<td class="heading1" colspan="2" style="text-align: left"><span class="heading">Login</span></td>
	</tr>
<?
	if($error != "") echo "	<tr>
		<td class=\"error\" colspan=\"2\" style=\"text-align: left\"><span class=\"heading\">Error: $error</span></td>
	</tr>
"; ?>
	<tr>
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><strong>User Name</strong></span></td>
		<td class="one" style="text-align: left; width: 80%"><input class="textbox" maxlength="20" name="login_name" size="20" tabindex="1" type="text" value="<?=$login_name?>" /></td>
	</tr>
	<tr>
		<td class="two" style="text-align: left; width: 20%"><span class="normal"><strong>Password</strong></span></td>
		<td class="two" style="text-align: left; width: 80%"><input class="textbox" maxlength="16" name="login_pw" size="20" tabindex="2" type="password" /></td>
	</tr>
	<tr>
		<td class="one" style="text-align: left; width: 20%"><span class="normal"><strong>Automatic Login</strong> <acronym title="This will keep you logged in until your cookies are cleared.">(?)</acronym></span></td>
		<td class="one" style="text-align: left; width: 80%"><select class="textbox" name="stayli" tabindex="3"><option value="yes">Yes</option><option value="no">No</option></select></td>
	</tr>
</table>
</object><br />
<object>
<table cellspacing="<?=$cellspacing?>" style="margin-left: auto; margin-right: auto; text-align: center; width: <?=$twidth?>">
	<tr>
		<td><input class="button" tabindex="4" type="submit" value="Login" /></td>
	</tr>
</table>
</object>
</form>
<?
	}
	}
	if($page == "logout") {

	$write = "";

	if($user_logged_in == 1) {

	$logging = explode(',',$config['record_options']);
	if(in_array(7,$logging)) {
	record("7","%1: Logged Out [IP: %2]");
	}

	setcookie("cookie_xbbuser",'',time()-1000,'/');

	session_unregister("session_user_pw"); session_unregister("session_user_id");

	if($config['show_online_users'] == 1) {
	$online_file = myfile("objects/online_users.txt"); $online_file_size = sizeof($online_file);
	for($i = 0; $i < $online_file_size; $i++) {
	$currentr_online = myexplode($online_file[$i]);
	if($currentr_online[1] == $special_id) {
	$write = "ok"; $online_file[$i] = "";
	break;
	}
	}
	if($write == "ok") myfwrite("objects/online_users.txt",$online_file,"w");
	session_unregister("session_online");
	}
	}

	header("Location: index.php");
	}
?>