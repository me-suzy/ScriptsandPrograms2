<?php
$la = "a";
$z = "b";
include ("config.php");

if ($ipbancheck2 == "0") {

$yourpoints = points();

echo "<script language='javascript'>function awindow(towhere, newwinname, properties) {window.open(towhere,newwinname,properties);}</script>";

if (($forumskin) && (!$forumskin == "1")) {
$query="SELECT * FROM onecms_skins WHERE id = '".$forumskin."'";
} else {
$query="SELECT * FROM onecms_skins WHERE id = '3'";
}
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$skin1[] = "/{version}/";
	$skin1[] = "/{users}/"; // amount of users online
	$skin1[] = "/{online}/"; // the users that are online
	$skin1[] = "/{pms}/"; // amount of private messages
	$skin1[] = "/{new}/"; // amount of new private messages
	$skin1[] = "/{pm}/"; // display new private messages
	$skin1[] = "/{welcome}/"; // Displays "Welcome username" if person is logged in, other wise "Welcome Guest" and a link to login
	$skin1[] = "/{chooseskin}/";
	$skin1[] = "/{skinname}/"; // the name of the current skin being used
	$skin2[] = "".$version."";
	$skin2[] = online();
	$skin2[] = usersonline();
	$skin2[] = totalpms();
	$skin2[] = newpms();
	$skin2[] = pms();
	$skin2[] = welcome();
	$skin2[] = "<form action='".$REQUEST_URI."' method='post' name='skin2'><select name='skin2' onchange='this.form.submit()'><option value='1' selected>-----</option><?php include ('".$siteurl."/a_skins.php?view=skin2'); ?></select></form>";
	$skin2[] = skinname();

eval (" ?>" . preg_replace($skin1, $skin2, stripslashes($row[header])) . " <?php ");
}

echo "<table cellpadding='5' align='center' cellspacing='2' border='0' width='100%'><tr><td>&nbsp;<a href='".$forumsurl."'><img src='a_images/home.jpg' style='border:1px solid black'></a></td><td>&nbsp;<a href='search.php?view=forums'><img src='a_images/search.jpg' style='border:1px solid black'></a></td>";
if ($_COOKIE[username] == "") {
echo "<td>+&nbsp;<a href='members.php?action=login&step=1&url=".$HTTP_SERVER_VARS['REQUEST_URI']."'>Login</a></td><td>+&nbsp;<a href='members.php?action=register'>Register</a></td>";
} else {
echo "<td>&nbsp;<a href='elite.php'><img src='a_images/fprofile.jpg' style='border:1px solid black'></a></td><td>&nbsp;<a href='members.php?action=logout'><img src='a_images/logout.jpg' style='border:1px solid black'></a></td>";
}

$pms2 = mysql_query("SELECT * FROM onecms_pm WHERE jo = '".$_COOKIE[username]."' AND viewed = '1'") or die(mysql_error());
$pms = mysql_num_rows($pms2);

echo "<td>&nbsp;<a href='members.php?action=list'><img src='a_images/mlist.jpg' style='border:1px solid black'></a></td><td>";

$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."'"));

if ($sql > "0") {
	echo "&nbsp;<a href='boardcp.php'><img src='a_images/boardcp.jpg' style='border:1px solid black'></a></td><td>";
}
if ($_COOKIE[username] == "") {
	echo "Welcome Visitor!";
} else {
	echo "Welcome ".$_COOKIE[username]."! You have (<a href='pm.php?box=in'><b>$pms</b></a>) new pm";
if (($pms == "0") or ($pms > "1")) {
	echo "'s";
}
}
echo "</td></tr></table>";


if (($_GET['t'] == "") && ($_GET['f'] == "")) {
echo "<title>".$sitename." :: Forum Home</title><table cellpadding=5 cellspacing=2 border=1 width=100% style='border:1px solid black'><tr><td style='border:1px solid black'><b>Name</b></td><td style='border:1px solid black'><b>Description</b></td><td style='border:1px solid black'><b>Topics</b></td><td style='border:1px solid black'><b>Replies</b></td><td style='border:1px solid black'><b>Last Post</b></td></tr>";

	$query="SELECT * FROM onecms_forums WHERE type = 'cat' ORDER BY `ord`";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

	echo "<tr><td style='border:1px solid black'><b>".$row[name]."</b></td></tr>";

	$forums = mysql_query("SELECT * FROM onecms_forums WHERE cat = '".$row[name]."' AND type = 'forum' ORDER BY `ord` ASC");
	while($row2 = mysql_fetch_array($forums)) {
		$id = "$row2[id]";

			$topic = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$row2[id]."' AND type = 'topic'"));

			$topic2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$row2[id]."' AND type = 'Sticky'"));

			$topic3 = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$row2[id]."' AND type = 'Announcement'"));

			$topicsa = $topic + $topic2 + $topic3;

			$replies = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$row2[id]."' AND type = 'post'"));

			echo "<tr><td style='border:1px solid black'><a href='".$f1part1."".$row2[id]."".$f1part2."'>".$row2[name]."</a>";
if ($row2[locked]) {
echo " <img src='a_images/locked.jpg' border='1'>";
}
echo "</td><td style='border:1px solid black'>".stripslashes($row2[des])."<br><small>Moderators:";

	$sql = mysql_query("SELECT * FROM onecms_boardcp WHERE type = 'mod' AND place = '".$row2[id]."'");
	while($r = mysql_fetch_array($sql)) {
		$sql2 = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$r[id]."'"));

		echo "&nbsp;<a href='elite.php?user=".$r[uid]."'>".$sql2[0]."</a>";
	}

