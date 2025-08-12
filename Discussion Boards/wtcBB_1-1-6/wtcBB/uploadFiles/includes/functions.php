<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ###################### //FUNCTIONS\\ ###################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// connect to MySQL database
$link = mysql_connect($host,$db_username,$db_password) or DIE(mysql_error().mail($db_email,"DATABASE ERROR","There has been a database error because: ".mysql_error()));

mysql_select_db($db_name,$link);

// attempt a quick fix at getting rid of the STUPID 
// magic_quotes_gpc... what a pain!
// if this doesn't work, there is a fallback plan below
set_magic_quotes_runtime(0);

if(get_magic_quotes_gpc() == 1) {
   function strip_magic_slashes($array) {
       if(is_array($array)) {
           return array_map("strip_magic_slashes",$array);
	   } else {
		   return stripslashes($array); 
	   }
   }

	$_POST = array_map("strip_magic_slashes",$_POST);
	$_GET = array_map("strip_magic_slashes",$_GET);
}

// trims all values
function trimArr($array) {
	if(is_array($array)) {
		return array_map("trimArr",$array);
	} else {
		return trim($array);
	}
}

$_POST = array_map("trimArr",$_POST);
$_GET = array_map("trimArr",$_GET);

// this should fix the security leak that allows you 
// to modify SQL queries via URL... anything it could've
// broken, i think i fixed...
$find = Array("'",'"',"`");
$replace = Array("%27","%22","%60");

if(is_array($_GET) AND strpos($_SERVER['PHP_SELF'],"/admin/") === false) {
	foreach($_GET as $key2 => $value2) {
		$_GET[$key2] = str_replace($find,$replace,$_GET[$key2]);
		$$key2 = str_replace($find,$replace,$value2);
	}
}

// unset a few variables.. we don't want any security leaks, now do we???
unset($host,$db_username,$db_password,$host);

// declare query counter
$queryCounter = 0;

// create a function to run a query and assign it to an array all-in-one
// declare this first so we can use it 
function query($statement,$fetch=0) {
	global $db_email, $queryCounter, $userinfo;

	$queryCounter++;

	// you can use this line to debug queries if you want...
	// just uncomment the following line... obviously
	//print($queryCounter." --- ".microtime()." --- ".$statement."<br />");

	// run the query...
	if($db_email) {
		$run = @mysql_query($statement) OR die(mysql_error()."<br /><br />Query: ".$statement.mail($db_email,"DATABASE ERROR","There has been a database error because: ".mysql_error()."\n\nQuery: ".$statement."\n\nScript: ".$_SERVER['PHP_SELF']."\n\nUsername: ".$userinfo['username'],$bboptions['details_contact']));
	} else {
		$run = @mysql_query($statement) OR die(mysql_error()."<br /><br />Query: ".$statement);
	}
	
	// if we want to fetch it.. then we fetch it
	if($fetch) {
		return @mysql_fetch_array($run); // return array
	}

	// return SQL query...
	else {
		return $run; // return mysql_query function
	}
}

// get bboptions
$bboptions = query("SELECT * FROM wtcBBoptions",1);

