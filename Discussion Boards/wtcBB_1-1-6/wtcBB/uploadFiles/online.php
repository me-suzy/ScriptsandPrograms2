<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //FRONT END - INDEX\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include a few files
include("./includes/config.php");
include("./includes/functions.php");
include("./global.php");

// hmmm.. we should be able to get away
// with using the forumhome stylesheet
// for who's online... uses same table structure
eval("\$stylesheets_sub = \"".getTemplate("stylesheets_forumhome")."\";");

// if no css file.. get internetl block!
if(!$bboptions['css_in_file']) {
	$stylesheets_sub = filterCss($stylesheets_sub);
	eval("\$internalCss = \"".getTemplate("header_internalCss")."\";");
} else {
	$internalCss = '';
}

// what if disabled?? or no permission!
if(!$bboptions['online_enabled']) {
	doError(
		"The administrator has disabled the viewing of Who's Online.",
		"Error Viewing Who's Online",
		"Who's Online is Disabled"
	);
}

if(!$usergroupinfo[$userinfo['usergroupid']]['can_view_online']) {
	doError(
		"perms",
		"Error Viewing Who's Online"
	);
}

// ok we're good to go!
// deal with sessions
$sessionInclude = doSessions("Viewing Who's Online","none");
include("./includes/sessions.php");

// create nav bar array
$navbarArr = Array(
	"Who's Online" => "#"
);
$navbarText = getNavbarLinks($navbarArr);

// meta
if($_SERVER['QUERY_STRING']) {
	$url = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
} else {
	$url = $_SERVER['PHP_SELF'];
}

$metaRedirect = getMetaRedirect($url,$bboptions['online_refresh']);

// intialize templates
eval("\$header = \"".getTemplate("header")."\";");
eval("\$footer = \"".getTemplate("footer")."\";");

// setup online array
if(!is_array($_GET['online'])) {
	$online = Array (
		"display" => "all",
		"order" => "ASC",
		"perpage" => 20,
		"sortby" => "username",
		"useragent" => 0
	);
} else {
	$online = $_GET['online'];
}

// check to make sure perpage is 1 or greater...
if($online['perpage'] < 1) {
	$online['perpage'] = 1;
}

// get selection
if($online['display'] == "all") {
	$selectedAll = " selected=\"selected\"";
	$selectedMembers = "";
	$selectedGuests = "";
} else if($online['display'] == "members") {
	$selectedAll = "";
	$selectedMembers = " selected=\"selected\"";
	$selectedGuests = "";
} else {
	$selectedAll = "";
	$selectedMembers = "";
	$selectedGuests = " selected=\"selected\"";
}

if($online['order'] == "ASC") {
	$ascSelect = " selected=\"selected\"";
	$descSelect = "";
} else {
	$ascSelect = "";
	$descSelect = " selected=\"selected\"";
}

if($online['useragent'] == 0) {
	$userAgentSelect1 = " selected=\"selected\"";
	$userAgentSelect2 = "";
} else {
	$userAgentSelect1 = "";
	$userAgentSelect2 = " selected=\"selected\"";
}

// record info
$recordOnline = $bboptions['record_num'];
$recordDate = processDate($bboptions['date_formatted'],$bboptions['record_date']);
$recordTime = processDate($bboptions['date_time_format'],$bboptions['record_date']);

$totalMembers = 0;
$totalGuests = 0;

foreach($sessArr as $sessid => $session) {
	if($session['userid']) {
		$totalMembers++;
	} else {
		$totalGuests++;
	}
}

// get total users
$totalUsers = $totalMembers + $totalGuests;

$page = $_REQUEST['page'];

if(!$page) {
	$page = 1;
}

// get our starting point..
if($page == 1) {
	$startingPoint = 0;
	$endingPoint = $online['perpage'];
} else {
	$endingPoint = $page * $online['perpage'];
	$startingPoint = $endingPoint - $online['perpage'];
}

if($online['display'] == "all") {
	$getUsers = query("SELECT * FROM sessions LEFT JOIN user_info ON sessions.userid = user_info.userid WHERE sessions.userid != 0 ORDER BY sessions.".$online['sortby']." ".$online['order']);

	$getGuests = query("SELECT * FROM sessions WHERE userid = 0 ORDER BY ".$online['sortby']." ".$online['order']);

	$totalSelected = mysql_num_rows($getUsers) + mysql_num_rows($getGuests);
} else if($online['display'] == "members") {
	$getUsers = query("SELECT * FROM sessions LEFT JOIN user_info ON sessions.userid = user_info.userid WHERE sessions.userid != 0 ORDER BY sessions.".$online['sortby']." ".$online['order']);

	$totalSelected = mysql_num_rows($getUsers);
} else {
	$getGuests = query("SELECT * FROM sessions WHERE userid = 0 ORDER BY ".$online['sortby']." ".$online['order']);

	$totalSelected = mysql_num_rows($getGuests);
}

