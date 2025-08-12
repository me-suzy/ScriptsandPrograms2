<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################### //ADMIN FUNCTIONS\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include the functions for forums...
include('./../includes/functions_forums.php');

// form button attributes.. neater up here...
$submitbg = "style=\"font-family: verdana; font-size: 8pt; border: #9E9E9E 1px solid; background-image: url('./../images/button_bg.jpg'); background-repeat: repeat-x; background-color: #ECECEC;\" onMouseDown=\"this.style.borderColor='#C98C00'; this.style.backgroundImage='url(./../images/button_bgclick.jpg)'; this.style.backgroundColor='#F6EAB9';\" onMouseOver=\"this.style.borderColor='#245F9B'; this.style.backgroundImage='url(./../images/button_bgover.jpg)'; this.style.backgroundColor='#6FBADF';\" onMouseout=\"this.style.borderColor='#9E9E9E'; this.style.backgroundImage='url(./../images/button_bg.jpg)'; this.style.backgroundColor='#ECECEC';\"";

// construct admin header...
function admin_header($title,$onLoad="",$meta="") {
	print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n");
	print("<html>\n");
	print("<head>\n");
	print("<title> ".$title." </title>\n");
	print("<link rel=\"stylesheet\" href=\"content_style.css\" type=\"text/css\" />\n");
	print("<script language=\"javascript\" type=\"text/javascript\" src=\"./../scripts/global.js\"></script>\n");
	print($meta);
	print("</head>\n");
	print("<body".$onLoad.">\n\n\n");
}

function admin_footer() {
	print("\n\n\n<span class=\"footer\"><a href=\"http://www.webtrickscentral.com\" target=\"_top\">Copyright ©, WebTricksCentral.com</a></span>");
	print("\n\n\n</body>");
	print("\n</html>\n\n");
}

// start the "construct_title" function
function construct_title($text) {
	print("<h1>".$text."</h1>");
}

// just use a quick little function to name a section.. avoid html-whoring it up
function a_name($name) {
	print("\n\n<a name=\"".$name."\" />\n\n");
}

// start the "construct_table" function
function construct_table($class,$form_name,$submit_id,$make_form=0) {

	// only make the form is so specified...
	if($make_form == 1) {
		print(stripslashes("\n<form method=\"post\" action=\"\" name=\"".$form_name."\" style=\"margin: 0px;\">\n<br />"));
		print("<input type=\"hidden\" name=\"".$form_name."[set_form]\" value=\"1\" />\n\n");
	}

	print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\" class=\"".$class."\">\n");
}

// start the "construct_header" function
function construct_header($text,$colspan) {
	print("\t<tr>\n");
	print("\t\t<td class=\"header\" colspan=\"".$colspan."\">".$text."</td>\n");
	print("\t</tr>\n\n");
}

// start the "construct_footer" function
function construct_footer($colspan,$submit_id,$text="") {
	global $submitbg;

	print("\t<tr>\n");
	print("\t\t<td class=\"footer\" colspan=\"".$colspan."\">".$text."<pre><button type=\"submit\" id=\"".$submit_id."\" ".$submitbg.">Submit</button>  <button type=\"reset\" ".$submitbg.">Reset</button></pre></td>\n");
	print("\t</tr>\n\n");
}

// start the "construct_input"
function construct_input($counter,$title,$desc,$array,$key,$bottom=0,$other=0,$array2="") {
	global $bboptions, $forum_options, $userinfo, $moderatorinfo;

	// get name..
	$name = stripslashes("$array\[$key\]");

	print("\t<tr>\n");

	// check to see if it's a bottom row...
	if($bottom == 1) {
		$extra = "_bottom";
	} else {
		$extra = "";
	}
	
	if($other == 0) {
		if(!empty($array2)) {
			if($array2[$key] == 1) {
				$check1 = "checked=\"checked\" ";
			} else {
				$check2 = "checked=\"checked\" ";
			}
		}

		else {
			// let's see if we can get the proper value from the db... then decide if we should check it or not
			if($bboptions[$key] == 1) {
				$check1 = "checked=\"checked\" ";
			} else {
				$check2 = "checked=\"checked\" ";
			}
		}
	}

	elseif($other != 0) {
		if($other == 1) {
			$check1 = "checked=\"checked\" ";
		} else {
			$check2 = "checked=\"checked\" ";
		}
	}

	if($counter == 1) {
		$color = 1;
	} else {
		$color = 2;
	}

	print("\t\t<td class=\"desc".$color.$extra."\">\n");
	print("\t\t\t<b>".$title."</b> <br /> <span class=\"small\">".$desc."</span>\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"input".$color.$extra."\">\n");
	print("\t\t\t<label for=\"".$key."1\"><input type=\"radio\" name=\"".$name."\" id=\"".$key."1\" value=\"1\" ".$check1."/> Yes</label>\n");
	print("\t\t\t<label for=\"".$key."2\"><input type=\"radio\" name=\"".$name."\" id=\"".$key."2\" value=\"0\" ".$check2."/> No</label>\n");
	print("\t\t</td>\n");

	print("</tr>\n\n");
}

// start the "construct_text"
function construct_text($counter,$title,$desc,$array,$key,$value="",$bottom=0) {
	global $bboptions;

	// get name..
	$name = stripslashes("$array\[$key\]");

	print("\t<tr>\n");

	// check to see if it's a bottom row...
	if($bottom == 1) {
		$extra = "_bottom";
	} else {
		$extra = "";
	}

	if($counter == 1) {
		$color = 1;
	} else {
		$color = 2;
	}

	print("\t\t<td class=\"desc".$color.$extra."\">\n");
	print("\t\t\t<b>".$title."</b> <br /> <span class=\"small\">".$desc."</span>\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"input".$color.$extra."\">\n");
	print("\t\t\t<input type=\"text\" class=\"text\" name=\"".$name."\" value=\"".$value."\" ".$check."/>\n");
	print("\t\t</td>\n");

	print("</tr>\n\n");
}

// start the "construct_text"
function constructColorText($counter,$title,$desc,$array,$key,$value="",$bottom=0,$customDefault="",$colorViewer=0) {
	global $bboptions;

	// get name..
	$name = stripslashes("$array\[$key\]");

	print("\t<tr>\n");

	// check to see if it's a bottom row...
	if($bottom == 1) {
		$extra = "_bottom";
	} else {
		$extra = "";
	}

	if($counter == 1) {
		$color = 1;
	} else {
		$color = 2;
	}

	// customized?
	if($customDefault == "scyth543216789") {
		$custom = " style=\"color: #BB0000; font-weight: bold;\"";
		$revertSelect = "<label for=\"revert_".$key."\"><span class=\"small\">Revert</span><input type=\"checkbox\" name=\"revert5[".$key."]\" value=\"1\" id=\"revert_".$key."\" /></label>";
	} else {
		$custom = "";
		$revertSelect = "";
	}

	// javascript function?
	if($colorViewer == 1) {
		$jScriptFunction = " onblur=\"colorViewer('".$name."');\"";
	} else {
		$jScriptFunction = "";
	}

	print("\t\t<td class=\"desc".$color.$extra."\">\n");
	print("\t\t\t<b>".$title."</b> <br /> <span class=\"small\">".$desc."</span>\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"input".$color.$extra."\">\n");

	print($revertSelect);

	if($colorViewer == 1) {
		print("\t\t\t<div id=\"".$name."5\" style=\"display: inline; font-size: 18pt; border: 1px solid #000000; background-color: ".$value.";\">&nbsp;&nbsp;&nbsp;</div>\n");
	}

	print("\t\t\t<input type=\"text\" class=\"text\" style=\"margin-left: 10px;\" size=\"25\" name=\"".$name."\"".$jScriptFunction." value=\"".$value."\"".$custom." id=\"".$name."\" ".$check."/>\n");
	print("\t\t</td>\n");

	print("</tr>\n\n");
}

