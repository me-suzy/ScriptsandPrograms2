<?
	/*
	Silentum Boards v1.4.3
	last_activity.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	$show_post_flag = 0;

	$l_log_in = "";
	$last_log_in = "";
	if(myfile_exists("members/$l_user_id.information.txt")) {
	$luser_log = file("members/$l_user_id.information.txt");
	$luser_size = sizeof($luser_log);
	$month = substr($luser_log[0],0,2);
	$day = substr($luser_log[0],2,2);
	$year = substr($luser_log[0],4,4);
	$hour = substr($luser_log[0],8,2);
	$minute = substr($luser_log[0],10,2);
	$second = substr($luser_log[0],12,4);
	$ip = $luser_log[1];
	$agent = $luser_log[2];
	$uri = $luser_log[3];
	if($month == 1) $month = "January";
	if($month == 2) $month = "February";
	if($month == 3) $month = "March";
	if($month == 4) $month = "April";
	if($month == 5) $month = "May";
	if($month == 6) $month = "June";
	if($month == 7) $month = "July";
	if($month == 8) $month = "August";
	if($month == 9) $month = "September";
	if($month == 10) $month = "October";
	if($month == 11) $month = "November";
	if($month == 12) $month = "December";

	if($hour == "13") $hour = "01";
	if($hour == "14") $hour = "02";
	if($hour == "15") $hour = "03";
	if($hour == "16") $hour = "04";
	if($hour == "17") $hour = "05";
	if($hour == "18") $hour = "06";
	if($hour == "19") $hour = "07";
	if($hour == "20") $hour = "08";
	if($hour == "21") $hour = "09";
	if($hour == "22") $hour = "10";
	if($hour == "23") $hour = "11";
	if($hour == "00") $hour = "12";
	$l_log_in = $year."-".$month."-".$day." / ".$hour.":".$minute.":".$second;$last_log_in=substr($luser_log[0],0,10);
	$ipaddress = $ip;
	$remoteagent = $agent;
	$lastpageaccessed = $uri;
	}

	$ext_path_to_board = ".";
	$show_post_flag = 1;

	$date = date("mdYHisa");
	$luserin = myfile("members/$user_id.information.txt");
	myfwrite("members/$user_id.information.txt",$date."\n".$REMOTE_ADDR."\n".$HTTP_USER_AGENT."\n".$_SERVER['REQUEST_URI'],"w");
?>