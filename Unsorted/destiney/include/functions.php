<?php

if(isset($debug) && $debug){
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
} else {
	error_reporting(E_ERROR|E_WARNING|E_PARSE);
	ini_set("display_errors", 0);
}

if($db = mysql_pconnect($dbhost, $dbuser, $dbpasswd)){
	mysql_select_db($dbname, $db);
} else {
	echo mysql_error();
	exit();
}

function increment_user_comment_count($user_id){
	global $tb_users;
	$sql = "
		update
			$tb_users
		set
			total_comments = total_comments + 1
		where
			id = '$user_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function get_user_types_count(){
global $tb_user_types;
	$sql = "
		select
			count(*) as count
		from
			$tb_user_types
	";
	$query = mysql_query($sql) or die(mysql_error());
	return (int) mysql_result($query, 0, "count");
}

function get_email($id){
global $tb_users;
$sql = "
	select
		email
	from
		$tb_users
	where
		id = '$id'
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query))
	return mysql_result($query, 0, "email");
return "Email not found";
}

function get_age_options($selected){
global $low_age_limit, $high_age_limit;
$options = "";
for($x = $low_age_limit; $x <= $high_age_limit; $x++){
$options .= <<<EOF
<option value="$x"
EOF;
if($x == $selected) $options .= " selected";
$options .= <<<EOF
>$x</option>
EOF;
}
return $options;
}

function getmicrotime(){ 
	list($usec, $sec) = explode(" ", microtime()); 
	return ((float) $usec + (float) $sec); 
}

function get_states_list($selected){
global $states_array;
	$html = "";
	reset($states_array);
	while(list(, $state) = each($states_array)){
			$html .= "<option value=\"" . $state . "\"";
			if($selected == $state){$html .= " selected=\"selected\"";}
			$html .= ">" . $state . "</option>\n";
	}
	return $html;
}

function insert_comment_thread($comment_id){
global $tb_comment_threads;
	$sql = "
		insert into $tb_comment_threads (
			comment_id,
			updated,
			timestamp
		) values (
			'$comment_id',
			now(),
			now()
		)
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function delete_thread_views($thread_id){
global $tb_thread_views;
	$sql = "
		delete from
			$tb_thread_views
		where
			thread_id = '$thread_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function delete_posts($thread_id){
global $tb_posts;
	$sql = "
		delete from
			$tb_posts
		where
			thread_id = '$thread_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function delete_threads($forum_id){
global $tb_threads;
	$sql = "
		select
			thread_id
		from
			$tb_threads
		where
			forum_id = '$forum_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		while($array = mysql_fetch_array($query)){
			delete_posts($array["thread_id"]);
			delete_thread_views($array["thread_id"]);
		}
	}
	$d_sql = "
		delete from
			$tb_threads
		where
			forum_id = '$forum_id'
	";
	$d_query = mysql_query($d_sql) or die(mysql_error());
}

function delete_forum($forum_id){
global $tb_forums;
	delete_sub_forums($forum_id);
	$sql = "
		delete from
			$tb_forums
		where
			forum_id = '$forum_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function get_forum_id_from_forum_pid($forum_pid){
global $tb_forums;
	$sql = "
		select
			forum_id
		from
			$tb_forums
		where
			forum_pid = '$forum_pid'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return mysql_result($query, 0, "forum_id");
	}
	return 0;
}

function delete_sub_forums($forum_pid){
global $tb_forums;
	delete_threads(get_forum_id_from_forum_pid($forum_pid));
	$sql = "
		delete from
			$tb_forums
		where
			forum_pid = '$forum_pid'
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function get_gender_types_sql($gender){
global $tb_user_types;
	$return = "";
	$sql = "
		select
			id
		from
			$tb_user_types
		where
			gender = '$gender'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$gender_array = array();
		while($array = mysql_fetch_array($query)){
			$gender_array[] = $array["id"];
		}
		reset($gender_array);
		for($x=0; $x < sizeof($gender_array); $x++){
			$return .= "user_type = " . $gender_array[$x] . " ";
			if($x < sizeof($gender_array)-1){
				$return .= "or ";
			}
		}
	} else {
		$return = "(1)";
	}
	return $return;
}

function toplist_nav_links($nr, $cpp, $pnp, $pn, $url){
global $csr, $sn, $sid;
	if(!isset($pn)) $pn = 1;
	$pnav = "";
	$link = "";
	$start = "";
	$previous = "";
	$next = "";
	$end = "";
	if($pn >= 2){
		$previous .= " <a href=\"" . $url . "csr=" . ($csr - $cpp);
		$previous .= "&amp;cpp=" . $cpp . "&amp;ccp=" . ($pn - 1) . "\">&lt;&lt; Back</a> ... ";
	}
	if($pn < $nr and ($pn * $cpp) < $nr){
		$next .= " ... <a href=\"" . $url . "csr=" . ($csr + $cpp);
		$next .= "&amp;cpp=" . $cpp . "&amp;ccp=" . ($pn + 1) . "\">Next &gt;&gt;</a> ";
	}
	if($nr > $cpp){
		$tp = $nr / $cpp;
		if($tp != intval($tp)) $tp = intval($tp) + 1;
		$ccp = 0;
		while($ccp++ < $tp){
			if(($ccp < $pn - $pnp or $ccp > $pn + $pnp) and $pnp != 0){
				if($ccp == 1){
					$start .= " <a class=\"navLink\" href=\"" . $url;
					$start .= "csr=0&amp;";
					$start .= "cpp=" . $cpp . "&amp;ccp=1\">&lt;&lt; Start</a> ... ";
				}
				if($ccp == $tp){
					$end .= " ... <a class=\"navLink\" href=\"" . $url;
					$end .= "csr=";
					$end .= ($tp - 1) * $cpp . "&amp;cpp=" . $cpp . "&amp;ccp=";
					$end .= $tp . "\">End &gt;&gt;</a> ... ";
				}
			} else {
				if($ccp == $pn){
					$link .= " <span class=\"selectedNav\">[ $ccp ]</span> ";
				} else {
					$link .= "  <a class=\"navLink\" href=\"" . $url;
					$link .= "csr=" . ($ccp - 1) * $cpp;
					$link .= "&amp;cpp=" . $cpp . "&amp;ccp=" . $ccp . "\">[ $ccp ]</a> ";
				}
			}
		}
		$pnav .= $start;
		$pnav .= $previous;
		$pnav .= $link;
		$pnav .= $next;
		$pnav .= $end;
	}
	if($nr==0) $nom=0; else $nom=1;
	$pnav .= "&nbsp;&nbsp;... #" . ($csr + $nom);
	if($cpp > 1){
		$pnav .= " - ";
		if($csr + $nom + $cpp < $nr) $pnav .= ($csr + $nom + $cpp) - 1;
		else $pnav .= $nr;
	}
	$pnav .= " of " . $nr . " ";
	return $pnav;
}

