<?
	/*
	Silentum Boards v1.4.3
	settings.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	if(!myfile_exists("objects/settings.txt")) {
	$setfile[0] = $config['board_name'];
	$setfile[1] = $config['board_url'];
	$setfile[2] = $config['default_timezone'];
	$setfile[3] = $config['default_directory'];
	$setfile[4] = $config['default_stylesheet'];
	$setfile[5] = $config['webmasters_email_address'];
	$setfile[6] = $cellspacing;
	$setfile[7] = $twidth;
	$setfile[8] = $config['show_online_users'];
	$setfile[9] = $config['guests_must_enter_a_name'];
	$setfile[10] = $config['online_users_timeout'];
	$setfile[11] = $config['must_be_logged_in'];
	$setfile[12] = $config['enable_categories'];
	$setfile[13] = $config['show_page_execution_time'];
	$setfile[14] = $config['enable_censor'];
	$setfile[15] = $config['announcement_position'];
	$setfile[16] = $config['enable_search'];
	$setfile[17] = $config['topics_per_page'];
	$setfile[18] = $config['enable_top_10'];
	$setfile[19] = $config['posts_per_page'];
	$setfile[20] = $config['show_private_boards'];
	$setfile[21] = $config['offline'];
	$setfile[22] = $config['offline_message'];
	$setfile[23] = $config['record_options'];
	$setfile[24] = $config['enable_registration'];
	$setfile[25] = $config['max_registrations'];
	$setfile[26] = $config['status_host'];
	$setfile[27] = $config['status_closed'];
	$setfile[28] = $config['status_administrator'];
	$setfile[29] = $config['status_suspended'];
	$setfile[30] = $config['status_moderator'];
	$setfile[31] = $config['status_banned'];
	$setfile[32] = $config['use_file_caching'];
	$setfile[33] = $config['use_output_caching'];
	$setfile = implode("\n",array_pad($setfile,200,''))."\n";
	myfwrite("objects/settings.txt",$setfile,'w');
	}
	else {
	$setfile = myfile("objects/settings.txt");	
	$config['board_name'] = chop($setfile[0]);
	$config['board_url'] = chop($setfile[1]);
	$config['default_timezone'] = chop($setfile[2]);
	$config['default_directory'] = chop($setfile[3]);
	$config['default_stylesheet'] = chop($setfile[4]);
	$config['webmasters_email_address'] = chop($setfile[5]);
	$cellspacing = chop($setfile[6]);
	$twidth = chop($setfile[7]);
	$config['show_online_users'] = chop($setfile[8]);
	$config['guests_must_enter_a_name'] = chop($setfile[9]);
	$config['online_users_timeout'] = chop($setfile[10]);
	$config['must_be_logged_in'] = chop($setfile[11]);
	$config['enable_categories'] = chop($setfile[12]);
	$config['show_page_execution_time'] = chop($setfile[13]);
	$config['enable_censor'] = chop($setfile[14]);
	$config['announcement_position'] = chop($setfile[15]);
	$config['enable_search'] = chop($setfile[16]);
	$config['topics_per_page'] = chop($setfile[17]);
	$config['enable_top_10'] = chop($setfile[18]);
	$config['posts_per_page'] = chop($setfile[19]);
	$config['show_private_boards'] = chop($setfile[20]);
	$config['offline'] = chop($setfile[21]);
	$config['offline_message'] = chop($setfile[22]);
	$config['record_options'] = chop($setfile[23]);
	$config['enable_registration'] = chop($setfile[24]);
	$config['max_registrations'] = chop($setfile[25]);
	$config['status_host'] = chop($setfile[26]);
	$config['status_closed'] = chop($setfile[27]);
	$config['status_administrator'] = chop($setfile[28]);
	$config['status_suspended'] = chop($setfile[29]);
	$config['status_moderator'] = chop($setfile[30]);
	$config['status_banned'] = chop($setfile[31]);	
	$config['use_file_caching'] = chop($setfile[32]);
	$config['use_output_caching'] = chop($setfile[33]);
	}
?>