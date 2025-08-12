<?php
if ($ip == "True") {
$resultID = mysql_query("INSERT INTO onecms_log VALUES ('null', '".$HTTP_SERVER_VARS['REQUEST_URI']."', '".$_SERVER['REMOTE_ADDR']."', '".date("F j, y - I:s A")."', '".$username."')") or die(mysql_error());
}

if ($skins) {
$result = mysql_query("SELECT * FROM onecms_skins WHERE id = '".$skins."'");
} else {
$result = mysql_query("SELECT * FROM onecms_skins WHERE id = '1'");
}
while($row = mysql_fetch_array($result)) {

    $skin1[] = "/{version}/";
	$skin1[] = "/{menu1}/";
	$skin1[] = "/{menu2}/";
	$skin1[] = "/{menu3}/";
	$skin1[] = "/{menu4}/";
	$skin1[] = "/{menu5}/";
	$skin1[] = "/{menu6}/";
	$skin1[] = "/{menu7}/";
	$skin1[] = "/{users}/"; // amount of users online
	$skin1[] = "/{online}/"; // the users that are online
	$skin1[] = "/{pms}/"; // amount of private messages
	$skin1[] = "/{new}/"; // amount of new private messages
	$skin1[] = "/{pm}/"; // display new private messages
	$skin1[] = "/{welcome}/"; // Displays "Welcome username" if person is logged in, other wise "Welcome Guest" and a link to login
	$skin1[] = "/{chooseskin}/";
	$skin1[] = "/{skinname}/"; // the name of the current skin being used
	$skin2[] = "".$version."";

	$resultbab = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'admin'");
    $ojbab = mysql_num_rows($resultbab);

	$resultbab2 = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'global'");
    $ojbab2 = mysql_num_rows($resultbab2);

	$resultbab3 = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'mod'");
    $ojbab3 = mysql_num_rows($resultbab3);

	if ((($ojbab > "0") or ($ojbab2 > "0") or ($ojbab3 > "0"))) {
	$skin2[] = "- <a href='a_index.php?view=home'>Admin CP</a><br>- <a href='index.php'>Frontend</a><br>- <a href='boards.php'>Forums</a><br>- <a href='".$siteurl."/boardcp.php'>Board CP</a><br>- <a href='a_help.php'>Help Files</a><br>- <a href='a_updates.php'>Check for Updates</a><br>- <a href='a_updates.php?view=new'><b>What's New?</b></a><br>";
	} else {
	$skin2[] = "- <a href='a_index.php?view=home'>Admin CP Home</a><br>- <a href='index.php'>Frontend</a><br>- <a href='boards.php'>Forums</a><br>- <a href='a_help.php'>Help Files</a><br>- <a href='a_updates.php'>Check for Updates</a><br>- <a href='a_updates.php?view=new'><b>What's New?</b></a><br>";
	}

    $query7 = mysql_query("SELECT * FROM onecms_skins WHERE type = 'cp'");
	while($rowb = mysql_fetch_array($query7)) {
	$vart .= "<option value='".$rowb[id]."'>".$rowb[name]."</option>";
	}

$querym = mysql_query("SELECT * FROM onecms_mods WHERE installed = 'Yes' AND status = 'On' ORDER BY `name` ASC");
while($rowm = mysql_fetch_array($querym)) {
if ($row[url]) {
$datam .= "- <a href='".$rowm[url]."'>".$rowm[name]."</a><br>";
} else {
$datam .= "- ".$rowm[name]."<br>";
}
}

    $skin2[] = "- <a href='a_modules.php'><b>Modules Manager</b></a><br>".$datam."";

    $squery = mysql_query("SELECT * FROM onecms_settings ORDER BY `sname` ASC");
	while($r = mysql_fetch_array($squery)) {
    $settings .= "- <a href='a_settings.php?type=".$r[sname]."'>".ucfirst($r[sname])."</a><br>";
	}

	$skin2[] = $settings;

    $skin2[] = '- <a href="a_users.php">User Manager</a><br>
- <a href="a_users.php?view=levels">User Levels</a><br>
- <a href="a_logout.php">Logout</a><br>
- <a href="a_users.php?view=log">IP Logger</a><br>
- <a href="a_ipban.php">IP Banner</a><br>
- <a href="a_users.php?view=warn">Warn Users</a><br>
- <a href="a_users.php?view=ban">Ban Users</a><br>
- <a href="a_users.php?view=permissions">Set Permissions</a><br>
- <a href="a_users.php?view=manage&change=1">Change Passwords</a><br>';

$sqla = mysql_query("SELECT * FROM onecms_cat ORDER BY `name` ASC");
while($rowa = mysql_fetch_array($sqla)) {
$cname = $rowa[name];

$content1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='a_index.php?cat=$cname'>+ $cname</a><br>";
}

     $skin2[] = "- <a href='a_index.php'>Manage Content</a><br>".$content1."- <a href='a_index.php?view=ver'>Verify Content</a><br>- <a href='a_index.php?view=postpone'>Delay Content</a><br><br>";
     $skin2[] = '- <a href="a_games.php">Game Manager</a><br>
- <a href="a_systems.php">Systems</a><br>
- <a href="a_cat.php">Categories</a><br>
- <a href="a_fields.php">Custom Fields</a><br><br>';
     $skin2[] = '- <a href="a_stats.php">Stats Manager</a><br>
- <a href="a_templates.php">Templates</a><br>- <a href="a_templates.php?view=skins">Skin Manager</a><br>- <a href="a_elite.php">Elite</a><br><br>';

     $skin2[] = online();
	 $skin2[] = usersonline();
	 $skin2[] = totalpms();
	 $skin2[] = newpms();
	 $skin2[] = pms();
	 $skin2[] = welcome();
	 $skin2[] = "<form action='".$REQUEST_URI."' method='post' name='skin'><select name='skin' onchange='this.form.submit()'><option value='1' selected>- Select Skin -</option>".$vart."</select></form>";
	 $skin2[] = skinname();

	 eval (" ?>" . preg_replace($skin1, $skin2, stripslashes($row[header])) . " <?php ");

}
?>