echo "</small></td><td style='border:1px solid black'>".$topicsa."</td><td style='border:1px solid black'>".$replies."</td><td style='border:1px solid black'>";
    $querys3 = mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$row2[id]."'") or die(mysql_error());
	$see = mysql_num_rows($querys3);

	if ($see > "0") {
	$query3 = mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$row2[id]."' ORDER BY `date` DESC LIMIT 1") or die(mysql_error());
	while($row3 = mysql_fetch_array($query3)) {

echo "<center><a href='".$f2part1."".$row3[tid]."".$f2part2."";

		if ($row3[type] == "post") {
		echo "#".$row3[id]."";
		}

		   echo "'>";

		if ($row3[subject] == "") {
			echo "Post";
		} else {
			print $row3[subject];
		}
		echo "<a><br>".date($dformat, $row3['date'])."</center></td></tr>";
	}
	} else {
		echo "<center>No Posts Made</center></td></tr>";
	}
		}
	}

$posts = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'post'"));
$topics = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'topic' OR type = 'Announcement' OR type = 'Sticky'"));

$members = mysql_num_rows(mysql_query("SELECT * FROM onecms_users"));
$timee = time();
$online = mysql_num_rows(mysql_query("SELECT * FROM onecms_users WHERE logged = '".$timee."'"));

	echo "</table><br><table cellpadding=5 cellspacing=2 border=0 width=50%><tr><td><b>Total Posts:</b> $posts</td></tr><tr><td><b>Total Topics:</b> $topics</td></tr><tr><td><b>Members Registered:</b> $members</td></tr><tr><td>";
	
	if ($online == "1") {
		echo "There is <i>".$online."</i> user";
	} else {
		echo "There are <i>".$online."</i> users";
	}
	echo " online</td></tr><tr><td>";

	$sqleee421 = mysql_query("SELECT * FROM onecms_users WHERE logged = '".time()."'");
    while($i = mysql_fetch_array($sqleee421)) {
		$loggedd423 = "$i[logged]";

	$onv = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$i[username]."'");
    while($y = mysql_fetch_array($onv)) {
	$yid = "$row[id]";
	
	$onvr = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$i[username]."'");
	$on2v = mysql_num_rows($onvr);
    if ($loggedd423 == "0") {
    } else {
	echo "<a href='elite.php?user=".$y[id]."'>".$i[username]."</a>";
	}
	}
	}
	echo "</td></tr></table>";
} // checked and 100% alright

if ((is_numeric($_GET['f'])) && ($_GET['t'] == "")) {
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$e2 = $e;
$from = (($page * $e2) - $e2);

		$find = mysql_query("SELECT * FROM onecms_forums WHERE id = '".$_GET['f']."'");
		while($f = mysql_fetch_array($find)) {
			$explode2 = explode("||", $f[name]);

			if ($explode2[0] == "") {
			$forumname = "$f[name]";
			} else {
			$forumname == $explode2[0];
			}
			$fa = "$f[type]";
			$cat = "$f[cat]";
		}

		$fname = mysql_fetch_row(mysql_query("SELECT name FROM onecms_forums WHERE id = '".$_GET['f']."'"));

		echo "<title>".$sitename." :: Viewing Forum - ".$fname[0]."</title><center><b>Sub-Forums</b> <img src='a_images/subforum.jpg' border='1'></center><table cellpadding=3 cellspacing=3 border=0 width=100% style='border:1px solid black'><tr><td style='border:1px solid black'><b>Name</b></td><td style='border:1px solid black'><b>Description</b></td><td style='border:1px solid black'><b>Topics</b></td><td style='border:1px solid black'><b>Replies</b></td><td style='border:1px solid black'><b>Last Post</b></td></tr>";

$ssql = mysql_query("SELECT * FROM onecms_forums WHERE type = 'sub'");
while($r = mysql_fetch_array($ssql)) {
$explode = explode("||", $r[name]);

if ($explode[1] == $_GET['f']) {

$topic = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$r[id]."' AND type = 'topic'"));

$topic2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$r[id]."' AND type = 'Sticky'"));

$topic3 = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$r[id]."' AND type = 'Announcement'"));

$numg3 = $topic + $topic2 + $topic3;

$query4k = mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$r[id]."' AND type = 'post'") or die(mysql_error());
$numg22 = mysql_num_rows($query4k);

echo "<tr><td style='border:1px solid black'><a href='".$f1part1."".$r[id]."".$f1part2."'>".$explode[0]."</a>";

if ($r[locked]) {
echo " <img src='a_images/locked.jpg' border='1'>";
}
echo "</td><td style='border:1px solid black'>".stripslashes($r[des])."<br><small>Moderators:";

	$sql = mysql_query("SELECT * FROM onecms_boardcp WHERE type = 'mod' AND place = '".$r[id]."'");
	while($ra = mysql_fetch_array($sql)) {
		$sql2 = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$ra[id]."'"));

		echo "&nbsp;<a href='elite.php?user=".$ra[uid]."'>".$sql2[0]."</a>";
	}

echo "</small></td><td style='border:1px solid black'>".$numg3."</td><td style='border:1px solid black'>".$numg22."</td><td style='border:1px solid black'>";

	$querys3 = mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND fid = '".$_GET['f']."'") or die(mysql_error());
	$see = mysql_num_rows($querys3);
	if ($see > "0") {
	$query3 = mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND fid = '".$_GET['f']."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
	while($row3 = mysql_fetch_array($query3)) {

		echo "<center><a href='".$f2part1."".$row3[id]."".$f2part2."#".$row3[id]."'>";

		if ($row3[subject] == "") {
			echo "Post";
		} else {
			print $row3[subject];
		}
		echo "<a><br>".date($dformat, $row3['date'])."</center></td></tr>";
	}
	} else {
		echo "<center>No Posts Made</center></td></tr>";
	}
		}
		}
		echo "</table><table cellpadding=3 cellspacing=2 border=0 width=100%><tr><td align='center'>";

