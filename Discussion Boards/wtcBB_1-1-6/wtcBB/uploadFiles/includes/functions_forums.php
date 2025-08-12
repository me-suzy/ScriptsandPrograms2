<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //FUNCTIONS - FORUMS\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// this file is more or less the construction 
// of all the important arrays...
// the first is the most important, where we construct
// the foruminfo array... which holds information for 
// every forum.. it's a matrix...
// we can then use that array to manuever in and around
// any forums we want... without using excessive amounts of queries
// the array is constructed in the order that the admin has chosen
// feel free to add the foruminfo array in the buildForumArr function...

// construct the foruminfo array
function buildForumArr($forumid="") {
	global $foruminfoARR, $bboptions;

	// create base array.. if it isn't already made
	if(!is_array($foruminfoARR)) {
		$foruminfoARR = Array();
	}

	// get all forums
	$allForums = query("SELECT * FROM forums");

	if(mysql_num_rows($allForums)) {
		while($foruminfo = mysql_fetch_array($allForums)) {
			// let's make the array!
			$foruminfoARR[$foruminfo['forumid']] = $foruminfo;

			$foruminfoARR[$foruminfo['forumid']]['original_style'] = $foruminfo['default_style'];

			if(!$foruminfoARR[$foruminfo['forumid']]['default_style']) {
				$foruminfoARR[$foruminfo['forumid']]['default_style'] = $bboptions['general_style'];
			}
		}
	}

	return $foruminfoARR;
}

// get foruminfo array
$foruminfo = buildForumArr();

// this function will take the foruminfo
// and make an array which will be sorted properly...
// it will simply serve as a structural array...
// no real information will be stored in this array
function buildOrderedForumArr() {
	global $foruminfo;

	// create array
	$oForumInfo = Array();

	foreach($foruminfo as $key => $value) {
		// fill oForumInfo
		if($foruminfo[$key]['category_parent'] == -1) {
			$categoryParentId = 0;
		} else {
			$categoryParentId = $foruminfo[$key]['category_parent'];
		}

		$oForumInfo[$categoryParentId][$foruminfo[$key]['display_order']][$foruminfo[$key]['forumid']] = $key;
	}

	// alright let's sort this baby...
	foreach($oForumInfo as $catParent => $value) {
		ksort($oForumInfo["$catParent"]);
	}

	// go through foruminfo array and switch 0 back to -1...
	// stupid ksort function wouldn't accept negative values...
	// don't know why though.. at least on most environments it worked!
	$oForumInfo["-1"] = $oForumInfo["0"];
	unset($oForumInfo["0"]);

	// return the ordered array
	return $oForumInfo;
}

// build oForumInfo
$oForumInfo = buildOrderedForumArr();


// recurse moderators... so they mod all the proper forums...
function recurseModerators($parentId, $o, $f, $m, $mo, $u) {	
	global $moderators; 
	
	if(is_array($o["$parentId"])) {
		// loop through forums
		foreach($o["$parentId"] as $displayOrder => $aRR2) {
			foreach($aRR2 as $key => $value) {
				$moderators[$key][$m['moderatorid']] = $m;
				$moderators[$key][$m['moderatorid']]['recurse'] = 2;
				
				recurseModerators($key, $o, $f, $m, $mo, $u);
			}
		}
	}
}

// function to build moderator array
// returns three dimensional array...
// forum > modid > field = value
function buildModeratorArr() {
	global $oForumInfo, $userinfo, $foruminfo, $moderators;
	
	// go through each mod...
	$allMods = query("SELECT * FROM moderators LEFT JOIN user_info ON moderators.userid = user_info.userid ORDER BY user_info.username");

	if(!mysql_num_rows($allMods)) {
		return null;
	}

	// create array
	$moderators = Array();

	while($modinfo = mysql_fetch_array($allMods)) {
		$moderators[$modinfo['forumid']][$modinfo['moderatorid']] = $modinfo;
		
		// recurse down through?
		if($modinfo['recurse']) {
			recurseModerators($modinfo['forumid'], $oForumInfo, $foruminfo, $modinfo, $moderators, $userinfo);
		}			
	}

	return $moderators; // retuns array with all moderators, and their corresponding user_info...
}

$modinfo = buildModeratorArr();

