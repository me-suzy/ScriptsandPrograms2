<?
// Automatically get $tpath to avoid possible security holes
$tpath = realpath(__FILE__);
$tpath = substr($tpath,0,strrpos($tpath,DIRECTORY_SEPARATOR)+1);
// Check if the file exists on local server and include it
if(file_exists($tpath . "cn_config.php")) {
	require_once($tpath . "cn_config.php");
} else {
	die("Could not include required configuration file");
}

// Set limits for multiple pages 
if(!isset($pg)) { $pg = 1; }
// Number of news items to display per page 
$pgset = $set[newslimit];
$lims = ($pg-1)*$pgset;

// If article id is set [v1.01] 
if(isset($_REQUEST[a])) {
// Comment posting [v1.12] 
	if($_POST[post] == "comment") {
		$q[userchk] = mysql_query("SELECT * FROM $t_user WHERE user = '$_POST[name]' LIMIT 1", $link) or E("Couldn't check reserved usernames:<br />" . mysql_error());
		$usernum = mysql_num_rows($q[userchk]);
		if(!isset($_POST[passw]) || $usernum == "0") {
			if($usernum == "1") {
				?></p><strong>Error</strong></p>
				<p>The username you entered is currently reserved.  To post with this username, please enter a password below:</p>
				<div style="text-align:center">
				<form method="post" action="<?=$_SERVER[PHP_SELF]?>">
				<fieldset>
				<legend>Enter Password</legend>
				Password: <input type="password" name="passw" />
				<input type="hidden" name="name" value="<?=$_POST[name]?>" />
				<input type="hidden" name="email" value="<?=$_POST[email]?>" />
				<input type="hidden" name="comment" value="<?=$_POST[comment]?>" />
				<input type="hidden" name="a" value="<?=$_POST[a]?>" />
				<input type="hidden" name="post" value="comment" />
				<input type="submit" value="Submit" />
				</fieldset>
				</form></div><?
			} else {
				// Post comment 
				if(empty($_POST[name]) || empty($_POST[email]) || empty($_POST[comment])) {
					print("<p>Please fill-in all fields.  All fields are required to post a comment.</p>");
				} else {
					// Strip HTML tags [v1.13]
					$_POST['name'] = cn_htmltrans($_POST['name'],'text');
					$_POST['comment'] = cn_htmltrans($_POST['comment'],'text');
					$q[ins_com] = mysql_query("INSERT INTO $t_coms (id, news_id, name, email, comment, date, ip) VALUES ('', '$_POST[a]', '$_POST[name]', '$_POST[email]', '$_POST[comment]', '$now', '$_SERVER[REMOTE_ADDR]')", $link) or E("Couldn't insert new comment:<br />" . mysql_error());
					print "<p>Your comment has been added.<br />[ <a href=\"$_SERVER[PHP_SELF]" . cn_buildQueryString(array('a'=>$_POST[a])) . "\">&lt;&lt; Go Back</a> ]</p>";
				}
			}
	} else {
		$q[userchk] = mysql_query("SELECT * FROM $t_user WHERE user = '$_POST[name]' AND pass='$_POST[passw]' LIMIT 1", $link) or E("Couldn't check user info:<br />" . mysql_error());
		if(mysql_num_rows($q[userchk]) == "1") {
			// Post comment with protected username 
			$_POST['name'] = cn_htmltrans($_POST['name'],'text');
			$_POST['comment'] = cn_htmltrans($_POST['comment'],'text');
			$q[ins_com] = mysql_query("INSERT INTO $t_coms (id, news_id, name, email, comment, date, ip) VALUES ('', '$_POST[a]', '$_POST[name]', '$_POST[email]', '$_POST[comment]', '$now', '$_SERVER[REMOTE_ADDR]')", $link) or E("Couldn't insert new comment:<br />" . mysql_error());
			print "<p>Your comment has been added.<br />[ <a href=\"$_SERVER[PHP_SELF]" . cn_buildQueryString(array('a'=>$_POST[a])) . "\">&lt;&lt; Go Back</a> ]</p>";
		} else {
			print "<p>The password you entered was incorrect.<br />[ <a href=\"javascript:history.go(-1)\">&lt;&lt; Go Back</a> ]</p>";
		}
	}


	} else {
	$q[info] = mysql_query("SELECT * FROM $t_news WHERE id = '$_REQUEST[a]' LIMIT 1", $link) or E("Couldn't select news article:<br />" . mysql_error());
	$newsnum = mysql_num_rows($q[info]);
	}

// If search is performed [v1.12] 
} elseif(isset($_REQUEST[s])) {
	$q[info] = mysql_query("SELECT * FROM $t_news WHERE content LIKE '%$_REQUEST[s]%' ORDER BY date DESC LIMIT $lims, $pgset", $link) or E("Couldn't search news articles:<br />" . mysql_error());
	$newsnum = mysql_num_rows($q[info]);
	
	// Retrieve all news items	
} else {

	$extra = "";
	if(!empty($c)) { $_REQUEST['c'] = "$c"; }
	if($_REQUEST['c'] != "") { $extra = "WHERE cat = '$_REQUEST[c]'"; }
	$q[cats] = mysql_query("SELECT * FROM $t_cats ORDER BY name ASC", $link) or E("Couldn't select categories:<br />" . mysql_error());
	$catnum = mysql_num_rows($q[cats]);
	
	$q[info] = mysql_query("SELECT * FROM $t_news $extra ORDER BY date DESC LIMIT $lims, $pgset", $link) or E("Couldn't select news:<br />" . mysql_error());
	$q[countn] = mysql_query("SELECT COUNT(id) as newscount FROM $t_news $extra", $link) or E("Couldn't count news:<br />" . mysql_error());
	$newsnum = mysql_result($q[countn],newscount);
	
	if($catnum > "1" && $set[catbox] == "on") { ?>
		<div>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<span style="width: 49%; float: left; text-align: left;"><strong>View news posted in category:</strong></span>
		<span style="width: 49%; float: right; text-align: right;"><? print cn_catBox("c","$_REQUEST[c]","yes","news"); ?></span>
		</form>
		</div>
		<br />
		<?
	}

}