$sql = mysql_query("SELECT locked FROM onecms_forums WHERE id = '".$_GET['f']."'");
$fa = mysql_fetch_row($sql);

if ($fa[0] == "") {
if ((($_COOKIE[username]) or ($b == "yes") && ($_COOKIE[username] == ""))) {
echo "<a href='boards.php?t=new&f=".$_GET['f']."'><img src='a_images/topic.jpg' style='border:1px solid black'></a>";
}
}
echo "</td><td align='right'>";
		
		$rowba = mysql_fetch_row(mysql_query("SELECT id,name FROM onecms_forums WHERE type = 'sub' OR type = 'forum' AND cat = '".$f[cat]."'"));
		if ($rowba[0] == $explode2[1]) {
		echo "<a href='".$f1part1."".$rowba[0]."".$f1part2."'>".$rowba[1]."</a>";
		}
		
		echo "<a href='".$f1part1."".$_GET['f']."".$f1part2."'>".$explode2[0]."</a></td></tr></table><table cellpadding=3 cellspacing=2 border=0 width=100% style='border:1px solid black'><tr><td style='border:1px solid black'><b>Topic Name</b></td><td style='border:1px solid black'><b>Poster</b></td><td style='border:1px solid black'><b>Replies</b></td><td style='border:1px solid black'><b>Views</b></td><td style='border:1px solid black'><b>Last Post</b></td></tr>";

	$query2at = mysql_query("SELECT * FROM onecms_posts WHERE fid = '".$_GET['f']."' AND type = 'Sticky' ORDER BY `date` DESC");
	while($row2 = mysql_fetch_array($query2at)) {
	   $tid = "$row2[id]";
	   $views = "$row2[stats]";

       echo "<tr><td style='border:1px solid black'><img src='a_images/Sticky.jpg' border='1'>&nbsp;<a href='".$f2part1."".$row2[id]."".$f2part2."'><b>Sticky:</b> ".$row2[subject]."</a>";

	   if ($row2[locked] == "yes") {
	   echo "&nbsp;&nbsp;<img src='a_images/locked.jpg' border='1'>";
	   }
	   
	   echo "</td><td style='border:1px solid black'>";

	if ($row2[uid]) {
	$user = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$row2[uid]."' LIMIT 1"));
    echo "<a href='elite.php?user=".$row2[uid]."'> ".$user[0]."</a>";
	} else {
	echo "Visitor";
	}

	$replies = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."'"));

	$lcount = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."'"));

    if ($lcount > "0") {
	$query3w = mysql_fetch_row(mysql_query("SELECT date FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."' ORDER BY `date` DESC LIMIT 1"));
	$date = $query3w[0];
	} else {
    $query3w = mysql_fetch_row(mysql_query("SELECT date FROM onecms_posts WHERE id = '".$tid."'"));
	$date = $query3w[0];
	}
	

		$last = "".date($dformat, $date)."<br>";

		if ($row2[uid]) {
		$last .= "<a href='javascript:awindow(\"users.php?mid=".$row4[username]."\", \"\", \"width=600,height=350,scroll=yes\")'> $row4[username]</a>";
		} else {
		$last .= "Visitor";
		}
	echo "</td><td style='border:1px solid black'>".$replies."</td><td style='border:1px solid black'>".$views."</td><td style='border:1px solid black'>".$last."</td></tr>";
	}

	$query2at = mysql_query("SELECT * FROM onecms_posts WHERE type = 'Announcement' AND fid = '".$_GET['f']."' ORDER BY `date` DESC");
	while($row2 = mysql_fetch_array($query2at)) {
	   $tid = "$row2[id]";
	   $views = "$row2[stats]";

echo "<tr><td style='border:1px solid black'><img src='a_images/".$row2[type].".jpg' border='1'>&nbsp;<a href='".$f2part1."".$row2[id]."".$f2part2."'><b>".$row2[type].":</b>".$row2[subject]."</a>";

	   if ($row2[locked] == "yes") {
	   echo "&nbsp;&nbsp;<img src='a_images/locked.jpg' border='1'>";
	   }
	   
	   echo "</td><td style='border:1px solid black'>";

	if ($row2[uid]) {
    $user = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$row2[uid]."' LIMIT 1"));
    echo "<a href='elite.php?user=".$row2[uid]."'> ".$user[0]."</a>";
	} else {
	echo "Visitor";
	}

	$replies = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."'"));

	$lcount = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."'"));

    if ($lcount > "0") {
	$query3w = mysql_fetch_row(mysql_query("SELECT date FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."' ORDER BY `date` DESC LIMIT 1"));
	$date = $query3w[0];
	} else {
    $query3w = mysql_fetch_row(mysql_query("SELECT date FROM onecms_posts WHERE id = '".$tid."'"));
	$date = $query3w[0];
	}

		$last = "".date($dformat, $row3w[0])."<br>";

		if ($row2[uid]) {
		$last .= "<a href='elite.php?user=".$row2[uid]."'> $row4[username]</a>";
		} else {
		$last .= "Visitor";
		}
	
	echo "</td><td style='border:1px solid black'>".$replies."</td><td style='border:1px solid black'>".$views."</td><td style='border:1px solid black'>".$last."</td></tr>";
	}

	$query2at = mysql_query("SELECT * FROM onecms_posts WHERE type = 'topic' AND fid = '".$_GET['f']."' ORDER BY `date` DESC");
	while($row2 = mysql_fetch_array($query2at)) {
	   $tid = "$row2[id]";
	   $views = "$row2[stats]";

       echo "<tr><td style='border:1px solid black'>";
	   
	   if ($row2[type] == "Announcement") {
		   echo "<img src='a_images/Announcement.jpg' border='1'>&nbsp;<a href='".$f2part1."".$row2[id]."".$f2part2."'><b>".$row2[type].":</b>";
	   } else {
		   echo "<a href='".$f2part1."".$row2[id]."".$f2part2."'>";
	   }
	   echo " ".$row2[subject]."</a>";

	   	   if ($row2[locked] == "yes") {
		   echo "&nbsp;&nbsp;<img src='a_images/locked.jpg' border='1'>";
	   }
	   
	   echo "</td><td style='border:1px solid black'>";

	if ($row2[uid]) {

	$user = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$row2[uid]."' LIMIT 1"));
    echo "<a href='elite.php?user=".$row2[uid]."'> ".$user[0]."</a>";
	} else {
	echo "Visitor";
	}

	$replies = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."'"));

	$lcount = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."'"));

    if ($lcount > "0") {
	$query3w = mysql_fetch_row(mysql_query("SELECT date FROM onecms_posts WHERE type = 'post' AND tid = '".$tid."' ORDER BY `date` DESC LIMIT 1"));
	$date = $query3w[0];
	} else {
    $query3w = mysql_fetch_row(mysql_query("SELECT date FROM onecms_posts WHERE id = '".$tid."'"));
	$date = $query3w[0];
	}

		$last = "".date($dformat, $row3w[0])."<br>";

		if ($row2[uid]) {
		$last .= "<a href='elite.php?user=".$row2[uid]."'> $row4[username]</a>";
		} else {
		$last .= "Visitor";
		}
	echo "</td><td style='border:1px solid black'>".$replies."</td><td style='border:1px solid black'>".$views."</td><td style='border:1px solid black'>";
	if ($last == "") {
		echo "No Replies Made";
	} else {
		print $last;
	}

	echo "</td></tr>";
	}
	echo "</table><br>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_posts WHERE fid = '".$_GET['f']."' AND type = 'topic' OR type = 'Announcement'"),0);