// function loops through all forums.. 
// grab permissions for each forum from db
// if no permissions, set default
// returns multi-dimensional (matrix) array.. filled with forum permissions
// works the same way as the buildForumArr
function buildPermissionsArr($forumid="") {
	global $usergroupinfo, $foruminfo, $userinfo;

	// create forum perms array
	if(!is_array($forumPerms)) {
		$forumPerms = Array();
	}

	// try to grab permissions from DB...
	// we only need to store CURRENT usergroup information.. no need for all usergroups
	$grabPermsQ = query("SELECT * FROM forums_permissions WHERE usergroupid = '".$usergroupinfo[$userinfo['usergroupid']]['usergroupid']."'");

	// if rows.. loop through each.. appending to array
	if(mysql_num_rows($grabPermsQ)) {
		while($grabPerms = mysql_fetch_array($grabPermsQ)) {
			if(!is_array($forumPerms[$grabPerms['forumid']])) {
				$forumPerms[$grabPerms['forumid']] = Array(
					"usergroupid" => $grabPerms['usergroupid'],
					"forumid" => $foruminfo['forumid'],
					"can_view_board" => $grabPerms['can_view_board'],
					"can_view_threads" => $grabPerms['can_view_threads'],
					"can_view_deletion" => $grabPerms['can_view_deletion'],
					"can_search" => $grabPerms['can_search'],
					"can_attachments" => $grabPerms['can_attachments'],
					"can_post_threads" => $grabPerms['can_post_threads'],
					"can_reply_own" => $grabPerms['can_reply_own'],
					"can_reply_others" => $grabPerms['can_reply_others'],
					"can_upload_attachments" => $grabPerms['can_upload_attachments'],
					"can_rate" => $grabPerms['can_rate'],
					"can_edit_own" => $grabPerms['can_edit_own'],
					"can_delete_threads_own" => $grabPerms['can_delete_threads_own'],
					"can_delete_own" => $grabPerms['can_delete_own'],
					"can_close_own" => $grabPerms['can_close_own'],
					"can_post_polls" => $grabPerms['can_post_polls'],
					"can_vote_polls" => $grabPerms['can_vote_polls'],
					"can_perm_delete" => $grabPerms['can_perm_delete'],
					"is_inherited" => $grabPerms['is_inherited'],
					"flood_immunity" => $grabPerms['flood_immunity'],
					"manual" => 0,
					"permissionsid" => $grabPerms['permissionsid']
					);
			}
		}
	}
	
	// now go through each forum to make permissions for forums there aren't any permissions for...
	foreach($foruminfo as $key => $value) {
		if(!is_array($forumPerms[$key])) {
			$forumPerms[$key] = Array(
				"usergroupid" => $usergroupinfo[$userinfo['usergroupid']]['usergroupid'],
				"forumid" => $foruminfo['forumid'],
				"can_view_board" => $usergroupinfo[$userinfo['usergroupid']]['can_view_board'],
				"can_view_threads" => $usergroupinfo[$userinfo['usergroupid']]['can_view_threads'],
				"can_view_deletion" => $usergroupinfo[$userinfo['usergroupid']]['can_view_deletion'],
				"can_search" => $usergroupinfo[$userinfo['usergroupid']]['can_search'],
				"can_attachments" => $usergroupinfo[$userinfo['usergroupid']]['can_attachments'],
				"can_post_threads" => $usergroupinfo[$userinfo['usergroupid']]['can_post_threads'],
				"can_reply_own" => $usergroupinfo[$userinfo['usergroupid']]['can_reply_own'],
				"can_reply_others" => $usergroupinfo[$userinfo['usergroupid']]['can_reply_others'],
				"can_upload_attachments" => $usergroupinfo[$userinfo['usergroupid']]['can_upload_attachments'],
				"can_rate" => $usergroupinfo[$userinfo['usergroupid']]['can_rate'],
				"can_edit_own" => $usergroupinfo[$userinfo['usergroupid']]['can_edit_own'],
				"can_delete_threads_own" => $usergroupinfo[$userinfo['usergroupid']]['can_delete_threads_own'],
				"can_delete_own" => $usergroupinfo[$userinfo['usergroupid']]['can_delete_own'],
				"can_close_own" => $usergroupinfo[$userinfo['usergroupid']]['can_close_own'],
				"can_post_polls" => $usergroupinfo[$userinfo['usergroupid']]['can_post_polls'],
				"can_vote_polls" => $usergroupinfo[$userinfo['usergroupid']]['can_vote_polls'],
				"can_perm_delete" => $usergroupinfo[$userinfo['usergroupid']]['can_perm_delete'],
				"flood_immunity" => $usergroupinfo[$userinfo['usergroupid']]['flood_immunity'],
				"is_inherited" => 0,
				"manual" => 1
				);
		}
	}
	return $forumPerms;
}