// get userinfo...styleid...etc...
if($_COOKIE['wtcBB_Userid']) {
	$userinfo = query("
	SELECT user_info.*,
	COUNT(personal_msg.pid) AS newPms,
	COUNT(logged_ips.ipId) AS counting
	FROM user_info 
	LEFT JOIN personal_msg ON
	personal_msg.sentTo = user_info.userid AND
	personal_msg.alert = 1 
	LEFT JOIN logged_ips ON 
	user_info.userid = logged_ips.userid AND
	logged_ips.ip_address = '".$_SERVER['REMOTE_ADDR']."' 
	WHERE user_info.userid = '".addslashes($_COOKIE['wtcBB_Userid'])."' 
	GROUP BY username 
	LIMIT 1
	",1);

	$userinfo['original_style'] = $userinfo['style_id'];

	// if style id is 0.. then use forum default
	if(!$userinfo['style_id']) {
		$userinfo['style_id'] = $bboptions['general_style'];
	}

	// uh oh!!!
	if($userinfo['password'] != $_COOKIE['wtcBB_Password']) {
		unset($userinfo);
	}
} 

if(!is_array($userinfo)) {
	// populate the userinfo array.. 
	$userinfo = Array(
		"username" => "Guest",
		"userid" => "0",
		"date_timezone" => $bboptions['date_timezone'],
		"password" => "",
		"avatar_url" => "none",
		"usergroupid" => 1,
		"lastactivity" => time(),
		"lastpost" => time(),
		"lastvisit" => time(),
		"email" => "",
		"style_id" => $bboptions['general_style'],
		"dst" => $bboptions['date_dst'],
		"view_signature" => 1,
		"view_avatar" => 1,
		"view_attachment" => 1,
		"toolbar" => 1,
		"view_posts" => $bboptions['max_posts'],
		"display_order" => "ASC"
		);
}

// create guestinfo array...
$guestinfo = Array(
		"username" => "Guest",
		"userid" => "0",
		"date_timezone" => $bboptions['date_timezone'],
		"password" => "",
		"avatar_url" => "none",
		"usergroupid" => 1,
		"lastactivity" => time(),
		"lastpost" => time(),
		"lastvisit" => time(),
		"email" => "",
		"style_id" => $bboptions['general_style'],
		"dst" => $bboptions['date_dst'],
		"view_signature" => 1,
		"view_avatar" => 1,
		"view_attachment" => 1,
		"toolbar" => 1,
		"view_posts" => $bboptions['max_posts'],
		"display_order" => "ASC"
		);

// usergroup info
// function to get all usergroups.. and info
function buildUsergroupinfoARR() {
	// get all usergroups
	$allUsergroups = query("SELECT * FROM usergroups ORDER BY name");

	// create array
	$usergroupinfo = Array();

	// loop through to build array
	while($usergroups = mysql_fetch_array($allUsergroups)) {
		$usergroupinfo[$usergroups['usergroupid']] = $usergroups;
	}

	// return the array
	return $usergroupinfo;
}

// create usergroupinfo array
$usergroupinfo = buildUsergroupinfoARR();

// do redirect function.. to redirect to different pages...
function redirect($url) {
	print("<script language=\"javascript\" type=\"text/javascript\">\n");
	print("\tlocation.href = '".addslashes($url)."';\n");
	print("</script>\n");
}

// do redirect with header.. no javascript
function redirectHeader($url) {
	header("Location: ".$url);
	exit;
}

// do redirect with metas..
function getMetaRedirect($url,$length=3) {
	return "<meta http-equiv=\"refresh\" content=\"".$length."; url=".$url."\" />\n";
}

// function to get page loading time
function grabLoadTime() {
	global $startTime;

	// you can find the $startTime instantiated in the global.php...

	// calling this function at the end...
	// split both
	$startTime = explode(" ",$startTime);
	$startMillSec = $startTime[0];
	$startRegSec = date("s",$startTime[1]);

	$endTime = explode(" ",microtime());
	$endMillSec = $endTime[0];
	$endRegSec = date("s",$endTime[1]);

	$startTotal = $startRegSec + $startMillSec;
	$endTotal = $endRegSec + $endMillSec;
	$total = $endTotal - $startTotal;

	// what if end is 0.xxx and start is xx.xxx??
	if($total < 0) {
		$startDiff = 60 - $startTotal;
		$total = $startDiff + $endTotal;
	}

	return $total;
}

// I guess this way works better than running
// One really really big query... The other query
// was killing the time it took to load, and this one has 
// proved to be around 8 times faster...
function buildTemplateArr() {
	global $bboptions, $setStyleID;

	// get all default templates
	$default = query("SELECT defaultid, title, template_php AS template FROM templates_default");

	// get all customized templates
	$cus = query("SELECT defaultid, is_global, title, template_php AS template FROM templates WHERE styleid = '".$setStyleID."' OR is_global = 1");
	
	// create array
	if(!is_array($templateinfo)) {
		$templateinfo = Array();
	}

	// put custom into array...
	if(mysql_num_rows($cus) > 0) {
		while($custom = mysql_fetch_array($cus)) {
			// different if it's global...
			if($custom['is_global'] == 1 OR empty($custom['defaultid']) OR $custom['defaultid'] == null) {
				$templateinfo[$custom['title']] = Array(
					"title" => $custom['title'],
					"template" => $custom['template']
				);
			}

			else {
				$customTemplates[$custom['defaultid']] = $custom;
			}
		}
	}

	// loop through each template
	while($defaultTemplates = mysql_fetch_array($default)) {
		if(is_array($customTemplates[$defaultTemplates['defaultid']])) {
			// put in templateinfo array
			$templateinfo[$customTemplates[$defaultTemplates['defaultid']]['title']] = Array(
				"title" => $customTemplates[$defaultTemplates['defaultid']]['title'],
				"template" => $customTemplates[$defaultTemplates['defaultid']]['template']
				);
		}

		// if we didn't get a custom template...
		else {
			$templateinfo[$defaultTemplates['title']] = Array(
				"title" => $defaultTemplates['title'],
				"template" => $defaultTemplates['template']
				);
		}
	}

	// returns all templates for current style...
	return $templateinfo;
}

// this function will parse template conditionals
function parseConditionals($template) {
	$template = addslashes(addslashes($template));

	// look for matches... go until there are no more!
	while(preg_match("#<if\((.*)\)>(.*)(<else />(.*)</if>|</if>|<else /></if>)#isU",$template,$matches)) {
		if($matches[3] == "<else /></if>" OR $matches[3] == "</if>") {
			$template = str_replace($matches[0],'".(('.stripslashes($matches[1]).') ? ("'.$matches[2].'") : (""))."',$template);
		} else {
			$template = str_replace($matches[0],'".(('.stripslashes($matches[1]).') ? ("'.$matches[2].'") : ("'.$matches[4].'"))."',$template);
		}
	}

	return $template;
}

// this function will attemp to add styleid=x into templates
function addStyleid($text) {
	if(strpos($text,"?") !== false) {
		if(strpos($text,"title=") !== false) {
			$more = split('\\\\\" title=',$text);
			$more[0] = str_replace('\\','',$more[0]);
			$more[1] = str_replace('\\\\"','',$more[1]);
			$return = '<a href="'.$more[0].'&amp;styleid='.$_GET['styleid'].'" title="'.$more[1].'">';
		} else {
			$return = '<a href="'.$text.'&amp;styleid='.$_GET['styleid'].'">';
		}
	} else {
		if(strpos($text,"title=") !== false) {
			$more = split('\\\\\" title=',$text);
			$more[0] = str_replace('\\','',$more[0]);
			$more[1] = str_replace('\\\\"','',$more[1]);
			$return = '<a href="'.$more[0].'?styleid='.$_GET['styleid'].'" title="'.$more[1].'">';
		} else {
			$return = '<a href="'.$text.'?styleid='.$_GET['styleid'].'">';
		}
	}

	return addslashes(addslashes($return));
}

// my previous getTemplate function ran two queries each time it was called
// one to get default.. and one to find customized...
// using the above function which only uses two queries.. we find the template
// in the array we've created.. actually took off 100 queries from the forumhome!
function getTemplate($title) {
	global $templateinfo, $bboptions, $startTime, $queryCounter, $total, $totalQueries, $replacements, $setStyleID, $stylesheets_header, $stylesheets_sub;

	$template = $templateinfo[$title]['template'];

	// add comments to template.. if it is enabled
	if($bboptions['general_templatename'] AND $title != "header" AND $title != "footer" AND $title != "smileybox_more_page" AND strpos($title,"stylesheet") === false AND strpos($title,"mail") === false) {
		// beginning...
		$beginning = "\n<!-- BEGIN ".$title." TEMPLATE -->\n";
		$end = "\n<!-- END ".$title." TEMPLATE -->\n";
		$template = $beginning.$template.$end;
	}

	// what if footer..? get load time and queries ran...
	if($title == "footer") {
		$total = substr(grabLoadTime(),0,8);

		// make a little warning if 40 or over... heh...
		if($queryCounter >= 40) {
			$totalQueries = "<strong style=\"color: #bb0000;\">".$queryCounter."</strong>";
		} else {
			$totalQueries = $queryCounter;
		}
	}

	// fix the PHPSESSID &
	$template = preg_replace("|&PHPSESSID|","&amp;PHPSESSID",$template);

	// do replacements
	if(strpos($_SERVER['HTTP_HOST'],"webtrickscentral.com") !== false AND strpos($_SERVER['PHP_SELF'],"forums/") === false) {
		$template = $template;
	}

	else {
		$template = replaceReplacements($template);
	}

	// try to add styleid=x in links...
	if($_GET['styleid']) {
		$template = preg_replace("%<a href=\\\\\"(.*)\\\\\">%ieU","addStyleid('$1')",$template);
	}

	// returns the template
	return $template;
}

// create function to print the template
function printTemplate($template) {
	print(stripslashes($template));
}

// create function to get the current styles colors
function getColors() {
	global $userinfo, $setStyleID;

	$getColors = query("SELECT * FROM styles_colors_default LIMIT 1",1);

	// use same method of getting customized colors as we did in the admincp...
	// do we have customized?
	$customizedColors = query("SELECT * FROM styles_colors WHERE defaultid = '".$getColors['defaultid']."' AND styleid = '".$setStyleID."' LIMIT 1");

	// if so... we should replace
	if(mysql_num_rows($customizedColors)) {
		$getColors = mysql_fetch_array($customizedColors);
	}

	// we have all colors.. return
	return $getColors; // return array
}

// construct the navbar links.. accepts an argument of links...
// this should be used on EVERY page!
// if it isn't.. the navtext will simply be the board name
// no errors should result if this function is omitted
function getNavbarLinks($array) {
	global $titleText;

	// loop through array
	foreach($array as $key => $value) {
		// our key is the title of the link.. and the value is the href..
		$nav_url = $value;
		$nav_text = $key;

		$titleText .= "- ".$nav_text." ";

		// fetch template...
		if($nav_url == "#") {
			eval("\$navbar_links .= \"".getTemplate("navbar_nolink")."\";");
		} else {
			eval("\$navbar_links .= \"".getTemplate("navbar_link")."\";");
		}
	}

	// return the links...
	return $navbar_links; // returns a string
}

// confirm login function... pretty versatile, you should be able to use it anywhere.. 
// make sure you send an encrypted md5 password as an argument...
function confirmLogin($username, $password) {
	// we're getting an encrypted password
	$checkValiditity = query("SELECT * FROM user_info WHERE username = '".htmlspecialchars(addslashes($username))."' LIMIT 1");

	// true or false?
	if(mysql_num_rows($checkValiditity)) {
		$checkinfo = mysql_fetch_array($checkValiditity);

		// if we imported vB.. we need to check their "salt"
		if($checkinfo['vBsalt'] != null) {
			if($checkinfo['password'] == md5($password.$checkinfo['vBsalt'])) {
				return true;
			}

			else {
				return false;
			}
		}

		else {
			if($checkinfo['password'] == $password) {
				return true;
			}

			else {
				return false;
			}
		}
	} else {
		return false;
	}
}

// function for printing a thankyou template...
function printThankYou($template,$text,$url) {
	// all we need to do is set variables and use the getTemplate function...
	$message = $text;
	$linkTo = $url;

	// get template
	eval("\$thanks = \"".getTemplate($template)."\";");

	// print template
	printTemplate($thanks);

	// nothing to return O.o
}

// function for printing error.. almost same thing as above
function printStandardError($template,$text="",$print=1) {
	global $userinfo, $colors;

	$message = $text;

	// what about permissions error?
	if($template == "error_permissions") {
		// templates...
		if(!$userinfo['userid']) {
			// what if quotes?
			if(strpos($_SERVER['PHP_SELF'],"postreply.php") !== false AND $_REQUEST['quoteArr']) {
				foreach($_REQUEST['quoteArr'] as $key => $value) {
					$quoteArrName = "quoteArr[".$key."]";
					$quoteArrValue = $value;
					eval("\$quoteInformation .= \"".getTemplate("error_permissions_loggedout_quoteArr")."\";");
				}
			}

			eval("\$permissionLogin = \"".getTemplate("error_permissions_loggedout")."\";");
		} else {
			eval("\$permissionLogin = \"".getTemplate("error_permissions_loggedin")."\";");
		}
	}

	// get template
	eval("\$error = \"".getTemplate($template)."\";");

	// print template
	if($print) {
		printTemplate($error);
	} else {
		return $error;
	}
}

// accepts a timestamp as an argument, and returns that date according to the end user's GMT offset
function processDate($format,$timestamp,$todayyest = 1) {
	global $bboptions, $userinfo;

	// get the actual date
	$actualDate = gmdate($format, $timestamp + (($userinfo['date_timezone'] + $userinfo['dst']) * 3600));

	// today and yest?
	if($format == $bboptions['date_formatted'] AND $todayyest AND $bboptions['date_todayYesterday']) {
		// get today
		$today = gmdate($bboptions['date_formatted'],time() + ($userinfo['date_timezone'] * 3600));

		// get yesterday
		$yesterday = gmdate($bboptions['date_formatted'],(time() - 86400) + ($userinfo['date_timezone'] * 3600));

		// get tomorrow? O.o
		$tomorrow = gmdate($bboptions['date_formatted'],(time() + 86400) + ($userinfo['date_timezone'] * 3600));

		// today.. yesterday.. or tomorrow?
		if($actualDate == $today) {
			// no time like the present!
			$actualDate = "Today";
		} else if($actualDate == $yesterday) {
			// been there, done that
			$actualDate = "Yesterday";
		} else if($actualDate == $tomorrow) {
			// i've always wanted to travel through time!
			$actualDate = "Tomorrow";
		}
	}

	// return actualDate..
	// if it's time, or no today/yesterday.. then it takes on it's proper format
	return $actualDate;
}

// function to trim things... accepts a string, and the number of characters to keep
function trimString($string,$trim,$fancy = 1) {
	// make sure that the length of string is larger than trim
	if(strlen($string) > $trim) {
		// trim
		$string = substr($string,0,strrpos(substr($string,0,$trim)," "));

		// add some dots to make it look good
		if($fancy) {
			$string .= "...";
		}

		// return!
		return $string;
	}

	// otherwise, it's already short enough!
	else {
		return $string;
	}
}

// do sessions
function doSessions($action,$title) {
	$sessionInclude['action'] = $action;
	$sessionInclude['title'] = $title;

	// it will form the location URL.. 
	// depending upon if there is a 
	// query string or not
	if($_SERVER['QUERY_STRING']) {
		$sessionInclude['location'] = addslashes($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
	} else {
		$sessionInclude['location'] = addslashes($_SERVER['PHP_SELF']);
	}

	return $sessionInclude;
}

function doError($msg,$sessNorm = '',$sessDetail = 'none') {
	global $bboptions, $totalQueries, $total, $forumjump, $userinfo, $colors, $internalCss;
	global $header_images, $loginLogout, $metaRedirect, $linkRel, $shutDownWarning, $serverLoad;

	if($msg == "perms") {
		$sessDetail = "Permissions Error";
	}

	if($sessNorm) {
		$sessionInclude = doSessions($sessNorm." <img src=\"".$colors['images_folder']."/error.gif\" alt=\"Error\" />",$sessDetail);
		include("./includes/sessions.php");
	}

	$navbarArr = Array(
		$sessNorm => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	eval("printTemplate(\"".getTemplate("header")."\");");

	if($msg == "perms") {
		printStandardError('error_permissions');
	} else {
		printStandardError('error_standard',$msg);
	}

	eval("printTemplate(\"".getTemplate("footer")."\");");

	exit;
}

function doThanks($msg,$sessNorm,$sessDetail,$uri,$meta = 1) {
	global $bboptions, $totalQueries, $total, $forumjump, $userinfo, $colors, $internalCss;
	global $header_images, $loginLogout, $metaRedirect, $linkRel, $shutDownWarning, $serverLoad;

	$sessionInclude = doSessions($sessNorm,$sessDetail);
	include("./includes/sessions.php");

	$navbarArr = Array(
		$sessNorm => "#"
	);
	$navbarText = getNavbarLinks($navbarArr);

	if($meta) {
		$metaRedirect = getMetaRedirect($uri);
	}

	eval("printTemplate(\"".getTemplate("header")."\");");
	printThankYou("thankyou_standard",$msg,$uri);
	eval("printTemplate(\"".getTemplate("footer")."\");");

	exit;
}

// we *could* use html_entity_decode...
// but it's only for phpversion 4.3.0 and greater
// so create own function!
function unhtmlspecialchars($given_html) {
   // get the html table
   $trans_table = get_html_translation_table();

   // now flip the table so we can translate
   $trans_table = array_flip($trans_table);

   // use the strtr function to replace occurrences
   $html = strtr($given_html,$trans_table);

   // return the 
   return $html;   
}

// function to return edited username
// just give it an array of userinfo...
// it *should* take care of the rest...
function getHTMLUsername($user) {	
	global $usergroupinfo;

	// first look for user specific HTML
	if($user['username_html_begin'] AND $user['username_html_end']) {
		// format
		$username = $user['username_html_begin'].$user['username'].$user['username_html_end'];
	}

	// now look for usergroup specific
	else if($usergroupinfo[$user['usergroupid']]['name_html_begin'] AND $usergroupinfo[$user['usergroupid']]['name_html_end']) {
		// format
		$username = $usergroupinfo[$user['usergroupid']]['name_html_begin'].$user['username'].$usergroupinfo[$user['usergroupid']]['name_html_end'];
	}

	// nothing...
	else {
		$username = $user['username'];
	}

	// return 
	return $username;
}

// this function is helpful for the one below it..
// it will cache all usertitles...
function buildUsertitles() {
	// get all usertitles
	$allUsertitles = query("SELECT * FROM usertitles ORDER BY minimumposts ASC");

	// make sure we have rows
	if(mysql_num_rows($allUsertitles)) {
		// intiate counter... starts at 1!
		$x = 1;

		// build array
		while($theUsertitle = mysql_fetch_array($allUsertitles)) {
			$usertitles[$x] = $theUsertitle;
			$x++;
		}
	}

	// return the array...
	if(is_array($usertitles)) {
		return $usertitles;
	} else {
		return null;
	}
}

// build the array
$usertitles = buildUsertitles();

// this function accepts a user info array
// this takes HTML permission into account
function getCustomTitle($user) {
	global $usertitles, $usergroupinfo;

	// firstly.. if it's a guest.. then give it the guest's usergroup usertitle...
	if(!$user['userid']) {
		if($usergroupinfo[1]['usertitle']) {
			$usertitle = $usergroupinfo[1]['usertitle'];
		} else {
			$usertitle = "Guest";
		}
	}

	// first check the actual userinfo...
	else if($user['usertitle'] AND $user['usertitle_option']) {
		$usertitle = $user['usertitle'];
	}

	// now look for the usergroup...
	else if($usergroupinfo[$user['usergroupid']]['usertitle']) {
		$usertitle = $usergroupinfo[$user['usergroupid']]['usertitle'];
	}

	// otherwise, find proper usertitle according to posts
	else {
		if(is_array($usertitles)) {
			// loop through user titles
			foreach($usertitles as $counter => $arr) {
				// make sure we have the next counter
				// if not.. simply give the usertitle the current.. there's nothin left!
				if(!is_array($usertitles[($counter + 1)])) {
					$usertitle = $arr['title'];
					break;
				}

				// now we compare...
				if($user['posts'] >= $arr['minimumposts'] AND $user['posts'] < $usertitles[($counter + 1)]['minimumposts']) {
					// yay!
					$usertitle = $arr['title'];
					break;
				}
			}
		}
	}

	// if it's still empty.. then well.. use "N/A"
	if(!$usertitle) {
		$usertitle = "N/A";
	}

	// return the usertitle.. it must have gotten set one way or another...
	return $usertitle;
}

// devise function to make page links...
// you can really use this function ANYWHERE... no need to rewrite it
// just specify the total number of pages, and the current page...
// this function will take care of the rest ;)
function buildPageLinks($numOfPages,$currPage,$arrayName = false,$threadbit = false,$threadinfo = "") {
	global $bboptions, $lastPostID, $pageThreadID;
	
	if(!is_numeric($currPage)) {
		$currPage = 1;
	}
	
	// make sure we have more than one page...
	if($numOfPages == 1) {
		return;
	}

	// get url...
	if($threadbit) {
		$locationUrl = "thread.php?t=".$pageThreadID;
	} else if($_SERVER['QUERY_STRING']) {
		$locationUrl = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
	} else {
		$locationUrl = $_SERVER['PHP_SELF'];
	}

	if($arrayName) {
		$append = $arrayName."%5Bpage%5D";
	} else {
		$append = "page";
	}

	// get rid of $arrayName[page] in locationUrl...
	$locationUrl = ereg_replace($append."=.*($|&)","",$locationUrl);

	// chop off "&" if there is one
	$locationUrl = ereg_replace("&$","",$locationUrl);

	// get previous and next
	$previousPageNum = $currPage - 1;
	$nextPageNum = $currPage + 1;

	// do we want to show "First"?
	if($currPage != 1 AND ($currPage - ($bboptions['general_pagelinks'] - 1)) > 1) {
		if($_SERVER['QUERY_STRING']) {
			eval("\$first = \"".getTemplate("pagelinks_first")."\";");
		} else {
			eval("\$first = \"".getTemplate("pagelinks_noquery_first")."\";");
		}

		// get the "previous" page while we're at it...
		if($_SERVER['QUERY_STRING']) {
			eval("\$previous = \"".getTemplate("pagelinks_previous")."\";");
		} else {
			eval("\$previous = \"".getTemplate("pagelinks_noquery_previous")."\";");
		}
	}

	// last?
	if($currPage != $numOfPages AND ($currPage + ($bboptions['general_pagelinks'] - 1)) < $numOfPages) {
		if($_SERVER['QUERY_STRING']) {
			eval("\$last = \"".getTemplate("pagelinks_last")."\";");
		} else {
			eval("\$last = \"".getTemplate("pagelinks_noquery_last")."\";");
		}

		// get the "next" page while we're at it...
		if($_SERVER['QUERY_STRING']) {
			eval("\$next = \"".getTemplate("pagelinks_next")."\";");
		} else {
			eval("\$next = \"".getTemplate("pagelinks_noquery_next")."\";");
		}
	}

	if(!$threadbit) {
		// loop through pages
		for($x = 1; $x <= $numOfPages; $x++) {
			// make sure it's within curr page...
			if($bboptions['general_pagelinks']) {
				if($x < $currPage AND ($currPage - $bboptions['general_pagelinks']) > $x-1) {
					continue;
				}

				if($x > $currPage AND ($currPage + $bboptions['general_pagelinks']) < $x+1) {
					continue;
				}
			}

			if($x == $currPage) {
				eval("\$pagenumbers .= \"".getTemplate("pagelinks_current")."\";");
			} else {
				$pagenum = $x;

				if($_SERVER['QUERY_STRING']) {
					eval("\$pagenumbers .= \"".getTemplate("pagelinks_nocurrent")."\";");
				} else {
					eval("\$pagenumbers .= \"".getTemplate("pagelinks_noquery_nocurrent")."\";");
				}
			}
		}
	}
	
	else {
		// loop through thread pages...
		for($x = 2; $x <= $numOfPages; $x++) {
			// get out!
			if($x > ($bboptions['multi_thread_max_links'] + 1)) { 
				break;
			}

			$pagenum = $x;

			// make sure's it'w within range...
			if($_SERVER['QUERY_STRING']) {
				eval("\$pagenumbers .= \"".getTemplate("pagelinks_nocurrent")."\";");
			} else {
				eval("\$pagenumbers .= \"".getTemplate("pagelinks_noquery_nocurrent")."\";");
			}
		}
	}

	// get full template
	if($threadbit) {
		eval("\$pagelinks = \"".getTemplate("pagelinks_thread")."\";");
	} else {
		eval("\$pagelinks = \"".getTemplate("pagelinks")."\";");
	}

	return $pagelinks;
}

// builds session array
// basically finds users who are within the cookie timeout
// and corresponds them with those in the sessions table...
// only call when you want to display online users...
function buildSessionsArr() {
	global $bboptions, $userinfo;

	$getSessionUsers = query("
	SELECT * 
	FROM sessions 
	LEFT JOIN user_info ON sessions.userid = user_info.userid 
	WHERE user_info.userid != 0 AND sessions.userid != 0 
	ORDER BY sessions.username
	");

	if(mysql_num_rows($getSessionUsers)) {
		// create sessions array
		$sessions = Array();
	}

	// otherwise.. return false.. there's no need for this function!
	else {
		return false;
	}

	// go through sessions
	while($sessioninfo = mysql_fetch_array($getSessionUsers)) {
		$sessions[$sessioninfo['userid']] = $sessioninfo;
	}

	// return the array created...
	return $sessions;
}

// this will build a sessions arr with everything
// it's used quite a few times on a global spectrum
// so we need it
function buildTotalSessions() {
	$online = query("SELECT * FROM sessions ORDER BY username ASC");

	while($session = mysql_fetch_array($online)) {
		$sessions[$session['sessionid']] = $session;
	}

	return $sessions;
}

// this function will include any things that should be run at the end of everyfile..
// such as destroying session, closing mysql.. etc..
function wrapUp() {
	mysql_close();
}

// this function is going to construct the forumjump
// it goes up to 4 levels...
// we also double the functionality of this function
// by making it usable as a "forum list" ...
function buildForumJump($forumid2 = "",$selection = false,$search = false) {
	global $bboptions, $colors, $forumPerms, $foruminfo, $oForumInfo, $forumjumpbits;

	// if we don't want it.. return false..
	if(!$bboptions['general_forumjump'] AND !$selection AND !$search) {
		return false;
	}

	// make sure a forum has this as a parent!!!!!
	if(!is_array($oForumInfo["$forumid2"])) {
		return;
	}

	// loop through forums
	foreach($oForumInfo["$forumid2"] as $displayOrder => $aRR2) {
		foreach($aRR2 as $key => $value) {
			// transform array...
			foreach($foruminfo[$key] as $key2 => $value2) {
				$$key2 = $value2;
			}

			// proper depth.. AND proper permissions.. and show on forum jump
			if(!$foruminfo[$key]['link_redirect'] AND $foruminfo[$key]['depth'] <= 4 AND $foruminfo[$key]['show_on_forumjump'] AND $foruminfo[$key]['is_active'] AND ($forumPerms[$foruminfo[$key]['forumid']]['can_view_board'] OR !$bboptions[$varPrefix.'hide_private']) AND (($search AND $forumPerms[$key]['can_search']) OR !$search)) {
				// get the appropriate template...
				eval("\$forumjumpbits .= \"".getTemplate("forumjump_level_".$foruminfo[$key]['depth'])."\";");

				// recursion
				buildForumJump($key,$selection,$search);
			}
		}
	}

	// get template to hold all this
	// which to get though?
	if($selection) {
		eval("\$forumjump = \"".getTemplate("forumjump_selectionList")."\";");
	} else {
		eval("\$forumjump = \"".getTemplate("forumjump")."\";");
	}

	if(!$search) {
		return $forumjump;
	} else {
		return $forumjumpbits;
	}
}

// this function is going to construct the forum selection list
// it does not take on anything from forum jump, but does include permissions
// it only forms the "<option>"
function buildForumSelection($forumid2="") {
	global $bboptions, $colors, $forumPerms, $foruminfo, $oForumInfo, $forumOptionBits;

	// make sure a forum has this as a parent!!!!!
	if(!is_array($oForumInfo["$forumid2"])) {
		return;
	}

	// loop through forums
	foreach($oForumInfo["$forumid2"] as $displayOrder => $aRR2) {
		foreach($aRR2 as $key => $value) {
			// transform array...
			foreach($foruminfo[$key] as $key2 => $value2) {
				$$key2 = $value2;
			}

			// proper depth.. AND proper permissions..
			if($foruminfo[$key]['is_active'] AND ($forumPerms[$foruminfo[$key]['forumid']]['can_view_board'] OR !$bboptions['other_hide_private'])) {
				unset($depthMark);

				// get depth mark
				for($x = 1; $x < $foruminfo[$key]['depth']; $x++) {
					eval("\$depthMark .= \"".getTemplate("forumjump_selection_depthmark")."\";");
				}

				// no posting?
				if($foruminfo[$key]['is_category'] OR $foruminfo[$key]['link_redirect'] OR !$foruminfo[$key]['is_open']) {
					eval("\$noPosting = \"".getTemplate("forumjump_selection_noPosting")."\";");
				} else {
					$noPosting = "";
				}

				// get the appropriate template...
				eval("\$forumOptionBits .= \"".getTemplate("forumjump_selection_option")."\";");

				// recursion
				buildForumSelection($key);
			}
		}
	}

	return $forumOptionBits;
}

// this function will process an username
// it will spit back the error if one occurs...
// this should be used in the register or if a guest is posting...
function processUsername($username) {
	global $bboptions;

	// check to make sure we aren't using an illegal username...
	if($bboptions['illegal_username']) {
		$illegals = split(" ",$bboptions['illegal_username']);

		foreach($illegals as $key => $word) {
			// see if we can find it...
			if(strpos(strtolower($username),strtolower($word)) !== false) {
				return "Sorry, the word '<strong>".$word."</strong>' is not allowed in your username.";
			}
		}
	}

	if(strpos($username,'"') !== false OR strpos($username,'&quot;') !== false OR strpos($username,'&quot') !== false OR strpos($username,"'") !== false OR strpos($username,"&#039;") !== false OR strpos($username,"&#039") !== false OR strpos($username,",") !== false OR strpos($username,'`') !== false OR strpos($username,'%27') !== false OR strpos($username,'%22') !== false OR strpos($username,'%60') !== false) {
		return "Sorry, you have illegal characters in your username. Illegal characters includes quotes (single and double), and commas.";
	}

	// minimum?
	if(strlen($username) < $bboptions['minimum_username']) {
		return "Sorry, your username must be at least ".$bboptions['minimum_username']." characters long.";
	}

	if(strlen($username) > $bboptions['maximum_username']) {
		return "Sorry, your username must be under ".$bboptions['maximum_username']." characters.";
	}

	$checkUsername = query("SELECT COUNT(username) AS counting FROM user_info WHERE username = '".addslashes(trim($username))."'",1);

	// one already exists?
	if($checkUsername['counting']) {
		return "Sorry, the username you picked is already in use. Please choose another.";
	}

	// yay!
	return true;
}

// similar to the one above, but does it for usertitle
// only user on usercp.php?do=profile
function processUserTitle($usertitle,$userid) {
	global $bboptions, $usergroupinfo, $userinfo;

	// is mod?
	$isMod = query("SELECT COUNT(*) AS is_mod FROM moderators WHERE userid = '".$userid."'",1);
	
	if($bboptions['exempt_mods'] AND ($usergroupinfo[$userinfo['usergroupid']]['is_admin'] OR $usergroupinfo[$userinfo['usergroupid']]['is_super_moderator'] OR $isMod['is_mod'])) {
		// meh
	}

	else {
		if($bboptions['usertitle_censored']) {
			$illegals = split(" ",$bboptions['usertitle_censored']);

			foreach($illegals as $key => $word) {
				// see if we can find it...
				if(strpos($usertitle,$word) !== false) {
					return "Sorry, you have illegal words in your usertitle.";
				}
			}
		}
	}

	// max chars?
	if(strlen(trim($usertitle)) > $bboptions['usertitle_maximum']) {
		return "Sorry, your usertitle is over the ".$bboptions['usertitle_maximum']." maximum character limit.";
	}

	if(strpos($usertitle,'"') !== false OR strpos($usertitle,'&quot;') !== false OR strpos($usertitle,'&quot') !== false OR strpos($usertitle,"'") !== false OR strpos($usertitle,"&#039;") !== false OR strpos($usertitle,"&#039") !== false OR strpos($usertitle,",") !== false OR strpos($usertitle,'`') !== false OR strpos($usertitle,'%27') !== false OR strpos($usertitle,'%22') !== false OR strpos($usertitle,'%60') !== false) {
		return "Sorry, you have illegal characters in your user title. Illegal characters includes quotes (single and double), and commas.";
	}

	// yay!
	return true;
}

// this will check if the user is allowed to use an usertitle
// it returns a boolean, true or false...
function canUserTitle($user) {
	global $bboptions, $usergroupinfo;

	$groupid = $user['usergroupid'];

	if($usergroupinfo[$groupid]['can_usertitle']) {
		// already???
		return true;
	}

	if(!$bboptions['customTitle_posts'] AND !$bboptions['customTitle_days']) {
		// itsa shame...
		return false;
	}

	if($bboptions['customTitle_posts'] AND !$bboptions['customTitle_days']) {
		if($user['posts'] >= $bboptions['customTitle_posts']) {
			return true;
		} else {
			return false;
		}
	}

	if(!$bboptions['customTitle_posts'] AND $bboptions['customTitle_days']) {
		if($user['date_joined'] <= (time() - ($bboptions['customTitle_days'] * 86400))) {
			return true;
		} else {
			return false;
		}
	}

	if($bboptions['customTitle_posts'] AND $bboptions['customTitle_days']) {
		if($bboptions['customTitle_or'] == 1) {
			if(($user['posts'] >= $bboptions['customTitle_posts']) OR ($user['date_joined'] <= (time() - ($bboptions['customTitle_days'] * 86400)))) {
				return true;
			} else {
				return false;
			}
		}

		else {
			if(($user['posts'] >= $bboptions['customTitle_posts']) AND ($user['date_joined'] <= (time() - ($bboptions['customTitle_days'] * 86400)))) {
				return true;
			} else {
				return false;
			}
		}
	}

	// what the... we should be here!
	return false;
}

// this function will fetch the online status, and return a template (the name of the template actually)
// only requires a userid...
function fetchOnlineStatus($userid) {
	global $sessArr;

	$isOnline = false;

	// loop through the onlinestatus array...
	// uses a query run in sessions.php...
	// make sure that file is included before using this function!
	foreach($sessArr as $sessionid => $arr) {
		// if match.. break out... and set to true
		if($arr['userid'] == $userid) {
			$isOnline = true;
			break;
		}
	}

	// which template to return?
	if($isOnline) {
		$template = "onlinestatus_online";
	} else {
		$template = "onlinestatus_offline";
	}

	return $template;
}

// this function accepts the birthday..
// and returns it in the proper format 
// according to bboptions... also fixes 
// dates before 1970 but after 1901
function processBirthday($theBirthday) {
	global $bboptions;

	// split the bday from the DB
	$separate = split("-",$theBirthday);

	// should be in the form of month-day(-year)
	$month = $separate[0];
	$day = $separate[1];
	$year = $separate[2];

	// if year is not applicable.. just 
	// use the no year format and get out...
	if($year == "0000" OR $year <= 1901 OR $year > date("Y") OR (strpos(PHP_OS,"WIN") !== false AND $year < 1970)) {
		if($year == "0000") {
			return date($bboptions['date_birthday_noyear'],mktime(0,0,0,$month,$day,0));
		} else {
			$theDate = date($bboptions['date_birthday_year'],mktime(0,0,0,$month,$day,1970));

			return str_replace(1970,$year,$theDate);
		}
	}

	$offSet = 0;
	$dst = -1;

	// if year is less than 1952.. use offset
	if($year < 1952) {
		// if it's less than 1942.. no DST
		if($year < 1942) {
			$dst = 0;
		}

		$offSet = -2650838400;
        $year += 84;
	}

	else if($year < 1970) {
		$offSet = -883612800;
		$year += 28;
	}

	$timestamp = mktime(0,0,0,$month,$day,$year,$dst) + $offSet;

	return date($bboptions['date_birthday_year'],$timestamp);
}

// this function caches all appropriate replacements, into one array
function buildReplacements() {
	global $setStyleID, $replacements_q;

	$getReplaceVars = query("SELECT * FROM replacement_variables WHERE styleid = '".$setStyleID."' OR is_global = 1");

	// loop through each
	while($replaceinfo = mysql_fetch_array($getReplaceVars)) {
		if($replaceinfo['replaceid']) {
			$replacements[$replaceinfo['replaceid']] = $replaceinfo;
		}
	}

	if(is_array($replacements)) {
		return $replacements;
	} else {
		return false;
	}
}

// this function accepts text, and puts the replacements in
function replaceReplacements($text) {
	global $replacements;

	// loop through all replacements
	if(is_array($replacements)) {
		foreach($replacements as $replaceid => $arr) {
			$text = str_replace($arr['find'],$arr['replacement'],$text);
		}
	}

	// all done!
	return $text;
}

// this will add to mod log...
function doModLog($fileAction) {
	global $userinfo;

	// just insert i guess...
	query("INSERT INTO log_moderator (userid,username,filepath,file_action,ip_address,action_date) VALUES ('".$userinfo['userid']."','".$userinfo['username']."','".$_SERVER['PHP_SELF']."','".addslashes($fileAction)."','".$_SERVER['REMOTE_ADDR']."','".time()."')");
}

// this function is an alias for file_get_contents for phpversion < 4.3.0
if(!function_exists("file_get_contents")) {
	function file_get_contents($filename) {
		if($file = @fopen($filename,"rb")) {
			$contents = "";
			while(!feof($file)) {
				$contents .= @fread($file, 1024);
			}

			@fclose($file);

			return $contents;
		}

		return false;
	}
}

// this will get rid of the 
// property: ; in the css...
// and then any empty brackets
function filterCss($css) {
	$css = preg_replace("%.*:\s;(\n|\r\n)%iU","",$css);
	$css = preg_replace("%.*\{(\n|\r\n)(\s|\S)\}(\n\n|\r\n\r\n)%iU","",$css);

	return $css;
}

// recursion to find a match in an array
function arrayFind($find,$search) {
	// uh oh!
	if(!is_array($search)) {
		return false;
	}

	foreach($search as $key => $va) {
		if(is_array($va)) {
			arrayFind($find,$va);
		}
		
		else if(strtolower(trim($va)) == strtolower(trim($find))) {
			return true;
		}
	}

	return false;
}

?>