function insert_private_message($user_id, $subject, $message, $authorid){
global $tb_pms;
	$subject = ereg_replace("<([^>]+)>", "", addslashes($subject));
	$comment = ereg_replace("<([^>]+)>", "", addslashes($comment));
	$sql = "
			insert into $tb_pms (
				id,
				user_id,
				subject,
				message,
				author_id,
				author_ip,
				pm_status
			) values (
				'',
				'$user_id',
				'$subject',
				'$message',
				'$authorid',
				'$_SERVER[REMOTE_ADDR]',
				'inbox'
			)
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function insert_comment($user_id, $subject, $comment, $authorid){
global $tb_comments;
	$subject = ereg_replace("<([^>]+)>", "", addslashes($subject));
	$comment = ereg_replace("<([^>]+)>", "", addslashes($comment));
	$sql = "
		insert into $tb_comments (
			id,
			pid,
			user_id,
			subject,
			comment,
			author_id,
			author_ip,
			status
		) values (
			'',
			'0',
			'$user_id',
			'$subject',
			'$comment',
			'$authorid',
			'$_SERVER[REMOTE_ADDR]',
			'approved'
		)
	";
	$query = mysql_query($sql) or die(mysql_error());
	$insert_id =  mysql_insert_id();
	insert_comment_thread($insert_id);
	increment_user_comment_count($user_id);
	return $insert_id;
}

function get_comment_pid($comment_id){
global $tb_comments;
	$sql = "
		select
			pid
		from
			$tb_comments
		where
			id = '$comment_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$pid = mysql_result($query, 0, "pid");
	if(!$pid) return $comment_id;
	return $pid;
}

function edit_comment($comment_id, $subject, $comment){
global $tb_comments;
	$subject = ereg_replace("<([^>]+)>", "", addslashes($subject));
	$comment = ereg_replace("<([^>]+)>", "", addslashes($comment));
	$sql = "
		update
			$tb_comments
		set
			subject = '$subject',
			comment = '$comment'
		where
			id = '$comment_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	update_comment_last_post_time($comment_id);
}

function insert_comment_reply($comment_id, $subject, $comment, $authorid){
global $tb_comments;
	$subject = ereg_replace("<([^>]+)>", "", addslashes($subject));
	$comment = ereg_replace("<([^>]+)>", "", addslashes($comment));
	$userid = get_userid_from_comment_id($comment_id);
	$sql = "
		insert into $tb_comments (
			id,
			pid,
			user_id,
			subject,
			comment,
			author_id,
			author_ip,
			status
		) values (
			'',
			'$comment_id',
			'$userid',
			'$subject',
			'$comment',
			'$authorid',
			'$_SERVER[REMOTE_ADDR]',
			'approved'
		)
	";
	$query = mysql_query($sql) or die(mysql_error());
	$insert_id = mysql_insert_id();
	insert_comment_last_post_time($insert_id);
	update_comment_last_post_time($comment_id);
}

function insert_comment_last_post_time($comment_id){
global $tb_comment_threads;
	$sql = "
		insert into 	$tb_comment_threads (
			comment_id,
			updated,
			timestamp
		) values (
			'$comment_id',
			now(),
			now()
		)
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function update_comment_last_post_time($comment_id){
global $tb_comment_threads;
	$sql = "
		update
			$tb_comment_threads
		set
			updated = now()
		where
			comment_id = '$comment_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function get_userid_from_comment_id($comment_id){
global $tb_comments;
$sql = "
	select
		user_id
	from
		$tb_comments
	where
		id = '$comment_id'
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query))
	return mysql_result($query, 0, "user_id");
return 0;
}

function get_comment($comment_id){
global $tb_comments;
	$sql = "
		select
			comment
		from
			$tb_comments
		where
			id = '$comment_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return mysql_result($query, 0, "comment");
	}
	return "";
}

function comment_exists($comment_id){
global $tb_comments;
	$sql = "
		select
			id
		from
			$tb_comments
		where
			id = '$comment_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return true;
	}
	return false;
}

function comment_is_editable($comment_id, $author_id){
global $tb_comments, $tb_comment_threads;
	if($author_id > 0){
		$sql = "
			select
				$tb_comments.author_id as author_id,
				unix_timestamp($tb_comment_threads.updated) as unix_time
			from
				$tb_comments
			left join
				$tb_comment_threads
			on
				$tb_comments.id = $tb_comment_threads.comment_id
			where
				$tb_comments.id = '$comment_id'
		";
		$query = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($query)){
			$array = mysql_fetch_array($query);
			$fifteen_minutes = mktime(date("H"), abs(date("i"))-15, abs(date("s")), date("m"), date("d"), date("Y"));
			if($array["unix_time"] >= $fifteen_minutes && $author_id == $array["author_id"]) return true;
		}
	}
	return false;
}

function get_user_comments_count($userid){
global $tb_users;
	if($userid > 0){
		$sql = "
			select
				total_comments
			from
				$tb_users
			where
				id = '$userid'
		";
		$query = mysql_query($sql) or die(mysql_error());
		return (int) mysql_result($query, 0, "total_comments");
	}
	return "N/A";
}

function get_author_comments_count($userid){
global $tb_comments;
	if($userid > 0){
		$sql = "
			select
				count(*) as count
			from
				$tb_comments
			where
				author_id = '$userid'
		";
		$query = mysql_query($sql) or die(mysql_error());
		return (int) mysql_result($query, 0, "count");
	}
	return "N/A";
}

function get_comments_in_comment_count($comment_id){
global $tb_comments;
	$sql = "
		select
			count(*) as count
		from
			$tb_comments
		where
			pid = '$comment_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return (int) (mysql_result($query, 0, "count") + 1);
}

function tally_comment_view($comment_id, $ip){
global $tb_comment_views;
$sql = "
	insert ignore into $tb_comment_views (
		comment_id,
		ip
	) values (
		'$comment_id',
		'$ip'
	)
";
$query = mysql_query($sql) or die(mysql_error());
} 

function get_comment_starter_id($comment_id){
global $tb_comments;
$sql = "
	select
		author_id
	from
		$tb_comments
	where
		id = '$comment_id'
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query))
	return mysql_result($query, 0, "author_id");
return 0;
}

function get_comment_views_count($comment_id){
global $tb_comment_views;
$sql = "
	select
		count(*) as count
	from
		$tb_comment_views
	where
		comment_id = '$comment_id'
";
$query = mysql_query($sql) or die(mysql_error());
return (int) mysql_result($query, 0, "count");
}