// build the forum permissions array...
$forumPerms = buildPermissionsArr();

// this function will be used recursively
// in the lastPostInfo function below...
// main goal is to retrieve last post and totals
function lastPostInfo_recursion($forumid,$origForumid) {
	global $foruminfo, $forumPerms;

	$key = $origForumid;

	$childlist = explode(",",$foruminfo[$forumid]['childlist']);

	foreach($childlist as $key2 => $value2) {
		// now lets make sure we have proper permissions
		if($forumPerms[$value2]['can_view_board'] == 1) {
			// get threads and posts
			$foruminfo[$key]['posts'] += $foruminfo[$value2]['posts'];
			$foruminfo[$key]['threads'] += $foruminfo[$value2]['threads'];

			// last post? make sure we have proper permissions
			if($forumPerms[$value2]['can_view_threads'] == 1) {
				// if somethin
				if($foruminfo[$value2]['last_reply_threadid'] AND $foruminfo[$value2]['last_reply_threadid'] != null) {
					// compare
					if($foruminfo[$value2]['last_reply_date'] > $foruminfo[$key]['last_reply_date']) {
						$foruminfo[$key]['last_reply_threadid'] = $foruminfo[$value2]['last_reply_threadid'];
						$foruminfo[$key]['last_reply_threadtitle'] = $foruminfo[$value2]['last_reply_threadtitle'];
						$foruminfo[$key]['last_reply_username'] = $foruminfo[$value2]['last_reply_username'];
						$foruminfo[$key]['last_reply_userid'] = $foruminfo[$value2]['last_reply_userid'];
						$foruminfo[$key]['last_reply_date'] = $foruminfo[$value2]['last_reply_date'];
					}
				}
			}
		}

		if($foruminfo[$value2]['childlist']) {
			// recursion
			lastPostInfo_recursion($foruminfo[$value2]['forumid'],$key);
		}
	}
}

// this function will tally up total threads and posts... 
// as well as last post info.. 
// permissions are taken into account
function lastPostInfo($anotherForumID) {
	global $foruminfo, $forumPerms, $oForumInfo;

	// make sure a forum has this as a parent!!!!!
	if(!is_array($oForumInfo["$anotherForumID"])) {
		return;
	}

	// loop through forums
	foreach($oForumInfo["$anotherForumID"] as $displayOrder => $aRR2) {
		foreach($aRR2 as $key => $value) {
			if($forumPerms[$key]['can_view_threads'] AND $forumPerms[$key]['can_view_board'] AND (!$foruminfo[$key]['last_reply_threadid'] OR $foruminfo[$key]['last_reply_threadid'] == null)) {
				$foruminfo[$key]['last_reply_threadid'] = 0;
			} else if(!$forumPerms[$key]['can_view_threads'] OR !$forumPerms[$key]['can_view_board']) {
				$foruminfo[$key]['last_reply_threadid'] = -1;
			}

			// if it isn't empty...
			if($foruminfo[$key]['childlist']) {
				lastPostInfo_recursion($foruminfo[$key]['forumid'],$foruminfo[$key]['forumid']);
			}

			// hey what do ya know.. more recursion.. just wondeeeeeful
			lastPostInfo($key);
		}
	}
}

// run function
lastPostInfo(-1);