// if we don't need the construct_select, we will hard code it with this...
function construct_select_begin($counter,$title,$desc,$array,$key,$bottom=0,$no_select=0) {
	// get name
	$name = stripslashes("$array\[$key\]");

	print("\t<tr>\n");

	// check to see if it's a bottom row...
	if($bottom == 1) {
		$extra = "_bottom";
	} else {
		$extra = "";
	}

	// find the alternating color...
	if($counter == 1) {
		$color = 1;
	} else {
		$color = 2;
	}

	print("\t\t<td class=\"desc".$color.$extra."\">\n");
	print("\t\t\t<b>".$title."</b> <br /> <span class=\"small\">".$desc."</span>\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"input".$color.$extra."\">\n");
	
	if($no_select != 1) {
		print("\t\t\t<select name=\"".$name."\">\n");
	}
}

// now construct the function to end the above...
function construct_select_end($counter,$no_select=0) {

	if($no_select != 1) {
		print("\t\t\t</select>\n");
	}

	print("\t\t</td>\n");

	print("</tr>\n\n");
}

// start the "construct_select"
function construct_select($counter,$title,$desc,$array,$key,$items="",$timezone=0,$bottom=0,$style=0,$forums=0,$selection=-2,$options_threadviewage=0,$no_select=0) {

	global $bboptions, $_GET, $announce_stuff, $forum_options, $is_announcement, $is_user, $wtcBB_adminIsMod;

	// get name..
	$name = stripslashes("$array\[$key\]");

	if($array == "options" OR $array == "add_user") {
		$name2 = $bboptions;
	}

	print("\t<tr>\n");

	// check to see if it's a bottom row...
	if($bottom == 1) {
		$extra = "_bottom";
	} else {
		$extra = "";
	}

	// find the alternating color...
	if($counter == 1) {
		$color = 1;
	} else {
		$color = 2;
	}

	print("\t\t<td class=\"desc".$color.$extra."\">\n");
	print("\t\t\t<b>".$title."</b> <br /> <span class=\"small\">".$desc."</span>\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"input".$color.$extra."\">\n");
	
	if($no_select != 1) {
		print("\t\t\t<select name=\"".$name."\">\n");
	}

	// make sure we aren't doing a style... and not timezones.. and not forums.. and not threadviewage
	if($style != 1 AND $timezone != 1 AND $forums == 0 AND $options_threadviewage == 0) {
		$option_select = split(",",$items);

		foreach($option_select as $option_key => $option_value) {
			if($option_value == $bboptions[$key] OR $selection == $option_key) {
				$check_select = " selected=\"selected\"";
			} else {
				$check_select = "";
			}

			if($is_user == true) {
				print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");
			}

			else {
				print("<option value=\"".$option_value."\"".$check_select.">".$option_value."</option>\n");
			}
		}
	}

	// do forums...
	if($forums == 1) {
		// two different things here.. one for add and one for edit ;)
		if($_GET['do'] == "edit") {
			if($announce_stuff['forum'] == "all_forums") {
				$selected1 = " selected=\"selected\"";
			} else {
				$selected1 = "";
			}

			print("<option value=\"all_forums\"".$selected1.">All Forums</options>\n");

			$run_query = mysql_query("SELECT * FROM forums ORDER BY display_order");
			while($announcements = mysql_fetch_array($run_query)) {
				if($announce_stuff['forum'] == $announcements['forum_name']) {
					$selected = " selected=\"selected\"";
				} else { 
					$selected = "";
				}
				print("<option value=\"".$announcements['forum_name']."\"".$selected.">".$announcements['forum_name']."</option>\n");
			}
		}

		else {
			print("<option value=\"all_forums\" selected=\"selected\">All Forums</options>\n");

			$run_query = mysql_query("SELECT * FROM forums ORDER BY display_order");
			while($announcements = mysql_fetch_array($run_query)) {
				print("<option value=\"".$announcements['forum_name']."\">".$announcements['forum_name']."</option>\n");
			}
		}
	}

	else if($forums >= 2) {
		if(!isset($wtcBB_adminIsMod) AND $forums == 2) {
			if(isset($forum_options)) {
				print("<option value=\"-1\">None</option>\n");
			} 

			else if($_GET['do'] == "posts" OR $_GET['do'] == "threads" OR (!isset($_GET['id']) AND $is_announcement == true)) {
				print("<option value=\"-1\" selected=\"selected\">All Forums</option>\n");
			}

			else if(isset($_GET['id']) AND $is_announcement == true) {
				print("<option value=\"-1\">All Forums</option>\n");
			}
			
			else if($_GET['do'] != "add_moderator" AND $_GET['do'] != "edit_moderator") {
				print("<option value=\"-1\" selected=\"selected\">None</option>\n");
			}
		}

		if(isset($wtcBB_adminIsMod)) {
			if($forums == 3) {
				$isMod = 3;
			} else {
				$isMod = 2;
			}
		} else {
			$isMod = false;
		}

		loop_forums(1,$forum_options['category_parent'],-1,$isMod);
	}

	if($no_select != 1) {
		print("\t\t\t</select>\n");
	}

	print("\t\t</td>\n");

	print("</tr>\n\n");
}

// start the "construct_textarea"
function construct_textarea($counter,$title,$desc,$array,$key,$value="",$bottom=0) {
	global $bboptions;

	// get name..
	$name = stripslashes("$array\[$key\]");

	print("\t<tr>\n");

	// check to see if it's a bottom row...
	if($bottom == 1) {
		$extra = "_bottom";
	} else {
		$extra = "";
	}

	// get alternating color...
	if($counter == 1) {
		$color = 1;
	} else {
		$color = 2;
	}

	print("\t\t<td class=\"desc".$color.$extra."\">\n");
	print("\t\t\t<b>".$title."</b> <br /> <span class=\"small\">".$desc."</span>\n");
	print("\t\t</td>\n\n");

	print("\t\t<td class=\"input".$color.$extra."\">\n");
	print("\t\t\t<textarea cols=\"25\" rows=\"6\" name=\"".$name."\">".$value."</textarea>\n");
	print("\t\t</td>\n");

	print("</tr>\n\n");
}