function get_comment_replies_count($comment_id){
global $tb_comments;
	$sql = "
		select
			count(*) as count
		from
			$tb_comments
		where
			pid = '$comment_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return (int) mysql_result($query, 0, "count");
}

function get_comment_subject($comment_id){
global $tb_comments;
	$sql = "
		select
			subject
		from
			$tb_comments
		where
			id = '$comment_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$subject = mysql_result($query, 0, "subject");
	}
	if(strlen($subject)) return $subject;
	return "No Comment Subject..";
}

function get_months($month){
	$html = ""; 
	for($x = 1; $x < 13; $x++){
		$value = strlen($x) == 1 ? "0" . $x : $x;
		$selected = $value == $month ? "selected" : "";
$html .= <<<EOF
<option value="$value"$selected>$value</option>
EOF;
	}
	return $html;
}

function get_days($day){
	$html = ""; 
	for($x = 1; $x < 32; $x++){
		$value = strlen($x) == 1 ? "0" . $x : $x;
		$selected = $value == $day ? "selected" : "";
$html .= <<<EOF
<option value="$value"$selected>$value</option>
EOF;
	}
	return $html;
}

function get_years($year){
	$html = ""; 
	for($x = 2002; $x < 2011; $x++){
		$selected = $x == $year ? "selected" : "";
$html .= <<<EOF
<option value="$x"$selected>$x</option>
EOF;
	}
	return $html;
}

function get_thread_id_from_post_id($post_id){
global $tb_posts;
	$sql = "
		select
			thread_id
		from
			$tb_posts
		where
			post_id = '$post_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return (int) mysql_result($query, 0, "thread_id");
	}
	return 0;
}

function insert_new_thread($forum_id){
global $tb_threads;
	$sql = "
		insert into $tb_threads (
			thread_id,
			forum_id,
			timestamp
		) values (
			'',
			'$forum_id',
			now( )
		)
	";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_insert_id();
}

function update_thread_last_post_time($thread_id){
global $tb_threads;
	$sql = "
		update
			$tb_threads
		set
			timestamp = now()
		where
			thread_id = '$thread_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
}

function update_post($post_id, $subject, $post){
global $tb_posts;
	$subject = ereg_replace("<([^>]+)>", "", addslashes($subject));
	$post = ereg_replace("<([^>]+)>", "", addslashes($post));
	$sql = "
		update
			$tb_posts
		set
			subject = '$subject',
			post = '$post',
			updated = now()
		where
			post_id = '$post_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$thread_id = get_thread_id_from_post_id($post_id);
	update_thread_last_post_time($thread_id);
}

function insert_post_reply($thread_id, $subject, $post, $userid){
global $tb_posts;
	$subject = ereg_replace("<([^>]+)>", "", addslashes($subject));
	$post = ereg_replace("<([^>]+)>", "", addslashes($post));
	$sql = "
		insert into $tb_posts (
			post_id,
			thread_id,
			subject,
			post,
			userid,
			updated,
			timestamp
		) values (
			'',
			'$thread_id',
			'$subject',
			'$post',
			'$userid',
			now(),
			now()
		)
	";
	$query = mysql_query($sql) or die(mysql_error());
	update_thread_last_post_time($thread_id);
}

function forum_exists($forum_id){
global $tb_forums;
	$sql = "
		select
			forum_id
		from
			$tb_forums
		where
			forum_id = '$forum_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return true;
	}
	return false;
}

function thread_exists($thread_id){
global $tb_threads;
	$sql = "
		select
			thread_id
		from
			$tb_threads
		where
			thread_id = '$thread_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return true;
	}
	return false;
}

function get_post_subject($post_id){
global $tb_posts;
	$sql = "
		select
			subject
		from
			$tb_posts
		where
			post_id = '$post_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return mysql_result($query, 0, "subject");
	}
	return "";
}

function get_post_post($post_id){
global $tb_posts;
	$sql = "
		select
			post
		from
			$tb_posts
		where
			post_id = '$post_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return mysql_result($query, 0, "post");
	}
	return "";
}

function post_is_editable($post_id){
global $tb_posts;
	$sql = "
		select
			unix_timestamp(timestamp) as unix_time
		from
			$tb_posts
		where
			post_id = '$post_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$unix_time = mysql_result($query, 0, "unix_time");
		$fifteen_minutes = mktime(date("H"), abs(date("i"))-15, abs(date("s")), date("m"), date("d"), date("Y"));
		if($unix_time >= $fifteen_minutes) return true;
	}
	return false;
}

function post_exists($post_id){
global $tb_posts;
	$sql = "
		select
			post_id
		from
			$tb_posts
		where
			post_id = '$post_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return true;
	}
	return false;
}

function get_thread_name($thread_id){
global $tb_posts;
	$sql = "
		select
			subject
		from
			$tb_posts
		where
			thread_id = '$thread_id'
		order by
			post_id
		limit
			0, 1
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return mysql_result($query, 0, "subject");
	}
	return "";
}

function get_forum_id_from_thread_id($thread_id){
global $tb_threads;
	$sql = "
		select
			forum_id
		from
			$tb_threads
		where
			thread_id = '$thread_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return (int) mysql_result($query, 0, "forum_id");
	} else {
		return 0;
	}
}

function get_parent_forum_name($forum_id){
global $tb_forums, $base_url;
	$sql = "
		select
			forum_pid
		from
			$tb_forums
		where
			forum_id = '$forum_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$array = mysql_fetch_array($query);
		$ssql = "
			select
				forum
			from
				$tb_forums
			where
				forum_id = '$array[forum_pid]'
		";
		$squery = mysql_query($ssql) or die(mysql_error());
		if(mysql_num_rows($squery)){
			
			$forum_name = mysql_result($squery, 0, "forum");

return <<<EOF
 >> <a class="bold" href="$base_url/forums.php?p=$array[forum_pid]">$forum_name</a>
EOF;

		} else {
			return "";
		}
	} else {
		return "";
	}
}

function get_forum_name_linked($sep, $forum_id){
global $tb_forums, $base_url;
	$sql = "
		select
			forum
		from
			$tb_forums
		where
			forum_id = '$forum_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){

		$forum = mysql_result($query, 0, "forum");

return <<<EOF
$sep<a class="bold" href="$base_url/threads.php?f=$forum_id">$forum</a>
EOF;

	} else {
		return "";
	}
}

function get_forum_name($sep, $forum_id){
global $tb_forums;
	$sql = "
		select
			forum
		from
			$tb_forums
		where
			forum_id = '$forum_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return $sep . mysql_result($query, 0, "forum");
	} else {
		return "";
	}
}