if($newsnum == "0") {
	print "<p>No records found in database</p><br />";
}

if(isset($q[info])) { // $q[info] check 

	// Retrieve array of images from database [v1.14]
	$q[images] = mysql_query("SELECT id, filename, thumbname FROM $t_img", $link) or E("Couldn't select news images:<br/>" . mysql_error());
	while($img = mysql_fetch_array($q[images])) {
		if(empty($img[thumbname])) {
			$imgarr[$img[id]] = "$img[filename]";
		} else {
			$imgarr[$img[id]] = "$img[thumbname]";
		}
	}
	
	// Begin output of news items 
	while ($r = mysql_fetch_array($q[info], MYSQL_ASSOC)) {
		$auid = $r[author];
		$output = $set[output];
		$r[subject] = "<a name=\"cn_$r[id]\"></a>$r[subject]";
		$serv_tzone = (date("Z")/3600);
		// Format date to current timezone, using format setting specified 
		$r[date] = date("$set[dateform]", cn_zonechange("$serv_tzone", "$set[timezone]", "$r[date]"));
		
		// Print category name for current news item [v1.13]
		$catname = cn_getinfo($r[cat],"name",$t_cats);
		$output = str_replace("{cat}", "$catname", $output);
		
		// Add line breaks to both content areas only [v1.13 fix]
		$content = str_replace("\n", "<br />\n", $r[content]);
		$content2 = str_replace("\n", "<br />\n", $r[content2]);
		
		// Replace image tags with proper images [v1.14]
		if(is_array($imgarr)) {
			foreach($imgarr as $imgid => $filename) {
				$content = str_replace("{img:$imgid}","" . cn_showImage($filename,"left") . "", $content);
				$content = str_replace("{img:$imgid|left}","" . cn_showImage($filename,"left") . "", $content);
				$content = str_replace("{img:$imgid|center}","" . cn_showImage($filename,"center") . "", $content);
				$content = str_replace("{img:$imgid|right}","" . cn_showImage($filename,"right") . "", $content);
				$content2 = str_replace("{img:$imgid}","" . cn_showImage($filename,"left") . "", $content2);
				$content2 = str_replace("{img:$imgid|left}","" . cn_showImage($filename,"left") . "", $content2);
				$content2 = str_replace("{img:$imgid|center}","" . cn_showImage($filename,"center") . "", $content2);
				$content2 = str_replace("{img:$imgid|right}","" . cn_showImage($filename,"right") . "", $content2);
			}
		}
		
		// Highlight searched item in returned news if search is performed and searches are allowed [v1.12] 
		if(isset($_REQUEST['s'])) {
			$content = cn_highlight(stripslashes($content), "$_REQUEST[s]"); // case-insensitive or partial word search 
			//$output = str_replace("$_REQUEST[s]", "<span style=\"background-color:yellow; color: black\">$s</span>", $output); // exact word search 
		}
	
		// Summarize story option [v1.12] 
		if($r[sumstory] == "on" && isset($_REQUEST['a'])) { 
			$output = str_replace("{news}", "$content<br /><br />$content2", $output);
		} elseif($r[sumstory] == "on") {
			$output = str_replace("{news}", "$content<br /><small><a href=\"" . $_SERVER['PHP_SELF'] . "?a=$r[id]\">Read More...</a></small>", $output);
		} else {
			$output = str_replace("{news}", "$content", $output);
		}
		
	
		// Use Keywords and word filter if filter is turned "on" [v1.12] 
		if($set[words] == "on") {
			$q[words] = mysql_query("SELECT * FROM $t_words ORDER BY word ASC", $link) or E("Couldn't select keywords:<br />" . mysql_error());
			while ($w = mysql_fetch_array($q[words], MYSQL_ASSOC)) {
				if($w[type] == "link") {
					$w[replaced] = "<a href=\"$w[replaced]\" target=\"_blank\">$w[word]</a>";
				} elseif($w[type] == "picture") {
					$w[replaced] = "<img src=\"$w[replaced]\" alt=\"$w[word]\" />";
				}
				$output = str_replace("$w[word]", "$w[replaced]", $output);
			}
		}
	
		// Build user-defined source link [v1.12] 
		if(empty($r[source]) || empty($r[sourceurl])) {
			$output = str_replace("{source}", "", $output);
		} else {
			if(empty($set[source])) {
				$setsource = "<a href=\"$r[sourceurl]\" target=\"_blank\">$r[source]</a>";
				$output = str_replace("{source}", "$setsource", $output);
			} else {
				$setsource = str_replace("{sname}", "$r[source]", $set[source]);
				$setsource = str_replace("{surl}", "$r[sourceurl]", $setsource);
				$output = str_replace("{source}", "$setsource", $output);
			}
		}
	
		// Build user-defined author link [v1.12] 
		if(empty($set[author])) {
			$setauthor = "<a href=\"mailto:" . cn_getinfo($r[author],"email") . "\">" . cn_getinfo($r[author]) . "</a>";
			$output = str_replace("{author}", "$setauthor", $output);
		} else {
			$setauthor = str_replace("{aemail}", "" . cn_getinfo($r[author], "email") . "", $set[author]);
			$setauthor = str_replace("{aname}", "" . cn_getinfo($r[author]) . "", $setauthor);
			$output = str_replace("{author}", "$setauthor", $output);
		}
		
		$output = str_replace("{subject}", "$r[subject]", $output);
		$output = str_replace("{date}", "$r[date]", $output);
		
		// Transform news output to HTML code 
		$output =  cn_htmltrans($output,'html');
		
		// View/Post Comments Link [v1.12] 
		if($set[comments] == "on" && !isset($_REQUEST['a'])) {
			$q[comsn] = mysql_query("SELECT COUNT(id) as comscount FROM $t_coms WHERE news_id = '$r[id]'", $link) or E("Couldn't count comments for current news article:<br />" . mysql_error());
			$comsnum = mysql_result($q[comsn],comscount);
			if(empty($set[coms_text])) {
			$output = str_replace("{comments}", "<a href=\"" . $_SERVER['PHP_SELF'] . "" . cn_buildQueryString(array('a'=>$r[id])) . "\"><small>View/Post Comments ($comsnum)</small></a>", $output);
			} else {
				$setcoms_text = str_replace("{cnum}", "$comsnum", $set[coms_text]);
				$output = str_replace("{comments}", "<a href=\"" . $_SERVER['PHP_SELF'] . "" . cn_buildQueryString(array('a'=>$r[id])) . "\"><small>$setcoms_text</small></a>", $output);
			}
		} elseif(isset($_REQUEST['a'])) {
			$output = str_replace("{comments}", "<br /><small>[ <a href=\"" . $_SERVER['PHP_SELF'] . "" . cn_buildQueryString(array('a'=>'')) . "\">&lt;&lt; Return to News Page</a> ]</small>", $output);
		} else {
			$output = str_replace("{comments}", "", $output);
		}

		// Output formatted news 
		echo $output;
	
	} // End While (output of news items) 
	
	// If single article is viewed, and comments are turned "on", list comments [v1.12] 
	if(isset($_REQUEST['a']) && $set[comments] == "on") {
		$q[coms] = mysql_query("SELECT * FROM $t_coms WHERE news_id = '$_REQUEST[a]' ORDER BY date ASC", $link) or E("Couldn't select comments for current news article:<br />" . mysql_error());
		$comsnum = mysql_num_rows($q[coms]);
		if($comsnum != "0") {
			?>
			<hr />
			<p><strong>Comments: (<?=$comsnum?> total)</strong></p>
			<?
			while ($c = mysql_fetch_array($q[coms], MYSQL_ASSOC)) {
				echo "
				<p>\n
				<strong>Name:</strong> $c[name]<br />\n
				<strong>Email:</strong> <a href=\"mailto:$c[email]\">$c[email]</a><br />\n
				<strong>Comments:</strong> " . stripslashes(nl2br($c['comment'])) . "<br />\n
				</p>\n
				";
			}
		}
		?>
		<hr />
		<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>">
		<fieldset>
		<legend>Post a Comment</legend>
		<p>
		<em>Name:</em><br />
		<input type="text" name="name" /><br />
		<em>Email:</em><br />
		<input type="text" name="email" /><br />
		<em>Comment:</em><br />
		<textarea rows="4" cols="30" name="comment"></textarea><br />
		<input type="submit" value="Add Comment" />
		<input type="hidden" name="a" value="<? print $_REQUEST['a']; ?>" />
		<input type="hidden" name="post" value="comment" />
		</p>
		</fieldset>
		</form>
		<?
	}
	
	if(!isset($_REQUEST['a'])) {
		?>
		<form method="post" action="<?=$_SERVER[PHP_SELF]?>">
		<div>
		<? if($set[search] == "on" && !isset($_REQUEST[a])) { ?>
		<small>News Search:</small>
		<span style="width: 49%; float: left; text-align: left;">
			<input type="text" name="s" value="<?=$_REQUEST[s]?>" /><input type="submit" value="Search" />
		</span>
		<? } ?>
		<span style="width: 49%; float: right; text-align: center;">
		<?
		$extras = "";
		if($c != "") { $extras .= "&c=$c"; }
		
		### Page numbering code 
		if($newsnum != "1" && $set[pages] == "on") {
			if ($newsnum > $pgset) {
				if ($pg != "1") {
					$pgn = $pg-1;
					print "<a href=\"$_SERVER[PHP_SELF]" . cn_buildQueryString(array('pg'=>$pgn,'c'=>$_REQUEST[c],'a'=>'')) . "\">";
					print "&lt;&lt; Prev";
					print "</a>&nbsp;&nbsp;";
				}
				
				$totalpages = ceil($newsnum / $pgset);
				for ($loop = 1; ;$loop++) {
					if ($loop > $totalpages) {
						break;
					}
					if ($loop == $pg) {
						print "<b>$loop</b>";
						print "&nbsp;&nbsp;";
					}
					else {
						print "<a href=\"$_SERVER[PHP_SELF]" . cn_buildQueryString(array('pg'=>$loop,'c'=>$_REQUEST[c],'a'=>'')) . "\">";
						print $loop;
						print "</a>";
						print "&nbsp;&nbsp;";
					}
				}
			}
		
			if ($pg < $totalpages) {
				$pgn = $pg+1;
				print "<a href=\"$_SERVER[PHP_SELF]" . cn_buildQueryString(array('pg'=>$pgn,'c'=>$_REQUEST[c],'a'=>'')) . "\">";
				print "Next &gt;&gt;";
				print "</a>";
			}
		}
		?>
		</span>
		</div>
		</form>
		<?
	}
} // End $q[info] check 

/*
###################################

PLEASE DO NOT REMOVE THE LINK BELOW
A lot of effort went into the creation of this script, and I give it away for free without
asking for any money in return.  The least you can do is link back to www.czaries.net to
give me credit for making the script.

Please either:
a) Leave this link intact where it is
b) Remove the link and link to www.czaries.net somewhere else on your website
c) Pay $35 for full rights to remove the link and all mention of my authorship of the script
   [ Paypal email: czaries@czaries.net ].  Please type in the URL of your website
   in the 'comments' field if you choose this route.

Note: If you are re-selling CzarNews (like installing it on a client's website and charging for
      it), please pay the $35 (option c) and remove the copyrights for a professional look.

I do appreciate that you have chosen to use my news script.

  Vance Lucas aka "Czaries"
  http://www.czaries.net

###################################
*/

?>
<br />
<div style="clear: both;">
<small>News Managed by <a href="http://www.czaries.net/scripts/" class="creditlink">CzarNews</a></small>
</div>