// function to build the forums...
// uses array created by buildForumArr
// this function can be used in forum display
// use forumid argument to only do a specified forum...
// make sure to give the $other arg a value of one
// if you are using this function anywhere besides forumhome!!!!!
// stops at specified max depth...
$z = 0;
function buildForum($forumid2 = "",$other = 0) {
	global $bboptions, $usergroupinfo, $userinfo, $forums, $colors, $forumPerms, $foruminfo, $modinfo, $oForumInfo, $x, $z;

	// make sure a forum has this as a parent!!!!!
	if(!is_array($oForumInfo["$forumid2"])) {
		return;
	}

	// forumhome.. or other?
	if(!$other) {
		$prefix = "forumhome";
		$varPrefix = "";
		$subtractDepth = 0;
	} else {
		$prefix = "forumdisplay";
		$varPrefix = "other_";
		$subtractDepth = $other;
	}

	// loop through forums
	foreach($oForumInfo["$forumid2"] as $displayOrder => $aRR2) {
		foreach($aRR2 as $key => $value) {
			// form template name...
			// cat or reg?
			if($foruminfo[$key]['is_category']) {
				$catReg = "category";
			} else {
				$catReg = "regular";
			}

			// transform array...
			foreach($foruminfo[$key] as $key2 => $value2) {
				$$key2 = $value2;
			}

			// what if link redirect?
			if($foruminfo[$key]['link_redirect']) {
				$templateName = $prefix."_level".($foruminfo[$key]['depth'] - $subtractDepth)."_link";
			}

			else {
				$templateName = $prefix."_level".($foruminfo[$key]['depth'] - $subtractDepth)."_".$catReg;
			}

			// proper depth.. AND proper permissions
			if(($foruminfo[$key]['depth'] - $subtractDepth) <= $bboptions[$varPrefix.'depth_forums'] AND $foruminfo[$key]['is_active'] AND ($forumPerms[$foruminfo[$key]['forumid']]['can_view_board'] OR !$bboptions[$varPrefix.'hide_private'])) {
				$z++;
				// do moderators.. only if mod column should be shown!
				if($bboptions[$varPrefix.'show_mod_column']) {
					$oldMods = $moderators;
					unset($moderators,
						$moderatorid,
						$moderatorusername,
						$showModerators
						);

					// if rows, form moderators
					if(is_array($modinfo[$foruminfo[$key]['forumid']])) {
						$x = 1;
						foreach($modinfo[$foruminfo[$key]['forumid']] as $key3 => $value3) {
							$moderatorid = $value3['userid'];
							
							if($foruminfo[$key]['is_category']) {
								$moderatorusername = $value3['username'];
								
								eval("\$moderators .= \"".getTemplate($prefix."_headMods")."\";");
							}
							
							else {
								$moderatorusername = getHTMLUsername($value3);
								
								// get template
							eval("\$moderators .= \"".getTemplate($prefix."_moderators_modlist")."\";");
							}

							$x++;
						}
					}

					eval("\$showModerators = \"".getTemplate($prefix."_moderators")."\";");
				}

				// threads and posts...
				$totalThreads = $foruminfo[$key]['threads'];
				$totalPosts = $foruminfo[$key]['posts'];

				// last post
				if($foruminfo[$key]['last_reply_threadid'] > 0) {
					// format dates
					$date = processDate($bboptions['date_formatted'],$foruminfo[$key]['last_reply_date']);
					$time = processDate($bboptions['date_time_format'],$foruminfo[$key]['last_reply_date']);

					$lastPoster = $foruminfo[$key]['last_reply_username'];
					$lastPosterID = $foruminfo[$key]['last_reply_userid'];
					$lastThreadTitle = htmlspecialchars(trimString($foruminfo[$key]['last_reply_threadtitle'],25));
					$lastThreadID = $foruminfo[$key]['last_reply_threadid'];

					// get template
					eval("\$last_post_info = \"".getTemplate($prefix."_lastpost_post")."\";");
				} 
				
				else if($foruminfo[$key]['last_reply_threadid'] == -1) {
					$totalThreads = 0;
					$totalPosts = 0;

					// get template
					eval("\$last_post_info = \"".getTemplate($prefix."_lastpost_noperm")."\";");
				} 
				
				else {
					// get template
					eval("\$last_post_info = \"".getTemplate($prefix."_lastpost_never")."\";");
				}

				// set forum description to empty 
				// if the admin doesn't want it
				if(!$bboptions[$varPrefix.'show_forum_descriptions']) {
					$forum_description = "";
				}

				else {
					$forum_description = unhtmlspecialchars($forum_description);
				}

				unset($showSubforums,
					$subForumSeparator,
					$childlist,
					$forumid_child,
					$forum_name_child,
					$getSubforums
					);

				// form sub-forums if it's set to...
				if($bboptions[$varPrefix.'show_subforums'] AND $foruminfo[$key]['childlist']) {
					// get childlist
					$childlist = explode(",",$foruminfo[$key]['childlist']);

					// loop through childlist and form template...
					$x = 1;

					foreach($childlist as $key4 => $value4) {
						// globalize childlist vars
						$forumid_child = $foruminfo[$value4]['forumid'];
						$forum_name_child = $foruminfo[$value4]['forum_name'];

						// grab template
						eval("\$getSubforums .= \"".getTemplate($prefix."_subforums_bit")."\";");
						$x++;
					}

					eval("\$showSubforums = \"".getTemplate($prefix."_subforums")."\";");
				}

				// new
				if($foruminfo[$key]['last_reply_date'] > $userinfo['lastvisit'] AND !isset($_COOKIE['wtcBB_forum'][$key])) {
					// locked?
					if(!$foruminfo[$key]['is_open'] OR $foruminfo[$key]['is_category'] OR !$forumPerms[$foruminfo[$key]['forumid']]['can_view_board']) {
						// it's locked...
						$newPostIcon = '<img src="'.$colors['images_folder'].'/icon_forumnewlockedpost.gif" alt="There have been new posts since your last visit. This forum is locked." />';
					} else {
						$newPostIcon = '<img src="'.$colors['images_folder'].'/icon_forumnewpost.gif" alt="There have been new posts since your last visit." />';
					}
				} 
				
				else if($_COOKIE['wtcBB_forum'][$key] AND $foruminfo[$key]['last_reply_date'] > $_COOKIE['wtcBB_forum'][$key]) {
					// locked?
					if(!$foruminfo[$key]['is_open'] OR $foruminfo[$key]['is_category'] OR !$forumPerms[$foruminfo[$key]['forumid']]['can_view_board']) {
						// it's locked...
						$newPostIcon = '<img src="'.$colors['images_folder'].'/icon_forumnewlockedpost.gif" alt="There have been new posts since your last visit. This forum is locked." />';
					} else {
						$newPostIcon = '<img src="'.$colors['images_folder'].'/icon_forumnewpost.gif" alt="There have been new posts since your last visit." />';
					}
				}

				else {
					// locked?
					if(!$foruminfo[$key]['is_open'] OR $foruminfo[$key]['is_category'] OR !$forumPerms[$foruminfo[$key]['forumid']]['can_view_board']) {
						// it's locked...
						$newPostIcon = '<img src="'.$colors['images_folder'].'/icon_forumnonewlockedpost.gif" alt="There have been no new posts since your last visit. This forum is locked." />';
					} else {
						$newPostIcon = '<img src="'.$colors['images_folder'].'/icon_forumnonewpost.gif" alt="There have been no new posts since your last visit." />';
					}
				}

				// grab remaining template...
				eval("\$forums .= \"".getTemplate($templateName)."\";");

				// recursion
				buildForum($key,$other);
			}
		}
	}

	return $forums;
}