function get_user_posts_count($userid){
global $tb_posts;
	$sql = "
		select
			count(*) as count
		from
			$tb_posts
		where
			userid = '$userid'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return (int) mysql_result($query, 0, "count");
}

function get_user_location($userid){
global $tb_users;
	$sql = "
		select
			state,
			country
		from
			$tb_users
		where
			id = '$userid'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$array = mysql_fetch_array($query);
		$country_array = explode(".", $array["country"]);
		$location = $array["state"];
		if(strlen($array["state"])){
			$location .= ", ";
		}
		$location .= eregi_replace("_", " ", $country_array[0]);
		return $location;
	} else {
		return "N/A";
	}
}

function get_date(){
global $mysql_dates;
	$sql = "
		select
			date_format(now(), '$mysql_dates') as the_date
	";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_result($query, 0, "the_date");
}

function get_user_signup_date($userid){
global $tb_users, $signup_dates;
	$sql = "
		select
			date_format(signup, '$signup_dates') as signup_date
		from
			$tb_users
		where
			id = '$userid'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query))
		return mysql_result($query, 0, "signup_date");
	return "N/A";
}

function tally_thread_view($thread_id, $ip){
global $tb_thread_views;
$sql = "
	insert ignore into $tb_thread_views (
		thread_id,
		ip
	) values (
		'$thread_id',
		'$ip'
	)
";
$query = mysql_query($sql) or die(mysql_error());
} 

function get_posts_in_thread_count($thread_id){
global $tb_posts;
	$sql = "
		select
			count(*) as count
		from
			$tb_posts
		where
			thread_id = '$thread_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return (int) mysql_result($query, 0, "count");
}

function get_posts_count($forum_id){
global $tb_posts, $tb_threads;
$count = 0;
$sql = "
	select
		thread_id
	from
		$tb_threads
	where
		forum_id = '$forum_id'
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query)){
	while($array = mysql_fetch_array($query))
		$count += get_posts_in_thread_count($array["thread_id"]);
}
return $count;
}

function get_threads_count($forum_id){
global $tb_threads;
$sql = "
	select
		count(*) as count
	from
		$tb_threads
	where
		forum_id = '$forum_id'
";
$query = mysql_query($sql) or die(mysql_error());
return (int) mysql_result($query, 0, "count");
}

function get_username($id){
global $tb_users;
$sql = "
	select
		username
	from
		$tb_users
	where
		id = '$id'
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query))
	return mysql_result($query, 0, "username");
return "Username not found";
}

function get_thread_views_count($thread_id){
global $tb_thread_views;
$sql = "
	select
		count(*) as count
	from
		$tb_thread_views
	where
		thread_id = '$thread_id'
";
$query = mysql_query($sql) or die(mysql_error());
return (int) mysql_result($query, 0, "count");
}

function get_thread_replies_count($thread_id){
 return get_posts_in_thread_count($thread_id) - 1;
}

function get_thread_starter_id($username){
global $tb_users;
$sql = "
	select
		id
	from
		$tb_users
	where
		username = '$username'
";
$query = mysql_query($sql) or die(mysql_error());
return mysql_result($query, 0, "id");
}

function get_thread_starter($thread_id){
global $tb_posts;
	$sql = "
		select
			min(userid) as userid
		from
			$tb_posts
		where
			thread_id = '$thread_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query))
		return get_username(mysql_result($query, 0, "userid"));
}

function get_last_thread_id($forum_id){
global $tb_threads;
	$sql = "
		select
			thread_id
		from
			$tb_threads
		where
			forum_id = '$forum_id'
		order by
			timestamp desc
		limit 0, 1
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return (int) mysql_result($query, 0, "thread_id");
	}
	return 0;
}

function get_last_post_for_thread_id($thread_id){
global $tb_posts, $base_url, $mysql_dates;
	$sql = "
		select
			date_format(timestamp, '$mysql_dates') as the_date,
			userid
		from
			$tb_posts
		where
			thread_id = '$thread_id'
		order by
			timestamp desc
		limit 0, 1
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$array = mysql_fetch_array($query);
		$username = get_username($array['userid']);

return <<<EOF
$array[the_date]<br>
by <a class="small" href="$base_url/?i=$array[userid]">$username</a>
EOF;

	}
	return "";
}

function get_last_post($forum_id){
global $tb_posts, $tb_threads, $base_url;
	$thread_id = get_last_thread_id($forum_id);
	if($thread_id > 0){
		return get_last_post_for_thread_id($thread_id);
	}
return "No posts yet..";
}

function final_output($html){
global $clean_final_output;
	if($clean_final_output){
		$return = eregi_replace("\n", "", $html);
		$return = eregi_replace("\r", "", $return);
		return eregi_replace("\t", "", $return);
	} else {
		return $html;
	}
}

function clean_ratings(){
global $tb_ratings;
	if(mt_rand(1, 100) % 10) return;
	$sql = "
		select
			count(*) as count
		from
			$tb_ratings
		where
			to_days(now()) - to_days(timestamp)  > 31
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_result($query, 0, "count") > 100){
		$dsql = "
			delete from
				$tb_ratings
			where
				to_days(now()) - to_days(timestamp)  > 31
		";
		$dquery = mysql_query($dsql) or die(mysql_error());
		$osql = "
			optimize table
				$tb_ratings
		";
		$oquery = mysql_query($osql) or die(mysql_error());
	}
}

function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

function check_login(){
global $base_url, $sn, $sid;
	if(!isset($_SESSION['admin'])){
		header("Location: $base_url/admin/login.php");
		exit();
	}
}

function check_user_login(){
global $base_url;
	$s = $_SERVER['REQUEST_URI'];
	$a = explode("/", $s);
	$r = urlencode($a[sizeof($a)-1]);
	if(!isset($_SESSION['userid']) || !isset($_SESSION['username'])){
		$_SESSION['m'] = 1;
		header("Location: $base_url/login_page.php?r=$r");
		exit();
	}
}

function check_user_email($email){
global $tb_users;
$sql = "
	select
		*
	from
		$tb_users
	where
		email = '$email'
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query)) return true;
return false;
}

function check_username($username){
global $tb_users;
$sql = "
	select
		*
	from
		$tb_users
	where
		username = '$username'
";
$query = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($query)) return true;
return false;
}

function users_online($online_expire){
global $tb_sessions;
$sql = "
    select
        count(*) as count
    from
        $tb_sessions
    where
        expire > UNIX_TIMESTAMP() - $online_expire
";
$query = mysql_query($sql) or die(mysql_error());
return (int) mysql_result($query, 0, "count");
}