$total_pages = ceil($total_results / $e2);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
	    $test = ($page - 1);
    echo "<a href=\"boards.php?f=".$_GET['f']."&page=$test\"><b>Previous</b></a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"boards.php?f=".$_GET['f']."&page=$i\">$i</a>&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"boards.php?f=".$_GET['f']."&page=$next\"><b>Next</b></a>";
}
echo "</center>";
}

if (($_GET['locked'] == "yes") && ($_GET['t'])) {
	$res = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'global' OR level = 'admin'");

	$ou = mysql_num_rows($res);

	$res2 = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'mod' AND place = '".$_GET['f']."'");

    $ou2 = mysql_num_rows($res2);
	if (($ou > "0") or ($ou2 > "0")) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to lock this topic?");
if (agree)
document.write("");
else
history.go(-1);
</SCRIPT>';

$lock = mysql_query("UPDATE onecms_posts SET locked = 'yes' WHERE id = '".$_GET['t']."'") or die(mysql_error());

if ($lock == TRUE) {
echo "<center><b>You successfully locked this topic</b><br><a href='".$f2part1."".$_GET['t']."".$f2part2."'>View Topic</a><br><a href='boards.php?t=".$_GET['t']."&locked=no'>Unlock Topic</a></center>";
}
}
}

if (($_GET['locked'] == "no") && ($_GET['t'])) {
$res = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'global' OR level = 'admin'");
$ou = mysql_num_rows($res);

$res2 = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'mod' AND place = '".$_GET['f']."'");

$ou2 = mysql_num_rows($res2);
if (($ou > "0") or ($ou2 > "0")) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to unlock this topic?");
if (agree)
document.write("");
else
history.go(-1);
</SCRIPT>';

$lock = mysql_query("UPDATE onecms_posts SET locked = 'no' WHERE id = '".$_GET['t']."'") or die(mysql_error());

if ($lock == TRUE) {
echo "<center><b>You successfully unlocked this topic</b><br><a href='".$f2part1."".$_GET['t']."".$f2part2."'>View Topic</a><br><a href='boards.php?t=".$_GET['t']."&locked=yes'>Lock Topic</a></center>";
}
}
}

if ($_GET['t'] == "options") {
echo "<form action='".$HTTP_SERVER_VARS['REQUEST_URI']."' method='post' name='skin2'><select name='skin2' onchange='this.form.submit()'><option value='1' selected>-----</option>";

$query="SELECT * FROM onecms_skins WHERE type = 'forum'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
echo "<option value='".$row[id]."'>".$row[name]."</option>";
}

echo "</select></form>";
}

