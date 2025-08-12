<?

	#check to see if this script is being run from the command line
	if (php_sapi_name() != 'cli') {
		echo 'Access Denied. Script can only be run from the command line.<BR />';
		exit();
	}


	# script to mail people page update information based
	# on their frequency preference and the time that
	# they last visited the page

	include_once(dirname(dirname(__FILE__))."/init.php");
	global $INCLUDE_PATH;
	include_once($INCLUDE_PATH . "/parameter_set.inc");

	mail_updates();

	function mail_updates(){

		$users_sys = &get_users_system();
		$db        = &$users_sys->get_db();
		$web       = &get_web_system();
		$site      = $web->get_site();

		# a list of users and their details
		
		$users = array();

		# get the users' frequencies
		
		$sql    = "SELECT userid, freq FROM xtra_web_extension_page_update_freq";
		$result = $db->associative_array($sql);
		
		if (!count($result)){
			# no-one has a frequency, so just exit
			
			echo "no frequencies recorded for any users...exiting...";
			exit();
		}
		# build a user frequency list
		$userids = array();

		foreach ($result as $userid => $freq) {
			if ($freq != 0) {
				$users[$userid]['freq'] = $freq;
				$userids[] = $userid;
			}
		}
		unset($result);

		# why do we want to do it this way you ask??
		# this is because mysql has a spack with long IN clause
		# queries, so only get 500 at a time

		$pieces = array_chunk($userids, 500);		
		foreach ($pieces as $piece) {
			# get the users' emails
			$userids = implode(",", $piece);
			$sql     = "SELECT userid, login, email FROM user WHERE userid IN (" . $userids . ") LIMIT 500";

			$result = array();
		
			while($row = $db->associative_array($sql)){
				$result = array_merge($row, $result);
				if(count($row) < 500) break;
			}	
		}
		unset($pieces);

		# build a email and login for each user
		foreach ($result as $userid => $user) {

			# if the user has not supplied their email in the
			# account manager, there is not much we can do, so
			# just skip them...
			
			if($user['email'] != "") {
				$users[$user['userid']]['email'] = $user['email'];
				$users[$user['userid']]['login'] = $user['login'];
			} else {
				unset($users[$user['userid']]);
			}
		}

		# now get the page details for each user

		$sql = "SELECT update_id, userid, pageid, siteid, email_date, last_viewed FROM xtra_web_extension_email_page_update";
		$result = $db->associative_array($sql);

		foreach($result as $r){
			if($r['pageid']){
				$users[$r['userid']]['pages'][$r['pageid']]['siteid']      = $r['siteid'];
				$users[$r['userid']]['pages'][$r['pageid']]['email_date']  = $r['email_date'];
				$users[$r['userid']]['pages'][$r['pageid']]['last_viewed'] = $r['last_viewed'];
			}
		}
		# get the info about each of the page updates
		
		$sql = "SELECT u.update_id, u.pageid, u.siteid, u.userid, u.message, DATE_FORMAT(date, '%Y-%m-%d') as ISOdate, DATE_FORMAT(date, '%W %D %M %Y') as date FROM xtra_web_extension_email_page_update AS e, xtra_web_extension_update_description AS u WHERE e.pageid = u.pageid AND e.email_date = now()";
		
		$result = $db->associative_array($sql);

		$mailed = 0;

		# now, we want to get some details from the parameter_set about the email body, etc, etc
		
		$obj = &$web->get_extension('email_page_update');
		$pset = new Parameter_Set(get_class($obj),"$obj->xtra_path/$obj->codename.pset",$obj->parameters, $obj);
		
		$parameters = array();
		$parameters = $obj->parameters;
		unset($obj);

		# go through each of the users and mail if necessary
		foreach ($users as $userid => $info) {

			$emailheader = str_replace(array('%name%', '%website%'), array($info['login'], $parameters['website_name']), $parameters['first_paragraph']);
			
			$freq  = $info['freq'];  # users frequency
			$email = $info['email']; # users email
			$pages = array();
			$pages = $info['pages'];	# pages user is subscribed to
			$count = 0;
			$emailbody = "";
	
			# check to see if this user has opted to receive updates 'never'
			if($freq == 0) continue;

			foreach ($result as $r) {				
				
				$sub = ($freq > 1) ? $freq . " days ago" : $freq . " day ago";
			
				update_user($userid, $r['pageid'] , $freq);

				# make sure that the update was within the frequency (time) that the user
				# wants emails (older than this have already been mailed (hopefully)

				if(date_compare($r['ISOdate'], $sub)) {
					$count++;
					$href = $web->get_page_url($r['siteid'], $r['pageid']);
					$emailbody .= $r['date'] . " - " . $href . "\n";
					$emailbody .= $r['message'] . "\n\n";

				} # 1
			} # end foreach

			# only mail if there were some pages that 
			# applied to them
	
			if ($count != 0) {
				$emailheader .= " " . $count . " page updates have been recorded in " . $freq . " days.\n\n";
				$emailmsg = $emailheader . $emailbody . $parameters['last_paragraph'];
				mail($email, $parameters['subject'], $emailmsg, "From: " . $parameters['from_address'] . "\r\n");
				$mailed++;
			}
			
		} # end foreach
		
		if($mailed){
			echo "Mailing Successful, " . count($users) . " user(s) mailed.";
		} else {
			echo "No Mail needed to be sent";
		}
	}
	 ###########################################
	# date comparison function 

	function date_compare($date1, $date2){
	
		# now can be used in strtotome, but it returns 
		# a unix timestamp of this exact period in time
		# we as we only want it for today

		if($date2 == "now"){
			$date2 = date("Y-m-d");
		}
		return (date($date1) == date("Y-m-d", strtotime($date2))) ? true : false;
	}

	 ###############################################
	# touch the users details

	function update_user($userid, $pageid, $freq){
		$users_sys = &get_users_system();
		$db        = &$users_sys->get_db();

		$sql = "UPDATE xtra_web_extension_email_page_update SET last_viewed=now(), email_date=date_add(now(), INTERVAL " . $freq . " DAY) WHERE userid=" . $userid . " AND pageid=" . $pageid;
		
		$db->update($sql);
	}

?>