function get_submit_user_types($selected){
global $tb_user_types, $tb_users, $ml_order_by_rand, $base_url;

$html = <<<EOF
<option value="$base_url/?s=f"
EOF;

if($selected == "f") $html .= " selected";

$html .= <<<EOF
>Show Babes</option><option value="$base_url/?s=m"
EOF;

if($selected == "m") $html .= " selected";

$html .= <<<EOF
>Show Dudes</option>
EOF;

$sql = "
	select
		*
	from
		$tb_user_types
	order by
";

$sql .= $ml_order_by_rand ? " rand()" : "order_by";

$query = mysql_query($sql) or die(mysql_error());
while($array = mysql_fetch_array($query)){

	$c_sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			user_type = '$array[id]'
		and
			image_status = 'approved'
	";
	$c_query = mysql_query($c_sql) or die(mysql_error());

	if(mysql_result($c_query, 0, "count") > 0){

$html .= <<<EOF
<option value="$base_url/?s=$array[id]"
EOF;

if($array["id"] == $selected)
				$html .= " selected";

$html .= <<<EOF
>$array[user_type]</option>
EOF;

	}
}
return $html;
}

function get_user_types($selected){
global $tb_user_types, $ml_order_by_rand;
$html = "";
$sql = "
	select
		*
	from
		$tb_user_types
	order by
";

$sql .= $ml_order_by_rand ? " rand()" : "order_by";

$query = mysql_query($sql) or die(mysql_error());
while($array = mysql_fetch_array($query)){

$html .= <<<EOF
<option value="$array[id]"
EOF;

if($array["id"] == $selected)
				$html .= " selected";

$html .= <<<EOF
>$array[user_type]</option>
EOF;
}
return $html;
}

function get_user_type($id){
global $tb_user_types;
	$sql = "
		select
			user_type
		from
			$tb_user_types
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		return mysql_result($query, 0, "user_type");
	}
	return 0;
}

function ut($id){
global $tb_users;
	$sql = "
		select
			user_type
		from
			$tb_users
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query) == 1){
		$array = mysql_fetch_array($query);
		return $array["user_type"];
	} else return false;
}

function mv_msg_bar($folder, $msg_id){
global $sn, $sid, $base_url;
$content = <<<EOF
<table cellpadding="5" cellspacing="0" border="0" align="right">
<form method="post" action="$base_url/">
<tr>
	<td>
EOF;
	switch($folder){
		case "inbox" :
$content .= <<<EOF
<input type="submit" name="save_msg" value=" Save "> <input type="submit" name="delete_msg" value=" Delete ">
EOF;
		break;
		case "saved" :
$content .= <<<EOF
<input type="submit" name="delete_msg" value=" Delete ">
EOF;
			break;
		case "trash" :
$content .= <<<EOF
<input type="submit" name="undelete_msg" value=" Undelete ">
EOF;
			break;
	}
$content .= <<<EOF
</td></tr>
<input type="hidden" name="show" value="view_msg">
<input type="hidden" name="folder" value="$folder">
<input type="hidden" name="msg_id" value="$msg_id">
</form>
</table>
EOF;
	return $content;
}

function empty_trash_bar(){
global $sn, $sid, $base_url;
$content = <<<EOF
<table cellpadding="5" cellspacing="0" border="0" align="right">
<form method="post" action="$base_url/empty_trash.php">
<tr>
	<td><input class="button" type="submit" name="empty_trash" value=" Empty Trash "></td>
</tr>
<input type="hidden" name="show" value="messages">
<input type="hidden" name="folder" value="trash">
</form>
</table>
EOF;
	return $content;
}

function move_message($msg_id, $folder){
global $tb_pms;
	$sql = "
		update
			$tb_pms
		set
			pm_status = '$folder'
		where
			id = '$msg_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return true;
}

function delete_message($msg_id){
global $tb_pms;
	$sql = "
		delete from
			$tb_pms
		where
			id = '$msg_id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return true;
}

function empty_trash($userid){
global $tb_pms;
	$sql = "
		delete from
			$tb_pms
		where
			user_id = '$userid'
		and
			pm_status = 'trash'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return true;
}

function total_messages($userid, $folder){
global $tb_pms;
	$sql = "
		select
			count(*) as count
		from
			$tb_pms
		where
			user_id = '$userid'
		and
			pm_status = '$folder'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$total = mysql_result($query,"","count");
	return $total;
}

function folder_table($userid, $folder){
global $sn, $sid, $base_url;
	$total_inbox = total_messages($userid, "inbox");
	$total_saved = total_messages($userid, "saved");
	$total_trash = total_messages($userid, "trash");

$content = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
<tr>
	<td class="regular">Folders:</td>
	<td class="regular">
EOF;

if($folder == "inbox"){
$content .= <<<EOF
<span class="bold">Inbox</span>
EOF;
} else {
$content .= <<<EOF
<a href="$base_url/messages.php?folder=inbox">Inbox</a>
EOF;
}

$content .= <<<EOF
: $total_inbox</td>	<td class="regular">
EOF;

if($folder == "saved"){
$content .= <<<EOF
<span class="bold">Saved</span>
EOF;
} else {
$content .= <<<EOF
<a href="$base_url/messages.php?folder=saved">Saved</a>
EOF;
}

$content .= <<<EOF
: $total_saved</td><td class="regular">
EOF;

if($folder == "trash"){
$content .= <<<EOF
<span class="bold">Trash</span>
EOF;
} else {
$content .= <<<EOF
<a href="$base_url/messages.php?folder=trash">Trash</a>
EOF;
}

$content .= <<<EOF
: $total_trash</td>
</tr>
</table>
EOF;

	return $content;
}