if (($_GET['t'] == "new") && (is_numeric($_GET['f']))) {
$sql = mysql_fetch_row(mysql_query("SELECT name FROM onecms_forums WHERE id = '".$_GET['f']."'"));

echo "<title>".$sitename." :: Posting New Topic - Forum: ".$sql[0]."</title>";

if (($_COOKIE[username] == "") && ($b == "no")) {
echo "Sorry, but you have to be logged in, in order to post a topic on the ".$sitename." forums. Please <a href='members.php?action=login&step=1'><b>Login</b></a> or <a href='members.php?action=register'><b>Register</b></a>";
} else {

if (!$_POST['submit']) {
echo "<SCRIPT LANGUAGE='JavaScript'>
  function smiles(which) {
  document.form1.text1.value = document.form1.text1.value + which;
  }
</SCRIPT>
<script language='javascript'>
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\"><form name='form1' method='post' action='".$HTTP_SERVER_VARS['REQUEST_URI']."'><tr><td><b>Username</b></td>";

if (($_COOKIE[username] == "") && ($d == "no")) {
echo "<td><input type='hidden' name='username' value='Visitor'>Visitor</td>";
}

if (($_COOKIE[username] == "") && ($d == "yes")) {
	echo "<td><input type='text' name='username' value=''></td>";
}

if ($_COOKIE[username]) {
	echo "<td><input type='hidden' name='username' value='".$_COOKIE[username]."'>".$_COOKIE[username]."</td>";
}

echo "</tr><tr><td><b>Subject</b></td><td><input type='text' name='subject'></td></tr><tr><td><b>Message</b></td><td><textarea name=\"text1\" cols='40' rows='16'></textarea><input type='hidden' name='fid' value='".$_GET['f']."'></td>";

if ((($_COOKIE[username] == "") && ($c == "yes") or ($_COOKIE[username]))) {
echo "<td width='75'><b><center>Smilies</center></b><center>";

$query2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
$limit = mysql_num_rows($query2);
$query="SELECT * FROM onecms_comments1 WHERE type = 'smiley' LIMIT 9";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

$tag = "$row[field]";
$name = "$row[name]";
list($width, $height, $type, $attr) = getimagesize("".$images."/".$tag."");
echo "<a href=\"javascript:smiles(' ".$name." ')\"><img src='".$images."/".$tag."' border='0' width='";
if ($width > "20") {
echo "20";
} else {
echo "$width";
}
echo "'></a>";
if (($limit/3) == (int)($limit/3)) {
	echo "<br>";
}
}
echo "<br><br><a href='javascript:awindow(\"comments.php?view=smilies\", \"\", \"width=200,height=200,scroll=yes\")'>View All</a>";
echo "</center></td></tr>";
}

if ($useridn) {
$result = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'admin' OR level = 'mod' OR level = 'global'");
$oj = mysql_num_rows($result);
if ($oj > "0") {

echo "<tr><td>Type of topic:</td><td><input type='radio' name='type' value='' checked>Normal</td><td><input type='radio' name='type' value='Sticky'>Sticky</td><td><input type='radio' name='type' value='Announcement'>Announcement</td></tr>";
}
}

echo "<tr><td><input type='submit' name='submit' value='Submit Topic'></td><td><input type='reset' name='reset' value='Reset'></td></tr></table></form>";
}

if ($_POST['submit']) {
if (($_COOKIE[username] == "") && ($d == "yes")) {
$check = mysql_num_rows(mysql_query("SELECT * FROM onecms_users WHERE username = '".$_POST['username']."'"));
if ($check > "0") {
echo "Sorry but this username is already in use, please go back and choose another username";
die;
}
}

$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST["text1"]));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST["text1"]));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST["text1"]));

$topic2 = "INSERT INTO onecms_posts VALUES ('null', '".addslashes($_POST["subject"])."', '".$autobr."', '".$useridn."', '".time()."', '".$_POST["fid"]."', '', '0', '";
					
if ($_POST['type']) {
$topic2 .= "".$_POST['type']."";
} else {
$topic2 .= "topic";
}

$topic2 .= "', 'no')";
$topic = mysql_query($topic2);
					
	$query = mysql_fetch_row(mysql_query("SELECT id FROM onecms_posts WHERE subject = '".$_POST["subject"]."' AND type = 'topic' OR type = 'Announcement' OR type = 'Sticky' AND message = '".$_POST["text1"]."'"));
	$topicid = $query[0];

	$topic2 = mysql_query("UPDATE onecms_posts SET tid = '".$topicid."' WHERE id = '".$topicid."'");

