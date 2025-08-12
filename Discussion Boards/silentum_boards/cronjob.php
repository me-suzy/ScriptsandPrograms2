<?
	/*
	Silentum Boards v1.4.3
	cronjob.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	$config['datapath'] = ".";

	function killnl($text) {
	return str_replace("\n","",str_replace("\r\n","",$text));
	}

	function nlbr($text) {
	$text = str_replace("\r\n", "<br />", $text);
	return str_replace("\n", "<br />", $text);
	}

	function brnl($text) {
	$text = str_replace("<br />", "\n", $text);
	return str_replace("<br />", "\n", $text);
	}

	function get_user_data($user_id) {
	if(!$user_file = myfile("members/$user_id.txt")) return FALSE;
	if(killnl($user_file[4]) == 5) return FALSE;
	$user_data[0] = killnl($user_file[0]); $user_data['nick'] = &$user_data[0];
	$user_data[1] = killnl($user_file[1]); $user_data['id'] = &$user_data[1];
	$user_data[2] = killnl($user_file[2]); $user_data['pw'] = &$user_data[2];
	$user_data[3] = killnl($user_file[3]); $user_data['email'] = &$user_data[3];
	$user_data[4] = killnl($user_file[4]); $user_data['status'] = &$user_data[4];
	$user_data[5] = killnl($user_file[5]); $user_data['posts'] = &$user_data[5];
	$user_data[6] = killnl($user_file[6]); $user_data['regdate'] = &$user_data[6];
	$user_data[7] = killnl($user_file[7]); $user_data['timezone'] = &$user_data[7];
	$user_data[8] = killnl($user_file[8]); $user_data['signature'] = &$user_data[8];
	$user_data[9] = killnl($user_file[9]); $user_data['aim'] = &$user_data[9];
	$user_data[10] = killnl($user_file[10]); $user_data['displayoptions'] = &$user_data[10];
	$user_data[11] = killnl($user_file[11]); $user_data['title'] = &$user_data[11];
	$user_data[12] = killnl($user_file[12]); $user_data['msn'] = &$user_data[12];
	$user_data[13] = killnl($user_file[13]); $user_data['yahoo'] = &$user_data[13];
	$user_data[14] = killnl($user_file[14]); $user_data['possibleaura'] = &$user_data[14];
	$user_data[15] = killnl($user_file[15]); $user_data['stylesheet'] = &$user_data[15];
	$user_data[16] = killnl($user_file[16]); $user_data['aura'] = &$user_data[16];
	$user_data[17] = killnl($user_file[17]); $user_data['information'] = &$user_data[17];
	$user_data[18] = killnl($user_file[18]); $user_data['icq'] = &$user_data[18];

	$display_options = explode(",",$user_data[10]);
	$user_data['showemail'] = $display_options[0];
	$user_data['showsignatures'] = $display_options[1];
	$user_data['showtitles'] = $display_options[2];
	$user_data['showsmilies'] = $display_options[3];

	return $user_data;
	}

	function increase_aura($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	if($user_data[4] != 4 && $user_data[4] != 6 && $user_data[4] != 7 && $user_data[19] == 1) {
	$user_data[16] = killnl($user_data[16])+1; $user_data[16] .= "\n";
	$user_data[19] = "0"; $user_data[19] .= "\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	}
	}
	}
	}

	function increase_days_registered($user_id) {
	if($user_id != 0) {
	if($user_data = myfile("members/$user_id.txt")) {
	if($user_data[4] != 4 && $user_data[4] != 6 && $user_data[4] != 7) {
	$user_data[14] = killnl($user_data[14])+1; $user_data[14] .= "\n";
	myfwrite("members/$user_id.txt",$user_data,"w");
	}
	}
	}
	}

	function nix() {
	}

	function myfwrite($file,$towrite,$method) {
	global $cache,$file_counter,$config;
	$set_chmod = 0;
	if(!myfile_exists($file)) $set_chmod = 1;
	$fp = fopen($config['datapath'].'/'.$file,$method.'b') or die(record("1","Data Error: Data: $file; method: $method")); flock($fp,LOCK_EX);
	if(!is_array($towrite)) {
	fwrite($fp,$towrite);
	}
	else {
	for($i = 0; $i < sizeof($towrite); $i++) {
	fwrite($fp,$towrite[$i]);
	}
	}
	flock($fp,LOCK_UN); fclose($fp);
	if($set_chmod == 1) {
	@chmod($config['datapath'].'/'.$file,0777);
	}
	if($method == "w") $cache['files'][$file] = $towrite;
	else {
	$file_counter++;
	$cache['files'][$file] = @file($config['datapath'].'/'.$file);
	}
	}

	function record($method,$data) {
	global $config,$user_data,$user_logged_in,$REMOTE_ADDR;
	$x = explode(",",$config['record_options']);
	for($i = 0; $i < sizeof($x); $i++) {
	if($x[$i] == $method) {
	if($user_logged_in == 1) $log_name = "User ID $user_data[id] - $user_data[nick]";
	else $log_name = "User ID 0 - Guest";
	$date1 = date("Y-F-d");
	$data = str_replace("%1",$log_name,$data);
	$data = str_replace("%2",$REMOTE_ADDR,$data);
	$data = date("Y-F-d / h:i:sa")." ".$data."\r\n";
	myfwrite("records/$date1.txt",$data,"a");
	break;
	}
	}
	}

	function myfile($file) {
	global $file_counter,$cache,$config;
	if(!isset($cache['files'][$file]) || $config['use_file_caching'] != 1) {
	$cache['files'][$file] = @file($config['datapath'].'/'.$file);
	$file_counter++;
	}
	return $cache['files'][$file];
	}

	function myfile_exists($file) {
	global $config;
	return file_exists($config['datapath'].'/'.$file);
	}

	foreach (range(1, 999) as $number) {
	increase_aura($number);
	increase_days_registered($number);
	}
	$logging = explode(',',$config['record_options']);
	if(in_array(8,$logging)) {
	record("8","%1: Aura Distributed [IP: %2]");
	}
?>