function get_messages($userid, $folder){
global $tb_pms, $tb_users, $sn, $sid, $base_url;
	$x = 0;
	$folder_table = folder_table($userid, $folder);
$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular" colspan="5" align="right">$folder_table</td>
</tr>
EOF;
	$sql = "
		select
			$tb_users.username as author_name,
			$tb_users.user_type as user_type,
			$tb_pms.author_id as author_id,	
			$tb_pms.id as pm_id,
			$tb_pms.subject as subject,
			$tb_pms.timestamp as timestamp
		from
			$tb_pms
		left join
			$tb_users
		on
			$tb_users.id = $tb_pms.author_id
		where
			$tb_pms.user_id = '$userid'
		and
			$tb_pms.pm_status = '$folder'
		group by
			$tb_pms.id
		order by
			$tb_pms.timestamp desc
		limit
			0, 10
	";
	$query = mysql_query($sql) or die(mysql_error());
	$total_msgs = mysql_num_rows($query);
	if($total_msgs > 0){
$content .= <<<EOF
<tr>
	<td class="bold">&nbsp;From</td>
	<td class="bold">Subject</td>
	<td class="bold" align="right">Recieved&nbsp;</td>
</tr>
EOF;
		while($array = mysql_fetch_array($query)){
			$x++;
			$pretty_time = pretty_time($array["timestamp"]);

$content .= <<<EOF
<tr><form method="post" action="$base_url/messages.php?folder=$folder">
<td class="regular" width="20%" nowrap="nowrap">&nbsp;
EOF;

	$check_sql = "
		select
			image_status
		from
			$tb_users
		where
			id = '$array[author_id]'
	";
	$check_query = mysql_query($check_sql) or die(mysql_error());

	if(mysql_result($check_query, 0, "image_status") == "approved"){

$content .= <<<EOF
<a class="regular" href="$base_url/?i=$array[author_id]">$array[author_name]</a>
EOF;

	} else {

$content .= $array['author_name'];

	}

$content .= <<<EOF
</td>
<td class="regular" width="58%" nowrap="nowrap"><a class="regular" href="$base_url/view_msg.php?msg_id=$array[pm_id]&amp;folder=$folder">$array[subject]</a></td>
<td class="regular" width="20%" nowrap="nowrap" align="right">$pretty_time&nbsp;</td>
</tr></form>
EOF;
		}
	} else {
		$content .= <<<EOF
		<tr>
			<td class="regular" align="center"><br><br>No messages in your $folder folder.<br><br><br></td>
		</tr>
EOF;
	}
	$content .= <<<EOF
	</table><br>
EOF;
	return $content;
}

function get_prev_message_url($userid, $folder, $msg_id){
global $tb_pms, $base_url, $sn, $sid;
$html = "";
	$sql = "
		select
			id
		from
			$tb_pms
		where
			user_id = '$userid'
		and
			pm_status = '$folder'
		and
			id < '$msg_id'
		order by
			id desc
		limit
			1
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$array = mysql_fetch_array($query);

$html = <<<EOF
<a class="regular" href="$base_url/view_msg.php?msg_id=$array[id]&amp;folder=$folder"><< Previous</a>
EOF;

	}

	return $html;
}

function get_next_message_url($userid, $folder, $msg_id){
global $tb_pms, $base_url, $sn, $sid;
$html = "";
	$sql = "
		select
			id
		from
			$tb_pms
		where
			user_id = '$userid'
		and
			pm_status = '$folder'
		and
			id > '$msg_id'
		order by
			id
		limit
			1
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$array = mysql_fetch_array($query);

$html = <<<EOF
<a class="regular" href="$base_url/view_msg.php?msg_id=$array[id]&amp;folder=$folder">Next >></a>
EOF;

	}

	return $html;
}

function get_total_users($id){
global $tb_users;
	$sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			user_type = '$id'
		and
			image_status	=	'approved'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return mysql_result($query, 0, "count");
}

function rand_pass($len = 16){ 
	$char = "abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789";
	$str = "";
	for($i=0;$i<$len;$i++)
		$str .= substr($char, mt_rand(0,62),1);
	return $str; 
}

function getFlagList($dirName, $Country){
	$d = dir($dirName);
	$html = "";
	while($entry = $d->read()){
		if($entry != "." && $entry != ".." && $entry != "CVS"){
			$short_entry = eregi_replace(".gif", "", $entry);
			$short_entry = eregi_replace("_", " ", $short_entry);
			$html .= "<option value=\"" . $entry . "\"";
			if($Country == $entry){$html .= " selected=\"selected\"";}
			$html .= ">" . $short_entry . "</option>\n";
		}
	}
	$d->close();
	return $html;
}

function getTableFileList($dirName, $current_file){
	$d = dir($dirName);
	$html = "";
	while($entry = $d->read()){
		if($entry != "." && $entry != ".." && $entry != "CVS"){
			if(eregi("^tables.*", $entry)){ 
				$short_entry = $entry;
				$html .= "<option value=\"" . $entry . "\"";
				if($current_file == $entry){$html .= " selected=\"selected\"";}
				$html .= ">" . $short_entry . "</option>\n";
			}
		}
	}
	$d->close();
	return $html;
}

function convert_single($i){
global $pp, $tb_users, $base_url;
  $nav = "";
	$ut = ut($i);
	$x = 0;
	$sql = "
		select
			id,
			user_type,
			average_rating
		from
			$tb_users
		where
			user_type = '$ut'
		and
			image_status = 'approved'
		order by
			average_rating desc
	";
	$query = mysql_query($sql) or die(mysql_error());
	while($array = mysql_fetch_array($query)){
		if($i == $array["id"]){
			$sr = $x;
			$cp = $x +1;
			$nav = "sr=" . $sr . "&pp=" . $pp . "&cp=" . $cp . "&ut=" . $array["user_type"] . "&i=" . $i;
		}
		$x++;
	}
	header("Location: $base_url/view.php?$nav");
	exit();
}

function nav_links($nr, $pp, $pnp, $pn, $url){
global $sr, $sn, $sid;
	if(!isset($pn)) $pn = 1;
	$pnav = "";
	$link = "";
	$start = "";
	$previous = "";
	$next = "";
	$end = "";
	if($pn >= 2){
		$previous .= " <a href=\"" . $url . "sr=" . ($sr - $pp);
		$previous .= "&amp;pp=" . $pp . "&amp;cp=" . ($pn - 1) . "\">&lt;&lt; Back</a> ... ";
	}
	if($pn < $nr and ($pn * $pp) < $nr){
		$next .= " ... <a href=\"" . $url . "sr=" . ($sr + $pp);
		$next .= "&amp;pp=" . $pp . "&amp;cp=" . ($pn + 1) . "\">Next &gt;&gt;</a> ";
	}
	if($nr > $pp){
		$tp = $nr / $pp;
		if($tp != intval($tp)) $tp = intval($tp) + 1;
		$cp = 0;
		while($cp++ < $tp){
			if(($cp < $pn - $pnp or $cp > $pn + $pnp) and $pnp != 0){
				if($cp == 1){
					$start .= " <a class=\"navLink\" href=\"" . $url;
					$start .= "sr=0&amp;";
					$start .= "pp=" . $pp . "&amp;cp=1\">&lt;&lt; Start</a> ... ";
				}
				if($cp == $tp){
					$end .= " ... <a class=\"navLink\" href=\"" . $url;
					$end .= "sr=";
					$end .= ($tp - 1) * $pp . "&amp;pp=" . $pp . "&amp;cp=";
					$end .= $tp . "\">End &gt;&gt;</a> ... ";
				}
			} else {
				if($cp == $pn){
					$link .= " <span class=\"selectedNav\">[ $cp ]</span> ";
				} else {
					$link .= "  <a class=\"navLink\" href=\"" . $url;
					$link .= "sr=" . ($cp - 1) * $pp;
					$link .= "&amp;pp=" . $pp . "&amp;cp=" . $cp . "\">[ $cp ]</a> ";
				}
			}
		}
		$pnav .= $start;
		$pnav .= $previous;
		$pnav .= $link;
		$pnav .= $next;
		$pnav .= $end;
	}
	if($nr==0) $nom=0; else $nom=1;
	$pnav .= "&nbsp;&nbsp;... #" . ($sr + $nom);
	if($pp > 1){
		$pnav .= " - ";
		if($sr + $nom + $pp < $nr) $pnav .= ($sr + $nom + $pp) - 1;
		else $pnav .= $nr;
	}
	$pnav .= " of " . $nr . " ";
	return $pnav;
}