if ($topic == TRUE) {
echo "<center>Topic has been created.</center><br><center>Click <a href='".$f1part1."".$_GET['fid']."".$f1part2."'><b>here</b></a> to return to the forum<br>Click <a href='".$f2part1."".$topicid."".$f2part2."'><b>here</b></a> to view your topic</center>";
}
}
}
}
		
		if (is_numeric($_GET['t'])) {
		$sql = mysql_query("SELECT fid,locked,subject FROM onecms_posts WHERE id = '".$_GET['t']."' AND type = 'topic' OR type = 'Sticky' OR type = 'Announcement'");
		$fetch = mysql_fetch_row($sql);

		echo "<title>".$sitename." :: Viewing Topic - ".stripslashes($fetch[2])."</title>";

		$find = mysql_fetch_row(mysql_query("SELECT name,cat,locked FROM onecms_forums WHERE id = '".$fetch[0]."'"));
		$forumname = $find[0];
		$cat = $find[1];
		$locked = $find[2];

		echo "<table cellpadding=3 cellspacing=2 border=0 width=100%><tr><td align='left'>";
		
		if (($locked == "") && ($_GET['f'])) {
		if ((($_COOKIE[username]) or ($b == "yes") && ($_COOKIE[username] == ""))) {
		echo "<a href='boards.php?t=new&f=".$p[fid]."'><img src='a_images/topic.jpg' style='border:1px solid black'></a>";
		}
		}
		echo "</td><td><a href='".$forumsurl."'>Home</a> ><a href='".$f1part1."".$fetch[0]."".$f1part2."'>".$forumname."</a>";

		$rowba = mysql_fetch_row(mysql_query("SELECT id,name FROM onecms_forums WHERE type = 'sub' AND cat = '".$cat."'"));
		if ($rowba[0] == $fetch[0]) {
		echo "> <a href='".$f1part1."".$rowba[0]."".$f1part2."'>".$rowba[1]."</a>";
		}

		echo "</td>";
	
	if ($_COOKIE[username]) {
	$result2 = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'global' OR level = 'mod' OR level = 'admin'");
    $ou = mysql_num_rows($result2);
	if ($ou > "0") {
	if ($fetch[1] == "yes") {
	echo "<td><a href='boards.php?t=".$_GET['t']."&f=".$fetch[0]."&locked=no'>Unlock Topic?</a></td>";
	} else {
	echo "<td><a href='boards.php?t=".$_GET['t']."&f=".$fetch[0]."&locked=yes'>Lock Topic?</a></td>";
	}
	}
	}

	echo "</tr></table><table cellpadding='3' cellspacing='2' border='0' width='100%' style='border:1px solid black'>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $u) - $u);
					$query = "SELECT * FROM onecms_posts WHERE tid = '".$_GET['t']."' OR id = '".$_GET['t']."' ORDER BY `id` ASC LIMIT $from, $u";
                    $result = mysql_query($query);
                    while($p = mysql_fetch_array($result)) {
						echo "<tr><td align='left' style='border:1px solid black'>";

                        if ($p[uid]) {
						$profile2 = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$p[uid]."'"));
						$pusername = $profile2[0];

						$to = mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND uid = '".$p[uid]."'");
						$posts = mysql_num_rows($to);

						$tot = mysql_query("SELECT * FROM onecms_posts WHERE uid = '".$p[uid]."'");
						$topicsa = mysql_num_rows($tot);

						$topics = $topicsa - $posts;

$sql = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$pusername."'");
while($row = mysql_fetch_array($sql)){
	$av = "$row[avatar]";

	$signature2 = stripslashes($row[sig]);
	$signaturey = preg_replace("/<br>\n/","\n",$signature2);
	$signaturey = preg_replace("/<br\>\n/","\n",$signature2);
	$signaturey = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",$signature2);

	                        $sm = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
                            while ($row = mysql_fetch_array($sm)) {
                      	    $name = "$row[name]";
	                        $ty[] = "$name";
	                        $np[] = "<img src='".$images."/".$row[field]."'>";
							}
                            $signature = str_replace($ty, $np, $signaturey);
}
}

if ($p[uid]) {
$ranks2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE uid = '".$p[uid]."'"));

$ranks = mysql_query("SELECT * FROM onecms_ranks ORDER BY `points` ASC");
while($s = mysql_fetch_array($ranks)) {
	if (($ranks2 > $s[points]) or ($s[points] == $ranks2)) {
	$rank = "<center><a href='elite.php?user=".$p[uid]."'><font color='".$s[color]."' size='1'>".$pusername."</font></a><br><small>".$s[name]."</small><br><br>";
	}
}

if ($rank == "") {
echo "<center><a href='elite.php?user=".$p[uid]."'>".$pusername."</a><br>";
} else {
print $rank;
}
	$page = @file_get_contents("$av");
	if (!$page == NULL) {
	$avatar = "<img src='".$av."'";

	list($widtha, $heighta) = getimagesize($av);

	if ($widtha > $avat1) {
	$avatar .= " width='".$avat1."'";
	}
	if ($heighta > $avat2) {
	$avatar .= " height='".$avat1."'";
	}

	$avatar .= " border='1'>";
	print $avatar;
	}
    }
	if ($p[uid] == "") {
	echo "<center>Visitor<br>";
	}

	$val = $p[stats] + 1;

	mysql_query("UPDATE onecms_posts SET stats = '".$val."' WHERE tid = '".$p[tid]."'");
	
	if ($p[uid]) {		
	echo "<br><br><b>Posts:</b> $posts<br><b>Topics:</b> $topics";
	}
	
	echo "</center></td><td width='85%' style='border:1px solid black'><b>".stripslashes($p[subject])."</b> posted at <i>".date($dformat, $p['date'])."</i> - ".$p[stats]." views<br><br><p><a name='".$p[id]."'>";
	$m = stripslashes($p[message]);

    $smiley = "SELECT * FROM onecms_comments1 WHERE type = 'smiley'";
    $hehe = mysql_query($smiley);
    while ($row = mysql_fetch_array($hehe)) {
	    $s[] = "".$row[name]."";
	    $r[] = "<img src=\"".$images."/".$row[field]."\">";
	}

	$hehe2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'badword'");
    while ($row = mysql_fetch_array($hehe2)) {
		$s[] = "".$row[name]."";
		$r[] = "".$row[field]."";
	}

							$s[] = "{quote}";
							$s[] = "{endquote}";
							$r[] = "<table cellspacing='1' cellpadding='1' border='0' align='center' style='border:1px solid black'><tr><td align='center'><b>Quote:</b></td></tr><tr><td align='center'>";
							$r[] = "</td></tr></table><br>";
                            echo str_replace($s, $r, stripslashes($p[message]));
							echo "</p>";
							if ($p[uid]) {
							echo "<br>---------------<br>".$signature."";
							}
							echo "</td></tr><tr><td style='border:1px solid black'><center>";
							if (($p[uid] > "0") && ($_COOKIE[username])) {
							echo "<a href='pm.php?new=msg&user=".$pusername."'><img src='a_images/pm.jpg' style='border:1px solid black'></a>";
							}

							echo "</center></td><td style='border:1px solid black'>";

							$flock = mysql_fetch_row(mysql_query("SELECT locked FROM onecms_forums WHERE id = '".$p[fid]."'"));

							if ((($p[locked] == "no") && ($locked == "") && ($flock[0] == ""))) {
							if ((($_COOKIE[username] == "") && ($a == "yes") or ($_COOKIE[username]))) {
							echo "<a href='boards.php?t=reply&id=".$p[id]."&tid=".$p[tid]."&fid=".$p[fid]."'><img src='a_images/reply.jpg' style='border:1px solid black'></a>&nbsp;&nbsp;&nbsp;<a href='boards.php?t=quote&id=".$p[id]."&tid=".$p[tid]."&fid=".$p[fid]."'><img src='a_images/quote.jpg' style='border:1px solid black'></a>&nbsp;&nbsp;&nbsp;";
							}
							}

    if ($_COOKIE[username]) {
	$res = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'global' OR level = 'admin'");

	$ou = mysql_num_rows($res);

	$res2 = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'mod' AND place = '".$p[fid]."'");

    $ou2 = mysql_num_rows($res2);
	if (($ou > "0") or ($ou2 > "0")) {					
	echo "<a href='boardcp.php?view=users3&act=delete&id=".$p[id]."'><img src='a_images/delete.jpg' style='border:1px solid black'></a>&nbsp;&nbsp;&nbsp;<a href='boardcp.php?view=users3&act=update&id=".$p[id]."'><img src='a_images/edit.jpg' style='border:1px solid black'></a>&nbsp;&nbsp;&nbsp;";
	}
	}
	echo "</td></tr>";
	}
	echo "</table>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_posts WHERE tid = '".$_GET['t']."' AND type = 'post'"),0);