// end a table with "construct_end"
function construct_table_END($form=0) {
	print("\n\n</table>\n\n");
	
	if($form == 1) print("\n\n</form>\n\n");
}

// construct the function to make a header code in the nav...
function construct_nav_header($title) {
	print("<h2 ondblclick=\"wtcBB_expandCollapse('".$title."');\"><img src=\"./../images/collapse.gif\" alt=\"Collapse\" id=\"".$title."_img\" onclick=\"wtcBB_expandCollapse('".$title."');\" style=\"cursor: hand;\" /> ".$title."</h2>\n");
	print("<div id=\"".$title."\" style=\"display: block; margin: 0 0 5px 0;\">\n\n");
}

// construct the function to make a link code in the nav...
function construct_nav_link($link,$display,$target) {
	print("\t<a href=\"".$link."\" target=\"".$target."\">".$display."</a>\n\n");
}

// construct a function to output a error.. much like the thank you page...
function construct_error($text,$meta_tag="") {
	// do header
	admin_header("wtcBB Admin Panel - Options","",$meta_tag);

	print("<h1 style=\"width: 60%;\">ERROR!</h1>\n\n");
	print("<div align=\"center\"><blockquote style=\"width: 60%; text-align: left;\">\n\n");
	print($text);
	print("</blockquote></div>\n\n");

	// do footer
	admin_footer();
}

// do a confirm page...
function construct_confirm($text="",$action="",$transfer=0) {
	global $submitbg, $results, $movePrune;

	// do header
	admin_header("wtcBB Admin Panel - Options","",$meta_tag);

	print("<h1 style=\"width: 60%;\">DELETE</h1>\n\n");
	print("<div align=\"center\"><blockquote style=\"width: 60%; text-align: left;\">\n\n");
	
	if(empty($text)) {
		print("Are you sure you want to delete this? It <b>cannot</b> be undone! If you select no you will be redirected back.\n\n<br />");
	}

	else {
		print($text."\n\n<br />");
	}

	print("<form method=\"post\" action=\"".$action."\" name=\"confirm\" style=\"text-align: center;\">\n");
	print("<input type=\"hidden\" name=\"confirm[set_form]\" value=\"1\" />\n");
	if($transfer == 1) {
		print("<input type=\"hidden\" name=\"confirm[special2]\" value=\"".$_POST['results']['special']."\" />");
		print("<input type=\"hidden\" name=\"confirm[special3]\" value=\"".$movePrune."\" />");
		print("<input type=\"hidden\" name=\"confirm[special4]\" value=\"".$_POST['results']['move_to']."\" />");
		
		// intiate counter
		$x = 1;

		// find all checked users
		foreach($_POST['results'] as $option_key => $option_value) {
			if($option_key != "move_to" AND $option_key != "delete" AND $option_key != "move" AND $option_key != "check_all") {
				if($x == 1) {
					$comma = "";
				} else {
					$comma = ",";
				}

				if($option_value == 1) {
					$users_checked .= $comma.$option_key;
					$x++;
				}
			}
		}

		print("<input type=\"hidden\" name=\"confirm[checked_users]\" value=\"".$users_checked."\" />");

	}
	print("<label for=\"yes\"><input type=\"radio\" name=\"confirm[yes_no]\" id=\"yes\" value=\"1\" checked=\"checked\" /> Yes</label>\n");
	print("<label for=\"no\"><input type=\"radio\" name=\"confirm[yes_no]\" id=\"no\" value=\"0\" /> No</label>\n<br />");
	print("<button type=\"submit\" ".$submitbg.">Submit</button>\n\n");
	print("</form>\n\n\n");
	print("</blockquote></div>\n\n");

	// do footer
	admin_footer();
}