function comment_nav_links($nr, $cpp, $pnp, $pn, $url){
global $csr, $sn, $sid;
	if(!isset($pn)) $pn = 1;
	$pnav = "";
	$link = "";
	$start = "";
	$previous = "";
	$next = "";
	$end = "";
	if($pn >= 2){
		$previous .= " <a href=\"" . $url . "csr=" . ($csr - $cpp);
		$previous .= "&amp;cpp=" . $cpp . "&amp;ccp=" . ($pn - 1) . "\">&lt;&lt; Back</a> ... ";
	}
	if($pn < $nr and ($pn * $cpp) < $nr){
		$next .= " ... <a href=\"" . $url . "csr=" . ($csr + $cpp);
		$next .= "&amp;cpp=" . $cpp . "&amp;ccp=" . ($pn + 1) . "\">Next &gt;&gt;</a> ";
	}
	if($nr > $cpp){
		$tp = $nr / $cpp;
		if($tp != intval($tp)) $tp = intval($tp) + 1;
		$ccp = 0;
		while($ccp++ < $tp){
			if(($ccp < $pn - $pnp or $ccp > $pn + $pnp) and $pnp != 0){
				if($ccp == 1){
					$start .= " <a class=\"navLink\" href=\"" . $url;
					$start .= "csr=0&amp;";
					$start .= "cpp=" . $cpp . "&amp;ccp=1\">&lt;&lt; Start</a> ... ";
				}
				if($ccp == $tp){
					$end .= " ... <a class=\"navLink\" href=\"" . $url;
					$end .= "csr=";
					$end .= ($tp - 1) * $cpp . "&amp;cpp=" . $cpp . "&amp;ccp=";
					$end .= $tp . "\">End &gt;&gt;</a> ... ";
				}
			} else {
				if($ccp == $pn){
					$link .= " <span class=\"selectedNav\">[ $ccp ]</span> ";
				} else {
					$link .= "  <a class=\"navLink\" href=\"" . $url;
					$link .= "csr=" . ($ccp - 1) * $cpp;
					$link .= "&amp;cpp=" . $cpp . "&amp;ccp=" . $ccp . "\">[ $ccp ]</a> ";
				}
			}
		}
		$pnav .= $start;
		$pnav .= $previous;
		$pnav .= $link;
		$pnav .= $next;
		$pnav .= $end;
	}
	if($nr==0) $nom=0; else $nom=1;
	$pnav .= "&nbsp;&nbsp;... #" . ($csr + $nom);
	if($cpp > 1){
		$pnav .= " - ";
		if($csr + $nom + $cpp < $nr) $pnav .= ($csr + $nom + $cpp) - 1;
		else $pnav .= $nr;
	}
	$pnav .= " of " . $nr . " ";
	return $pnav;
}

function ow_image($the_file, $the_file_ext, $userid){
global $base_path, $image_path;
	$error = validate_upload($the_file, $the_file_ext);
	if($error){
		$html = $error;
	} else {
		$file_name = $userid . "." . $the_file_ext;
		if(@copy($the_file, $image_path . "/" . $file_name)) {
			update_ext($the_file_ext, $userid);
			$html = "Your image was uploaded successfully.  It will now have to be reviewed<br>before being shown live on the site.  Please allow up to 48 hours for review.";
		} else {
			$html = "Your image was not uploaded, a file write error occured.";	
		}
		update_url("here", "", $userid);
		queue_image($userid);
	}
	return $html;
}

function adm_ow_image($the_file, $the_file_ext, $userid){
global $base_path, $image_path;
	$error = validate_upload($the_file, $the_file_ext);
	if($error){
		$html = $error;
	} else {
		$file_name = $userid . "." . $the_file_ext;
		if (@copy($the_file, $image_path . "/" . $file_name)) {
			$html = "Image uploaded successfully.";
			update_ext($the_file_ext, $userid);
		} else {
			$html = "Image not uploaded, file write error.";	
		}
		adm_update_url("here", "", $userid);
		add_rotation($userid);
	}
	return $html;
}

function update_url($where, $new_url, $userid){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image = '$where',
			image_url	 = '$new_url'
		where
			id = '$userid'
	";
	$query = mysql_query($sql) or die(mysql_error());
	notify_admin();
	queue_image($userid);
	return "Your URL location has been updated.";
}

function adm_update_url($where, $new_url, $userid){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image = '$where',
			image_url	 = '$new_url'
		where
			id = '$userid'
	";
	$query = mysql_query($sql) or die(mysql_error());
	add_rotation($userid);
	return "Your URL location has been updated.";
}

function drop_rotation($id){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image_status = 'disabled'
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return true;
}

function queue_image($id){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image_status = 'queued'
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return true;
}

function adm_image_status($userid, $status){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image_status = '$status'
		where
			id = '$userid'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return " Image Status Updated";
}

function add_rotation($id){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image_status = 'approved'
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return true;
}

function update_ext($the_file_ext, $userid){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image_ext = '$the_file_ext'
		where
			id = '$userid'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return true;
}

function new_image($the_file, $the_file_ext, $userid) {
global $base_path, $the_file_type, $image_path;
	$error = validate_upload($the_file, $the_file_ext);
	if($error){
		$html = $error;
	} else {
		$file_name = $userid . "." . $the_file_ext;
		if (!@copy($the_file, $image_path . "/" . $file_name)){
			$html = "Your new image was not uploaded.";	
		} else {
			$html = "Your new image has been uploaded.";
			update_ext($the_file_ext, $userid);
		}
		update_url("here", "", $userid);
		queue_image($userid);
	}
	return $html;
}