// set counter
$onlinemisc['counter2'] = 0;

if($getUsers AND mysql_num_rows($getUsers)) {
	while($onlineinfo1 = mysql_fetch_array($getUsers)) {
		$onlineuserinfo[$onlineinfo1['sessionid']] = $onlineinfo1;
	}
}

if($getGuests AND mysql_num_rows($getGuests)) {
	while($onlineinfo2 = mysql_fetch_array($getGuests)) {
		$onlineguestinfo[$onlineinfo2['sessionid']] = $onlineinfo2;
	}
}

// set colspan to default of 4
$colspan = 4;

// detailed info?
if($usergroupinfo[$userinfo['usergroupid']]['can_view_online_details']) {
	eval("\$detailedLocation_header = \"".getTemplate("whosonline_detailed_header")."\";");
	eval("\$detailedLocation = \"".getTemplate("whosonline_detailed")."\";");
	$colspan++;
}

// ip address?
if($usergroupinfo[$userinfo['usergroupid']]['can_view_online_ip']) {
	eval("\$ipAddress_header = \"".getTemplate("whosonline_ipaddress_header")."\";");
	eval("\$ipAddress = \"".getTemplate("whosonline_ipaddress")."\";");
	$colspan++;
}

// get class... (first and/or second)
if(!$usergroupinfo[$userinfo['usergroupid']]['can_view_online_details'] AND $usergroupinfo[$userinfo['usergroupid']]['can_view_online_ip']) {
	$classFirSec = "first";
} else {
	$classFirSec = "second";
}

// make sure we display users
if(is_array($onlineuserinfo) AND ($online['display'] == "all" OR $online['display'] == "members")) {
	// loop through the user array first...
	foreach($onlineuserinfo as $key => $value) {
		$onlinemisc['counter2']++;

		// make sure we're not above our limit...
		if($startingPoint >= $onlinemisc['counter2']) {
			// continue!
			continue;
		}

		// we can break here
		if($endingPoint < $onlinemisc['counter2']) {
			// break!
			break;
		}

		// only if we have proper permissions
		if(!$value['invisible'] OR $usergroupinfo[$userinfo['usergroupid']]['see_invisible'] OR $userinfo['userid'] == $value['userid']) {			
			// if invisible add a little something...
			if($value['invisible']) {
				$invisibleMark = "*";
			}

			else {
				$invisibleMark = '';
			}

			// do we want to resolve ip?
			if($bboptions['online_resolveIP']) {
				// use "@" so errors don't spit out if we get one O_o
				$onlineuserinfo[$key]['ip_address'] = @gethostbyaddr($onlineuserinfo[$key]['ip_address']);
			}

			// define variables
			$action5 = $onlineuserinfo[$key]['action'];
			$title5 = $onlineuserinfo[$key]['title'];
			$location5 = $onlineuserinfo[$key]['location'];
			$ipAddress5 = $onlineuserinfo[$key]['ip_address'];
			if($online['useragent'] == 1) $userAgent5 = $onlineuserinfo[$key]['user_agent'];
			$userid5 = $onlineuserinfo[$key]['userid'];
			$email5 = $onlineuserinfo[$key]['email'];
			$username5 = $onlineuserinfo[$key]['username'];

			// format time
			$lastactive = processDate($bboptions['date_time_format'],$onlineuserinfo[$key]['last_activity']);

			// get colored username
			$onlineUsername = getHTMLUsername($value);

			// detailed info?
			if($usergroupinfo[$userinfo['usergroupid']]['can_view_online_details'] == 1) {
				if($onlineuserinfo[$key]['title'] == "none") {
					eval("\$detailedLocation = \"".getTemplate("whosonline_detailed_none")."\";");
				} else {
					eval("\$detailedLocation = \"".getTemplate("whosonline_detailed_user")."\";");
				}
			}

			// do we want to use the separator?
			$userSep_1 = false;
			$userSep_2 = false;

			// display email?
			if($value['receive_emails'] AND $bboptions['enable_user_email']) {
				eval("\$sendEmail = \"".getTemplate("whosonline_userrow_sendEmail")."\";");
				$userSep_1 = true;
			} else {
				$sendEmail = "";
			}

			// pm?
			if($value['use_pm'] AND $usergroupinfo[$value['usergroupid']]['personal_max_messages'] AND $bboptions['personal_enabled']) {
				eval("\$sendPM = \"".getTemplate("whosonline_userrow_sendPM")."\";");
				$userSep_2 = true;
			} else {
				$sendPM = "";
			}

			if($userSep_1 AND $userSep_2) {
				eval("\$separator = \"".getTemplate("whosonline_userrow_separator")."\";");
			} else {
				$separator = "";
			}

			// ip address? get user agent too
			if($usergroupinfo[$userinfo['usergroupid']]['can_view_online_ip']) {
				eval("\$ipAddress = \"".getTemplate("whosonline_ipaddress_user")."\";");
			}

			// get bit
			eval("\$onlineUsers .= \"".getTemplate("whosonline_userrows")."\";");
		}
	}
}

