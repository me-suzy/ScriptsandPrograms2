<?
	/*
	Silentum Boards v1.4.3
	administrator_actions.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("function_list.php");
	require_once("settings.php");
	require_once("permission.php");
	administrator();

	if($user_logged_in != 1 || $user_data['status'] != 1) {
	$logging = explode(',',$config['record_options']);
	if(in_array(2,$logging)) {
	record("2","%1: Control Panel Access Attempt [IP: %2]");
	}
	header("Location: index.php");
	exit;
	}

	else {

	if($ban == "yes") {
	unsuspend_user($id);
	ban_user($id);
	$reference = trim(mutate($reference)); $notebox = nlbr(trim(mutate($notebox)));
	$new_id = myfile("members/$id.notebox.txt"); $new_id = myexplode($new_id[sizeof($new_id)-1]); $new_id = $new_id[0]+1;
	$timesent = date("Y-F-d / h:i:sa");
	$reference = "Your account has been banned";
	$notebox = "Your account has been banned for violating the Terms of Service. Please review your <a href=\"index.php?page=moderations\">moderations</a> for more information.";
	$towrite = "$new_id\t$reference\t$notebox\t$note_box_id\t$timesent\t1\t1\t1\t\r\n";
	myfwrite("members/$id.notebox.txt",$towrite,"a");
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: User ID $id Banned [IP: %2]");
	}
	header("Location: index.php?page=queue");
	}
	}

	if($user_logged_in != 1 || $user_data['status'] != 1 && $user_data['status'] != 2) {
	record("2","%1: Control Panel Access Attempt [IP: %2]");
	header("Location: index.php");
	exit;
	}

	else {

	if($unsuspend == "yes") {
	unsuspend_user($id);
	}
	}

	if($increase == 1 && $password == "H72kVmal091jGu43") {
	foreach (range(1, 999) as $number) {
	increase_karma($number);
	increase_days_registered($number);
	}
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Karma Distributed [IP: %2]");
	}
	header("Location: index.php?page=top_10");
	}
?>