function validate_upload($the_file, $the_file_ext){
global $max_image_size, $max_image_width, $max_image_height, $tb_image_types;
	$error = "";
	$start_error = "Error: <ul>";
	if(!strlen($the_file)) {
		$error .= "<li>You did not upload anything!</li>";
	} else {
		$allowed = false;
		$sql = "
			select
				*
			from
				$tb_image_types
		";
		$query = mysql_query($sql) or die(mysql_error());
		while($array = mysql_fetch_array($query))
			if($the_file_ext == $array["ext"]) $allowed = true;
		if(!$allowed){
			$error .= "<li>The file that you uploaded was of a type that is not<br>allowed, you are only allowed to upload files of the type:<ul>";
			while($array = mysql_fetch_array($query))
				$error .= "<li>." . $array[ext] . "</li>";
			$error .= "</ul>";
		}
		if($allowed){
			$size = getimagesize($the_file);
			list($foo, $width, $bar, $height) = explode("\"", $size[3]);
			if($width > $max_image_width)
				$error .= "<li>Your image should be no wider than " . $max_image_width . " Pixels</li>";
			if($height > $max_image_height)
				$error .= "<li>Your image should be no higher than " . $max_image_height . " Pixels</li>";
		}
		if($error){
			$error = $start_error . $error . "</ul>";
			return $error;
		} else return false;
	}
}

function adm_new_image($the_file, $the_file_ext, $userid) {
global $base_path, $the_file_type, $image_path;
	$error = validate_upload($the_file, $the_file_ext);
	if($error){
		$html = $error;
	} else {
		$file_name = $userid . "." . $the_file_ext;
		if (!@copy($the_file, $image_path . "/" . $file_name)) {
			$html = "The new image was not updated.  Check the member image directory write permissions.  Errors were experienced when trying to copy a file there.";	
		} else {
			$html = "The image was uploaded.";
			update_ext($the_file_ext, $userid);
		}
		update_url("here", "", $userid);
		queue_image($userid);
	}
	return $html;
}

function set_notfound_image($id){
global $tb_users, $base_url;
	$image_url = $base_url . "/images/notfound_image.gif";
	$sql = "
		update
			$tb_users
		set
			image_url = '$image_url',
			image = 'there'
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	return true;
}

function del_image($id){
global $tb_users, $base_path, $image_path;
	$sql = "
		select
			concat(id, '.', image_ext) as image
		from
			$tb_users
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$image = mysql_result($query, 0, "image");

	$file = $image_path . "/" . $image;
	if(file_exists($file)){
		if(unlink($file)){
			drop_rotation($id);
			set_notfound_image($id);
			return "Your image has been deleted.";
		} else return "An error occured, your image was not deleted.";
	} else return "Could not delete image, no image was found.";
}

function get_user($id){
global $tb_users;
	$sql = "
		select
			*
		from
			$tb_users
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$user_array = mysql_fetch_array($query);
	return $user_array["id"];
}

function notify_admin(){
global $owner_name, $owner_email, $base_url, $SERVER_NAME;
	$recipient = $owner_name . " <" . $owner_email . ">";
	$subject = "Image Updated...";
	$content = "A new image has been uploaded.  Please proceed to\r\n";
	$content .= $base_url . "/admin/ to validate it.";
	$headers = "From: " . $owner_name . "<" . $owner_email . ">\n";
	$headers .= "X-Sender: <" . $owner_email . ">\n";
	$headers .= "Return-Path: <" . $owner_email . ">\n";
	$headers .= "Error-To: <" . $owner_email . ">\n";
	$headers .= "X-Mailer: " . $SERVER_NAME . "\n";
	mail($recipient, $subject, $content, $headers);
	return true;
}

function get_image($id){
global $tb_users, $base_url, $image_path;
	$size = array();
	$img_src = $base_url . "/images/notfound_image.gif";
	$sql = "
		select
			id,
			username,
			image,
			image_url,
			concat(id,'.',image_ext) as theimage,
			image_status
		from
			$tb_users
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$array = mysql_fetch_array($query);
	if($array["image_status"] == "approved" || $array["image_status"] == "queued"){
		if($array["image"] == "here"){
			$img_src = $base_url . "/image.php?id=" . $array["id"];
			$size = getimagesize($image_path . "/" . $array["theimage"]);
		} else {
			$img_src = $array["image_url"];
			$size = @getimagesize($array["image_url"]);
		}

	}
$return = <<<EOF
<img src="$img_src"
EOF;

if(isset($size["3"])) $return .= " " . $size["3"];

$return .= <<<EOF
 alt=".: $array[username] :." title=".: $array[username] :.">
EOF;

return $return;
}

function check_approved_image($id){
global $tb_users;
	$sql = "
		select
			image_status
		from
			$tb_users
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		if(mysql_result($query, 0, "image_status") == "approved"){
			return true;
		}
	}
	return false;
}

function image_status($id){
global $tb_users;
	$sql = "
		select
			image_status
		from
			$tb_users
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$array = mysql_fetch_array($query);
	switch($array["image_status"]){
		case "queued":
			$status = "Queued";
			break;
		case "approved":
			$status = "Approved";
			break;
		case "disabled":
			$status = "Disabled";
			break;
	}
	return $status;
}

function query_image($id){
global $tb_users, $base_url, $image_path;
	$sql = "
		select
			concat(id, '.', image_ext) as image
		from
			$tb_users
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$array = mysql_fetch_array($query);
	$image_path = $image_path . "/" . $array["image"];
	return fopen($image_path, "r");
}

function query_where($id){
global $tb_users;
	$sql = "
		select
			image
		from
			$tb_users
		where
			id = '$id'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$array = mysql_fetch_array($query);
	return $array["image"];
}

function pretty_time($timestamp){
	$year = substr($timestamp,0,4);
	$month = substr($timestamp,4,2);
	$day = substr($timestamp,6,2);
	$hour = substr($timestamp,8,2);
	$hour += 0;
	$dayPart = "PM";
	if($hour < 12) $dayPart = "AM";
	if($hour > 12) $hour -= 12;
	$minute = substr($timestamp,10,2);
	$second = substr($timestamp,12,2);
	$year += 0;
	$month += 0;
	if($month==1) $month="January";
	if($month==2) $month="February";
	if($month==3) $month="March";
	if($month==4) $month="April";
	if($month==5) $month="May";
	if($month==6) $month="June";
	if($month==7) $month="July";
	if($month==8) $month="August";
	if($month==9) $month="September";
	if($month==10) $month="October";
	if($month==11) $month="November";
	if($month==12) $month="December";
	$day += 0;
	$pretty_time = $month." ".$day.", ".$year;
	return $pretty_time;
}

?>