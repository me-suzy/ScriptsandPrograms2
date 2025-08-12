<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/ARTICLES.PHP > 07-11-2005

$start = TRUE; 
include("system/include.php"); 
if ($checkauth) { 

if ($userdata['level'] >= 1) {

if (!$action) {
?>
<a name='top'></a>
<strong>Add a new article - <a href="articles.php#active">Active articles</a>/<a href="articles.php#hidden">Hidden articles</a></strong><br />
<table><tr><td><form method="post" action="articles.php"><input type="hidden" name="action" value="post"></td></tr></table>
<table>
<tr><td width=175>Author</td><td><?php echo $user; ?></td></tr>
<tr><td width=175>Article title</td><td><input size=50 name='title' type='text'></td></tr>
<tr><td width=175>Post in category</td><td><select name='category'>
<?php
	$query = "SELECT * FROM ".$prefix."categories ORDER BY cid";
	$result = mysql_query($query);while($row=mysql_fetch_object($result)) {
	echo "<option value='$row->cid'>$row->category";
} ?>
</select></td></tr>
<tr><td width=175 valign=top>Article body</td><td><textarea name='body' rows=12 cols=50></textarea></td></tr>
<tr><td width=175 valign=top>Article more</td><td><textarea name='more' rows=12 cols=50></textarea></td></tr>
<tr><td width=175>Show body after more</td><td><input type='checkbox' name='showbody' CHECKED></td></tr>
<tr><td width=175>Enable comments</td><td><input type='checkbox' name='comments' CHECKED></td></tr>
<?php
loadsettings();
if ($settings[startstatus] == 1) $d = " CHECKED";
?>
<tr><td width=175>Article visible<br><br></td><td><input type='checkbox' name='status'<?php echo $d; ?>><br><br></td></tr>
<tr><td width=175>Save changes</td><td><input type='submit' value='proceed'></td></tr>
</table><br>
<?php

	$result = mysql_query("SELECT * FROM ".$prefix."articles WHERE status = '1'");
	$total = mysql_num_rows($result);
	echo "<a name='active'></a><table><tr><td><b>Active articles (".$total.") - <a href='articles.php#top'>Back to top</a></b></td></tr>";
	$result = mysql_query("SELECT * FROM ".$prefix."articles WHERE status = '1' ORDER BY aid DESC");
	while($row=mysql_fetch_object($result)) {
		$resultc = mysql_query("SELECT * FROM ".$prefix."comments WHERE parentid = '".$row->aid."'");
		$totalc = mysql_num_rows($resultc);

		echo "<tr><td width=100%>";
		if ($user == $row->username || $userdata['level'] >= 3) {
			echo "<a href='articles.php?action=edit&aid=$row->aid'>".debbcode($row->title)."</a>";
		} else {
			echo debbcode($row->title);
		}
		echo " by ".$row->username." (<a href='articles.php?action=comview&aid=$row->aid'>comments: ".$totalc."</a>)</td></tr>";
	}
	if (!$total) echo "<tr><td width=100%>None</td></tr>";
	echo "</table><br>";

	$result = mysql_query("SELECT * FROM ".$prefix."articles WHERE status = '0'");
	$total = mysql_num_rows($result);
	echo "<a name='hidden'></a><table><tr><td><b>Hidden articles (".$total.") - <a href='articles.php#top'>Back to top</a></b></td></tr>";
	$result = mysql_query("SELECT * FROM ".$prefix."articles WHERE status = '0' ORDER BY aid DESC");
	while($row=mysql_fetch_object($result)) {
		$resultc = mysql_query("SELECT * FROM ".$prefix."comments WHERE parentid = '".$row->aid."'");
		$totalc = mysql_num_rows($resultc);

		echo "<tr><td width=100%><a href='articles.php?action=edit&aid=$row->aid'>".debbcode($row->title)."</a> by ".$row->username." (<a href='articles.php?action=comview&aid=$row->aid'>comments: ".$totalc."</a>)</td></tr>";
	}
	if (!$total) echo "<tr><td width=100%>None</td></tr>";
	echo "</table>";
} elseif ($action == "post") {
	$title = debbcode($title);
	$title = cleanstring($title);
	$body = cleanstring($body);
	$more = cleanstring($more);
	$body = addslashes($body);
	$more = addslashes($more);
	if ($showbody) { $showbody = 1; } else { $showbody = 0; }
	if ($comments) { $comments = 1; } else { $comments = 0; }
	if ($status) { $status = 1; } else { $status = 0; }
	$postdate = (date ("Ymd"));
	$posttime = (date ("Hi"));

	if (!$title && !$err) { echo $error[11]; $err = 1; }
	if (!$body && !$err) { echo $error[12]; $err = 1; }

	if (!$err) {
		$result = mysql_query("INSERT INTO ".$prefix."articles (username,title,body,more,category,date,time,status,show_comments,show_body) VALUES ('".$userdata['username']."','$title','$body','$more','$category','$postdate','$posttime','$status','$comments','$showbody')");
 	      echo "<meta http-equiv=Refresh content=0;URL='articles.php'>";
	}
} elseif ($action == "edit") {
	$result = mysql_query("SELECT * FROM ".$prefix."articles WHERE aid = '$aid'");
	while($row=mysql_fetch_object($result)) {
	if ($user == $row->username || $userdata['level'] >= 3) {

		if ($row->status) $status = " CHECKED";
		if ($row->show_comments) $comments = " CHECKED";
		if ($row->show_body) $showbody = " CHECKED";
		?>
		<b>Edit an article</b><br>
		<table><tr><td><form method="post" action="articles.php"><input type="hidden" name="action" value="update"><input type="hidden" name="aid" value="<?php echo $aid;?>"></td></tr></table>
		<table>
		<tr><td width=175>Article title</td><td><input size=50 name='title' type='text' value='<?php echo $row->title;?>'></td></tr>
		<tr><td width=175>Post in category</td><td><select name='category'>
		<?php
			$resultcat = mysql_query("SELECT * FROM ".$prefix."categories ORDER BY cid");while($rowc=mysql_fetch_object($resultcat)) {
			if ($rowc->cid == $row->category) {
				echo "<option value='$rowc->cid' SELECTED>$rowc->category";
			} else {
				echo "<option value='$rowc->cid'>$rowc->category";
			}
		} ?>
		</select></td></tr>
		<tr><td width=175 valign=top>Article body</td><td><textarea name='body' rows=9 cols=50><?php echo $row->body;?></textarea></td></tr>
		<tr><td width=175 valign=top>Article more</td><td><textarea name='more' rows=9 cols=50><?php echo $row->more;?></textarea></td></tr>
		<tr><td width=175>Show body when using more</td><td><input type='checkbox' name='showbody'<?php echo $showbody;?>></td></tr>
		<tr><td width=175>Enable comments</td><td><input type='checkbox' name='comments'<?php echo $comments;?>></td></tr>
		<tr><td width=175>Article visible after editing</td><td><input type='checkbox' name='status'<?php echo $status;?>></td></tr>
		<tr><td width=175><br>Delete this post</td><td><br><input type='checkbox' name='delete'></td></tr>
		<tr><td width=175>Save changes</td><td><input type='submit' value='proceed'></td></tr>
		</table><br>
		<?php
	} else { echo "You're not allowed to do that"; }
	}
} elseif ($action == "update") {
	if ($delete) {
		$result = mysql_query("DELETE FROM ".$prefix."articles WHERE aid = '$aid'");
		$result = mysql_query("DELETE FROM ".$prefix."comments WHERE parentid = '$aid'");
		echo "<meta http-equiv=Refresh content=0;URL='articles.php'>";
		$err = 1;
	} else {
	$title = debbcode($title);
	$title = cleanstring($title);
	$body = cleanstring($body);
	$more = cleanstring($more);
	if ($showbody) { $showbody = 1; } else { $showbody = 0; }
	if ($comments) { $comments = 1; } else { $comments = 0; }
	if ($status) { $status = 1; } else { $status = 0; }

	if (!$title && !$err) { echo $error[11]; $err = 1; }
	if (!$body && !$err) { echo $error[12]; $err = 1; }

	if (!$err) {
		$result = mysql_query("UPDATE ".$prefix."articles SET
			title='$title',
			body='$body',
			more='$more',
			category='$category',
			status='$status',
			show_comments='$comments',
			show_body='$showbody'
		WHERE aid = '$aid'");
		echo "<meta http-equiv=Refresh content=0;URL='articles.php'>";
	}
	}
} elseif ($action == "comview") {
	$result = mysql_query("SELECT * FROM ".$prefix."comments WHERE parentid = '".$aid."'");
	$total = mysql_num_rows($result);
	echo "<b>view comments ($total)</b><br><br>";
	$result = mysql_query("SELECT * FROM ".$prefix."comments WHERE parentid = '".$aid."' ORDER BY coid");
	while($row=mysql_fetch_object($result)) {
		datetime($row->date);
		datetime($row->time);
		echo "<b><a href='mailto:$row->email'>$row->author</a></b> ".$dtr['da']."/".$dtr['mo']."/".$dtr['ye']." ".$dtr['ho'].":".$dtr['mi']." - <a href='articles.php?action=comedit&coid=$row->coid'>edit</a><br>";
		echo "$row->comment<br><br>";
	}
	echo "<table><tr><td><form method='post' action='articles.php'><input type='hidden' name='action' value='compost'><input type='hidden' name='aid' value='$aid'></td></tr></table>
		<table>
		<tr><td width=175 valign=top>Author</td><td><input size=30 name='author' type='text' value='".loadprofile($user,"nickname")."'></td></tr>
		<tr><td width=175 valign=top>Author email</td><td><input size=30 name='email' type='text' value='".loadprofile($user,"email")."'></td></tr>
		<tr><td width=175 valign=top>Author url</td><td><input size=30 name='url' type='text' value='".loadprofile($user,"url")."'></td></tr>
		<tr><td width=175 valign=top>Author comment</td><td><textarea name='comment' rows=9 cols=29>$row->comment</textarea></td></tr>
		<tr><td width=175>Save changes</td><td><input type='submit' value='proceed'></td></tr></table>";
} elseif ($action == "comedit") {
	echo "<b>edit comment</b><br>";
	$result = mysql_query("SELECT * FROM ".$prefix."comments WHERE coid = '".$coid."'");
	echo "<table><tr><td><form method='post' action='articles.php'><input type='hidden' name='action' value='comupdate'><input type='hidden' name='coid' value='$coid'></td></tr></table>
	<table>";
	while($row=mysql_fetch_object($result)) {
		echo "
		<tr><td width=175 valign=top>Author</td><td><input size=30 name='author' type='text' value='$row->author'></td></tr>
		<tr><td width=175 valign=top>Author email</td><td><input size=30 name='email' type='text' value='$row->email'></td></tr>
		<tr><td width=175 valign=top>Author url</td><td><input size=30 name='url' type='text' value='$row->url'></td></tr>
		<tr><td width=175 valign=top>Author comment</td><td><textarea name='comment' rows=9 cols=29>$row->comment</textarea></td></tr>
		<tr><td width=175><br>Delete this post</td><td><br><input type='checkbox' name='delete'></td></tr>
		<tr><td width=175>save changes</td><td><input type='submit' value='proceed'></td></tr>";
	}
	echo "</table>";
} elseif ($action == "comupdate") {
	$result = mysql_query("SELECT * FROM ".$prefix."comments WHERE coid = '$coid'");
	while($row=mysql_fetch_object($result)) {
		$aid = $row->parentid;
	}
	if ($delete) {
		$result = mysql_query("DELETE FROM ".$prefix."comments WHERE coid = '$coid'");
		echo "<meta http-equiv=Refresh content=0;URL='articles.php?action=comview&aid=$aid'>";
		$err = 1;
	} else {
	$author = debbcode($author);
	$author = cleanstring($author);
	$email = debbcode($email);
	$email = cleanstring($email);
	$url = debbcode($url);
	$url = cleanstring($url);
	$comment = cleanstring($comment);
	if (!$author && !$err) { echo $error[15]; $err = 1; }
	if (!$comment && !$err) { echo $error[16]; $err = 1; }

	if (!$err) {
		$result = mysql_query("UPDATE ".$prefix."comments SET
			author='$author',
			email='$email',
			url='$url',
			comment='$comment'
		WHERE coid = '$coid'");
	      echo "<meta http-equiv=Refresh content=0;URL='articles.php?action=comview&aid=$aid'>";
	}
	}
} elseif ($action == "compost") {
	$author = debbcode($author);
	$author = cleanstring($author);
	$email = debbcode($email);
	$email = cleanstring($email);
	$url = debbcode($url);
	$url = cleanstring($url);
	$comment = cleanstring($comment);
	$postdate = (date ("Ymd"));
	$posttime = (date ("Hi"));

	if (!$author && !$err) { echo $error[15]; $err = 1; }
	if (!$comment && !$err) { echo $error[16]; $err = 1; }

	if (!$err) {
		$result = mysql_query("INSERT INTO ".$prefix."comments (parentid,author,email,url,comment,date,time) VALUES ('$aid','$author','$email','$url','$comment','$postdate','$posttime')");
      	echo "<meta http-equiv=Refresh content=0;URL='articles.php?action=comview&aid=$aid'>";
	}
}
} ?>
<?php }; $start = FALSE; include("system/include.php"); ?>