$total_pages = ceil($total_results / $u);

if ($total_pages) {
echo "<center>Select a Page<br>";
}

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"boards.php?t=".$_GET['t']."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"boards.php?t=".$_GET['t']."&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"boards.php?t=".$_GET['t']."&page=$next\">Next>></a>";
}
echo "</center>";
}

if ($_GET['t'] == "quote") {
if (($_COOKIE[username] == "") && ($a == "no")) {
echo "Sorry, but you have to be logged in, in order to post on the ".$sitename." forums. Please <a href='members.php?action=login&step=1&url=".$HTTP_SERVER_VARS['REQUEST_URI']."'><b>Login</b></a> or <a href='members.php?action=register'><b>Register</b></a>";
} else {

if (!$_POST['submit']) {	
$sql = mysql_fetch_row(mysql_query("SELECT subject FROM onecms_posts WHERE id = '".$_GET['id']."'"));

echo "<title>".$sitename." :: Quoting Post - ".$sql[0]."</title>";
echo "<SCRIPT LANGUAGE='JavaScript'>
  function smiles(which) {
  document.form1.text1.value = document.form1.text1.value + which;
  }
</SCRIPT>
<script language='javascript'>
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\"><form name='form1' method='post' action='".$HTTP_SERVER_VARS['REQUEST_URI']."'><tr><td><b>Username</b></td>";
if (($useridn == "") && ($d == "no")) {
	echo "<td><input type='hidden' name='username' value='Visitor'>Visitor</td>";
}

if (($useridn == "") && ($d == "yes")) {
	echo "<td><input type='text' name='username' value=''></td>";
}

if ($useridn) {
	echo "<td><input type='hidden' name='username' value='".$_COOKIE[username]."'>".$_COOKIE[username]."</td>";
}

$smilies = mysql_fetch_row(mysql_query("SELECT subject,message FROM onecms_posts WHERE id = '".$_GET['id']."'"));
$topic = stripslashes($smilies[0]);
$msg = stripslashes($smilies[1]);

echo "<input type='hidden' name='id' value='".$_GET['id']."'><input type='hidden' name='tid' value='".$_GET['tid']."'></tr><tr><td><b>Subject</b></td><td><input type='text' name='subject' value='Re: ".$topic."'></td></tr><tr><td><b>Message</b></td><td><textarea name=\"text1\" cols='40' rows='16'>{quote}".$msg."{endquote}

</textarea></td><td width='75'><b><center>Smilies</center></b><center><input type='hidden' name='fid' value='".$_GET['fid']."'>";

$query2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
$limit = mysql_num_rows($query2);

$smilies = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley' LIMIT 9");
while($row = mysql_fetch_array($smilies)) {
$tag = "$row[field]";
$name = "$row[name]";

list($width, $height, $type, $attr) = getimagesize("".$images."/".$tag."");
echo "<a href=\"javascript:smiles(' ".$name." ')\"><img src='".$images."/".$tag."' border='0' width='";
if ($width > "20") {
echo "20";
} else {
echo "$width";
}
echo "'></a>";
if (($limit/3) == (int)($limit/3)) {
	echo "<br>";
}
}
echo "<br><br><a href='javascript:awindow(\"comments.php?view=smilies\", \"\", \"width=200,height=200,scroll=yes\")'>View All</a>";
echo "</center></td></tr><tr><td><input type='submit' name='submit' value='Submit Post'></td><td><input type='reset' name='reset' value='Reset'></td></tr></table></form>";
}

if ($_POST['submit']) {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST["text1"]));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST["text1"]));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST["text1"]));

$post = mysql_query("INSERT INTO onecms_posts VALUES ('null', '".addslashes($_POST["subject"])."', '".$autobr."', '".$useridn."', '".time()."', '".$_POST["fid"]."', '".$_POST["tid"]."', '0', 'post', 'no')") or die(mysql_error());
					
$query = "SELECT id FROM onecms_posts WHERE subject = '".$_POST["subject"]."' AND type = 'post' AND message = '".$_POST["text1"]."'";
$result = mysql_fetch_row(mysql_query($query));
$postid = $result[0];