// this should get the appropriate links for the navbar
// in the forumdisplay
function getForumNav($startID) {
	global $foruminfo, $navbarArr;

	// if parent is -1.. get out!
	if($foruminfo[$startID]['category_parent'] == -1) {
		return;
	}

	// add to array
	$navbarArr[$foruminfo[$foruminfo[$startID]['category_parent']]['forum_name']] = "forum.php?f=".$foruminfo[$startID]['category_parent'];

	// recursion
	getForumNav($foruminfo[$startID]['category_parent']);

	return $navbarArr;
}

// this function will get a style from a parent forum
// it will keep going up till it finds a style other than 0...
// should use same method as getForumNav...
$done = false;
function getForumStyle($startID) {
	global $foruminfo, $done, $return;

	// if parent is -1.. get out!
	// and if there is no selected style.. it will return the forums default style
	if($foruminfo[$startID]['category_parent'] == -1) {
		$done = true;
		$return = $foruminfo[$startID]['default_style'];
		return $foruminfo[$startID]['default_style'];
	}

	// look for a different style...
	if($foruminfo[$foruminfo[$startID]['category_parent']]['original_style']) {
		$done = true;
		$return = $foruminfo[$foruminfo[$startID]['category_parent']]['original_style'];
		return $foruminfo[$foruminfo[$startID]['category_parent']]['original_style'];
	}

	// recursion
	getForumStyle($foruminfo[$startID]['category_parent']);

	if($done) {
		return $return;
	}
}