// a very similar version of this exists in functions.php
// except it wasn't organized well, and it isn't worth it
// to go all back through the files just adding one thing
// so i just make a more organized replica...
function buildPermissionsArr2($forumid="") {
	global $usergroupinfo, $foruminfo;

	// create forum perms array
	if(!is_array($forumPerms)) {
		$forumPerms = Array();
	}

	// try to grab permissions from DB...
	$grabPermsQ = query("SELECT * FROM forums_permissions");

	// if rows.. loop through each.. appending to array
	if(mysql_num_rows($grabPermsQ) > 0) {
		while($grabPerms = mysql_fetch_array($grabPermsQ)) {
			if(!is_array($forumPerms[$grabPerms['forumid']][$grabPerms['usergroupid']])) {
				$forumPerms[$grabPerms['forumid']][$grabPerms['usergroupid']] = Array(
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
		foreach($usergroupinfo as $usergroupid => $arr) {
			if(!is_array($forumPerms[$key][$usergroupid])) {
				$forumPerms[$key][$usergroupid] = Array(
					"usergroupid" => $usergroupinfo[$usergroupid]['usergroupid'],
					"forumid" => $foruminfo['forumid'],
					"can_view_board" => $usergroupinfo[$usergroupid]['can_view_board'],
					"can_view_threads" => $usergroupinfo[$usergroupid]['can_view_threads'],
					"can_view_deletion" => $usergroupinfo[$usergroupid]['can_view_deletion'],
					"can_search" => $usergroupinfo[$usergroupid]['can_search'],
					"can_attachments" => $usergroupinfo[$usergroupid]['can_attachments'],
					"can_post_threads" => $usergroupinfo[$usergroupid]['can_post_threads'],
					"can_reply_own" => $usergroupinfo[$usergroupid]['can_reply_own'],
					"can_reply_others" => $usergroupinfo[$usergroupid]['can_reply_others'],
					"can_upload_attachments" => $usergroupinfo[$usergroupid]['can_upload_attachments'],
					"can_edit_own" => $usergroupinfo[$usergroupid]['can_edit_own'],
					"can_delete_threads_own" => $usergroupinfo[$usergroupid]['can_delete_threads_own'],
					"can_delete_own" => $usergroupinfo[$usergroupid]['can_delete_own'],
					"can_close_own" => $usergroupinfo[$usergroupid]['can_close_own'],
					"can_post_polls" => $usergroupinfo[$usergroupid]['can_post_polls'],
					"can_vote_polls" => $usergroupinfo[$usergroupid]['can_vote_polls'],
					"can_perm_delete" => $usergroupinfo[$usergroupid]['can_perm_delete'],
					"flood_immunity" => $usergroupinfo[$usergroupid]['flood_immunity'],
					"is_inherited" => 0,
					"manual" => 1
					);
			}
		}
	}
	return $forumPerms;
}

// build the forum permissions array...
$forumPerms2 = buildPermissionsArr2();

// create function to loop through forum permissions...
function loopForumPermissions($forum_id=-1) {
	global $submitbg, $foruminfo, $oForumInfo, $forumPerms2, $usergroupinfo, $modinfo;

	// make sure a forum has this as a parent!!!!!
	if(!is_array($oForumInfo["$forum_id"])) {
		return;
	}

	// loop through forums
	foreach($oForumInfo["$forum_id"] as $displayOrder => $aRR2) {
		foreach($aRR2 as $key => $value) {
			// depthmark.. if it isn't depth 1
			if($foruminfo[$key]['depth'] > 1) {
				// unset the depthmark...
				unset($depthmark);

				// find the depth...
				for($i=1;$i < $foruminfo[$key]['depth'];$i++) {
					$depthmark += 50;
				}
			} else {
				$depthmark = 20;
			}

			print("\t<tr>\n");
				print("\t\t<td class=\"cat\">\n");
					print("\t\t\t<div style=\"margin: 0px; margin-left: ".$depthmark."px;\">".$foruminfo[$key]['forum_name']." <span class=\"small\">(id: ".$foruminfo[$key]['forumid'].")</span></div>\n");
				print("\t\t</td>\n");
			print("\t</tr>\n\n");

			print("\t<tr>\n");
				print("\t\t<td class=\"desc1\">\n");

			if(is_array($usergroupinfo)) {
				if(is_array($modinfo[$key])) {
					// print moderators of this forum...
					print("\t\t\t<div style=\"margin: 0px; margin-left: ".$depthmark."px;\"><span class=\"small\"><strong>Moderators:</strong><em>");
						$x = 1;
						foreach($modinfo[$key] as $modid => $moderator) {
							if($x == 1) {
								$comma = "";
							} else {
								$comma = ",";
							}

							print($comma." ".$moderator['username']);
							$x++;
						}
					print("</em></span></div>\n\n");
				}

				print("<div style=\"margin-bottom: 0px; margin-top: 0px; margin-left: ".$depthmark."px;\">\n");

				// loop through usergroups.. print edit and the usergroup name
				foreach($usergroupinfo as $usergroupid => $arr) {
					if($forumPerms2[$key][$usergroupid]['manual'] == 1) {
						// standard!
						print("\t\t\t<div style=\"margin: 0px; margin-bottom: 5px;\"><button type=\"button\" onClick=\"location.href='forum.php?do=permission&usergroupid=".$arr['usergroupid']."&forumid=".$foruminfo[$key]['forumid']."';\" ".$submitbg.">Edit</button> &nbsp;&nbsp; ".$arr['name']."</div>\n");
					}

					else {
						if($forumPerms2[$key][$usergroupid]['is_inherited'] == 1) {
							// inherited!
							print("\t\t\t<div style=\"margin-top: 0px; margin-bottom: 5px;\"><button type=\"button\" onClick=\"location.href='forum.php?do=permission&permissionsid=".$forumPerms2[$key][$usergroupid]['permissionsid']."';\" ".$submitbg.">Edit</button> &nbsp;&nbsp; <span style=\"color: #00285B; font-weight: bold;\">".$arr['name']."</span></div>\n");
						}

						else {
							// custom!
							print("\t\t\t<div style=\"margin-top: 0px; margin-bottom: 5px;\"><button type=\"button\" onClick=\"location.href='forum.php?do=permission&permissionsid=".$forumPerms2[$key][$usergroupid]['permissionsid']."';\" ".$submitbg.">Edit</button> &nbsp;&nbsp; <span style=\"color: #BB0000; font-weight: bold;\">".$arr['name']."</span></div>\n");
						}
					}
				}
			}
					print("\t\t\t</div>\n");
				print("\t\t</td>\n");
			print("\t</tr>\n");

			// recursion
			if(!empty($foruminfo[$key]['childlist'])) {
				loopForumPermissions($foruminfo[$key]['forumid']);
			}
		}
	}
}

// check to see if we have inheritance for forum permissions
function backToDefault($forumid,$usergroupid) {
	global $foruminfo, $forumPerms2;

	// if our category parent is at -1.. stop and return false
	if($foruminfo[$forumid]['category_parent'] == -1) {
		// now we need to <em>delete</em> rows here.. there is nothing to fall back on.. no inheritance
		removePermissions($foruminfo[$forumid]['forumid'],$usergroupid);

		return;
	}

	if($forumPerms2[$foruminfo[$forumid]['category_parent']][$usergroupid]['manual'] == 0) {
		// put into an easier array
		$parentinfo = $forumPerms2[$foruminfo[$forumid]['category_parent']][$usergroupid];

		// form query... long one, stupid array wasn't working right -_-
		$query = "UPDATE forums_permissions SET can_view_board = '".$parentinfo['can_view_board']."' , can_view_threads = '".$parentinfo['can_view_threads']."' , can_view_deletion = '".$parentinfo['can_view_deletion']."' , can_search = '".$parentinfo['can_search']."' , can_attachments = '".$parentinfo['can_attachments']."' , can_post_threads = '".$parentinfo['can_post_threads']."' , can_reply_own = '".$parentinfo['can_reply_own']."' , can_reply_others = '".$parentinfo['can_reply_others']."' , can_upload_attachments = '".$parentinfo['can_upload_attachments']."' , can_edit_own = '".$parentinfo['can_edit_own']."' , can_delete_threads_own = '".$parentinfo['can_delete_threads_own']."' , can_delete_own = '".$parentinfo['can_delete_own']."' , can_close_own = '".$parentinfo['can_close_own']."' , can_post_polls = '".$parentinfo['can_post_polls']."' , can_vote_polls = '".$parentinfo['can_vote_polls']."' , is_inherited = '1' WHERE forumid = '".$foruminfo[$forumid]['forumid']."' AND usergroupid = '".$usergroupid."'";

		//print($query);
		
		// run query
		query($query);

		return;
	}

	// no inheritance at all.. just delete permission
	else {
		removePermissions($foruminfo[$forumid]['forumid'],$usergroupid);

		return;
	}
}

// devise function to add permissions inheritance to forums
function addInheritedPermissions($forumid,$usergroupid) {
	global $start_query, $key_query, $value_query, $foruminfo, $forumPerms2;

	unset($childlist);

	// make sure this forum has a childlist.. since we've already added permissions to THIS forum.. just do childs
	if(!empty($foruminfo[$forumid]['childlist'])) {
		$childlist = explode(",",$foruminfo[$forumid]['childlist']);

		// loop through the childlist...
		foreach($childlist as $key => $value) {
			// make sure they haven't already been added...
			if($forumPerms2[$value][$usergroupid]['manual'] == 1) {
				// before we do anything.. check to see if a permission entry exists for this child.. if so.. delete!
				query("DELETE FROM forums_permissions WHERE forumid = '".$value."' AND usergroupid = '".$usergroupid."'");

				// we don't even need the foruminfo.. all we have to do is add the permissions
				$query = $start_query.$key_query.",forumid,usergroupid,is_inherited) VALUES (".$value_query.",'".$value."','".$usergroupid."','1')";

				// run query
				query($query);

				//print($query);

				// recursive function!
				addInheritedPermissions($value,$usergroupid);
			}
		}
	}
}

// devise function to update permissions.. inherited ones
function updateInheritedPermissions($forumid,$usergroupid) {
	global $start_query, $key_query, $value_query, $query, $foruminfo, $forumPerms2;

	unset($childlist);
	unset($select_permission);
	unset($perms);

	// make sure this forum has a childlist.. since we've already added permissions to THIS forum.. just do childs
	if(!empty($foruminfo[$forumid]['childlist'])) {
		$childlist = explode(",",$foruminfo[$forumid]['childlist']);

		// loop through the childlist...
		foreach($childlist as $key => $value) {
			// so there is a row
			if($forumPerms2[$forumid][$usergroupid]['manual'] == 0) {
				// only do this if it isn't inherited...
				if($forumPerms2[$forumid][$usergroupid]['is_inherited'] == 0) {
					// run update query
					query($query.", is_inherited = '1' WHERE permissionsid = '".$forumPerms[$forumid][$usergroupid]['permissionsid']."'");

					//print($query.", is_inherited = '1' WHERE permissionsid = '".$perms['permissionsid']."'");

					// recursive function!
					updateInheritedPermissions($value,$usergroupid);
				}
			}

			// otherwise.. we need to add a row.. nothin to update
			else {
				// we don't even need the foruminfo.. all we have to do is add the permissions
				$query2 = $start_query.$key_query.",forumid,usergroupid,is_inherited) VALUES (".$value_query.",'".$value."','".$usergroupid."','1')";

				// run query
				query($query2);

				//print($query);

				// recursive function!
				updateInheritedPermissions($value,$usergroupid);
			}
		}
	}
}

// devise function to REMOVE permissions.. only for forums with no inheritance!
function removePermissions($forumid,$usergroupid) {
	global $foruminfo, $forumPerms2;

	// make sure this forum has a childlist.. 
	if(!empty($foruminfo[$forumid]['childlist'])) {
		$childlist = explode(",",$foruminfo[$forumid]['childlist']);

		// loop through the childlist...
		foreach($childlist as $key => $value) {
			if($forumPerms2[$value][$usergroupid]['is_inherited'] == 1) {
				// run recursion now.. before we delete above
				removePermissions($value,$usergroupid);

				// delete child permission
				query("DELETE FROM forums_permissions WHERE forumid = '".$value."' AND usergroupid = '".$usergroupid."' LIMIT 1");
			}
		}
	}

	// now we delete the permission itself
	query("DELETE FROM forums_permissions WHERE forumid = '".$forumid."' AND usergroupid = '".$usergroupid."'");
}


// create function to loop through announcements...
function loop_announcements($forum_id=-1,$isMod=false) {
	global $submitbg, $modinfo, $oForumInfo, $foruminfo, $wtcBB_adminUserid;

	// make sure a forum has this as a parent!!!!!
	if(!is_array($oForumInfo["$forum_id"])) {
		return;
	}

	// loop through forums
	foreach($oForumInfo["$forum_id"] as $displayOrder => $aRR2) {
		foreach($aRR2 as $key => $value) {
			// depthmark.. if it isn't depth 1
			if($foruminfo[$key]['depth'] > 1) {
				// unset the depthmark...
				unset($depthmark);

				// find the depth...
				for($i=1;$i < $foruminfo[$key]['depth'];$i++) {
					$depthmark .= "<strong>-- </strong>";
				}
			} else {
				$depthmark = "";
			}

			// run query to find announcements...
			$announce_query = mysql_query("SELECT * FROM announcements WHERE forum = '".$foruminfo[$key]['forumid']."' ORDER BY title");
		
			if($foruminfo[$key]['depth'] == 1) {
				print("\t<tr>\n");

					print("\t\t<td class=\"cat\">\n");
					print("\t\t\tForum\n");
					print("\t\t</td>\n\n");

					print("\t\t<td class=\"cat2\">\n");
					print("\t\t\tAnnouncements\n");
					print("\t\t</td>\n\n");

					print("\t\t<td class=\"cat2\">\n");
					print("\t\t\tAdd\n");
					print("\t\t</td>\n");

				print("\t</tr>\n\n");
			}

			print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"width: 30%; padding: 7px;\">\n");
			print("\t\t\t".$depthmark.$foruminfo[$key]['forum_name']."\n");
			print("\t\t</td>\n\n");

			print("\t\t<td class=\"desc2\" style=\"border-left: none; width: 70%; padding: 7px;\">\n");
			print("\t\t\t");

			$canPostAnnounce = false;
			
			// can post announcements?
			if(is_array($modinfo[$key])) {
				foreach($modinfo[$key] as $modid => $arr) {
					if($arr['can_post_announcements'] == 1 AND $_COOKIE['wtcBB_adminUserid'] == $arr['userid']) {
						$canPostAnnounce = true;
					}
				}
			}

			if(mysql_num_rows($announce_query) > 0) {

				print("<ul style=\"margin-bottom: 0px; margin-left: 20px;\">\n");

				while($announcement = mysql_fetch_array($announce_query)) {

					// get date...
					$start_date = date("m-d-y",$announcement['start_date']);
					$end_date = date("m-d-y",$announcement['end_date']);
					
					if($isMod == true AND $canPostAnnounce == true) {
						print("<li><strong>".$announcement['title']."</strong>");
						print("<form method=\"post\" action=\"\" style=\"margin: 0px; display: inline;\"><button type=\"button\" onClick=\"location.href='moderator.php?do=edit_announcement&amp;f=".$key."&amp;id=".$announcement['announcementid']."';\" style=\"margin-right: 5px; margin-bottom: 5px;\" ".$submitbg.">Edit</button> <button type=\"button\" onClick=\"location.href='moderator.php?do=edit_announcement&amp;f=".$key."&amp;id=".$announcement['announcementid']."&action=delete';\" style=\"margin-bottom: 5px;\" ".$submitbg.">Delete</button></form> <span class=\"small\"> &nbsp;&nbsp;(".$announcement['username'].") (".$start_date." to ".$end_date.")</span></li>\n");
					}

					else if($isMod == false) {
						print("<li><strong>".$announcement['title']."</strong>");
						print("<form method=\"post\" action=\"\" style=\"margin: 0px; display: inline;\"><button type=\"button\" onClick=\"location.href='announcement.php?do=edit&id=".$announcement['announcementid']."';\" style=\"margin-right: 5px; margin-bottom: 5px;\" ".$submitbg.">Edit</button> <button type=\"button\" onClick=\"location.href='announcement.php?do=edit&id=".$announcement['announcementid']."&action=delete';\" style=\"margin-bottom: 5px;\" ".$submitbg.">Delete</button></form> <span class=\"small\"> &nbsp;&nbsp;(".$announcement['username'].") (".$start_date." to ".$end_date.")</span></li>\n");
					}

					else {
						print("&nbsp;");
					}
				}

				print("</ul>\n\n");
			}

			else {
				print("&nbsp;\n");
			}

			print("\n");

			print("\t\t</td>\n\n");

			print("\t\t<td class=\"desc1\" style=\"border-left: none; width: 0%; padding: 7px;\">\n");
			print("\t\t\t<form style=\"margin: 0px;\" method=\"post\" action=\"\">\n");

			if($canPostAnnounce == true AND $isMod == true) {
				print("\t\t\t\t<button type=\"button\" onclick=\"location.href='moderator.php?do=add&amp;f=".$key."&amp;id=".$foruminfo[$key]['forumid']."';\" ".$submitbg.">NEW</button>\n");
			}

			else if($isMod == false) {
				print("\t\t\t\t<button type=\"button\" onclick=\"location.href='announcement.php?do=add&amp;id=".$foruminfo[$key]['forumid']."';\" ".$submitbg.">NEW</button>\n");
			}

			else {
				print("&nbsp;");
			}

			print("\t\t\t</form>\n");
			print("\t\t</td>\n\n");

			print("\t</tr>\n\n\n");

			// recursion
			if(!empty($foruminfo[$key]['childlist'])) {
				loop_announcements($foruminfo[$key]['forumid'],$isMod);
			}
		}
	}
}


// create a function for looping through all forums
function loop_forums($select=0,$category_parent="",$forum_id=-1,$isMod=false) {
	global $_POST, $order, $_GET, $is_announcement, $announce_stuff, $submitbg, $moderatorinfo, $foruminfo, $oForumInfo, $modinfo, $wtcBB_adminUserid;

	// make sure a forum has this as a parent!!!!!
	if(!is_array($oForumInfo["$forum_id"])) {
		return;
	}

	// loop through forums
	foreach($oForumInfo["$forum_id"] as $displayOrder => $aRR2) {
		foreach($aRR2 as $key => $value) {
			// depthmark.. if it isn't depth 1
			if($foruminfo[$key]['depth'] > 1) {
				// unset the depthmark...
				unset($depthmark);

				// find the depth...
				for($i=1;$i < $foruminfo[$key]['depth'];$i++) {
					$depthmark .= "<strong>-- </strong>";
				}
			} else {
				$depthmark = "";
			}

			if($select == 1) {
				if($isMod != false) {
					$modOfThisForum = false;
					
					foreach($modinfo[$key] as $modid => $arr) {
						if($_COOKIE['wtcBB_adminUserid'] == $arr['userid'] AND (($isMod == 2 AND $arr['can_massprune_threads'] == 1) OR ($isMod == 3 AND $arr['can_massmove_threads']))) {
							$modOfThisForum = true;
						}
					}

					if($modOfThisForum == false) {
						loop_forums($select,$category_parent,$foruminfo[$key]['forumid'],$isMod);
						continue;
					}
				}

				// get selected...
				if($_GET['do'] == "edit" AND !isset($is_announcement)) {
					if($category_parent == $foruminfo[$key]['forumid']) {
						$selecting = " selected=\"selected\"";
					} else {
						$selecting = "";
					}
				}
				
				else if($_GET['do'] == "edit" AND $is_announcement == true) {
					if($announce_stuff['forum'] == $foruminfo[$key]['forumid']) {
						$selecting = " selected=\"selected\"";
					} else {
						$selecting = "";
					}
				}

				else if($_GET['do'] == "edit_moderator") {
					if($moderatorinfo['forumid'] == $foruminfo[$key]['forumid']) {
						$selecting = " selected=\"selected\"";
					} else {
						$selecting = "";
					}
				}
				
				else {
					if(($_GET['do'] == "add" AND $_GET['id'] == $foruminfo[$key]['forumid']) OR ($_GET['do'] == "add_moderator" AND $_GET['forumid'] == $foruminfo[$key]['forumid'])) {
						$selecting = " selected=\"selected\"";
					} else {
						$selecting = "";
					}
				}

				print("<option value=\"".$foruminfo[$key]['forumid']."\"".$selecting.">".$depthmark.$foruminfo[$key]['forum_name']."</option>\n");
			}

			else {

				if($foruminfo[$key]['depth'] == 1) {
					print("\n\n\t<tr>\n");

						print("\t\t<td class=\"cat\">\n");
						print("\t\t\tTitle\n");
						print("\t\t</td>\n\n");

						print("\t\t<td class=\"cat2\">\n");
						print("\t\t\tOptions\n");
						print("\t\t</td>\n\n");

						print("\t\t<td class=\"cat2\">\n");
						print("\t\t\tDisplay Order\n");
						print("\t\t</td>\n");

						print("\t\t<td class=\"cat2\">\n");
						print("\t\t\tModerators\n");
						print("\t\t</td>\n");

					print("\t</tr>\n\n");
				}

				$x++;

				print("\t<tr>\n");

					print("\t\t<td class=\"desc1\" style=\"width: 30%; vertical-align: bottom; padding: 7px;\">\n");
					print("\t\t\t".$depthmark."<a href=\"forum.php?do=edit&id=".$foruminfo[$key]['forumid']."\">".$foruminfo[$key]['forum_name']."</a>\n");
					print("\t\t</td>\n\n");

					print("\t\t<td class=\"desc2\" style=\"border-left: none; width: 30%; vertical-align: bottom; padding: 7px; white-space: nowrap;\">\n");
					print("\t\t\t\t<select style=\"margin-bottom: 3px;\" name=\"control".$foruminfo[$key]['forumid']."\" onChange=\"location.href=(form.control".$foruminfo[$key]['forumid'].".options[form.control".$foruminfo[$key]['forumid'].".selectedIndex].value)\">\n");
					print("\t\t\t\t\t<option value=\"forum.php?do=edit&id=".$foruminfo[$key]['forumid']."\" selected=\"selected\">Edit Forum</option>\n");
					print("\t\t\t\t\t<option value=\"forum.php?do=edit&id=".$foruminfo[$key]['forumid']."&action=delete\">Delete Forum</option>\n");
					print("\t\t\t\t\t<option value=\"forum.php?do=add&id=".$foruminfo[$key]['forumid']."\">Add Child</option>\n");
					print("\t\t\t\t\t<option value=\"announcement.php?do=add&id=".$foruminfo[$key]['forumid']."\">Add Announcement</option>\n");
					print("\t\t\t\t\t<option value=\"forum.php?do=add_moderator&forumid=".$foruminfo[$key]['forumid']."\">Add Moderator</option>\n");
					print("\t\t\t\t\t<option value=\"./../forum.php?f=".$foruminfo[$key]['forumid']."\">View Forum</option>\n");
					print("\t\t\t\t</select>\n");
					print("\t\t\t\t <button type=\"button\" onClick=\"location.href=(form.control".$foruminfo[$key]['forumid'].".options[form.control".$foruminfo[$key]['forumid'].".selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px; margin-top: 0px;\" ".$submitbg.">Go</button>\n");
					print("\t\t</td>\n\n");

					print("\t\t<td class=\"desc1\" style=\"border-left: none; width: 30%; vertical-align: bottom; padding: 7px; white-space: nowrap;\">\n");
					print("\t\t\t<input type=\"text\" name=\"update_forum_order[".$foruminfo[$key]['forumid']."]\" class=\"text\" value=\"".$foruminfo[$key]['display_order']."\" style=\"width: 10%; margin: 0px; padding: 1px;\" size=\"1\" />\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"border-left: none; width: 30%; vertical-align: bottom; padding: 7px;\">\n");

						print("\t\t\t<select style=\"margin-bottom: 3px;\" name=\"mod".$foruminfo[$key]['forumid']."1\" onChange=\"location.href=(form.mod".$foruminfo[$key]['forumid']."1.options[form.mod".$foruminfo[$key]['forumid']."1.selectedIndex].value)\">\n");
						print("\t\t\t\t<option value=\"#\" selected=\"selected\">Moderators: <strong>".count($modinfo[$foruminfo[$key]['forumid']])."</strong></option>\n");

						// only get moderators if there are some... to avoid errors
						if(is_array($modinfo[$foruminfo[$key]['forumid']])) {
							foreach($modinfo[$foruminfo[$key]['forumid']] as $moderators) {
								print("\t\t\t\t<option value=\"forum.php?do=edit_moderator&forumid=".$foruminfo[$key]['forumid']."&userid=".$moderators['moderatorid']."\">&nbsp;&nbsp;&nbsp;&nbsp;".$moderators['username']."</option>\n");
							}
						}

						print("\t\t\t\t<option value=\"forum.php?do=add_moderator&forumid=".$foruminfo[$key]['forumid']."\">Add Moderator</option>\n");
						print("\t\t\t</select>\n\n");
						print("\t\t\t<button type=\"button\" onClick=\"location.href=(form.mod".$foruminfo[$key]['forumid']."1.options[form.mod".$foruminfo[$key]['forumid']."1.selectedIndex].value)\" style=\"margin: 0px; margin-bottom: 4px; margin-left: 2px;\" ".$submitbg.">Go</button>\n");
					print("\t\t</td>\n\n");

				print("\t</tr>\n\n\n");
			}

			// recursion
			if(!empty($foruminfo[$key]['childlist'])) {
				loop_forums($select,$category_parent,$foruminfo[$key]['forumid'],$isMod);
			}
		}
	}
}

// errmmmm.. this is for a check when editing a forum.. making sure we aren't parenting a forum to one of it's childs...
function checkChilds($forumid,$forumidCompare) {
	global $foruminfo;

	if(!empty($foruminfo[$forumid]['childlist'])) {
		// get child list
		$childList = explode(",",$foruminfo[$forumid]['childlist']);

		// loop through child list to see if we can find a match...
		foreach($childList as $key => $value) {
			// match!
			if($value == $forumidCompare) {
				// return true
				return true;
			}

			// recursion
			checkChilds($value,$forumidCompare);
		}
	}

	// if we made it this far.. no match! return false...
	return false;
}


// create function to DELETE forums... more recursive functions!
function delete_forum($forumid) {	
	global $foruminfo;

	// look for sub-forums.. and use separate function to delete them...
	if(!empty($foruminfo[$forumid]['childlist'])) {
		delete_subforums($foruminfo[$forumid]['forumid'],$foruminfo[$forumid]['childlist']);
	}

	// before we delete.. we need to find the childlists that is was on... which is ONLY <i>direct</i> parents... 
	// but make sure it isn't depth 1..
	if($foruminfo[$forumid]['depth'] > 1) {
		// loop through the forums childlist.. and look for the id...
		$child_list = explode(",",$foruminfo[$foruminfo[$forumid]['category_parent']]['childlist']);

		// intiate counter so we know when to not use a comma...
		$counter = 1;

		foreach($child_list as $key => $value) {
			// find the id...
			if($value == $forumid) {
				// unset value for this loop...
				unset($value);
			}

			// otherwise.. we are good to go...
			else {
				if($counter == 1) {
					$new_childlist .= $value;
					$counter++;
				}

				else {
					$new_childlist .= ",".$value;
				}
			}
		}

		// now let's update the current childlist with the new one.. and end this craaaazy loop!
		query("UPDATE forums SET childlist = '".$new_childlist."' WHERE forumid = '".$foruminfo[$forumid]['category_parent']."' LIMIT 1");
	}

	// delete any moderators belonging to this forum...
	query("DELETE FROM moderators WHERE forumid = '".$forumid."'");

	// delete any announcements belonging to this forum
	query("DELETE FROM announcements WHERE forum = '".$forumid."'");

	// delete posts
	query("DELETE FROM posts WHERE forumid = '".$forumid."'");

	// delete threads
	query("DELETE FROM threads WHERE forumid = '".$forumid."'");

	// delete any permissions made by this forum
	query("DELETE FROM forums_permissions WHERE forumid = '".$forumid."'");

	// now delete main forum...
	query("DELETE FROM forums WHERE forumid = '".$forumid."' LIMIT 1");
}


// form function to delete all sub-forums of the current forum...
function delete_subforums($forum_delete_id,$get_childlist) {
	global $foruminfo;

	$childlist = explode(",",$get_childlist);

	// loop through the childlist...
	foreach($childlist as $key => $value) {
		if(!empty($foruminfo[$value]['childlist'])) {
			// recrusive function time... 
			delete_subforums($current_forum['forumid'],$foruminfo[$value]['childlist']);
		}

		// delete any moderators belonging to this forum
		query("DELETE FROM moderators WHERE forumid = '".$foruminfo[$value]['forumid']."'");

		// delete any announcements belonging to this forum
		query("DELETE FROM announcements WHERE forum = '".$foruminfo[$value]['forumid']."'");

		// delete posts
		query("DELETE FROM posts WHERE forumid = '".$foruminfo[$value]['forumid']."'");

		// delete threads
		query("DELETE FROM threads WHERE forumid = '".$foruminfo[$value]['forumid']."'");

		// delete any permissions made by this forum
		query("DELETE FROM forums_permissions WHERE forumid = '".$foruminfo[$value]['forumid']."'");

		// now it's safe to say we can delete the forum we are on... we should have to take care of anything else...
		query("DELETE FROM forums WHERE forumid = '".$foruminfo[$value]['forumid']."' LIMIT 1");
	}
}

function update_depth($forumid) {
	global $oForumInfo, $foruminfo;

	// make sure a forum has this as a parent!!!!!
	if(!is_array($oForumInfo["$forumid"])) {
		return;
	}

	// loop through forums
	foreach($oForumInfo["$forumid"] as $displayOrder => $aRR2) {
		foreach($aRR2 as $key => $value) {
			// update depth...
			query("UPDATE forums SET depth = '".($foruminfo[$foruminfo[$key]['category_parent']]['depth']+1)."' WHERE forumid = '".$foruminfo[$key]['forumid']."'");

			// recursion
			update_depth($foruminfo[$key]['forumid']);
		}
	}
}

function construct_select_months($offset=0,$edit_announcements=0,$start_end=0,$null_select=0,$edit_user=0) {

	global $_GET, $userinfo, $birthday;

	$current_month = date("n");

	if($offset != 0) {
		$current_month += $offset;
	}

	$month1 = "";
	$month2 = "";
	$month3 = "";
	$month4 = "";
	$month5 = "";
	$month6 = "";
	$month7 = "";
	$month8 = "";
	$month9 = "";
	$month10 = "";
	$month11 = "";
	$month12 = "";

	if($null_select != 0) {
		$special_select = " selected=\"selected\"";
	}

	else {

		if($edit_announcements == 0) {
			if($current_month == 1) {
				$month1 = " selected=\"selected\"";
			} else if($current_month == 2) {
				$month2 = " selected=\"selected\"";
			} else if($current_month == 3) {
				$month3 = " selected=\"selected\"";
			} else if($current_month == 4) {
				$month4 = " selected=\"selected\"";
			} else if($current_month == 5) {
				$month5 = " selected=\"selected\"";
			} else if($current_month == 6) {
				$month6 = " selected=\"selected\"";
			} else if($current_month == 7) {
				$month7 = " selected=\"selected\"";
			} else if($current_month == 8) {
				$month8 = " selected=\"selected\"";
			} else if($current_month == 9) {
				$month9 = " selected=\"selected\"";
			} else if($current_month == 10) {
				$month10 = " selected=\"selected\"";
			} else if($current_month == 11) {
				$month11 = " selected=\"selected\"";
			} else if($current_month == 12) {
				$month12 = " selected=\"selected\"";
			}
		}

		else if(!empty($edit_user)) {
			
			// get month
			if($edit_user == "birthday") {
				$userinfo_month = $birthday[0];
			} else {
				$userinfo_month = date("m",$userinfo[$edit_user]);
			}

			if($userinfo_month == 1) {
				$month1 = " selected=\"selected\"";
			} else if($userinfo_month == 2) {
				$month2 = " selected=\"selected\"";
			} else if($userinfo_month == 3) {
				$month3 = " selected=\"selected\"";
			} else if($userinfo_month == 4) {
				$month4 = " selected=\"selected\"";
			} else if($userinfo_month == 5) {
				$month5 = " selected=\"selected\"";
			} else if($userinfo_month == 6) {
				$month6 = " selected=\"selected\"";
			} else if($userinfo_month == 7) {
				$month7 = " selected=\"selected\"";
			} else if($userinfo_month == 8) {
				$month8 = " selected=\"selected\"";
			} else if($userinfo_month == 9) {
				$month9 = " selected=\"selected\"";
			} else if($userinfo_month == 10) {
				$month10 = " selected=\"selected\"";
			} else if($userinfo_month == 11) {
				$month11 = " selected=\"selected\"";
			} else if($userinfo_month == 12) {
				$month12 = " selected=\"selected\"";
			}
		}

		else {
			// select the current month from the db...
			$current_month_array = query("SELECT * FROM announcements WHERE announcementid = '".$_GET['id']."' LIMIT 1",1);

			if($start_end == 1) {
				// now break down the unix timestamp into months...
				$current_month = date("n",$current_month_array['start_date']);

				if($current_month == 1) {
					$month1 = " selected=\"selected\"";
				} else if($current_month == 2) {
					$month2 = " selected=\"selected\"";
				} else if($current_month == 3) {
					$month3 = " selected=\"selected\"";
				} else if($current_month == 4) {
					$month4 = " selected=\"selected\"";
				} else if($current_month == 5) {
					$month5 = " selected=\"selected\"";
				} else if($current_month == 6) {
					$month6 = " selected=\"selected\"";
				} else if($current_month == 7) {
					$month7 = " selected=\"selected\"";
				} else if($current_month == 8) {
					$month8 = " selected=\"selected\"";
				} else if($current_month == 9) {
					$month9 = " selected=\"selected\"";
				} else if($current_month == 10) {
					$month10 = " selected=\"selected\"";
				} else if($current_month == 11) {
					$month11 = " selected=\"selected\"";
				} else if($current_month == 12) {
					$month12 = " selected=\"selected\"";
				}
			}

			else {
				// now break down the unix timestamp into months...
				$current_month = date("n",$current_month_array['end_date']);

				if($current_month == 1) {
					$month1 = " selected=\"selected\"";
				} else if($current_month == 2) {
					$month2 = " selected=\"selected\"";
				} else if($current_month == 3) {
					$month3 = " selected=\"selected\"";
				} else if($current_month == 4) {
					$month4 = " selected=\"selected\"";
				} else if($current_month == 5) {
					$month5 = " selected=\"selected\"";
				} else if($current_month == 6) {
					$month6 = " selected=\"selected\"";
				} else if($current_month == 7) {
					$month7 = " selected=\"selected\"";
				} else if($current_month == 8) {
					$month8 = " selected=\"selected\"";
				} else if($current_month == 9) {
					$month9 = " selected=\"selected\"";
				} else if($current_month == 10) {
					$month10 = " selected=\"selected\"";
				} else if($current_month == 11) {
					$month11 = " selected=\"selected\"";
				} else if($current_month == 12) {
					$month12 = " selected=\"selected\"";
				}
			}
		}
	}

	print("<option value=\"0\"".$special_select.">- - - -</option>\n");
	print("<option value=\"1\"".$month1.">January</option>\n");
	print("<option value=\"2\"".$month2.">February</option>\n");
	print("<option value=\"3\"".$month3.">March</option>\n");
	print("<option value=\"4\"".$month4.">April</option>\n");
	print("<option value=\"5\"".$month5.">May</option>\n");
	print("<option value=\"6\"".$month6.">June</option>\n");
	print("<option value=\"7\"".$month7.">July</option>\n");
	print("<option value=\"8\"".$month8.">August</option>\n");
	print("<option value=\"9\"".$month9.">September</option>\n");
	print("<option value=\"10\"".$month10.">October</option>\n");
	print("<option value=\"11\"".$month11.">November</option>\n");
	print("<option value=\"12\"".$month12.">December</option>\n");
}

// make function to find if the viewing user is a super admin...
function isSuperAdmin() {
	global $super_administrator;

	$superAdmins = split(",",$super_administrator);

	// loop through the super admins to see if we can find a match
	foreach($superAdmins as $option_value) {
		// we have a match! return true
		if($_COOKIE['wtcBB_adminUserid'] == $option_value) {
			return true;
		}
	}

	// if we're here... then we didn't get a match :( .. return false :( :( :(
	return false;
}

// make function to see if the given user is undeletable
function isUndeletable($userid) {
	global $uneditable_user;

	$users = split(",",$uneditable_user);

	// loop through the users to see if we can find a match
	foreach($users as $option_value) {
		// we have a match! return true
		if($userid == $option_value) {
			return true;
		}
	}

	// if we're here... then we didn't get a match :( .. return false :( :( :(
	return false;
}

// devise function for who can view administrative logs...
function canViewAdminLog($userid) {
	global $can_view_adminlog;

	$users = split(",",$can_view_adminlog);

	// loop through the users to see if we can find a match
	foreach($users as $option_value) {
		// we have a match! return true
		if($userid == $option_value) {
			return true;
		}
	}

	// if we're here... then we didn't get a match :( .. return false :( :( :(
	return false;
}

// devise function for who can delete administrative logs...
function canPruneAdminLog($userid) {
	global $can_prune_adminlog;

	$users = split(",",$can_prune_adminlog);

	// loop through the users to see if we can find a match
	foreach($users as $option_value) {
		// we have a match! return true
		if($userid == $option_value) {
			return true;
		}
	}

	// if we're here... then we didn't get a match :( .. return false :( :( :(
	return false;
}

?>