// make sure we display guests...
if(is_array($onlineguestinfo) AND $bboptions['online_guest'] AND ($online['display'] == "all" OR $online['display'] == "guests")) {
	// robot detection?
	if($bboptions['robots']) {
		$robots = preg_split('#[\s]{2,}#isU',$bboptions['robots']);
		$robots_desc = preg_split('#[\s]{2,}#isU',$bboptions['robots_desc']);
	}

	// now loop through guest array
	foreach($onlineguestinfo as $key => $value) {
		$onlinemisc['counter2']++;

		// make sure we're not above our limit...
		if($startingPoint >= $onlinemisc['counter2']) {
			// continue!
			continue;
		}

		// we can break here
		if($endingPoint < $onlinemisc['counter2']) {
			// break!
			break;
		}

		// nothin to get really... just give the username "Guest" and userid "0".. and usergroupid of 1 (Guests)
		$moreinfo['username'] = "Guest";
		$moreinfo['userid'] = 0;
		$moreinfo['usergroupid'] = 1;

		// just in case admin has "Guest" display differently..
		$moreinfo['username'] = getHTMLUsername($moreinfo);

		// do we want to resolve ip?
		if($bboptions['online_resolveIP']) {
			// use "@" so errors don't spit out if we get one O_o
			$onlineguestinfo[$key]['ip_address'] = @gethostbyaddr($onlineguestinfo[$key]['ip_address']);
		}

		// define variables
		$action5 = $onlineguestinfo[$key]['action'];
		$title5 = $onlineguestinfo[$key]['title'];
		$location5 = $onlineguestinfo[$key]['location'];
		$ipAddress5 = $onlineguestinfo[$key]['ip_address'];
		if($online['useragent'] == 1) $userAgent5 = $onlineguestinfo[$key]['user_agent'];
		$userid5 = $onlineguestinfo[$key]['userid'];
		$email5 = $onlineuserinfo[$key]['email'];

		// format time
		$lastactive = processDate($bboptions['date_time_format'],$onlineguestinfo[$key]['last_activity']);

		// detailed info?
		if($usergroupinfo[$userinfo['usergroupid']]['can_view_online_details']) {
			if($onlineguestinfo[$key]['title'] == "none") {
				eval("\$detailedLocation = \"".getTemplate("whosonline_detailed_none")."\";");
			} else {
				eval("\$detailedLocation = \"".getTemplate("whosonline_detailed_guest")."\";");
			}
		}

		// ip address? and user agent...
		if($usergroupinfo[$userinfo['usergroupid']]['can_view_online_ip']) {
			eval("\$ipAddress = \"".getTemplate("whosonline_ipaddress_guest")."\";");
		}

		// find robots?
		if(is_array($robots)) {
			foreach($robots as $pos => $name) {
				if(strpos(strtolower($ipAddress5),strtolower($name)) !== false OR strpos(strtolower($onlineguestinfo[$key]['user_agent']),strtolower($name)) !== false) {
					$moreinfo['username'] = $robots_desc[$pos];
				}
			}
		}

		// get bit
		eval("\$onlineUsers .= \"".getTemplate("whosonline_guestrows")."\";");
	}
}

// set variables for url to sort...
$sortUsername = "online.php?online%5Bsortby%5D=username&online%5Bpage%5D=".$page."&online%5Bdisplay%5D=".$online['display']."&online%5Border%5D=".$online['order']."&online%5Bperpage%5D=".$online['perpage'];

$sortLastActivity = "online.php?online%5Bsortby%5D=last_activity&online%5Bpage%5D=".$page."&online%5Bdisplay%5D=".$online['display']."&online%5Border%5D=".$online['order']."&online%5Bperpage%5D=".$online['perpage'];

$sortAction = "online.php?online%5Bsortby%5D=action&online%5Bpage%5D=".$page."&online%5Bdisplay%5D=".$online['display']."&online%5Border%5D=".$online['order']."&online%5Bperpage%5D=".$online['perpage'];

// get user agent?
if($usergroupinfo[$userinfo['usergroupid']]['can_view_online_ip']) {
	eval("\$selectUserAgent = \"".getTemplate("whosonline_useragent")."\";");
}

// build page links
// get number of pages...
if($totalSelected % $online['perpage'] != 0) {
	$numOfPages = ($totalSelected / $online['perpage']) + 1;
	settype($numOfPages,"integer");
} else {
	$numOfPages = $totalSelected / $online['perpage'];
}

$pagelinks = buildPageLinks($numOfPages,$page);

// get who's online template
eval("\$usersOnline = \"".getTemplate("whosonline")."\";");

// print templates
printTemplate($header);
printTemplate($usersOnline);
printTemplate($footer);

// wrrrrrrrap it up!
wrapUp();

?>