if ($post == TRUE) {
echo "<center>Post has been created.</center><br><center>Click <a href='".$f1part1."".$_GET['fid']."".$f1part2."'><b>here</b></a> to return to the forum<br>Click <a href='".$f2part1."".$_GET['tid']."".$f2part2."#".$postid."'><b>here</b></a> to view your post</center>";
}
}
}
}

if ($_GET['t'] == "reply") {
$sql = mysql_fetch_row(mysql_query("SELECT subject FROM onecms_posts WHERE id = '".$_GET['id']."'"));

echo "<title>".$sitename." :: Replying to Post - ".$sql[0]."</title>";
if (($_COOKIE[username] == "") && ($a == "no")) {
echo "Sorry, but you have to be logged in, in order to post on the ".$sitename." forums. Please <a href='members.php?action=login&step=1&url=".$HTTP_SERVER_VARS['REQUEST_URI']."'><b>Login</b></a> or <a href='members.php?action=register'><b>Register</b></a>";
} else {

if (!$_POST['submit']) {
echo "<SCRIPT LANGUAGE='JavaScript'>
  function smiles(which) {
  document.form1.text1.value = document.form1.text1.value + which;
  }
</SCRIPT>
<script language='javascript'>
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\"><form name='form1' method='post' action='".$HTTP_SERVER_VARS['REQUEST_URI']."'><tr><td><b>Username</b></td>";
if (($useridn == "") && ($d == "no")) {
	echo "<td><input type='hidden' name='username' value='Visitor'>Visitor</td>";
}

if (($useridn == "") && ($d == "yes")) {
	echo "<td><input type='text' name='username' value=''></td>";
}

if ($useridn) {
	echo "<td><input type='hidden' name='username' value='".$_COOKIE[username]."'>".$_COOKIE[username]."</td>";
}

$smilies2 = mysql_query("SELECT subject FROM onecms_posts WHERE id = '".$_GET['id']."'");
$topic = stripslashes($smilies2[0]);

echo "<input type='hidden' name='id' value='".$_GET['id']."'><input type='hidden' name='tid' value='".$_GET['tid']."'></tr><tr><td><b>Subject</b></td><td><input type='text' name='subject' value='Re: ".$topic."'></td></tr><tr><td><b>Message</b></td><td><textarea name=\"text1\" cols='40' rows='16'></textarea></td><td width='75'><b><center>Smilies</center></b><center><input type='hidden' name='fid' value='".$_GET['fid']."'>";

$query2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
$limit = mysql_num_rows($query2);

$smilies = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley' LIMIT 9");
while($row = mysql_fetch_array($smilies)) {

$tag = "$row[field]";
$name = "$row[name]";
list($width, $height, $type, $attr) = getimagesize("".$images."/".$tag."");
echo "<a href=\"javascript:smiles(' ".$name." ')\"><img src='".$images."/".$tag."' border='0' width='";
if ($width > "20") {
echo "20";
} else {
echo "$width";
}
echo "'></a>";
if (($limit/3) == (int)($limit/3)) {
echo "<br>";
}
}
echo "<br><br><a href='javascript:awindow(\"comments.php?view=smilies\", \"\", \"width=200,height=200,scroll=yes\")'>View All</a>";
echo "</center></td></tr><tr><td><input type='submit' name='submit' value='Submit Post'></td><td><input type='reset' name='reset' value='Reset'></td></tr></table></form>";
}

if ($_POST['submit']) {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST["text1"]));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST["text1"]));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST["text1"]));

$post = mysql_query("INSERT INTO onecms_posts VALUES ('null', '".addslashes($_POST["subject"])."', '".$autobr."', '".$useridn."', '".time()."', '".$_POST["fid"]."', '".$_POST["tid"]."', '0', 'post', 'no')") or die(mysql_error());
					
$query = mysql_fetch_row(mysql_query("SELECT id FROM onecms_posts WHERE subject = '".$_POST["subject"]."' AND type = 'post' AND message = '".$_POST["text1"]."'"));
$postid = $query[0];

if ($post == TRUE) {
echo "<center>Post has been created.</center><br><center>Click <a href='".$f1part1."".$_GET['fid']."".$f1part2."'><b>here</b></a> to return to the forum<br>Click <a href='".$f2part1."".$_GET['tid']."".$f2part2."#".$postid."'><b>here</b></a> to view your post</center>";
}
}
}
}

} // end 1

if (($forumskin) && (!$forumskin == "1")) {
$query="SELECT * FROM onecms_skins WHERE id = '".$forumskin."'";
} else {
$query="SELECT * FROM onecms_skins WHERE id = '3'";
}
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$skin1[] = "/{version}/";
	$skin1[] = "/{users}/"; // amount of users online
	$skin1[] = "/{online}/"; // the users that are online
	$skin1[] = "/{pms}/"; // amount of private messages
	$skin1[] = "/{new}/"; // amount of new private messages
	$skin1[] = "/{pm}/"; // display new private messages
	$skin1[] = "/{welcome}/"; // Displays "Welcome username" if person is logged in, other wise "Welcome Guest" and a link to login
	$skin1[] = "/{chooseskin}/";
	$skin1[] = "/{skinname}/"; // the name of the current skin being used
	$skin2[] = "".$version."";
	$skin2[] = online();
	$skin2[] = usersonline();
	$skin2[] = totalpms();
	$skin2[] = newpms();
	$skin2[] = pms();
	$skin2[] = welcome();
	$skin2[] = "<form name='popupform'><input type='button' onClick=\"window.open('a_skins.php?view=skin2','','width=250,height=150,top=100,left=100');\" value='Choose Skin' name='choice'></form>";
	$skin2[] = skinname();
eval (" ?>" . preg_replace($skin1, $skin2, stripslashes($row[footer])) . " <?php ");
}
?>