// this function will return a boolean
// telling if this forum is active or not...
$done2 = false;
function isActive($forumid) {
	global $foruminfo, $done2;

	if(!$foruminfo[$forumid]['is_active']) {
		$done2 = true;
		return false;
	}

	// recursion
	if($foruminfo[$forumid]['category_parent'] != -1) {
		isActive($foruminfo[$forumid]['category_parent']);
	}

	// if it still isn't done.. then it's active...
	if(!$done2) {
		return true;
	}
}

// this function with accept a FORUMID, and will 
// return the mod id (is moderator), true (sup mod or admin) or false (no mod permissions)
function hasModPermissions($forumid) {
	global $modinfo, $usergroupinfo, $userinfo;

	// now check if it's an administrator or a super mod...
	if($usergroupinfo[$userinfo['usergroupid']]['is_super_moderator'] OR $usergroupinfo[$userinfo['usergroupid']]['is_admin']) {
		return true;
	}

	// loop through moderators of this forum...
	if(is_array($modinfo[$forumid])) {
		foreach($modinfo[$forumid] as $modid => $arr) {
			if($arr['userid'] == $userinfo['userid']) {
				// we have a match!
				return $modid;
			}
		}
	}

	// no match.. return false
	return false;
}

// this function will verify forum passwords
// it will also output the appropriate screens
// it includes the header and footer files
// it uses temporarily set cookies
function check_wtcBB_forumPassword($forumid) {
	global $foruminfo, $stylesheets_header, $stylesheets_sub, $bboptions, $replacements, $templateinfo;
	global $colors, $forumjump, $lastVisitDate, $lastVisitTime, $nowTime, $header_images, $loginLogout;
	global $userinfo, $usergroupinfo, $forumPerms, $internalCss, $linkRel;

	// make sure this is a password protect forum...
	// if not, return
	if(!$foruminfo[$forumid]['fpassword']) {
		return;
	}

	// get name
	$cookieName = "wtcBB_forumpass".$forumid;

	// if cookie isn't set, spit out templates
	if(!$_COOKIE[$cookieName]) {
		if($_POST['submitForumPassword'] OR $_POST['forumPassword']) {
			// validation
			if($foruminfo[$forumid]['fpassword'] == $_POST['forumPassword']) {
				// set cookie!
				setcookie($cookieName,1,0,$bboptions['cookie_path'],$bboptions['cookie_domain']);

				if($_SERVER['QUERY_STRING']) {
					$newURI = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
				} else {
					$newURI = $_SERVER['PHP_SELF'];
				}

				doThanks(
					"You have entered the password successfully.",
					"Entering Forum Password",
					"none",
					$newURI
				);
			}

			// uh oh!
			else {
				// error inside error! what the hell???
				$theError = printStandardError("error_standard","Sorry, the password you entered is correct.",0);
			}
		}

		// create nav bar array
		$navbarArr = getForumNav($forumid);

		// reverse it... if array exists
		if(is_array($navbarArr)) {
			$navbarArr = array_reverse($navbarArr);
		}

		// add to it...
		$navbarArr[$foruminfo[$forumid]['forum_name']] = "forum.php?f=".$forumid;
		$navbarArr['Enter Password'] = "#";

		$navbarText = getNavbarLinks($navbarArr);

		// deal with sessions
		$sessionInclude = doSessions("Entering Forum Password",$foruminfo[$forumid]['forum_name']);
		include("./includes/sessions.php");

		// get templates
		eval("\$header = \"".getTemplate("header")."\";");
		eval("\$error = \"".getTemplate("error_password")."\";");
		eval("\$footer = \"".getTemplate("footer")."\";");

		printTemplate($header);

		// an error inside an error? that's obsurd!
		if($theError) {
			printTemplate($theError);
		}

		printTemplate($error);
		printTemplate($footer);
		exit;
	}
}


// this function will strip all BB Code from the message
function stripBBCode($text) {
	$text = preg_replace('/\[[^\]]\](.*?)\[\/[^\]]\]/s', '$1', $text);
	
	return $text;
}

?>