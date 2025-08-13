<?

/*
 * $Id: functions.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

if($debug) error_reporting(E_ALL);
else error_reporting(E_ERROR|E_WARNING|E_PARSE);

// make a db connection
if($db = mysql_pconnect($dbhost, $dbuser, $dbpasswd)){
	mysql_select_db($dbname, $db);
} else {
	echo mysql_error();
	exit();
}

function sql_query($sql){
global $db;
	return @mysql_query($sql, $db);
}

function sql_insert_id(){
global $db;
	return @mysql_insert_id($db);
}

function sql_result($sql,$row,$field){
	global $db;
	return @mysql_result($sql,$row,$field);
}

function sql_fetch_array($result){
	return @mysql_fetch_array($result);
}

function sql_num_rows($result){
	return @mysql_num_rows($result);
}

function sql_affected_rows(){
	return mysql_affected_rows();
}

function do_settings(){
global $tb_settings;
	$sql = "
		select
			*
		from
			$tb_settings
	";
	$query = sql_query($sql);
	while($array = sql_fetch_array($query)){
		$$array["name"] = $array["setting"];
		session_register($array["name"]);
		$GLOBALS[$array["name"]] = $array["setting"];
	}
	$vars_set = true;
	session_register("vars_set");
	$GLOBALS["vars_set"] = $vars_set;
	return true;
}

function get_user_name($id){
global $tb_users;
	$sql = "
		select
			username
		from
			$tb_users
		where
			id = '$id'
	";
	$query = sql_query($sql);
	return sql_result($query, 0, "username");
}

function get_user_sex($id){
global $tb_users;
	$sql = "
		select
			sex
		from
			$tb_users
		where
			id = '$id'
	";
	$query = sql_query($sql);
	return sql_result($query, 0, "sex");
}

function sex($sex){
	if($sex == "m") return "Guys";
	return "Girls";
}

function mf($id){
global $tb_users;
	$sql = "
		select
			sex
		from
			$tb_users
		where
			id = '$id'
	";
	$query = sql_query($sql);
	if(sql_num_rows($query) == 1){
		$array = sql_fetch_array($query);
		return $array["sex"];
	} else return false;
}

function mv_msg_bar($folder, $msg_id){
global $sn, $sid, $base_url;
$content = <<<EOF
<table cellpadding="5" cellspacing="0" border="0" align="right">
<form method="post" action="$base_url/index.php?$sn=$sid">
<tr>
	<td>
EOF;
	switch($folder){
		case "inbox" :
$content .= <<<EOF
<input type="submit" name="save_msg" value=" Save " /> <input type="submit" name="delete_msg" value=" Delete " />
EOF;
		break;
		case "saved" :
$content .= <<<EOF
<input type="submit" name="delete_msg" value=" Delete " />
EOF;
			break;
		case "trash" :
$content .= <<<EOF
<input type="submit" name="undelete_msg" value=" Undelete " />
EOF;
			break;
	}
$content .= <<<EOF
</td></tr>
<input type="hidden" name="show" value="view_msg" />
<input type="hidden" name="folder" value="$folder" />
<input type="hidden" name="msg_id" value="$msg_id" />
</form>
</table>
EOF;
	return $content;
}

function empty_trash_bar(){
global $sn, $sid, $base_url;
$content = <<<EOF
<table cellpadding="5" cellspacing="0" border="0" align="right">
<form method="post" action="$base_url/index.php?$sn=$sid">
<tr>
	<td><input type="submit" name="empty_trash" value=" Empty Trash " /></td>
</tr>
<input type="hidden" name="show" value="messages" />
<input type="hidden" name="folder" value="trash" />
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
	if($query = sql_query($sql)) return true;
	else return false;
}

function delete_message($msg_id){
global $tb_pms;
	$sql = "
		delete from
			$tb_pms
		where
			id = '$msg_id'
	";
	if($query = sql_query($sql)) return true;
	else return false;
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
	if($query = sql_query($sql)) return true;
	else return false;
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
	$query = sql_query($sql);
	$total = sql_result($query,"","count");
	return $total;
}

function folder_table($userid){
global $sn, $sid, $base_url;
	$total_inbox = total_messages($userid, "inbox");
	$total_saved = total_messages($userid, "saved");
	$total_trash = total_messages($userid, "trash");
$content = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
<tr>
	<td class="bold">Folders:</td>
	<td class="regular"><a href="$base_url/index.php?$sn=$sid&amp;show=messages&amp;folder=inbox">Inbox</a>: $total_inbox</td>
	<td class="regular"><a href="$base_url/index.php?$sn=$sid&amp;show=messages&amp;folder=saved">Saved</a>: $total_saved</td>
	<td class="regular"><a href="$base_url/index.php?$sn=$sid&amp;show=messages&amp;folder=trash">Trash</a>: $total_trash</td>
</tr>
</table>
EOF;
	return $content;
}

function get_messages($userid, $folder){
global $tb_pms, $tb_users, $sn, $sid, $base_url;
	$x = 0;
	$folder_table = folder_table($userid);
$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular" colspan="5" align="right">$folder_table</td>
</tr>
EOF;
	$sql = "
		select
			$tb_users.username as author_name,
			$tb_users.sex as gender,
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
	$query = sql_query($sql);
	$total_msgs = sql_num_rows($query);
	if($total_msgs > 0){
$content .= <<<EOF
<tr>
	<td class="bold">From</td>
	<td class="bold">Subject</td>
	<td class="bold" align="right">Recieved</td>
</tr>
EOF;
		while($array = sql_fetch_array($query)){
			$x++;
			$pretty_time = pretty_time($array["timestamp"]);
$content .= <<<EOF
<tr><form method="post" action="$base_url/index.php?$sn=$sid&amp;show=messages&amp;folder=$folder">
<td class="regular" width="20%" nowrap="nowrap"><a href="$base_url/index.php?$sn=$sid&amp;show=view&amp;sing=$array[author_id]&amp;s=$array[gender]">$array[author_name]</a></td>
<td class="regular" width="58%" nowrap="nowrap"><a href="$base_url/index.php?$sn=$sid&amp;show=view_msg&amp;msg_id=$array[pm_id]&amp;folder=$folder">$array[subject]</a></td>
<td class="regular" width="20%" nowrap="nowrap" align="right">$pretty_time</td>
</tr></form>
EOF;
		}
	} else {
		$content .= <<<EOF
		<tr>
			<td class="regular" align="center"><br />No messages in your $folder folder.<br /><br /></td>
		</tr>
EOF;
	}
	$content .= <<<EOF
	</table>
EOF;
	return $content;
}

function get_total_users($sex){
global $tb_users;
	$sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			sex = '$sex'
		and
			image_status	=	'1'
	";
	$query = sql_query($sql);
	$total_users = sql_result($query, 0, "count");
	return $total_users;
}

function template($name, $set="main"){
global $tb_templates;
	$sql = "
		select
			*
		from
			$tb_templates
		where
			name = '$name'
	";
	if($query = sql_query($sql)){
		if(sql_num_rows($query)==1){
			$array = sql_fetch_array($query);
			$template = str_replace("\\'","'",addslashes($array["template"]));
		} else $template = $name . " template not found.";
	}
	return $template;
}

function rand_pass($len = 16){ 
	$char = "abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789";
	$str = "";
	for($i=0;$i<$pass_len;$i++)
		$str .= substr($char, mt_rand(0,62),1);
	return $str ; 
}

function profile_bar($show, $sing, $s, $id){
global $sn, $sid, $show, $sr, $pp, $cp, $base_url;
	$html = "";
	if ($sing>0){
		if($show == "view"){
			$html .= "<span class=\"selectedNav\">Profile</span> | ";
		} else {
$html .= <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;show=view&amp;id=$id&amp;sing=$sing&amp;s=$s#profile" target="_top">Profile</a> | 
EOF;
		}
		if($show == "rate"){
			$html .= "<span class=\"selectedNav\">Rate Me!</span> | ";
		} else {
$html .= <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;show=rate&amp;id=$id&amp;sing=$sing&amp;s=$s#rate" target="_top">Rate Me!</a> | 
EOF;
		}
		if($show == "comment"){
			$html .= "<span class=\"selectedNav\">Comment</span> | ";
		} else {
$html .= <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;show=comment&amp;id=$id&amp;sing=$sing&amp;s=$s#comment" target="_top">Comment</a> | 
EOF;
		}
		if($show == "pm"){
			$html .= "<span class=\"selectedNav\">Private Message</span> | ";
		} else {
$html .= <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;show=pm&amp;id=$id&amp;sing=$sing&amp;s=$s#pm" target="_top">Private Message</a>
EOF;
		}
	} else {
		if($show == "rate"){
			$html .= "<span class=\"selectedNav\">Rate Me!</span> | ";
		} else {
$html .= <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;show=rate&amp;id=$id&amp;s=$s&amp;sr=$sr&amp;pp=$pp&amp;cp=$cp#rate" target="_top">Rate Me!</a> | 
EOF;
		}
		if($show == "comment"){
			$html .= "<span class=\"selectedNav\">Comment</span> | ";
		} else {
$html .= <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;show=comment&amp;id=$id&amp;s=$s&amp;sr=$sr&amp;pp=$pp&amp;cp=$cp#comment" target="_top">Comment</a> | 
EOF;
		}
		if($show == "pm"){
			$html .= "<span class=\"selectedNav\">Private Message</span>";
		} else {
$html .= <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;show=pm&amp;id=$id&amp;s=$s&amp;sr=$sr&amp;pp=$pp&amp;cp=$cp#pm" target="_top">Private Message</a>
EOF;
		}
	}
	return $html;
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
global $pp, $tb_users;
$sex = mf($i);
	$x = 0;
	//$sql_order = convert_order_by($order);
	$sql = "
		select
			id,
			sex,
			average_rating
		from
			$tb_users
		where
			sex = '$sex'
		and
			image_status = '1'
		order by
			average_rating desc
	";
	$query = sql_query($sql);
	while($array = sql_fetch_array($query)){
		if($i == $array["id"]){
			$sr = $x;
			$cp = $x +1;
			return "sr=" . $sr . "&pp=" . $pp . "&cp=" . $cp . "&s=" . $array["sex"];
		}
		$x++;
	}
}

function convert_order_by($order){
	switch($order){
		case "newest" :
			return "newest";
		case "username" :
			return "username";
		case "rating" :
			return "average_rating desc";
		default :
			return "average_rating desc";
	}
}

function nav_links($nr, $pp, $pnp, $pn, $url){
global $sr, $sn, $sid;
	if(!isset($pn)) $pn = 1;
	$pnav = "&nbsp;";
	$link = "";
	$start = "";
	$previous = "";
	$next = "";
	$end = "";
	if($pn >= 2){
		$previous .= " <a href=\"" . $url . $sn . "=" . $sid . "&amp;sr=" . ($sr - $pp);
		$previous .= "&amp;pp=" . $pp . "&amp;cp=" . ($pn - 1) . "\">&lt;&lt; Back</a> ... ";
	}
	if($pn < $nr and ($pn * $pp) < $nr){
		$next .= " ... <a href=\"" . $url . $sn . "=" . $sid . "&amp;sr=" . ($sr + $pp);
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
					$start .= $sn . "=" . $sid . "&amp;sr=0&amp;";
					$start .= "pp=" . $pp . "&amp;cp=1\">&lt;&lt; Start</a> ... ";
				}
				if($cp == $tp){
					$end .= " ... <a class=\"navLink\" href=\"" . $url;
					$end .= $sn . "=" . $sid . "&amp;sr=";
					$end .= ($tp - 1) * $pp . "&amp;pp=" . $pp . "&amp;cp=";
					$end .= $tp . "\">End &gt;&gt;</a> ... ";
				}
			} else {
				if($cp == $pn){
					$link .= " <span class=\"selectedNav\">[ $cp ]</span> ";
				} else {
					$link .= "  <a class=\"navLink\" href=\"" . $url . $sn;
					$link .= "=" . $sid . "&amp;sr=" . ($cp - 1) * $pp;
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

function ow_image($the_file, $the_file_ext, $userid){
global $base_path;
	$error = validate_upload($the_file, $the_file_ext);
	if($error){
		$html = $error;
	} else {
		$file_name = $userid . "." . $the_file_ext;
		if (!@copy($the_file, $base_path . "/images/members/" . $file_name)) {
			$html = "Your image was not updated.  Check the member image directory write permissions.  Errors were experienced when trying to copy a file there.";	
		} else {
			$html = "Your image was updated.  It will have to be reviewed by us before being shown on the site again.  We will review it within 24 hours.";
			update_ext($the_file_ext, $userid);
		}
		update_url("here", "", $userid);
		queue_image($userid);
	}
	return $html;
}

function adm_ow_image($the_file, $the_file_ext, $userid){
global $base_path;
	$error = validate_upload($the_file, $the_file_ext);
	if($error){
		$html = $error;
	} else {
		$file_name = $userid . "." . $the_file_ext;
		if (!@copy($the_file, $base_path . "/images/members/" . $file_name)) {
			$html = "Your image was not updated.  Check the member image directory write permissions.  Errors were experienced when trying to copy a file there.";	
		} else {
			$html = "The image was uploaded.";
			update_ext($the_file_ext, $userid);
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
	if($query = sql_query($sql)){
		$html = "Your URL location has been updated.";
		notify_admin();
	} else $html = "Your URL location was not updated.";
	queue_image($userid);
	return $html;
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
	if($query = sql_query($sql)) $html = "Your URL location has been updated.";
	else $html = "Your URL location was not updated.";
	add_rotation($userid);
	return $html;
}

function drop_rotation($id){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image_status = '-1'
		where
			id = '$id'
	";
	$query = sql_query($sql);
	return true;
}

function queue_image($id){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image_status = '0'
		where
			id = '$id'
	";
	$query = sql_query($sql);
	return true;
}

function add_rotation($id){
global $tb_users;
	$sql = "
		update
			$tb_users
		set
			image_status = '1'
		where
			id = '$id'
	";
	$query = sql_query($sql);
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
	$query = sql_query($sql);
	return true;
}

function new_image($the_file, $the_file_ext, $userid) {
global $base_path, $the_file_type;
	$error = validate_upload($the_file, $the_file_ext);
	if($error){
		$html = $error;
	} else {
		$file_name = $userid . "." . $the_file_ext;
		if (!@copy($the_file, $base_path . "/images/members/" . $file_name)){
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
	if($the_file == "none") {
		$error .= "<li>You did not upload anything!</li>";
	} else {
		$allowed = false;
		$sql = "
			select
				*
			from
				$tb_image_types
		";
		$query = sql_query($sql);
		while($array = sql_fetch_array($query))
			if($the_file_ext == $array["ext"]) $allowed = true;
		if(!$allowed){
			$error .= "<li>The file that you uploaded was of a type that is not<br>allowed, you are only allowed to upload files of the type:<ul>";
			while($array = sql_fetch_array($query))
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
global $base_path, $the_file_type;
	$error = validate_upload($the_file, $the_file_ext);
	if($error){
		$html = $error;
	} else {
		$file_name = $userid . "." . $the_file_ext;
		if (!@copy($the_file, $base_path . "/images/members/" . $file_name)) {
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

// used to delete an image directly from the filesystem
function del_image($id){
global $tb_users, $base_path;
	$sql = "
		select
			concat(id,'.',image_ext) as image
		from
			$tb_users
		where
			id = '$id'
	";
	$query = sql_query($sql);
	$array = sql_fetch_array($query);
	$image = $array["image"];
	$file = $base_path . "/images/members/" . $image;
	if(file_exists($file)){
		if(unlink($file)){
			drop_rotation($id);
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
	$query = sql_query($sql);
	$user_array = sql_fetch_array($query);
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
global $tb_users, $base_url;
	$img_src = $base_url . "/images/notfound_image.gif";
	$sql = "
		select
			id,
			image,
			image_url,
			concat(id,'.',image_ext) as theimage
		from
			$tb_users
		where
			id = '$id'
	";
	$query = sql_query($sql);
	$array = sql_fetch_array($query);
	switch($array["image"]){
		case "here":
			$fp = @fopen($base_url . "/images/members/" . $array["theimage"],"rb");
			if($fp) $img_src = $base_url . "/images/members/" . $array["theimage"];
			break;
		case "there":
			$fp = @fopen($array["image_url"],"rb");
			if($fp) $img_src = $array["image_url"];
			break;
	}
	return $img_src;
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
	$query = sql_query($sql);
	$array = sql_fetch_array($query);
	switch($array["image_status"]){
		case 0 :
			$status = "Queued";
			break;
		case 1 :
			$status = "Approved";
			break;
		default :
			$status = "Inactive";
	}
	return $status;
}

function query_image($id){
global $tb_users, $base_url;
	$sql = "
		select
			concat(id,'.',image_ext) as image
		from
			$tb_users
		where
			id = '$id'
	";
	$query = sql_query($sql);
	$array = sql_fetch_array($query);
	$fp = @fopen($base_url . "/images/members/" . $array["image"], "rb");
	if($fp) return true;
	return false;
}

function query_where($id){
global $tb_users;
	$sql = "select image from $tb_users where id = '$id'";
	$query = sql_query($sql);
	$array = sql_fetch_array($query);
	return $array["image"];
}

function get_version(){
	$buffer = "";
	$fp = fopen("http://destiney.com/prated/version.txt", "r");
	while (!feof($fp))
		$buffer .= fread($fp, 4096);
	return $buffer;
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
	//." at ".$hour.":".$minute." ".$dayPart;
	return $pretty_time;
}

/*
 * $Id: functions.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>
